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
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (\App\Models\Customer $customer) {
            $categories = \App\Models\GarmentCategory::inRandomOrder()->take(rand(1, 2))->get();
            foreach ($categories as $cat) {
                $customer->measurements()->createMany([
                    ['garment_category_id' => $cat->id, 'measurement_key' => 'Lingkar Dada', 'measurement_value' => fake()->numberBetween(80, 120)],
                    ['garment_category_id' => $cat->id, 'measurement_key' => 'Lingkar Pinggang', 'measurement_value' => fake()->numberBetween(70, 110)],
                    ['garment_category_id' => $cat->id, 'measurement_key' => 'Panjang Baju', 'measurement_value' => fake()->numberBetween(60, 80)],
                ]);
            }
        });
    }
}
