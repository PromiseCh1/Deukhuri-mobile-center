<?php
declare(strict_types=1);

/**
 * categories/index.php – Professional category listing.
 */

const APP_START = true;
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/services/CategoryService.php';

authenticate(['Staff', 'Admin']);

$categoryService = new CategoryService($pdo);

$perPage = PAGINATION_LIMIT;
$page = max(1, (int) ($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');

$categories = $categoryService->getCategoriesWithProductCount($search, $page, $perPage);
$total = $categoryService->getTotalCategoriesWithProductCount($search);
$totalPages = max(1, ceil($total / $perPage));

// Stats
$totalAll = $categoryService->countAll();
$totalActive = $categoryService->countActive();
$totalInactive = $categoryService->countInactive();
$totalUsed = $categoryService->countUsedCategories();
$totalHome = $categoryService->countHomepageCategories();

$pageTitle = 'Categories';
$breadcrumbs = ['Dashboard' => 'dashboard.php', 'Categories' => ''];
require_once __DIR__ . '/../includes/page_header.php';
?>
<link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/css/categories.css">

<!-- Flash Messages -->
<?php if ($msg = displayFlashMessage('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($msg) ?>
        <button type="button" class="alert-close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>
<?php if ($msg = displayFlashMessage('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($msg) ?>
        <button type="button" class="alert-close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-tags"></i></div>
        <div class="stat-number"><?= $totalAll ?></div>
        <div class="stat-label">Total Categories</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-number"><?= $totalActive ?></div>
        <div class="stat-label">Active</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        <div class="stat-number"><?= $totalInactive ?></div>
        <div class="stat-label">Inactive</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="fas fa-home"></i></div>
        <div class="stat-number"><?= $totalHome ?></div>
        <div class="stat-label">On Homepage</div>
    </div>
    <div class="stat-card info">
        <div class="stat-icon"><i class="fas fa-boxes"></i></div>
        <div class="stat-number"><?= $totalUsed ?></div>
        <div class="stat-label">Used by Products</div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <div class="toolbar-left">
        <form method="GET" action="" class="search-form" id="searchForm">
            <div class="search-group">
                <input type="text" name="search" id="searchInput" placeholder="Search categories..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                <?php if ($search): ?>
                    <button type="button" class="btn-clear" id="clearSearch"><i class="fas fa-times"></i></button>
                <?php endif; ?>
            </div>
        </form>
        <?php if ($search): ?>
            <span class="search-result-count">Showing <strong><?= $total ?></strong> result<?= $total !== 1 ? 's' : '' ?></span>
        <?php endif; ?>
    </div>
    <div class="toolbar-right">
        <a href="form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>
    </div>
</div>

<!-- Category Table -->
<div class="card table-card">
    <?php if (empty($categories)): ?>
        <div class="empty-state">
            <i class="fas fa-tags empty-icon"></i>
            <h3><?= $search ? 'No categories found' : 'No categories yet' ?></h3>
            <p><?= $search ? 'Try adjusting your search.' : 'Get started by creating your first category.' ?></p>
            <?php if (!$search): ?>
                <a href="form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>
            <?php else: ?>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset Search</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th class="text-center">Homepage</th>
                        <th>Status</th>
                        <th class="text-center">Products</th>
                        <th>Created</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = (($page - 1) * $perPage) + 1;
                    foreach ($categories as $cat):
                        $statusClass = $cat['status'] === 'Active' ? 'badge-success' : 'badge-secondary';
                        $homeIcon = $cat['show_on_home'] ? '✔️ Yes' : '✖️ No';
                        $homeClass = $cat['show_on_home'] ? 'text-success' : 'text-muted';
                        $imageSrc = $cat['image'] ? ADMIN_URL . '/uploads/categories/' . $cat['image'] : ADMIN_URL . '/assets/images/placeholder.png';
                    ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($cat['name']) ?>" class="category-thumb"></td>
                            <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                            <td><?= htmlspecialchars($cat['description'] ?? '') ?></td>
                            <td class="text-center"><span class="<?= $homeClass ?>"><?= $homeIcon ?></span></td>
                            <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($cat['status']) ?></span></td>
                            <td class="text-center">
                                <?php if ((int)$cat['product_count'] > 0): ?>
                                    <span class="badge badge-info"><?= (int)$cat['product_count'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted">0</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($cat['created_at'] ?? 'now')) ?></td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="form.php?id=<?= (int)$cat['id'] ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="toggle_status.php?id=<?= (int)$cat['id'] ?>" class="btn-icon" title="<?= $cat['status'] === 'Active' ? 'Deactivate' : 'Activate' ?>">
                                        <i class="fas <?= $cat['status'] === 'Active' ? 'fa-pause-circle' : 'fa-play-circle' ?>"></i>
                                    </a>
                                    <a href="#" class="btn-icon btn-delete" data-modal="delete"
                                       data-url="delete.php?id=<?= (int)$cat['id'] ?>"
                                       data-name="<?= htmlspecialchars($cat['name']) ?>"
                                       data-has-products="<?= (int)$cat['product_count'] > 0 ? 'true' : 'false' ?>"
                                       title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing <strong><?= (($page - 1) * $perPage) + 1 ?></strong> –
                    <strong><?= min($page * $perPage, $total) ?></strong> of <strong><?= $total ?></strong>
                </div>
                <nav class="pagination">
                    <ul>
                        <?php if ($page > 1): ?>
                            <li><a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">&laquo;</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="<?= $i === $page ? 'active' : '' ?>">
                                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <li><a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">&raquo;</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="deleteModal" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Confirm Deletion</h3>
            <button type="button" class="modal-close" id="deleteModalClose">&times;</button>
        </div>
        <div class="modal-body">
            <p id="deleteModalMessage">Are you sure you want to delete this category?</p>
            <p id="deleteModalWarning" style="color: #EF4444; display:none;">
                <i class="fas fa-exclamation-triangle"></i> This category contains products. It cannot be deleted.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="deleteModalCancel">Cancel</button>
            <a href="#" class="btn btn-danger" id="deleteModalConfirm">Yes, Delete</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>