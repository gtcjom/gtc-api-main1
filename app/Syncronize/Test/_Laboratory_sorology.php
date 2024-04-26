<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory_sorology extends Model
{ 
    public static function laboratory_sorology(){ 
        // syncronize laboratory_sorology table from offline to online   
        $lab_offline = DB::table('laboratory_sorology')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory_sorology')->where('ls_id', $lab_offline->ls_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory_sorology')->where('ls_id', $lab_offline->ls_id)->update([    
                            'ls_id'=> $lab_offline->ls_id, 
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
                            'hbsag'=>$lab_offline->hbsag,
                            'hav'=>$lab_offline->hav,
                            'hcv'=>$lab_offline->hcv,
                            'vdrl_rpr'=>$lab_offline->vdrl_rpr,
                            'remarks'=>$lab_offline->remarks,
                            'order_status'=>$lab_offline->order_status,
                            'status'=>$lab_offline->status,
                            'created_at'=>$lab_offline->created_at,
                            'updated_at'=>$lab_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory_sorology')->where('ls_id', $lab_offline_count[0]->ls_id)->update([  
                            'ls_id'=> $lab_offline_count[0]->ls_id, 
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
                            'hbsag'=>$lab_offline_count[0]->hbsag,
                            'hav'=>$lab_offline_count[0]->hav,
                            'hcv'=>$lab_offline_count[0]->hcv,
                            'vdrl_rpr'=>$lab_offline_count[0]->vdrl_rpr,
                            'remarks'=>$lab_offline_count[0]->remarks,
                            'order_status'=>$lab_offline_count[0]->order_status,
                            'status'=>$lab_offline_count[0]->status,
                            'created_at'=>$lab_offline_count[0]->created_at,
                            'updated_at'=>$lab_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory_sorology')->insert([ 
                        'ls_id'=> $lab_offline->ls_id, 
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
                        'hbsag'=>$lab_offline->hbsag,
                        'hav'=>$lab_offline->hav,
                        'hcv'=>$lab_offline->hcv,
                        'vdrl_rpr'=>$lab_offline->vdrl_rpr,
                        'remarks'=>$lab_offline->remarks,
                        'order_status'=>$lab_offline->order_status,
                        'status'=>$lab_offline->status,
                        'created_at'=>$lab_offline->created_at,
                        'updated_at'=>$lab_offline->updated_at
                    ]); 
                } 
        }

        // syncronize laboratory_sorology table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory_sorology')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory_sorology')->where('ls_id', $lab_online->ls_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory_sorology')->where('ls_id', $lab_online->ls_id)->update([  
                        'ls_id'=> $lab_online->ls_id, 
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
                        'hbsag'=>$lab_online->hbsag,
                        'hav'=>$lab_online->hav,
                        'hcv'=>$lab_online->hcv,
                        'vdrl_rpr'=>$lab_online->vdrl_rpr,
                        'remarks'=>$lab_online->remarks,
                        'order_status'=>$lab_online->order_status,
                        'status'=>$lab_online->status,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory_sorology')->insert([    
                        'ls_id'=> $lab_online->ls_id, 
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
                        'hbsag'=>$lab_online->hbsag,
                        'hav'=>$lab_online->hav,
                        'hcv'=>$lab_online->hcv,
                        'vdrl_rpr'=>$lab_online->vdrl_rpr,
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