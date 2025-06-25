<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard'; # Changed to dashboard

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->routes(function () {
            # Define API routes. These routes are stateless.
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            # Define web routes. These routes are stateful.
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            /*
             * Multi-tenancy routes can be defined here or in a dedicated file.
             *
             * If you're using Spatie\Multitenancy\TenantFinder\DomainTenantFinder,
             * you might define tenant-specific routes or logic based on the domain.
             *
             * Example:
             * Route::middleware(['web', 'tenant'])->domain('{tenant}.' . config('app.domain'))->group(function () {
             * Route::get('/dashboard', [App\Http\Controllers\TenantDashboardController::class, 'index'])->name('tenant.dashboard');
             * });
             *
             * Or for fallback for non-tenant contexts:
             * Route::fallback(function () {
             * # Handle requests that don't match any route, especially if no tenant is set
             * # E.g., redirect to main landing page or show a generic error
             * return redirect('/'); # Or abort(404);
             * });
             */
        });
    }
}
