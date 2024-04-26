<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_Validator;
use App\_OnlineChat;

class OnlineChat extends Controller
{
    public function getMessage(Request $request){
        $result = _OnlineChat::getMessage($request); 
        return response()->json($result);  
    }

    public function sendMessage(Request $request){

        $message = '';
        $type = '';
            if(empty($request->message_attachment)){
                $message = $request->message;
                $type = 'text';
            }else{
                $type = 'file';
                $image = $request->file('message_attachment');
                $message = $request->message.'-'.rand(0,999).'.'.$image->getClientOriginalExtension(); 
                $destinationPath = public_path('../images/appointment/chat_attachment'); // set folder where to save
                $image->move($destinationPath, $message); //move uploaded file.
            }

        $result = _OnlineChat::sendMessage($request, $message, $type); 
        if($result){
            return response()->json('success'); 
        }else{
            return response()->json('db-error');
        } 
    }

    public function getNewMessage(Request $request){
        $result = _OnlineChat::getNewMessage($request); 
        return response()->json($result);  
    }
    
 
    public function allowProfilePermission(Request $request){
        if(_Validator::verifyAccount($request)){
            if((new _OnlineChat)::allowProfilePermission($request)){
                return response()->json('success');
            }else{
                return response()->json('db-error');
            }
        }else{
            return response()->json('pass-invalid');
        }
    }
}
