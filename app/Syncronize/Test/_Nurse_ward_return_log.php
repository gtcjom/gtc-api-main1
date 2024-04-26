<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Nurse_ward_return_log extends Model
{ 
    public static function nurse_ward_return_log(){ 
        // syncronize nurse_ward_return_log table from offline to online   
        $nurse_offline = DB::table('nurse_ward_return_log')->get();  
        foreach($nurse_offline as $nurse_offline){  
            $nurse_offline_count = DB::connection('mysql2')->table('nurse_ward_return_log')->where('log_id', $nurse_offline->log_id)->get();
                if(count($nurse_offline_count) > 0){ 
                    if($nurse_offline->updated_at > $nurse_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('nurse_ward_return_log')->where('log_id', $nurse_offline->log_id)->update([    
                            'log_id'=> $nurse_offline->log_id, 
                            'activity'=>$nurse_offline->activity,
                            'user_id'=>$nurse_offline->user_id,
                            'item_id'=>$nurse_offline->item_id,
                            'quantity'=>$nurse_offline->quantity,
                            'status'=>$nurse_offline->status,
                            'created_at'=>$nurse_offline->created_at,
                            'updated_at'=>$nurse_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('nurse_ward_return_log')->where('log_id', $nurse_offline_count[0]->log_id)->update([  
                            'log_id'=> $nurse_offline_count[0]->log_id, 
                            'activity'=>$nurse_offline_count[0]->activity,
                            'user_id'=>$nurse_offline_count[0]->user_id,
                            'item_id'=>$nurse_offline_count[0]->item_id,
                            'quantity'=>$nurse_offline_count[0]->quantity,
                            'status'=>$nurse_offline_count[0]->status,
                            'created_at'=>$nurse_offline_count[0]->created_at,
                            'updated_at'=>$nurse_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('nurse_ward_return_log')->insert([ 
                        'log_id'=> $nurse_offline->log_id, 
                        'activity'=>$nurse_offline->activity,
                        'user_id'=>$nurse_offline->user_id,
                        'item_id'=>$nurse_offline->item_id,
                        'quantity'=>$nurse_offline->quantity,
                        'status'=>$nurse_offline->status,
                        'created_at'=>$nurse_offline->created_at,
                        'updated_at'=>$nurse_offline->updated_at
                    ]); 
                } 
        }

        // syncronize nurse_ward_return_log table from online to offline 
        $nurse_online = DB::connection('mysql2')->table('nurse_ward_return_log')->get();  
        foreach($nurse_online as $nurse_online){  
            $lab_online_count = DB::table('nurse_ward_return_log')->where('log_id', $nurse_online->log_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('nurse_ward_return_log')->where('log_id', $nurse_online->log_id)->update([  
                        'log_id'=> $nurse_online->log_id, 
                        'activity'=>$nurse_online->activity,
                        'user_id'=>$nurse_online->user_id,
                        'item_id'=>$nurse_online->item_id,
                        'quantity'=>$nurse_online->quantity,
                        'status'=>$nurse_online->status,
                        'created_at'=>$nurse_online->created_at,
                        'updated_at'=>$nurse_online->updated_at
                    ]); 
                }else{
                    DB::table('nurse_ward_return_log')->insert([    
                        'log_id'=> $nurse_online->log_id, 
                        'activity'=>$nurse_online->activity,
                        'user_id'=>$nurse_online->user_id,
                        'item_id'=>$nurse_online->item_id,
                        'quantity'=>$nurse_online->quantity,
                        'status'=>$nurse_online->status,
                        'created_at'=>$nurse_online->created_at,
                        'updated_at'=>$nurse_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}