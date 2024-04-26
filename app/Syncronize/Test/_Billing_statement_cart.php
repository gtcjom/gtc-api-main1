<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Billing_statement_cart extends Model
{ 
    public  static function syncronize_billing_statement_cartTable(){
        // syncronize billing_statement_cart by table from offline to online  
        $billing_offline = DB::table('billing_statement_cart')->get();  
        foreach($billing_offline as $billing_offline){  
            $billing_offline_count = DB::connection('mysql2')->table('billing_statement_cart')->where('billing_cart_id', $billing_offline->billing_cart_id)->get();
                if(count($billing_offline_count) > 0){
                    if($billing_offline->updated_at > $billing_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('billing_statement_cart')->where('billing_cart_id', $billing_offline->billing_cart_id)->update([   
                            'billing_cart_id'=>$billing_offline->billing_cart_id,
                            'billing_statement_id'=>$billing_offline->billing_statement_id,
                            'billing_id'=>$billing_offline->billing_id,
                            'is_package'=>$billing_offline->is_package,
                            'patient_id'=>$billing_offline->patient_id, 
                            'billing_user_id'=>$billing_offline->billing_user_id,
                            'product_id'=>$billing_offline->product_id,
                            'product'=>$billing_offline->product,
                            'quantity'=>$billing_offline->quantity,
                            'amount'=>$billing_offline->amount,
                            'discount'=>$billing_offline->discount,
                            'discount_reason'=>$billing_offline->discount_reason,
                            'created_at'=>$billing_offline->created_at,
                            'updated_at'=>$billing_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('billing_statement_cart')->where('billing_cart_id', $billing_offline_count[0]->billing_cart_id)->update([  
                            'billing_cart_id'=>$billing_offline_count[0]->billing_cart_id,
                            'billing_statement_id'=>$billing_offline_count[0]->billing_statement_id,
                            'billing_id'=>$billing_offline_count[0]->billing_id,
                            'is_package'=>$billing_offline_count[0]->is_package,
                            'patient_id'=>$billing_offline_count[0]->patient_id, 
                            'billing_user_id'=>$billing_offline_count[0]->billing_user_id,
                            'product_id'=>$billing_offline_count[0]->product_id,
                            'product'=>$billing_offline_count[0]->product,
                            'quantity'=>$billing_offline_count[0]->quantity,
                            'amount'=>$billing_offline_count[0]->amount,
                            'discount'=>$billing_offline_count[0]->discount,
                            'discount_reason'=>$billing_offline_count[0]->discount_reason,
                            'created_at'=>$billing_offline_count[0]->created_at,
                            'updated_at'=>$billing_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('billing_statement_cart')->insert([
                        'billing_cart_id'=>$billing_offline->billing_cart_id,
                        'billing_statement_id'=>$billing_offline->billing_statement_id,
                        'billing_id'=>$billing_offline->billing_id,
                        'is_package'=>$billing_offline->is_package,
                        'patient_id'=>$billing_offline->patient_id, 
                        'billing_user_id'=>$billing_offline->billing_user_id,
                        'product_id'=>$billing_offline->product_id,
                        'product'=>$billing_offline->product,
                        'quantity'=>$billing_offline->quantity,
                        'amount'=>$billing_offline->amount,
                        'discount'=>$billing_offline->discount,
                        'discount_reason'=>$billing_offline->discount_reason,
                        'created_at'=>$billing_offline->created_at,
                        'updated_at'=>$billing_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize billing_statement_cart table from online to offline
        $billing_online = DB::connection('mysql2')->table('billing_statement_cart')->get();  
        foreach($billing_online as $billing_online){  
            $billing_online_count = DB::table('billing_statement_cart')->where('billing_cart_id', $billing_online->billing_cart_id)->get();
                if(count($billing_online_count) > 0){
                    DB::table('billing_statement_cart')->where('billing_cart_id', $billing_online->billing_cart_id)->update([   
                        'billing_cart_id'=>$billing_online->billing_cart_id,
                        'billing_statement_id'=>$billing_online->billing_statement_id,
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'quantity'=>$billing_online->quantity,
                        'amount'=>$billing_online->amount,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at
                    ]); 
                }else{
                    DB::table('billing_statement_cart')->insert([
                        'billing_cart_id'=>$billing_online->billing_cart_id,
                        'billing_statement_id'=>$billing_online->billing_statement_id,
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'quantity'=>$billing_online->quantity,
                        'amount'=>$billing_online->amount,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    }
}