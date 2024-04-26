<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_diagnosis extends Model
{ 
    public static function patients_diagnosis(){ 
        // syncronize patients_diagnosis table from offline to online   
        $patient_offline = DB::table('patients_diagnosis')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_diagnosis')->where('pd_id', $patient_offline->pd_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_diagnosis')->where('pd_id', $patient_offline->pd_id)->update([    
                            'pd_id'=> $patient_offline->pd_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'doctor_id'=>$patient_offline->doctor_id,
                            'diagnosis'=>$patient_offline->diagnosis,
                            'remarks'=>$patient_offline->remarks,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_diagnosis')->where('pd_id', $patient_offline_count[0]->pd_id)->update([  
                            'pd_id'=> $patient_offline_count[0]->pd_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'doctor_id'=>$patient_offline_count[0]->doctor_id,
                            'diagnosis'=>$patient_offline_count[0]->diagnosis,
                            'remarks'=>$patient_offline_count[0]->remarks,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_diagnosis')->insert([ 
                        'pd_id'=> $patient_offline->pd_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'doctor_id'=>$patient_offline->doctor_id,
                        'diagnosis'=>$patient_offline->diagnosis,
                        'remarks'=>$patient_offline->remarks,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_diagnosis table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_diagnosis')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_diagnosis')->where('pd_id', $patient_online->pd_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_diagnosis')->where('pd_id', $patient_online->pd_id)->update([
                        'pd_id'=> $patient_online->pd_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'doctor_id'=>$patient_online->doctor_id,
                        'diagnosis'=>$patient_online->diagnosis,
                        'remarks'=>$patient_online->remarks,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_diagnosis')->insert([    
                        'pd_id'=> $patient_online->pd_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'doctor_id'=>$patient_online->doctor_id,
                        'diagnosis'=>$patient_online->diagnosis,
                        'remarks'=>$patient_online->remarks,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}