<?php
// Shared template for mobiles.php / parts.php / vapes.php
include __DIR__ . '/header.php';
$products = getProductsByCategory($CATEGORY_ID);
?>
<section class="section">
  <h1 class="section-title"><?= htmlspecialchars($CATEGORY_TITLE) ?></h1>
  <div class="product-grid">
    <?php if (empty($products)): ?>
      <p class="empty">No products in this category yet.</p>
    <?php else: foreach ($products as $p): ?>
      <article class="product-card" data-product-id="<?= (int)$p['id'] ?>" data-name="<?= htmlspecialchars(strtolower($p['name'])) ?>">
        <div class="product-img">
          <img src="<?= productImageUrl($p['first_image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
          <?php if (isOutOfStock($p['stock'])): ?>
            <span class="badge badge-out">Out of Stock</span>
          <?php else: ?>
            <span class="badge badge-in">In Stock</span>
          <?php endif; ?>
        </div>
        <div class="product-info">
          <h3><?= htmlspecialchars($p['name']) ?></h3>
          <p class="price"><?= formatPrice($p['price']) ?></p>
        </div>
      </article>
    <?php endforeach; endif; ?>
  </div>
</section>
<?php include __DIR__ . '/footer.php'; ?>
