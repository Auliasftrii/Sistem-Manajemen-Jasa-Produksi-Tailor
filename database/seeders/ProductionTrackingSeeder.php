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
        $orders = Order::all();
        $pegawai = User::where('role', 'Pegawai')->first();
        
        $stages = ['Pola', 'Potong', 'Jahit', 'Finishing'];

        foreach ($orders as $order) {
            // For half of the orders, add tracking logic
            if (rand(0, 1) == 1) {
                foreach ($stages as $index => $stage) {
                    $status = 'pending';
                    $started = null;
                    $completed = null;
                    $handler = null;
                    
                    if ($index < 2) {
                        $status = 'completed';
                        $started = now()->subDays(2);
                        $completed = now()->subDay(1);
                        $handler = $pegawai ? $pegawai->id : 1;
                    } elseif ($index == 2) {
                        $status = 'in_progress';
                        $started = now();
                        $handler = $pegawai ? $pegawai->id : 1;
                    }
                    
                    ProductionTracking::create([
                        'order_id' => $order->id,
                        'stage' => $stage,
                        'status' => $status,
                        'handled_by' => $handler,
                        'started_at' => $started,
                        'completed_at' => $completed,
                    ]);
                }
            } else {
                // Initialize as pending for all
                foreach ($stages as $stage) {
                    ProductionTracking::create([
                        'order_id' => $order->id,
                        'stage' => $stage,
                        'status' => 'pending'
                    ]);
                }
            }
        }
    }
}
