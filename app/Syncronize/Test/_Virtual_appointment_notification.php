<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Virtual_appointment_notification extends Model
{ 
    public static function virtual_appointment_notification(){
        // syncronize virtual_appointment_notification table from offline to online
        $virtual_offline = DB::table('virtual_appointment_notification')->get();  
        foreach($virtual_offline as $virtual_offline){  
            $virtual_offline_count = DB::connection('mysql2')->table('virtual_appointment_notification')->where('notif_id', $virtual_offline->notif_id)->get();
                if(count($virtual_offline_count) > 0){ 
                    if($virtual_offline->updated_at > $virtual_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_appointment_notification')->where('notif_id', $virtual_offline->notif_id)->update([    
                            'notif_id'=> $virtual_offline->notif_id,
                            'appointment_id'=>$virtual_offline->appointment_id,
                            'doctors_id'=>$virtual_offline->doctors_id,
                            'patient_id'=>$virtual_offline->patient_id,
                            'notification_msg'=>$virtual_offline->notification_msg,
                            'is_read'=>$virtual_offline->is_read,
                            'notification_type'=>$virtual_offline->notification_type,
                            'status'=>$virtual_offline->status,
                            'created_at'=>$virtual_offline->created_at,
                            'updated_at'=>$virtual_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_appointment_notification')->where('notif_id', $virtual_offline_count[0]->notif_id)->update([  
                            'notif_id'=> $virtual_offline_count[0]->notif_id,
                            'appointment_id'=>$virtual_offline_count[0]->appointment_id,
                            'doctors_id'=>$virtual_offline_count[0]->doctors_id,
                            'patient_id'=>$virtual_offline_count[0]->patient_id,
                            'notification_msg'=>$virtual_offline_count[0]->notification_msg,
                            'is_read'=>$virtual_offline_count[0]->is_read,
                            'notification_type'=>$virtual_offline_count[0]->notification_type,
                            'status'=>$virtual_offline_count[0]->status,
                            'created_at'=>$virtual_offline_count[0]->created_at,
                            'updated_at'=>$virtual_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_appointment_notification')->insert([
                        'notif_id'=> $virtual_offline->notif_id,
                        'appointment_id'=>$virtual_offline->appointment_id,
                        'doctors_id'=>$virtual_offline->doctors_id,
                        'patient_id'=>$virtual_offline->patient_id,
                        'notification_msg'=>$virtual_offline->notification_msg,
                        'is_read'=>$virtual_offline->is_read,
                        'notification_type'=>$virtual_offline->notification_type,
                        'status'=>$virtual_offline->status,
                        'created_at'=>$virtual_offline->created_at,
                        'updated_at'=>$virtual_offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_appointment_notification table from online to offline 
        $virtual_online = DB::connection('mysql2')->table('virtual_appointment_notification')->get();
        foreach($virtual_online as $virtual_online){  
            $virtual_online_count = DB::table('virtual_appointment_notification')->where('notif_id', $virtual_online->notif_id)->get();
                if(count($virtual_online_count) > 0){
                    DB::table('virtual_appointment_notification')->where('notif_id', $virtual_online->notif_id)->update([
                        'notif_id'=> $virtual_online->notif_id,
                        'appointment_id'=>$virtual_online->appointment_id,
                        'doctors_id'=>$virtual_online->doctors_id,
                        'patient_id'=>$virtual_online->patient_id,
                        'notification_msg'=>$virtual_online->notification_msg,
                        'is_read'=>$virtual_online->is_read,
                        'notification_type'=>$virtual_online->notification_type,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_appointment_notification')->insert([ 
                        'notif_id'=> $virtual_online->notif_id,
                        'appointment_id'=>$virtual_online->appointment_id,
                        'doctors_id'=>$virtual_online->doctors_id,
                        'patient_id'=>$virtual_online->patient_id,
                        'notification_msg'=>$virtual_online->notification_msg,
                        'is_read'=>$virtual_online->is_read,
                        'notification_type'=>$virtual_online->notification_type,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}