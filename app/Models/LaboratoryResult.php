<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LaboratoryOrder;

class LaboratoryResult extends Model
{
	use HasFactory;

	protected $fillable = [
		'laboratory_order_id',
		'laboratory_order_type',
		'remarks',
		'results',
		'image',
		'status',
		'added_by', 'laboratory_test_id'
	];

	public function laboratoryOrder()
	{
		return $this->belongsTo(LaboratoryOrder::class);
	}
	public function laboratoryTest()
	{
		return $this->belongsTo(LaboratoryTest::class);
	}
	public function addedBy()
	{
		return $this->belongsTo(User::class, 'added_by', 'id');
	}
}
