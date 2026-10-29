<?php
/**
 * StudentHub Portal - Core Configuration & JSON Storage Helpers
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone and encoding
date_default_timezone_set('Asia/Kolkata');
mb_internal_encoding('UTF-8');

// Define data directory path
define('DATA_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR);

// Allow CORS for local development / fetch requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/**
 * Read and decode a JSON file safely
 *
 * @param string $filename
 * @param mixed $default
 * @return mixed
 */
function read_json_data($filename, $default = []) {
    $filePath = DATA_DIR . $filename;
    if (!file_exists($filePath)) {
        return $default;
    }
    $content = file_get_contents($filePath);
    if ($content === false || trim($content) === '') {
        return $default;
    }
    $data = json_decode($content, true);
    return ($data !== null) ? $data : $default;
}

/**
 * Write data array to a JSON file safely with file locking
 *
 * @param string $filename
 * @param mixed $data
 * @return bool
 */
function write_json_data($filename, $data) {
    $filePath = DATA_DIR . $filename;
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return (file_put_contents($filePath, $json, LOCK_EX) !== false);
}

/**
 * Send a structured JSON response and terminate script
 *
 * @param bool $success
 * @param string $message
 * @param mixed $data
 * @param int $statusCode
 */
function send_json_response($success, $message = '', $data = null, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Retrieve request body (supporting both JSON payload and standard form data)
 *
 * @return array
 */
function get_request_data() {
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        return is_array($json) ? $json : [];
    }
    return $_POST;
}

/**
 * Check if the user is logged in
 *
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * Get current logged in user
 *
 * @return array|null
 */
function get_current_user_data() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

/**
 * Sanitize string input
 *
 * @param string $input
 * @return string
 */
function clean_input($input) {
    return htmlspecialchars(trim((string)$input), ENT_QUOTES, 'UTF-8');
}
