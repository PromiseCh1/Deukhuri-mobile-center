<?php
$page_css = "topups.css";
// $extra_css = "home.css";
require_once 'includes/functions.php';
include 'includes/header.php';

// Get all top‑up products (category_id = 4)
$allTopups = getProductsByCategory(4);

$freefire = array_values(array_filter($allTopups, function ($p) {
  return stripos($p['name'], 'FreeFire') !== false;
}));
$pubg = array_values(array_filter($allTopups, function ($p) {
  return stripos($p['name'], 'PUBG') !== false;
}));
$allOrdered = array_merge($freefire, $pubg);
?>

<div class="topups-page">

  <!-- Hero -->
  <section class="topups-hero topups-container">
    <h1>Game Top-ups</h1>
    <p>Instant FreeFire Diamonds and PUBG UC. Pay via Esewa and send proof on WhatsApp to receive credits within 1 hour.</p>
  </section>

  <!-- Filter tabs -->
  <div class="topups-filters">
    <button class="topups-tab active" data-filter="all">All</button>
    <button class="topups-tab" data-filter="freefire">FreeFire</button>
    <button class="topups-tab" data-filter="pubg">PUBG</button>
  </div>

  <!-- Product grid -->
  <section class="topups-container">
    <div class="topups-grid" id="topupsGrid"></div>
  </section>

  <!-- How it works (with custom icons) -->
  <section class="topups-how">
    <div class="topups-container">
      <h2>How it works</h2>
      <div class="topups-steps">
        <div class="topups-step">
          <div class="topups-step-icon">
            <img src="assets/images/esewa_icon.png" alt="Esewa" class="step-icon-img">
          </div>
          <h3>1. Pay with Esewa</h3>
          <p>Scan QR or send to our official Esewa number: <strong>9847956550</strong>.</p>
        </div>
        <div class="topups-step">
          <div class="topups-step-icon camera-icon">
            <i class="fas fa-camera"></i>
          </div>
          <h3>2. Screenshot + UID</h3>
          <p>Take a screenshot of the payment and write your Game UID.</p>
        </div>
        <div class="topups-step">
          <div class="topups-step-icon whatsapp-icon">
            <i class="fab fa-whatsapp"></i>
          </div>
          <h3>3. Send on WhatsApp</h3>
          <p>Send everything to our WhatsApp. Top‑up arrives within 1 hour.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- WhatsApp CTA (matches contact page) -->
  <section class="whatsapp-cta">
    <div class="cta-content">
      <h2>Prefer WhatsApp?</h2>
      <p>Message us directly for quick replies about product availability.</p>
      <a href="https://wa.me/9847956550?text=Hello%2C%20I%20want%20to%20top%20up"
        class="btn-whatsapp-large"
        target="_blank"
        rel="noopener noreferrer">
        <i class="fa-brands fa-whatsapp"></i> Message on WhatsApp
      </a>
    </div>
  </section>

</div>

<!-- Modal – same as home.php -->
<div id="productModal" class="modal-overlay" style="display: none;">
  <div class="modal-container">
    <button class="modal-close">&times;</button>
    <div class="modal-content">
      <div class="modal-row">
        <div class="modal-image">
          <img id="modalImage" src="" alt="Product">
        </div>
        <div class="modal-header-text">
          <h2 id="modalTitle"></h2>
          <p class="modal-price" id="modalPrice"></p>
        </div>
      </div>
      <div class="payment-steps">
        <h3>📦 How to complete your purchase</h3>
        <ol>
          <li><strong>Pay the amount</strong> using Esewa (scan QR or send to number below).</li>
          <li><strong>Take a screenshot</strong> of your payment confirmation.</li>
          <li><strong>Send the screenshot</strong> along with your Game <strong>UID</strong> to our WhatsApp <strong>9847956550</strong>.</li>
          <li>Your top‑up will be <strong>processed within 1 hour</strong> after payment verification.</li>
        </ol>
      </div>
      <div class="esewa-box">
        <div class="qr-placeholder">
          <img src="assets/images/esewa_qr.png" alt="Esewa QR Code"
            onerror="this.src='https://placehold.co/200x200/f8fafc/1e293b?text=Esewa+QR'">
        </div>
        <div class="esewa-details">
          <p><i class="fas fa-phone-alt"></i> <strong>Esewa:</strong> 9847956550
            <button class="copy-esewa-btn" data-number="9847956550">Copy</button>
          </p>
          <p><i class="fas fa-user"></i> <strong>Name:</strong> Mahesh Chaudhary</p>
        </div>
      </div>
      <div style="text-align: center;">
        <a href="#" id="whatsappBtn" class="btn-whatsapp-modal" target="_blank">
          <i class="fa-brands fa-whatsapp"></i> Send Payment Proof
        </a>
      </div>
      <p class="warning-note">⚠️ Pay only to the number above. We are not responsible for payments to other numbers.</p>
    </div>
  </div>
</div>

<script>
  window.topupData = {
    all: <?= json_encode($allOrdered) ?>,
    freefire: <?= json_encode($freefire) ?>,
    pubg: <?= json_encode($pubg) ?>
  };
</script>
<script src="assets/js/topup-page.js" defer></script>

<?php include 'includes/footer.php'; ?>