<?php

namespace App\Observers;

use App\Models\OrderRevision;

class OrderRevisionObserver
{
    /**
     * Handle the OrderRevision "saved" event.
     */
    public function saved(OrderRevision $orderRevision): void
    {
        $orderRevision->order->syncStatus();
    }

    /**
     * Handle the OrderRevision "deleted" event.
     */
    public function deleted(OrderRevision $orderRevision): void
    {
        $orderRevision->order->syncStatus();
    }
}
