<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\PatientQueue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentService
{

    public function index()
    {
        return Appointment::query()
            ->with(['patient' => [
                'purokData',
                'barangayData',
                'municipalityData'
            ] , 'doctor'])
            ->where('clinic_id', request()->get('clinic_id'))
            ->when( request('doctor_id'), fn($q,$doctor_id) => $q->where('doctor_id', $doctor_id))
            ->when( request('date'), fn($q,$date) => $q->whereDate('date', $date))
            ->get();
    }
    public function store(Request $request)
    {
        $appointment = new Appointment();
        $appointment->date = $request->get('date')." ".$request->get('time');
        $appointment->purpose = $request->get('purpose');
        $appointment->doctor_id = $request->get('doctor_id');
        $appointment->room_number = $request->get('room_number');
        $appointment->patient_id = $request->get('patient_id');
        $appointment->status = "pending";
        $appointment->clinic_id = $request->get('clinic_id');
        $appointment->type_service = $request->get('type_service');
        $appointment->save();

        return $appointment;
    }

	public function monthlyAppointments(Request $request, $clinic_id)
	{
		$date = $request->date ? $request->date : Carbon::now()->startOfMonth()->format('Y-m');
		$month = Carbon::parse($date)->format('m');
		$year = Carbon::parse($date)->format('Y');

		$appointments = PatientQueue::with(['patient', 'clinic', 'doctor'])
			->where('clinic_id', $clinic_id)
			->whereYear('date_queue', $year)
			->whereMonth('date_queue', $month)
			->get();

		return $appointments;
	}

	public function dailyAppointments(Request $request, $clinic_id)
	{
		$date = $request->date ? $request->date : Carbon::now()->toDateString();

		$appointments = PatientQueue::with(['patient', 'clinic', 'doctor'])
			->where('clinic_id', $clinic_id)
			->where('date_queue', $date)
			->get();

		return $appointments;
	}

	public function doctorAppointments(Request $request, $doctor_id)
	{
		$date = $request->date ? $request->date : Carbon::now()->toDateString();

		$appointments = PatientQueue::with(['patient', 'clinic', 'doctor'])
			->where('doctor_id', $doctor_id)
			->where('date_queue', $date)
			->get();

		return $appointments;
	}
}
