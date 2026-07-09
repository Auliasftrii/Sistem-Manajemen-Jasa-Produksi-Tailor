<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ProductionTracking;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::all();
        $stages = \App\Models\ProductionStage::orderBy('sequence_order')->get();
        $tailors = \App\Models\Tailor::all();

        if ($orders->count() > 0 && $stages->count() > 0 && $tailors->count() > 0) {
            foreach ($orders as $index => $order) {
                // Untuk contoh, selesaikan 2 tahap pertama dari setiap order
                $completedStages = $stages->take(2);

                foreach ($completedStages as $stageIndex => $stage) {
                    $tailor = $tailors->random();
                    // Distribusi hari acak untuk grafik Pegawai
                    $completionDate = now()->subDays(rand(0, 6)); 

                    \App\Models\ProductionTracking::create([
                        'order_id' => $order->id,
                        'production_stage_id' => $stage->id,
                        'tailor_id' => $tailor->id,
                        'status' => 'completed',
                        'started_at' => $completionDate->copy()->subHours(2), // 2 jam sebelumnya
                        'completed_at' => $completionDate
                    ]);
                }

                // Tambahkan 1 tahap on-progress
                if ($stages->count() > 2) {
                    $stage = $stages[2];
                    $tailor = $tailors->random();
                    
                    \App\Models\ProductionTracking::create([
                        'order_id' => $order->id,
                        'production_stage_id' => $stage->id,
                        'tailor_id' => $tailor->id,
                        'status' => 'in_progress',
                        'started_at' => now(),
                        'completed_at' => null
                    ]);
                }
            }
        }
    }
}
