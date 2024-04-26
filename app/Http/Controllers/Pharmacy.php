<?php

namespace App\Http\Controllers;

use App\Models\_Pharmacy;
use App\Models\_Validator;
use Illuminate\Http\Request;

class Pharmacy extends Controller
{
    function hispharmacyGetHeaderInfo(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetHeaderInfo($request));
    }

    function hispharmacyGetRole(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetRole($request));
    }

    function hispharmacyGetInventoryList(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetInventoryList($request->management_id));
    }
    
    function hispharmacyGetPuchaseList(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetPuchaseList($request));
    }

    function hispharmacyGetBrandList(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetBrandList($request));
    }

    function hispharmacyGetBatchList(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetBatchList($request));
    }

    function hispharmacyGetBatchInfo(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetBatchInfo($request));
    }

    function hispharmacyConfirmPaymentPurchase(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyConfirmPaymentPurchase($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyNewProductSave(Request $request){ 
        if(_Validator::verifyAccount($request)){
            if(_Validator::checkProductIfExist($request)){
                return response()->json('product-exist');
            }
            $result = _Pharmacy::hispharmacyNewProductSave($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyGetBatchesByProdId(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetBatchesByProdId($request));
    }

    function hispharmacyAddNewStockByProdId(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyAddNewStockByProdId($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyAddQtyBySpecificBatch(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyAddQtyBySpecificBatch($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyDelQtyBySpecificBatch(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyDelQtyBySpecificBatch($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyAddPuchase(Request $request){ 
        if(_Validator::checkProductBatchIfExist($request)){
            return response()->json('product-exist');
        }
        $result = _Pharmacy::hispharmacyAddPuchase($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    function hispharmacyGetReceiptList(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetReceiptList($request));
    }
    
    function hispharmacyPrintForTransaction(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyPrintForTransaction($request));
    }

    function hispharmacyGetStockList(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetStockList($request));
    }

    function hispharmacyGetLogAct(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetLogAct($request));
    }
    
    function hispharmacyGetSalesReport(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetSalesReport($request));
    }

    function hispharmacyGetFilterByDate(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetFilterByDate($request));
    }

    function hispharmacyDeletePurchaseById(Request $request){ 
        $result = _Pharmacy::hispharmacyDeletePurchaseById($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    function hispharmacyGetPersonalInfoById(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetPersonalInfoById($request));
    }

    function hispharmacyUploadProfile(Request $request){ 
        $patientprofile = $request->file('profile'); 
        $destinationPath = public_path('../images/pharmacy');
        $filename = time().'.'.$patientprofile->getClientOriginalExtension();  
        $result = _Pharmacy::hispharmacyUploadProfile($request, $filename); 
        if($result){
             $patientprofile->move($destinationPath, $filename); // move file to patient folder 
            return response()->json('success');
        }else{ return response()->json('db-error'); } 
    }

    function hispharmacyUpdatePersonalInfo(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyUpdatePersonalInfo($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyUpdateUsername(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyUpdateUsername($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyUpdatePassword(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyUpdatePassword($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hispharmacyGetPrescriptionList(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetPrescriptionList($request));
    }

    function hispharmacyGetPrescriptionDetails(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetPrescriptionDetails($request));
    }   

    function hispharmacyGetRxDoctorsRx(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetRxDoctorsRx($request));
    }

    function hispharmacyGetPatientInformation(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetPatientInformation($request));
    }

    function hispharmacyGetPrescription(Request $request){
        return response()->json((new _Pharmacy)::hispharmacyGetPrescription($request));
    }

    function hispharmacyUpdateQtyPrescription(Request $request){   
        $result = _Pharmacy::hispharmacyUpdateQtyPrescription($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    function hispharmacyProcessPaymentPresc(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Pharmacy::hispharmacyProcessPaymentPresc($request);
            if($result){
                // return response()->json($result);
                return response()->json('success');
            }else{  
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function getAllUnClaimedPres(Request $request){
        return response()->json((new _Pharmacy)::getAllUnClaimedPres($request));
    }
    
    function getClaimIdDetails(Request $request){
        return response()->json((new _Pharmacy)::getClaimIdDetails($request));
    }

    function prescriptionNewQtyOrdered(Request $request){
        if((new _Pharmacy)::prescriptionNewQtyOrdered($request)){ 
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    
    function prescriptionPaymentProcess(Request $request){
        if( ! _Validator::verifyAccount($request)){
            return response()->json('pass-invalid');
        }
        if((new _Pharmacy)::prescriptionPaymentProcess($request)){ 
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    function prescriptionAddToBilling(Request $request){ 
        if((new _Pharmacy)::prescriptionAddToBilling($request)){ 
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    function prescriptionProductBatches(Request $request){
        return response()->json((new _Pharmacy)::prescriptionProductBatches($request));
    }

    function prescriptionProductBatchesAvsQty(Request $request){
        return response()->json((new _Pharmacy)::prescriptionProductBatchesAvsQty($request));
    }

    
}
