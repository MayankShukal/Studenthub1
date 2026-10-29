<?php
/**
 * StudentHub - Events API Endpoint
 */
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$events = read_json_data('events.json', []);

// GET: Retrieve list of events
if ($method === 'GET') {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    
    $filtered = array_values(array_filter($events, function($item) use ($search, $category) {
        $matchesSearch = empty($search) || 
            stripos($item['title'], $search) !== false || 
            stripos($item['description'], $search) !== false ||
            stripos($item['venue'], $search) !== false;
            
        $matchesCat = empty($category) || (isset($item['category']) && strcasecmp($item['category'], $category) === 0);
        return $matchesSearch && $matchesCat;
    }));

    send_json_response(true, 'Events fetched successfully', [
        'total'  => count($filtered),
        'events' => $filtered
    ]);
}

// POST: Add, Register, Unregister or Delete
if ($method === 'POST') {
    $input = get_request_data();
    $action = isset($input['action']) ? $input['action'] : 'register';

    // 1. Register for an event
    if ($action === 'register') {
        $eventId = isset($input['event_id']) ? (int)$input['event_id'] : 0;
        $rollNo  = isset($input['roll_no']) ? clean_input($input['roll_no']) : (isset($_SESSION['user']['roll_no']) ? $_SESSION['user']['roll_no'] : '');

        if (!$eventId) {
            send_json_response(false, 'Event ID is required.');
        }
        if (empty($rollNo)) {
            send_json_response(false, 'Roll Number is required to register for an event.');
        }

        $found = false;
        foreach ($events as &$ev) {
            if ($ev['id'] === $eventId) {
                $found = true;
                if (!isset($ev['registered_users'])) {
                    $ev['registered_users'] = [];
                }
                if (in_array($rollNo, $ev['registered_users'])) {
                    send_json_response(false, 'You have already registered for this event!');
                }
                if (isset($ev['seats']) && count($ev['registered_users']) >= $ev['seats']) {
                    send_json_response(false, 'Sorry, seats for this event are full.');
                }

                $ev['registered_users'][] = $rollNo;
                $ev['registered_count'] = count($ev['registered_users']);
                break;
            }
        }

        if (!$found) {
            send_json_response(false, 'Event not found.');
        }

        write_json_data('events.json', $events);
        send_json_response(true, 'Successfully registered for the event!', ['event_id' => $eventId]);
    }

    // 2. Create new event (Admin)
    if ($action === 'create') {
        $title = isset($input['title']) ? clean_input($input['title']) : '';
        $date  = isset($input['date']) ? clean_input($input['date']) : '';
        $time  = isset($input['time']) ? clean_input($input['time']) : '';
        $venue = isset($input['venue']) ? clean_input($input['venue']) : '';
        $desc  = isset($input['description']) ? clean_input($input['description']) : '';
        $cat   = isset($input['category']) ? clean_input($input['category']) : 'General';
        $icon  = isset($input['icon']) ? clean_input($input['icon']) : '📌';
        $seats = isset($input['seats']) ? (int)$input['seats'] : 100;

        if (empty($title) || empty($date) || empty($venue)) {
            send_json_response(false, 'Title, Date, and Venue are required fields.');
        }

        $maxId = 0;
        foreach ($events as $ev) {
            if ($ev['id'] > $maxId) $maxId = $ev['id'];
        }

        $newEvent = [
            'id' => $maxId + 1,
            'title' => $title,
            'icon' => $icon,
            'category' => $cat,
            'date' => $date,
            'time' => $time,
            'venue' => $venue,
            'speaker' => isset($input['speaker']) ? clean_input($input['speaker']) : '',
            'seats' => $seats,
            'registered_count' => 0,
            'description' => $desc,
            'registered_users' => []
        ];

        $events[] = $newEvent;
        write_json_data('events.json', $events);
        send_json_response(true, 'New event created successfully!', ['event' => $newEvent]);
    }

    // 3. Delete event (Admin)
    if ($action === 'delete') {
        $eventId = isset($input['event_id']) ? (int)$input['event_id'] : 0;
        $initialCount = count($events);
        $events = array_values(array_filter($events, function($ev) use ($eventId) {
            return $ev['id'] !== $eventId;
        }));

        if (count($events) < $initialCount) {
            write_json_data('events.json', $events);
            send_json_response(true, 'Event deleted successfully.');
        } else {
            send_json_response(false, 'Event not found.');
        }
    }

    send_json_response(false, 'Invalid action specified.');
}
