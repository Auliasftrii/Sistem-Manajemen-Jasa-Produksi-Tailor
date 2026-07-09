<?php

namespace App\Http\Controllers;

use App\Models\OrderRevision;
use App\Http\Requests\StoreOrderRevisionRequest;
use App\Http\Requests\UpdateOrderRevisionRequest;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderRevisionController extends Controller
{
    public function index()
    {
        $revisions = OrderRevision::with('order.customer')
            ->latest()
            ->get();

        return view('revision.index', [
            'title' => 'Daftar Revisi Pesanan',
            'revisions' => $revisions,
        ]);
    }

    public function create(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = null;

        if ($orderId) {
            $order = Order::with('customer')->findOrFail($orderId);
        }

        // Only allow completed orders to be revised
        $orders = Order::where('status', 'completed')->latest()->get();

        return view('revision.create', [
            'title' => 'Buat Komplain / Revisi',
            'orders' => $orders,
            'selectedOrder' => $order,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'revision_notes' => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->status !== 'completed') {
            return back()->withInput()->withError('Hanya pesanan yang sudah selesai (Completed) yang dapat direvisi.');
        }

        OrderRevision::create([
            'order_id' => $request->order_id,
            'revision_notes' => $request->revision_notes,
            'status' => 'Pending',
        ]);

        return to_route('revision.index')->withSuccess('Revisi pesanan berhasil dicatat.');
    }

    public function update(Request $request, OrderRevision $revision)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Resolved',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'Resolved' && $revision->status !== 'Resolved') {
            $data['resolved_at'] = now();
        } elseif ($request->status !== 'Resolved') {
            $data['resolved_at'] = null;
        }

        $revision->update($data);

        return back()->withSuccess('Status revisi berhasil diperbarui.');
    }

    public function destroy(OrderRevision $revision)
    {
        $revision->delete();
        return back()->withSuccess('Revisi berhasil dihapus.');
    }
}
