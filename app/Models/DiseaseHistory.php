<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseHistory extends Model
{
    use HasFactory;


    protected $guarded = [];

    protected $dates = ['date_started', 'date_cured'];


    public function patient()
    {
        return $this->belongsTo(Patient::class ,'patient_id');
    }

    public function diseaseData()
    {
        return $this->belongsTo(Disease::class,'disease');
    }
    public function municipalityData()
    {
        return $this->belongsTo(Municipality::class,'municipality');
    }
    public function barangayData()
    {
        return $this->belongsTo(Barangay::class,'barangay');
    }
}
