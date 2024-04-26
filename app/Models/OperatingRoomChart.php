<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Clinic;

class OperatingRoomChart extends Model
{
	use HasFactory;

	protected $fillable = [
		'clinic_id',
		'patient_id',
		'date',
		'procedure',
		'status',
		'priority',
		'room_number',
		'appointment_id',
		'room_id'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}

	public function room()
	{
		return $this->belongsTo(OperatingRoom::class, 'room_id');
	}

	public function clinic()
	{
		return $this->belongsTo(Clinic::class, 'clinic_id');
	}

	public function appointment()
	{
		return $this->belongsTo(Appointment::class, 'appointment_id');
	}

	public function healthcareProfessionals()
	{
		return $this->hasMany(OperatingRoomHealthcareProfessional::class);
	}
}
