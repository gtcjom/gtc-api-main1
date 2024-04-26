<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_Telerad;
use App\_Validator;

class Telerad extends Controller
{
    public function getReviewedPatient(Request $request){
        return response()->json((new _Telerad)::getReviewedPatient($request));
    }

    public function getMessages(Request $request){
        return response()->json((new _Telerad)::getMessages($request));
    }

    public function getMessageConversation(Request $request){
        return response()->json((new _Telerad)::getMessageConversation($request));
    }

    public function sendMessage(Request $request){
        if((new _Telerad)::sendMessage($request)){
            return response()->json('success');
        }
    }

    public function getUnreadMessage(Request $request){
        return response()->json((new _Telerad)::getUnreadMessage($request));
    }

    public function saveFindings(Request $request){
        $result = (new _Telerad)::saveFindings($request);
        if($result){
            return response()->json('success');
        }
    }

    public function getOrderDetails(Request $request){
        return response()->json((new _Telerad)::getOrderDetails($request));
    }

    public function getReviewedList(Request $request){
        return response()->json((new _Telerad)::getReviewedList($request));
    }

    public function getReviewedListFilteredByDate(Request $request){
        return response()->json((new _Telerad)::getReviewedListFilteredByDate($request));
    }


    public function getReviewedListToday(Request $request){
        return response()->json((new _Telerad)::getReviewedListToday($request));
    }

    public function getLatestPatient(Request $request){
        return response()->json((new _Telerad)::getLatestPatient($request));
    }

    public function getTeleradSidebarHeader(Request $request){
        return response()->json((new _Telerad)::getTeleradSidebarHeader($request));
    }

    public function updateUsername(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Telerad)::updateUsername($request);
            if($result){
                return response()->json('success');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function updatePassword(Request $request){
        if(_Validator::verifyAccount($request)){
            $result = (new _Telerad)::updatePassword($request);
            if($result){    
                return response()->json('success');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }

    public function getCodes(Request $request){
        return response()->json((new _Telerad)::getCodes($request));
    }
}
