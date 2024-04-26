<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_rod extends Model
{ 
    public static function hospital_rod(){ 
        // syncronize hospital_rod table from offline to online   
        $hosp_offline = DB::table('hospital_rod')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_rod')->where('hre_id', $hosp_offline->hre_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_rod')->where('hre_id', $hosp_offline->hre_id)->update([    
                            'hre_id'=> $hosp_offline->hre_id, 
                            'doctors_id'=>$hosp_offline->doctors_id,
                            'management_id'=>$hosp_offline->management_id,
                            'added_by'=>$hosp_offline->added_by,
                            'department'=>$hosp_offline->department,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_rod')->where('hre_id', $hosp_offline_count[0]->hre_id)->update([  
                            'hre_id'=> $hosp_offline_count[0]->hre_id, 
                            'doctors_id'=>$hosp_offline_count[0]->doctors_id,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'added_by'=>$hosp_offline_count[0]->added_by,
                            'department'=>$hosp_offline_count[0]->department,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_rod')->insert([    
                        'hre_id'=> $hosp_offline->hre_id, 
                        'doctors_id'=>$hosp_offline->doctors_id,
                        'management_id'=>$hosp_offline->management_id,
                        'added_by'=>$hosp_offline->added_by,
                        'department'=>$hosp_offline->department,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_rod table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_rod')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_rod')->where('hre_id', $hosp_online->hre_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_rod')->where('hre_id', $hosp_online->hre_id)->update([  
                        'hre_id'=> $hosp_online->hre_id, 
                        'doctors_id'=>$hosp_online->doctors_id,
                        'management_id'=>$hosp_online->management_id,
                        'added_by'=>$hosp_online->added_by,
                        'department'=>$hosp_online->department,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_rod')->insert([    
                        'hre_id'=> $hosp_online->hre_id, 
                        'doctors_id'=>$hosp_online->doctors_id,
                        'management_id'=>$hosp_online->management_id,
                        'added_by'=>$hosp_online->added_by,
                        'department'=>$hosp_online->department,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}