(function () {
    var buttons = document.querySelectorAll('[data-player-toggle]');
    var icons = document.querySelectorAll('[data-player-icon]');
    var isPlaying = true;

    function sync() {
        document.body.classList.toggle('is-paused', !isPlaying);
        icons.forEach(function (icon) {
            icon.textContent = isPlaying ? 'II' : '▶';
        });
        buttons.forEach(function (button) {
            button.setAttribute('aria-pressed', isPlaying ? 'true' : 'false');
        });
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            isPlaying = !isPlaying;
            sync();
        });
    });

    sync();
}());
