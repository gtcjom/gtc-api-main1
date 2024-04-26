<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncActiveUsers extends Model
{ 
    public static function syncActiveUsers(){
        // syncronize users table from offline to online 
        $offline = DB::table('active_users')->get();  
        foreach($offline as $offline){  
            $offline_online = DB::connection('mysql2')->table('active_users')->where('active_id', $offline->active_id)->get();
                if(count($offline_online) > 0){ 
                    if($offline->updated_at > $offline_online[0]->updated_at){  
                        DB::connection('mysql2')->table('active_users')->where('active_id', $offline->active_id)->update([ 
                            'active_id'=>$offline->active_id,
                            'user_id'=>$offline->user_id,
                            'token'=>$offline->token,
                            'type'=>$offline->type, 
                            'manage_by'=>$offline->manage_by,
                            'status'=>$offline->status,
                            'updated_at'=>$offline->updated_at,
                            'created_at'=>$offline->created_at
                        ]);
                    } 
                    
                    else{
                        DB::table('active_users')->where('active_id', $offline_online[0]->active_id)->update([ 
                            'active_id'=>$offline_online[0]->active_id,
                            'user_id'=>$offline_online[0]->user_id,
                            'token'=>$offline_online[0]->token,
                            'type'=>$offline_online[0]->type, 
                            'manage_by'=>$offline_online[0]->manage_by,
                            'status'=>$offline_online[0]->status,
                            'updated_at'=>$offline_online[0]->updated_at,
                            'created_at'=>$offline_online[0]->created_at
                        ]);
                    }

                }else{
                    DB::connection('mysql2')->table('active_users')->insert([
                        'active_id'=>$offline->active_id,
                        'user_id'=>$offline->user_id,
                        'token'=>$offline->token,
                        'type'=>$offline->type, 
                        'manage_by'=>$offline->manage_by,
                        'status'=>$offline->status,
                        'updated_at'=>$offline->updated_at,
                        'created_at'=>$offline->created_at
                    ]); 
                } 
        } 

        // syncronize active_users table from online to offline 
        $online = DB::connection('mysql2')->table('active_users')->get();  
        foreach($online as $online){  
            $online_online = DB::table('active_users')->where('active_id', $online->active_id)->get();
                if(count($online_online) > 0){
                    DB::table('active_users')->where('active_id', $online->active_id)->update([
                        'active_id'=>$online->active_id,
                        'user_id'=>$online->user_id,
                        'token'=>$online->token,
                        'type'=>$online->type, 
                        'manage_by'=>$online->manage_by,
                        'status'=>$online->status,
                        'updated_at'=>$online->updated_at,
                        'created_at'=>$online->created_at
                    ]);
     
                }else{
                    DB::table('active_users')->insert([
                        'active_id'=>$online->active_id,
                        'user_id'=>$online->user_id,
                        'token'=>$online->token,
                        'type'=>$online->type, 
                        'manage_by'=>$online->manage_by,
                        'status'=>$online->status,
                        'updated_at'=>$online->updated_at,
                        'created_at'=>$online->created_at
                    ]); 
                } 
        }
        return true;
    }
}