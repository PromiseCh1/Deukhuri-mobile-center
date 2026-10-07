<?php
declare(strict_types=1);

/**
 * auth_check.php
 * Authentication middleware for every admin page.
 */

if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// ---------------------------------------------------------------------------
// 1. SESSION CONFIGURATION
// ---------------------------------------------------------------------------
session_name(SESSION_NAME);
session_set_cookie_params([
    'lifetime' => SESSION_TIMEOUT,
    'path'     => SESSION_COOKIE_PATH ?: '/',
    'domain'   => SESSION_COOKIE_DOMAIN ?: '',
    'secure'   => isHttps(),
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------------------------
// 2. SESSION TIMEOUT
// ---------------------------------------------------------------------------
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        $flash = $_SESSION['__flash'] ?? [];
        $_SESSION = [];
        $_SESSION['__flash'] = $flash;
        setFlashMessage('error', 'Your session has expired. Please log in again.');
        redirect(ADMIN_URL . '/login.php');
    }
}
$_SESSION['last_activity'] = time();

// ---------------------------------------------------------------------------
// 3. SESSION REGENERATION (periodic)
// ---------------------------------------------------------------------------
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// ---------------------------------------------------------------------------
// 4. AUTHENTICATION CHECK
// ---------------------------------------------------------------------------
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please log in to access the admin panel.');
    redirect(ADMIN_URL . '/login.php');
}

// ---------------------------------------------------------------------------
// 5. REUSABLE ROLE HELPERS
// ---------------------------------------------------------------------------

/**
 * Ensure the user is logged in.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Please log in to access this page.');
        redirect(ADMIN_URL . '/login.php');
    }
}

/**
 * Ensure the logged-in user has one or more allowed roles.
 * Accepts a single role string or an array of role strings.
 *
 * @param string|array $allowedRoles Single role or array of allowed roles.
 * @param string       $redirectUrl  Optional custom redirect URL.
 */
function requireRole(string|array $allowedRoles, string $redirectUrl = ''): void
{
    requireLogin();

    $userRole = $_SESSION['role'] ?? '';

    // Admin has universal access
    if ($userRole === 'Admin') {
        return;
    }

    // Convert to array for consistent checking
    if (is_string($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }

    if (!in_array($userRole, $allowedRoles, true)) {
        setFlashMessage('error', 'You do not have permission to access this page.');
        $redirectUrl = $redirectUrl ?: ADMIN_URL . '/dashboard.php';
        redirect($redirectUrl);
    }
}

/**
 * Alias for requireRole() – ensures backward compatibility.
 * @deprecated Use requireRole() directly (supports arrays now).
 */
function requireAnyRole(array $allowedRoles, string $redirectUrl = ''): void
{
    requireRole($allowedRoles, $redirectUrl);
}

/**
 * Combined authentication and optional role check.
 * Call this after including the file.
 *
 * @param string|array|null $requiredRole Optional role(s) to require.
 * @param string            $redirectUrl  Optional custom redirect URL.
 */
function authenticate(string|array|null $requiredRole = null, string $redirectUrl = ''): void
{
    // Already checked above, but re-check for safety.
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Please log in.');
        redirect(ADMIN_URL . '/login.php');
    }

    if ($requiredRole !== null) {
        requireRole($requiredRole, $redirectUrl);
    }
}

// ---------------------------------------------------------------------------
// 6. Automatic role check if page defines REQUIRE_ROLE
// ---------------------------------------------------------------------------
if (defined('REQUIRE_ROLE')) {
    authenticate(REQUIRE_ROLE);
}