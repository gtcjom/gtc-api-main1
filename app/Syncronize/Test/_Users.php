<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Users extends Model
{ 
    public static function users(){
        // syncronize users table from offline to online
        $user_offline = DB::table('users')->get();  
        foreach($user_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('users')->where('user_id', $user_offline->user_id)->get();
                if(count($user_offline_count) > 0){ 
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('users')->where('user_id', $user_offline->user_id)->update([    
                            'user_id'=> $user_offline->user_id,
                            'username'=>$user_offline->username,
                            'password'=>$user_offline->password,
                            'type'=>$user_offline->type,
                            'email'=>$user_offline->email,
                            'is_verify'=>$user_offline->is_verify,
                            'is_confirm'=>$user_offline->is_confirm,
                            'manage_by'=>$user_offline->manage_by,
                            'remember_token'=>$user_offline->remember_token,
                            'is_disable'=>$user_offline->is_disable,
                            'api_token'=>$user_offline->api_token,
                            'is_disable_msg'=>$user_offline->is_disable_msg,
                            'created_at'=>$user_offline->created_at,
                            'updated_at'=>$user_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('users')->where('user_id', $user_offline_count[0]->user_id)->update([  
                            'user_id'=> $user_offline_count[0]->user_id,
                            'username'=>$user_offline_count[0]->username,
                            'password'=>$user_offline_count[0]->password,
                            'type'=>$user_offline_count[0]->type,
                            'email'=>$user_offline_count[0]->email,
                            'is_verify'=>$user_offline_count[0]->is_verify,
                            'is_confirm'=>$user_offline_count[0]->is_confirm,
                            'manage_by'=>$user_offline_count[0]->manage_by,
                            'remember_token'=>$user_offline_count[0]->remember_token,
                            'is_disable'=>$user_offline_count[0]->is_disable,
                            'api_token'=>$user_offline_count[0]->api_token,
                            'is_disable_msg'=>$user_offline_count[0]->is_disable_msg,
                            'created_at'=>$user_offline_count[0]->created_at,
                            'updated_at'=>$user_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('users')->insert([
                        'user_id'=> $user_offline->user_id,
                        'username'=>$user_offline->username,
                        'password'=>$user_offline->password,
                        'type'=>$user_offline->type,
                        'email'=>$user_offline->email,
                        'is_verify'=>$user_offline->is_verify,
                        'is_confirm'=>$user_offline->is_confirm,
                        'manage_by'=>$user_offline->manage_by,
                        'remember_token'=>$user_offline->remember_token,
                        'is_disable'=>$user_offline->is_disable,
                        'api_token'=>$user_offline->api_token,
                        'is_disable_msg'=>$user_offline->is_disable_msg,
                        'created_at'=>$user_offline->created_at,
                        'updated_at'=>$user_offline->updated_at
                    ]); 
                } 
        }

        // syncronize users table from online to offline 
        $user_online = DB::connection('mysql2')->table('users')->get();
        foreach($user_online as $user_online){  
            $user_online_count = DB::table('users')->where('user_id', $user_online->user_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('users')->where('user_id', $user_online->user_id)->update([
                        'user_id'=> $user_online->user_id,
                        'username'=>$user_online->username,
                        'password'=>$user_online->password,
                        'type'=>$user_online->type,
                        'email'=>$user_online->email,
                        'is_verify'=>$user_online->is_verify,
                        'is_confirm'=>$user_online->is_confirm,
                        'manage_by'=>$user_online->manage_by,
                        'remember_token'=>$user_online->remember_token,
                        'is_disable'=>$user_online->is_disable,
                        'api_token'=>$user_online->api_token,
                        'is_disable_msg'=>$user_online->is_disable_msg,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                }else{
                    DB::table('users')->insert([ 
                        'user_id'=> $user_online->user_id,
                        'username'=>$user_online->username,
                        'password'=>$user_online->password,
                        'type'=>$user_online->type,
                        'email'=>$user_online->email,
                        'is_verify'=>$user_online->is_verify,
                        'is_confirm'=>$user_online->is_confirm,
                        'manage_by'=>$user_online->manage_by,
                        'remember_token'=>$user_online->remember_token,
                        'is_disable'=>$user_online->is_disable,
                        'api_token'=>$user_online->api_token,
                        'is_disable_msg'=>$user_online->is_disable_msg,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}