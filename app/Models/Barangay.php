<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Municipality;
use App\Models\Purok;

class Barangay extends Model
{
    use HasFactory;

	protected $fillable = [
		'name',
		'code',
		'municipality_id'
	];

    public function puroks()
    {
        return $this->hasMany(Purok::class,'barangay_id');
    }

	public function municipality()
	{
		return $this->belongsTo(Municipality::class);
	}
}
