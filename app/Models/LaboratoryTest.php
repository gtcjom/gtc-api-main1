<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryTest extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'type',
		'description',
        'lab_rate'
	];
}
