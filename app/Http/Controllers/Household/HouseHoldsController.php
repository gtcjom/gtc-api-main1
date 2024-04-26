<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Http\Resources\HouseholdResource;
use App\Services\HouseholdService;
use App\Services\InterviewService;
use App\Services\SubmissionEditService;

class HouseHoldsController extends Controller
{

    public function index(HouseholdService $householdService)
    {
        return HouseholdResource::collection($householdService->dataList());
    }
    public function show(HouseholdService $householdService, int $id)
    {
        return HouseholdResource::make($householdService->getHousehold($id));
    }

    public function store(HouseholdService $householdService, InterviewService $interviewService)
    {
        request()->merge([
                'management' => 'yes'
        ]);
        $households = $householdService->create();

        $interviewService->houseHoldData($households->rawAnswer);

        return HouseholdResource::make($households);
    }

    public function update(HouseholdService $householdService, SubmissionEditService $interviewService, int $id)
    {
        request()->merge([
            'management' => 'yes'
        ]);
        $households = $householdService->update($id);

        $interviewService->houseHoldData($households['raw']);

        return response()->noContent();
    }
}
