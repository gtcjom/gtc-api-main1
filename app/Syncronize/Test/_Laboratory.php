<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory extends Model
{ 
    public static function laboratory(){ 
        // syncronize laboratory table from offline to online   
        $lab_offline = DB::table('laboratory')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory')->where('lab_id', $lab_offline->lab_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory')->where('lab_id', $lab_offline->lab_id)->update([    
                            'lab_id'=> $lab_offline->lab_id, 
                            'laboratory_id'=>$lab_offline->laboratory_id,
                            'patients_id'=>$lab_offline->patients_id,
                            'doctors_id'=>$lab_offline->doctors_id,
                            'ward_nurse_id'=>$lab_offline->ward_nurse_id,
                            'case_file'=>$lab_offline->case_file,
                            'doctors_remarks'=>$lab_offline->doctors_remarks,
                            'laboratory_orders'=>$lab_offline->laboratory_orders,
                            'laboratory_results'=>$lab_offline->laboratory_results,
                            'laboratory_result_image'=>$lab_offline->laboratory_result_image,
                            'laboratory_remarks'=>$lab_offline->laboratory_remarks,
                            'laboratory_attachment'=>$lab_offline->laboratory_attachment,
                            'is_viewed'=>$lab_offline->is_viewed,
                            'is_processed'=>$lab_offline->is_processed,
                            'processed_by'=>$lab_offline->processed_by,
                            'start_time'=>$lab_offline->start_time,
                            'time_end'=>$lab_offline->time_end,
                            'is_pending'=>$lab_offline->is_pending,
                            'pending_reason'=>$lab_offline->pending_reason,
                            'pending_date'=>$lab_offline->pending_date,
                            'pending_by'=>$lab_offline->pending_by,
                            'created_at'=>$lab_offline->created_at,
                            'updated_at'=>$lab_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory')->where('lab_id', $lab_offline_count[0]->lab_id)->update([  
                            'lab_id'=> $lab_offline_count[0]->lab_id, 
                            'laboratory_id'=>$lab_offline_count[0]->laboratory_id,
                            'patients_id'=>$lab_offline_count[0]->patients_id,
                            'doctors_id'=>$lab_offline_count[0]->doctors_id,
                            'ward_nurse_id'=>$lab_offline_count[0]->ward_nurse_id,
                            'case_file'=>$lab_offline_count[0]->case_file,
                            'doctors_remarks'=>$lab_offline_count[0]->doctors_remarks,
                            'laboratory_orders'=>$lab_offline_count[0]->laboratory_orders,
                            'laboratory_results'=>$lab_offline_count[0]->laboratory_results,
                            'laboratory_result_image'=>$lab_offline_count[0]->laboratory_result_image,
                            'laboratory_remarks'=>$lab_offline_count[0]->laboratory_remarks,
                            'laboratory_attachment'=>$lab_offline_count[0]->laboratory_attachment,
                            'is_viewed'=>$lab_offline_count[0]->is_viewed,
                            'is_processed'=>$lab_offline_count[0]->is_processed,
                            'processed_by'=>$lab_offline_count[0]->processed_by,
                            'start_time'=>$lab_offline_count[0]->start_time,
                            'time_end'=>$lab_offline_count[0]->time_end,
                            'is_pending'=>$lab_offline_count[0]->is_pending,
                            'pending_reason'=>$lab_offline_count[0]->pending_reason,
                            'pending_date'=>$lab_offline_count[0]->pending_date,
                            'pending_by'=>$lab_offline_count[0]->pending_by,
                            'created_at'=>$lab_offline_count[0]->created_at,
                            'updated_at'=>$lab_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory')->insert([ 
                        'lab_id'=> $lab_offline->lab_id, 
                        'laboratory_id'=>$lab_offline->laboratory_id,
                        'patients_id'=>$lab_offline->patients_id,
                        'doctors_id'=>$lab_offline->doctors_id,
                        'ward_nurse_id'=>$lab_offline->ward_nurse_id,
                        'case_file'=>$lab_offline->case_file,
                        'doctors_remarks'=>$lab_offline->doctors_remarks,
                        'laboratory_orders'=>$lab_offline->laboratory_orders,
                        'laboratory_results'=>$lab_offline->laboratory_results,
                        'laboratory_result_image'=>$lab_offline->laboratory_result_image,
                        'laboratory_remarks'=>$lab_offline->laboratory_remarks,
                        'laboratory_attachment'=>$lab_offline->laboratory_attachment,
                        'is_viewed'=>$lab_offline->is_viewed,
                        'is_processed'=>$lab_offline->is_processed,
                        'processed_by'=>$lab_offline->processed_by,
                        'start_time'=>$lab_offline->start_time,
                        'time_end'=>$lab_offline->time_end,
                        'is_pending'=>$lab_offline->is_pending,
                        'pending_reason'=>$lab_offline->pending_reason,
                        'pending_date'=>$lab_offline->pending_date,
                        'pending_by'=>$lab_offline->pending_by,
                        'created_at'=>$lab_offline->created_at,
                        'updated_at'=>$lab_offline->updated_at
                    ]); 
                } 
        }

        // syncronize laboratory table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory')->where('lab_id', $lab_online->lab_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory')->where('lab_id', $lab_online->lab_id)->update([  
                        'lab_id'=> $lab_online->lab_id, 
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'patients_id'=>$lab_online->patients_id,
                        'doctors_id'=>$lab_online->doctors_id,
                        'ward_nurse_id'=>$lab_online->ward_nurse_id,
                        'case_file'=>$lab_online->case_file,
                        'doctors_remarks'=>$lab_online->doctors_remarks,
                        'laboratory_orders'=>$lab_online->laboratory_orders,
                        'laboratory_results'=>$lab_online->laboratory_results,
                        'laboratory_result_image'=>$lab_online->laboratory_result_image,
                        'laboratory_remarks'=>$lab_online->laboratory_remarks,
                        'laboratory_attachment'=>$lab_online->laboratory_attachment,
                        'is_viewed'=>$lab_online->is_viewed,
                        'is_processed'=>$lab_online->is_processed,
                        'processed_by'=>$lab_online->processed_by,
                        'start_time'=>$lab_online->start_time,
                        'time_end'=>$lab_online->time_end,
                        'is_pending'=>$lab_online->is_pending,
                        'pending_reason'=>$lab_online->pending_reason,
                        'pending_date'=>$lab_online->pending_date,
                        'pending_by'=>$lab_online->pending_by,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory')->insert([    
                        'lab_id'=> $lab_online->lab_id, 
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'patients_id'=>$lab_online->patients_id,
                        'doctors_id'=>$lab_online->doctors_id,
                        'ward_nurse_id'=>$lab_online->ward_nurse_id,
                        'case_file'=>$lab_online->case_file,
                        'doctors_remarks'=>$lab_online->doctors_remarks,
                        'laboratory_orders'=>$lab_online->laboratory_orders,
                        'laboratory_results'=>$lab_online->laboratory_results,
                        'laboratory_result_image'=>$lab_online->laboratory_result_image,
                        'laboratory_remarks'=>$lab_online->laboratory_remarks,
                        'laboratory_attachment'=>$lab_online->laboratory_attachment,
                        'is_viewed'=>$lab_online->is_viewed,
                        'is_processed'=>$lab_online->is_processed,
                        'processed_by'=>$lab_online->processed_by,
                        'start_time'=>$lab_online->start_time,
                        'time_end'=>$lab_online->time_end,
                        'is_pending'=>$lab_online->is_pending,
                        'pending_reason'=>$lab_online->pending_reason,
                        'pending_date'=>$lab_online->pending_date,
                        'pending_by'=>$lab_online->pending_by,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}