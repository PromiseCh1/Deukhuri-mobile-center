<?php
$page_css = "contact.css";
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<section class="contact-hero">
    <h1>Get in touch</h1>
    <p class="subtitle">Have a question or want to confirm availability? Reach out anytime.</p>
</section>

<section class="contact-cards">
    <div class="card-grid">
        <div class="contact-card">
            <div class="card-icon"><i class="fas fa-phone-alt"></i></div>
            <h3>Phone</h3>
            <p><a href="tel:+9779847956550">+977 9847956550</a></p>
        </div>
        <div class="contact-card">
            <div class="card-icon"><i class="far fa-envelope"></i></div>
            <h3>Email</h3>
            <p><a href="mailto:msc.np67@gmail.com">msc.np67@gmail.com</a></p>
        </div>
        <div class="contact-card">
            <div class="card-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h3>Address</h3>
            <p>Dang, Lamahi Deukhiri</p>
        </div>
    </div>
</section>

<section class="map-section">
    <div class="section-header">
        <h2>Find us on the map</h2>
    </div>
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3539.404490235301!2d82.52231!3d27.87319!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjfCsDUyJzIzLjUiTiA4MsKwMzEnMjAuMyJF!5e0!3m2!1sen!2snp!4v1747065600000!5m2!1sen!2snp" 
            width="100%" 
            height="400" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</section>

<section class="whatsapp-cta">
    <div class="cta-content">
        <h2>Prefer WhatsApp?</h2>
        <p>Message us directly for quick replies about product availability.</p>
        <a href="https://wa.me/9847956550?text=Hello%2C%20I%27m%20interested%20in%20your%20products" 
           class="btn-whatsapp-large" 
           target="_blank" 
           rel="noopener noreferrer">
            <i class="fa-brands fa-whatsapp"></i> Message on WhatsApp
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>