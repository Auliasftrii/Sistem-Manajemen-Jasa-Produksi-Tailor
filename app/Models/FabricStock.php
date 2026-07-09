<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricStock extends Model
{
    protected $fillable = [
        'fabric_id',
        'quantity_in_meters',
        'last_restock_date',
    ];

    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }
}
