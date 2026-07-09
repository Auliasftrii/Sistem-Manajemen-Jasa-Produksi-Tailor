<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderRevisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::where('status', 'completed')->take(2)->get();
        
        if ($orders->count() > 0) {
            \App\Models\OrderRevision::create([
                'order_id' => $orders[0]->id,
                'revision_notes' => 'Jahitan pada bagian kerah sedikit melenceng.',
                'status' => 'Pending',
                'reported_at' => now()->subDays(2),
            ]);

            if (isset($orders[1])) {
                \App\Models\OrderRevision::create([
                    'order_id' => $orders[1]->id,
                    'revision_notes' => 'Ukuran celana bagian pinggang terlalu ketat, mohon dilonggarkan 2cm.',
                    'status' => 'Resolved',
                    'reported_at' => now()->subDays(5),
                    'resolved_at' => now()->subDays(1),
                ]);
            }
        }
    }
}
