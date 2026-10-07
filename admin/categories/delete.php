<?php
declare(strict_types=1);

/**
 * categories/delete.php – Delete a category (only if no products exist).
 */

const APP_START = true;
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/services/CategoryService.php';

authenticate(['Staff', 'Admin']);

$categoryService = new CategoryService($pdo);

// Validate ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    setFlashMessage('error', 'Invalid category ID.');
    redirect(ADMIN_URL . '/categories/index.php');
}

// Check if category exists
$category = $categoryService->getById($id);
if (!$category) {
    setFlashMessage('error', 'Category not found.');
    redirect(ADMIN_URL . '/categories/index.php');
}

// Check if category has products
if ($categoryService->hasProducts($id)) {
    setFlashMessage('error', 'This category contains products and cannot be deleted.');
    redirect(ADMIN_URL . '/categories/index.php');
}

// Delete
$categoryService->delete($id);
setFlashMessage('success', 'Category "' . htmlspecialchars($category['name']) . '" deleted successfully.');
redirect(ADMIN_URL . '/categories/index.php');