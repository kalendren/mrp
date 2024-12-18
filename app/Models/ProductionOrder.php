<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
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
        'so_sequence',
    ];

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'wo_id'); // Link to assets by wo_id
    }

    public static function generateWoNumber($soNumber, $sequenceNumber)
    {
        $currentYear = date('y');
        $formattedSequence = str_pad($sequenceNumber, 5, '0', STR_PAD_LEFT);
        $suffix = str_pad($sequenceNumber, 2, '0', STR_PAD_LEFT);

        return "{$currentYear}-{$formattedSequence}-{$suffix}";
    }

    public function rawMaterials()
    {
    return $this->hasMany(RawMaterial::class, 'wo_id');  // 
    }

    public function consumables()
    {
    return $this->hasMany(Consumable::class, 'wo_id');  // Adjust
    }
}
