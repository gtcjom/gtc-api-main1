<?php

namespace App\Http\Controllers\Clinic\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientMedicationResource;
use App\Models\Medication;
use Illuminate\Http\Response;

class MedicationController extends Controller
{

    public function index($id)
    {
		return PatientMedicationResource::collection(Medication::where('patient_id', $id)->latest()->get());
    }

	public function show(Medication $medication)
	{
		return response()->json([
			'data' => new PatientMedicationResource($medication),
			'message' => 'Medication retrieved successfully.'
		], Response::HTTP_OK);
	}

}