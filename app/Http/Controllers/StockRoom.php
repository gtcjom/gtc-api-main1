<?php

namespace App\Http\Controllers;

use App\Models\_StockRoom;
use App\Models\_Validator;
use Illuminate\Http\Request;

class StockRoom extends Controller
{

    public function hmisGetHeaderInfo(Request $request)
    {
        return response()->json((new _StockRoom)::hmisGetHeaderInfo($request));
    }

    public function hisAccountingGetPersonalInfoById(Request $request)
    {
        return response()->json((new _StockRoom)::hisAccountingGetPersonalInfoById($request));
    }

    public function hisStockroomUploadProfile(Request $request)
    {
        $patientprofile = $request->file('profile');
        $destinationPath = public_path('../images/stockroom');
        $filename = time() . '.' . $patientprofile->getClientOriginalExtension();
        $result = _StockRoom::hisStockroomUploadProfile($request, $filename);
        if ($result) {
            $patientprofile->move($destinationPath, $filename); // move file to patient folder
            return response()->json('success');
        } else {return response()->json('db-error');}
    }

    public function hisStockroomUpdatePersonalInfo(Request $request)
    {
        if (_Validator::verifyAccount($request)) {
            $result = _StockRoom::hisStockroomUpdatePersonalInfo($request);
            if ($result) {
                return response()->json('success');
            } else {
                return response()->json('db-error');
            }
        } else {
            return response()->json('pass-invalid');
        }
    }

    public function warehouseProductList(Request $request)
    {
        return response()->json((new _StockRoom)::warehouseProductList($request));
    }

    public function stockRoomTempProductsSave(Request $request)
    {
        $result = _StockRoom::stockRoomTempProductsSave($request);
        if ($result) {
            return response()->json(["message" => 'success']);
        } else {
            return response()->json(["message" => 'db-error']);
        }
    }

    public function getStockRoomTempDrProducts(Request $request)
    {
        return response()->json((new _StockRoom)::getStockRoomTempDrProducts($request));
    }

    public function removeStockroomTempProducts(Request $request)
    {
        $result = _StockRoom::removeStockroomTempProducts($request);
        if ($result) {
            return response()->json(["message" => 'success']);
        } else {
            return response()->json(["message" => 'db-error']);
        }
    }

    public function processInProduct(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _StockRoom::processInProduct($request);
        if ($result) {
            return response()->json(["message" => 'success']);
        } else {
            return response()->json(["message" => 'db-error']);
        }
    }

    public function getStockroomMonitoring(Request $request)
    {
        return response()->json((new _StockRoom)::getStockroomMonitoring($request));
    }

    public function getInventoryStockroom(Request $request)
    {
        return response()->json((new _StockRoom)::getInventoryStockroom($request));
    }

    public function getInventoryStockroomDetails(Request $request)
    {
        return response()->json((new _StockRoom)::getInventoryStockroomDetails($request));
    }

    public function processOutProduct(Request $request)
    {
        if (!_Validator::verifyAccount($request)) {
            return response()->json([
                "message" => 'pass-invalid',
            ]);
        }

        $result = _StockRoom::processOutProduct($request);
        if ($result) {
            return response()->json(["message" => 'success']);
        } else {
            return response()->json(["message" => 'db-error']);
        }
    }
}
