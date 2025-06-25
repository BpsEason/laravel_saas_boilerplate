// tests/e2e/pages/LoginPage.js
import BasePage from './BasePage';
import { expect } from '@playwright/test';

class LoginPage extends BasePage {
    /**
     * @param {import('@playwright/test').Page} page
     */
    constructor(page) {
        super(page);
        this.emailInput = page.locator('input[type="email"]');
        this.passwordInput = page.locator('input[type="password"]');
        this.loginButton = page.locator('button[type="submit"]');
        this.registerLink = page.locator('a[href="/register"]');
        this.errorMessage = page.locator('.alert-danger'); # Assuming Bootstrap-like alert for errors
    }

    /**
     * Navigates to the login page.
     */
    async navigate() {
        await super.navigate('/login');
        await expect(this.loginButton).toBeVisible();
    }

    /**
     * Performs a login operation.
     * @param {string} email - The user's email.
     * @param {string} password - The user's password.
     */
    async login(email, password) {
        await this.emailInput.fill(email);
        await this.passwordInput.fill(password);
        await this.loginButton.click();
    }

    /**
     * Clicks the register link to navigate to the registration page.
     */
    async goToRegister() {
        await this.registerLink.click();
        await this.page.waitForURL(/register/);
    }

    /**
     * Gets the text of the error message element.
     * @returns {Promise<string>} The error message text.
     */
    async getErrorMessage() {
        await expect(this.errorMessage).toBeVisible();
        return this.errorMessage.textContent();
    }
}
export default LoginPage;
