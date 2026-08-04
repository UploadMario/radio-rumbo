(function () {
    var buttons = document.querySelectorAll('[data-player-toggle]');
    var retryButtons = document.querySelectorAll('[data-audio-retry]');
    var icons = document.querySelectorAll('[data-player-icon]');
    var players = document.querySelectorAll('[data-audio-player]');
    var statusLabels = document.querySelectorAll('[data-audio-status-label]');
    var statusMessages = document.querySelectorAll('[data-audio-status-message]');
    var isPlaying = false;
    var currentStatus = 'ready';

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

    setStatus(players.length > 0 ? 'ready' : 'unavailable');
    sync();
}());
