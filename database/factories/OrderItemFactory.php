<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 5);
        $price = fake()->randomElement([150000, 200000, 250000, 350000, 500000]);
        
        return [
            'order_id' => Order::factory(),
            'product_type' => fake()->randomElement(['Kemeja Pria', 'Celana Panjang', 'Jas Hujan', 'Gamis', 'Rok Plisket']),
            'fabric_details' => fake()->randomElement(['Kain Katun', 'Kain Linen', 'Kain Drill', 'Sutra', 'Denim']),
            'quantity' => $qty,
            'unit_price' => $price,
            'subtotal' => $qty * $price,
        ];
    }
}
