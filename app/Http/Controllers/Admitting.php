<?php

namespace App\Http\Controllers;

use App\Models\_Admitting;
use App\Models\_Validator;
use Illuminate\Http\Request;


class Admitting extends Controller
{
    public function getAdmittingInfo(Request $request)
    {
        return response()->json((new _Admitting)::getAdmittingInfo($request));
    }

    public function AdmittingGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Admitting)::AdmittingGetPersonalInfoById($request));
    }

    public function AdmittingUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/admitting');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Admitting::AdmittingUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function AdmittingUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Admitting::AdmittingUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function AdmittingUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Admitting::AdmittingUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function AdmittingUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Admitting::AdmittingUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getHospitalRoooms(Request $request)
    {
        return response()->json((new _Admitting)::getHospitalRoooms($request));
    }

    public function getHospitalRooomDetails(Request $request)
    {
        return response()->json((new _Admitting)::getHospitalRooomDetails($request));
    }

    public function getHospitalRooomBedsDetails(Request $request)
    {
        return response()->json((new _Admitting)::getHospitalRooomBedsDetails($request));
    }

    public function getPatientsForAdmit(Request $request)
    {
        return response()->json((new _Admitting)::getPatientsForAdmit($request));
    }

    public function getRoomNumberList(Request $request)
    {
        return response()->json((new _Admitting)::getRoomNumberList($request));
    }

    public function getRoomsBedsList(Request $request)
    {
        return response()->json((new _Admitting)::getRoomsBedsList($request));
    }

    public function handleAdmitPatient(Request $request)
    {
        $result = (new _Admitting)::handleAdmitPatient($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        }

        return response()->json([
            "message" => "db-error",
        ]);
    }

    public function getRoomsListByRoomId(Request $request)
    {
        return response()->json((new _Admitting)::getRoomsListByRoomId($request));
    }

    public function getRoomsBedByRoomId(Request $request)
    {
        return response()->json((new _Admitting)::getRoomsBedByRoomId($request));
    }

}
