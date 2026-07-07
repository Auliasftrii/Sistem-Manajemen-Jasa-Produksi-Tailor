<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil 5 order pertama
        $orders = Order::where('status', '!=', 'cancelled')->take(5)->get();

        if ($orders->count() >= 5) {
            // 3 transaksi lunas
            for ($i = 0; $i < 3; $i++) {
                $order = $orders[$i];
                
                // DP 50%
                $dpAmount = $order->total_amount * 0.5;
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $dpAmount,
                    'payment_date' => $order->order_date,
                    'payment_method' => 'Cash',
                    'status' => 'DP'
                ]);

                // Pelunasan 50%
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount - $dpAmount,
                    'payment_date' => now(),
                    'payment_method' => 'Transfer Bank',
                    'status' => 'Pelunasan'
                ]);
            }

            // 2 transaksi baru DP
            for ($i = 3; $i < 5; $i++) {
                $order = $orders[$i];
                $dpAmount = $order->total_amount * 0.5;

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $dpAmount,
                    'payment_date' => $order->order_date,
                    'payment_method' => 'Cash',
                    'status' => 'DP'
                ]);
            }
        }
    }
}
