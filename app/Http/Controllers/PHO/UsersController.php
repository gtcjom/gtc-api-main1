<?php

namespace App\Http\Controllers\PHO;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function index(UserService $service)
    {
        request()->validate([
            'column' => ['nullable', Rule::in(['name',])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])]
        ]);

        return UserResource::collection($service->dataList());
    }

    public function store(UserRequest $request, UserService $service)
    {
        $user = $service->create();
        $user->load(['municipalityData','barangayData','purokData']);

        return UserResource::make($user);

    }

    public function update(UserRequest $request, UserService $service, int $id)
    {
        $user = $service->update($id);
        $user->load(['municipalityData','barangayData','purokData']);

        return UserResource::make($user);
    }
}