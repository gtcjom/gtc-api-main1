<?php

namespace App\Http\Controllers;

use App\Models\_Triage;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Triage extends Controller
{
    public function histriageGetHeaderInfo(Request $request)
    {
        return response()->json((new _Triage)::histriageGetHeaderInfo($request));
    }

    public function histriageGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Triage)::histriageGetPersonalInfoById($request));
    }

    public function histriageUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/triage');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Triage::histriageUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function histriageUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Triage::histriageUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function histriageUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Triage::histriageUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function histriageUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Triage::histriageUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function histriageNewPatient(Request $request)
    {
        $filename = '';
        // if (!_Validator::verifyAccount($request)) {
        //     return response()->json('pass-invalid');
        // }
        if (!empty($request->email)) {
            if (_Validator::checkEmailInPatient($request->email)) {
                return response()->json('email-exist');
            }
        }
        if (str_contains($request->image, 'data:image/png;base64')) {
            $destinationPath = public_path('../images/patients/');
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $filename = time() . '.jpeg';

            $file = $destinationPath . $filename;
            $success = file_put_contents($file, $data);
        }
        if (_Triage::histriageNewPatient($request, $filename)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function histriageGetIncompleteList(Request $request)
    {
        return response()->json(_Triage::histriageGetIncompleteList($request));
    }

    public function histriageGetPatientInformation(Request $request)
    {
        return response()->json(_Triage::histriageGetPatientInformation($request));
    }

    public function histriageUpdatePatientInfo(Request $request)
    {
        // if (!_Validator::verifyAccount($request)) {
        //     return response()->json('pass-invalid');
        // }
        if (_Triage::histriageUpdatePatientInfo($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function histriageAddNewContactTracing(Request $request)
    {
        $result = _Triage::histriageAddNewContactTracing($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

}
