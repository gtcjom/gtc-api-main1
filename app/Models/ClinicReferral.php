<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\User;
use App\Models\Clinic;

class ClinicReferral extends Model
{
    use HasFactory;

	protected $fillable = [
		'patient_id',
		'referral_date',
		'notes',
		'added_by',
		'from_clinic_id',
		'to_clinic_id',
		'diagnosis',
		'received_by',
		'date_received',
		'status'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}

	public function addedBy()
	{
		return $this->belongsTo(User::class, 'added_by');
	}

	public function fromClinic()
	{
		return $this->belongsTo(Clinic::class, 'from_clinic_id');
	}

	public function toClinic()
	{
		return $this->belongsTo(Clinic::class, 'to_clinic_id');
	}

	public function receivedBy()
	{
		return $this->belongsTo(User::class, 'received_by');
	}
}
