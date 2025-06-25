<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Multitenancy\Models\Tenant;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tenantId = Tenant::current()?->id;
        $user = User::where('tenant_id', $tenantId)->inRandomOrder()->first();

        if (!$user && $tenantId) {
            $user = User::factory()->create(['tenant_id' => $tenantId]);
        } elseif (!$user && !$tenantId) {
            $user = User::factory()->create();
        }

        return [
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'customer_name' => $this->faker->name(),
            'total_amount' => $this->faker->randomFloat(2, 50, 5000),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'shipped', 'completed', 'cancelled']),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::where('tenant_id', $order->tenant_id)->inRandomOrder()->take($this->faker->numberBetween(1, 3))->get();
            if ($products->isEmpty()) {
                $products = Product::factory()->count(3)->create(['tenant_id' => $order->tenant_id, 'user_id' => $order->user_id]);
            }

            foreach ($products as $product) {
                $quantity = $this->faker->numberBetween(1, min(5, $product->stock > 0 ? $product->stock : 1));
                if ($quantity > 0) {
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price_per_unit' => $product->price,
                    ]);
                    $product->decrement('stock', $quantity);
                }
            }
        });
    }
}
