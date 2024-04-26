<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Encoder_patientbills_records extends Model
{ 
    public static function encoder_patientbills_records(){ 
        // syncronize encoder_patientbills_records table from offline to online   
        $enc_offline_List = DB::table('encoder_patientbills_records')->get();  
        foreach($enc_offline_List as $enc_offline){  
            $enc_offline_count = DB::connection('mysql2')->table('encoder_patientbills_records')->where('epr_id', $enc_offline->epr_id)->get();
                if(count($enc_offline_count) > 0){ 
                    if($enc_offline->updated_at > $enc_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('encoder_patientbills_records')->where('epr_id', $enc_offline->epr_id)->update([    
                            'epr_id'=> $enc_offline->epr_id, 
                            'trace_number'=>$enc_offline->trace_number,
                            'management_id'=>$enc_offline->management_id,
                            'doctors_id'=>$enc_offline->doctors_id,
                            'patient_id'=>$enc_offline->patient_id,
                            'bill_name'=>$enc_offline->bill_name,
                            'bill_amount'=>$enc_offline->bill_amount,
                            'bill_from'=>$enc_offline->bill_from,
                            'bill_payment'=>$enc_offline->bill_payment,
                            'bill_department'=>$enc_offline->bill_department,
                            'bill_total'=>$enc_offline->bill_total,
                            'process_by'=>$enc_offline->process_by,
                            'receipt_number'=>$enc_offline->receipt_number,
                            'order_id'=>$enc_offline->order_id,
                            'status'=>$enc_offline->status,
                            'created_at'=>$enc_offline->created_at,
                            'updated_at'=>$enc_offline->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('encoder_patientbills_records')->where('epr_id', $enc_offline_count[0]->epr_id)->update([     
                            'epr_id'=> $enc_offline_count[0]->epr_id, 
                            'trace_number'=>$enc_offline_count[0]->trace_number,
                            'management_id'=>$enc_offline_count[0]->management_id,
                            'doctors_id'=>$enc_offline_count[0]->doctors_id,
                            'patient_id'=>$enc_offline_count[0]->patient_id,
                            'bill_name'=>$enc_offline_count[0]->bill_name,
                            'bill_amount'=>$enc_offline_count[0]->bill_amount,
                            'bill_from'=>$enc_offline_count[0]->bill_from,
                            'bill_payment'=>$enc_offline_count[0]->bill_payment,
                            'bill_department'=>$enc_offline_count[0]->bill_department,
                            'bill_total'=>$enc_offline_count[0]->bill_total,
                            'process_by'=>$enc_offline_count[0]->process_by,
                            'receipt_number'=>$enc_offline_count[0]->receipt_number,
                            'order_id'=>$enc_offline_count[0]->order_id,
                            'status'=>$enc_offline_count[0]->status,
                            'created_at'=>$enc_offline_count[0]->created_at,
                            'updated_at'=>$enc_offline_count[0]->updated_at
                        ]);
                    }  
                     
                }else{
                    DB::connection('mysql2')->table('encoder_patientbills_records')->insert([    
                        'epr_id'=> $enc_offline->epr_id, 
                        'trace_number'=>$enc_offline->trace_number,
                        'management_id'=>$enc_offline->management_id,
                        'doctors_id'=>$enc_offline->doctors_id,
                        'patient_id'=>$enc_offline->patient_id,
                        'bill_name'=>$enc_offline->bill_name,
                        'bill_amount'=>$enc_offline->bill_amount,
                        'bill_from'=>$enc_offline->bill_from,
                        'bill_payment'=>$enc_offline->bill_payment,
                        'bill_department'=>$enc_offline->bill_department,
                        'bill_total'=>$enc_offline->bill_total,
                        'process_by'=>$enc_offline->process_by,
                        'receipt_number'=>$enc_offline->receipt_number,
                        'order_id'=>$enc_offline->order_id,
                        'status'=>$enc_offline->status,
                        'created_at'=>$enc_offline->created_at,
                        'updated_at'=>$enc_offline->updated_at
                    ]); 
                } 
        }

        // syncronize encoder_patientbills_records table from online to offline 
        $enc_online_List = DB::connection('mysql2')->table('encoder_patientbills_records')->get();  
        foreach($enc_online_List as $enc_online){  
            $enc_online_count = DB::table('encoder_patientbills_records')->where('epr_id', $enc_online->epr_id)->get();
                if(count($enc_online_count) > 0){
                    DB::table('encoder_patientbills_records')->where('epr_id', $enc_online->epr_id)->update([     
                        'epr_id'=> $enc_online->epr_id, 
                        'trace_number'=>$enc_online->trace_number,
                        'management_id'=>$enc_online->management_id,
                        'doctors_id'=>$enc_online->doctors_id,
                        'patient_id'=>$enc_online->patient_id,
                        'bill_name'=>$enc_online->bill_name,
                        'bill_amount'=>$enc_online->bill_amount,
                        'bill_from'=>$enc_online->bill_from,
                        'bill_payment'=>$enc_online->bill_payment,
                        'bill_department'=>$enc_online->bill_department,
                        'bill_total'=>$enc_online->bill_total,
                        'process_by'=>$enc_online->process_by,
                        'receipt_number'=>$enc_online->receipt_number,
                        'order_id'=>$enc_online->order_id,
                        'status'=>$enc_online->status,
                        'created_at'=>$enc_online->created_at,
                        'updated_at'=>$enc_online->updated_at
                    ]); 
                }else{
                    DB::table('encoder_patientbills_records')->insert([    
                        'epr_id'=> $enc_online->epr_id, 
                        'trace_number'=>$enc_online->trace_number,
                        'management_id'=>$enc_online->management_id,
                        'doctors_id'=>$enc_online->doctors_id,
                        'patient_id'=>$enc_online->patient_id,
                        'bill_name'=>$enc_online->bill_name,
                        'bill_amount'=>$enc_online->bill_amount,
                        'bill_from'=>$enc_online->bill_from,
                        'bill_payment'=>$enc_online->bill_payment,
                        'bill_department'=>$enc_online->bill_department,
                        'bill_total'=>$enc_online->bill_total,
                        'process_by'=>$enc_online->process_by,
                        'receipt_number'=>$enc_online->receipt_number,
                        'order_id'=>$enc_online->order_id,
                        'status'=>$enc_online->status,
                        'created_at'=>$enc_online->created_at,
                        'updated_at'=>$enc_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}