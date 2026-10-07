<?php
/**
 * footer.php
 * Admin panel footer – closes the main content, admin-main, and wrapper.
 */
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}
?>
        </main> <!-- .admin-content -->
    </div> <!-- .admin-main -->
</div> <!-- .admin-wrapper -->

<!-- Footer -->
<footer class="admin-footer">
    <div class="footer-left">
        &copy; <?= date('Y') ?> <?= SITE_NAME ?> – All rights reserved.
    </div>
    <div class="footer-right">
        Version <?= APP_VERSION ?>
    </div>
</footer>

<!-- JavaScript -->
<script src="<?= ADMIN_URL ?>/assets/js/admin.js"></script>
<?php if (isset($page_js) && !empty($page_js)): ?>
    <script src="<?= ADMIN_URL ?>/assets/js/<?= htmlspecialchars($page_js) ?>"></script>
<?php endif; ?>
</body>
</html>