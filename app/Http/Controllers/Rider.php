<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_Rider; 
use App\_Validator;

class Rider extends Controller
{  
    public function getAllForDeliveryList(Request $request){
        return response()->json((new _Rider)::getAllForDeliveryList($request));
    }

    public function getRiderPickUpDetails(Request $request){
        return response()->json((new _Rider)::getRiderPickUpDetails($request));
    }

    public function pickUpItemFromPharma(Request $request){    
        $model = new _Rider();
        $result = $model->pickUpItemFromPharma($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function startDeliveryToPatient(Request $request){    
        $model = new _Rider();
        $result = $model->startDeliveryToPatient($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function getAllDetailsToDeliver(Request $request){
        return response()->json((new _Rider)::getAllDetailsToDeliver($request));
    }

    public function itemDropOffToCustomer(Request $request){    
        $model = new _Rider();
        $result = $model->itemDropOffToCustomer($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function getPersonalInfo(Request $request){
        return response()->json((new _Rider)::getPersonalInfo($request));
    }

    public function getRiderPickUpCount(Request $request){
        return response()->json((new _Rider)::getRiderPickUpCount($request));
    }

    public function getAllDeliveryHist(Request $request){
        return response()->json((new _Rider)::getAllDeliveryHist($request));
    }


    public function riderReadMore(Request $request){
        return response()->json((new _Rider)::riderReadMore($request));
    }
    

}