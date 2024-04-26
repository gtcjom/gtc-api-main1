<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory_fecal_analysis extends Model
{ 
    public static function laboratory_fecal_analysis(){ 
        // syncronize laboratory_fecal_analysis table from offline to online   
        $lab_offline = DB::table('laboratory_fecal_analysis')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory_fecal_analysis')->where('lfa_id', $lab_offline->lfa_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory_fecal_analysis')->where('lfa_id', $lab_offline->lfa_id)->update([    
                            'lfa_id'=> $lab_offline->lfa_id, 
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
                            'fecal_analysis'=>$lab_offline->fecal_analysis,
                            'cellular_elements_color'=>$lab_offline->cellular_elements_color,
                            'cellular_elements_consistency'=>$lab_offline->cellular_elements_consistency,
                            'cellular_elements_pus'=>$lab_offline->cellular_elements_pus,
                            'cellular_elements_rbc'=>$lab_offline->cellular_elements_rbc,
                            'cellular_elements_fat_globules'=>$lab_offline->cellular_elements_fat_globules,
                            'cellular_elements_occultblood'=>$lab_offline->cellular_elements_occultblood,
                            'cellular_elements_bacteria'=>$lab_offline->cellular_elements_bacteria,
                            'cellular_elements_result'=>$lab_offline->cellular_elements_result,
                            'remarks'=>$lab_offline->remarks,
                            'order_status'=>$lab_offline->order_status,
                            'status'=>$lab_offline->status,
                            'created_at'=>$lab_offline->created_at,
                            'updated_at'=>$lab_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory_fecal_analysis')->where('lfa_id', $lab_offline_count[0]->lfa_id)->update([  
                            'lfa_id'=> $lab_offline_count[0]->lfa_id, 
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
                            'fecal_analysis'=>$lab_offline_count[0]->fecal_analysis,
                            'cellular_elements_color'=>$lab_offline_count[0]->cellular_elements_color,
                            'cellular_elements_consistency'=>$lab_offline_count[0]->cellular_elements_consistency,
                            'cellular_elements_pus'=>$lab_offline_count[0]->cellular_elements_pus,
                            'cellular_elements_rbc'=>$lab_offline_count[0]->cellular_elements_rbc,
                            'cellular_elements_fat_globules'=>$lab_offline_count[0]->cellular_elements_fat_globules,
                            'cellular_elements_occultblood'=>$lab_offline_count[0]->cellular_elements_occultblood,
                            'cellular_elements_bacteria'=>$lab_offline_count[0]->cellular_elements_bacteria,
                            'cellular_elements_result'=>$lab_offline_count[0]->cellular_elements_result,
                            'remarks'=>$lab_offline_count[0]->remarks,
                            'order_status'=>$lab_offline_count[0]->order_status,
                            'status'=>$lab_offline_count[0]->status,
                            'created_at'=>$lab_offline_count[0]->created_at,
                            'updated_at'=>$lab_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory_fecal_analysis')->insert([ 
                        'lfa_id'=> $lab_offline->lfa_id, 
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
                        'fecal_analysis'=>$lab_offline->fecal_analysis,
                        'cellular_elements_color'=>$lab_offline->cellular_elements_color,
                        'cellular_elements_consistency'=>$lab_offline->cellular_elements_consistency,
                        'cellular_elements_pus'=>$lab_offline->cellular_elements_pus,
                        'cellular_elements_rbc'=>$lab_offline->cellular_elements_rbc,
                        'cellular_elements_fat_globules'=>$lab_offline->cellular_elements_fat_globules,
                        'cellular_elements_occultblood'=>$lab_offline->cellular_elements_occultblood,
                        'cellular_elements_bacteria'=>$lab_offline->cellular_elements_bacteria,
                        'cellular_elements_result'=>$lab_offline->cellular_elements_result,
                        'remarks'=>$lab_offline->remarks,
                        'order_status'=>$lab_offline->order_status,
                        'status'=>$lab_offline->status,
                        'created_at'=>$lab_offline->created_at,
                        'updated_at'=>$lab_offline->updated_at
                    ]); 
                } 
        }

        // syncronize laboratory_fecal_analysis table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory_fecal_analysis')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory_fecal_analysis')->where('lfa_id', $lab_online->lfa_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory_fecal_analysis')->where('lfa_id', $lab_online->lfa_id)->update([  
                        'lfa_id'=> $lab_online->lfa_id, 
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
                        'fecal_analysis'=>$lab_online->fecal_analysis,
                        'cellular_elements_color'=>$lab_online->cellular_elements_color,
                        'cellular_elements_consistency'=>$lab_online->cellular_elements_consistency,
                        'cellular_elements_pus'=>$lab_online->cellular_elements_pus,
                        'cellular_elements_rbc'=>$lab_online->cellular_elements_rbc,
                        'cellular_elements_fat_globules'=>$lab_online->cellular_elements_fat_globules,
                        'cellular_elements_occultblood'=>$lab_online->cellular_elements_occultblood,
                        'cellular_elements_bacteria'=>$lab_online->cellular_elements_bacteria,
                        'cellular_elements_result'=>$lab_online->cellular_elements_result,
                        'remarks'=>$lab_online->remarks,
                        'order_status'=>$lab_online->order_status,
                        'status'=>$lab_online->status,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory_fecal_analysis')->insert([    
                        'lfa_id'=> $lab_online->lfa_id, 
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
                        'fecal_analysis'=>$lab_online->fecal_analysis,
                        'cellular_elements_color'=>$lab_online->cellular_elements_color,
                        'cellular_elements_consistency'=>$lab_online->cellular_elements_consistency,
                        'cellular_elements_pus'=>$lab_online->cellular_elements_pus,
                        'cellular_elements_rbc'=>$lab_online->cellular_elements_rbc,
                        'cellular_elements_fat_globules'=>$lab_online->cellular_elements_fat_globules,
                        'cellular_elements_occultblood'=>$lab_online->cellular_elements_occultblood,
                        'cellular_elements_bacteria'=>$lab_online->cellular_elements_bacteria,
                        'cellular_elements_result'=>$lab_online->cellular_elements_result,
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