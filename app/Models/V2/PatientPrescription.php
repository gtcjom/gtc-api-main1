<?php

namespace App\Models\V2;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPrescription extends Model
{
    use HasFactory;

	protected $fillable = [
		'patient_id',
		'prescription',
		'quantity',
		'type',
		'added_by_id',
		'doctor_id',
		'remarks',
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}

	public function doctor()
	{
		return $this->belongsTo(Doctor::class, 'doctor_id');
	}

	public function addedBy()
	{
		return $this->belongsTo(User::class, 'added_by_id', 'user_id');
	}
}
