<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class PatientDependents extends Model
{
    use HasFactory;

	protected $fillable = [
		'patient_id',
		'firstname',
		'lastname',
		'middle_name',
		'name_extension',
		'relationship',
		'birthday',
		'citizenship',
		'is_permanently_disabled'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}
}
