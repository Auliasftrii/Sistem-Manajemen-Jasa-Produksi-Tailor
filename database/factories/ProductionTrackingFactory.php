<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionTracking>
 */
class ProductionTrackingFactory extends Factory
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
            'stage' => fake()->randomElement(['Pola', 'Potong', 'Jahit', 'Finishing']),
            'status' => 'pending',
            'handled_by' => null,
            'started_at' => null,
            'completed_at' => null,
        ];
    }
}
