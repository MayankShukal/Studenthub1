const http = require('http');
const fs = require('fs');
const path = require('path');

const PORT = 3000;
const ROOT = __dirname;

const MIME_TYPES = {
    '.html': 'text/html; charset=utf-8',
    '.css': 'text/css; charset=utf-8',
    '.js': 'text/javascript; charset=utf-8',
    '.json': 'application/json; charset=utf-8',
    '.png': 'image/png',
    '.jpg': 'image/jpeg',
    '.jpeg': 'image/jpeg',
    '.svg': 'image/svg+xml',
    '.ico': 'image/x-icon'
};

const server = http.createServer((req, res) => {
    // CORS headers
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    if (req.method === 'OPTIONS') {
        res.writeHead(200);
        res.end();
        return;
    }

    const parsedUrl = new URL(req.url, `http://localhost:${PORT}`);
    let pathname = decodeURIComponent(parsedUrl.pathname);

    // Redirect root to index.html
    if (pathname === '/' || pathname === '/index.html') {
        res.writeHead(302, { 'Location': '/pages/index.html' });
        res.end();
        return;
    }

    // Handle mock PHP endpoints if requested
    if (pathname.startsWith('/php/')) {
        let body = '';
        req.on('data', chunk => { body += chunk; });
        req.on('end', () => {
            let jsonInput = {};
            try { jsonInput = JSON.parse(body); } catch(e) {}

            if (pathname.includes('login.php')) {
                const identifier = (jsonInput.username || '').trim();
                const password = jsonInput.password || '';

                let users = [];
                try {
                    users = JSON.parse(fs.readFileSync(path.join(ROOT, 'json', 'users.json'), 'utf8'));
                } catch(e) {}

                // Find user by username, email, or roll_no
                const matchedUser = users.find(u => 
                    (u.username && u.username.toLowerCase() === identifier.toLowerCase()) ||
                    (u.email && u.email.toLowerCase() === identifier.toLowerCase()) ||
                    (u.roll_no && u.roll_no.toLowerCase() === identifier.toLowerCase())
                );

                if (!matchedUser) {
                    res.writeHead(404, { 'Content-Type': 'application/json; charset=utf-8' });
                    res.end(JSON.stringify({
                        success: false,
                        not_registered: true,
                        message: `⚠️ This ID (${identifier}) is not registered yet! Please register your student account first.`,
                        data: { redirect: 'register.html', not_registered: true }
                    }));
                    return;
                }

                // Check password
                const passwordValid = (matchedUser.plain_password_demo && password === matchedUser.plain_password_demo) ||
                                      (matchedUser.password && password === matchedUser.password) ||
                                      (password === 'student123' || (matchedUser.role === 'admin' && password === 'admin123'));

                if (!passwordValid) {
                    res.writeHead(401, { 'Content-Type': 'application/json; charset=utf-8' });
                    res.end(JSON.stringify({
                        success: false,
                        not_registered: false,
                        message: "❌ Incorrect password for this registered account. Please check your password and try again.",
                        data: { not_registered: false }
                    }));
                    return;
                }

                const role = matchedUser.role || 'student';
                res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
                res.end(JSON.stringify({
                    success: true,
                    message: "Login successful! Redirecting...",
                    data: {
                        user: matchedUser,
                        role: role,
                        redirect: (role === 'admin') ? 'admin.html' : 'dashboard.html'
                    }
                }));
                return;
            }

            if (pathname.includes('register.php')) {
                let users = [];
                const usersPath = path.join(ROOT, 'json', 'users.json');
                try { users = JSON.parse(fs.readFileSync(usersPath, 'utf8')); } catch(e) {}

                const email = (jsonInput.email || '').trim();
                const rollNo = (jsonInput.roll_no || '').trim() || ('23ST' + String(users.length + 1).padStart(3, '0'));
                const username = email.split('@')[0].replace(/[^a-zA-Z0-9_]/g, '');

                const newUser = {
                    id: users.length + 1,
                    name: jsonInput.name || "New Student",
                    username: username,
                    email: email,
                    roll_no: rollNo,
                    role: "student",
                    department: jsonInput.department || "Computer Engineering",
                    semester: jsonInput.semester || "4th Semester",
                    password: jsonInput.password || "student123",
                    plain_password_demo: jsonInput.password || "student123",
                    created_at: new Date().toISOString().replace('T', ' ').slice(0, 19)
                };

                // Check duplicate email
                const existing = users.find(u => u.email && u.email.toLowerCase() === email.toLowerCase());
                if (existing) {
                    res.writeHead(400, { 'Content-Type': 'application/json; charset=utf-8' });
                    res.end(JSON.stringify({
                        success: false,
                        message: "An account with this email address is already registered. Please sign in instead."
                    }));
                    return;
                }

                users.push(newUser);
                try { fs.writeFileSync(usersPath, JSON.stringify(users, null, 4)); } catch(e) {}

                res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
                res.end(JSON.stringify({
                    success: true,
                    message: "Registration successful! Welcome to StudentHub.",
                    data: {
                        user: newUser,
                        redirect: "dashboard.html"
                    }
                }));
                return;
            }

            if (pathname.includes('feedback.php')) {
                res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
                res.end(JSON.stringify({
                    success: true,
                    message: "Thank you for your valuable feedback!",
                    data: { feedback_id: 101 }
                }));
                return;
            }

            if (pathname.includes('contact.php')) {
                res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
                res.end(JSON.stringify({
                    success: true,
                    message: "Thank you! Your inquiry has been submitted to the helpdesk."
                }));
                return;
            }

            if (pathname.includes('dashboard.php')) {
                const roll = parsedUrl.searchParams.get('roll_no') || '23CE045';
                let attData = {};
                try {
                    const rawAtt = fs.readFileSync(path.join(ROOT, 'json', 'attendance.json'), 'utf8');
                    attData = JSON.parse(rawAtt);
                } catch(e) {}

                const studentAtt = attData[roll] || attData['23CE045'] || { overall_percentage: 91.5 };
                res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
                res.end(JSON.stringify({
                    success: true,
                    message: "Dashboard data loaded",
                    data: {
                        student: { name: studentAtt.student_name || "Mayank Shukal", roll_no: roll },
                        stats: {
                            attendance_percentage: studentAtt.overall_percentage,
                            registered_events_count: 2,
                            pending_assignments: 2,
                            latest_notices_count: 5
                        },
                        attendance: studentAtt
                    }
                }));
                return;
            }

            // Generic fallback
            res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
            res.end(JSON.stringify({ success: true, message: "OK" }));
        });
        return;
    }

    // Static file serving
    const filePath = path.join(ROOT, pathname.replace(/^\//, ''));

    // Prevent directory traversal
    if (!filePath.startsWith(ROOT)) {
        res.writeHead(403, { 'Content-Type': 'text/plain' });
        res.end('Access Denied');
        return;
    }

    fs.stat(filePath, (err, stats) => {
        if (err || !stats.isFile()) {
            res.writeHead(404, { 'Content-Type': 'text/plain' });
            res.end('File Not Found: ' + pathname);
            return;
        }

        const ext = path.extname(filePath).toLowerCase();
        const contentType = MIME_TYPES[ext] || 'application/octet-stream';

        res.writeHead(200, { 'Content-Type': contentType });
        const stream = fs.createReadStream(filePath);
        stream.pipe(res);
    });
});

server.listen(PORT, '127.0.0.1', () => {
    console.log(`StudentHub Local Test Server running at http://127.0.0.1:${PORT}/pages/index.html`);
});
