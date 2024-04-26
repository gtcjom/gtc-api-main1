<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_cholesterol_history extends Model
{ 
    public static function patients_cholesterol_history(){ 
        // syncronize patients_cholesterol_history table from offline to online   
        $patient_offline = DB::table('patients_cholesterol_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_cholesterol_history')->where('cholesterol_id', $patient_offline->cholesterol_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_cholesterol_history')->where('cholesterol_id', $patient_offline->cholesterol_id)->update([    
                            'cholesterol_id'=> $patient_offline->cholesterol_id, 
                            'patients_id'=>$patient_offline->patients_id,
                            'cholesterol'=>$patient_offline->cholesterol,
                            'added_by'=>$patient_offline->added_by,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_cholesterol_history')->where('cholesterol_id', $patient_offline_count[0]->cholesterol_id)->update([  
                            'cholesterol_id'=> $patient_offline_count[0]->cholesterol_id, 
                            'patients_id'=>$patient_offline_count[0]->patients_id,
                            'cholesterol'=>$patient_offline_count[0]->cholesterol,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_cholesterol_history')->insert([ 
                        'cholesterol_id'=> $patient_offline->cholesterol_id, 
                        'patients_id'=>$patient_offline->patients_id,
                        'cholesterol'=>$patient_offline->cholesterol,
                        'added_by'=>$patient_offline->added_by,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_cholesterol_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_cholesterol_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_cholesterol_history')->where('cholesterol_id', $patient_online->cholesterol_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_cholesterol_history')->where('cholesterol_id', $patient_online->cholesterol_id)->update([  
                        'cholesterol_id'=> $patient_online->cholesterol_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'cholesterol'=>$patient_online->cholesterol,
                        'added_by'=>$patient_online->added_by,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_cholesterol_history')->insert([    
                        'cholesterol_id'=> $patient_online->cholesterol_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'cholesterol'=>$patient_online->cholesterol,
                        'added_by'=>$patient_online->added_by,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}