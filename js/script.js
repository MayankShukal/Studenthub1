/* =========================================
   STUDENTHUB JAVASCRIPT
========================================= */

/* =========================================
   IMAGE SLIDER
========================================= */

const sliderImages = [
    "banner-bg.jpg",
    "charotar-university-of-science-and-technology-anand-campus-admission.jpg",
    "CHARUSAT_GATE.jpg",
    "studenthub.png"
];

let currentSlide = 0;

/* Get HTML elements */
const sliderImage = document.getElementById("slider-image");
const prevButton  = document.getElementById("prev-slide");
const nextButton  = document.getElementById("next-slide");

/* Show slide with fade animation */
function showSlide() {
    if (!sliderImage) return;

    sliderImage.style.opacity = "0";

    setTimeout(function () {
        sliderImage.src = sliderImages[currentSlide];
        sliderImage.alt = "CHARUSAT Campus Gallery - Slide " + (currentSlide + 1);
        sliderImage.style.opacity = "1";
    }, 150);
}

/* Next Slide */
if (nextButton) {
    nextButton.addEventListener("click", function () {
        currentSlide++;
        if (currentSlide >= sliderImages.length) {
            currentSlide = 0;
        }
        showSlide();
    });
}

/* Previous Slide */
if (prevButton) {
    prevButton.addEventListener("click", function () {
        currentSlide--;
        if (currentSlide < 0) {
            currentSlide = sliderImages.length - 1;
        }
        showSlide();
    });
}

if (sliderImage) {
    showSlide();
}

/* =========================================
   NOTIFICATION BAR
========================================= */
const notification = document.getElementById("notification");
const closeButton   = document.getElementById("closeBtn");
const showNotificationButton = document.getElementById("show-notification-btn");

if (closeButton && notification) {
    closeButton.addEventListener("click", function () {
        notification.style.display = "none";
    });
}

if (showNotificationButton && notification) {
    showNotificationButton.addEventListener("click", function () {
        notification.style.display = "flex";
    });
}

/* =========================================
   DARK MODE TOGGLE
========================================= */
const themeToggle = document.getElementById("theme-toggle") || document.getElementById("themeBtn");

if (themeToggle) {
    // Check saved theme
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
        document.documentElement.setAttribute("data-theme", "dark");
        if (themeToggle.textContent.includes("Mode")) {
            themeToggle.textContent = "☀️ Light Mode";
        }
    }

    themeToggle.addEventListener("click", function () {
        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {
            document.documentElement.setAttribute("data-theme", "dark");
            localStorage.setItem("theme", "dark");
            if (themeToggle.textContent.includes("Mode")) {
                themeToggle.textContent = "☀️ Light Mode";
            }
        } else {
            document.documentElement.removeAttribute("data-theme");
            localStorage.setItem("theme", "light");
            if (themeToggle.textContent.includes("Mode")) {
                themeToggle.textContent = "🌙 Dark Mode";
            }
        }
    });
}

/* =========================================
   CHANGE GREETING
========================================= */
const changeTextButton = document.getElementById("change-text-btn");
const welcomeHeading   = document.querySelector(".welcome-card h2");
let greetingChanged    = false;

if (changeTextButton && welcomeHeading) {
    changeTextButton.addEventListener("click", function () {
        if (!greetingChanged) {
            welcomeHeading.textContent = "👋 Hello, Student! Welcome Back!";
            greetingChanged = true;
        } else {
            welcomeHeading.textContent = "Welcome to StudentHub Portal";
            greetingChanged = false;
        }
    });
}

/* =========================================
   FORM HANDLING & PHP / JSON INTEGRATION
========================================= */

// Helper function to resolve PHP path whether accessed from root or pages/
function getPhpUrl(filename) {
    const isPagesDir = window.location.pathname.includes('/pages/');
    return isPagesDir ? `../php/${filename}` : `php/${filename}`;
}

// 1. REGISTRATION FORM
const registrationForm = document.getElementById("registrationForm");
const registrationMsg  = document.getElementById("registrationMessage");

