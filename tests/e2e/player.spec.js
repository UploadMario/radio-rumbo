import { expect, test } from '@playwright/test';

const audioMock = () => {
  const originalPlay = HTMLMediaElement.prototype.play;

  Object.defineProperty(HTMLMediaElement.prototype, 'play', {
    configurable: true,
    value() {
      this.dispatchEvent(new Event('loadstart'));
      this.dispatchEvent(new Event('playing'));
      return Promise.resolve();
    }
  });

  Object.defineProperty(HTMLMediaElement.prototype, 'pause', {
    configurable: true,
    value() {
      this.dispatchEvent(new Event('pause'));
    }
  });

  Object.defineProperty(HTMLMediaElement.prototype, 'load', {
    configurable: true,
    value() {}
  });

  window.addEventListener('beforeunload', () => {
    HTMLMediaElement.prototype.play = originalPlay;
  });
};

async function preparePage(page) {
  await page.addInitScript(audioMock);
  await page.goto('/', { waitUntil: 'domcontentloaded' });
  await expect(page.locator('.floating-player')).toBeVisible();
}

test.describe('reproductor global de audio', () => {
  test('reproduce y pausa desde el control principal', async ({ page }) => {
    await preparePage(page);
    const toggle = page.locator('[data-player-toggle]').last();
    const status = page.locator('[data-audio-status-label]').last();

    await toggle.click();
    await expect(status).toHaveText('En vivo');
    await expect(toggle).toHaveAttribute('aria-pressed', 'true');
    await toggle.click();
    await expect(status).toHaveText('Señal disponible');
    await expect(toggle).toHaveAttribute('aria-pressed', 'false');
    expect(await page.evaluate(() => localStorage.getItem('radioWasPlaying'))).toBe('false');
  });

  test('aplica y persiste el volumen al recargar', async ({ page }) => {
    await preparePage(page);
    const slider = page.locator('[data-volume-slider]').last();

    await slider.fill('0.35');
    await expect(slider).toHaveValue('0.35');
    await expect(page.locator('audio[data-audio-player]').last()).toHaveJSProperty('volume', 0.35);
    expect(await page.evaluate(() => localStorage.getItem('radioVolume'))).toBe('0.35');

    await page.reload({ waitUntil: 'domcontentloaded' });
    await expect(page.locator('[data-volume-slider]').last()).toHaveValue('0.35');
    await expect(page.locator('audio[data-audio-player]').last()).toHaveJSProperty('volume', 0.35);
  });

  test('conserva intención de reproducción durante navegación', async ({ page }) => {
    await preparePage(page);
    await page.locator('[data-player-toggle]').last().click();
    await expect(page.locator('[data-audio-status-label]').last()).toHaveText('En vivo');

    await page.goto('/programacion/', { waitUntil: 'domcontentloaded' });
    expect(await page.evaluate(() => localStorage.getItem('radioWasPlaying'))).toBe('true');
    await expect(page.locator('[data-audio-status-label]').last()).toHaveText('En vivo', { timeout: 2000 });
  });

  test('expone estados de señal deterministas sin depender del proveedor externo', async ({ page }) => {
    await preparePage(page);
    const audio = page.locator('audio[data-audio-player]').last();
    const status = page.locator('[data-audio-status-label]').last();
    const retry = page.locator('[data-audio-retry]').last();

    await page.locator('[data-player-toggle]').last().click();
    await expect(status).toHaveText('En vivo');

    await audio.evaluate((element) => element.dispatchEvent(new Event('waiting')));
    await expect(status).toHaveText('Cargando');

    await audio.evaluate((element) => element.dispatchEvent(new Event('error')));
    await expect(status).toHaveText('Sin señal');
    await expect(retry).toBeVisible();

    await audio.evaluate((element) => element.dispatchEvent(new Event('playing')));
    await expect(status).toHaveText('En vivo');
    await expect(retry).toBeHidden();
  });

  test('mantiene controles accesibles y geometría segura en cada viewport', async ({ page }) => {
    await preparePage(page);
    const player = page.locator('.floating-player');
    const toggle = page.locator('[data-player-toggle]').last();
    const volume = page.locator('[data-volume-slider]').last();

    await expect(toggle).toHaveAccessibleName(/reproducir o pausar/i);
    await expect(volume).toHaveAccessibleName(/volumen/i);
    await toggle.focus();
    await expect(toggle).toBeFocused();
    await page.keyboard.press('Tab');
    await expect(page.locator(':focus')).toBeVisible();

    const geometry = await page.evaluate(() => {
      const node = document.querySelector('.floating-player');
      const rect = node?.getBoundingClientRect();
      if (!rect) return null;
      return {
        left: rect.left,
        right: rect.right,
        bottom: rect.bottom,
        viewportWidth: window.innerWidth,
        viewportHeight: window.innerHeight,
        height: rect.height
      };
    });

    expect(geometry).not.toBeNull();
    expect(geometry.left).toBeGreaterThanOrEqual(0);
    expect(geometry.right).toBeLessThanOrEqual(geometry.viewportWidth + 1);
    expect(geometry.bottom).toBeLessThanOrEqual(geometry.viewportHeight + 1);
    expect(geometry.height).toBeLessThan(180);
  });
});
