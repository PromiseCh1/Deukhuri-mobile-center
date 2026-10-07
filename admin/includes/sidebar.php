<?php
/**
 * sidebar.php
 * Admin sidebar navigation.
 */
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

// Determine current page for active class
$current_page = basename($_SERVER['PHP_SELF']);

// Navigation items with optional submenu (submenu support for future)
$nav_items = [
    'Dashboard' => [
        'url' => ADMIN_URL . '/dashboard.php',
        'icon' => 'fa-chart-pie',
        'sub' => []
    ],
    'Categories' => [
        'url' => '#',
        'icon' => 'fa-tags',
        'sub' => [
            ['label' => 'All Categories', 'url' => ADMIN_URL . '/categories/index.php'],
            ['label' => 'Add Category', 'url' => ADMIN_URL . '/categories/form.php'],
        ]
    ],
    // Products and Brands hidden for now
    'Settings' => [
        'url' => '#',
        'icon' => 'fa-cog',
        'sub' => []
    ],
];

// Determine if a submenu should be expanded (for active child)
function isSubActive($subItems): bool {
    global $current_page;
    foreach ($subItems as $item) {
        if ($item['url'] === $current_page) {
            return true;
        }
    }
    return false;
}
?>
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <i class="fas fa-microchip"></i>
        <span><?= SITE_NAME ?></span>
        <button class="sidebar-close" id="sidebarClose" aria-label="Close sidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul>
            <?php foreach ($nav_items as $label => $item): ?>
                <?php
                $isActive = ($current_page === $item['url'] || isSubActive($item['sub']));
                $hasSub = !empty($item['sub']);
                $liClass = $isActive ? 'active' : '';
                $liClass .= $hasSub ? ' has-sub' : '';
                ?>
                <li class="<?= $liClass ?>">
                    <a href="<?= $item['url'] ?>" <?= $hasSub ? 'class="sub-toggle"' : '' ?>>
                        <i class="fas <?= $item['icon'] ?>"></i>
                        <span><?= $label ?></span>
                        <?php if ($hasSub): ?>
                            <i class="fas fa-chevron-down sub-arrow"></i>
                        <?php endif; ?>
                    </a>
                    <?php if ($hasSub): ?>
                        <ul class="sub-menu <?= $isActive ? 'open' : '' ?>">
                            <?php foreach ($item['sub'] as $sub): ?>
                                <li class="<?= ($current_page === $sub['url']) ? 'active' : '' ?>">
                                    <a href="<?= $sub['url'] ?>">
                                        <i class="fas fa-circle"></i>
                                        <span><?= $sub['label'] ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Footer (Logout) -->
    <div class="sidebar-footer">
        <a href="<?= ADMIN_URL ?>/logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>