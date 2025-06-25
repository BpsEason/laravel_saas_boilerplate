// tests/e2e/pages/RegisterPage.js
import BasePage from './BasePage';
import { expect } from '@playwright/test';

class RegisterPage extends BasePage {
    /**
     * @param {import('@playwright/test').Page} page
     */
    constructor(page) {
        super(page);
        this.nameInput = page.locator('input[name="name"]');
        this.emailInput = page.locator('input[name="email"]');
        this.passwordInput = page.locator('input[name="password"]');
        this.passwordConfirmationInput = page.locator('input[name="password_confirmation"]');
        this.tenantNameInput = page.locator('input[name="tenant_name"]');
        this.tenantDomainInput = page.locator('input[name="tenant_domain"]');
        this.registerButton = page.locator('button[type="submit"]');
        this.errorMessage = page.locator('.alert-danger');
    }

    /**
     * Navigates to the registration page.
     */
    async navigate() {
        await super.navigate('/register');
        await expect(this.registerButton).toBeVisible();
    }

    /**
     * Fills the registration form and submits it.
     * @param {Object} userData
     * @param {string} userData.name
     * @param {string} userData.email
     * @param {string} userData.password
     * @param {string} userData.tenantName
     * @param {string} userData.tenantDomain
     */
    async register(userData) {
        await this.nameInput.fill(userData.name);
        await this.emailInput.fill(userData.email);
        await this.passwordInput.fill(userData.password);
        await this.passwordConfirmationInput.fill(userData.password);
        await this.tenantNameInput.fill(userData.tenantName);
        await this.tenantDomainInput.fill(userData.tenantDomain);
        await this.registerButton.click();
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
export default RegisterPage;
