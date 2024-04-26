<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_uric_acid_history extends Model
{ 
    public static function patients_uric_acid_history(){ 
        // syncronize patients_uric_acid_history table from offline to online   
        $patient_offline = DB::table('patients_uric_acid_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_uric_acid_history')->where('uric_acid_id', $patient_offline->uric_acid_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_uric_acid_history')->where('uric_acid_id', $patient_offline->uric_acid_id)->update([    
                            'uric_acid_id'=> $patient_offline->uric_acid_id, 
                            'patients_id'=>$patient_offline->patients_id,
                            'uric_acid'=>$patient_offline->uric_acid,
                            'added_by'=>$patient_offline->added_by,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_uric_acid_history')->where('uric_acid_id', $patient_offline_count[0]->uric_acid_id)->update([  
                            'uric_acid_id'=> $patient_offline_count[0]->uric_acid_id, 
                            'patients_id'=>$patient_offline_count[0]->patients_id,
                            'uric_acid'=>$patient_offline_count[0]->uric_acid,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_uric_acid_history')->insert([ 
                        'uric_acid_id'=> $patient_offline->uric_acid_id, 
                        'patients_id'=>$patient_offline->patients_id,
                        'uric_acid'=>$patient_offline->uric_acid,
                        'added_by'=>$patient_offline->added_by,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_uric_acid_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_uric_acid_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_uric_acid_history')->where('uric_acid_id', $patient_online->uric_acid_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_uric_acid_history')->where('uric_acid_id', $patient_online->uric_acid_id)->update([
                        'uric_acid_id'=> $patient_online->uric_acid_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'uric_acid'=>$patient_online->uric_acid,
                        'added_by'=>$patient_online->added_by,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_uric_acid_history')->insert([ 
                        'uric_acid_id'=> $patient_online->uric_acid_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'uric_acid'=>$patient_online->uric_acid,
                        'added_by'=>$patient_online->added_by,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}