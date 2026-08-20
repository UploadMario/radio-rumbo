(function () {
    var buttons = document.querySelectorAll('[data-player-toggle]');
    var retryButtons = document.querySelectorAll('[data-audio-retry]');
    var icons = document.querySelectorAll('[data-player-icon]');
    var players = document.querySelectorAll('[data-audio-player]');
    var statusLabels = document.querySelectorAll('[data-audio-status-label]');
    var statusMessages = document.querySelectorAll('[data-audio-status-message]');
    var volumeToggles = document.querySelectorAll('[data-volume-toggle]');
    var volumeSliders = document.querySelectorAll('[data-volume-slider]');
    var playerShell = document.querySelector('.floating-player');
    var isPlaying = false;
    var currentStatus = 'ready';
    var storageKey = 'radio-rumbo-volume';

    var labels = {
        ready: 'En vivo',
        loading: 'Cargando',
        playing: 'En vivo',
        offline: 'Sin señal',
        unavailable: 'Fuera de horario o temporalmente no disponible'
    };

    function setStatus(status) {
        currentStatus = status;
        statusLabels.forEach(function (label) {
            label.textContent = labels[status] || labels.ready;
        });
        statusMessages.forEach(function (message) {
            message.hidden = status !== 'offline' && status !== 'unavailable';
        });
        retryButtons.forEach(function (button) {
            button.hidden = status !== 'offline' && status !== 'unavailable';
        });
        document.body.dataset.audioStatus = status;
    }

    function sync() {
        document.body.classList.toggle('is-paused', !isPlaying);
        icons.forEach(function (icon) {
            icon.textContent = isPlaying ? 'II' : '\u25B6';
        });
        buttons.forEach(function (button) {
            button.setAttribute('aria-pressed', isPlaying ? 'true' : 'false');
        });
    }

    function pauseAll() {
        players.forEach(function (player) {
            player.pause();
        });
    }

    function setVolume(value) {
        var volume = Math.max(0, Math.min(1, Number(value)));
        if (Number.isNaN(volume)) {
            volume = 0.8;
        }

        players.forEach(function (player) {
            player.volume = volume;
        });
        volumeSliders.forEach(function (slider) {
            slider.value = String(volume);
        });

        try {
            window.localStorage.setItem(storageKey, String(volume));
        } catch (error) {
            return;
        }
    }

    function loadVolume() {
        var stored = '0.8';
        try {
            stored = window.localStorage.getItem(storageKey) || stored;
        } catch (error) {
            stored = '0.8';
        }
        setVolume(stored);
    }

    function playPrimary() {
        if (players.length === 0) {
            setStatus('unavailable');
            return;
        }

        setStatus('loading');
        players[0].load();
        players[0].play().then(function () {
            isPlaying = true;
            setStatus('playing');
            sync();
        }).catch(function () {
            isPlaying = false;
            setStatus('offline');
            sync();
        });
    }

    function refreshProgramCopy() {
        if (!playerShell) {
            return;
        }

        var name = playerShell.getAttribute('data-program-name') || '';
        var host = playerShell.getAttribute('data-program-host') || '';
        var schedule = playerShell.getAttribute('data-program-schedule') || '';
        var programNodes = document.querySelectorAll('[data-player-program]');
        var hostNodes = document.querySelectorAll('[data-player-host]');
        var scheduleNodes = document.querySelectorAll('[data-player-schedule]');

        programNodes.forEach(function (node) {
            if (name !== '') {
                node.textContent = name;
            }
        });
        hostNodes.forEach(function (node) {
            if (host !== '') {
                node.textContent = host;
            }
        });
        scheduleNodes.forEach(function (node) {
            if (schedule !== '') {
                node.textContent = schedule;
            }
        });
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (players.length === 0) {
                isPlaying = !isPlaying;
                setStatus(isPlaying ? 'playing' : 'unavailable');
                sync();
                return;
            }

            if (isPlaying) {
                pauseAll();
                isPlaying = false;
                sync();
                return;
            }

            playPrimary();
        });
    });

    retryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            pauseAll();
            isPlaying = false;
            sync();
            playPrimary();
        });
    });

    volumeToggles.forEach(function (button) {
        button.addEventListener('click', function () {
            var expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            if (playerShell) {
                playerShell.classList.toggle('is-volume-open', !expanded);
            }
        });
    });

    volumeSliders.forEach(function (slider) {
        slider.addEventListener('input', function () {
            setVolume(slider.value);
        });
    });

    players.forEach(function (player) {
        player.addEventListener('loadstart', function () {
            setStatus('loading');
        });
        player.addEventListener('waiting', function () {
            setStatus('loading');
        });
        player.addEventListener('playing', function () {
            isPlaying = true;
            setStatus('playing');
            sync();
        });
        player.addEventListener('pause', function () {
            isPlaying = false;
            if (currentStatus !== 'offline' && currentStatus !== 'unavailable') {
                setStatus('ready');
            }
            sync();
        });
        player.addEventListener('error', function () {
            isPlaying = false;
            setStatus('offline');
            sync();
        });
        player.addEventListener('stalled', function () {
            isPlaying = false;
            setStatus('offline');
            sync();
        });
    });

    loadVolume();
    refreshProgramCopy();
    window.setInterval(refreshProgramCopy, 60000);
    setStatus(players.length > 0 ? 'ready' : 'unavailable');
    sync();
}());
