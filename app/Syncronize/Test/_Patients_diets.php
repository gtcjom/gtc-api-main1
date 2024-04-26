<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_diets extends Model
{ 
    public static function patients_diets(){ 
        // syncronize patients_diets table from offline to online   
        $patient_offline = DB::table('patients_diets')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_diets')->where('pd_id', $patient_offline->pd_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_diets')->where('pd_id', $patient_offline->pd_id)->update([    
                            'pd_id'=> $patient_offline->pd_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'doctor_id'=>$patient_offline->doctor_id,
                            'meals'=>$patient_offline->meals,
                            'description'=>$patient_offline->description,
                            'is_suggested'=>$patient_offline->is_suggested,
                            'status'=>$patient_offline->status,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_diets')->where('pd_id', $patient_offline_count[0]->pd_id)->update([  
                            'pd_id'=> $patient_offline_count[0]->pd_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'doctor_id'=>$patient_offline_count[0]->doctor_id,
                            'meals'=>$patient_offline_count[0]->meals,
                            'description'=>$patient_offline_count[0]->description,
                            'is_suggested'=>$patient_offline_count[0]->is_suggested,
                            'status'=>$patient_offline_count[0]->status,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_diets')->insert([ 
                        'pd_id'=> $patient_offline->pd_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'doctor_id'=>$patient_offline->doctor_id,
                        'meals'=>$patient_offline->meals,
                        'description'=>$patient_offline->description,
                        'is_suggested'=>$patient_offline->is_suggested,
                        'status'=>$patient_offline->status,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_diets table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_diets')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_diets')->where('pd_id', $patient_online->pd_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_diets')->where('pd_id', $patient_online->pd_id)->update([
                        'pd_id'=> $patient_online->pd_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'doctor_id'=>$patient_online->doctor_id,
                        'meals'=>$patient_online->meals,
                        'description'=>$patient_online->description,
                        'is_suggested'=>$patient_online->is_suggested,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_diets')->insert([    
                        'pd_id'=> $patient_online->pd_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'doctor_id'=>$patient_online->doctor_id,
                        'meals'=>$patient_online->meals,
                        'description'=>$patient_online->description,
                        'is_suggested'=>$patient_online->is_suggested,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}