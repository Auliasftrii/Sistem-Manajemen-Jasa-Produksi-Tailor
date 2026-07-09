<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 10 order
        for ($i = 0; $i < 10; $i++) {
            $order = Order::factory()->create([
                // Distribusikan tanggal order ke 7 hari terakhir secara acak atau berurutan
                'order_date' => now()->subDays(rand(0, 6)),
            ]);

            // Setiap order punya 1 sampai 3 items
            $items = OrderItem::factory(rand(1, 3))->create([
                'order_id' => $order->id
            ]);

            // Hitung total_amount
            $order->update([
                'total_amount' => $items->sum('subtotal')
            ]);
        }
    }
}
