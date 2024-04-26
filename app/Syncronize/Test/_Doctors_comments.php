<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_comments extends Model
{ 
    public static function doctors_comments(){ 
        // syncronize doctors_comments table from online to offline 
        $doctor_offline = DB::table('doctors_comments')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_comments')->where('dc_id', $doctor_offline->dc_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_comments')->where('dc_id', $doctor_offline->dc_id)->update([ 
                            'dc_id'=>$doctor_offline->dc_id, 
                            'doctors_id'=>$doctor_offline->doctors_id, 
                            'patient_id'=>$doctor_offline->patient_id, 
                            'comment'=>$doctor_offline->comment, 
                            'comment_status'=>$doctor_offline->comment_status, 
                            'status'=>$doctor_offline->status, 
                            'created_at'=>$doctor_offline->created_at,
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('doctors_comments')->where('dc_id', $doctor_offline_count[0]->dc_id)->update([ 
                            'dc_id'=>$doctor_offline_count[0]->dc_id, 
                            'doctors_id'=>$doctor_offline_count[0]->doctors_id, 
                            'patient_id'=>$doctor_offline_count[0]->patient_id, 
                            'comment'=>$doctor_offline_count[0]->comment, 
                            'comment_status'=>$doctor_offline_count[0]->comment_status, 
                            'status'=>$doctor_offline_count[0]->status, 
                            'created_at'=>$doctor_offline_count[0]->created_at,
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
     
                }else{
                    DB::connection('mysql2')->table('doctors_comments')->insert([  
                        'dc_id'=>$doctor_offline->dc_id, 
                        'doctors_id'=>$doctor_offline->doctors_id, 
                        'patient_id'=>$doctor_offline->patient_id, 
                        'comment'=>$doctor_offline->comment, 
                        'comment_status'=>$doctor_offline->comment_status, 
                        'status'=>$doctor_offline->status, 
                        'created_at'=>$doctor_offline->created_at,
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_comments table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_comments')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_comments')->where('dc_id', $doctor_online->dc_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_comments')->where('dc_id', $doctor_online->dc_id)->update([ 
                        'dc_id'=>$doctor_online->dc_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'patient_id'=>$doctor_online->patient_id, 
                        'comment'=>$doctor_online->comment, 
                        'comment_status'=>$doctor_online->comment_status, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]);
                }else{
                    DB::table('doctors_comments')->insert([  
                        'dc_id'=>$doctor_online->dc_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'patient_id'=>$doctor_online->patient_id, 
                        'comment'=>$doctor_online->comment, 
                        'comment_status'=>$doctor_online->comment_status, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}