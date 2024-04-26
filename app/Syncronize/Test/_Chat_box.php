<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Chat_box extends Model
{ 
    public static function chat_box(){ 
        // syncronize chat_box table from offline to online  
        $chat_offline = DB::table('chat_box')->get();  
        foreach($chat_offline as $chat_offline){  
            $chat_offline_count = DB::connection('mysql2')->table('chat_box')->where('chat_id', $chat_offline->chat_id)->get();
                if(count($chat_offline_count) > 0){
                    if($chat_offline->updated_at > $chat_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('chat_box')->where('chat_id', $chat_offline->chat_id)->update([      
                            'chat_id'=>$chat_offline->chat_id,
                            'senders_id'=>$chat_offline->senders_id,
                            'recievers_id'=>$chat_offline->recievers_id,
                            'message'=>$chat_offline->message, 
                            'is_read'=>$chat_offline->is_read,
                            'is_typing'=>$chat_offline->is_typing, 
                            'status'=>$chat_offline->status,
                            'created_at'=>$chat_offline->created_at,
                            'updated_at'=>$chat_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('chat_box')->where('chat_id', $chat_offline_count[0]->chat_id)->update([  
                            'chat_id'=>$chat_offline_count[0]->chat_id,
                            'senders_id'=>$chat_offline_count[0]->senders_id,
                            'recievers_id'=>$chat_offline_count[0]->recievers_id,
                            'message'=>$chat_offline_count[0]->message, 
                            'is_read'=>$chat_offline_count[0]->is_read,
                            'is_typing'=>$chat_offline_count[0]->is_typing, 
                            'status'=>$chat_offline_count[0]->status,
                            'created_at'=>$chat_offline_count[0]->created_at,
                            'updated_at'=>$chat_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('chat_box')->insert([
                        'chat_id'=>$chat_offline->chat_id,
                        'senders_id'=>$chat_offline->senders_id,
                        'recievers_id'=>$chat_offline->recievers_id,
                        'message'=>$chat_offline->message, 
                        'is_read'=>$chat_offline->is_read,
                        'is_typing'=>$chat_offline->is_typing, 
                        'status'=>$chat_offline->status,
                        'created_at'=>$chat_offline->created_at,
                        'updated_at'=>$chat_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize chat_box table from online to offline
        $chat_online = DB::connection('mysql2')->table('chat_box')->get();  
        foreach($chat_online as $chat_online){  
            $chat_online_count = DB::table('chat_box')->where('chat_id', $chat_online->chat_id)->get();
                if(count($chat_online_count) > 0){
                    DB::table('chat_box')->where('chat_id', $chat_online->chat_id)->update([   
                        'chat_id'=>$chat_online->chat_id,
                        'senders_id'=>$chat_online->senders_id,
                        'recievers_id'=>$chat_online->recievers_id,
                        'message'=>$chat_online->message, 
                        'is_read'=>$chat_online->is_read,
                        'is_typing'=>$chat_online->is_typing, 
                        'status'=>$chat_online->status,
                        'created_at'=>$chat_online->created_at,
                        'updated_at'=>$chat_online->updated_at
                    ]); 
                }else{
                    DB::table('chat_box')->insert([
                        'chat_id'=>$chat_online->chat_id,
                        'senders_id'=>$chat_online->senders_id,
                        'recievers_id'=>$chat_online->recievers_id,
                        'message'=>$chat_online->message, 
                        'is_read'=>$chat_online->is_read,
                        'is_typing'=>$chat_online->is_typing, 
                        'status'=>$chat_online->status,
                        'created_at'=>$chat_online->created_at,
                        'updated_at'=>$chat_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}