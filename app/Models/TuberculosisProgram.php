<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\User;
use App\Models\Barangay;
use App\Models\Clinic;

class TuberculosisProgram extends Model
{
    use HasFactory;

	protected $fillable = [
		'patient_id',
		'address',
		'program',
		'barangay_id',
		'brgy_refferal_date',
		'refer_by_brgy_asst',
		'brgy_notes',
		'barangay_clinic_id',
		'rhu',
		'date_received_by_rhu',
		'received_by_rhu_id',
		'rhu_refferal_date',
		'rhu_notes',
		'municipality_clinic_id',
		'refer_by_rhu',
		'hospital_id',
		'date_received_by_sph',
		'received_by_sph_id',
		'status',
		'program_status',
		'date_approved',
		'approved_by'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}

	public function referByBarangay()
	{
		return $this->belongsTo(User::class, 'reffer_by_brgy_asst', 'id');
	}

	public function referByRhu()
	{
		return $this->belongsTo(User::class, 'refer_by_rhu', 'id');
	}

	public function approvedBy()
	{
		return $this->belongsTo(User::class, 'approved_by', 'id');
	}

	public function barangay()
	{
		return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
	}

	public function barangayClinic()
	{
		return $this->belongsTo(Clinic::class, 'barangay_clinic_id', 'id');
	}

	public function municipalityClinic()
	{
		return $this->belongsTo(Clinic::class, 'municipality_clinic_id', 'id');
	}
}
