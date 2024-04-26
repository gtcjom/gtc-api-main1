<?php

namespace App\Http\Controllers;

use App\Models\_Nurse;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Nurse extends Controller
{
    public function hisNurseGetHeaderInfo(Request $request)
    {
        return response()->json((new _Nurse)::hisNurseGetHeaderInfo($request));
    }

    public function hisNurseGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Nurse)::hisNurseGetPersonalInfoById($request));
    }

    public function hisNurseUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/nurse');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Nurse::hisNurseUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisNurseUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Nurse::hisNurseUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisNurseUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Nurse::hisNurseUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisNurseUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Nurse::hisNurseUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getAllNurseOnQueue(Request $request)
    {
        return response()->json((new _Nurse)::getAllNurseOnQueue($request));
    }

    public function nurseGetPatientInformation(Request $request)
    {
        return response()->json(_Nurse::nurseGetPatientInformation($request));
    }

    public function nurseUpdatePatientInfo(Request $request)
    {
        if (_Nurse::nurseUpdatePatientInfo($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function nurseGetAllDoctors(Request $request)
    {
        return response()->json(_Nurse::nurseGetAllDoctors($request));
    }

    public function nurseCreateAppointment(Request $request)
    {
        if ((new _Validator)::checkActiveAppointment($request->patient_id)) {
            return response()->json('has-appointment');
        }
        if (_Validator::verifyAccount($request)) {
            $result = _Nurse::nurseCreateAppointment($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function nurseRescheduleAppointment(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Nurse::nurseRescheduleAppointment($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function nurseNewQueueToCashier(Request $request)
    {
        $result = _Nurse::nurseNewQueueToCashier($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getNotes(Request $request)
    {
        $result = _Nurse::getNotes($request);
        return response()->json($result);
    }

    public function getTreatmentPlan(Request $request)
    {
        return (new _Nurse)::getTreatmentPlan($request);
    }

    public function hisNurseUploadPatientProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/patients/');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Nurse::hisNurseUploadPatientProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function getCompletedMedCert(Request $request)
    {
        return response()->json(_Nurse::getCompletedMedCert($request));
    }

    public function getDoctorsInfo(Request $request)
    {
        return response()->json(_Nurse::getDoctorsInfo($request));
    }

    public function getPatientInformation(Request $request)
    {
        return response()->json(_Nurse::getPatientInformation($request));
    }

    public function updatePatientProfPic(Request $request)
    {
        $filename = '';
        if (str_contains($request->image, 'data:image/png;base64')) {
            $destinationPath = public_path('../images/patients/'); // set folder where to save
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $filename = time() . '.jpeg';

            $file = $destinationPath . $filename;
            $success = file_put_contents($file, $data);
        }
        if (_Nurse::updatePatientProfPic($request, $filename)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function getCompletedMedCertById(Request $request)
    {
        return response()->json(_Nurse::getCompletedMedCertById($request));
    }

    public function getAllHistoryIllnessList(Request $request)
    {
        return response()->json((new _Nurse)::getAllHistoryIllnessList($request));
    }

    public function createAllHistoryIllnessList(Request $request)
    {
        if ((new _Nurse)::createAllHistoryIllnessList($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function updateAllHistoryIllnessList(Request $request)
    {
        if ((new _Nurse)::updateAllHistoryIllnessList($request)) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllAdmittedPatient(Request $request)
    {
        return (new _Nurse)::getAllAdmittedPatient($request);
    }

    public function sentPatientToDischarge(Request $request)
    {
        if ((new _Nurse)::sentPatientToDischarge($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public static function sentPatientToBillout(Request $request)
    {
        if ((new _Nurse)::sentPatientToBillout($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getPatientListForOperation(Request $request)
    {
        return (new _Nurse)::getPatientListForOperation($request);
    }

    public static function setOrPatientToPacuNurse(Request $request)
    {
        if ((new _Nurse)::setOrPatientToPacuNurse($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public static function setPatientForMonitoring(Request $request)
    {
        if ((new _Nurse)::setPatientForMonitoring($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getAdmittedPatientDetails(Request $request)
    {
        return (new _Nurse)::getAdmittedPatientDetails($request);
    }

}
