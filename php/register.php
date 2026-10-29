<?php
/**
 * StudentHub - Student Registration Endpoint
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(false, 'Only POST requests are allowed', null, 405);
}

$input = get_request_data();

$name     = isset($input['name']) ? clean_input($input['name']) : '';
$email    = isset($input['email']) ? clean_input($input['email']) : '';
$password = isset($input['password']) ? (string)$input['password'] : '';
$confirm  = isset($input['confirmPassword']) ? (string)$input['confirmPassword'] : (isset($input['confirm_password']) ? (string)$input['confirm_password'] : '');
$roll_no  = isset($input['roll_no']) ? clean_input($input['roll_no']) : '';
$department = isset($input['department']) ? clean_input($input['department']) : 'Computer Engineering';
$semester = isset($input['semester']) ? clean_input($input['semester']) : '4th Semester';

// Basic Validations
if (empty($name) || empty($email) || empty($password)) {
    send_json_response(false, 'Please fill in all required fields (Name, Email, Password).');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_json_response(false, 'Please enter a valid email address.');
}

if (strlen($password) < 6) {
    send_json_response(false, 'Password must be at least 6 characters long.');
}

if (!empty($confirm) && $password !== $confirm) {
    send_json_response(false, 'Passwords do not match.');
}

// Load existing users
$users = read_json_data('users.json', []);

// Check for duplicate email or roll number
foreach ($users as $u) {
    if (strcasecmp($u['email'], $email) === 0) {
        send_json_response(false, 'An account with this email address already exists.');
    }
    if (!empty($roll_no) && !empty($u['roll_no']) && strcasecmp($u['roll_no'], $roll_no) === 0) {
        send_json_response(false, 'An account with this Roll / Enrollment number already exists.');
    }
}

// Generate new ID and Roll No if not provided
$maxId = 0;
foreach ($users as $u) {
    if (isset($u['id']) && $u['id'] > $maxId) {
        $maxId = $u['id'];
    }
}
$newId = $maxId + 1;

if (empty($roll_no)) {
    $roll_no = '23ST' . str_pad((string)$newId, 3, '0', STR_PAD_LEFT);
}

// Generate unique username from email
$usernamePart = explode('@', $email)[0];
$username = preg_replace('/[^a-zA-Z0-9_]/', '', $usernamePart);

$newUser = [
    'id'         => $newId,
    'name'       => $name,
    'username'   => $username,
    'email'      => $email,
    'roll_no'    => $roll_no,
    'role'       => 'student',
    'department' => $department,
    'semester'   => $semester,
    'password'   => password_hash($password, PASSWORD_DEFAULT),
    'plain_password_demo' => $password,
    'created_at' => date('Y-m-d H:i:s')
];

$users[] = $newUser;

if (write_json_data('users.json', $users)) {
    // Automatically set session for user
    $_SESSION['user'] = [
        'id'         => $newUser['id'],
        'name'       => $newUser['name'],
        'username'   => $newUser['username'],
        'email'      => $newUser['email'],
        'roll_no'    => $newUser['roll_no'],
        'role'       => $newUser['role'],
        'department' => $newUser['department'],
        'semester'   => $newUser['semester']
    ];

    send_json_response(true, 'Registration successful! Welcome to StudentHub.', [
        'user' => $_SESSION['user'],
        'redirect' => 'dashboard.html'
    ]);
} else {
    send_json_response(false, 'Failed to save student record to database.', null, 500);
}
