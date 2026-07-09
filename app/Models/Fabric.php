<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $fillable = [
        'name',
        'fabric_type',
        'color',
    ];

    public function stocks()
    {
        return $this->hasMany(FabricStock::class);
    }
}
