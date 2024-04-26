<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_ClinicSecretary;
use App\_Validator;

class ClinicSecretary extends Controller
{
    public function getInquiryByPatient(Request $request){
        return response()->json(_ClinicSecretary::getInquiryByPatient($request)); 
    } 

    public function getLatestInquiries(Request $request){
        return response()->json(_ClinicSecretary::getLatestInquiries($request)); 
    } 

    public function sendInquiryMsg(Request $request){ 
        $result = (new _ClinicSecretary)::sendInquiryMsg($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function getInquiryMessages(Request $request){
        return response()->json(_ClinicSecretary::getInquiryMessages($request)); 
    }

    public function getnewMessageByInterval(Request $request){
        return response()->json(_ClinicSecretary::getnewMessageByInterval($request)); 
    }

    public function getInquiryLastMsg(Request $request){
        return response()->json(_ClinicSecretary::getInquiryLastMsg($request)); 
    } 

    public function getAccreditedDoctorsList(Request $request){
        return response()->json(_ClinicSecretary::getAccreditedDoctorsList($request)); 
    }

    public function getDoctorsDetails(Request $request){
        return response()->json(_ClinicSecretary::getDoctorsDetails($request)); 
    }

    public function getDetailsInformation(Request $request){
        return response()->json(_ClinicSecretary::getDetailsInformation($request)); 
    }

    public function editClinicInformation(Request $request){
        if(_Validator::verifyAccount($request)){
            if(_ClinicSecretary::editClinicInformation($request)){ return response()->json('success'); }
            else{ return response()->json('db-error'); }
        }else{ return response()->json('pass-invalid'); }
    } 
}
