<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCsr extends Model
{
    use HasFactory;
    protected $fillable = [
        'csr_date',
        'csr_supplies',
        'csr_stocks',
        'csr_price',
        'csr_status',
    ];
    // public function inventoryCSR()
    // {
    //     return $this->hasOne(InventoryCsr::class, 'inventory_csr_id');
    // }
}
