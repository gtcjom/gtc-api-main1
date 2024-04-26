<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyclinic_products_package_temp extends Model
{ 
    public static function pharmacyclinic_products_package_temp(){
        // syncronize pharmacyclinic_products_package_temp table from offline to online
        $pharmacy_offline = DB::table('pharmacyclinic_products_package_temp')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyclinic_products_package_temp')->where('pppt_id', $pharmacy_offline->pppt_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyclinic_products_package_temp')->where('pppt_id', $pharmacy_offline->pppt_id)->update([    
                            'pppt_id'=> $pharmacy_offline->pppt_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'product_id'=>$pharmacy_offline->product_id,
                            'product_qty'=>$pharmacy_offline->product_qty,
                            'status'=>$pharmacy_offline->status,
                            'updated_at'=>$pharmacy_offline->updated_at,
                            'created_at'=>$pharmacy_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyclinic_products_package_temp')->where('pppt_id', $pharmacy_offline_count[0]->pppt_id)->update([  
                            'pppt_id'=> $pharmacy_offline_count[0]->pppt_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'product_id'=>$pharmacy_offline_count[0]->product_id,
                            'product_qty'=>$pharmacy_offline_count[0]->product_qty,
                            'status'=>$pharmacy_offline_count[0]->status,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at,
                            'created_at'=>$pharmacy_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyclinic_products_package_temp')->insert([
                        'pppt_id'=> $pharmacy_offline->pppt_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'product_id'=>$pharmacy_offline->product_id,
                        'product_qty'=>$pharmacy_offline->product_qty,
                        'status'=>$pharmacy_offline->status,
                        'updated_at'=>$pharmacy_offline->updated_at,
                        'created_at'=>$pharmacy_offline->created_at
                    ]); 
                } 
        }

        // syncronize pharmacyclinic_products_package_temp table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyclinic_products_package_temp')->get();
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyclinic_products_package_temp')->where('pppt_id', $pharmacy_online->pppt_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyclinic_products_package_temp')->where('pppt_id', $pharmacy_online->pppt_id)->update([
                        'pppt_id'=> $pharmacy_online->pppt_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'product_id'=>$pharmacy_online->product_id,
                        'product_qty'=>$pharmacy_online->product_qty,
                        'status'=>$pharmacy_online->status,
                        'updated_at'=>$pharmacy_online->updated_at,
                        'created_at'=>$pharmacy_online->created_at
                    ]); 
                }else{
                    DB::table('pharmacyclinic_products_package_temp')->insert([ 
                        'pppt_id'=> $pharmacy_online->pppt_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'product_id'=>$pharmacy_online->product_id,
                        'product_qty'=>$pharmacy_online->product_qty,
                        'status'=>$pharmacy_online->status,
                        'updated_at'=>$pharmacy_online->updated_at,
                        'created_at'=>$pharmacy_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}