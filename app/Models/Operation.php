<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'process',
        'workstation',
        'standard_hours',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }
}

