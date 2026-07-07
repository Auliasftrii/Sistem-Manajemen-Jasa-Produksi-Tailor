<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'measurements' => [
                'Lingkar Dada' => fake()->numberBetween(80, 120),
                'Lingkar Pinggang' => fake()->numberBetween(70, 110),
                'Panjang Baju' => fake()->numberBetween(60, 80),
                'Panjang Lengan' => fake()->numberBetween(20, 65),
                'Lebar Bahu' => fake()->numberBetween(35, 55),
            ],
        ];
    }
}
