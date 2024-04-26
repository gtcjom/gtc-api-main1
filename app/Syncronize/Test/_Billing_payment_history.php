<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Billing_payment_history extends Model
{ 
    public static function billing_payment_history(){ 
        // syncronize billing_payment_history table from offline to online  
        $billing = DB::table('billing_payment_history')->get();  
        foreach($billing as $billing){  
            $billing_count = DB::connection('mysql2')->table('billing_payment_history')->where('payment_history_id', $billing->payment_history_id)->get();
                if(count($billing_count) > 0){  
                    if($billing->updated_at > $billing_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('billing_payment_history')->where('payment_history_id', $billing->payment_history_id)->update([   
                            'payment_history_id'=>$billing->payment_history_id,
                            'billing_statement_id'=>$billing->billing_statement_id,
                            'billing_id'=>$billing->billing_id,
                            'billing_user_id'=>$billing->billing_user_id, 
                            'patient_id'=>$billing->patient_id,
                            'payment_amount'=>$billing->payment_amount,
                            'payment_type'=>$billing->payment_type,
                            'check_no'=>$billing->check_no,
                            'bank'=>$billing->bank,  
                            'discount'=>$billing->discount, 
                            'discount_reason'=>$billing->discount_reason, 
                            'overall_discount_percent'=>$billing->overall_discount_percent, 
                            'overall_discount_amount'=>$billing->overall_discount_amount, 
                            'overall_discount_reason'=>$billing->overall_discount_reason,
                            'created_at'=>$billing->created_at,
                            'updated_at'=>$billing->updated_at
                        ]);  
                    }  
                    else{
                        DB::table('billing_payment_history')->where('payment_history_id', $billing_count[0]->payment_history_id)->update([  
                            'payment_history_id'=>$billing_count[0]->payment_history_id,
                            'billing_statement_id'=>$billing_count[0]->billing_statement_id,
                            'billing_id'=>$billing_count[0]->billing_id,
                            'billing_user_id'=>$billing_count[0]->billing_user_id, 
                            'patient_id'=>$billing_count[0]->patient_id,
                            'payment_amount'=>$billing_count[0]->payment_amount,
                            'payment_type'=>$billing_count[0]->payment_type,
                            'check_no'=>$billing_count[0]->check_no,
                            'bank'=>$billing_count[0]->bank,  
                            'discount'=>$billing_count[0]->discount, 
                            'discount_reason'=>$billing_count[0]->discount_reason, 
                            'overall_discount_percent'=>$billing_count[0]->overall_discount_percent, 
                            'overall_discount_amount'=>$billing_count[0]->overall_discount_amount, 
                            'overall_discount_reason'=>$billing_count[0]->overall_discount_reason,
                            'created_at'=>$billing_count[0]->created_at,
                            'updated_at'=>$billing_count[0]->updated_at
                        ]);
                    } 
                }
                else{ 
                    DB::connection('mysql2')->table('billing_payment_history')->insert([
                        'payment_history_id'=>$billing->payment_history_id,
                        'billing_statement_id'=>$billing->billing_statement_id,
                        'billing_id'=>$billing->billing_id,
                        'billing_user_id'=>$billing->billing_user_id, 
                        'patient_id'=>$billing->patient_id,
                        'payment_amount'=>$billing->payment_amount,
                        'payment_type'=>$billing->payment_type,
                        'check_no'=>$billing->check_no,
                        'bank'=>$billing->bank,  
                        'discount'=>$billing->discount, 
                        'discount_reason'=>$billing->discount_reason, 
                        'overall_discount_percent'=>$billing->overall_discount_percent, 
                        'overall_discount_amount'=>$billing->overall_discount_amount, 
                        'overall_discount_reason'=>$billing->overall_discount_reason,
                        'created_at'=>$billing->created_at,
                        'updated_at'=>$billing->updated_at
                    ]);  
                } 
        } 
     
        // syncronize billing_payment_history table from online to offline 
        $billing_hist_online = DB::connection('mysql2')->table('billing_payment_history')->get();  
        foreach($billing_hist_online as $billing_hist_online){  
            $billing_hist_online_count = DB::table('billing_payment_history')->where('payment_history_id', $billing_hist_online->payment_history_id)->get();
                if(count($billing_hist_online_count) > 0){
                    DB::table('billing_payment_history')->where('payment_history_id', $billing_hist_online->payment_history_id)->update([   
                        'payment_history_id'=>$billing_hist_online->payment_history_id,
                        'billing_statement_id'=>$billing_hist_online->billing_statement_id,
                        'billing_id'=>$billing_hist_online->billing_id,
                        'billing_user_id'=>$billing_hist_online->billing_user_id, 
                        'patient_id'=>$billing_hist_online->patient_id,
                        'payment_amount'=>$billing_hist_online->payment_amount,
                        'payment_type'=>$billing_hist_online->payment_type,
                        'check_no'=>$billing_hist_online->check_no,
                        'bank'=>$billing_hist_online->bank,  
                        'discount'=>$billing_hist_online->discount, 
                        'discount_reason'=>$billing_hist_online->discount_reason, 
                        'overall_discount_percent'=>$billing_hist_online->overall_discount_percent, 
                        'overall_discount_amount'=>$billing_hist_online->overall_discount_amount, 
                        'overall_discount_reason'=>$billing_hist_online->overall_discount_reason,
                        'created_at'=>$billing_hist_online->created_at,
                        'updated_at'=>$billing_hist_online->updated_at
                    ]); 
                }else{
                    DB::table('billing_payment_history')->insert([     
                        'payment_history_id'=>$billing_hist_online->payment_history_id,
                        'billing_statement_id'=>$billing_hist_online->billing_statement_id,
                        'billing_id'=>$billing_hist_online->billing_id,
                        'billing_user_id'=>$billing_hist_online->billing_user_id, 
                        'patient_id'=>$billing_hist_online->patient_id,
                        'payment_amount'=>$billing_hist_online->payment_amount,
                        'payment_type'=>$billing_hist_online->payment_type,
                        'check_no'=>$billing_hist_online->check_no,
                        'bank'=>$billing_hist_online->bank,  
                        'discount'=>$billing_hist_online->discount, 
                        'discount_reason'=>$billing_hist_online->discount_reason, 
                        'overall_discount_percent'=>$billing_hist_online->overall_discount_percent, 
                        'overall_discount_amount'=>$billing_hist_online->overall_discount_amount, 
                        'overall_discount_reason'=>$billing_hist_online->overall_discount_reason,
                        'created_at'=>$billing_hist_online->created_at,
                        'updated_at'=>$billing_hist_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    } 
}