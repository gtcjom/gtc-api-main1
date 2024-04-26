<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_admission extends Model
{ 
    public  static function hospital_admission(){ 
        // syncronize hospital_admission table from offline to online   
        $hosp_offline = DB::table('hospital_admission')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_admission')->where('adm_id', $hosp_offline->adm_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_admission')->where('adm_id', $hosp_offline->adm_id)->update([    
                            'adm_id'=> $hosp_offline->adm_id, 
                            'management_id'=>$hosp_offline->management_id,
                            'patient_id'=>$hosp_offline->patient_id,
                            'doctors_id'=>$hosp_offline->doctors_id,
                            'doctors_order'=>$hosp_offline->doctors_order,
                            'nurse_assign'=>$hosp_offline->nurse_assign,
                            'status'=>$hosp_offline->status,
                            'updated_at'=>$hosp_offline->updated_at,
                            'created_at'=>$hosp_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_admission')->where('adm_id', $hosp_offline_count[0]->adm_id)->update([  
                            'adm_id'=> $hosp_offline_count[0]->adm_id, 
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'doctors_id'=>$hosp_offline_count[0]->doctors_id,
                            'doctors_order'=>$hosp_offline_count[0]->doctors_order,
                            'nurse_assign'=>$hosp_offline_count[0]->nurse_assign,
                            'status'=>$hosp_offline_count[0]->status,
                            'updated_at'=>$hosp_offline_count[0]->updated_at,
                            'created_at'=>$hosp_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_admission')->insert([    
                        'adm_id'=> $hosp_offline->adm_id, 
                        'management_id'=>$hosp_offline->management_id,
                        'patient_id'=>$hosp_offline->patient_id,
                        'doctors_id'=>$hosp_offline->doctors_id,
                        'doctors_order'=>$hosp_offline->doctors_order,
                        'nurse_assign'=>$hosp_offline->nurse_assign,
                        'status'=>$hosp_offline->status,
                        'updated_at'=>$hosp_offline->updated_at,
                        'created_at'=>$hosp_offline->created_at
                    ]); 
                } 
        }

        // syncronize hospital_admission table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_admission')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_admission')->where('adm_id', $hosp_online->adm_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_admission')->where('adm_id', $hosp_online->adm_id)->update([   
                        'adm_id'=> $hosp_online->adm_id, 
                        'management_id'=>$hosp_online->management_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'doctors_id'=>$hosp_online->doctors_id,
                        'doctors_order'=>$hosp_online->doctors_order,
                        'nurse_assign'=>$hosp_online->nurse_assign,
                        'status'=>$hosp_online->status,
                        'updated_at'=>$hosp_online->updated_at,
                        'created_at'=>$hosp_online->created_at
                    ]); 
                }else{
                    DB::table('hospital_admission')->insert([    
                        'adm_id'=> $hosp_online->adm_id, 
                        'management_id'=>$hosp_online->management_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'doctors_id'=>$hosp_online->doctors_id,
                        'doctors_order'=>$hosp_online->doctors_order,
                        'nurse_assign'=>$hosp_online->nurse_assign,
                        'status'=>$hosp_online->status,
                        'updated_at'=>$hosp_online->updated_at,
                        'created_at'=>$hosp_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}