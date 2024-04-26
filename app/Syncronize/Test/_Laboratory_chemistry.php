<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory_chemistry extends Model
{ 
    public static function laboratory_chemistry(){ 
        // syncronize laboratory_chemistry table from offline to online   
        $lab_offline = DB::table('laboratory_chemistry')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory_chemistry')->where('lc_id', $lab_offline->lc_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory_chemistry')->where('lc_id', $lab_offline->lc_id)->update([    
                            'lc_id'=> $lab_offline->lc_id, 
                            'order_id'=>$lab_offline->order_id,
                            'doctor_id'=>$lab_offline->doctor_id,
                            'patient_id'=>$lab_offline->patient_id,
                            'laboratory_id'=>$lab_offline->laboratory_id,
                            'ward_nurse_id'=>$lab_offline->ward_nurse_id,
                            'case_file'=>$lab_offline->case_file,
                            'is_viewed'=>$lab_offline->is_viewed,
                            'is_processed'=>$lab_offline->is_processed,
                            'is_processed_by'=>$lab_offline->is_processed_by,
                            'is_processed_time_start'=>$lab_offline->is_processed_time_start,
                            'is_processed_time_end'=>$lab_offline->is_processed_time_end,
                            'is_pending'=>$lab_offline->is_pending,
                            'is_pending_reason'=>$lab_offline->is_pending_reason,
                            'is_pending_date'=>$lab_offline->is_pending_date,
                            'is_pending_by'=>$lab_offline->is_pending_by,
                            'spicemen'=>$lab_offline->spicemen,
                            'glucose'=>$lab_offline->glucose,
                            'creatinine'=>$lab_offline->creatinine,
                            'uric_acid'=>$lab_offline->uric_acid,
                            'cholesterol'=>$lab_offline->cholesterol,
                            'triglyceride'=>$lab_offline->triglyceride,
                            'hdl_cholesterol'=>$lab_offline->hdl_cholesterol,
                            'ldl_cholesterol'=>$lab_offline->ldl_cholesterol,
                            'sgot'=>$lab_offline->sgot,
                            'sgpt'=>$lab_offline->sgpt,
                            'remarks'=>$lab_offline->remarks,
                            'order_status'=>$lab_offline->order_status,
                            'status'=>$lab_offline->status,
                            'created_at'=>$lab_offline->created_at,
                            'updated_at'=>$lab_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory_chemistry')->where('lc_id', $lab_offline_count[0]->lc_id)->update([  
                            'lc_id'=> $lab_offline_count[0]->lc_id, 
                            'order_id'=>$lab_offline_count[0]->order_id,
                            'doctor_id'=>$lab_offline_count[0]->doctor_id,
                            'patient_id'=>$lab_offline_count[0]->patient_id,
                            'laboratory_id'=>$lab_offline_count[0]->laboratory_id,
                            'ward_nurse_id'=>$lab_offline_count[0]->ward_nurse_id,
                            'case_file'=>$lab_offline_count[0]->case_file,
                            'is_viewed'=>$lab_offline_count[0]->is_viewed,
                            'is_processed'=>$lab_offline_count[0]->is_processed,
                            'is_processed_by'=>$lab_offline_count[0]->is_processed_by,
                            'is_processed_time_start'=>$lab_offline_count[0]->is_processed_time_start,
                            'is_processed_time_end'=>$lab_offline_count[0]->is_processed_time_end,
                            'is_pending'=>$lab_offline_count[0]->is_pending,
                            'is_pending_reason'=>$lab_offline_count[0]->is_pending_reason,
                            'is_pending_date'=>$lab_offline_count[0]->is_pending_date,
                            'is_pending_by'=>$lab_offline_count[0]->is_pending_by,
                            'spicemen'=>$lab_offline_count[0]->spicemen,
                            'glucose'=>$lab_offline_count[0]->glucose,
                            'creatinine'=>$lab_offline_count[0]->creatinine,
                            'uric_acid'=>$lab_offline_count[0]->uric_acid,
                            'cholesterol'=>$lab_offline_count[0]->cholesterol,
                            'triglyceride'=>$lab_offline_count[0]->triglyceride,
                            'hdl_cholesterol'=>$lab_offline_count[0]->hdl_cholesterol,
                            'ldl_cholesterol'=>$lab_offline_count[0]->ldl_cholesterol,
                            'sgot'=>$lab_offline_count[0]->sgot,
                            'sgpt'=>$lab_offline_count[0]->sgpt,
                            'remarks'=>$lab_offline_count[0]->remarks,
                            'order_status'=>$lab_offline_count[0]->order_status,
                            'status'=>$lab_offline_count[0]->status,
                            'created_at'=>$lab_offline_count[0]->created_at,
                            'updated_at'=>$lab_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory_chemistry')->insert([ 
                        'lc_id'=> $lab_offline->lc_id, 
                        'order_id'=>$lab_offline->order_id,
                        'doctor_id'=>$lab_offline->doctor_id,
                        'patient_id'=>$lab_offline->patient_id,
                        'laboratory_id'=>$lab_offline->laboratory_id,
                        'ward_nurse_id'=>$lab_offline->ward_nurse_id,
                        'case_file'=>$lab_offline->case_file,
                        'is_viewed'=>$lab_offline->is_viewed,
                        'is_processed'=>$lab_offline->is_processed,
                        'is_processed_by'=>$lab_offline->is_processed_by,
                        'is_processed_time_start'=>$lab_offline->is_processed_time_start,
                        'is_processed_time_end'=>$lab_offline->is_processed_time_end,
                        'is_pending'=>$lab_offline->is_pending,
                        'is_pending_reason'=>$lab_offline->is_pending_reason,
                        'is_pending_date'=>$lab_offline->is_pending_date,
                        'is_pending_by'=>$lab_offline->is_pending_by,
                        'spicemen'=>$lab_offline->spicemen,
                        'glucose'=>$lab_offline->glucose,
                        'creatinine'=>$lab_offline->creatinine,
                        'uric_acid'=>$lab_offline->uric_acid,
                        'cholesterol'=>$lab_offline->cholesterol,
                        'triglyceride'=>$lab_offline->triglyceride,
                        'hdl_cholesterol'=>$lab_offline->hdl_cholesterol,
                        'ldl_cholesterol'=>$lab_offline->ldl_cholesterol,
                        'sgot'=>$lab_offline->sgot,
                        'sgpt'=>$lab_offline->sgpt,
                        'remarks'=>$lab_offline->remarks,
                        'order_status'=>$lab_offline->order_status,
                        'status'=>$lab_offline->status,
                        'created_at'=>$lab_offline->created_at,
                        'updated_at'=>$lab_offline->updated_at
                    ]); 
                } 
        }

        // syncronize laboratory_chemistry table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory_chemistry')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory_chemistry')->where('lc_id', $lab_online->lc_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory_chemistry')->where('lc_id', $lab_online->lc_id)->update([  
                        'lc_id'=> $lab_online->lc_id, 
                        'order_id'=>$lab_online->order_id,
                        'doctor_id'=>$lab_online->doctor_id,
                        'patient_id'=>$lab_online->patient_id,
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'ward_nurse_id'=>$lab_online->ward_nurse_id,
                        'case_file'=>$lab_online->case_file,
                        'is_viewed'=>$lab_online->is_viewed,
                        'is_processed'=>$lab_online->is_processed,
                        'is_processed_by'=>$lab_online->is_processed_by,
                        'is_processed_time_start'=>$lab_online->is_processed_time_start,
                        'is_processed_time_end'=>$lab_online->is_processed_time_end,
                        'is_pending'=>$lab_online->is_pending,
                        'is_pending_reason'=>$lab_online->is_pending_reason,
                        'is_pending_date'=>$lab_online->is_pending_date,
                        'is_pending_by'=>$lab_online->is_pending_by,
                        'spicemen'=>$lab_online->spicemen,
                        'glucose'=>$lab_online->glucose,
                        'creatinine'=>$lab_online->creatinine,
                        'uric_acid'=>$lab_online->uric_acid,
                        'cholesterol'=>$lab_online->cholesterol,
                        'triglyceride'=>$lab_online->triglyceride,
                        'hdl_cholesterol'=>$lab_online->hdl_cholesterol,
                        'ldl_cholesterol'=>$lab_online->ldl_cholesterol,
                        'sgot'=>$lab_online->sgot,
                        'sgpt'=>$lab_online->sgpt,
                        'remarks'=>$lab_online->remarks,
                        'order_status'=>$lab_online->order_status,
                        'status'=>$lab_online->status,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory_chemistry')->insert([    
                        'lc_id'=> $lab_online->lc_id, 
                        'order_id'=>$lab_online->order_id,
                        'doctor_id'=>$lab_online->doctor_id,
                        'patient_id'=>$lab_online->patient_id,
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'ward_nurse_id'=>$lab_online->ward_nurse_id,
                        'case_file'=>$lab_online->case_file,
                        'is_viewed'=>$lab_online->is_viewed,
                        'is_processed'=>$lab_online->is_processed,
                        'is_processed_by'=>$lab_online->is_processed_by,
                        'is_processed_time_start'=>$lab_online->is_processed_time_start,
                        'is_processed_time_end'=>$lab_online->is_processed_time_end,
                        'is_pending'=>$lab_online->is_pending,
                        'is_pending_reason'=>$lab_online->is_pending_reason,
                        'is_pending_date'=>$lab_online->is_pending_date,
                        'is_pending_by'=>$lab_online->is_pending_by,
                        'spicemen'=>$lab_online->spicemen,
                        'glucose'=>$lab_online->glucose,
                        'creatinine'=>$lab_online->creatinine,
                        'uric_acid'=>$lab_online->uric_acid,
                        'cholesterol'=>$lab_online->cholesterol,
                        'triglyceride'=>$lab_online->triglyceride,
                        'hdl_cholesterol'=>$lab_online->hdl_cholesterol,
                        'ldl_cholesterol'=>$lab_online->ldl_cholesterol,
                        'sgot'=>$lab_online->sgot,
                        'sgpt'=>$lab_online->sgpt,
                        'remarks'=>$lab_online->remarks,
                        'order_status'=>$lab_online->order_status,
                        'status'=>$lab_online->status,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}