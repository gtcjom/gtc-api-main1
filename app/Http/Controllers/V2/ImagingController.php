<?php

namespace App\Http\Controllers\V2;

use App\Enums\ImagingStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImagingResource;
use App\Models\V2\Imaging;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImagingController extends Controller
{
	public function index()
	{
		return ImagingResource::collection(Imaging::latest()->get()->load('patient', 'processedBy'));
	}
	public function getPatientImaging(int $patientId)
	{
		return ImagingResource::collection(Imaging::query()->where('patient_id', $patientId)->latest()->get()->load('patient', 'processedBy'));
	}

	public function store(Request $request)
	{
		// validate input
		$request->validate([
			'patient_id' => ['required', 'string'],
			'description' => ['required', 'string'],
			'type' => ['required', 'string'],
			'image' => ['required', 'image', 'mimes:jpeg,jpg,png'],
			'processed_by' => ['nullable'],
			'processed_at' => ['nullable'],
			'status' => ['nullable'],
		]);

		// create imaging
		$imaging = new Imaging();
		$imaging->patient_id = $request->patient_id;
		$imaging->description = $request->description;
		$imaging->type = $request->type;
		$imaging->processed_by = $request->user()->id;
		$imaging->processed_at = Carbon::now();
		if ($request->hasFile('image')) {
			$imaging->image = $request->file('image')->store('imaging');
		}
		$imaging->save();


		return response()->json([
			'data' => new ImagingResource($imaging),
			'message' => 'Imaging created successfully.'
		], Response::HTTP_CREATED);
	}

	public function show(Imaging $imaging)
	{
		return response()->json([
			'data' => new ImagingResource($imaging->load('patient', 'processedBy')),
			'message' => 'Imaging retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function update(Request $request, Imaging $imaging)
	{
		// validate input
		$request->validate([
			'description' => ['required', 'string'],
			'type' => ['required', 'string'],
			'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png'],
		]);

		// update imaging
		$imaging->update(array_merge([
			'description' => $request->description,
			'type' => $request->type,
		]));

		if ($request->hasFile('avatar')) {
			$imaging->image = $request->file('image')->store('imagings/image');
			$imaging->save();
		}

		return response()->json([
			'data' => new ImagingResource($imaging),
			'message' => 'Imaging updated successfully.'
		], Response::HTTP_OK);
	}
}
