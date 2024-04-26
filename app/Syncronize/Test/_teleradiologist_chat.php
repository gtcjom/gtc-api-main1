<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _teleradiologist_chat extends Model
{ 
    public static function teleradiologist_chat(){
        // syncronize teleradiologist_chat table from offline to online
        $telerad_offline = DB::table('teleradiologist_chat')->get();  
        foreach($telerad_offline as $telerad_offline){  
            $telerad_offline_count = DB::connection('mysql2')->table('teleradiologist_chat')->where('chat_id', $telerad_offline->chat_id)->get();
                if(count($telerad_offline_count) > 0){ 
                    if($telerad_offline->updated_at > $telerad_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('teleradiologist_chat')->where('chat_id', $telerad_offline->chat_id)->update([    
                            'chat_id'=> $telerad_offline->chat_id,
                            'sender_user_id'=>$telerad_offline->sender_user_id,
                            'receiver_user_id'=>$telerad_offline->receiver_user_id,
                            'message'=>$telerad_offline->message,
                            'is_viewed'=>$telerad_offline->is_viewed,
                            'created_at'=>$telerad_offline->created_at,
                            'updated_at'=>$telerad_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('teleradiologist_chat')->where('chat_id', $telerad_offline_count[0]->chat_id)->update([  
                            'chat_id'=> $telerad_offline_count[0]->chat_id,
                            'sender_user_id'=>$telerad_offline_count[0]->sender_user_id,
                            'receiver_user_id'=>$telerad_offline_count[0]->receiver_user_id,
                            'message'=>$telerad_offline_count[0]->message,
                            'is_viewed'=>$telerad_offline_count[0]->is_viewed,
                            'created_at'=>$telerad_offline_count[0]->created_at,
                            'updated_at'=>$telerad_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('teleradiologist_chat')->insert([
                        'chat_id'=> $telerad_offline->chat_id,
                        'sender_user_id'=>$telerad_offline->sender_user_id,
                        'receiver_user_id'=>$telerad_offline->receiver_user_id,
                        'message'=>$telerad_offline->message,
                        'is_viewed'=>$telerad_offline->is_viewed,
                        'created_at'=>$telerad_offline->created_at,
                        'updated_at'=>$telerad_offline->updated_at
                    ]); 
                } 
        }

        // syncronize teleradiologist_chat table from online to offline 
        $telerad_online = DB::connection('mysql2')->table('teleradiologist_chat')->get();
        foreach($telerad_online as $telerad_online){  
            $telerad_online_count = DB::table('teleradiologist_chat')->where('chat_id', $telerad_online->chat_id)->get();
                if(count($telerad_online_count) > 0){
                    DB::table('teleradiologist_chat')->where('chat_id', $telerad_online->chat_id)->update([
                        'chat_id'=> $telerad_online->chat_id,
                        'sender_user_id'=>$telerad_online->sender_user_id,
                        'receiver_user_id'=>$telerad_online->receiver_user_id,
                        'message'=>$telerad_online->message,
                        'is_viewed'=>$telerad_online->is_viewed,
                        'created_at'=>$telerad_online->created_at,
                        'updated_at'=>$telerad_online->updated_at
                    ]); 
                }else{
                    DB::table('teleradiologist_chat')->insert([ 
                        'chat_id'=> $telerad_online->chat_id,
                        'sender_user_id'=>$telerad_online->sender_user_id,
                        'receiver_user_id'=>$telerad_online->receiver_user_id,
                        'message'=>$telerad_online->message,
                        'is_viewed'=>$telerad_online->is_viewed,
                        'created_at'=>$telerad_online->created_at,
                        'updated_at'=>$telerad_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}