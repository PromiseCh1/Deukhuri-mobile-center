<?php

declare(strict_types=1);

/**
 * CategoryService.php
 * Handles all database operations for categories.
 * No SQL should exist outside this file.
 */

if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

class CategoryService
{
    private PDO $pdo;
    private string $uploadPath;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->uploadPath = UPLOAD_CATEGORIES; // defined in config.php
        $this->ensureUploadDir();
    }

    private function ensureUploadDir(): void
    {
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    // ========================================================================
    // PRIVATE HELPERS
    // ========================================================================

    private function buildCategoryQuery(bool $withProductCount = false): string
    {
        $select = $withProductCount
            ? "c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) AS product_count"
            : "c.*";
        return "SELECT {$select} FROM categories c";
    }

    /**
     * Generate a unique filename for an uploaded image.
     */
    private function generateImageName(int $categoryId, string $extension): string
    {
        return 'category_' . $categoryId . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    }

    /**
     * Validate uploaded image.
     */
    private function validateImage(array $file): void
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Image upload failed.');
        }
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Only JPG, PNG, WEBP images are allowed.');
        }
        if ($file['size'] > $maxSize) {
            throw new Exception('Image size must be less than 2MB.');
        }
        // Additional MIME check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowedTypes)) {
            throw new Exception('Invalid file type.');
        }
    }

    /**
     * Upload image, returns filename or null.
     */
    private function uploadImageFile(array $file, int $categoryId): ?string
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $this->validateImage($file);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $this->generateImageName($categoryId, $ext);
        $destination = $this->uploadPath . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Failed to save image.');
        }
        return $filename;
    }

    /**
     * Delete image file from disk.
     */
    private function deleteImageFile(?string $filename): void
    {
        if ($filename) {
            $path = $this->uploadPath . $filename;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    // ========================================================================
    // PUBLIC METHODS
    // ========================================================================

    /**
     * Get categories with product count, search, and pagination.
     */
    public function getCategoriesWithProductCount(string $search = '', int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = '%' . $search . '%';
        $sql = $this->buildCategoryQuery(true) . "
            WHERE c.name LIKE :search_name OR c.slug LIKE :search_slug
            ORDER BY c.display_order ASC, c.id DESC
            LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search_name', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':search_slug', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCategoriesWithProductCount(string $search = ''): int
    {
        $searchTerm = '%' . $search . '%';
        $sql = "SELECT COUNT(*) FROM categories WHERE name LIKE :search_name OR slug LIKE :search_slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search_name', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':search_slug', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getAll(string $search = '', int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = '%' . $search . '%';
        $sql = $this->buildCategoryQuery(false) . "
            WHERE c.name LIKE :search_name OR c.slug LIKE :search_slug
            ORDER BY c.display_order ASC, c.id DESC
            LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search_name', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':search_slug', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCount(string $search = ''): int
    {
        $searchTerm = '%' . $search . '%';
        $sql = "SELECT COUNT(*) FROM categories WHERE name LIKE :search_name OR slug LIKE :search_slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search_name', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':search_slug', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function nameExists(string $name, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM categories WHERE name = :name";
        $params = [':name' => trim($name)];
        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM categories WHERE slug = :slug";
        $params = [':slug' => $slug];
        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function validateCategory(string $name, string $slug, ?int $excludeId = null): array
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Category name is required.';
        } elseif ($this->nameExists($name, $excludeId)) {
            $errors['name'] = 'A category with this name already exists.';
        }
        if (!empty($slug) && $this->slugExists($slug, $excludeId)) {
            $errors['slug'] = 'A category with this slug already exists.';
        }
        return $errors;
    }

    public function create(array $data, ?array $imageFile): int
    {
        $name = trim($data['name']);
        $slug = $data['slug'] ?? $this->generateSlug($name);
        $status = $data['status'] ?? 'Active';
        $description = trim($data['description'] ?? '');
        $displayOrder = (int)($data['display_order'] ?? 0);
        $showOnHome = isset($data['show_on_home']) ? 1 : 0;

        // Insert category
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (name, slug, status, description, display_order, show_on_home)
            VALUES (:name, :slug, :status, :description, :display_order, :show_on_home)
        ");
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':status' => $status,
            ':description' => $description,
            ':display_order' => $displayOrder,
            ':show_on_home' => $showOnHome,
        ]);
        $id = (int) $this->pdo->lastInsertId();

        // Handle image upload
        if ($imageFile && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
            $filename = $this->uploadImageFile($imageFile, $id);
            if ($filename) {
                $this->updateImage($id, $filename);
            }
        }
        return $id;
    }

    public function update(int $id, array $data, ?array $imageFile): bool
    {
        $category = $this->getById($id);
        if (!$category) {
            return false;
        }

        $name = trim($data['name']);
        $slug = $data['slug'] ?? $this->generateSlug($name);
        $status = $data['status'] ?? 'Active';
        $description = trim($data['description'] ?? '');
        $displayOrder = (int)($data['display_order'] ?? 0);
        $showOnHome = isset($data['show_on_home']) ? 1 : 0;

        // Update category
        $stmt = $this->pdo->prepare("
            UPDATE categories
            SET name = :name, slug = :slug, status = :status,
                description = :description, display_order = :display_order,
                show_on_home = :show_on_home
            WHERE id = :id
        ");
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':slug' => $slug,
            ':status' => $status,
            ':description' => $description,
            ':display_order' => $displayOrder,
            ':show_on_home' => $showOnHome,
        ]);

        // Handle image upload
        if ($imageFile && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
            // Delete old image
            if ($category['image']) {
                $this->deleteImageFile($category['image']);
            }
            $filename = $this->uploadImageFile($imageFile, $id);
            if ($filename) {
                $this->updateImage($id, $filename);
            }
        }
        return true;
    }

    private function updateImage(int $id, string $filename): void
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET image = :image WHERE id = :id");
        $stmt->execute([':image' => $filename, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $category = $this->getById($id);
        if (!$category) {
            return false;
        }
        // Delete image if exists
        if ($category['image']) {
            $this->deleteImageFile($category['image']);
        }
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function toggleStatus(int $id): bool
    {
        $category = $this->getById($id);
        if (!$category) {
            return false;
        }
        $newStatus = $category['status'] === 'Active' ? 'Inactive' : 'Active';
        $stmt = $this->pdo->prepare("UPDATE categories SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $newStatus, ':id' => $id]);
    }

    public function hasProducts(int $categoryId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = :category_id");
        $stmt->execute([':category_id' => $categoryId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM categories");
        return (int) $stmt->fetchColumn();
    }

    public function countActive(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM categories WHERE status = 'Active'");
        return (int) $stmt->fetchColumn();
    }

    public function countInactive(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM categories WHERE status = 'Inactive'");
        return (int) $stmt->fetchColumn();
    }

    public function countUsedCategories(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(DISTINCT category_id) FROM products WHERE category_id IS NOT NULL");
        return (int) $stmt->fetchColumn();
    }

    public function countHomepageCategories(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM categories WHERE show_on_home = 1");
        return (int) $stmt->fetchColumn();
    }

    public function getActiveCategories(): array
    {
        $stmt = $this->pdo->query("SELECT id, name FROM categories WHERE status = 'Active' ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getHomepageCategories(): array
    {
        $stmt = $this->pdo->query("
            SELECT id, name, slug, description, image
            FROM categories
            WHERE status = 'Active' AND show_on_home = 1
            ORDER BY display_order ASC, id ASC
        ");
        return $stmt->fetchAll();
    }

    public function generateSlug(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }
}