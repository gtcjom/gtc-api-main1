<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncVirtualPharmacyProductsInvCart extends Model
{ 
    public  static function syncVirtualPharmacyProductsInvCart(){
        // syncronize virtual_pharmacy_product_inv_cart table from offline to online
        $offline = DB::table('virtual_pharmacy_product_inv_cart')->get();  
        foreach($offline as $offline){  
            $offline_count = DB::connection('mysql2')->table('virtual_pharmacy_product_inv_cart')->where('vppi_id', $offline->vppi_id)->get();
                if(count($offline_count) > 0){ 
                    if($offline->updated_at > $offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_pharmacy_product_inv_cart')->where('vppi_id', $offline->vppi_id)->update([    
                            'vppi_id'=> $offline->vppi_id,
                            'management_id'=>$offline->management_id,
                            'product_id'=>$offline->product_id,
                            'claim_id'=>$offline->claim_id,
                            'ordered_qty'=>$offline->ordered_qty,
                            'released_qty'=>$offline->released_qty,
                            'order_status'=>$offline->order_status,
                            'release_by'=>$offline->release_by,
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_pharmacy_product_inv_cart')->where('vppi_id', $offline_count[0]->vppi_id)->update([  
                            'vppi_id'=> $offline_count[0]->vppi_id,
                            'management_id'=>$offline_count[0]->management_id,
                            'product_id'=>$offline_count[0]->product_id,
                            'claim_id'=>$offline_count[0]->claim_id,
                            'ordered_qty'=>$offline_count[0]->ordered_qty,
                            'released_qty'=>$offline_count[0]->released_qty,
                            'order_status'=>$offline_count[0]->order_status,
                            'release_by'=>$offline_count[0]->release_by,
                            'created_at'=>$offline_count[0]->created_at,
                            'updated_at'=>$offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_pharmacy_product_inv_cart')->insert([
                        'vppi_id'=> $offline->vppi_id,
                        'management_id'=>$offline->management_id,
                        'product_id'=>$offline->product_id,
                        'claim_id'=>$offline->claim_id,
                        'ordered_qty'=>$offline->ordered_qty,
                        'released_qty'=>$offline->released_qty,
                        'order_status'=>$offline->order_status,
                        'release_by'=>$offline->release_by,
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_pharmacy_product_inv_cart table from online to offline 
        $online = DB::connection('mysql2')->table('virtual_pharmacy_product_inv_cart')->get();
        foreach($online as $online){  
            $online_count = DB::table('virtual_pharmacy_product_inv_cart')->where('vppi_id', $online->vppi_id)->get();
                if(count($online_count) > 0){
                    DB::table('virtual_pharmacy_product_inv_cart')->where('vppi_id', $online->vppi_id)->update([
                        'vppi_id'=> $online->vppi_id,
                        'management_id'=>$online->management_id,
                        'product_id'=>$online->product_id,
                        'claim_id'=>$online->claim_id,
                        'ordered_qty'=>$online->ordered_qty,
                        'released_qty'=>$online->released_qty,
                        'order_status'=>$online->order_status,
                        'release_by'=>$online->release_by,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_pharmacy_product_inv_cart')->insert([ 
                        'vppi_id'=> $online->vppi_id,
                        'management_id'=>$online->management_id,
                        'product_id'=>$online->product_id,
                        'claim_id'=>$online->claim_id,
                        'ordered_qty'=>$online->ordered_qty,
                        'released_qty'=>$online->released_qty,
                        'order_status'=>$online->order_status,
                        'release_by'=>$online->release_by,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}