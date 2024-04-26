<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_history_potassium extends Model
{ 
    public  static function patients_history_potassium(){ 
        // syncronize patients_history_potassium table from offline to online   
        $patient_offline = DB::table('patients_history_potassium')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_potassium')->where('php_id', $patient_offline->php_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_history_potassium')->where('php_id', $patient_offline->php_id)->update([    
                            'php_id'=> $patient_offline->php_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'potassium'=>$patient_offline->potassium,
                            'added_by'=>$patient_offline->added_by,
                            'adder_type'=>$patient_offline->adder_type,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_history_potassium')->where('php_id', $patient_offline_count[0]->php_id)->update([  
                            'php_id'=> $patient_offline_count[0]->php_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'potassium'=>$patient_offline_count[0]->potassium,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_history_potassium')->insert([ 
                        'php_id'=> $patient_offline->php_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'potassium'=>$patient_offline->potassium,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_history_potassium table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_history_potassium')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_potassium')->where('php_id', $patient_online->php_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_potassium')->where('php_id', $patient_online->php_id)->update([
                        'php_id'=> $patient_online->php_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'potassium'=>$patient_online->potassium,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_history_potassium')->insert([ 
                        'php_id'=> $patient_online->php_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'potassium'=>$patient_online->potassium,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}