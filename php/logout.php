<?php
/**
 * StudentHub - Logout Endpoint
 */
require_once __DIR__ . '/config.php';

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

if (isset($_GET['format']) && $_GET['format'] === 'html') {
    header('Location: ../pages/login.html');
    exit;
}

send_json_response(true, 'Logged out successfully', [
    'redirect' => 'login.html'
]);
