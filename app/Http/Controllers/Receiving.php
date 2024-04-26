<?php

namespace App\Http\Controllers;

use App\Models\_Receiving;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Receiving extends Controller
{
    public static function getInformation(Request $request)
    {
        return response()->json((new _Receiving)::getInformation($request));
    }

    public static function getPatientQueue(Request $request)
    {
        return response()->json((new _Receiving)::getPatientQueue($request));
    }

    public function newSpecimen(Request $request)
    {
        $result = (new _Receiving)::newSpecimen($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public static function specimentList(Request $request)
    {
        return response()->json((new _Receiving)::specimentList($request));
    }

    public function specimentRemove(Request $request)
    {
        $result = (new _Receiving)::specimentRemove($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function setAsDone(Request $request)
    {
        $result = (new _Receiving)::setAsDone($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function updateProfileImage(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/receiving');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Receiving::updateProfileImage($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function updateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Receiving::updateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function updatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Receiving::updatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function updatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Receiving::updatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    function receivingGetPersonalInfoById(Request $request){
        return response()->json((new _Receiving)::receivingGetPersonalInfoById($request));
    }
    
}
