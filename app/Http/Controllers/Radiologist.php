<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Radiologist;
use App\Models\_Validator;

class Radiologist extends Controller
{
    public function getPatientForReview(Request $request){
        return (new _Radiologist)::getPatientForReview($request);
    }

    public function getPatientReviewed(Request $request){
        return (new _Radiologist)::getPatientReviewed($request);
    }

    public function getOrderDetails(Request $request){
        return (new _Radiologist)::getOrderDetails($request);
    } 

    public function saveOrderResult(Request $request){
        $model = new _Radiologist();
        $result = $model->saveOrderResult($request); 
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    public function getPatientReviewedByDate(Request $request){
        return (new _Radiologist)::getPatientReviewedByDate($request);
    }

    function radiologistGetHeaderInfo(Request $request){
        return response()->json((new _Radiologist)::radiologistGetHeaderInfo($request));
    }

    function hisradGetPersonalInfoById(Request $request){
        return response()->json((new _Radiologist)::hisradGetPersonalInfoById($request));
    }

    function hisradUploadProfile(Request $request){ 
        $patientprofile = $request->file('profile'); 
        $destinationPath = public_path('../images/radiologist');
        $filename = time().'.'.$patientprofile->getClientOriginalExtension();  
        $result = _Radiologist::hisradUploadProfile($request, $filename); 
        if($result){
             $patientprofile->move($destinationPath, $filename); // move file to patient folder 
            return response()->json('success');
        }else{ return response()->json('db-error'); } 
    }

    function hisradUpdatePersonalInfo(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Radiologist::hisradUpdatePersonalInfo($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hisradUpdateUsername(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Radiologist::hisradUpdateUsername($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hisradUpdatePassword(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Radiologist::hisradUpdatePassword($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }
    
    public function getPatientForReviewUltraSound(Request $request){
        return (new _Radiologist)::getPatientForReviewUltraSound($request);
    }

    public function saveOrderUltraSoundResult(Request $request){
        $model = new _Radiologist();
        $result = $model->saveOrderUltraSoundResult($request); 
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }
    

}
