/**
 * topup-page.js – for topups.php
 * Handles product grid rendering, filtering, modal with WhatsApp pre‑fill, and copy.
 */

// Helper: escape HTML
function escapeHtml(str) {
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Format price
function formatPrice(price) {
    return 'Rs. ' + parseFloat(price).toFixed(2);
}

// Toast notification
function showToast(message) {
    let toast = document.querySelector('.toast-notification');
    if (toast) toast.remove();
    toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

// Copy Esewa number – works for any .copy-esewa-btn
document.addEventListener('click', function(e) {
    if (e.target.closest('.copy-esewa-btn')) {
        const btn = e.target.closest('.copy-esewa-btn');
        const number = btn.dataset.number;
        if (number) {
            navigator.clipboard.writeText(number);
            showToast('✓ Esewa number copied!');
        }
    }
});

// Main logic
document.addEventListener('DOMContentLoaded', function() {
    const allProducts = window.topupData?.all || [];
    const ffProducts = window.topupData?.freefire || [];
    const pubgProducts = window.topupData?.pubg || [];

    const grid = document.getElementById('topupsGrid');
    if (!grid) return;

    const filterTabs = document.querySelectorAll('.topups-tab');
    let currentFilter = 'all';

    // Render products into grid
    function renderProducts(products) {
        grid.innerHTML = '';
        products.forEach(prod => {
            const card = document.createElement('a');
            card.className = 'topup-card';
            card.setAttribute('data-product-id', prod.id);
            const imgSrc = (prod.first_image && prod.first_image !== 'assets/images/placeholder.png.svg')
                ? prod.first_image
                : 'assets/images/placeholder.png';
            card.innerHTML = `
                <div class="topup-card-img">
                    <img src="${imgSrc}" alt="${escapeHtml(prod.name)}" loading="lazy">
                </div>
                <div class="topup-card-body">
                    <h3 class="topup-card-title">${escapeHtml(prod.name)}</h3>
                    <p class="topup-card-price">${formatPrice(prod.price)}</p>
                </div>
            `;
            grid.appendChild(card);
        });
        attachModalListeners();
    }

    // Modal elements (using the home‑page modal structure)
    const modal = document.getElementById('productModal');
    const modalImg = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalPriceElem = document.getElementById('modalPrice');
    const whatsappBtn = document.getElementById('whatsappBtn');
    const modalClose = document.querySelector('.modal-close');

    function attachModalListeners() {
        const cards = document.querySelectorAll('.topup-card');
        cards.forEach(card => {
            card.removeEventListener('click', card._modalHandler);
            card._modalHandler = function(e) {
                e.preventDefault();
                const img = this.querySelector('.topup-card-img img').src;
                const name = this.querySelector('.topup-card-title').innerText;
                const priceText = this.querySelector('.topup-card-price').innerText;
                if (modalImg) modalImg.src = img;
                if (modalTitle) modalTitle.innerText = name;
                if (modalPriceElem) modalPriceElem.innerText = priceText;
                if (modal) modal.style.display = 'flex';
                const priceNumber = priceText.replace('Rs. ', '');
                const msg = `Hello, I have paid Rs. ${priceNumber} for ${name}. My UID is: `;
                if (whatsappBtn) {
                    whatsappBtn.href = `https://wa.me/9847956550?text=${encodeURIComponent(msg)}`;
                }
            };
            card.addEventListener('click', card._modalHandler);
        });
    }

    // Close modal
    if (modalClose) {
        modalClose.addEventListener('click', () => {
            if (modal) modal.style.display = 'none';
        });
    }
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });

    // Filter tabs
    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentFilter = tab.dataset.filter;
            if (currentFilter === 'all') renderProducts(allProducts);
            else if (currentFilter === 'freefire') renderProducts(ffProducts);
            else if (currentFilter === 'pubg') renderProducts(pubgProducts);
        });
    });

    // Initial render
    renderProducts(allProducts);
});