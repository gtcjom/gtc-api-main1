<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory_unsaveorder extends Model
{ 
    public static function laboratory_unsaveorder(){ 
        // syncronize laboratory_unsaveorder table from offline to online   
        $lab_offline = DB::table('laboratory_unsaveorder')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory_unsaveorder')->where('lu_id', $lab_offline->lu_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory_unsaveorder')->where('lu_id', $lab_offline->lu_id)->update([    
                            'lu_id'=> $lab_offline->lu_id, 
                            'patient_id'=>$lab_offline->patient_id,
                            'doctor_id'=>$lab_offline->doctor_id,
                            'laborotary_id'=>$lab_offline->laborotary_id,
                            'management_id'=>$lab_offline->management_id,
                            'department'=>$lab_offline->department,
                            'laboratory_test_id'=>$lab_offline->laboratory_test_id,
                            'laboratory_test'=>$lab_offline->laboratory_test,
                            'laboratory_rate'=>$lab_offline->laboratory_rate,
                            'status'=>$lab_offline->status,
                            'updated_at'=>$lab_offline->updated_at,
                            'created_at'=>$lab_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory_unsaveorder')->where('lu_id', $lab_offline_count[0]->lu_id)->update([  
                            'lu_id'=> $lab_offline_count[0]->lu_id, 
                            'patient_id'=>$lab_offline_count[0]->patient_id,
                            'doctor_id'=>$lab_offline_count[0]->doctor_id,
                            'laborotary_id'=>$lab_offline_count[0]->laborotary_id,
                            'management_id'=>$lab_offline_count[0]->management_id,
                            'department'=>$lab_offline_count[0]->department,
                            'laboratory_test_id'=>$lab_offline_count[0]->laboratory_test_id,
                            'laboratory_test'=>$lab_offline_count[0]->laboratory_test,
                            'laboratory_rate'=>$lab_offline_count[0]->laboratory_rate,
                            'status'=>$lab_offline_count[0]->status,
                            'updated_at'=>$lab_offline_count[0]->updated_at,
                            'created_at'=>$lab_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory_unsaveorder')->insert([ 
                        'lu_id'=> $lab_offline->lu_id, 
                        'patient_id'=>$lab_offline->patient_id,
                        'doctor_id'=>$lab_offline->doctor_id,
                        'laborotary_id'=>$lab_offline->laborotary_id,
                        'management_id'=>$lab_offline->management_id,
                        'department'=>$lab_offline->department,
                        'laboratory_test_id'=>$lab_offline->laboratory_test_id,
                        'laboratory_test'=>$lab_offline->laboratory_test,
                        'laboratory_rate'=>$lab_offline->laboratory_rate,
                        'status'=>$lab_offline->status,
                        'updated_at'=>$lab_offline->updated_at,
                        'created_at'=>$lab_offline->created_at
                    ]); 
                } 
        }

        // syncronize laboratory_unsaveorder table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory_unsaveorder')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory_unsaveorder')->where('lu_id', $lab_online->lu_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory_unsaveorder')->where('lu_id', $lab_online->lu_id)->update([  
                        'lu_id'=> $lab_online->lu_id, 
                        'patient_id'=>$lab_online->patient_id,
                        'doctor_id'=>$lab_online->doctor_id,
                        'laborotary_id'=>$lab_online->laborotary_id,
                        'management_id'=>$lab_online->management_id,
                        'department'=>$lab_online->department,
                        'laboratory_test_id'=>$lab_online->laboratory_test_id,
                        'laboratory_test'=>$lab_online->laboratory_test,
                        'laboratory_rate'=>$lab_online->laboratory_rate,
                        'status'=>$lab_online->status,
                        'updated_at'=>$lab_online->updated_at,
                        'created_at'=>$lab_online->created_at
                    ]); 
                }else{
                    DB::table('laboratory_unsaveorder')->insert([    
                        'lu_id'=> $lab_online->lu_id, 
                        'patient_id'=>$lab_online->patient_id,
                        'doctor_id'=>$lab_online->doctor_id,
                        'laborotary_id'=>$lab_online->laborotary_id,
                        'management_id'=>$lab_online->management_id,
                        'department'=>$lab_online->department,
                        'laboratory_test_id'=>$lab_online->laboratory_test_id,
                        'laboratory_test'=>$lab_online->laboratory_test,
                        'laboratory_rate'=>$lab_online->laboratory_rate,
                        'status'=>$lab_online->status,
                        'updated_at'=>$lab_online->updated_at,
                        'created_at'=>$lab_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}