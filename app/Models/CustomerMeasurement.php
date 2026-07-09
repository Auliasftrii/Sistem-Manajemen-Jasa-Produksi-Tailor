<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMeasurement extends Model
{
    protected $fillable = [
        'customer_id',
        'garment_category_id',
        'measurement_key',
        'measurement_value',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function garmentCategory()
    {
        return $this->belongsTo(GarmentCategory::class);
    }
}
