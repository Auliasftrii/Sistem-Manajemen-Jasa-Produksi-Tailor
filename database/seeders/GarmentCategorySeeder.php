<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GarmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Kemeja Pria', 'description' => 'Pakaian atasan pria berkerah'],
            ['name' => 'Celana Pria', 'description' => 'Celana panjang atau pendek pria'],
            ['name' => 'Jas / Blazer', 'description' => 'Jas formal atau semi-formal'],
            ['name' => 'Kebaya', 'description' => 'Pakaian tradisional wanita'],
            ['name' => 'Gamis / Dress', 'description' => 'Pakaian panjang wanita'],
        ];

        foreach ($categories as $category) {
            \App\Models\GarmentCategory::create($category);
        }
    }
}
