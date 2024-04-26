<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\LaboratoryTest;
use App\Models\User;

class LaboratoryOrder extends Model
{
	use HasFactory;

	protected $fillable = [
		'order_number',
		'order_date',
		'patient_id',
		'laboratory_test_type',
		'doctor_id',
		'clinic_id',
		'notes',
		'lab_result_description',
		'processed_by',
		'date_processed',
		'order_status',
		'accepted_by',
		'accepted_at',
		'health_unit_id',
		'appointment_id',
	];


	public function healthUnit()
	{
		return $this->belongsTo(HealthUnit::class, 'health_unit_id');
	}

	public function doctor()
	{
		return $this->belongsTo(User::class, 'doctor_id');
	}
	public function type()
	{
		return $this->belongsTo(LaboratoryTest::class, 'laboratory_test_type');
	}

	public function clinic()
	{
		return $this->belongsTo(Clinic::class) ?: $this->belongsTo(HealthUnit::class, 'clinic_id', 'id');
	}

	public function patient()
	{
		return $this->belongsTo(Patient::class);
	}

	public function processedBy()
	{
		return $this->belongsTo(User::class, 'processed_by', 'id');
	}

	public function acceptedBy()
	{
		return $this->belongsTo(User::class, 'accepted_by', 'id');
	}
}
