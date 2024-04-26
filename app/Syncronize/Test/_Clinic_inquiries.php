<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Clinic_inquiries extends Model
{ 
    public static function clinic_inquiries(){ 
        // syncronize clinic_inquiries table from offline to online  
        $clinic_offline = DB::table('clinic_inquiries')->get();  
        foreach($clinic_offline as $clinic_offline){  
            $clinic_offline_count = DB::connection('mysql2')->table('clinic_inquiries')->where('clinic_id', $clinic_offline->clinic_id)->get();
                if(count($clinic_offline_count) > 0){
                    if($clinic_offline->updated_at > $clinic_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('clinic_inquiries')->where('clinic_id', $clinic_offline->clinic_id)->update([      
                            'clinic_id'=>$clinic_offline->clinic_id,
                            'patient_id'=>$clinic_offline->patient_id,
                            'message'=>$clinic_offline->message,
                            'send_by'=>$clinic_offline->send_by, 
                            'is_read'=>$clinic_offline->is_read,
                            'status'=>$clinic_offline->status,
                            'created_at'=>$clinic_offline->created_at,
                            'updated_at'=>$clinic_offline->updated_at
                        ]);  
                    } 
                    else{
                        DB::table('clinic_inquiries')->where('clinic_id', $clinic_offline_count[0]->clinic_id)->update([  
                            'clinic_id'=>$clinic_offline_count[0]->clinic_id,
                            'patient_id'=>$clinic_offline_count[0]->patient_id,
                            'message'=>$clinic_offline_count[0]->message,
                            'send_by'=>$clinic_offline_count[0]->send_by, 
                            'is_read'=>$clinic_offline_count[0]->is_read,
                            'status'=>$clinic_offline_count[0]->status,
                            'created_at'=>$clinic_offline_count[0]->created_at,
                            'updated_at'=>$clinic_offline_count[0]->updated_at
                        ]);
                    } 
                }
                else{ 
                    DB::connection('mysql2')->table('clinic_inquiries')->insert([
                        'clinic_id'=>$clinic_offline->clinic_id,
                        'patient_id'=>$clinic_offline->patient_id,
                        'message'=>$clinic_offline->message,
                        'send_by'=>$clinic_offline->send_by, 
                        'is_read'=>$clinic_offline->is_read,
                        'status'=>$clinic_offline->status,
                        'created_at'=>$clinic_offline->created_at,
                        'updated_at'=>$clinic_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize clinic_inquiries table from online to offline
        $clinic_online = DB::connection('mysql2')->table('clinic_inquiries')->get();  
        foreach($clinic_online as $clinic_online){  
            $clinic_online_count = DB::table('clinic_inquiries')->where('clinic_id', $clinic_online->clinic_id)->get();
                if(count($clinic_online_count) > 0){
                    DB::table('clinic_inquiries')->where('clinic_id', $clinic_online->clinic_id)->update([  
                        'clinic_id'=>$clinic_online->clinic_id,
                        'patient_id'=>$clinic_online->patient_id,
                        'message'=>$clinic_online->message,
                        'send_by'=>$clinic_online->send_by, 
                        'is_read'=>$clinic_online->is_read,
                        'status'=>$clinic_online->status,
                        'created_at'=>$clinic_online->created_at,
                        'updated_at'=>$clinic_online->updated_at
                    ]); 
                }else{
                    DB::table('clinic_inquiries')->insert([
                        'clinic_id'=>$clinic_online->clinic_id,
                        'patient_id'=>$clinic_online->patient_id,
                        'message'=>$clinic_online->message,
                        'send_by'=>$clinic_online->send_by, 
                        'is_read'=>$clinic_online->is_read,
                        'status'=>$clinic_online->status,
                        'created_at'=>$clinic_online->created_at,
                        'updated_at'=>$clinic_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}