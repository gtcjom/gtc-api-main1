<?php

namespace App\Models\V2\Patient;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAllergy extends Model
{
    use HasFactory;

	protected $fillable = [
		'patient_id',
		'date',
		'description',
		'added_by'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}

	public function addedBy()
	{
		return $this->belongsTo(User::class, 'added_by', 'user_id');
	}
}
