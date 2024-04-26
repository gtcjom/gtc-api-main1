<?php

namespace App\Models\V2\Patient;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diet extends Model
{
    use HasFactory;

	protected $fillable = [
		'patient_id',
		'added_by_id',
		'meals',
		'date',
		'description'
	];

	public function addedBy()
    {
        return $this->belongsTo(User::class,'added_by_id');
    }

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}
}
