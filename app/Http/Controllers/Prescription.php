<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Prescription;
use App\Models\_Validator;

class Prescription extends Controller
{

    public function getLocalPharmacy(Request $request){
        return (new _Prescription)::getLocalPharmacy($request);
    }

    public function getProduct(Request $request){
        return (new _Prescription)::getProduct($request);
    }
    
    public function getProductDetails(Request $request){
        return (new _Prescription)::getProductDetails($request);
    }

    public function addProduct(Request $request){
        if((new _Prescription)::addProduct($request)){
            return response()->json('success');
        }
    }

    public function unsaveProductCount(Request $request){
        return (new _Prescription)::unsaveProductCount($request);
    }

    public function unsaveProduct(Request $request){
        return (new _Prescription)::unsaveProduct($request);
    } 

    public function getPrescriptionDetails(Request $request){
        return (new _Prescription)::getPrescriptionDetails($request);
    }

    public function removeUnsave(Request $request){
        $result = (new _Prescription)::removeUnsave($request);
        if($result){
            return response()->json('success');
        }
    }

    public function getprescriptionList(Request $request){
        return (new _Prescription)::getprescriptionList($request);
    } 
    
    public function pescriptionSaveallUnsave(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Prescription)::pescriptionSaveallUnsave($request);
            if($result){
                return response()->json('success');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getvirtualPharmacy(Request $request){
        return (new _Prescription)::getvirtualPharmacy($request);
    }
    

    // online prescripiton
    
    public function getvirtualPharmacyProducts(Request $request){
        return (new _Prescription)::getvirtualPharmacyProducts($request);
    }

    public function getvirtualPharmacyProductsDetails(Request $request){
        return (new _Prescription)::getvirtualPharmacyProductsDetails($request);
    }

    public function addVirtualPrescription(Request $request){ 
        $result = (new _Prescription)::addVirtualPrescription($request);
        if($result){
            return response()->json('success');
        } 
    }

    public function getMedication(Request $request){
        return (new _Prescription)::getMedication($request);
    }

    public function getMedicationDetails(Request $request){
        return (new _Prescription)::getMedicationDetails($request);
    }

    public function getUnsaveCountByPresc(Request $request){
        return (new _Prescription)::getUnsaveCountByPresc($request);
    }

    //01-21-2022
    public function prescriptionSaveallUnsaveNurse(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Prescription)::prescriptionSaveallUnsaveNurse($request);
            if($result){
                return response()->json('success');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getPrescriptionByNurse(Request $request){
        return (new _Prescription)::getPrescriptionByNurse($request);
    } 

    public function getPrescriptionDetailsByNurse(Request $request){
        return (new _Prescription)::getPrescriptionDetailsByNurse($request);
    }
}
