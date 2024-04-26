<?php

namespace App\Http\Controllers;

use App\Models\_Cashier;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Cashier extends Controller
{
    public function hiscashierGetHeaderInfo(Request $request)
    {
        return response()->json((new _Cashier)::hiscashierGetHeaderInfo($request));
    }

    public function hiscashierGetPatientsBillings(Request $request)
    {
        return response()->json((new _Cashier)::hiscashierGetPatientsBillings($request));
    }

    public function hiscashierGetPatientsBillingsDetails(Request $request)
    {
        return response()->json((new _Cashier)::hiscashierGetPatientsBillingsDetails($request));
    }

    public function hiscashierBillingCancel(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        if (_Cashier::hiscashierBillingCancel($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function hiscashierBillingSetAsPaid(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        if (_Cashier::hiscashierBillingSetAsPaid($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function hiscashierGetBillingRecords(Request $request)
    {
        return response()->json(_Cashier::hiscashierGetBillingRecords($request));
    }

    public function hiscashierRefundOrderList(Request $request)
    {
        return response()->json(_Cashier::hiscashierRefundOrderList($request));
    }

    public function hiscashierGetBillingRecordsDetails(Request $request)
    {
        return response()->json(_Cashier::hiscashierGetBillingRecordsDetails($request));
    }

    public function hiscashierRefundOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        if (_Cashier::hiscashierRefundOrder($request)) {
            return response()->json('success');
        }
        return response()->json('db-error');
    }

    public function hiscashierGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Cashier)::hiscashierGetPersonalInfoById($request));
    }

    public function hiscashierUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/cashier');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Cashier::hiscashierUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hiscashierUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Cashier::hiscashierUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hiscashierUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Cashier::hiscashierUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hiscashierUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Cashier::hiscashierUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hiscashierReceiptDetails(Request $request)
    {
        return response()->json(_Cashier::hiscashierReceiptDetails($request));
    }

    public function cashierHeaderInformation(Request $request)
    {
        return response()->json((new _Cashier)::cashierHeaderInformation($request));
    }

    public function refundOrderList(Request $request)
    {
        return response()->json(_Cashier::refundOrderList($request));
    }

    public function getBillingRecordsDetails(Request $request)
    {
        return response()->json(_Cashier::getBillingRecordsDetails($request));
    }

    public function getBillingRecords(Request $request)
    {
        return response()->json(_Cashier::getBillingRecords($request));
    }

    public function hiscashierGetHeaderReceipt(Request $request)
    {
        return response()->json((new _Cashier)::hiscashierGetHeaderReceipt($request));
    }

    public function cashierAddNewAddOns(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Cashier::cashierAddNewAddOns($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getPatientsList(Request $request)
    {
        return response()->json((new _Cashier)::getPatientsList($request));
    }

    public function getPatientInformation(Request $request)
    {
        return response()->json(_Cashier::getPatientInformation($request));
    }

    public function getPackageList(Request $request)
    {
        return response()->json(_Cashier::getPackageList($request));
    }

    public function getUnpaidListByPatientId(Request $request)
    {
        return response()->json(_Cashier::getUnpaidListByPatientId($request));
    }

    public function savePackageOrderTemp(Request $request)
    {
        if (_Cashier::savePackageOrderTemp($request)) {
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
        if (_Cashier::deleteOrder($request)) {
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
        if (_Cashier::saveOrderProcess($request)) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getUnpaidOrderList(Request $request)
    {
        return response()->json((new _Cashier)::getUnpaidOrderList($request));
    }

    public static function getPaidOrderList(Request $request)
    {
        return response()->json((new _Cashier)::getPaidOrderList($request));
    }

    public static function getAllHmoList(Request $request)
    {
        return response()->json((new _Cashier)::getAllHmoList($request));
    }

    public function getAllCashierOnQueue(Request $request)
    {
        return response()->json((new _Cashier)::getAllCashierOnQueue($request));
    }

    public function getAllCashierBillingDetails(Request $request)
    {
        return response()->json((new _Cashier)::getAllCashierBillingDetails($request));
    }

    //8-25-2021
    public function getLabOrderDeptDetails(Request $request)
    {
        $result = _Cashier::getLabOrderDeptDetails($request);
        return response()->json($result);
    }

    public function addLabOrderTounsave(Request $request)
    {
        $result = _Cashier::addLabOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeLabOrderFromUnsave(Request $request)
    {
        $result = _Cashier::removeLabOrderFromUnsave($request);
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
        $result = _Cashier::processLabOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getImagingDetails(Request $request)
    {
        return response()->json(_Cashier::getImagingDetails($request));
    }

    public function imagingOrderList(Request $request)
    {
        return response()->json((new _Cashier)::imagingOrderList($request));
    }

    public function imagingOrderSelectedDetails(Request $request)
    {
        return response()->json((new _Cashier)::imagingOrderSelectedDetails($request));
    }

    public function imagingAddOrderUnsavelist(Request $request)
    {
        return response()->json((new _Cashier)::imagingAddOrderUnsavelist($request));
    }

    public function imagingAddOrder(Request $request)
    {
        if (_Validator::checkImagingTestIfExist($request)) {
            return response()->json('test-exist');
        } else {
            $result = (new _Cashier)::imagingAddOrder($request);
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
        $result = (new _Cashier)::imagingOrderUnsaveProcess($request);
        if ($result) {return response()->json('success');}
    }

    public function getPsycOrderDeptDetails(Request $request)
    {
        $result = _Cashier::getPsycOrderDeptDetails($request);
        return response()->json($result);
    }

    public function getUnsavePsycOrder(Request $request)
    {
        return response()->json((new _Cashier)::getUnsavePsycOrder($request));
    }

    public function addPsycOrderTounsave(Request $request)
    {
        $result = _Cashier::addPsycOrderTounsave($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removePsyOrderFromUnsave(Request $request)
    {
        $result = _Cashier::removePsyOrderFromUnsave($request);
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
        $result = _Cashier::processPsychologyOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllReport(Request $request)
    {
        return response()->json((new _Cashier)::getAllReport($request));
    }

    public function getAllReportByFilter(Request $request)
    {
        return response()->json((new _Cashier)::getAllReportByFilter($request));
    }

    // new methods on bmcdc opening 9-18-2021

    // new route for p.e
    public function getUnsavePEOrder(Request $request)
    {
        $result = _Cashier::getUnsavePEOrder($request);
        return response()->json($result);
    }

    public function processPEOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Cashier::processPEOrder($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function addPEOrderTounsave(Request $request)
    {
        $result = _Cashier::addPEOrderTounsave($request);
        if ((int) $result == 2) {
            return response()->json([
                "message" => 'order-exist',
            ]);
        }
        if ((int) $result == 1) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function getDoctorsList(Request $request)
    {
        return response()->json((new _Cashier)::getDoctorsList($request));
    }

    public function handleNewDoctorsServiceOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Cashier::handleNewDoctorsServiceOrder($request);

        if ($result == 1) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function getUnpaidDoctorServiceOrder(Request $request)
    {
        $result = _Cashier::getUnpaidDoctorServiceOrder($request);
        return response()->json($result);
    }

    public function casherGetAllLocalDoctors(Request $request)
    {
        return response()->json((new _Cashier)::casherGetAllLocalDoctors($request));
    }

    public function casherGetDoctorDetailsById(Request $request)
    {
        return response()->json((new _Cashier)::casherGetDoctorDetailsById($request));
    }

    //new jhomar

    public function getOtherTestList(Request $request)
    {
        return response()->json((new _Cashier)::getOtherTestList($request));
    }

    public function getOtherTestListUnpaid(Request $request)
    {
        return response()->json((new _Cashier)::getOtherTestListUnpaid($request));
    }

    public function saveOtherTestToUnpaid(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Cashier::saveOtherTestToUnpaid($request);

        if ($result == 1) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function removeUnpaidOrderTest(Request $request)
    {
        $result = _Cashier::removeUnpaidOrderTest($request);

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

    public function getDoctorServiceList(Request $request)
    {
        return response()->json((new _Cashier)::getDoctorServiceList($request));
    }

    public function saveDoctorServiceToUnpaid(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        $result = _Cashier::saveDoctorServiceToUnpaid($request);

        if ($result == 1) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json([
                "message" => 'db-error',
            ]);
        }
    }

    public function getAllHMOListNotBaseInCompany(Request $request)
    {
        return response()->json((new _Cashier)::getAllHMOListNotBaseInCompany($request));
    }

    public function createNewHMO(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Cashier::createNewHMO($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function updateExistingHMOInfo(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Cashier::updateExistingHMOInfo($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllDoctorGeneratedRecord(Request $request)
    {
        return response()->json((new _Cashier)::getAllDoctorGeneratedRecord($request));
    }

    public function cashierCreateSalaryRecord(Request $request)
    {
        $result = _Cashier::cashierCreateSalaryRecord($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getLaboratoryList(Request $request)
    {
        return response()->json((new _Cashier)::getLaboratoryList($request));
    }

    public function getAllOrdersByTraceNumberToEdit(Request $request)
    {
        return response()->json((new _Cashier)::getAllOrdersByTraceNumberToEdit($request));
    }

    public function deletePatientTestById(Request $request)
    {
        $result = _Cashier::deletePatientTestById($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getCompositionPackage(Request $request)
    {
        return response()->json((new _Cashier)::getCompositionPackage($request));
    }

    public function updateToSendOutPatientTestById(Request $request)
    {
        $result = _Cashier::updateToSendOutPatientTestById($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getFilterByDateClinicSOA(Request $request)
    {
        return response()->json((new _Cashier)::getFilterByDateClinicSOA($request));
    }

    public function updateToBillOutPatientTestById(Request $request)
    {
        $result = _Cashier::updateToBillOutPatientTestById($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllSOATempList(Request $request)
    {
        return response()->json((new _Cashier)::getAllSOATempList($request));
    }

    public function removeSOATempList(Request $request)
    {
        $result = _Cashier::removeSOATempList($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function addAllToSOAList(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Cashier::addAllToSOAList($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getAllSOAList(Request $request)
    {
        return response()->json((new _Cashier)::getAllSOAList($request));
    }

    public function getSOADetailsById(Request $request)
    {
        return response()->json((new _Cashier)::getSOADetailsById($request));
    }

    public function getAllSalesByFilterDate(Request $request)
    {
        return response()->json((new _Cashier)::getAllSalesByFilterDate($request));
    }

    public function getAllSalesExpenseByFilterDate(Request $request)
    {
        return response()->json((new _Cashier)::getAllSalesExpenseByFilterDate($request));
    }

    public function createSalesExpensesByDate(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json('pass-invalid');
        }
        $result = _Cashier::createSalesExpensesByDate($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function archivePatientTransactionByID(Request $request)
    {
        $result = _Cashier::archivePatientTransactionByID($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function deletePatientQueueById(Request $request)
    {
        $result = _Cashier::deletePatientQueueById($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getBillingRecordsNotGroup(Request $request)
    {
        return response()->json(_Cashier::getBillingRecordsNotGroup($request));
    }

    public static function addBillToAdmittedPatients(Request $request)
    {
        $result = _Cashier::addBillToAdmittedPatients($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getPatientListForDischarge(Request $request)
    {
        return response()->json(_Cashier::getPatientListForDischarge($request));
    }

    public function getAdmittedPatientForDischarge(Request $request)
    {
        return response()->json(_Cashier::getAdmittedPatientForDischarge($request));
    }

    public static function dischargedPatientFromAdmitting(Request $request)
    {
        $result = _Cashier::dischargedPatientFromAdmitting($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function dischargedPatientListGroupByPatientId(Request $request)
    {
        return response()->json(_Cashier::dischargedPatientListGroupByPatientId($request));
    }


    public function getDischargeSlipToPatient(Request $request)
    {
        return response()->json(_Cashier::getDischargeSlipToPatient($request));
    }

    public static function addDischargeSlipToPatient(Request $request)
    {
        $result = _Cashier::addDischargeSlipToPatient($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        } else {
            return response()->json('db-error');
        }
    }

    public function getDischargedSlipPatientInfo(Request $request)
    {
        return response()->json(_Cashier::getDischargedSlipPatientInfo($request));
    }

    public function getPhilhealthRecord(Request $request)
    {
        return response()->json(_Cashier::getPhilhealthRecord($request));
    }
}
