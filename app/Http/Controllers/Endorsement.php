<?php

namespace App\Http\Controllers;

use App\Models\_Endorsement;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Endorsement extends Controller
{
    public function getInformation(Request $request)
    {
        return response()->json((new _Endorsement)::getInformation($request));
    }

    public function updateProfileImage(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/endorsement');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Endorsement::updateProfileImage($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function updateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Endorsement::updateUsername($request);
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
            $result = _Endorsement::updatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getQueueList(Request $request)
    {
        return response()->json((new _Endorsement)::getQueueList($request));
    }

    public function getLaboratoryOrder(Request $request)
    {
        return response()->json((new _Endorsement)::getLaboratoryOrder($request));
    }

    public function getLaboratoryOrderUnsave(Request $request)
    {
        return response()->json((new _Endorsement)::getLaboratoryOrderUnsave($request));
    }

    public function processLabOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }

        $result = _Endorsement::processLabOrder($request);

        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function paidLabOrderDetails(Request $request)
    {
        $result = _Endorsement::paidLabOrderDetails($request);
        return response()->json($result);
    }

    public function getImagingDetails(Request $request)
    {
        $result = _Endorsement::getImagingDetails($request);
        return response()->json($result);
    }

    public function imagingOrderList(Request $request)
    {
        $result = _Endorsement::imagingOrderList($request);
        return response()->json($result);
    }

    public function getImagingOrderList(Request $request)
    {
        $result = _Endorsement::getImagingOrderList($request);
        return response()->json($result);
    }

    public function getPackagesList(Request $request)
    {
        $result = _Endorsement::getPackagesList($request);
        return response()->json($result);
    }

    public function getUnpaidListByPatientId(Request $request)
    {
        $result = _Endorsement::getUnpaidListByPatientId($request);
        return response()->json($result);
    }

    public function savePackageOrderTemp(Request $request)
    {
        if (_Endorsement::savePackageOrderTemp($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function setAsDone(Request $request)
    {
        if (_Endorsement::setAsDone($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function getCompany(Request $request)
    {
        $result = _Endorsement::getCompany($request);
        return response()->json($result);
    }

    public function getCompanyHMO(Request $request)
    {
        $result = _Endorsement::getCompanyHMO($request);
        return response()->json($result);
    }

    public function updatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Endorsement::updatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    function endorsementGetPersonalInfoById(Request $request){
        return response()->json((new _Endorsement)::endorsementGetPersonalInfoById($request));
    }

    public function processPEOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Endorsement::processPEOrder($request);
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

    public function PEUnpaidOrderByPatient(Request $request)
    {
        $result = _Endorsement::PEUnpaidOrderByPatient($request);
        return response()->json($result);
    }

    public function PEUnpaidOrderByPatientDetails(Request $request)
    {
        $result = _Endorsement::PEUnpaidOrderByPatientDetails($request);
        return response()->json($result);
    }

    public function getDoctorsServices(Request $request)
    {
        $result = _Endorsement::getDoctorsServices($request);
        return response()->json($result);
    }

}