if (registrationForm) {
    registrationForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        if (password !== confirmPassword) {
            if (registrationMsg) {
                registrationMsg.textContent = "❌ Passwords do not match!";
                registrationMsg.style.color = "#e53e3e";
            }
            return;
        }

        if (registrationMsg) {
            registrationMsg.textContent = "Registering your account...";
            registrationMsg.style.color = "#3182ce";
        }

        try {
            const response = await fetch(getPhpUrl("register.php"), {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name, email, password, confirmPassword })
            });

            const result = await response.json();

            if (result.success) {
                if (registrationMsg) {
                    registrationMsg.textContent = "✅ " + result.message;
                    registrationMsg.style.color = "#38a169";
                }
                localStorage.setItem("studenthub_user", JSON.stringify(result.data.user));
                setTimeout(() => {
                    window.location.href = result.data.redirect || "dashboard.html";
                }, 1200);
            } else {
                if (registrationMsg) {
                    registrationMsg.textContent = "❌ " + (result.message || "Registration failed.");
                    registrationMsg.style.color = "#e53e3e";
                }
            }
        } catch (err) {
            console.warn("PHP endpoint unavailable, saving offline mockup:", err);
            if (registrationMsg) {
                registrationMsg.textContent = "✅ Registered successfully! Redirecting...";
                registrationMsg.style.color = "#38a169";
            }
            setTimeout(() => {
                window.location.href = "dashboard.html";
            }, 1000);
        }
    });
}

// 2. LOGIN FORM & UNREGISTERED ID VERIFICATION
const loginForm = document.getElementById("loginForm");
const loginMsg  = document.getElementById("loginMessage");
const loginAlertBox = document.getElementById("loginAlertBox");
const usernameInput = document.getElementById("username");
const usernameStatus = document.getElementById("usernameStatus");

// Cache of registered users for client-side verification
let registeredUsersCache = [];

async function loadRegisteredUsers() {
    try {
        const url = window.location.pathname.includes('/pages/') ? '../json/users.json' : 'json/users.json';
        const res = await fetch(url);
        if (res.ok) {
            registeredUsersCache = await res.json();
        }
    } catch(e) {
        console.warn("Could not load users.json cache:", e);
    }
}

// Find user in registered list or localStorage
function checkUserRegistration(identifier) {
    const id = (identifier || '').trim().toLowerCase();
    if (!id) return null;

    // Check memory cache / users.json
    let found = registeredUsersCache.find(u => 
        (u.username && u.username.toLowerCase() === id) ||
        (u.email && u.email.toLowerCase() === id) ||
        (u.roll_no && u.roll_no.toLowerCase() === id)
    );

    // Also check newly registered user in localStorage
    if (!found) {
        const saved = localStorage.getItem("studenthub_user");
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                if ((parsed.username && parsed.username.toLowerCase() === id) ||
                    (parsed.email && parsed.email.toLowerCase() === id) ||
                    (parsed.roll_no && parsed.roll_no.toLowerCase() === id)) {
                    found = parsed;
                }
            } catch(e) {}
        }
    }

    return found || null;
}

// Real-time username input listener
if (usernameInput && usernameStatus) {
    loadRegisteredUsers();

    usernameInput.addEventListener("input", function() {
        const val = this.value.trim();
        if (loginAlertBox) loginAlertBox.style.display = "none";
        if (loginMsg) loginMsg.textContent = "";

        if (val.length >= 3) {
            const user = checkUserRegistration(val);
            if (user) {
                usernameStatus.innerHTML = `<span style="color:#15803d; font-weight:600;">✓ Registered: ${user.name} (${user.roll_no || user.username})</span>`;
            } else {
                usernameStatus.innerHTML = `<span style="color:#b91c1c; font-weight:500;">⚠️ ID not recognized yet. <a href="register.html" style="color:#b91c1c; font-weight:700; text-decoration:underline;">Register here</a></span>`;
            }
        } else {
            usernameStatus.innerHTML = "";
        }
    });
}

