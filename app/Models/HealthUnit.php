<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthUnit extends Model
{
    use HasFactory;


    public function rooms()
    {
        return $this->hasMany(OperatingRoom::class, 'health_unit_id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }
}
