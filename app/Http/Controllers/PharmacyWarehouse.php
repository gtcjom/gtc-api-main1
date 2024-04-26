<?php

namespace App\Http\Controllers;

use App\_PharmacyWarehouse;
use App\_Validator;
use Illuminate\Http\Request;

class PharmacyWarehouse extends Controller
{
    public function newWarehouseProducts(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _PharmacyWarehouse)::newWarehouseProducts($request)) {
            return response()->json(["message" => 'success']);
        }

        return response()->json(["message" => 'db-error']);

    }

    public function getProductListInWarehouse(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProductListInWarehouse($request));
    }

    public function getProducsListForNewInvoice(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProducsListForNewInvoice($request));
    }

    public function saveProductToTemp(Request $request)
    {
        if ($request->selectedBatchType == "new_batch") {
            if (count((new _PharmacyWarehouse)::getProducsByBatchNo($request->product_name, $request->product_batch))) {
                return response()->json(["message" => 'batch-exist']);
            }
        }
        if ((new _PharmacyWarehouse)::saveProductToTemp($request)) {
            return response()->json(["message" => 'success']);
        }

        return response()->json(["message" => 'db-error']);
    }

    public function getProductTempList(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProductTempList($request));
    }

    public function removeItemFromUnsaveList(Request $request)
    {
        if ((new _PharmacyWarehouse)::removeItemFromUnsaveList($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function processUnsaveProduct(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _PharmacyWarehouse)::processUnsaveProduct($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProductInventory(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProductInventory($request));
    }

    public function getProductDetails(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProductDetails($request));
    }

    public function getAccountList(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getAccountList($request));
    }

    public function accountSave(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _PharmacyWarehouse)::accountSave($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function removeItem(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _PharmacyWarehouse)::removeItem($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProductBatchDetails(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProductBatchDetails($request));
    }

    public function saveProductDrtoTemp(Request $request)
    {

        if (count((new _PharmacyWarehouse)::checkProductInTemp($request))) {
            return response()->json(["message" => 'product-exist']);
        }

        if ((new _PharmacyWarehouse)::saveProductDrtoTemp($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getProductToDrList(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProductToDrList($request));
    }

    public function removeItemFromTempList(Request $request)
    {
        if ((new _PharmacyWarehouse)::removeItemFromTempList($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function processDrItemsInTemp(Request $request)
    {

        if (!_Validator::verifyAccount($request)) {
            return response()->json(["message" => 'pass-invalid']);
        }

        if ((new _PharmacyWarehouse)::processDrItemsInTemp($request)) {
            return response()->json(["message" => 'success']);
        }
        return response()->json(["message" => 'db-error']);
    }

    public function getDrProducts(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getDrProducts($request));
    }

    public function getDrNumberDetails(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getDrNumberDetails($request));
    }

    public function getProductInventoryBatches(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getProductInventoryBatches($request));
    }

    public function getDrProductsByAccount(Request $request)
    {
        return response()->json((new _PharmacyWarehouse)::getDrProductsByAccount($request));
    }
}
