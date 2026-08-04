(function () {
    var buttons = document.querySelectorAll('[data-player-toggle]');
    var icons = document.querySelectorAll('[data-player-icon]');
    var players = document.querySelectorAll('[data-audio-player]');
    var isPlaying = false;

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

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (players.length === 0) {
                isPlaying = !isPlaying;
                sync();
                return;
            }

            if (isPlaying) {
                pauseAll();
                isPlaying = false;
                sync();
                return;
            }

            players[0].play().then(function () {
                isPlaying = true;
                sync();
            }).catch(function () {
                isPlaying = false;
                sync();
            });
        });
    });

    players.forEach(function (player) {
        player.addEventListener('pause', function () {
            isPlaying = false;
            sync();
        });
        player.addEventListener('play', function () {
            isPlaying = true;
            sync();
        });
    });

    sync();
}());
