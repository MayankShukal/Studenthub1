<?php
/**
 * StudentHub - Unified API Gateway
 * Handles endpoints like:
 *   /php/api.php?action=login
 *   /php/api.php?action=register
 *   /php/api.php?action=events
 *   /php/api.php?action=notices
 *   /php/api.php?action=contact
 *   /php/api.php?action=feedback
 *   /php/api.php?action=dashboard
 *   /php/api.php?action=profile
 *   /php/api.php?action=admin
 *   /php/api.php?action=timetable
 *   /php/api.php?action=attendance
 *   /php/api.php?action=assignments
 */
require_once __DIR__ . '/config.php';

$action = isset($_GET['action']) ? strtolower(trim($_GET['action'])) : '';

if (empty($action)) {
    // If no specific action, return site overview
    $siteData = read_json_data('studenthub.json', []);
    send_json_response(true, 'StudentHub API is active', [
        'version' => '1.0.0',
        'site' => $siteData
    ]);
}

switch ($action) {
    case 'login':
        require __DIR__ . '/login.php';
        break;

    case 'register':
        require __DIR__ . '/register.php';
        break;

    case 'logout':
        require __DIR__ . '/logout.php';
        break;

    case 'events':
        require __DIR__ . '/events.php';
        break;

    case 'notices':
        require __DIR__ . '/notices.php';
        break;

    case 'contact':
        require __DIR__ . '/contact.php';
        break;

    case 'feedback':
        require __DIR__ . '/feedback.php';
        break;

    case 'profile':
        require __DIR__ . '/profile.php';
        break;

    case 'admin':
        require __DIR__ . '/admin.php';
        break;

    case 'dashboard':
        require __DIR__ . '/dashboard.php';
        break;

    case 'timetable':
        $data = read_json_data('timetable.json', []);
        send_json_response(true, 'Timetable data', $data);
        break;

    case 'attendance':
        $data = read_json_data('attendance.json', []);
        $roll = isset($_GET['roll_no']) ? trim($_GET['roll_no']) : (isset($_SESSION['user']['roll_no']) ? $_SESSION['user']['roll_no'] : '');
        if (!empty($roll) && isset($data[$roll])) {
            send_json_response(true, 'Attendance data for ' . $roll, $data[$roll]);
        }
        send_json_response(true, 'All Attendance records', $data);
        break;

    case 'assignments':
        $data = read_json_data('assignments.json', []);
        send_json_response(true, 'Assignments data', $data);
        break;

    case 'site':
        $data = read_json_data('studenthub.json', []);
        send_json_response(true, 'Site configuration data', $data);
        break;

    default:
        send_json_response(false, 'Unknown API action: ' . htmlspecialchars($action), null, 404);
        break;
}
