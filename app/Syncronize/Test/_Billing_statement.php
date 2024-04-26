<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Billing_statement extends Model
{ 
    public static function syncronize_billing_statementTable(){
        // syncronize billing_statement table from offline to online 
        $billing_offline = DB::table('billing_statement')->get();  
        foreach($billing_offline as $billing_offline){  
            $billing_offline_count = DB::connection('mysql2')->table('billing_statement')->where('billing_statement_id', $billing_offline->billing_statement_id)->get();
                if(count($billing_offline_count) > 0){
                    if($billing_offline->updated_at > $billing_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('billing_statement')->where('billing_statement_id', $billing_offline->billing_statement_id)->update([      
                            'billing_statement_id'=>$billing_offline->billing_statement_id,
                            'patient_id'=>$billing_offline->patient_id,
                            'encoders_id'=>$billing_offline->encoders_id,
                            'management_id'=>$billing_offline->management_id, 
                            'billing_id'=>$billing_offline->billing_id,
                            'is_package'=>$billing_offline->is_package,
                            'payment_amount'=>$billing_offline->payment_amount,
                            'payment_type'=>$billing_offline->payment_type,
                            'check_no'=>$billing_offline->check_no,
                            'bank'=>$billing_offline->bank,
                            'discount'=>$billing_offline->discount,
                            'discount_reason'=>$billing_offline->discount_reason,
                            'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                            'bill_status'=>$billing_offline->bill_status,
                            'created_at'=>$billing_offline->created_at,
                            'updated_at'=>$billing_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('billing_statement')->where('billing_statement_id', $billing_offline_count[0]->billing_statement_id)->update([  
                            'billing_statement_id'=>$billing_offline_count[0]->billing_statement_id,
                            'patient_id'=>$billing_offline_count[0]->patient_id,
                            'encoders_id'=>$billing_offline_count[0]->encoders_id,
                            'management_id'=>$billing_offline_count[0]->management_id, 
                            'billing_id'=>$billing_offline_count[0]->billing_id,
                            'is_package'=>$billing_offline_count[0]->is_package,
                            'payment_amount'=>$billing_offline_count[0]->payment_amount,
                            'payment_type'=>$billing_offline_count[0]->payment_type,
                            'check_no'=>$billing_offline_count[0]->check_no,
                            'bank'=>$billing_offline_count[0]->bank,
                            'discount'=>$billing_offline_count[0]->discount,
                            'discount_reason'=>$billing_offline_count[0]->discount_reason,
                            'overall_discount_percent'=>$billing_offline_count[0]->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline_count[0]->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline_count[0]->overall_discount_reason,
                            'bill_status'=>$billing_offline_count[0]->bill_status,
                            'created_at'=>$billing_offline_count[0]->created_at,
                            'updated_at'=>$billing_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('billing_statement')->insert([
                        'billing_statement_id'=>$billing_offline->billing_statement_id,
                        'patient_id'=>$billing_offline->patient_id,
                        'encoders_id'=>$billing_offline->encoders_id,
                        'management_id'=>$billing_offline->management_id, 
                        'billing_id'=>$billing_offline->billing_id,
                        'is_package'=>$billing_offline->is_package,
                        'payment_amount'=>$billing_offline->payment_amount,
                        'payment_type'=>$billing_offline->payment_type,
                        'check_no'=>$billing_offline->check_no,
                        'bank'=>$billing_offline->bank,
                        'discount'=>$billing_offline->discount,
                        'discount_reason'=>$billing_offline->discount_reason,
                        'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                        'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                        'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                        'bill_status'=>$billing_offline->bill_status,
                        'created_at'=>$billing_offline->created_at,
                        'updated_at'=>$billing_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize billing_statement table from online to offline
        $billing_online = DB::connection('mysql2')->table('billing_statement')->get();  
        foreach($billing_online as $billing_online){  
            $billing_online_count = DB::table('billing_statement')->where('billing_statement_id', $billing_online->billing_statement_id)->get();
                if(count($billing_online_count) > 0){
                    DB::table('billing_statement')->where('billing_statement_id', $billing_online->billing_statement_id)->update([   
                        'billing_statement_id'=>$billing_online->billing_statement_id,
                        'patient_id' => $billing_online->patient_id,
                        'encoders_id'=>$billing_online->encoders_id,
                        'management_id'=>$billing_online->management_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'payment_amount'=>$billing_online->payment_amount,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'bill_status'=>$billing_online->bill_status,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at
                    ]); 
                }else{
                    DB::table('billing_statement')->insert([
                        'billing_statement_id'=>$billing_online->billing_statement_id,
                        'patient_id' => $billing_online->patient_id,
                        'encoders_id'=>$billing_online->encoders_id,
                        'management_id'=>$billing_online->management_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'payment_amount'=>$billing_online->payment_amount,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'bill_status'=>$billing_online->bill_status,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    }
}