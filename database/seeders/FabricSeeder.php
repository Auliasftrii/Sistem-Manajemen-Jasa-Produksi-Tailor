<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FabricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fabrics = [
            [
                'name' => 'Katun Toyobo Premium',
                'fabric_type' => 'Katun',
                'color' => 'Putih Tulang',
            ],
            [
                'name' => 'Linen Rami Grade A',
                'fabric_type' => 'Linen',
                'color' => 'Coklat Susu',
            ],
            [
                'name' => 'Drill American',
                'fabric_type' => 'Drill',
                'color' => 'Hitam Pekat',
            ]
        ];

        foreach ($fabrics as $f) {
            $fabric = \App\Models\Fabric::create($f);
            \App\Models\FabricStock::create([
                'fabric_id' => $fabric->id,
                'quantity_in_meters' => rand(20, 100),
                'last_restock_date' => now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
