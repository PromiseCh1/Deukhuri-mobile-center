<?php
if (!defined('DMC_APP')) { http_response_code(403); exit('Forbidden'); }
// Expects: $p (product row). Uses sale_price if column exists.
$saleRaw    = $p['sale_price'] ?? null;
$isSale     = $saleRaw !== null && (float)$saleRaw > 0 && (float)$saleRaw < (float)$p['price'];
$finalPrice = $isSale ? (float)$saleRaw : (float)$p['price'];
$href       = 'product.php?id=' . (int)$p['id'];
?>
<article class="product-card" data-product-id="<?= (int)$p['id'] ?>">
  <a class="product-img" href="<?= htmlspecialchars($href) ?>" aria-label="<?= htmlspecialchars($p['name']) ?>">
    <img src="<?= productImageUrl($p['first_image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
    <?php if (isOutOfStock($p['stock'])): ?>
      <span class="badge badge-out">Out of stock</span>
    <?php elseif ($isSale): ?>
      <span class="badge badge-sale">Sale</span>
    <?php endif; ?>
    <button type="button" class="quick-view" data-product-id="<?= (int)$p['id'] ?>">
      <i class="fa-regular fa-eye" aria-hidden="true"></i>
      <span>Quick view</span>
    </button>
  </a>
  <div class="product-info">
    <?php if (!empty($p['category_name'])): ?>
      <span class="product-cat"><?= htmlspecialchars($p['category_name']) ?></span>
    <?php endif; ?>
    <h3 class="product-name">
      <a href="<?= htmlspecialchars($href) ?>"><?= htmlspecialchars($p['name']) ?></a>
    </h3>
    <p class="product-price">
      <?php if ($isSale): ?>
        <span class="price-sale"><?= formatPrice($finalPrice) ?></span>
        <span class="price-old"><?= formatPrice($p['price']) ?></span>
      <?php else: ?>
        <?= formatPrice($p['price']) ?>
      <?php endif; ?>
    </p>
  </div>
</article>