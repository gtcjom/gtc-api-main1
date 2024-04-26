<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Bill_list extends Model
{ 
    public static function billing_payment_history(){ 
        // syncronize bill_list table from offline to online  
        $bill_list_offline = DB::table('bill_list')->get();  
        foreach($bill_list_offline as $bill_list_offline){  
            $bill_list_offline_count = DB::connection('mysql2')->table('bill_list')->where('billing_id', $bill_list_offline->billing_id)->get();
                if(count($bill_list_offline_count) > 0){
                    if($bill_list_offline->updated_at > $bill_list_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('bill_list')->where('billing_id', $bill_list_offline->billing_id)->update([      
                            'billing_id'=>$bill_list_offline->billing_id,
                            'encoders_id'=>$bill_list_offline->encoders_id,
                            'management_id'=>$bill_list_offline->management_id,
                            'billing'=>$bill_list_offline->billing, 
                            'amount'=>$bill_list_offline->amount,
                            'status'=>$bill_list_offline->status, 
                            'created_at'=>$bill_list_offline->created_at,
                            'updated_at'=>$bill_list_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('bill_list')->where('billing_id', $bill_list_offline_count[0]->billing_id)->update([  
                            'billing_id'=>$bill_list_offline_count[0]->billing_id,
                            'encoders_id'=>$bill_list_offline_count[0]->encoders_id,
                            'management_id'=>$bill_list_offline_count[0]->management_id,
                            'billing'=>$bill_list_offline_count[0]->billing, 
                            'amount'=>$bill_list_offline_count[0]->amount,
                            'status'=>$bill_list_offline_count[0]->status, 
                            'created_at'=>$bill_list_offline_count[0]->created_at,
                            'updated_at'=>$bill_list_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('bill_list')->insert([
                        'billing_id'=>$bill_list_offline->billing_id,
                        'encoders_id'=>$bill_list_offline->encoders_id,
                        'management_id'=>$bill_list_offline->management_id,
                        'billing'=>$bill_list_offline->billing, 
                        'amount'=>$bill_list_offline->amount,
                        'status'=>$bill_list_offline->status, 
                        'created_at'=>$bill_list_offline->created_at,
                        'updated_at'=>$bill_list_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize bill_list table from online to offline
        $bill_list_online = DB::connection('mysql2')->table('bill_list')->get();  
        foreach($bill_list_online as $bill_list_online){  
            $bill_list_online_count = DB::table('bill_list')->where('billing_id', $bill_list_online->billing_id)->get();
                if(count($bill_list_online_count) > 0){
                    DB::table('bill_list')->where('billing_id', $bill_list_online->billing_id)->update([   
                        'billing_id'=>$bill_list_online->billing_id,
                        'encoders_id'=>$bill_list_online->encoders_id,
                        'management_id'=>$bill_list_online->management_id,
                        'billing'=>$bill_list_online->billing, 
                        'amount'=>$bill_list_online->amount,
                        'status'=>$bill_list_online->status, 
                        'created_at'=>$bill_list_online->created_at,
                        'updated_at'=>$bill_list_online->updated_at
                    ]); 
                }else{
                    DB::table('bill_list')->insert([
                        'billing_id'=>$bill_list_online->billing_id,
                        'encoders_id'=>$bill_list_online->encoders_id,
                        'management_id'=>$bill_list_online->management_id,
                        'billing'=>$bill_list_online->billing, 
                        'amount'=>$bill_list_online->amount,
                        'status'=>$bill_list_online->status, 
                        'created_at'=>$bill_list_online->created_at,
                        'updated_at'=>$bill_list_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    } 
}