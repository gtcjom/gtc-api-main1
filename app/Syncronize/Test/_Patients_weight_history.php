<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_weight_history extends Model
{ 
    public static function patients_weight_history(){ 
        // syncronize patients_weight_history table from offline to online   
        $patient_offline = DB::table('patients_weight_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_weight_history')->where('pwh_id', $patient_offline->pwh_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_weight_history')->where('pwh_id', $patient_offline->pwh_id)->update([    
                            'pwh_id'=> $patient_offline->pwh_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'weight'=>$patient_offline->weight,
                            'added_by'=>$patient_offline->added_by,
                            'status'=>$patient_offline->status,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_weight_history')->where('pwh_id', $patient_offline_count[0]->pwh_id)->update([  
                            'pwh_id'=> $patient_offline_count[0]->pwh_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'weight'=>$patient_offline_count[0]->weight,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'status'=>$patient_offline_count[0]->status,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_weight_history')->insert([ 
                        'pwh_id'=> $patient_offline->pwh_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'weight'=>$patient_offline->weight,
                        'added_by'=>$patient_offline->added_by,
                        'status'=>$patient_offline->status,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_weight_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_weight_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_weight_history')->where('pwh_id', $patient_online->pwh_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_weight_history')->where('pwh_id', $patient_online->pwh_id)->update([
                        'pwh_id'=> $patient_online->pwh_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'weight'=>$patient_online->weight,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_weight_history')->insert([ 
                        'pwh_id'=> $patient_online->pwh_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'weight'=>$patient_online->weight,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}