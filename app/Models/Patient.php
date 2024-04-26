<?php

namespace App\Models;

use App\Enums\OperatingRoomChartStatusEnum;
use App\Enums\PatientQueueStatusEnum;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }

    //listen when patient created, update and delete
    protected static function boot()
    {
        parent::boot();

        static::created(function ($patient) {
            Cache::forget('patient-mapping');
        });

        static::updated(function ($patient) {
            Cache::forget('patient-mapping');
        });

        static::deleted(function ($patient) {
            Cache::forget('patient-mapping');
        });
    }


    public function diseaseHistories()
    {
        return $this->hasMany(DiseaseHistory::class);
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_histories', 'patient_id', 'disease');
    }


    public function houseHold()
    {
        return $this->belongsTo(Household::class, 'household_id');
    }


    public function information()
    {
        return $this->hasOne(PatientInformation::class);
    }

    public function rawAnswer()
    {
        return $this->hasOne(PatientRawAnswer::class);
    }

    public function purokData()
    {
        return $this->belongsTo(Barangay::class, 'purok_id');
    }

    public function barangayData()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function municipalityData()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function queueData()
    {
        return $this->hasMany(PatientQueue::class, 'patient_id');
    }

    public function fullName()
    {
        return $this->firstname . ' ' . $this->middle . ' ' . $this->lastname;
    }

    public function existingQueue($clinic_id)
    {
        return $this->hasMany(PatientQueue::class, 'patient_id')
            ->where('clinic_id', $clinic_id)
            ->whereNotIn('status', [PatientQueueStatusEnum::Done]);
    }

    public function existingTBProgramReferral()
    {
        return $this->hasMany(TuberculosisProgram::class, 'patient_id')
            ->whereNull('program_status')
            ->first();
    }

    public function existingOperatingRoomChart($clinic_id)
    {
        return $this->hasMany(OperatingRoomChart::class, 'patient_id')
            ->where('clinic_id', $clinic_id)
            ->whereNotIn('status', [OperatingRoomChartStatusEnum::Done]);
    }
    public function patientDependents()
    {
        return $this->hasMany(PatientDependents::class, 'patient_id');
    }

    public function philhealthDetails()
    {
        return $this->hasOne(PatientPhilhealthDetails::class, 'patient_id');
    }

    public function latestSocialHistory()
    {
        return $this->hasOne(SocialHistory::class, 'patient_id')->latestOfMany();
    }

    public function latestEnvironmentalHistory()
    {
        return $this->hasOne(EnvironmentalHistory::class, 'patient_id')->latestOfMany();
    }

    public function appointments()
    {
        return $this->hasMany(AppointmentData::class, 'patient_id');
    }

    public function pmrf()
    {
        return $this->hasOne(PatientPMRFInformation::class, 'patient_id');
    }
}
