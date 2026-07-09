<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarmentCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function customerMeasurements()
    {
        return $this->hasMany(CustomerMeasurement::class);
    }
}
