<?php

namespace App\Models\V2;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imaging extends Model
{
    use HasFactory;

	protected $fillable = [
		'patient_id',
		'description',
		'type',
		'image',
		'requested_by',
		'requested_at',
		'processed_by',
		'processed_at',
		'status'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class);
	}

	public function processedBy()
	{
		return $this->belongsTo(User::class, 'processed_by', 'id');
	}
}
