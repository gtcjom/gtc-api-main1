<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Cashier_temporary_out extends Model
{ 
    public static function cashier_tempory_out(){ 
        // syncronize cashier_tempory_out table from offline to online  
        $cashier_offline = DB::table('cashier_tempory_out')->get();  
        foreach($cashier_offline as $cashier_offline){  
            $cashier_offline_count = DB::connection('mysql2')->table('cashier_tempory_out')->where('purchase_id', $cashier_offline->purchase_id)->get();
                if(count($cashier_offline_count) > 0){
                    if($cashier_offline->updated_at > $cashier_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('cashier_tempory_out')->where('purchase_id', $cashier_offline->purchase_id)->update([      
                            'purchase_id'=>$cashier_offline->purchase_id,
                            'cashier_id'=>$cashier_offline->cashier_id,
                            'management_id'=>$cashier_offline->management_id,
                            'product'=>$cashier_offline->product, 
                            'product_id'=>$cashier_offline->product_id,
                            'username'=>$cashier_offline->username, 
                            'description'=>$cashier_offline->description,
                            'supplier'=>$cashier_offline->supplier,
                            'purchase_quantity'=>$cashier_offline->purchase_quantity,
                            'unit'=>$cashier_offline->unit,
                            'price'=>$cashier_offline->price,
                            'total'=>$cashier_offline->total
                        ]);  
                    } 
                    
                    else{
                        DB::table('cashier_tempory_out')->where('purchase_id', $cashier_offline_count[0]->purchase_id)->update([  
                            'purchase_id'=>$cashier_offline_count[0]->purchase_id,
                            'cashier_id'=>$cashier_offline_count[0]->cashier_id,
                            'management_id'=>$cashier_offline_count[0]->management_id,
                            'product'=>$cashier_offline_count[0]->product, 
                            'product_id'=>$cashier_offline_count[0]->product_id,
                            'username'=>$cashier_offline_count[0]->username, 
                            'description'=>$cashier_offline_count[0]->description,
                            'supplier'=>$cashier_offline_count[0]->supplier,
                            'purchase_quantity'=>$cashier_offline_count[0]->purchase_quantity,
                            'unit'=>$cashier_offline_count[0]->unit,
                            'price'=>$cashier_offline_count[0]->price,
                            'total'=>$cashier_offline_count[0]->total
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('cashier_tempory_out')->insert([
                        'purchase_id'=>$cashier_offline->purchase_id,
                        'cashier_id'=>$cashier_offline->cashier_id,
                        'management_id'=>$cashier_offline->management_id,
                        'product'=>$cashier_offline->product, 
                        'product_id'=>$cashier_offline->product_id,
                        'username'=>$cashier_offline->username, 
                        'description'=>$cashier_offline->description,
                        'supplier'=>$cashier_offline->supplier,
                        'purchase_quantity'=>$cashier_offline->purchase_quantity,
                        'unit'=>$cashier_offline->unit,
                        'price'=>$cashier_offline->price,
                        'total'=>$cashier_offline->total
                    ]);  
                } 
        } 
     
        // syncronize cashier_tempory_out table from online to offline
        $cashier_online = DB::connection('mysql2')->table('cashier_tempory_out')->get();  
        foreach($cashier_online as $cashier_online){  
            $cashier_online_count = DB::table('cashier_tempory_out')->where('purchase_id', $cashier_online->purchase_id)->get();
                if(count($cashier_online_count) > 0){
                    DB::table('cashier_tempory_out')->where('purchase_id', $cashier_online->purchase_id)->update([   
                        'purchase_id'=>$cashier_online->purchase_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'management_id'=>$cashier_online->management_id,
                        'product'=>$cashier_online->product, 
                        'product_id'=>$cashier_online->product_id,
                        'username'=>$cashier_online->username, 
                        'description'=>$cashier_online->description,
                        'supplier'=>$cashier_online->supplier,
                        'purchase_quantity'=>$cashier_online->purchase_quantity,
                        'unit'=>$cashier_online->unit,
                        'price'=>$cashier_online->price,
                        'total'=>$cashier_online->total
                    ]); 
                }else{
                    DB::table('cashier_tempory_out')->insert([
                        'purchase_id'=>$cashier_online->purchase_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'management_id'=>$cashier_online->management_id,
                        'product'=>$cashier_online->product, 
                        'product_id'=>$cashier_online->product_id,
                        'username'=>$cashier_online->username, 
                        'description'=>$cashier_online->description,
                        'supplier'=>$cashier_online->supplier,
                        'purchase_quantity'=>$cashier_online->purchase_quantity,
                        'unit'=>$cashier_online->unit,
                        'price'=>$cashier_online->price,
                        'total'=>$cashier_online->total
                    ]); 
                } 
        } 
        return true;
    } 
}