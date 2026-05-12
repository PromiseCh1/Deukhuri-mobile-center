<?php
$page_css = "";
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<div class="maintenance-page">
    <div class="maintenance-content">
        <div class="maintenance-icon">
            <i class="fas fa-smoking"></i>
        </div>
        <h1>Vapes Section</h1>
        <h2>Coming Soon</h2>
        <p>We are stocking premium vapes, pods, and accessories. Stay tuned for the latest products and flavours.</p>
        <a href="home.php" class="btn btn-primary">← Back to Home</a>
    </div>
</div>

<style>
    .maintenance-page {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 4rem 1rem;
    }
    .maintenance-content {
        max-width: 550px;
        margin: 0 auto;
    }
    .maintenance-icon {
        font-size: 4rem;
        color: var(--theme-accent, #2563eb);
        margin-bottom: 1.5rem;
    }
    .maintenance-content h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-dark, #1e293b);
    }
    .maintenance-content h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin-bottom: 1rem;
        color: var(--text-muted, #475569);
    }
    .maintenance-content p {
        font-size: 1rem;
        color: var(--text-muted, #475569);
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    .btn-primary {
        background: #000000;
        color: white;
        padding: 0.75rem 1.8rem;
        border-radius: 40px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }
    .btn-primary:hover {
        background: var(--theme-accent, #2563eb);
        transform: translateY(-2px);
    }
</style>

<?php include 'includes/footer.php'; ?>