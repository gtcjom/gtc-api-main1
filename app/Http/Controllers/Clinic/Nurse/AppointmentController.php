<?php

namespace App\Http\Controllers\Clinic\Nurse;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\PatientQueueResource;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public function index(AppointmentService $appointmentService)
    {
        return AppointmentResource::collection($appointmentService->index());
    }

    public function store(AppointmentService $appointmentService, Request $request)
    {
        return AppointmentResource::make($appointmentService->store($request));
    }

	public function monthlyAppointments(AppointmentService $appointmentService, Request $request, $clinic_id)
	{
		return PatientQueueResource::collection($appointmentService->monthlyAppointments($request, $clinic_id));
	}

	public function dailyAppointments(AppointmentService $appointmentService, Request $request, $clinic_id)
	{
		return PatientQueueResource::collection($appointmentService->dailyAppointments($request, $clinic_id));
	}

	public function doctorAppointments(AppointmentService $appointmentService, Request $request, $doctor_id)
	{
		return PatientQueueResource::collection($appointmentService->doctorAppointments($request, $doctor_id));
	}
}
