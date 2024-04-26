<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Services\PatientService;

class PatientChartsController extends  Controller
{

    public function show(PatientService $patientService, int $id)
    {
        return $patientService->chartData($id);
    }
}