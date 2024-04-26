<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_history_magnessium extends Model
{ 
    public static function patients_history_magnessium(){ 
        // syncronize patients_history_magnessium table from offline to online   
        $patient_offline = DB::table('patients_history_magnessium')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_magnessium')->where('phm_id', $patient_offline->phm_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_history_magnessium')->where('phm_id', $patient_offline->phm_id)->update([    
                            'phm_id'=> $patient_offline->phm_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'magnessium'=>$patient_offline->magnessium,
                            'added_by'=>$patient_offline->added_by,
                            'adder_type'=>$patient_offline->adder_type,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_history_magnessium')->where('phm_id', $patient_offline_count[0]->phm_id)->update([  
                            'phm_id'=> $patient_offline_count[0]->phm_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'magnessium'=>$patient_offline_count[0]->magnessium,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_history_magnessium')->insert([ 
                        'phm_id'=> $patient_offline->phm_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'magnessium'=>$patient_offline->magnessium,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_history_magnessium table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_history_magnessium')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_magnessium')->where('phm_id', $patient_online->phm_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_magnessium')->where('phm_id', $patient_online->phm_id)->update([
                        'phm_id'=> $patient_online->phm_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'magnessium'=>$patient_online->magnessium,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_history_magnessium')->insert([ 
                        'phm_id'=> $patient_online->phm_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'magnessium'=>$patient_online->magnessium,
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