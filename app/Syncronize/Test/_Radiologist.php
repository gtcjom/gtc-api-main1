<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Radiologist extends Model
{ 
    public static function radiologist(){
        // syncronize radiologist table from offline to online
        $rad_offline = DB::table('radiologist')->get();  
        foreach($rad_offline as $rad_offline){  
            $rad_offline_count = DB::connection('mysql2')->table('radiologist')->where('r_id', $rad_offline->r_id)->get();
                if(count($rad_offline_count) > 0){ 
                    if($rad_offline->updated_at > $rad_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('radiologist')->where('r_id', $rad_offline->r_id)->update([    
                            'r_id'=> $rad_offline->r_id,
                            'radiologist_id'=>$rad_offline->radiologist_id,
                            'user_id'=>$rad_offline->user_id,
                            'management_id'=>$rad_offline->management_id,
                            'name'=>$rad_offline->name,
                            'gender'=>$rad_offline->gender,
                            'birthday'=>$rad_offline->birthday,
                            'address'=>$rad_offline->address,
                            'role'=>$rad_offline->role,
                            'added_by'=>$rad_offline->added_by,
                            'status'=>$rad_offline->status,
                            'created_at'=>$rad_offline->created_at,
                            'updated_at'=>$rad_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('radiologist')->where('r_id', $rad_offline_count[0]->r_id)->update([  
                            'r_id'=> $rad_offline_count[0]->r_id,
                            'radiologist_id'=>$rad_offline_count[0]->radiologist_id,
                            'user_id'=>$rad_offline_count[0]->user_id,
                            'management_id'=>$rad_offline_count[0]->management_id,
                            'name'=>$rad_offline_count[0]->name,
                            'gender'=>$rad_offline_count[0]->gender,
                            'birthday'=>$rad_offline_count[0]->birthday,
                            'address'=>$rad_offline_count[0]->address,
                            'role'=>$rad_offline_count[0]->role,
                            'added_by'=>$rad_offline_count[0]->added_by,
                            'status'=>$rad_offline_count[0]->status,
                            'created_at'=>$rad_offline_count[0]->created_at,
                            'updated_at'=>$rad_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('radiologist')->insert([
                        'r_id'=> $rad_offline->r_id,
                        'radiologist_id'=>$rad_offline->radiologist_id,
                        'user_id'=>$rad_offline->user_id,
                        'management_id'=>$rad_offline->management_id,
                        'name'=>$rad_offline->name,
                        'gender'=>$rad_offline->gender,
                        'birthday'=>$rad_offline->birthday,
                        'address'=>$rad_offline->address,
                        'role'=>$rad_offline->role,
                        'added_by'=>$rad_offline->added_by,
                        'status'=>$rad_offline->status,
                        'created_at'=>$rad_offline->created_at,
                        'updated_at'=>$rad_offline->updated_at
                    ]); 
                } 
        }

        // syncronize radiologist table from online to offline 
        $rad_online = DB::connection('mysql2')->table('radiologist')->get();
        foreach($rad_online as $rad_online){  
            $rad_online_count = DB::table('radiologist')->where('r_id', $rad_online->r_id)->get();
                if(count($rad_online_count) > 0){
                    DB::table('radiologist')->where('r_id', $rad_online->r_id)->update([
                        'r_id'=> $rad_online->r_id,
                        'radiologist_id'=>$rad_online->radiologist_id,
                        'user_id'=>$rad_online->user_id,
                        'management_id'=>$rad_online->management_id,
                        'name'=>$rad_online->name,
                        'gender'=>$rad_online->gender,
                        'birthday'=>$rad_online->birthday,
                        'address'=>$rad_online->address,
                        'role'=>$rad_online->role,
                        'added_by'=>$rad_online->added_by,
                        'status'=>$rad_online->status,
                        'created_at'=>$rad_online->created_at,
                        'updated_at'=>$rad_online->updated_at
                    ]); 
                }else{
                    DB::table('radiologist')->insert([ 
                        'r_id'=> $rad_online->r_id,
                        'radiologist_id'=>$rad_online->radiologist_id,
                        'user_id'=>$rad_online->user_id,
                        'management_id'=>$rad_online->management_id,
                        'name'=>$rad_online->name,
                        'gender'=>$rad_online->gender,
                        'birthday'=>$rad_online->birthday,
                        'address'=>$rad_online->address,
                        'role'=>$rad_online->role,
                        'added_by'=>$rad_online->added_by,
                        'status'=>$rad_online->status,
                        'created_at'=>$rad_online->created_at,
                        'updated_at'=>$rad_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}