<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaboratoryResultRequest;
use App\Http\Requests\UpdateLaboratoryResultRequest;
use App\Http\Resources\LaboratoryResultResource;
use App\Models\LaboratoryResult;
use App\Services\LaboratoryResultService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class LaboratoryResultController extends Controller
{

	public function index(Request $request, LaboratoryResultService $laboratoryResultService)
    {
        return LaboratoryResultResource::collection($laboratoryResultService->list($request));
    }

    public function store(StoreLaboratoryResultRequest $request, LaboratoryResultService $laboratoryResultService)
    {
		return response()->json([
			'data' => new LaboratoryResultResource($laboratoryResultService->store($request)),
			'message' => 'Laboratory result created successfully.'
		], Response::HTTP_CREATED);
    }

    public function show(LaboratoryResult $laboratoryResult)
    {
		return response()->json([
			'data' => new LaboratoryResultResource($laboratoryResult->load('laboratoryOrder', 'addedBy')),
			'message' => 'Laboratory result retrieved successfully.'
		], Response::HTTP_OK);
    }

    public function update(UpdateLaboratoryResultRequest $request, LaboratoryResultService $laboratoryResultService, int $id)
    {
		return response()->json([
			'data' => new LaboratoryResultResource($laboratoryResultService->update($request, $id)),
			'message' => 'Laboratory result updated successfully.'
		], Response::HTTP_OK);
    }
}
