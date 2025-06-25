// tests/e2e/specs/dashboard.spec.js
import { test, expect } from '@playwright/test';
import LoginPage from '../pages/LoginPage';
import DashboardPage from '../pages/DashboardPage';

test.describe('Dashboard Functionality', () => {
    let loginPage;
    let dashboardPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        dashboardPage = new DashboardPage(page);
        
        # Log in a user before each dashboard test
        await loginPage.navigate();
        await loginPage.login('tenant.a@example.com', 'password');
        await page.waitForURL(/dashboard/);
    });

    test('should display dashboard elements correctly', async () => {
        await expect(dashboardPage.welcomeHeading).toBeVisible();
        await expect(dashboardPage.getWelcomeMessage()).resolves.toContain('儀表板');
        await expect(dashboardPage.productsOverviewCard).toBeVisible();
        await expect(dashboardPage.ordersOverviewCard).toBeVisible();
    });

    test('should navigate to Products list from dashboard', async () => {
        await dashboardPage.navbar.goToProducts();
        await expect(dashboardPage.page).toHaveURL(/products/);
        await expect(dashboardPage.page.locator('h1:has-text("產品列表")')).toBeVisible();
    });

    test('should navigate to Orders list from dashboard', async () => {
        await dashboardPage.navbar.goToOrders();
        await expect(dashboardPage.page).toHaveURL(/orders/);
        await expect(dashboardPage.page.locator('h1:has-text("訂單列表")')).toBeVisible();
    });

    test('should log out from dashboard', async () => {
        await dashboardPage.navbar.logout();
        await expect(dashboardPage.page).toHaveURL(/login/);
        await expect(loginPage.loginButton).toBeVisible();
    });
});
