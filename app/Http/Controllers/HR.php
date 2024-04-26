<?php

namespace App\Http\Controllers;

use App\Models\_HR;
use App\Models\_Validator;
use Illuminate\Http\Request;

class HR extends Controller
{
    public function hisHRHeaderInfo(Request $request)
    {
        return response()->json((new _HR)::hisHRHeaderInfo($request));
    }

    public function hisHRGetPersonalInfoById(Request $request)
    {
        return response()->json((new _HR)::hisHRGetPersonalInfoById($request));
    }

    public function hisHRUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/hr');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _HR::hisHRUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisHRUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HR::hisHRUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisHRUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HR::hisHRUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisHRUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HR::hisHRUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisHRGetAllUsersForSummary(Request $request){
        return response()->json((new _HR)::hisHRGetAllUsersForSummary($request));
    }

    public function hisHRGetPayslipDeductionByPeriod(Request $request){
        return response()->json((new _HR)::hisHRGetPayslipDeductionByPeriod($request));
    }

    public function hisHRGetPayslipBonusByPeriod(Request $request){
        return response()->json((new _HR)::hisHRGetPayslipBonusByPeriod($request));
    }

    public function hisHRGetPayrollHeaderList(Request $request){
        return response()->json((new _HR)::hisHRGetPayrollHeaderList($request));
    }

    public function hisHRGetPayrollHeaderListByBracket(Request $request){
        return response()->json((new _HR)::hisHRGetPayrollHeaderListByBracket($request));
    }
    
    public function hisHRNewPayrollHeader(Request $request){
        $result = _HR::hisHRNewPayrollHeader($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function hisHRAddPayslip(Request $request){
        $result = _HR::hisHRAddPayslip($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    
    public function getEmpPayrollSummary(Request $request){
        return response()->json((new _HR)::getEmpPayrollSummary($request));
    }

    public function hisHRPayrollSendToEmail(Request $request){
        $result = _HR::hisHRPayrollSendToEmail($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllBraches(Request $request){
        return response()->json((new _HR)::getAllBraches($request));
    }

    public function getEditExistingBranch(Request $request){
        $result = _HR::getEditExistingBranch($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function addNewBranch(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HR::addNewBranch($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }
    
    public function getSpecificInfoOfuserForEdit(Request $request){
        return response()->json((new _HR)::getSpecificInfoOfuserForEdit($request));
    }

    public function hisHRUpdateUserInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HR::hisHRUpdateUserInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getSpecificInfoOfuserForEditDoc(Request $request){
        return response()->json((new _HR)::getSpecificInfoOfuserForEditDoc($request));
    }
    
    public function hisHRUpdateUserInfoDoc(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HR::hisHRUpdateUserInfoDoc($request);
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
