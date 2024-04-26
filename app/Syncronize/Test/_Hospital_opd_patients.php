<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_opd_patients extends Model
{ 
    public static function hospital_opd_patients(){ 
        // syncronize hospital_opd_patients table from offline to online   
        $hosp_offline = DB::table('hospital_opd_patients')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_opd_patients')->where('hop_id', $hosp_offline->hop_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_opd_patients')->where('hop_id', $hosp_offline->hop_id)->update([    
                            'hop_id'=> $hosp_offline->hop_id, 
                            'opd_id'=>$hosp_offline->opd_id,
                            'patient_id'=>$hosp_offline->patient_id,
                            'case_file'=>$hosp_offline->case_file,
                            'management_id'=>$hosp_offline->management_id,
                            'opd_doctor'=>$hosp_offline->opd_doctor,
                            'is_opd_recieved'=>$hosp_offline->is_opd_recieved,
                            'opd_recieved_on'=>$hosp_offline->opd_recieved_on,
                            'nurse_recieved_on'=>$hosp_offline->nurse_recieved_on,
                            'added_on'=>$hosp_offline->added_on,
                            'added_by'=>$hosp_offline->added_by,
                            'added_reason'=>$hosp_offline->added_reason,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_opd_patients')->where('hop_id', $hosp_offline_count[0]->hop_id)->update([  
                            'hop_id'=> $hosp_offline_count[0]->hop_id, 
                            'opd_id'=>$hosp_offline_count[0]->opd_id,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'case_file'=>$hosp_offline_count[0]->case_file,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'opd_doctor'=>$hosp_offline_count[0]->opd_doctor,
                            'is_opd_recieved'=>$hosp_offline_count[0]->is_opd_recieved,
                            'opd_recieved_on'=>$hosp_offline_count[0]->opd_recieved_on,
                            'nurse_recieved_on'=>$hosp_offline_count[0]->nurse_recieved_on,
                            'added_on'=>$hosp_offline_count[0]->added_on,
                            'added_by'=>$hosp_offline_count[0]->added_by,
                            'added_reason'=>$hosp_offline_count[0]->added_reason,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_opd_patients')->insert([    
                        'hop_id'=> $hosp_offline->hop_id, 
                        'opd_id'=>$hosp_offline->opd_id,
                        'patient_id'=>$hosp_offline->patient_id,
                        'case_file'=>$hosp_offline->case_file,
                        'management_id'=>$hosp_offline->management_id,
                        'opd_doctor'=>$hosp_offline->opd_doctor,
                        'is_opd_recieved'=>$hosp_offline->is_opd_recieved,
                        'opd_recieved_on'=>$hosp_offline->opd_recieved_on,
                        'nurse_recieved_on'=>$hosp_offline->nurse_recieved_on,
                        'added_on'=>$hosp_offline->added_on,
                        'added_by'=>$hosp_offline->added_by,
                        'added_reason'=>$hosp_offline->added_reason,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_opd_patients table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_opd_patients')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_opd_patients')->where('hop_id', $hosp_online->hop_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_opd_patients')->where('hop_id', $hosp_online->hop_id)->update([  
                        'hop_id'=> $hosp_online->hop_id, 
                        'opd_id'=>$hosp_online->opd_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'management_id'=>$hosp_online->management_id,
                        'opd_doctor'=>$hosp_online->opd_doctor,
                        'is_opd_recieved'=>$hosp_online->is_opd_recieved,
                        'opd_recieved_on'=>$hosp_online->opd_recieved_on,
                        'nurse_recieved_on'=>$hosp_online->nurse_recieved_on,
                        'added_on'=>$hosp_online->added_on,
                        'added_by'=>$hosp_online->added_by,
                        'added_reason'=>$hosp_online->added_reason,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_opd_patients')->insert([    
                        'hop_id'=> $hosp_online->hop_id, 
                        'opd_id'=>$hosp_online->opd_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'management_id'=>$hosp_online->management_id,
                        'opd_doctor'=>$hosp_online->opd_doctor,
                        'is_opd_recieved'=>$hosp_online->is_opd_recieved,
                        'opd_recieved_on'=>$hosp_online->opd_recieved_on,
                        'nurse_recieved_on'=>$hosp_online->nurse_recieved_on,
                        'added_on'=>$hosp_online->added_on,
                        'added_by'=>$hosp_online->added_by,
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