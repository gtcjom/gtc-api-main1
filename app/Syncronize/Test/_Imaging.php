<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Imaging extends Model
{ 
    public static function imaging(){ 
        // syncronize imaging table from offline to online   
        $imgng_offline = DB::table('imaging')->get();  
        foreach($imgng_offline as $imgng_offline){  
            $imgng_offline_count = DB::connection('mysql2')->table('imaging')->where('i_id', $imgng_offline->i_id)->get();
                if(count($imgng_offline_count) > 0){ 
                    if($imgng_offline->updated_at > $imgng_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('imaging')->where('i_id', $imgng_offline->i_id)->update([    
                            'i_id'=> $imgng_offline->i_id, 
                            'imaging_id'=>$imgng_offline->imaging_id,
                            'management_id'=>$imgng_offline->management_id,
                            'user_id'=>$imgng_offline->user_id,
                            'name'=>$imgng_offline->name,
                            'gender'=>$imgng_offline->gender,
                            'birthday'=>$imgng_offline->birthday,
                            'address'=>$imgng_offline->address,
                            'role'=>$imgng_offline->role,
                            'added_by'=>$imgng_offline->added_by,
                            'created_at'=>$imgng_offline->created_at,
                            'updated_at'=>$imgng_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('imaging')->where('i_id', $imgng_offline_count[0]->i_id)->update([  
                            'i_id'=> $imgng_offline_count[0]->i_id, 
                            'imaging_id'=>$imgng_offline_count[0]->imaging_id,
                            'management_id'=>$imgng_offline_count[0]->management_id,
                            'user_id'=>$imgng_offline_count[0]->user_id,
                            'name'=>$imgng_offline_count[0]->name,
                            'gender'=>$imgng_offline_count[0]->gender,
                            'birthday'=>$imgng_offline_count[0]->birthday,
                            'address'=>$imgng_offline_count[0]->address,
                            'role'=>$imgng_offline_count[0]->role,
                            'added_by'=>$imgng_offline_count[0]->added_by,
                            'created_at'=>$imgng_offline_count[0]->created_at,
                            'updated_at'=>$imgng_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('imaging')->insert([ 
                        'i_id'=> $imgng_offline->i_id, 
                        'imaging_id'=>$imgng_offline->imaging_id,
                        'management_id'=>$imgng_offline->management_id,
                        'user_id'=>$imgng_offline->user_id,
                        'name'=>$imgng_offline->name,
                        'gender'=>$imgng_offline->gender,
                        'birthday'=>$imgng_offline->birthday,
                        'address'=>$imgng_offline->address,
                        'role'=>$imgng_offline->role,
                        'added_by'=>$imgng_offline->added_by,
                        'created_at'=>$imgng_offline->created_at,
                        'updated_at'=>$imgng_offline->updated_at
                    ]); 
                } 
        }

        // syncronize imaging table from online to offline 
        $imgng_online = DB::connection('mysql2')->table('imaging')->get();  
        foreach($imgng_online as $imgng_online){  
            $imgng_online_count = DB::table('imaging')->where('i_id', $imgng_online->i_id)->get();
                if(count($imgng_online_count) > 0){
                    DB::table('imaging')->where('i_id', $imgng_online->i_id)->update([  
                        'i_id'=> $imgng_online->i_id, 
                        'imaging_id'=>$imgng_online->imaging_id,
                        'management_id'=>$imgng_online->management_id,
                        'user_id'=>$imgng_online->user_id,
                        'name'=>$imgng_online->name,
                        'gender'=>$imgng_online->gender,
                        'birthday'=>$imgng_online->birthday,
                        'address'=>$imgng_online->address,
                        'role'=>$imgng_online->role,
                        'added_by'=>$imgng_online->added_by,
                        'created_at'=>$imgng_online->created_at,
                        'updated_at'=>$imgng_online->updated_at
                    ]); 
                }else{
                    DB::table('imaging')->insert([    
                        'i_id'=> $imgng_online->i_id, 
                        'imaging_id'=>$imgng_online->imaging_id,
                        'management_id'=>$imgng_online->management_id,
                        'user_id'=>$imgng_online->user_id,
                        'name'=>$imgng_online->name,
                        'gender'=>$imgng_online->gender,
                        'birthday'=>$imgng_online->birthday,
                        'address'=>$imgng_online->address,
                        'role'=>$imgng_online->role,
                        'added_by'=>$imgng_online->added_by,
                        'created_at'=>$imgng_online->created_at,
                        'updated_at'=>$imgng_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}