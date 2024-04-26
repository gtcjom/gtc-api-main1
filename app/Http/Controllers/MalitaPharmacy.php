<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_MalitaPharmacy;
use App\_Validator;

class MalitaPharmacy extends Controller
{
    public function newProductSave(Request $request){ 
        if(_Validator::verifyAccount($request)){
            if(_Validator::checkProductIfExist($request->brand)){
                return response()->json('product-exist');
            }
            // if(_Validator::checkInvoiceIfExist($request->invoice)){
            //     return response()->json('invoice-exist');
            // }
            $result = _MalitaPharmacy::newProductSave($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getInventoryList(Request $request){
        return response()->json((new _MalitaPharmacy)::getInventoryList($request->management_id));
    }

    public function getBrandList(Request $request){
        return response()->json((new _MalitaPharmacy)::getBrandList($request));
    }

    public function getBatchList(Request $request){
        return response()->json((new _MalitaPharmacy)::getBatchList($request));
    }

    public function getBatchInfo(Request $request){
        return response()->json((new _MalitaPharmacy)::getBatchInfo($request));
    }
    
    public function getPuchaseList(Request $request){
        return response()->json((new _MalitaPharmacy)::getPuchaseList($request));
    }

    public function addPuchase(Request $request){ 
        if(_Validator::checkProductBatchIfExist($request)){
            return response()->json('product-exist');
        }
        $result = _MalitaPharmacy::addPuchase($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    public function deletePurchaseById(Request $request){ 
        $result = _MalitaPharmacy::deletePurchaseById($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }
    }

    public function getBatchesByProdId(Request $request){
        return response()->json((new _MalitaPharmacy)::getBatchesByProdId($request));
    }

    public function addNewStockByProdId(Request $request){ 
        if(_Validator::verifyAccount($request)){
            if(_Validator::checkInvoiceIfExist($request->invoice)){
                return response()->json('invoice-exist');
            }
            $result = _MalitaPharmacy::addNewStockByProdId($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function addQtyBySpecificBatch(Request $request){ 
        if(_Validator::verifyAccount($request)){
            if(_Validator::checkInvoiceIfExist($request->invoice)){
                return response()->json('invoice-exist');
            }
            $result = _MalitaPharmacy::addQtyBySpecificBatch($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function delQtyBySpecificBatch(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _MalitaPharmacy::delQtyBySpecificBatch($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function confirmPaymentPurchase(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _MalitaPharmacy::confirmPaymentPurchase($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getReceiptList(Request $request){
        return response()->json((new _MalitaPharmacy)::getReceiptList($request));
    }

    public function printForTransaction(Request $request){
        return response()->json((new _MalitaPharmacy)::printForTransaction($request));
    }

    public function getStockList(Request $request){
        return response()->json((new _MalitaPharmacy)::getStockList($request));
    }

    public function getLogAct(Request $request){
        return response()->json((new _MalitaPharmacy)::getLogAct($request));
    }

    public function getSalesReport(Request $request){
        return response()->json((new _MalitaPharmacy)::getSalesReport($request));
    }

    public function getFilterByDate(Request $request){
        return response()->json((new _MalitaPharmacy)::getFilterByDate($request));
    }

    public function getLogsList(Request $request){
        return response()->json((new _MalitaPharmacy)::getLogsList($request));
    }

    public function getInfo(Request $request){
        return response()->json((new _MalitaPharmacy)::getInfo($request));
    }

    public function updatePharmacyInfo(Request $request){
        $image = null; 
        if(!empty($request->image)){
            $attachment = $request->file('image'); 
            $destinationPath = public_path('../images/pharmacy');
            $image = time().'.'.$attachment->getClientOriginalExtension(); 
            $attachment->move($destinationPath, $image);
        }  
        $result = _MalitaPharmacy::updatePharmacyInfo($request, $image);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function updatePharmacyUsername(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _MalitaPharmacy::updatePharmacyUsername($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function updatePharmacyPassword(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _MalitaPharmacy::updatePharmacyPassword($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getUsersList(Request $request){
        return response()->json((new _MalitaPharmacy)::getUsersList($request));
    }

    public function updateSelectedUserInfo(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _MalitaPharmacy::updateSelectedUserInfo($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function updateSelectedUserDeActivate(Request $request){
        $result = _MalitaPharmacy::updateSelectedUserDeActivate($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function updateSelectedUserActivate(Request $request){
        $result = _MalitaPharmacy::updateSelectedUserActivate($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function getPrescriptionList(Request $request){
        return response()->json((new _MalitaPharmacy)::getPrescriptionList($request));
    }
    
    public function getPrescriptionDetails(Request $request){
        return response()->json((new _MalitaPharmacy)::getPrescriptionDetails($request));
    }
    
    public function updateQtyPrescription(Request $request){   
        $result = _MalitaPharmacy::updateQtyPrescription($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function processPaymentPresc(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _MalitaPharmacy::processPaymentPresc($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function addUserPharmacy(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _MalitaPharmacy::addUserPharmacy($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getRole(Request $request){
        return response()->json((new _MalitaPharmacy)::getRole($request));
    }

    public function broadcastOrderSigbin(Request $request){   
        $result = _MalitaPharmacy::broadcastOrderSigbin($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function getAllBrandList(Request $request){
        return response()->json((new _MalitaPharmacy)::getAllBrandList($request));
    }
    
}
