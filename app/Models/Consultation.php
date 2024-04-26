<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nature_of_visit',
        'date',
        'age_in_year',
        'age_in_month',
        'age_in_day',
        'mode_of_transaction',
        'weight',
        'height',
        'bmi',
        'bmi_category',
        'height_for_age',
        'weight_for_age',
        'attending_provider',
        'chief_complaint',
        'consent',
        'patient_id',
    ];

    protected $casts = [
        'consent' => 'boolean',
    ];

    public function types(): HasMany
    {
        return $this->hasMany(ConsultationType::class);
    }



}
