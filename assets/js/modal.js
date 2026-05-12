// TODO: replace with your real WhatsApp number
const WHATSAPP_NUMBER = '9779800000000';

document.addEventListener('DOMContentLoaded', () => {
  // Event delegation: any click on a product card opens the modal
  document.body.addEventListener('click', (e) => {
    const card = e.target.closest('.product-card');
    if (!card) return;
    const id = card.dataset.productId;
    if (id) openProductModal(id);
  });
});

async function openProductModal(productId) {
  try {
    const res = await fetch(`product_modal.php?id=${encodeURIComponent(productId)}`);
    if (!res.ok) throw new Error('Failed to load product');
    const p = await res.json();
    renderModal(p);
  } catch (err) {
    console.error(err);
    alert('Could not load product details.');
  }
}

function renderModal(p) {
  closeModal();
  const overlay = document.createElement('div');
  overlay.className = 'modal-overlay';
  overlay.id = 'product-modal';

  const outOfStock = !p.stock || p.stock <= 0;
  const waText = encodeURIComponent(`Hello, I'm interested in "${p.name}"`);
  const waHref = `https://wa.me/${WHATSAPP_NUMBER}?text=${waText}`;

  const imgs = p.images && p.images.length ? p.images : ['assets/images/placeholder.png'];

  overlay.innerHTML = `
    <div class="modal" role="dialog" aria-modal="true" aria-label="${escapeHtml(p.name)}">
      <button class="modal-close" aria-label="Close">✕</button>
      <div class="modal-gallery">
        <img id="modal-main-img" src="${imgs[0]}" alt="${escapeHtml(p.name)}">
        ${imgs.length > 1 ? `
          <div class="modal-thumbs">
            ${imgs.map((src, i) => `<img src="${src}" data-idx="${i}" class="${i===0?'active':''}" alt="thumbnail ${i+1}">`).join('')}
          </div>` : ''}
      </div>
      <div class="modal-body">
        <h2>${escapeHtml(p.name)}</h2>
        <div class="price">${escapeHtml(p.price_formatted || ('Rs. ' + p.price))}</div>
        <div class="stock ${outOfStock ? 'out' : 'in'}">
          ${outOfStock ? 'Out of Stock' : `In Stock (${p.stock})`}
        </div>
        <p>${escapeHtml(p.description || '')}</p>
        ${p.specs_formatted || ''}
        ${outOfStock
          ? `<button class="btn btn-disabled" disabled>Out of Stock</button>`
          : `<a class="btn btn-whatsapp" href="${waHref}" target="_blank" rel="noopener">
               <i class="fa-brands fa-whatsapp"></i> Inquire on WhatsApp
             </a>`}
      </div>
    </div>
  `;

  document.body.appendChild(overlay);
  document.body.style.overflow = 'hidden';

  overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });
  overlay.querySelector('.modal-close').addEventListener('click', closeModal);

  overlay.querySelectorAll('.modal-thumbs img').forEach((thumb) => {
    thumb.addEventListener('click', () => {
      overlay.querySelector('#modal-main-img').src = thumb.src;
      overlay.querySelectorAll('.modal-thumbs img').forEach((t) => t.classList.remove('active'));
      thumb.classList.add('active');
    });
  });

  document.addEventListener('keydown', escListener);
}

function closeModal() {
  const existing = document.getElementById('product-modal');
  if (existing) existing.remove();
  document.body.style.overflow = '';
  document.removeEventListener('keydown', escListener);
}

function escListener(e) { if (e.key === 'Escape') closeModal(); }

function escapeHtml(s) {
  return String(s ?? '').replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[c]));
}
