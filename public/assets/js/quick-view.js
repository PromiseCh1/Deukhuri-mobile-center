(function () {
  'use strict';

  const modal = document.getElementById('quickViewModal');
  if (!modal) return;

  const els = {
    image: modal.querySelector('#qvImage'),
    title: modal.querySelector('#qvTitle'),
    price: modal.querySelector('#qvPrice'),
    desc:  modal.querySelector('#qvDesc'),
    specs: modal.querySelector('#qvSpecs'),
    wa:    modal.querySelector('#qvWhatsApp'),
  };

  let lastFocus = null;

  function open(product) {
    const img = (product.images && product.images[0]) || 'assets/images/placeholder.png.svg';
    els.image.src = img;
    els.image.alt = product.name || '';
    els.title.textContent = product.name || '';
    els.price.textContent = product.price_formatted || '';
    els.desc.innerHTML = product.description || '';
    els.specs.innerHTML = product.specs_formatted || '';

    const msg = `Hello, I'm interested in "${product.name}" (${product.price_formatted}).`;
    els.wa.href = 'https://wa.me/9847956550?text=' + encodeURIComponent(msg);

    lastFocus = document.activeElement;
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    els.title.setAttribute('tabindex', '-1');
    els.title.focus();
  }

  function close() {
    modal.hidden = true;
    document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }

  document.addEventListener('click', async (e) => {
    const closeEl = e.target.closest('[data-qv-close]');
    if (closeEl) { e.preventDefault(); close(); return; }

    const btn = e.target.closest('.quick-view');
    if (!btn) return;
    e.preventDefault();

    const id = btn.dataset.productId;
    if (!id) return;

    try {
      const res = await fetch('api/product.php?id=' + encodeURIComponent(id));
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const data = await res.json();
      if (data.error) throw new Error(data.error);
      open(data);
    } catch (err) {
      console.error('Quick view failed:', err);
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.hidden) close();
  });
})();