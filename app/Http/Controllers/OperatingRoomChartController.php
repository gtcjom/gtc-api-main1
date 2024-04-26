<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperatingRoomChartRequest;
use App\Http\Resources\OperatingRoomChartResource;
use App\Services\OperatingRoomChartService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Patient;

class OperatingRoomChartController extends Controller
{
	public function store(StoreOperatingRoomChartRequest $request, OperatingRoomChartService $operatingRoomChartService)
	{
		$patient = Patient::findOrFail($request->patient_id);

		if ($patient->existingOperatingRoomChart($request->clinic_id)->count() >= 1) {
			return response()->json(['message' => 'Patient already has an existing operating room chart schedule.'], Response::HTTP_OK);
		} else {
			return response()->json([
				'data' => OperatingRoomChartResource::make($operatingRoomChartService->store($request)),
				'message' => 'Operating room chart created successfully.'
			], Response::HTTP_CREATED);
		}
	}

	public function show(int $id, OperatingRoomChartService $operatingRoomChartService)
	{
		return response()->json([
			'data' => OperatingRoomChartResource::make($operatingRoomChartService->show($id)
				->load('patient', 'clinic', 'healthcareProfessionals', 'appointment')),
			'message' => 'Operating room chart retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function update(Request $request, OperatingRoomChartService $operatingRoomChartService, int $id)
	{
		return response()->json([
			'data' => OperatingRoomChartResource::make($operatingRoomChartService->update($request, $id)),
			'message' => 'Client referral updated successfully.'
		], Response::HTTP_OK);
	}

	public function destroy(int $id, OperatingRoomChartService $operatingRoomChartService)
	{
		$operatingRoomChartService->delete($id);
		return response()->json(['message' => 'Operating room chart deleted successfully.'], Response::HTTP_OK);
	}

	public function list(Request $request, OperatingRoomChartService $operatingRoomChartService)
	{
		return response()->json([
			'data' => OperatingRoomChartResource::collection($operatingRoomChartService->list($request)),
			'message' => 'List of operating room charts retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function toResu(OperatingRoomChartService $operatingRoomChartService, int $id)
	{
		return response()->json([
			'data' => OperatingRoomChartResource::make($operatingRoomChartService->toResu($id)),
			'message' => 'Operating room chart sent to RESU.',
		], Response::HTTP_OK);
	}

	public function done(OperatingRoomChartService $operatingRoomChartService, int $id)
	{
		return response()->json([
			'data' => OperatingRoomChartResource::make($operatingRoomChartService->done($id)),
			'message' => 'Operating room chart was successfully completed.',
		], Response::HTTP_OK);
	}
}
