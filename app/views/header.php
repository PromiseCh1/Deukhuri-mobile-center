<?php
if (!defined('DMC_APP')) { http_response_code(403); exit('Forbidden'); }
$current = basename($_SERVER['PHP_SELF']);

$DMC_PHONE = '9847956550';
$DMC_WA    = 'https://wa.me/9847956550?text=' . rawurlencode("Hello, I'm interested in your products");
$DMC_EMAIL = 'msc.np67@gmail.com';

// Nav is data-driven. Add/remove items here later — markup is fixed.
$nav_items = [
    ['href' => 'index.php',       'label' => 'Home',        'file' => 'index.php'],
    ['href' => 'mobiles.php',     'label' => 'Mobiles',     'file' => 'mobiles.php'],
    ['href' => 'electronics.php', 'label' => 'Electronics', 'file' => 'electronics.php'],
    ['href' => 'components.php',  'label' => 'Components',  'file' => 'components.php'],
    ['href' => 'topups.php',      'label' => 'Top-ups',     'file' => 'topups.php'],
    ['href' => 'contact.php',     'label' => 'Contact',     'file' => 'contact.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — Deukhuri Mobile Center' : 'Deukhuri Mobile Center' ?></title>
  <meta name="description" content="Shop mobiles, electronics, components and game top-ups at Deukhuri Mobile Center, Lamahi.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/variables.css">
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/components/buttons.css">
  <link rel="stylesheet" href="assets/css/components/cards.css">
  <link rel="stylesheet" href="assets/css/components/modal.css">
  <link rel="stylesheet" href="assets/css/layout.css">
  <?php if (!empty($page_css)): ?>
    <link rel="stylesheet" href="assets/css/<?= htmlspecialchars($page_css) ?>">
  <?php endif; ?>
  <link rel="stylesheet" href="assets/css/responsive.css">

  <script src="assets/js/main.js" defer></script>
  <script src="assets/js/search.js" defer></script>
    <script src="assets/js/quick-view.js" defer></script>
    <link rel="stylesheet" href="assets/css/components/quick-view.css">
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" id="siteHeader">
  <div class="header-top">
    <div class="container header-top-inner">

      <a href="index.php" class="brand" aria-label="Deukhuri Mobile Center home">
        <span class="brand-mark" aria-hidden="true">DMC</span>
        <span class="brand-text">Deukhuri Mobile Center</span>
      </a>

      <form class="header-search" action="search.php" method="get" role="search">
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        <input type="search" name="q" placeholder="Search mobiles, chargers, top-ups..." aria-label="Search products" autocomplete="off">
        <button type="submit" class="search-submit">Search</button>
      </form>

      <div class="header-actions">
        <a href="<?= htmlspecialchars($DMC_WA) ?>" class="icon-action whatsapp-action" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
          <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
          <span class="icon-action-label">WhatsApp</span>
        </a>
        <a href="contact.php" class="icon-action" aria-label="Visit store">
          <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
          <span class="icon-action-label">Store</span>
        </a>
        <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobileNav">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </div>

  <nav class="header-nav" aria-label="Main navigation">
    <div class="container">
      <ul class="nav-list">
        <?php foreach ($nav_items as $item): ?>
          <li>
            <a href="<?= htmlspecialchars($item['href']) ?>"
               class="nav-link <?= $current === $item['file'] ? 'is-active' : '' ?>">
              <?= htmlspecialchars($item['label']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </nav>

  <div class="mobile-nav" id="mobileNav" hidden>
    <form class="mobile-search" action="search.php" method="get" role="search">
      <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
      <input type="search" name="q" placeholder="Search products..." aria-label="Search products">
    </form>

    <ul class="mobile-nav-list">
      <?php foreach ($nav_items as $item): ?>
        <li>
          <a href="<?= htmlspecialchars($item['href']) ?>"
             class="<?= $current === $item['file'] ? 'is-active' : '' ?>">
            <?= htmlspecialchars($item['label']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <a class="mobile-nav-wa" href="<?= htmlspecialchars($DMC_WA) ?>" target="_blank" rel="noopener">
      <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
    </a>
  </div>
</header>

<main id="main" class="site-main">