<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Messages extends Model
{ 
    public static function messages(){ 
        // syncronize messages table from offline to online   
        $msg_offline = DB::table('messages')->get();  
        foreach($msg_offline as $msg_offline){  
            $msg_offline_count = DB::connection('mysql2')->table('messages')->where('message_id', $msg_offline->message_id)->get();
                if(count($msg_offline_count) > 0){ 
                    if($msg_offline->updated_at > $msg_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('messages')->where('message_id', $msg_offline->message_id)->update([    
                            'message_id'=> $msg_offline->message_id, 
                            'name'=>$msg_offline->name,
                            'email'=>$msg_offline->email,
                            'messages'=>$msg_offline->messages,
                            'regarding'=>$msg_offline->regarding,
                            'status'=>$msg_offline->status,
                            'created_at'=>$msg_offline->created_at,
                            'updated_at'=>$msg_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('messages')->where('message_id', $msg_offline_count[0]->message_id)->update([  
                            'message_id'=> $msg_offline_count[0]->message_id, 
                            'name'=>$msg_offline_count[0]->name,
                            'email'=>$msg_offline_count[0]->email,
                            'messages'=>$msg_offline_count[0]->messages,
                            'regarding'=>$msg_offline_count[0]->regarding,
                            'status'=>$msg_offline_count[0]->status,
                            'created_at'=>$msg_offline_count[0]->created_at,
                            'updated_at'=>$msg_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('messages')->insert([ 
                        'message_id'=> $msg_offline->message_id, 
                        'name'=>$msg_offline->name,
                        'email'=>$msg_offline->email,
                        'messages'=>$msg_offline->messages,
                        'regarding'=>$msg_offline->regarding,
                        'status'=>$msg_offline->status,
                        'created_at'=>$msg_offline->created_at,
                        'updated_at'=>$msg_offline->updated_at
                    ]); 
                } 
        }

        // syncronize messages table from online to offline 
        $msg_online = DB::connection('mysql2')->table('messages')->get();  
        foreach($msg_online as $msg_online){  
            $lab_online_count = DB::table('messages')->where('message_id', $msg_online->message_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('messages')->where('message_id', $msg_online->message_id)->update([  
                        'message_id'=> $msg_online->message_id, 
                        'name'=>$msg_online->name,
                        'email'=>$msg_online->email,
                        'messages'=>$msg_online->messages,
                        'regarding'=>$msg_online->regarding,
                        'status'=>$msg_online->status,
                        'created_at'=>$msg_online->created_at,
                        'updated_at'=>$msg_online->updated_at
                    ]); 
                }else{
                    DB::table('messages')->insert([    
                        'message_id'=> $msg_online->message_id, 
                        'name'=>$msg_online->name,
                        'email'=>$msg_online->email,
                        'messages'=>$msg_online->messages,
                        'regarding'=>$msg_online->regarding,
                        'status'=>$msg_online->status,
                        'created_at'=>$msg_online->created_at,
                        'updated_at'=>$msg_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}