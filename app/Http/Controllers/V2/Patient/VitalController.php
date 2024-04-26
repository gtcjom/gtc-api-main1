<?php

namespace App\Http\Controllers\V2\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientVitalResource;
use App\Models\AppointmentData;
use App\Models\PatientCase;
use App\Models\Vital;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VitalController extends Controller
{

	public function index($id)
	{
		return PatientVitalResource::collection(Vital::where('patient_id', $id)->latest()->get()->load('patient'));
	}

	public function store(Request $request)
	{
		// validate input
		$validatedData = $request->validate([
			'temperature' => ['required', 'numeric'],
			'respiratory' => ['nullable', 'numeric'],
			'uric_acid' => ['nullable', 'numeric'],
			'cholesterol' => ['nullable', 'numeric'],
			'glucose' => ['nullable', 'numeric'],
			'pulse' => ['nullable', 'numeric'],
			'weight' => ['nullable', 'numeric'],
			'height' => ['nullable', 'numeric'],
			'patient_id' => ['required', 'integer'],
			'added_by_id' => ['nullable', 'integer'],
			'blood_systolic' => ['nullable', 'string'],
			'blood_diastolic' => ['nullable', 'string'],
			'bmi' => ['nullable'],
			'height_for_age' => ['nullable'],
			'weight_for_age' => ['nullable'],
			'blood_type' => ['nullable'],
			'bloody_type' => ['nullable'],
			'oxygen_saturation' => ['nullable'],
			'heart_rate' => ['nullable'],
			'regular_rhythm' => ['nullable'],
			'covid_19' => ['nullable'],
			'tb' => ['nullable'],
			'appointment_id' => ['nullable']
		]);

		// create new vital
		$vital = Vital::create($validatedData);

		if (request('appointment_id')) {
			$appointment = AppointmentData::findOrFail(request('appointment_id'));
			$appointment->vital_id = $vital->id;
			$appointment->save();

            $patientCase = PatientCase::query()->where('appointment_id', request('appointment_id'))->first();
            if($patientCase){
                $vitals = $patientCase->vitals ? json_decode($patientCase->vitals) : [];
                $vitals[] = [
                    'temperature' => $request->get('temperature'),
                    'respiratory' => $request->get('respiratory'),
                    'uric_acid' => $request->get('uric_acid'),
                    'cholesterol' => $request->get('cholesterol'),
                    'glucose' => $request->get('glucose'),
                    'pulse' => $request->get('pulse'),
                    'weight' => $request->get('weight'),
                    'height' => $request->get('height'),
                    'blood_systolic' => $request->get('blood_systolic'),
                    'blood_diastolic' => $request->get('blood_diastolic'),
                    'bmi' => $request->get('bmi'),
                    'height_for_age' => $request->get('height_for_age'),
                    'weight_for_age' => $request->get('weight_for_age'),
                    'blood_type' => $request->get('blood_type'),
                    'bloody_type' => $request->get('bloody_type'),
                    'oxygen_saturation' => $request->get('oxygen_saturation'),
                    'heart_rate' => $request->get('heart_rate'),
                    'regular_rhythm' => $request->get('regular_rhythm'),
                    'covid_19' => $request->get('covid_19'),
                    'tb' => $request->get('tb'),
                    'update_by' => $request->user()->name,
                    'update_at' => now()->toDateTimeString()
                ];
                $patientCase->vitals = json_encode($vitals);
                $patientCase->save();
            }
		}
		return response()->json([
			'data' => new PatientVitalResource($vital),
			'message' => 'Patient vitals created successfully.'
		], Response::HTTP_CREATED);
	}

	public function show(Vital $vitals)
	{
		return response()->json([
			'data' => new PatientVitalResource($vitals->load('patient', 'addedBy')),
			'message' => 'Patient vitals retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function update(Request $request, Vital $vitals)
	{
		// validate input
		$validatedData = $request->validate([
			'temperature' => ['required', 'numeric'],
			'respiratory' => ['nullable', 'numeric'],
			'uric_acid' => ['nullable', 'numeric'],
			'cholesterol' => ['nullable', 'numeric'],
			'glucose' => ['nullable', 'numeric'],
			'pulse' => ['nullable', 'numeric'],
			'weight' => ['nullable', 'numeric'],
			'height' => ['nullable', 'numeric'],
			'blood_systolic' => ['nullable', 'string'],
			'blood_diastolic' => ['nullable', 'string'],
			'bmi' => ['nullable'],
			'height_for_age' => ['nullable'],
			'weight_for_age' => ['nullable'],
			'blood_type' => ['nullable'],
			'oxygen_saturation' => ['nullable'],
			'heart_rate' => ['nullable'],
			'regular_rhythm' => ['nullable'],
			'covid_19' => ['nullable'],
			'tb' => ['nullable'],
		]);

		// update vital
		$vitals->update($validatedData);

		return response()->json([
			'data' => new PatientVitalResource($vitals),
			'message' => 'Patient vitals updated successfully.'
		], Response::HTTP_OK);
	}

	public function destroy(Vital $vitals)
	{
		// delete the vital
		$vitals->delete();
		return response()->json(['message' => 'Patient vital deleted successfully']);
	}

	public function vitalSigns($id)
	{
		$latestVitals = Vital::where('patient_id', $id)->latest()->first();

		return response()->json([
			'data' => new PatientVitalResource($latestVitals),
			'message' => 'Patient latest vital signs retrieved successfully.'
		], Response::HTTP_OK);
	}
}
