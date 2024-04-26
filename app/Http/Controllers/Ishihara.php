<?php

namespace App\Http\Controllers;

use App\Models\_Ishihara;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Ishihara extends Controller
{
    public function getHeaderInfo(Request $request)
    {
        return response()->json((new _Ishihara)::getHeaderInfo($request));
    }

    public static function getNewPatients(Request $request)
    {
        return response()->json((new _Ishihara)::getNewPatients($request));
    }

    public static function getIshiharaTest(Request $request)
    {
        return response()->json((new _Ishihara)::getIshiharaTest($request));
    }

    public static function newIshiharaTest(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => "pass-invalid",
            ]);
        }

        if ((new _Ishihara)::newIshiharaTest($request)) {
            return response()->json([
                "message" => "success",
            ]);
        }

        return response()->json([
            "message" => "db-error",
        ]);
    }

    public static function getPatientWithOrder(Request $request)
    {
        return response()->json((new _Ishihara)::getPatientWithOrder($request));
    }

    public function hisishiharaGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Ishihara)::hisishiharaGetPersonalInfoById($request));
    }

    public function hisishiharaUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Ishihara::hisishiharaUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisishiharaUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/isihihara');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Ishihara::hisishiharaUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisishiharaUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Ishihara::hisishiharaUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisishiharaUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Ishihara::hisishiharaUpdatePassword($request);
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
