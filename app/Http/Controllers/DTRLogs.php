<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_DTRLogs; 
use App\Models\_Validator;

class DTRLogs extends Controller
{
    public function getInsertInLogs(Request $request){
        if(_Validator::verifyUserId($request)){
            if(! _Validator::userIdAlredyLog($request)){
                $result = _DTRLogs::getInsertInLogs($request);
                if($result){
                    return response()->json('success');
                }   
                else{
                    return response()->json('db-error');
                }
            }
            else{
                return response()->json('user-already-log');
            }
        }
        else{
            return response()->json('user-not-existed');
        }
    }

    public function getInsertOutLogs(Request $request){
        if(_Validator::verifyUserId($request)){
            if(! _Validator::userIdAlredyLog($request)){
                $result = _DTRLogs::getInsertOutLogs($request);
                if($result){
                    return response()->json('success');
                }   
                else{
                    return response()->json('user-not-yet-login');
                }
            }
            else{
                return response()->json('user-already-log');
            }
        }
        else{
            return response()->json('user-not-existed');
        }
    }


}
