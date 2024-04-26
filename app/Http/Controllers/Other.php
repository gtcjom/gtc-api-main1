<?php

namespace App\Http\Controllers;

use App\Models\_Other;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Other extends Controller
{
    public function hisOtherHeaderInfo(Request $request){
        return response()->json((new _Other)::hisOtherHeaderInfo($request));
    }

    public function hisOtherGetPersonalInfoById(Request $request){
        return response()->json((new _Other)::hisOtherGetPersonalInfoById($request));
    }

    public function hisOtherUploadProfile(Request $request){
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/other');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Other::hisOtherUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisOtherUpdateUsername(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _Other::hisOtherUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisOtherUpdatePassword(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _Other::hisOtherUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisOtherUpdatePersonalInfo(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _Other::hisOtherUpdatePersonalInfo($request);
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
