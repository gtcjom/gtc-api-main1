<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Doctor;

class PatientQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'to_intended_id',
        'purpose',
        'send_to',
        'date_queue',
        'number',
        'priority',
        'room_number',
        'type_service',
        'doctor_id',
        'appointment_id',
        'status'
    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
