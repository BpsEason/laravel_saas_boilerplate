// tests/e2e/utils/test-helpers.js
// This file contains utility functions for Playwright tests, such as generating unique data.

/**
 * Generates a unique email for testing purposes.
 * @returns {string} A unique email address.
 */
export function generateRandomEmail() {
    return `test-user-${Date.now()}-${Math.floor(Math.random() * 1000)}@example.com`;
}

/**
 * Generates a unique tenant domain for testing.
 * @returns {string} A unique tenant domain.
 */
export function generateRandomTenantDomain() {
    return `tenant-${Date.now()}-${Math.floor(Math.random() * 1000)}.localhost:8000`;
}

# You can add more helpers here, e.g., for direct API calls to set up test data
# Example for an API helper (requires 'node-fetch' or similar in Playwright environment)
# async function createProductViaApi(baseURL, token, productData) {
#     const response = await fetch(`${baseURL}/api/v1/products`, {
#         method: 'POST',
#         headers: {
#             'Authorization': `Bearer ${token}`,
#             'Content-Type': 'application/json',
#             'Accept': 'application/json'
#         },
#         body: JSON.stringify(productData)
#     });
#     return response.json();
# }

# async function resetDatabaseViaApi(baseURL, token) {
#     # This would be a custom API endpoint in your Laravel app for testing only
#     const response = await fetch(`${baseURL}/api/testing/reset-db`, {
#         method: 'POST',
#         headers: {
#             'Authorization': `Bearer ${token}`,
#             'Content-Type': 'application/json',
#         },
#     });
#     return response.json();
# }
