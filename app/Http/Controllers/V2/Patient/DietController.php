<?php

namespace App\Http\Controllers\V2\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientDietResource;
use App\Models\V2\Patient\Diet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DietController extends Controller
{
    public function index($id)
    {
        return PatientDietResource::collection(Diet::where('patient_id', $id)->latest()->get());
    }

    public function store(Request $request)
    {
        // validate data
		$request->validate([
			'patient_id' => ['required', 'string'],
			'added_by_id' => ['nullable'],
			'meals' => ['required', 'string'],
			'date' => ['required', 'date'],
			'description' => ['required', 'string'],
		]);

		// create patient diet
		$diet = Diet::create(array_merge([
			'patient_id' => $request->patient_id,
			'added_by_id' => $request->user()->id,
			'meals' => $request->meals,
			'date' => $request->date,
			'description' => $request->description,
		]));

		return response()->json([
			'data' => new PatientDietResource($diet),
			'message' => 'Patient diet created successfully.'
		], Response::HTTP_CREATED);
    }

    public function show(Diet $diet)
    {
		return response()->json([
			'data' => new PatientDietResource($diet),
			'message' => 'Patient diet retrived successfully.'
		], Response::HTTP_OK);
    }

    public function update(Request $request, Diet $diet)
    {
        // validate data
		$data = $request->validate([
			'meals' => ['required', 'string'],
			'date' => ['required', 'date'],
			'description' => ['required', 'string'],
		]);

		// update patient diet
		$diet->update(array_merge($data));

		return response()->json([
			'data' => new PatientDietResource($diet),
			'message' => 'Patient diet updated successfully.'
		], Response::HTTP_OK);
    }

    public function destroy(Diet $diet)
    {
        $diet->delete();
		return response()->json(['message' => 'Patient diet deleted successfully']);
    }
}
