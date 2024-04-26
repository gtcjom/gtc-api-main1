<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Users_unemail_accounts extends Model
{ 
    public static function users_unemail_accounts(){
        // syncronize users_unemail_accounts table from offline to online
        $user_offline = DB::table('users_unemail_accounts')->get();  
        foreach($user_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('users_unemail_accounts')->where('unemail_id', $user_offline->unemail_id)->get();
                if(count($user_offline_count) > 0){ 
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('users_unemail_accounts')->where('unemail_id', $user_offline->unemail_id)->update([    
                            'unemail_id'=> $user_offline->unemail_id,
                            'user_id'=>$user_offline->user_id,
                            'username'=>$user_offline->username,
                            'password'=>$user_offline->password,
                            'status'=>$user_offline->status,
                            'updated_at'=>$user_offline->updated_at,
                            'created_at'=>$user_offline->created_at
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
                            'created_at'=>$user_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('users_unemail_accounts')->insert([
                        'unemail_id'=> $user_offline->unemail_id,
                        'user_id'=>$user_offline->user_id,
                        'username'=>$user_offline->username,
                        'password'=>$user_offline->password,
                        'status'=>$user_offline->status,
                        'updated_at'=>$user_offline->updated_at,
                        'created_at'=>$user_offline->created_at
                    ]); 
                } 
        }

        // syncronize users_unemail_accounts table from online to offline 
        $user_online = DB::connection('mysql2')->table('users_unemail_accounts')->get();
        foreach($user_online as $user_online){  
            $user_online_count = DB::table('users_unemail_accounts')->where('unemail_id', $user_online->unemail_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('users_unemail_accounts')->where('unemail_id', $user_online->unemail_id)->update([
                        'unemail_id'=> $user_online->unemail_id,
                        'user_id'=>$user_online->user_id,
                        'username'=>$user_online->username,
                        'password'=>$user_online->password,
                        'status'=>$user_online->status,
                        'updated_at'=>$user_online->updated_at,
                        'created_at'=>$user_online->created_at
                    ]); 
                }else{
                    DB::table('users_unemail_accounts')->insert([ 
                        'unemail_id'=> $user_online->unemail_id,
                        'user_id'=>$user_online->user_id,
                        'username'=>$user_online->username,
                        'password'=>$user_online->password,
                        'status'=>$user_online->status,
                        'updated_at'=>$user_online->updated_at,
                        'created_at'=>$user_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}