<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_treatment_plan extends Model
{ 
    public static function doctors_treatment_plan(){ 
        // syncronize doctors_treatment_plan table from online to offline 
        $doctor_offline = DB::table('doctors_treatment_plan')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_treatment_plan')->where('dtp_id', $doctor_offline->dtp_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_treatment_plan')->where('dtp_id', $doctor_offline->dtp_id)->update([ 
                            'dtp_id'=>$doctor_offline->dtp_id, 
                            'treatment_id'=>$doctor_offline->treatment_id, 
                            'management_id'=>$doctor_offline->management_id, 
                            'doctors_id'=>$doctor_offline->doctors_id, 
                            'patient_id'=>$doctor_offline->patient_id,
                            'treatment_plan'=>$doctor_offline->treatment_plan,
                            'date'=>$doctor_offline->date,
                            'type'=>$doctor_offline->type,
                            'status'=>$doctor_offline->status,
                            'created_at'=>$doctor_offline->created_at,
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('doctors_treatment_plan')->where('dtp_id', $doctor_offline_count[0]->dtp_id)->update([ 
                            'dtp_id'=>$doctor_offline_count[0]->dtp_id, 
                            'treatment_id'=>$doctor_offline_count[0]->treatment_id, 
                            'management_id'=>$doctor_offline_count[0]->management_id, 
                            'doctors_id'=>$doctor_offline_count[0]->doctors_id, 
                            'patient_id'=>$doctor_offline_count[0]->patient_id,
                            'treatment_plan'=>$doctor_offline_count[0]->treatment_plan,
                            'date'=>$doctor_offline_count[0]->date,
                            'type'=>$doctor_offline_count[0]->type,
                            'status'=>$doctor_offline_count[0]->status,
                            'created_at'=>$doctor_offline_count[0]->created_at,
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('doctors_treatment_plan')->insert([  
                        'dtp_id'=>$doctor_offline->dtp_id, 
                        'treatment_id'=>$doctor_offline->treatment_id, 
                        'management_id'=>$doctor_offline->management_id, 
                        'doctors_id'=>$doctor_offline->doctors_id, 
                        'patient_id'=>$doctor_offline->patient_id,
                        'treatment_plan'=>$doctor_offline->treatment_plan,
                        'date'=>$doctor_offline->date,
                        'type'=>$doctor_offline->type,
                        'status'=>$doctor_offline->status,
                        'created_at'=>$doctor_offline->created_at,
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_treatment_plan table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_treatment_plan')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_treatment_plan')->where('dtp_id', $doctor_online->dtp_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_treatment_plan')->where('dtp_id', $doctor_online->dtp_id)->update([ 
                        'dtp_id'=>$doctor_online->dtp_id, 
                        'treatment_id'=>$doctor_online->treatment_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'patient_id'=>$doctor_online->patient_id,
                        'treatment_plan'=>$doctor_online->treatment_plan,
                        'date'=>$doctor_online->date,
                        'type'=>$doctor_online->type,
                        'status'=>$doctor_online->status,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]);
                }else{
                    DB::table('doctors_treatment_plan')->insert([  
                        'dtp_id'=>$doctor_online->dtp_id, 
                        'treatment_id'=>$doctor_online->treatment_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'patient_id'=>$doctor_online->patient_id,
                        'treatment_plan'=>$doctor_online->treatment_plan,
                        'date'=>$doctor_online->date,
                        'type'=>$doctor_online->type,
                        'status'=>$doctor_online->status,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}