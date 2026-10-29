<?php
/**
 * StudentHub - Notices API Endpoint
 */
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$notices = read_json_data('notices.json', []);

// GET: Retrieve notices
if ($method === 'GET') {
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    
    $filtered = array_values(array_filter($notices, function($item) use ($category) {
        return empty($category) || (isset($item['category']) && strcasecmp($item['category'], $category) === 0);
    }));

    send_json_response(true, 'Notices retrieved successfully', [
        'total'   => count($filtered),
        'notices' => $filtered
    ]);
}

// POST: Add new notice or delete notice
if ($method === 'POST') {
    $input = get_request_data();
    $action = isset($input['action']) ? $input['action'] : 'create';

    if ($action === 'create') {
        $title = isset($input['title']) ? clean_input($input['title']) : '';
        $desc  = isset($input['description']) ? clean_input($input['description']) : '';
        $cat   = isset($input['category']) ? clean_input($input['category']) : 'General';
        $badge = isset($input['badge']) ? clean_input($input['badge']) : 'Notice';
        $date  = isset($input['date']) ? clean_input($input['date']) : date('Y-m-d');

        if (empty($title) || empty($desc)) {
            send_json_response(false, 'Notice Title and Description are required.');
        }

        $maxId = 0;
        foreach ($notices as $n) {
            if (isset($n['id']) && $n['id'] > $maxId) {
                $maxId = $n['id'];
            }
        }

        $newNotice = [
            'id'          => $maxId + 1,
            'title'       => $title,
            'category'    => $cat,
            'date'        => $date,
            'badge'       => $badge,
            'description' => $desc
        ];

        array_unshift($notices, $newNotice);
        write_json_data('notices.json', $notices);
        send_json_response(true, 'Notice published successfully!', ['notice' => $newNotice]);
    }

    if ($action === 'delete') {
        $noticeId = isset($input['id']) ? (int)$input['id'] : 0;
        $initialCount = count($notices);
        $notices = array_values(array_filter($notices, function($n) use ($noticeId) {
            return $n['id'] !== $noticeId;
        }));

        if (count($notices) < $initialCount) {
            write_json_data('notices.json', $notices);
            send_json_response(true, 'Notice deleted successfully.');
        } else {
            send_json_response(false, 'Notice not found.');
        }
    }

    send_json_response(false, 'Invalid action.');
}
