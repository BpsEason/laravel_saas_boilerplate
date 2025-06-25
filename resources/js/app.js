// resources/js/app.js

import './bootstrap';

// Example: Simple logic for a global message/toast display
window.showToast = function(message, type = 'success') {
    const toast = document.createElement('div');
    toast.classList.add('toast-message');
    if (type === 'error') {
        toast.classList.add('error');
    }
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 100); // Small delay to allow reflow for transition

    setTimeout(() => {
        toast.classList.remove('show');
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }, 5000); // Hide after 5 seconds
};


// Example: Fetch data for product/order listings (if using simple Blade + JS)
// This is a placeholder. For complex SPAs, you'd use a framework like React/Vue.
document.addEventListener('DOMContentLoaded', () => {
    // Check if on product index page
    if (document.getElementById('products-list-page')) {
        fetchProducts();
    }
    // Check if on order index page
    if (document.getElementById('orders-list-page')) {
        fetchOrders();
    }
    // Check if on specific order show page
    if (document.getElementById('order-show-page')) {
        const orderId = document.getElementById('order-show-page').dataset.orderId;
        fetchOrderDetails(orderId);
    }
});

async function fetchProducts() {
    try {
        const response = await fetch('/api/v1/products', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('authToken')}` // Assuming token stored here
            }
        });
        const data = await response.json();
        if (data.status === 'success' && data.data) {
            renderProducts(data.data);
        } else {
            console.error('Failed to fetch products:', data.message || 'Unknown error');
            window.showToast(data.message || 'Failed to load products.', 'error');
        }
    } catch (error) {
        console.error('Error fetching products:', error);
        window.showToast('Error connecting to API for products.', 'error');
    }
}

function renderProducts(products) {
    const tbody = document.querySelector('.product-table tbody');
    if (!tbody) return;
    tbody.innerHTML = ''; // Clear existing rows

    if (products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No products found.</td></tr>';
        return;
    }

    products.forEach(product => {
        const row = `
            <tr>
                <td>${product.id}</td>
                <td class="product-name">${product.name}</td>
                <td>${product.description || '-'}</td>
                <td>$${parseFloat(product.price).toFixed(2)}</td>
                <td>${product.stock}</td>
                <td>
                    <a href="/products/${product.id}/edit" class="action-button">編輯</a>
                    <button class="action-button delete-button" data-product-id="${product.id}">刪除</button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });

    // Add event listeners for delete buttons
    tbody.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', async (event) => {
            const productId = event.target.dataset.productId;
            if (confirm('確定要刪除這個產品嗎？')) {
                await deleteProduct(productId);
            }
        });
    });
}

async function deleteProduct(productId) {
    try {
        const response = await fetch(`/api/v1/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('authToken')}`
            }
        });
        const data = await response.json();
        if (data.status === 'success') {
            window.showToast('產品已成功刪除。');
            fetchProducts(); // Refresh the list
        } else {
            window.showToast(data.message || '刪除產品失敗。', 'error');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        window.showToast('連線 API 刪除產品失敗。', 'error');
    }
}

async function fetchOrders() {
    try {
        const response = await fetch('/api/v1/orders', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('authToken')}`
            }
        });
        const data = await response.json();
        if (data.status === 'success' && data.data) {
            renderOrders(data.data);
        } else {
            console.error('Failed to fetch orders:', data.message || 'Unknown error');
            window.showToast(data.message || 'Failed to load orders.', 'error');
        }
    } catch (error) {
        console.error('Error fetching orders:', error);
        window.showToast('Error connecting to API for orders.', 'error');
    }
}

function renderOrders(orders) {
    const tbody = document.querySelector('.order-table tbody');
    if (!tbody) return;
    tbody.innerHTML = ''; // Clear existing rows

    if (orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No orders found.</td></tr>';
        return;
    }

    orders.forEach(order => {
        const row = `
            <tr>
                <td>${order.id}</td>
                <td class="order-customer-name">${order.customer_name}</td>
                <td>$${parseFloat(order.total_amount).toFixed(2)}</td>
                <td class="order-status">${order.status}</td>
                <td>${order.created_at.split(' ')[0]}</td>
                <td>
                    <a href="/orders/${order.id}" class="action-button order-details-link">查看</a>
                    <button class="action-button delete-button" data-order-id="${order.id}">刪除</button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
    });

    // Add event listeners for delete buttons
    tbody.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', async (event) => {
            const orderId = event.target.dataset.orderId;
            if (confirm('確定要刪除這個訂單嗎？這將恢復庫存。')) {
                await deleteOrder(orderId);
            }
        });
    });
}

async function deleteOrder(orderId) {
    try {
        const response = await fetch(`/api/v1/orders/${orderId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('authToken')}`
            }
        });
        const data = await response.json();
        if (data.status === 'success') {
            window.showToast('訂單已成功刪除。庫存已恢復。');
            fetchOrders(); // Refresh the list
        } else {
            window.showToast(data.message || '刪除訂單失敗。', 'error');
        }
    } catch (error) {
        console.error('Error deleting order:', error);
        window.showToast('連線 API 刪除訂單失敗。', 'error');
    }
}

