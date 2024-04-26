<?php

namespace App\Http\Controllers;

use App\Models\_Admission;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Admission extends Controller
{
    public function hisadmissionGetHeaderInfo(Request $request)
    {
        return response()->json((new _Admission)::hisadmissionGetHeaderInfo($request));
    }

    public function hisadmissionGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Admission)::hisadmissionGetPersonalInfoById($request));
    }

    public function hisadmissionUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/registration');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Admission::hisadmissionUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisadmissionUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Admission::hisadmissionUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisadmissionUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Admission::hisadmissionUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisadmissionUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Admission::hisadmissionUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisadmissionGetPatientList(Request $request)
    {
        return response()->json(_Admission::hisadmissionGetPatientList($request));
    }

    public function hisadmissionGetAllDoctors(Request $request)
    {
        return response()->json(_Admission::hisadmissionGetAllDoctors($request));
    }

    public function hisadmissionNewPatient(Request $request)
    {
        // $filename = '';
        // if (!_Validator::verifyAccount($request)) {
        //     return response()->json('pass-invalid');
        // }
        if (!empty($request->email)) {
            if (_Validator::checkEmailInPatient($request->email)) {
                return response()->json('email-exist');
            }
        }
        // if (str_contains($request->image, 'data:image/png;base64')) {
        //     $destinationPath = public_path('../images/patients/'); // set folder where to save
        //     $img = $request->image;
        //     $img = str_replace('data:image/png;base64,', '', $img);
        //     $img = str_replace(' ', '+', $img);
        //     $data = base64_decode($img);
        //     $filename = time() . '.jpeg';

        //     $file = $destinationPath . $filename;
        //     $success = file_put_contents($file, $data);
        // }
        if (_Admission::hisadmissionNewPatient($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function hisadmissionGetPatientInformation(Request $request)
    {
        return response()->json(_Admission::hisadmissionGetPatientInformation($request));
    }

    public function hisadmissionGetPatientInformationTriage(Request $request)
    {
        return response()->json(_Admission::hisadmissionGetPatientInformationTriage($request));
    }

    public function hisadmissionGetPatientInfo(Request $request)
    {
        return response()->json(_Admission::hisadmissionGetPatientInfo($request));
    }

    public function hisadmissionUpdatePatientInfo(Request $request)
    {
        // if (!_Validator::verifyAccount($request)) {
        //     return response()->json('pass-invalid');
        // }
        if (_Admission::hisadmissionUpdatePatientInfo($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function getContactTracingRecord(Request $request)
    {
        return response()->json(_Admission::getContactTracingRecord($request));
    }

    public function getImagingDetails(Request $request)
    {
        return response()->json(_Admission::getImagingDetails($request));
    }

    public function imagingOrderList(Request $request)
    {
        return response()->json((new _Admission)::imagingOrderList($request));
    }

    public function imagingOrderSelectedDetails(Request $request)
    {
        return response()->json((new _Admission)::imagingOrderSelectedDetails($request));
    }

    public function imagingAddOrderUnsavelist(Request $request)
    {
        return response()->json((new _Admission)::imagingAddOrderUnsavelist($request));
    }

    public function imagingAddOrder(Request $request)
    {
        if (_Validator::checkImagingTestIfExist($request)) {
            return response()->json('test-exist');
        } else {
            $result = (new _Admission)::imagingAddOrder($request);
            if ($result) {
                return response()->json('success');
            }
        }
    }

    public function imagingOrderUnsaveProcess(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }

        $result = (new _Admission)::imagingOrderUnsaveProcess($request);
        if ($result) {return response()->json('success');}
    }

    public function getImagingOrderList(Request $request)
    {
        return response()->json((new _Admission)::getImagingOrderList($request));
    }

    public function getUnsaveLabOrder(Request $request)
    {
        return response()->json((new _Admission)::getUnsaveLabOrder($request));
    }

    public function addLabOrderTounsave(Request $request)
    {
        $result = _Admission::addLabOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function processLabOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Admission::processLabOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function laboratoryPaidOrderByPatient(Request $request)
    {
        return response()->json((new _Admission)::laboratoryPaidOrderByPatient($request));
    }

    public function laboratoryUnpaidOrderByPatient(Request $request)
    {
        $result = _Admission::laboratoryUnpaidOrderByPatient($request);
        return response()->json($result);
    }

    public function laboratoryUnpaidOrderByPatientDetails(Request $request)
    {
        $result = _Admission::laboratoryUnpaidOrderByPatientDetails($request);
        return response()->json($result);
    }

    public function hisadmissionGetPatientListQueue(Request $request)
    {
        return response()->json(_Admission::hisadmissionGetPatientListQueue($request));
    }

    public function hisadmissionUpdatePatientContactTracing(Request $request)
    {
        // if (!_Validator::verifyAccount($request)) {
        //     return response()->json('pass-invalid');
        // }
        if (_Admission::hisadmissionUpdatePatientContactTracing($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function hisAdmissionCreateAppointment(Request $request)
    {
        if ((new _Validator)::checkActiveAppointment($request->patient_id)) {
            return response()->json('has-appointment');
        }
        if (_Validator::verifyAccount($request)) {
            $result = _Admission::hisAdmissionCreateAppointment($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisAdmissionRescheduleAppointment(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Admission::hisAdmissionRescheduleAppointment($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    // method for ishihara
    public function getIshiharaTestList(Request $request)
    {
        return response()->json(_Admission::getIshiharaTestList($request));

    }

    public function newIshiharaOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        if (_Admission::newIshiharaOrder($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function getOrderList(Request $request)
    {
        return response()->json(_Admission::getOrderList($request));

    }

    public static function getPackagesList(Request $request)
    {
        return response()->json(_Admission::getPackagesList($request));
    }

    public function getUnpaidListByPatientId(Request $request)
    {
        return response()->json((new _Admission)::getUnpaidListByPatientId($request));
    }

    public function savePackageOrderTemp(Request $request)
    {
        if (_Admission::savePackageOrderTemp($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function deleteOrder(Request $request)
    {
        if (_Admission::deleteOrder($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function saveOrderProcess(Request $request)
    {
        if (_Admission::saveOrderProcess($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    // 7-2-2021
    public static function getUnpaidOrderList(Request $request)
    {
        return response()->json((new _Admission)::getUnpaidOrderList($request));
    }

    public static function getPaidOrderList(Request $request)
    {
        return response()->json((new _Admission)::getPaidOrderList($request));

    }

    public function getCompanyAccreditedList(Request $request)
    {
        return response()->json((new _Admission)::getCompanyAccreditedList($request));
    }

    //7-13-2021
    public function getPsychologyTestList(Request $request)
    {
        return response()->json(_Admission::getPsychologyTestList($request));
    }

    public function newPsychologyOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        if (_Validator::checkPsychologyTestIfExist($request)) {
            return response()->json([
                "message" => 'test-exist',
            ]);
        }
        if (_Admission::newPsychologyOrder($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function getPsychologyOrderList(Request $request)
    {
        return response()->json(_Admission::getPsychologyOrderList($request));
    }

    public function getUnpaidImagingOrder(Request $request)
    {
        return response()->json(_Admission::getUnpaidImagingOrder($request));
    }

    public function getAllCensus(Request $request)
    {
        return response()->json(_Admission::getAllCensus($request));
    }

    public function getAllCensusFilterByDate(Request $request)
    {
        return response()->json(_Admission::getAllCensusFilterByDate($request));
    }

    public function getUnsavePsycOrder(Request $request)
    {
        return response()->json((new _Admission)::getUnsavePsycOrder($request));
    }

    public function addPsycOrderTounsave(Request $request)
    {
        $result = _Admission::addPsycOrderTounsave($request);
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
        $result = _Admission::processPsychologyOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function psychologyUnpaidOrderByPatient(Request $request)
    {
        $result = _Admission::psychologyUnpaidOrderByPatient($request);
        return response()->json($result);
    }

    public function psychologyPaidOrderByPatient(Request $request)
    {
        return response()->json((new _Admission)::psychologyPaidOrderByPatient($request));
    }

    public function admissionAddNewContactTracing(Request $request)
    {
        $result = _Admission::admissionAddNewContactTracing($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function paidPsychologyOrderDetails(Request $request)
    {
        $result = _Admission::paidPsychologyOrderDetails($request);
        return response()->json($result);
    }

    public function getQueuingList(Request $request)
    {
        $result = _Admission::getQueuingList($request);
        return response()->json($result);
    }

    public function createNewQueueForAdditional(Request $request)
    {
        $result = _Admission::createNewQueueForAdditional($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllCompanyListRegistration(Request $request)
    {
        $result = _Admission::getAllCompanyListRegistration($request);
        return response()->json($result);
    }

    public function getAllPatientListByCompanyId(Request $request)
    {
        $result = _Admission::getAllPatientListByCompanyId($request);
        return response()->json($result);
    }

    public function getOthersTestList(Request $request)
    {
        $result = _Admission::getOthersTestList($request);
        return response()->json($result);
    }

    public function hisadmissionGetPatientContactTracing(Request $request)
    {
        return response()->json(_Admission::hisadmissionGetPatientContactTracing($request));
    }

}
