document.querySelectorAll('iframe[data-external-embed]').forEach((frame) => {
  frame.addEventListener('load', () => {
    frame.closest('[data-embed-shell]')?.classList.add('is-loaded');
  });
});

const videoDock = document.querySelector('#mini-live-video');
const videoDockFrame = document.querySelector('[data-video-dock-frame]');
const videoDockToggle = document.querySelector('[data-video-dock-toggle]');
const videoDockClose = document.querySelector('[data-video-dock-close]');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
let isVideoOpen = false;
let audioWasPlayingBeforeVideo = false;
let lastFocusedVideoToggle = null;

function mountedVideoSrc() {
  if (!(videoDockFrame instanceof HTMLIFrameElement)) {
    return '';
  }

  return videoDockFrame.dataset.src || '';
}

function pauseAudioForVideo() {
  audioWasPlayingBeforeVideo = false;
  document.querySelectorAll('audio[data-audio-player]').forEach((audio) => {
    if (audio instanceof HTMLAudioElement && !audio.paused) {
      audioWasPlayingBeforeVideo = true;
    }
  });

  if (audioWasPlayingBeforeVideo) {
    document.dispatchEvent(new CustomEvent('radio:video-opening'));
  }
}

function resumeAudioAfterVideo() {
  if (!audioWasPlayingBeforeVideo) {
    return;
  }

  document.dispatchEvent(new CustomEvent('radio:video-closed-resume-audio'));
  audioWasPlayingBeforeVideo = false;
}

function mountVideo() {
  if (!(videoDockFrame instanceof HTMLIFrameElement)) {
    return;
  }

  const src = mountedVideoSrc();
  if (src !== '' && videoDockFrame.getAttribute('src') !== src) {
    videoDockFrame.setAttribute('src', src);
  }
}

function unmountVideo() {
  if (!(videoDockFrame instanceof HTMLIFrameElement)) {
    return;
  }

  videoDockFrame.setAttribute('src', 'about:blank');
  window.setTimeout(() => {
    videoDockFrame.removeAttribute('src');
  }, 0);
}

function openVideo() {
  if (!(videoDock instanceof HTMLElement) || !(videoDockToggle instanceof HTMLElement)) {
    return;
  }

  if (isVideoOpen) {
    return;
  }

  lastFocusedVideoToggle = document.activeElement instanceof HTMLElement ? document.activeElement : videoDockToggle;
  pauseAudioForVideo();
  mountVideo();
  isVideoOpen = true;
  videoDock.hidden = false;
  videoDockToggle.setAttribute('aria-expanded', 'true');

  if (videoDockClose instanceof HTMLElement) {
    videoDockClose.focus({ preventScroll: true });
  }

  if (!reducedMotion) {
    videoDock.animate(
      [
        { opacity: 0, transform: 'translateY(10px)' },
        { opacity: 1, transform: 'translateY(0)' }
      ],
      { duration: 200, easing: 'ease-out' }
    );
  }
}

function closeVideo() {
  if (!(videoDock instanceof HTMLElement) || !(videoDockToggle instanceof HTMLElement)) {
    return;
  }

  if (!isVideoOpen) {
    return;
  }

  unmountVideo();
  isVideoOpen = false;
  videoDock.hidden = true;
  videoDockToggle.setAttribute('aria-expanded', 'false');
  resumeAudioAfterVideo();

  if (lastFocusedVideoToggle instanceof HTMLElement) {
    lastFocusedVideoToggle.focus({ preventScroll: true });
  }
}

if (videoDockToggle instanceof HTMLElement) {
  videoDockToggle.addEventListener('click', () => {
    if (isVideoOpen) {
      closeVideo();
      return;
    }

    openVideo();
  });
}

if (videoDockClose instanceof HTMLElement) {
  videoDockClose.addEventListener('click', closeVideo);
}

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    closeVideo();
  }
});
