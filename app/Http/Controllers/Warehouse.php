<?php

namespace App\Http\Controllers;

use App\Models\_Validator;
use App\Models\_Warehouse;
use Illuminate\Http\Request;

class Warehouse extends Controller
{
    public function hisWarehouseHeaderInfo(Request $request)
    {
        return response()->json((new _Warehouse)::hisWarehouseHeaderInfo($request));
    }

    public function newWarehouseProducts(Request $request)
    {
        if (count((new _Warehouse)::checkIfCodeAlreadyExisted($request->product_code))) {
            return response()->json(["message" => 'code-exist']);
        }

        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::newWarehouseProducts($request)) {
            return response()->json(["message" => 'success']);
        }

        return response()->json(["message" => 'db-error']);

    }

    public function getProductListInWarehouse(Request $request)
    {
        return response()->json((new _Warehouse)::getProductListInWarehouse($request));
    }

    public function getProductListUnAvailable(Request $request)
    {
        return response()->json((new _Warehouse)::getProductListUnAvailable($request));
    }

    public function getProducsListForNewInvoice(Request $request)
    {
        return response()->json((new _Warehouse)::getProducsListForNewInvoice($request));
    }

    public function saveProductToTemp(Request $request)
    {
        if ($request->selectedBatchType == "new_batch") {
            if (count((new _Warehouse)::getProducsByBatchNo($request->product_name, $request->product_batch))) {
                return response()->json(["message" => 'batch-exist']);
            }
        }
        if ((new _Warehouse)::saveProductToTemp($request)) {
            return response()->json(["message" => 'success']);
        }

        return response()->json(["message" => 'db-error']);
    }

    public function getProductTempList(Request $request)
    {
        return response()->json((new _Warehouse)::getProductTempList($request));
    }

    public function removeItemFromUnsaveList(Request $request)
    {
        if ((new _Warehouse)::removeItemFromUnsaveList($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function processUnsaveProduct(Request $request)
    {
        if (count((new _Warehouse)::checkInvoiceIfExistedByGivenInvoice($request->invoice_number))) {
            return response()->json(["message" => 'invoice-exist']);
        }
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }
        if ((new _Warehouse)::processUnsaveProduct($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProductInventory(Request $request)
    {
        return response()->json((new _Warehouse)::getProductInventory($request));
    }

    public function getProductDetails(Request $request)
    {
        return response()->json((new _Warehouse)::getProductDetails($request));
    }

    public function getAccountList(Request $request)
    {
        return response()->json((new _Warehouse)::getAccountList($request));
    }

    public function accountSave(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::accountSave($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function removeItem(Request $request)
    {
        if ((new _Warehouse)::removeItem($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProductBatchDetails(Request $request)
    {
        return response()->json((new _Warehouse)::getProductBatchDetails($request));
    }

    public function saveProductDrtoTemp(Request $request)
    {

        if (count((new _Warehouse)::checkProductInTemp($request))) {
            return response()->json(["message" => 'product-exist']);
        }

        if ((new _Warehouse)::saveProductDrtoTemp($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProductToDrList(Request $request)
    {
        return response()->json((new _Warehouse)::getProductToDrList($request));
    }

    public function removeItemFromTempList(Request $request)
    {
        if ((new _Warehouse)::removeItemFromTempList($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function processDrItemsInTemp(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::processDrItemsInTemp($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getDrProducts(Request $request)
    {
        return response()->json((new _Warehouse)::getDrProducts($request));
    }

    public function getDrNumberDetails(Request $request)
    {
        return response()->json((new _Warehouse)::getDrNumberDetails($request));
    }

    public function getMonitoringList(Request $request)
    {
        return response()->json((new _Warehouse)::getMonitoringList($request));
    }

    public function warehouseGetPersonalInfoById(Request $request)
    {
        return response()->json((new _Warehouse)::warehouseGetPersonalInfoById($request));
    }

    public function warehouseUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/warehouse');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _Warehouse::warehouseUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function warehouseUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Warehouse::warehouseUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function warehouseUpdateUsername(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Warehouse::warehouseUpdateUsername($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function warehouseUpdatePassword(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _Warehouse::warehouseUpdatePassword($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    // new routes 7-5-2021 1:14pm

    public static function getForApprovalInvoice(Request $request)
    {
        return response()->json((new _Warehouse)::getForApprovalInvoice($request));
    }

    public static function getForApprovalInvoiceDetails(Request $request)
    {
        return response()->json((new _Warehouse)::getForApprovalInvoiceDetails($request));
    }

    public static function getForApprovalDr(Request $request)
    {
        return response()->json((new _Warehouse)::getForApprovalDr($request));
    }

    public static function getForApprovalDrDetails(Request $request)
    {
        return response()->json((new _Warehouse)::getForApprovalDrDetails($request));
    }

    public static function getDrAccountAgentList(Request $request)
    {
        return response()->json((new _Warehouse)::getDrAccountAgentList($request));
    }

    public static function newDrAccountAgent(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Warehouse::newDrAccountAgent($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getDrAccountProductDelivered(Request $request)
    {
        return response()->json((new _Warehouse)::getDrAccountProductDelivered($request));
    }

    //09-19-2021
    public static function getAgentAccounts(Request $request)
    {
        return response()->json((new _Warehouse)::getAgentAccounts($request));
    }

    public static function getProductMonitoringDetails(Request $request)
    {
        return response()->json((new _Warehouse)::getProductMonitoringDetails($request));
    }

    public static function getDrAccountProductDeliveredByReport(Request $request)
    {
        return response()->json((new _Warehouse)::getDrAccountProductDeliveredByReport($request));
    }

    public static function createNewBrand(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Warehouse::createNewBrand($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getHaptechBrandList(Request $request)
    {
        return response()->json((new _Warehouse)::getHaptechBrandList($request));
    }

    public static function createNewCategory(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Warehouse::createNewCategory($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public static function getHaptechCategoryList(Request $request)
    {
        return response()->json((new _Warehouse)::getHaptechCategoryList($request));
    }

    public function getBrandListForNewInvoice(Request $request)
    {
        return response()->json((new _Warehouse)::getBrandListForNewInvoice($request));
    }

    public function getCategoryListForNewInvoice(Request $request)
    {
        return response()->json((new _Warehouse)::getCategoryListForNewInvoice($request));
    }

    public function getDescListForNewInvoice(Request $request)
    {
        return response()->json((new _Warehouse)::getDescListForNewInvoice($request));
    }

    public function editAccountInfo(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _Warehouse::editAccountInfo($request);
        if ($result) {
            return response()->json([
                "message" => "success",
            ]);
        }

        return response()->json([
            "message" => 'db-error',
        ]);
    }

    public function savePOToTemp(Request $request)
    {
        if ((new _Warehouse)::savePOToTemp($request)) {
            return response()->json(["message" => 'success']);
        }

        return response()->json(["message" => 'db-error']);
    }

    public function getProductToPOList(Request $request)
    {
        return response()->json((new _Warehouse)::getProductToPOList($request));
    }

    public function removeItemFromPOTempList(Request $request)
    {
        if ((new _Warehouse)::removeItemFromPOTempList($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function processPOItemsInTemp(Request $request)
    {
        if (count((new _Warehouse)::checkPOIfExistByGivenPO($request->po_number))) {
            return response()->json(["message" => 'po-exist']);
        }
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::processPOItemsInTemp($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getPONumberDetails(Request $request)
    {
        return response()->json((new _Warehouse)::getPONumberDetails($request));
    }

    public function getProductsByPONumber(Request $request)
    {
        return response()->json((new _Warehouse)::getProductsByPONumber($request));
    }

    public function saveCompletePOToTemp(Request $request)
    {

        if (count((new _Warehouse)::getProducsByProdId($request->product_id))) {
            return response()->json(["message" => 'product-exist']);
        }
        if ((new _Warehouse)::saveCompletePOToTemp($request)) {
            return response()->json(["message" => 'success']);
        }

        return response()->json(["message" => 'db-error']);
    }
    
    public function saveProductInfo(Request $request)
    {
        if($request->product_type == 'new'){
            if (count((new _Warehouse)::checkIfCodeAlreadyExisted($request->product_code))) {
                return response()->json(["message" => 'code-exist']);
            }
        }

        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::saveProductInfo($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function saveConfirmedItem(Request $request)
    {
        if (count((new _Warehouse)::checkInvoiceIfExistedByGivenInvoice($request->invoice_number))) {
            return response()->json(["message" => 'invoice-exist']);
        }
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }
        if ((new _Warehouse)::saveConfirmedItem($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }
    
    public function updateBrandName(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::updateBrandName($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function updateCategoryName(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::updateCategoryName($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function updateProductById(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _Warehouse)::updateProductById($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function updateProductDeactivate(Request $request)
    {
        if ((new _Warehouse)::updateProductDeactivate($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function updateProductActivate(Request $request)
    {
        if ((new _Warehouse)::updateProductActivate($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getLastPONumber(Request $request)
    {
        return response()->json((new _Warehouse)::getLastPONumber($request));
    }
    
    public function updateHaptechRequestToDR(Request $request)
    {
        $result = _Warehouse::updateHaptechRequestToDR($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function confirmHaptechRequestToDR(Request $request)
    {
        $result = _Warehouse::confirmHaptechRequestToDR($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function updateExclusiveToDR(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if (count((new _Warehouse)::checkDRNumberIfExist($request))) {
            return response()->json(["message" => 'dr-exist']);
        }

        if ((new _Warehouse)::updateExclusiveToDR($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getSupplierAllRequest(Request $request)
    {
        return response()->json((new _Warehouse)::getSupplierAllRequest($request));
    }
    
}
