<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_history extends Model
{ 
    public static function patients_history(){ 
        // syncronize patients_history table from offline to online   
        $patient_offline = DB::table('patients_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history')->where('ph_id', $patient_offline->ph_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_history')->where('ph_id', $patient_offline->ph_id)->update([    
                            'ph_id'=> $patient_offline->ph_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'street'=>$patient_offline->street,
                            'barangay'=>$patient_offline->barangay,
                            'city'=>$patient_offline->city,
                            'zip'=>$patient_offline->zip,
                            'height'=>$patient_offline->height,
                            'weight'=>$patient_offline->weight,
                            'occupation'=>$patient_offline->occupation,
                            'allergies'=>$patient_offline->allergies,
                            'medication'=>$patient_offline->medication,
                            'remarks'=>$patient_offline->remarks,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_history')->where('ph_id', $patient_offline_count[0]->ph_id)->update([  
                            'ph_id'=> $patient_offline_count[0]->ph_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'street'=>$patient_offline_count[0]->street,
                            'barangay'=>$patient_offline_count[0]->barangay,
                            'city'=>$patient_offline_count[0]->city,
                            'zip'=>$patient_offline_count[0]->zip,
                            'height'=>$patient_offline_count[0]->height,
                            'weight'=>$patient_offline_count[0]->weight,
                            'occupation'=>$patient_offline_count[0]->occupation,
                            'allergies'=>$patient_offline_count[0]->allergies,
                            'medication'=>$patient_offline_count[0]->medication,
                            'remarks'=>$patient_offline_count[0]->remarks,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_history')->insert([ 
                        'ph_id'=> $patient_offline->ph_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'street'=>$patient_offline->street,
                        'barangay'=>$patient_offline->barangay,
                        'city'=>$patient_offline->city,
                        'zip'=>$patient_offline->zip,
                        'height'=>$patient_offline->height,
                        'weight'=>$patient_offline->weight,
                        'occupation'=>$patient_offline->occupation,
                        'allergies'=>$patient_offline->allergies,
                        'medication'=>$patient_offline->medication,
                        'remarks'=>$patient_offline->remarks,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_history')->where('ph_id', $patient_online->ph_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history')->where('ph_id', $patient_online->ph_id)->update([
                        'ph_id'=> $patient_online->ph_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'street'=>$patient_online->street,
                        'barangay'=>$patient_online->barangay,
                        'city'=>$patient_online->city,
                        'zip'=>$patient_online->zip,
                        'height'=>$patient_online->height,
                        'weight'=>$patient_online->weight,
                        'occupation'=>$patient_online->occupation,
                        'allergies'=>$patient_online->allergies,
                        'medication'=>$patient_online->medication,
                        'remarks'=>$patient_online->remarks,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_history')->insert([    
                        'ph_id'=> $patient_online->ph_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'street'=>$patient_online->street,
                        'barangay'=>$patient_online->barangay,
                        'city'=>$patient_online->city,
                        'zip'=>$patient_online->zip,
                        'height'=>$patient_online->height,
                        'weight'=>$patient_online->weight,
                        'occupation'=>$patient_online->occupation,
                        'allergies'=>$patient_online->allergies,
                        'medication'=>$patient_online->medication,
                        'remarks'=>$patient_online->remarks,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}