<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_pain_history extends Model
{ 
    public static function patients_pain_history(){ 
        // syncronize patients_pain_history table from offline to online   
        $patient_offline = DB::table('patients_pain_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_pain_history')->where('pph_id', $patient_offline->pph_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_pain_history')->where('pph_id', $patient_offline->pph_id)->update([    
                            'pph_id'=> $patient_offline->pph_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'pain_position_x'=>$patient_offline->pain_position_x,
                            'pain_position_y'=>$patient_offline->pain_position_y,
                            'pain_level'=>$patient_offline->pain_level,
                            'description'=>$patient_offline->description,
                            'facing'=>$patient_offline->facing,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_pain_history')->where('pph_id', $patient_offline_count[0]->pph_id)->update([  
                            'pph_id'=> $patient_offline_count[0]->pph_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'pain_position_x'=>$patient_offline_count[0]->pain_position_x,
                            'pain_position_y'=>$patient_offline_count[0]->pain_position_y,
                            'pain_level'=>$patient_offline_count[0]->pain_level,
                            'description'=>$patient_offline_count[0]->description,
                            'facing'=>$patient_offline_count[0]->facing,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_pain_history')->insert([ 
                        'pph_id'=> $patient_offline->pph_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'pain_position_x'=>$patient_offline->pain_position_x,
                        'pain_position_y'=>$patient_offline->pain_position_y,
                        'pain_level'=>$patient_offline->pain_level,
                        'description'=>$patient_offline->description,
                        'facing'=>$patient_offline->facing,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_pain_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_pain_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_pain_history')->where('pph_id', $patient_online->pph_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_pain_history')->where('pph_id', $patient_online->pph_id)->update([
                        'pph_id'=> $patient_online->pph_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'pain_position_x'=>$patient_online->pain_position_x,
                        'pain_position_y'=>$patient_online->pain_position_y,
                        'pain_level'=>$patient_online->pain_level,
                        'description'=>$patient_online->description,
                        'facing'=>$patient_online->facing,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_pain_history')->insert([ 
                        'pph_id'=> $patient_online->pph_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'pain_position_x'=>$patient_online->pain_position_x,
                        'pain_position_y'=>$patient_online->pain_position_y,
                        'pain_level'=>$patient_online->pain_level,
                        'description'=>$patient_online->description,
                        'facing'=>$patient_online->facing,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}