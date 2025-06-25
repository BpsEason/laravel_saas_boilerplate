<?php

return [
    /*
     * This class is responsible for determining which tenant should be current.
     * You can provide your own class, or extend this one.
     */
    'tenant_finder' => Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class, # Enabled DomainTenantFinder

    /*
     * The `tenant_column` is the column used to identify the tenant in the database.
     */
    'tenant_column' => 'tenant_id',

    /*
     * The connection name to reach the tenant database.
     *
     * Set to `null` to use the default connection.
     */
    'tenant_db_connection_name' => null,

    /*
     * The connection name to reach the landlord database.
     *
     * Set to `null` to use the default connection.
     */
    'landlord_db_connection_name' => null,

    /*
     * These fields are used by the DomainTenantFinder to find the tenant.
     * IMPORTANT: You need to set your actual tenant domains here for local testing.
     * For example, 'mytenant.localhost:8000' => 1 for tenant with ID 1.
     * Or if using dynamic domains, ensure your web server config routes them correctly.
     */
    'tenant_domains' => [
        'tenant-a.localhost:8000' => 1, # Example: map tenant-a.localhost:8000 to tenant ID 1 (for E2E tests)
        'tenant-b.localhost:8000' => 2, # Example: map tenant-b.localhost:8000 to tenant ID 2 (for E2E tests)
        # Use a wildcard subdomain if you manage DNS: '{tenant}.yourdomain.com' => null # will use domain property in tenant model
        # You'll need to configure your web server (Nginx/Apache) for subdomain routing to Laravel.
    ],

    /*
     * You can customize the way the current tenant is stored.
     */
    'tenant_storage' => Spatie\Multitenancy\TenantStorage\SessionTenantStorage::class,

    /*
     * If the current request does not have a tenant set, these actions will be performed.
     * You can add your own custom actions here.
     */
    'actions_when_no_tenant_set' => [
        Spatie\Multitenancy\Actions\ForbidRequestWhenNoTenantIsSet::class, # Forbid requests if no tenant is set
        # Spatie\Multitenancy\Actions\RedirectToTenantAwareUrlWhenNoTenantIsSet::class, # Uncomment to redirect
        # App\Http\Middleware\RedirectToLandingPageIfNoTenant::class, # Custom action example
    ],

    /*
     * If the current request has a tenant set, these actions will be performed.
     * You can add your own custom actions here.
     */
    'actions_when_tenant_set' => [
        Spatie\Multitenancy\Actions\SwitchDatabaseAction::class,
        Spatie\Multitenancy\Actions\ForbidDefaultRoutesWhenTenantIsSet::class,
    ],

    /*
     * You can configure per-tenant migrations.
     */
    'tenant_migrations_paths' => [
        database_path('migrations/tenant'),
    ],

    /*
     * You can define a custom `Tenant` model.
     */
    'tenant_model' => Spatie\Multitenancy\Models\Tenant::class,

    /*
     * Should the commands `migrate`, `db:seed`, `cache:clear`, etc. be tenant aware?
     */
    'artisan_commands_are_tenant_aware_by_default' => true,
];
