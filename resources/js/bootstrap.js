// resources/js/bootstrap.js

import _ from 'lodash';
window._ = _;

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true; // Important for Sanctum CSRF

// Set a default Authorization header for Axios if a token exists in localStorage
const authToken = localStorage.getItem('authToken');
if (authToken) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`;
}

// Example: Intercept Axios responses to handle unauthorized errors
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401 && error.response?.config.url !== '/api/v1/login') {
            // If 401 and not trying to log in, likely token expired or invalid
            localStorage.removeItem('authToken');
            localStorage.removeItem('userEmail');
            window.showToast('您的會話已過期，請重新登入。', 'error');
            window.location.href = '/login'; // Redirect to login page
        }
        return Promise.reject(error);
    }
);
