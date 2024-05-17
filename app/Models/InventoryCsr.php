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
    public static function getSupplies()
    {
        return self::all();
        // return self::all()->pluck('csr_supplies');
    }
    public function inventoryCsrOrders()
    {
        return $this->hasMany(InventoryCSROrder::class, 'inventory_csrs_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
