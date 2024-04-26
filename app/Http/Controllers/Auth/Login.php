<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Login extends Controller
{
    public function index(Request $request)
    {
        $token = Auth::attempt($request->only('username', 'password', 'status'));

        if (!$token) {
            return response()->json(['msg' => 'account-invalid']);
        }

        $userData = [];
        $userData[] = array(
            "user_id" => Auth::user()->user_id,
            "type" => Auth::user()->type,
            "username" => Auth::user()->username,
            "token" => $token,
            "manage_by" => Auth::user()->manage_by,
            "main_mgmt_id" => Auth::user()->main_mgmt_id,
            "is_login" => true,
        );

        return response()->json($userData);
    }

    public static function verifyLocalStorageAccount(Request $data)
    {
        return DB::table('users')->select('type', 'manage_by', 'user_id', 'username')->where('type', $data->get('type'))->where('user_id', $data->get('user_id'))->get();
    }
}
