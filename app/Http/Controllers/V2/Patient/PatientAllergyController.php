<?php

namespace App\Http\Controllers\V2\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientAllergyResource;
use App\Models\V2\Patient\PatientAllergy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PatientAllergyController extends Controller
{

    public function index($id)
    {
        return PatientAllergyResource::collection(PatientAllergy::where('patient_id', $id)->latest()->get());
    }

    public function store(Request $request)
    {
		// validate input
		$request->validate([
			'patient_id' => ['required', 'string'],
			'date' => ['required','date'],
			'description' => ['required', 'string'],
			'added_by' => ['nullable'],
		]);

		// create new patient allergy
		$patientAllergy = PatientAllergy::create(array_merge([
			'patient_id' => $request->patient_id,
			'date' => $request->date,
			'description' => $request->description,
			'added_by' => $request->user()->user_id,
		]));

		return response()->json([
			'data' => new PatientAllergyResource($patientAllergy),
			'message' => 'Patient allergy created successfully.'
		], Response::HTTP_CREATED);
    }

    public function show(PatientAllergy $patientAllergy)
    {
        return response()->json([
			'data' => new PatientAllergyResource($patientAllergy),
			'message' => 'Patient allergy created successfully.'
		], Response::HTTP_OK);
    }

    public function update(Request $request, PatientAllergy $patientAllergy)
    {
        // validate input
		$data = $request->validate([
			'date' => ['required','date'],
			'description' => ['required', 'string'],
		]);

		// update new patient allergy
		$patientAllergy->update(array_merge($data));

		return response()->json([
			'data' => new PatientAllergyResource($patientAllergy),
			'message' => 'Patient allergy updated successfully.'
		], Response::HTTP_OK);
    }

    public function destroy(PatientAllergy $patientAllergy)
    {
        $patientAllergy->delete();
		return response()->json(['message' => 'Patient allergy deleted successfully']);
    }
}
