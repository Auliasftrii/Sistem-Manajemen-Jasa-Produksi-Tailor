<?php

namespace App\Observers;

use App\Models\ProductionTracking;

class ProductionTrackingObserver
{
    /**
     * Handle the ProductionTracking "saved" event.
     */
    public function saved(ProductionTracking $productionTracking): void
    {
        $productionTracking->order->syncStatus();
    }

    /**
     * Handle the ProductionTracking "deleted" event.
     */
    public function deleted(ProductionTracking $productionTracking): void
    {
        $productionTracking->order->syncStatus();
    }
}
