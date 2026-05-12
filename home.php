<?php

/**
 * home.php – Main landing page for Deukhuri Shop
 * 
 * Displays hero section, category grid, dynamic top‑up products (FreeFire / PUBG),
 * and a modal for WhatsApp + Esewa payment flow.
 * All product rendering and filtering is handled by assets/js/topup.js
 */

// Load page‑specific CSS and required files
$page_css = "home.css";
require_once 'includes/functions.php';
include 'includes/header.php';

// ─────────────────────────────────────────────────────────────────
// 1. Fetch and prepare top‑up product data from database
// ─────────────────────────────────────────────────────────────────

// Get all categories (not used directly on home page, but available)
$categories = getAllCategories();

// Get all products in category "Topups" (category_id = 4)
$allTopups = getProductsByCategory(4);

// Split into FreeFire and PUBG based on product name
$freefire = array_values(array_filter($allTopups, function ($p) {
    return stripos($p['name'], 'FreeFire') !== false;
}));
$pubg = array_values(array_filter($allTopups, function ($p) {
    return stripos($p['name'], 'PUBG') !== false;
}));

// Combined list for the "All" filter (FreeFire first, then PUBG)
$allOrdered = array_merge($freefire, $pubg);
?>

<!-- 2. Hero Section -->
<section class="hero">
    <div class="hero-text">
        <h1>Mobiles, parts &amp; repairs — <br>done right.</h1>
        <p>Trusted service for phones, components and vapes. Browse our selection, then message us on WhatsApp to confirm availability.</p>
        <div class="hero-cta">
            <a href="mobiles.php" class="btn btn-primary">Shop Mobiles</a>
            <a href="contact.php" class="btn btn-secondary">Visit Store</a>
        </div>
    </div>
</section>

<!-- 3. Category Grid (static images) -->
<section class="section category-grid-section">
    <h2 class="section-title">Browse Categories</h2>
    <div class="category-grid">
        <a href="mobiles.php" class="category-card">
            <div class="category-img"><img src="assets/images/browse_products/browse_mobile.png" alt="Mobiles" loading="lazy"></div>
            <h3>Mobiles</h3>
            <p>New & pre‑owned phones</p>
        </a>
        <a href="parts.php" class="category-card">
            <div class="category-img"><img src="assets/images/browse_products/browse_parts.png" alt="Parts" loading="lazy"></div>
            <h3>Parts</h3>
            <p>Screens, batteries, more</p>
        </a>
        <a href="vapes.php" class="category-card">
            <div class="category-img"><img src="assets/images/browse_products/browse_vape.png" alt="Vapes" loading="lazy"></div>
            <h3>Vapes</h3>
            <p>Pods, mods &amp; accessories</p>
        </a>
        <a href="topups.php" class="category-card">
            <div class="category-img"><img src="assets/images/browse_products/browse_topup.png" alt="Top‑ups" loading="lazy"></div>
            <h3>Top‑ups</h3>
            <p>Free Fire, PUBG, COC, Steam</p>
        </a>
    </div>
</section>

<!-- 4. Game Top‑ups Section – dynamic, controlled by topup.js -->
<section id="topups-section" class="section">
    <h2 class="section-title">🎮 Game Top‑ups</h2>
    <div class="topup-filters">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="freefire">FreeFire</button>
        <button class="filter-btn" data-filter="pubg">PUBG</button>
    </div>
    <div class="product-grid visible-grid" id="visibleGrid"></div>
    <!-- "View More" button – toggles horizontal scroll mode (visible only when >4 products) -->
    <div class="view-more-container" style="text-align: center; margin-top: 1rem;">
        <button id="viewMoreBtn" class="btn btn-outline">View More →</button>
    </div>
</section>

<!-- 5. Modal – used for all products (populated by topup.js) -->
<div id="productModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <button class="modal-close">&times;</button>
        <div class="modal-content">
            <!-- Row: product image + title + price -->
            <div class="modal-row">
                <div class="modal-image">
                    <img id="modalImage" src="" alt="Product">
                </div>
                <div class="modal-header-text">
                    <h2 id="modalTitle"></h2>
                    <p class="modal-price" id="modalPrice"></p>
                </div>
            </div>

            <!-- Payment instructions -->
            <div class="payment-steps">
                <h3>📦 How to complete your purchase</h3>
                <ol>
                    <li><strong>Pay the amount</strong> using Esewa (scan QR or send to number below).</li>
                    <li><strong>Take a screenshot</strong> of your payment confirmation.</li>
                    <li><strong>Send the screenshot</strong> along with your Game <strong>UID</strong> to our WhatsApp <strong>9847956550</strong>.</li>
                    <li>Your top‑up will be <strong>processed within 1 hour</strong> after payment verification.</li>
                </ol>
            </div>

            <!-- Esewa payment box with QR and copy button -->
            <div class="esewa-box">
                <div class="qr-placeholder">
                    <img src="assets/images/esewa_qr.png" alt="Esewa QR Code"
                        onerror="this.src='https://placehold.co/200x200/f8fafc/1e293b?text=Esewa+QR'">
                </div>
                <div class="esewa-details">
                    <p><i class="fas fa-phone-alt"></i> <strong>Esewa:</strong> 9847956550
                        <button class="copy-esewa-btn" data-number="9847956550" aria-label="Copy Esewa number">
                            <i class="far fa-copy"></i>
                        </button>
                    </p>
                    <p><i class="fas fa-user"></i> <strong>Name:</strong> Mahesh Chaudhary</p>
                </div>
            </div>

            <!-- WhatsApp button pre‑filled with product details -->
            <div style="text-align: center;">
                <a href="#" id="whatsappBtn" class="btn-whatsapp-modal" target="_blank">
                    <i class="fa-brands fa-whatsapp"></i> Send Payment Proof
                </a>
            </div>
            <p class="warning-note">⚠️ Pay only to the number above. We are not responsible for payments to other numbers.</p>
        </div>
    </div>
</div>

<!-- 6. Inject PHP product data into JavaScript (used by topup.js) -->
<script>
    window.topupData = {
        all: <?= json_encode($allOrdered) ?>,
        freefire: <?= json_encode($freefire) ?>,
        pubg: <?= json_encode($pubg) ?>
    };
</script>
<script src="assets/js/topup.js" defer></script>

<!-- 7. Why Choose Us – static features section -->
<section class="section about">
    <h2 class="section-title">Why Choose Us?</h2>
    <div class="features-grid">
        <div class="feature">
            <div class="feature-img"><img src="assets/images/browse_products/why_trusted_since.png" alt="Trusted Since 2015" loading="lazy"></div>
            <h3>Trusted Since 2015</h3>
            <p>10+ years of honest service in Deukhuri.</p>
        </div>
        <div class="feature">
            <div class="feature-img"><img src="assets/images/browse_products/why_genuine_parts.png" alt="Genuine Parts" loading="lazy"></div>
            <h3>Genuine Parts</h3>
            <p>100% original spares with warranty.</p>
        </div>
        <div class="feature">
            <div class="feature-img"><img src="assets/images/browse_products/why_best_price.png" alt="Best Prices" loading="lazy"></div>
            <h3>Best Prices</h3>
            <p>Affordable rates on mobiles & repairs.</p>
        </div>
        <div class="feature">
            <div class="feature-img"><img src="assets/images/browse_products/why_flash.png" alt="Instant Top-ups" loading="lazy"></div>
            <h3>Instant Top‑ups</h3>
            <p>Game credits delivered in minutes.</p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>