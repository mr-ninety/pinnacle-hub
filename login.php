<?php
// login.php

// Start session early
session_start();

// Load helpers & config
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';   // ← make sure this points to your real config.php

// If already logged in → redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle form submission
$errors = [];
$flash = get_flash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        $errors[] = 'Both username/email and password are required.';
    } else {
        global $conn;

        // Allow login with username OR email
        $stmt = $conn->prepare("
            SELECT id, username, email, password, role, full_name 
            FROM users 
            WHERE username = ? OR email = ?
            LIMIT 1
        ");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['last_activity'] = time();

            // Optional: "Remember me" (longer session cookie lifetime)
            if ($remember) {
                ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // 30 days
                session_regenerate_id(true);
            }

            // Update last_login
            $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update->bind_param("i", $user['id']);
            $update->execute();

            set_flash('success', 'Welcome back, ' . e($user['full_name'] ?? $user['username']) . '!');
            
            // Redirect to intended page or default dashboard
            $redirect = $_SESSION['redirect_after_login'] ?? 'dashboard.php';
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit;
        } else {
            $errors[] = 'Invalid username/email or password.';
        }
    }

    if ($errors) {
        set_flash('error', implode('<br>', $errors));
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | Unnnati Dashboard</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css">
  <link rel="manifest" href="manifest.json">
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 flex items-center justify-center p-6">

  <div class="w-full max-w-md">
    <div class="card p-10">
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-emerald-400">Unnnati</h1>
        <p class="text-slate-400 mt-2">Sign in to your dashboard</p>
      </div>

      <?php if ($flash): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $flash['type'] === 'success' ? 'bg-emerald-900/40 text-emerald-200 border border-emerald-700/50' : 'bg-red-900/40 text-red-200 border border-red-700/50'; ?>">
          <?= $flash['message'] ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div>
          <label for="username" class="block text-sm font-medium text-slate-300 mb-2">
            Username or Email
          </label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            required 
            autofocus
            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-white placeholder-slate-500"
            placeholder="naveen / naveen@example.com"
          >
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
            Password
          </label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            required 
            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-white placeholder-slate-500"
            placeholder="••••••••"
          >
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center text-slate-400">
            <input type="checkbox" name="remember" class="mr-2 rounded border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500">
            Remember me
          </label>
          <!-- <a href="#" class="text-emerald-400 hover:text-emerald-300">Forgot password?</a> -->
        </div>

        <button 
          type="submit" 
          class="w-full btn btn-primary py-3 text-lg font-medium"
        >
          Sign In
        </button>
      </form>

      <div class="mt-8 text-center text-sm text-slate-400">
        Don't have an account? 
        <!-- <a href="register.php" class="text-emerald-400 hover:text-emerald-300">Register</a> -->
        <!-- For now we can comment register link out if you only want admin account -->
      </div>

      <div class="mt-6 text-center">
        <a href="index.php" class="text-slate-400 hover:text-slate-300 text-sm">
          ← Back to Portfolio
        </a>
      </div>
    </div>
  </div>

  <!-- Theme toggle script (same as index.php) -->
  <script>
    const toggle = document.createElement('button');
    toggle.id = 'theme-toggle';
    toggle.className = 'fixed bottom-6 right-6 text-2xl theme-toggle bg-slate-800 p-3 rounded-full shadow-lg';
    toggle.setAttribute('aria-label', 'Toggle theme');
    toggle.textContent = '🌙';
    document.body.appendChild(toggle);

    const html = document.documentElement;
    function setTheme(theme) {
      html.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
      toggle.textContent = theme === 'dark' ? '🌙' : '☀️';
    }

    const saved = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    setTheme(saved);

    toggle.addEventListener('click', () => {
      setTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });
  </script>
</body>
</html>