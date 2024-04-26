<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Models\_Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    function myinfo(Request $request)
    {

        $user = Auth::user();
        $management_name = _Validator::getManagementName($user->manage_by, $user->main_mgmt_id);

        return [
            "email" => $user->email,
            "management_id" => $user->manage_by,
            "main_mgmt_id" => $user->main_mgmt_id,
            "type" => $user->type,
            "user_id" => $user->user_id,
            "username" => $user->username,
            "ready" => true,
            "management_name" => $management_name->name ?? "",
            'name' => $user->name ?: "",
            'avatar' => $user->avatar ? Storage::url($user->avatar) : "",
            'id' => $user->id,
            'municipality_id' => $user->municipality,
            'barangay_id' => $user->barangay,
            'purok_id' => $user->purok,
        ];
    }
}
