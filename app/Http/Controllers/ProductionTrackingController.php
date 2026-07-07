<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionTrackingController extends Controller
{
    public function index()
    {
        // Get orders that are not cancelled, sort by order_date
        $orders = Order::with('trackings.handler', 'customer')
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->get();

        return view('production.index', [
            'title' => 'Pelacakan Produksi',
            'orders' => $orders,
        ]);
    }

    public function edit(Order $production) // actually $production is the Order instance passing in route
    {
        $production->load('trackings.handler', 'items');
        
        // Ensure default trackings exist
        $stages = ['Pola', 'Potong', 'Jahit', 'Finishing'];
        
        foreach ($stages as $stage) {
            if (!$production->trackings->where('stage', $stage)->first()) {
                ProductionTracking::create([
                    'order_id' => $production->id,
                    'stage' => $stage,
                    'status' => 'pending'
                ]);
            }
        }
        
        $production->load('trackings.handler'); // reload

        return view('production.edit', [
            'title' => 'Update Status Produksi',
            'order' => $production
        ]);
    }

    public function update(Request $request, Order $production)
    {
        $request->validate([
            'trackings' => 'required|array',
            'trackings.*.id' => 'required|exists:production_trackings,id',
            'trackings.*.status' => 'required|in:pending,in_progress,completed',
        ]);

        foreach ($request->trackings as $trackingData) {
            $tracking = ProductionTracking::find($trackingData['id']);
            
            // Only update if status changed
            if ($tracking->status != $trackingData['status']) {
                $data = [
                    'status' => $trackingData['status'],
                    'handled_by' => Auth::id() ?? 1,
                ];

                if ($trackingData['status'] == 'in_progress' && !$tracking->started_at) {
                    $data['started_at'] = now();
                }

                if ($trackingData['status'] == 'completed' && !$tracking->completed_at) {
                    $data['completed_at'] = now();
                }

                $tracking->update($data);
            }
        }

        return to_route('production.index')->withSuccess('Status produksi berhasil diperbarui.');
    }
}
