<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeleMedicineSchedule extends Model
{
    use HasFactory;


    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function slot()
    {
        return $this->belongsTo(TimeSlot::class, 'slot_id');
    }
}
