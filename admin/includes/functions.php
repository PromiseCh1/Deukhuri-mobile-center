<?php
declare(strict_types=1);

/**
 * functions.php
 * Reusable helper functions for the admin panel.
 * Grouped by responsibility: Input/Output, HTTP, Redirect, Auth, Flash, CSRF, Headers.
 */

// ---------------------------------------------------------------------------
// Security – Prevent direct access
// ---------------------------------------------------------------------------
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// ===========================================================================
// 1. INPUT & OUTPUT HELPERS
// ===========================================================================

/**
 * Escape output for HTML – prevents XSS.
 * Use this when displaying dynamic data in HTML.
 *
 * @param mixed $input The data to escape.
 * @return mixed The escaped data (string or array).
 */
function escape(mixed $input): mixed
{
    if (is_array($input)) {
        return array_map('escape', $input);
    }
    return htmlspecialchars((string)$input, ENT_QUOTES, 'UTF-8');
}

/**
 * Alias for escape() – maintained for backward compatibility.
 * @see escape()
 */
function sanitize(mixed $input): mixed
{
    return escape($input);
}

/**
 * Sanitise input for storage – trims and strips tags.
 * Use this for user input before validation (not for output).
 *
 * @param mixed $input The input to sanitise.
 * @return mixed The sanitised data.
 */
function sanitizeInput(mixed $input): mixed
{
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return trim(strip_tags((string)$input));
}

// ===========================================================================
// 2. HTTP HELPERS
// ===========================================================================

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function isGet(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function isAjax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function isHttps(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
           (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
}

function getClientIp(): ?string
{
    $ip = null;
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    }
    return $ip;
}

// ===========================================================================
// 3. REDIRECTION HELPERS
// ===========================================================================

/**
 * Redirect to a given URL and terminate execution.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Redirect back to the previous page, or to a fallback.
 */
function redirectBack(string $fallback = ''): never
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    if ($referer) {
        redirect($referer);
    } elseif ($fallback) {
        redirect($fallback);
    } else {
        redirect(ADMIN_URL . '/dashboard.php');
    }
}

// ===========================================================================
// 4. AUTHENTICATION & ROLE STATE HELPERS
// ===========================================================================

/**
 * Check if the user is currently logged in.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Check if the logged-in user has the 'Admin' role.
 */
function isAdmin(): bool
{
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
}

/**
 * Check if the logged-in user has 'Staff' or 'Admin' role.
 */
function isStaff(): bool
{
    return isLoggedIn() && isset($_SESSION['role']) &&
           ($_SESSION['role'] === 'Staff' || $_SESSION['role'] === 'Admin');
}

/**
 * Check if the logged-in user has a specific role.
 */
function hasRole(string $role): bool
{
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Get the current user's ID.
 */
function getCurrentUserId(): ?int
{
    return isLoggedIn() ? (int)$_SESSION['admin_id'] : null;
}

/**
 * Get the current user's role.
 */
function getCurrentUserRole(): ?string
{
    return isLoggedIn() ? $_SESSION['role'] : null;
}

/**
 * Get the current user's full name.
 */
function getCurrentUserName(): ?string
{
    return isLoggedIn() ? $_SESSION['full_name'] : null;
}

// ===========================================================================
// 5. FLASH MESSAGES (Session-based)
// ===========================================================================

/**
 * Store a flash message in the session.
 */
function setFlashMessage(string $key, string $message): void
{
    if (!isset($_SESSION['__flash'])) {
        $_SESSION['__flash'] = [];
    }
    $_SESSION['__flash'][$key] = $message;
}

/**
 * Display and optionally clear a flash message.
 */
function displayFlashMessage(string $key, bool $keep = false): ?string
{
    if (isset($_SESSION['__flash'][$key])) {
        $msg = $_SESSION['__flash'][$key];
        if (!$keep) {
            unset($_SESSION['__flash'][$key]);
        }
        return $msg;
    }
    return null;
}

/**
 * Check if a flash message exists.
 */
function hasFlashMessage(string $key): bool
{
    return isset($_SESSION['__flash'][$key]);
}

// ===========================================================================
// 6. CSRF TOKEN HELPERS
// ===========================================================================

/**
 * Generate and store a CSRF token.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION[CSRF_TOKEN_NAME]) || !isset($_SESSION[CSRF_TOKEN_NAME . '_time'])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Retrieve the current CSRF token.
 */
function getCsrfToken(): ?string
{
    return $_SESSION[CSRF_TOKEN_NAME] ?? null;
}

/**
 * Verify a CSRF token (with optional expiration check).
 * Clears the token after successful verification (single-use).
 */
function verifyCsrfToken(string $token, bool $checkExpiry = false, int $lifetime = CSRF_TOKEN_LIFETIME): bool
{
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }

    if ($checkExpiry) {
        $time = $_SESSION[CSRF_TOKEN_NAME . '_time'] ?? 0;
        if (time() - $time > $lifetime) {
            return false;
        }
    }

    $valid = hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    if ($valid) {
        clearCsrfToken(); // Single-use
    }
    return $valid;
}

/**
 * Clear the CSRF token.
 */
function clearCsrfToken(): void
{
    unset($_SESSION[CSRF_TOKEN_NAME]);
    unset($_SESSION[CSRF_TOKEN_NAME . '_time']);
}

// ===========================================================================
// 7. SECURITY HEADERS
// ===========================================================================

/**
 * Send common security headers.
 */
function sendSecurityHeaders(): void
{
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer-when-downgrade');
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    if (isHttps() && APP_ENV === 'production') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}