async function fetchOrderDetails(orderId) {
    try {
        const response = await fetch(`/api/v1/orders/${orderId}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('authToken')}`
            }
        });
        const data = await response.json();
        if (data.status === 'success' && data.data) {
            renderOrderDetails(data.data);
        } else {
            console.error('Failed to fetch order details:', data.message || 'Unknown error');
            window.showToast(data.message || 'Failed to load order details.', 'error');
        }
    } catch (error) {
        console.error('Error fetching order details:', error);
        window.showToast('Error connecting to API for order details.', 'error');
    }
}

function renderOrderDetails(order) {
    const detailsContainer = document.getElementById('order-details-container');
    if (!detailsContainer) return;

    detailsContainer.innerHTML = `
        <h2 class="text-xl font-bold mb-4">訂單 #${order.id} 詳細資訊</h2>
        <div class="mb-4">
            <p><strong>顧客名稱:</strong> ${order.customer_name}</p>
            <p><strong>總金額:</strong> $${parseFloat(order.total_amount).toFixed(2)}</p>
            <p><strong>狀態:</strong> <span class="order-status">${order.status}</span></p>
            <p><strong>創建日期:</strong> ${order.created_at.split(' ')[0]}</p>
        </div>

        <h3 class="text-lg font-semibold mb-3">訂單項目</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>產品名稱</th>
                    <th>數量</th>
                    <th>單價</th>
                    <th>小計</th>
                </tr>
            </thead>
            <tbody>
                ${order.items.map(item => `
                    <tr>
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>$${parseFloat(item.price_per_unit).toFixed(2)}</td>
                        <td>$${parseFloat(item.subtotal).toFixed(2)}</td>
                    </tr>
                `).join('')}
                ${order.items.length === 0 ? '<tr><td colspan="4">無訂單項目。</td></tr>' : ''}
            </tbody>
        </table>

        <div class="mt-6 flex justify-end">
            <button id="update-status-button" class="action-button mr-2">更新狀態</button>
            <button id="delete-order-button" class="action-button delete-button">刪除訂單</button>
        </div>
    `;

    // Add event listener for update status button
    const updateStatusButton = document.getElementById('update-status-button');
    if (updateStatusButton) {
        updateStatusButton.addEventListener('click', async () => {
            const newStatus = prompt('輸入新的訂單狀態 (pending, confirmed, shipped, completed, cancelled):', order.status);
            if (newStatus) {
                await updateOrderStatus(order.id, newStatus);
            }
        });
    }

    // Add event listener for delete order button
    const deleteOrderButton = document.getElementById('delete-order-button');
    if (deleteOrderButton) {
        deleteOrderButton.addEventListener('click', async () => {
            if (confirm('確定要刪除這個訂單嗎？這將恢復庫存。')) {
                await deleteOrder(order.id);
                // After deletion, redirect back to orders list
                window.location.href = '/orders';
            }
        });
    }
}

async function updateOrderStatus(orderId, newStatus) {
    try {
        const response = await fetch(`/api/v1/orders/${orderId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('authToken')}`
            },
            body: JSON.stringify({ status: newStatus })
        });
        const data = await response.json();
        if (data.status === 'success') {
            window.showToast('訂單狀態已成功更新。');
            fetchOrderDetails(orderId); // Refresh details
        } else {
            window.showToast(data.message || '更新訂單狀態失敗。', 'error');
        }
    } catch (error) {
        console.error('Error updating order status:', error);
        window.showToast('連線 API 更新訂單狀態失敗。', 'error');
    }
}


