<?php
/**
 * config.example.php
 * 
 * Copy this file to config.php and fill in your real values.
 * NEVER commit config.php to git!
 */

session_start();

// === Database Configuration ===
define('DB_HOST',     'localhost');
define('DB_NAME',     'unnnati_db');
define('DB_USER',     'root');           // ← change this
define('DB_PASS',     '');               // ← change this (empty for default XAMPP)

// === Site Configuration ===
define('SITE_URL',    'http://localhost/unnnati');  // change to your domain later
define('SITE_NAME',   'Unnnati Dashboard');
define('ADMIN_EMAIL', 'yourname@example.com');

// === Security / Session Settings ===
define('SESSION_TIMEOUT_MINUTES', 60);   // auto-logout after X minutes of inactivity
define('CSRF_TOKEN_LENGTH', 64);

// === File Upload Settings ===
define('UPLOAD_DIR',      __DIR__ . '/uploads/');
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2MB

// === Database Connection ===
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    // In production: log error, show friendly message
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

// === Helper Functions ===

/**
 * Generate or retrieve CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate submitted CSRF token
 */
function validate_csrf(): void {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('Invalid CSRF token. Possible attack detected.');
    }
    // Optional: regenerate token after successful validation (one-time-use)
    // unset($_SESSION['csrf_token']);
}

/**
 * Safe output (prevent XSS)
 */
function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in + optional role check
 */
function require_login(string $required_role = null): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    
    if ($required_role && $_SESSION['role'] !== $required_role) {
        http_response_code(403);
        die('Access denied: insufficient permissions.');
    }
}