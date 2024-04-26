<?php

namespace App\Http\Controllers\V2\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientPrescriptionResource;
use App\Models\V2\PatientPrescription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PatientPrescriptionController extends Controller
{

    public function index($id)
    {
		return PatientPrescriptionResource::collection(PatientPrescription::where('patient_id', $id)->get());
    }

    public function store(Request $request)
    {
        // validate input
		$request->validate([
			'patient_id' => ['required', 'string'],
			'prescription' => ['required','string'],
			'quantity' => ['required', 'integer'],
			'type' => ['required', 'string'],
			'added_by_id' => ['nullable'],
			'doctors_id' => ['nullable', 'string'],
			'remarks' => ['required', 'string'],
		]);

		// create new patient prescription
		$patientPrescription = PatientPrescription::create(array_merge([
			'patient_id' => $request->patient_id,
			'prescription' => $request->prescription,
			'quantity' => $request->quantity,
			'type' => $request->type,
			'added_by_id' => $request->user()->id,
			'doctors_id' => $request->doctors_id,
			'remarks' => $request->remarks,
		]));

		return response()->json([
			'data' => new PatientPrescriptionResource($patientPrescription),
			'message' => 'Prescription created successfully.'
		], Response::HTTP_CREATED);
    }

    public function show(PatientPrescription $patientPrescription)
    {
		return response()->json([
			'data' => new PatientPrescriptionResource($patientPrescription->load('patient', 'doctor', 'addedBy')),
			'message' => 'Prescription retrieved successfully.'
		], Response::HTTP_OK);
    }


    public function update(Request $request, PatientPrescription $patientPrescription)
    {
         // validate input
		$request->validate([
			'prescription' => ['required','string'],
			'quantity' => ['required', 'integer'],
			'type' => ['required', 'string'],
			'remarks' => ['required', 'string'],
		]);

		// create new patient prescription
		$patientPrescription->update(array_merge([
			'prescription' => $request->prescription,
			'quantity' => $request->quantity,
			'type' => $request->type,
			'remarks' => $request->remarks,
		]));

		return response()->json([
			'data' => new PatientPrescriptionResource($patientPrescription),
			'message' => 'Prescription updated successfully.'
		], Response::HTTP_OK);
    }

    public function destroy(PatientPrescription $patientPrescription)
    {
        // delete the item
        $patientPrescription->delete();
		return response()->json(['message' => 'Prescription deleted successfully']);
    }
}
