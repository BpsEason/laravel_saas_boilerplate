<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Tenant;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        # Create example tenants
        $tenant1 = Tenant::firstOrCreate(['name' => 'Tenant A', 'domain' => 'tenant-a.localhost:8000']);
        $tenant2 = Tenant::firstOrCreate(['name' => 'Tenant B', 'domain' => 'tenant-b.localhost:8000']);

        # Seed data for Tenant A
        $tenant1->makeCurrent();
        User::factory()->create([
            'name' => 'Tenant A User',
            'email' => 'tenant.a@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant1->id,
        ]);
        Product::factory()->count(10)->create(['tenant_id' => $tenant1->id]);
        Order::factory()->count(5)->create(['tenant_id' => $tenant1->id]);
        $tenant1->forgetCurrent();

        # Seed data for Tenant B
        $tenant2->makeCurrent();
        User::factory()->create([
            'name' => 'Tenant B User',
            'email' => 'tenant.b@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant2->id,
        ]);
        Product::factory()->count(15)->create(['tenant_id' => $tenant2->id]);
        Order::factory()->count(7)->create(['tenant_id' => $tenant2->id]);
        $tenant2->forgetCurrent();

        echo "Database seeding complete for tenants: Tenant A and Tenant B.\n";
        echo "Example users: tenant.a@example.com (pass: password), tenant.b@example.com (pass: password)\n";
    }
}
