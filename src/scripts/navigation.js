const toggle = document.querySelector('[data-nav-toggle]');
const nav = document.getElementById('main-nav');
const header = document.querySelector('.site-header');

if (toggle && nav) {
  const closeNav = () => {
    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  toggle.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  nav.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', closeNav);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && nav.classList.contains('is-open')) {
      closeNav();
      toggle.focus({ preventScroll: true });
    }
  });
}

if (header) {
  const syncHeader = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 8);
  };

  syncHeader();
  window.addEventListener('scroll', syncHeader, { passive: true });
}
