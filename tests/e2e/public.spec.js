import { expect, test } from '@playwright/test';

const publicRoutes = [
  { name: 'home', path: '/' },
  { name: 'en-vivo', path: '/en-vivo/' },
  { name: 'programacion', path: '/programacion/' },
  { name: 'noticias', path: '/noticias/' },
  { name: 'equipo', path: '/equipo/' },
  { name: 'publicidad-contacto', path: '/publicidad-contacto/' },
];

for (const route of publicRoutes) {
  test(`${route.name} carga sin errores críticos ni overflow`, async ({ page }, testInfo) => {
    const errors = [];
    const missingResources = [];
    page.on('pageerror', (error) => errors.push(error.message));
    page.on('console', (message) => {
      if (message.type() === 'error') errors.push(message.text());
    });
    page.on('response', (response) => {
      if (response.status() < 400) return;
      const url = new URL(response.url());
      const currentUrl = page.url();
      const localOrigin = currentUrl.startsWith('http') ? new URL(currentUrl).origin : '';
      if (localOrigin !== '' && url.origin === localOrigin) {
        missingResources.push(`${response.status()} ${url.pathname}`);
      }
    });

    await page.goto(route.path, { waitUntil: 'domcontentloaded' });
    await expect(page.locator('body')).toBeVisible();
    expect(errors, `Errores JavaScript en ${route.path}`).toEqual([]);
    expect(missingResources, `Recursos locales con error en ${route.path}`).toEqual([]);

    const overflow = await page.evaluate(() => document.documentElement.scrollWidth > window.innerWidth + 1);
    expect(overflow, `Overflow horizontal en ${route.path}`).toBe(false);

    await page.screenshot({ path: testInfo.outputPath(`${route.name}.png`), fullPage: true });
  });
}

test('login administrativo carga sin exponer autenticación', async ({ page }, testInfo) => {
  const errors = [];
  page.on('pageerror', (error) => errors.push(error.message));
  page.on('console', (message) => {
    if (message.type() === 'error') errors.push(message.text());
  });

  await page.goto('/admin/login/', { waitUntil: 'domcontentloaded' });
  await expect(page.locator('input[name="identifier"]')).toBeVisible();
  await expect(page.locator('input[name="password"]')).toBeVisible();
  await expect(page.locator('input[name="_csrf_token"]')).toHaveCount(1);
  expect(errors).toEqual([]);

  const overflow = await page.evaluate(() => document.documentElement.scrollWidth > window.innerWidth + 1);
  expect(overflow).toBe(false);
  await page.screenshot({ path: testInfo.outputPath('admin-login.png'), fullPage: true });
});

test('navegación pública mantiene estados accesibles en móvil y escritorio', async ({ page }) => {
  const errors = [];
  page.on('pageerror', (error) => errors.push(error.message));
  page.on('console', (message) => {
    if (message.type() === 'error') errors.push(message.text());
  });

  await page.goto('/', { waitUntil: 'domcontentloaded' });
  const toggle = page.locator('[data-nav-toggle]');
  const nav = page.locator('#main-nav');
  const isMobile = (page.viewportSize()?.width || 0) < 900;

  if (!isMobile) {
    await expect(nav).toBeVisible();
    await expect(toggle).toBeHidden();
    expect(errors).toEqual([]);
    return;
  }

  await expect(toggle).toHaveAttribute('aria-expanded', 'false');
  await toggle.click();
  await expect(toggle).toHaveAttribute('aria-expanded', 'true');
  await expect(nav).toHaveClass(/is-open/);

  await page.keyboard.press('Escape');
  await expect(nav).not.toHaveClass(/is-open/);
  await expect(toggle).toHaveAttribute('aria-expanded', 'false');
  await expect(toggle).toBeFocused();

  await toggle.click();
  await nav.locator('a[href="/noticias/"]').click();
  await expect(page).toHaveURL(/\/noticias\/?$/);
  await expect(page.locator('#main-nav')).not.toHaveClass(/is-open/);
  expect(errors).toEqual([]);
});

test('dashboard administrativo exige sesión', async ({ page }) => {
  const response = await page.goto('/admin/dashboard/', { waitUntil: 'domcontentloaded' });
  expect(response?.status()).toBeLessThan(400);
  await expect(page).toHaveURL(/\/admin\/login\/?$/);
});
