<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\_Validator;
use DB;
use Illuminate\Http\Request;

class Message extends Controller
{
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $query = DB::table('message_from_users')
            ->insert([
                'msg_id' => rand(0, 9999) . time(),
                'fullname' => $request->fullname,
                'email' => $request->email_add,
                'msg' => $request->message,
                'created_at' => date('Y-m-d H:i:s'),
                'update_at' => date('Y-m-d H:i:s'),
            ]);
        if ($query) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public static function checkInternetConnectionStatus(Request $request)
    {
        if ($request->connection == 'online') {
            if (!_Validator::checkInternetConnection()) {
                return response()->json([
                    "message" => 'disconnected',
                ]);
            }
        }

        return response()->json([
            "message" => 'connected',
        ]);
    }
}
