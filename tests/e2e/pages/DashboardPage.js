// tests/e2e/pages/DashboardPage.js
import BasePage from './BasePage';
import NavbarComponent from '../components/NavbarComponent';
import { expect } from '@playwright/test';

class DashboardPage extends BasePage {
    /**
     * @param {import('@playwright/test').Page} page
     */
    constructor(page) {
        super(page);
        this.navbar = new NavbarComponent(page);
        this.welcomeHeading = page.locator('h1:has-text("儀表板")'); # Updated selector
        this.productsOverviewCard = page.locator('#products-overview-card'); # Example card selector
        this.ordersOverviewCard = page.locator('#orders-overview-card'); # Example card selector
    }

    /**
     * Navigates to the dashboard page.
     */
    async navigate() {
        await super.navigate('/dashboard');
        await expect(this.welcomeHeading).toBeVisible();
    }

    /**
     * Gets the welcome message from the heading.
     * @returns {Promise<string>} The welcome message text.
     */
    async getWelcomeMessage() {
        return this.welcomeHeading.textContent();
    }

    /**
     * Checks if the products overview card is visible.
     * @returns {Promise<boolean>} True if visible, false otherwise.
     */
    async isProductsOverviewVisible() {
        return this.productsOverviewCard.isVisible();
    }

    /**
     * Checks if the orders overview card is visible.
     * @returns {Promise<boolean>} True if visible, false otherwise.
     */
    async isOrdersOverviewVisible() {
        return this.ordersOverviewCard.isVisible();
    }
}
export default DashboardPage;
