<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_patients_log extends Model
{ 
    public static function hospital_patients_logs(){ 
        // syncronize hospital_patients_logs table from offline to online   
        $hosp_offline = DB::table('hospital_patients_logs')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_patients_logs')->where('hpl_id', $hosp_offline->hpl_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_patients_logs')->where('hpl_id', $hosp_offline->hpl_id)->update([    
                            'hpl_id'=> $hosp_offline->hpl_id, 
                            'log_id'=>$hosp_offline->log_id,
                            'patient_id'=>$hosp_offline->patient_id,
                            'case_file'=>$hosp_offline->case_file,
                            'management_id'=>$hosp_offline->management_id,
                            'log'=>$hosp_offline->log,
                            'added_by'=>$hosp_offline->added_by,
                            'adder_type'=>$hosp_offline->adder_type,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_patients_logs')->where('hpl_id', $hosp_offline_count[0]->hpl_id)->update([  
                            'hpl_id'=> $hosp_offline_count[0]->hpl_id, 
                            'log_id'=>$hosp_offline_count[0]->log_id,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'case_file'=>$hosp_offline_count[0]->case_file,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'log'=>$hosp_offline_count[0]->log,
                            'added_by'=>$hosp_offline_count[0]->added_by,
                            'adder_type'=>$hosp_offline_count[0]->adder_type,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_patients_logs')->insert([    
                        'hpl_id'=> $hosp_offline->hpl_id, 
                        'log_id'=>$hosp_offline->log_id,
                        'patient_id'=>$hosp_offline->patient_id,
                        'case_file'=>$hosp_offline->case_file,
                        'management_id'=>$hosp_offline->management_id,
                        'log'=>$hosp_offline->log,
                        'added_by'=>$hosp_offline->added_by,
                        'adder_type'=>$hosp_offline->adder_type,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_patients_logs table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_patients_logs')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_patients_logs')->where('hpl_id', $hosp_online->hpl_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_patients_logs')->where('hpl_id', $hosp_online->hpl_id)->update([  
                        'hpl_id'=> $hosp_online->hpl_id, 
                        'log_id'=>$hosp_online->log_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'management_id'=>$hosp_online->management_id,
                        'log'=>$hosp_online->log,
                        'added_by'=>$hosp_online->added_by,
                        'adder_type'=>$hosp_online->adder_type,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_patients_logs')->insert([    
                        'hpl_id'=> $hosp_online->hpl_id, 
                        'log_id'=>$hosp_online->log_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'case_file'=>$hosp_online->case_file,
                        'management_id'=>$hosp_online->management_id,
                        'log'=>$hosp_online->log,
                        'added_by'=>$hosp_online->added_by,
                        'adder_type'=>$hosp_online->adder_type,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}