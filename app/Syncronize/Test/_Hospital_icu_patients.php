<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_icu_patients extends Model
{ 
    public static function hospital_icu_patients(){ 
        // syncronize hospital_icu_patients table from offline to online   
        $hosp_offline = DB::table('hospital_icu_patients')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_icu_patients')->where('hip_id', $hosp_offline->hip_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_icu_patients')->where('hip_id', $hosp_offline->hip_id)->update([    
                            'hip_id'=> $hosp_offline->hip_id, 
                            'icu_id'=>$hosp_offline->icu_id,
                            'management_id'=>$hosp_offline->management_id,
                            'patient_id'=>$hosp_offline->patient_id,
                            'case_file'=>$hosp_offline->case_file,
                            'is_nod_received'=>$hosp_offline->is_nod_received,
                            'is_nod_received_on'=>$hosp_offline->is_nod_received_on,
                            'added_by'=>$hosp_offline->added_by,
                            'adder_type'=>$hosp_offline->adder_type,
                            'added_on'=>$hosp_offline->added_on,
                            'added_reason'=>$hosp_offline->added_reason,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_icu_patients')->where('hip_id', $hosp_offline_count[0]->hip_id)->update([  
                            'hip_id'=> $hosp_offline_count[0]->hip_id, 
                            'icu_id'=>$hosp_offline_count[0]->icu_id,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'case_file'=>$hosp_offline_count[0]->case_file,
                            'is_nod_received'=>$hosp_offline_count[0]->is_nod_received,
                            'is_nod_received_on'=>$hosp_offline_count[0]->is_nod_received_on,
                            'added_by'=>$hosp_offline_count[0]->added_by,
                            'adder_type'=>$hosp_offline_count[0]->adder_type,
                            'added_on'=>$hosp_offline_count[0]->added_on,
                            'added_reason'=>$hosp_offline_count[0]->added_reason,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_icu_patients')->insert([    
                        'hip_id'=> $hosp_offline->hip_id, 
                        'icu_id'=>$hosp_offline->icu_id,
                        'management_id'=>$hosp_offline->management_id,
                        'patient_id'=>$hosp_offline->patient_id,
                        'case_file'=>$hosp_offline->case_file,
                        'is_nod_received'=>$hosp_offline->is_nod_received,
                        'is_nod_received_on'=>$hosp_offline->is_nod_received_on,
                        'added_by'=>$hosp_offline->added_by,
                        'adder_type'=>$hosp_offline->adder_type,
                        'added_on'=>$hosp_offline->added_on,
                        'added_reason'=>$hosp_offline->added_reason,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_icu_patients table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_icu_patients')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_icu_patients')->where('hip_id', $hosp_online->hip_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_icu_patients')->where('hip_id', $hosp_online->hip_id)->update([  
                        'hip_id'=> $hosp_online->hip_id, 
                        'icu_id'=>$hosp_online->icu_id,
                        'management_id'=>$hosp_online->management_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'is_nod_received'=>$hosp_online->is_nod_received,
                        'is_nod_received_on'=>$hosp_online->is_nod_received_on,
                        'added_by'=>$hosp_online->added_by,
                        'adder_type'=>$hosp_online->adder_type,
                        'added_on'=>$hosp_online->added_on,
                        'added_reason'=>$hosp_online->added_reason,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_icu_patients')->insert([    
                        'hip_id'=> $hosp_online->hip_id, 
                        'icu_id'=>$hosp_online->icu_id,
                        'management_id'=>$hosp_online->management_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'is_nod_received'=>$hosp_online->is_nod_received,
                        'is_nod_received_on'=>$hosp_online->is_nod_received_on,
                        'added_by'=>$hosp_online->added_by,
                        'adder_type'=>$hosp_online->adder_type,
                        'added_on'=>$hosp_online->added_on,
                        'added_reason'=>$hosp_online->added_reason,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}