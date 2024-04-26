<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Cashier extends Model
{ 
    public static function billing_payment_history(){ 
        // syncronize cashier table from offline to online  
        $cashier_offline = DB::table('cashier')->get();  
        foreach($cashier_offline as $cashier_offline){  
            $cashier_offline_count = DB::connection('mysql2')->table('cashier')->where('c_id', $cashier_offline->c_id)->get();
                if(count($cashier_offline_count) > 0){
                    if($cashier_offline->updated_at > $cashier_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('cashier')->where('c_id', $cashier_offline->c_id)->update([      
                            'c_id'=>$cashier_offline->c_id,
                            'cashier_id'=>$cashier_offline->cashier_id,
                            'user_id'=>$cashier_offline->user_id,
                            'management_id'=>$cashier_offline->management_id, 
                            'name'=>$cashier_offline->name,
                            'gender'=>$cashier_offline->gender, 
                            'address'=>$cashier_offline->address,
                            'birthday'=>$cashier_offline->birthday,
                            'role'=>$cashier_offline->role,
                            'status'=>$cashier_offline->status,
                            'added_by'=>$cashier_offline->added_by,
                            'created_at'=>$cashier_offline->created_at,
                            'updated_at'=>$cashier_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('cashier')->where('c_id', $cashier_offline_count[0]->c_id)->update([  
                            'c_id'=>$cashier_offline_count[0]->c_id,
                            'cashier_id'=>$cashier_offline_count[0]->cashier_id,
                            'user_id'=>$cashier_offline_count[0]->user_id,
                            'management_id'=>$cashier_offline_count[0]->management_id, 
                            'name'=>$cashier_offline_count[0]->name,
                            'gender'=>$cashier_offline_count[0]->gender, 
                            'address'=>$cashier_offline_count[0]->address,
                            'birthday'=>$cashier_offline_count[0]->birthday,
                            'role'=>$cashier_offline_count[0]->role,
                            'status'=>$cashier_offline_count[0]->status,
                            'added_by'=>$cashier_offline_count[0]->added_by,
                            'created_at'=>$cashier_offline_count[0]->created_at,
                            'updated_at'=>$cashier_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('cashier')->insert([
                        'c_id'=>$cashier_offline->c_id,
                        'cashier_id'=>$cashier_offline->cashier_id,
                        'user_id'=>$cashier_offline->user_id,
                        'management_id'=>$cashier_offline->management_id, 
                        'name'=>$cashier_offline->name,
                        'gender'=>$cashier_offline->gender, 
                        'address'=>$cashier_offline->address,
                        'birthday'=>$cashier_offline->birthday,
                        'role'=>$cashier_offline->role,
                        'status'=>$cashier_offline->status,
                        'added_by'=>$cashier_offline->added_by,
                        'created_at'=>$cashier_offline->created_at,
                        'updated_at'=>$cashier_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize cashier table from online to offline
        $cashier_online = DB::connection('mysql2')->table('cashier')->get();  
        foreach($cashier_online as $cashier_online){  
            $cashier_online_count = DB::table('cashier')->where('c_id', $cashier_online->c_id)->get();
                if(count($cashier_online_count) > 0){
                    DB::table('cashier')->where('c_id', $cashier_online->c_id)->update([   
                        'c_id'=>$cashier_online->c_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'user_id'=>$cashier_online->user_id,
                        'management_id'=>$cashier_online->management_id, 
                        'name'=>$cashier_online->name,
                        'gender'=>$cashier_online->gender, 
                        'address'=>$cashier_online->address,
                        'birthday'=>$cashier_online->birthday,
                        'role'=>$cashier_online->role,
                        'status'=>$cashier_online->status,
                        'added_by'=>$cashier_online->added_by,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                }else{
                    DB::table('cashier')->insert([
                        'c_id'=>$cashier_online->c_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'user_id'=>$cashier_online->user_id,
                        'management_id'=>$cashier_online->management_id, 
                        'name'=>$cashier_online->name,
                        'gender'=>$cashier_online->gender, 
                        'address'=>$cashier_online->address,
                        'birthday'=>$cashier_online->birthday,
                        'role'=>$cashier_online->role,
                        'status'=>$cashier_online->status,
                        'added_by'=>$cashier_online->added_by,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    } 
}