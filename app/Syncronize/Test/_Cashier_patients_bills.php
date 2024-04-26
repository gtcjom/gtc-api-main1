<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Cashier_patients_bills extends Model
{ 
    public static function billing_payment_history(){ 
        // syncronize cashier_patients_bills table from offline to online  
        $cashier_offline = DB::table('cashier_patients_bills')->get();  
        foreach($cashier_offline as $cashier_offline){  
            $cashier_offline_count = DB::connection('mysql2')->table('cashier_patients_bills')->where('cpb_id', $cashier_offline->cpb_id)->get();
                if(count($cashier_offline_count) > 0){
                    if($cashier_offline->updated_at > $cashier_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('cashier_patients_bills')->where('cpb_id', $cashier_offline->c_id)->update([      
                            'c_id'=>$cashier_offline->c_id,
                            'cashier_id'=>$cashier_offline->cashier_id,
                            'patient_id'=>$cashier_offline->patient_id,
                            'doctors_id'=>$cashier_offline->doctors_id, 
                            'ward_nurse_id'=>$cashier_offline->ward_nurse_id,
                            'management_id'=>$cashier_offline->management_id, 
                            'billing_id'=>$cashier_offline->billing_id,
                            'case_file'=>$cashier_offline->case_file,
                            'amount_category'=>$cashier_offline->amount_category,
                            'quantity'=>$cashier_offline->quantity,
                            'billing_type'=>$cashier_offline->billing_type,
                            'billing_status'=>$cashier_offline->billing_status,
                            'created_at'=>$cashier_offline->created_at,
                            'updated_at'=>$cashier_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('cashier_patients_bills')->where('c_id', $cashier_offline_count[0]->c_id)->update([  
                            'c_id'=>$cashier_offline_count[0]->c_id,
                            'cashier_id'=>$cashier_offline_count[0]->cashier_id,
                            'patient_id'=>$cashier_offline_count[0]->patient_id,
                            'doctors_id'=>$cashier_offline_count[0]->doctors_id, 
                            'ward_nurse_id'=>$cashier_offline_count[0]->ward_nurse_id,
                            'management_id'=>$cashier_offline_count[0]->management_id, 
                            'billing_id'=>$cashier_offline_count[0]->billing_id,
                            'case_file'=>$cashier_offline_count[0]->case_file,
                            'amount_category'=>$cashier_offline_count[0]->amount_category,
                            'quantity'=>$cashier_offline_count[0]->quantity,
                            'billing_type'=>$cashier_offline_count[0]->billing_type,
                            'billing_status'=>$cashier_offline_count[0]->billing_status,
                            'created_at'=>$cashier_offline_count[0]->created_at,
                            'updated_at'=>$cashier_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('cashier_patients_bills')->insert([
                        'c_id'=>$cashier_offline->c_id,
                        'cashier_id'=>$cashier_offline->cashier_id,
                        'patient_id'=>$cashier_offline->patient_id,
                        'doctors_id'=>$cashier_offline->doctors_id, 
                        'ward_nurse_id'=>$cashier_offline->ward_nurse_id,
                        'management_id'=>$cashier_offline->management_id, 
                        'billing_id'=>$cashier_offline->billing_id,
                        'case_file'=>$cashier_offline->case_file,
                        'amount_category'=>$cashier_offline->amount_category,
                        'quantity'=>$cashier_offline->quantity,
                        'billing_type'=>$cashier_offline->billing_type,
                        'billing_status'=>$cashier_offline->billing_status,
                        'created_at'=>$cashier_offline->created_at,
                        'updated_at'=>$cashier_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize cashier_patients_bills table from online to offline
        $cashier_online = DB::connection('mysql2')->table('cashier_patients_bills')->get();  
        foreach($cashier_online as $cashier_online){  
            $cashier_online_count = DB::table('cashier_patients_bills')->where('c_id', $cashier_online->c_id)->get();
                if(count($cashier_online_count) > 0){
                    DB::table('cashier_patients_bills')->where('c_id', $cashier_online->c_id)->update([   
                        'c_id'=>$cashier_online->c_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'patient_id'=>$cashier_online->patient_id,
                        'doctors_id'=>$cashier_online->doctors_id, 
                        'ward_nurse_id'=>$cashier_online->ward_nurse_id,
                        'management_id'=>$cashier_online->management_id, 
                        'billing_id'=>$cashier_online->billing_id,
                        'case_file'=>$cashier_online->case_file,
                        'amount_category'=>$cashier_online->amount_category,
                        'quantity'=>$cashier_online->quantity,
                        'billing_type'=>$cashier_online->billing_type,
                        'billing_status'=>$cashier_online->billing_status,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                }else{
                    DB::table('cashier_patients_bills')->insert([
                        'c_id'=>$cashier_online->c_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'patient_id'=>$cashier_online->patient_id,
                        'doctors_id'=>$cashier_online->doctors_id, 
                        'ward_nurse_id'=>$cashier_online->ward_nurse_id,
                        'management_id'=>$cashier_online->management_id, 
                        'billing_id'=>$cashier_online->billing_id,
                        'case_file'=>$cashier_online->case_file,
                        'amount_category'=>$cashier_online->amount_category,
                        'quantity'=>$cashier_online->quantity,
                        'billing_type'=>$cashier_online->billing_type,
                        'billing_status'=>$cashier_online->billing_status,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    } 
}