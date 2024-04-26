<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_patient_for_operation extends Model
{ 
    public static function hospital_patient_for_operation(){ 
        // syncronize hospital_patient_for_operation table from offline to online   
        $hosp_offline = DB::table('hospital_patient_for_operation')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_patient_for_operation')->where('hpfo_id', $hosp_offline->hpfo_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_patient_for_operation')->where('hpfo_id', $hosp_offline->hpfo_id)->update([    
                            'hpfo_id'=> $hosp_offline->hpfo_id, 
                            'operation_id'=>$hosp_offline->operation_id,
                            'csr_id'=>$hosp_offline->csr_id,
                            'reason'=>$hosp_offline->reason,
                            'doctor_assign'=>$hosp_offline->doctor_assign,
                            'operation_date'=>$hosp_offline->operation_date,
                            'operation_start'=>$hosp_offline->operation_start,
                            'operation_end'=>$hosp_offline->operation_end,
                            'patient_id'=>$hosp_offline->patient_id,
                            'case_file'=>$hosp_offline->case_file,
                            'or_room_id'=>$hosp_offline->or_room_id,
                            'or_room_number'=>$hosp_offline->or_room_number,
                            'or_room_bed_number'=>$hosp_offline->or_room_bed_number,
                            'management_id'=>$hosp_offline->management_id,
                            'added_by_rod'=>$hosp_offline->added_by_rod,
                            'added_by_nurse'=>$hosp_offline->added_by_nurse,
                            'process_by'=>$hosp_offline->process_by,
                            'is_or_nurse_received'=>$hosp_offline->is_or_nurse_received,
                            'is_received_on'=>$hosp_offline->is_received_on,
                            'csr_received'=>$hosp_offline->csr_received,
                            'csr_received_on'=>$hosp_offline->csr_received_on,
                            'csr_process_by'=>$hosp_offline->csr_process_by,
                            'status'=>$hosp_offline->status,
                            'updated_at'=>$hosp_offline->updated_at,
                            'created_at'=>$hosp_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_patient_for_operation')->where('hpfo_id', $hosp_offline_count[0]->hpfo_id)->update([  
                            'hpfo_id'=> $hosp_offline_count[0]->hpfo_id, 
                            'operation_id'=>$hosp_offline_count[0]->operation_id,
                            'csr_id'=>$hosp_offline_count[0]->csr_id,
                            'reason'=>$hosp_offline_count[0]->reason,
                            'doctor_assign'=>$hosp_offline_count[0]->doctor_assign,
                            'operation_date'=>$hosp_offline_count[0]->operation_date,
                            'operation_start'=>$hosp_offline_count[0]->operation_start,
                            'operation_end'=>$hosp_offline_count[0]->operation_end,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'case_file'=>$hosp_offline_count[0]->case_file,
                            'or_room_id'=>$hosp_offline_count[0]->or_room_id,
                            'or_room_number'=>$hosp_offline_count[0]->or_room_number,
                            'or_room_bed_number'=>$hosp_offline_count[0]->or_room_bed_number,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'added_by_rod'=>$hosp_offline_count[0]->added_by_rod,
                            'added_by_nurse'=>$hosp_offline_count[0]->added_by_nurse,
                            'process_by'=>$hosp_offline_count[0]->process_by,
                            'is_or_nurse_received'=>$hosp_offline_count[0]->is_or_nurse_received,
                            'is_received_on'=>$hosp_offline_count[0]->is_received_on,
                            'csr_received'=>$hosp_offline_count[0]->csr_received,
                            'csr_received_on'=>$hosp_offline_count[0]->csr_received_on,
                            'csr_process_by'=>$hosp_offline_count[0]->csr_process_by,
                            'status'=>$hosp_offline_count[0]->status,
                            'updated_at'=>$hosp_offline_count[0]->updated_at,
                            'created_at'=>$hosp_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_patient_for_operation')->insert([    
                        'hpfo_id'=> $hosp_offline->hpfo_id, 
                        'operation_id'=>$hosp_offline->operation_id,
                        'csr_id'=>$hosp_offline->csr_id,
                        'reason'=>$hosp_offline->reason,
                        'doctor_assign'=>$hosp_offline->doctor_assign,
                        'operation_date'=>$hosp_offline->operation_date,
                        'operation_start'=>$hosp_offline->operation_start,
                        'operation_end'=>$hosp_offline->operation_end,
                        'patient_id'=>$hosp_offline->patient_id,
                        'case_file'=>$hosp_offline->case_file,
                        'or_room_id'=>$hosp_offline->or_room_id,
                        'or_room_number'=>$hosp_offline->or_room_number,
                        'or_room_bed_number'=>$hosp_offline->or_room_bed_number,
                        'management_id'=>$hosp_offline->management_id,
                        'added_by_rod'=>$hosp_offline->added_by_rod,
                        'added_by_nurse'=>$hosp_offline->added_by_nurse,
                        'process_by'=>$hosp_offline->process_by,
                        'is_or_nurse_received'=>$hosp_offline->is_or_nurse_received,
                        'is_received_on'=>$hosp_offline->is_received_on,
                        'csr_received'=>$hosp_offline->csr_received,
                        'csr_received_on'=>$hosp_offline->csr_received_on,
                        'csr_process_by'=>$hosp_offline->csr_process_by,
                        'status'=>$hosp_offline->status,
                        'updated_at'=>$hosp_offline->updated_at,
                        'created_at'=>$hosp_offline->created_at
                    ]); 
                } 
        }

        // syncronize hospital_patient_for_operation table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_patient_for_operation')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_patient_for_operation')->where('hpfo_id', $hosp_online->hpfo_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_patient_for_operation')->where('hpfo_id', $hosp_online->hpfo_id)->update([  
                        'hpfo_id'=> $hosp_online->hpfo_id, 
                        'operation_id'=>$hosp_online->operation_id,
                        'csr_id'=>$hosp_online->csr_id,
                        'reason'=>$hosp_online->reason,
                        'doctor_assign'=>$hosp_online->doctor_assign,
                        'operation_date'=>$hosp_online->operation_date,
                        'operation_start'=>$hosp_online->operation_start,
                        'operation_end'=>$hosp_online->operation_end,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'or_room_id'=>$hosp_online->or_room_id,
                        'or_room_number'=>$hosp_online->or_room_number,
                        'or_room_bed_number'=>$hosp_online->or_room_bed_number,
                        'management_id'=>$hosp_online->management_id,
                        'added_by_rod'=>$hosp_online->added_by_rod,
                        'added_by_nurse'=>$hosp_online->added_by_nurse,
                        'process_by'=>$hosp_online->process_by,
                        'is_or_nurse_received'=>$hosp_online->is_or_nurse_received,
                        'is_received_on'=>$hosp_online->is_received_on,
                        'csr_received'=>$hosp_online->csr_received,
                        'csr_received_on'=>$hosp_online->csr_received_on,
                        'csr_process_by'=>$hosp_online->csr_process_by,
                        'status'=>$hosp_online->status,
                        'updated_at'=>$hosp_online->updated_at,
                        'created_at'=>$hosp_online->created_at
                    ]); 
                }else{
                    DB::table('hospital_patient_for_operation')->insert([    
                        'hpfo_id'=> $hosp_online->hpfo_id, 
                        'operation_id'=>$hosp_online->operation_id,
                        'csr_id'=>$hosp_online->csr_id,
                        'reason'=>$hosp_online->reason,
                        'doctor_assign'=>$hosp_online->doctor_assign,
                        'operation_date'=>$hosp_online->operation_date,
                        'operation_start'=>$hosp_online->operation_start,
                        'operation_end'=>$hosp_online->operation_end,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'or_room_id'=>$hosp_online->or_room_id,
                        'or_room_number'=>$hosp_online->or_room_number,
                        'or_room_bed_number'=>$hosp_online->or_room_bed_number,
                        'management_id'=>$hosp_online->management_id,
                        'added_by_rod'=>$hosp_online->added_by_rod,
                        'added_by_nurse'=>$hosp_online->added_by_nurse,
                        'process_by'=>$hosp_online->process_by,
                        'is_or_nurse_received'=>$hosp_online->is_or_nurse_received,
                        'is_received_on'=>$hosp_online->is_received_on,
                        'csr_received'=>$hosp_online->csr_received,
                        'csr_received_on'=>$hosp_online->csr_received_on,
                        'csr_process_by'=>$hosp_online->csr_process_by,
                        'status'=>$hosp_online->status,
                        'updated_at'=>$hosp_online->updated_at,
                        'created_at'=>$hosp_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}