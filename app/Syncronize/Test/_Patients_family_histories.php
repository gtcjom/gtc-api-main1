<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_family_histories extends Model
{ 
    public static function patients_family_histories(){ 
        // syncronize patients_family_histories table from offline to online   
        $patient_offline = DB::table('patients_family_histories')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_family_histories')->where('pfh_id', $patient_offline->pfh_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_family_histories')->where('pfh_id', $patient_offline->pfh_id)->update([    
                            'pfh_id'=> $patient_offline->pfh_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'name'=>$patient_offline->name,
                            'address'=>$patient_offline->address,
                            'birthday'=>$patient_offline->birthday,
                            'occupation'=>$patient_offline->occupation,
                            'health_status'=>$patient_offline->health_status,
                            'category'=>$patient_offline->category,
                            'is_deceased'=>$patient_offline->is_deceased,
                            'is_deceased_reason'=>$patient_offline->is_deceased_reason,
                            'status'=>$patient_offline->balance,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_family_histories')->where('pfh_id', $patient_offline_count[0]->pfh_id)->update([  
                            'pfh_id'=> $patient_offline_count[0]->pfh_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'name'=>$patient_offline_count[0]->name,
                            'address'=>$patient_offline_count[0]->address,
                            'birthday'=>$patient_offline_count[0]->birthday,
                            'occupation'=>$patient_offline_count[0]->occupation,
                            'health_status'=>$patient_offline_count[0]->health_status,
                            'category'=>$patient_offline_count[0]->category,
                            'is_deceased'=>$patient_offline_count[0]->is_deceased,
                            'is_deceased_reason'=>$patient_offline_count[0]->is_deceased_reason,
                            'status'=>$patient_offline_count[0]->balance,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_family_histories')->insert([ 
                        'pfh_id'=> $patient_offline->pfh_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'name'=>$patient_offline->name,
                        'address'=>$patient_offline->address,
                        'birthday'=>$patient_offline->birthday,
                        'occupation'=>$patient_offline->occupation,
                        'health_status'=>$patient_offline->health_status,
                        'category'=>$patient_offline->category,
                        'is_deceased'=>$patient_offline->is_deceased,
                        'is_deceased_reason'=>$patient_offline->is_deceased_reason,
                        'status'=>$patient_offline->balance,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_family_histories table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_family_histories')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_family_histories')->where('pfh_id', $patient_online->pfh_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_family_histories')->where('pfh_id', $patient_online->pfh_id)->update([
                        'pfh_id'=> $patient_online->pfh_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'name'=>$patient_online->name,
                        'address'=>$patient_online->address,
                        'birthday'=>$patient_online->birthday,
                        'occupation'=>$patient_online->occupation,
                        'health_status'=>$patient_online->health_status,
                        'category'=>$patient_online->category,
                        'is_deceased'=>$patient_online->is_deceased,
                        'is_deceased_reason'=>$patient_online->is_deceased_reason,
                        'status'=>$patient_online->balance,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_family_histories')->insert([    
                        'pfh_id'=> $patient_online->pfh_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'name'=>$patient_online->name,
                        'address'=>$patient_online->address,
                        'birthday'=>$patient_online->birthday,
                        'occupation'=>$patient_online->occupation,
                        'health_status'=>$patient_online->health_status,
                        'category'=>$patient_online->category,
                        'is_deceased'=>$patient_online->is_deceased,
                        'is_deceased_reason'=>$patient_online->is_deceased_reason,
                        'status'=>$patient_online->balance,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}