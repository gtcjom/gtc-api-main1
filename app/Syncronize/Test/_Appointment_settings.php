<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Appointment_settings extends Model
{ 
    public static function syncronize_appointment_settingsTable(){
        // syncronize appointment_settings table from offline to online 
        $as_offline = DB::table('appointment_settings')->get();  
        foreach($as_offline as $as){  
            $as_count = DB::connection('mysql2')->table('appointment_settings')->where('app_settings_id', $as->app_settings_id)->get();
                if(count($as_count) > 0){ 
                    if($as->updated_at > $as_count[0]->updated_at){  
                        DB::connection('mysql2')->table('appointment_settings')->where('app_settings_id', $as->app_settings_id)->update([  
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
                    
                    else{
                        DB::table('appointment_settings')->where('app_settings_id', $as_count[0]->app_settings_id)->update([  
                            'app_settings_id'=>$as_count[0]->app_settings_id,
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
                        'app_settings_id'=>$as_online->app_settings_id,
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
}