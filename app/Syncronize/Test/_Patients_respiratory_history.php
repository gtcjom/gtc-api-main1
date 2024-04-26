<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_respiratory_history extends Model
{ 
    public static function patients_respiratory_history(){ 
        // syncronize patients_respiratory_history table from offline to online   
        $patient_offline = DB::table('patients_respiratory_history')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_respiratory_history')->where('prh_id', $patient_offline->prh_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_respiratory_history')->where('prh_id', $patient_offline->prh_id)->update([    
                            'prh_id'=> $patient_offline->prh_id, 
                            'patients_id'=>$patient_offline->patients_id,
                            'respiratory'=>$patient_offline->respiratory,
                            'added_by'=>$patient_offline->added_by,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_respiratory_history')->where('prh_id', $patient_offline_count[0]->prh_id)->update([  
                            'prh_id'=> $patient_offline_count[0]->prh_id, 
                            'patients_id'=>$patient_offline_count[0]->patients_id,
                            'respiratory'=>$patient_offline_count[0]->respiratory,
                            'added_by'=>$patient_offline_count[0]->added_by,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_respiratory_history')->insert([ 
                        'prh_id'=> $patient_offline->prh_id, 
                        'patients_id'=>$patient_offline->patients_id,
                        'respiratory'=>$patient_offline->respiratory,
                        'added_by'=>$patient_offline->added_by,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_respiratory_history table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_respiratory_history')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_respiratory_history')->where('prh_id', $patient_online->prh_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_respiratory_history')->where('prh_id', $patient_online->prh_id)->update([
                        'prh_id'=> $patient_online->prh_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'respiratory'=>$patient_online->respiratory,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_respiratory_history')->insert([ 
                        'prh_id'=> $patient_online->prh_id, 
                        'patients_id'=>$patient_online->patients_id,
                        'respiratory'=>$patient_online->respiratory,
                        'added_by'=>$patient_online->added_by,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}