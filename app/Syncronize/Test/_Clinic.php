<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Clinic extends Model
{ 
    public static function clinic(){ 
        // syncronize clinic table from offline to online  
        $clinic_offline = DB::table('clinic')->get();  
        foreach($clinic_offline as $clinic_offline){  
            $clinic_offline_count = DB::connection('mysql2')->table('clinic')->where('clinic_id', $clinic_offline->clinic_id)->get();
                if(count($clinic_offline_count) > 0){
                    if($clinic_offline->updated_at > $clinic_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('clinic')->where('clinic_id', $clinic_offline->clinic_id)->update([      
                            'clinic_id'=>$clinic_offline->clinic_id,
                            'doctors_id'=>$clinic_offline->doctors_id,
                            'encoder_id'=>$clinic_offline->encoder_id,
                            'management_id'=>$clinic_offline->management_id, 
                            'clinic'=>$clinic_offline->clinic,
                            'address'=>$clinic_offline->address, 
                            'days_open'=>$clinic_offline->days_open,
                            'time_open'=>$clinic_offline->time_open,
                            'contact_no'=>$clinic_offline->contact_no,
                            'clinic_image'=>$clinic_offline->clinic_image,
                            'remarks'=>$clinic_offline->remarks,
                            'latitude'=>$clinic_offline->latitude,
                            'longitude'=>$clinic_offline->longitude,
                            'status'=>$clinic_offline->status,
                            'created_at'=>$clinic_offline->created_at,
                            'updated_at'=>$clinic_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('clinic')->where('clinic_id', $clinic_offline_count[0]->clinic_id)->update([  
                            'clinic_id'=>$clinic_offline_count[0]->clinic_id,
                            'doctors_id'=>$clinic_offline_count[0]->doctors_id,
                            'encoder_id'=>$clinic_offline_count[0]->encoder_id,
                            'management_id'=>$clinic_offline_count[0]->management_id, 
                            'clinic'=>$clinic_offline_count[0]->clinic,
                            'address'=>$clinic_offline_count[0]->address, 
                            'days_open'=>$clinic_offline_count[0]->days_open,
                            'time_open'=>$clinic_offline_count[0]->time_open,
                            'contact_no'=>$clinic_offline_count[0]->contact_no,
                            'clinic_image'=>$clinic_offline_count[0]->clinic_image,
                            'remarks'=>$clinic_offline_count[0]->remarks,
                            'latitude'=>$clinic_offline_count[0]->latitude,
                            'longitude'=>$clinic_offline_count[0]->longitude,
                            'status'=>$clinic_offline_count[0]->status,
                            'created_at'=>$clinic_offline_count[0]->created_at,
                            'updated_at'=>$clinic_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('clinic')->insert([
                        'clinic_id'=>$clinic_offline->clinic_id,
                        'doctors_id'=>$clinic_offline->doctors_id,
                        'encoder_id'=>$clinic_offline->encoder_id,
                        'management_id'=>$clinic_offline->management_id, 
                        'clinic'=>$clinic_offline->clinic,
                        'address'=>$clinic_offline->address, 
                        'days_open'=>$clinic_offline->days_open,
                        'time_open'=>$clinic_offline->time_open,
                        'contact_no'=>$clinic_offline->contact_no,
                        'clinic_image'=>$clinic_offline->clinic_image,
                        'remarks'=>$clinic_offline->remarks,
                        'latitude'=>$clinic_offline->latitude,
                        'longitude'=>$clinic_offline->longitude,
                        'status'=>$clinic_offline->status,
                        'created_at'=>$clinic_offline->created_at,
                        'updated_at'=>$clinic_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize clinic table from online to offline
        $clinic_online = DB::connection('mysql2')->table('clinic')->get();  
        foreach($clinic_online as $clinic_online){  
            $clinic_online_count = DB::table('clinic')->where('clinic_id', $clinic_online->clinic_id)->get();
                if(count($clinic_online_count) > 0){
                    DB::table('clinic')->where('clinic_id', $clinic_online->clinic_id)->update([   
                        'clinic_id'=>$clinic_online->clinic_id,
                        'doctors_id'=>$clinic_online->doctors_id,
                        'encoder_id'=>$clinic_online->encoder_id,
                        'management_id'=>$clinic_online->management_id, 
                        'clinic'=>$clinic_online->clinic,
                        'address'=>$clinic_online->address, 
                        'days_open'=>$clinic_online->days_open,
                        'time_open'=>$clinic_online->time_open,
                        'contact_no'=>$clinic_online->contact_no,
                        'clinic_image'=>$clinic_online->clinic_image,
                        'remarks'=>$clinic_online->remarks,
                        'latitude'=>$clinic_online->latitude,
                        'longitude'=>$clinic_online->longitude,
                        'status'=>$clinic_online->status,
                        'created_at'=>$clinic_online->created_at,
                        'updated_at'=>$clinic_online->updated_at
                    ]); 
                }else{
                    DB::table('clinic')->insert([
                        'clinic_id'=>$clinic_online->clinic_id,
                        'doctors_id'=>$clinic_online->doctors_id,
                        'encoder_id'=>$clinic_online->encoder_id,
                        'management_id'=>$clinic_online->management_id, 
                        'clinic'=>$clinic_online->clinic,
                        'address'=>$clinic_online->address, 
                        'days_open'=>$clinic_online->days_open,
                        'time_open'=>$clinic_online->time_open,
                        'contact_no'=>$clinic_online->contact_no,
                        'clinic_image'=>$clinic_online->clinic_image,
                        'remarks'=>$clinic_online->remarks,
                        'latitude'=>$clinic_online->latitude,
                        'longitude'=>$clinic_online->longitude,
                        'status'=>$clinic_online->status,
                        'created_at'=>$clinic_online->created_at,
                        'updated_at'=>$clinic_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}