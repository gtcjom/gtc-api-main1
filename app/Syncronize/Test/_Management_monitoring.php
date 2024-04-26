<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Management_monitoring extends Model
{ 
    public static function management_monitoring(){ 
        // syncronize management_monitoring table from offline to online   
        $mgt_offline = DB::table('management_monitoring')->get();  
        foreach($mgt_offline as $mgt_offline){  
            $mgt_offline_count = DB::connection('mysql2')->table('management_monitoring')->where('monitoring_id', $mgt_offline->monitoring_id)->get();
                if(count($mgt_offline_count) > 0){ 
                    if($mgt_offline->updated_at > $mgt_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('management_monitoring')->where('monitoring_id', $mgt_offline->monitoring_id)->update([    
                            'monitoring_id'=> $mgt_offline->monitoring_id, 
                            'city'=>$mgt_offline->city,
                            'population'=>$mgt_offline->population
                        ]);
                    } 
                    else{
                        DB::table('management_monitoring')->where('monitoring_id', $mgt_offline_count[0]->monitoring_id)->update([  
                            'monitoring_id'=> $mgt_offline_count[0]->monitoring_id, 
                            'city'=>$mgt_offline_count[0]->city,
                            'population'=>$mgt_offline_count[0]->population
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('management_monitoring')->insert([ 
                        'monitoring_id'=> $mgt_offline->monitoring_id, 
                        'city'=>$mgt_offline->city,
                        'population'=>$mgt_offline->population
                    ]); 
                } 
        }

        // syncronize management_monitoring table from online to offline 
        $mgt_online = DB::connection('mysql2')->table('management_monitoring')->get();  
        foreach($mgt_online as $mgt_online){  
            $lab_online_count = DB::table('management_monitoring')->where('monitoring_id', $mgt_online->monitoring_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('management_monitoring')->where('monitoring_id', $mgt_online->monitoring_id)->update([  
                        'monitoring_id'=> $mgt_online->monitoring_id, 
                        'city'=>$mgt_online->city,
                        'population'=>$mgt_online->population
                    ]); 
                }else{
                    DB::table('management_monitoring')->insert([    
                        'monitoring_id'=> $mgt_online->monitoring_id, 
                        'city'=>$mgt_online->city,
                        'population'=>$mgt_online->population
                    ]); 
                } 
        }   

        return true;
    } 
}