<?php

namespace App\Http\Controllers\Clinic\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnosisResource;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiagnosisController extends Controller
{
	public function index()
	{
		$id = request()->get('patient_id');
		return DiagnosisResource::collection(Diagnosis::where('patient_id', $id)->latest()->get());
	}

	public function store(Request $request)
	{
		// validate data
		$request->validate([
			'patient_id' => ['required', 'string'],
			'added_by_id' => ['nullable'],
			'title' => ['nullable', 'string'],
			'description' => ['required', 'string'],
			'datetime' => ['required'],
			'diagnosis_list' => ['nullable', 'array', 'min:1']
		]);

		// create diagnosis
		$diagnosis = Diagnosis::create(array_merge([
			'patient_id' => $request->patient_id,
			'added_by_id' => $request->user()->id,
			'title' => $request->title,
			'description' => $request->description,
			'datetime' => $request->datetime,
		]));

		// create list of diagnosis values
		foreach ($request->diagnosis_list as $list) {
			$diagnosis->diagnosisList()->create([
				'description' => $list
			]);
		}
		return response()->json([
			'data' => new DiagnosisResource($diagnosis),
			'message' => 'Diagnosis created successfully.'
		], Response::HTTP_CREATED);
	}

	public function show(Diagnosis $diagnosis)
	{
		return response()->json([
			'data' => new DiagnosisResource($diagnosis),
			'message' => 'Diagnosis retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function update(Request $request, Diagnosis $diagnosis)
	{
		// validate data
		$request->validate([
			'title' => ['nullable', 'string'],
			'description' => ['required', 'string'],
			'datetime' => ['required'],
			'diagnosis_list' => ['required', 'array', 'min:1']
		]);

		// update diagnosis
		$diagnosis->update(array_merge([
			'title' => $request->title,
			'description' => $request->description,
			'datetime' => $request->datetime,
		]));

		$diagnosis->diagnosisList()->delete();

		// create list of diagnosis values
		foreach ($request->diagnosis_list as $list) {
			$diagnosis->diagnosisList()->create([
				'description' => $list
			]);
		}
		return response()->json([
			'data' => new DiagnosisResource($diagnosis),
			'message' => 'Diagnosis updated successfully.'
		], Response::HTTP_OK);
	}

	public function destroy(Diagnosis $diagnosis)
	{
		$diagnosis->diagnosisList()->delete();
		$diagnosis->delete();
		return response()->json(['message' => 'Diagnosis deleted successfully']);
	}
}
