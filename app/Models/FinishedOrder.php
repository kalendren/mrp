<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishedOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number',
        'so_number',
        'cust_po_number',
        'customer',
        'order_date',
        'required_date',
        'job_scope',
        'part_description',
        'drawing_no',
        'lot_size',
        'date_closed',
        'closed_by',
    ];

     // Relationship to fetch assets related to the finished order
     public function operations()
    {
        return $this->hasMany(Operation::class, 'finished_order_id'); // Define relationship for finished orders
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'fo_id'); // Define relationship for assets
    }

    public function rawMaterials()
    {
    return $this->hasMany(RawMaterial::class, 'fo_id');  // 
    }

    public function consumables()
    {
    return $this->hasMany(Consumable::class, 'fo_id');  // Adjust
    }
 }

