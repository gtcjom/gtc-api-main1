<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_credit_transaction extends Model
{ 
    public static function patients_credit_transaction(){ 
        // syncronize patients_credit_transaction table from offline to online   
        $patient_offline = DB::table('patients_credit_transaction')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_credit_transaction')->where('transaction_id', $patient_offline->transaction_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_credit_transaction')->where('transaction_id', $patient_offline->transaction_id)->update([    
                            'transaction_id'=> $patient_offline->transaction_id, 
                            'reference_no'=>$patient_offline->reference_no,
                            'patient_id'=>$patient_offline->patient_id,
                            'doctors_id'=>$patient_offline->doctors_id,
                            'doctors_service_id'=>$patient_offline->doctors_service_id,
                            'transaction_cost'=>$patient_offline->transaction_cost,
                            'transaction_status'=>$patient_offline->transaction_status,
                            'status'=>$patient_offline->status,
                            'updated_at'=>$patient_offline->updated_at,
                            'created_at'=>$patient_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('patients_credit_transaction')->where('transaction_id', $patient_offline_count[0]->transaction_id)->update([  
                            'transaction_id'=> $patient_offline_count[0]->transaction_id, 
                            'reference_no'=>$patient_offline_count[0]->reference_no,
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'doctors_id'=>$patient_offline_count[0]->doctors_id,
                            'doctors_service_id'=>$patient_offline_count[0]->doctors_service_id,
                            'transaction_cost'=>$patient_offline_count[0]->transaction_cost,
                            'transaction_status'=>$patient_offline_count[0]->transaction_status,
                            'status'=>$patient_offline_count[0]->status,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                            'created_at'=>$patient_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_credit_transaction')->insert([ 
                        'transaction_id'=> $patient_offline->transaction_id, 
                        'reference_no'=>$patient_offline->reference_no,
                        'patient_id'=>$patient_offline->patient_id,
                        'doctors_id'=>$patient_offline->doctors_id,
                        'doctors_service_id'=>$patient_offline->doctors_service_id,
                        'transaction_cost'=>$patient_offline->transaction_cost,
                        'transaction_status'=>$patient_offline->transaction_status,
                        'status'=>$patient_offline->status,
                        'updated_at'=>$patient_offline->updated_at,
                        'created_at'=>$patient_offline->created_at
                    ]); 
                } 
        }

        // syncronize patients_credit_transaction table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_credit_transaction')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_credit_transaction')->where('transaction_id', $patient_online->transaction_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_credit_transaction')->where('transaction_id', $patient_online->transaction_id)->update([  
                        'transaction_id'=> $patient_online->transaction_id, 
                        'reference_no'=>$patient_online->reference_no,
                        'patient_id'=>$patient_online->patient_id,
                        'doctors_id'=>$patient_online->doctors_id,
                        'doctors_service_id'=>$patient_online->doctors_service_id,
                        'transaction_cost'=>$patient_online->transaction_cost,
                        'transaction_status'=>$patient_online->transaction_status,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                }else{
                    DB::table('patients_credit_transaction')->insert([    
                        'transaction_id'=> $patient_online->transaction_id, 
                        'reference_no'=>$patient_online->reference_no,
                        'patient_id'=>$patient_online->patient_id,
                        'doctors_id'=>$patient_online->doctors_id,
                        'doctors_service_id'=>$patient_online->doctors_service_id,
                        'transaction_cost'=>$patient_online->transaction_cost,
                        'transaction_status'=>$patient_online->transaction_status,
                        'status'=>$patient_online->status,
                        'updated_at'=>$patient_online->updated_at,
                        'created_at'=>$patient_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}