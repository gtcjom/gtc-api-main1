<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_history_hdl extends Model
{ 
    public static function patients_history_hdl(){ 
        // syncronize patients_history_hdl table from offline to online   
        $patient_offline = DB::table('patients_history_hdl')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_hdl')->where('phh_id', $patient_offline->phh_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_history_hdl')->where('phh_id', $patient_offline->phh_id)->update([    
                            'phh_id'=> $patient_offline->phh_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'high_density_lipoproteins'=>$patient_offline->high_density_lipoproteins,
                            'added_by'=>$patient_offline->added_by,
                            'adder_type'=>$patient_offline->adder_type,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_history_hdl')->where('phh_id', $patient_offline_count[0]->phh_id)->update([  
                            'phh_id'=> $patient_offline_count[0]->phh_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'high_density_lipoproteins'=>$patient_offline_count[0]->high_density_lipoproteins,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_history_hdl')->insert([ 
                        'phh_id'=> $patient_offline->phh_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'high_density_lipoproteins'=>$patient_offline->high_density_lipoproteins,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_history_hdl table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_history_hdl')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_hdl')->where('phh_id', $patient_online->phh_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_hdl')->where('phh_id', $patient_online->phh_id)->update([
                        'phh_id'=> $patient_online->phh_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'high_density_lipoproteins'=>$patient_online->high_density_lipoproteins,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_history_hdl')->insert([ 
                        'phh_id'=> $patient_online->phh_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'high_density_lipoproteins'=>$patient_online->high_density_lipoproteins,
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