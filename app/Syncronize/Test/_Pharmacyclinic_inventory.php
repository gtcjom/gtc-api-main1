<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyclinic_inventory extends Model
{ 
    public static function pharmacyclinic_inventory(){ 
        // syncronize pharmacyclinic_inventory table from offline to online   
        $pharmacy_offline = DB::table('pharmacyclinic_inventory')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_offline->inventory_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_offline->inventory_id)->update([    
                            'inventory_id'=> $pharmacy_offline->inventory_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'product_id'=>$pharmacy_offline->product_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'dr_no'=>$pharmacy_offline->dr_no,
                            'quantity'=>$pharmacy_offline->quantity,
                            'unit'=>$pharmacy_offline->unit,
                            'starting_quantity'=>$pharmacy_offline->starting_quantity,
                            'manufacture_date'=>$pharmacy_offline->manufacture_date,
                            'batch_no'=>$pharmacy_offline->batch_no,
                            'expiry_date'=>$pharmacy_offline->expiry_date,
                            'request_type'=>$pharmacy_offline->request_type,
                            'comment'=>$pharmacy_offline->comment,
                            'created_at'=>$pharmacy_offline->created_at,
                            'updated_at'=>$pharmacy_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_offline_count[0]->inventory_id)->update([  
                            'inventory_id'=> $pharmacy_offline_count[0]->inventory_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'product_id'=>$pharmacy_offline_count[0]->product_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'dr_no'=>$pharmacy_offline_count[0]->dr_no,
                            'quantity'=>$pharmacy_offline_count[0]->quantity,
                            'unit'=>$pharmacy_offline_count[0]->unit,
                            'starting_quantity'=>$pharmacy_offline_count[0]->starting_quantity,
                            'manufacture_date'=>$pharmacy_offline_count[0]->manufacture_date,
                            'batch_no'=>$pharmacy_offline_count[0]->batch_no,
                            'expiry_date'=>$pharmacy_offline_count[0]->expiry_date,
                            'request_type'=>$pharmacy_offline_count[0]->request_type,
                            'comment'=>$pharmacy_offline_count[0]->comment,
                            'created_at'=>$pharmacy_offline_count[0]->created_at,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyclinic_inventory')->insert([ 
                        'inventory_id'=> $pharmacy_offline->inventory_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'product_id'=>$pharmacy_offline->product_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'dr_no'=>$pharmacy_offline->dr_no,
                        'quantity'=>$pharmacy_offline->quantity,
                        'unit'=>$pharmacy_offline->unit,
                        'starting_quantity'=>$pharmacy_offline->starting_quantity,
                        'manufacture_date'=>$pharmacy_offline->manufacture_date,
                        'batch_no'=>$pharmacy_offline->batch_no,
                        'expiry_date'=>$pharmacy_offline->expiry_date,
                        'request_type'=>$pharmacy_offline->request_type,
                        'comment'=>$pharmacy_offline->comment,
                        'created_at'=>$pharmacy_offline->created_at,
                        'updated_at'=>$pharmacy_offline->updated_at
                    ]); 
                } 
        }

        // syncronize pharmacyclinic_inventory table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyclinic_inventory')->get();  
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_online->inventory_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_online->inventory_id)->update([
                        'inventory_id'=> $pharmacy_online->inventory_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'product_id'=>$pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'quantity'=>$pharmacy_online->quantity,
                        'unit'=>$pharmacy_online->unit,
                        'starting_quantity'=>$pharmacy_online->starting_quantity,
                        'manufacture_date'=>$pharmacy_online->manufacture_date,
                        'batch_no'=>$pharmacy_online->batch_no,
                        'expiry_date'=>$pharmacy_online->expiry_date,
                        'request_type'=>$pharmacy_online->request_type,
                        'comment'=>$pharmacy_online->comment,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                }else{
                    DB::table('pharmacyclinic_inventory')->insert([ 
                        'inventory_id'=> $pharmacy_online->inventory_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'product_id'=>$pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'quantity'=>$pharmacy_online->quantity,
                        'unit'=>$pharmacy_online->unit,
                        'starting_quantity'=>$pharmacy_online->starting_quantity,
                        'manufacture_date'=>$pharmacy_online->manufacture_date,
                        'batch_no'=>$pharmacy_online->batch_no,
                        'expiry_date'=>$pharmacy_online->expiry_date,
                        'request_type'=>$pharmacy_online->request_type,
                        'comment'=>$pharmacy_online->comment,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}