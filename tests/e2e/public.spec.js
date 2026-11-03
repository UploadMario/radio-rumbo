import { expect, test } from '@playwright/test';

const publicRoutes = [
  { name: 'home', path: '/' },
  { name: 'en-vivo', path: '/en-vivo/' },
  { name: 'programacion', path: '/programacion/' },
];

for (const route of publicRoutes) {
  test(`${route.name} carga sin errores críticos ni overflow`, async ({ page }, testInfo) => {
    const errors = [];
    page.on('pageerror', (error) => errors.push(error.message));
    page.on('console', (message) => {
      if (message.type() === 'error') errors.push(message.text());
    });

    await page.goto(route.path, { waitUntil: 'domcontentloaded' });
    await expect(page.locator('body')).toBeVisible();
    expect(errors, `Errores JavaScript en ${route.path}`).toEqual([]);

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

test('dashboard administrativo exige sesión', async ({ page }) => {
  const response = await page.goto('/admin/dashboard/', { waitUntil: 'domcontentloaded' });
  expect(response?.status()).toBeLessThan(400);
  await expect(page).toHaveURL(/\/admin\/login\/?$/);
});
