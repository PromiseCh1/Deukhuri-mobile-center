<?php
$page_css   = 'pages/home.css';
$page_title = 'Home';
require_once __DIR__ . '/../app/helpers/bootstrap.php';
include __DIR__ . '/../app/views/header.php';

// ── Products ────────────────────────────────────────────────────
$all          = getLatestProducts(12);
$featured     = array_slice($all, 0, 4);
$newArrivals  = array_slice($all, 4, 4);

// ── Top-ups preview ─────────────────────────────────────────────
$allTopups    = getProductsByCategory(4);
$freefire     = array_values(array_filter($allTopups, fn($p) => stripos($p['name'], 'FreeFire') !== false));
$pubg         = array_values(array_filter($allTopups, fn($p) => stripos($p['name'], 'PUBG') !== false));
$topupPreview = array_slice(array_merge($freefire, $pubg), 0, 4);

// ── Ad slots (fill to show) ────────────────────────────────────
$promoBanner = null;
// $promoBanner = ['image' => 'assets/images/banners/promo-1.jpg', 'link' => 'topups.php', 'alt' => 'Free Fire top-up offer'];
?>

<!-- ========== 1. HERO ========== -->
<section class="hero" aria-label="Store introduction">
  <div class="hero-overlay" aria-hidden="true"></div>
  <div class="container hero-content">
    <p class="hero-eyebrow">Deukhuri's local electronics store</p>
    <h1>Shop the latest in mobiles &amp; electronics</h1>
    <p class="hero-sub">Phones, speakers, chargers and components — genuine products, fair prices, quick support across Nepal.</p>
    <div class="hero-cta">
      <a href="#featured" class="btn btn-primary btn-lg">Browse products</a>
      <a href="topups.php" class="btn btn-ghost btn-lg">Game top-ups</a>
    </div>
  </div>
</section>

<!-- ========== 2. FEATURED CATEGORIES ========== -->
<section class="section featured-cats-section">
  <div class="container">
    <?php
      $title = 'Shop by Category';
      $link  = 'mobiles.php';
      $link_text = 'View all';
      include __DIR__ . '/../app/views/partials/section-head.php';
    ?>
    <div class="category-grid">
      <a class="category-tile" href="mobiles.php">
        <img src="assets/images/browse_products/browse_mobile.png" alt="" loading="lazy">
        <div class="category-tile-overlay">
          <h3>Mobiles</h3>
          <p>New &amp; pre-owned phones</p>
        </div>
      </a>
      <a class="category-tile" href="electronics.php">
        <img src="assets/images/browse_products/browse_parts.png" alt="" loading="lazy">
        <div class="category-tile-overlay">
          <h3>Electronics</h3>
          <p>Speakers, chargers &amp; more</p>
        </div>
      </a>
      <a class="category-tile" href="components.php">
        <img src="assets/images/browse_products/browse_parts.png" alt="" loading="lazy">
        <div class="category-tile-overlay">
          <h3>Components</h3>
          <p>Screens, batteries, PCB</p>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ========== 3. FEATURED PRODUCTS ========== -->
<section class="section" id="featured">
  <div class="container">
    <?php
      $title = 'Featured Products';
      $link  = 'mobiles.php';
      $link_text = 'View all';
      include __DIR__ . '/../app/views/partials/section-head.php';
    ?>
    <?php if (empty($featured)): ?>
      <p class="empty-state">No products added yet. Check back soon.</p>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($featured as $p) {
          include __DIR__ . '/../app/views/partials/product-card.php';
        } ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ========== 4. PROMO BANNER ========== -->
<?php if (!empty($promoBanner)): ?>
<section class="section promo-banner-section">
  <div class="container">
    <a class="promo-banner" href="<?= htmlspecialchars($promoBanner['link'] ?? '#') ?>">
      <img src="<?= htmlspecialchars($promoBanner['image']) ?>" alt="<?= htmlspecialchars($promoBanner['alt'] ?? 'Offer') ?>" loading="lazy">
    </a>
  </div>
</section>
<?php endif; ?>

<!-- ========== 5. NEW ARRIVALS ========== -->
<?php if (!empty($newArrivals)): ?>
<section class="section">
  <div class="container">
    <?php
      $title = 'New Arrivals';
      $link  = 'mobiles.php';
      $link_text = 'View all';
      include __DIR__ . '/../app/views/partials/section-head.php';
    ?>
    <div class="product-grid">
      <?php foreach ($newArrivals as $p) {
        include __DIR__ . '/../app/views/partials/product-card.php';
      } ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========== 6. TOP-UP STRIP ========== -->
<?php if (!empty($topupPreview)): ?>
<section class="section topup-preview-section">
  <div class="container">
    <?php
      $title = 'Instant Game Top-ups';
      $link  = 'topups.php';
      $link_text = 'View all';
      include __DIR__ . '/../app/views/partials/section-head.php';
    ?>
    <p class="section-sub">Free Fire diamonds &amp; PUBG UC — pay via Esewa, credited within 1 hour.</p>
    <div class="product-grid">
      <?php foreach ($topupPreview as $p) {
        include __DIR__ . '/../app/views/partials/product-card.php';
      } ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ========== QUICK VIEW MODAL ========== -->
<div class="qv-modal" id="quickViewModal" hidden role="dialog" aria-modal="true" aria-labelledby="qvTitle">
  <div class="qv-backdrop" data-qv-close></div>
  <div class="qv-panel" role="document">
    <button type="button" class="qv-close" data-qv-close aria-label="Close quick view">&times;</button>
    <div class="qv-grid">
      <div class="qv-image"><img id="qvImage" src="" alt=""></div>
      <div class="qv-details">
        <h2 id="qvTitle" class="qv-title"></h2>
        <p id="qvPrice" class="qv-price"></p>
        <div id="qvDesc" class="qv-desc"></div>
        <div id="qvSpecs" class="qv-specs"></div>
        <a id="qvWhatsApp" class="btn btn-primary qv-wa" href="#" target="_blank" rel="noopener">
          <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Enquire on WhatsApp
        </a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../app/views/footer.php'; ?>