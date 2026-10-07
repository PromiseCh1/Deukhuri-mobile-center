<?php
declare(strict_types=1);

/**
 * categories/toggle_status.php – Toggle category status.
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

// Toggle status
$categoryService->toggleStatus($id);

// Preserve search and page
$returnUrl = $_SERVER['HTTP_REFERER'] ?? ADMIN_URL . '/categories/index.php';
setFlashMessage('success', 'Category "' . htmlspecialchars($category['name']) . '" status updated.');
redirect($returnUrl);