<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRevision extends Model
{
    /** @use HasFactory<\Database\Factories\OrderRevisionFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'revision_notes',
        'status',
        'reported_at',
        'resolved_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
