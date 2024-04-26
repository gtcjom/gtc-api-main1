<?php

namespace App\Http\Controllers\Clinic\Doctor;

use App\Events\ClinicQueueEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientQueueResource;
use App\Services\ClinicService;
use Illuminate\Http\Request;

class PatientAcceptanceController extends Controller
{

    public function update(Request $request, ClinicService $clinicService, int $id)
    {
        $patientQueue = $clinicService->queueAcceptance($id, $request->get('clinic_id'));

        ClinicQueueEvent::dispatch($request->get('clinic_id'));
        return PatientQueueResource::make($patientQueue);
    }

    public function destroy(Request $request, ClinicService $clinicService, int $id)
    {
        $patientQueue = $clinicService->queueDone($id, $request->get('clinic_id'));
        $result = PatientQueueResource::make($patientQueue);

        ClinicQueueEvent::dispatch($request->get('clinic_id'));
        return $result;
    }
}