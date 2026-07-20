// FreshCrate — shared interactions
document.addEventListener('DOMContentLoaded', () => {
  if (window.lucide) lucide.createIcons();

  /* Mobile nav toggle */
  const navToggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      const open = navLinks.classList.contains('open');
      navToggle.setAttribute('aria-expanded', open);
    });
    navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navLinks.classList.remove('open')));
  }

  /* Toast */
  window.showToast = (msg) => {
    let toast = document.querySelector('.toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'toast';
      toast.innerHTML = '<i data-lucide="check-circle"></i><span></span>';
      document.body.appendChild(toast);
      if (window.lucide) lucide.createIcons();
    }
    toast.querySelector('span').textContent = msg;
    toast.classList.add('show');
    clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(() => toast.classList.remove('show'), 2400);
  };

  /* Cart badge (demo/session state — resets per page load; wire to Laravel cart session for production) */
  const cartCountEls = document.querySelectorAll('.cart-count');
  let cartCount = parseInt(document.body.dataset.cartCount || '0', 10);
  const renderCartCount = () => cartCountEls.forEach(el => el.textContent = cartCount);
  renderCartCount();

  document.querySelectorAll('[data-add-to-cart]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      cartCount += 1;
      renderCartCount();
      const name = btn.dataset.name || 'Item';
      showToast(`${name} added to cart`);
    });
  });

  /* Filter chips (shop page) */
  const chips = document.querySelectorAll('.chip[data-filter]');
  const products = document.querySelectorAll('[data-category]');
  if (chips.length && products.length) {
    chips.forEach(chip => {
      chip.addEventListener('click', () => {
        chips.forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        const filter = chip.dataset.filter;
        products.forEach(p => {
          p.style.display = (filter === 'all' || p.dataset.category === filter) ? '' : 'none';
        });
      });
    });
  }

  /* Generic tabs (product detail / auth pages) */
  document.querySelectorAll('[data-tabs]').forEach(group => {
    const tabs = group.querySelectorAll('[data-tab]');
    const panels = document.querySelectorAll(`[data-panel-group="${group.dataset.tabs}"] [data-panel]`);
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        panels.forEach(p => p.classList.toggle('active', p.dataset.panel === tab.dataset.tab));
      });
    });
  });

  /* Quantity steppers */
  document.querySelectorAll('.qty-box').forEach(box => {
    const input = box.querySelector('input');
    box.querySelector('[data-qty="dec"]').addEventListener('click', () => {
      input.value = Math.max(1, parseInt(input.value || '1', 10) - 1);
    });
    box.querySelector('[data-qty="inc"]').addEventListener('click', () => {
      input.value = Math.min(99, parseInt(input.value || '1', 10) + 1);
    });
  });

  /* Remove cart line (demo) */
  document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const line = btn.closest('.cart-line');
      line.style.opacity = '0';
      setTimeout(() => line.remove(), 200);
    });
  });

  /* Payment option select (checkout) */
  document.querySelectorAll('.pay-opt').forEach(opt => {
    opt.addEventListener('click', () => {
      document.querySelectorAll('.pay-opt').forEach(o => o.classList.remove('selected'));
      opt.classList.add('selected');
      const radio = opt.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    });
  });

  /* Auth tabs (login/register) */
  document.querySelectorAll('.auth-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      document.querySelectorAll('.auth-panel').forEach(p => p.classList.toggle('active', p.dataset.auth === tab.dataset.auth));
    });
  });

  /* Fake submit handlers for demo forms (all forms wire to Laravel routes in production) */
  document.querySelectorAll('form[data-demo-submit]').forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast(form.dataset.demoSubmit || 'Submitted');
    });
  });
});
