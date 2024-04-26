<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\_ProcessRegistration;
use App\_Validator;

class ProcessRegistration extends Controller
{
    public function index(Request $request){ 
        if(_Validator::checkEmailInPatient($request->email)){
            return response()->json('email-exist');
        }else{ 
            if((new _ProcessRegistration)::index($request)){
                return response()->json('success'); 
            }
        }
    }

    public function generateHashString(Request $request){
        return Hash::make($request->generate_password);
    }
 
}
