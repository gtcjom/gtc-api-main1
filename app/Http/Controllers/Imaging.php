<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Imaging;
use App\Models\_Validator;
use Illuminate\Support\Facades\Storage;

class Imaging extends Controller
{
    public function imagingDetails(Request $request){ 
        return response()->json((new _Imaging)::imagingDetails($request->management_id));
    }

    public function imagingByPatient(Request $request){
        return response()->json((new _Imaging)::imagingByPatient($request->patient_id));
    }

    public function imagingUnprocess(Request $request){
        return response()->json((new _Imaging)::imagingUnprocess($request));
    }

    public function imagingPending(Request $request){
        return response()->json((new _Imaging)::imagingPending($request));
    }

    public function imagingProcessed(Request $request){
        return response()->json((new _Imaging)::imagingProcessed($request));
    }

    public function imagingOrderDetails(Request $request){
        return response()->json((new _Imaging)::imagingOrderDetails($request));
    }

    public function createOrder(Request $request){
        if(_Validator::verifyAccount($request)){
            if((new _Imaging)::createOrder($request)){ return response()->json('success'); }
            else{ return response()->json('db-error'); }
        }else{ return response()->json('pass-inv'); }
    }

    public function imagingCounts(Request $request){
        return response()->json((new _Imaging)::imagingCounts($request));
    }

    public function getOngoingOrder(Request $request){
        return response()->json((new _Imaging)::getOngoingOrder($request));
    }  

    public function getCounts(Request $request){ 
        $imaging_id = (new _Imaging)::getImagingId($request->user_id);
        return response()->json((new _Imaging)::getCounts($imaging_id));
    }

    public function getNewOrder(Request $request){ 
        $imaging_id = (new _Imaging)::getImagingId($request->user_id);
        return response()->json((new _Imaging)::getNewOrder($imaging_id));
    }

    public function getPendingOrder(Request $request){ 
        $imaging_id = (new _Imaging)::getImagingId($request->user_id);
        return response()->json((new _Imaging)::getPendingOrder($imaging_id));
    }

    public function getProcessingOrder(Request $request){ 
        $imaging_id = (new _Imaging)::getImagingId($request->user_id);
        return response()->json((new _Imaging)::getProcessingOrder($imaging_id));
    }
 
    public function getAllRecords(Request $request){ 
        $imaging_id = (new _Imaging)::getImagingId($request->user_id);
        return response()->json((new _Imaging)::getAllRecords($imaging_id));
    }  
    
