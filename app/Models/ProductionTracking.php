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
        'production_stage_id',
        'status',
        'tailor_id',
        'started_at',
        'completed_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productionStage()
    {
        return $this->belongsTo(ProductionStage::class);
    }

    public function tailor()
    {
        return $this->belongsTo(Tailor::class);
    }
}
