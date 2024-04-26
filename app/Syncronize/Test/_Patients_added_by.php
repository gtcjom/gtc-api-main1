<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_added_by extends Model
{ 
    public static function patients_added_by(){ 
        // syncronize patients_added_by table from offline to online   
        $patient_offline = DB::table('patients_added_by')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_added_by')->where('added_id', $patient_offline->added_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_added_by')->where('added_id', $patient_offline->added_id)->update([    
                            'added_id'=> $patient_offline->added_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'case_file'=>$patient_offline->case_file,
                            'management_id'=>$patient_offline->management_id,
                            'department'=>$patient_offline->department,
                            'added_by'=>$patient_offline->added_by,
                            'adder_type'=>$patient_offline->adder_type,
                            'doctors_on_duty'=>$patient_offline->doctors_on_duty,
                            'is_rod_recieved'=>$patient_offline->is_rod_recieved,
                            'nurse_recieved_date'=>$patient_offline->nurse_recieved_date,
                            'rod_recieved_date'=>$patient_offline->rod_recieved_date,
                            'patient_status'=>$patient_offline->patient_status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_added_by')->where('added_id', $patient_offline_count[0]->added_id)->update([  
                            'added_id'=> $patient_offline_count[0]->added_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'case_file'=>$patient_offline_count[0]->case_file,
                            'management_id'=>$patient_offline_count[0]->management_id,
                            'department'=>$patient_offline_count[0]->department,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'doctors_on_duty'=>$patient_offline_count[0]->doctors_on_duty,
                            'is_rod_recieved'=>$patient_offline_count[0]->is_rod_recieved,
                            'nurse_recieved_date'=>$patient_offline_count[0]->nurse_recieved_date,
                            'rod_recieved_date'=>$patient_offline_count[0]->rod_recieved_date,
                            'patient_status'=>$patient_offline_count[0]->patient_status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_added_by')->insert([ 
                        'added_id'=> $patient_offline->added_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'case_file'=>$patient_offline->case_file,
                        'management_id'=>$patient_offline->management_id,
                        'department'=>$patient_offline->department,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'doctors_on_duty'=>$patient_offline->doctors_on_duty,
                        'is_rod_recieved'=>$patient_offline->is_rod_recieved,
                        'nurse_recieved_date'=>$patient_offline->nurse_recieved_date,
                        'rod_recieved_date'=>$patient_offline->rod_recieved_date,
                        'patient_status'=>$patient_offline->patient_status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_added_by table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_added_by')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_added_by')->where('added_id', $patient_online->added_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_added_by')->where('added_id', $patient_online->added_id)->update([  
                        'added_id'=> $patient_online->added_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'case_file'=>$patient_online->case_file,
                        'management_id'=>$patient_online->management_id,
                        'department'=>$patient_online->department,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'doctors_on_duty'=>$patient_online->doctors_on_duty,
                        'is_rod_recieved'=>$patient_online->is_rod_recieved,
                        'nurse_recieved_date'=>$patient_online->nurse_recieved_date,
                        'rod_recieved_date'=>$patient_online->rod_recieved_date,
                        'patient_status'=>$patient_online->patient_status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_added_by')->insert([    
                        'added_id'=> $patient_online->added_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'case_file'=>$patient_online->case_file,
                        'management_id'=>$patient_online->management_id,
                        'department'=>$patient_online->department,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'doctors_on_duty'=>$patient_online->doctors_on_duty,
                        'is_rod_recieved'=>$patient_online->is_rod_recieved,
                        'nurse_recieved_date'=>$patient_online->nurse_recieved_date,
                        'rod_recieved_date'=>$patient_online->rod_recieved_date,
                        'patient_status'=>$patient_online->patient_status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}