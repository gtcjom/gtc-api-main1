<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_temp_history extends Model
{ 
    public static function patients_temp_history(){ 
        // syncronize patients_temp_history table from offline to online   
        $patient_offline = DB::table('patients_temp_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_temp_history')->where('pth_id', $patient_offline->pth_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_temp_history')->where('pth_id', $patient_offline->pth_id)->update([    
                            'pth_id'=> $patient_offline->pth_id, 
                            'patients_id'=>$patient_offline->patients_id,
                            'temp'=>$patient_offline->temp,
                            'added_by'=>$patient_offline->added_by,
                            'status'=>$patient_offline->status,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_temp_history')->where('pth_id', $patient_offline_count[0]->pth_id)->update([  
                            'pth_id'=> $patient_offline_count[0]->pth_id, 
                            'patients_id'=>$patient_offline_count[0]->patients_id,
                            'temp'=>$patient_offline_count[0]->temp,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'status'=>$patient_offline_count[0]->status,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_temp_history')->insert([ 
                        'pth_id'=> $patient_offline->pth_id, 
                        'patients_id'=>$patient_offline->patients_id,
                        'temp'=>$patient_offline->temp,
                        'added_by'=>$patient_offline->added_by,
                        'status'=>$patient_offline->status,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_temp_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_temp_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_temp_history')->where('pth_id', $patient_online->pth_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_temp_history')->where('pth_id', $patient_online->pth_id)->update([
                        'pth_id'=> $patient_online->pth_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'temp'=>$patient_online->temp,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_temp_history')->insert([ 
                        'pth_id'=> $patient_online->pth_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'temp'=>$patient_online->temp,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}