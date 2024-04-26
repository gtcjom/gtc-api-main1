<?php

namespace App\Http\Controllers;

use App\Models\_Documentation;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Documentation extends Controller
{
    public function hisDocumentationGetHeaderInfo(Request $request)
    {
        return response()->json((new _Documentation)::hisDocumentationGetHeaderInfo($request));
    }

    public function hisDocumentationGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Documentation)::hisDocumentationGetPersonalInfoById($request));
    }

    public function hisDocumentationUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/documentation');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Documentation::hisDocumentationUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisDocumentationUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Documentation::hisDocumentationUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisDocumentationUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Documentation::hisDocumentationUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisDocumentationUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Documentation::hisDocumentationUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getResultToEditOnline(Request $request){
        return response()->json((new _Documentation)::getResultToEditOnline($request));
    }

    public function getResultToEditLocal(Request $request){
        return response()->json((new _Documentation)::getResultToEditLocal($request));
    }

    public function saveEditedResult(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _Documentation::saveEditedResult($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function saveEditedResultLocal(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _Documentation::saveEditedResultLocal($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getAllBraches(Request $request){
        return response()->json((new _Documentation)::getAllBraches($request));
    }

    public function getResultToPrint(Request $request){
        return response()->json((new _Documentation)::getResultToPrint($request));
    }

    public function getPatientInfoPatientId(Request $request){
        return response()->json((new _Documentation)::getPatientInfoPatientId($request));
    }
    
}
