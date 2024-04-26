<?php

namespace App\Http\Controllers;

use App\Models\_Accounting;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Accounting extends Controller
{
    public function hisAccountingGetHeaderInfo(Request $request)
    {
        return response()->json((new _Accounting)::hisAccountingGetHeaderInfo($request));
    }

    public function hisAccountingGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Accounting)::hisAccountingGetPersonalInfoById($request));
    }

    public function hisAccountingUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/accounting');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Accounting::hisAccountingUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisAccountingUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::hisAccountingUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisAccountingUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::hisAccountingUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function hisAccountingUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::hisAccountingUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function accountingItemDeliveryTempList(Request $request)
    {
        return response()->json((new _Accounting)::accountingItemDeliveryTempList($request));
    }

    public function accountingItemList(Request $request)
    {
        return response()->json((new _Accounting)::accountingItemList($request));
    }

    public function accountingItemListByBatches(Request $request)
    {
        return response()->json((new _Accounting)::accountingItemListByBatches($request));
    }

    public function accountingItemDeliveryTempProcess(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Accounting::accountingItemDeliveryTempProcess($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function getAccountingItemsInventory(Request $request)
    {
        return response()->json((new _Accounting)::getAccountingItemsInventory($request));
    }

    public function getItemMonitoring(Request $request)
    {
        return response()->json((new _Accounting)::getItemMonitoring($request));
    }

    public static function getLabOrderTemp(Request $request)
    {
        return response()->json((new _Accounting)::getLabOrderTemp($request));
    }

    public static function getAccountingOrderList(Request $request)
    {
        return response()->json((new _Accounting)::getAccountingOrderList($request));
    }

    public static function getAccountingOrderListItems(Request $request)
    {
        return response()->json((new _Accounting)::getAccountingOrderListItems($request));
    }

    public function saveTempOrderNoItem(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Accounting::saveTempOrderNoItem($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function laboratoryRemoveOrder(Request $request)
    {
        $result = _Accounting::laboratoryRemoveOrder($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function laboratoryRemoveItemInOrder(Request $request)
    {
        $result = _Accounting::laboratoryRemoveItemInOrder($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function laboratoryItemRemove(Request $request)
    {
        $result = _Accounting::laboratoryItemRemove($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function laboratoryOrderUsesThisItem(Request $request)
    {
        return response()->json((new _Accounting)::laboratoryOrderUsesThisItem($request));
    }

    public function getOrdersItems(Request $request)
    {
        return response()->json((new _Accounting)::getOrdersItems($request));
    }

    public function saveOrderItem(Request $request)
    {
        $result = _Accounting::saveOrderItem($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function processTempOrderWithItems(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Accounting::processTempOrderWithItems($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getImagingTestPerson(Request $request)
    {
        return response()->json((new _Accounting)::getImagingTestPerson($request));
    }

    public static function getImagingSalesByDate(Request $request)
    {
        return response()->json((new _Accounting)::getImagingSalesByDate($request));
    }

    public static function getLaboratorySalesReport(Request $request)
    {
        return response()->json((new _Accounting)::getLaboratorySalesReport($request));
    }

    public static function getLaboratorySalesReportByDate(Request $request)
    {
        return response()->json((new _Accounting)::getLaboratorySalesReportByDate($request));
    }

    public static function getOrderByDepartment(Request $request)
    {
        return response()->json((new _Accounting)::getOrderByDepartment($request));
    }

    public function addPackage(Request $request)
    {
        $result = _Accounting::addPackage($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function removePackage(Request $request)
    {
        $result = _Accounting::removePackage($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getAllUnsavePackage(Request $request)
    {
        return response()->json((new _Accounting)::getAllUnsavePackage($request));
    }

    public function confirmPackage(Request $request)
    {
        $result = _Accounting::confirmPackage($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getAllConfirmedPackages(Request $request)
    {
        return response()->json((new _Accounting)::getAllConfirmedPackages($request));
    }

    public static function getDetailsPackageById(Request $request)
    {
        return response()->json((new _Accounting)::getDetailsPackageById($request));
    }

    public function newLaboratoryItem(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Accounting::newLaboratoryItem($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'pass-invalid',
        ]);
    }

    public function laboratoryItemDeliveryTemp(Request $request)
    {
        $result = _Accounting::laboratoryItemDeliveryTemp($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'pass-invalid',
        ]);
    }

    public function laboratoryItemDeliveryTempProcess(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Accounting::laboratoryItemDeliveryTempProcess($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function accountingItemDeliveryTempRemove(Request $request)
    {
        $result = _Accounting::accountingItemDeliveryTempRemove($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    // route 7-5-2021 4:34 0000

    public static function getForApprovalInvoice(Request $request)
    {
        return response()->json((new _Accounting)::getForApprovalInvoice($request));
    }

    public static function getForApprovalInvoiceDetails(Request $request)
    {
        return response()->json((new _Accounting)::getForApprovalInvoiceDetails($request));
    }

    public static function getForApprovalDr(Request $request)
    {
        return response()->json((new _Accounting)::getForApprovalDr($request));
    }

    public static function getForApprovalDrDetails(Request $request)
    {
        return response()->json((new _Accounting)::getForApprovalDrDetails($request));
    }

    public static function drApprovedByAccounting(Request $request)
    {
        $result = _Accounting::drApprovedByAccounting($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function invoiceApprovedByAccounting(Request $request)
    {

        $result = _Accounting::invoiceApprovedByAccounting($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);

    }

    public function newWarehouseProducts(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Accounting)::newWarehouseProducts($request)) {
            return response()->json(["message" => 'success']);
        }

        return response()->json(["message" => 'db-error']);

    }

    public function getProductListInWarehouse(Request $request)
    {
        return response()->json((new _Accounting)::getProductListInWarehouse($request));
    }

    public function getMonitoringList(Request $request)
    {
        return response()->json((new _Accounting)::getMonitoringList($request));
    }

    public function getCompanyAccreditedList(Request $request)
    {
        return response()->json((new _Accounting)::getCompanyAccreditedList($request));
    }

    public static function saveCompanyAccredited(Request $request)
    {

        $result = _Accounting::saveCompanyAccredited($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);

    }

    public static function removeCompanyAccredited(Request $request)
    {

        $result = _Accounting::removeCompanyAccredited($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);

    }

    public static function editCompanyAccredited(Request $request)
    {

        $result = _Accounting::editCompanyAccredited($request);

        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);

    }

    public function addNewBankAccount(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::addNewBankAccount($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getBankAccountList(Request $request)
    {
        return response()->json((new _Accounting)::getBankAccountList($request));
    }

    public function getContactList(Request $request)
    {
        return response()->json((new _Accounting)::getContactList($request));
    }

    public function editContactInfo(Request $request)
    {
        $result = _Accounting::editContactInfo($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function editBankInfo(Request $request)
    {
        $result = _Accounting::editBankInfo($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeBankAccount(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::removeBankAccount($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getBankDetailsById(Request $request)
    {
        return response()->json((new _Accounting)::getBankDetailsById($request));
    }

    public function addNewDeposit(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::addNewDeposit($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function addNewWithdrawal(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::addNewWithdrawal($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function addNewExpense(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::addNewExpense($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getDepositList(Request $request)
    {
        return response()->json((new _Accounting)::getDepositList($request));
    }

    public function getWithdrawalList(Request $request)
    {
        return response()->json((new _Accounting)::getWithdrawalList($request));
    }

    public function getExpenseList(Request $request)
    {
        return response()->json((new _Accounting)::getExpenseList($request));
    }

    public function getFilterSearch(Request $request)
    {
        return response()->json((new _Accounting)::getFilterSearch($request));
    }

    public function getReceivableList(Request $request)
    {
        return response()->json((new _Accounting)::getReceivableList($request));
    }

    public function getProductInventory(Request $request)
    {
        return response()->json((new _Accounting)::getProductInventory($request));
    }

    // branches stockroom inventory
    public function getBranchStockroomInventory(Request $request)
    {
        return response()->json((new _Accounting)::getBranchStockroomInventory($request));
    }

    public function getBranchLaboratoryInventory(Request $request)
    {
        return response()->json((new _Accounting)::getBranchLaboratoryInventory($request));
    }

    public function getBranchSales(Request $request)
    {
        return response()->json((new _Accounting)::getBranchSales($request));
    }

    public function getPayableList(Request $request)
    {
        return response()->json((new _Accounting)::getPayableList($request));
    }

    public function getTotalResources(Request $request)
    {
        return response()->json((new _Accounting)::getTotalResources($request));
    }

    public function setAsPaidByCompany(Request $request)
    {
        $result = _Accounting::setAsPaidByCompany($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function payableInvoicePayment(Request $request)
    {
        $result = _Accounting::payableInvoicePayment($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function getCollectionList(Request $request)
    {
        return response()->json((new _Accounting)::getCollectionList($request));
    }

    public function getAllBraches(Request $request)
    {
        return response()->json((new _Accounting)::getAllBraches($request));
    }

    public function getSalesGrandTotalAmount(Request $request)
    {
        return response()->json((new _Accounting)::getSalesGrandTotalAmount($request));
    }

    public function getInventoryGrandTotalAmountLaboratory(Request $request)
    {
        return response()->json((new _Accounting)::getInventoryGrandTotalAmountLaboratory($request));
    }

    public function getInventoryGrandTotalAmountStockroom(Request $request)
    {
        return response()->json((new _Accounting)::getInventoryGrandTotalAmountStockroom($request));
    }

    public function getPayableGrandTotalAmount(Request $request)
    {
        return response()->json((new _Accounting)::getPayableGrandTotalAmount($request));
    }

    public function getRecievableGrandTotalAmount(Request $request)
    {
        return response()->json((new _Accounting)::getRecievableGrandTotalAmount($request));
    }

    public function getExpensesGrandTotalAmount(Request $request)
    {
        return response()->json((new _Accounting)::getExpensesGrandTotalAmount($request));
    }

    public function getBankGrandTotalAmount(Request $request)
    {
        return response()->json((new _Accounting)::getBankGrandTotalAmount($request));
    }

    public function getCollectionGrandTotalAmount(Request $request)
    {
        return response()->json((new _Accounting)::getCollectionGrandTotalAmount($request));
    }

    public function getBranchLaboratoryInventoryTotal(Request $request)
    {
        return response()->json((new _Accounting)::getBranchLaboratoryInventoryTotal($request));
    }

    public function getBranchStockroomInventoryTotal(Request $request)
    {
        return response()->json((new _Accounting)::getBranchStockroomInventoryTotal($request));
    }

    public function getBranchSalesTotal(Request $request)
    {
        return response()->json((new _Accounting)::getBranchSalesTotal($request));
    }

    public function getBranchReceivableTotal(Request $request)
    {
        return response()->json((new _Accounting)::getBranchReceivableTotal($request));
    }

    public function getBranchCollectionTotal(Request $request)
    {
        return response()->json((new _Accounting)::getBranchCollectionTotal($request));
    }

    public function getBranchExpenseTotal(Request $request)
    {
        return response()->json((new _Accounting)::getBranchExpenseTotal($request));
    }

    public function getTempSaveExpense(Request $request)
    {
        return response()->json((new _Accounting)::getTempSaveExpense($request));
    }

    public function addNewTempExpense(Request $request)
    {
        $result = _Accounting::addNewTempExpense($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function removeTempExpense(Request $request)
    {
        $result = _Accounting::removeTempExpense($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "db-error",
            ]);
        }
    }

    public function saveConfirmExpense(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::saveConfirmExpense($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getExpenseAllNoGroup(Request $request)
    {
        return response()->json((new _Accounting)::getExpenseAllNoGroup($request));
    }

    public function getExpenseDetailsById(Request $request)
    {
        return response()->json((new _Accounting)::getExpenseDetailsById($request));
    }

    public function getCurrentFormInformationExpense(Request $request)
    {
        return response()->json((new _Accounting)::getCurrentFormInformationExpense($request));
    }

    public function editExpensePrintInfo(Request $request)
    {
        $result = _Accounting::editExpensePrintInfo($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function saveLeaveApplication(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::saveLeaveApplication($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getForLeaveApproval(Request $request)
    {
        return response()->json((new _Accounting)::getForLeaveApproval($request));
    }

    public function saveLeaveDecision(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::saveLeaveDecision($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function saveTempPsychologyOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        if (_Validator::checkPsychologyTestIfExistByMgmt($request)) {
            return response()->json([
                "message" => 'test-exist',
            ]);
        }
        $result = _Accounting::saveTempPsychologyOrder($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function getAllPsychology(Request $request)
    {
        return response()->json((new _Accounting)::getAllPsychology($request));
    }

    public function getDoctorServiceByDocId(Request $request)
    {
        return response()->json((new _Accounting)::getDoctorServiceByDocId($request));
    }

    public function getLaboratoryTestByOrderId(Request $request)
    {
        return response()->json((new _Accounting)::getLaboratoryTestByOrderId($request));
    }

    public function saveMedicalOrder(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
        if (_Validator::checkPhysicalExamTestIfExistByMgmt($request)) {
            return response()->json([
                "message" => 'test-exist',
            ]);
        }
        $result = _Accounting::saveMedicalOrder($request);
        if ($result) {
            return response()->json([
                "message" => 'success',
            ]);
        }
        return response()->json([
            "message" => 'db-error',
        ]);
    }

    //09-19-2021
    public function getMedicalOrder(Request $request)
    {
        return response()->json((new _Accounting)::getMedicalOrder($request));
    }

    public function getHaptechProducts(Request $request)
    {
        return response()->json((new _Accounting)::getHaptechProducts($request));
    }

    public function getHaptechProductDescriptions(Request $request)
    {
        return response()->json((new _Accounting)::getHaptechProductDescriptions($request));
    }

    public function getLabItemProducts(Request $request)
    {
        return response()->json((new _Accounting)::getLabItemProducts($request));
    }

    public function getLabItemProductDescriptions(Request $request)
    {
        return response()->json((new _Accounting)::getLabItemProductDescriptions($request));
    }

    public function newAdditionalOrder(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::newAdditionalOrder($request);
            if ($result) {
                return response()->json([
                    "message" => 'success',
                ]);
            } else {
                return response()->json([
                    "message" => 'db-error',
                ]);
            }
        } else {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
    }
    public function getAdditionalOrder(Request $request)
    {
        return response()->json((new _Accounting)::getAdditionalOrder($request));
    }

    public static function getOtherTestPerson(Request $request)
    {
        return response()->json((new _Accounting)::getOtherTestPerson($request));
    }

    public static function getOtherSalesByDate(Request $request)
    {
        return response()->json((new _Accounting)::getOtherSalesByDate($request));
    }

    public static function getDoctorServices(Request $request)
    {
        return response()->json((new _Accounting)::getDoctorServices($request));
    }

    public function createNewServiceSave(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::createNewServiceSave($request);
            if ($result) {
                return response()->json([
                    "message" => 'success',
                ]);
            } else {
                return response()->json([
                    "message" => 'db-error',
                ]);
            }
        } else {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
    }

    public function updateNewServiceSave(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::updateNewServiceSave($request);
            if ($result) {
                return response()->json([
                    "message" => 'success',
                ]);
            } else {
                return response()->json([
                    "message" => 'db-error',
                ]);
            }
        } else {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }
    }

    public function getPsychologyOrderList(Request $request)
    {
        return response()->json((new _Accounting)::getPsychologyOrderList($request));
    }

    public static function getDoctorTestPerson(Request $request)
    {
        return response()->json((new _Accounting)::getDoctorTestPerson($request));
    }

    public static function getDoctorSalesByDate(Request $request)
    {
        return response()->json((new _Accounting)::getDoctorSalesByDate($request));
    }

    public static function getPsychologyTestPerson(Request $request)
    {
        return response()->json((new _Accounting)::getPsychologyTestPerson($request));
    }

    public static function getPsychologySalesByDate(Request $request)
    {
        return response()->json((new _Accounting)::getPsychologySalesByDate($request));
    }

    public static function getPackageTestPerson(Request $request)
    {
        return response()->json((new _Accounting)::getPackageTestPerson($request));
    }

    public static function getPackageSalesByDate(Request $request)
    {
        return response()->json((new _Accounting)::getPackageSalesByDate($request));
    }

    public static function accountingGetDoctorList(Request $request)
    {
        return response()->json((new _Accounting)::accountingGetDoctorList($request));
    }

    public function accountingUpdateDoctorShare(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Accounting::accountingUpdateDoctorShare($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function getUpdateLaboratoryRates(Request $request)
    {
        $result = _Accounting::getUpdateLaboratoryRates($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function addOrderToPackage(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => "pass-invalid",
            ]);
        }

        $result = _Accounting::addOrderToPackage($request);
        if ($result) {
            return response()->json([
                'message' => "success",
            ]);
        } else {
            return response()->json([
                'message' => "db-error",
            ]);
        }
    }

    public function setAsPaidByPatient(Request $request)
    {
        $result = _Accounting::setAsPaidByPatient($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public static function getItemRequestConfirmById(Request $request)
    {
        return response()->json((new _Accounting)::getItemRequestConfirmById($request));
    }

    public function saveProductDrtoTemp(Request $request)
    {

        if (count((new _Accounting)::checkProductInTemp($request))) {
            return response()->json(["message" => 'product-exist']);
        }

        if ((new _Accounting)::saveProductDrtoTemp($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProductToDrList(Request $request)
    {
        return response()->json((new _Accounting)::getProductToDrList($request));
    }

    public function removeItemFromTempList(Request $request)
    {
        if ((new _Accounting)::removeItemFromTempList($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProducsListForNewInvoice(Request $request)
    {
        return response()->json((new _Accounting)::getProducsListForNewInvoice($request));
    }

    public function getProductDetails(Request $request)
    {
        return response()->json((new _Accounting)::getProductDetails($request));
    }

    public function getDescListForNewInvoice(Request $request)
    {
        return response()->json((new _Accounting)::getDescListForNewInvoice($request));
    }

    public function getProductBatchDetails(Request $request)
    {
        return response()->json((new _Accounting)::getProductBatchDetails($request));
    }

    public function getBrandListForNewInvoice(Request $request)
    {
        return response()->json((new _Accounting)::getBrandListForNewInvoice($request));
    }

    public function confirmRequestToHaptech(Request $request)
    {
        $result = _Accounting::confirmRequestToHaptech($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function restoreDisapprovedRequest(Request $request)
    {
        if ((new _Accounting)::restoreDisapprovedRequest($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function updateAccountingPackage(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => "pass-invalid",
            ]);
        }

        $result = _Accounting::updateAccountingPackage($request);
        if ($result) {
            return response()->json([
                'message' => "success",
            ]);
        } else {
            return response()->json([
                'message' => "db-error",
            ]);
        }
    }

    public function updateAccountingOtherRate(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json("pass-invalid");
        }
        $result = _Accounting::updateAccountingOtherRate($request);
        if ($result) {
            return response()->json("success");
        } else {
            return response()->json("db-error");
        }
    }

    public function updateAccountingPsychologyRate(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json("pass-invalid");
        }
        $result = _Accounting::updateAccountingPsychologyRate($request);
        if ($result) {
            return response()->json("success");
        } else {
            return response()->json("db-error");
        }
    }

    public function getAllSOAListByMgmtID(Request $request)
    {
        return response()->json((new _Accounting)::getAllSOAListByMgmtID($request));
    }

    public function getHMOListByMainMgmt(Request $request)
    {
        return response()->json((new _Accounting)::getHMOListByMainMgmt($request));
    }

    public function createNewHMOAccounting(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json("pass-invalid");
        }
        $result = _Accounting::createNewHMOAccounting($request);
        if ($result) {
            return response()->json("success");
        } else {
            return response()->json("db-error");
        }
    }

    public function updateSpecificHMOByMain(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json("pass-invalid");
        }
        $result = _Accounting::updateSpecificHMOByMain($request);
        if ($result) {
            return response()->json("success");
        } else {
            return response()->json("db-error");
        }
    }

    public function getHMOAccreditedList(Request $request)
    {
        return response()->json((new _Accounting)::getHMOAccreditedList($request));
    }

    public function updateHMOStatus(Request $request)
    {
        $result = _Accounting::updateHMOStatus($request);
        if ($result) {
            return response()->json("success");
        } else {
            return response()->json("db-error");
        }
    }

    public function createNewHMOByCompanyId(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json("pass-invalid");
        }
        $result = _Accounting::createNewHMOByCompanyId($request);
        if ($result) {
            return response()->json("success");
        } else {
            return response()->json("db-error");
        }
    }

    public function getRoomLists(Request $request)
    {
        return response()->json((new _Accounting)::getRoomLists($request));
    }

    public function newRoom(Request $request)
    {
        $result = _Accounting::newRoom($request);
        if ($result) {
            return response()->json(["message" => "success"]);
        } else {
            return response()->json(["message" => "db-error"]);
        }
    }

    public function getRoomListByRoomId(Request $request)
    {
        return response()->json((new _Accounting)::getRoomListByRoomId($request));
    }

    public function getListOfBedsByRoom(Request $request)
    {
        return response()->json((new _Accounting)::getListOfBedsByRoom($request));
    }

    public function newBedInRoom(Request $request)
    {
        $result = _Accounting::newBedInRoom($request);
        if ($result) {
            return response()->json(["message" => "success"]);
        } else {
            return response()->json(["message" => "db-error"]);
        }
    }

    public function addRoomToList(Request $request)
    {
        $result = _Accounting::addRoomToList($request);
        if ($result) {
            return response()->json(["message" => "success"]);
        } else {
            return response()->json(["message" => "db-error"]);
        }
    } 
}
