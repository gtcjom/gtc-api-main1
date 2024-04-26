<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicRequest;
use App\Http\Resources\ClinicResources;
use App\Services\ClinicService;
use Illuminate\Http\Request;

class ClinicController extends Controller
{

    public function  index(ClinicService $clinicService)
    {
        return ClinicResources::collection($clinicService->index());
    }
    public function getClinics(ClinicService $clinicService, $type = '')
    {
        return ClinicResources::collection($clinicService->getClinicOftype($type));
    }
    public function store(ClinicService $clinicService, ClinicRequest $request)
    {
        return ClinicResources::make($clinicService->create($request));
    }

    public function update(ClinicService $clinicService, ClinicRequest $request, int $id)
    {
        return ClinicResources::make($clinicService->update($request, $id));
    }

    public function show(ClinicService $clinicService, int $id)
    {
        return ClinicResources::make($clinicService->show($id));
    }

    public function destroy(ClinicService $clinicService, int $id)
    {
        $clinicService->delete($id);

        return response()->noContent();
    }

    public function myClinic(ClinicService $clinicService)
    {
        $clinic = $clinicService->getClinic();
        if (is_null($clinic)) {
            return response()->json(['message' => 'You are not yet assigned to a clinic'], 404);
        }
        return ClinicResources::make($clinic);
    }

    public function capableClinics(ClinicService $clinicService, Request $request)
    {
        return ClinicResources::collection($clinicService->capableClinics($request));
    }
}
