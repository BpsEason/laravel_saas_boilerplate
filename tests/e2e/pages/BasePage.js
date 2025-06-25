// tests/e2e/pages/BasePage.js
import { expect } from '@playwright/test';

class BasePage {
    constructor(page) {
        this.page = page;
        this.navbar = page.locator('nav'); # Changed to common nav tag
        this.logoutButton = page.locator('button#logout-button'); # Example logout button selector
    }

    /**
     * Navigates to a specific path relative to the base URL.
     * @param {string} path - The path to navigate to.
     */
    async navigate(path) {
        await this.page.goto(path);
    }

    /**
     * Clicks the logout button and waits for redirection to the login page.
     */
    async logout() {
        await this.logoutButton.click();
        await this.page.waitForURL(/login/);
    }

    /**
     * Asserts that a logged-in user's username is displayed.
     * @param {string} username - The expected username.
     */
    async expectLoggedInUser(username) {
        await this.page.waitForSelector('.user-info');
        await expect(this.page.locator('.user-info')).toHaveText(username);
    }
}
export default BasePage;
