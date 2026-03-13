<?php
// logout.php

// Start session (must be first thing)
session_start();

// Load helpers if needed (optional here, but good practice)
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

// Clear all session data
$_SESSION = [];

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Completely destroy the session
session_destroy();

// Optional: clear any "remember me" long-lived cookies if you added them later
// setcookie('remember_token', '', time() - 3600, '/');

// Flash message for nice UX
set_flash('success', 'You have been successfully logged out.');

// Redirect to login or public home
header('Location: login.php');
exit;