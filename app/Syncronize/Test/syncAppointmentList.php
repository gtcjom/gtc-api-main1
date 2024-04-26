<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncAppointmentList extends Model
{ 
    public static function syncAppointmentList(){
          
        // syncronize appointment list table from offline to online 
         $al_online_list_offline = DB::table('appointment_list')->get();   
        foreach($al_online_list_offline as $al_offline){  
            $al_offline_count = DB::connection('mysql2')->table('appointment_list')->where('appointment_id', $al_offline->appointment_id)->get();
                if(count($al_offline_count) > 0){ 
                    if($al_offline->updated_at > $al_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('appointment_list')->where('appointment_id', $al_offline->appointment_id)->update([
                            'patients_id'=>$al_offline->patients_id,
                            'encoders_id'=>$al_offline->encoders_id,
                            'doctors_id'=>$al_offline->doctors_id,
                            'services'=>$al_offline->services,
                            'amount'=>$al_offline->amount,
                            'app_date'=>$al_offline->app_date,
                            'app_date_end'=>$al_offline->app_date_end,
                            'app_reason'=>$al_offline->app_reason, 
                            'is_reschedule'=>$al_offline->is_reschedule,
                            'is_reschedule_date'=>$al_offline->is_reschedule_date,
                            'is_reschedule_reason'=>$al_offline->is_reschedule_reason,
                            'apperance'=>$al_offline->apperance,
                            'is_waiting'=>$al_offline->is_waiting,
                            'is_waiting_reason'=>$al_offline->is_waiting_reason,
                            'is_complete'=>$al_offline->is_complete,
                            'is_remove'=>$al_offline->is_remove,
                            'is_remove_reason'=>$al_offline->is_remove_reason,
                            'is_remove_date'=>$al_offline->is_remove_date,
                            'referred_by'=>$al_offline->referred_by,
                            'is_paid_bysecretary'=>$al_offline->is_paid_bysecretary, 
                            'status'=>$al_offline->status,
                            'updated_at'=>$al_offline->updated_at,
                            'created_at'=>$al_offline->created_at,
                        ]);
                    } 
                    
                    else{
                        DB::table('appointment_list')->where('appointment_id', $al_offline_count[0]->appointment_id)->update([
                            'patients_id'=>$al_offline_count[0]->patients_id,
                            'encoders_id'=>$al_offline_count[0]->encoders_id,
                            'doctors_id'=>$al_offline_count[0]->doctors_id,
                            'services'=>$al_offline_count[0]->services,
                            'amount'=>$al_offline_count[0]->amount,
                            'app_date'=>$al_offline_count[0]->app_date,
                            'app_date_end'=>$al_offline_count[0]->app_date_end,
                            'app_reason'=>$al_offline_count[0]->app_reason,
                            'is_reschedule'=>$al_offline_count[0]->is_reschedule,
                            'is_reschedule_date'=>$al_offline_count[0]->is_reschedule_date,
                            'is_reschedule_reason'=>$al_offline_count[0]->is_reschedule_reason, 
                            'apperance'=>$al_offline_count[0]->apperance,
                            'is_waiting'=>$al_offline_count[0]->is_waiting,
                            'is_waiting_reason'=>$al_offline_count[0]->is_waiting_reason,
                            'is_complete'=>$al_offline_count[0]->is_complete,
                            'is_remove'=>$al_offline_count[0]->is_remove,
                            'is_remove_reason'=>$al_offline_count[0]->is_remove_reason,
                            'is_remove_date'=>$al_offline_count[0]->is_remove_date,
                            'referred_by'=>$al_offline_count[0]->referred_by,
                            'is_paid_bysecretary'=>$al_offline_count[0]->is_paid_bysecretary,
                            'status'=>$al_offline_count[0]->status,
                            'updated_at'=>$al_offline_count[0]->updated_at,
                            'created_at'=>$al_offline_count[0]->created_at,
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('appointment_list')->insert([
                        'appointment_id'=>$al_offline->appointment_id,
                        'patients_id'=>$al_offline->patients_id,
                        'encoders_id'=>$al_offline->encoders_id,
                        'doctors_id'=>$al_offline->doctors_id,
                        'services'=>$al_offline->services,
                        'amount'=>$al_offline->amount,
                        'app_date'=>$al_offline->app_date,
                        'app_date_end'=>$al_offline->app_date_end,
                        'is_reschedule'=>$al_offline->is_reschedule,
                        'is_reschedule_date'=>$al_offline->is_reschedule_date,
                        'is_reschedule_reason'=>$al_offline->is_reschedule_reason, 
                        'apperance'=>$al_offline->apperance,
                        'is_waiting'=>$al_offline->is_waiting,
                        'is_waiting_reason'=>$al_offline->is_waiting_reason,
                        'is_complete'=>$al_offline->is_complete,
                        'is_remove'=>$al_offline->is_remove,
                        'is_remove_reason'=>$al_offline->is_remove_reason,
                        'is_remove_date'=>$al_offline->is_remove_date,
                        'referred_by'=>$al_offline->referred_by,
                        'is_paid_bysecretary'=>$al_offline->is_paid_bysecretary,
                        'status'=>$al_offline->status,
                        'updated_at'=>$al_offline->updated_at,
                        'created_at'=>$al_offline->created_at,
                    ]); 
                } 
        }  

        // syncronize appointment list table from online to offline 
        $al_online_list = DB::connection('mysql2')->table('appointment_list')->get();   
        foreach($al_online_list as $al_online){  
            $al_online_online = DB::table('appointment_list')->where('appointment_id', $al_online->appointment_id)->get();
                if(count($al_online_online) > 0){
                    DB::table('appointment_list')->where('appointment_id', $al_online->appointment_id)->update([
                        'patients_id'=>$al_online->patients_id,
                        'encoders_id'=>$al_online->encoders_id,
                        'doctors_id'=>$al_online->doctors_id,
                        'services'=>$al_online->services,
                        'amount'=>$al_online->amount,
                        'app_date'=>$al_online->app_date,
                        'app_date_end'=>$al_online->app_date_end,
                        'app_reason'=>$al_online->app_reason,
                        'is_reschedule'=>$al_online->is_reschedule,
                        'is_reschedule_date'=>$al_online->is_reschedule_date,
                        'is_reschedule_reason'=>$al_online->is_reschedule_reason,
                        'apperance'=>$al_online->apperance,
                        'is_waiting'=>$al_online->is_waiting,
                        'is_waiting_reason'=>$al_online->is_waiting_reason,
                        'is_complete'=>$al_online->is_complete,
                        'is_remove'=>$al_online->is_remove,
                        'is_remove_reason'=>$al_online->is_remove_reason,
                        'is_remove_date'=>$al_online->is_remove_date,
                        'referred_by'=>$al_online->referred_by,
                        'is_paid_bysecretary'=>$al_online->is_paid_bysecretary,
                        'status'=>$al_online->status,
                        'updated_at'=>$al_online->updated_at,
                        'created_at'=>$al_online->created_at,
                    ]);
        
                }else{
                    DB::table('appointment_list')->insert([
                        'appointment_id'=>$al_online->appointment_id,
                        'patients_id'=>$al_online->patients_id,
                        'encoders_id'=>$al_online->encoders_id,
                        'doctors_id'=>$al_online->doctors_id,
                        'services'=>$al_online->services,
                        'amount'=>$al_online->amount,
                        'app_date'=>$al_online->app_date,
                        'app_date_end'=>$al_online->app_date_end,
                        'app_reason'=>$al_online->app_reason,
                        'is_reschedule'=>$al_online->is_reschedule,
                        'is_reschedule_date'=>$al_online->is_reschedule_date,
                        'is_reschedule_reason'=>$al_online->is_reschedule_reason,
                        'apperance'=>$al_online->apperance,
                        'is_waiting'=>$al_online->is_waiting,
                        'is_waiting_reason'=>$al_online->is_waiting_reason,
                        'is_complete'=>$al_online->is_complete,
                        'is_remove'=>$al_online->is_remove,
                        'is_remove_reason'=>$al_online->is_remove_reason,
                        'is_remove_date'=>$al_online->is_remove_date,
                        'referred_by'=>$al_online->referred_by,
                        'is_paid_bysecretary'=>$al_online->is_paid_bysecretary,
                        'status'=>$al_online->status,
                        'updated_at'=>$al_online->updated_at,
                        'created_at'=>$al_online->created_at,
                    ]); 
                } 
        } 

        return true;
    }
}