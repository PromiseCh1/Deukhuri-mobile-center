<?php if (!defined('DMC_APP')) { http_response_code(403); exit('Forbidden'); } ?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">

      <div class="footer-col footer-brand">
        <a href="index.php" class="brand brand-footer">
          <span class="brand-mark">DMC</span>
          <span class="brand-text">Deukhuri Mobile Center</span>
        </a>
        <p class="footer-tagline">
          Mobiles, electronics, components and game top-ups —
          serving Lamahi, Deukhuri from one trusted local store.
        </p>
        <div class="footer-social">
          <a href="https://www.facebook.com/share/g/1AmedkxrWE/" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="https://wa.me/9847956550?text=Hello%2C%20I%27m%20interested%20in%20your%20products" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Shop</h4>
        <ul>
          <li><a href="mobiles.php">Mobiles</a></li>
          <li><a href="parts.php">Parts</a></li>
          <li><a href="topups.php">Game Top-ups</a></li>
          <li><a href="index.php">All Products</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Customer</h4>
        <ul>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="contact.php">Store Location</a></li>
          <li><a href="https://wa.me/9847956550" target="_blank" rel="noopener">WhatsApp Support</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Contact</h4>
        <ul class="footer-contact">
          <li><i class="fa-solid fa-phone" aria-hidden="true"></i> <a href="tel:+9779847956550">+977 9847956550</a></li>
          <li><i class="fa-solid fa-envelope" aria-hidden="true"></i> <a href="mailto:msc.np67@gmail.com">msc.np67@gmail.com</a></li>
          <li><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Lamahi, Deukhuri, Dang</li>
          <li><i class="fa-regular fa-clock" aria-hidden="true"></i> Sun–Fri, 10:00–19:00</li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> Deukhuri Mobile Center. All rights reserved.</p>
      <p class="footer-bottom-note">Prices and stock subject to change. Confirm availability on WhatsApp.</p>
    </div>
  </div>
</footer>

<a class="wa-float" href="https://wa.me/9847956550?text=Hello%2C%20I%27m%20interested%20in%20your%20products" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <i class="fa-brands fa-whatsapp"></i>
</a>

</body>
</html>