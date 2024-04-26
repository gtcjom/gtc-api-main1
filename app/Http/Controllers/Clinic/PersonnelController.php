<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Http\Resources\UserResource;
use App\Services\PersonnelService;

class PersonnelController extends Controller
{

    public function index(PersonnelService $personnelService)
    {
        return UserResource::collection($personnelService->index());
    }

    public function store(StorePersonnelRequest $request, PersonnelService $personnelService)
    {
        return UserResource::make($personnelService->store($request));
    }

    public function update(UpdatePersonnelRequest $request, PersonnelService $personnelService, int $id)
    {
        return UserResource::make($personnelService->update($request,$id));
    }

	public function destroy(PersonnelService $personnelService, int $id)
	{
		return $personnelService->destroy($id);
		
	}
}