<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_history_lithium extends Model
{ 
    public static function patients_history_lithium(){ 
        // syncronize patients_history_lithium table from offline to online   
        $patient_offline = DB::table('patients_history_lithium')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_lithium')->where('phl_id', $patient_offline->phl_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_history_lithium')->where('phl_id', $patient_offline->phl_id)->update([    
                            'phl_id'=> $patient_offline->phl_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'lithium'=>$patient_offline->lithium,
                            'added_by'=>$patient_offline->added_by,
                            'adder_type'=>$patient_offline->adder_type,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_history_lithium')->where('phl_id', $patient_offline_count[0]->phl_id)->update([  
                            'phl_id'=> $patient_offline_count[0]->phl_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'lithium'=>$patient_offline_count[0]->lithium,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_history_lithium')->insert([ 
                        'phl_id'=> $patient_offline->phl_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'lithium'=>$patient_offline->lithium,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_history_lithium table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_history_lithium')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_lithium')->where('phl_id', $patient_online->phl_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_lithium')->where('phl_id', $patient_online->phl_id)->update([
                        'phl_id'=> $patient_online->phl_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'lithium'=>$patient_online->lithium,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_history_lithium')->insert([ 
                        'phl_id'=> $patient_online->phl_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'lithium'=>$patient_online->lithium,
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