// --- Authentication / Token Handling for client-side JS ---
document.addEventListener('DOMContentLoaded', () => {
    // Handle login form submission
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const email = loginForm.email.value;
            const password = loginForm.password.value;

            try {
                const response = await fetch('/api/v1/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();
                if (response.ok && data.token) {
                    localStorage.setItem('authToken', data.token);
                    localStorage.setItem('userEmail', email); // Store email for display or tenant detection
                    window.showToast('登入成功！');
                    window.location.href = '/dashboard'; // Redirect to dashboard
                } else {
                    const errorMessage = data.message || (data.errors && data.errors.email ? data.errors.email[0] : '登入失敗，請檢查憑證。');
                    window.showToast(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Login error:', error);
                window.showToast('網路錯誤或伺服器無回應。', 'error');
            }
        });
    }

    // Handle registration form submission
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const name = registerForm.name.value;
            const email = registerForm.email.value;
            const password = registerForm.password.value;
            const password_confirmation = registerForm.password_confirmation.value;
            const tenant_name = registerForm.tenant_name.value;
            const tenant_domain = registerForm.tenant_domain.value;

            try {
                const response = await fetch('/api/v1/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, email, password, password_confirmation, tenant_name, tenant_domain })
                });
                const data = await response.json();
                if (response.ok && data.token) {
                    localStorage.setItem('authToken', data.token);
                    localStorage.setItem('userEmail', email); // Store email
                    window.showToast('註冊成功！歡迎加入。');
                    window.location.href = '/dashboard'; // Redirect to dashboard
                } else {
                    let errorMessage = data.message || '註冊失敗。';
                    if (data.errors) {
                        for (const key in data.errors) {
                            errorMessage += `\n${data.errors[key].join(', ')}`;
                        }
                    }
                    window.showToast(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Registration error:', error);
                window.showToast('網路錯誤或伺服器無回應。', 'error');
            }
        });
    }

    // Handle logout button click
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', async (event) => {
            event.preventDefault();
            try {
                const response = await fetch('/api/v1/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                    }
                });
                const data = await response.json();
                if (response.ok) {
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('userEmail');
                    window.showToast('已成功登出。');
                    window.location.href = '/'; // Redirect to landing page
                } else {
                    window.showToast(data.message || '登出失敗。', 'error');
                }
            } catch (error) {
                console.error('Logout error:', error);
                window.showToast('網路錯誤或伺服器無回應。', 'error');
            }
        });
    }

    // Product create/edit form submission
    const productForm = document.getElementById('product-form');
    if (productForm) {
        productForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const productId = productForm.dataset.productId; // Will be undefined for create
            const url = productId ? `/api/v1/products/${productId}` : '/api/v1/products';
            const method = productId ? 'PUT' : 'POST';

            const formData = {
                name: productForm.name.value,
                description: productForm.description.value,
                price: parseFloat(productForm.price.value),
                stock: parseInt(productForm.stock.value, 10),
            };

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                    },
                    body: JSON.stringify(formData)
                });
                const data = await response.json();
                if (response.ok && data.status === 'success') {
                    window.showToast(`產品已成功${productId ? '更新' : '創建'}。`);
                    window.location.href = '/products'; // Redirect to product list
                } else {
                    let errorMessage = data.message || `產品${productId ? '更新' : '創建'}失敗。`;
                    if (data.errors) {
                        for (const key in data.errors) {
                            errorMessage += `\n${data.errors[key].join(', ')}`;
                        }
                    }
                    window.showToast(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Product form submission error:', error);
                window.showToast('網路錯誤或伺服器無回應。', 'error');
            }
        });
    }

    // Load product data for edit page
    if (document.getElementById('product-edit-page')) {
        const productId = document.getElementById('product-edit-page').dataset.productId;
        fetchProductForEdit(productId);
    }

    async function fetchProductForEdit(productId) {
        try {
            const response = await fetch(`/api/v1/products/${productId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                }
            });
            const data = await response.json();
            if (data.status === 'success' && data.data) {
                const product = data.data;
                document.getElementById('product-name').value = product.name;
                document.getElementById('product-description').value = product.description;
                document.getElementById('product-price').value = product.price;
                document.getElementById('product-stock').value = product.stock;
            } else {
                window.showToast(data.message || '載入產品資訊失敗。', 'error');
                console.error('Failed to fetch product for edit:', data.message);
            }
        } catch (error) {
            console.error('Error fetching product for edit:', error);
            window.showToast('連線 API 載入產品資訊失敗。', 'error');
        }
    }


    // Order create form submission
    const orderForm = document.getElementById('order-form');
    if (orderForm) {
        // Fetch products to populate the product dropdown for new orders
        fetchProductsForOrderForm();

        orderForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const customer_name = orderForm.customer_name.value;
            const items = [];
            // Assuming multiple item rows can be added, or at least one is present
            // For this simple example, we'll assume one product_id and quantity input
            const productId = orderForm.product_id ? orderForm.product_id.value : null;
            const quantity = orderForm.quantity ? parseInt(orderForm.quantity.value, 10) : null;

            if (productId && quantity) {
                items.push({ product_id: productId, quantity: quantity });
            } else {
                window.showToast('請至少添加一個訂單項目。', 'error');
                return;
            }

            try {
                const response = await fetch('/api/v1/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                    },
                    body: JSON.stringify({ customer_name, items })
                });
                const data = await response.json();
                if (response.ok && data.status === 'success') {
                    window.showToast('訂單已成功創建！');
                    window.location.href = '/orders'; // Redirect to order list
                } else {
                    let errorMessage = data.message || '創建訂單失敗。';
                    if (data.errors) {
                        for (const key in data.errors) {
                            errorMessage += `\n${data.errors[key].join(', ')}`;
                        }
                    }
                    window.showToast(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Order form submission error:', error);
                window.showToast('網路錯誤或伺服器無回應。', 'error');
            }
        });
    }

    async function fetchProductsForOrderForm() {
        try {
            const response = await fetch('/api/v1/products', { // Fetch all products for dropdown
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                }
            });
            const data = await response.json();
            if (data.status === 'success' && data.data) {
                const productSelect = document.getElementById('product-id');
                if (productSelect) {
                    productSelect.innerHTML = '<option value="">請選擇產品</option>';
                    data.data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = `${product.name} (庫存: ${product.stock}, 單價: $${parseFloat(product.price).toFixed(2)})`;
                        productSelect.appendChild(option);
                    });
                }
            } else {
                window.showToast(data.message || '載入產品列表失敗。', 'error');
            }
        } catch (error) {
            console.error('Error fetching products for order form:', error);
            window.showToast('連線 API 載入產品列表失敗。', 'error');
        }
    }
});
