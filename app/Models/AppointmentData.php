<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentData extends Model
{
    use HasFactory;


    protected $guarded = [];


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


    public function bhs()
    {
        return $this->belongsTo(HealthUnit::class, 'bhs_id', 'id');
    }

    public function rhu()
    {
        return $this->belongsTo(HealthUnit::class, 'rhu_id', 'id');
    }

    public function tb_symptoms()
    {
        return $this->belongsTo(TuberculosisData::class, 'tb_data_id');
    }

    public function vitals()
    {
        return $this->belongsTo(Vital::class, 'vital_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'prescribed_by', 'id');
    }
    public function referredToDoctor()
    {
        return $this->belongsTo(User::class, 'referred_to', 'id');
    }

    public function prescriptions()
    {
        return $this->hasMany(ItemUsage::class, 'appointment_id', 'id')->where('type', 'prescription');
    }
    public function laboratoryTests()
    {
        return $this->hasMany(ItemUsage::class, 'appointment_id', 'id')->where('type', 'laboratory');
    }
    public function labOrders()
    {
        return $this->hasMany(LaboratoryOrder::class, 'appointment_id', 'id');
    }

    public function forReadingLabOrders()
    {
        return $this->hasMany(LaboratoryOrder::class, 'appointment_id', 'id')->where('order_status', 'for-result-reading');
    }

    public function servicedBy()
    {
        return $this->belongsTo(User::class, 'serviced_by', 'id');
    }

    public function prescribedByDoctor()
    {
        return $this->belongsTo(User::class, 'prescribed_by', 'id');
    }


    public function generalHistory()
    {
        return $this->hasOne(PatientGeneralHistory::class, 'appointment_id', 'id');
    }

    public function surgicalHistory()
    {
        return $this->hasOne(MedicalSurgicalHistory::class, 'appointment_id', 'id');
    }

    public function environmentalHistory()
    {
        return $this->hasOne(EnvironmentalHistory::class, 'appointment_id', 'id');
    }

    public function socialHistory()
    {
        return $this->hasOne(SocialHistory::class, 'appointment_id', 'id');
    }
}
