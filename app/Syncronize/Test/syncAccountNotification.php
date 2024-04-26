<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncAccountNotification extends Model
{ 
    public static function syncAccountNotification(){
        // syncronize users table from offline to online 
        $offline = DB::table('account_notification')->get();  
        foreach($offline as $offline){  
            $offline_online = DB::connection('mysql2')->table('account_notification')->where('user_id', $offline->user_id)->get();
                if(count($offline_online) > 0){ 
                        
                    if($offline->updated_at > $offline_online[0]->updated_at){  
                        DB::connection('mysql2')->table('account_notification')->where('user_id', $offline->user_id)->update([ 
                            'notification'=>$offline->notification,
                            'is_read'=>$offline->is_read,
                            'is_read_date'=>$offline->is_read_date,
                            'status'=>$offline->status, 
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at,
                        ]);
                    } 
                    
                    else{
                        DB::table('account_notification')->where('user_id', $offline_online[0]->user_id)->update([ 
                            'notification'=>$offline_online[0]->notification,
                            'is_read'=>$offline_online[0]->is_read,
                            'is_read_date'=>$offline_online[0]->is_read_date,
                            'status'=>$offline_online[0]->status, 
                            'created_at'=>$offline_online[0]->created_at,
                            'updated_at'=>$offline_online[0]->updated_at,
                        ]);
                    }

                }else{
                    DB::connection('mysql2')->table('account_notification')->insert([
                        'user_id'=>$offline->user_id,
                        'notification'=>$offline->notification,
                        'is_read'=>$offline->is_read,
                        'is_read_date'=>$offline->is_read_date,
                        'status'=>$offline->status, 
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at,
                    ]); 
                } 
        } 

        // syncronize account_notification table from online to offline 
        $online = DB::connection('mysql2')->table('account_notification')->get();  
        foreach($online as $online){  
            $online_online = DB::table('account_notification')->where('user_id', $online->user_id)->get();
                if(count($online_online) > 0){
                    DB::table('account_notification')->where('user_id', $online->user_id)->update([
                        'notification'=>$online->notification,
                        'is_read'=>$online->is_read,
                        'is_read_date'=>$online->is_read_date,
                        'status'=>$online->status, 
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at,
                    ]);
     
                }else{
                    DB::table('account_notification')->insert([
                        'user_id'=>$online->user_id,
                        'notification'=>$online->notification,
                        'is_read'=>$online->is_read,
                        'is_read_date'=>$online->is_read_date,
                        'status'=>$online->status, 
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at,
                    ]); 
                } 
        }
        return true;
    }
}