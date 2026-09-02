const toggle = document.querySelector('[data-nav-toggle]');
const nav = document.getElementById('main-nav');
const header = document.querySelector('.site-header');

if (toggle && nav) {
  toggle.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });
}

if (header) {
  const syncHeader = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 8);
  };

  syncHeader();
  window.addEventListener('scroll', syncHeader, { passive: true });
}
