<?php

namespace App\Http\Controllers\V2\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\TreatmentPlanResource;
use App\Models\V2\TreatmentPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TreatmentPlanController extends Controller
{

    public function index($id)
    {
        return TreatmentPlanResource::collection(TreatmentPlan::where('patient_id', $id)->get());
    }

    public function store(Request $request)
    {
		//validate input
		$request->validate([
			'patient_id' => ['required', 'string'],
			'schedule' => ['required', 'date'],
			'description' => ['required', 'string'],
			'added_by' => ['nullable'],
		]);

		//create treatment plan
		$treatmentPlan = TreatmentPlan::create(array_merge([
			'patient_id' => $request->patient_id,
			'schedule' => $request->schedule,
			'description' => $request->description,
			'added_by' => request()->user()->user_id,
		]));

		return response()->json([
			'data' => new TreatmentPlanResource($treatmentPlan),
			'message' => 'Treatment plan created successfully.'
		], Response::HTTP_CREATED);

    }

    public function show(TreatmentPlan $treatmentPlan)
    {
        return response()->json([
			'data' => new TreatmentPlanResource($treatmentPlan->load('patient', 'addedBy')),
			'message' => 'Treatment plan retrieved successfully.'
		], Response::HTTP_OK);
    }

    public function update(Request $request, TreatmentPlan $treatmentPlan)
    {
       // validate input
		$data = $request->validate([
			'schedule' => ['required', 'date'],
			'description' => ['required', 'string'],
		]);

		// update treatment plan
		$treatmentPlan->update(array_merge($data));

		return response()->json([
			'data' => new TreatmentPlanResource($treatmentPlan),
			'message' => 'Treatment plan updated successfully.'
		], Response::HTTP_OK);
    }

    public function destroy(TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->delete();
		return response()->json(['message' => 'Treatment plan deleted successfully']);
    }
}
