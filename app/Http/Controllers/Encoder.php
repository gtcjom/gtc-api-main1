<?php

namespace App\Http\Controllers;

use App\Models\_Encoder;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Encoder extends Controller
{
    public function hisSecretaryGetHeaderInfo(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetHeaderInfo($request));
    }

    public function hisSecretaryNewPatient(Request $request)
    {
        $filename = '';
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        if (_Validator::checkEmailInPatient($request->email)) {
            return response()->json('email-exist');
        }
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
        if (_Encoder::hisSecretaryNewPatient($request, $filename)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function hisSecretaryGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Encoder)::hisSecretaryGetPersonalInfoById($request));
    }

    public function hisSecretaryUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/secretary');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Encoder::hisSecretaryUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisSecretaryUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryPatientInfo(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryPatientInfo($request));
    }

    public function hisSecretaryGetAppointmentLocal(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetAppointmentLocal($request));
    }

    public function hisSecretaryGetPatientInfo(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetPatientInfo($request));
    }

    public function hisSecretaryUpdatePatientInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryUpdatePatientInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryCreateAppointment(Request $request)
    {
        if ((new _Validator)::checkActiveAppointment($request->patient_id)) {
            return response()->json('has-appointment');
        }
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryCreateAppointment($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryRescheduleAppointment(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryRescheduleAppointment($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryGetPatientsBillings(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetPatientsBillings($request));
    }

    public function hisSecretaryGetPatientsBillingsDetails(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetPatientsBillingsDetails($request));
    }

    public function hisSecretaryBillingCancel(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryBillingCancel($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryBillingSetAsPaid(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryBillingSetAsPaid($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisSecretaryGetReceiptHeader(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetReceiptHeader($request));
    }

    public function hisSecretaryReceiptDetails(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryReceiptDetails($request));
    }

    public function hisSecretaryGetBillingRecords(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetBillingRecords($request));
    }

    public function hisSecretaryRefundOrderList(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryRefundOrderList($request));
    }

    public function hisSecretaryGetBillingRecordsDetails(Request $request)
    {
        return response()->json(_Encoder::hisSecretaryGetBillingRecordsDetails($request));
    }

    public function hisSecretaryRefundOrder(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Encoder::hisSecretaryRefundOrder($request);
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
