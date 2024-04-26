<?php

namespace App\Http\Controllers;

use App\Models\_Billing;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Billing extends Controller
{
    public function hisBillingHeaderInfo(Request $request)
    {
        return response()->json((new _Billing)::hisBillingHeaderInfo($request));
    }

    public function hisBillingGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Billing)::hisBillingGetPersonalInfoById($request));
    }

    public function hisBillingUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/billing');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Billing::hisBillingUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisBillingUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Billing::hisBillingUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisBillingUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Billing::hisBillingUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisBillingUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Billing::hisBillingUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getSoaManagmentPatient(Request $request)
    {
        return response()->json((new _Billing)::getSoaManagmentPatient($request));
    }

    public function getSoaManagmentPatientInfo(Request $request)
    {
        return response()->json((new _Billing)::getSoaManagmentPatientInfo($request));
    }

    public function getSoaManagementPatientTransactions(Request $request)
    {
        return response()->json((new _Billing)::getSoaManagementPatientTransactions($request));
    }

    public function getManagementCompanies(Request $request)
    {
        return response()->json((new _Billing)::getManagementCompanies($request));
    }

    public function getCompaniesTransaction(Request $request)
    {
        return response()->json((new _Billing)::getCompaniesTransaction($request));
    }

    public static function getCompaniesTrasactionByPatients(Request $request)
    {
        return response()->json((new _Billing)::getCompaniesTrasactionByPatients($request));
    }

    public static function editFormInfo(Request $request)
    {
        $result = _Billing::editFormInfo($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public static function getCurrentFormInformation(Request $request)
    {
        return response()->json((new _Billing)::getCurrentFormInformation($request));
    }

    public function getCompaniesHMOTransaction(Request $request)
    {
        return response()->json((new _Billing)::getCompaniesHMOTransaction($request));
    }

    public function getAdmittedPatientForBilling(Request $request)
    {
        return response()->json((new _Billing)::getAdmittedPatientForBilling($request));
    }

    public function getPatientsAdmittingBills(Request $request)
    {
        return response()->json((new _Billing)::getPatientsAdmittingBills($request));
    }

    public static function admittedPatientProcessBillingAddPhilhealth(Request $request)
    {
        $result = _Billing::admittedPatientProcessBillingAddPhilhealth($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public static function admittedPatientSentToCashier(Request $request)
    {
        $result = _Billing::admittedPatientSentToCashier($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function dischargedPatientListGroupByPatientId(Request $request)
    {
        return response()->json((new _Billing)::dischargedPatientListGroupByPatientId($request));
    }

    public function dischargedPatientListByTracenumber(Request $request)
    {
        return response()->json((new _Billing)::dischargedPatientListByTracenumber($request));
    }

    public function dischargedPatientBillRecords(Request $request)
    {
        return response()->json((new _Billing)::dischargedPatientBillRecords($request));
    }

}
