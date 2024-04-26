<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Users_subscription extends Model
{ 
    public static function users_subscription(){
        // syncronize users_subscription table from offline to online
        $user_offline = DB::table('users_subscription')->get();  
        foreach($user_offline as $user_offline){  
            $user_offline_count = DB::connection('mysql2')->table('users_subscription')->where('subscription_id', $user_offline->subscription_id)->get();
                if(count($user_offline_count) > 0){ 
                    if($user_offline->updated_at > $user_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('users_subscription')->where('subscription_id', $user_offline->subscription_id)->update([    
                            'subscription_id'=> $user_offline->subscription_id,
                            'user_id'=>$user_offline->user_id,
                            'subscription'=>$user_offline->subscription,
                            'subscription_length_month'=>$user_offline->subscription_length_month,
                            'subscription_amount'=>$user_offline->subscription_amount,
                            'subscription_status'=>$user_offline->subscription_status,
                            'is_processby'=>$user_offline->is_processby,
                            'is_approvedby'=>$user_offline->is_approvedby,
                            'payment_link'=>$user_offline->payment_link,
                            'subscription_started'=>$user_offline->subscription_started,
                            'subscription_end'=>$user_offline->subscription_end,
                            'status'=>$user_offline->status,
                            'created_at'=>$user_offline->created_at,
                            'updated_at'=>$user_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('users_subscription')->where('subscription_id', $user_offline_count[0]->subscription_id)->update([  
                            'subscription_id'=> $user_offline_count[0]->subscription_id,
                            'user_id'=>$user_offline_count[0]->user_id,
                            'subscription'=>$user_offline_count[0]->subscription,
                            'subscription_length_month'=>$user_offline_count[0]->subscription_length_month,
                            'subscription_amount'=>$user_offline_count[0]->subscription_amount,
                            'subscription_status'=>$user_offline_count[0]->subscription_status,
                            'is_processby'=>$user_offline_count[0]->is_processby,
                            'is_approvedby'=>$user_offline_count[0]->is_approvedby,
                            'payment_link'=>$user_offline_count[0]->payment_link,
                            'subscription_started'=>$user_offline_count[0]->subscription_started,
                            'subscription_end'=>$user_offline_count[0]->subscription_end,
                            'status'=>$user_offline_count[0]->status,
                            'created_at'=>$user_offline_count[0]->created_at,
                            'updated_at'=>$user_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('users_subscription')->insert([
                        'subscription_id'=> $user_offline->subscription_id,
                        'user_id'=>$user_offline->user_id,
                        'subscription'=>$user_offline->subscription,
                        'subscription_length_month'=>$user_offline->subscription_length_month,
                        'subscription_amount'=>$user_offline->subscription_amount,
                        'subscription_status'=>$user_offline->subscription_status,
                        'is_processby'=>$user_offline->is_processby,
                        'is_approvedby'=>$user_offline->is_approvedby,
                        'payment_link'=>$user_offline->payment_link,
                        'subscription_started'=>$user_offline->subscription_started,
                        'subscription_end'=>$user_offline->subscription_end,
                        'status'=>$user_offline->status,
                        'created_at'=>$user_offline->created_at,
                        'updated_at'=>$user_offline->updated_at
                    ]); 
                } 
        }

        // syncronize users_subscription table from online to offline 
        $user_online = DB::connection('mysql2')->table('users_subscription')->get();
        foreach($user_online as $user_online){  
            $user_online_count = DB::table('users_subscription')->where('subscription_id', $user_online->subscription_id)->get();
                if(count($user_online_count) > 0){
                    DB::table('users_subscription')->where('subscription_id', $user_online->subscription_id)->update([
                        'subscription_id'=> $user_online->subscription_id,
                        'user_id'=>$user_online->user_id,
                        'subscription'=>$user_online->subscription,
                        'subscription_length_month'=>$user_online->subscription_length_month,
                        'subscription_amount'=>$user_online->subscription_amount,
                        'subscription_status'=>$user_online->subscription_status,
                        'is_processby'=>$user_online->is_processby,
                        'is_approvedby'=>$user_online->is_approvedby,
                        'payment_link'=>$user_online->payment_link,
                        'subscription_started'=>$user_online->subscription_started,
                        'subscription_end'=>$user_online->subscription_end,
                        'status'=>$user_online->status,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                }else{
                    DB::table('users_subscription')->insert([ 
                        'subscription_id'=> $user_online->subscription_id,
                        'user_id'=>$user_online->user_id,
                        'subscription'=>$user_online->subscription,
                        'subscription_length_month'=>$user_online->subscription_length_month,
                        'subscription_amount'=>$user_online->subscription_amount,
                        'subscription_status'=>$user_online->subscription_status,
                        'is_processby'=>$user_online->is_processby,
                        'is_approvedby'=>$user_online->is_approvedby,
                        'payment_link'=>$user_online->payment_link,
                        'subscription_started'=>$user_online->subscription_started,
                        'subscription_end'=>$user_online->subscription_end,
                        'status'=>$user_online->status,
                        'created_at'=>$user_online->created_at,
                        'updated_at'=>$user_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}