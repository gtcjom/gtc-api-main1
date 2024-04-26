<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'price',
        'amount',
        'consignment_order_id',
        'consignment_order_location_id',
    ];

    public function consignment()
    {
        return $this->belongsTo(ConsignmentOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
