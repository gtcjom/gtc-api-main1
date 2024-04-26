<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_admitted_patient extends Model
{ 
    public  static function hospital_admitted_patient(){ 
        // syncronize hospital_admitted_patient table from offline to online   
        $hosp_offline = DB::table('hospital_admitted_patient')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_admitted_patient')->where('had_id', $hosp_offline->had_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_admitted_patient')->where('had_id', $hosp_offline->had_id)->update([    
                            'had_id'=> $hosp_offline->had_id, 
                            'admit_id'=>$hosp_offline->admit_id,
                            'patient_id'=>$hosp_offline->patient_id,
                            'case_file'=>$hosp_offline->case_file,
                            'management_id'=>$hosp_offline->management_id,
                            'room_id'=>$hosp_offline->room_id,
                            'room_number'=>$hosp_offline->room_number,
                            'room_bed_number'=>$hosp_offline->room_bed_number,
                            'process_by'=>$hosp_offline->process_by,
                            'admitted_by'=>$hosp_offline->admitted_by,
                            'admitted_on'=>$hosp_offline->admitted_on,
                            'discharge_on'=>$hosp_offline->discharge_on,
                            'discharge_remarks'=>$hosp_offline->discharge_remarks,
                            'reason'=>$hosp_offline->reason,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_admitted_patient')->where('had_id', $hosp_offline_count[0]->had_id)->update([  
                            'had_id'=> $hosp_offline_count[0]->had_id, 
                            'admit_id'=>$hosp_offline_count[0]->admit_id,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'case_file'=>$hosp_offline_count[0]->case_file,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'room_id'=>$hosp_offline_count[0]->room_id,
                            'room_number'=>$hosp_offline_count[0]->room_number,
                            'room_bed_number'=>$hosp_offline_count[0]->room_bed_number,
                            'process_by'=>$hosp_offline_count[0]->process_by,
                            'admitted_by'=>$hosp_offline_count[0]->admitted_by,
                            'admitted_on'=>$hosp_offline_count[0]->admitted_on,
                            'discharge_on'=>$hosp_offline_count[0]->discharge_on,
                            'discharge_remarks'=>$hosp_offline_count[0]->discharge_remarks,
                            'reason'=>$hosp_offline_count[0]->reason,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_admitted_patient')->insert([    
                        'had_id'=> $hosp_offline->had_id, 
                        'admit_id'=>$hosp_offline->admit_id,
                        'patient_id'=>$hosp_offline->patient_id,
                        'case_file'=>$hosp_offline->case_file,
                        'management_id'=>$hosp_offline->management_id,
                        'room_id'=>$hosp_offline->room_id,
                        'room_number'=>$hosp_offline->room_number,
                        'room_bed_number'=>$hosp_offline->room_bed_number,
                        'process_by'=>$hosp_offline->process_by,
                        'admitted_by'=>$hosp_offline->admitted_by,
                        'admitted_on'=>$hosp_offline->admitted_on,
                        'discharge_on'=>$hosp_offline->discharge_on,
                        'discharge_remarks'=>$hosp_offline->discharge_remarks,
                        'reason'=>$hosp_offline->reason,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_admitted_patient table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_admitted_patient')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_admitted_patient')->where('had_id', $hosp_online->had_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_admitted_patient')->where('had_id', $hosp_online->had_id)->update([   
                        'had_id'=> $hosp_online->had_id, 
                        'admit_id'=>$hosp_online->admit_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'management_id'=>$hosp_online->management_id,
                        'room_id'=>$hosp_online->room_id,
                        'room_number'=>$hosp_online->room_number,
                        'room_bed_number'=>$hosp_online->room_bed_number,
                        'process_by'=>$hosp_online->process_by,
                        'admitted_by'=>$hosp_online->admitted_by,
                        'admitted_on'=>$hosp_online->admitted_on,
                        'discharge_on'=>$hosp_online->discharge_on,
                        'discharge_remarks'=>$hosp_online->discharge_remarks,
                        'reason'=>$hosp_online->reason,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_admitted_patient')->insert([    
                        'had_id'=> $hosp_online->had_id, 
                        'admit_id'=>$hosp_online->admit_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'management_id'=>$hosp_online->management_id,
                        'room_id'=>$hosp_online->room_id,
                        'room_number'=>$hosp_online->room_number,
                        'room_bed_number'=>$hosp_online->room_bed_number,
                        'process_by'=>$hosp_online->process_by,
                        'admitted_by'=>$hosp_online->admitted_by,
                        'admitted_on'=>$hosp_online->admitted_on,
                        'discharge_on'=>$hosp_online->discharge_on,
                        'discharge_remarks'=>$hosp_online->discharge_remarks,
                        'reason'=>$hosp_online->reason,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}