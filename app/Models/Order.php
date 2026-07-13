<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    
    protected $fillable = [
        'customer_id',
        'user_id',
        'invoice_number',
        'order_date',
        'expected_completion_date',
        'total_amount',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackings()
    {
        return $this->hasMany(ProductionTracking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function revisions()
    {
        return $this->hasMany(OrderRevision::class);
    }

    public function syncStatus()
    {
        $newStatus = 'pending';
        $trackings = $this->trackings;
        $totalStages = \App\Models\ProductionStage::count();

        if ($trackings->count() > 0) {
            $completedCount = 0;
            $anyStarted = false;

            foreach ($trackings as $tracking) {
                if ($tracking->started_at) {
                    $anyStarted = true;
                }
                if ($tracking->completed_at) {
                    $completedCount++;
                }
            }

            if ($completedCount === $totalStages && $totalStages > 0) {
                $newStatus = 'completed';
            } elseif ($anyStarted) {
                $newStatus = 'in_progress';
            }
        }

        if ($this->status !== $newStatus) {
            $this->updateQuietly(['status' => $newStatus]);
        }
    }
}
