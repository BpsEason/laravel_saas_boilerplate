<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\OrderController; // Assuming you'll have web views for these too

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main landing page - accessible to everyone
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes (for Blade/Web UI)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Tenant-aware and authenticated routes for web UI
// These routes assume a logged-in user and a resolved tenant context.
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Products Management UI
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () {
            return view('products.index');
        })->name('index');
        Route::get('/create', function () {
            return view('products.create');
        })->name('create');
        Route::get('/{product}/edit', function ($product) {
            return view('products.edit', ['product_id' => $product]);
        })->name('edit');
    });

    // Orders Management UI
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () {
            return view('orders.index');
        })->name('index');
        Route::get('/create', function () {
            return view('orders.create');
        })->name('create');
        Route::get('/{order}', function ($order) {
            return view('orders.show', ['order_id' => $order]);
        })->name('show');
    });

    // Logout via web (optional, if you're not solely relying on API logout)
    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// If you need any specific routes that are tenant-aware but not auth-protected (less common for SaaS dashboards)
// Route::middleware('tenant')->group(function () {
//     // Example: public tenant landing page
//     Route::get('/public-tenant-page', function () {
//         return 'This is a public page for ' . \Spatie\Multitenancy\Models\Tenant::current()->name;
//     });
// });
