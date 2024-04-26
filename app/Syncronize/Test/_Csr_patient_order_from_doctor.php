<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Csr_patient_order_from_doctor extends Model
{ 
    public  static function csr_patient_order_from_doctor(){ 
        // syncronize csr_patient_order_from_doctor table from offline to online  
        $csr_offline = DB::table('csr_patient_order_from_doctor')->get();  
        foreach($csr_offline as $csr_offline){  
            $csr_offline_count = DB::connection('mysql2')->table('csr_patient_order_from_doctor')->where('cpofd_id', $csr_offline->cpofd_id)->get();
                if(count($csr_offline_count) > 0){
                    if($csr_offline->updated_at > $csr_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('csr_patient_order_from_doctor')->where('cpofd_id', $csr_offline->cpofd_id)->update([      
                            'cpofd_id'=>$csr_offline->cpofd_id,
                            'csr_id'=>$csr_offline->csr_id,
                            'management_id'=>$csr_offline->management_id,
                            'patient_id'=>$csr_offline->patient_id,
                            'case_file'=>$csr_offline->case_file,
                            'order_id'=>$csr_offline->order_id, 
                            'quantity'=>$csr_offline->quantity,
                            'order_category'=>$csr_offline->order_category,
                            'status'=>$csr_offline->status,
                            'added_by'=>$csr_offline->added_by,
                            'adder_type'=>$csr_offline->adder_type,
                            'verify_by'=>$csr_offline->verify_by,
                            'created_at'=>$csr_offline->created_at,
                            'updated_at'=>$csr_offline->updated_at
                        ]);  
                    } 
                    else{
                        DB::table('csr_patient_order_from_doctor')->where('cpofd_id', $csr_offline_count[0]->cpofd_id)->update([  
                            'cpofd_id'=>$csr_offline_count[0]->cpofd_id,
                            'csr_id'=>$csr_offline_count[0]->csr_id,
                            'management_id'=>$csr_offline_count[0]->management_id,
                            'patient_id'=>$csr_offline_count[0]->patient_id,
                            'case_file'=>$csr_offline_count[0]->case_file,
                            'order_id'=>$csr_offline_count[0]->order_id, 
                            'quantity'=>$csr_offline_count[0]->quantity,
                            'order_category'=>$csr_offline_count[0]->order_category,
                            'status'=>$csr_offline_count[0]->status,
                            'added_by'=>$csr_offline_count[0]->added_by,
                            'adder_type'=>$csr_offline_count[0]->adder_type,
                            'verify_by'=>$csr_offline_count[0]->verify_by,
                            'created_at'=>$csr_offline_count[0]->created_at,
                            'updated_at'=>$csr_offline_count[0]->updated_at
                        ]);
                    } 
                }
                else{ 
                    DB::connection('mysql2')->table('csr_patient_order_from_doctor')->insert([
                        'cpofd_id'=>$csr_offline->cpofd_id,
                        'csr_id'=>$csr_offline->csr_id,
                        'management_id'=>$csr_offline->management_id,
                        'patient_id'=>$csr_offline->patient_id,
                        'case_file'=>$csr_offline->case_file,
                        'order_id'=>$csr_offline->order_id, 
                        'quantity'=>$csr_offline->quantity,
                        'order_category'=>$csr_offline->order_category,
                        'status'=>$csr_offline->status,
                        'added_by'=>$csr_offline->added_by,
                        'adder_type'=>$csr_offline->adder_type,
                        'verify_by'=>$csr_offline->verify_by,
                        'created_at'=>$csr_offline->created_at,
                        'updated_at'=>$csr_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize csr_patient_order_from_doctor table from online to offline
        $csr_online = DB::connection('mysql2')->table('csr_patient_order_from_doctor')->get();
        foreach($csr_online as $csr_online){
            $csr_online_count = DB::table('csr_patient_order_from_doctor')->where('cpofd_id', $csr_online->cpofd_id)->get();
                if(count($csr_online_count) > 0){
                    DB::table('csr_patient_order_from_doctor')->where('cpofd_id', $csr_online->cpofd_id)->update([   
                        'cpofd_id'=>$csr_online->cpofd_id,
                        'csr_id'=>$csr_online->csr_id,
                        'management_id'=>$csr_online->management_id,
                        'patient_id'=>$csr_online->patient_id,
                        'case_file'=>$csr_online->case_file,
                        'order_id'=>$csr_online->order_id, 
                        'quantity'=>$csr_online->quantity,
                        'order_category'=>$csr_online->order_category,
                        'status'=>$csr_online->status,
                        'added_by'=>$csr_online->added_by,
                        'adder_type'=>$csr_online->adder_type,
                        'verify_by'=>$csr_online->verify_by,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                }else{
                    DB::table('csr_patient_order_from_doctor')->insert([
                        'cpofd_id'=>$csr_online->cpofd_id,
                        'csr_id'=>$csr_online->csr_id,
                        'management_id'=>$csr_online->management_id,
                        'patient_id'=>$csr_online->patient_id,
                        'case_file'=>$csr_online->case_file,
                        'order_id'=>$csr_online->order_id, 
                        'quantity'=>$csr_online->quantity,
                        'order_category'=>$csr_online->order_category,
                        'status'=>$csr_online->status,
                        'added_by'=>$csr_online->added_by,
                        'adder_type'=>$csr_online->adder_type,
                        'verify_by'=>$csr_online->verify_by,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}