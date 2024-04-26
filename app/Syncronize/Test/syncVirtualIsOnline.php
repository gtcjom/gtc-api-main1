<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncVirtualIsOnline extends Model
{ 
    public function syncVirtualIsOnline(){
        // syncronize virtual_is_online table from offline to online
        $offline = DB::table('virtual_is_online')->get();  
        foreach($offline as $offline){  
            $offline_count = DB::connection('mysql2')->table('virtual_is_online')->where('online_id', $offline->online_id)->get();
                if(count($offline_count) > 0){ 
                    if($offline->updated_at > $offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_is_online')->where('online_id', $offline->online_id)->update([    
                            'online_id'=> $offline->online_id,
                            'appointment_id'=>$offline->appointment_id,
                            'room_number'=>$offline->room_number,
                            'patient_id'=>$offline->patient_id,
                            'patient_webrtc_id'=>$offline->patient_webrtc_id,
                            'doctors_id'=>$offline->doctors_id,
                            'doctors_webrtc_id'=>$offline->doctors_webrtc_id,
                            'is_patient_online_status'=>$offline->is_patient_online_status,
                            'checkup_status'=>$offline->checkup_status,
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_is_online')->where('online_id', $offline_count[0]->online_id)->update([  
                            'online_id'=> $offline_count[0]->online_id,
                            'appointment_id'=>$offline_count[0]->appointment_id,
                            'room_number'=>$offline_count[0]->room_number,
                            'patient_id'=>$offline_count[0]->patient_id,
                            'patient_webrtc_id'=>$offline_count[0]->patient_webrtc_id,
                            'doctors_id'=>$offline_count[0]->doctors_id,
                            'doctors_webrtc_id'=>$offline_count[0]->doctors_webrtc_id,
                            'is_patient_online_status'=>$offline_count[0]->is_patient_online_status,
                            'checkup_status'=>$offline_count[0]->checkup_status,
                            'created_at'=>$offline_count[0]->created_at,
                            'updated_at'=>$offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_is_online')->insert([
                        'online_id'=> $offline->online_id,
                        'appointment_id'=>$offline->appointment_id,
                        'room_number'=>$offline->room_number,
                        'patient_id'=>$offline->patient_id,
                        'patient_webrtc_id'=>$offline->patient_webrtc_id,
                        'doctors_id'=>$offline->doctors_id,
                        'doctors_webrtc_id'=>$offline->doctors_webrtc_id,
                        'is_patient_online_status'=>$offline->is_patient_online_status,
                        'checkup_status'=>$offline->checkup_status,
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_is_online table from online to offline 
        $online = DB::connection('mysql2')->table('virtual_is_online')->get();
        foreach($online as $online){  
            $online_count = DB::table('virtual_is_online')->where('online_id', $online->online_id)->get();
                if(count($online_count) > 0){
                    DB::table('virtual_is_online')->where('online_id', $online->online_id)->update([
                        'online_id'=> $online->online_id,
                        'appointment_id'=>$online->appointment_id,
                        'room_number'=>$online->room_number,
                        'patient_id'=>$online->patient_id,
                        'patient_webrtc_id'=>$online->patient_webrtc_id,
                        'doctors_id'=>$online->doctors_id,
                        'doctors_webrtc_id'=>$online->doctors_webrtc_id,
                        'is_patient_online_status'=>$online->is_patient_online_status,
                        'checkup_status'=>$online->checkup_status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_is_online')->insert([ 
                        'online_id'=> $online->online_id,
                        'appointment_id'=>$online->appointment_id,
                        'room_number'=>$online->room_number,
                        'patient_id'=>$online->patient_id,
                        'patient_webrtc_id'=>$online->patient_webrtc_id,
                        'doctors_id'=>$online->doctors_id,
                        'doctors_webrtc_id'=>$online->doctors_webrtc_id,
                        'is_patient_online_status'=>$online->is_patient_online_status,
                        'checkup_status'=>$online->checkup_status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}