<?php
/**
 * breadcrumb.php
 * Renders a dynamic breadcrumb trail.
 */
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}

if (empty($breadcrumbs)) {
    return;
}
?>
<nav class="breadcrumb" aria-label="Breadcrumb">
    <ol>
        <?php foreach ($breadcrumbs as $label => $url): ?>
            <?php if ($url): ?>
                <li><a href="<?= $url ?>"><?= htmlspecialchars($label) ?></a></li>
            <?php else: ?>
                <li><span><?= htmlspecialchars($label) ?></span></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>