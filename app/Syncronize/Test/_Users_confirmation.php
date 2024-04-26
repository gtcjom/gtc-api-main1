<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Users_confirmation extends Model
{ 
    public static function users_confirmation(){
        // syncronize users_confirmation table from offline to online
        $user_offline = DB::table('users_confirmation')->get();  
        foreach($user_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('users_confirmation')->where('user_conf_id', $user_offline->user_conf_id)->get();
                if(count($user_offline_count) > 0){ 
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('users_confirmation')->where('user_conf_id', $user_offline->user_conf_id)->update([    
                            'user_conf_id'=> $user_offline->user_conf_id,
                            'user_id'=>$user_offline->user_id,
                            'email'=>$user_offline->email,
                            'token'=>$user_offline->token,
                            'created_at'=>$user_offline->created_at,
                            'updated_at'=>$user_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('users_confirmation')->where('user_conf_id', $user_offline_count[0]->user_conf_id)->update([  
                            'user_conf_id'=> $user_offline_count[0]->user_conf_id,
                            'user_id'=>$user_offline_count[0]->user_id,
                            'email'=>$user_offline_count[0]->email,
                            'token'=>$user_offline_count[0]->token,
                            'created_at'=>$user_offline_count[0]->created_at,
                            'updated_at'=>$user_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('users_confirmation')->insert([
                        'user_conf_id'=> $user_offline->user_conf_id,
                        'user_id'=>$user_offline->user_id,
                        'email'=>$user_offline->email,
                        'token'=>$user_offline->token,
                        'created_at'=>$user_offline->created_at,
                        'updated_at'=>$user_offline->updated_at
                    ]); 
                } 
        }

        // syncronize users_confirmation table from online to offline 
        $user_online = DB::connection('mysql2')->table('users_confirmation')->get();
        foreach($user_online as $user_online){  
            $user_online_count = DB::table('users_confirmation')->where('user_conf_id', $user_online->user_conf_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('users_confirmation')->where('user_conf_id', $user_online->user_conf_id)->update([
                        'user_conf_id'=> $user_online->user_conf_id,
                        'user_id'=>$user_online->user_id,
                        'email'=>$user_online->email,
                        'token'=>$user_online->token,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                }else{
                    DB::table('users_confirmation')->insert([ 
                        'user_conf_id'=> $user_online->user_conf_id,
                        'user_id'=>$user_online->user_id,
                        'email'=>$user_online->email,
                        'token'=>$user_online->token,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}