<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Message_from_users extends Model
{ 
    public static function message_from_users(){ 
        // syncronize message_from_users table from offline to online   
        $msg_offline = DB::table('message_from_users')->get();  
        foreach($msg_offline as $msg_offline){  
            $msg_offline_count = DB::connection('mysql2')->table('message_from_users')->where('msg_id', $msg_offline->msg_id)->get();
                if(count($msg_offline_count) > 0){ 
                    if($msg_offline->updated_at > $msg_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('message_from_users')->where('msg_id', $msg_offline->msg_id)->update([    
                            'msg_id'=> $msg_offline->msg_id, 
                            'fullname'=>$msg_offline->fullname,
                            'email'=>$msg_offline->email,
                            'msg'=>$msg_offline->msg,
                            'created_at'=>$msg_offline->created_at,
                            'update_at'=>$msg_offline->update_at
                        ]);
                    } 
                    else{
                        DB::table('message_from_users')->where('msg_id', $msg_offline_count[0]->msg_id)->update([  
                            'msg_id'=> $msg_offline_count[0]->msg_id, 
                            'fullname'=>$msg_offline_count[0]->fullname,
                            'email'=>$msg_offline_count[0]->email,
                            'msg'=>$msg_offline_count[0]->msg,
                            'created_at'=>$msg_offline_count[0]->created_at,
                            'update_at'=>$msg_offline_count[0]->update_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('message_from_users')->insert([ 
                        'msg_id'=> $msg_offline->msg_id, 
                        'fullname'=>$msg_offline->fullname,
                        'email'=>$msg_offline->email,
                        'msg'=>$msg_offline->msg,
                        'created_at'=>$msg_offline->created_at,
                        'update_at'=>$msg_offline->update_at
                    ]); 
                } 
        }

        // syncronize message_from_users table from online to offline 
        $msg_online = DB::connection('mysql2')->table('message_from_users')->get();  
        foreach($msg_online as $msg_online){  
            $lab_online_count = DB::table('message_from_users')->where('msg_id', $msg_online->msg_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('message_from_users')->where('msg_id', $msg_online->msg_id)->update([  
                        'msg_id'=> $msg_online->msg_id, 
                        'fullname'=>$msg_online->fullname,
                        'email'=>$msg_online->email,
                        'msg'=>$msg_online->msg,
                        'created_at'=>$msg_online->created_at,
                        'update_at'=>$msg_online->update_at
                    ]); 
                }else{
                    DB::table('message_from_users')->insert([    
                        'msg_id'=> $msg_online->msg_id, 
                        'fullname'=>$msg_online->fullname,
                        'email'=>$msg_online->email,
                        'msg'=>$msg_online->msg,
                        'created_at'=>$msg_online->created_at,
                        'update_at'=>$msg_online->update_at
                    ]); 
                } 
        }   

        return true;
    } 
}