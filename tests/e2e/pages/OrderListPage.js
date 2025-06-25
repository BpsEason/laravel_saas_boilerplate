// tests/e2e/pages/OrderListPage.js
import BasePage from './BasePage';
import NavbarComponent from '../components/NavbarComponent';
import { expect } from '@playwright/test';

class OrderListPage extends BasePage {
    /**
     * @param {import('@playwright/test').Page} page
     */
    constructor(page) {
        super(page);
        this.navbar = new NavbarComponent(page);
        this.pageTitle = page.locator('h1:has-text("訂單列表")'); # Updated selector
        this.createOrderButton = page.locator('#create-order-button');
        this.orderTableBody = page.locator('.order-table tbody');
        this.orderRows = this.orderTableBody.locator('tr');
    }

    /**
     * Navigates to the orders list page.
     */
    async navigate() {
        await super.navigate('/orders');
        await expect(this.pageTitle).toBeVisible();
    }

    /**
     * Clicks the "Create Order" button.
     */
    async clickCreateOrder() {
        await this.createOrderButton.click();
        await this.page.waitForURL(/orders\/create/);
    }

    /**
     * Gets the count of order rows in the table.
     * @returns {Promise<number>} The number of order rows.
     */
    async getOrderCount() {
        return this.orderRows.count();
    }

    /**
     * Gets the customer name of the order at a given index.
     * @param {number} index - The zero-based index of the order row.
     * @returns {Promise<string>} The customer name.
     */
    async getOrderCustomerNameAtIndex(index) {
        await expect(this.orderRows.nth(index)).toBeVisible();
        return this.orderRows.nth(index).locator('.order-customer-name').textContent();
    }

    /**
     * Clicks the "Details" link for the order at a given index.
     * @param {number} index - The zero-based index of the order row.
     */
    async clickOrderDetailsAtIndex(index) {
        await this.orderRows.nth(index).locator('.order-details-link').click();
    }
}
export default OrderListPage;
