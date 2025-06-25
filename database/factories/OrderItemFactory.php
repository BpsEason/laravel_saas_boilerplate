<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            # 'order_id' will be set by the OrderFactory's afterCreating callback
            # 'product_id' will be set by the OrderFactory's afterCreating callback
            'quantity' => $this->faker->numberBetween(1, 10),
            'price_per_unit' => $this->faker->randomFloat(2, 5, 500),
        ];
    }
}
