<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Clinic_doctorslist extends Model
{ 
    public  static function clinic_doctorslist(){ 
        // syncronize clinic_doctorslist table from offline to online  
        $clinic_offline = DB::table('clinic_doctorslist')->get();  
        foreach($clinic_offline as $clinic_offline){  
            $clinic_offline_count = DB::connection('mysql2')->table('clinic_doctorslist')->where('clinic_id', $clinic_offline->clinic_id)->get();
                if(count($clinic_offline_count) > 0){
                    if($clinic_offline->updated_at > $clinic_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('clinic_doctorslist')->where('clinic_id', $clinic_offline->clinic_id)->update([      
                            'clinic_id'=>$clinic_offline->clinic_id,
                            'doctor_userid'=>$clinic_offline->doctor_userid,
                            'status'=>$clinic_offline->status,
                            'added_by'=>$clinic_offline->added_by, 
                            'updated_at'=>$clinic_offline->updated_at,
                            'created_at'=>$clinic_offline->created_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('clinic_doctorslist')->where('clinic_id', $clinic_offline_count[0]->clinic_id)->update([  
                            'clinic_id'=>$clinic_offline_count[0]->clinic_id,
                            'doctor_userid'=>$clinic_offline_count[0]->doctor_userid,
                            'status'=>$clinic_offline_count[0]->status,
                            'added_by'=>$clinic_offline_count[0]->added_by, 
                            'updated_at'=>$clinic_offline_count[0]->updated_at,
                            'created_at'=>$clinic_offline_count[0]->created_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('clinic_doctorslist')->insert([
                        'clinic_id'=>$clinic_offline->clinic_id,
                        'doctor_userid'=>$clinic_offline->doctor_userid,
                        'status'=>$clinic_offline->status,
                        'added_by'=>$clinic_offline->added_by, 
                        'updated_at'=>$clinic_offline->updated_at,
                        'created_at'=>$clinic_offline->created_at
                    ]);  
                } 
        } 
     
        // syncronize clinic_doctorslist table from online to offline
        $clinic_online = DB::connection('mysql2')->table('clinic_doctorslist')->get();  
        foreach($clinic_online as $clinic_online){  
            $clinic_online_count = DB::table('clinic_doctorslist')->where('clinic_id', $clinic_online->clinic_id)->get();
                if(count($clinic_online_count) > 0){
                    DB::table('clinic_doctorslist')->where('clinic_id', $clinic_online->clinic_id)->update([  
                        'clinic_id'=>$clinic_online->clinic_id,
                        'doctor_userid'=>$clinic_online->doctor_userid,
                        'status'=>$clinic_online->status,
                        'added_by'=>$clinic_online->added_by, 
                        'updated_at'=>$clinic_online->updated_at,
                        'created_at'=>$clinic_online->created_at
                    ]); 
                }else{
                    DB::table('clinic_doctorslist')->insert([
                        'clinic_id'=>$clinic_online->clinic_id,
                        'doctor_userid'=>$clinic_online->doctor_userid,
                        'status'=>$clinic_online->status,
                        'added_by'=>$clinic_online->added_by, 
                        'updated_at'=>$clinic_online->updated_at,
                        'created_at'=>$clinic_online->created_at
                    ]); 
                } 
        } 
        return true;
    } 
}