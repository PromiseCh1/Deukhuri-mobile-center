<?php
/**
 * functions.php
 * Reusable helper functions for the admin panel.
 * Includes sanitisation, redirection, authentication, flash messages, and CSRF.
 */

// Ensure this file is only accessed via the bootstrap
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// ---------------------------------------------------------------------------
// Sanitisation
// ---------------------------------------------------------------------------

/**
 * Recursively sanitise input (trim, strip tags, htmlspecialchars).
 * Works on both strings and arrays.
 *
 * @param mixed $input The data to sanitise.
 * @return mixed The sanitised data.
 */
function sanitize($input)
{
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim((string)$input), ENT_QUOTES, 'UTF-8');
}

// ---------------------------------------------------------------------------
// Redirection
// ---------------------------------------------------------------------------

/**
 * Redirect to a given URL and exit.
 *
 * @param string $url The absolute or relative URL.
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

// ---------------------------------------------------------------------------
// Authentication & Role Checks
// ---------------------------------------------------------------------------

/**
 * Check if the user is logged in (session exists).
 *
 * @return bool True if logged in, false otherwise.
 */
function isLoggedIn()
{
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Check if the logged-in user has the 'Admin' role.
 *
 * @return bool True if role is Admin, false otherwise.
 */
function isAdmin()
{
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
}

/**
 * Check if the logged-in user has the 'Staff' role (or Admin).
 *
 * @return bool True if role is Staff or Admin, false otherwise.
 */
function isStaff()
{
    return isLoggedIn() && isset($_SESSION['role']) && ($_SESSION['role'] === 'Staff' || $_SESSION['role'] === 'Admin');
}

/**
 * Get the current logged-in user's ID.
 *
 * @return int|null The user ID, or null if not logged in.
 */
function getCurrentUserId()
{
    return isLoggedIn() ? (int)$_SESSION['admin_id'] : null;
}

/**
 * Get the current logged-in user's role.
 *
 * @return string|null The role, or null if not logged in.
 */
function getCurrentUserRole()
{
    return isLoggedIn() ? $_SESSION['role'] : null;
}

// ---------------------------------------------------------------------------
// Flash Messages (session-based)
// ---------------------------------------------------------------------------

/**
 * Store a flash message in the session.
 *
 * @param string $key     The message key (e.g., 'success', 'error').
 * @param string $message The message content.
 */
function setFlashMessage($key, $message)
{
    if (!isset($_SESSION['__flash'])) {
        $_SESSION['__flash'] = [];
    }
    $_SESSION['__flash'][$key] = $message;
}

/**
 * Display and clear a flash message.
 *
 * @param string $key The message key.
 * @return string|null The message, or null if none.
 */
function displayFlashMessage($key)
{
    if (isset($_SESSION['__flash'][$key])) {
        $msg = $_SESSION['__flash'][$key];
        unset($_SESSION['__flash'][$key]);
        return $msg;
    }
    return null;
}

// ---------------------------------------------------------------------------
// CSRF Token (for future forms)
// ---------------------------------------------------------------------------

/**
 * Generate and store a CSRF token in the session.
 *
 * @return string The generated token.
 */
function generateCsrfToken()
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Retrieve the current CSRF token from the session.
 *
 * @return string|null The token, or null if not set.
 */
function getCsrfToken()
{
    return $_SESSION[CSRF_TOKEN_NAME] ?? null;
}

/**
 * Verify that a given token matches the stored CSRF token.
 *
 * @param string $token The token to verify.
 * @return bool True if valid, false otherwise.
 */
function verifyCsrfToken($token)
{
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}