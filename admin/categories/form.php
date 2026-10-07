<?php
declare(strict_types=1);

/**
 * categories/form.php – Single reusable add/edit form.
 */

const APP_START = true;
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/services/CategoryService.php';

authenticate(['Staff', 'Admin']);

$categoryService = new CategoryService($pdo);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;
$category = $isEdit ? $categoryService->getById($id) : null;

if ($isEdit && !$category) {
    setFlashMessage('error', 'Category not found.');
    redirect(ADMIN_URL . '/categories/index.php');
}

// ---------------------------------------------------------------------------
// Process Submission
// ---------------------------------------------------------------------------
$errors = [];
$name = '';
$slug = '';
$status = 'Active';
$description = '';
$displayOrder = 0;
$showOnHome = 1;
$imagePath = '';

if (isPost()) {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $status = $_POST['status'] ?? 'Active';
    $description = trim($_POST['description'] ?? '');
    $displayOrder = (int)($_POST['display_order'] ?? 0);
    $showOnHome = isset($_POST['show_on_home']) ? 1 : 0;

    if (empty($slug)) {
        $slug = $categoryService->generateSlug($name);
    }

    // Validate
    $errors = $categoryService->validateCategory($name, $slug, $isEdit ? $id : null);

    if (empty($errors)) {
        $data = [
            'name' => $name,
            'slug' => $slug,
            'status' => $status,
            'description' => $description,
            'display_order' => $displayOrder,
            'show_on_home' => $showOnHome,
        ];

        $imageFile = $_FILES['image'] ?? null;

        try {
            if ($isEdit) {
                $result = $categoryService->update($id, $data, $imageFile);
                setFlashMessage('success', 'Category updated successfully.');
            } else {
                $newId = $categoryService->create($data, $imageFile);
                setFlashMessage('success', 'Category created successfully.');
            }
            redirect(ADMIN_URL . '/categories/index.php');
        } catch (Exception $e) {
            $errors['image'] = $e->getMessage();
        }
    }
}

// If editing and no errors, populate from DB
if ($isEdit && empty($errors)) {
    $name = $category['name'];
    $slug = $category['slug'];
    $status = $category['status'];
    $description = $category['description'] ?? '';
    $displayOrder = (int)($category['display_order'] ?? 0);
    $showOnHome = (int)($category['show_on_home'] ?? 1);
    $imagePath = $category['image'] ?? '';
}

// ---------------------------------------------------------------------------
// Page Header
// ---------------------------------------------------------------------------
$pageTitle = $isEdit ? 'Edit Category' : 'Add Category';
$breadcrumbs = [
    'Dashboard' => 'dashboard.php',
    'Categories' => 'index.php',
    ($isEdit ? 'Edit' : 'Add') => '',
];
require_once __DIR__ . '/../includes/page_header.php';
?>
<link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/css/categories.css">

<!-- Category Form -->
<div class="card form-card">
    <div class="card-title"><?= $isEdit ? '✏️ Edit Category' : '➕ Add New Category' ?></div>

    <form method="POST" action="" id="categoryForm" enctype="multipart/form-data" novalidate>
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Category Name <span class="required">*</span></label>
                <input type="text" id="name" name="name"
                       value="<?= htmlspecialchars($name) ?>"
                       class="<?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       required autofocus aria-describedby="nameHelp">
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
                <?php endif; ?>
                <small id="nameHelp" class="form-hint">The display name of the category.</small>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug"
                       value="<?= htmlspecialchars($slug) ?>"
                       class="<?= isset($errors['slug']) ? 'is-invalid' : '' ?>"
                       aria-describedby="slugHelp">
                <?php if (isset($errors['slug'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['slug']) ?></div>
                <?php endif; ?>
                <small id="slugHelp" class="form-hint">URL-friendly version. Auto-generated from the name.</small>
            </div>

            <div class="form-group">
                <label for="description">Short Description</label>
                <textarea id="description" name="description" rows="2"
                          class="<?= isset($errors['description']) ? 'is-invalid' : '' ?>"
                          aria-describedby="descHelp"><?= htmlspecialchars($description) ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
                <?php endif; ?>
                <small id="descHelp" class="form-hint">Brief description (max 255 chars).</small>
            </div>

            <div class="form-group">
                <label for="display_order">Display Order</label>
                <input type="number" id="display_order" name="display_order" min="0"
                       value="<?= $displayOrder ?>"
                       aria-describedby="orderHelp">
                <small id="orderHelp" class="form-hint">Lower numbers appear first on homepage.</small>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Active" <?= $status === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= $status === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <small class="form-hint">Inactive categories won't appear in product forms or homepage.</small>
            </div>

            <div class="form-group checkbox-group">
                <label for="show_on_home">
                    <input type="checkbox" id="show_on_home" name="show_on_home" value="1" <?= $showOnHome ? 'checked' : '' ?>>
                    Show on Homepage
                </label>
                <small class="form-hint">If checked, this category will appear in the "Browse Categories" section.</small>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="form-group image-upload">
            <label>Category Image</label>
            <div class="image-upload-wrapper">
                <?php if ($imagePath): ?>
                    <div class="image-preview">
                        <img src="<?= UPLOAD_URL . 'categories/' . htmlspecialchars($imagePath) ?>" alt="Category Image">
                        <button type="button" class="btn-remove-image" id="removeImageBtn">✕</button>
                    </div>
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($imagePath) ?>">
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                       aria-describedby="imageHelp">
                <small id="imageHelp" class="form-hint">Allowed: JPG, PNG, WEBP. Max 2MB.</small>
                <?php if (isset($errors['image'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['image']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Update Category' : 'Create Category' ?>
            </button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>