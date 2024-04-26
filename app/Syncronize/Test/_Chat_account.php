<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Chat_account extends Model
{ 
    public static function chat_account(){ 
        // syncronize chat_account table from offline to online  
        $chat_offline = DB::table('chat_account')->get();  
        foreach($chat_offline as $chat_offline){  
            $chat_offline_count = DB::connection('mysql2')->table('chat_account')->where('chat_account_id', $chat_offline->chat_account_id)->get();
                if(count($chat_offline_count) > 0){
                    if($chat_offline->updated_at > $chat_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('chat_account')->where('chat_account_id', $chat_offline->chat_account_id)->update([      
                            'chat_account_id'=>$chat_offline->chat_account_id,
                            'user_id'=>$chat_offline->user_id,
                            'name'=>$chat_offline->name,
                            'type'=>$chat_offline->type, 
                            'manage_by'=>$chat_offline->manage_by,
                            'status'=>$chat_offline->status, 
                            'created_at'=>$chat_offline->created_at,
                            'updated_at'=>$chat_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('chat_account')->where('chat_account_id', $chat_offline_count[0]->chat_account_id)->update([  
                            'chat_account_id'=>$chat_offline_count[0]->chat_account_id,
                            'user_id'=>$chat_offline_count[0]->user_id,
                            'name'=>$chat_offline_count[0]->name,
                            'type'=>$chat_offline_count[0]->type, 
                            'manage_by'=>$chat_offline_count[0]->manage_by,
                            'status'=>$chat_offline_count[0]->status, 
                            'created_at'=>$chat_offline_count[0]->created_at,
                            'updated_at'=>$chat_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('chat_account')->insert([
                        'chat_account_id'=>$chat_offline->chat_account_id,
                        'user_id'=>$chat_offline->user_id,
                        'name'=>$chat_offline->name,
                        'type'=>$chat_offline->type, 
                        'manage_by'=>$chat_offline->manage_by,
                        'status'=>$chat_offline->status, 
                        'created_at'=>$chat_offline->created_at,
                        'updated_at'=>$chat_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize chat_account table from online to offline
        $chat_online = DB::connection('mysql2')->table('chat_account')->get();  
        foreach($chat_online as $chat_online){  
            $chat_online_count = DB::table('chat_account')->where('chat_account_id', $chat_online->chat_account_id)->get();
                if(count($chat_online_count) > 0){
                    DB::table('chat_account')->where('chat_account_id', $chat_online->chat_account_id)->update([   
                        'chat_account_id'=>$chat_online->chat_account_id,
                        'user_id'=>$chat_online->user_id,
                        'name'=>$chat_online->name,
                        'type'=>$chat_online->type, 
                        'manage_by'=>$chat_online->manage_by,
                        'status'=>$chat_online->status, 
                        'created_at'=>$chat_online->created_at,
                        'updated_at'=>$chat_online->updated_at
                    ]); 
                }else{
                    DB::table('chat_account')->insert([
                        'chat_account_id'=>$chat_online->chat_account_id,
                        'user_id'=>$chat_online->user_id,
                        'name'=>$chat_online->name,
                        'type'=>$chat_online->type, 
                        'manage_by'=>$chat_online->manage_by,
                        'status'=>$chat_online->status, 
                        'created_at'=>$chat_online->created_at,
                        'updated_at'=>$chat_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}