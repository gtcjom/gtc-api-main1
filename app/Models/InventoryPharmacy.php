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
    public static function getSupplies()
    {
        return self::all();
        // return self::all()->pluck('csr_supplies');
    }
    public function inventoryPharmacyOrders()
    {
        return $this->hasMany(InventoryPharmacyOrder::class, 'inventory_pharmacies_id');
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
