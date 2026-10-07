<?php
/**
 * dashboard.php
 * Admin dashboard with stats and recent products.
 */

const APP_START = true;
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';

// Authenticate – login required
authenticate();

// ---------------------------------------------------------------------------
// Fetch Dashboard Data
// ---------------------------------------------------------------------------
try {
    // Total products
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $totalProducts = (int) $stmt->fetchColumn();

    // Total categories
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $totalCategories = (int) $stmt->fetchColumn();

    // Total brands
    $stmt = $pdo->query("SELECT COUNT(*) FROM brands");
    $totalBrands = (int) $stmt->fetchColumn();

    // Low stock products (stock < 5)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE stock < 5");
    $stmt->execute();
    $lowStock = (int) $stmt->fetchColumn();

    // Recent 5 products
    $stmt = $pdo->query("
        SELECT p.*, c.name AS category_name, b.name AS brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id
        ORDER BY p.created_at DESC
        LIMIT 5
    ");
    $recentProducts = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Dashboard query error: ' . $e->getMessage());
    $totalProducts = 0;
    $totalCategories = 0;
    $totalBrands = 0;
    $lowStock = 0;
    $recentProducts = [];
}

// ---------------------------------------------------------------------------
// Page Header (includes header, sidebar, breadcrumb)
// ---------------------------------------------------------------------------
$pageTitle = 'Dashboard';
$breadcrumbs = ['Dashboard' => ''];
require_once __DIR__ . '/includes/page_header.php';
?>

<!-- ============================================ -->
<!-- WELCOME SECTION -->
<!-- ============================================ -->
<div class="welcome-section">
    <h2>Welcome back, <?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?>! 👋</h2>
    <p class="welcome-meta">
        <i class="fas fa-calendar-alt"></i> <?= date('l, d M Y') ?> &nbsp;|&nbsp;
        <i class="fas fa-user-tag"></i> Role: <?= htmlspecialchars($_SESSION['role'] ?? 'Staff') ?>
    </p>
    <p class="welcome-message">"Manage your inventory with ease – everything is under control."</p>
</div>

<!-- ============================================ -->
<!-- STATS CARDS -->
<!-- ============================================ -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-box"></i></div>
        <div class="stat-number"><?= $totalProducts ?></div>
        <div class="stat-label">Total Products</div>
        <div class="stat-desc">All products in inventory</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="fas fa-tags"></i></div>
        <div class="stat-number"><?= $totalCategories ?></div>
        <div class="stat-label">Categories</div>
        <div class="stat-desc">Product categories</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="fas fa-building"></i></div>
        <div class="stat-number"><?= $totalBrands ?></div>
        <div class="stat-label">Brands</div>
        <div class="stat-desc">Product brands</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-number"><?= $lowStock ?></div>
        <div class="stat-label">Low Stock</div>
        <div class="stat-desc">Products with stock &lt; 5</div>
    </div>
</div>

<!-- ============================================ -->
<!-- RECENT PRODUCTS -->
<!-- ============================================ -->
<div class="card">
    <div class="card-title"><i class="fas fa-clock"></i> Recent Products</div>
    <?php if ($recentProducts): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProducts as $product): ?>
                        <?php
                        $stock = (int) $product['stock'];
                        if ($stock <= 0) {
                            $status = 'out-of-stock';
                            $statusLabel = 'Out of Stock';
                        } elseif ($stock < 5) {
                            $status = 'low-stock';
                            $statusLabel = 'Low Stock';
                        } else {
                            $status = 'in-stock';
                            $statusLabel = 'In Stock';
                        }
                        ?>
                        <tr>
                            <td><?= (int) $product['id'] ?></td>
                            <td><strong><?= htmlspecialchars($product['name']) ?></strong></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['brand_name'] ?? '-') ?></td>
                            <td><?= $stock ?></td>
                            <td><span class="status-badge <?= $status ?>"><?= $statusLabel ?></span></td>
                            <td>Rs. <?= number_format((float) $product['price'], 2) ?></td>
                            <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="empty-state">No products found in the inventory.</p>
    <?php endif; ?>
</div>

<?php
// ---------------------------------------------------------------------------
// Footer
// ---------------------------------------------------------------------------
require_once __DIR__ . '/includes/footer.php';
?>