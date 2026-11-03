import { expect, test } from '@playwright/test';

const credentials = {
  superAdminEmail: process.env.E2E_SUPERADMIN_EMAIL,
  superAdminPassword: process.env.E2E_SUPERADMIN_PASSWORD,
  adminEmail: process.env.E2E_ADMIN_EMAIL,
  adminPassword: process.env.E2E_ADMIN_PASSWORD,
};

const configured = Object.values(credentials).every((value) => typeof value === 'string' && value !== '');
const authRequired = process.env.E2E_AUTH_REQUIRED === '1';

if (authRequired && !configured) {
  throw new Error('La suite autenticada requiere E2E_* y una base radio_rumbo_e2e preparada.');
}

test.describe('autenticación administrativa E2E', () => {
  test.skip(!configured, 'Requiere variables externas E2E_* y una base radio_rumbo_e2e preparada.');

  test('Super Admin inicia sesión y puede abrir configuración', async ({ page }) => {
    await page.goto('/admin/login/');
    await page.locator('input[name="identifier"]').fill(credentials.superAdminEmail);
    await page.locator('input[name="password"]').fill(credentials.superAdminPassword);
    await page.locator('form[action="/admin/login/"]').locator('button[type="submit"]').click();
    await expect(page).toHaveURL(/\/admin\/dashboard\/?$/);
    await page.goto('/admin/settings/');
    await expect(page.locator('button[type="submit"]')).toBeEnabled();
  });

  test('rechaza credenciales inválidas sin exponer detalles', async ({ page }) => {
    await page.goto('/admin/login/');
    await page.locator('input[name="identifier"]').fill(credentials.adminEmail);
    await page.locator('input[name="password"]').fill('invalid-e2e-password');
    await page.locator('form[action="/admin/login/"]').locator('button[type="submit"]').click();
    await expect(page).toHaveURL(/\/admin\/login\/?$/);
    const errorAlert = page.locator('.form-alert');
    await expect(errorAlert).toBeVisible();
    await expect(errorAlert).toHaveText('Credenciales inválidas o usuario inactivo.');
    await expect(errorAlert).not.toContainText(/sql|pdo|password|exception|stack trace/i);
  });

  test('Admin no puede editar configuración', async ({ page }) => {
    await page.goto('/admin/login/');
    await page.locator('input[name="identifier"]').fill(credentials.adminEmail);
    await page.locator('input[name="password"]').fill(credentials.adminPassword);
    await page.locator('form[action="/admin/login/"]').locator('button[type="submit"]').click();
    await page.goto('/admin/settings/');
    await expect(page.locator('button[type="submit"]')).toBeDisabled();
    await expect(page.locator('text=Vista de solo lectura')).toBeVisible();
  });

  test('logout invalida la sesión y protege el dashboard', async ({ page }) => {
    await page.goto('/admin/login/');
    await page.locator('input[name="identifier"]').fill(credentials.adminEmail);
    await page.locator('input[name="password"]').fill(credentials.adminPassword);
    await page.locator('form[action="/admin/login/"]').locator('button[type="submit"]').click();
    await page.goto('/admin/dashboard/');
    await page.locator('form[action="/admin/logout/"] input[name="_csrf_token"]').evaluate((input) => input.form.submit());
    await expect(page).toHaveURL(/\/admin\/login\/?$/);
    await page.goto('/admin/dashboard/');
    await expect(page).toHaveURL(/\/admin\/login\/?$/);
  });
});
