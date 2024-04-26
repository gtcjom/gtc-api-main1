<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorsUnsavePrescription extends Model
{
    use HasFactory, HasUuids;

	protected $fillable = [
		'dp_id',
		'prescription_id',
		'management_id',
		'patients_id',
		'doctors_id',
		'prescription',
		'product_name',
		'product_amount',
		'quantity',
		'type',
		'dosage',
		'remarks',
		'prescription_type'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id', 'patients_id');
	}

	public function doctor()
	{
		return $this->belongsTo(Doctor::class, 'doctors_id', 'doctors_id');
	}
}
