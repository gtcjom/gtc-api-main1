<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Users_geolocation extends Model
{ 
    public static function users_geolocation(){
        // syncronize users_geolocation table from offline to online
        $user_offline = DB::table('users_geolocation')->get();  
        foreach($user_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('users_geolocation')->where('geo_id', $user_offline->geo_id)->get();
                if(count($user_offline_count) > 0){ 
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('users_geolocation')->where('geo_id', $user_offline->geo_id)->update([    
                            'geo_id'=> $user_offline->geo_id,
                            'user_id'=>$user_offline->user_id,
                            'latitude'=>$user_offline->latitude,
                            'longitude'=>$user_offline->longitude,
                            'status'=>$user_offline->status,
                            'updated_at'=>$user_offline->updated_at,
                            'created_at'=>$user_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('users_geolocation')->where('geo_id', $user_offline_count[0]->geo_id)->update([  
                            'geo_id'=> $user_offline_count[0]->geo_id,
                            'user_id'=>$user_offline_count[0]->user_id,
                            'latitude'=>$user_offline_count[0]->latitude,
                            'longitude'=>$user_offline_count[0]->longitude,
                            'status'=>$user_offline_count[0]->status,
                            'updated_at'=>$user_offline_count[0]->updated_at,
                            'created_at'=>$user_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('users_geolocation')->insert([
                        'geo_id'=> $user_offline->geo_id,
                        'user_id'=>$user_offline->user_id,
                        'latitude'=>$user_offline->latitude,
                        'longitude'=>$user_offline->longitude,
                        'status'=>$user_offline->status,
                        'updated_at'=>$user_offline->updated_at,
                        'created_at'=>$user_offline->created_at
                    ]); 
                } 
        }

        // syncronize users_geolocation table from online to offline 
        $user_online = DB::connection('mysql2')->table('users_geolocation')->get();
        foreach($user_online as $user_online){  
            $user_online_count = DB::table('users_geolocation')->where('geo_id', $user_online->geo_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('users_geolocation')->where('geo_id', $user_online->geo_id)->update([
                        'geo_id'=> $user_online->geo_id,
                        'user_id'=>$user_online->user_id,
                        'latitude'=>$user_online->latitude,
                        'longitude'=>$user_online->longitude,
                        'status'=>$user_online->status,
                        'updated_at'=>$user_online->updated_at,
                        'created_at'=>$user_online->created_at
                    ]); 
                }else{
                    DB::table('users_geolocation')->insert([ 
                        'geo_id'=> $user_online->geo_id,
                        'user_id'=>$user_online->user_id,
                        'latitude'=>$user_online->latitude,
                        'longitude'=>$user_online->longitude,
                        'status'=>$user_online->status,
                        'updated_at'=>$user_online->updated_at,
                        'created_at'=>$user_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}