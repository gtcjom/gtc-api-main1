<?php

namespace App\Http\Controllers\Clinic\Nurse;

use App\Events\ClinicQueueEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientQueueResource;
use App\Models\Appointment;
use App\Models\PatientQueue;
use App\Services\ClinicService;
use Illuminate\Http\Request;

class AppointmentQueueController extends Controller
{

    public function update(Request $request, ClinicService $clinicService, int $id)
    {
        $appointment = Appointment::query()->where('status', 'pending')->findOrFail($id);

        $request->merge([
            'purpose' => $appointment->purpose,
            'clinic_id' => $appointment->clinic_id,
            'patient_id' => $appointment->patient_id,
            'room_number' => $appointment->room_number,
            'type_service' => $appointment->type_service,
            'doctor_id' => $appointment->doctor_id,
        ]);

        $appointment->status = 'done';

        $appointment->save();



        $receive = $clinicService->receivingPatient($request, 1);

        $receive->load([
            'doctor',
            'patient' => [
                'barangayData',
                'purokData',
                'municipalityData',
            ]
        ]);

        ClinicQueueEvent::dispatch($request->get('clinic_id'));
        return PatientQueueResource::make($receive);
    }
}
