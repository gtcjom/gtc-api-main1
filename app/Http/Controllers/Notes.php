<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_Notes;
use App\Models\_Validator;

class Notes extends Controller
{
    public function getNotes(Request $request){
        $result = _Notes::getNotes($request);
        return response()->json($result);
    }

    public function newNotes(Request $request){ 
        $result = _Notes::newNotes($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function editNotes(Request $request){ 
        $result = _Notes::editNotes($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }
    
    public function deleteNotes(Request $request){ 
        $result = _Notes::deleteNotes($request);
        if($result){
            return response()->json('success');
        }else{
            return response()->json('db-error');
        } 
    }

    public function newNotesCanvas(Request $request){ 
        if(!_Validator::verifyAccount($request)){ 
            return response()->json('pass-invalid');
        } 

        $destinationPath = public_path('../images/doctor/notes/'); // set folder where to save
        $img = $request->image;
        $img = str_replace('data:image/png;base64,', '', $img); 
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $filename = time().'.jpeg';
        
        $result = (new _Notes)::newNotesCanvas($request, $filename);
        if($result){
            $file = $destinationPath. $filename;
            $success = file_put_contents($file, $data);  
            return response()->json('success');
        }
    }

    public function getCanvasNotesList(Request $request){
        $result = _Notes::getCanvasNotesList($request);
        return response()->json($result);
    }
}
