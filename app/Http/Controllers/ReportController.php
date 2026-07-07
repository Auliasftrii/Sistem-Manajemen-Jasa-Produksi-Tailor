<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', date('Y-m-01'));
        $endDate = $request->query('end_date', date('Y-m-t'));

        // Query pesanan yang selesai dalam rentang waktu
        $orders = Order::with('customer', 'payments')
            ->where('status', 'completed')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $totalPendapatan = $orders->sum('total_amount');
        
        // Pendapatan riil dari payment di periode tersebut
        $pendapatanRiil = \App\Models\Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        return view('report.index', [
            'title' => 'Laporan Pendapatan',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'orders' => $orders,
            'totalPendapatan' => $totalPendapatan,
            'pendapatanRiil' => $pendapatanRiil,
        ]);
    }
}
