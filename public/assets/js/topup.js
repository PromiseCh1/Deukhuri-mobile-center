/**
 * topup.js – Handles dynamic top‑up products, filtering, modal, and Esewa copy.
 * Relies on window.topupData injected by home.php.
 */

// Global helper functions (used by copy feature)
function openModal() {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Self‑executing function to avoid polluting global scope
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        // Retrieve product data from global variable (set in home.php)
        const allProducts = window.topupData?.all || [];
        const ffProducts = window.topupData?.freefire || [];
        const pubgProducts = window.topupData?.pubg || [];

        const visibleGrid = document.getElementById('visibleGrid');
        if (!visibleGrid) return;

        const filterBtns = document.querySelectorAll('.filter-btn');
        let currentFilter = 'all';

        // Helper: escape HTML to prevent XSS
        function escapeHtml(str) {
            return str.replace(/[&<>]/g, function (m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        // Format price as "Rs. X.XX"
        function formatPrice(price) {
            return 'Rs. ' + parseFloat(price).toFixed(2);
        }

        // Render product cards into the grid
        function renderProducts(products) {
            visibleGrid.innerHTML = '';
            const total = products.length;

            products.forEach(prod => {
                const card = document.createElement('article');
                card.className = 'product-card';
                const category = prod.name.toLowerCase().includes('freefire') ? 'freefire' : 'pubg';
                card.setAttribute('data-category', category);
                card.setAttribute('data-product-id', prod.id);

                // Choose image (fallback to placeholder)
                const imgSrc = prod.first_image && prod.first_image !== 'assets/images/placeholder.png.svg'
                    ? prod.first_image
                    : 'assets/images/placeholder.png';

                card.innerHTML = `
                    <div class="product-img">
                        <img src="${imgSrc}" alt="${escapeHtml(prod.name)}" loading="lazy">
                        <span class="badge badge-in">In Stock</span>
                    </div>
                    <div class="product-info">
                        <h3>${escapeHtml(prod.name)}</h3>
                        <p class="price">${formatPrice(prod.price)}</p>
                    </div>
                `;
                visibleGrid.appendChild(card);
            });

            attachModalListeners();

            // Enable horizontal scroll mode if more than 4 products
            if (total > 4) {
                visibleGrid.classList.add('horizontal-scroll-mode');
            } else {
                visibleGrid.classList.remove('horizontal-scroll-mode');
            }
        }

        // Modal DOM elements
        const modal = document.getElementById('productModal');
        if (!modal) return;

        const modalImg = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        const modalPriceElem = document.getElementById('modalPrice');
        const whatsappBtn = document.getElementById('whatsappBtn');
        const modalClose = document.querySelector('.modal-close');

        // Attach click listeners to each product card to open modal
        function attachModalListeners() {
            const cards = document.querySelectorAll('#visibleGrid .product-card');
            cards.forEach(card => {
                // Remove previous listener to avoid duplicates
                card.removeEventListener('click', card._modalHandler);
                card._modalHandler = function () {
                    const img = this.querySelector('.product-img img').src;
                    const name = this.querySelector('.product-info h3').innerText;
                    const priceText = this.querySelector('.price').innerText;

                    if (modalImg) modalImg.src = img;
                    if (modalTitle) modalTitle.innerText = name;
                    if (modalPriceElem) modalPriceElem.innerText = priceText;

                    modal.style.display = 'flex';   // open modal

                    const priceNumber = priceText.replace('Rs. ', '');
                    const msg = `Hello, I have paid Rs. ${priceNumber} for ${name}. My UID is: `;
                    if (whatsappBtn) {
                        whatsappBtn.href = `https://wa.me/9847956550?text=${encodeURIComponent(msg)}`;
                    }
                };
                card.addEventListener('click', card._modalHandler);
            });
        }

        // Close modal when clicking × or outside
        if (modalClose) {
            modalClose.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }
        window.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });

        // Filter button logic
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.dataset.filter;

                if (currentFilter === 'all') {
                    renderProducts(allProducts);
                } else if (currentFilter === 'freefire') {
                    renderProducts(ffProducts);
                } else {
                    renderProducts(pubgProducts);
                }
            });
        });

        // Initial render (All products)
        renderProducts(allProducts);
    });
})();

// Show a temporary toast notification (used by copy button)
function showToast(message) {
    let toast = document.querySelector('.toast-notification');
    if (toast) toast.remove();

    toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 2000);
}

// Copy Esewa number to clipboard when copy button is clicked
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('copy-esewa-btn')) {
        const number = e.target.dataset.number;
        navigator.clipboard.writeText(number);
        showToast('✓ Esewa number copied!');
    }
});