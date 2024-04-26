<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Csr_patient_order extends Model
{ 
    public static function csr_patient_order(){ 
        // syncronize csr_patient_order table from offline to online  
        $csr_offline = DB::table('csr_patient_order')->get();  
        foreach($csr_offline as $csr_offline){  
            $csr_offline_count = DB::connection('mysql2')->table('csr_patient_order')->where('cpo_id', $csr_offline->cpo_id)->get();
                if(count($csr_offline_count) > 0){
                    if($csr_offline->updated_at > $csr_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('csr_patient_order')->where('cpo_id', $csr_offline->cpo_id)->update([      
                            'cpo_id'=>$csr_offline->cpo_id,
                            'csr_id'=>$csr_offline->csr_id,
                            'management_id'=>$csr_offline->management_id,
                            'operation_id'=>$csr_offline->operation_id,
                            'order_id'=>$csr_offline->order_id,
                            'patient_id'=>$csr_offline->patient_id, 
                            'case_file'=>$csr_offline->case_file,
                            'added_by'=>$csr_offline->added_by,
                            'verify_by'=>$csr_offline->verify_by,
                            'order_category'=>$csr_offline->order_category,
                            'quantity'=>$csr_offline->quantity,
                            'status'=>$csr_offline->status,
                            'created_at'=>$csr_offline->created_at,
                            'updated_at'=>$csr_offline->updated_at
                        ]);  
                    } 
                    else{
                        DB::table('csr_patient_order')->where('cpo_id', $csr_offline_count[0]->cpo_id)->update([  
                            'cpo_id'=>$csr_offline_count[0]->cpo_id,
                            'csr_id'=>$csr_offline_count[0]->csr_id,
                            'management_id'=>$csr_offline_count[0]->management_id,
                            'operation_id'=>$csr_offline_count[0]->operation_id,
                            'order_id'=>$csr_offline_count[0]->order_id,
                            'patient_id'=>$csr_offline_count[0]->patient_id, 
                            'case_file'=>$csr_offline_count[0]->case_file,
                            'added_by'=>$csr_offline_count[0]->added_by,
                            'verify_by'=>$csr_offline_count[0]->verify_by,
                            'order_category'=>$csr_offline_count[0]->order_category,
                            'quantity'=>$csr_offline_count[0]->quantity,
                            'status'=>$csr_offline_count[0]->status,
                            'created_at'=>$csr_offline_count[0]->created_at,
                            'updated_at'=>$csr_offline_count[0]->updated_at
                        ]);
                    } 
                }
                else{ 
                    DB::connection('mysql2')->table('csr_patient_order')->insert([
                        'cpo_id'=>$csr_offline->cpo_id,
                        'csr_id'=>$csr_offline->csr_id,
                        'management_id'=>$csr_offline->management_id,
                        'operation_id'=>$csr_offline->operation_id,
                        'order_id'=>$csr_offline->order_id,
                        'patient_id'=>$csr_offline->patient_id, 
                        'case_file'=>$csr_offline->case_file,
                        'added_by'=>$csr_offline->added_by,
                        'verify_by'=>$csr_offline->verify_by,
                        'order_category'=>$csr_offline->order_category,
                        'quantity'=>$csr_offline->quantity,
                        'status'=>$csr_offline->status,
                        'created_at'=>$csr_offline->created_at,
                        'updated_at'=>$csr_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize csr_patient_order table from online to offline
        $csr_online = DB::connection('mysql2')->table('csr_patient_order')->get();
        foreach($csr_online as $csr_online){
            $csr_online_count = DB::table('csr_patient_order')->where('cpo_id', $csr_online->cpo_id)->get();
                if(count($csr_online_count) > 0){
                    DB::table('csr_patient_order')->where('cpo_id', $csr_online->cpo_id)->update([   
                        'cpo_id'=>$csr_online->cpo_id,
                        'csr_id'=>$csr_online->csr_id,
                        'management_id'=>$csr_online->management_id,
                        'operation_id'=>$csr_online->operation_id,
                        'order_id'=>$csr_online->order_id,
                        'patient_id'=>$csr_online->patient_id, 
                        'case_file'=>$csr_online->case_file,
                        'added_by'=>$csr_online->added_by,
                        'verify_by'=>$csr_online->verify_by,
                        'order_category'=>$csr_online->order_category,
                        'quantity'=>$csr_online->quantity,
                        'status'=>$csr_online->status,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                }else{
                    DB::table('csr_patient_order')->insert([
                        'cpo_id'=>$csr_online->cpo_id,
                        'csr_id'=>$csr_online->csr_id,
                        'management_id'=>$csr_online->management_id,
                        'operation_id'=>$csr_online->operation_id,
                        'order_id'=>$csr_online->order_id,
                        'patient_id'=>$csr_online->patient_id, 
                        'case_file'=>$csr_online->case_file,
                        'added_by'=>$csr_online->added_by,
                        'verify_by'=>$csr_online->verify_by,
                        'order_category'=>$csr_online->order_category,
                        'quantity'=>$csr_online->quantity,
                        'status'=>$csr_online->status,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}