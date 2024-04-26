<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\_TreatmentPlan;
use App\Models\_Validator;

class TreatmentPlan extends Controller
{
    public function getTreatmentPlan(Request $request){
        return (new _TreatmentPlan)::getTreatmentPlan($request);
    }

    public function saveTreatmentPlan(Request $request){
        if((new _TreatmentPlan)::saveTreatmentPlan($request)){
            return response()->json('success');
        }
    }

    public function updateTreatmentPlan(Request $request){ 
        if(!_Validator::verifyAccount($request)){ 
            return response()->json('pass-invalid');
        } 
        if((new _TreatmentPlan)::updateTreatmentPlan($request)){
            return response()->json('success');
        } 
    }

    public function deleteTreatmentPlan(Request $request){ 
        if(!_Validator::verifyAccount($request)){ 
            return response()->json('pass-invalid');
        } 
        if((new _TreatmentPlan)::deleteTreatmentPlan($request)){
            return response()->json('success');
        } 
    }

    public function canvasTreatmentPlan(Request $request){ 
        if(!_Validator::verifyAccount($request)){ 
            return response()->json('pass-invalid');
        } 

        $destinationPath = public_path('../images/doctor/treatmentplan/'); // set folder where to save
        $img = $request->image;
        $img = str_replace('data:image/png;base64,', '', $img); 
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $filename = time().'.jpeg';
        
        $result = (new _TreatmentPlan)::canvasTreatmentPlan($request, $filename);
        if($result){
            $file = $destinationPath. $filename;
            $success = file_put_contents($file, $data);  
            return response()->json('success');
        }
    }

    // public function canvasTreatmentPlan(Request $request){ 
    //     if(!_Validator::verifyAccount($request)){ 
    //         return response()->json('pass-invalid');
    //     } 

    //     $destinationPath = public_path('../images/doctors/treatmentplan/');  
    //     $filename = time().'.jpeg'; 
    //     $file = $destinationPath. $filename;    
    //     $data = $request->image;
    //     $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
    //     $success = file_put_contents($file, $data);   
        
    //     $result = (new _TreatmentPlan)::canvasTreatmentPlan($request, $filename);
    //     if($result){ 
    //         return response()->json('success');
    //     }
    // }
}
