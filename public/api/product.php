<?php
require_once 'includes/functions.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid id']);
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.name AS category
    FROM products p LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

$images = getProductImages($id);
if (empty($images)) $images = ['assets/images/placeholder.png'];

echo json_encode([
    'id'              => (int)$product['id'],
    'name'            => $product['name'],
    'description'     => $product['description'],
    'price'           => (float)$product['price'],
    'price_formatted' => formatPrice($product['price']),
    'stock'           => (int)$product['stock'],
    'category'        => $product['category'],
    'specs_formatted' => formatSpecs($product['specs']),
    'images'          => $images,
]);
