<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\_DrugStore; 
use App\_Validator;

class DrugStore extends Controller
{
    public function getAllForHomeRx(Request $request){
        return response()->json((new _DrugStore)::getAllForHomeRx($request));
    }

    public function getAllForHomeRxDetails(Request $request){
        return response()->json((new _DrugStore)::getAllForHomeRxDetails($request));
    }

    public function updatePatientPriceQty(Request $request){    
        $model = new _DrugStore();
        $result = $model->updatePatientPriceQty($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function processPatientOrder (Request $request){
        $model = new _DrugStore();
        $result = $model->processPatientOrder($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function getAllConfirmedOrder(Request $request){
        return response()->json((new _DrugStore)::getAllConfirmedOrder($request));
    }

    public function getAllConfirmedOrderDetails(Request $request){
        return response()->json((new _DrugStore)::getAllConfirmedOrderDetails($request));
    }

    public function updateOrderToRdy(Request $request){    
        $model = new _DrugStore();
        $result = $model->updateOrderToRdy($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function updateOrderToBroadcast(Request $request){
        $model = new _DrugStore();
        $result = $model->updateOrderToBroadcast($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function pharmaConfirmOrder (Request $request){
        $model = new _DrugStore();
        $result = $model->pharmaConfirmOrder($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function getMyCurrentOTP(Request $request){
        return response()->json((new _DrugStore)::getMyCurrentOTP($request));
    }

    public function changeMyOTP(Request $request){    
        $model = new _DrugStore();
        $result = $model->changeMyOTP($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function getAllCancelOrder(Request $request){
        return response()->json((new _DrugStore)::getAllCancelOrder($request));
    }

    public function getCancelOrderDetails(Request $request){
        return response()->json((new _DrugStore)::getCancelOrderDetails($request));
    }

    public function getAllCompleteOrder(Request $request){
        return response()->json((new _DrugStore)::getAllCompleteOrder($request));
    }

    public function getCompleteOrderDetails(Request $request){
        return response()->json((new _DrugStore)::getCompleteOrderDetails($request));
    }

    public function getPersonalInfo(Request $request){
        return response()->json((new _DrugStore)::getPersonalInfo($request));
    }

    public function getDoctorPrescription(Request $request){
        return response()->json((new _DrugStore)::getDoctorPrescription($request));
    }

    public function getPickUpCount(Request $request){
        return response()->json((new _DrugStore)::getPickUpCount($request));
    }
    
}