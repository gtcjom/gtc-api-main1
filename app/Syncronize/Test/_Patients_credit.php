<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_credit extends Model
{ 
    public static function patients_credit(){ 
        // syncronize patients_credit table from offline to online   
        $patient_offline = DB::table('patients_credit')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_credit')->where('loadout_id', $patient_offline->loadout_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_credit')->where('loadout_id', $patient_offline->loadout_id)->update([    
                            'loadout_id'=> $patient_offline->loadout_id, 
                            'user_id'=>$patient_offline->user_id,
                            'account_no'=>$patient_offline->account_no,
                            'credit'=>$patient_offline->credit,
                            'trace_no'=>$patient_offline->trace_no,
                            'purchase_on'=>$patient_offline->purchase_on,
                            'process_by'=>$patient_offline->process_by,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_credit')->where('loadout_id', $patient_offline_count[0]->loadout_id)->update([  
                            'loadout_id'=> $patient_offline_count[0]->loadout_id, 
                            'user_id'=>$patient_offline_count[0]->user_id,
                            'account_no'=>$patient_offline_count[0]->account_no,
                            'credit'=>$patient_offline_count[0]->credit,
                            'trace_no'=>$patient_offline_count[0]->trace_no,
                            'purchase_on'=>$patient_offline_count[0]->purchase_on,
                            'process_by'=>$patient_offline_count[0]->process_by,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_credit')->insert([ 
                        'loadout_id'=> $patient_offline->loadout_id, 
                        'user_id'=>$patient_offline->user_id,
                        'account_no'=>$patient_offline->account_no,
                        'credit'=>$patient_offline->credit,
                        'trace_no'=>$patient_offline->trace_no,
                        'purchase_on'=>$patient_offline->purchase_on,
                        'process_by'=>$patient_offline->process_by,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_credit table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_credit')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_credit')->where('loadout_id', $patient_online->loadout_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_credit')->where('loadout_id', $patient_online->loadout_id)->update([  
                        'loadout_id'=> $patient_online->loadout_id, 
                        'user_id'=>$patient_online->user_id,
                        'account_no'=>$patient_online->account_no,
                        'credit'=>$patient_online->credit,
                        'trace_no'=>$patient_online->trace_no,
                        'purchase_on'=>$patient_online->purchase_on,
                        'process_by'=>$patient_online->process_by,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_credit')->insert([    
                        'loadout_id'=> $patient_online->loadout_id, 
                        'user_id'=>$patient_online->user_id,
                        'account_no'=>$patient_online->account_no,
                        'credit'=>$patient_online->credit,
                        'trace_no'=>$patient_online->trace_no,
                        'purchase_on'=>$patient_online->purchase_on,
                        'process_by'=>$patient_online->process_by,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}