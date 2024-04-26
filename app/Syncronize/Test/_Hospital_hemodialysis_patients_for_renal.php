<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_hemodialysis_patients_for_renal extends Model
{ 
    public static function hospital_hemodialysis_patients_for_renal(){ 
        // syncronize hospital_hemodialysis_patients_for_renal table from offline to online   
        $hosp_offline = DB::table('hospital_hemodialysis_patients_for_renal')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_hemodialysis_patients_for_renal')->where('hhpfr_id', $hosp_offline->hhpfr_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_hemodialysis_patients_for_renal')->where('hhpfr_id', $hosp_offline->hhpfr_id)->update([    
                            'hhpfr_id'=> $hosp_offline->hhpfr_id, 
                            'renal_id'=>$hosp_offline->renal_id,
                            'management_id'=>$hosp_offline->management_id,
                            'patient_id'=>$hosp_offline->patient_id,
                            'case_file'=>$hosp_offline->case_file,
                            'is_nod_process'=>$hosp_offline->is_nod_process,
                            'is_nod_process_date'=>$hosp_offline->is_nod_process_date,
                            'is_move_to_icu'=>$hosp_offline->is_move_to_icu,
                            'added_by'=>$hosp_offline->added_by,
                            'added_on'=>$hosp_offline->added_on,
                            'added_reason'=>$hosp_offline->added_reason,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_hemodialysis_patients_for_renal')->where('hhpfr_id', $hosp_offline_count[0]->hhpfr_id)->update([  
                            'hhpfr_id'=> $hosp_offline_count[0]->hhpfr_id, 
                            'renal_id'=>$hosp_offline_count[0]->renal_id,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'case_file'=>$hosp_offline_count[0]->case_file,
                            'is_nod_process'=>$hosp_offline_count[0]->is_nod_process,
                            'is_nod_process_date'=>$hosp_offline_count[0]->is_nod_process_date,
                            'is_move_to_icu'=>$hosp_offline_count[0]->is_move_to_icu,
                            'added_by'=>$hosp_offline_count[0]->added_by,
                            'added_on'=>$hosp_offline_count[0]->added_on,
                            'added_reason'=>$hosp_offline_count[0]->added_reason,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_hemodialysis_patients_for_renal')->insert([    
                        'hhpfr_id'=> $hosp_offline->hhpfr_id, 
                        'renal_id'=>$hosp_offline->renal_id,
                        'management_id'=>$hosp_offline->management_id,
                        'patient_id'=>$hosp_offline->patient_id,
                        'case_file'=>$hosp_offline->case_file,
                        'is_nod_process'=>$hosp_offline->is_nod_process,
                        'is_nod_process_date'=>$hosp_offline->is_nod_process_date,
                        'is_move_to_icu'=>$hosp_offline->is_move_to_icu,
                        'added_by'=>$hosp_offline->added_by,
                        'added_on'=>$hosp_offline->added_on,
                        'added_reason'=>$hosp_offline->added_reason,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_hemodialysis_patients_for_renal table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_hemodialysis_patients_for_renal')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_hemodialysis_patients_for_renal')->where('hhpfr_id', $hosp_online->hhpfr_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_hemodialysis_patients_for_renal')->where('hhpfr_id', $hosp_online->hhpfr_id)->update([  
                        'hhpfr_id'=> $hosp_online->hhpfr_id, 
                        'renal_id'=>$hosp_online->renal_id,
                        'management_id'=>$hosp_online->management_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'is_nod_process'=>$hosp_online->is_nod_process,
                        'is_nod_process_date'=>$hosp_online->is_nod_process_date,
                        'is_move_to_icu'=>$hosp_online->is_move_to_icu,
                        'added_by'=>$hosp_online->added_by,
                        'added_on'=>$hosp_online->added_on,
                        'added_reason'=>$hosp_online->added_reason,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_hemodialysis_patients_for_renal')->insert([    
                        'hhpfr_id'=> $hosp_online->hhpfr_id, 
                        'renal_id'=>$hosp_online->renal_id,
                        'management_id'=>$hosp_online->management_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'is_nod_process'=>$hosp_online->is_nod_process,
                        'is_nod_process_date'=>$hosp_online->is_nod_process_date,
                        'is_move_to_icu'=>$hosp_online->is_move_to_icu,
                        'added_by'=>$hosp_online->added_by,
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