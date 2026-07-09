<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionTracking;
use App\Models\ProductionStage;
use App\Models\Tailor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionTrackingController extends Controller
{
    public function index()
    {
        // Get orders that are not cancelled, sort by order_date
        $orders = Order::with('trackings.tailor.user', 'customer', 'trackings.productionStage')
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
        $production->load('trackings.tailor.user', 'items', 'trackings.productionStage');
        
        // Ensure default trackings exist
        $stages = ProductionStage::orderBy('sequence_order')->get();
        
        foreach ($stages as $stage) {
            if (!$production->trackings->where('production_stage_id', $stage->id)->first()) {
                ProductionTracking::create([
                    'order_id' => $production->id,
                    'production_stage_id' => $stage->id,
                    'status' => 'pending'
                ]);
            }
        }
        
        $production->load('trackings.tailor.user', 'trackings.productionStage'); // reload

        return view('production.edit', [
            'title' => 'Update Status Produksi',
            'order' => $production,
            'tailors' => Tailor::with('user')->get(),
        ]);
    }

    public function update(Request $request, Order $production)
    {
        $request->validate([
            'trackings' => 'required|array',
            'trackings.*.id' => 'required|exists:production_trackings,id',
            'trackings.*.status' => 'required|in:pending,in_progress,completed',
            'trackings.*.tailor_id' => 'nullable|exists:tailors,id',
        ]);

        foreach ($request->trackings as $trackingData) {
            $tracking = ProductionTracking::find($trackingData['id']);
            
            $data = [];
            if (isset($trackingData['tailor_id'])) {
                $data['tailor_id'] = $trackingData['tailor_id'];
            }

            // Update timestamps only if status changed
            if ($tracking->status != $trackingData['status']) {
                $data['status'] = $trackingData['status'];

                if ($trackingData['status'] == 'in_progress' && !$tracking->started_at) {
                    $data['started_at'] = now();
                }

                if ($trackingData['status'] == 'completed' && !$tracking->completed_at) {
                    $data['completed_at'] = now();
                }
            }

            if (!empty($data)) {
                $tracking->update($data);
            }
        }

        return to_route('production.index')->withSuccess('Status produksi berhasil diperbarui.');
    }
}
