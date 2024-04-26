<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Patient;
use App\Http\Resources\PatientResource;
use App\Services\ClinicService;

class ClinicPatientController extends Controller
{

    public function index(ClinicService $clinicService)
    {

        return PatientResource::collection($clinicService->patients());
    }
    public function show(ClinicService $clinicService, $id)
    {
        return PatientResource::make($clinicService->getPatient($id));
    }
}
