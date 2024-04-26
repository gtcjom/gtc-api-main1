<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyclinic_products_package extends Model
{ 
    public  static function pharmacyclinic_products_package(){
        // syncronize pharmacyclinic_products_package table from offline to online
        $pharmacy_offline = DB::table('pharmacyclinic_products_package')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyclinic_products_package')->where('ppp_id', $pharmacy_offline->ppp_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyclinic_products_package')->where('ppp_id', $pharmacy_offline->ppp_id)->update([    
                            'ppp_id'=> $pharmacy_offline->ppp_id,
                            'package_id'=>$pharmacy_offline->package_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'package'=>$pharmacy_offline->package,
                            'amount'=>$pharmacy_offline->amount,
                            'product_id'=>$pharmacy_offline->product_id,
                            'product_qty'=>$pharmacy_offline->product_qty,
                            'status'=>$pharmacy_offline->status,
                            'created_at'=>$pharmacy_offline->created_at,
                            'updated_at'=>$pharmacy_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyclinic_products_package')->where('ppp_id', $pharmacy_offline_count[0]->ppp_id)->update([  
                            'ppp_id'=> $pharmacy_offline_count[0]->ppp_id,
                            'package_id'=>$pharmacy_offline_count[0]->package_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'package'=>$pharmacy_offline_count[0]->package,
                            'amount'=>$pharmacy_offline_count[0]->amount,
                            'product_id'=>$pharmacy_offline_count[0]->product_id,
                            'product_qty'=>$pharmacy_offline_count[0]->product_qty,
                            'status'=>$pharmacy_offline_count[0]->status,
                            'created_at'=>$pharmacy_offline_count[0]->created_at,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyclinic_products_package')->insert([ 
                        'ppp_id'=> $pharmacy_offline->ppp_id,
                        'package_id'=>$pharmacy_offline->package_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'package'=>$pharmacy_offline->package,
                        'amount'=>$pharmacy_offline->amount,
                        'product_id'=>$pharmacy_offline->product_id,
                        'product_qty'=>$pharmacy_offline->product_qty,
                        'status'=>$pharmacy_offline->status,
                        'created_at'=>$pharmacy_offline->created_at,
                        'updated_at'=>$pharmacy_offline->updated_at
                    ]); 
                } 
        }

        // syncronize pharmacyclinic_products_package table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyclinic_products_package')->get();
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyclinic_products_package')->where('ppp_id', $pharmacy_online->ppp_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyclinic_products_package')->where('ppp_id', $pharmacy_online->ppp_id)->update([
                        'ppp_id'=> $pharmacy_online->ppp_id,
                        'package_id'=>$pharmacy_online->package_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'package'=>$pharmacy_online->package,
                        'amount'=>$pharmacy_online->amount,
                        'product_id'=>$pharmacy_online->product_id,
                        'product_qty'=>$pharmacy_online->product_qty,
                        'status'=>$pharmacy_online->status,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                }else{
                    DB::table('pharmacyclinic_products_package')->insert([ 
                        'ppp_id'=> $pharmacy_online->ppp_id,
                        'package_id'=>$pharmacy_online->package_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'package'=>$pharmacy_online->package,
                        'amount'=>$pharmacy_online->amount,
                        'product_id'=>$pharmacy_online->product_id,
                        'product_qty'=>$pharmacy_online->product_qty,
                        'status'=>$pharmacy_online->status,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}