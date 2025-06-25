<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Multitenancy\Models\Tenant;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        # Ensure a user exists for the current tenant or create one
        $tenantId = Tenant::current()?->id;
        $user = User::where('tenant_id', $tenantId)->inRandomOrder()->first();

        if (!$user && $tenantId) {
            # Create a user for this tenant if none exists
            $user = User::factory()->create([
                'tenant_id' => $tenantId,
                'email' => 'test_user_' . $tenantId . '_' . $this->faker->unique()->randomNumber(5) . '@example.com',
            ]);
        } elseif (!$user && !$tenantId) {
             # Fallback for landlord context if needed, create a dummy user
             $user = User::factory()->create([
                'email' => 'landlord_user_' . $this->faker->unique()->randomNumber(5) . '@example.com',
             ]);
        }

        return [
            'tenant_id' => $tenantId, # Automatically assigned by ForCurrentTenant
            'user_id' => $user->id,
            'name' => $this->faker->word() . ' ' . $this->faker->randomNumber(3, true) . ' Product',
            'description' => $this->faker->paragraph(2),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
