import { animate } from 'motion';

(function () {
    const buttons = document.querySelectorAll('[data-player-toggle]');
    const retryButtons = document.querySelectorAll('[data-audio-retry]');
    const icons = document.querySelectorAll('[data-player-icon]');
    const players = document.querySelectorAll('[data-audio-player]');
    const statusLabels = document.querySelectorAll('[data-audio-status-label]');
    const statusMessages = document.querySelectorAll('[data-audio-status-message]');
    const volumeMuteButtons = document.querySelectorAll('[data-volume-mute]');
    const volumeIcons = document.querySelectorAll('[data-volume-icon]');
    const volumeSliders = document.querySelectorAll('[data-volume-slider]');
    const playerShell = document.querySelector('.floating-player');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const storage = {
        volume: 'radio-rumbo-volume',
        muted: 'radio-rumbo-muted',
        wasPlaying: 'radio-rumbo-was-playing'
    };
    const state = {
        isPlaying: false,
        volume: 0.8,
        muted: false,
        signalState: 'ready',
        pausedForVideo: false
    };
    const labels = {
        ready: 'Señal disponible',
        resume: 'Continuar escuchando',
        loading: 'Cargando',
        playing: 'En vivo',
        offline: 'Sin señal',
        unavailable: 'Fuera de horario'
    };

    function readStorage(key, fallback) {
        try {
            return window.localStorage.getItem(key) ?? fallback;
        } catch (error) {
            return fallback;
        }
    }

    function writeStorage(key, value) {
        try {
            window.localStorage.setItem(key, value);
        } catch (error) {
            return;
        }
    }

    function setWantedPlayback(value) {
        writeStorage(storage.wasPlaying, value ? 'true' : 'false');
    }

    function setStatus(status) {
        state.signalState = status;
        statusLabels.forEach((label) => {
            label.textContent = labels[status] || labels.ready;
        });
        statusMessages.forEach((message) => {
            message.hidden = status !== 'offline' && status !== 'unavailable';
        });
        retryButtons.forEach((button) => {
            button.hidden = status !== 'offline' && status !== 'unavailable';
        });
        document.body.dataset.audioStatus = status;
        if (playerShell && !reducedMotion) {
            animate(playerShell, { opacity: [0.94, 1] }, { duration: 0.16 });
        }
    }

    function syncButtons() {
        document.body.classList.toggle('is-paused', !state.isPlaying);
        icons.forEach((icon) => {
            icon.textContent = state.isPlaying ? 'II' : '\u25B6';
        });
        buttons.forEach((button) => {
            button.setAttribute('aria-pressed', state.isPlaying ? 'true' : 'false');
            if (!reducedMotion) {
                animate(button, { scale: state.isPlaying ? [1, 1.04, 1] : [1, 0.98, 1] }, { duration: 0.18 });
            }
        });
    }

    function syncVolume() {
        players.forEach((player) => {
            player.volume = state.volume;
            player.muted = state.muted;
        });
        volumeSliders.forEach((slider) => {
            slider.value = String(state.volume);
            slider.setAttribute('aria-valuenow', String(state.volume));
        });
        volumeMuteButtons.forEach((button) => {
            button.setAttribute('aria-pressed', state.muted ? 'true' : 'false');
            button.setAttribute('aria-label', state.muted ? 'Activar audio' : 'Silenciar audio');
        });
        volumeIcons.forEach((icon) => {
            icon.textContent = state.muted || state.volume === 0 ? '\uD83D\uDD07' : '\uD83D\uDD0A';
        });
        writeStorage(storage.volume, String(state.volume));
        writeStorage(storage.muted, state.muted ? 'true' : 'false');
    }

    function loadVolumeState() {
        const volume = Number(readStorage(storage.volume, '0.8'));
        state.volume = Number.isNaN(volume) ? 0.8 : Math.max(0, Math.min(1, volume));
        state.muted = readStorage(storage.muted, 'false') === 'true';
        syncVolume();
    }

    function pauseAll(persistIntent) {
        players.forEach((player) => {
            player.pause();
        });
        if (!persistIntent) {
            setWantedPlayback(false);
        }
    }

    function playPrimary(isResumeAttempt) {
        if (players.length === 0) {
            setStatus('unavailable');
            setWantedPlayback(false);
            return Promise.resolve(false);
        }

        setStatus('loading');
        players[0].load();

        return players[0].play().then(() => {
            state.isPlaying = true;
            state.pausedForVideo = false;
            setStatus('playing');
            setWantedPlayback(true);
            syncButtons();
            return true;
        }).catch(() => {
            state.isPlaying = false;
            setStatus(isResumeAttempt ? 'resume' : 'offline');
            if (!isResumeAttempt) {
                setWantedPlayback(false);
            }
            syncButtons();
            return false;
        });
    }

    function refreshProgramCopy() {
        if (!playerShell) {
            return;
        }

        const name = playerShell.getAttribute('data-program-name') || '';
        const host = playerShell.getAttribute('data-program-host') || '';
        const schedule = playerShell.getAttribute('data-program-schedule') || '';
        const programNodes = document.querySelectorAll('[data-player-program]');
        const hostNodes = document.querySelectorAll('[data-player-host]');
        const scheduleNodes = document.querySelectorAll('[data-player-schedule]');

        programNodes.forEach((node) => {
            if (name !== '') {
                node.textContent = name;
            }
        });
        hostNodes.forEach((node) => {
            if (host !== '') {
                node.textContent = host;
            }
        });
        scheduleNodes.forEach((node) => {
            if (schedule !== '') {
                node.textContent = schedule;
            }
        });
    }

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            if (state.isPlaying) {
                pauseAll(false);
                state.isPlaying = false;
                setStatus('ready');
                syncButtons();
                return;
            }

            playPrimary(false);
        });
    });

    retryButtons.forEach((button) => {
        button.addEventListener('click', () => {
            pauseAll(true);
            state.isPlaying = false;
            syncButtons();
            playPrimary(false);
        });
    });

    volumeMuteButtons.forEach((button) => {
        button.addEventListener('click', () => {
            state.muted = !state.muted;
            syncVolume();
        });
    });

    volumeSliders.forEach((slider) => {
        slider.addEventListener('input', () => {
            const nextVolume = Number(slider.value);
            state.volume = Number.isNaN(nextVolume) ? 0.8 : Math.max(0, Math.min(1, nextVolume));
            if (state.volume > 0) {
                state.muted = false;
            }
            syncVolume();
        });
    });

    players.forEach((player) => {
        player.addEventListener('loadstart', () => {
            setStatus('loading');
        });
        player.addEventListener('waiting', () => {
            setStatus('loading');
        });
        player.addEventListener('playing', () => {
            state.isPlaying = true;
            setStatus('playing');
            setWantedPlayback(true);
            syncButtons();
        });
        player.addEventListener('pause', () => {
            state.isPlaying = false;
            if (!state.pausedForVideo) {
                setWantedPlayback(false);
            }
            if (state.signalState !== 'offline' && state.signalState !== 'unavailable') {
                setStatus('ready');
            }
            syncButtons();
        });
        player.addEventListener('error', () => {
            state.isPlaying = false;
            setStatus('offline');
            setWantedPlayback(false);
            syncButtons();
        });
        player.addEventListener('stalled', () => {
            state.isPlaying = false;
            setStatus('offline');
            setWantedPlayback(false);
            syncButtons();
        });
    });

    document.addEventListener('radio:video-opening', () => {
        state.pausedForVideo = true;
        pauseAll(true);
    });

    document.addEventListener('radio:video-closed-resume-audio', () => {
        state.pausedForVideo = false;
        playPrimary(false);
    });

    loadVolumeState();
    refreshProgramCopy();
    window.setInterval(refreshProgramCopy, 60000);
    setStatus(players.length > 0 ? 'ready' : 'unavailable');
    syncButtons();

    if (readStorage(storage.wasPlaying, 'false') === 'true') {
        window.setTimeout(() => {
            playPrimary(true);
        }, 80);
    }
}());
