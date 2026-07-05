<?php
/**
 * login.php
 * Admin login page.
 */

// Bootstrap
const APP_START = true;
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect(SITE_URL . '/admin/dashboard.php');
}

$error = null;

// Process login
if (isPost()) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT id, username, full_name, password, role, status FROM admins WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch();

            if ($admin && $admin['status'] === 'Active' && password_verify($password, $admin['password'])) {
                // Regenerate session ID
                session_regenerate_id(true);

                // Store session variables
                $_SESSION['admin_id']   = (int)$admin['id'];
                $_SESSION['username']   = $admin['username'];
                $_SESSION['full_name']  = $admin['full_name'];
                $_SESSION['role']       = $admin['role'];
                $_SESSION['last_activity'] = time();
                $_SESSION['created']    = time();

                setFlashMessage('success', 'Welcome back, ' . htmlspecialchars($admin['full_name']) . '!');
                redirect(SITE_URL . '/admin/dashboard.php');
            }

            $error = 'Invalid username or password.';
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            $error = 'An error occurred. Please try again later.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}

// Include login CSS
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-header">
                <h1><?= SITE_NAME ?></h1>
                <p>Admin Panel</p>
            </div>

            <?php if ($error): ?>
                <div class="login-alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php $flash = displayFlashMessage('success'); if ($flash): ?>
                <div class="login-alert success"><?= htmlspecialchars($flash) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="login-footer">
                &copy; <?= date('Y') ?> <?= SITE_NAME ?> – All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>