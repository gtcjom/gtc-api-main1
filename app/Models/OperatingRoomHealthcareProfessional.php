<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OperatingRoomChart;
use App\Models\Doctor;

class OperatingRoomHealthcareProfessional extends Model
{
    use HasFactory;

	protected $fillable = [
		'operating_room_chart_id',
		'doctor_id',
		'title',
	];

	public function operatingRoomChart()
	{
		return $this->belongsTo(OperatingRoomChart::class, 'operating_room_chart_id');
	}

	public function doctor()
	{
		return $this->belongsTo(User::class, 'doctor_id');
	}
}
