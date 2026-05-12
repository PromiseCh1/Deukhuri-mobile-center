document.addEventListener('DOMContentLoaded', () => {
  const inputs = [document.getElementById('search-desktop'), document.getElementById('search-mobile')].filter(Boolean);

  const filter = (term) => {
    const q = term.trim().toLowerCase();
    document.querySelectorAll('.product-card').forEach((card) => {
      const name = (card.dataset.name || card.innerText || '').toLowerCase();
      card.style.display = !q || name.includes(q) ? '' : 'none';
    });
  };

  inputs.forEach((input) => {
    input.addEventListener('input', (e) => {
      const val = e.target.value;
      inputs.forEach((other) => { if (other !== e.target) other.value = val; });
      filter(val);
    });
  });
});
