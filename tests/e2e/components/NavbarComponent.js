// tests/e2e/components/NavbarComponent.js
// This represents a reusable UI component like a navigation bar.

import { expect } from '@playwright/test';

class NavbarComponent {
    /**
     * @param {import('@playwright/test').Page} page
     */
    constructor(page) {
        this.page = page;
        this.dashboardLink = page.locator('nav a[href="/dashboard"]');
        this.productsLink = page.locator('nav a[href="/products"]');
        this.ordersLink = page.locator('nav a[href="/orders"]');
        this.logoutButton = page.locator('button#logout-button');
    }

    /**
     * Clicks the Dashboard link and waits for navigation.
     */
    async goToDashboard() {
        await this.dashboardLink.click();
        await this.page.waitForURL(/dashboard/);
        await expect(this.page.locator('h1:has-text("儀表板")')).toBeVisible(); # Updated selector for traditional Chinese
    }

    /**
     * Clicks the Products link and waits for navigation.
     */
    async goToProducts() {
        await this.productsLink.click();
        await this.page.waitForURL(/products/);
        await expect(this.page.locator('h1:has-text("產品列表")')).toBeVisible(); # Updated selector for traditional Chinese
    }

    /**
     * Clicks the Orders link and waits for navigation.
     */
    async goToOrders() {
        await this.ordersLink.click();
        await this.page.waitForURL(/orders/);
        await expect(this.page.locator('h1:has-text("訂單列表")')).toBeVisible(); # Updated selector for traditional Chinese
    }

    /**
     * Clicks the logout button and waits for redirection to the login page.
     */
    async logout() {
        await this.logoutButton.click();
        await this.page.waitForURL(/login/);
    }
}
export default NavbarComponent;
