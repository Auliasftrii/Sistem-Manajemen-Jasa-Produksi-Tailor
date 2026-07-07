<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 month', 'now');
        return [
            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'invoice_number' => 'INV-' . $date->format('Ymd') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'order_date' => $date,
            'expected_completion_date' => fake()->dateTimeBetween($date, '+1 month'),
            'total_amount' => 0, // will be calculated in seeder
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
