<?php

namespace App\Http\Controllers;

use App\Models\_OM;
use App\Models\_Validator;
use Illuminate\Http\Request;

class OM extends Controller
{
    public function hisOMHeaderInfo(Request $request){
        return response()->json((new _OM)::hisOMHeaderInfo($request));
    }

    public function hisOMGetPersonalInfoById(Request $request){
        return response()->json((new _OM)::hisOMGetPersonalInfoById($request));
    }

    public function hisOMUploadProfile(Request $request){
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/om');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _OM::hisOMUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisOMUpdateUsername(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _OM::hisOMUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisOMUpdatePassword(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _OM::hisOMUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisOMUpdatePersonalInfo(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _OM::hisOMUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }
    
}
