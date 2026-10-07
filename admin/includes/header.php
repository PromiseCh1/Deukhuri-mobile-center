<?php
/**
 * header.php
 * Admin panel HTML head and wrapper opening.
 */
if (!defined('APP_START')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Direct access not allowed.');
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> – <?= $pageTitle ?? 'Admin' ?></title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Font (optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/css/admin.css">
    <!-- Page-specific CSS -->
    <?php if (isset($page_css) && !empty($page_css)): ?>
        <link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/css/<?= htmlspecialchars($page_css) ?>">
    <?php endif; ?>
</head>
<body>
<div class="admin-wrapper">