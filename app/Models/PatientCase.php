<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientCase extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'general_history' => 'array',
        'medical_and_surgical_history' => 'array',
        'environmental_history' => 'array',
        'personal_social_history' => 'array',
        'patient_symptoms' => 'array',
        'entities' => 'array',
        'tb_symptoms' => 'array',
        'vitals' => 'array',
        'case_items' => 'array',
        'referral_data' => 'array',
        'prescriptions' => 'array',
        ];




    //creating event
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patientCase) {
            //generate unique case number with CASE-entity-date-unique number  CASE-1-2021-09-09-0001
            //0001-A if more than 9999 cases in a day then it will be 0001-B

            if($patientCase->code != null)
                return;


            $code = 1;
            $suffix = "A";
            $lastCase = PatientCase::query()
                ->whereDate('case_date', date('Y-m-d'))
                ->latest()
                ->first();
            if ($lastCase) {
                $suffix = $lastCase->suffix;

                //last code is 0001

                $code = (int)$lastCase->code + 1;
                if ($code > 9999) {
                    $suffix = chr(ord($suffix) + 1);
                    $code = 1;
                }
            }

            $patientCase->code = str_pad($code, 4, '0', STR_PAD_LEFT);
            $patientCase->suffix = $suffix;
            $patientCase->case_date = date('Y-m-d');
            $patientCase->prefix = config('app.case_code','A');
            $patientCase->health_unit_id = config('app.health_unit_id','10');
        });
    }


    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_cloud_id');
    }
}
