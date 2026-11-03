import { animate, inView, stagger } from 'motion';

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.documentElement.classList.add('motion-ready');

if (!reducedMotion) {
  const heroItems = document.querySelectorAll('.hero-copy > *, .live-copy > *');
  if (heroItems.length > 0) {
    animate(
      heroItems,
      { opacity: [1, 1], y: [8, 0] },
      { duration: 0.7, delay: stagger(0.08), easing: 'ease-out' }
    );
  }

  const revealed = new WeakSet();
  inView('[data-reveal]', (element) => {
    if (revealed.has(element)) {
      return;
    }

    revealed.add(element);
    animate(element, { opacity: [1, 1] }, { duration: 0.42, easing: 'ease-out' });
  }, { margin: '0px 0px -10% 0px' });

  document.querySelectorAll('.btn, .player-play, .main-nav a').forEach((element) => {
    element.addEventListener('pointerdown', () => {
      animate(element, { scale: [1, 0.97, 1] }, { duration: 0.22 });
    });
  });
} else {
  document.documentElement.classList.add('reduced-motion');
}
