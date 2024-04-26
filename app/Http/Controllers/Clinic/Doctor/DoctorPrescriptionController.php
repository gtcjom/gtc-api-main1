<?php

namespace App\Http\Controllers\Clinic\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorPrescriptionResource;
use App\Models\DoctorsPrescription;
use App\Services\DoctorPrescriptionService;
use Illuminate\Http\Request;

class DoctorPrescriptionController extends Controller
{

    public function index($id)
    {
		return DoctorPrescriptionResource::collection(DoctorsPrescription::where('patient_id', $id)->get());
    }

    public function store(Request $request)
    {
		// validate input
		$request->validate([
			'prescription_id' => ['required', 'string'],
			'product_name' => ['required','string'],
			'quantity' => ['required', 'integer'],
			'type' => ['required', 'string'],
			'remarks' => ['required', 'string'],
			'patients_id' => ['required', 'string'],
			'doctors_id' => ['required', 'string'],
		]);

		// create a new doctor's prescription
		$doctorPrescription = DoctorsPrescription::create($request->validated());

		// return doctor prescription resource
		return new DoctorPrescriptionResource($doctorPrescription);
    }

    public function update(Request $request, DoctorsPrescription $doctorPrescription)
    {
		// validate input
		$request->validate([
			'prescription_id' => ['required', 'string'],
			'product_name' => ['required','string'],
			'quantity' => ['required', 'integer'],
			'type' => ['required', 'string'],
			'remarks' => ['required', 'string'],
		]);

		// create a new doctor's prescription
		$doctorPrescription->update($request->validated());

		// return doctor prescription resource
		return new DoctorPrescriptionResource($doctorPrescription);
    }

    public function destroy(DoctorsPrescription $doctorPrescription)
    {
		$doctorPrescription->delete();
		return response()->json(['message' => 'Prescription deleted successfully']);
    }
}
