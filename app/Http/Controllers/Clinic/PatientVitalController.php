<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientVitalController extends Controller
{

    public function update(Request $request, PatientService $patientService ,int $id)
    {
        $patientService->updateVitals($request,$id);

        return response()->noContent();
    }
}