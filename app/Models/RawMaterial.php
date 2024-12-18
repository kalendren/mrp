<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['wo_id', 'fo_id', 'grade', 'size', 'length', 'hn_bar_tno'];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'wo_id');
    }

    public function finishedOrder()
    {
        return $this->belongsTo(FinishedOrder::class, 'fo_id');
    }
}
