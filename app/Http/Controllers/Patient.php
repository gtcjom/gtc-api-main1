<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_Patient;
use App\_Validator;

class Patient extends Controller
{
 
    public function getCurrentLocation(Request $request){
        $result = _Patient::getCurrentLocation($request->user_id);
        return response()->json($result);
    }

    public function checkCurrentSubscription(Request $request){
        $result = _Patient::checkCurrentSubscription($request);
        return response()->json($result);
    }

    public function getDoctorsRoom(Request $request){
        $result = _Patient::getDoctorsRoom($request);
        return response()->json($result);
    }

    public function getDoctorsWebRtcId(Request $request){
        $result = _Patient::getDoctorsWebRtcId($request);
        return response()->json($result);
    }

    public function enteringRoom(Request $request){
        $result = _Patient::enteringRoom($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function getPersonalInfo(Request $request){
        $result = _Patient::getPersonalInfo($request);
        return response()->json($result);
    }
 
    public function getPersonalHistory(Request $request){
        $result = _Patient::getPersonalHistory($request);
        return response()->json($result);
    }
 
    public function getBloodPressure(Request $request){
        $result = _Patient::getBloodPressure($request);
        return response()->json($result);
    }

    public function getTemperature(Request $request){
        $result = _Patient::getTemperature($request);
        return response()->json($result);
    }
    public function getUricacid(Request $request){
        $result = _Patient::getUricacid($request);
        return response()->json($result);
    }  

    public function getGlucose(Request $request){
        $result = _Patient::getGlucose($request);
        return response()->json($result);
    }

    public function getRespiratory(Request $request){
        $result = _Patient::getRespiratory($request);
        return response()->json($result);
    }

    public function getPulse(Request $request){
        $result = _Patient::getPulse($request);
        return response()->json($result);
    }

    public function getCholesterol(Request $request){
        $result = _Patient::getCholesterol($request);
        return response()->json($result);
    }

    public function getWeight(Request $request){
        $result = _Patient::getWeight($request);
        return response()->json($result);
    }

    public function getCalcium(Request $request){
        $result = _Patient::getCalcium($request);
        return response()->json($result);
    }
    
    public function getChloride(Request $request){
        $result = _Patient::getChloride($request);
        return response()->json($result);
    }

    public function getCreatinine(Request $request){
        $result = _Patient::getCreatinine($request);
        return response()->json($result);
    }

    public function getHDL(Request $request){
        $result = _Patient::getHDL($request);
        return response()->json($result);
    }

    public function getLDL(Request $request){
        $result = _Patient::getLDL($request);
        return response()->json($result);
    }
    
    public function getLithium(Request $request){
        $result = _Patient::getLithium($request);
        return response()->json($result);
    }

    public function getMagnesium(Request $request){
        $result = _Patient::getMagnesium($request);
        return response()->json($result);
    }

    public function getPotassium(Request $request){
        $result = _Patient::getPotassium($request);
        return response()->json($result);
    }

    public function getProtein(Request $request){
        $result = _Patient::getProtein($request);
        return response()->json($result);
    }

    public function getSodium(Request $request){
        $result = _Patient::getSodium($request);
        return response()->json($result);
    } 

    public function getRoomDetails(Request $request){
        return response()->json((new _Patient)::getRoomDetails($request));
    }

    public function checkAccountSubscription(Request $request){
        return response()->json((new _Patient)::checkAccountSubscription($request));
    }
    
    public function saveSubscription(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Patient)::saveSubscription($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function saveCommentToDoctor(Request $request){ 
        $result = (new _Patient)::saveCommentToDoctor($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function getapprovedComments(Request $request){
        return response()->json((new _Patient)::getapprovedComments($request));
    }
    
    public function newBp(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newBp($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newTemp(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newTemp($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newGlucose(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newGlucose($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newUricacid(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newUricacid($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newCholesterol(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newCholesterol($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newPulse(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newPulse($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newRespiratory(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newRespiratory($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newHepatitis(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newHepatitis($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newDengue(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newDengue($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newTuberculosis(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newTuberculosis($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newMedication(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newMedication($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }
    public function NewAllergies(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::NewAllergies($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function newWeight(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newWeight($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }
    
    public function updatePersonalInfo(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::updatePersonalInfo($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function uploadProfile(Request $request){ 

        $patientprofile = $request->file('profile'); 
        $destinationPath = public_path('../images/patients');
        $filename = time().'.'.$patientprofile->getClientOriginalExtension();  
        $result = _Patient::uploadProfile($request, $filename); 
        if($result){
             $patientprofile->move($destinationPath, $filename); // move file to patient folder 
            return response()->json('success');
        }else{ return response()->json('db-error'); } 
    }

    public function getSubscription(Request $request){
        return response()->json((new _Patient)::getSubscription($request));
    }

    public function subscriptionSetExpired(Request $request){ 
        $result = _Patient::subscriptionSetExpired($request); 
        if($result){
            return response()->json('success');
        }else{ return response()->json('db-error'); } 
    }

    public function getImagingOrder(Request $request){
        $result = _Patient::getImagingOrder($request);
        return response()->json($result);
    }

    public function shareNewImage(Request $request){   
        if(_Validator::verifyAccount($request)){
            $patientprofile = $request->file('share_image'); 
            $destinationPath = public_path('../images/imaging/sharedimages');
            $filename = rand(0, 888).'-'.time().'.'.$patientprofile->getClientOriginalExtension();  
            $result = _Patient::shareNewImage($request, $filename); 
            if($result){
                $patientprofile->move($destinationPath, $filename); // move file to patient folder 
                return response()->json('success');
            }else{ return response()->json('db-error'); } 
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getShareImages(Request $request){
        $result = _Patient::getShareImages($request);
        return response()->json($result);
    }

    public function updateHealthMonitoring(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::updateHealthMonitoring($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getSharedImagesDates(Request $request){
        $result = _Patient::getSharedImagesDates($request);
        return response()->json($result);
    }

    public function saveFamilyHistory(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::saveFamilyHistory($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function getFamilyHistories(Request $request){
        $result = _Patient::getFamilyHistories($request);
        return response()->json($result);
    }

    public function getFamilyHistoryById(Request $request){
        $result = _Patient::getFamilyHistoryById($request);
        return response()->json($result);
    }

    public function updateHistoryByid(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::updateHistoryByid($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function newDietSave(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newDietSave($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function getPersonalDiet(Request $request){
        $result = _Patient::getPersonalDiet($request);
        return response()->json($result);
    }

    public function getPersonalDietByDate(Request $request){
        $result = _Patient::getPersonalDietByDate($request);
        return response()->json($result);
    } 

    public function getPersonalDietBySelectedDate(Request $request){
        $result = _Patient::getPersonalDietBySelectedDate($request);
        return response()->json($result);
    }

    public function getSuggestedDiet(Request $request){
        $result = _Patient::getSuggestedDiet($request);
        return response()->json($result);
    } 
 

    public function getSuggestedDietByDate(Request $request){
        $result = _Patient::getSuggestedDietByDate($request);
        return response()->json($result);
    }

    public function newPersonalMedication(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::newPersonalMedication($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function personalMedicationListByDate(Request $request){
        $result = _Patient::personalMedicationListByDate($request);
        return response()->json($result);
    }

    public function personalMedicationList(Request $request){
        $result = _Patient::personalMedicationList($request);
        return response()->json($result);
    } 

    public static function getUnreadInquiryReply(Request $request){
        $result = _Patient::getUnreadInquiryReply($request);
        return response()->json($result);
    }

    public static function getDoctorsListBySpecialization(Request $request){
        $result = _Patient::getDoctorsListBySpecialization($request);
        return response()->json($result);
    }

    public static function addRxToCart(Request $request){
        if(_Patient::checkClaimIdInCart($request)){
            return response()->json('rx-existed');
        }
            $result = _Patient::addRxToCart($request);
            if($result > 0){ 
                return response()->json([
                    'message'=> 'success',
                    'count' => $result
                ]); 
            }
            else{ return response()->json('db-error'); }
    }

    public static function getPermissionList(Request $request){
        $result = _Patient::getPermissionList($request);
        return response()->json($result);
    }

    public function removePermission(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::removePermission($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function approvePermission(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::approvePermission($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }


    public function getOrderByGroup(Request $request){
        $result = _Patient::getOrderByGroup($request);
        return response()->json($result);
    }

    public function getOrderDetails(Request $request){
        $result = _Patient::getOrderDetails($request);
        return response()->json($result);
    }

    public function getVPharmacyList(Request $request){
        $result = _Patient::getVPharmacyList($request);
        return response()->json($result);
    }

    public function getVPharmacyItemList(Request $request){
        $result = _Patient::getVPharmacyItemList($request);
        return response()->json($result);
    }

    public function vPharmItemToCart(Request $request){
        $result = _Patient::vPharmItemToCart($request);   
        if($result === 2){
            return response()->json('item-added');
        }
        else if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error'); 
        }
    }

    public function removeAddOnItem(Request $request){
        $result = _Patient::removeAddOnItem($request);    
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error'); 
        }
    }

    public function updateCartQty(Request $request){  
        if(_Validator::verifyAccount($request)){ 
            $result = _Patient::updateCartQty($request);      
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error'); 
            }
        }else{
            return response()->json('pass-invalid'); 
        }
    }
 

    public function sentOrderToVpharm(Request $request){
        $result = _Patient::sentOrderToVpharm($request);    
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error'); 
        }
    }

    public function updateLocation(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::updateLocation($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function forApprovalOrder(Request $request){
        $result = _Patient::forApprovalOrder($request);
        return response()->json($result);
    }

    public function orderApprovedByPatient(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::orderApprovedByPatient($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function orderCancelledByPatient(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Patient::orderCancelledByPatient($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function cancelledItemDetails(Request $request){
        $result = _Patient::cancelledItemDetails($request);
        return response()->json($result);
    }

    public function getCompleteOrderTransaction(Request $request){
        $result = _Patient::getCompleteOrderTransaction($request);
        return response()->json($result);
    }

    public function getIncompleteOrder(Request $request){
        $result = _Patient::getIncompleteOrder($request);
        return response()->json($result);
    }

    public function getPharmCoordinates(Request $request){
        $result = _Patient::getPharmCoordinates($request);
        return response()->json($result);
    } 

    public function deleteOrderFromCart(Request $request){ 
        $result = _Patient::deleteOrderFromCart($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    } 

    public function savePain(Request $request){ 
        if(!_Validator::verifyAccount($request)){ 
            return response()->json('pass-invalid');
        }
        $result = _Patient::savePain($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function getPainList(Request $request){
        $result = _Patient::getPainList($request);
        return response()->json($result);
    }  

    public function getUnapprovePermission(Request $request){
        $result = _Patient::getUnapprovePermission($request);
        return response()->json($result);
    } 


    // sample qr scanner
    
    public function getQRScannerDetails(Request $request){
        $result = _Patient::getQRScannerDetails($request);
        return response()->json($result);
    } 

    public static function getDoctorsListBySearch(Request $request){
        $result = _Patient::getDoctorsListBySearch($request);
        return response()->json($result);
    } 

    public function forApprovalGetUnpaid(Request $request){
        $result = _Patient::forApprovalGetUnpaid($request);
        return response()->json($result);
    }

    public function completeTransactGetPaid(Request $request){
        $result = _Patient::completeTransactGetPaid($request);
        return response()->json($result);
    }
    
    public function getVcallRefInfo(Request $request){
        $result = _Patient::getVcallRefInfo($request);
        return response()->json($result);
    }

    public function deleteVcallRoom(Request $request){
        $result = _Patient::deleteVcallRoom($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    //new
    public function getCompleteHemathologyDetails(Request $request){
        return response()->json((new _Patient)::getCompleteHemathologyDetails($request));
    }
    public function getCompleteSoroDetails(Request $request){
        return response()->json((new _Patient)::getCompleteSoroDetails($request));
    }
    public function getCompleteChemDetails(Request $request){
        return response()->json((new _Patient)::getCompleteChemDetails($request));
    }
    public function getCompleteMicroDetails(Request $request){
        return response()->json((new _Patient)::getCompleteMicroDetails($request));
    }
    public function getCompleteFecalDetails(Request $request){
        return response()->json((new _Patient)::getCompleteFecalDetails($request));
    }

    //prev edit
    public function patientGetDocSpecial(Request $request){
        $result = _Patient::patientGetDocSpecial($request);
        return response()->json($result);
    }

    public function updateTranscMethod(Request $request){ 
        if(!_Validator::verifyAccount($request)){ 
            return response()->json('pass-invalid');
        }
        $result = _Patient::updateTranscMethod($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function getUserInfoSetDelFee(Request $request){
        return response()->json((new _Patient)::getUserInfoSetDelFee($request));
    }

    public function getItemsByOrderID(Request $request){
        return response()->json((new _Patient)::getItemsByOrderID($request));
    }

    public function notificationUnview(Request $request){
        return response()->json((new _Patient)::notificationUnview($request));
    }

    public function updateUnreadNotification(Request $request){
        $result = _Patient::updateUnreadNotification($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function updateImagingUnreadNotification(Request $request){
        $result = _Patient::updateImagingUnreadNotification($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }
    
    public function clearAllNotification(Request $request){
        $result = _Patient::clearAllNotification($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }
    
    public function getAllUnreadMsgFromDoctor(Request $request){
        return response()->json((new _Patient)::getAllUnreadMsgFromDoctor($request));
    }

     public function qwertyuiop(Request $request){
        return response()->json((new _Patient)::qwertyuiop($request));
    }

    public function getPatientNotifAppointment(Request $request){
        return response()->json((new _Patient)::getPatientNotifAppointment($request));
    }

    public function getPersonalInfoById(Request $request){
        return response()->json((new _Patient)::getPersonalInfoById($request));
    }

    public function OrderDetailsClinicalChem(Request $request){
        return response()->json((new _Patient)::OrderDetailsClinicalChem($request));
    }

    public function OrderDetailsClinicalMicro(Request $request){
        return response()->json((new _Patient)::OrderDetailsClinicalMicro($request));
    }

    public function OrderDetailsFecalAnal(Request $request){
        return response()->json((new _Patient)::OrderDetailsFecalAnal($request));
    }

    public function OrderDetailsHemathology(Request $request){
        return response()->json((new _Patient)::OrderDetailsHemathology($request));
    }

    public function OrderDetailsSerology(Request $request){
        return response()->json((new _Patient)::OrderDetailsSerology($request));
    }

    public function graphDetailsHemathology(Request $request){
        return response()->json((new _Patient)::graphDetailsHemathology($request));
    }

    public function graphDetailsClinicalChem(Request $request){
        return response()->json((new _Patient)::graphDetailsClinicalChem($request));
    }

    public function graphDetailsFecalAnalysis(Request $request){
        return response()->json((new _Patient)::graphDetailsFecalAnalysis($request));
    }

    public function graphDetailsClinicalMicro(Request $request){
        return response()->json((new _Patient)::graphDetailsClinicalMicro($request));
    }

    public function getAllUnviewedPatientNotif(Request $request){
        return response()->json((new _Patient)::getAllUnviewedPatientNotif($request));
    }

    public function getAllViewedPatientNotif(Request $request){
        return response()->json((new _Patient)::getAllViewedPatientNotif($request));
    }

    public function getUpdateToViewByOrderId(Request $request){
        $result = _Patient::getUpdateToViewByOrderId($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }
    
    public function updateToViewedById(Request $request){
        $result = _Patient::updateToViewedById($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function getDiagnosisList(Request $request){
        return response()->json((new _Patient)::getDiagnosisList($request));
    }

    public function getMedicationList(Request $request){
        return response()->json((new _Patient)::getMedicationList($request));
    }

}
