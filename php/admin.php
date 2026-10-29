<?php
/**
 * StudentHub - Admin Management & System Analytics API
 */
require_once __DIR__ . '/config.php';

$users = read_json_data('users.json', []);
$events = read_json_data('events.json', []);
$notices = read_json_data('notices.json', []);
$contacts = read_json_data('contacts.json', []);
$feedback = read_json_data('feedback.json', []);

$method = $_SERVER['REQUEST_METHOD'];

// GET: Summary statistics and lists
if ($method === 'GET') {
    $students = array_values(array_filter($users, function($u) {
        return isset($u['role']) && $u['role'] === 'student';
    }));

    // Clean passwords before returning
    foreach ($students as &$s) {
        unset($s['password']);
    }

    $stats = [
        'total_students' => count($students),
        'total_events'   => count($events),
        'total_notices'  => count($notices),
        'total_contacts' => count($contacts),
        'total_feedback' => count($feedback)
    ];

    send_json_response(true, 'Admin data loaded', [
        'stats'    => $stats,
        'students' => $students,
        'events'   => $events,
        'contacts' => $contacts
    ]);
}

// POST: Manage Students (Delete, Update, Add)
if ($method === 'POST') {
    $input = get_request_data();
    $action = isset($input['action']) ? $input['action'] : '';

    if ($action === 'delete_student') {
        $studentId = isset($input['student_id']) ? (int)$input['student_id'] : 0;
        $initialCount = count($users);

        $users = array_values(array_filter($users, function($u) use ($studentId) {
            return $u['id'] !== $studentId;
        }));

        if (count($users) < $initialCount) {
            write_json_data('users.json', $users);
            send_json_response(true, 'Student record deleted successfully.');
        } else {
            send_json_response(false, 'Student not found.');
        }
    }

    send_json_response(false, 'Invalid admin action.');
}
