<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Billing_receipt extends Model
{ 
    public static function syncronize_billing_receiptTable(){
        // syncronize billing_receipt table from offline to online 
        $billing_receipt_offline = DB::table('billing_receipt')->get();  
        foreach($billing_receipt_offline as $billing_offline){  
            $billing_offline_count = DB::connection('mysql2')->table('billing_receipt')->where('receipt_id', $billing_offline->receipt_id)->get();
                if(count($billing_offline_count) > 0){
                    if($billing_offline->updated_at > $billing_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('billing_receipt')->where('receipt_id', $billing_offline->receipt_id)->update([      
                            'receipt_id'=>$billing_offline->receipt_id,
                            'payment_id'=>$billing_offline->payment_id,
                            'billing_user_id'=>$billing_offline->billing_user_id,
                            'patient_id'=>$billing_offline->patient_id, 
                            'billing_id'=>$billing_offline->billing_id,
                            'product_id'=>$billing_offline->product_id,
                            'product'=>$billing_offline->product,
                            'discount_per_product'=>$billing_offline->discount_per_product,
                            'quantity'=>$billing_offline->quantity,
                            'amount_pay'=>$billing_offline->amount_pay,
                            'payment_type'=>$billing_offline->payment_type,
                            'check_no'=>$billing_offline->check_no,
                            'bank'=>$billing_offline->bank,
                            'payment'=>$billing_offline->payment,
                            'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                            'status'=>$billing_offline->status,
                            'created_at'=>$billing_offline->created_at,
                            'updated_at'=>$billing_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('billing_receipt')->where('receipt_id', $billing_offline_count[0]->receipt_id)->update([  
                            'receipt_id'=>$billing_offline_count[0]->receipt_id,
                            'payment_id'=>$billing_offline_count[0]->payment_id,
                            'billing_user_id'=>$billing_offline_count[0]->billing_user_id,
                            'patient_id'=>$billing_offline_count[0]->patient_id, 
                            'billing_id'=>$billing_offline_count[0]->billing_id,
                            'product_id'=>$billing_offline_count[0]->product_id,
                            'product'=>$billing_offline_count[0]->product,
                            'discount_per_product'=>$billing_offline_count[0]->discount_per_product,
                            'quantity'=>$billing_offline_count[0]->quantity,
                            'amount_pay'=>$billing_offline_count[0]->amount_pay,
                            'payment_type'=>$billing_offline_count[0]->payment_type,
                            'check_no'=>$billing_offline_count[0]->check_no,
                            'bank'=>$billing_offline_count[0]->bank,
                            'payment'=>$billing_offline_count[0]->payment,
                            'overall_discount_percent'=>$billing_offline_count[0]->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline_count[0]->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline_count[0]->overall_discount_reason,
                            'status'=>$billing_offline_count[0]->status,
                            'created_at'=>$billing_offline_count[0]->created_at,
                            'updated_at'=>$billing_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('billing_receipt')->insert([
                        'receipt_id'=>$billing_offline->receipt_id,
                        'payment_id'=>$billing_offline->payment_id,
                        'billing_user_id'=>$billing_offline->billing_user_id,
                        'patient_id'=>$billing_offline->patient_id, 
                        'billing_id'=>$billing_offline->billing_id,
                        'product_id'=>$billing_offline->product_id,
                        'product'=>$billing_offline->product,
                        'discount_per_product'=>$billing_offline->discount_per_product,
                        'quantity'=>$billing_offline->quantity,
                        'amount_pay'=>$billing_offline->amount_pay,
                        'payment_type'=>$billing_offline->payment_type,
                        'check_no'=>$billing_offline->check_no,
                        'bank'=>$billing_offline->bank,
                        'payment'=>$billing_offline->payment,
                        'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                        'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                        'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                        'status'=>$billing_offline->status,
                        'created_at'=>$billing_offline->created_at,
                        'updated_at'=>$billing_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize billing_receipt table from online to offline
        $billing_online = DB::connection('mysql2')->table('billing_receipt')->get();  
        foreach($billing_online as $billing_online){  
            $billing_online_count = DB::table('billing_receipt')->where('receipt_id', $billing_online->receipt_id)->get();
                if(count($billing_online_count) > 0){
                    DB::table('billing_receipt')->where('receipt_id', $billing_online->receipt_id)->update([   
                        'receipt_id'=>$billing_online->receipt_id,
                        'payment_id'=>$billing_online->payment_id,
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'discount_per_product'=>$billing_online->discount_per_product,
                        'quantity'=>$billing_online->quantity,
                        'amount_pay'=>$billing_online->amount_pay,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'payment'=>$billing_online->payment,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'status'=>$billing_online->status,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at
                    ]); 
                }else{
                    DB::table('billing_receipt')->insert([
                        'receipt_id'=>$billing_online->receipt_id,
                        'payment_id'=>$billing_online->payment_id,
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'discount_per_product'=>$billing_online->discount_per_product,
                        'quantity'=>$billing_online->quantity,
                        'amount_pay'=>$billing_online->amount_pay,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'payment'=>$billing_online->payment,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'status'=>$billing_online->status,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    }
}