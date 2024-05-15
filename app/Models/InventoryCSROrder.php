<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCSROrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        // 'inventory_csr_id',
        'doctor_id',
        'date',
        'supplies',
        'quality',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    // public function inventoryCSR()
    // {
    //     return $this->belongsTo(InventoryCsr::class, 'inventory_csr_id');
    // }
}
