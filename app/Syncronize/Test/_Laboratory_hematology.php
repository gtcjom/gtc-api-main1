<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory_hematology extends Model
{ 
    public static function laboratory_hematology(){ 
        // syncronize laboratory_hematology table from offline to online   
        $lab_offline = DB::table('laboratory_hematology')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory_hematology')->where('lh_id', $lab_offline->lh_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory_hematology')->where('lh_id', $lab_offline->lh_id)->update([    
                            'lh_id'=> $lab_offline->lh_id, 
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
                            'hemoglobin'=>$lab_offline->hemoglobin,
                            'hematocrit'=>$lab_offline->hematocrit,
                            'rbc'=>$lab_offline->rbc,
                            'wbc'=>$lab_offline->wbc,
                            'platelet_count'=>$lab_offline->platelet_count,
                            'differential_count'=>$lab_offline->differential_count,
                            'neutrophil'=>$lab_offline->neutrophil,
                            'lymphocyte'=>$lab_offline->lymphocyte,
                            'monocyte'=>$lab_offline->monocyte,
                            'eosinophil'=>$lab_offline->eosinophil,
                            'basophil'=>$lab_offline->basophil,
                            'bands'=>$lab_offline->bands,
                            'abo_blood_type_and_rh_type'=>$lab_offline->abo_blood_type_and_rh_type,
                            'bleeding_time'=>$lab_offline->bleeding_time,
                            'clotting_time'=>$lab_offline->clotting_time,
                            'pathologist'=>$lab_offline->pathologist,
                            'medical_technologist'=>$lab_offline->medical_technologist,
                            'remarks'=>$lab_offline->remarks,
                            'order_status'=>$lab_offline->order_status,
                            'status'=>$lab_offline->status,
                            'created_at'=>$lab_offline->created_at,
                            'updated_at'=>$lab_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory_hematology')->where('lh_id', $lab_offline_count[0]->lh_id)->update([  
                            'lh_id'=> $lab_offline_count[0]->lh_id, 
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
                            'hemoglobin'=>$lab_offline_count[0]->hemoglobin,
                            'hematocrit'=>$lab_offline_count[0]->hematocrit,
                            'rbc'=>$lab_offline_count[0]->rbc,
                            'wbc'=>$lab_offline_count[0]->wbc,
                            'platelet_count'=>$lab_offline_count[0]->platelet_count,
                            'differential_count'=>$lab_offline_count[0]->differential_count,
                            'neutrophil'=>$lab_offline_count[0]->neutrophil,
                            'lymphocyte'=>$lab_offline_count[0]->lymphocyte,
                            'monocyte'=>$lab_offline_count[0]->monocyte,
                            'eosinophil'=>$lab_offline_count[0]->eosinophil,
                            'basophil'=>$lab_offline_count[0]->basophil,
                            'bands'=>$lab_offline_count[0]->bands,
                            'abo_blood_type_and_rh_type'=>$lab_offline_count[0]->abo_blood_type_and_rh_type,
                            'bleeding_time'=>$lab_offline_count[0]->bleeding_time,
                            'clotting_time'=>$lab_offline_count[0]->clotting_time,
                            'pathologist'=>$lab_offline_count[0]->pathologist,
                            'medical_technologist'=>$lab_offline_count[0]->medical_technologist,
                            'remarks'=>$lab_offline_count[0]->remarks,
                            'order_status'=>$lab_offline_count[0]->order_status,
                            'status'=>$lab_offline_count[0]->status,
                            'created_at'=>$lab_offline_count[0]->created_at,
                            'updated_at'=>$lab_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory_hematology')->insert([ 
                        'lh_id'=> $lab_offline->lh_id, 
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
                        'hemoglobin'=>$lab_offline->hemoglobin,
                        'hematocrit'=>$lab_offline->hematocrit,
                        'rbc'=>$lab_offline->rbc,
                        'wbc'=>$lab_offline->wbc,
                        'platelet_count'=>$lab_offline->platelet_count,
                        'differential_count'=>$lab_offline->differential_count,
                        'neutrophil'=>$lab_offline->neutrophil,
                        'lymphocyte'=>$lab_offline->lymphocyte,
                        'monocyte'=>$lab_offline->monocyte,
                        'eosinophil'=>$lab_offline->eosinophil,
                        'basophil'=>$lab_offline->basophil,
                        'bands'=>$lab_offline->bands,
                        'abo_blood_type_and_rh_type'=>$lab_offline->abo_blood_type_and_rh_type,
                        'bleeding_time'=>$lab_offline->bleeding_time,
                        'clotting_time'=>$lab_offline->clotting_time,
                        'pathologist'=>$lab_offline->pathologist,
                        'medical_technologist'=>$lab_offline->medical_technologist,
                        'remarks'=>$lab_offline->remarks,
                        'order_status'=>$lab_offline->order_status,
                        'status'=>$lab_offline->status,
                        'created_at'=>$lab_offline->created_at,
                        'updated_at'=>$lab_offline->updated_at
                    ]); 
                } 
        }

        // syncronize laboratory_hematology table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory_hematology')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory_hematology')->where('lh_id', $lab_online->lh_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory_hematology')->where('lh_id', $lab_online->lh_id)->update([  
                        'lh_id'=> $lab_online->lh_id, 
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
                        'hemoglobin'=>$lab_online->hemoglobin,
                        'hematocrit'=>$lab_online->hematocrit,
                        'rbc'=>$lab_online->rbc,
                        'wbc'=>$lab_online->wbc,
                        'platelet_count'=>$lab_online->platelet_count,
                        'differential_count'=>$lab_online->differential_count,
                        'neutrophil'=>$lab_online->neutrophil,
                        'lymphocyte'=>$lab_online->lymphocyte,
                        'monocyte'=>$lab_online->monocyte,
                        'eosinophil'=>$lab_online->eosinophil,
                        'basophil'=>$lab_online->basophil,
                        'bands'=>$lab_online->bands,
                        'abo_blood_type_and_rh_type'=>$lab_online->abo_blood_type_and_rh_type,
                        'bleeding_time'=>$lab_online->bleeding_time,
                        'clotting_time'=>$lab_online->clotting_time,
                        'pathologist'=>$lab_online->pathologist,
                        'medical_technologist'=>$lab_online->medical_technologist,
                        'remarks'=>$lab_online->remarks,
                        'order_status'=>$lab_online->order_status,
                        'status'=>$lab_online->status,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory_hematology')->insert([    
                        'lh_id'=> $lab_online->lh_id, 
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
                        'hemoglobin'=>$lab_online->hemoglobin,
                        'hematocrit'=>$lab_online->hematocrit,
                        'rbc'=>$lab_online->rbc,
                        'wbc'=>$lab_online->wbc,
                        'platelet_count'=>$lab_online->platelet_count,
                        'differential_count'=>$lab_online->differential_count,
                        'neutrophil'=>$lab_online->neutrophil,
                        'lymphocyte'=>$lab_online->lymphocyte,
                        'monocyte'=>$lab_online->monocyte,
                        'eosinophil'=>$lab_online->eosinophil,
                        'basophil'=>$lab_online->basophil,
                        'bands'=>$lab_online->bands,
                        'abo_blood_type_and_rh_type'=>$lab_online->abo_blood_type_and_rh_type,
                        'bleeding_time'=>$lab_online->bleeding_time,
                        'clotting_time'=>$lab_online->clotting_time,
                        'pathologist'=>$lab_online->pathologist,
                        'medical_technologist'=>$lab_online->medical_technologist,
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