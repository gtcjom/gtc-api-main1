<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Users_otp extends Model
{ 
    public static function users_otp(){
        // syncronize users_otp table from offline to online
        $user_offline = DB::table('users_otp')->get();  
        foreach($user_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('users_otp')->where('otp_id', $user_offline->otp_id)->get();
                if(count($user_offline_count) > 0){ 
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('users_otp')->where('otp_id', $user_offline->otp_id)->update([    
                            'otp_id'=> $user_offline->otp_id,
                            'user_id'=>$user_offline->user_id,
                            'otp'=>$user_offline->otp,
                            'created_at'=>$user_offline->created_at,
                            'updated_at'=>$user_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('users_otp')->where('otp_id', $user_offline_count[0]->otp_id)->update([  
                            'otp_id'=> $user_offline_count[0]->otp_id,
                            'user_id'=>$user_offline_count[0]->user_id,
                            'otp'=>$user_offline_count[0]->otp,
                            'created_at'=>$user_offline_count[0]->created_at,
                            'updated_at'=>$user_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('users_otp')->insert([
                        'otp_id'=> $user_offline->otp_id,
                        'user_id'=>$user_offline->user_id,
                        'otp'=>$user_offline->otp,
                        'created_at'=>$user_offline->created_at,
                        'updated_at'=>$user_offline->updated_at
                    ]); 
                } 
        }

        // syncronize users_otp table from online to offline 
        $user_online = DB::connection('mysql2')->table('users_otp')->get();
        foreach($user_online as $user_online){  
            $user_online_count = DB::table('users_otp')->where('otp_id', $user_online->otp_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('users_otp')->where('otp_id', $user_online->otp_id)->update([
                        'otp_id'=> $user_online->otp_id,
                        'user_id'=>$user_online->user_id,
                        'otp'=>$user_online->otp,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                }else{
                    DB::table('users_otp')->insert([ 
                        'otp_id'=> $user_online->otp_id,
                        'user_id'=>$user_online->user_id,
                        'otp'=>$user_online->otp,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}