<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_patients extends Model
{ 
    public static function doctors_patients(){ 
        // syncronize doctors_patients table from online to offline 
        $doctor_offline = DB::table('doctors_patients')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_patients')->where('dp_id', $doctor_offline->dp_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_patients')->where('dp_id', $doctor_offline->dp_id)->update([ 
                            'dp_id'=>$doctor_offline->dp_id, 
                            'doctors_userid'=>$doctor_offline->doctors_userid, 
                            'patient_userid'=>$doctor_offline->patient_userid, 
                            'added_by'=>$doctor_offline->added_by, 
                            'status'=>$doctor_offline->status, 
                            'created_at'=>$doctor_offline->created_at, 
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('doctors_patients')->where('dp_id', $doctor_offline_count[0]->dp_id)->update([ 
                            'dp_id'=>$doctor_offline_count[0]->dp_id, 
                            'doctors_userid'=>$doctor_offline_count[0]->doctors_userid, 
                            'patient_userid'=>$doctor_offline_count[0]->patient_userid, 
                            'added_by'=>$doctor_offline_count[0]->added_by, 
                            'status'=>$doctor_offline_count[0]->status, 
                            'created_at'=>$doctor_offline_count[0]->created_at, 
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('doctors_patients')->insert([  
                        'dp_id'=>$doctor_offline->dp_id, 
                        'doctors_userid'=>$doctor_offline->doctors_userid, 
                        'patient_userid'=>$doctor_offline->patient_userid, 
                        'added_by'=>$doctor_offline->added_by, 
                        'status'=>$doctor_offline->status, 
                        'created_at'=>$doctor_offline->created_at, 
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_patients table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_patients')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_patients')->where('dp_id', $doctor_online->dp_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_patients')->where('dp_id', $doctor_online->dp_id)->update([ 
                        'dp_id'=>$doctor_online->dp_id, 
                        'doctors_userid'=>$doctor_online->doctors_userid, 
                        'patient_userid'=>$doctor_online->patient_userid, 
                        'added_by'=>$doctor_online->added_by, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at
                    ]);
                }else{
                    DB::table('doctors_patients')->insert([  
                        'dp_id'=>$doctor_online->dp_id, 
                        'doctors_userid'=>$doctor_online->doctors_userid, 
                        'patient_userid'=>$doctor_online->patient_userid, 
                        'added_by'=>$doctor_online->added_by, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}