<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPharmacyOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'inventory_pharmacies_id',
        'doctor_id',
        'date',
        // 'supplies',
        'quantity',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function inventoryPharmacy()
    {
        return $this->belongsTo(InventoryPharmacy::class, 'inventory_pharmacies_id');
    }
}