    public function orderSetProcess(Request $request){   
        if(_Validator::verifyAccount($request)){
            if((new _Imaging)::orderSetProcess($request)){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{ return response()->json('pass-inv'); }
    }
 
    public function orderSetPending(Request $request){   
        if(_Validator::verifyAccount($request)){
            if((new _Imaging)::orderSetPending($request)){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{ return response()->json('pass-inv'); }
    }

    public function addResult(Request $request){
        if(_Validator::verifyAccount($request)){
            $filename = '';
            if(!empty($request->attachment)){
                $attachment = $request->file('attachment'); 
                $destinationPath = public_path('../images/imaging');
                $filename = time().'.'.$attachment->getClientOriginalExtension(); 
                $attachment->move($destinationPath, $filename);
            }
            if((new _Imaging)::addResult($request, $filename)){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-inv');
        }
    }

    public function getImagingOrderReadByTelerad(Request $request){  
        return response()->json((new _Imaging)::getImagingOrderReadByTelerad($request));
    } 

    public function getTeleradist(Request $request){  
        return response()->json((new _Imaging)::getTeleradist($request));
    } 

    public function getTeleradConversation(Request $request){  
        return response()->json((new _Imaging)::getTeleradConversation($request));
    } 

    public function sendMessage(Request $request){
        if((new _Imaging)::sendMessage($request)){
            return response()->json('success');
        }
    } 

    public function validateOrder(Request $request){  
        return response()->json((new _Imaging)::validateOrder($request));
    } 

    public function getRadiologist(Request $request){  
        return response()->json((new _Imaging)::getRadiologist($request));
    } 

    public function sentToRadiologist(Request $request){ 
        if(_Validator::verifyAccount($request)){   

            // $i = 1;
            // $rname = $request->type === 'Telerad' ? 'totelerad-'.time().'-' : 'toradio-'.time().'-';

            // foreach ($request->file('attachments') as $file) {
            //     $name =  $rname.$i++.'.'.$file->extension();
            //     $file->move(public_path('../images/imaging'), $name); 
            //     $filename[] = $name; 
            // } 


            // $fname = implode(',', $filename);   

            $model = new _Imaging();     
 
            $files = $request->file('attachments'); 
            $filename = []; 
            $count = 0; 
            $ftp = Storage::disk('ftp'); 
            
            foreach($files as $file){  

                $fname = date('Y').'-'.rand(0, 9999).'-'.time();
                $filename[] = $fname.'.'.$file->getClientOriginalExtension();   

                $newfname = $filename[$count++];
                
                if($request->type == 'Telerad'){
                    $ftp->put('/imaging/'.$newfname, fopen($file, 'r+'));
                }

                $file->move(public_path('../images/imaging'), $newfname);  
            }        

            $result = $model->sentToRadiologist($request , implode(',',$filename)); 

            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }  
        }else{
            return response()->json('pass-invalid');
        } 
    }

    public function getNewFindings(Request $request){  
        return response()->json((new _Imaging)::getNewFindings($request));
    } 


    public function newFindingsSetAsRead(Request $request){   
        if((new _Imaging)::newFindingsSetAsRead($request)){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        }   
    } 

    //new 123456789
    function hisimagingGetHeaderInfo(Request $request){  
        return response()->json((new _Imaging)::hisimagingGetHeaderInfo($request));
    } 

    function hisimagingGetPersonalInfoById(Request $request){
        return response()->json((new _Imaging)::hisimagingGetPersonalInfoById($request));
    }

    function hisimagingUploadProfile(Request $request){ 
        $patientprofile = $request->file('profile'); 
        $destinationPath = public_path('../images/imaging');
        $filename = time().'.'.$patientprofile->getClientOriginalExtension();  
        $result = _Imaging::hisimagingUploadProfile($request, $filename); 
        if($result){
             $patientprofile->move($destinationPath, $filename); // move file to patient folder 
            return response()->json('success');
        }else{ return response()->json('db-error'); } 
    }

    function hisimagingUpdatePersonalInfo(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Imaging::hisimagingUpdatePersonalInfo($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hisimagingUpdateUsername(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Imaging::hisimagingUpdateUsername($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hisimagingUpdatePassword(Request $request){ 
        if(_Validator::verifyAccount($request)){
            $result = _Imaging::hisimagingUpdatePassword($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }
    
    function hisimagingGetAllTest(Request $request){ 
        return response()->json((new _Imaging)::hisimagingGetAllTest($request));
    } 

    function hisimagingSaveNewTest(Request $request){
        if(_Validator::verifyAccount($request)){
            if((new _Imaging)::hisimagingSaveNewTest($request)){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hisimagingEditTest(Request $request){
        if(_Validator::verifyAccount($request)){
            if((new _Imaging)::hisimagingEditTest($request)){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    function hisimagingGetPatientForImaging(Request $request){ 
        return response()->json((new _Imaging)::hisimagingGetPatientForImaging($request));
    }

    function hisimagingGetPatientInformation(Request $request){
        return response()->json((new _Imaging)::hisimagingGetPatientInformation($request));
    }

    function hisimagingOrderAddResult(Request $request){
        if(_Validator::verifyAccount($request)){
            $image = ''; 
            if(!empty($request->image)){
                $attachment = $request->file('image'); 
                $attachmentname = [];
                $count = 0;
                $destinationPath = public_path('../images/imaging');
                $ftp = Storage::disk('ftp'); 
    
                foreach ($attachment as $file) {
                    $fname = date('Y').'-'.rand(0, 9999).'-'.time();
                    $attachmentname[] = $fname.'.'.$file->getClientOriginalExtension();   
                    $newfname = $attachmentname[$count++];
    
                    if($request->radiologist_type == 'Telerad'){
                        $ftp->put('/imaging/'.$newfname, fopen($file, 'r+'));
                    }

                    $file->move($destinationPath, $newfname);  
                }
            }  
    
            $result = _Imaging::hisimagingOrderAddResult($request, implode(',',$attachmentname));
    
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            } 
        }else{
            return response()->json('pass-invalid');
        }
    }

    // function hisimagingOrderAddResult(Request $request){
    //     if(_Validator::verifyAccount($request)){
    //         $result = _Imaging::hisimagingOrderAddResult($request);
    //         if($result){
    //             return response()->json('success');
    //         }else{
    //             return response()->json('db-error');
    //         } 
    //     }else{
    //         return response()->json('pass-invalid');
    //     }
    // }

    public function updatePatientPriceQty(Request $request){    
        $model = new _DrugStore();
        $result = $model->updatePatientPriceQty($request);
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        }
    }

    public function imagingOrderList(Request $request){   
        return response()->json((new _Imaging)::imagingOrderList($request));
    } 

    public function imagingOrderSelectedDetails(Request $request){  
        return response()->json((new _Imaging)::imagingOrderSelectedDetails($request));
    }   

    public function hisimagingUploadImagingAttach(Request $request){
        if(!empty($request->image)){
            $attachment = $request->file('image'); 
            $attachmentname = [];
            $count = 0;
            $destinationPath = public_path('../images/imaging');

            foreach ($attachment as $file) {
                $fname = date('Y').'-'.rand(0, 9999).'-'.time();
                $attachmentname[] = $fname.'.'.$file->getClientOriginalExtension();   
                $newfname = $attachmentname[$count++];
                $file->move($destinationPath, $newfname);  
            }
        }  
        $result = _Imaging::hisimagingUploadImagingAttach($request, implode(',',$attachmentname));
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function hisimagingGetAllLocalRadiologist(Request $request){   
        return response()->json((new _Imaging)::hisimagingGetAllLocalRadiologist($request));
    } 

    public function hisimagingGetAllTeleRadiologist(Request $request){   
        return response()->json((new _Imaging)::hisimagingGetAllTeleRadiologist($request));
    } 
    
    public function hisimagingGetNewOrder(Request $request){
        return response()->json((new _Imaging)::hisimagingGetNewOrder($request));
    }

    public function hisimagingGetImagingOrderReports(Request $request){
        return response()->json((new _Imaging)::hisimagingGetImagingOrderReports($request));
    }

    public function hisimagingGetReportDetails(Request $request){
        return response()->json((new _Imaging)::hisimagingGetReportDetails($request));
    }
    
    public function getImagingOrderReportsByDate(Request $request){
        return response()->json((new _Imaging)::getImagingOrderReportsByDate($request));
    }

    public function getPatientInformation(Request $request){
        return response()->json((new _Imaging)::getPatientInformation($request));
    }

    public function getImagingOrderInformation(Request $request){
        return response()->json((new _Imaging)::getImagingOrderInformation($request));
    }

    public function getTeleradiologistList(Request $request){
        return response()->json((new _Imaging)::getTeleradiologistList($request));
    }

    public static function setImagingOrderRadiologist(Request $request){  
        $files = $request->file('attachment'); 
        $filename = []; 
        $count = 0;
        $ftp = Storage::disk('ftp');
        foreach($files as $file){ 

            $fname = date('Y').'-'.rand(0, 9999).'-'.time();
            $filename[] = $fname.'.'.$file->getClientOriginalExtension();   
 
            $ftp->put('/imaging/'.$filename[$count++], fopen($file, 'r+'));
        } 
 
        if(_Imaging::setImagingOrderRadiologist($request , implode(',',$filename))){ 
            return response()->json('success');
        }
    }

    public function getPatientReviewed(Request $request){
        return response()->json((new _Imaging)::getPatientReviewed($request));
    }

    public function imagingGetPatientListQueue(Request $request){
        return response()->json((new _Imaging)::imagingGetPatientListQueue($request));
    }
    
    public function getResultToEditOnline(Request $request){
        return response()->json((new _Imaging)::getResultToEditOnline($request));
    }

    public function getImagingPrintableHeader(Request $request){
        return response()->json((new _Imaging)::getImagingPrintableHeader($request));
    }

    public function getPrintOrder(Request $request){
        return response()->json((new _Imaging)::getPrintOrder($request));
    }

    function editConfirmedResultImaging(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Imaging::editConfirmedResultImaging($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            } 
        }else{
            return response()->json('pass-invalid');
        }
    }
    
    public function getAllToAddResult(Request $request){
        return response()->json((new _Imaging)::getAllToAddResult($request));
    }

    function saveNewFlowEditedResult(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Imaging::saveNewFlowEditedResult($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            } 
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function hisimagingGetPatientForImagingUltraSound(Request $request){
        return response()->json((new _Imaging)::hisimagingGetPatientForImagingUltraSound($request));
    }

    function hisimagingOrderAddResultUltraSound(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = _Imaging::hisimagingOrderAddResultUltraSound($request);
            if($result){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            } 
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getCurrentFormInformationResult(Request $request){
        return response()->json((new _Imaging)::getCurrentFormInformationResult($request));
    }

    public function editResultPrintLayout(Request $request)
    {
        $result = _Imaging::editResultPrintLayout($request);
        if ($result) {
            return response()->json('success');
        } else {
            return response()->json('db-error');
        }
    }

    public function getImagingFormHeader(Request $request)
    {
        return response()->json((new _Imaging)::getImagingFormHeader($request));
    }
    
    public function getOrderImagingDetailsPrint(Request $request)
    {
        return response()->json((new _Imaging)::getOrderImagingDetailsPrint($request));
    }

}
