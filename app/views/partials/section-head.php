<?php
if (!defined('DMC_APP')) { http_response_code(403); exit('Forbidden'); }
$title     = $title     ?? '';
$link      = $link      ?? '';
$link_text = $link_text ?? 'View all';
?>
<div class="section-head">
  <h2 class="section-title"><?= htmlspecialchars($title) ?></h2>
  <?php if ($link): ?>
    <a href="<?= htmlspecialchars($link) ?>" class="section-link"><?= htmlspecialchars($link_text) ?> →</a>
  <?php endif; ?>
</div>