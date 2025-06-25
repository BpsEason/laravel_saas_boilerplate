// tests/e2e/pages/ProductListPage.js
import BasePage from './BasePage';
import NavbarComponent from '../components/NavbarComponent';
import { expect } from '@playwright/test';

class ProductListPage extends BasePage {
    /**
     * @param {import('@playwright/test').Page} page
     */
    constructor(page) {
        super(page);
        this.navbar = new NavbarComponent(page);
        this.pageTitle = page.locator('h1:has-text("產品列表")'); # Updated selector
        this.addProductButton = page.locator('#add-product-button');
        this.productTableBody = page.locator('.product-table tbody');
        this.productRows = this.productTableBody.locator('tr');
    }

    /**
     * Navigates to the products list page.
     */
    async navigate() {
        await super.navigate('/products');
        await expect(this.pageTitle).toBeVisible();
    }

    /**
     * Clicks the "Add Product" button.
     */
    async clickAddProduct() {
        await this.addProductButton.click();
        await this.page.waitForURL(/products\/create/);
    }

    /**
     * Gets the count of product rows in the table.
     * @returns {Promise<number>} The number of product rows.
     */
    async getProductCount() {
        return this.productRows.count();
    }

    /**
     * Gets the name of the product at a given index.
     * @param {number} index - The zero-based index of the product row.
     * @returns {Promise<string>} The product name.
     */
    async getProductNameAtIndex(index) {
        await expect(this.productRows.nth(index)).toBeVisible();
        return this.productRows.nth(index).locator('.product-name').textContent();
    }

    /**
     * Clicks the "Edit" button for the product at a given index.
     * @param {number} index - The zero-based index of the product row.
     */
    async clickEditProductAtIndex(index) {
        await this.productRows.nth(index).locator('.edit-product-button').click();
    }

    /**
     * Clicks the "Delete" button for the product at a given index.
     * @param {number} index - The zero-based index of the product row.
     */
    async clickDeleteProductAtIndex(index) {
        await this.productRows.nth(index).locator('.delete-product-button').click();
    }
}
export default ProductListPage;
