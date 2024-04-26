<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
	use HasFactory;

	protected $fillable = [
		'temperature',
		'blood_pressure',
		'respiratory',
		'uric_acid',
		'cholesterol',
		'glucose',
		'pulse',
		'weight',
		'height',
		'patient_id',
		'added_by_id',
		'blood_systolic',
		'blood_diastolic',
		'bmi',
		'height_for_age',
		'weight_for_age',
		'bloody_type',
		'oxygen_saturation',
		'heart_rate',
		'regular_rhythm',
		'covid_19',
		'tb',
	];

	public function addedBy()
	{
		return $this->belongsTo(User::class, 'added_by_id', 'id');
	}

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}
}
