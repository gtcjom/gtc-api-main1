<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationProcedure extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'operation_number',
        'operation_date',
        'operation_time',
        'procedure',
        'operation_notes',
        'doctor_id',
        'operation_status',
        'health_unit_id',
        'appointment_id'
    ];
    public function healthUnit()
    {
        return $this->belongsTo(HealthUnit::class, 'health_unit_id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function clinic()
    {
        return $this->belongsTo(Clinic::class) ?: $this->belongsTo(HealthUnit::class, 'clinic_id', 'id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
