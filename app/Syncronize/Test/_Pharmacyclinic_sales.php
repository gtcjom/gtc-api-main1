<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyclinic_sales extends Model
{ 
    public static function pharmacyclinic_sales(){
        // syncronize pharmacyclinic_sales table from offline to online
        $pharmacy_offline = DB::table('pharmacyclinic_sales')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyclinic_sales')->where('sales_id', $pharmacy_offline->sales_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyclinic_sales')->where('sales_id', $pharmacy_offline->sales_id)->update([    
                            'sales_id'=> $pharmacy_offline->sales_id,
                            'product_id'=>$pharmacy_offline->product_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'username'=>$pharmacy_offline->username,
                            'product'=>$pharmacy_offline->product,
                            'description'=>$pharmacy_offline->description,
                            'unit'=>$pharmacy_offline->unit,
                            'quantity'=>$pharmacy_offline->quantity,
                            'total'=>$pharmacy_offline->total,
                            'dr_no'=>$pharmacy_offline->dr_no,
                            'updated_at'=>$pharmacy_offline->updated_at,
                            'created_at'=>$pharmacy_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyclinic_sales')->where('sales_id', $pharmacy_offline_count[0]->sales_id)->update([  
                            'sales_id'=> $pharmacy_offline_count[0]->sales_id,
                            'product_id'=>$pharmacy_offline_count[0]->product_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'username'=>$pharmacy_offline_count[0]->username,
                            'product'=>$pharmacy_offline_count[0]->product,
                            'description'=>$pharmacy_offline_count[0]->description,
                            'unit'=>$pharmacy_offline_count[0]->unit,
                            'quantity'=>$pharmacy_offline_count[0]->quantity,
                            'total'=>$pharmacy_offline_count[0]->total,
                            'dr_no'=>$pharmacy_offline_count[0]->dr_no,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at,
                            'created_at'=>$pharmacy_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyclinic_sales')->insert([
                        'sales_id'=> $pharmacy_offline->sales_id,
                        'product_id'=>$pharmacy_offline->product_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'username'=>$pharmacy_offline->username,
                        'product'=>$pharmacy_offline->product,
                        'description'=>$pharmacy_offline->description,
                        'unit'=>$pharmacy_offline->unit,
                        'quantity'=>$pharmacy_offline->quantity,
                        'total'=>$pharmacy_offline->total,
                        'dr_no'=>$pharmacy_offline->dr_no,
                        'updated_at'=>$pharmacy_offline->updated_at,
                        'created_at'=>$pharmacy_offline->created_at
                    ]); 
                } 
        }

        // syncronize pharmacyclinic_sales table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyclinic_sales')->get();
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyclinic_sales')->where('sales_id', $pharmacy_online->sales_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyclinic_sales')->where('sales_id', $pharmacy_online->sales_id)->update([
                        'sales_id'=> $pharmacy_online->sales_id,
                        'product_id'=>$pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'username'=>$pharmacy_online->username,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'unit'=>$pharmacy_online->unit,
                        'quantity'=>$pharmacy_online->quantity,
                        'total'=>$pharmacy_online->total,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'updated_at'=>$pharmacy_online->updated_at,
                        'created_at'=>$pharmacy_online->created_at
                    ]); 
                }else{
                    DB::table('pharmacyclinic_sales')->insert([ 
                        'sales_id'=> $pharmacy_online->sales_id,
                        'product_id'=>$pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'username'=>$pharmacy_online->username,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'unit'=>$pharmacy_online->unit,
                        'quantity'=>$pharmacy_online->quantity,
                        'total'=>$pharmacy_online->total,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'updated_at'=>$pharmacy_online->updated_at,
                        'created_at'=>$pharmacy_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}