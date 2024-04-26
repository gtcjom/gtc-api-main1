<?php

namespace App\Http\Controllers;

use App\Http\Resources\HealthUnitResource;
use App\Models\HealthUnit;
use App\Services\HealthUnitService;
use Illuminate\Http\Request;

class HealthUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HealthUnitService $healthUnitService)
    {
        return HealthUnitResource::collection($healthUnitService->getHealthUnitList());
    }

    public function store(Request $request)
    {
        $hu = new HealthUnit();
        $hu->name = $request->get('name');
        $hu->type = $request->get('type');
        $hu->barangay_id = $request->get('barangay_id');
        $hu->municipality_id = $request->get('municipality_id');
        $hu->status = $request->get('status');
        $hu->region = $request->get('region');
        $hu->province = $request->get('province');
        $hu->municipality = $request->get('municipality');
        $hu->barangay = $request->get('barangay');
        $hu->street = $request->get('street');
        $hu->purok = $request->get('purok');
        $hu->zip = $request->get('zip');
        $hu->save();
        return HealthUnitResource::make($hu);
    }
    public function update($id, Request $request)
    {
        $hu = HealthUnit::query()->findOrFail($id);
        $hu->name = $request->get('name');
        $hu->type = $request->get('type');
        $hu->barangay_id = $request->get('barangay_id');
        $hu->municipality_id = $request->get('municipality_id');
        $hu->status = $request->get('status');
        $hu->region = $request->get('region');
        $hu->province = $request->get('province');
        $hu->municipality = $request->get('municipality');
        $hu->barangay = $request->get('barangay');
        $hu->street = $request->get('street');
        $hu->purok = $request->get('purok');
        $hu->zip = $request->get('zip');
        $hu->save();
        return HealthUnitResource::make($hu);
    }
    public function activate($id, Request $request)
    {
        $hu = HealthUnit::query()->findOrFail($id);
        $hu->status = 'active';
        $hu->save();
        return HealthUnitResource::make($hu);
    }
    public function deactivate($id, Request $request)
    {
        $hu = HealthUnit::query()->findOrFail($id);
        $hu->status = 'inactive';
        $hu->save();
        return HealthUnitResource::make($hu);
    }
    public function getUserHealthUnit(Request $request)
    {

        $user = request()->user();
        $location = HealthUnit::first();
        if (str_contains($user->type, 'RHU')) {
            $location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
        }
        if ($user->type == 'LMIS-BHS' || $user->type == 'BHS-BHW') {
            $location = HealthUnit::query()->where('type', '=', 'BHS')->where('barangay_id', '=', $user->barangay)->first();
        }
        if ($user->type == 'LMIS-CNOR') {
            $location = HealthUnit::query()->where('type', 'CNOR')->first();
        }
        return $location;
        return HealthUnitResource::make($location);
    }
}
