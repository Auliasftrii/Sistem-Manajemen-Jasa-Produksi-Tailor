<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        return view('order.index', [
            'title' => 'Pesanan',
            'orders' => Order::with(['customer', 'user'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('order.create', [
            'title' => 'Buat Pesanan Baru',
            'customers' => Customer::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after_or_equal:order_date',
            'items' => 'required|array|min:1',
            'items.*.product_type' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'items.required' => 'Minimal 1 item pesanan wajib ada.',
        ]);

        try {
            DB::beginTransaction();

            // Generate Invoice Number
            $latestOrder = Order::latest('id')->first();
            $nextId = $latestOrder ? $latestOrder->id + 1 : 1;
            $invoiceNumber = 'INV-' . date('Ymd', strtotime($request->order_date)) . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id() ?? 1, // Fallback to 1 if not logged in (e.g. for testing)
                'invoice_number' => $invoiceNumber,
                'order_date' => $request->order_date,
                'expected_completion_date' => $request->expected_completion_date,
                'status' => 'pending',
                'total_amount' => 0, // Akan dihitung nanti
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_type' => $item['product_type'],
                    'fabric_details' => $item['fabric_details'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return to_route('order.index')->withSuccess('Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('order.create')->withError('Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'user', 'items']);
        return view('order.show', [
            'title' => 'Detail Pesanan',
            'order' => $order,
        ]);
    }

    public function edit(Order $order)
    {
        // Untuk penyederhanaan pada task ini, kita asumsikan update hanya status dan tanggal, 
        // namun untuk kebutuhan master-detail penuh, bisa diimplementasikan juga.
        // Di sini kita akan membuat form edit status sederhana.
        $order->load(['customer', 'items']);
        return view('order.edit', [
            'title' => 'Edit Pesanan',
            'order' => $order,
            'customers' => Customer::all(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'expected_completion_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        try {
            $order->update([
                'expected_completion_date' => $request->expected_completion_date,
                'status' => $request->status,
            ]);
            return to_route('order.index')->withSuccess('Pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return to_route('order.edit', $order)->withError('Gagal memperbarui pesanan: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete(); // Items cascade on delete
            return to_route('order.index')->withSuccess('Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return to_route('order.index')->withError('Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }
}
