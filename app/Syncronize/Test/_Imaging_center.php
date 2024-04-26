<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Imaging_center extends Model
{ 
    public static function imaging_center(){ 
        // syncronize imaging_center table from offline to online   
        $imgng_offline = DB::table('imaging_center')->get();  
        foreach($imgng_offline as $imgng_offline){  
            $imgng_offline_count = DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id', $imgng_offline->imaging_center_id)->get();
                if(count($imgng_offline_count) > 0){ 
                    if($imgng_offline->updated_at > $imgng_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id', $imgng_offline->imaging_center_id)->update([    
                            'imaging_center_id'=> $imgng_offline->imaging_center_id, 
                            'patients_id'=>$imgng_offline->patients_id,
                            'doctors_id'=>$imgng_offline->doctors_id,
                            'ward_nurse_id'=>$imgng_offline->ward_nurse_id,
                            'case_file'=>$imgng_offline->case_file,
                            'radiologist'=>$imgng_offline->radiologist,
                            'radiologist_type'=>$imgng_offline->radiologist_type,
                            'request_ward'=>$imgng_offline->request_ward,
                            'request_doctor'=>$imgng_offline->request_doctor,
                            'charge_slip'=>$imgng_offline->charge_slip,
                            'additional_charge_slip'=>$imgng_offline->additional_charge_slip,
                            'number_shots'=>$imgng_offline->number_shots,
                            'additional_number_shots'=>$imgng_offline->additional_number_shots,
                            'imaging_order'=>$imgng_offline->imaging_order,
                            'imaging_remarks'=>$imgng_offline->imaging_remarks,
                            'imaging_center'=>$imgng_offline->imaging_center,
                            'imaging_result'=>$imgng_offline->imaging_result,
                            'imaging_result_screenshot'=>$imgng_offline->imaging_result_screenshot,
                            'imaging_results_remarks'=>$imgng_offline->imaging_results_remarks,
                            'imaging_result_attachment'=>$imgng_offline->imaging_result_attachment,
                            'stitch_order_request'=>$imgng_offline->stitch_order_request,
                            'stitch_reason_request'=>$imgng_offline->stitch_reason_request,
                            'stitch_result_attachment'=>$imgng_offline->stitch_result_attachment,
                            'is_viewed'=>$imgng_offline->is_viewed,
                            'is_processed'=>$imgng_offline->is_processed,
                            'processed_by'=>$imgng_offline->processed_by,
                            'start_time'=>$imgng_offline->start_time,
                            'end_time'=>$imgng_offline->end_time,
                            'is_pending'=>$imgng_offline->is_pending,
                            'pending_reason'=>$imgng_offline->pending_reason,
                            'pending_date'=>$imgng_offline->pending_date,
                            'pending_by'=>$imgng_offline->pending_by,
                            'manage_by'=>$imgng_offline->manage_by,
                            'created_at'=>$imgng_offline->created_at,
                            'updated_at'=>$imgng_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('imaging_center')->where('imaging_center_id', $imgng_offline_count[0]->imaging_center_id)->update([  
                            'imaging_center_id'=> $imgng_offline_count[0]->imaging_center_id, 
                            'patients_id'=>$imgng_offline_count[0]->patients_id,
                            'doctors_id'=>$imgng_offline_count[0]->doctors_id,
                            'ward_nurse_id'=>$imgng_offline_count[0]->ward_nurse_id,
                            'case_file'=>$imgng_offline_count[0]->case_file,
                            'radiologist'=>$imgng_offline_count[0]->radiologist,
                            'radiologist_type'=>$imgng_offline_count[0]->radiologist_type,
                            'request_ward'=>$imgng_offline_count[0]->request_ward,
                            'request_doctor'=>$imgng_offline_count[0]->request_doctor,
                            'charge_slip'=>$imgng_offline_count[0]->charge_slip,
                            'additional_charge_slip'=>$imgng_offline_count[0]->additional_charge_slip,
                            'number_shots'=>$imgng_offline_count[0]->number_shots,
                            'additional_number_shots'=>$imgng_offline_count[0]->additional_number_shots,
                            'imaging_order'=>$imgng_offline_count[0]->imaging_order,
                            'imaging_remarks'=>$imgng_offline_count[0]->imaging_remarks,
                            'imaging_center'=>$imgng_offline_count[0]->imaging_center,
                            'imaging_result'=>$imgng_offline_count[0]->imaging_result,
                            'imaging_result_screenshot'=>$imgng_offline_count[0]->imaging_result_screenshot,
                            'imaging_results_remarks'=>$imgng_offline_count[0]->imaging_results_remarks,
                            'imaging_result_attachment'=>$imgng_offline_count[0]->imaging_result_attachment,
                            'stitch_order_request'=>$imgng_offline_count[0]->stitch_order_request,
                            'stitch_reason_request'=>$imgng_offline_count[0]->stitch_reason_request,
                            'stitch_result_attachment'=>$imgng_offline_count[0]->stitch_result_attachment,
                            'is_viewed'=>$imgng_offline_count[0]->is_viewed,
                            'is_processed'=>$imgng_offline_count[0]->is_processed,
                            'processed_by'=>$imgng_offline_count[0]->processed_by,
                            'start_time'=>$imgng_offline_count[0]->start_time,
                            'end_time'=>$imgng_offline_count[0]->end_time,
                            'is_pending'=>$imgng_offline_count[0]->is_pending,
                            'pending_reason'=>$imgng_offline_count[0]->pending_reason,
                            'pending_date'=>$imgng_offline_count[0]->pending_date,
                            'pending_by'=>$imgng_offline_count[0]->pending_by,
                            'manage_by'=>$imgng_offline_count[0]->manage_by,
                            'created_at'=>$imgng_offline_count[0]->created_at,
                            'updated_at'=>$imgng_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('imaging_center')->insert([ 
                        'imaging_center_id'=> $imgng_offline->imaging_center_id, 
                        'patients_id'=>$imgng_offline->patients_id,
                        'doctors_id'=>$imgng_offline->doctors_id,
                        'ward_nurse_id'=>$imgng_offline->ward_nurse_id,
                        'case_file'=>$imgng_offline->case_file,
                        'radiologist'=>$imgng_offline->radiologist,
                        'radiologist_type'=>$imgng_offline->radiologist_type,
                        'request_ward'=>$imgng_offline->request_ward,
                        'request_doctor'=>$imgng_offline->request_doctor,
                        'charge_slip'=>$imgng_offline->charge_slip,
                        'additional_charge_slip'=>$imgng_offline->additional_charge_slip,
                        'number_shots'=>$imgng_offline->number_shots,
                        'additional_number_shots'=>$imgng_offline->additional_number_shots,
                        'imaging_order'=>$imgng_offline->imaging_order,
                        'imaging_remarks'=>$imgng_offline->imaging_remarks,
                        'imaging_center'=>$imgng_offline->imaging_center,
                        'imaging_result'=>$imgng_offline->imaging_result,
                        'imaging_result_screenshot'=>$imgng_offline->imaging_result_screenshot,
                        'imaging_results_remarks'=>$imgng_offline->imaging_results_remarks,
                        'imaging_result_attachment'=>$imgng_offline->imaging_result_attachment,
                        'stitch_order_request'=>$imgng_offline->stitch_order_request,
                        'stitch_reason_request'=>$imgng_offline->stitch_reason_request,
                        'stitch_result_attachment'=>$imgng_offline->stitch_result_attachment,
                        'is_viewed'=>$imgng_offline->is_viewed,
                        'is_processed'=>$imgng_offline->is_processed,
                        'processed_by'=>$imgng_offline->processed_by,
                        'start_time'=>$imgng_offline->start_time,
                        'end_time'=>$imgng_offline->end_time,
                        'is_pending'=>$imgng_offline->is_pending,
                        'pending_reason'=>$imgng_offline->pending_reason,
                        'pending_date'=>$imgng_offline->pending_date,
                        'pending_by'=>$imgng_offline->pending_by,
                        'manage_by'=>$imgng_offline->manage_by,
                        'created_at'=>$imgng_offline->created_at,
                        'updated_at'=>$imgng_offline->updated_at
                    ]); 
                } 
        }

        // syncronize imaging_center table from online to offline 
        $imgng_online = DB::connection('mysql2')->table('imaging_center')->get();  
        foreach($imgng_online as $imgng_online){  
            $imgng_online_count = DB::table('imaging_center')->where('imaging_center_id', $imgng_online->imaging_center_id)->get();
                if(count($imgng_online_count) > 0){
                    DB::table('imaging_center')->where('imaging_center_id', $imgng_online->imaging_center_id)->update([  
                        'imaging_center_id'=> $imgng_online->imaging_center_id, 
                        'patients_id'=>$imgng_online->patients_id,
                        'doctors_id'=>$imgng_online->doctors_id,
                        'ward_nurse_id'=>$imgng_online->ward_nurse_id,
                        'case_file'=>$imgng_online->case_file,
                        'radiologist'=>$imgng_online->radiologist,
                        'radiologist_type'=>$imgng_online->radiologist_type,
                        'request_ward'=>$imgng_online->request_ward,
                        'request_doctor'=>$imgng_online->request_doctor,
                        'charge_slip'=>$imgng_online->charge_slip,
                        'additional_charge_slip'=>$imgng_online->additional_charge_slip,
                        'number_shots'=>$imgng_online->number_shots,
                        'additional_number_shots'=>$imgng_online->additional_number_shots,
                        'imaging_order'=>$imgng_online->imaging_order,
                        'imaging_remarks'=>$imgng_online->imaging_remarks,
                        'imaging_center'=>$imgng_online->imaging_center,
                        'imaging_result'=>$imgng_online->imaging_result,
                        'imaging_result_screenshot'=>$imgng_online->imaging_result_screenshot,
                        'imaging_results_remarks'=>$imgng_online->imaging_results_remarks,
                        'imaging_result_attachment'=>$imgng_online->imaging_result_attachment,
                        'stitch_order_request'=>$imgng_online->stitch_order_request,
                        'stitch_reason_request'=>$imgng_online->stitch_reason_request,
                        'stitch_result_attachment'=>$imgng_online->stitch_result_attachment,
                        'is_viewed'=>$imgng_online->is_viewed,
                        'is_processed'=>$imgng_online->is_processed,
                        'processed_by'=>$imgng_online->processed_by,
                        'start_time'=>$imgng_online->start_time,
                        'end_time'=>$imgng_online->end_time,
                        'is_pending'=>$imgng_online->is_pending,
                        'pending_reason'=>$imgng_online->pending_reason,
                        'pending_date'=>$imgng_online->pending_date,
                        'pending_by'=>$imgng_online->pending_by,
                        'manage_by'=>$imgng_online->manage_by,
                        'created_at'=>$imgng_online->created_at,
                        'updated_at'=>$imgng_online->updated_at
                    ]); 
                }else{
                    DB::table('imaging_center')->insert([    
                        'imaging_center_id'=> $imgng_online->imaging_center_id, 
                        'patients_id'=>$imgng_online->patients_id,
                        'doctors_id'=>$imgng_online->doctors_id,
                        'ward_nurse_id'=>$imgng_online->ward_nurse_id,
                        'case_file'=>$imgng_online->case_file,
                        'radiologist'=>$imgng_online->radiologist,
                        'radiologist_type'=>$imgng_online->radiologist_type,
                        'request_ward'=>$imgng_online->request_ward,
                        'request_doctor'=>$imgng_online->request_doctor,
                        'charge_slip'=>$imgng_online->charge_slip,
                        'additional_charge_slip'=>$imgng_online->additional_charge_slip,
                        'number_shots'=>$imgng_online->number_shots,
                        'additional_number_shots'=>$imgng_online->additional_number_shots,
                        'imaging_order'=>$imgng_online->imaging_order,
                        'imaging_remarks'=>$imgng_online->imaging_remarks,
                        'imaging_center'=>$imgng_online->imaging_center,
                        'imaging_result'=>$imgng_online->imaging_result,
                        'imaging_result_screenshot'=>$imgng_online->imaging_result_screenshot,
                        'imaging_results_remarks'=>$imgng_online->imaging_results_remarks,
                        'imaging_result_attachment'=>$imgng_online->imaging_result_attachment,
                        'stitch_order_request'=>$imgng_online->stitch_order_request,
                        'stitch_reason_request'=>$imgng_online->stitch_reason_request,
                        'stitch_result_attachment'=>$imgng_online->stitch_result_attachment,
                        'is_viewed'=>$imgng_online->is_viewed,
                        'is_processed'=>$imgng_online->is_processed,
                        'processed_by'=>$imgng_online->processed_by,
                        'start_time'=>$imgng_online->start_time,
                        'end_time'=>$imgng_online->end_time,
                        'is_pending'=>$imgng_online->is_pending,
                        'pending_reason'=>$imgng_online->pending_reason,
                        'pending_date'=>$imgng_online->pending_date,
                        'pending_by'=>$imgng_online->pending_by,
                        'manage_by'=>$imgng_online->manage_by,
                        'created_at'=>$imgng_online->created_at,
                        'updated_at'=>$imgng_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}