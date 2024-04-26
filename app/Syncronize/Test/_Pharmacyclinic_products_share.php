<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyclinic_products_share extends Model
{ 
    public static function pharmacyclinic_products_share(){
        // syncronize pharmacyclinic_products_share table from offline to online
        $pharmacy_offline = DB::table('pharmacyclinic_products_share')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyclinic_products_share')->where('pcps_id', $pharmacy_offline->pcps_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyclinic_products_share')->where('pcps_id', $pharmacy_offline->pcps_id)->update([    
                            'pcps_id'=> $pharmacy_offline->pcps_id,
                            'doctors_id'=>$pharmacy_offline->doctors_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'product_id'=>$pharmacy_offline->product_id,
                            'share_percent'=>$pharmacy_offline->share_percent,
                            'status'=>$pharmacy_offline->status,
                            'updated_at'=>$pharmacy_offline->updated_at,
                            'created_at'=>$pharmacy_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyclinic_products_share')->where('pcps_id', $pharmacy_offline_count[0]->pcps_id)->update([  
                            'pcps_id'=> $pharmacy_offline_count[0]->pcps_id,
                            'doctors_id'=>$pharmacy_offline_count[0]->doctors_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'product_id'=>$pharmacy_offline_count[0]->product_id,
                            'share_percent'=>$pharmacy_offline_count[0]->share_percent,
                            'status'=>$pharmacy_offline_count[0]->status,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at,
                            'created_at'=>$pharmacy_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyclinic_products_share')->insert([
                        'pcps_id'=> $pharmacy_offline->pcps_id,
                        'doctors_id'=>$pharmacy_offline->doctors_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'product_id'=>$pharmacy_offline->product_id,
                        'share_percent'=>$pharmacy_offline->share_percent,
                        'status'=>$pharmacy_offline->status,
                        'updated_at'=>$pharmacy_offline->updated_at,
                        'created_at'=>$pharmacy_offline->created_at
                    ]); 
                } 
        }

        // syncronize pharmacyclinic_products_share table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyclinic_products_share')->get();
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyclinic_products_share')->where('pcps_id', $pharmacy_online->pcps_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyclinic_products_share')->where('pcps_id', $pharmacy_online->pcps_id)->update([
                        'pcps_id'=> $pharmacy_online->pcps_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'product_id'=>$pharmacy_online->product_id,
                        'product_qty'=>$pharmacy_online->product_qty,
                        'status'=>$pharmacy_online->status,
                        'updated_at'=>$pharmacy_online->updated_at,
                        'created_at'=>$pharmacy_online->created_at
                    ]); 
                }else{
                    DB::table('pharmacyclinic_products_share')->insert([ 
                        'pcps_id'=> $pharmacy_online->pcps_id,
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