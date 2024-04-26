<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\AppointmentDataResource;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\HealthUnitResource;
use App\Http\Resources\UserResource;
use App\Models\AppointmentData;
use App\Models\HealthUnit;
use App\Services\Cloud\PhoPatientCaseService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends  Controller
{
	public function show(UserService $userService)
	{
		return new UserResource($userService->show());
	}

	public function update(ProfileRequest $request, UserService $userService)
	{
		return new UserResource($userService->profileUpdate());
	}

	public function updateUsername(Request $request)
	{
		request()->validate([
			'old_username' => ['required', 'string', 'max:50'],
			'new_username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore(request()->user()->id)],
			'confirm_new_username' => ['required', 'same:new_username'],
		]);

		$user = request()->user();

		if ($request->old_username !== $user->username) {
			throw ValidationException::withMessages(['old_username' => 'Old username is incorret']);
		}

		$user->username = $request->new_username;
		$user->save();

		return UserResource::make($user);
	}

	public function me()
	{
		return UserResource::make(Auth::user());
	}
	public function profileData(PhoPatientCaseService $service)
	{
		$location = $this->getUserLocation();
        $pendingReferrals = [
            'data' => [],
        ];
        $reading =  [
            'data' => [],
        ];
        $user = \request()->user();
        if($user->type == 'RHU-DOCTOR'){
            $reading = $service->getCloudCaseList([
                'rhu_id' => config('health_unit_id', '3'),
                'referred_to_doctor' => $user->id,
                'status' => 'pending-doctor-confirmation',
                'referral_accepted' => 0,
            ]);
        }else{
            $pendingReferrals = $service->getListOfReferrals(config('health_unit_id', '3'));
        }

		return [
			'user' => Auth::user(),
			'healthUnit' => HealthUnitResource::make($this->getUserLocation()),
			'referrals' => $pendingReferrals['data'],
            'reading' => $reading['data'],

		];
	}
	public function getUserLocation()
	{
		$user = request()->user();
		$location = HealthUnit::first();
		if ($user->health_unit_id) {
			$location = HealthUnit::findOrFail($user->health_unit_id);
		} else {

			if (str_contains($user->type, 'RHU')) {
				$location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
			}
			if ($user->type == 'LMIS-BHS' || $user->type == 'BHS-BHW') {
				$location = HealthUnit::query()->where('type', '=', 'BHS')->where('barangay_id', '=', $user->barangay)->first();
			}
			if ($user->type == 'LMIS-CNOR') {
				$location = HealthUnit::query()->where('type', 'CNOR')->first();
			}
		}

		return $location;
	}
}
