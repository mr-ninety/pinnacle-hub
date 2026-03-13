<?php
// auth.php - Shared authentication, security & helper functions
// Include this file at the top of every protected page

// Prevent direct access
if (!defined('SITE_URL')) {
    http_response_code(403);
    die('Direct access forbidden');
}

// Load configuration (you should have config.php already)
require_once __DIR__ . '/config.php';   // ← change to config.php once you created it

// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------------------
// 1. Require login (call this on protected pages)
// ---------------------------------------------------------------------
function require_login($required_role = null) {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }

    // Optional: check role if specified ('admin' or 'user')
    if ($required_role && isset($_SESSION['role']) && $_SESSION['role'] !== $required_role) {
        http_response_code(403);
        die('Access denied: insufficient permissions.');
    }

    // Optional: session timeout (in minutes)
    $timeout = SESSION_TIMEOUT_MINUTES * 60;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit;
    }

    $_SESSION['last_activity'] = time(); // update last activity time
}

// ---------------------------------------------------------------------
// 2. CSRF Protection
// ---------------------------------------------------------------------
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('Invalid CSRF token. Possible attack detected.');
    }
    // Optional: one-time token → regenerate after successful use
    // $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ---------------------------------------------------------------------
// 3. Safe output (XSS prevention)
// ---------------------------------------------------------------------
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// ---------------------------------------------------------------------
// 4. Flash message helper (for success/error after redirects)
// ---------------------------------------------------------------------
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type'    => $type,     // 'success', 'error', 'info', 'warning'
        'message' => $message
    ];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// ---------------------------------------------------------------------
// 5. Optional: Get current user info
// ---------------------------------------------------------------------
function current_user() {
    if (!isset($_SESSION['user_id'])) return null;

    global $conn;
    $stmt = $conn->prepare("SELECT id, username, email, full_name, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}