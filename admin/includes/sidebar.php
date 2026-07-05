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

// Navigation items
$nav_items = [
    'Dashboard'     => 'dashboard.php',
    'Products'      => 'products.php',
    'Categories'    => 'categories.php',
    'Brands'        => 'brands.php',
    'Settings'      => '#', // placeholder
];
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-logo">
        <i class="fas fa-microchip"></i>
        <span><?= SITE_NAME ?></span>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <?php foreach ($nav_items as $label => $link): ?>
                <?php
                $active = ($current_page === $link) ? 'active' : '';
                // For placeholder '#', never active
                if ($link === '#') $active = '';
                ?>
                <li class="<?= $active ?>">
                    <a href="<?= $link ?>">
                        <i class="fas fa-<?php
                            // Assign icons
                            echo match($label) {
                                'Dashboard'   => 'chart-pie',
                                'Products'    => 'box',
                                'Categories'  => 'tags',
                                'Brands'      => 'building',
                                'Settings'    => 'cog',
                                default       => 'circle'
                            };
                        ?>"></i>
                        <span><?= $label ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= SITE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</aside>