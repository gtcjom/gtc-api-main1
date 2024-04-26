<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Cashier_sales extends Model
{ 
    public static function cashier_sales(){ 
        // syncronize cashier_sales table from offline to online  
        $cashier_offline = DB::table('cashier_sales')->get();  
        foreach($cashier_offline as $cashier_offline){  
            $cashier_offline_count = DB::connection('mysql2')->table('cashier_sales')->where('sales_id', $cashier_offline->sales_id)->get();
                if(count($cashier_offline_count) > 0){
                    if($cashier_offline->updated_at > $cashier_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('cashier_sales')->where('sales_id', $cashier_offline->sales_id)->update([      
                            'sales_id'=>$cashier_offline->sales_id,
                            'product_id'=>$cashier_offline->product_id,
                            'cashier_id'=>$cashier_offline->cashier_id,
                            'management_id'=>$cashier_offline->management_id, 
                            'patient_id'=>$cashier_offline->patient_id,
                            'username'=>$cashier_offline->username, 
                            'quantity'=>$cashier_offline->quantity,
                            'invoice'=>$cashier_offline->invoice,
                            'billing_type'=>$cashier_offline->billing_type,
                            'type_discount'=>$cashier_offline->type_discount,
                            'created_at'=>$cashier_offline->created_at,
                            'updated_at'=>$cashier_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('cashier_sales')->where('sales_id', $cashier_offline_count[0]->sales_id)->update([  
                            'sales_id'=>$cashier_offline_count[0]->sales_id,
                            'product_id'=>$cashier_offline_count[0]->product_id,
                            'cashier_id'=>$cashier_offline_count[0]->cashier_id,
                            'management_id'=>$cashier_offline_count[0]->management_id, 
                            'patient_id'=>$cashier_offline_count[0]->patient_id,
                            'username'=>$cashier_offline_count[0]->username, 
                            'quantity'=>$cashier_offline_count[0]->quantity,
                            'invoice'=>$cashier_offline_count[0]->invoice,
                            'billing_type'=>$cashier_offline_count[0]->billing_type,
                            'type_discount'=>$cashier_offline_count[0]->type_discount,
                            'created_at'=>$cashier_offline_count[0]->created_at,
                            'updated_at'=>$cashier_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('cashier_sales')->insert([
                        'sales_id'=>$cashier_offline->sales_id,
                        'product_id'=>$cashier_offline->product_id,
                        'cashier_id'=>$cashier_offline->cashier_id,
                        'management_id'=>$cashier_offline->management_id, 
                        'patient_id'=>$cashier_offline->patient_id,
                        'username'=>$cashier_offline->username, 
                        'quantity'=>$cashier_offline->quantity,
                        'invoice'=>$cashier_offline->invoice,
                        'billing_type'=>$cashier_offline->billing_type,
                        'type_discount'=>$cashier_offline->type_discount,
                        'created_at'=>$cashier_offline->created_at,
                        'updated_at'=>$cashier_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize cashier_sales table from online to offline
        $cashier_online = DB::connection('mysql2')->table('cashier_sales')->get();  
        foreach($cashier_online as $cashier_online){  
            $cashier_online_count = DB::table('cashier_sales')->where('sales_id', $cashier_online->sales_id)->get();
                if(count($cashier_online_count) > 0){
                    DB::table('cashier_sales')->where('sales_id', $cashier_online->sales_id)->update([   
                        'sales_id'=>$cashier_online->sales_id,
                        'product_id'=>$cashier_online->product_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'management_id'=>$cashier_online->management_id, 
                        'patient_id'=>$cashier_online->patient_id,
                        'username'=>$cashier_online->username, 
                        'quantity'=>$cashier_online->quantity,
                        'invoice'=>$cashier_online->invoice,
                        'billing_type'=>$cashier_online->billing_type,
                        'type_discount'=>$cashier_online->type_discount,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                }else{
                    DB::table('cashier_sales')->insert([
                        'sales_id'=>$cashier_online->sales_id,
                        'product_id'=>$cashier_online->product_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'management_id'=>$cashier_online->management_id, 
                        'patient_id'=>$cashier_online->patient_id,
                        'username'=>$cashier_online->username, 
                        'quantity'=>$cashier_online->quantity,
                        'invoice'=>$cashier_online->invoice,
                        'billing_type'=>$cashier_online->billing_type,
                        'type_discount'=>$cashier_online->type_discount,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}