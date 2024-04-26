<?php

namespace App\Models;

use App\Models\Patient;
use App\Models\User;
use App\Models\V2\Patient\DiagnosisList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
	use HasFactory;
	protected $table = 'diagnoses';
	protected $fillable = [
		'patient_id',
		'added_by_id',
		'title',
		'description',
		'datetime',
	];

	protected $dates = ['datetime'];

	public function addedBy()
	{
		return $this->belongsTo(User::class, 'added_by_id');
	}

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}

	public function diagnosisList()
	{
		return $this->hasMany(DiagnosisList::class, 'diagnoses_id');
	}
}