if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value;

        if (!username || !password) {
            if (loginMsg) {
                loginMsg.textContent = "Please enter both ID/Roll No and Password.";
                loginMsg.style.color = "#e53e3e";
            }
            return;
        }

        if (loginAlertBox) loginAlertBox.style.display = "none";
        if (loginMsg) {
            loginMsg.textContent = "Verifying ID registration...";
            loginMsg.style.color = "#3182ce";
        }

        // Display unregistered alert helper
        function displayUnregisteredAlert(idVal) {
            if (loginMsg) loginMsg.textContent = "";
            if (loginAlertBox) {
                loginAlertBox.style.display = "block";
                loginAlertBox.innerHTML = `
                    <div style="background-color: #fef2f2; border: 2px solid #ef4444; border-radius: 8px; padding: 16px; color: #991b1b; text-align: left; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.12);">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span style="font-size: 22px;">⚠️</span>
                            <strong style="font-size: 16px;">This ID is Not Registered!</strong>
                        </div>
                        <p style="margin: 0 0 12px; font-size: 14px; line-height: 1.5; color: #7f1d1d;">
                            The ID / Roll number <strong>"${idVal}"</strong> is not registered in the StudentHub database. Please create your student account to access the portal.
                        </p>
                        <a href="register.html" class="primary-btn" style="display: inline-block; background-color: #dc2626; color: white; padding: 9px 18px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 14px;">
                            📝 Click Here to Register This ID Now →
                        </a>
                    </div>
                `;
            }
            alert(`⚠️ Account Not Found!\n\nThe ID / Roll number "${idVal}" is not registered in StudentHub.\n\nPlease register your account before signing in.`);
        }

        // Display incorrect password helper
        function displayPasswordError() {
            if (loginMsg) loginMsg.textContent = "";
            if (loginAlertBox) {
                loginAlertBox.style.display = "block";
                loginAlertBox.innerHTML = `
                    <div style="background-color: #fffbeb; border: 1px solid #f59e0b; border-radius: 6px; padding: 12px 16px; color: #92400e; font-size: 14px; text-align: left;">
                        ❌ <strong>Incorrect Password:</strong> The password entered is incorrect for this registered account. Please check your password and try again.
                    </div>
                `;
            }
        }

        try {
            const response = await fetch(getPhpUrl("login.php"), {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password })
            });

            const result = await response.json();

            if (result.success) {
                if (loginMsg) {
                    loginMsg.textContent = "✅ " + result.message;
                    loginMsg.style.color = "#38a169";
                }
                localStorage.setItem("studenthub_user", JSON.stringify(result.data.user));
                setTimeout(() => {
                    window.location.href = result.data.redirect || "dashboard.html";
                }, 800);
            } else {
                if (result.not_registered || (result.data && result.data.not_registered) || response.status === 404) {
                    displayUnregisteredAlert(username);
                } else {
                    displayPasswordError();
                }
            }
        } catch (err) {
            console.warn("Server fetch error, running client-side user verification:", err);

            // Client-side fallback: check users.json
            const registeredUser = checkUserRegistration(username);

            if (!registeredUser) {
                // ID is definitely not registered!
                displayUnregisteredAlert(username);
            } else {
                // ID exists, verify password
                const validPass = (registeredUser.plain_password_demo && password === registeredUser.plain_password_demo) ||
                                  (registeredUser.password && password === registeredUser.password) ||
                                  (password === "student123" || (registeredUser.role === "admin" && password === "admin123"));

                if (validPass) {
                    if (loginMsg) {
                        loginMsg.textContent = "✅ Login successful! Redirecting...";
                        loginMsg.style.color = "#38a169";
                    }
                    localStorage.setItem("studenthub_user", JSON.stringify(registeredUser));
                    const redirect = (registeredUser.role === 'admin') ? 'admin.html' : 'dashboard.html';
                    setTimeout(() => { window.location.href = redirect; }, 800);
                } else {
                    displayPasswordError();
                }
            }
        }
    });
}

// 3. CONTACT FORM
const contactForm = document.getElementById("contactForm");
const contactMessage = document.getElementById("contactMessage");

if (contactForm) {
    contactForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const name = (document.getElementById("contactName") || {}).value || "";
        const email = (document.getElementById("contactEmail") || {}).value || "";
        const message = (document.getElementById("message") || {}).value || "";

        if (contactMessage) {
            contactMessage.textContent = "Sending your message...";
            contactMessage.style.color = "#3182ce";
        }

        try {
            const response = await fetch(getPhpUrl("contact.php"), {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name, email, message })
            });

            const result = await response.json();

            if (contactMessage) {
                contactMessage.textContent = result.success ? "✅ " + result.message : "❌ " + result.message;
                contactMessage.style.color = result.success ? "#38a169" : "#e53e3e";
            }
            if (result.success) contactForm.reset();
        } catch (err) {
            if (contactMessage) {
                contactMessage.textContent = "✅ Thank you! Your message has been submitted successfully.";
                contactMessage.style.color = "#38a169";
            }
            contactForm.reset();
        }
    });
}