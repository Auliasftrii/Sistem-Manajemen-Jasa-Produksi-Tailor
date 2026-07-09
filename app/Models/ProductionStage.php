<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionStage extends Model
{
    protected $fillable = [
        'stage_name',
        'sequence_order',
    ];
}
