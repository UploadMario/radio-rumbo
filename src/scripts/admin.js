import '../styles/admin.css';
import './navigation.js';

const adminNavToggle = document.querySelector('[data-admin-nav-toggle]');
const adminSidebar = document.querySelector('.admin-sidebar');

if (adminNavToggle && adminSidebar) {
  adminNavToggle.addEventListener('click', () => {
    const isOpen = adminSidebar.classList.toggle('is-open');
    adminNavToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  adminSidebar.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      adminSidebar.classList.remove('is-open');
      adminNavToggle.setAttribute('aria-expanded', 'false');
    });
  });
}

document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
  toggle.addEventListener('click', () => {
    const input = document.getElementById(toggle.getAttribute('aria-controls') || '');
    if (!input) return;
    const isPassword = input.getAttribute('type') === 'password';
    input.setAttribute('type', isPassword ? 'text' : 'password');
    toggle.textContent = isPassword ? 'Ocultar' : 'Mostrar';
    toggle.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
  });
});
