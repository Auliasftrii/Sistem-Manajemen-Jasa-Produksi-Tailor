<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductionStage;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index', [
            'title' => 'Lacak Pesanan',
            'order' => null
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
        ]);

        $order = Order::with(['customer', 'items', 'payments', 'trackings.productionStage', 'revisions'])
            ->where('invoice_number', $request->invoice_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'Data pesanan tidak ditemukan, periksa kembali Nomor Invoice Anda.')->withInput();
        }

        // Calculate totals
        $totalPaid = $order->payments->sum('amount');
        $sisaTagihan = $order->total_amount - $totalPaid;
        
        $productionStages = ProductionStage::orderBy('sequence_order')->get();

        return view('tracking.index', [
            'title' => 'Hasil Lacak Pesanan',
            'order' => $order,
            'totalPaid' => $totalPaid,
            'sisaTagihan' => $sisaTagihan,
            'productionStages' => $productionStages
        ]);
    }
}
