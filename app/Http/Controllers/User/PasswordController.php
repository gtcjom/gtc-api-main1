<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{

    public function update(Request $request)
    {

        $request->validate([
			'old_password' => ['required','string'],
			'new_password' => ['required', 'string', 'min:6', 'max:25'],
            'confirm_new_password' => ['required', 'same:new_password'],
        ]);

        $user = $request->user();

		if (!Hash::check(request()->get('old_password'), $user->password)) {
			throw ValidationException::withMessages(['old_password' => 'Old password in incorrect']);
		}

        $user->password = Hash::make($request->get('new_password'));

        $user->save();

        return UserResource::make($user);
    }
}