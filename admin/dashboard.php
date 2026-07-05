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
// Fetch Dashboard Statistics
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
// Page Header (includes HTML head, sidebar, and top navbar)
// ---------------------------------------------------------------------------
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/page_header.php';
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= $totalProducts ?></div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $totalCategories ?></div>
        <div class="stat-label">Categories</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $totalBrands ?></div>
        <div class="stat-label">Brands</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $lowStock ?></div>
        <div class="stat-label">Low Stock ( &lt; 5 )</div>
    </div>
</div>

<!-- Recent Products -->
<div class="card">
    <div class="card-title">Recent Products</div>
    <?php if ($recentProducts): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProducts as $product): ?>
                        <tr>
                            <td><?= (int) $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($product['brand_name'] ?? '-') ?></td>
                            <td><?= number_format((float) $product['price'], 2) ?></td>
                            <td><?= (int) $product['stock'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="empty-state">No products found.</p>
    <?php endif; ?>
</div>

<?php
// ---------------------------------------------------------------------------
// Footer (closes all open wrappers)
// ---------------------------------------------------------------------------
require_once __DIR__ . '/includes/footer.php';
?>