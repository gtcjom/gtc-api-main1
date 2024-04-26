<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_prescription extends Model
{ 
    public static function doctors_prescription(){ 
        // syncronize doctors_prescription table from online to offline 
        $doctor_offline = DB::table('doctors_prescription')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_prescription')->where('dp_id', $doctor_offline->dp_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_prescription')->where('dp_id', $doctor_offline->dp_id)->update([ 
                            'dp_id'=>$doctor_offline->dp_id, 
                            'prescription_id'=>$doctor_offline->prescription_id, 
                            'management_id'=>$doctor_offline->management_id, 
                            'patients_id'=>$doctor_offline->patients_id, 
                            'case_file'=>$doctor_offline->case_file, 
                            'doctors_id'=>$doctor_offline->doctors_id, 
                            'prescription'=>$doctor_offline->prescription,
                            'product_name'=>$doctor_offline->product_name,
                            'product_amount'=>$doctor_offline->product_amount,
                            'is_package'=>$doctor_offline->is_package,
                            'brand'=>$doctor_offline->brand,
                            'quantity'=>$doctor_offline->quantity,
                            'type'=>$doctor_offline->type,
                            'dosage'=>$doctor_offline->dosage,
                            'per_day'=>$doctor_offline->per_day,
                            'per_take'=>$doctor_offline->per_take,
                            'remarks'=>$doctor_offline->remarks,
                            'prescription_type'=>$doctor_offline->prescription_type,
                            'pharmacy_id'=>$doctor_offline->pharmacy_id,
                            'claim_id'=>$doctor_offline->claim_id,
                            'created_at'=>$doctor_offline->created_at,
                            'updated_at'=>$doctor_offline->updated_at

                        ]);
                    } 
                    else{
                        DB::table('doctors_prescription')->where('dp_id', $doctor_offline_count[0]->dp_id)->update([ 
                            'dp_id'=>$doctor_offline_count[0]->dp_id, 
                            'prescription_id'=>$doctor_offline_count[0]->prescription_id, 
                            'management_id'=>$doctor_offline_count[0]->management_id, 
                            'patients_id'=>$doctor_offline_count[0]->patients_id, 
                            'case_file'=>$doctor_offline_count[0]->case_file, 
                            'doctors_id'=>$doctor_offline_count[0]->doctors_id, 
                            'prescription'=>$doctor_offline_count[0]->prescription,
                            'product_name'=>$doctor_offline_count[0]->product_name,
                            'product_amount'=>$doctor_offline_count[0]->product_amount,
                            'is_package'=>$doctor_offline_count[0]->is_package,
                            'brand'=>$doctor_offline_count[0]->brand,
                            'quantity'=>$doctor_offline_count[0]->quantity,
                            'type'=>$doctor_offline_count[0]->type,
                            'dosage'=>$doctor_offline_count[0]->dosage,
                            'per_day'=>$doctor_offline_count[0]->per_day,
                            'per_take'=>$doctor_offline_count[0]->per_take,
                            'remarks'=>$doctor_offline_count[0]->remarks,
                            'prescription_type'=>$doctor_offline_count[0]->prescription_type,
                            'pharmacy_id'=>$doctor_offline_count[0]->pharmacy_id,
                            'claim_id'=>$doctor_offline_count[0]->claim_id,
                            'created_at'=>$doctor_offline_count[0]->created_at,
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('doctors_prescription')->insert([  
                        'dp_id'=>$doctor_offline->dp_id, 
                        'prescription_id'=>$doctor_offline->prescription_id, 
                        'management_id'=>$doctor_offline->management_id, 
                        'patients_id'=>$doctor_offline->patients_id, 
                        'case_file'=>$doctor_offline->case_file, 
                        'doctors_id'=>$doctor_offline->doctors_id, 
                        'prescription'=>$doctor_offline->prescription,
                        'product_name'=>$doctor_offline->product_name,
                        'product_amount'=>$doctor_offline->product_amount,
                        'is_package'=>$doctor_offline->is_package,
                        'brand'=>$doctor_offline->brand,
                        'quantity'=>$doctor_offline->quantity,
                        'type'=>$doctor_offline->type,
                        'dosage'=>$doctor_offline->dosage,
                        'per_day'=>$doctor_offline->per_day,
                        'per_take'=>$doctor_offline->per_take,
                        'remarks'=>$doctor_offline->remarks,
                        'prescription_type'=>$doctor_offline->prescription_type,
                        'pharmacy_id'=>$doctor_offline->pharmacy_id,
                        'claim_id'=>$doctor_offline->claim_id,
                        'created_at'=>$doctor_offline->created_at,
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_prescription table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_prescription')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_prescription')->where('dp_id', $doctor_online->dp_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_prescription')->where('dp_id', $doctor_online->dp_id)->update([ 
                        'dp_id'=>$doctor_online->dp_id, 
                        'prescription_id'=>$doctor_online->prescription_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'patients_id'=>$doctor_online->patients_id, 
                        'case_file'=>$doctor_online->case_file, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'prescription'=>$doctor_online->prescription,
                        'product_name'=>$doctor_online->product_name,
                        'product_amount'=>$doctor_online->product_amount,
                        'is_package'=>$doctor_online->is_package,
                        'brand'=>$doctor_online->brand,
                        'quantity'=>$doctor_online->quantity,
                        'type'=>$doctor_online->type,
                        'dosage'=>$doctor_online->dosage,
                        'per_day'=>$doctor_online->per_day,
                        'per_take'=>$doctor_online->per_take,
                        'remarks'=>$doctor_online->remarks,
                        'prescription_type'=>$doctor_online->prescription_type,
                        'pharmacy_id'=>$doctor_online->pharmacy_id,
                        'claim_id'=>$doctor_online->claim_id,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]);
                }else{
                    DB::table('doctors_prescription')->insert([  
                        'dp_id'=>$doctor_online->dp_id, 
                        'prescription_id'=>$doctor_online->prescription_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'patients_id'=>$doctor_online->patients_id, 
                        'case_file'=>$doctor_online->case_file, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'prescription'=>$doctor_online->prescription,
                        'product_name'=>$doctor_online->product_name,
                        'product_amount'=>$doctor_online->product_amount,
                        'is_package'=>$doctor_online->is_package,
                        'brand'=>$doctor_online->brand,
                        'quantity'=>$doctor_online->quantity,
                        'type'=>$doctor_online->type,
                        'dosage'=>$doctor_online->dosage,
                        'per_day'=>$doctor_online->per_day,
                        'per_take'=>$doctor_online->per_take,
                        'remarks'=>$doctor_online->remarks,
                        'prescription_type'=>$doctor_online->prescription_type,
                        'pharmacy_id'=>$doctor_online->pharmacy_id,
                        'claim_id'=>$doctor_online->claim_id,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}