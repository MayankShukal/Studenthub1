<?php
/**
 * StudentHub - Contact Us Form Submission Endpoint
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(false, 'Only POST requests are allowed', null, 405);
}

$input = get_request_data();

$name    = isset($input['name']) ? clean_input($input['name']) : '';
$email   = isset($input['email']) ? clean_input($input['email']) : '';
$message = isset($input['message']) ? clean_input($input['message']) : '';
$phone   = isset($input['phone']) ? clean_input($input['phone']) : '';

if (empty($name) || empty($email) || empty($message)) {
    send_json_response(false, 'Please provide Name, Email, and Message.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_json_response(false, 'Please enter a valid email address.');
}

$contacts = read_json_data('contacts.json', []);

$maxId = 0;
foreach ($contacts as $c) {
    if (isset($c['id']) && $c['id'] > $maxId) {
        $maxId = $c['id'];
    }
}

$newContact = [
    'id'           => $maxId + 1,
    'name'         => $name,
    'email'        => $email,
    'phone'        => $phone,
    'message'      => $message,
    'submitted_at' => date('Y-m-d H:i:s'),
    'status'       => 'Pending'
];

$contacts[] = $newContact;

if (write_json_data('contacts.json', $contacts)) {
    send_json_response(true, 'Thank you! Your message has been received. Our team will contact you shortly.', [
        'contact_id' => $newContact['id']
    ]);
} else {
    send_json_response(false, 'Failed to submit message. Please try again later.', null, 500);
}
