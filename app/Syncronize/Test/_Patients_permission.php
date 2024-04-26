<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_permission extends Model
{ 
    public static function patients_permission(){ 
        // syncronize patients_permission table from offline to online   
        $patient_offline = DB::table('patients_permission')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_permission')->where('permission_id', $patient_offline->permission_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_permission')->where('permission_id', $patient_offline->permission_id)->update([    
                            'permission_id'=> $patient_offline->permission_id, 
                            'patients_id'=>$patient_offline->patients_id,
                            'doctors_id'=>$patient_offline->doctors_id,
                            'permission_on'=>$patient_offline->permission_on,
                            'permission_status'=>$patient_offline->permission_status,
                            'status'=>$patient_offline->status,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_permission')->where('permission_id', $patient_offline_count[0]->permission_id)->update([  
                            'permission_id'=> $patient_offline_count[0]->permission_id, 
                            'patients_id'=>$patient_offline_count[0]->patients_id,
                            'doctors_id'=>$patient_offline_count[0]->doctors_id,
                            'permission_on'=>$patient_offline_count[0]->permission_on,
                            'permission_status'=>$patient_offline_count[0]->permission_status,
                            'status'=>$patient_offline_count[0]->status,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_permission')->insert([ 
                        'permission_id'=> $patient_offline->permission_id, 
                        'patients_id'=>$patient_offline->patients_id,
                        'doctors_id'=>$patient_offline->doctors_id,
                        'permission_on'=>$patient_offline->permission_on,
                        'permission_status'=>$patient_offline->permission_status,
                        'status'=>$patient_offline->status,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_permission table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_permission')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_permission')->where('permission_id', $patient_online->permission_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_permission')->where('permission_id', $patient_online->permission_id)->update([
                        'permission_id'=> $patient_online->permission_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'doctors_id'=>$patient_online->doctors_id,
                        'permission_on'=>$patient_online->permission_on,
                        'permission_status'=>$patient_online->permission_status,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_permission')->insert([ 
                        'permission_id'=> $patient_online->permission_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'doctors_id'=>$patient_online->doctors_id,
                        'permission_on'=>$patient_online->permission_on,
                        'permission_status'=>$patient_online->permission_status,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}