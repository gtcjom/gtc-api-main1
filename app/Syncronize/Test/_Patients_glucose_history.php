<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_glucose_history extends Model
{ 
    public static function patients_glucose_history(){ 
        // syncronize patients_glucose_history table from offline to online   
        $patient_offline = DB::table('patients_glucose_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_glucose_history')->where('pgh_id', $patient_offline->pgh_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_glucose_history')->where('pgh_id', $patient_offline->pgh_id)->update([    
                            'pgh_id'=> $patient_offline->pgh_id, 
                            'patients_id'=>$patient_offline->patients_id,
                            'glucose'=>$patient_offline->glucose,
                            'added_by'=>$patient_offline->added_by,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_glucose_history')->where('pgh_id', $patient_offline_count[0]->pgh_id)->update([  
                            'pgh_id'=> $patient_offline_count[0]->pgh_id, 
                            'patients_id'=>$patient_offline_count[0]->patients_id,
                            'glucose'=>$patient_offline_count[0]->glucose,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_glucose_history')->insert([ 
                        'pgh_id'=> $patient_offline->pgh_id, 
                        'patients_id'=>$patient_offline->patients_id,
                        'glucose'=>$patient_offline->glucose,
                        'added_by'=>$patient_offline->added_by,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_glucose_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_glucose_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_glucose_history')->where('pgh_id', $patient_online->pgh_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_glucose_history')->where('pgh_id', $patient_online->pgh_id)->update([
                        'pgh_id'=> $patient_online->pgh_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'glucose'=>$patient_online->glucose,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_glucose_history')->insert([    
                        'pgh_id'=> $patient_online->pgh_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'glucose'=>$patient_online->glucose,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}