<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _User_request_credit extends Model
{ 
    public static function user_request_credit(){
        // syncronize user_request_credit table from offline to online
        $user_offline = DB::table('user_request_credit')->get();  
        foreach($user_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('user_request_credit')->where('urc_id', $user_offline->urc_id)->get();
                if(count($user_offline_count) > 0){ 
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('user_request_credit')->where('urc_id', $user_offline->urc_id)->update([    
                            'urc_id'=> $user_offline->urc_id,
                            'user_id'=>$user_offline->user_id,
                            'request_token'=>$user_offline->request_token,
                            'payment_link'=>$user_offline->payment_link,
                            'process_by'=>$user_offline->process_by,
                            'status'=>$user_offline->status,
                            'created_at'=>$user_offline->created_at,
                            'updated_at'=>$user_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('user_request_credit')->where('urc_id', $user_offline_count[0]->urc_id)->update([  
                            'urc_id'=> $user_offline_count[0]->urc_id,
                            'user_id'=>$user_offline_count[0]->user_id,
                            'request_token'=>$user_offline_count[0]->request_token,
                            'payment_link'=>$user_offline_count[0]->payment_link,
                            'process_by'=>$user_offline_count[0]->process_by,
                            'status'=>$user_offline_count[0]->status,
                            'created_at'=>$user_offline_count[0]->created_at,
                            'updated_at'=>$user_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('user_request_credit')->insert([
                        'urc_id'=> $user_offline->urc_id,
                        'user_id'=>$user_offline->user_id,
                        'request_token'=>$user_offline->request_token,
                        'payment_link'=>$user_offline->payment_link,
                        'process_by'=>$user_offline->process_by,
                        'status'=>$user_offline->status,
                        'created_at'=>$user_offline->created_at,
                        'updated_at'=>$user_offline->updated_at
                    ]); 
                } 
        }

        // syncronize user_request_credit table from online to offline 
        $user_online = DB::connection('mysql2')->table('user_request_credit')->get();
        foreach($user_online as $user_online){  
            $user_online_count = DB::table('user_request_credit')->where('urc_id', $user_online->urc_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('user_request_credit')->where('urc_id', $user_online->urc_id)->update([
                        'urc_id'=> $user_online->urc_id,
                        'user_id'=>$user_online->user_id,
                        'request_token'=>$user_online->request_token,
                        'payment_link'=>$user_online->payment_link,
                        'process_by'=>$user_online->process_by,
                        'status'=>$user_online->status,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                }else{
                    DB::table('user_request_credit')->insert([ 
                        'urc_id'=> $user_online->urc_id,
                        'user_id'=>$user_online->user_id,
                        'request_token'=>$user_online->request_token,
                        'payment_link'=>$user_online->payment_link,
                        'process_by'=>$user_online->process_by,
                        'status'=>$user_online->status,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}