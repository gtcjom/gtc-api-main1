<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients extends Model
{ 
    public static function patients(){ 
        // syncronize patients table from offline to online   
        $patient_offline = DB::table('patients')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients')->where('patient_id', $patient_offline->patient_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients')->where('patient_id', $patient_offline->patient_id)->update([    
                            'patient_id'=> $patient_offline->patient_id, 
                            'encoders_id'=>$patient_offline->encoders_id,
                            'doctors_id'=>$patient_offline->doctors_id,
                            'management_id'=>$patient_offline->management_id,
                            'user_id'=>$patient_offline->user_id,
                            'firstname'=>$patient_offline->firstname,
                            'lastname'=>$patient_offline->lastname,
                            'middle'=>$patient_offline->middle,
                            'email'=>$patient_offline->email,
                            'mobile'=>$patient_offline->mobile,
                            'telephone'=>$patient_offline->telephone,
                            'birthday'=>$patient_offline->birthday,
                            'birthplace'=>$patient_offline->birthplace,
                            'gender'=>$patient_offline->gender,
                            'civil_status'=>$patient_offline->civil_status,
                            'religion'=>$patient_offline->religion,
                            'height'=>$patient_offline->height,
                            'weight'=>$patient_offline->weight,
                            'occupation'=>$patient_offline->occupation,
                            'street'=>$patient_offline->street,
                            'barangay'=>$patient_offline->barangay,
                            'city'=>$patient_offline->city,
                            'tin'=>$patient_offline->tin,
                            'zip'=>$patient_offline->zip,
                            'blood_type'=>$patient_offline->blood_type,
                            'blood_systolic'=>$patient_offline->blood_systolic,
                            'blood_diastolic'=>$patient_offline->blood_diastolic,
                            'temperature'=>$patient_offline->temperature,
                            'pulse'=>$patient_offline->pulse,
                            'rispiratory'=>$patient_offline->rispiratory,
                            'glucose'=>$patient_offline->glucose,
                            'uric_acid'=>$patient_offline->uric_acid,
                            'hepatitis'=>$patient_offline->hepatitis,
                            'tuberculosis'=>$patient_offline->tuberculosis,
                            'dengue'=>$patient_offline->dengue,
                            'cholesterol'=>$patient_offline->cholesterol,
                            'allergies'=>$patient_offline->allergies,
                            'medication'=>$patient_offline->medication,
                            'remarks'=>$patient_offline->remarks,
                            'image'=>$patient_offline->image,
                            'status'=>$patient_offline->status,
                            'doctors_response'=>$patient_offline->doctors_response,
                            'is_edited_bydoc'=>$patient_offline->is_edited_bydoc,
                            'package_selected'=>$patient_offline->package_selected,
                            'join_category'=>$patient_offline->join_category,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients')->where('patient_id', $patient_offline_count[0]->patient_id)->update([  
                            'patient_id'=> $patient_offline_count[0]->patient_id, 
                            'encoders_id'=>$patient_offline_count[0]->encoders_id,
                            'doctors_id'=>$patient_offline_count[0]->doctors_id,
                            'management_id'=>$patient_offline_count[0]->management_id,
                            'user_id'=>$patient_offline_count[0]->user_id,
                            'firstname'=>$patient_offline_count[0]->firstname,
                            'lastname'=>$patient_offline_count[0]->lastname,
                            'middle'=>$patient_offline_count[0]->middle,
                            'email'=>$patient_offline_count[0]->email,
                            'mobile'=>$patient_offline_count[0]->mobile,
                            'telephone'=>$patient_offline_count[0]->telephone,
                            'birthday'=>$patient_offline_count[0]->birthday,
                            'birthplace'=>$patient_offline_count[0]->birthplace,
                            'gender'=>$patient_offline_count[0]->gender,
                            'civil_status'=>$patient_offline_count[0]->civil_status,
                            'religion'=>$patient_offline_count[0]->religion,
                            'height'=>$patient_offline_count[0]->height,
                            'weight'=>$patient_offline_count[0]->weight,
                            'occupation'=>$patient_offline_count[0]->occupation,
                            'street'=>$patient_offline_count[0]->street,
                            'barangay'=>$patient_offline_count[0]->barangay,
                            'city'=>$patient_offline_count[0]->city,
                            'tin'=>$patient_offline_count[0]->tin,
                            'zip'=>$patient_offline_count[0]->zip,
                            'blood_type'=>$patient_offline_count[0]->blood_type,
                            'blood_systolic'=>$patient_offline_count[0]->blood_systolic,
                            'blood_diastolic'=>$patient_offline_count[0]->blood_diastolic,
                            'temperature'=>$patient_offline_count[0]->temperature,
                            'pulse'=>$patient_offline_count[0]->pulse,
                            'rispiratory'=>$patient_offline_count[0]->rispiratory,
                            'glucose'=>$patient_offline_count[0]->glucose,
                            'uric_acid'=>$patient_offline_count[0]->uric_acid,
                            'hepatitis'=>$patient_offline_count[0]->hepatitis,
                            'tuberculosis'=>$patient_offline_count[0]->tuberculosis,
                            'dengue'=>$patient_offline_count[0]->dengue,
                            'cholesterol'=>$patient_offline_count[0]->cholesterol,
                            'allergies'=>$patient_offline_count[0]->allergies,
                            'medication'=>$patient_offline_count[0]->medication,
                            'remarks'=>$patient_offline_count[0]->remarks,
                            'image'=>$patient_offline_count[0]->image,
                            'status'=>$patient_offline_count[0]->status,
                            'doctors_response'=>$patient_offline_count[0]->doctors_response,
                            'is_edited_bydoc'=>$patient_offline_count[0]->is_edited_bydoc,
                            'package_selected'=>$patient_offline_count[0]->package_selected,
                            'join_category'=>$patient_offline_count[0]->join_category,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients')->insert([ 
                        'patient_id'=> $patient_offline->patient_id, 
                        'encoders_id'=>$patient_offline->encoders_id,
                        'doctors_id'=>$patient_offline->doctors_id,
                        'management_id'=>$patient_offline->management_id,
                        'user_id'=>$patient_offline->user_id,
                        'firstname'=>$patient_offline->firstname,
                        'lastname'=>$patient_offline->lastname,
                        'middle'=>$patient_offline->middle,
                        'email'=>$patient_offline->email,
                        'mobile'=>$patient_offline->mobile,
                        'telephone'=>$patient_offline->telephone,
                        'birthday'=>$patient_offline->birthday,
                        'birthplace'=>$patient_offline->birthplace,
                        'gender'=>$patient_offline->gender,
                        'civil_status'=>$patient_offline->civil_status,
                        'religion'=>$patient_offline->religion,
                        'height'=>$patient_offline->height,
                        'weight'=>$patient_offline->weight,
                        'occupation'=>$patient_offline->occupation,
                        'street'=>$patient_offline->street,
                        'barangay'=>$patient_offline->barangay,
                        'city'=>$patient_offline->city,
                        'tin'=>$patient_offline->tin,
                        'zip'=>$patient_offline->zip,
                        'blood_type'=>$patient_offline->blood_type,
                        'blood_systolic'=>$patient_offline->blood_systolic,
                        'blood_diastolic'=>$patient_offline->blood_diastolic,
                        'temperature'=>$patient_offline->temperature,
                        'pulse'=>$patient_offline->pulse,
                        'rispiratory'=>$patient_offline->rispiratory,
                        'glucose'=>$patient_offline->glucose,
                        'uric_acid'=>$patient_offline->uric_acid,
                        'hepatitis'=>$patient_offline->hepatitis,
                        'tuberculosis'=>$patient_offline->tuberculosis,
                        'dengue'=>$patient_offline->dengue,
                        'cholesterol'=>$patient_offline->cholesterol,
                        'allergies'=>$patient_offline->allergies,
                        'medication'=>$patient_offline->medication,
                        'remarks'=>$patient_offline->remarks,
                        'image'=>$patient_offline->image,
                        'status'=>$patient_offline->status,
                        'doctors_response'=>$patient_offline->doctors_response,
                        'is_edited_bydoc'=>$patient_offline->is_edited_bydoc,
                        'package_selected'=>$patient_offline->package_selected,
                        'join_category'=>$patient_offline->join_category,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients')->where('patient_id', $patient_online->patient_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients')->where('patient_id', $patient_online->patient_id)->update([  
                        'patient_id'=> $patient_online->patient_id, 
                        'encoders_id'=>$patient_online->encoders_id,
                        'doctors_id'=>$patient_online->doctors_id,
                        'management_id'=>$patient_online->management_id,
                        'user_id'=>$patient_online->user_id,
                        'firstname'=>$patient_online->firstname,
                        'lastname'=>$patient_online->lastname,
                        'middle'=>$patient_online->middle,
                        'email'=>$patient_online->email,
                        'mobile'=>$patient_online->mobile,
                        'telephone'=>$patient_online->telephone,
                        'birthday'=>$patient_online->birthday,
                        'birthplace'=>$patient_online->birthplace,
                        'gender'=>$patient_online->gender,
                        'civil_status'=>$patient_online->civil_status,
                        'religion'=>$patient_online->religion,
                        'height'=>$patient_online->height,
                        'weight'=>$patient_online->weight,
                        'occupation'=>$patient_online->occupation,
                        'street'=>$patient_online->street,
                        'barangay'=>$patient_online->barangay,
                        'city'=>$patient_online->city,
                        'tin'=>$patient_online->tin,
                        'zip'=>$patient_online->zip,
                        'blood_type'=>$patient_online->blood_type,
                        'blood_systolic'=>$patient_online->blood_systolic,
                        'blood_diastolic'=>$patient_online->blood_diastolic,
                        'temperature'=>$patient_online->temperature,
                        'pulse'=>$patient_online->pulse,
                        'rispiratory'=>$patient_online->rispiratory,
                        'glucose'=>$patient_online->glucose,
                        'uric_acid'=>$patient_online->uric_acid,
                        'hepatitis'=>$patient_online->hepatitis,
                        'tuberculosis'=>$patient_online->tuberculosis,
                        'dengue'=>$patient_online->dengue,
                        'cholesterol'=>$patient_online->cholesterol,
                        'allergies'=>$patient_online->allergies,
                        'medication'=>$patient_online->medication,
                        'remarks'=>$patient_online->remarks,
                        'image'=>$patient_online->image,
                        'status'=>$patient_online->status,
                        'doctors_response'=>$patient_online->doctors_response,
                        'is_edited_bydoc'=>$patient_online->is_edited_bydoc,
                        'package_selected'=>$patient_online->package_selected,
                        'join_category'=>$patient_online->join_category,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients')->insert([    
                        'patient_id'=> $patient_online->patient_id, 
                        'encoders_id'=>$patient_online->encoders_id,
                        'doctors_id'=>$patient_online->doctors_id,
                        'management_id'=>$patient_online->management_id,
                        'user_id'=>$patient_online->user_id,
                        'firstname'=>$patient_online->firstname,
                        'lastname'=>$patient_online->lastname,
                        'middle'=>$patient_online->middle,
                        'email'=>$patient_online->email,
                        'mobile'=>$patient_online->mobile,
                        'telephone'=>$patient_online->telephone,
                        'birthday'=>$patient_online->birthday,
                        'birthplace'=>$patient_online->birthplace,
                        'gender'=>$patient_online->gender,
                        'civil_status'=>$patient_online->civil_status,
                        'religion'=>$patient_online->religion,
                        'height'=>$patient_online->height,
                        'weight'=>$patient_online->weight,
                        'occupation'=>$patient_online->occupation,
                        'street'=>$patient_online->street,
                        'barangay'=>$patient_online->barangay,
                        'city'=>$patient_online->city,
                        'tin'=>$patient_online->tin,
                        'zip'=>$patient_online->zip,
                        'blood_type'=>$patient_online->blood_type,
                        'blood_systolic'=>$patient_online->blood_systolic,
                        'blood_diastolic'=>$patient_online->blood_diastolic,
                        'temperature'=>$patient_online->temperature,
                        'pulse'=>$patient_online->pulse,
                        'rispiratory'=>$patient_online->rispiratory,
                        'glucose'=>$patient_online->glucose,
                        'uric_acid'=>$patient_online->uric_acid,
                        'hepatitis'=>$patient_online->hepatitis,
                        'tuberculosis'=>$patient_online->tuberculosis,
                        'dengue'=>$patient_online->dengue,
                        'cholesterol'=>$patient_online->cholesterol,
                        'allergies'=>$patient_online->allergies,
                        'medication'=>$patient_online->medication,
                        'remarks'=>$patient_online->remarks,
                        'image'=>$patient_online->image,
                        'status'=>$patient_online->status,
                        'doctors_response'=>$patient_online->doctors_response,
                        'is_edited_bydoc'=>$patient_online->is_edited_bydoc,
                        'package_selected'=>$patient_online->package_selected,
                        'join_category'=>$patient_online->join_category,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}