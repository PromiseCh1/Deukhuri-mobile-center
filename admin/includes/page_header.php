<?php
/**
 * page_header.php
 * Standard admin page header.
 * Includes the HTML head, sidebar, top navbar, and opens the content area.
 */

// ---------------------------------------------------------------------------
// Security – Prevent direct access
// ---------------------------------------------------------------------------
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// Determine current page for sidebar highlighting
$current_page = basename($_SERVER['PHP_SELF']);
$pageTitle = $pageTitle ?? 'Dashboard';

// Include the HTML head and open admin-wrapper
require_once __DIR__ . '/header.php';

// Include the sidebar
require_once __DIR__ . '/sidebar.php';
?>

<!-- Main content area -->
<div class="admin-main">
    <!-- Top Navbar -->
    <header class="admin-header">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <span class="header-title"><?= htmlspecialchars($pageTitle) ?></span>
        </div>
        <div class="header-right">
            <span class="user-name"><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></span>
            <span class="role-badge <?= strtolower($_SESSION['role'] ?? 'staff') ?>">
                <?= htmlspecialchars($_SESSION['role'] ?? 'Staff') ?>
            </span>
            <div class="dropdown">
                <button class="dropdown-toggle" id="userDropdown">
                    <i class="fas fa-user-circle"></i>
                </button>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="<?= ADMIN_URL ?>/profile.php"><i class="fas fa-user"></i> Profile</a>
                    <a href="<?= ADMIN_URL ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Page content opens here -->
    <main class="admin-content"></main>