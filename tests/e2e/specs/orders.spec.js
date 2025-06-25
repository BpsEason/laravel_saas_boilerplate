// tests/e2e/specs/orders.spec.js
import { test, expect } from '@playwright/test';
import LoginPage from '../pages/LoginPage';
import OrderListPage from '../pages/OrderListPage';
import ProductListPage from '../pages/ProductListPage';
import DashboardPage from '../pages/DashboardPage';

test.describe('Order Management', () => {
    let loginPage;
    let orderListPage;
    let productListPage;
    let dashboardPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        orderListPage = new OrderListPage(page);
        productListPage = new ProductListPage(page);
        dashboardPage = new DashboardPage(page);

        await loginPage.navigate();
        await loginPage.login('tenant.a@example.com', 'password');
        await dashboardPage.navigate();
        await orderListPage.navbar.goToOrders();
    });

    test('should display orders list', async () => {
        await expect(orderListPage.orderTableBody).toBeVisible();
        const rowCount = await orderListPage.getOrderCount();
        expect(rowCount).toBeGreaterThanOrEqual(0);
    });

    test('should create a new order', async ({ page }) => {
        await orderListPage.navbar.goToProducts();
        const productCount = await productListPage.getProductCount();
        if (productCount === 0) {
            await productListPage.clickAddProduct();
            await page.locator('input[name="name"]').fill('用於訂單的虛擬產品');
            await page.locator('textarea[name="description"]').fill('用於訂單測試');
            await page.locator('input[name="price"]').fill('100.00');
            await page.locator('input[name="stock"]').fill('100');
            await page.locator('button[type="submit"]').click();
            await expect(page).toHaveURL(/products/);
        }
        await orderListPage.navbar.goToOrders();

        await orderListPage.clickCreateOrder();
        
        const customerName = '客戶 ' + Date.now();
        await page.locator('input[name="customer_name"]').fill(customerName);
        
        # This part assumes you have a UI to select products and quantities for an order.
        # For now, it proceeds assuming a single item can be added or the form handles it implicitly.
        # If your frontend requires explicit selection or addition of items,
        # you'll need to add Playwright actions here to interact with those elements.
        # Example: await page.locator('select[name="product_id"]').selectOption({ index: 0 });
        # Example: await page.locator('input[name="quantity"]').fill('1');
        # Example: await page.click('#add-item-button');

        await page.locator('button[type="submit"]').click();

        await expect(page).toHaveURL(/orders/);
        await expect(page.locator(`text="${customerName}"`)).toBeVisible();
    });

    test('should view order details', async ({ page }) => {
        let initialCount = await orderListPage.getOrderCount();
        if (initialCount === 0) {
            await orderListPage.navbar.goToProducts();
            const productCount = await productListPage.getProductCount();
            if (productCount === 0) {
                await productListPage.clickAddProduct();
                await page.locator('input[name="name"]').fill('用於訂單詳情的產品');
                await page.locator('input[name="price"]').fill('20.00');
                await page.locator('input[name="stock"]').fill('50');
                await page.locator('button[type="submit"]').click();
                await expect(page).toHaveURL(/products/);
            }
            await orderListPage.navbar.goToOrders();
            await orderListPage.clickCreateOrder();
            await page.locator('input[name="customer_name"]').fill('詳情客戶');
            await page.locator('button[type="submit"]').click();
            await expect(page).toHaveURL(/orders/);
            initialCount = await orderListPage.getOrderCount();
        }

        await orderListPage.clickOrderDetailsAtIndex(0);
        await expect(page).toHaveURL(/\/orders\/\d+/);
        await expect(page.locator('h1:has-text("訂單詳情")')).toBeVisible(); # Updated selector
    });

    test('should update order status', async ({ page }) => {
        let initialCount = await orderListPage.getOrderCount();
        if (initialCount === 0) {
            await orderListPage.navbar.goToProducts();
            const productCount = await productListPage.getProductCount();
            if (productCount === 0) {
                await productListPage.clickAddProduct();
                await page.locator('input[name="name"]').fill('用於訂單更新的產品');
                await page.locator('input[name="price"]').fill('30.00');
                await page.locator('input[name="stock"]').fill('60');
                await page.locator('button[type="submit"]').click();
                await expect(page).toHaveURL(/products/);
            }
            await orderListPage.navbar.goToOrders();
            await orderListPage.clickCreateOrder();
            await page.locator('input[name="customer_name"]').fill('更新客戶');
            await page.locator('button[type="submit"]').click();
            await expect(page).toHaveURL(/orders/);
            initialCount = orderListPage.getOrderCount();
        }

        await orderListPage.clickOrderDetailsAtIndex(0);
        await expect(page).toHaveURL(/\/orders\/\d+/);

        await page.locator('select[name="status"]').selectOption('completed');
        await page.locator('button#update-status-button').click();

        await expect(page.locator('.alert-success')).toHaveText(/訂單更新成功/); # Assuming success alert
        await expect(page.locator('.order-status')).toHaveText('completed');
    });

    test('should delete an order', async ({ page }) => {
        let initialCount = await orderListPage.getOrderCount();
        if (initialCount === 0) {
            await orderListPage.navbar.goToProducts();
            const productCount = await productListPage.getProductCount();
            if (productCount === 0) {
                await productListPage.clickAddProduct();
                await page.locator('input[name="name"]').fill('用於訂單刪除的產品');
                await page.locator('input[name="price"]').fill('40.00');
                await page.locator('input[name="stock"]').fill('70');
                await page.locator('button[type="submit"]').click();
                await expect(page).toHaveURL(/products/);
            }
            await orderListPage.navbar.goToOrders();
            await orderListPage.clickCreateOrder();
            await page.locator('input[name="customer_name"]').fill('刪除客戶');
            await page.locator('button[type="submit"]').click();
            await expect(page).toHaveURL(/orders/);
            initialCount = await orderListPage.getOrderCount();
        }

        const customerNameToDelete = await orderListPage.getOrderCustomerNameAtIndex(0);
        await orderListPage.clickOrderDetailsAtIndex(0);
        await page.locator('#delete-order-button').click();

        page.on('dialog', async dialog => {
            expect(dialog.type()).toContain('confirm');
            await dialog.accept();
        });

        await expect(page).toHaveURL(/orders/);
        await expect(page.locator(`text="${customerNameToDelete}"`)).not.toBeVisible();
        const finalCount = await orderListPage.getOrderCount();
        expect(finalCount).toBe(initialCount - 1);
    });
});
