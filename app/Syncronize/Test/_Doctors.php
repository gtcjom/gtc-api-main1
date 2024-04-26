<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors extends Model
{ 
    public static function doctors(){ 
        // syncronize doctors table from online to offline 
        $doctor_offline = DB::table('doctors')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors')->where('d_id', $doctor_offline->d_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors')->where('d_id', $doctor_offline->d_id)->update([ 
                            'd_id'=>$doctor_offline->d_id, 
                            'doctors_id'=>$doctor_offline->doctors_id, 
                            'management_id'=>$doctor_offline->management_id, 
                            'user_id'=>$doctor_offline->user_id, 
                            'name'=>$doctor_offline->name, 
                            'address'=>$doctor_offline->address, 
                            'gender'=>$doctor_offline->gender, 
                            'contact_no'=>$doctor_offline->contact_no, 
                            'birthday'=>$doctor_offline->birthday, 
                            'specialization'=>$doctor_offline->specialization, 
                            'image'=>$doctor_offline->image, 
                            'image_signature'=>$doctor_offline->image_signature, 
                            'cil_umn'=>$doctor_offline->cil_umn, 
                            'ead_mun'=>$doctor_offline->ead_mun, 
                            'status'=>$doctor_offline->status, 
                            'role'=>$doctor_offline->role, 
                            'added_by'=>$doctor_offline->added_by,   
                            'online_appointment'=>$doctor_offline->online_appointment,   
                            'created_at'=>$doctor_offline->created_at,   
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('doctors')->where('d_id', $doctor_offline_count[0]->d_id)->update([ 
                            'd_id'=>$doctor_offline_count[0]->d_id, 
                            'doctors_id'=>$doctor_offline_count[0]->doctors_id, 
                            'management_id'=>$doctor_offline_count[0]->management_id, 
                            'user_id'=>$doctor_offline_count[0]->user_id, 
                            'name'=>$doctor_offline_count[0]->name, 
                            'address'=>$doctor_offline_count[0]->address, 
                            'gender'=>$doctor_offline_count[0]->gender, 
                            'contact_no'=>$doctor_offline_count[0]->contact_no, 
                            'birthday'=>$doctor_offline_count[0]->birthday, 
                            'specialization'=>$doctor_offline_count[0]->specialization, 
                            'image'=>$doctor_offline_count[0]->image, 
                            'image_signature'=>$doctor_offline_count[0]->image_signature, 
                            'cil_umn'=>$doctor_offline_count[0]->cil_umn, 
                            'ead_mun'=>$doctor_offline_count[0]->ead_mun, 
                            'status'=>$doctor_offline_count[0]->status, 
                            'role'=>$doctor_offline_count[0]->role, 
                            'added_by'=>$doctor_offline_count[0]->added_by,   
                            'online_appointment'=>$doctor_offline_count[0]->online_appointment,   
                            'created_at'=>$doctor_offline_count[0]->created_at,   
                            'updated_at'=>$doctor_offline_count[0]->updated_at 
                        ]);
                    }
     
                }else{
                    DB::connection('mysql2')->table('doctors')->insert([  
                        'd_id'=>$doctor_offline->d_id, 
                        'doctors_id'=>$doctor_offline->doctors_id, 
                        'management_id'=>$doctor_offline->management_id, 
                        'user_id'=>$doctor_offline->user_id, 
                        'name'=>$doctor_offline->name, 
                        'address'=>$doctor_offline->address, 
                        'gender'=>$doctor_offline->gender, 
                        'contact_no'=>$doctor_offline->contact_no, 
                        'birthday'=>$doctor_offline->birthday, 
                        'specialization'=>$doctor_offline->specialization, 
                        'image'=>$doctor_offline->image, 
                        'image_signature'=>$doctor_offline->image_signature, 
                        'cil_umn'=>$doctor_offline->cil_umn, 
                        'ead_mun'=>$doctor_offline->ead_mun, 
                        'status'=>$doctor_offline->status, 
                        'role'=>$doctor_offline->role, 
                        'added_by'=>$doctor_offline->added_by,   
                        'online_appointment'=>$doctor_offline->online_appointment,   
                        'created_at'=>$doctor_offline->created_at,   
                        'updated_at'=>$doctor_offline->updated_at  
                    ]); 
                } 
        } 


        // syncronize doctors table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors')->where('d_id', $doctor_online->d_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors')->where('d_id', $doctor_online->d_id)->update([ 
                        'd_id'=>$doctor_online->d_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'user_id'=>$doctor_online->user_id, 
                        'name'=>$doctor_online->name, 
                        'address'=>$doctor_online->address, 
                        'gender'=>$doctor_online->gender, 
                        'contact_no'=>$doctor_online->contact_no, 
                        'birthday'=>$doctor_online->birthday, 
                        'specialization'=>$doctor_online->specialization, 
                        'image'=>$doctor_online->image, 
                        'image_signature'=>$doctor_online->image_signature, 
                        'cil_umn'=>$doctor_online->cil_umn, 
                        'ead_mun'=>$doctor_online->ead_mun, 
                        'status'=>$doctor_online->status, 
                        'role'=>$doctor_online->role, 
                        'added_by'=>$doctor_online->added_by,   
                        'online_appointment'=>$doctor_online->online_appointment,   
                        'created_at'=>$doctor_online->created_at,   
                        'updated_at'=>$doctor_online->updated_at  
                    ]);
                }else{
                    DB::table('doctors')->insert([  
                        'd_id'=>$doctor_online->d_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'management_id'=>$doctor_online->management_id, 
                        'user_id'=>$doctor_online->user_id, 
                        'name'=>$doctor_online->name, 
                        'address'=>$doctor_online->address, 
                        'gender'=>$doctor_online->gender, 
                        'contact_no'=>$doctor_online->contact_no, 
                        'birthday'=>$doctor_online->birthday, 
                        'specialization'=>$doctor_online->specialization, 
                        'image'=>$doctor_online->image, 
                        'image_signature'=>$doctor_online->image_signature, 
                        'cil_umn'=>$doctor_online->cil_umn, 
                        'ead_mun'=>$doctor_online->ead_mun, 
                        'status'=>$doctor_online->status, 
                        'role'=>$doctor_online->role, 
                        'added_by'=>$doctor_online->added_by,   
                        'online_appointment'=>$doctor_online->online_appointment,   
                        'created_at'=>$doctor_online->created_at,   
                        'updated_at'=>$doctor_online->updated_at  
                    ]); 
                } 
        }   

        return true;
    } 
}