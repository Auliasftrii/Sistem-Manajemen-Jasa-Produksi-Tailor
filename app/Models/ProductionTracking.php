<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTracking extends Model
{
    /** @use HasFactory<\Database\Factories\ProductionTrackingFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'stage',
        'status',
        'handled_by',
        'started_at',
        'completed_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
