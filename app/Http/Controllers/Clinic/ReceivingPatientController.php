<?php

namespace App\Http\Controllers\Clinic;


use App\Events\ClinicQueueEvent;
use App\Http\Resources\PatientQueueResource;
use App\Http\Resources\PatientResource;
use App\Models\PatientQueue;
use App\Services\ClinicService;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReceivingPatientController
{


    public function index(ClinicService $clinicService)
    {
        return PatientResource::collection($clinicService->receivingPatientList());
    }

    public function store(PatientService $patientService, ClinicService $clinicService, Request $request)
    {
        $check = PatientQueue::query()
            ->whereIn('status', ['pending', 'served'])
            ->where('patient_id', $request->get('patient_id'))
            ->first();


        if (!is_null($check))
            throw ValidationException::withMessages(['You already added in queue']);

        //$patientService->updateVitals($request, $request->get('patient_id'));
        $receive = $clinicService->receivingPatient($request, $request->has('appointment_id') ? 1 : 0);
        $receive->load(['patient' => [
            'barangayData',
            'purokData',
            'municipalityData',
        ]]);

        ClinicQueueEvent::dispatch($request->get('clinic_id'));
        return PatientQueueResource::make($receive);
    }

    public function show(ClinicService $clinicService, int $id)
    {
        return PatientQueueResource::collection($clinicService->queuePatients($id));
    }
}
