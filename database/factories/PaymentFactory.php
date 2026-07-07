<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'amount' => 100000,
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'payment_method' => fake()->randomElement(['Cash', 'Transfer Bank']),
            'status' => 'DP',
        ];
    }
}
