// tests/e2e/specs/products.spec.js
import { test, expect } from '@playwright/test';
import LoginPage from '../pages/LoginPage';
import ProductListPage from '../pages/ProductListPage';
import DashboardPage from '../pages/DashboardPage';

test.describe('Product Management', () => {
    let loginPage;
    let productListPage;
    let dashboardPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        productListPage = new ProductListPage(page);
        dashboardPage = new DashboardPage(page);

        await loginPage.navigate();
        await loginPage.login('tenant.a@example.com', 'password');
        await dashboardPage.navigate();
        await productListPage.navbar.goToProducts();
    });

    test('should display products list', async () => {
        await expect(productListPage.productTableBody).toBeVisible();
        const rowCount = await productListPage.getProductCount();
        expect(rowCount).toBeGreaterThanOrEqual(0);
    });

    test('should create a new product', async ({ page }) => {
        await productListPage.clickAddProduct();
        
        const newProductName = '新測試產品 ' + Date.now();
        await page.locator('input[name="name"]').fill(newProductName);
        await page.locator('textarea[name="description"]').fill('這個產品的描述 ' + newProductName);
        await page.locator('input[name="price"]').fill('123.45');
        await page.locator('input[name="stock"]').fill('50');
        await page.locator('button[type="submit"]').click();

        await expect(page).toHaveURL(/products/);
        await expect(page.locator(`text="${newProductName}"`)).toBeVisible();
    });

    test('should edit an existing product', async ({ page }) => {
        let initialCount = await productListPage.getProductCount();
        if (initialCount === 0) {
            await productListPage.clickAddProduct();
            await page.locator('input[name="name"]').fill('要編輯的產品');
            await page.locator('textarea[name="description"]').fill('暫時產品用於編輯測試');
            await page.locator('input[name="price"]').fill('10.00');
            await page.locator('input[name="stock"]').fill('10');
            await page.locator('button[type="submit"]').click();
            await expect(page).toHaveURL(/products/);
            initialCount = await productListPage.getProductCount();
        }

        await productListPage.clickEditProductAtIndex(0);

        const updatedName = '已更新產品 ' + Date.now();
        await page.locator('input[name="name"]').fill(updatedName);
        await page.locator('button[type="submit"]').click();

        await expect(page).toHaveURL(/products/);
        await expect(page.locator(`text="${updatedName}"`)).toBeVisible();
    });

    test('should delete a product', async ({ page }) => {
        let initialCount = await productListPage.getProductCount();
        if (initialCount === 0) {
            await productListPage.clickAddProduct();
            await page.locator('input[name="name"]').fill('要刪除的產品');
            await page.locator('textarea[name="description"]').fill('暫時產品用於刪除測試');
            await page.locator('input[name="price"]').fill('5.00');
            await page.locator('input[name="stock"]').fill('5');
            await page.locator('button[type="submit"]').click();
            await expect(page).toHaveURL(/products/);
            initialCount = await productListPage.getProductCount();
        }

        const productNameToDelete = await productListPage.getProductNameAtIndex(0);
        await productListPage.clickDeleteProductAtIndex(0);

        page.on('dialog', async dialog => {
            expect(dialog.type()).toContain('confirm');
            await dialog.accept();
        });

        await expect(page.locator(`text="${productNameToDelete}"`)).not.toBeVisible();
        const finalCount = await productListPage.getProductCount();
        expect(finalCount).toBe(initialCount - 1);
    });
});
