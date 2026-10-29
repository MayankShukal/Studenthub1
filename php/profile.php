<?php
/**
 * StudentHub - Student Profile API Endpoint
 */
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$users = read_json_data('users.json', []);

// GET Profile
if ($method === 'GET') {
    $rollNo = isset($_GET['roll_no']) ? trim($_GET['roll_no']) : (isset($_SESSION['user']['roll_no']) ? $_SESSION['user']['roll_no'] : '23IT001');

    $student = null;
    foreach ($users as $u) {
        if (isset($u['roll_no']) && strcasecmp($u['roll_no'], $rollNo) === 0) {
            $student = $u;
            break;
        }
    }

    if (!$student) {
        // Fallback to first student
        foreach ($users as $u) {
            if (isset($u['role']) && $u['role'] === 'student') {
                $student = $u;
                break;
            }
        }
    }

    if ($student) {
        unset($student['password']); // Never expose hashed password
        send_json_response(true, 'Profile details retrieved', ['profile' => $student]);
    } else {
        send_json_response(false, 'Student profile not found.', null, 404);
    }
}

// POST Profile Updates
if ($method === 'POST') {
    $input = get_request_data();
    $rollNo = isset($input['roll_no']) ? trim($input['roll_no']) : (isset($_SESSION['user']['roll_no']) ? $_SESSION['user']['roll_no'] : '');

    if (empty($rollNo)) {
        send_json_response(false, 'Roll number is required.');
    }

    $found = false;
    foreach ($users as &$u) {
        if (isset($u['roll_no']) && strcasecmp($u['roll_no'], $rollNo) === 0) {
            $found = true;
            if (!empty($input['name'])) $u['name'] = clean_input($input['name']);
            if (!empty($input['email'])) $u['email'] = clean_input($input['email']);
            if (!empty($input['department'])) $u['department'] = clean_input($input['department']);
            if (!empty($input['semester'])) $u['semester'] = clean_input($input['semester']);
            if (!empty($input['phone'])) $u['phone'] = clean_input($input['phone']);
            if (!empty($input['bio'])) $u['bio'] = clean_input($input['bio']);
            
            // Update session if editing self
            if (isset($_SESSION['user']) && strcasecmp($_SESSION['user']['roll_no'], $rollNo) === 0) {
                $_SESSION['user']['name'] = $u['name'];
                $_SESSION['user']['email'] = $u['email'];
                $_SESSION['user']['department'] = $u['department'];
                $_SESSION['user']['semester'] = $u['semester'];
            }
            break;
        }
    }

    if (!$found) {
        send_json_response(false, 'User not found.');
    }

    write_json_data('users.json', $users);
    send_json_response(true, 'Profile updated successfully!');
}
