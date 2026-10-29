<?php
/**
 * StudentHub - Authentication Endpoint (Login)
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(false, 'Only POST requests are allowed', null, 405);
}

$input = get_request_data();

$identifier = isset($input['username']) ? trim($input['username']) : (isset($input['email']) ? trim($input['email']) : '');
$password   = isset($input['password']) ? (string)$input['password'] : '';

if (empty($identifier) || empty($password)) {
    send_json_response(false, 'Please provide both username/email/roll no and password.');
}

$users = read_json_data('users.json', []);
$matchedUser = null;
$userExists = false;

foreach ($users as $user) {
    // Match against username, email, or roll_no
    $matchUsername = (isset($user['username']) && strcasecmp($user['username'], $identifier) === 0);
    $matchEmail    = (isset($user['email']) && strcasecmp($user['email'], $identifier) === 0);
    $matchRollNo   = (isset($user['roll_no']) && strcasecmp($user['roll_no'], $identifier) === 0);

    if ($matchUsername || $matchEmail || $matchRollNo) {
        $userExists = true;
        $passwordValid = false;

        // Verify with password_verify or fallback to plain_password_demo
        if (!empty($user['password']) && password_verify($password, $user['password'])) {
            $passwordValid = true;
        } elseif (isset($user['plain_password_demo']) && $password === $user['plain_password_demo']) {
            $passwordValid = true;
        } elseif (isset($user['password']) && $password === $user['password']) {
            $passwordValid = true;
        }

        if ($passwordValid) {
            $matchedUser = $user;
        }
        break;
    }
}

if (!$userExists) {
    // Alert the user that the ID is not registered
    send_json_response(false, '⚠️ This ID / Roll Number is not registered yet! Please register your student account first.', [
        'not_registered' => true,
        'redirect' => 'register.html'
    ], 404);
}

if ($matchedUser) {
    // Set user session
    $_SESSION['user'] = [
        'id'         => $matchedUser['id'],
        'name'       => $matchedUser['name'],
        'username'   => isset($matchedUser['username']) ? $matchedUser['username'] : '',
        'email'      => $matchedUser['email'],
        'roll_no'    => isset($matchedUser['roll_no']) ? $matchedUser['roll_no'] : '',
        'role'       => isset($matchedUser['role']) ? $matchedUser['role'] : 'student',
        'department' => isset($matchedUser['department']) ? $matchedUser['department'] : 'Information Technology',
        'semester'   => isset($matchedUser['semester']) ? $matchedUser['semester'] : '4th Semester'
    ];

    $redirectUrl = ($matchedUser['role'] === 'admin') ? 'admin.html' : 'dashboard.html';

    send_json_response(true, 'Login successful! Redirecting...', [
        'user'     => $_SESSION['user'],
        'role'     => $matchedUser['role'],
        'redirect' => $redirectUrl
    ]);
} else {
    // User exists, but incorrect password
    send_json_response(false, '❌ Incorrect password for this registered account. Please check your credentials and try again.', [
        'not_registered' => false
    ], 401);
}
