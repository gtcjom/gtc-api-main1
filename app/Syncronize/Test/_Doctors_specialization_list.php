<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_specialization_list extends Model
{ 
    public static function doctors_specialization_list(){ 
        // syncronize doctors_specialization_list table from online to offline 
        $doctor_offline = DB::table('doctors_specialization_list')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_specialization_list')->where('spec_id', $doctor_offline->spec_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_specialization_list')->where('spec_id', $doctor_offline->spec_id)->update([ 
                            'spec_id'=>$doctor_offline->spec_id, 
                            'specialization'=>$doctor_offline->specialization, 
                            'status'=>$doctor_offline->status, 
                            'created_at'=>$doctor_offline->created_at, 
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('doctors_specialization_list')->where('spec_id', $doctor_offline_count[0]->spec_id)->update([ 
                            'spec_id'=>$doctor_offline_count[0]->spec_id, 
                            'specialization'=>$doctor_offline_count[0]->specialization, 
                            'status'=>$doctor_offline_count[0]->status, 
                            'created_at'=>$doctor_offline_count[0]->created_at, 
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('doctors_specialization_list')->insert([  
                        'spec_id'=>$doctor_offline->spec_id, 
                        'specialization'=>$doctor_offline->specialization, 
                        'status'=>$doctor_offline->status, 
                        'created_at'=>$doctor_offline->created_at, 
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_specialization_list table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_specialization_list')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_specialization_list')->where('spec_id', $doctor_online->spec_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_specialization_list')->where('spec_id', $doctor_online->spec_id)->update([ 
                        'spec_id'=>$doctor_online->spec_id, 
                        'specialization'=>$doctor_online->specialization, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at
                    ]);
                }else{
                    DB::table('doctors_specialization_list')->insert([  
                        'spec_id'=>$doctor_online->spec_id, 
                        'specialization'=>$doctor_online->specialization, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}