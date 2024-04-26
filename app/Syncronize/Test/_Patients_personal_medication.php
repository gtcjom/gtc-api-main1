<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_personal_medication extends Model
{ 
    public static function patients_personal_medication(){ 
        // syncronize patients_personal_medication table from offline to online   
        $patient_offline = DB::table('patients_personal_medication')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_personal_medication')->where('ppm_id', $patient_offline->ppm_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_personal_medication')->where('ppm_id', $patient_offline->ppm_id)->update([    
                            'ppm_id'=> $patient_offline->ppm_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'meals'=>$patient_offline->meals,
                            'description'=>$patient_offline->description,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_personal_medication')->where('ppm_id', $patient_offline_count[0]->ppm_id)->update([  
                            'ppm_id'=> $patient_offline_count[0]->ppm_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'meals'=>$patient_offline_count[0]->meals,
                            'description'=>$patient_offline_count[0]->description,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_personal_medication')->insert([ 
                        'ppm_id'=> $patient_offline->ppm_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'meals'=>$patient_offline->meals,
                        'description'=>$patient_offline->description,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_personal_medication table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_personal_medication')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_personal_medication')->where('ppm_id', $patient_online->ppm_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_personal_medication')->where('ppm_id', $patient_online->ppm_id)->update([
                        'ppm_id'=> $patient_online->ppm_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'meals'=>$patient_online->meals,
                        'description'=>$patient_online->description,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_personal_medication')->insert([ 
                        'ppm_id'=> $patient_online->ppm_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'meals'=>$patient_online->meals,
                        'description'=>$patient_online->description,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}