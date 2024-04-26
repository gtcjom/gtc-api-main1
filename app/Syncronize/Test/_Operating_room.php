<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Operating_room extends Model
{ 
    public static function operating_room(){ 
        // syncronize operating_room table from offline to online   
        $or_offline = DB::table('operating_room')->get();  
        foreach($or_offline as $or_offline){  
            $or_offline_count = DB::connection('mysql2')->table('operating_room')->where('r_id', $or_offline->r_id)->get();
                if(count($or_offline_count) > 0){ 
                    if($or_offline->updated_at > $or_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('operating_room')->where('r_id', $or_offline->r_id)->update([    
                            'r_id'=> $or_offline->r_id, 
                            'or_id'=>$or_offline->or_id,
                            'management_id'=>$or_offline->management_id,
                            'user_id'=>$or_offline->user_id,
                            'name'=>$or_offline->name,
                            'gender'=>$or_offline->gender,
                            'birthday'=>$or_offline->birthday,
                            'address'=>$or_offline->address,
                            'status'=>$or_offline->status,
                            'added_by'=>$or_offline->added_by,
                            'role'=>$or_offline->role,
                            'created_at'=>$or_offline->created_at,
                            'updated_at'=>$or_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('operating_room')->where('r_id', $or_offline_count[0]->r_id)->update([  
                            'r_id'=> $or_offline_count[0]->r_id, 
                            'or_id'=>$or_offline_count[0]->or_id,
                            'management_id'=>$or_offline_count[0]->management_id,
                            'user_id'=>$or_offline_count[0]->user_id,
                            'name'=>$or_offline_count[0]->name,
                            'gender'=>$or_offline_count[0]->gender,
                            'birthday'=>$or_offline_count[0]->birthday,
                            'address'=>$or_offline_count[0]->address,
                            'status'=>$or_offline_count[0]->status,
                            'added_by'=>$or_offline_count[0]->added_by,
                            'role'=>$or_offline_count[0]->role,
                            'created_at'=>$or_offline_count[0]->created_at,
                            'updated_at'=>$or_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('operating_room')->insert([ 
                        'r_id'=> $or_offline->r_id, 
                        'or_id'=>$or_offline->or_id,
                        'management_id'=>$or_offline->management_id,
                        'user_id'=>$or_offline->user_id,
                        'name'=>$or_offline->name,
                        'gender'=>$or_offline->gender,
                        'birthday'=>$or_offline->birthday,
                        'address'=>$or_offline->address,
                        'status'=>$or_offline->status,
                        'added_by'=>$or_offline->added_by,
                        'role'=>$or_offline->role,
                        'created_at'=>$or_offline->created_at,
                        'updated_at'=>$or_offline->updated_at
                    ]); 
                } 
        }

        // syncronize operating_room table from online to offline 
        $or_online = DB::connection('mysql2')->table('operating_room')->get();  
        foreach($or_online as $or_online){  
            $lab_online_count = DB::table('operating_room')->where('r_id', $or_online->r_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('operating_room')->where('r_id', $or_online->r_id)->update([  
                        'r_id'=> $or_online->r_id, 
                        'or_id'=>$or_online->or_id,
                        'management_id'=>$or_online->management_id,
                        'user_id'=>$or_online->user_id,
                        'name'=>$or_online->name,
                        'gender'=>$or_online->gender,
                        'birthday'=>$or_online->birthday,
                        'address'=>$or_online->address,
                        'status'=>$or_online->status,
                        'added_by'=>$or_online->added_by,
                        'role'=>$or_online->role,
                        'created_at'=>$or_online->created_at,
                        'updated_at'=>$or_online->updated_at
                    ]); 
                }else{
                    DB::table('operating_room')->insert([    
                        'r_id'=> $or_online->r_id, 
                        'or_id'=>$or_online->or_id,
                        'management_id'=>$or_online->management_id,
                        'user_id'=>$or_online->user_id,
                        'name'=>$or_online->name,
                        'gender'=>$or_online->gender,
                        'birthday'=>$or_online->birthday,
                        'address'=>$or_online->address,
                        'status'=>$or_online->status,
                        'added_by'=>$or_online->added_by,
                        'role'=>$or_online->role,
                        'created_at'=>$or_online->created_at,
                        'updated_at'=>$or_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}