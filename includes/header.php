<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Deukhuri Mobile &amp; Computer Repairing Center</title>

  <!-- Design System & Components -->
  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/components/buttons.css">
  <link rel="stylesheet" href="assets/css/components/cards.css">
  <link rel="stylesheet" href="assets/css/components/modal.css">

  <!-- Layout & Utilities -->
  <link rel="stylesheet" href="assets/css/header_footer.css">
  <link rel="stylesheet" href="assets/css/responsive.css">

  <!-- Page-specific CSS (if defined) -->
  <?php if (isset($page_css) && !empty($page_css)): ?>
    <link rel="stylesheet" href="assets/css/<?= htmlspecialchars($page_css) ?>">
  <?php endif; ?>

  <!-- Font Awesome & Google Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

  <!-- JavaScript (defer) -->
  <script src="assets/js/theme.js" defer></script>
  <script src="assets/js/main.js" defer></script>
  <script src="assets/js/search.js" defer></script>
  <?php if (basename($_SERVER['PHP_SELF']) !== 'home.php'): ?>
    <script src="assets/js/modal.js" defer></script>
  <?php endif; ?>
</head>

<body>
  <header class="site-header">
    <div class="header-inner">
      <a href="home.php" class="logo">
        <i class="fa-solid fa-microchip"></i>
        <span>Deukhuri<span class="logo-accent"> Mobile <br>Repairing Center</span></span>
      </a>
      <nav class="main-nav" id="main-nav">
        <a href="home.php" class="<?= $current === 'home.php' ? 'active' : '' ?>">Home</a>
        <a href="mobiles.php" class="<?= $current === 'mobiles.php' ? 'active' : '' ?>">Mobiles</a>
        <a href="parts.php" class="<?= $current === 'parts.php' ? 'active' : '' ?>">Parts</a>
        <a href="vapes.php" class="<?= $current === 'vapes.php' ? 'active' : '' ?>">Vapes</a>
        <a href="topups.php" class="<?= $current === 'topups.php' ? 'active' : '' ?>">Top‑ups</a>
        <a href="contact.php" class="<?= $current === 'contact.php' ? 'active' : '' ?>">Contact</a>
      </nav>
      <div class="header-actions">
        <input type="text" id="search-desktop" class="search-input" placeholder="Search products..." aria-label="Search">
        <button id="hamburger" class="icon-btn hamburger" aria-label="Menu">☰</button>
        <button id="theme-toggle" class="icon-btn" aria-label="Switch theme">🌙</button>
      </div>
    </div>
    <div class="mobile-search">
      <input type="text" id="search-mobile" class="search-input" placeholder="Search products..." aria-label="Search">
    </div>
  </header>
  <main class="container">