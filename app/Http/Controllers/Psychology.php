<?php

namespace App\Http\Controllers;

use App\Models\_Psychology;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Psychology extends Controller
{
    public function getHeaderInfo(Request $request)
    {
        return response()->json((new _Psychology)::getHeaderInfo($request));
    }

    public static function getNewPatients(Request $request)
    {
        return response()->json((new _Psychology)::getNewPatients($request));
    }

    public static function getPsychologyTest(Request $request)
    {
        return response()->json((new _Psychology)::getPsychologyTest($request));
    }

    public static function newPsychologyTest(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => "pass-invalid",
            ]);
        }

        if ((new _Psychology)::newPsychologyTest($request)) {
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
        return response()->json((new _Psychology)::getPatientWithOrder($request));
    }

    public static function getPatientWithOrderVan(Request $request)
    {
        return response()->json((new _Psychology)::getPatientWithOrderVan($request));
    }
    
    public function hisPsychologyGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Psychology)::hisPsychologyGetPersonalInfoById($request));
    }

    public function hisPsychologyUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Psychology::hisPsychologyUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisPsychologyUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/psychology');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Psychology::hisPsychologyUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisPsychologyUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Psychology::hisPsychologyUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisPsychologyUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Psychology::hisPsychologyUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    //audiometric report
    public function getOrderAudiometryNew(Request $request)
    {
        return response()->json((new _Psychology)::getOrderAudiometryNew($request));
    }
    public function getOrderAudiometryNewDetails(Request $request)
    {
        return response()->json((new _Psychology)::getOrderAudiometryNewDetails($request));
    }
    public static function psychologyAudiometryOrderProcessed(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Psychology::psychologyAudiometryOrderProcessed($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveAudiometryOrderResult(Request $request)
    {
        if ((new _Psychology)::saveAudiometryOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public function getCompleteAudiometryOrderDetails(Request $request)
    {
        return response()->json((new _Psychology)::getCompleteAudiometryOrderDetails($request));
    }

    //ishihara test
    public function getOrderIshiharaNew(Request $request){
        return response()->json((new _Psychology)::getOrderIshiharaNew($request));
    }
    public function getOrderIshiharaNewDetails(Request $request){
        return response()->json((new _Psychology)::getOrderIshiharaNewDetails($request));
    }
    public static function psychologyIshiharaOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Psychology::psychologyIshiharaOrderProcessed($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveIshiharaOrderResult(Request $request){
        if ((new _Psychology)::saveIshiharaOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public function getCompleteIshiharaOrderDetails(Request $request){
        return response()->json((new _Psychology)::getCompleteIshiharaOrderDetails($request));
    }

    //neurology
    public function getOrderNeurologyNew(Request $request){
        return response()->json((new _Psychology)::getOrderNeurologyNew($request));
    }
    public function getOrderNeurologyNewDetails(Request $request){
        return response()->json((new _Psychology)::getOrderNeurologyNewDetails($request));
    }
    public static function psychologyNeurologyOrderProcessed(Request $request){
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Psychology::psychologyNeurologyOrderProcessed($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }
    public function saveNeurologyOrderResult(Request $request){
        if ((new _Psychology)::saveNeurologyOrderResult($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    public function getCompleteNeurologyOrderDetails(Request $request){
        return response()->json((new _Psychology)::getCompleteNeurologyOrderDetails($request));
    }

    public function getPsychologyCompletedReport(Request $request)
    {
        return response()->json((new _Psychology)::getPsychologyCompletedReport($request));
    }

    public function getPsycologyOrder(Request $request)
    {
        return response()->json((new _Psychology)::getPsycologyOrder($request));
    }

    public function getUnsavePsycologyOrder(Request $request)
    {
        return response()->json((new _Psychology)::getUnsavePsycologyOrder($request));
    }

    public function addPsycOrderTounsave(Request $request)
    {
        $result = _Psychology::addPsycOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removePsyOrderFromUnsave(Request $request)
    {
        $result = _Psychology::removePsyOrderFromUnsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function processPsychologyOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Psychology::processPsychologyOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getPsychologyUnpaidList(Request $request)
    {
        $result = _Psychology::getPsychologyUnpaidList($request);
        return response()->json($result);
    }

    public function getPsychologyUnpaidListDetails(Request $request)
    {
        $result = _Psychology::getPsychologyUnpaidListDetails($request);
        return response()->json($result);
    }

    public function psychologyPaidOrderByPatient(Request $request)
    {
        return response()->json((new _Psychology)::psychologyPaidOrderByPatient($request));
    }

    public function getAllPsychologyReport(Request $request){
        return response()->json((new _Psychology)::getAllPsychologyReport($request));
    }

    public function getAllPsychologyReportFilter(Request $request){
        return response()->json((new _Psychology)::getAllPsychologyReportFilter($request));
    }

}
