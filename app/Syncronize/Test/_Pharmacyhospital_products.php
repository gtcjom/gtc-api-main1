<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyhospital_products extends Model
{ 
    public  static function pharmacyhospital_products(){
        // syncronize pharmacyhospital_products table from offline to online
        $pharmacy_offline = DB::table('pharmacyhospital_products')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyhospital_products')->where('product_id', $pharmacy_offline->product_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyhospital_products')->where('product_id', $pharmacy_offline->product_id)->update([    
                            'product_id'=> $pharmacy_offline->product_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'product'=>$pharmacy_offline->product,
                            'description'=>$pharmacy_offline->description,
                            'supplier'=>$pharmacy_offline->supplier,
                            'msrp'=>$pharmacy_offline->msrp,
                            'srp'=>$pharmacy_offline->srp,
                            'created_at'=>$pharmacy_offline->created_at,
                            'updated_at'=>$pharmacy_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyhospital_products')->where('product_id', $pharmacy_offline_count[0]->product_id)->update([  
                            'product_id'=> $pharmacy_offline_count[0]->product_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'product'=>$pharmacy_offline_count[0]->product,
                            'description'=>$pharmacy_offline_count[0]->description,
                            'supplier'=>$pharmacy_offline_count[0]->supplier,
                            'msrp'=>$pharmacy_offline_count[0]->msrp,
                            'srp'=>$pharmacy_offline_count[0]->srp,
                            'created_at'=>$pharmacy_offline_count[0]->created_at,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyhospital_products')->insert([
                        'product_id'=> $pharmacy_offline->product_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'product'=>$pharmacy_offline->product,
                        'description'=>$pharmacy_offline->description,
                        'supplier'=>$pharmacy_offline->supplier,
                        'msrp'=>$pharmacy_offline->msrp,
                        'srp'=>$pharmacy_offline->srp,
                        'created_at'=>$pharmacy_offline->created_at,
                        'updated_at'=>$pharmacy_offline->updated_at
                    ]); 
                } 
        }

        // syncronize pharmacyhospital_products table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyhospital_products')->get();
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyhospital_products')->where('product_id', $pharmacy_online->product_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyhospital_products')->where('product_id', $pharmacy_online->product_id)->update([
                        'product_id'=> $pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'supplier'=>$pharmacy_online->supplier,
                        'msrp'=>$pharmacy_online->msrp,
                        'srp'=>$pharmacy_online->srp,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                }else{
                    DB::table('pharmacyhospital_products')->insert([ 
                        'product_id'=> $pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'supplier'=>$pharmacy_online->supplier,
                        'msrp'=>$pharmacy_online->msrp,
                        'srp'=>$pharmacy_online->srp,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}