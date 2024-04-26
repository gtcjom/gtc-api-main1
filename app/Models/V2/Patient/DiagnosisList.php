<?php

namespace App\Models\V2\Patient;

use App\Models\Diagnosis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisList extends Model
{
    use HasFactory;

	protected $fillable = [
		'diagnoses_id',
		'description'
	];

	public function diagnoses()
	{
		return $this->belongsTo(Diagnosis::class);
	}
}
