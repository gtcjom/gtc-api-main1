<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
	use HasFactory;

	protected $fillable = [
		'patient_id',
		'notes',
		'remarks',
		'added_by',
		'datetime', 'status', 'doctor_id'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}
	public function doctor()
	{
		return $this->belongsTo(User::class, 'doctor_id');
	}

	public function addedBy()
	{
		return $this->belongsTo(User::class, 'added_by', 'user_id');
	}
}
