<?php

namespace App\Http\Controllers;

use App\Models\_HMIS;
use App\Models\_Validator;
use Illuminate\Http\Request;

class HMIS extends Controller
{
    public function hmisGetHeaderInfo(Request $request)
    {
        return response()->json((new _HMIS)::hmisGetHeaderInfo($request));
    }

    public function hmisGetAllIncome(Request $request)
    {
        return response()->json((new _HMIS)::hmisGetAllIncome($request));
    }

    public function hmisGetAllLabTest(Request $request)
    {
        return response()->json((new _HMIS)::hmisGetAllLabTest($request));
    }

    public function himsSaveNewTest(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            if ((new _HMIS)::himsSaveNewTest($request)) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function himsSalesResultLab(Request $request)
    {
        return response()->json((new _HMIS)::himsSalesResultLab($request));
    }

    public function himsPendingPatientsLab(Request $request)
    {
        return response()->json((new _HMIS)::himsPendingPatientsLab($request));
    }

    public function himsSalesResultImg(Request $request)
    {
        return response()->json((new _HMIS)::himsSalesResultImg($request));
    }

    public function himsPendingPatientsImg(Request $request)
    {
        return response()->json((new _HMIS)::himsPendingPatientsImg($request));
    }

    public function himsGetAllORByBillFrom(Request $request)
    {
        return response()->json((new _HMIS)::himsGetAllORByBillFrom($request));
    }

    public function himsGetReceiptInfoPrint(Request $request)
    {
        return response()->json((new _HMIS)::himsGetReceiptInfoPrint($request));
    }

    public function himsGetAllActive(Request $request)
    {
        return response()->json((new _HMIS)::himsGetAllActive($request));
    }

    public function himsGetAllAccountList(Request $request)
    {
        return response()->json((new _HMIS)::himsGetAllAccountList($request));
    }

    public function himsGetAllAccountActive(Request $request)
    {
        return response()->json((new _HMIS)::himsGetAllAccountActive($request));
    }

    public function hismGetPersonalInfoById(Request $request)
    {
        return response()->json((new _HMIS)::hismGetPersonalInfoById($request));
    }

    public function himsUploadProfile(Request $request)
    {
        $himsprofile = $request->file('profile');
        $destinationPath = public_path('../images/hmis');
        $filename = time() . '.' . $himsprofile->getClientOriginalExtension();
        $result = _HMIS::himsUploadProfile($request, $filename);
        if ($result) {
            $himsprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function himsUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::himsUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function himsUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::himsUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function himsUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::himsUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function himsGetAllUsersAccount(Request $request)
    {
        return response()->json((new _HMIS)::himsGetAllUsersAccount($request));
    }

    public function himsAddNewDepartmentAccount(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::himsAddNewDepartmentAccount($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function himsUpdateAccountToInactive(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::himsUpdateAccountToInactive($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function himsGetIncomeReportByYear(Request $request)
    {
        return response()->json((new _HMIS)::himsGetIncomeReportByYear($request));
    }

    public function himsGetAllEmployee(Request $request)
    {
        return response()->json((new _HMIS)::himsGetAllEmployee($request));
    }

    public function himsGetAllEmployeeWithDate(Request $request)
    {
        return response()->json((new _HMIS)::himsGetAllEmployeeWithDate($request));
    }

    public function hmisGetEmployeeInfoPayroll(Request $request)
    {
        return response()->json((new _HMIS)::hmisGetEmployeeInfoPayroll($request));
    }

    public function hmisGetPayrollReportByDate(Request $request)
    {
        return response()->json((new _HMIS)::hmisGetPayrollReportByDate($request));
    }



    //7-05-2021
    public function laboratorySalesFilterByDate(Request $request)
    {
        return response()->json((new _HMIS)::laboratorySalesFilterByDate($request));
    }

    public function imagingSalesFilterByDate(Request $request)
    {
        return response()->json((new _HMIS)::imagingSalesFilterByDate($request));
    }

    public function himsUpdateAccountRateClass(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::himsUpdateAccountRateClass($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getGeneralManagementBranches(Request $request)
    {
        return response()->json((new _HMIS)::getGeneralManagementBranches($request));
    }

    public function getForLeaveApproval(Request $request)
    {
        return response()->json((new _HMIS)::getForLeaveApproval($request));
    }
    
    public function saveLeaveDecision(Request $request){
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::saveLeaveDecision($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getHIMSDoctorList(Request $request)
    {
        return response()->json((new _HMIS)::getHIMSDoctorList($request));
    }

    public function getHIMSServicesByDocId(Request $request)
    {
        return response()->json((new _HMIS)::getHIMSServicesByDocId($request));
    }
    
    public function getHIMSDoctorSales(Request $request)
    {
        return response()->json((new _HMIS)::getHIMSDoctorSales($request));
    }

    public function doctorSalesFilterByDate(Request $request)
    {
        return response()->json((new _HMIS)::doctorSalesFilterByDate($request));
    }

    public function getHIMSPsychologySales(Request $request)
    {
        return response()->json((new _HMIS)::getHIMSPsychologySales($request));
    }

    public function psychologySalesFilterByDate(Request $request)
    {
        return response()->json((new _HMIS)::psychologySalesFilterByDate($request));
    }

    public function haptechAddNewDoctorAccount(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _HMIS::haptechAddNewDoctorAccount($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getForItemApprovalByID(Request $request)
    {
        return response()->json((new _HMIS)::getForItemApprovalByID($request));
    }

    public function updateItemApprovalByID(Request $request)
    {
        $result = _HMIS::updateItemApprovalByID($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }
    
}
