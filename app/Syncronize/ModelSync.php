<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ModelSync extends Model
{ 

    public function __construc(){
        set_time_limit(800);
    }

    // okkkkkk
    public function syncronizeusersTable(){ 
        // syncronize users table from offline to online 
        $user = DB::table('users')->get();  
        foreach($user as $user){  
            $user_online = DB::connection('mysql2')->table('users')->where('user_id', $user->user_id)->get();
                if(count($user_online) > 0){
                    
                    if($user->updated_at > $user_online[0]->updated_at){  
                        DB::connection('mysql2')->table('users')->where('user_id', $user->user_id)->update([
                            'username'=>$user->username,
                            'password'=>$user->password,
                            'status'=>$user->status,
                            'type'=>$user->type,
                            'email'=>$user->email,
                            'is_verify'=>$user->is_verify,
                            'manage_by'=>$user->manage_by,
                            'remember_token'=>$user->remember_token,
                            'is_disable'=>$user->is_disable,
                            'api_token'=>$user->api_token,
                            'is_disable_msg'=>$user->is_disable_msg,
                            'created_at'=>$user->created_at,
                            'updated_at'=>$user->updated_at,
                        ]); 
                    } 
                    
                    else{
                        DB::table('users')->where('user_id', $user_online[0]->user_id)->update([
                            'username'=>$user_online[0]->username,
                            'password'=>$user_online[0]->password,
                            'status'=>$user_online[0]->status,
                            'type'=>$user_online[0]->type,
                            'email'=>$user_online[0]->email,
                            'is_verify'=>$user_online[0]->is_verify,
                            'manage_by'=>$user_online[0]->manage_by,
                            'remember_token'=>$user_online[0]->remember_token,
                            'is_disable'=>$user_online[0]->is_disable,
                            'api_token'=>$user_online[0]->api_token,
                            'is_disable_msg'=>$user_online[0]->is_disable_msg,
                            'created_at'=>$user_online[0]->created_at,
                            'updated_at'=>$user_online[0]->updated_at,
                        ]);
    
                    }
     
                }else{
                    DB::connection('mysql2')->table('users')->insert([
                        'user_id'=>$user->user_id,
                        'username'=>$user->username,
                        'password'=>$user->password,
                        'status'=>$user->status,
                        'type'=>$user->type,
                        'email'=>$user->email,
                        'is_verify'=>$user->is_verify,
                        'manage_by'=>$user->manage_by,
                        'remember_token'=>$user->remember_token,
                        'is_disable'=>$user->is_disable,
                        'api_token'=>$user->api_token,
                        'is_disable_msg'=>$user->is_disable_msg,
                        'created_at'=>$user->created_at,
                        'updated_at'=>$user->updated_at,
                    ]); 
                } 
        } 

        // syncronize users table from online to offline 
        $user_reverse = DB::connection('mysql2')->table('users')->get();  
        foreach($user_reverse as $user_reverse){  
            $user_reverse_online = DB::table('users')->where('user_id', $user_reverse->user_id)->get();
                if(count($user_reverse_online) > 0){
                    DB::table('users')->where('user_id', $user_reverse->user_id)->update([
                        'username'=>$user_reverse->username,
                        'password'=>$user_reverse->password,
                        'status'=>$user_reverse->status,
                        'type'=>$user_reverse->type,
                        'email'=>$user_reverse->email,
                        'is_verify'=>$user_reverse->is_verify,
                        'manage_by'=>$user_reverse->manage_by,
                        'remember_token'=>$user_reverse->remember_token,
                        'is_disable'=>$user_reverse->is_disable,
                        'api_token'=>$user_reverse->api_token,
                        'is_disable_msg'=>$user_reverse->is_disable_msg,
                        'created_at'=>$user_reverse->created_at,
                        'updated_at'=>$user_reverse->updated_at,
                    ]);
     
                }else{
                    DB::table('users')->insert([
                        'user_id'=>$user_reverse->user_id,
                        'username'=>$user_reverse->username,
                        'password'=>$user_reverse->password,
                        'status'=>$user_reverse->status,
                        'type'=>$user_reverse->type,
                        'email'=>$user_reverse->email,
                        'is_verify'=>$user_reverse->is_verify,
                        'manage_by'=>$user_reverse->manage_by,
                        'remember_token'=>$user_reverse->remember_token,
                        'is_disable'=>$user_reverse->is_disable,
                        'api_token'=>$user_reverse->api_token,
                        'is_disable_msg'=>$user_reverse->is_disable_msg,
                        'created_at'=>$user_reverse->created_at,
                        'updated_at'=>$user_reverse->updated_at,
                    ]); 
                } 
        }
 
        return true;
    }

    // okkkkkkk
    public function syncronizeaccount_notificationTable(){
         
        // syncronize users table from offline to online 
        $an_notification = DB::table('account_notification')->get();  
        foreach($an_notification as $an_notification){  
            $an_notification_online = DB::connection('mysql2')->table('account_notification')->where('user_id', $an_notification->user_id)->get();
                if(count($an_notification_online) > 0){ 
                        
                    if($an_notification->updated_at > $an_notification_online[0]->updated_at){  
                        DB::connection('mysql2')->table('account_notification')->where('user_id', $an_notification->user_id)->update([ 
                            'notification'=>$an_notification->notification,
                            'is_read'=>$an_notification->is_read,
                            'is_read_date'=>$an_notification->is_read_date,
                            'status'=>$an_notification->status, 
                            'created_at'=>$an_notification->created_at,
                            'updated_at'=>$an_notification->updated_at,
                        ]);
                    } 
                    
                    else{
                        DB::table('account_notification')->where('user_id', $an_notification_online[0]->user_id)->update([ 
                            'notification'=>$an_notification_online[0]->notification,
                            'is_read'=>$an_notification_online[0]->is_read,
                            'is_read_date'=>$an_notification_online[0]->is_read_date,
                            'status'=>$an_notification_online[0]->status, 
                            'created_at'=>$an_notification_online[0]->created_at,
                            'updated_at'=>$an_notification_online[0]->updated_at,
                        ]);
                    }

                }else{
                    DB::connection('mysql2')->table('account_notification')->insert([
                        'user_id'=>$an_notification->user_id,
                        'notification'=>$an_notification->notification,
                        'is_read'=>$an_notification->is_read,
                        'is_read_date'=>$an_notification->is_read_date,
                        'status'=>$an_notification->status, 
                        'created_at'=>$an_notification->created_at,
                        'updated_at'=>$an_notification->updated_at,
                    ]); 
                } 
        } 

        // syncronize account_notification table from online to offline 
        $an_reverse = DB::connection('mysql2')->table('account_notification')->get();  
        foreach($an_reverse as $an_reverse){  
            $an_reverse_online = DB::table('account_notification')->where('user_id', $an_reverse->user_id)->get();
                if(count($an_reverse_online) > 0){
                    DB::table('account_notification')->where('user_id', $an_reverse->user_id)->update([
                        'notification'=>$an_reverse->notification,
                        'is_read'=>$an_reverse->is_read,
                        'is_read_date'=>$an_reverse->is_read_date,
                        'status'=>$an_reverse->status, 
                        'created_at'=>$an_reverse->created_at,
                        'updated_at'=>$an_reverse->updated_at,
                    ]);
     
                }else{
                    DB::table('account_notification')->insert([
                        'user_id'=>$an_reverse->user_id,
                        'notification'=>$an_reverse->notification,
                        'is_read'=>$an_reverse->is_read,
                        'is_read_date'=>$an_reverse->is_read_date,
                        'status'=>$an_reverse->status, 
                        'created_at'=>$an_reverse->created_at,
                        'updated_at'=>$an_reverse->updated_at,
                    ]); 
                } 
        }

        return true;
    }

    // okkkkkkkk
    public function syncronizeappointment_list(){
          
        // syncronize users table from offline to online 
        $al_online_list_offline = DB::table('appointment_list')->get();   
        foreach($al_online_list_offline as $al_offline){  
            $al_offline_count = DB::connection('mysql2')->table('appointment_list')->where('appointment_id', $al_offline->appointment_id)->get();
                if(count($al_offline_count) > 0){ 
                    if($al_offline->updated_at > $al_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('appointment_list')->where('appointment_id', $al_offline->appointment_id)->update([
                            'patients_id'=>$al_offline->patients_id,
                            'encoders_id'=>$al_offline->encoders_id,
                            'doctors_id'=>$al_offline->doctors_id,
                            'app_date'=>$al_offline->app_date,
                            'app_date_end'=>$al_offline->app_date_end,
                            'app_reason'=>$al_offline->app_reason,
                            'apperance'=>$al_offline->apperance,
                            'is_waiting'=>$al_offline->is_waiting,
                            'is_waiting_reason'=>$al_offline->is_waiting_reason,
                            'is_complete'=>$al_offline->is_complete,
                            'is_remove'=>$al_offline->is_remove,
                            'is_remove_reason'=>$al_offline->is_remove_reason,
                            'is_remove_date'=>$al_offline->is_remove_date,
                            'referred_by'=>$al_offline->referred_by,
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
                            'app_date'=>$al_offline_count[0]->app_date,
                            'app_date_end'=>$al_offline_count[0]->app_date_end,
                            'app_reason'=>$al_offline_count[0]->app_reason,
                            'apperance'=>$al_offline_count[0]->apperance,
                            'is_waiting'=>$al_offline_count[0]->is_waiting,
                            'is_waiting_reason'=>$al_offline_count[0]->is_waiting_reason,
                            'is_complete'=>$al_offline_count[0]->is_complete,
                            'is_remove'=>$al_offline_count[0]->is_remove,
                            'is_remove_reason'=>$al_offline_count[0]->is_remove_reason,
                            'is_remove_date'=>$al_offline_count[0]->is_remove_date,
                            'referred_by'=>$al_offline_count[0]->referred_by,
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
                        'app_date'=>$al_offline->app_date,
                        'app_date_end'=>$al_offline->app_date_end,
                        'app_reason'=>$al_offline->app_reason,
                        'apperance'=>$al_offline->apperance,
                        'is_waiting'=>$al_offline->is_waiting,
                        'is_waiting_reason'=>$al_offline->is_waiting_reason,
                        'is_complete'=>$al_offline->is_complete,
                        'is_remove'=>$al_offline->is_remove,
                        'is_remove_reason'=>$al_offline->is_remove_reason,
                        'is_remove_date'=>$al_offline->is_remove_date,
                        'referred_by'=>$al_offline->referred_by,
                        'status'=>$al_offline->status,
                        'updated_at'=>$al_offline->updated_at,
                        'created_at'=>$al_offline->created_at,
                    ]); 
                } 
        }  

        // syncronize users table from online to offline 
        $al_online_list = DB::connection('mysql2')->table('appointment_list')->get();   
        foreach($al_online_list as $al_online){  
            $al_online_online = DB::table('appointment_list')->where('appointment_id', $al_online->appointment_id)->get();
                if(count($al_online_online) > 0){
                    DB::table('appointment_list')->where('appointment_id', $al_online->appointment_id)->update([
                        'patients_id'=>$al_online->patients_id,
                        'encoders_id'=>$al_online->encoders_id,
                        'doctors_id'=>$al_online->doctors_id,
                        'app_date'=>$al_online->app_date,
                        'app_date_end'=>$al_online->app_date_end,
                        'app_reason'=>$al_online->app_reason,
                        'apperance'=>$al_online->apperance,
                        'is_waiting'=>$al_online->is_waiting,
                        'is_waiting_reason'=>$al_online->is_waiting_reason,
                        'is_complete'=>$al_online->is_complete,
                        'is_remove'=>$al_online->is_remove,
                        'is_remove_reason'=>$al_online->is_remove_reason,
                        'is_remove_date'=>$al_online->is_remove_date,
                        'referred_by'=>$al_online->referred_by,
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
                        'app_date'=>$al_online->app_date,
                        'app_date_end'=>$al_online->app_date_end,
                        'app_reason'=>$al_online->app_reason,
                        'apperance'=>$al_online->apperance,
                        'is_waiting'=>$al_online->is_waiting,
                        'is_waiting_reason'=>$al_online->is_waiting_reason,
                        'is_complete'=>$al_online->is_complete,
                        'is_remove'=>$al_online->is_remove,
                        'is_remove_reason'=>$al_online->is_remove_reason,
                        'is_remove_date'=>$al_online->is_remove_date,
                        'referred_by'=>$al_online->referred_by,
                        'status'=>$al_online->status,
                        'updated_at'=>$al_online->updated_at,
                        'created_at'=>$al_online->created_at,
                    ]); 
                } 
        } 

        return true;
    }

    // okkkkkkkk
    public function syncronizeappointment_settings(){
        // syncronize users table from offline to online 
        $as_offline = DB::table('appointment_settings')->get();  
        foreach($as_offline as $as){  
            $as_count = DB::connection('mysql2')->table('appointment_settings')->where('app_settings_id', $as->app_settings_id)->get();
                if(count($as_count) > 0){ 
                    if($as->updated_at > $as_count[0]->updated_at){  
                        DB::connection('mysql2')->table('appointment_settings')->where('app_settings_id', $as->app_settings_id)->update([  
                            'encoder_id'=>$as->encoder_id,
                            'doctors_id'=>$as->doctors_id,
                            'app_time_start'=>$as->app_time_start,
                            'app_time_close'=>$as->app_time_close,
                            'app_duration'=>$as->app_duration,
                            'updated_at'=>$as->updated_at,
                            'created_at'=>$as->created_at
                        ]);
                    } 
                    
                    else{
                        DB::table('appointment_settings')->where('app_settings_id', $as_count[0]->app_settings_id)->update([  
                            'encoder_id'=>$as_count[0]->encoder_id,
                            'doctors_id'=>$as_count[0]->doctors_id,
                            'app_time_start'=>$as_count[0]->app_time_start,
                            'app_time_close'=>$as_count[0]->app_time_close,
                            'app_duration'=>$as_count[0]->app_duration,
                            'updated_at'=>$as_count[0]->updated_at,
                            'created_at'=>$as_count[0]->created_at
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('appointment_settings')->insert([ 
                        'app_settings_id'=>$as->app_settings_id,
                        'encoder_id'=>$as->encoder_id,
                        'doctors_id'=>$as->doctors_id,
                        'app_time_start'=>$as->app_time_start,
                        'app_time_close'=>$as->app_time_close,
                        'app_duration'=>$as->app_duration,
                        'updated_at'=>$as->updated_at,
                        'created_at'=>$as->created_at
                    ]); 
                } 
        }


        // syncronize appointment_settings table from online to offline 
        $as_online = DB::connection('mysql2')->table('appointment_settings')->get();  
        foreach($as_online as $as_online){  
            $as_online_count = DB::table('appointment_settings')->where('app_settings_id', $as_online->app_settings_id)->get();
                if(count($as_online_count) > 0){
                    DB::table('appointment_settings')->where('app_settings_id', $as_online->app_settings_id)->update([  
                        'encoder_id'=>$as_online->encoder_id,
                        'doctors_id'=>$as_online->doctors_id,
                        'app_time_start'=>$as_online->app_time_start,
                        'app_time_close'=>$as_online->app_time_close,
                        'app_duration'=>$as_online->app_duration,
                        'updated_at'=>$as_online->updated_at,
                        'created_at'=>$as_online->created_at
                    ]);
     
                }else{
                    DB::table('appointment_settings')->insert([ 
                        'app_settings_id'=>$as_online->app_settings_id,
                        'encoder_id'=>$as_online->encoder_id,
                        'doctors_id'=>$as_online->doctors_id,
                        'app_time_start'=>$as_online->app_time_start,
                        'app_time_close'=>$as_online->app_time_close,
                        'app_duration'=>$as_online->app_duration,
                        'updated_at'=>$as_online->updated_at,
                        'created_at'=>$as_online->created_at
                    ]); 
                } 
        } 

        return true;
    } 

    // okkkkkkkk
    public function syncronizeclinic(){ 
        // syncronize users table from offline to online  
        $clinic_offline_List = DB::table('clinic')->get();  
        foreach($clinic_offline_List as $clinic_offline){  
            $clinic_offline_count = DB::connection('mysql2')->table('clinic')->where('clinic_id', $clinic_offline->clinic_id)->get();
                if(count($clinic_offline_count) > 0){
                     
                    if($clinic_offline->updated_at > $clinic_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('clinic')->where('clinic_id', $clinic_offline->clinic_id)->update([  
                            'doctors_id'=>$clinic_offline->doctors_id, 
                            'encoder_id'=>$clinic_offline->encoder_id, 
                            'management_id'=>$clinic_offline->management_id, 
                            'clinic'=>$clinic_offline->clinic, 
                            'address'=>$clinic_offline->address, 
                            'days_open'=>$clinic_offline->days_open, 
                            'time_open'=>$clinic_offline->time_open, 
                            'contact_no'=>$clinic_offline->contact_no, 
                            'remarks'=>$clinic_offline->remarks, 
                            'status'=>$clinic_offline->status, 
                            'created_at'=>$clinic_offline->created_at, 
                            'updated_at'=>$clinic_offline->updated_at, 
                        ]);
                    } 
                    
                    else{
                        DB::table('clinic')->where('clinic_id', $clinic_offline_count[0]->clinic_id)->update([  
                            'doctors_id'=>$clinic_offline_count[0]->doctors_id, 
                            'encoder_id'=>$clinic_offline_count[0]->encoder_id, 
                            'management_id'=>$clinic_offline_count[0]->management_id, 
                            'clinic'=>$clinic_offline_count[0]->clinic, 
                            'address'=>$clinic_offline_count[0]->address, 
                            'days_open'=>$clinic_offline_count[0]->days_open, 
                            'time_open'=>$clinic_offline_count[0]->time_open, 
                            'contact_no'=>$clinic_offline_count[0]->contact_no, 
                            'remarks'=>$clinic_offline_count[0]->remarks, 
                            'status'=>$clinic_offline_count[0]->status, 
                            'created_at'=>$clinic_offline_count[0]->created_at, 
                            'updated_at'=>$clinic_offline_count[0]->updated_at, 
                        ]);
                    }
     
                }else{
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
                        'remarks'=>$clinic_offline->remarks, 
                        'status'=>$clinic_offline->status, 
                        'created_at'=>$clinic_offline->created_at, 
                        'updated_at'=>$clinic_offline->updated_at,  
                    ]); 
                } 
        }

        // syncronize appointment_settings table from online to offline 
        $clinic_online_List = DB::connection('mysql2')->table('clinic')->get();  
        foreach($clinic_online_List as $clinic_online){  
            $clinic_online_count = DB::table('clinic')->where('clinic_id', $clinic_online->clinic_id)->get();
                if(count($clinic_online_count) > 0){
                    DB::table('clinic')->where('clinic_id', $clinic_online->clinic_id)->update([  
                        'doctors_id'=>$clinic_online->doctors_id, 
                        'encoder_id'=>$clinic_online->encoder_id, 
                        'management_id'=>$clinic_online->management_id, 
                        'clinic'=> $clinic_online->clinic,
                        'address'=>$clinic_online->address, 
                        'days_open'=>$clinic_online->days_open, 
                        'time_open'=>$clinic_online->time_open, 
                        'contact_no'=>$clinic_online->contact_no, 
                        'remarks'=>$clinic_online->remarks, 
                        'status'=>$clinic_online->status, 
                        'created_at'=>$clinic_online->created_at, 
                        'updated_at'=>$clinic_online->updated_at, 
                    ]);
     
                }else{
                    DB::table('clinic')->insert([ 
                        'clinic_id'=>$clinic_online->clinic_id, 
                        'doctors_id'=>$clinic_online->doctors_id, 
                        'encoder_id'=>$clinic_online->encoder_id, 
                        'management_id'=>$clinic_online->management_id, 
                        'clinic'=> $clinic_online->clinic,
                        'address'=>$clinic_online->address, 
                        'days_open'=>$clinic_online->days_open, 
                        'time_open'=>$clinic_online->time_open, 
                        'contact_no'=>$clinic_online->contact_no, 
                        'remarks'=>$clinic_online->remarks, 
                        'status'=>$clinic_online->status, 
                        'created_at'=>$clinic_online->created_at, 
                        'updated_at'=>$clinic_online->updated_at,  
                    ]); 
                } 
        }

        return true;
    } 

    // okkkkkkkkkk
    public function syncronizedoctors(){

        // syncronize appointment_settings table from online to offline 
        $doctor_offline_List = DB::table('doctors')->get();  
        foreach($doctor_offline_List as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors')->where('doctors_id', $doctor_offline->doctors_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors')->where('doctors_id', $doctor_offline->doctors_id)->update([ 
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
                            'created_at'=>$doctor_offline->created_at, 
                            'updated_at'=>$doctor_offline->updated_at,   
                        ]);
                    } 
                    
                    else{
                        DB::table('doctors')->where('doctors_id', $doctor_offline_count[0]->doctors_id)->update([ 
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
                            'created_at'=>$doctor_offline_count[0]->created_at, 
                            'updated_at'=>$doctor_offline_count[0]->updated_at,   
                        ]);
                    }
     
                }else{
                    DB::connection('mysql2')->table('doctors')->insert([  
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
                        'created_at'=>$doctor_offline->created_at, 
                        'updated_at'=>$doctor_offline->updated_at,  
                    ]); 
                } 
        } 


        // syncronize appointment_settings table from offline to online 
        $doctor_online_List = DB::connection('mysql2')->table('doctors')->get();  
        foreach($doctor_online_List as $doctor_online){  
            $doctor_online_count = DB::table('doctors')->where('doctors_id', $doctor_online->doctors_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors')->where('doctors_id', $doctor_online->doctors_id)->update([ 
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
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at,   
                    ]);
     
                }else{
                    DB::table('doctors')->insert([  
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
                        'created_at'=>$doctor_online->created_at, 
                        'updated_at'=>$doctor_online->updated_at,  
                    ]); 
                } 
        }   

        return true;
    }

    // okkkkkkkkkk
    public function syncronizenotes(){
         // syncronize appointment_settings table from offline to online 
         $notes_offline_List = DB::table('doctors_notes')->get();  
         foreach($notes_offline_List as $notes_ofline){  
             $notes_ofline_count = DB::connection('mysql2')->table('doctors_notes')->where('notes_id', $notes_ofline->notes_id)->get();
                 if(count($notes_ofline_count) > 0){ 
                     if($notes_ofline->updated_at > $notes_ofline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_notes')->where('notes_id', $notes_ofline->notes_id)->update([     
                            'patients_id'=>$notes_ofline->patients_id,
                            'doctors_id'=>$notes_ofline->doctors_id,
                            'initial_diagnosis'=>$notes_ofline->initial_diagnosis,
                            'notes'=>$notes_ofline->notes,
                            'status'=>$notes_ofline->status,
                            'created_at'=>$notes_ofline->created_at,
                            'updated_at'=>$notes_ofline->updated_at 
                        ]);
                    } 
                    
                    else{
                        DB::table('doctors_notes')->where('notes_id', $notes_ofline_count[0]->notes_id)->update([     
                            'patients_id'=>$notes_ofline_count[0]->patients_id,
                            'doctors_id'=>$notes_ofline_count[0]->doctors_id,
                            'initial_diagnosis'=>$notes_ofline_count[0]->initial_diagnosis,
                            'notes'=>$notes_ofline_count[0]->notes,
                            'status'=>$notes_ofline_count[0]->status,
                            'created_at'=>$notes_ofline_count[0]->created_at,
                            'updated_at'=>$notes_ofline_count[0]->updated_at 
                        ]);
                    }  
                 }else{
                     DB::connection('mysql2')->table('doctors_notes')->insert([   
                         'notes_id'=>$notes_ofline->notes_id,
                         'patients_id'=>$notes_ofline->patients_id,
                         'doctors_id'=>$notes_ofline->doctors_id,
                         'initial_diagnosis'=>$notes_ofline->initial_diagnosis,
                         'notes'=>$notes_ofline->notes,
                         'status'=>$notes_ofline->status,
                         'created_at'=>$notes_ofline->created_at,
                         'updated_at'=>$notes_ofline->updated_at 
                     ]); 
                 } 
         }

        // syncronize appointment_settings table from online to offline 
        $notes_online_List = DB::connection('mysql2')->table('doctors_notes')->get();  
        foreach($notes_online_List as $notes_online){  
            $notes_online_count = DB::table('doctors_notes')->where('notes_id', $notes_online->notes_id)->get();
                if(count($notes_online_count) > 0){
                    DB::table('doctors_notes')->where('notes_id', $notes_online->notes_id)->update([     
                        'patients_id'=>$notes_online->patients_id,
                        'doctors_id'=>$notes_online->doctors_id,
                        'initial_diagnosis'=>$notes_online->initial_diagnosis,
                        'notes'=>$notes_online->notes,
                        'status'=>$notes_online->status,
                        'created_at'=>$notes_online->created_at,
                        'updated_at'=>$notes_online->updated_at 
                    ]);
     
                }else{
                    DB::table('doctors_notes')->insert([   
                        'notes_id'=>$notes_online->notes_id,
                        'patients_id'=>$notes_online->patients_id,
                        'doctors_id'=>$notes_online->doctors_id,
                        'initial_diagnosis'=>$notes_online->initial_diagnosis,
                        'notes'=>$notes_online->notes,
                        'status'=>$notes_online->status,
                        'created_at'=>$notes_online->created_at,
                        'updated_at'=>$notes_online->updated_at 
                    ]); 
                } 
        }  

        return true;
    }

    // okkkkkkkkkk
    public function doctorsTreatmentPlan(){
        // syncronize appointment_settings table from offline to online 
        $treatmentplan = DB::table('doctors_treatment_plan')->get();  
        foreach($treatmentplan as $treatmentplan){  
            $treatmentplan_count = DB::connection('mysql2')->table('doctors_treatment_plan')->where('dtp_id', $treatmentplan->dtp_id)->get();
                if(count($treatmentplan_count) > 0){ 
                    if($treatmentplan->updated_at > $treatmentplan_count[0]->updated_at){  
                       DB::connection('mysql2')->table('doctors_treatment_plan')->where('dtp_id', $treatmentplan->dtp_id)->update([     
                           'treatment_id'=>$treatmentplan->treatment_id,
                           'management_id'=>$treatmentplan->management_id,
                           'doctors_id'=>$treatmentplan->doctors_id,
                           'patient_id'=>$treatmentplan->patient_id,
                           'treatment_plan'=>$treatmentplan->treatment_plan,
                           'treatment_plan'=>$treatmentplan->treatment_plan,
                           'status'=>$treatmentplan->status,
                           'updated_at'=>$treatmentplan->updated_at 
                       ]);
                   } 
                   
                   else{
                       DB::table('doctors_treatment_plan')->where('dtp_id', $treatmentplan_count[0]->dtp_id)->update([     
                            'treatment_id'=>$treatmentplan_count[0]->treatment_id,
                            'management_id'=>$treatmentplan_count[0]->management_id,
                            'doctors_id'=>$treatmentplan_count[0]->doctors_id,
                            'patient_id'=>$treatmentplan_count[0]->patient_id,
                            'treatment_plan'=>$treatmentplan_count[0]->treatment_plan,
                            'treatment_plan'=>$treatmentplan_count[0]->treatment_plan,
                            'status'=>$treatmentplan_count[0]->status,
                            'updated_at'=>$treatmentplan_count[0]->updated_at 
                       ]);
                   } 

                }else{
                    DB::connection('mysql2')->table('doctors_treatment_plan')->insert([   
                        'dtp_id'=>$treatmentplan->dtp_id,
                        'treatment_id'=>$treatmentplan->treatment_id,
                        'management_id'=>$treatmentplan->management_id,
                        'doctors_id'=>$treatmentplan->doctors_id,
                        'patient_id'=>$treatmentplan->patient_id,
                        'treatment_plan'=>$treatmentplan->treatment_plan,
                        'treatment_plan'=>$treatmentplan->treatment_plan,
                        'status'=>$treatmentplan->status,
                        'updated_at'=>$treatmentplan->updated_at 
                    ]); 
                } 
        }

       // syncronize appointment_settings table from online to offline 
       $treatmentplan_online = DB::connection('mysql2')->table('doctors_treatment_plan')->get();  
       foreach($treatmentplan_online as $treatmentplan_online){  
           $treatmentplan_online_count = DB::table('doctors_treatment_plan')->where('dtp_id', $treatmentplan_online->dtp_id)->get();
               if(count($treatmentplan_online_count) > 0){
                   DB::table('doctors_treatment_plan')->where('dtp_id', $treatmentplan_online->dtp_id)->update([     
                        'treatment_id'=>$treatmentplan_online->treatment_id,
                        'management_id'=>$treatmentplan_online->management_id,
                        'doctors_id'=>$treatmentplan_online->doctors_id,
                        'patient_id'=>$treatmentplan_online->patient_id,
                        'treatment_plan'=>$treatmentplan_online->treatment_plan,
                        'treatment_plan'=>$treatmentplan_online->treatment_plan,
                        'status'=>$treatmentplan_online->status,
                        'updated_at'=>$treatmentplan_online->updated_at 
                   ]);
    
               }else{
                   DB::table('doctors_treatment_plan')->insert([   
                       'dtp_id'=>$treatmentplan_online->dtp_id,
                       'treatment_id'=>$treatmentplan_online->treatment_id,
                        'management_id'=>$treatmentplan_online->management_id,
                        'doctors_id'=>$treatmentplan_online->doctors_id,
                        'patient_id'=>$treatmentplan_online->patient_id,
                        'treatment_plan'=>$treatmentplan_online->treatment_plan,
                        'treatment_plan'=>$treatmentplan_online->treatment_plan,
                        'status'=>$treatmentplan_online->status,
                        'updated_at'=>$treatmentplan_online->updated_at 
                   ]); 
               } 
       }  

       return true;
   }
    

    //  okkkkkkkkkk
    public function syncronizedoctors_prescription(){

        // syncronize appointment_settings table from offline to online  
        $dp_offline_List = DB::table('doctors_prescription')->get();  
        foreach($dp_offline_List as $dp_offline){  
            $dp_offline_count = DB::connection('mysql2')->table('doctors_prescription')->where('dp_id', $dp_offline->dp_id)->get();
                if(count($dp_offline_count) > 0){
                    if($dp_offline->updated_at > $dp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_prescription')->where('dp_id', $dp_offline->dp_id)->update([       
                            'prescription_id'=>$dp_offline->prescription_id, 
                            'management_id'=>$dp_offline->management_id, 
                            'patients_id'=>$dp_offline->patients_id, 
                            'case_file'=>$dp_offline->case_file, 
                            'doctors_id'=>$dp_offline->doctors_id,  
                            'prescription'=>$dp_offline->prescription, 
                            'is_package'=>$dp_offline->is_package, 
                            'brand'=>$dp_offline->brand, 
                            'quantity'=>$dp_offline->quantity, 
                            'type'=>$dp_offline->type, 
                            'dosage'=>$dp_offline->dosage,
                            'per_day'=>$dp_offline->per_day,
                            'per_take'=>$dp_offline->per_take,
                            'remarks'=>$dp_offline->remarks, 
                            'prescription_type'=>$dp_offline->prescription_type, 
                            'claim_id'=>$dp_offline->claim_id, 
                            'created_at'=>$dp_offline->created_at, 
                            'updated_at'=>$dp_offline->updated_at
                        ]); 
                    } 
                    
                    else{
                        DB::table('doctors_prescription')->where('dp_id', $dp_offline_count[0]->dp_id)->update([       
                            'prescription_id'=>$dp_offline_count[0]->prescription_id, 
                            'management_id'=>$dp_offline_count[0]->management_id, 
                            'patients_id'=>$dp_offline_count[0]->patients_id, 
                            'case_file'=>$dp_offline_count[0]->case_file, 
                            'doctors_id'=>$dp_offline_count[0]->doctors_id,  
                            'prescription'=>$dp_offline_count[0]->prescription, 
                            'is_package'=>$dp_offline_count[0]->is_package, 
                            'brand'=>$dp_offline_count[0]->brand, 
                            'quantity'=>$dp_offline_count[0]->quantity, 
                            'type'=>$dp_offline_count[0]->type, 
                            'dosage'=>$dp_offline_count[0]->dosage,
                            'per_day'=>$dp_offline_count[0]->per_day,
                            'per_take'=>$dp_offline_count[0]->per_take,
                            'remarks'=>$dp_offline_count[0]->remarks, 
                            'prescription_type'=>$dp_offline_count[0]->prescription_type, 
                            'claim_id'=>$dp_offline_count[0]->claim_id, 
                            'created_at'=>$dp_offline_count[0]->created_at, 
                            'updated_at'=>$dp_offline_count[0]->updated_at
                        ]); 
                    }   
                    
                }else{
                    DB::connection('mysql2')->table('doctors_prescription')->insert([   
                        'prescription_id'=>$dp_offline->prescription_id, 
                        'management_id'=>$dp_offline->management_id, 
                        'patients_id'=>$dp_offline->patients_id, 
                        'doctors_id'=>$dp_offline->doctors_id,  
                        'prescription'=>$dp_offline->prescription, 
                        'is_package'=>$dp_offline->is_package, 
                        'brand'=>$dp_offline->brand, 
                        'quantity'=>$dp_offline->quantity, 
                        'type'=>$dp_offline->type, 
                        'dosage'=>$dp_offline->dosage,
                        'per_day'=>$dp_offline->per_day,
                        'per_take'=>$dp_offline->per_take,
                        'remarks'=>$dp_offline->remarks, 
                        'created_at'=>$dp_offline->created_at, 
                        'updated_at'=>$dp_offline->updated_at
                    ]); 
                } 
        } 


        // syncronize appointment_settings table from online to offline 
        $dp_online_List = DB::connection('mysql2')->table('doctors_prescription')->get();  
        foreach($dp_online_List as $dp_online){  
            $dp_online_count = DB::table('doctors_prescription')->where('dp_id', $dp_online->dp_id)->get();
                if(count($dp_online_count) > 0){
                    DB::table('doctors_prescription')->where('dp_id', $dp_online->dp_id)->update([       
                        'prescription_id'=>$dp_online->prescription_id, 
                        'management_id'=>$dp_online->management_id, 
                        'patients_id'=>$dp_online->patients_id, 
                        'doctors_id'=>$dp_online->doctors_id,  
                        'prescription'=>$dp_online->prescription, 
                        'is_package'=>$dp_online->is_package, 
                        'brand'=>$dp_online->brand, 
                        'quantity'=>$dp_online->quantity, 
                        'type'=>$dp_online->type, 
                        'dosage'=>$dp_online->dosage,
                        'per_day'=>$dp_online->per_day,
                        'per_take'=>$dp_online->per_take,
                        'remarks'=>$dp_online->remarks, 
                        'prescription_type'=>$dp_online->prescription_type, 
                        'claim_id'=>$dp_online->claim_id, 
                        'created_at'=>$dp_online->created_at, 
                        'updated_at'=>$dp_online->updated_at
                    ]); 
                }else{
                    DB::table('doctors_prescription')->insert([   
                        'dp_id'=>$dp_online->dp_id, 
                        'prescription_id'=>$dp_online->prescription_id, 
                        'management_id'=>$dp_online->management_id, 
                        'patients_id'=>$dp_online->patients_id, 
                        'doctors_id'=>$dp_online->doctors_id,  
                        'prescription'=>$dp_online->prescription, 
                        'is_package'=>$dp_online->is_package, 
                        'brand'=>$dp_online->brand, 
                        'quantity'=>$dp_online->quantity, 
                        'type'=>$dp_online->type, 
                        'dosage'=>$dp_online->dosage,
                        'per_day'=>$dp_online->per_day,
                        'per_take'=>$dp_online->per_take,
                        'remarks'=>$dp_online->remarks, 
                        'prescription_type'=>$dp_online->prescription_type, 
                        'claim_id'=>$dp_online->claim_id, 
                        'created_at'=>$dp_online->created_at, 
                        'updated_at'=>$dp_online->updated_at
                    ]); 
                } 
        }  

        return true;
    }

    // okkkkkkkk
    public function syncronizeencoder(){
        // syncronize appointment_settings table from offline to online   
        $enc_offline_List = DB::table('encoder')->get();  
        foreach($enc_offline_List as $enc_offline){  
            $enc_offline_count = DB::connection('mysql2')->table('encoder')->where('encoder_id', $enc_offline->encoder_id)->get();
                if(count($enc_offline_count) > 0){ 
                    if($enc_offline->updated_at > $enc_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('encoder')->where('encoder_id', $enc_offline->encoder_id)->update([     
                            'doctors_id'=>$enc_offline->doctors_id,
                            'management_id'=>$enc_offline->management_id,
                            'user_id'=>$enc_offline->user_id,
                            'name'=>$enc_offline->name,
                            'address'=>$enc_offline->address,
                            'gender'=>$enc_offline->gender,
                            'birthday'=>$enc_offline->birthday,
                            'image'=>$enc_offline->image,
                            'status'=>$enc_offline->status,
                            'updated_at'=>$enc_offline->updated_at,
                            'created_at'=>$enc_offline->created_at
                        ]);
                    } 
                    
                    else{
                        DB::table('encoder')->where('encoder_id', $enc_offline_count[0]->encoder_id)->update([     
                            'doctors_id'=>$enc_offline_count[0]->doctors_id,
                            'management_id'=>$enc_offline_count[0]->management_id,
                            'user_id'=>$enc_offline_count[0]->user_id,
                            'name'=>$enc_offline_count[0]->name,
                            'address'=>$enc_offline_count[0]->address,
                            'gender'=>$enc_offline_count[0]->gender,
                            'birthday'=>$enc_offline_count[0]->birthday,
                            'image'=>$enc_offline_count[0]->image,
                            'status'=>$enc_offline_count[0]->status,
                            'updated_at'=>$enc_offline_count[0]->updated_at,
                            'created_at'=>$enc_offline_count[0]->created_at
                        ]);
                    }  
                     
                }else{
                    DB::connection('mysql2')->table('encoder')->insert([    
                        'encoder_id'=>$enc_offline->encoder_id,
                        'doctors_id'=>$enc_offline->doctors_id,
                        'management_id'=>$enc_offline->management_id,
                        'user_id'=>$enc_offline->user_id,
                        'name'=>$enc_offline->name,
                        'address'=>$enc_offline->address,
                        'gender'=>$enc_offline->gender,
                        'birthday'=>$enc_offline->birthday,
                        'image'=>$enc_offline->image,
                        'status'=>$enc_offline->status,
                        'updated_at'=>$enc_offline->updated_at,
                        'created_at'=>$enc_offline->created_at
                    ]); 
                } 
        }

        // syncronize appointment_settings table from online to offline 
        $enc_online_List = DB::connection('mysql2')->table('encoder')->get();  
        foreach($enc_online_List as $enc_online){  
            $enc_online_count = DB::table('encoder')->where('encoder_id', $enc_online->encoder_id)->get();
                if(count($enc_online_count) > 0){
                    DB::table('encoder')->where('encoder_id', $enc_online->encoder_id)->update([     
                        'doctors_id'=>$enc_online->doctors_id,
                        'management_id'=>$enc_online->management_id,
                        'user_id'=>$enc_online->user_id,
                        'name'=>$enc_online->name,
                        'address'=>$enc_online->address,
                        'gender'=>$enc_online->gender,
                        'birthday'=>$enc_online->birthday,
                        'image'=>$enc_online->image,
                        'status'=>$enc_online->status,
                        'updated_at'=>$enc_online->updated_at,
                        'created_at'=>$enc_online->created_at
                    ]); 
                }else{
                    DB::table('encoder')->insert([    
                        'encoder_id'=>$enc_online->encoder_id,
                        'doctors_id'=>$enc_online->doctors_id,
                        'management_id'=>$enc_online->management_id,
                        'user_id'=>$enc_online->user_id,
                        'name'=>$enc_online->name,
                        'address'=>$enc_online->address,
                        'gender'=>$enc_online->gender,
                        'birthday'=>$enc_online->birthday,
                        'image'=>$enc_online->image,
                        'status'=>$enc_online->status,
                        'updated_at'=>$enc_online->updated_at,
                        'created_at'=>$enc_online->created_at
                    ]); 
                } 
        }   

        return true;
    }

    // okkkkkkkk
    public function syncronizeimaging(){ 
        // syncronize appointment_settings table from offline to online  
        $imaging_offline_List = DB::table('imaging')->get();  
        foreach($imaging_offline_List as $imaging_offline){  
            $imaging_offline_count = DB::connection('mysql2')->table('imaging')->where('i_id', $imaging_offline->i_id)->get();
                if(count($imaging_offline_count) > 0){ 
                    if($imaging_offline->updated_at > $imaging_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('imaging')->where('i_id', $imaging_offline->i_id)->update([      
                            'imaging_id'=> $imaging_offline->imaging_id,
                            'management_id'=> $imaging_offline->management_id,
                            'user_id'=> $imaging_offline->user_id,
                            'name'=> $imaging_offline->name,
                            'gender'=> $imaging_offline->gender,
                            'birthday'=> $imaging_offline->birthday,
                            'address'=> $imaging_offline->address,
                            'role'=> $imaging_offline->role,
                            'added_by'=> $imaging_offline->added_by,
                            'address'=> $imaging_offline->address,
                            'created_at'=> $imaging_offline->created_at,
                            'updated_at'=> $imaging_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('imaging')->where('i_id', $imaging_offline_count[0]->i_id)->update([      
                            'imaging_id'=> $imaging_offline_count[0]->imaging_id,
                            'management_id'=> $imaging_offline_count[0]->management_id,
                            'user_id'=> $imaging_offline_count[0]->user_id,
                            'name'=> $imaging_offline_count[0]->name,
                            'gender'=> $imaging_offline_count[0]->gender,
                            'birthday'=> $imaging_offline_count[0]->birthday,
                            'address'=> $imaging_offline_count[0]->address,
                            'role'=> $imaging_offline_count[0]->role,
                            'added_by'=> $imaging_offline_count[0]->added_by,
                            'address'=> $imaging_offline_count[0]->address,
                            'created_at'=> $imaging_offline_count[0]->created_at,
                            'updated_at'=> $imaging_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('imaging')->insert([    
                        'i_id'=> $imaging_offline->i_id,
                        'imaging_id'=> $imaging_offline->imaging_id,
                        'management_id'=> $imaging_offline->management_id,
                        'user_id'=> $imaging_offline->user_id,
                        'name'=> $imaging_offline->name, 
                        'gender'=> $imaging_offline->gender,
                        'birthday'=> $imaging_offline->birthday,
                        'address'=> $imaging_offline->address,
                        'role'=> $imaging_offline->role,
                        'added_by'=> $imaging_offline->added_by,
                        'address'=> $imaging_offline->address,
                        'created_at'=> $imaging_offline->created_at,
                        'updated_at'=> $imaging_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $imaging_online_List = DB::connection('mysql2')->table('imaging')->get();  
        foreach($imaging_online_List as $imaging_online){  
            $imaging_online_count = DB::table('imaging')->where('i_id', $imaging_online->i_id)->get();
                if(count($imaging_online_count) > 0){
                    DB::table('imaging')->where('i_id', $imaging_online->i_id)->update([      
                        'imaging_id'=> $imaging_online->imaging_id,
                        'management_id'=> $imaging_online->management_id,
                        'user_id'=> $imaging_online->user_id,
                        'name'=> $imaging_online->name,
                        'gender'=> $imaging_online->gender,
                        'birthday'=> $imaging_online->birthday,
                        'address'=> $imaging_online->address,
                        'role'=> $imaging_online->role,
                        'added_by'=> $imaging_online->added_by,
                        'address'=> $imaging_online->address,
                        'created_at'=> $imaging_online->created_at,
                        'updated_at'=> $imaging_online->updated_at,
                    ]); 
                }else{
                    DB::table('imaging')->insert([    
                         'i_id'=> $imaging_online->i_id,
                         'imaging_id'=> $imaging_online->imaging_id,
                         'management_id'=> $imaging_online->management_id,
                         'user_id'=> $imaging_online->user_id,
                         'name'=> $imaging_online->name,
                         'gender'=> $imaging_online->gender,
                         'birthday'=> $imaging_online->birthday,
                         'address'=> $imaging_online->address,
                         'role'=> $imaging_online->role,
                         'added_by'=> $imaging_online->added_by,
                         'address'=> $imaging_online->address,
                         'created_at'=> $imaging_online->created_at,
                         'updated_at'=> $imaging_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkkk
    public function syncronizeimaging_center(){ 
        // syncronize appointment_settings table from offline to online  
        $imaging_center_offline_List = DB::table('imaging_center')->get();  
        foreach($imaging_center_offline_List as $imaging_center_offline){  
            $imaging_center_offline_count = DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id', $imaging_center_offline->imaging_center_id)->get();
                if(count($imaging_center_offline_count) > 0){  
                    if($imaging_center_offline->updated_at > $imaging_center_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id', $imaging_center_offline->imaging_center_id)->update([      
                            'patients_id'=>$imaging_center_offline->patients_id,
                            'doctors_id'=>$imaging_center_offline->doctors_id,
                            'ward_nurse_id'=>$imaging_center_offline->ward_nurse_id,
                            'case_file'=>$imaging_center_offline->case_file,
                            'radiologist'=>$imaging_center_offline->radiologist,
                            'request_ward'=>$imaging_center_offline->request_ward,
                            'request_doctor'=>$imaging_center_offline->request_doctor,
                            'imaging_order'=>$imaging_center_offline->imaging_order,
                            'imaging_remarks'=>$imaging_center_offline->imaging_remarks,
                            'imaging_center'=>$imaging_center_offline->imaging_center,
                            'imaging_result'=>$imaging_center_offline->imaging_result,
                            'imaging_results_remarks'=>$imaging_center_offline->imaging_results_remarks,
                            'imaging_result_attachment'=>$imaging_center_offline->imaging_result_attachment,
                            'is_viewed'=>$imaging_center_offline->is_viewed, 
                            'is_processed'=>$imaging_center_offline->is_processed,
                            'processed_by'=>$imaging_center_offline->processed_by,  
                            'start_time'=>$imaging_center_offline->start_time,
                            'end_time'=>$imaging_center_offline->end_time,
                            'is_pending'=>$imaging_center_offline->is_pending,
                            'pending_reason'=>$imaging_center_offline->pending_reason,
                            'pending_date'=>$imaging_center_offline->pending_date,
                            'pending_by'=>$imaging_center_offline->pending_by,
                            'manage_by'=>$imaging_center_offline->manage_by, 
                            'updated_at'=>$imaging_center_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('imaging_center')->where('imaging_center_id', $imaging_center_offline_count[0]->imaging_center_id)->update([     
                            'patients_id'=>$imaging_center_offline_count[0]->patients_id,
                            'ward_nurse_id'=>$imaging_center_offline_count[0]->ward_nurse_id,
                            'case_file'=>$imaging_center_offline_count[0]->case_file,
                            'doctors_id'=>$imaging_center_offline_count[0]->doctors_id,
                            'radiologist'=>$imaging_center_offline_count[0]->radiologist, 
                            'request_ward'=>$imaging_center_offline_count[0]->request_ward, 
                            'request_doctor'=>$imaging_center_offline_count[0]->request_doctor, 
                            'imaging_order'=>$imaging_center_offline_count[0]->imaging_order,
                            'imaging_remarks'=>$imaging_center_offline_count[0]->imaging_remarks,
                            'imaging_center'=>$imaging_center_offline_count[0]->imaging_center,
                            'imaging_result'=>$imaging_center_offline_count[0]->imaging_result,
                            'imaging_results_remarks'=>$imaging_center_offline_count[0]->imaging_results_remarks,
                            'imaging_result_attachment'=>$imaging_center_offline_count[0]->imaging_result_attachment,
                            'is_viewed'=>$imaging_center_offline_count[0]->is_viewed, 
                            'is_processed'=>$imaging_center_offline_count[0]->is_processed,
                            'processed_by'=>$imaging_center_offline_count[0]->processed_by,
                            'start_time'=>$imaging_center_offline_count[0]->start_time,
                            'end_time'=>$imaging_center_offline_count[0]->end_time,
                            'is_pending'=>$imaging_center_offline_count[0]->is_pending,
                            'pending_reason'=>$imaging_center_offline_count[0]->pending_reason,
                            'pending_date'=>$imaging_center_offline_count[0]->pending_date,
                            'pending_by'=>$imaging_center_offline_count[0]->pending_by,
                            'manage_by'=>$imaging_center_offline_count[0]->manage_by,
                            'created_at'=>$imaging_center_offline_count[0]->created_at,
                            'updated_at'=>$imaging_center_offline_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('imaging_center')->insert([    
                        'imaging_center_id'=>$imaging_center_offline->imaging_center_id,
                          'patients_id'=>$imaging_center_offline->patients_id,
                          'doctors_id'=>$imaging_center_offline->doctors_id,
                          'ward_nurse_id'=>$imaging_center_offline->ward_nurse_id,
                          'case_file'=>$imaging_center_offline->case_file,
                          'radiologist'=>$imaging_center_offline->radiologist,
                          'request_ward'=>$imaging_center_offline->request_ward,
                          'request_doctor'=>$imaging_center_offline->request_doctor,
                          'imaging_order'=>$imaging_center_offline->imaging_order,
                          'imaging_remarks'=>$imaging_center_offline->imaging_remarks,
                          'imaging_center'=>$imaging_center_offline->imaging_center,
                          'imaging_result'=>$imaging_center_offline->imaging_result,
                          'imaging_results_remarks'=>$imaging_center_offline->imaging_results_remarks,
                          'imaging_result_attachment'=>$imaging_center_offline->imaging_result_attachment,
                          'is_viewed'=>$imaging_center_offline->is_viewed, 
                          'is_processed'=>$imaging_center_offline->is_processed,
                          'processed_by'=>$imaging_center_offline->processed_by,
                          'start_time'=>$imaging_center_offline->start_time,
                          'end_time'=>$imaging_center_offline->end_time,
                          'is_pending'=>$imaging_center_offline->is_pending,
                          'pending_reason'=>$imaging_center_offline->pending_reason,
                          'pending_date'=>$imaging_center_offline->pending_date,
                          'pending_by'=>$imaging_center_offline->pending_by,
                          'manage_by'=>$imaging_center_offline->manage_by,
                          'created_at'=>$imaging_center_offline->created_at,
                          'updated_at'=>$imaging_center_offline->updated_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $imaging_center_online_List = DB::connection('mysql2')->table('imaging_center')->get();  
        foreach($imaging_center_online_List as $imaging_center_online){  
            $imaging_center_online_count = DB::table('imaging_center')->where('imaging_center_id', $imaging_center_online->imaging_center_id)->get();
                if(count($imaging_center_online_count) > 0){
                    DB::table('imaging_center')->where('imaging_center_id', $imaging_center_online->imaging_center_id)->update([      
                        'patients_id'=>$imaging_center_online->patients_id,
                        'doctors_id'=>$imaging_center_online->doctors_id,
                        'ward_nurse_id'=>$imaging_center_online->ward_nurse_id,
                        'case_file'=>$imaging_center_online->case_file,
                        'radiologist'=>$imaging_center_online->radiologist,
                        'request_doctor'=>$imaging_center_online->request_doctor,
                        'radiologist'=>$imaging_center_online->radiologist,
                        'imaging_order'=>$imaging_center_online->imaging_order,
                        'imaging_remarks'=>$imaging_center_online->imaging_remarks,
                        'imaging_center'=>$imaging_center_online->imaging_center,
                        'imaging_result'=>$imaging_center_online->imaging_result,
                        'imaging_results_remarks'=>$imaging_center_online->imaging_results_remarks,
                        'imaging_result_attachment'=>$imaging_center_online->imaging_result_attachment,
                        'is_viewed'=>$imaging_center_online->is_viewed,
                        'is_processed'=>$imaging_center_online->is_processed,
                        'processed_by'=>$imaging_center_online->processed_by,
                        'start_time'=>$imaging_center_online->start_time,
                        'end_time'=>$imaging_center_online->end_time,
                        'is_pending'=>$imaging_center_online->is_pending,
                        'pending_reason'=>$imaging_center_online->pending_reason,
                        'pending_date'=>$imaging_center_online->pending_date,
                        'pending_by'=>$imaging_center_online->pending_by,
                        'manage_by'=>$imaging_center_online->manage_by,
                        'created_at'=>$imaging_center_online->created_at,
                        'updated_at'=>$imaging_center_online->updated_at, 
                    ]); 
                }else{
                    DB::table('imaging_center')->insert([    
                          'imaging_center_id'=>$imaging_center_online->imaging_center_id,
                          'patients_id'=>$imaging_center_online->patients_id,
                          'doctors_id'=>$imaging_center_online->doctors_id,
                          'ward_nurse_id'=>$imaging_center_online->ward_nurse_id,
                          'case_file'=>$imaging_center_online->case_file,
                          'radiologist'=>$imaging_center_online->radiologist,
                          'request_doctor'=>$imaging_center_online->request_doctor,
                          'radiologist'=>$imaging_center_online->radiologist,
                          'imaging_order'=>$imaging_center_online->imaging_order,
                          'imaging_remarks'=>$imaging_center_online->imaging_remarks,
                          'imaging_center'=>$imaging_center_online->imaging_center,
                          'imaging_result'=>$imaging_center_online->imaging_result,
                          'imaging_results_remarks'=>$imaging_center_online->imaging_results_remarks,
                          'imaging_result_attachment'=>$imaging_center_online->imaging_result_attachment,
                          'is_viewed'=>$imaging_center_online->is_viewed,
                          'is_processed'=>$imaging_center_online->is_processed,
                          'processed_by'=>$imaging_center_online->processed_by,
                          'start_time'=>$imaging_center_online->start_time,
                          'end_time'=>$imaging_center_online->end_time,
                          'is_pending'=>$imaging_center_online->is_pending,
                          'pending_reason'=>$imaging_center_online->pending_reason,
                          'pending_date'=>$imaging_center_online->pending_date,
                          'pending_by'=>$imaging_center_online->pending_by,
                          'manage_by'=>$imaging_center_online->manage_by,
                          'created_at'=>$imaging_center_online->created_at,
                          'updated_at'=>$imaging_center_online->updated_at, 
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkkk
    public function syncronizelaboratory(){ 
        // syncronize appointment_settings table from offline to online  
        $laboratory_offline_List = DB::table('laboratory')->get();  
        foreach($laboratory_offline_List as $laboratory_offline){  
            $laboratory_offline_count = DB::connection('mysql2')->table('laboratory')->where('lab_id', $laboratory_offline->lab_id)->get();
                if(count($laboratory_offline_count) > 0){  
                    if($laboratory_offline->updated_at > $laboratory_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('laboratory')->where('lab_id', $laboratory_offline->lab_id)->update([      
                            'laboratory_id'=>$laboratory_offline->laboratory_id,
                            'patients_id'=>$laboratory_offline->patients_id,
                            'doctors_id'=>$laboratory_offline->doctors_id,
                            'ward_nurse_id'=>$laboratory_offline->ward_nurse_id,
                            'case_file'=>$laboratory_offline->case_file,
                            'doctors_remarks'=>$laboratory_offline->doctors_remarks,
                            'laboratory_orders'=>$laboratory_offline->laboratory_orders,
                            'laboratory_results'=>$laboratory_offline->laboratory_results,
                            'laboratory_result_image'=>$laboratory_offline->laboratory_result_image,
                            'laboratory_remarks'=>$laboratory_offline->laboratory_remarks,
                            'laboratory_attachment'=>$laboratory_offline->laboratory_attachment,
                            'is_viewed'=>$laboratory_offline->is_viewed,
                            'is_processed'=>$laboratory_offline->is_processed,
                            'processed_by'=>$laboratory_offline->processed_by,
                            'start_time'=>$laboratory_offline->start_time,
                            'time_end'=>$laboratory_offline->time_end,
                            'is_pending'=>$laboratory_offline->is_pending,
                            'pending_reason'=>$laboratory_offline->pending_reason,
                            'pending_date'=>$laboratory_offline->pending_date,
                            'pending_by'=>$laboratory_offline->pending_by,
                            'created_at'=>$laboratory_offline->created_at,
                            'updated_at'=>$laboratory_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('laboratory')->where('lab_id', $laboratory_offline_count[0]->lab_id)->update([     
                            'laboratory_id'=>$laboratory_offline_count[0]->laboratory_id,
                            'patients_id'=>$laboratory_offline_count[0]->patients_id,
                            'doctors_id'=>$laboratory_offline_count[0]->doctors_id,
                            'ward_nurse_id'=>$laboratory_offline_count[0]->ward_nurse_id,
                            'case_file'=>$laboratory_offline_count[0]->case_file,
                            'doctors_remarks'=>$laboratory_offline_count[0]->doctors_remarks,
                            'laboratory_orders'=>$laboratory_offline_count[0]->laboratory_orders,
                            'laboratory_results'=>$laboratory_offline_count[0]->laboratory_results,
                            'laboratory_result_image'=>$laboratory_offline_count[0]->laboratory_result_image,
                            'laboratory_remarks'=>$laboratory_offline_count[0]->laboratory_remarks,
                            'laboratory_attachment'=>$laboratory_offline_count[0]->laboratory_attachment,
                            'is_viewed'=>$laboratory_offline_count[0]->is_viewed,
                            'is_processed'=>$laboratory_offline_count[0]->is_processed,
                            'processed_by'=>$laboratory_offline_count[0]->processed_by,
                            'start_time'=>$laboratory_offline_count[0]->start_time,
                            'time_end'=>$laboratory_offline_count[0]->time_end,
                            'is_pending'=>$laboratory_offline_count[0]->is_pending,
                            'pending_reason'=>$laboratory_offline_count[0]->pending_reason,
                            'pending_date'=>$laboratory_offline_count[0]->pending_date,
                            'pending_by'=>$laboratory_offline_count[0]->pending_by,
                            'created_at'=>$laboratory_offline_count[0]->created_at,
                            'updated_at'=>$laboratory_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('laboratory')->insert([    
                        'lab_id'=>$laboratory_offline->lab_id,
                        'laboratory_id'=>$laboratory_offline->laboratory_id,
                        'patients_id'=>$laboratory_offline->patients_id,
                        'doctors_id'=>$laboratory_offline->doctors_id,
                        'ward_nurse_id'=>$laboratory_offline->ward_nurse_id,
                        'case_file'=>$laboratory_offline->case_file,
                        'doctors_remarks'=>$laboratory_offline->doctors_remarks,
                        'laboratory_orders'=>$laboratory_offline->laboratory_orders,
                        'laboratory_results'=>$laboratory_offline->laboratory_results,
                        'laboratory_result_image'=>$laboratory_offline->laboratory_result_image,
                        'laboratory_remarks'=>$laboratory_offline->laboratory_remarks,
                        'laboratory_attachment'=>$laboratory_offline->laboratory_attachment,
                        'is_viewed'=>$laboratory_offline->is_viewed,
                        'is_processed'=>$laboratory_offline->is_processed,
                        'processed_by'=>$laboratory_offline->processed_by,
                        'start_time'=>$laboratory_offline->start_time,
                        'time_end'=>$laboratory_offline->time_end,
                        'is_pending'=>$laboratory_offline->is_pending,
                        'pending_reason'=>$laboratory_offline->pending_reason,
                        'pending_date'=>$laboratory_offline->pending_date,
                        'pending_by'=>$laboratory_offline->pending_by,
                        'created_at'=>$laboratory_offline->created_at,
                        'updated_at'=>$laboratory_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $laboratory_online_List = DB::connection('mysql2')->table('laboratory')->get();  
        foreach($laboratory_online_List as $laboratory_online){  
            $laboratory_online_count = DB::table('laboratory')->where('lab_id', $laboratory_online->lab_id)->get();
                if(count($laboratory_online_count) > 0){
                    DB::table('laboratory')->where('lab_id', $laboratory_online->lab_id)->update([      
                        'laboratory_id'=>$laboratory_online->laboratory_id,
                        'patients_id'=>$laboratory_online->patients_id,
                        'doctors_id'=>$laboratory_online->doctors_id,
                        'ward_nurse_id'=>$laboratory_online->ward_nurse_id,
                        'case_file'=>$laboratory_online->case_file,
                        'doctors_remarks'=>$laboratory_online->doctors_remarks,
                        'laboratory_orders'=>$laboratory_online->laboratory_orders,
                        'laboratory_results'=>$laboratory_online->laboratory_results,
                        'laboratory_result_image'=>$laboratory_online->laboratory_result_image,
                        'laboratory_remarks'=>$laboratory_online->laboratory_remarks,
                        'laboratory_attachment'=>$laboratory_online->laboratory_attachment,
                        'is_viewed'=>$laboratory_online->is_viewed,
                        'is_processed'=>$laboratory_online->is_processed,
                        'processed_by'=>$laboratory_online->processed_by,
                        'start_time'=>$laboratory_online->start_time,
                        'time_end'=>$laboratory_online->time_end,
                        'is_pending'=>$laboratory_online->is_pending,
                        'pending_reason'=>$laboratory_online->pending_reason,
                        'pending_date'=>$laboratory_online->pending_date,
                        'pending_by'=>$laboratory_online->pending_by,
                        'created_at'=>$laboratory_online->created_at,
                        'updated_at'=>$laboratory_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory')->insert([    
                        'lab_id'=>$laboratory_online->lab_id,
                        'laboratory_id'=>$laboratory_online->laboratory_id,
                        'patients_id'=>$laboratory_online->patients_id,
                        'doctors_id'=>$laboratory_online->doctors_id,
                        'ward_nurse_id'=>$laboratory_online->ward_nurse_id,
                        'case_file'=>$laboratory_online->case_file,
                        'doctors_remarks'=>$laboratory_online->doctors_remarks,
                        'laboratory_orders'=>$laboratory_online->laboratory_orders,
                        'laboratory_results'=>$laboratory_online->laboratory_results,
                        'laboratory_result_image'=>$laboratory_online->laboratory_result_image,
                        'laboratory_remarks'=>$laboratory_online->laboratory_remarks,
                        'laboratory_attachment'=>$laboratory_online->laboratory_attachment,
                        'is_viewed'=>$laboratory_online->is_viewed,
                        'is_processed'=>$laboratory_online->is_processed,
                        'processed_by'=>$laboratory_online->processed_by,
                        'start_time'=>$laboratory_online->start_time,
                        'time_end'=>$laboratory_online->time_end,
                        'is_pending'=>$laboratory_online->is_pending,
                        'pending_reason'=>$laboratory_online->pending_reason,
                        'pending_date'=>$laboratory_online->pending_date,
                        'pending_by'=>$laboratory_online->pending_by,
                        'created_at'=>$laboratory_online->created_at,
                        'updated_at'=>$laboratory_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkkk
    public function syncronizelaboratory_list(){
        // syncronize appointment_settings table from offline to online  
        $lab_list_offline_list = DB::table('laboratory_list')->get();  
        foreach($lab_list_offline_list as $lab_list_offline){  
            $lab_list_offline_count = DB::connection('mysql2')->table('laboratory_list')->where('l_id', $lab_list_offline->l_id)->get();
                if(count($lab_list_offline_count) > 0){  
                    if($lab_list_offline->updated_at > $lab_list_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('laboratory_list')->where('l_id', $lab_list_offline->l_id)->update([     
                            'laboratory_id'=>$lab_list_offline->laboratory_id,
                            'management_id'=>$lab_list_offline->management_id,
                            'user_id'=>$lab_list_offline->user_id,
                            'name'=>$lab_list_offline->name,
                            'gender'=>$lab_list_offline->gender,
                            'birthday'=>$lab_list_offline->birthday,
                            'role'=>$lab_list_offline->role,
                            'added_by'=>$lab_list_offline->added_by,
                            'address'=>$lab_list_offline->address,
                            'created_at'=>$lab_list_offline->created_at,
                            'updated_at'=>$lab_list_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('laboratory_list')->where('l_id', $lab_list_offline_count[0]->l_id)->update([  
                            'laboratory_id'=>$lab_list_offline_count[0]->laboratory_id,
                            'management_id'=>$lab_list_offline_count[0]->management_id,
                            'user_id'=>$lab_list_offline_count[0]->user_id,
                            'name'=>$lab_list_offline_count[0]->name,
                            'gender'=>$lab_list_offline_count[0]->gender,
                            'birthday'=>$lab_list_offline_count[0]->birthday,
                            'role'=>$lab_list_offline_count[0]->role,
                            'added_by'=>$lab_list_offline_count[0]->added_by,
                            'address'=>$lab_list_offline_count[0]->address,
                            'created_at'=>$lab_list_offline_count[0]->created_at,
                            'updated_at'=>$lab_list_offline_count[0]->updated_at,    
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('laboratory_list')->insert([  
                        'l_id'=>$lab_list_offline->l_id,
                        'laboratory_id'=>$lab_list_offline->laboratory_id,
                        'management_id'=>$lab_list_offline->management_id,
                        'user_id'=>$lab_list_offline->user_id,
                        'name'=>$lab_list_offline->name,
                        'gender'=>$lab_list_offline->gender,
                        'birthday'=>$lab_list_offline->birthday,
                        'role'=>$lab_list_offline->role,
                        'added_by'=>$lab_list_offline->added_by,
                        'address'=>$lab_list_offline->address,
                        'created_at'=>$lab_list_offline->created_at,
                        'updated_at'=>$lab_list_offline->updated_at,   
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $lab_list_online = DB::connection('mysql2')->table('laboratory_list')->get();  
        foreach($lab_list_online as $lab_list){  
            $lab_list_count = DB::table('laboratory_list')->where('l_id', $lab_list->l_id)->get();
                if(count($lab_list_count) > 0){
                    DB::table('laboratory_list')->where('l_id', $lab_list->l_id)->update([      
                        'laboratory_id'=>$lab_list->laboratory_id,
                        'management_id'=>$lab_list->management_id,
                        'user_id'=>$lab_list->user_id,
                        'name'=>$lab_list->name,
                        'gender'=>$lab_list->gender,
                        'birthday'=>$lab_list->birthday,
                        'role'=>$lab_list->role,
                        'added_by'=>$lab_list->added_by,
                        'address'=>$lab_list->address,
                        'created_at'=>$lab_list->created_at,
                        'updated_at'=>$lab_list->updated_at,
                    ]); 
                }else{
                    DB::table('laboratory_list')->insert([    
                         'l_id'=>$lab_list->l_id,
                         'management_id'=>$lab_list->management_id,
                         'management_id'=>$lab_list->management_id,
                         'user_id'=>$lab_list->user_id,
                         'name'=>$lab_list->name,
                         'gender'=>$lab_list->gender,
                        'birthday'=>$lab_list->birthday,
                        'role'=>$lab_list->role,
                        'added_by'=>$lab_list->added_by,
                         'address'=>$lab_list->address,
                         'created_at'=>$lab_list->created_at,
                         'updated_at'=>$lab_list->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkkk
    public function syncronizelogs(){
        // syncronize appointment_settings table from offline to online  
        $logs_offline_list = DB::table('logs')->get();  
        foreach($logs_offline_list as $logs_offline){  
            $logs_offline_count = DB::connection('mysql2')->table('logs')->where('log_id', $logs_offline->log_id)->get();
                if(count($logs_offline_count) > 0){  
                    if($logs_offline->updated_at > $logs_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('logs')->where('log_id', $logs_offline->log_id)->update([     
                            'activity'=>$logs_offline->activity,
                            'user_id'=>$logs_offline->user_id,
                            'access_page'=>$logs_offline->access_page,
                            'status'=>$logs_offline->status,
                            'created_at'=>$logs_offline->created_at,
                            'updated_at'=>$logs_offline->updated_at,  
                        ]);  
                    } 
                    
                    else{
                        DB::table('logs')->where('log_id', $logs_offline_count[0]->log_id)->update([  
                            'activity'=>$logs_offline_count[0]->activity,
                            'user_id'=>$logs_offline_count[0]->user_id,
                            'access_page'=>$logs_offline_count[0]->access_page,
                            'status'=>$logs_offline_count[0]->status,
                            'created_at'=>$logs_offline_count[0]->created_at,
                            'updated_at'=>$logs_offline_count[0]->updated_at,     
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('logs')->insert([  
                        'log_id'=>$logs_offline->log_id,
                         'activity'=>$logs_offline->activity,
                         'user_id'=>$logs_offline->user_id,
                         'access_page'=>$logs_offline->access_page,
                         'status'=>$logs_offline->status,
                         'created_at'=>$logs_offline->created_at,
                         'updated_at'=>$logs_offline->updated_at,  
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $logs_online_list = DB::connection('mysql2')->table('logs')->get();  
        foreach($logs_online_list as $logs_online){  
            $logs_online_count = DB::table('logs')->where('log_id', $logs_online->log_id)->get();
                if(count($logs_online_count) > 0){
                    DB::table('logs')->where('log_id', $logs_online->log_id)->update([      
                        'activity'=>$logs_online->activity,
                         'user_id'=>$logs_online->user_id,
                         'access_page'=>$logs_online->access_page,
                         'status'=>$logs_online->status,
                         'created_at'=>$logs_online->created_at,
                         'updated_at'=>$logs_online->updated_at,
                    ]); 
                }else{
                    DB::table('logs')->insert([    
                         'log_id'=>$logs_online->log_id,
                         'activity'=>$logs_online->activity,
                         'user_id'=>$logs_online->user_id,
                         'access_page'=>$logs_online->access_page,
                         'status'=>$logs_online->status,
                         'created_at'=>$logs_online->created_at,
                         'updated_at'=>$logs_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkkk
    public function syncronizemanagement(){
        // syncronize appointment_settings table from offline to online  
        $manage_offline_list = DB::table('management')->get();  
        foreach($manage_offline_list as $manage_offline){  
            $manage_offline_count = DB::connection('mysql2')->table('management')->where('m_id', $manage_offline->m_id)->get();
                if(count($manage_offline_count) > 0){  
                    if($manage_offline->updated_at > $manage_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('management')->where('m_id', $manage_offline->m_id)->update([     
                            'management_id'=>$manage_offline->management_id,
                            'user_id'=>$manage_offline->user_id,
                            'name'=>$manage_offline->name,
                            'tin'=>$manage_offline->tin,
                            'business_style'=>$manage_offline->business_style,
                            'tax_type'=>$manage_offline->tax_type,
                            'logo'=>$manage_offline->logo, 
                            'header'=>$manage_offline->header, 
                            'address'=>$manage_offline->address,
                            'created_at'=>$manage_offline->created_at,
                            'updated_at'=>$manage_offline->updated_at,    
                        ]);  
                    } 
                    
                    else{
                        DB::table('management')->where('m_id', $manage_offline_count[0]->m_id)->update([  
                            'management_id'=>$manage_offline->management_id,
                            'user_id'=>$manage_offline->user_id,
                            'name'=>$manage_offline->name,
                            'tin'=>$manage_offline->tin,
                            'business_style'=>$manage_offline->business_style,
                            'tax_type'=>$manage_offline->tax_type,
                            'logo'=>$manage_offline->logo, 
                            'header'=>$manage_offline->header, 
                            'address'=>$manage_offline->address,
                            'created_at'=>$manage_offline->created_at,
                            'updated_at'=>$manage_offline->updated_at,  
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('management')->insert([  
                        'm_id'=>$manage_offline->m_id,
                        'management_id'=>$manage_offline->management_id,
                        'user_id'=>$manage_offline->user_id,
                        'name'=>$manage_offline->name,
                        'tin'=>$manage_offline->tin,
                        'business_style'=>$manage_offline->business_style,
                        'tax_type'=>$manage_offline->tax_type,
                        'logo'=>$manage_offline->logo, 
                        'header'=>$manage_offline->header, 
                        'address'=>$manage_offline->address,
                        'created_at'=>$manage_offline->created_at,
                        'updated_at'=>$manage_offline->updated_at,  
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $management_list = DB::connection('mysql2')->table('management')->get();  
        foreach($management_list as $manage_online){  
            $manage_online_count = DB::table('management')->where('m_id', $manage_online->m_id)->get();
                if(count($manage_online_count) > 0){
                    DB::table('management')->where('m_id', $manage_online->m_id)->update([       
                        'management_id'=>$manage_online->management_id,
                        'user_id'=>$manage_online->user_id,
                        'name'=>$manage_online->name,
                        'tin'=>$manage_online->tin,
                        'business_style'=>$manage_online->business_style,
                        'tax_type'=>$manage_online->tax_type,
                        'logo'=>$manage_online->logo,
                        'header'=>$manage_online->header,
                        'address'=>$manage_online->address,
                        'created_at'=>$manage_online->created_at,
                        'updated_at'=>$manage_online->updated_at,
                    ]); 
                }else{
                    DB::table('management')->insert([     
                        'm_id'=>$manage_online->m_id,
                        'user_id'=>$manage_online->management_id,
                        'user_id'=>$manage_online->user_id,
                        'name'=>$manage_online->name,
                        'tin'=>$manage_online->tin,
                        'business_style'=>$manage_online->business_style,
                        'tax_type'=>$manage_online->tax_type,
                        'logo'=>$manage_online->logo,
                        'header'=>$manage_online->header,
                        'address'=>$manage_online->address,
                        'created_at'=>$manage_online->created_at,
                        'updated_at'=>$manage_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizemessages(){
        // syncronize appointment_settings table from offline to online  
        $msg_offline_list = DB::table('messages')->get();  
        foreach($msg_offline_list as $msg_offline){  
            $msg_offline_count = DB::connection('mysql2')->table('messages')->where('message_id', $msg_offline->message_id)->get();
                if(count($msg_offline_count) > 0){  
                    if($msg_offline->updated_at > $msg_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('messages')->where('message_id', $msg_offline->message_id)->update([     
                            'name'=>$msg_offline->name,
                            'email'=>$msg_offline->email,
                            'messages'=>$msg_offline->messages,
                            'regarding'=>$msg_offline->regarding,
                            'status'=>$msg_offline->status,
                            'created_at'=>$msg_offline->created_at,
                            'updated_at'=>$msg_offline->updated_at,    
                        ]);  
                    } 
                    
                    else{
                        DB::table('messages')->where('message_id', $msg_offline_count[0]->message_id)->update([  
                            'name'=>$msg_offline_count[0]->name,
                            'email'=>$msg_offline_count[0]->email,
                            'messages'=>$msg_offline_count[0]->messages,
                            'regarding'=>$msg_offline_count[0]->regarding,
                            'status'=>$msg_offline_count[0]->status,
                            'created_at'=>$msg_offline_count[0]->created_at,
                            'updated_at'=>$msg_offline_count[0]->updated_at,  
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('messages')->insert([  
                        'message_id'=>$msg_offline->message_id,
                        'name'=>$msg_offline->name,
                        'email'=>$msg_offline->email,
                        'messages'=>$msg_offline->messages,
                        'regarding'=>$msg_offline->regarding,
                        'status'=>$msg_offline->status,
                        'created_at'=>$msg_offline->created_at,
                        'updated_at'=>$msg_offline->updated_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $msg_list = DB::connection('mysql2')->table('messages')->get();  
        foreach($msg_list as $msg_online){  
            $msg_online_count = DB::table('messages')->where('message_id', $msg_online->message_id)->get();
                if(count($msg_online_count) > 0){
                    DB::table('messages')->where('message_id', $msg_online->message_id)->update([    
                        'name'=>$msg_online->name,
                        'email'=>$msg_online->email,
                        'messages'=>$msg_online->messages,
                        'regarding'=>$msg_online->regarding,
                        'status'=>$msg_online->status,
                        'created_at'=>$msg_online->created_at,
                        'updated_at'=>$msg_online->updated_at,
                    ]); 
                }else{
                    DB::table('messages')->insert([     
                         'message_id'=>$msg_online->message_id,
                         'name'=>$msg_online->name,
                         'email'=>$msg_online->email,
                         'messages'=>$msg_online->messages,
                         'regarding'=>$msg_online->regarding,
                         'status'=>$msg_online->status,
                         'created_at'=>$msg_online->created_at,
                         'updated_at'=>$msg_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizepatients(){
        // syncronize appointment_settings table from offline to online  
        $patient_offline_list = DB::table('patients')->get();  
        foreach($patient_offline_list as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients')->where('patient_id', $patient_offline->patient_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients')->where('patient_id', $patient_offline->patient_id)->update([     
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
                            'street'=>$patient_offline->street, 
                            'city'=>$patient_offline->city, 
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
                            'cholesterol'=>$patient_offline->cholesterol,  
                            'allergies'=>$patient_offline->allergies, 
                            'medication'=>$patient_offline->medication, 
                            'remarks'=>$patient_offline->remarks, 
                            'image'=>$patient_offline->image, 
                            'status'=>$patient_offline->status, 
                            'doctors_response'=>$patient_offline->doctors_response, 
                            'is_edited_bydoc'=>$patient_offline->is_edited_bydoc, 
                            'created_at'=>$patient_offline->created_at, 
                            'updated_at'=>$patient_offline->updated_at,  
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients')->where('patient_id', $patient_offline_count[0]->patient_id)->update([  
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
                            'street'=>$patient_offline_count[0]->street, 
                            'city'=>$patient_offline_count[0]->city, 
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
                            'cholesterol'=>$patient_offline_count[0]->cholesterol, 
                            'allergies'=>$patient_offline_count[0]->allergies, 
                            'medication'=>$patient_offline_count[0]->medication, 
                            'remarks'=>$patient_offline_count[0]->remarks, 
                            'image'=>$patient_offline_count[0]->image, 
                            'status'=>$patient_offline_count[0]->status, 
                            'doctors_response'=>$patient_offline_count[0]->doctors_response, 
                            'is_edited_bydoc'=>$patient_offline_count[0]->is_edited_bydoc, 
                            'created_at'=>$patient_offline_count[0]->created_at, 
                            'updated_at'=>$patient_offline_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients')->insert([  
                        'patient_id'=>$patient_offline->patient_id, 
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
                        'street'=>$patient_offline->street, 
                        'city'=>$patient_offline->city, 
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
                        'cholesterol'=>$patient_offline->cholesterol, 
                        'allergies'=>$patient_offline->allergies, 
                        'medication'=>$patient_offline->medication, 
                        'remarks'=>$patient_offline->remarks, 
                        'image'=>$patient_offline->image, 
                        'status'=>$patient_offline->status, 
                        'doctors_response'=>$patient_offline->doctors_response, 
                        'is_edited_bydoc'=>$patient_offline->is_edited_bydoc, 
                        'created_at'=>$patient_offline->created_at, 
                        'updated_at'=>$patient_offline->updated_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $patient_list = DB::connection('mysql2')->table('patients')->get();  
        foreach($patient_list as $patient_online){  
            $patient_online_count = DB::table('patients')->where('patient_id', $patient_online->patient_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients')->where('patient_id', $patient_online->patient_id)->update([  
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
                        'street'=>$patient_online->street, 
                        'city'=>$patient_online->city, 
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
                        'cholesterol'=>$patient_online->cholesterol, 
                        'allergies'=>$patient_online->allergies, 
                        'medication'=>$patient_online->medication, 
                        'remarks'=>$patient_online->remarks, 
                        'image'=>$patient_online->image, 
                        'status'=>$patient_online->status, 
                        'doctors_response'=>$patient_online->doctors_response, 
                        'is_edited_bydoc'=>$patient_online->is_edited_bydoc, 
                        'created_at'=>$patient_online->created_at, 
                        'updated_at'=>$patient_online->updated_at,   
                    ]); 
                }else{
                    DB::table('patients')->insert([     
                        'patient_id'=>$patient_online->patient_id, 
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
                        'street'=>$patient_online->street, 
                        'city'=>$patient_online->city, 
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
                        'cholesterol'=>$patient_online->cholesterol, 
                        'allergies'=>$patient_online->allergies, 
                        'medication'=>$patient_online->medication, 
                        'remarks'=>$patient_online->remarks, 
                        'image'=>$patient_online->image, 
                        'status'=>$patient_online->status, 
                        'doctors_response'=>$patient_online->doctors_response, 
                        'is_edited_bydoc'=>$patient_online->is_edited_bydoc, 
                        'created_at'=>$patient_online->created_at, 
                        'updated_at'=>$patient_online->updated_at,  
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronize_patients_family_history(){ //patient family history
        // syncronize appointment_settings table from offline to online  
        $pfh_offline_list = DB::table('patients_family_history')->get();  
        foreach($pfh_offline_list as $pfh_offline){  
            $pfh_offline_count = DB::connection('mysql2')->table('patients_family_history')->where('dph_id', $pfh_offline->dph_id)->get();
                if(count($pfh_offline_count) > 0){  
                    if($pfh_offline->updated_at > $pfh_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_family_history')->where('dph_id', $pfh_offline->dph_id)->update([      
                            'doctors_id'=>$pfh_offline->doctors_id,
                            'patient_id'=>$pfh_offline->patient_id,
                            'family_history'=>$pfh_offline->family_history,
                            'status'=>$pfh_offline->status,
                            'updated_at'=>$pfh_offline->updated_at,  
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_family_history')->where('dph_id', $pfh_offline_count[0]->dph_id)->update([   
                            'doctors_id'=>$pfh_offline_count[0]->doctors_id,
                            'patient_id'=>$pfh_offline_count[0]->patient_id,
                            'family_history'=>$pfh_offline_count[0]->family_history,
                            'status'=>$pfh_offline_count[0]->status,
                            'updated_at'=>$pfh_offline_count[0]->updated_at,  
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_family_history')->insert([  
                        'dph_id'=>$pfh_offline->dph_id,
                        'doctors_id'=>$pfh_offline->doctors_id,
                        'patient_id'=>$pfh_offline->patient_id,
                        'family_history'=>$pfh_offline->family_history,
                        'status'=>$pfh_offline->status,
                        'updated_at'=>$pfh_offline->updated_at,  
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pfh_list = DB::connection('mysql2')->table('patients_family_history')->get();  
        foreach($pfh_list as $pfh_online){  
            $pfh_online_count = DB::table('patients_family_history')->where('dph_id', $pfh_online->dph_id)->get();
                if(count($pfh_online_count) > 0){
                    DB::table('patients_family_history')->where('dph_id', $pfh_online->dph_id)->update([   
                        'doctors_id'=>$pfh_online->doctors_id,
                        'patient_id'=>$pfh_online->patient_id,
                        'family_history'=>$pfh_online->family_history,
                        'status'=>$pfh_online->status,
                        'updated_at'=>$pfh_online->updated_at,  
                    ]); 
                }else{
                    DB::table('patients_family_history')->insert([     
                        'dph_id'=>$pfh_online->dph_id, 
                        'doctors_id'=>$pfh_online->doctors_id,
                        'patient_id'=>$pfh_online->patient_id,
                        'family_history'=>$pfh_online->family_history,
                        'status'=>$pfh_online->status,
                        'updated_at'=>$pfh_online->updated_at,  
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizepgh(){ //patient glucose history
        // syncronize appointment_settings table from offline to online  
        $pgh_offline_list = DB::table('patients_glucose_history')->get();  
        foreach($pgh_offline_list as $pgh_offline){  
            $pgh_offline_count = DB::connection('mysql2')->table('patients_glucose_history')->where('pgh_id', $pgh_offline->pgh_id)->get();
                if(count($pgh_offline_count) > 0){  
                    if($pgh_offline->updated_at > $pgh_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_glucose_history')->where('pgh_id', $pgh_offline->pgh_id)->update([      
                            'patients_id'=>$pgh_offline->patients_id,
                            'glucose'=>$pgh_offline->glucose,
                            'status'=>$pgh_offline->status,
                            'created_at'=>$pgh_offline->created_at,
                            'updated_at'=>$pgh_offline->updated_at,  
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_glucose_history')->where('pgh_id', $pgh_offline_count[0]->pgh_id)->update([   
                            'patients_id'=>$pgh_offline_count[0]->patients_id,
                            'glucose'=>$pgh_offline_count[0]->glucose,
                            'status'=>$pgh_offline_count[0]->status,
                            'created_at'=>$pgh_offline_count[0]->created_at,
                            'updated_at'=>$pgh_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_glucose_history')->insert([  
                        'pgh_id'=>$pgh_offline->pgh_id,
                        'patients_id'=>$pgh_offline->patients_id,
                        'glucose'=>$pgh_offline->glucose,
                        'status'=>$pgh_offline->status,
                        'created_at'=>$pgh_offline->created_at,
                        'updated_at'=>$pgh_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pgh_list = DB::connection('mysql2')->table('patients_glucose_history')->get();  
        foreach($pgh_list as $pgh_online){  
            $pgh_online_count = DB::table('patients_glucose_history')->where('pgh_id', $pgh_online->pgh_id)->get();
                if(count($pgh_online_count) > 0){
                    DB::table('patients_glucose_history')->where('pgh_id', $pgh_online->pgh_id)->update([  
                        'patients_id'=>$pgh_online->patients_id,
                        'glucose'=>$pgh_online->glucose,
                        'status'=>$pgh_online->status,
                        'created_at'=>$pgh_online->created_at,
                        'updated_at'=>$pgh_online->updated_at, 
                    ]); 
                }else{
                    DB::table('patients_glucose_history')->insert([     
                         'pgh_id'=>$pgh_online->pgh_id,
                         'patients_id'=>$pgh_online->patients_id,
                         'glucose'=>$pgh_online->glucose,
                         'status'=>$pgh_online->status,
                         'created_at'=>$pgh_online->created_at,
                         'updated_at'=>$pgh_online->updated_at, 
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizeph(){ //patient history
        // syncronize appointment_settings table from offline to online  
        $ph_offline_list = DB::table('patients_history')->get();  
        foreach($ph_offline_list as $ph_offline){  
            $ph_offline_count = DB::connection('mysql2')->table('patients_history')->where('ph_id', $ph_offline->ph_id)->get();
                if(count($ph_offline_count) > 0){  
                    if($ph_offline->updated_at > $ph_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history')->where('ph_id', $ph_offline->ph_id)->update([     
                            'patient_id'=>$ph_offline->patient_id,
                            'street'=>$ph_offline->street,
                            'barangay'=>$ph_offline->barangay,
                            'city'=>$ph_offline->city,
                            'zip'=>$ph_offline->zip,
                            'height'=>$ph_offline->height,
                            'weight'=>$ph_offline->weight,
                            'occupation'=>$ph_offline->occupation,
                            'allergies'=>$ph_offline->allergies,
                            'medication'=>$ph_offline->medication,
                            'remarks'=>$ph_offline->remarks,
                            'updated_at'=>$ph_offline->updated_at,
                            'created_at'=>$ph_offline->created_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history')->where('ph_id', $ph_offline_count[0]->ph_id)->update([  
                            'patient_id'=>$ph_offline_count[0]->patient_id,
                            'street'=>$ph_offline_count[0]->street,
                            'barangay'=>$ph_offline_count[0]->barangay,
                            'city'=>$ph_offline_count[0]->city,
                            'zip'=>$ph_offline_count[0]->zip,
                            'height'=>$ph_offline_count[0]->height,
                            'weight'=>$ph_offline_count[0]->weight,
                            'occupation'=>$ph_offline_count[0]->occupation,
                            'allergies'=>$ph_offline_count[0]->allergies,
                            'medication'=>$ph_offline_count[0]->medication,
                            'remarks'=>$ph_offline_count[0]->remarks,
                            'updated_at'=>$ph_offline_count[0]->updated_at,
                            'created_at'=>$ph_offline_count[0]->created_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history')->insert([  
                        'ph_id'=>$ph_offline->ph_id,
                        'patient_id'=>$ph_offline->patient_id,
                        'street'=>$ph_offline->street,
                        'barangay'=>$ph_offline->barangay,
                        'city'=>$ph_offline->city,
                        'zip'=>$ph_offline->zip,
                        'height'=>$ph_offline->height,
                        'weight'=>$ph_offline->weight,
                        'occupation'=>$ph_offline->occupation,
                        'allergies'=>$ph_offline->allergies,
                        'medication'=>$ph_offline->medication,
                        'remarks'=>$ph_offline->remarks,
                        'updated_at'=>$ph_offline->updated_at,
                        'created_at'=>$ph_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $ph_list = DB::connection('mysql2')->table('patients_history')->get();  
        foreach($ph_list as $ph_online){  
            $ph_online_count = DB::table('patients_history')->where('ph_id', $ph_online->ph_id)->get();
                if(count($ph_online_count) > 0){
                    DB::table('patients_history')->where('ph_id', $ph_online->ph_id)->update([  
                        'patient_id'=>$ph_online->patient_id,
                        'street'=>$ph_online->street,
                        'barangay'=>$ph_online->barangay,
                        'city'=>$ph_online->city,
                        'zip'=>$ph_online->zip,
                        'height'=>$ph_online->height,
                        'weight'=>$ph_online->weight,
                        'occupation'=>$ph_online->occupation,
                        'allergies'=>$ph_online->allergies,
                        'medication'=>$ph_online->medication,
                        'remarks'=>$ph_online->remarks,
                        'updated_at'=>$ph_online->updated_at,
                        'created_at'=>$ph_online->created_at,
                    ]); 
                }else{
                    DB::table('patients_history')->insert([     
                        'ph_id'=>$ph_online->ph_id,
                        'patient_id'=>$ph_online->patient_id,
                        'street'=>$ph_online->street,
                        'city'=>$ph_online->city,
                        'zip'=>$ph_online->zip,
                        'height'=>$ph_online->height,
                        'weight'=>$ph_online->weight,
                        'allergies'=>$ph_online->allergies,
                        'medication'=>$ph_online->medication,
                        'remarks'=>$ph_online->remarks,
                        'updated_at'=>$ph_online->updated_at,
                        'created_at'=>$ph_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizeplh(){ //patient lab history
        // syncronize appointment_settings table from offline to online  
        $plh_offline_list = DB::table('patients_lab_history')->get();  
        foreach($plh_offline_list as $plh_offline){  
            $plh_offline_count = DB::connection('mysql2')->table('patients_lab_history')->where('plh_id', $plh_offline->plh_id)->get();
                if(count($plh_offline_count) > 0){  
                    if($plh_offline->updated_at > $plh_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_lab_history')->where('plh_id', $plh_offline->plh_id)->update([      
                            'patients_id'=>$plh_offline->patients_id,
                            'systolic'=>$plh_offline->systolic,
                            'diastolic'=>$plh_offline->diastolic, 
                            'updated_at'=>$plh_offline->updated_at,
                            'created_at'=>$plh_offline->created_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_lab_history')->where('plh_id', $plh_offline_count[0]->plh_id)->update([   
                            'patients_id'=>$plh_offline_count[0]->patients_id,
                            'systolic'=>$plh_offline_count[0]->systolic,
                            'diastolic'=>$plh_offline_count[0]->diastolic, 
                            'updated_at'=>$plh_offline_count[0]->updated_at,
                            'created_at'=>$plh_offline_count[0]->created_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_lab_history')->insert([  
                        'plh_id'=>$plh_offline->plh_id,
                        'patients_id'=>$plh_offline->patients_id,
                        'systolic'=>$plh_offline->systolic,
                        'diastolic'=>$plh_offline->diastolic, 
                        'updated_at'=>$plh_offline->updated_at,
                        'created_at'=>$plh_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $plh_list = DB::connection('mysql2')->table('patients_lab_history')->get();  
        foreach($plh_list as $plh_online){  
            $plh_online_count = DB::table('patients_lab_history')->where('plh_id', $plh_online->plh_id)->get();
                if(count($plh_online_count) > 0){
                    DB::table('patients_lab_history')->where('plh_id', $plh_online->plh_id)->update([  
                        'patients_id'=>$plh_online->patients_id,
                        'systolic'=>$plh_online->systolic,
                        'diastolic'=>$plh_online->diastolic, 
                        'updated_at'=>$plh_online->updated_at,
                        'created_at'=>$plh_online->created_at,
                    ]); 
                }else{
                    DB::table('patients_lab_history')->insert([     
                        'plh_id'=>$plh_online->plh_id,
                        'patients_id'=>$plh_online->patients_id,
                        'systolic'=>$plh_online->systolic,
                        'diastolic'=>$plh_online->diastolic, 
                        'updated_at'=>$plh_online->updated_at,
                        'created_at'=>$plh_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizepph(){ //patient pulse history
        // syncronize appointment_settings table from offline to online  
        $pph_offline_list = DB::table('patients_pulse_history')->get();  
        foreach($pph_offline_list as $pph_offline){  
            $pph_offline_count = DB::connection('mysql2')->table('patients_pulse_history')->where('pph_id', $pph_offline->pph_id)->get();
                if(count($pph_offline_count) > 0){  
                    if($pph_offline->updated_at > $pph_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_pulse_history')->where('pph_id', $pph_offline->pph_id)->update([      
                            'patients_id'=>$pph_offline->patients_id,
                            'pulse'=>$pph_offline->pulse,
                            'status'=>$pph_offline->status, 
                            'updated_at'=>$pph_offline->updated_at,
                            'created_at'=>$pph_offline->created_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_pulse_history')->where('pph_id', $pph_offline_count[0]->pph_id)->update([   
                            'patients_id'=>$pph_offline_count[0]->patients_id,
                            'pulse'=>$pph_offline_count[0]->pulse,
                            'status'=>$pph_offline_count[0]->status, 
                            'updated_at'=>$pph_offline_count[0]->updated_at,
                            'created_at'=>$pph_offline_count[0]->created_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_pulse_history')->insert([  
                        'pph_id'=>$pph_offline->pph_id,
                        'patients_id'=>$pph_offline->patients_id,
                        'pulse'=>$pph_offline->pulse,
                        'status'=>$pph_offline->status, 
                        'updated_at'=>$pph_offline->updated_at,
                        'created_at'=>$pph_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pph_list = DB::connection('mysql2')->table('patients_pulse_history')->get();  
        foreach($pph_list as $pph_online){  
            $pph_online_count = DB::table('patients_pulse_history')->where('pph_id', $pph_online->pph_id)->get();
                if(count($pph_online_count) > 0){
                    DB::table('patients_pulse_history')->where('pph_id', $pph_online->pph_id)->update([  
                        'patients_id'=>$pph_online->patients_id,
                        'pulse'=>$pph_online->pulse,
                        'status'=>$pph_online->status, 
                        'updated_at'=>$pph_online->updated_at,
                        'created_at'=>$pph_online->created_at,
                    ]); 
                }else{
                    DB::table('patients_pulse_history')->insert([     
                        'pph_id'=>$pph_online->pph_id,
                        'patients_id'=>$pph_online->patients_id,
                        'pulse'=>$pph_online->pulse,
                        'status'=>$pph_online->status, 
                        'updated_at'=>$pph_online->updated_at,
                        'created_at'=>$pph_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizeprh(){ //patient rispiratory history
        // syncronize appointment_settings table from offline to online  
        $prh_offline_list = DB::table('patients_respiratory_history')->get();  
        foreach($prh_offline_list as $prh_offline){  
            $prh_offline_count = DB::connection('mysql2')->table('patients_respiratory_history')->where('prh_id', $prh_offline->prh_id)->get();
                if(count($prh_offline_count) > 0){  
                    if($prh_offline->updated_at > $prh_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_respiratory_history')->where('prh_id', $prh_offline->prh_id)->update([      
                            'patients_id'=>$prh_offline->patients_id,
                            'respiratory'=>$prh_offline->respiratory,
                            'status'=>$prh_offline->status, 
                            'updated_at'=>$prh_offline->updated_at,
                            'created_at'=>$prh_offline->created_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_respiratory_history')->where('prh_id', $prh_offline_count[0]->prh_id)->update([   
                            'patients_id'=>$prh_offline_count[0]->patients_id,
                            'respiratory'=>$prh_offline_count[0]->respiratory,
                            'status'=>$prh_offline_count[0]->status, 
                            'updated_at'=>$prh_offline_count[0]->updated_at,
                            'created_at'=>$prh_offline_count[0]->created_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_respiratory_history')->insert([  
                        'prh_id'=>$prh_offline->prh_id,
                        'patients_id'=>$prh_offline->patients_id,
                        'respiratory'=>$prh_offline->respiratory,
                        'status'=>$prh_offline->status, 
                        'updated_at'=>$prh_offline->updated_at,
                        'created_at'=>$prh_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pph_list = DB::connection('mysql2')->table('patients_respiratory_history')->get();  
        foreach($pph_list as $pph_online){  
            $pph_online_count = DB::table('patients_respiratory_history')->where('prh_id', $pph_online->prh_id)->get();
                if(count($pph_online_count) > 0){
                    DB::table('patients_respiratory_history')->where('prh_id', $pph_online->prh_id)->update([  
                        'patients_id'=>$pph_online->patients_id,
                        'respiratory'=>$pph_online->respiratory,
                        'status'=>$pph_online->status, 
                        'updated_at'=>$pph_online->updated_at,
                        'created_at'=>$pph_online->created_at,
                    ]); 
                }else{
                    DB::table('patients_respiratory_history')->insert([     
                        'prh_id'=>$pph_online->prh_id,
                        'patients_id'=>$pph_online->patients_id,
                        'respiratory'=>$pph_online->respiratory,
                        'status'=>$pph_online->status, 
                        'updated_at'=>$pph_online->updated_at,
                        'created_at'=>$pph_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizepth(){ //patient temp history
        // syncronize appointment_settings table from offline to online  
        $pth_offline_list = DB::table('patients_temp_history')->get();  
        foreach($pth_offline_list as $pth_offline){  
            $pth_offline_count = DB::connection('mysql2')->table('patients_temp_history')->where('pth_id', $pth_offline->pth_id)->get();
                if(count($pth_offline_count) > 0){  
                    if($pth_offline->updated_at > $pth_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_temp_history')->where('pth_id', $pth_offline->pth_id)->update([      
                            'patients_id'=>$pth_offline->patients_id,
                            'temp'=>$pth_offline->temp,
                            'status'=>$pth_offline->status, 
                            'updated_at'=>$pth_offline->updated_at,
                            'created_at'=>$pth_offline->created_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_temp_history')->where('pth_id', $pth_offline_count[0]->pth_id)->update([   
                            'patients_id'=>$pth_offline_count[0]->patients_id,
                            'temp'=>$pth_offline_count[0]->temp,
                            'status'=>$pth_offline_count[0]->status, 
                            'updated_at'=>$pth_offline_count[0]->updated_at,
                            'created_at'=>$pth_offline_count[0]->created_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_temp_history')->insert([  
                        'pth_id'=>$pth_offline->pth_id,
                        'patients_id'=>$pth_offline->patients_id,
                        'temp'=>$pth_offline->temp,
                        'status'=>$pth_offline->status, 
                        'updated_at'=>$pth_offline->updated_at,
                        'created_at'=>$pth_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pth_list = DB::connection('mysql2')->table('patients_temp_history')->get();  
        foreach($pth_list as $pth_online){  
            $pth_online_count = DB::table('patients_temp_history')->where('pth_id', $pth_online->pth_id)->get();
                if(count($pth_online_count) > 0){
                    DB::table('patients_temp_history')->where('pth_id', $pth_online->pth_id)->update([  
                        'patients_id'=>$pth_online->patients_id,
                        'temp'=>$pth_online->temp,
                        'status'=>$pth_online->status, 
                        'updated_at'=>$pth_online->updated_at,
                        'created_at'=>$pth_online->created_at,
                    ]); 
                }else{
                    DB::table('patients_temp_history')->insert([     
                        'pth_id'=>$pth_online->pth_id,
                        'patients_id'=>$pth_online->patients_id,
                        'temp'=>$pth_online->temp,
                        'status'=>$pth_online->status, 
                        'updated_at'=>$pth_online->updated_at,
                        'created_at'=>$pth_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizecholesterol(){ //patient cholesterol history
        // syncronize appointment_settings table from offline to online  
        $chol_offline_list = DB::table('patients_cholesterol_history')->get();  
        foreach($chol_offline_list as $choles_offline){  
            $choles_offline_count = DB::connection('mysql2')->table('patients_cholesterol_history')->where('cholesterol_id', $choles_offline->cholesterol_id)->get();
                if(count($choles_offline_count) > 0){  
                    if($choles_offline->updated_at > $choles_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_cholesterol_history')->where('cholesterol_id', $choles_offline->cholesterol_id)->update([      
                            'patients_id'=>$choles_offline->patients_id,
                            'cholesterol'=>$choles_offline->cholesterol, 
                            'updated_at'=>$choles_offline->updated_at,
                            'created_at'=>$choles_offline->created_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_cholesterol_history')->where('cholesterol_id', $choles_offline_count[0]->cholesterol_id)->update([   
                            'patients_id'=>$choles_offline_count[0]->patients_id,
                            'cholesterol'=>$choles_offline_count[0]->cholesterol, 
                            'updated_at'=>$choles_offline_count[0]->updated_at,
                            'created_at'=>$choles_offline_count[0]->created_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_cholesterol_history')->insert([  
                        'cholesterol_id'=>$choles_offline->cholesterol_id,
                        'patients_id'=>$choles_offline->patients_id,
                        'cholesterol'=>$choles_offline->cholesterol, 
                        'updated_at'=>$choles_offline->updated_at,
                        'created_at'=>$choles_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $choles_list = DB::connection('mysql2')->table('patients_cholesterol_history')->get();  
        foreach($choles_list as $choles_online){  
            $choles_online_count = DB::table('patients_cholesterol_history')->where('cholesterol_id', $choles_online->cholesterol_id)->get();
                if(count($choles_online_count) > 0){
                    DB::table('patients_cholesterol_history')->where('cholesterol_id', $choles_online->cholesterol_id)->update([  
                        'patients_id'=>$choles_online->patients_id,
                        'cholesterol'=>$choles_online->cholesterol, 
                        'updated_at'=>$choles_online->updated_at,
                        'created_at'=>$choles_online->created_at,
                    ]); 
                }else{
                    DB::table('patients_cholesterol_history')->insert([     
                        'cholesterol_id'=>$choles_online->cholesterol_id,
                        'patients_id'=>$choles_online->patients_id,
                        'cholesterol'=>$choles_online->cholesterol, 
                        'updated_at'=>$choles_online->updated_at,
                        'created_at'=>$choles_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    // okkkkkkk
    public function syncronizeuric_acid(){ //patient uric_acid history
        // syncronize appointment_settings table from offline to online  
        $chol_offline_list = DB::table('patients_uric_acid_history')->get();  
        foreach($chol_offline_list as $choles_offline){  
            $choles_offline_count = DB::connection('mysql2')->table('patients_uric_acid_history')->where('uric_acid_id', $choles_offline->uric_acid_id)->get();
                if(count($choles_offline_count) > 0){  
                    if($choles_offline->updated_at > $choles_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_uric_acid_history')->where('uric_acid_id', $choles_offline->uric_acid_id)->update([      
                            'patients_id'=>$choles_offline->patients_id,
                            'uric_acid'=>$choles_offline->uric_acid, 
                            'updated_at'=>$choles_offline->updated_at,
                            'created_at'=>$choles_offline->created_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_uric_acid_history')->where('uric_acid_id', $choles_offline_count[0]->uric_acid_id)->update([   
                            'patients_id'=>$choles_offline_count[0]->patients_id,
                            'uric_acid'=>$choles_offline_count[0]->uric_acid, 
                            'updated_at'=>$choles_offline_count[0]->updated_at,
                            'created_at'=>$choles_offline_count[0]->created_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_uric_acid_history')->insert([  
                        'uric_acid_id'=>$choles_offline->uric_acid_id,
                        'patients_id'=>$choles_offline->patients_id,
                        'uric_acid'=>$choles_offline->uric_acid, 
                        'updated_at'=>$choles_offline->updated_at,
                        'created_at'=>$choles_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $choles_list = DB::connection('mysql2')->table('patients_uric_acid_history')->get();  
        foreach($choles_list as $choles_online){  
            $choles_online_count = DB::table('patients_uric_acid_history')->where('uric_acid_id', $choles_online->uric_acid_id)->get();
                if(count($choles_online_count) > 0){
                    DB::table('patients_uric_acid_history')->where('uric_acid_id', $choles_online->uric_acid_id)->update([  
                        'patients_id'=>$choles_online->patients_id,
                        'uric_acid'=>$choles_online->uric_acid, 
                        'updated_at'=>$choles_online->updated_at,
                        'created_at'=>$choles_online->created_at,
                    ]); 
                }else{
                    DB::table('patients_uric_acid_history')->insert([     
                        'uric_acid_id'=>$choles_online->uric_acid_id,
                        'patients_id'=>$choles_online->patients_id,
                        'uric_acid'=>$choles_online->uric_acid, 
                        'updated_at'=>$choles_online->updated_at,
                        'created_at'=>$choles_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    }
 
    // okkkkkkk
    public function syncronizebug(){ //reported bugs
        // syncronize appointment_settings table from offline to online  
        $bug_offline_list = DB::table('report_bug')->get();  
        foreach($bug_offline_list as $bug_offline){  
            $bug_offline_count = DB::connection('mysql2')->table('report_bug')->where('bug_id', $bug_offline->bug_id)->get();
                if(count($bug_offline_count) > 0){  
                    if($bug_offline->updated_at > $bug_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('report_bug')->where('bug_id', $bug_offline->bug_id)->update([      
                            'bug_details'=>$bug_offline->bug_details,
                            'image'=>$bug_offline->image,
                            'related_issues'=>$bug_offline->related_issues, 
                            'response_status'=>$bug_offline->response_status,
                            'response_remarks'=>$bug_offline->response_remarks,
                            'reported_by'=>$bug_offline->reported_by,
                            'reported_from'=>$bug_offline->reported_from,
                            'status'=>$bug_offline->status,
                            'created_at'=>$bug_offline->created_at,
                            'updated_at'=>$bug_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('report_bug')->where('bug_id', $bug_offline_count[0]->bug_id)->update([   
                            'bug_details'=>$bug_offline_count[0]->bug_details,
                            'image'=>$bug_offline_count[0]->image,
                            'related_issues'=>$bug_offline_count[0]->related_issues, 
                            'response_status'=>$bug_offline_count[0]->response_status,
                            'response_remarks'=>$bug_offline_count[0]->response_remarks,
                            'reported_by'=>$bug_offline_count[0]->reported_by,
                            'reported_from'=>$bug_offline_count[0]->reported_from,
                            'status'=>$bug_offline_count[0]->status,
                            'created_at'=>$bug_offline_count[0]->created_at,
                            'updated_at'=>$bug_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('report_bug')->insert([  
                        'bug_id'=>$bug_offline->bug_id,
                        'bug_details'=>$bug_offline->bug_details,
                        'image'=>$bug_offline->image,
                        'related_issues'=>$bug_offline->related_issues, 
                        'response_status'=>$bug_offline->response_status,
                        'response_remarks'=>$bug_offline->response_remarks,
                        'reported_by'=>$bug_offline->reported_by,
                        'reported_from'=>$bug_offline->reported_from,
                        'status'=>$bug_offline->status,
                        'created_at'=>$bug_offline->created_at,
                        'updated_at'=>$bug_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $bug_list = DB::connection('mysql2')->table('report_bug')->get();  
        foreach($bug_list as $bug_online){  
            $bug_online_count = DB::table('report_bug')->where('bug_id', $bug_online->bug_id)->get();
                if(count($bug_online_count) > 0){
                    DB::table('report_bug')->where('bug_id', $bug_online->bug_id)->update([   
                        'bug_details'=>$bug_online->bug_details,
                        'image'=>$bug_online->image,
                        'related_issues'=>$bug_online->related_issues, 
                        'response_status'=>$bug_online->response_status,
                        'response_remarks'=>$bug_online->response_remarks,
                        'reported_by'=>$bug_online->reported_by,
                        'reported_from'=>$bug_online->reported_from,
                        'status'=>$bug_online->status,
                        'created_at'=>$bug_online->created_at,
                        'updated_at'=>$bug_online->updated_at,
                    ]); 
                }else{
                    DB::table('report_bug')->insert([     
                        'bug_id'=>$bug_online->bug_id,
                        'bug_details'=>$bug_online->bug_details,
                        'image'=>$bug_online->image,
                        'related_issues'=>$bug_online->related_issues, 
                        'response_status'=>$bug_online->response_status,
                        'response_remarks'=>$bug_online->response_remarks,
                        'reported_by'=>$bug_online->reported_by,
                        'reported_from'=>$bug_online->reported_from,
                        'status'=>$bug_online->status,
                        'created_at'=>$bug_online->created_at,
                        'updated_at'=>$bug_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }  

    // okkkkkkk
    public function patient_history_attachment(){ //patient pulse history
        // syncronize appointment_settings table from offline to online  
        $patient_hist_attachment = DB::table('patients_history_attachment')->get();  
        foreach($patient_hist_attachment as $attachment_offline){  
            $attachment_offline_count = DB::connection('mysql2')->table('patients_history_attachment')->where('pha_id', $attachment_offline->pha_id)->get();
                if(count($attachment_offline_count) > 0){  
                    if($attachment_offline->updated_at > $attachment_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_attachment')->where('pha_id', $attachment_offline->pha_id)->update([      
                            'history_attachment_id'=>$attachment_offline->history_attachment_id,
                            'patient_id'=>$attachment_offline->patient_id,
                            'attachment'=>$attachment_offline->attachment, 
                            'remarks'=>$attachment_offline->remarks,
                            'status'=>$attachment_offline->status,
                            'updated_at'=>$attachment_offline->updated_at, 
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_attachment')->where('pha_id', $attachment_offline_count[0]->pha_id)->update([  
                            'pha_id'=> $attachment_offline_count[0]->pha_id, 
                            'history_attachment_id'=>$attachment_offline_count[0]->history_attachment_id,
                            'patient_id'=>$attachment_offline_count[0]->patient_id,
                            'attachment'=>$attachment_offline_count[0]->attachment, 
                            'remarks'=>$attachment_offline_count[0]->remarks,
                            'status'=>$attachment_offline_count[0]->status,
                            'updated_at'=>$attachment_offline_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_attachment')->insert([
                        'pha_id'=> $attachment_offline->pha_id, 
                        'history_attachment_id'=>$attachment_offline->history_attachment_id,
                        'patient_id'=>$attachment_offline->patient_id,
                        'attachment'=>$attachment_offline->attachment, 
                        'remarks'=>$attachment_offline->remarks,
                        'status'=>$attachment_offline->status,
                        'created_at'=>$attachment_offline->created_at, 
                        'updated_at'=>$attachment_offline->updated_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $patient_attachment = DB::connection('mysql2')->table('patients_history_attachment')->get();  
        foreach($patient_attachment as $attachment_online){  
            $attachment_online_count = DB::table('patients_history_attachment')->where('pha_id', $attachment_online->pha_id)->get();
                if(count($attachment_online_count) > 0){
                    DB::table('patients_history_attachment')->where('pha_id', $attachment_online->pha_id)->update([   
                        'history_attachment_id'=>$attachment_online->history_attachment_id,
                        'patient_id'=>$attachment_online->patient_id,
                        'attachment'=>$attachment_online->attachment, 
                        'remarks'=>$attachment_online->remarks,
                        'status'=>$attachment_online->status,
                        'updated_at'=>$attachment_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_attachment')->insert([     
                        'pha_id'=> $attachment_online->pha_id, 
                        'history_attachment_id'=>$attachment_online->history_attachment_id,
                        'patient_id'=>$attachment_online->patient_id,
                        'attachment'=>$attachment_online->attachment, 
                        'remarks'=>$attachment_online->remarks,
                        'status'=>$attachment_online->status,
                        'created_at'=>$attachment_online->created_at,
                        'updated_at'=>$attachment_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }   

    // okkkkkkk
    public function pharmacy(){ //pharmacy
        // syncronize pharmacy table from offline to online  
        $pharmacy = DB::table('pharmacy')->get();  
        foreach($pharmacy as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacy')->where('phmcy_id', $pharmacy_offline->phmcy_id)->get();
                if(count($pharmacy_offline_count) > 0){  
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('pharmacy')->where('phmcy_id', $pharmacy_offline->phmcy_id)->update([ 
                            'pharmacy_id'=> $pharmacy_offline->pharmacy_id,
                            'user_id'=>$pharmacy_offline->user_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'name'=>$pharmacy_offline->name, 
                            'company_name'=>$pharmacy_offline->company_name,
                            'address'=>$pharmacy_offline->address,
                            'tin_number'=>$pharmacy_offline->tin_number,
                            'email'=>$pharmacy_offline->email,
                            'contact'=>$pharmacy_offline->contact,
                            'status'=>$pharmacy_offline->status, 
                            'role'=>$pharmacy_offline->role, 
                            'pharmacy_type'=>$pharmacy_offline->pharmacy_type, 
                            'company_logo'=>$pharmacy_offline->company_logo, 
                            'updated_at'=>$pharmacy_offline->updated_at, 
                        ]);  
                    }  
                    else{
                        DB::table('pharmacy')->where('phmcy_id', $pharmacy_offline_count[0]->phmcy_id)->update([  
                            'phmcy_id'=>$pharmacy_offline_count[0]->phmcy_id,
                            'pharmacy_id'=> $pharmacy_offline_count[0]->pharmacy_id,
                            'user_id'=>$pharmacy_offline_count[0]->user_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'name'=>$pharmacy_offline_count[0]->name, 
                            'company_name'=>$pharmacy_offline_count[0]->company_name,
                            'address'=>$pharmacy_offline_count[0]->address,
                            'tin_number'=>$pharmacy_offline_count[0]->tin_number,
                            'email'=>$pharmacy_offline_count[0]->email,
                            'contact'=>$pharmacy_offline_count[0]->contact,
                            'status'=>$pharmacy_offline_count[0]->status, 
                            'role'=>$pharmacy_offline_count[0]->role, 
                            'pharmacy_type'=>$pharmacy_offline_count[0]->pharmacy_type, 
                            'company_logo'=>$pharmacy_offline_count[0]->company_logo, 
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('pharmacy')->insert([
                        'phmcy_id'=> $pharmacy_offline->phmcy_id, 
                        'pharmacy_id'=> $pharmacy_offline->pharmacy_id,
                        'user_id'=>$pharmacy_offline->user_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'name'=>$pharmacy_offline->name, 
                        'company_name'=>$pharmacy_offline->company_name,
                        'address'=>$pharmacy_offline->address,
                        'tin_number'=>$pharmacy_offline->tin_number,
                        'email'=>$pharmacy_offline->email,
                        'contact'=>$pharmacy_offline->contact,
                        'status'=>$pharmacy_offline->status, 
                        'role'=>$pharmacy_offline->role, 
                        'pharmacy_type'=>$pharmacy_offline->pharmacy_type, 
                        'company_logo'=>$pharmacy_offline->company_logo, 
                        'created_at'=>$pharmacy_offline->created_at,
                        'updated_at'=>$pharmacy_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacy')->get();  
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacy')->where('phmcy_id', $pharmacy_online->phmcy_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacy')->where('phmcy_id', $pharmacy_online->phmcy_id)->update([   
                        'pharmacy_id'=> $pharmacy_online->pharmacy_id,
                        'user_id'=>$pharmacy_online->user_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'name'=>$pharmacy_online->name, 
                        'company_name'=>$pharmacy_online->company_name,
                        'address'=>$pharmacy_online->address,
                        'tin_number'=>$pharmacy_online->tin_number,
                        'email'=>$pharmacy_online->email,
                        'contact'=>$pharmacy_online->contact,
                        'status'=>$pharmacy_online->status, 
                        'role'=>$pharmacy_online->role, 
                        'pharmacy_type'=>$pharmacy_online->pharmacy_type, 
                        'company_logo'=>$pharmacy_online->company_logo, 
                        'updated_at'=>$pharmacy_online->updated_at,
                    ]); 
                }else{
                    DB::table('pharmacy')->insert([     
                        'phmcy_id'=> $pharmacy_online->phmcy_id, 
                        'pharmacy_id'=> $pharmacy_online->pharmacy_id,
                        'user_id'=>$pharmacy_online->user_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'name'=>$pharmacy_online->name, 
                        'company_name'=>$pharmacy_online->company_name,
                        'address'=>$pharmacy_online->address,
                        'tin_number'=>$pharmacy_online->tin_number,
                        'email'=>$pharmacy_online->email,
                        'contact'=>$pharmacy_online->contact,
                        'status'=>$pharmacy_online->status, 
                        'role'=>$pharmacy_online->role, 
                        'pharmacy_type'=>$pharmacy_online->pharmacy_type, 
                        'company_logo'=>$pharmacy_online->company_logo, 
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }  

    // okkkkkkk
    public function pharmacyclinic_history(){ //pharmacyclinic_history
        // syncronize pharmacy table from offline to online  
        $pharmacy_hist = DB::table('pharmacyclinic_history')->get();  
        foreach($pharmacy_hist as $pharmacy_history_offline){  
            $pharmacy_history_offline_count = DB::connection('mysql2')->table('pharmacyclinic_history')->where('pch_id', $pharmacy_history_offline->pch_id)->get();
                if(count($pharmacy_history_offline_count) > 0){  
                    if($pharmacy_history_offline->updated_at > $pharmacy_history_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('pharmacyclinic_history')->where('pch_id', $pharmacy_history_offline->pch_id)->update([      
                            'product_id'=>$pharmacy_history_offline->product_id,
                            'pharmacy_id'=>$pharmacy_history_offline->pharmacy_id,
                            'management_id'=>$pharmacy_history_offline->management_id, 
                            'username'=>$pharmacy_history_offline->username,
                            'product'=>$pharmacy_history_offline->product,
                            'description'=>$pharmacy_history_offline->description,
                            'unit'=>$pharmacy_history_offline->unit,
                            'quantity'=>$pharmacy_history_offline->quantity, 
                            'request_type'=>$pharmacy_history_offline->request_type, 
                            'dr_no'=>$pharmacy_history_offline->dr_no, 
                            'supplier'=>$pharmacy_history_offline->supplier, 
                            'remarks'=>$pharmacy_history_offline->remarks,  
                            'updated_at'=>$pharmacy_history_offline->updated_at,
                        ]);  
                    }  
                    else{
                        DB::table('pharmacyclinic_history')->where('pch_id', $pharmacy_history_offline_count[0]->pch_id)->update([ 
                            'pch_id' => $pharmacy_history_offline_count[0]->pch_id,
                            'product_id'=>$pharmacy_history_offline_count[0]->product_id,
                            'pharmacy_id'=>$pharmacy_history_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_history_offline_count[0]->management_id, 
                            'username'=>$pharmacy_history_offline_count[0]->username,
                            'product'=>$pharmacy_history_offline_count[0]->product,
                            'description'=>$pharmacy_history_offline_count[0]->description,
                            'unit'=>$pharmacy_history_offline_count[0]->unit,
                            'quantity'=>$pharmacy_history_offline_count[0]->quantity, 
                            'request_type'=>$pharmacy_history_offline_count[0]->request_type, 
                            'dr_no'=>$pharmacy_history_offline_count[0]->dr_no, 
                            'supplier'=>$pharmacy_history_offline_count[0]->supplier, 
                            'remarks'=>$pharmacy_history_offline_count[0]->remarks,  
                            'updated_at'=>$pharmacy_history_offline_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('pharmacyclinic_history')->insert([
                        'pch_id'=> $pharmacy_history_offline->pch_id, 
                        'product_id'=>$pharmacy_history_offline->product_id,
                        'pharmacy_id'=>$pharmacy_history_offline->pharmacy_id,
                        'management_id'=>$pharmacy_history_offline->management_id, 
                        'username'=>$pharmacy_history_offline->username,
                        'product'=>$pharmacy_history_offline->product,
                        'description'=>$pharmacy_history_offline->description,
                        'unit'=>$pharmacy_history_offline->unit,
                        'quantity'=>$pharmacy_history_offline->quantity, 
                        'request_type'=>$pharmacy_history_offline->request_type, 
                        'dr_no'=>$pharmacy_history_offline->dr_no, 
                        'supplier'=>$pharmacy_history_offline->supplier, 
                        'remarks'=>$pharmacy_history_offline->remarks,  
                        'created_at'=>$pharmacy_history_offline->created_at,  
                        'updated_at'=>$pharmacy_history_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pharmacy_hist_online = DB::connection('mysql2')->table('pharmacyclinic_history')->get();  
        foreach($pharmacy_hist_online as $pharmacy_hist_online){  
            $pharmacy_hist_online_count = DB::table('pharmacyclinic_history')->where('pch_id', $pharmacy_hist_online->pch_id)->get();
                if(count($pharmacy_hist_online_count) > 0){
                    DB::table('pharmacyclinic_history')->where('pch_id', $pharmacy_hist_online->pch_id)->update([   
                        'product_id'=>$pharmacy_hist_online->product_id,
                        'pharmacy_id'=>$pharmacy_hist_online->pharmacy_id,
                        'management_id'=>$pharmacy_hist_online->management_id, 
                        'username'=>$pharmacy_hist_online->username,
                        'product'=>$pharmacy_hist_online->product,
                        'description'=>$pharmacy_hist_online->description,
                        'unit'=>$pharmacy_hist_online->unit,
                        'quantity'=>$pharmacy_hist_online->quantity, 
                        'request_type'=>$pharmacy_hist_online->request_type, 
                        'dr_no'=>$pharmacy_hist_online->dr_no, 
                        'supplier'=>$pharmacy_hist_online->supplier, 
                        'remarks'=>$pharmacy_hist_online->remarks,   
                        'updated_at'=>$pharmacy_hist_online->updated_at,
                    ]); 
                }else{
                    DB::table('pharmacyclinic_history')->insert([     
                        'pch_id'=> $pharmacy_hist_online->pch_id, 
                        'product_id'=>$pharmacy_hist_online->product_id,
                        'pharmacy_id'=>$pharmacy_hist_online->pharmacy_id,
                        'management_id'=>$pharmacy_hist_online->management_id, 
                        'username'=>$pharmacy_hist_online->username,
                        'product'=>$pharmacy_hist_online->product,
                        'description'=>$pharmacy_hist_online->description,
                        'unit'=>$pharmacy_hist_online->unit,
                        'quantity'=>$pharmacy_hist_online->quantity, 
                        'request_type'=>$pharmacy_hist_online->request_type, 
                        'dr_no'=>$pharmacy_hist_online->dr_no, 
                        'supplier'=>$pharmacy_hist_online->supplier, 
                        'remarks'=>$pharmacy_hist_online->remarks,  
                        'created_at'=>$pharmacy_hist_online->created_at,
                        'updated_at'=>$pharmacy_hist_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    } 

    // okkkkkkk
    public function pharmacyclinic_inventory(){ //pharmacyclinic_inventory
        // syncronize pharmacy table from offline to online  
        $pharmacy_inventory = DB::table('pharmacyclinic_inventory')->get();  
        foreach($pharmacy_inventory as $pharmacy_inventory_offline){  
            $pharmacy_inventory_offline_count = DB::connection('mysql2')->table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_inventory_offline->inventory_id)->get();
                if(count($pharmacy_inventory_offline_count) > 0){  
                    if($pharmacy_inventory_offline->updated_at > $pharmacy_inventory_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_inventory_offline->inventory_id)->update([      
                            'management_id'=>$pharmacy_inventory_offline->management_id,
                            'product_id'=>$pharmacy_inventory_offline->product_id, 
                            'pharmacy_id'=>$pharmacy_inventory_offline->pharmacy_id,
                            'dr_no'=>$pharmacy_inventory_offline->dr_no,
                            'quantity'=>$pharmacy_inventory_offline->quantity,
                            'unit'=>$pharmacy_inventory_offline->unit,
                            'starting_quantity'=>$pharmacy_inventory_offline->starting_quantity,
                            'manufacture_date'=>$pharmacy_inventory_offline->manufacture_date, 
                            'batch_no'=>$pharmacy_inventory_offline->batch_no, 
                            'expiry_date'=>$pharmacy_inventory_offline->expiry_date, 
                            'request_type'=>$pharmacy_inventory_offline->request_type, 
                            'comment'=>$pharmacy_inventory_offline->comment,   
                            'updated_at'=>$pharmacy_inventory_offline->updated_at,
                        ]);  
                    }  
                    else{
                        DB::table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_inventory_offline_count[0]->inventory_id)->update([  
                            'inventory_id'=>$pharmacy_inventory_offline_count[0]->inventory_id,
                            'management_id'=>$pharmacy_inventory_offline_count[0]->management_id,
                            'product_id'=>$pharmacy_inventory_offline_count[0]->product_id, 
                            'pharmacy_id'=>$pharmacy_inventory_offline_count[0]->pharmacy_id,
                            'dr_no'=>$pharmacy_inventory_offline_count[0]->dr_no,
                            'quantity'=>$pharmacy_inventory_offline_count[0]->quantity,
                            'unit'=>$pharmacy_inventory_offline_count[0]->unit,
                            'starting_quantity'=>$pharmacy_inventory_offline_count[0]->starting_quantity,
                            'manufacture_date'=>$pharmacy_inventory_offline_count[0]->manufacture_date, 
                            'batch_no'=>$pharmacy_inventory_offline_count[0]->batch_no, 
                            'expiry_date'=>$pharmacy_inventory_offline_count[0]->expiry_date, 
                            'request_type'=>$pharmacy_inventory_offline_count[0]->request_type, 
                            'comment'=>$pharmacy_inventory_offline_count[0]->comment,   
                            'updated_at'=>$pharmacy_inventory_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('pharmacyclinic_inventory')->insert([
                        'inventory_id'=> $pharmacy_inventory_offline->inventory_id, 
                        'management_id'=>$pharmacy_inventory_offline->management_id,
                        'product_id'=>$pharmacy_inventory_offline->product_id, 
                        'pharmacy_id'=>$pharmacy_inventory_offline->pharmacy_id,
                        'dr_no'=>$pharmacy_inventory_offline->dr_no,
                        'quantity'=>$pharmacy_inventory_offline->quantity,
                        'unit'=>$pharmacy_inventory_offline->unit,
                        'starting_quantity'=>$pharmacy_inventory_offline->starting_quantity,
                        'manufacture_date'=>$pharmacy_inventory_offline->manufacture_date, 
                        'batch_no'=>$pharmacy_inventory_offline->batch_no, 
                        'expiry_date'=>$pharmacy_inventory_offline->expiry_date, 
                        'request_type'=>$pharmacy_inventory_offline->request_type, 
                        'comment'=>$pharmacy_inventory_offline->comment,   
                        'created_at'=>$pharmacy_inventory_offline->created_at,
                        'updated_at'=>$pharmacy_inventory_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pharmacy_inventory_online = DB::connection('mysql2')->table('pharmacyclinic_inventory')->get();  
        foreach($pharmacy_inventory_online as $pharmacy_inventory_online){  
            $pharmacy_inventory_online_count = DB::table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_inventory_online->inventory_id)->get();
                if(count($pharmacy_inventory_online_count) > 0){
                    DB::table('pharmacyclinic_inventory')->where('inventory_id', $pharmacy_inventory_online->inventory_id)->update([ 
                        'management_id'=>$pharmacy_inventory_online->management_id,
                        'product_id'=>$pharmacy_inventory_online->product_id, 
                        'pharmacy_id'=>$pharmacy_inventory_online->pharmacy_id,
                        'dr_no'=>$pharmacy_inventory_online->dr_no,
                        'quantity'=>$pharmacy_inventory_online->quantity,
                        'unit'=>$pharmacy_inventory_online->unit,
                        'starting_quantity'=>$pharmacy_inventory_online->starting_quantity,
                        'manufacture_date'=>$pharmacy_inventory_online->manufacture_date, 
                        'batch_no'=>$pharmacy_inventory_online->batch_no, 
                        'expiry_date'=>$pharmacy_inventory_online->expiry_date, 
                        'request_type'=>$pharmacy_inventory_online->request_type, 
                        'comment'=>$pharmacy_inventory_online->comment,   
                        'updated_at'=>$pharmacy_inventory_online->updated_at,
                    ]); 
                }else{
                    DB::table('pharmacyclinic_inventory')->insert([     
                        'inventory_id'=> $pharmacy_inventory_online->inventory_id, 
                        'management_id'=>$pharmacy_inventory_online->management_id,
                        'product_id'=>$pharmacy_inventory_online->product_id, 
                        'pharmacy_id'=>$pharmacy_inventory_online->pharmacy_id,
                        'dr_no'=>$pharmacy_inventory_online->dr_no,
                        'quantity'=>$pharmacy_inventory_online->quantity,
                        'unit'=>$pharmacy_inventory_online->unit,
                        'starting_quantity'=>$pharmacy_inventory_online->starting_quantity,
                        'manufacture_date'=>$pharmacy_inventory_online->manufacture_date, 
                        'batch_no'=>$pharmacy_inventory_online->batch_no, 
                        'expiry_date'=>$pharmacy_inventory_online->expiry_date, 
                        'request_type'=>$pharmacy_inventory_online->request_type, 
                        'comment'=>$pharmacy_inventory_online->comment,   
                        'created_at'=>$pharmacy_inventory_online->created_at,
                        'updated_at'=>$pharmacy_inventory_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    } 

    // okkkkkkk
    public function pharmacyclinic_products(){ //pharmacyclinic_products
        // syncronize pharmacy table from offline to online  
        $pharmacy_inventory = DB::table('pharmacyclinic_products')->get();  
        foreach($pharmacy_inventory as $pharmacy_inventory_offline){  
            $pharmacy_inventory_offline_count = DB::connection('mysql2')->table('pharmacyclinic_products')->where('product_id', $pharmacy_inventory_offline->product_id)->get();
                if(count($pharmacy_inventory_offline_count) > 0){  
                    if($pharmacy_inventory_offline->updated_at > $pharmacy_inventory_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('pharmacyclinic_products')->where('product_id', $pharmacy_inventory_offline->product_id)->update([    
                            'pharmacy_id'=>$pharmacy_inventory_offline->pharmacy_id,
                            'management_id'=>$pharmacy_inventory_offline->management_id, 
                            'product'=>$pharmacy_inventory_offline->product,
                            'description'=>$pharmacy_inventory_offline->description,
                            'supplier'=>$pharmacy_inventory_offline->supplier,
                            'unit_price'=>$pharmacy_inventory_offline->unit_price,
                            'srp'=>$pharmacy_inventory_offline->srp, 
                            'updated_at'=>$pharmacy_inventory_offline->updated_at,
                        ]);  
                    }  
                    else{
                        DB::table('pharmacyclinic_products')->where('product_id', $pharmacy_inventory_offline_count[0]->product_id)->update([  
                            'product_id'=>$pharmacy_inventory_offline_count[0]->product_id,
                            'pharmacy_id'=>$pharmacy_inventory_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_inventory_offline_count[0]->management_id, 
                            'product'=>$pharmacy_inventory_offline_count[0]->product,
                            'description'=>$pharmacy_inventory_offline_count[0]->description,
                            'supplier'=>$pharmacy_inventory_offline_count[0]->supplier,
                            'unit_price'=>$pharmacy_inventory_offline_count[0]->unit_price,
                            'srp'=>$pharmacy_inventory_offline_count[0]->srp, 
                            'updated_at'=>$pharmacy_inventory_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('pharmacyclinic_products')->insert([
                        'product_id'=> $pharmacy_inventory_offline->product_id,
                        'pharmacy_id'=>$pharmacy_inventory_offline->pharmacy_id,
                        'management_id'=>$pharmacy_inventory_offline->management_id, 
                        'product'=>$pharmacy_inventory_offline->product,
                        'description'=>$pharmacy_inventory_offline->description,
                        'supplier'=>$pharmacy_inventory_offline->supplier,
                        'unit_price'=>$pharmacy_inventory_offline->unit_price,
                        'srp'=>$pharmacy_inventory_offline->srp, 
                        'created_at'=>$pharmacy_inventory_offline->created_at,
                        'updated_at'=>$pharmacy_inventory_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pharmacy_inventory_online = DB::connection('mysql2')->table('pharmacyclinic_products')->get();  
        foreach($pharmacy_inventory_online as $pharmacy_inventory_online){  
            $pharmacy_inventory_online_count = DB::table('pharmacyclinic_products')->where('product_id', $pharmacy_inventory_online->product_id)->get();
                if(count($pharmacy_inventory_online_count) > 0){
                    DB::table('pharmacyclinic_products')->where('product_id', $pharmacy_inventory_online->product_id)->update([   
                        'pharmacy_id'=>$pharmacy_inventory_online->pharmacy_id,
                        'management_id'=>$pharmacy_inventory_online->management_id, 
                        'product'=>$pharmacy_inventory_online->product,
                        'description'=>$pharmacy_inventory_online->description,
                        'supplier'=>$pharmacy_inventory_online->supplier,
                        'unit_price'=>$pharmacy_inventory_online->unit_price,
                        'srp'=>$pharmacy_inventory_online->srp, 
                        'updated_at'=>$pharmacy_inventory_online->updated_at,
                    ]); 
                }else{
                    DB::table('pharmacyclinic_products')->insert([     
                        'product_id'=>$pharmacy_inventory_online->product_id,
                        'pharmacy_id'=>$pharmacy_inventory_online->pharmacy_id,
                        'management_id'=>$pharmacy_inventory_online->management_id, 
                        'product'=>$pharmacy_inventory_online->product,
                        'description'=>$pharmacy_inventory_online->description,
                        'supplier'=>$pharmacy_inventory_online->supplier,
                        'unit_price'=>$pharmacy_inventory_online->unit_price,
                        'srp'=>$pharmacy_inventory_online->srp, 
                        'created_at'=>$pharmacy_inventory_online->created_at,
                        'updated_at'=>$pharmacy_inventory_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    } 

    // okkkkkkk
    public function pharmacyclinic_receipt(){ //pharmacyclinic_receipt
        // syncronize pharmacy table from offline to online  
        $pharmacy_recpt = DB::table('pharmacyclinic_receipt')->get();  
        foreach($pharmacy_recpt as $pharmacy_recpt_offline){  
            $pharmacy_recpt_offline_count = DB::connection('mysql2')->table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_recpt_offline->pcr_id)->get();
                if(count($pharmacy_recpt_offline_count) > 0){  
                    if($pharmacy_recpt_offline->updated_at > $pharmacy_recpt_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_recpt_offline->pcr_id)->update([      
                            'receipt_id'=>$pharmacy_recpt_offline->receipt_id,
                            'pharmacy_id'=>$pharmacy_recpt_offline->pharmacy_id,
                            'management_id'=>$pharmacy_recpt_offline->management_id, 
                            'username'=>$pharmacy_recpt_offline->username,
                            'name_customer'=>$pharmacy_recpt_offline->name_customer,
                            'address_customer'=>$pharmacy_recpt_offline->address_customer,
                            'tin_customer'=>$pharmacy_recpt_offline->tin_customer,
                            'product'=>$pharmacy_recpt_offline->product, 
                            'description'=>$pharmacy_recpt_offline->description,
                            'unit'=>$pharmacy_recpt_offline->unit,
                            'quantity'=>$pharmacy_recpt_offline->quantity,
                            'srp'=>$pharmacy_recpt_offline->srp,
                            'total'=>$pharmacy_recpt_offline->total,
                            'amount_paid'=>$pharmacy_recpt_offline->amount_paid,
                            'payment_change'=>$pharmacy_recpt_offline->payment_change,
                            'dr_no'=>$pharmacy_recpt_offline->dr_no,
                            'updated_at'=>$pharmacy_recpt_offline->updated_at,
                        ]);  
                    }  
                    else{
                        DB::table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_recpt_offline_count[0]->pcr_id)->update([
                            'pcr_id'=>$pharmacy_recpt_offline_count[0]->pcr_id,
                            'receipt_id'=>$pharmacy_recpt_offline_count[0]->receipt_id,
                            'pharmacy_id'=>$pharmacy_recpt_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_recpt_offline_count[0]->management_id, 
                            'username'=>$pharmacy_recpt_offline_count[0]->username,
                            'name_customer'=>$pharmacy_recpt_offline_count[0]->name_customer,
                            'address_customer'=>$pharmacy_recpt_offline_count[0]->address_customer,
                            'tin_customer'=>$pharmacy_recpt_offline_count[0]->tin_customer,
                            'product'=>$pharmacy_recpt_offline_count[0]->product, 
                            'description'=>$pharmacy_recpt_offline_count[0]->description,
                            'unit'=>$pharmacy_recpt_offline_count[0]->unit,
                            'quantity'=>$pharmacy_recpt_offline_count[0]->quantity,
                            'srp'=>$pharmacy_recpt_offline_count[0]->srp,
                            'total'=>$pharmacy_recpt_offline_count[0]->total,
                            'amount_paid'=>$pharmacy_recpt_offline_count[0]->amount_paid,
                            'payment_change'=>$pharmacy_recpt_offline_count[0]->payment_change,
                            'dr_no'=>$pharmacy_recpt_offline_count[0]->dr_no,
                            'updated_at'=>$pharmacy_recpt_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('pharmacyclinic_receipt')->insert([
                        'pcr_id'=> $pharmacy_recpt_offline->pcr_id, 
                        'receipt_id'=>$pharmacy_recpt_offline->receipt_id,
                        'pharmacy_id'=>$pharmacy_recpt_offline->pharmacy_id,
                        'management_id'=>$pharmacy_recpt_offline->management_id, 
                        'username'=>$pharmacy_recpt_offline->username,
                        'name_customer'=>$pharmacy_recpt_offline->name_customer,
                        'address_customer'=>$pharmacy_recpt_offline->address_customer,
                        'tin_customer'=>$pharmacy_recpt_offline->tin_customer,
                        'product'=>$pharmacy_recpt_offline->product, 
                        'description'=>$pharmacy_recpt_offline->description,
                        'unit'=>$pharmacy_recpt_offline->unit,
                        'quantity'=>$pharmacy_recpt_offline->quantity,
                        'srp'=>$pharmacy_recpt_offline->srp,
                        'total'=>$pharmacy_recpt_offline->total,
                        'amount_paid'=>$pharmacy_recpt_offline->amount_paid,
                        'payment_change'=>$pharmacy_recpt_offline->payment_change,
                        'dr_no'=>$pharmacy_recpt_offline->dr_no,
                        'updated_at'=>$pharmacy_recpt_offline->updated_at,
                        'created_at'=>$pharmacy_recpt_offline->created_at,
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pharmacy_recpt_online = DB::connection('mysql2')->table('pharmacyclinic_receipt')->get();  
        foreach($pharmacy_recpt_online as $pharmacy_recpt_online){  
            $pharmacy_recpt_online_count = DB::table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_recpt_online->pcr_id)->get();
                if(count($pharmacy_recpt_online_count) > 0){
                    DB::table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_recpt_online->pcr_id)->update([   
                        'receipt_id'=>$pharmacy_recpt_online->receipt_id,
                        'pharmacy_id'=>$pharmacy_recpt_online->pharmacy_id,
                        'management_id'=>$pharmacy_recpt_online->management_id, 
                        'username'=>$pharmacy_recpt_online->username,
                        'name_customer'=>$pharmacy_recpt_online->name_customer,
                        'address_customer'=>$pharmacy_recpt_online->address_customer,
                        'tin_customer'=>$pharmacy_recpt_online->tin_customer,
                        'product'=>$pharmacy_recpt_online->product, 
                        'description'=>$pharmacy_recpt_online->description,
                        'unit'=>$pharmacy_recpt_online->unit,
                        'quantity'=>$pharmacy_recpt_online->quantity,
                        'srp'=>$pharmacy_recpt_online->srp,
                        'total'=>$pharmacy_recpt_online->total,
                        'amount_paid'=>$pharmacy_recpt_online->amount_paid,
                        'payment_change'=>$pharmacy_recpt_online->payment_change,
                        'dr_no'=>$pharmacy_recpt_online->dr_no,
                        'updated_at'=>$pharmacy_recpt_online->updated_at,
                    ]); 
                }else{
                    DB::table('pharmacyclinic_receipt')->insert([     
                        'pcr_id'=> $pharmacy_recpt_online->pcr_id, 
                        'receipt_id'=>$pharmacy_recpt_online->receipt_id,
                        'pharmacy_id'=>$pharmacy_recpt_online->pharmacy_id,
                        'management_id'=>$pharmacy_recpt_online->management_id, 
                        'username'=>$pharmacy_recpt_online->username,
                        'name_customer'=>$pharmacy_recpt_online->name_customer,
                        'address_customer'=>$pharmacy_recpt_online->address_customer,
                        'tin_customer'=>$pharmacy_recpt_online->tin_customer,
                        'product'=>$pharmacy_recpt_online->product, 
                        'description'=>$pharmacy_recpt_online->description,
                        'unit'=>$pharmacy_recpt_online->unit,
                        'quantity'=>$pharmacy_recpt_online->quantity,
                        'srp'=>$pharmacy_recpt_online->srp,
                        'total'=>$pharmacy_recpt_online->total,
                        'amount_paid'=>$pharmacy_recpt_online->amount_paid,
                        'payment_change'=>$pharmacy_recpt_online->payment_change,
                        'dr_no'=>$pharmacy_recpt_online->dr_no,
                        'updated_at'=>$pharmacy_recpt_online->updated_at,
                        'created_at'=>$pharmacy_recpt_online->created_at,
                    ]); 
                } 
        } 
        
        return true;
    } 

    // okkkkkkk
    public function pharmacyclinic_sales(){ //pharmacyclinic_sales
        // syncronize pharmacy table from offline to online  
        $pharmacy_sale = DB::table('pharmacyclinic_sales')->get();  
        foreach($pharmacy_sale as $pharmacy_sale_offline){  
            $pharmacy_sale_offline_count = DB::connection('mysql2')->table('pharmacyclinic_sales')->where('sales_id', $pharmacy_sale_offline->sales_id)->get();
                if(count($pharmacy_sale_offline_count) > 0){  
                    if($pharmacy_sale_offline->updated_at > $pharmacy_sale_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('pharmacyclinic_sales')->where('sales_id', $pharmacy_sale_offline->sales_id)->update([      
                            'product_id'=>$pharmacy_sale_offline->product_id,
                            'pharmacy_id'=>$pharmacy_sale_offline->pharmacy_id, 
                            'management_id'=>$pharmacy_sale_offline->management_id,
                            'username'=>$pharmacy_sale_offline->username,
                            'product'=>$pharmacy_sale_offline->product,
                            'description'=>$pharmacy_sale_offline->description,
                            'unit'=>$pharmacy_sale_offline->unit,  
                            'quantit'=>$pharmacy_sale_offline->quantity, 
                            'total'=>$pharmacy_sale_offline->total,
                            'dr_no'=>$pharmacy_sale_offline->dr_no,
                            'updated_at'=>$pharmacy_sale_offline->updated_at, 
                        ]);  
                    }  
                    else{
                        DB::table('pharmacyclinic_sales')->where('sales_id', $pharmacy_sale_offline_count[0]->sales_id)->update([  
                            'sales_id'=>$pharmacy_sale_offline_count[0]->sales_id,
                            'product_id'=>$pharmacy_sale_offline_count[0]->product_id,
                            'pharmacy_id'=>$pharmacy_sale_offline_count[0]->pharmacy_id, 
                            'management_id'=>$pharmacy_sale_offline_count[0]->management_id,
                            'username'=>$pharmacy_sale_offline_count[0]->username,
                            'product'=>$pharmacy_sale_offline_count[0]->product,
                            'description'=>$pharmacy_sale_offline_count[0]->description,
                            'unit'=>$pharmacy_sale_offline_count[0]->unit,  
                            'quantity'=>$pharmacy_sale_offline_count[0]->quantity, 
                            'total'=>$pharmacy_sale_offline_count[0]->total,
                            'dr_no'=>$pharmacy_sale_offline_count[0]->dr_no,
                            'updated_at'=>$pharmacy_sale_offline_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('pharmacyclinic_sales')->insert([
                        'sales_id'=> $pharmacy_sale_offline->sales_id, 
                        'product_id'=>$pharmacy_sale_offline->product_id,
                        'pharmacy_id'=>$pharmacy_sale_offline->pharmacy_id, 
                        'management_id'=>$pharmacy_sale_offline->management_id,
                        'username'=>$pharmacy_sale_offline->username,
                        'product'=>$pharmacy_sale_offline->product,
                        'description'=>$pharmacy_sale_offline->description,
                        'unit'=>$pharmacy_sale_offline->unit,  
                        'quantity'=>$pharmacy_sale_offline->quantity, 
                        'total'=>$pharmacy_sale_offline->total,
                        'dr_no'=>$pharmacy_sale_offline->dr_no,
                        'updated_at'=>$pharmacy_sale_offline->updated_at, 
                        'created_at'=>$pharmacy_sale_offline->created_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pharmacy_sale_online = DB::connection('mysql2')->table('pharmacyclinic_sales')->get();  
        foreach($pharmacy_sale_online as $pharmacy_sale_online){  
            $pharmacy_sale_online_count = DB::table('pharmacyclinic_sales')->where('sales_id', $pharmacy_sale_online->sales_id)->get();
                if(count($pharmacy_sale_online_count) > 0){
                    DB::table('pharmacyclinic_sales')->where('sales_id', $pharmacy_sale_online->sales_id)->update([ 
                        'product_id'=>$pharmacy_sale_online->product_id,
                        'pharmacy_id'=>$pharmacy_sale_online->pharmacy_id, 
                        'management_id'=>$pharmacy_sale_online->management_id,
                        'username'=>$pharmacy_sale_online->username,
                        'product'=>$pharmacy_sale_online->product,
                        'description'=>$pharmacy_sale_online->description,
                        'unit'=>$pharmacy_sale_online->unit,  
                        'quantity'=>$pharmacy_sale_online->quantity, 
                        'total'=>$pharmacy_sale_online->total,
                        'dr_no'=>$pharmacy_sale_online->dr_no,
                        'updated_at'=>$pharmacy_sale_online->updated_at, 
                    ]); 
                }else{
                    DB::table('pharmacyclinic_sales')->insert([     
                        'sales_id'=> $pharmacy_sale_online->sales_id, 
                        'product_id'=>$pharmacy_sale_online->product_id,
                        'pharmacy_id'=>$pharmacy_sale_online->pharmacy_id, 
                        'management_id'=>$pharmacy_sale_online->management_id,
                        'username'=>$pharmacy_sale_online->username,
                        'product'=>$pharmacy_sale_online->product,
                        'description'=>$pharmacy_sale_online->description,
                        'unit'=>$pharmacy_sale_online->unit,  
                        'quantity'=>$pharmacy_sale_online->quantity, 
                        'total'=>$pharmacy_sale_online->total,
                        'dr_no'=>$pharmacy_sale_online->dr_no,
                        'updated_at'=>$pharmacy_sale_online->updated_at, 
                        'created_at'=>$pharmacy_sale_online->created_at, 
                    ]); 
                } 
        } 
        
        return true;
    } 

    // okkkkkkk
    public function pharmacyclinic_products_package(){ //pharmacyclinic_products_package
        // syncronize pharmacy table from offline to online  
        $pharma_packages = DB::table('pharmacyclinic_products_package')->get();  
        foreach($pharma_packages as $pharma_packages_offline){  
            $pharma_packages_offline_count = DB::connection('mysql2')->table('pharmacyclinic_products_package')->where('ppp_id', $pharma_packages_offline->ppp_id)->get();
                if(count($pharma_packages_offline_count) > 0){  
                    if($pharma_packages_offline->updated_at > $pharma_packages_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('pharmacyclinic_products_package')->where('ppp_id', $pharma_packages_offline->ppp_id)->update([      
                            'package_id'=>$pharma_packages_offline->package_id,
                            'pharmacy_id'=>$pharma_packages_offline->pharmacy_id, 
                            'management_id'=>$pharma_packages_offline->management_id,
                            'amount'=>$pharma_packages_offline->amount,
                            'product_id'=>$pharma_packages_offline->product_id,
                            'product_qty'=>$pharma_packages_offline->product_qty,
                            'status'=>$pharma_packages_offline->status,   
                            'updated_at'=>$pharma_packages_offline->updated_at, 
                        ]);  
                    }  
                    else{
                        DB::table('pharmacyclinic_products_package')->where('ppp_id', $pharma_packages_offline_count[0]->ppp_id)->update([   
                            'package_id'=>$pharma_packages_offline_count[0]->package_id,
                            'pharmacy_id'=>$pharma_packages_offline_count[0]->pharmacy_id, 
                            'management_id'=>$pharma_packages_offline_count[0]->management_id,
                            'amount'=>$pharma_packages_offline_count[0]->amount,
                            'product_id'=>$pharma_packages_offline_count[0]->product_id,
                            'product_qty'=>$pharma_packages_offline_count[0]->product_qty,
                            'status'=>$pharma_packages_offline_count[0]->status,   
                            'updated_at'=>$pharma_packages_offline_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('pharmacyclinic_products_package')->insert([
                        'ppp_id'=> $pharma_packages_offline->ppp_id, 
                        'package_id'=>$pharma_packages_offline->package_id,
                        'pharmacy_id'=>$pharma_packages_offline->pharmacy_id, 
                        'management_id'=>$pharma_packages_offline->management_id,
                        'amount'=>$pharma_packages_offline->amount,
                        'product_id'=>$pharma_packages_offline->product_id,
                        'product_qty'=>$pharma_packages_offline->product_qty,
                        'status'=>$pharma_packages_offline->status,   
                        'updated_at'=>$pharma_packages_offline->updated_at,  
                        'created_at'=>$pharma_packages_offline->created_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $pharma_packages_online = DB::connection('mysql2')->table('pharmacyclinic_products_package')->get();  
        foreach($pharma_packages_online as $pharma_packages_online){  
            $pharma_packages_online_count = DB::table('pharmacyclinic_products_package')->where('ppp_id', $pharma_packages_online->ppp_id)->get();
                if(count($pharma_packages_online_count) > 0){
                    DB::table('pharmacyclinic_products_package')->where('ppp_id', $pharma_packages_online->ppp_id)->update([  
                        'package_id'=>$pharma_packages_online->package_id,
                        'pharmacy_id'=>$pharma_packages_online->pharmacy_id, 
                        'management_id'=>$pharma_packages_online->management_id,
                        'amount'=>$pharma_packages_online->amount,
                        'product_id'=>$pharma_packages_online->product_id,
                        'product_qty'=>$pharma_packages_online->product_qty,
                        'status'=>$pharma_packages_online->status,
                        'updated_at'=>$pharma_packages_online->updated_at,  
                        'created_at'=>$pharma_packages_online->created_at, 
                    ]);
                }else{
                    DB::table('pharmacyclinic_products_package')->insert([     
                        'ppp_id'=> $pharma_packages_online->ppp_id,  
                        'package_id'=>$pharma_packages_online->package_id,
                        'pharmacy_id'=>$pharma_packages_online->pharmacy_id, 
                        'management_id'=>$pharma_packages_online->management_id,
                        'amount'=>$pharma_packages_online->amount,
                        'product_id'=>$pharma_packages_online->product_id,
                        'product_qty'=>$pharma_packages_online->product_qty,
                        'status'=>$pharma_packages_online->status,   
                        'updated_at'=>$pharma_packages_online->updated_at,  
                        'created_at'=>$pharma_packages_online->created_at, 
                    ]); 
                } 
        } 
        
        return true;
    } 


    // okkkkkkk
    public function billing(){ //billing
        // syncronize pharmacy table from offline to online  
        $billing = DB::table('billing')->get();  
        foreach($billing as $billing_offline){  
            $billing_offline_count = DB::connection('mysql2')->table('billing')->where('b_id', $billing_offline->b_id)->get();
                if(count($billing_offline_count) > 0){  
                    if($billing_offline->updated_at > $billing_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('billing')->where('b_id', $billing_offline->b_id)->update([      
                            'billing_id'=>$billing_offline->billing_id,
                            'encoder_id'=>$billing_offline->encoder_id,
                            'management_id'=>$billing_offline->management_id,
                            'claim_id'=>$billing_offline->claim_id, 
                            'billing'=>$billing_offline->billing,
                            'product_id'=>$billing_offline->product_id,
                            'is_package'=>$billing_offline->is_package,
                            'brand'=>$billing_offline->brand,
                            'amount'=>$billing_offline->amount,
                            'quantity'=>$billing_offline->quantity,
                            'status'=>$billing_offline->status,  
                            'is_viewed'=>$billing_offline->is_viewed,  
                            'release_status'=>$billing_offline->release_status, 
                            'updated_at'=>$billing_offline->updated_at,
                        ]);  
                    }  
                    else{
                        DB::table('billing')->where('b_id', $billing_offline_count[0]->b_id)->update([  
                            'b_id'=>$billing_offline_count[0]->b_id,
                            'billing_id'=>$billing_offline_count[0]->billing_id,
                            'encoder_id'=>$billing_offline_count[0]->encoder_id,
                            'management_id'=>$billing_offline_count[0]->management_id,
                            'claim_id'=>$billing_offline_count[0]->claim_id, 
                            'billing'=>$billing_offline_count[0]->billing,
                            'product_id'=>$billing_offline_count[0]->product_id,
                            'is_package'=>$billing_offline_count[0]->is_package,
                            'brand'=>$billing_offline_count[0]->brand,
                            'amount'=>$billing_offline_count[0]->amount,
                            'quantity'=>$billing_offline_count[0]->quantity,
                            'status'=>$billing_offline_count[0]->status,  
                            'is_viewed'=>$billing_offline_count[0]->is_viewed,  
                            'release_status'=>$billing_offline_count[0]->release_status, 
                            'updated_at'=>$billing_offline_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('billing')->insert([
                        'b_id'=> $billing_offline->b_id, 
                        'billing_id'=>$billing_offline->billing_id,
                        'encoder_id'=>$billing_offline->encoder_id,
                        'management_id'=>$billing_offline->management_id,
                        'claim_id'=>$billing_offline->claim_id, 
                        'billing'=>$billing_offline->billing,
                        'product_id'=>$billing_offline->product_id,
                        'is_package'=>$billing_offline->is_package,
                        'brand'=>$billing_offline->brand,
                        'amount'=>$billing_offline->amount,
                        'quantity'=>$billing_offline->quantity,
                        'status'=>$billing_offline->status,  
                        'is_viewed'=>$billing_offline->is_viewed,  
                        'release_status'=>$billing_offline->release_status, 
                        'created_at'=>$billing_offline->created_at, 
                        'updated_at'=>$billing_offline->updated_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $billing_online = DB::connection('mysql2')->table('billing')->get();  
        foreach($billing_online as $billing_online){  
            $billing_online_count = DB::table('billing')->where('b_id', $billing_online->b_id)->get();
                if(count($billing_online_count) > 0){
                    DB::table('billing')->where('b_id', $billing_online->b_id)->update([   
                        'billing_id'=>$billing_online->billing_id,
                        'encoder_id'=>$billing_online->encoder_id,
                        'management_id'=>$billing_online->management_id,
                        'claim_id'=>$billing_online->claim_id, 
                        'billing'=>$billing_online->billing,
                        'product_id'=>$billing_online->product_id,
                        'amount'=>$billing_online->amount,
                        'quantity'=>$billing_online->quantity,
                        'status'=>$billing_online->status,  
                        'release_status'=>$billing_online->release_status, 
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                }else{
                    DB::table('billing')->insert([     
                        'b_id'=> $billing_online->b_id, 
                        'billing_id'=> $billing_online->billing_id, 
                        'encoder_id'=>$billing_online->encoder_id,
                        'management_id'=>$billing_online->management_id,
                        'claim_id'=>$billing_online->claim_id, 
                        'billing'=>$billing_online->billing,
                        'product_id'=>$billing_online->product_id,
                        'amount'=>$billing_online->amount,
                        'quantity'=>$billing_online->quantity,
                        'status'=>$billing_online->status,  
                        'release_status'=>$billing_online->release_status, 
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    } 

    // okkkkkkk
    public function billing_payment_history(){ //billing_payment_history
        // syncronize pharmacy table from offline to online  
        $billing = DB::table('billing_payment_history')->get();  
        foreach($billing as $billing_payment_hist_offline){  
            $billing_payment_hist_offline_count = DB::connection('mysql2')->table('billing_payment_history')->where('payment_history_id', $billing_payment_hist_offline->payment_history_id)->get();
                if(count($billing_payment_hist_offline_count) > 0){  
                    if($billing_payment_hist_offline->updated_at > $billing_payment_hist_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('billing_payment_history')->where('payment_history_id', $billing_payment_hist_offline->payment_history_id)->update([   
                            'billing_statement_id'=>$billing_payment_hist_offline->billing_statement_id,
                            'billing_id'=>$billing_payment_hist_offline->billing_id,
                            'billing_user_id'=>$billing_payment_hist_offline->billing_user_id, 
                            'patient_id'=>$billing_payment_hist_offline->patient_id,
                            'payment_amount'=>$billing_payment_hist_offline->payment_amount,
                            'payment_type'=>$billing_payment_hist_offline->payment_type,
                            'check_no'=>$billing_payment_hist_offline->check_no,
                            'bank'=>$billing_payment_hist_offline->bank,  
                            'discount'=>$billing_payment_hist_offline->discount, 
                            'discount_reason'=>$billing_payment_hist_offline->discount_reason, 
                            'overall_discount_percent'=>$billing_payment_hist_offline->overall_discount_percent, 
                            'overall_discount_amount'=>$billing_payment_hist_offline->overall_discount_amount, 
                            'overall_discount_reason'=>$billing_payment_hist_offline->overall_discount_reason,
                            'updated_at'=>$billing_payment_hist_offline->updated_at, 
                        ]);  
                    }  
                    else{
                        DB::table('billing_payment_history')->where('payment_history_id', $billing_payment_hist_offline_count[0]->payment_history_id)->update([  
                            'payment_history_id'=>$billing_payment_hist_offline_count[0]->payment_history_id,
                            'billing_statement_id'=>$billing_payment_hist_offline_count[0]->billing_statement_id,
                            'billing_id'=>$billing_payment_hist_offline_count[0]->billing_id,
                            'billing_user_id'=>$billing_payment_hist_offline_count[0]->billing_user_id, 
                            'patient_id'=>$billing_payment_hist_offline_count[0]->patient_id,
                            'payment_amount'=>$billing_payment_hist_offline_count[0]->payment_amount,
                            'payment_type'=>$billing_payment_hist_offline_count[0]->payment_type,
                            'check_no'=>$billing_payment_hist_offline_count[0]->check_no,
                            'bank'=>$billing_payment_hist_offline_count[0]->bank,  
                            'discount'=>$billing_payment_hist_offline_count[0]->discount, 
                            'discount_reason'=>$billing_payment_hist_offline_count[0]->discount_reason, 
                            'overall_discount_percent'=>$billing_payment_hist_offline_count[0]->overall_discount_percent, 
                            'overall_discount_amount'=>$billing_payment_hist_offline_count[0]->overall_discount_amount, 
                            'overall_discount_reason'=>$billing_payment_hist_offline_count[0]->overall_discount_reason,
                            'updated_at'=>$billing_payment_hist_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                else{ 
                    DB::connection('mysql2')->table('billing_payment_history')->insert([
                        'payment_history_id'=> $billing_payment_hist_offline->payment_history_id, 
                        'billing_statement_id'=>$billing_payment_hist_offline->billing_statement_id,
                        'billing_id'=>$billing_payment_hist_offline->billing_id,
                        'billing_user_id'=>$billing_payment_hist_offline->billing_user_id, 
                        'patient_id'=>$billing_payment_hist_offline->patient_id,
                        'payment_amount'=>$billing_payment_hist_offline->payment_amount,
                        'payment_type'=>$billing_payment_hist_offline->payment_type,
                        'check_no'=>$billing_payment_hist_offline->check_no,
                        'bank'=>$billing_payment_hist_offline->bank,  
                        'discount'=>$billing_payment_hist_offline->discount, 
                        'discount_reason'=>$billing_payment_hist_offline->discount_reason, 
                        'overall_discount_percent'=>$billing_payment_hist_offline->overall_discount_percent, 
                        'overall_discount_amount'=>$billing_payment_hist_offline->overall_discount_amount, 
                        'overall_discount_reason'=>$billing_payment_hist_offline->overall_discount_reason,
                        'created_at'=>$billing_payment_hist_offline->created_at, 
                        'updated_at'=>$billing_payment_hist_offline->updated_at, 
                    ]);  
                } 
        } 
     
        // syncronize appointment_settings table from online to offline 
        $billing_hist_online = DB::connection('mysql2')->table('billing_payment_history')->get();  
        foreach($billing_hist_online as $billing_hist_online){  
            $billing_hist_online_count = DB::table('billing_payment_history')->where('payment_history_id', $billing_hist_online->payment_history_id)->get();
                if(count($billing_hist_online_count) > 0){
                    DB::table('billing_payment_history')->where('payment_history_id', $billing_hist_online->payment_history_id)->update([   
                        'billing_statement_id'=>$billing_hist_online->billing_statement_id,
                        'billing_id'=>$billing_hist_online->billing_id,
                        'billing_user_id'=>$billing_hist_online->billing_user_id, 
                        'patient_id'=>$billing_hist_online->patient_id,
                        'payment_amount'=>$billing_hist_online->payment_amount,
                        'payment_type'=>$billing_hist_online->payment_type,
                        'check_no'=>$billing_hist_online->check_no,
                        'bank'=>$billing_hist_online->bank,  
                        'discount'=>$billing_hist_online->discount, 
                        'discount_reason'=>$billing_hist_online->discount_reason, 
                        'overall_discount_percent'=>$billing_hist_online->overall_discount_percent, 
                        'overall_discount_amount'=>$billing_hist_online->overall_discount_amount, 
                        'overall_discount_reason'=>$billing_hist_online->overall_discount_reason,
                        'updated_at'=>$billing_hist_online->updated_at,
                    ]); 
                }else{
                    DB::table('billing_payment_history')->insert([     
                        'payment_history_id'=> $billing_hist_online->payment_history_id, 
                        'billing_statement_id'=>$billing_hist_online->billing_statement_id,
                        'billing_id'=>$billing_hist_online->billing_id,
                        'billing_user_id'=>$billing_hist_online->billing_user_id, 
                        'patient_id'=>$billing_hist_online->patient_id,
                        'payment_amount'=>$billing_hist_online->payment_amount,
                        'payment_type'=>$billing_hist_online->payment_type,
                        'check_no'=>$billing_hist_online->check_no,
                        'bank'=>$billing_hist_online->bank,  
                        'discount'=>$billing_hist_online->discount, 
                        'discount_reason'=>$billing_hist_online->discount_reason, 
                        'overall_discount_percent'=>$billing_hist_online->overall_discount_percent, 
                        'overall_discount_amount'=>$billing_hist_online->overall_discount_amount, 
                        'overall_discount_reason'=>$billing_hist_online->overall_discount_reason,
                        'created_at'=>$billing_hist_online->created_at, 
                        'updated_at'=>$billing_hist_online->updated_at,  
                    ]); 
                } 
        } 
        
        return true;
    } 
 
    public function billing_statement_cart(){ //billing cart by
        // syncronize billing cart by table from offline to online  
        $billing_cart_offline = DB::table('billing_statement_cart')->get();  
        foreach($billing_cart_offline as $billing_offline){  
            $billing_offline_count = DB::connection('mysql2')->table('billing_statement_cart')->where('billing_cart_id', $billing_offline->billing_cart_id)->get();
                if(count($billing_offline_count) > 0){
                    if($billing_offline->updated_at > $billing_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('billing_statement_cart')->where('billing_cart_id', $billing_offline->billing_cart_id)->update([      
                            'billing_statement_id'=>$billing_offline->billing_statement_id,
                            'billing_id'=>$billing_offline->billing_id,
                            'is_package'=>$billing_offline->is_package,
                            'patient_id'=>$billing_offline->patient_id, 
                            'billing_user_id'=>$billing_offline->billing_user_id,
                            'product_id'=>$billing_offline->product_id,
                            'product'=>$billing_offline->product,
                            'quantity'=>$billing_offline->quantity,
                            'amount'=>$billing_offline->amount,
                            'discount'=>$billing_offline->discount,
                            'discount_reason'=>$billing_offline->discount_reason,
                            'updated_at'=>$billing_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('billing_statement_cart')->where('billing_cart_id', $billing_offline_count[0]->billing_cart_id)->update([  
                            'billing_cart_id'=> $billing_offline_count[0]->billing_cart_id,
                            'billing_statement_id'=>$billing_offline_count[0]->billing_statement_id,
                            'billing_id'=>$billing_offline_count[0]->billing_id,
                            'is_package'=>$billing_offline_count[0]->is_package,
                            'patient_id'=>$billing_offline_count[0]->patient_id,
                            'billing_user_id'=>$billing_offline_count[0]->billing_user_id,
                            'product_id'=>$billing_offline_count[0]->product_id,
                            'product'=>$billing_offline_count[0]->product,
                            'quantity'=>$billing_offline_count[0]->quantity,
                            'amount'=>$billing_offline_count[0]->amount,
                            'discount'=>$billing_offline_count[0]->discount,
                            'discount_reason'=>$billing_offline_count[0]->discount_reason,
                            'updated_at'=>$billing_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('billing_statement_cart')->insert([
                        'billing_cart_id'=> $billing_offline->billing_cart_id, 
                        'billing_statement_id'=>$billing_offline->billing_statement_id,
                        'billing_id'=>$billing_offline->billing_id,
                        'is_package'=>$billing_offline->is_package,
                        'patient_id'=>$billing_offline->patient_id,
                        'billing_user_id'=>$billing_offline->billing_user_id,
                        'product_id'=>$billing_offline->product_id,
                        'product'=>$billing_offline->product,
                        'quantity'=>$billing_offline->quantity,
                        'amount'=>$billing_offline->amount,
                        'discount'=>$billing_offline->discount,
                        'discount_reason'=>$billing_offline->discount_reason,
                        'created_at'=>$billing_offline->created_at,
                        'updated_at'=>$billing_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize billing cart table from online to offline
        $billing_cart_online = DB::connection('mysql2')->table('billing_statement_cart')->get();  
        foreach($billing_cart_online as $billing_online){  
            $billing_online_count = DB::table('billing_statement_cart')->where('billing_cart_id', $billing_online->billing_cart_id)->get();
                if(count($billing_online_count) > 0){
                    DB::table('billing_statement_cart')->where('billing_cart_id', $billing_online->billing_cart_id)->update([   
                        'billing_statement_id'=>$billing_online->billing_statement_id,
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'quantity'=>$billing_online->quantity,
                        'amount'=>$billing_online->amount,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                }else{
                    DB::table('billing_statement_cart')->insert([
                        'billing_cart_id'=> $billing_online->billing_cart_id, 
                        'billing_statement_id'=>$billing_online->billing_statement_id,
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'quantity'=>$billing_online->quantity,
                        'amount'=>$billing_online->amount,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function billing_statement(){ //billing statement by
        // syncronize billing statement by table from offline to online  
        $billing_statement_offline = DB::table('billing_statement')->get();  
        foreach($billing_statement_offline as $billing_offline){  
            $billing_offline_count = DB::connection('mysql2')->table('billing_statement')->where('billing_statement_id', $billing_offline->billing_statement_id)->get();
                if(count($billing_offline_count) > 0){
                    if($billing_offline->updated_at > $billing_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('billing_statement')->where('billing_statement_id', $billing_offline->billing_statement_id)->update([      
                            'patient_id'=>$billing_offline->patient_id,
                            'encoders_id'=>$billing_offline->encoders_id,
                            'management_id'=>$billing_offline->management_id, 
                            'billing_id'=>$billing_offline->billing_id,
                            'is_package'=>$billing_offline->is_package,
                            'payment_amount'=>$billing_offline->payment_amount,
                            'payment_type'=>$billing_offline->payment_type,
                            'check_no'=>$billing_offline->check_no,
                            'bank'=>$billing_offline->bank,
                            'discount'=>$billing_offline->discount,
                            'discount_reason'=>$billing_offline->discount_reason,
                            'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                            'bill_status'=>$billing_offline->bill_status,
                            'updated_at'=>$billing_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('billing_statement')->where('billing_statement_id', $billing_offline_count[0]->billing_statement_id)->update([  
                            'billing_statement_id'=> $billing_offline_count[0]->billing_statement_id, 
                            'patient_id'=>$billing_offline_count[0]->patient_id,
                            'encoders_id'=>$billing_offline_count[0]->encoders_id,
                            'management_id'=>$billing_offline_count[0]->management_id, 
                            'billing_id'=>$billing_offline_count[0]->billing_id,
                            'is_package'=>$billing_offline_count[0]->is_package,
                            'payment_amount'=>$billing_offline_count[0]->payment_amount,
                            'payment_type'=>$billing_offline_count[0]->payment_type,
                            'check_no'=>$billing_offline_count[0]->check_no,
                            'bank'=>$billing_offline_count[0]->bank,
                            'discount'=>$billing_offline_count[0]->discount,
                            'discount_reason'=>$billing_offline_count[0]->discount_reason,
                            'overall_discount_percent'=>$billing_offline_count[0]->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline_count[0]->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline_count[0]->overall_discount_reason,
                            'bill_status'=>$billing_offline_count[0]->bill_status,
                            'updated_at'=>$billing_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('billing_statement')->insert([
                        'billing_statement_id'=> $billing_offline->billing_statement_id, 
                        'patient_id'=>$billing_offline->patient_id,
                        'encoders_id'=>$billing_offline->encoders_id,
                        'management_id'=>$billing_offline->management_id, 
                        'billing_id'=>$billing_offline->billing_id,
                        'is_package'=>$billing_offline->is_package,
                        'payment_amount'=>$billing_offline->payment_amount,
                        'payment_type'=>$billing_offline->payment_type,
                        'check_no'=>$billing_offline->check_no,
                        'bank'=>$billing_offline->bank,
                        'discount'=>$billing_offline->discount,
                        'discount_reason'=>$billing_offline->discount_reason,
                        'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                        'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                        'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                        'bill_status'=>$billing_offline->bill_status,
                        'created_at'=>$billing_offline->created_at,
                        'updated_at'=>$billing_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize billing statement table from online to offline
        $billing_statement_online = DB::connection('mysql2')->table('billing_statement')->get();  
        foreach($billing_statement_online as $billing_online){  
            $billing_online_count = DB::table('billing_statement')->where('billing_statement_id', $billing_online->billing_statement_id)->get();
                if(count($billing_online_count) > 0){
                    DB::table('billing_statement')->where('billing_statement_id', $billing_online->billing_statement_id)->update([   
                        'patient_id' => $billing_online->patient_id,
                        'encoders_id'=>$billing_online->encoders_id,
                        'management_id'=>$billing_online->management_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'payment_amount'=>$billing_online->payment_amount,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'bill_status'=>$billing_online->bill_status,
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                }else{
                    DB::table('billing_statement')->insert([
                        'billing_statement_id'=> $billing_online->billing_statement_id, 
                        'patient_id' => $billing_online->patient_id,
                        'encoders_id'=>$billing_online->encoders_id,
                        'management_id'=>$billing_online->management_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'is_package'=>$billing_online->is_package,
                        'payment_amount'=>$billing_online->payment_amount,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'discount'=>$billing_online->discount,
                        'discount_reason'=>$billing_online->discount_reason,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'bill_status'=>$billing_online->bill_status,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function billing_receipt(){ //billing receipt by
        // syncronize billng receipt table from offline to online  
        $billing_receipt_offline = DB::table('billing_receipt')->get();  
        foreach($billing_receipt_offline as $billing_offline){  
            $billing_offline_count = DB::connection('mysql2')->table('billing_receipt')->where('receipt_id', $billing_offline->receipt_id)->get();
                if(count($billing_offline_count) > 0){
                    if($billing_offline->updated_at > $billing_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('billing_receipt')->where('receipt_id', $billing_offline->receipt_id)->update([      
                            'payment_id'=>$billing_offline->payment_id,
                            'billing_user_id'=>$billing_offline->billing_user_id,
                            'patient_id'=>$billing_offline->patient_id, 
                            'billing_id'=>$billing_offline->billing_id,
                            'product_id'=>$billing_offline->product_id,
                            'product'=>$billing_offline->product,
                            'discount_per_product'=>$billing_offline->discount_per_product,
                            'quantity'=>$billing_offline->quantity,
                            'amount_pay'=>$billing_offline->amount_pay,
                            'payment_type'=>$billing_offline->payment_type,
                            'check_no'=>$billing_offline->check_no,
                            'bank'=>$billing_offline->bank,
                            'payment'=>$billing_offline->payment,
                            'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                            'status'=>$billing_offline->status,
                            'updated_at'=>$billing_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('billing_receipt')->where('receipt_id', $billing_offline_count[0]->receipt_id)->update([  
                            'receipt_id'=> $billing_offline_count[0]->receipt_id, 
                            'payment_id'=>$billing_offline_count[0]->payment_id,
                            'billing_user_id'=>$billing_offline_count[0]->billing_user_id,
                            'patient_id'=>$billing_offline_count[0]->patient_id, 
                            'billing_id'=>$billing_offline_count[0]->billing_id,
                            'product_id'=>$billing_offline_count[0]->product_id,
                            'product'=>$billing_offline_count[0]->product,
                            'discount_per_product'=>$billing_offline_count[0]->discount_per_product,
                            'quantity'=>$billing_offline_count[0]->quantity,
                            'amount_pay'=>$billing_offline_count[0]->amount_pay,
                            'payment_type'=>$billing_offline_count[0]->payment_type,
                            'check_no'=>$billing_offline_count[0]->check_no,
                            'bank'=>$billing_offline_count[0]->bank,
                            'payment'=>$billing_offline_count[0]->payment,
                            'overall_discount_percent'=>$billing_offline_count[0]->overall_discount_percent,
                            'overall_discount_amount'=>$billing_offline_count[0]->overall_discount_amount,
                            'overall_discount_reason'=>$billing_offline_count[0]->overall_discount_reason,
                            'status'=>$billing_offline_count[0]->status,
                            'updated_at'=>$billing_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('billing_receipt')->insert([
                        'receipt_id'=> $billing_offline->receipt_id, 
                        'payment_id'=>$billing_offline->payment_id,
                        'billing_user_id'=>$billing_offline->billing_user_id,
                        'patient_id'=>$billing_offline->patient_id, 
                        'billing_id'=>$billing_offline->billing_id,
                        'product_id'=>$billing_offline->product_id,
                        'product'=>$billing_offline->product,
                        'discount_per_product'=>$billing_offline->discount_per_product,
                        'quantity'=>$billing_offline->quantity,
                        'amount_pay'=>$billing_offline->amount_pay,
                        'payment_type'=>$billing_offline->payment_type,
                        'check_no'=>$billing_offline->check_no,
                        'bank'=>$billing_offline->bank,
                        'payment'=>$billing_offline->payment,
                        'overall_discount_percent'=>$billing_offline->overall_discount_percent,
                        'overall_discount_amount'=>$billing_offline->overall_discount_amount,
                        'overall_discount_reason'=>$billing_offline->overall_discount_reason,
                        'status'=>$billing_offline->status,
                        'created_at'=>$billing_offline->created_at,
                        'updated_at'=>$billing_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize billing receipt table from online to offline
        $billing_receipt_online = DB::connection('mysql2')->table('billing_receipt')->get();  
        foreach($billing_receipt_online as $billing_online){  
            $billing_online_count = DB::table('billing_receipt')->where('receipt_id', $billing_online->receipt_id)->get();
                if(count($billing_online_count) > 0){
                    DB::table('billing_receipt')->where('receipt_id', $billing_online->receipt_id)->update([   
                        'patient_id' => $billing_online->patient_id,
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'discount_per_product'=>$billing_online->discount_per_product,
                        'quantity'=>$billing_online->quantity,
                        'amount_pay'=>$billing_online->amount_pay,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'payment'=>$billing_online->payment,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'status'=>$billing_online->status,
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                }else{
                    DB::table('billing_receipt')->insert([
                        'receipt_id'=> $billing_online->receipt_id, 
                        'patient_id' => $billing_online->patient_id,
                        'billing_user_id'=>$billing_online->billing_user_id,
                        'patient_id'=>$billing_online->patient_id, 
                        'billing_id'=>$billing_online->billing_id,
                        'product_id'=>$billing_online->product_id,
                        'product'=>$billing_online->product,
                        'discount_per_product'=>$billing_online->discount_per_product,
                        'quantity'=>$billing_online->quantity,
                        'amount_pay'=>$billing_online->amount_pay,
                        'payment_type'=>$billing_online->payment_type,
                        'check_no'=>$billing_online->check_no,
                        'bank'=>$billing_online->bank,
                        'payment'=>$billing_online->payment,
                        'overall_discount_percent'=>$billing_online->overall_discount_percent,
                        'overall_discount_amount'=>$billing_online->overall_discount_amount,
                        'overall_discount_reason'=>$billing_online->overall_discount_reason,
                        'status'=>$billing_online->status,
                        'created_at'=>$billing_online->created_at,
                        'updated_at'=>$billing_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function bill_list(){ //billing receipt by
        // syncronize billng receipt table from offline to online  
        $bill_list_offline = DB::table('bill_list')->get();  
        foreach($bill_list_offline as $bill_list_offline){  
            $bill_list_offline_count = DB::connection('mysql2')->table('bill_list')->where('billing_id', $bill_list_offline->billing_id)->get();
                if(count($bill_list_offline_count) > 0){
                    if($bill_list_offline->updated_at > $bill_list_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('bill_list')->where('billing_id', $bill_list_offline->billing_id)->update([      
                            'encoders_id'=>$bill_list_offline->encoders_id,
                            'management_id'=>$bill_list_offline->management_id,
                            'billing'=>$bill_list_offline->billing, 
                            'amount'=>$bill_list_offline->amount,
                            'status'=>$bill_list_offline->status, 
                            'updated_at'=>$bill_list_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('bill_list')->where('billing_id', $bill_list_offline_count[0]->billing_id)->update([  
                            'billing_id'=> $bill_list_offline_count[0]->billing_id, 
                            'encoders_id'=>$bill_list_offline_count[0]->encoders_id,
                            'management_id'=>$bill_list_offline_count[0]->management_id,
                            'billing'=>$bill_list_offline_count[0]->billing, 
                            'amount'=>$bill_list_offline_count[0]->amount,
                            'status'=>$bill_list_offline_count[0]->status, 
                            'updated_at'=>$bill_list_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('bill_list')->insert([
                        'billing_id'=> $bill_list_offline->billing_id, 
                        'encoders_id'=>$bill_list_offline->encoders_id,
                        'management_id'=>$bill_list_offline->management_id,
                        'billing'=>$bill_list_offline->billing, 
                        'amount'=>$bill_list_offline->amount,
                        'status'=>$bill_list_offline->status, 
                        'updated_at'=>$bill_list_offline->updated_at,
                        'created_at'=>$bill_list_offline->created_at, 
                    ]);  
                } 
        } 
     
        // syncronize billing receipt table from online to offline
        $bill_list_online = DB::connection('mysql2')->table('bill_list')->get();  
        foreach($bill_list_online as $bill_list_online){  
            $bill_list_online_count = DB::table('bill_list')->where('billing_id', $bill_list_online->billing_id)->get();
                if(count($bill_list_online_count) > 0){
                    DB::table('bill_list')->where('billing_id', $bill_list_online->billing_id)->update([   
                        'encoders_id'=>$bill_list_online->encoders_id,
                        'management_id'=>$bill_list_online->management_id,
                        'billing'=>$bill_list_online->billing, 
                        'amount'=>$bill_list_online->amount,
                        'status'=>$bill_list_online->status, 
                        'updated_at'=>$bill_list_online->updated_at,
                    ]); 
                }else{
                    DB::table('bill_list')->insert([
                        'billing_id'=> $bill_list_online->billing_id, 
                        'encoders_id'=>$bill_list_online->encoders_id,
                        'management_id'=>$bill_list_online->management_id,
                        'billing'=>$bill_list_online->billing, 
                        'amount'=>$bill_list_online->amount,
                        'status'=>$bill_list_online->status,  
                        'created_at'=>$bill_list_online->created_at,
                        'updated_at'=>$bill_list_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_sodium(){ //patient sodium history
        // syncronize patient sodium table from offline to online  
        $patient_sodium_offline = DB::table('patients_history_sodium')->get();  
        foreach($patient_sodium_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_sodium')->where('phs_id', $patient_offline->phs_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_sodium')->where('phs_id', $patient_offline->phs_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'sodium'=>$patient_offline->sodium,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_by'=>$patient_offline->adder_by,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_sodium')->where('phs_id', $patient_offline_count[0]->phs_id)->update([  
                            'phs_id'=> $patient_offline_count[0]->phs_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'sodium'=>$patient_offline_count[0]->sodium,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_by'=>$patient_offline_count[0]->adder_by,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_sodium')->insert([
                        'phs_id'=> $patient_offline->phs_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'sodium'=>$patient_offline->sodium,
                        'added_by'=>$patient_offline->added_by,
                        'adder_by'=>$patient_offline->adder_by,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient sodium table from online to offline
        $patient_sodium_online = DB::connection('mysql2')->table('patients_history_sodium')->get();  
        foreach($patient_sodium_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_sodium')->where('phs_id', $patient_online->phs_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_sodium')->where('phs_id', $patient_online->phs_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'sodium'=>$patient_online->sodium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_by'=>$patient_online->adder_by,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_sodium')->insert([     
                        'phs_id'=> $patient_online->phs_id,
                        'patient_id'=>$patient_online->patient_id,
                        'sodium'=>$patient_online->sodium,
                        'added_by'=>$patient_online->added_by,
                        'adder_by'=>$patient_online->adder_by,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_protein(){ //patient protein history
        // syncronize patient protein table from offline to online  
        $patient_protein_offline = DB::table('patients_history_protein')->get();  
        foreach($patient_protein_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_protein')->where('php_id', $patient_offline->php_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_protein')->where('php_id', $patient_offline->php_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'protein'=>$patient_offline->protein,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_protein')->where('php_id', $patient_offline_count[0]->php_id)->update([  
                            'php_id'=> $patient_offline_count[0]->php_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'protein'=>$patient_offline_count[0]->protein,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_protein')->insert([
                        'php_id'=> $patient_offline->php_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'protein'=>$patient_offline->protein,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient protein table from online to offline
        $patient_protein_online = DB::connection('mysql2')->table('patients_history_protein')->get();  
        foreach($patient_protein_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_protein')->where('php_id', $patient_online->php_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_protein')->where('php_id', $patient_online->php_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'protein'=>$patient_online->protein, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_protein')->insert([     
                        'php_id'=> $patient_online->php_id,
                        'patient_id'=>$patient_online->patient_id,
                        'protein'=>$patient_online->protein,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_potassium(){ //patient potassium histor=>
        // syncronize patient potassium table from offline to online  
        $patient_potassium_offline = DB::table('patients_history_potassium')->get();  
        foreach($patient_potassium_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_potassium')->where('php_id', $patient_offline->php_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_potassium')->where('php_id', $patient_offline->php_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'potassium'=>$patient_offline->potassium,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_potassium')->where('php_id', $patient_offline_count[0]->php_id)->update([  
                            'php_id'=> $patient_offline_count[0]->php_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'potassium'=>$patient_offline_count[0]->potassium,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_potassium')->insert([
                        'php_id'=> $patient_offline->php_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'potassium'=>$patient_offline->potassium,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient potassium table from online to offline
        $patient_potassium_online = DB::connection('mysql2')->table('patients_history_potassium')->get();  
        foreach($patient_potassium_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_potassium')->where('php_id', $patient_online->php_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_potassium')->where('php_id', $patient_online->php_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'potassium'=>$patient_online->potassium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_potassium')->insert([     
                        'php_id'=> $patient_online->php_id,
                        'patient_id'=>$patient_online->patient_id,
                        'potassium'=>$patient_online->potassium,
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_magnessium(){ //patient magnessium history
        // syncronize patient magnessium table from offline to online  
        $patient_magnessium_offline = DB::table('patients_history_magnessium')->get();  
        foreach($patient_magnessium_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_magnessium')->where('phm_id', $patient_offline->phm_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_magnessium')->where('phm_id', $patient_offline->phm_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'magnessium'=>$patient_offline->magnessium,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_magnessium')->where('phm_id', $patient_offline_count[0]->phm_id)->update([  
                            'phm_id'=> $patient_offline_count[0]->phm_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'magnessium'=>$patient_offline_count[0]->magnessium,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_magnessium')->insert([
                        'phm_id'=> $patient_offline->phm_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'magnessium'=>$patient_offline->magnessium,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient magnessium table from online to offline 
        $patient_magnessium_online = DB::connection('mysql2')->table('patients_history_magnessium')->get();  
        foreach($patient_magnessium_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_magnessium')->where('phm_id', $patient_online->phm_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_magnessium')->where('phm_id', $patient_online->phm_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'magnessium'=>$patient_online->magnessium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_magnessium')->insert([     
                        'phm_id'=> $patient_online->phm_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'magnessium'=>$patient_online->magnessium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_lithium(){ //patient lithium history
        // syncronize patient lithium table from offline to online  
        $patient_lithium_offline = DB::table('patients_history_lithium')->get();  
        foreach($patient_lithium_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_lithium')->where('phl_id', $patient_offline->phl_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_lithium')->where('phl_id', $patient_offline->phl_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'lithium'=>$patient_offline->lithium,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_lithium')->where('phl_id', $patient_offline_count[0]->phl_id)->update([  
                            'phl_id'=> $patient_offline_count[0]->phl_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'lithium'=>$patient_offline_count[0]->lithium,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_lithium')->insert([
                        'phl_id'=> $patient_offline->phl_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'lithium'=>$patient_offline->lithium,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient lithium table from online to offline 
        $patient_lithium_online = DB::connection('mysql2')->table('patients_history_lithium')->get();  
        foreach($patient_lithium_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_lithium')->where('phl_id', $patient_online->phl_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_lithium')->where('phl_id', $patient_online->phl_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'lithium'=>$patient_online->lithium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_lithium')->insert([     
                        'phl_id'=> $patient_online->phl_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'lithium'=>$patient_online->lithium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_ldl(){ //patient low density lipoprotein history
        // syncronize patient low density lipoprotein table from offline to online  
        $patient_ldl_offline = DB::table('patients_history_ldl')->get();  
        foreach($patient_ldl_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_ldl')->where('phl_id', $patient_offline->phl_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_ldl')->where('phl_id', $patient_offline->phl_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'low_density_lipoprotein'=>$patient_offline->low_density_lipoprotein,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_ldl')->where('phl_id', $patient_offline_count[0]->phl_id)->update([  
                            'phl_id'=> $patient_offline_count[0]->phl_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'low_density_lipoprotein'=>$patient_offline_count[0]->low_density_lipoprotein,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_ldl')->insert([
                        'phl_id'=> $patient_offline->phl_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'low_density_lipoprotein'=>$patient_offline->low_density_lipoprotein,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient low density lipoprotein table from online to offline 
        $patient_ldl_online = DB::connection('mysql2')->table('patients_history_ldl')->get();  
        foreach($patient_ldl_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_ldl')->where('phl_id', $patient_online->phl_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_ldl')->where('phl_id', $patient_online->phl_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'low_density_lipoprotein'=>$patient_online->low_density_lipoprotein, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_ldl')->insert([     
                        'phl_id'=> $patient_online->phl_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'low_density_lipoprotein'=>$patient_online->low_density_lipoprotein, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_hdl(){ //patient high density lipoprotein history
        // syncronize patient high density lipoprotein table from offline to online  
        $patient_hdl_offline = DB::table('patients_history_hdl')->get();  
        foreach($patient_hdl_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_hdl')->where('phh_id', $patient_offline->phh_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_hdl')->where('phh_id', $patient_offline->phh_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'high_density_lipoproteins'=>$patient_offline->high_density_lipoproteins,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_hdl')->where('phh_id', $patient_offline_count[0]->phh_id)->update([  
                            'phh_id'=> $patient_offline_count[0]->phh_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'high_density_lipoproteins'=>$patient_offline_count[0]->high_density_lipoproteins,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_hdl')->insert([
                        'phh_id'=> $patient_offline->phh_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'high_density_lipoproteins'=>$patient_offline->high_density_lipoproteins,
                        'added_by'=>$patient_offline->added_by,
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient high density lipoprotein table from online to offline 
        $patient_hdl_online = DB::connection('mysql2')->table('patients_history_hdl')->get();  
        foreach($patient_hdl_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_hdl')->where('phh_id', $patient_online->phh_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_hdl')->where('phh_id', $patient_online->phh_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'high_density_lipoproteins'=>$patient_online->high_density_lipoproteins, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_hdl')->insert([     
                        'phh_id'=> $patient_online->phh_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'high_density_lipoproteins'=>$patient_online->high_density_lipoproteins, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }

    public function patients_history_creatinine(){ //patient creatinine history
        // syncronize patient creatinine table from offline to online  
        $patient_creatinine_offline = DB::table('patients_history_creatinine')->get();  
        foreach($patient_creatinine_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_creatinine')->where('phc_id', $patient_offline->phc_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_creatinine')->where('phc_id', $patient_offline->phc_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'creatinine'=>$patient_offline->creatinine,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_creatinine')->where('phc_id', $patient_offline_count[0]->phc_id)->update([  
                            'phc_id'=> $patient_offline_count[0]->phc_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'creatinine'=>$patient_offline_count[0]->creatinine,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_creatinine')->insert([
                        'phc_id'=> $patient_offline->phc_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'creatinine'=>$patient_offline->creatinine,
                        'added_by'=>$patient_offline->added_by, 
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient creatinine table from online to offline 
        $patient_creatinine_online = DB::connection('mysql2')->table('patients_history_creatinine')->get();  
        foreach($patient_creatinine_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_creatinine')->where('phc_id', $patient_online->phc_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_creatinine')->where('phc_id', $patient_online->phc_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'creatinine'=>$patient_online->creatinine, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_creatinine')->insert([     
                        'phc_id'=> $patient_online->phc_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'creatinine'=>$patient_online->creatinine, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    } 

    public function patients_history_chloride(){ //patient chloride history
        // syncronize patient chloride table from offline to online  
        $patient_chloride_offline = DB::table('patients_history_chloride')->get();  
        foreach($patient_chloride_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_chloride')->where('phc_id', $patient_offline->phc_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_chloride')->where('phc_id', $patient_offline->phc_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'chloride'=>$patient_offline->chloride,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_chloride')->where('phc_id', $patient_offline_count[0]->phc_id)->update([  
                            'phc_id'=> $patient_offline_count[0]->phc_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'chloride'=>$patient_offline_count[0]->chloride,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_chloride')->insert([
                        'phc_id'=> $patient_offline->phc_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'chloride'=>$patient_offline->chloride,
                        'added_by'=>$patient_offline->added_by, 
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient chloride table from online to offline 
        $patient_chloride_online = DB::connection('mysql2')->table('patients_history_chloride')->get();  
        foreach($patient_chloride_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_chloride')->where('phc_id', $patient_online->phc_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_chloride')->where('phc_id', $patient_online->phc_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'chloride'=>$patient_online->chloride, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_chloride')->insert([     
                        'phc_id'=> $patient_online->phc_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'chloride'=>$patient_online->chloride, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    } 

    public function patients_history_calcium(){ //patient calcium histor=>
        // syncronize patient calcium table from offline to online  
        $patient_calcium_offline = DB::table('patients_history_calcium')->get();  
        foreach($patient_calcium_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_history_calcium')->where('phc_id', $patient_offline->phc_id)->get();
                if(count($patient_offline_count) > 0){  
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('patients_history_calcium')->where('phc_id', $patient_offline->phc_id)->update([      
                            'patient_id'=>$patient_offline->patient_id,
                            'calcium'=>$patient_offline->calcium,
                            'added_by'=>$patient_offline->added_by, 
                            'adder_type'=>$patient_offline->adder_type,
                            'updated_at'=>$patient_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('patients_history_calcium')->where('phc_id', $patient_offline_count[0]->phc_id)->update([  
                            'phc_id'=> $patient_offline_count[0]->phc_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'calcium'=>$patient_offline_count[0]->calcium,
                            'added_by'=>$patient_offline_count[0]->added_by, 
                            'adder_type'=>$patient_offline_count[0]->adder_type,
                            'updated_at'=>$patient_offline_count[0]->updated_at,
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('patients_history_calcium')->insert([
                        'phc_id'=> $patient_offline->phc_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'calcium'=>$patient_offline->calcium,
                        'added_by'=>$patient_offline->added_by, 
                        'adder_type'=>$patient_offline->adder_type,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize patient calcium table from online to offline 
        $patient_calcium_online = DB::connection('mysql2')->table('patients_history_calcium')->get();  
        foreach($patient_calcium_online as $patient_online){  
            $patient_online_count = DB::table('patients_history_calcium')->where('phc_id', $patient_online->phc_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_history_calcium')->where('phc_id', $patient_online->phc_id)->update([   
                        'patient_id'=>$patient_online->patient_id,
                        'calcium'=>$patient_online->calcium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                }else{
                    DB::table('patients_history_calcium')->insert([     
                        'phc_id'=> $patient_online->phc_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'calcium'=>$patient_online->calcium, 
                        'added_by'=>$patient_online->added_by,
                        'adder_type'=>$patient_online->adder_type,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at,
                    ]); 
                } 
        } 
        
        return true;
    }  

    public function users_unemail_accounts(){ // user unemail accounts
        // syncronize user unemail accounts table from offline to online
        $user_unemail_offline = DB::table('users_unemail_accounts')->get();
        foreach($user_unemail_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('users_unemail_accounts')->where('unemail_id', $user_offline->unemail_id)->get();
                if(count($user_offline_count) > 0){
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('users_unemail_accounts')->where('unemail_id', $user_offline->unemail_id)->update([      
                            'user_id'=>$user_offline->user_id,
                            'username'=>$user_offline->username,
                            'password'=>$user_offline->password,
                            'status'=>$user_offline->status,
                            'updated_at'=>$user_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('users_unemail_accounts')->where('unemail_id', $user_offline_count[0]->unemail_id)->update([
                            'unemail_id'=> $user_offline_count[0]->unemail_id,
                            'user_id'=>$user_offline_count[0]->user_id,
                            'username'=>$user_offline_count[0]->username,
                            'password'=>$user_offline_count[0]->password,
                            'status'=>$user_offline_count[0]->status,
                            'updated_at'=>$user_offline_count[0]->updated_at,
                        ]);
                    }
                }
                
                else{
                    DB::connection('mysql2')->table('users_unemail_accounts')->insert([
                        'unemail_id'=> $user_offline->unemail_id,
                        'user_id'=>$user_offline->user_id,
                        'username'=>$user_offline->username,
                        'password'=>$user_offline->password,
                        'status'=>$user_offline->status,
                        'updated_at'=>$user_offline->updated_at,
                        'created_at'=>$user_offline->created_at,
                    ]);
                }
        } 
     
        // syncronize user unemail accounts table from online to offline 
        $user_unemail_online = DB::connection('mysql2')->table('users_unemail_accounts')->get();  
        foreach($user_unemail_online as $user_online){
            $user_online_count = DB::table('users_unemail_accounts')->where('unemail_id', $user_online->unemail_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('users_unemail_accounts')->where('unemail_id', $user_online->unemail_id)->update([
                        'user_id'=>$user_online->user_id,
                        'username'=>$user_online->username,
                        'password'=>$user_online->password,
                        'status'=>$user_online->status,
                        'updated_at'=>$user_online->updated_at,
                    ]);
                }else{
                    DB::table('users_unemail_accounts')->insert([
                        'unemail_id'=> $user_online->unemail_id,
                        'user_id'=>$user_online->user_id,
                        'username'=>$user_online->username,
                        'password'=>$user_online->password,
                        'status'=>$user_online->status,
                        'updated_at'=>$user_online->updated_at,
                        'created_at'=>$user_online->created_at,
                    ]);
                }
        } 

        return true;
    }

    public function virtual_clinic(){ //virtual clini=>
        // syncronize virtual clinic table from offline to online
        $virtual_clinic_offline = DB::table('virtual_clinic')->get();
        foreach($virtual_clinic_offline as $virtual_offline){  
            $virtual_offline_count = DB::connection('mysql2')->table('virtual_clinic')->where('vda_id', $virtual_offline->vda_id)->get();
                if(count($virtual_offline_count) > 0){
                    if($virtual_offline->updated_at > $virtual_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('virtual_clinic')->where('vda_id', $virtual_offline->vda_id)->update([      
                            'virtual_clinic_id'=>$virtual_offline->virtual_clinic_id,
                            'doctors_user_id'=>$virtual_offline->doctors_user_id,
                            'secretary_id'=>$virtual_offline->secretary_id,
                            'clinic_id'=>$virtual_offline->clinic_id,
                            'status'=>$virtual_offline->status,
                            'updated_at'=>$virtual_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('virtual_clinic')->where('vda_id', $virtual_offline_count[0]->vda_id)->update([ 
                            'virtual_clinic_id'=>$virtual_offline_count[0]->virtual_clinic_id,
                            'doctors_user_id'=>$virtual_offline_count[0]->doctors_user_id,
                            'secretary_id'=>$virtual_offline_count[0]->secretary_id,
                            'clinic_id'=>$virtual_offline_count[0]->clinic_id,
                            'status'=>$virtual_offline_count[0]->status,
                            'updated_at'=>$virtual_offline_count[0]->updated_at,
                        ]);
                    }
                }
                
                else{
                    DB::connection('mysql2')->table('virtual_clinic')->insert([
                        'vda_id'=> $virtual_offline->vda_id,
                        'virtual_clinic_id'=>$virtual_offline->virtual_clinic_id,
                        'doctors_user_id'=>$virtual_offline->doctors_user_id,
                        'secretary_id'=>$virtual_offline->secretary_id,
                        'clinic_id'=>$virtual_offline->clinic_id,
                        'status'=>$virtual_offline->status,
                        'created_at'=>$virtual_offline->created_at,
                        'updated_at'=>$virtual_offline->updated_at,
                    ]);
                }
        } 
     
        // syncronize virtual clinic table from online to offline 
        $virtual_clinic_online = DB::connection('mysql2')->table('virtual_clinic')->get();  
        foreach($virtual_clinic_online as $virtual_online){
            $virtual_online_count = DB::table('virtual_clinic')->where('vda_id', $virtual_online->vda_id)->get();
                if(count($virtual_online_count) > 0){
                    DB::table('virtual_clinic')->where('vda_id', $virtual_online->vda_id)->update([
                        'virtual_clinic_id'=>$virtual_online->virtual_clinic_id,
                        'doctors_user_id'=>$virtual_online->doctors_user_id,
                        'secretary_id'=>$virtual_online->secretary_id,
                        'clinic_id'=>$virtual_online->clinic_id,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at,
                    ]);
                }else{
                    DB::table('virtual_clinic')->insert([
                        'vda_id'=> $virtual_online->vda_id,
                        'virtual_clinic_id'=>$virtual_online->virtual_clinic_id,
                        'doctors_user_id'=>$virtual_online->doctors_user_id,
                        'secretary_id'=>$virtual_online->secretary_id,
                        'clinic_id'=>$virtual_online->clinic_id,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at,
                    ]);
                }
        } 

        return true;
    }

    public function virtual_clinic_services(){ //virtual clinic services
        // syncronize virtual clinic services table from offline to online
        $virtual_services_offline = DB::table('virtual_clinic_services')->get();
        foreach($virtual_services_offline as $virtual_offline){  
            $virtual_offline_count = DB::connection('mysql2')->table('virtual_clinic_services')->where('vcs_id', $virtual_offline->vcs_id)->get();
                if(count($virtual_offline_count) > 0){
                    if($virtual_offline->updated_at > $virtual_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('virtual_clinic_services')->where('vcs_id', $virtual_offline->vcs_id)->update([      
                            'fees_id'=>$virtual_offline->fees_id,
                            'doctors_user_id'=>$virtual_offline->doctors_user_id,
                            'secretary_id'=>$virtual_offline->secretary_id,
                            'clinic_id'=>$virtual_offline->clinic_id,
                            'service'=>$virtual_offline->service,
                            'amount'=>$virtual_offline->amount,
                            'status'=>$virtual_offline->status,
                            'updated_at'=>$virtual_offline->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('virtual_clinic_services')->where('vcs_id', $virtual_offline_count[0]->vcs_id)->update([
                            'vcs_id'=> $virtual_offline_count[0]->vcs_id,
                            'fees_id'=>$virtual_offline_count[0]->fees_id,
                            'doctors_user_id'=>$virtual_offline_count[0]->doctors_user_id,
                            'secretary_id'=>$virtual_offline_count[0]->secretary_id,
                            'clinic_id'=>$virtual_offline_count[0]->clinic_id,
                            'service'=>$virtual_offline_count[0]->service,
                            'amount'=>$virtual_offline_count[0]->amount,
                            'status'=>$virtual_offline_count[0]->status,
                            'updated_at'=>$virtual_offline_count[0]->updated_at,
                        ]);
                    }
                }
                
                else{
                    DB::connection('mysql2')->table('virtual_clinic_services')->insert([
                        'vcs_id'=> $virtual_offline->vcs_id,
                        'fees_id'=>$virtual_offline->fees_id,
                        'doctors_user_id'=>$virtual_offline->doctors_user_id,
                        'secretary_id'=>$virtual_offline->secretary_id,
                        'clinic_id'=>$virtual_offline->clinic_id,
                        'service'=>$virtual_offline->service,
                        'amount'=>$virtual_offline->amount,
                        'status'=>$virtual_offline->status,
                        'created_at'=>$virtual_offline->created_at,
                        'updated_at'=>$virtual_offline->updated_at,
                    ]);
                }
        } 
     
        // syncronize virtual clinic services table from online to offline 
        $virtual_services_online = DB::connection('mysql2')->table('virtual_clinic_services')->get();  
        foreach($virtual_services_online as $virtual_online){
            $virtual_online_count = DB::table('virtual_clinic_services')->where('vcs_id', $virtual_online->vcs_id)->get();
                if(count($virtual_online_count) > 0){
                    DB::table('virtual_clinic_services')->where('vcs_id', $virtual_online->vcs_id)->update([
                        'fees_id'=>$virtual_online->fees_id,
                        'doctors_user_id'=>$virtual_online->doctors_user_id,
                        'secretary_id'=>$virtual_online->secretary_id,
                        'clinic_id'=>$virtual_online->clinic_id,
                        'service'=>$virtual_online->service,
                        'amount'=>$virtual_online->amount,
                        'status'=>$virtual_online->status,
                        'updated_at'=>$virtual_online->updated_at,
                    ]);
                }else{
                    DB::table('virtual_clinic_services')->insert([
                        'vcs_id'=> $virtual_online->vcs_id,
                        'fees_id'=>$virtual_online->fees_id,
                        'doctors_user_id'=>$virtual_online->doctors_user_id,
                        'secretary_id'=>$virtual_online->secretary_id,
                        'clinic_id'=>$virtual_online->clinic_id,
                        'service'=>$virtual_online->service,
                        'amount'=>$virtual_online->amount,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at,
                    ]);
                }
        } 

        return true;
    } 

    public function virtual_is_online(){ //virtual user online monitoring
        // syncronize virtual is online table from offline to online  
        $virtual_offline = DB::table('virtual_is_online')->get();  
        foreach($virtual_offline as $virtual){  
            $virtual_count = DB::connection('mysql2')->table('virtual_is_online')->where('online_id', $virtual->online_id)->get();
                if(count($virtual_count) > 0){  
                    if($virtual->updated_at > $virtual_count[0]->updated_at){ 
                        DB::connection('mysql2')->table('virtual_is_online')->where('online_id', $virtual->online_id)->update([      
                            'appointment_id'=>$virtual->appointment_id,
                            'patient_id'=>$virtual->patient_id,
                            'patient_webrtc_id'=>$virtual->patient_webrtc_id, 
                            'doctors_id'=>$virtual->doctors_id,
                            'doctors_webrtc_id'=>$virtual->doctors_webrtc_id,
                            'is_patient_online_status'=>$virtual->is_patient_online_status,
                            'updated_at'=>$virtual->updated_at,
                        ]);  
                    } 
                    
                    else{
                        DB::table('virtual_is_online')->where('online_id', $virtual_count[0]->online_id)->update([  
                            'online_id'=> $virtual_count[0]->online_id, 
                            'appointment_id'=>$virtual_count[0]->appointment_id,
                            'patient_id'=>$virtual_count[0]->patient_id,
                            'patient_webrtc_id'=>$virtual_count[0]->patient_webrtc_id, 
                            'doctors_id'=>$virtual_count[0]->doctors_id,
                            'doctors_webrtc_id'=>$virtual_count[0]->doctors_webrtc_id,
                            'is_patient_online_status'=>$virtual_count[0]->is_patient_online_status,
                            'updated_at'=>$virtual_count[0]->updated_at, 
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('virtual_is_online')->insert([
                        'online_id'=> $virtual->online_id,
                        'appointment_id'=>$virtual->appointment_id,
                        'patient_id'=>$virtual->patient_id,
                        'patient_webrtc_id'=>$virtual->patient_webrtc_id, 
                        'doctors_id'=>$virtual->doctors_id,
                        'doctors_webrtc_id'=>$virtual->doctors_webrtc_id,
                        'is_patient_online_status'=>$virtual->is_patient_online_status,
                        'created_at'=>$virtual->created_at,
                        'updated_at'=>$virtual->updated_at,
                    ]);  
                } 
        } 
     
        // syncronize virtual is online table from online to offline 
        $virtual_online = DB::connection('mysql2')->table('virtual_is_online')->get();  
        foreach($virtual_online as $virtual_online){ 
            $virtual_online_count = DB::table('virtual_is_online')->where('online_id', $virtual_online->online_id)->get();
                if(count($virtual_online_count) > 0){
                    DB::table('virtual_is_online')->where('online_id', $virtual_online->online_id)->update([
                        'appointment_id'=>$virtual_online->appointment_id,
                        'patient_id'=>$virtual_online->patient_id,
                        'patient_webrtc_id'=>$virtual_online->patient_webrtc_id,
                        'doctors_id'=>$virtual_online->doctors_id,
                        'doctors_webrtc_id'=>$virtual_online->doctors_webrtc_id,
                        'is_patient_online_status'=>$virtual_online->is_patient_online_status,
                        'updated_at'=>$virtual_online->updated_at,
                    ]);
                }else{
                    DB::table('virtual_is_online')->insert([
                        'online_id'=> $virtual_online->online_id, 
                        'appointment_id'=>$virtual_online->appointment_id,
                        'patient_id'=>$virtual_online->patient_id,
                        'patient_webrtc_id'=>$virtual_online->patient_webrtc_id, 
                        'doctors_id'=>$virtual_online->doctors_id,
                        'doctors_webrtc_id'=>$virtual_online->doctors_webrtc_id,
                        'is_patient_online_status'=>$virtual_online->is_patient_online_status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at,
                    ]);
                }
        } 

        return true;
    } 

}