<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_notes_canvas extends Model
{ 
    public static function doctors_notes_canvas(){ 
        // syncronize doctors_notes_canvas table from online to offline 
        $doctor_offline = DB::table('doctors_notes_canvas')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_notes_canvas')->where('dnc_id', $doctor_offline->dnc_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_notes_canvas')->where('dnc_id', $doctor_offline->dnc_id)->update([ 
                            'dnc_id'=>$doctor_offline->dnc_id, 
                            'patient_id'=>$doctor_offline->patient_id, 
                            'doctors_id'=>$doctor_offline->doctors_id, 
                            'canvas'=>$doctor_offline->canvas, 
                            'status'=>$doctor_offline->status, 
                            'updated_at'=>$doctor_offline->updated_at, 
                            'created_at'=>$doctor_offline->created_at
                        ]);
                    } 
                    
                    else{
                        DB::table('doctors_notes_canvas')->where('dnc_id', $doctor_offline_count[0]->dnc_id)->update([ 
                            'dnc_id'=>$doctor_offline_count[0]->dnc_id, 
                            'patient_id'=>$doctor_offline_count[0]->patient_id, 
                            'doctors_id'=>$doctor_offline_count[0]->doctors_id, 
                            'canvas'=>$doctor_offline_count[0]->canvas, 
                            'status'=>$doctor_offline_count[0]->status, 
                            'updated_at'=>$doctor_offline_count[0]->updated_at, 
                            'created_at'=>$doctor_offline_count[0]->created_at
                        ]);
                    }
     
                }else{
                    DB::connection('mysql2')->table('doctors_notes_canvas')->insert([  
                        'dnc_id'=>$doctor_offline->dnc_id, 
                        'patient_id'=>$doctor_offline->patient_id, 
                        'doctors_id'=>$doctor_offline->doctors_id, 
                        'canvas'=>$doctor_offline->canvas, 
                        'status'=>$doctor_offline->status, 
                        'updated_at'=>$doctor_offline->updated_at, 
                        'created_at'=>$doctor_offline->created_at
                    ]); 
                } 
        } 

        // syncronize doctors_notes_canvas table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_notes_canvas')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_notes_canvas')->where('dnc_id', $doctor_online->dnc_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_notes_canvas')->where('dnc_id', $doctor_online->dnc_id)->update([ 
                        'dnc_id'=>$doctor_online->dnc_id, 
                        'patient_id'=>$doctor_online->patient_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'canvas'=>$doctor_online->canvas, 
                        'status'=>$doctor_online->status, 
                        'updated_at'=>$doctor_online->updated_at, 
                        'created_at'=>$doctor_online->created_at
                    ]);
                }else{
                    DB::table('doctors_notes_canvas')->insert([  
                        'dnc_id'=>$doctor_online->dnc_id, 
                        'patient_id'=>$doctor_online->patient_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'canvas'=>$doctor_online->canvas, 
                        'status'=>$doctor_online->status, 
                        'updated_at'=>$doctor_online->updated_at, 
                        'created_at'=>$doctor_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}