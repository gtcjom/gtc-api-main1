<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPMRFInformation extends Model
{
    use HasFactory;

    protected $table = 'patient_pmrf_information';

    protected $casts = [
        'personal_details' => 'array',
        'address_contact_details' => 'array',
        'declaration_dependents' => 'array',
        'member_type' => 'array',
        'updating_amendment' => 'array',
        'for_philhealth' => 'array',
    ];
}
