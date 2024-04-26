<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_history_attachment extends Model
{ 
    public static function patients_history_attachment(){ 
        // syncronize patients_history_attachment table from offline to online   
        $patient_offline = DB::table('patients_history_attachment')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_attachment')->where('pha_id', $patient_offline->pha_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_history_attachment')->where('pha_id', $patient_offline->pha_id)->update([    
                            'pha_id'=> $patient_offline->pha_id, 
                            'history_attachment_id'=>$patient_offline->history_attachment_id,
                            'patient_id'=>$patient_offline->patient_id,
                            'attachment'=>$patient_offline->attachment,
                            'remarks'=>$patient_offline->remarks,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_history_attachment')->where('pha_id', $patient_offline_count[0]->pha_id)->update([  
                            'pha_id'=> $patient_offline_count[0]->pha_id, 
                            'history_attachment_id'=>$patient_offline_count[0]->history_attachment_id,
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'attachment'=>$patient_offline_count[0]->attachment,
                            'remarks'=>$patient_offline_count[0]->remarks,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_history_attachment')->insert([ 
                        'pha_id'=> $patient_offline->pha_id, 
                        'history_attachment_id'=>$patient_offline->history_attachment_id,
                        'patient_id'=>$patient_offline->patient_id,
                        'attachment'=>$patient_offline->attachment,
                        'remarks'=>$patient_offline->remarks,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_history_attachment table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_history_attachment')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_attachment')->where('pha_id', $patient_online->pha_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_attachment')->where('pha_id', $patient_online->pha_id)->update([
                        'pha_id'=> $patient_online->pha_id, 
                        'history_attachment_id'=>$patient_online->history_attachment_id,
                        'patient_id'=>$patient_online->patient_id,
                        'attachment'=>$patient_online->attachment,
                        'remarks'=>$patient_online->remarks,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_history_attachment')->insert([ 
                        'pha_id'=> $patient_online->pha_id, 
                        'history_attachment_id'=>$patient_online->history_attachment_id,
                        'patient_id'=>$patient_online->patient_id,
                        'attachment'=>$patient_online->attachment,
                        'remarks'=>$patient_online->remarks,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}