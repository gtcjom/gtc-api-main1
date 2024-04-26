<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_family_history extends Model
{ 
    public  static function patients_family_history(){ 
        // syncronize patients_family_history table from offline to online   
        $patient_offline = DB::table('patients_family_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_family_history')->where('dph_id', $patient_offline->dph_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_family_history')->where('dph_id', $patient_offline->dph_id)->update([    
                            'dph_id'=> $patient_offline->dph_id, 
                            'doctors_id'=>$patient_offline->doctors_id,
                            'patient_id'=>$patient_offline->patient_id,
                            'family_history'=>$patient_offline->family_history,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_family_history')->where('dph_id', $patient_offline_count[0]->dph_id)->update([  
                            'dph_id'=> $patient_offline_count[0]->dph_id, 
                            'doctors_id'=>$patient_offline_count[0]->doctors_id,
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'family_history'=>$patient_offline_count[0]->family_history,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_family_history')->insert([ 
                        'dph_id'=> $patient_offline->dph_id, 
                        'doctors_id'=>$patient_offline->doctors_id,
                        'patient_id'=>$patient_offline->patient_id,
                        'family_history'=>$patient_offline->family_history,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_family_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_family_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_family_history')->where('dph_id', $patient_online->dph_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_family_history')->where('dph_id', $patient_online->dph_id)->update([
                        'dph_id'=> $patient_online->dph_id, 
                        'doctors_id'=>$patient_online->doctors_id,
                        'patient_id'=>$patient_online->patient_id,
                        'family_history'=>$patient_online->family_history,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_family_history')->insert([    
                        'dph_id'=> $patient_online->dph_id, 
                        'doctors_id'=>$patient_online->doctors_id,
                        'patient_id'=>$patient_online->patient_id,
                        'family_history'=>$patient_online->family_history,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}