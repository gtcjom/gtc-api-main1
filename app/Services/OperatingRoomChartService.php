<?php

namespace App\Services;

use App\Enums\OperatingRoomChartStatusEnum;
use App\Models\OperatingRoomChart;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class OperatingRoomChartService
{
	public function show($id)
	{
		$orChart = OperatingRoomChart::query()
			->with(['clinic', 'room', 'patient', 'appointment'])
			->findOrFail($id);

		return $orChart;
	}

	public function store(Request $request)
	{
		$orChart = new OperatingRoomChart();
		$orChart->clinic_id = $request->get('clinic_id');
		$orChart->patient_id = $request->get('patient_id');
		$orChart->room_id = $request->get('room_id');
		$orChart->date = $request->get('date');
		$orChart->time = $request->get('time');
		$orChart->procedure = $request->get('procedure');
		$orChart->priority = $request->get('priority') ? $request->get('priority') : 0;
		$orChart->room_number = $request->get('room_number');
		$orChart->appointment_id = $request->get('appointment_id');

		$orChart->save();

		// add healthcare professionals
		if ($request->has('healthcare_professionals')) {
			foreach ($request->get('healthcare_professionals') as $data) {
				$orChart->healthcareProfessionals()->create([
					'doctor_id' => $data['doctor_id'],
					'title' => $data['title']
				]);
			}
		}

		$orChart->load(['patient', 'clinic', 'healthcareProfessionals', 'appointment']);

		return $orChart;
	}

	public function update($request, int $id): Model|OperatingRoomChart|Collection|Builder|array|null
	{
		$orChart = OperatingRoomChart::findOrFail($id);
		$orChart->date = $request->get('date');
		$orChart->room_id = $request->get('room_id');
		$orChart->time = $request->get('time');
		$orChart->procedure = $request->get('procedure');
		$orChart->priority = $request->get('priority') ? $request->get('priority') : 0;
		$orChart->room_number = $request->get('room_number');
		$orChart->appointment_id = $request->get('appointment_id');

		$orChart->save();

		$orChart->healthcareProfessionals()->delete();
		if ($request->has('healthcare_professionals')) {
			foreach ($request->get('healthcare_professionals') as $data) {
				$orChart->healthcareProfessionals()->create([
					'doctor_id' => $data['doctor_id'],
					'title' => $data['title']
				]);
			}
		}

		$orChart->load(['patient', 'clinic', 'healthcareProfessionals', 'appointment']);

		return $orChart;
	}

	public function list(Request $request)
	{
		$clinic_id = $request->get('clinic_id') ? $request->get('clinic_id') : null;
		$status = $request->get('status') ? $request->get('status') : null;
		$date_from = $request->get('date_from') ? $request->get('date_from') : null;
		$date_to = $request->get('date_to') ? $request->get('date_to') : null;
		$time_from = $request->get('time_from') ? $request->get('time_from') : null;
		$time_to = $request->get('time_to') ? $request->get('time_to') : null;
		$patient_id = $request->get('patient_id') ? $request->get('patient_id') : null;
		$room_id = $request->get('room_id') ? $request->get('room_id') : null;
		$orCharts = OperatingRoomChart::query()
			->with(['patient', 'room', 'clinic', 'healthcareProfessionals', 'appointment'])
			->when($clinic_id, function ($query, $clinic_id) {
				$query->where('clinic_id', $clinic_id);
			})
			->when($status, function ($query, $status) {
				$query->where('status', $status);
			})
			->when($date_from, function ($query, $date_from) {
				$query->where('date', '>=', $date_from);
			})
			->when($date_to, function ($query, $date_to) {
				$query->where('date', '<=', $date_to);
			})
			->when($time_from, function ($query, $time_from) {
				$query->where('time', '>=', $time_from);
			})
			->when($time_to, function ($query, $time_to) {
				$query->where('time', '<=', $time_to);
			})
			->when($patient_id, function ($query, $patient_id) {
				$query->where('patient_id', $patient_id);
			})
			->when($room_id, function ($query, $room_id) {
				$query->where('room_id', $room_id);
			})
			->get();

		return $orCharts;
	}

	public function toResu(int $id): Model|OperatingRoomChart|Collection|Builder|array|null
	{
		$orChart = OperatingRoomChart::findOrFail($id);
		$orChart->status = OperatingRoomChartStatusEnum::Resu;

		$orChart->save();
		$orChart->load(['patient', 'clinic', 'healthcareProfessionals', 'appointment']);

		return $orChart;
	}

	public function done(int $id): Model|OperatingRoomChart|Collection|Builder|array|null
	{
		$orChart = OperatingRoomChart::findOrFail($id);
		$orChart->status = OperatingRoomChartStatusEnum::Done;

		$orChart->save();
		$orChart->load(['patient', 'clinic', 'healthcareProfessionals', 'appointment']);

		return $orChart;
	}

	public function delete(int $id)
	{
		$orChart = OperatingRoomChart::findOrFail($id);
		$orChart->healthcareProfessionals()->delete();
		$orChart->delete();
	}
}
