<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Virtual_checkup_msg extends Model
{ 
    public static function virtual_checkup_msg(){
        // syncronize virtual_checkup_msg table from offline to online
        $virtual_offline = DB::table('virtual_checkup_msg')->get();  
        foreach($virtual_offline as $virtual_offline){  
            $virtual_offline_count = DB::connection('mysql2')->table('virtual_checkup_msg')->where('vcm_id', $virtual_offline->vcm_id)->get();
                if(count($virtual_offline_count) > 0){ 
                    if($virtual_offline->updated_at > $virtual_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_checkup_msg')->where('vcm_id', $virtual_offline->vcm_id)->update([    
                            'vcm_id'=> $virtual_offline->vcm_id,
                            'senders_id'=>$virtual_offline->senders_id,
                            'receivers_id'=>$virtual_offline->receivers_id,
                            'message'=>$virtual_offline->message,
                            'type'=>$virtual_offline->type,
                            'status'=>$virtual_offline->status,
                            'created_at'=>$virtual_offline->created_at,
                            'updated_at'=>$virtual_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_checkup_msg')->where('vcm_id', $virtual_offline_count[0]->vcm_id)->update([  
                            'vcm_id'=> $virtual_offline_count[0]->vcm_id,
                            'senders_id'=>$virtual_offline_count[0]->senders_id,
                            'receivers_id'=>$virtual_offline_count[0]->receivers_id,
                            'message'=>$virtual_offline_count[0]->message,
                            'type'=>$virtual_offline_count[0]->type,
                            'status'=>$virtual_offline_count[0]->status,
                            'created_at'=>$virtual_offline_count[0]->created_at,
                            'updated_at'=>$virtual_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_checkup_msg')->insert([
                        'vcm_id'=> $virtual_offline->vcm_id,
                        'senders_id'=>$virtual_offline->senders_id,
                        'receivers_id'=>$virtual_offline->receivers_id,
                        'message'=>$virtual_offline->message,
                        'type'=>$virtual_offline->type,
                        'status'=>$virtual_offline->status,
                        'created_at'=>$virtual_offline->created_at,
                        'updated_at'=>$virtual_offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_checkup_msg table from online to offline 
        $virtual_online = DB::connection('mysql2')->table('virtual_checkup_msg')->get();
        foreach($virtual_online as $virtual_online){  
            $virtual_online_count = DB::table('virtual_checkup_msg')->where('vcm_id', $virtual_online->vcm_id)->get();
                if(count($virtual_online_count) > 0){
                    DB::table('virtual_checkup_msg')->where('vcm_id', $virtual_online->vcm_id)->update([
                        'vcm_id'=> $virtual_online->vcm_id,
                        'senders_id'=>$virtual_online->senders_id,
                        'receivers_id'=>$virtual_online->receivers_id,
                        'message'=>$virtual_online->message,
                        'type'=>$virtual_online->type,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_checkup_msg')->insert([ 
                        'vcm_id'=> $virtual_online->vcm_id,
                        'senders_id'=>$virtual_online->senders_id,
                        'receivers_id'=>$virtual_online->receivers_id,
                        'message'=>$virtual_online->message,
                        'type'=>$virtual_online->type,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}