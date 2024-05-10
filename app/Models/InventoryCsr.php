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
        'csr_quantity',
        'csr_status',
    ];
}
