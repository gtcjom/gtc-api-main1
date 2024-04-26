<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Virtual_appointment extends Model
{ 
    public static function virtual_appointment(){
        // syncronize virtual_appointment table from offline to online
        $virtual_offline = DB::table('virtual_appointment')->get();  
        foreach($virtual_offline as $virtual_offline){  
            $virtual_offline_count = DB::connection('mysql2')->table('virtual_appointment')->where('appointment_id', $virtual_offline->appointment_id)->get();
                if(count($virtual_offline_count) > 0){ 
                    if($virtual_offline->updated_at > $virtual_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_appointment')->where('appointment_id', $virtual_offline->appointment_id)->update([    
                            'appointment_id'=> $virtual_offline->appointment_id,
                            'reference_no'=>$virtual_offline->reference_no,
                            'doctors_id'=>$virtual_offline->doctors_id,
                            'patient_id'=>$virtual_offline->patient_id,
                            'doctors_service_id'=>$virtual_offline->doctors_service_id,
                            'doctors_service'=>$virtual_offline->doctors_service,
                            'doctors_service_amount'=>$virtual_offline->doctors_service_amount,
                            'appointment_date'=>$virtual_offline->appointment_date,
                            'appointment_reason'=>$virtual_offline->appointment_reason,
                            'attachment'=>$virtual_offline->attachment,
                            'appointment_status'=>$virtual_offline->appointment_status,
                            'consumed_time'=>$virtual_offline->consumed_time,
                            'appointment_done_on'=>$virtual_offline->appointment_done_on,
                            'process_done_by'=>$virtual_offline->process_done_by,
                            'is_process'=>$virtual_offline->is_process,
                            'is_process_on'=>$virtual_offline->is_process_on,
                            'process_message'=>$virtual_offline->process_message,
                            'is_reschedule'=>$virtual_offline->is_reschedule,
                            'is_reschedule_date'=>$virtual_offline->is_reschedule_date,
                            'is_reschedule_reason'=>$virtual_offline->is_reschedule_reason,
                            'status'=>$virtual_offline->status,
                            'updated_at'=>$virtual_offline->updated_at,
                            'created_at'=>$virtual_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_appointment')->where('appointment_id', $virtual_offline_count[0]->appointment_id)->update([  
                            'appointment_id'=> $virtual_offline_count[0]->appointment_id,
                            'reference_no'=>$virtual_offline_count[0]->reference_no,
                            'doctors_id'=>$virtual_offline_count[0]->doctors_id,
                            'patient_id'=>$virtual_offline_count[0]->patient_id,
                            'doctors_service_id'=>$virtual_offline_count[0]->doctors_service_id,
                            'doctors_service'=>$virtual_offline_count[0]->doctors_service,
                            'doctors_service_amount'=>$virtual_offline_count[0]->doctors_service_amount,
                            'appointment_date'=>$virtual_offline_count[0]->appointment_date,
                            'appointment_reason'=>$virtual_offline_count[0]->appointment_reason,
                            'attachment'=>$virtual_offline_count[0]->attachment,
                            'appointment_status'=>$virtual_offline_count[0]->appointment_status,
                            'consumed_time'=>$virtual_offline_count[0]->consumed_time,
                            'appointment_done_on'=>$virtual_offline_count[0]->appointment_done_on,
                            'process_done_by'=>$virtual_offline_count[0]->process_done_by,
                            'is_process'=>$virtual_offline_count[0]->is_process,
                            'is_process_on'=>$virtual_offline_count[0]->is_process_on,
                            'process_message'=>$virtual_offline_count[0]->process_message,
                            'is_reschedule'=>$virtual_offline_count[0]->is_reschedule,
                            'is_reschedule_date'=>$virtual_offline_count[0]->is_reschedule_date,
                            'is_reschedule_reason'=>$virtual_offline_count[0]->is_reschedule_reason,
                            'status'=>$virtual_offline_count[0]->status,
                            'updated_at'=>$virtual_offline_count[0]->updated_at,
                            'created_at'=>$virtual_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_appointment')->insert([
                        'appointment_id'=> $virtual_offline->appointment_id,
                        'reference_no'=>$virtual_offline->reference_no,
                        'doctors_id'=>$virtual_offline->doctors_id,
                        'patient_id'=>$virtual_offline->patient_id,
                        'doctors_service_id'=>$virtual_offline->doctors_service_id,
                        'doctors_service'=>$virtual_offline->doctors_service,
                        'doctors_service_amount'=>$virtual_offline->doctors_service_amount,
                        'appointment_date'=>$virtual_offline->appointment_date,
                        'appointment_reason'=>$virtual_offline->appointment_reason,
                        'attachment'=>$virtual_offline->attachment,
                        'appointment_status'=>$virtual_offline->appointment_status,
                        'consumed_time'=>$virtual_offline->consumed_time,
                        'appointment_done_on'=>$virtual_offline->appointment_done_on,
                        'process_done_by'=>$virtual_offline->process_done_by,
                        'is_process'=>$virtual_offline->is_process,
                        'is_process_on'=>$virtual_offline->is_process_on,
                        'process_message'=>$virtual_offline->process_message,
                        'is_reschedule'=>$virtual_offline->is_reschedule,
                        'is_reschedule_date'=>$virtual_offline->is_reschedule_date,
                        'is_reschedule_reason'=>$virtual_offline->is_reschedule_reason,
                        'status'=>$virtual_offline->status,
                        'updated_at'=>$virtual_offline->updated_at,
                        'created_at'=>$virtual_offline->created_at
                    ]); 
                } 
        }

        // syncronize virtual_appointment table from online to offline 
        $virtual_online = DB::connection('mysql2')->table('virtual_appointment')->get();
        foreach($virtual_online as $virtual_online){  
            $virtual_online_count = DB::table('virtual_appointment')->where('appointment_id', $virtual_online->appointment_id)->get();
                if(count($virtual_online_count) > 0){
                    DB::table('virtual_appointment')->where('appointment_id', $virtual_online->appointment_id)->update([
                        'appointment_id'=> $virtual_online->appointment_id,
                        'reference_no'=>$virtual_online->reference_no,
                        'doctors_id'=>$virtual_online->doctors_id,
                        'patient_id'=>$virtual_online->patient_id,
                        'doctors_service_id'=>$virtual_online->doctors_service_id,
                        'doctors_service'=>$virtual_online->doctors_service,
                        'doctors_service_amount'=>$virtual_online->doctors_service_amount,
                        'appointment_date'=>$virtual_online->appointment_date,
                        'appointment_reason'=>$virtual_online->appointment_reason,
                        'attachment'=>$virtual_online->attachment,
                        'appointment_status'=>$virtual_online->appointment_status,
                        'consumed_time'=>$virtual_online->consumed_time,
                        'appointment_done_on'=>$virtual_online->appointment_done_on,
                        'process_done_by'=>$virtual_online->process_done_by,
                        'is_process'=>$virtual_online->is_process,
                        'is_process_on'=>$virtual_online->is_process_on,
                        'process_message'=>$virtual_online->process_message,
                        'is_reschedule'=>$virtual_online->is_reschedule,
                        'is_reschedule_date'=>$virtual_online->is_reschedule_date,
                        'is_reschedule_reason'=>$virtual_online->is_reschedule_reason,
                        'status'=>$virtual_online->status,
                        'updated_at'=>$virtual_online->updated_at,
                        'created_at'=>$virtual_online->created_at
                    ]); 
                }else{
                    DB::table('virtual_appointment')->insert([ 
                        'appointment_id'=> $virtual_online->appointment_id,
                        'reference_no'=>$virtual_online->reference_no,
                        'doctors_id'=>$virtual_online->doctors_id,
                        'patient_id'=>$virtual_online->patient_id,
                        'doctors_service_id'=>$virtual_online->doctors_service_id,
                        'doctors_service'=>$virtual_online->doctors_service,
                        'doctors_service_amount'=>$virtual_online->doctors_service_amount,
                        'appointment_date'=>$virtual_online->appointment_date,
                        'appointment_reason'=>$virtual_online->appointment_reason,
                        'attachment'=>$virtual_online->attachment,
                        'appointment_status'=>$virtual_online->appointment_status,
                        'consumed_time'=>$virtual_online->consumed_time,
                        'appointment_done_on'=>$virtual_online->appointment_done_on,
                        'process_done_by'=>$virtual_online->process_done_by,
                        'is_process'=>$virtual_online->is_process,
                        'is_process_on'=>$virtual_online->is_process_on,
                        'process_message'=>$virtual_online->process_message,
                        'is_reschedule'=>$virtual_online->is_reschedule,
                        'is_reschedule_date'=>$virtual_online->is_reschedule_date,
                        'is_reschedule_reason'=>$virtual_online->is_reschedule_reason,
                        'status'=>$virtual_online->status,
                        'updated_at'=>$virtual_online->updated_at,
                        'created_at'=>$virtual_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}