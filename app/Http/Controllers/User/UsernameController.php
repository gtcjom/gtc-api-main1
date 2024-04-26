<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Validation\Rule;

class UsernameController extends Controller
{

    public function update(UserService $userService)
    {
        request()->validate([
            'username' => ['required','string','max:50',Rule::unique('users', 'username')->ignore(request()->user()->id)],
            'password' => ['required','string']
        ]);

        return UserResource::make($userService->profileUpdate());
    }
}