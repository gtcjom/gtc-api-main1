<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_appointment_services extends Model
{ 
    public static function doctors_appointment_services(){ 
        // syncronize doctors_appointment_services table from online to offline 
        $doctor_offline = DB::table('doctors_appointment_services')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_appointment_services')->where('service_id', $doctor_offline->service_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_appointment_services')->where('service_id', $doctor_offline->service_id)->update([ 
                            'service_id'=>$doctor_offline->service_id, 
                            'doctors_id'=>$doctor_offline->doctors_id, 
                            'services'=>$doctor_offline->services, 
                            'amount'=>$doctor_offline->amount, 
                            'status'=>$doctor_offline->status, 
                            'created_at'=>$doctor_offline->created_at, 
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('doctors_appointment_services')->where('service_id', $doctor_offline_count[0]->service_id)->update([ 
                            'service_id'=>$doctor_offline_count[0]->service_id, 
                            'doctors_id'=>$doctor_offline_count[0]->doctors_id, 
                            'services'=>$doctor_offline_count[0]->services, 
                            'amount'=>$doctor_offline_count[0]->amount, 
                            'status'=>$doctor_offline_count[0]->status, 
                            'created_at'=>$doctor_offline_count[0]->created_at, 
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
     
                }else{
                    DB::connection('mysql2')->table('doctors_appointment_services')->insert([  
                        'service_id'=>$doctor_offline->service_id, 
                        'doctors_id'=>$doctor_offline->doctors_id, 
                        'services'=>$doctor_offline->services, 
                        'amount'=>$doctor_offline->amount, 
                        'status'=>$doctor_offline->status, 
                        'created_at'=>$doctor_offline->created_at, 
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_appointment_services table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_appointment_services')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_appointment_services')->where('service_id', $doctor_online->service_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_appointment_services')->where('service_id', $doctor_online->service_id)->update([ 
                        'service_id'=>$doctor_online->service_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'services'=>$doctor_online->services, 
                        'amount'=>$doctor_online->amount, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at 
                    ]);
                }else{
                    DB::table('doctors_appointment_services')->insert([  
                        'service_id'=>$doctor_online->service_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'services'=>$doctor_online->services, 
                        'amount'=>$doctor_online->amount, 
                        'status'=>$doctor_online->status, 
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at 
                    ]); 
                } 
        }   

        return true;
    } 
}