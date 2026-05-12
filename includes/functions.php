<?php
require_once __DIR__ . '/db.php';

function sanitizeInput($data) {
    if (is_array($data)) return array_map('sanitizeInput', $data);
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function getProductsByCategory($category_id, $limit = null) {
    global $pdo;
    $sql = "SELECT p.*, (
                SELECT image_path FROM product_images pi
                WHERE pi.product_id = p.id ORDER BY pi.id ASC LIMIT 1
            ) AS first_image
            FROM products p
            WHERE p.category_id = :cid
            ORDER BY p.created_at DESC";
    if ($limit !== null) $sql .= " LIMIT " . (int)$limit;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cid' => $category_id]);
    return $stmt->fetchAll();
}

function getLatestProducts($limit = 4) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, (
                SELECT image_path FROM product_images pi
                WHERE pi.product_id = p.id ORDER BY pi.id ASC LIMIT 1
            ) AS first_image
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        ORDER BY p.created_at DESC LIMIT " . (int)$limit);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProductImages($product_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = :pid ORDER BY id ASC");
    $stmt->execute([':pid' => $product_id]);
    return array_column($stmt->fetchAll(), 'image_path');
}

function getCategoryName($category_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
    $stmt->execute([':id' => $category_id]);
    $row = $stmt->fetch();
    return $row ? $row['name'] : '';
}

function formatSpecs($specs_json) {
    if (empty($specs_json)) return '';
    $specs = json_decode($specs_json, true);
    if (!is_array($specs)) return '';
    $html = '<ul class="specs-list">';
    foreach ($specs as $key => $value) {
        if ($value === '' || $value === null) continue;
        $label = htmlspecialchars(ucwords(str_replace('_', ' ', $key)));
        $val = htmlspecialchars(is_array($value) ? implode(', ', $value) : (string)$value);
        $html .= "<li><strong>{$label}:</strong> {$val}</li>";
    }
    $html .= '</ul>';
    return $html;
}

function isOutOfStock($stock) {
    return (int)$stock <= 0;
}

function getLowStockProducts($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE stock < 5 ORDER BY stock ASC LIMIT " . (int)$limit);
    $stmt->execute();
    return $stmt->fetchAll();
}

function productImageUrl($path) {
    if (!$path) return 'assets/images/placeholder.png';
    return htmlspecialchars($path);
}

function formatPrice($price) {
    return 'Rs. ' . number_format((float)$price, 2);
}

function getAllCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLatestProductsByCategory($category_id, $limit = 4) {
    global $pdo;
    $sql = "SELECT p.*, 
            (SELECT image_path FROM product_images WHERE product_id = p.id LIMIT 1) as first_image
            FROM products p
            WHERE p.category_id = ?
            ORDER BY p.created_at DESC
            LIMIT ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category_id, $limit]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as &$product) {
        $product['images'] = getProductImages($product['id']);
        $product['first_image'] = $product['first_image'] ?? 'assets/images/placeholder.png.svg';
    }
    return $products;
}


