<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer', 'payments')
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->get();

        return view('payment.index', [
            'title' => 'Manajemen Pembayaran',
            'orders' => $orders,
        ]);
    }

    public function create(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = null;
        $sisaTagihan = 0;

        if ($orderId) {
            $order = Order::with('payments', 'customer')->findOrFail($orderId);
            $totalDibayar = $order->payments->sum('amount');
            $sisaTagihan = $order->total_amount - $totalDibayar;
        }

        return view('payment.create', [
            'title' => 'Tambah Pembayaran',
            'orders' => Order::where('status', '!=', 'cancelled')->latest()->get(),
            'selectedOrder' => $order,
            'sisaTagihan' => $sisaTagihan,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'status' => 'required|in:DP,Pelunasan',
        ]);

        $order = Order::findOrFail($request->order_id);
        $totalDibayar = $order->payments->sum('amount');
        $sisaTagihan = $order->total_amount - $totalDibayar;

        if ($request->amount > $sisaTagihan) {
            return back()->withInput()->withError('Jumlah bayar tidak boleh melebihi sisa tagihan (Rp ' . number_format($sisaTagihan, 0, ',', '.') . ')');
        }

        try {
            Payment::create($request->all());
            return to_route('payment.index')->withSuccess('Pembayaran berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->withInput()->withError('Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function print(Order $order)
    {
        $order->load(['customer', 'payments']);
        return view('payment.print', [
            'title' => 'Cetak Struk Pembayaran',
            'order' => $order
        ]);
    }

    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();
            return back()->withSuccess('Pembayaran berhasil dibatalkan/dihapus.');
        } catch (\Exception $e) {
            return back()->withError('Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
