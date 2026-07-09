<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            ['stage_name' => 'Pola', 'sequence_order' => 1],
            ['stage_name' => 'Potong', 'sequence_order' => 2],
            ['stage_name' => 'Jahit', 'sequence_order' => 3],
            ['stage_name' => 'Finishing', 'sequence_order' => 4],
        ];

        foreach ($stages as $stage) {
            \App\Models\ProductionStage::create($stage);
        }
    }
}
