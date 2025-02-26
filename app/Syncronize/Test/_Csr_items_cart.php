<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Csr_items_cart extends Model
{ 
    public static function csr_items_cart(){ 
        // syncronize csr_items_cart table from offline to online  
        $csr_offline = DB::table('csr_items_cart')->get();  
        foreach($csr_offline as $csr_offline){  
            $csr_offline_count = DB::connection('mysql2')->table('csr_items_cart')->where('cic_id', $csr_offline->cic_id)->get();
                if(count($csr_offline_count) > 0){
                    if($csr_offline->updated_at > $csr_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('csr_items_cart')->where('cic_id', $csr_offline->cic_id)->update([      
                            'cic_id'=>$csr_offline->cic_id,
                            'operation_id'=>$csr_offline->operation_id,
                            'csr_id'=>$csr_offline->csr_id,
                            'patient_id'=>$csr_offline->patient_id, 
                            'case_file'=>$csr_offline->case_file,
                            'management_id'=>$csr_offline->management_id, 
                            'item_id'=>$csr_offline->item_id,
                            'quantity'=>$csr_offline->quantity,
                            'added_by'=>$csr_offline->added_by,
                            'status'=>$csr_offline->status,
                            'created_at'=>$csr_offline->created_at,
                            'updated_at'=>$csr_offline->updated_at
                        ]);  
                    } 
                    else{
                        DB::table('csr_items_cart')->where('cic_id', $csr_offline_count[0]->cic_id)->update([  
                            'cic_id'=>$csr_offline_count[0]->cic_id,
                            'operation_id'=>$csr_offline_count[0]->operation_id,
                            'csr_id'=>$csr_offline_count[0]->csr_id,
                            'patient_id'=>$csr_offline_count[0]->patient_id, 
                            'case_file'=>$csr_offline_count[0]->case_file,
                            'management_id'=>$csr_offline_count[0]->management_id, 
                            'item_id'=>$csr_offline_count[0]->item_id,
                            'quantity'=>$csr_offline_count[0]->quantity,
                            'added_by'=>$csr_offline_count[0]->added_by,
                            'status'=>$csr_offline_count[0]->status,
                            'created_at'=>$csr_offline_count[0]->created_at,
                            'updated_at'=>$csr_offline_count[0]->updated_at
                        ]);
                    } 
                }
                else{ 
                    DB::connection('mysql2')->table('csr_items_cart')->insert([
                        'cic_id'=>$csr_offline->cic_id,
                        'operation_id'=>$csr_offline->operation_id,
                        'csr_id'=>$csr_offline->csr_id,
                        'patient_id'=>$csr_offline->patient_id, 
                        'case_file'=>$csr_offline->case_file,
                        'management_id'=>$csr_offline->management_id, 
                        'item_id'=>$csr_offline->item_id,
                        'quantity'=>$csr_offline->quantity,
                        'added_by'=>$csr_offline->added_by,
                        'status'=>$csr_offline->status,
                        'created_at'=>$csr_offline->created_at,
                        'updated_at'=>$csr_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize csr_items_cart table from online to offline
        $csr_online = DB::connection('mysql2')->table('csr_items_cart')->get();
        foreach($csr_online as $csr_online){  
            $csr_online_count = DB::table('csr_items_cart')->where('cic_id', $csr_online->cic_id)->get();
                if(count($csr_online_count) > 0){
                    DB::table('csr_items_cart')->where('cic_id', $csr_online->cic_id)->update([   
                        'cic_id'=>$csr_online->cic_id,
                        'operation_id'=>$csr_online->operation_id,
                        'csr_id'=>$csr_online->csr_id,
                        'patient_id'=>$csr_online->patient_id, 
                        'case_file'=>$csr_online->case_file,
                        'management_id'=>$csr_online->management_id, 
                        'item_id'=>$csr_online->item_id,
                        'quantity'=>$csr_online->quantity,
                        'added_by'=>$csr_online->added_by,
                        'status'=>$csr_online->status,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                }else{
                    DB::table('csr_items_cart')->insert([
                        'cic_id'=>$csr_online->cic_id,
                        'operation_id'=>$csr_online->operation_id,
                        'csr_id'=>$csr_online->csr_id,
                        'patient_id'=>$csr_online->patient_id, 
                        'case_file'=>$csr_online->case_file,
                        'management_id'=>$csr_online->management_id, 
                        'item_id'=>$csr_online->item_id,
                        'quantity'=>$csr_online->quantity,
                        'added_by'=>$csr_online->added_by,
                        'status'=>$csr_online->status,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}