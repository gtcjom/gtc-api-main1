<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_unsave_prescription extends Model
{ 
    public static function doctors_unsave_prescription(){ 
        // syncronize doctors_unsave_prescription table from online to offline 
        $doctor_offline = DB::table('doctors_unsave_prescription')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_unsave_prescription')->where('dup_id', $doctor_offline->dup_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_unsave_prescription')->where('dup_id', $doctor_offline->dup_id)->update([ 
                            'dup_id'=>$doctor_offline->dup_id, 
                            'prescription_id'=>$doctor_offline->prescription_id, 
                            'patient_id'=>$doctor_offline->patient_id, 
                            'management_id'=>$doctor_offline->management_id, 
                            'prescription'=>$doctor_offline->prescription,
                            'brand'=>$doctor_offline->brand,
                            'product_id'=>$doctor_offline->product_id,
                            'is_package'=>$doctor_offline->is_package,
                            'case_file'=>$doctor_offline->case_file,
                            'quantity'=>$doctor_offline->quantity,
                            'amount'=>$doctor_offline->amount,
                            'type'=>$doctor_offline->type,
                            'dosage'=>$doctor_offline->dosage,
                            'per_day'=>$doctor_offline->per_day,
                            'per_take'=>$doctor_offline->per_take,
                            'remarks'=>$doctor_offline->remarks,
                            'prescription_type'=>$doctor_offline->prescription_type,
                            'pharmacy_id'=>$doctor_offline->pharmacy_id,
                            'created_at'=>$doctor_offline->created_at,
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('doctors_unsave_prescription')->where('dup_id', $doctor_offline_count[0]->dup_id)->update([ 
                            'dup_id'=>$doctor_offline_count[0]->dup_id, 
                            'prescription_id'=>$doctor_offline_count[0]->prescription_id, 
                            'patient_id'=>$doctor_offline_count[0]->patient_id, 
                            'management_id'=>$doctor_offline_count[0]->management_id, 
                            'prescription'=>$doctor_offline_count[0]->prescription,
                            'brand'=>$doctor_offline_count[0]->brand,
                            'product_id'=>$doctor_offline_count[0]->product_id,
                            'is_package'=>$doctor_offline_count[0]->is_package,
                            'case_file'=>$doctor_offline_count[0]->case_file,
                            'quantity'=>$doctor_offline_count[0]->quantity,
                            'amount'=>$doctor_offline_count[0]->amount,
                            'type'=>$doctor_offline_count[0]->type,
                            'dosage'=>$doctor_offline_count[0]->dosage,
                            'per_day'=>$doctor_offline_count[0]->per_day,
                            'per_take'=>$doctor_offline_count[0]->per_take,
                            'remarks'=>$doctor_offline_count[0]->remarks,
                            'prescription_type'=>$doctor_offline_count[0]->prescription_type,
                            'pharmacy_id'=>$doctor_offline_count[0]->pharmacy_id,
                            'created_at'=>$doctor_offline_count[0]->created_at,
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('doctors_unsave_prescription')->insert([  
                        'dup_id'=>$doctor_offline->dup_id, 
                        'prescription_id'=>$doctor_offline->prescription_id, 
                        'patient_id'=>$doctor_offline->patient_id, 
                        'management_id'=>$doctor_offline->management_id, 
                        'prescription'=>$doctor_offline->prescription,
                        'brand'=>$doctor_offline->brand,
                        'product_id'=>$doctor_offline->product_id,
                        'is_package'=>$doctor_offline->is_package,
                        'case_file'=>$doctor_offline->case_file,
                        'quantity'=>$doctor_offline->quantity,
                        'amount'=>$doctor_offline->amount,
                        'type'=>$doctor_offline->type,
                        'dosage'=>$doctor_offline->dosage,
                        'per_day'=>$doctor_offline->per_day,
                        'per_take'=>$doctor_offline->per_take,
                        'remarks'=>$doctor_offline->remarks,
                        'prescription_type'=>$doctor_offline->prescription_type,
                        'pharmacy_id'=>$doctor_offline->pharmacy_id,
                        'created_at'=>$doctor_offline->created_at,
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_unsave_prescription table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_unsave_prescription')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_unsave_prescription')->where('dup_id', $doctor_online->dup_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_unsave_prescription')->where('dup_id', $doctor_online->dup_id)->update([ 
                        'dup_id'=>$doctor_online->dup_id, 
                        'prescription_id'=>$doctor_online->prescription_id, 
                        'patient_id'=>$doctor_online->patient_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'prescription'=>$doctor_online->prescription,
                        'brand'=>$doctor_online->brand,
                        'product_id'=>$doctor_online->product_id,
                        'is_package'=>$doctor_online->is_package,
                        'case_file'=>$doctor_online->case_file,
                        'quantity'=>$doctor_online->quantity,
                        'amount'=>$doctor_online->amount,
                        'type'=>$doctor_online->type,
                        'dosage'=>$doctor_online->dosage,
                        'per_day'=>$doctor_online->per_day,
                        'per_take'=>$doctor_online->per_take,
                        'remarks'=>$doctor_online->remarks,
                        'prescription_type'=>$doctor_online->prescription_type,
                        'pharmacy_id'=>$doctor_online->pharmacy_id,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]);
                }else{
                    DB::table('doctors_unsave_prescription')->insert([  
                        'dup_id'=>$doctor_online->dup_id, 
                        'prescription_id'=>$doctor_online->prescription_id, 
                        'patient_id'=>$doctor_online->patient_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'prescription'=>$doctor_online->prescription,
                        'brand'=>$doctor_online->brand,
                        'product_id'=>$doctor_online->product_id,
                        'is_package'=>$doctor_online->is_package,
                        'case_file'=>$doctor_online->case_file,
                        'quantity'=>$doctor_online->quantity,
                        'amount'=>$doctor_online->amount,
                        'type'=>$doctor_online->type,
                        'dosage'=>$doctor_online->dosage,
                        'per_day'=>$doctor_online->per_day,
                        'per_take'=>$doctor_online->per_take,
                        'remarks'=>$doctor_online->remarks,
                        'prescription_type'=>$doctor_online->prescription_type,
                        'pharmacy_id'=>$doctor_online->pharmacy_id,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}