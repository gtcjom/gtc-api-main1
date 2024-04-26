<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Encoder_patientbills_unpaid extends Model
{ 
    public static function encoder_patientbills_unpaid(){ 
        // syncronize encoder_patientbills_unpaid table from offline to online   
        $enc_offline_List = DB::table('encoder_patientbills_unpaid')->get();  
        foreach($enc_offline_List as $enc_offline){  
            $enc_offline_count = DB::connection('mysql2')->table('encoder_patientbills_unpaid')->where('epb_id', $enc_offline->epb_id)->get();
                if(count($enc_offline_count) > 0){ 
                    if($enc_offline->updated_at > $enc_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('encoder_patientbills_unpaid')->where('epb_id', $enc_offline->epb_id)->update([    
                            'epb_id'=> $enc_offline->epb_id, 
                            'trace_number'=>$enc_offline->trace_number,
                            'doctors_id'=>$enc_offline->doctors_id,
                            'patient_id'=>$enc_offline->patient_id,
                            'bill_name'=>$enc_offline->bill_name,
                            'bill_amount'=>$enc_offline->bill_amount,
                            'bill_department'=>$enc_offline->bill_department,
                            'bill_from'=>$enc_offline->bill_from,
                            'order_id'=>$enc_offline->order_id,
                            'remarks'=>$enc_offline->remarks,
                            'created_at'=>$enc_offline->created_at,
                            'updated_at'=>$enc_offline->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('encoder_patientbills_unpaid')->where('epb_id', $enc_offline_count[0]->epb_id)->update([   
                            'epb_id'=> $enc_offline_count[0]->epb_id, 
                            'trace_number'=>$enc_offline_count[0]->trace_number,
                            'doctors_id'=>$enc_offline_count[0]->doctors_id,
                            'patient_id'=>$enc_offline_count[0]->patient_id,
                            'bill_name'=>$enc_offline_count[0]->bill_name,
                            'bill_amount'=>$enc_offline_count[0]->bill_amount,
                            'bill_department'=>$enc_offline_count[0]->bill_department,
                            'bill_from'=>$enc_offline_count[0]->bill_from,
                            'order_id'=>$enc_offline_count[0]->order_id,
                            'remarks'=>$enc_offline_count[0]->remarks,
                            'created_at'=>$enc_offline_count[0]->created_at,
                            'updated_at'=>$enc_offline_count[0]->updated_at
                        ]);
                    }  
                     
                }else{
                    DB::connection('mysql2')->table('encoder_patientbills_unpaid')->insert([    
                        'epb_id'=> $enc_offline->epb_id, 
                        'trace_number'=>$enc_offline->trace_number,
                        'doctors_id'=>$enc_offline->doctors_id,
                        'patient_id'=>$enc_offline->patient_id,
                        'bill_name'=>$enc_offline->bill_name,
                        'bill_amount'=>$enc_offline->bill_amount,
                        'bill_department'=>$enc_offline->bill_department,
                        'bill_from'=>$enc_offline->bill_from,
                        'order_id'=>$enc_offline->order_id,
                        'remarks'=>$enc_offline->remarks,
                        'created_at'=>$enc_offline->created_at,
                        'updated_at'=>$enc_offline->updated_at
                    ]); 
                } 
        }

        // syncronize encoder_patientbills_unpaid table from online to offline 
        $enc_online_List = DB::connection('mysql2')->table('encoder_patientbills_unpaid')->get();  
        foreach($enc_online_List as $enc_online){  
            $enc_online_count = DB::table('encoder_patientbills_unpaid')->where('epb_id', $enc_online->epb_id)->get();
                if(count($enc_online_count) > 0){
                    DB::table('encoder_patientbills_unpaid')->where('epb_id', $enc_online->epb_id)->update([     
                        'epb_id'=> $enc_online->epb_id, 
                        'trace_number'=>$enc_online->trace_number,
                        'doctors_id'=>$enc_online->doctors_id,
                        'patient_id'=>$enc_online->patient_id,
                        'bill_name'=>$enc_online->bill_name,
                        'bill_amount'=>$enc_online->bill_amount,
                        'bill_department'=>$enc_online->bill_department,
                        'bill_from'=>$enc_online->bill_from,
                        'order_id'=>$enc_online->order_id,
                        'remarks'=>$enc_online->remarks,
                        'created_at'=>$enc_online->created_at,
                        'updated_at'=>$enc_online->updated_at
                    ]); 
                }else{
                    DB::table('encoder_patientbills_unpaid')->insert([    
                        'epb_id'=> $enc_online->epb_id, 
                        'trace_number'=>$enc_online->trace_number,
                        'doctors_id'=>$enc_online->doctors_id,
                        'patient_id'=>$enc_online->patient_id,
                        'bill_name'=>$enc_online->bill_name,
                        'bill_amount'=>$enc_online->bill_amount,
                        'bill_department'=>$enc_online->bill_department,
                        'bill_from'=>$enc_online->bill_from,
                        'order_id'=>$enc_online->order_id,
                        'remarks'=>$enc_online->remarks,
                        'created_at'=>$enc_online->created_at,
                        'updated_at'=>$enc_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}