<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth; 
use DB;
use Session; 
class Logout extends Controller
{
    public function index(Request $request){ 

        date_default_timezone_set('Asia/Manila'); 
        // insert to log   
        
        if (Auth::check()){

            // ModelHelper::newLogs('Logout', Auth::user()->user_id, 'Logout Page');

            // signout active_users
            DB::table('active_users')->where('user_id', Auth::user()->user_id)->update([
                'token'=>null,
                'status'=>0,
                'updated_at'=>date('Y-m-d H:i:s')
            ]); 
        } 

        Auth::logout();
        Session::flush();
        //session_start();  
        return response()->json(['message' => 'logout-success']);
    }
}
