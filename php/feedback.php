<?php
/**
 * StudentHub - Feedback Submission Endpoint
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(false, 'Only POST requests are allowed', null, 405);
}

$input = get_request_data();

$name     = isset($input['name']) ? clean_input($input['name']) : '';
$email    = isset($input['email']) ? clean_input($input['email']) : '';
$rating   = isset($input['rating']) ? (int)$input['rating'] : 5;
$category = isset($input['category']) ? clean_input($input['category']) : 'General';
$comment  = isset($input['comment']) ? clean_input($input['comment']) : (isset($input['message']) ? clean_input($input['message']) : '');

if (empty($name) || empty($comment)) {
    send_json_response(false, 'Name and Comments/Feedback are required.');
}

if ($rating < 1 || $rating > 5) {
    $rating = 5;
}

$feedbackList = read_json_data('feedback.json', []);

$maxId = 0;
foreach ($feedbackList as $fb) {
    if (isset($fb['id']) && $fb['id'] > $maxId) {
        $maxId = $fb['id'];
    }
}

$newFeedback = [
    'id'           => $maxId + 1,
    'name'         => $name,
    'email'        => $email,
    'rating'       => $rating,
    'category'     => $category,
    'comment'      => $comment,
    'submitted_at' => date('Y-m-d H:i:s')
];

$feedbackList[] = $newFeedback;

if (write_json_data('feedback.json', $feedbackList)) {
    send_json_response(true, 'Thank you for your valuable feedback!', [
        'feedback_id' => $newFeedback['id']
    ]);
} else {
    send_json_response(false, 'Failed to save feedback.', null, 500);
}
