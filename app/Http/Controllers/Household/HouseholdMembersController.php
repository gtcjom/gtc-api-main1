<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Services\HouseholdService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HouseholdMembersController extends  Controller
{


    public function update(Request $request, HouseholdService $householdService, int $id)
    {
        $request->validate([
            'patient_ids' => ['nullable', 'array'],
            'head_relation' => [Rule::requiredIf( fn() => !is_null($request->patient_ids)), 'array'],
        ]);

        $householdService->updateMembers($id);

        return response()->noContent();

    }
}