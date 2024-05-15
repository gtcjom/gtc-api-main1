<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPharmacy extends Model
{
    use HasFactory;
    protected $fillable = [
        'pharmacy_date',
        'pharmacy_supplies',
        'pharmacy_stocks',
        'pharmacy_price',
        'pharmacy_status',
    ];
}
