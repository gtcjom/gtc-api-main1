<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Nurses extends Model
{ 
    public static function nurses(){ 
        // syncronize nurses table from offline to online   
        $nurse_offline = DB::table('nurses')->get();  
        foreach($nurse_offline as $nurse_offline){  
            $nurse_offline_count = DB::connection('mysql2')->table('nurses')->where('n_id', $nurse_offline->n_id)->get();
                if(count($nurse_offline_count) > 0){ 
                    if($nurse_offline->updated_at > $nurse_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('nurses')->where('n_id', $nurse_offline->n_id)->update([    
                            'n_id'=> $nurse_offline->n_id, 
                            'nurse_id'=>$nurse_offline->nurse_id,
                            'user_id'=>$nurse_offline->user_id,
                            'management_id'=>$nurse_offline->management_id,
                            'name'=>$nurse_offline->name,
                            'address'=>$nurse_offline->address,
                            'gender'=>$nurse_offline->gender,
                            'birthday'=>$nurse_offline->birthday,
                            'image'=>$nurse_offline->image,
                            'status'=>$nurse_offline->status,
                            'role'=>$nurse_offline->role,
                            'added_by'=>$nurse_offline->added_by,
                            'updated_at'=>$nurse_offline->updated_at,
                            'created_at'=>$nurse_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('nurses')->where('n_id', $nurse_offline_count[0]->n_id)->update([  
                            'n_id'=> $nurse_offline_count[0]->n_id, 
                            'nurse_id'=>$nurse_offline_count[0]->nurse_id,
                            'user_id'=>$nurse_offline_count[0]->user_id,
                            'management_id'=>$nurse_offline_count[0]->management_id,
                            'name'=>$nurse_offline_count[0]->name,
                            'address'=>$nurse_offline_count[0]->address,
                            'gender'=>$nurse_offline_count[0]->gender,
                            'birthday'=>$nurse_offline_count[0]->birthday,
                            'image'=>$nurse_offline_count[0]->image,
                            'status'=>$nurse_offline_count[0]->status,
                            'role'=>$nurse_offline_count[0]->role,
                            'added_by'=>$nurse_offline_count[0]->added_by,
                            'updated_at'=>$nurse_offline_count[0]->updated_at,
                            'created_at'=>$nurse_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('nurses')->insert([ 
                        'n_id'=> $nurse_offline->n_id, 
                        'nurse_id'=>$nurse_offline->nurse_id,
                        'user_id'=>$nurse_offline->user_id,
                        'management_id'=>$nurse_offline->management_id,
                        'name'=>$nurse_offline->name,
                        'address'=>$nurse_offline->address,
                        'gender'=>$nurse_offline->gender,
                        'birthday'=>$nurse_offline->birthday,
                        'image'=>$nurse_offline->image,
                        'status'=>$nurse_offline->status,
                        'role'=>$nurse_offline->role,
                        'added_by'=>$nurse_offline->added_by,
                        'updated_at'=>$nurse_offline->updated_at,
                        'created_at'=>$nurse_offline->created_at
                    ]); 
                } 
        }

        // syncronize nurses table from online to offline 
        $nurse_online = DB::connection('mysql2')->table('nurses')->get();  
        foreach($nurse_online as $nurse_online){  
            $lab_online_count = DB::table('nurses')->where('n_id', $nurse_online->n_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('nurses')->where('n_id', $nurse_online->n_id)->update([  
                        'n_id'=> $nurse_online->n_id, 
                        'nurse_id'=>$nurse_online->nurse_id,
                        'user_id'=>$nurse_online->user_id,
                        'management_id'=>$nurse_online->management_id,
                        'name'=>$nurse_online->name,
                        'address'=>$nurse_online->address,
                        'gender'=>$nurse_online->gender,
                        'birthday'=>$nurse_online->birthday,
                        'image'=>$nurse_online->image,
                        'status'=>$nurse_online->status,
                        'role'=>$nurse_online->role,
                        'added_by'=>$nurse_online->added_by,
                        'updated_at'=>$nurse_online->updated_at,
                        'created_at'=>$nurse_online->created_at
                    ]); 
                }else{
                    DB::table('nurses')->insert([    
                        'n_id'=> $nurse_online->n_id, 
                        'nurse_id'=>$nurse_online->nurse_id,
                        'user_id'=>$nurse_online->user_id,
                        'management_id'=>$nurse_online->management_id,
                        'name'=>$nurse_online->name,
                        'address'=>$nurse_online->address,
                        'gender'=>$nurse_online->gender,
                        'birthday'=>$nurse_online->birthday,
                        'image'=>$nurse_online->image,
                        'status'=>$nurse_online->status,
                        'role'=>$nurse_online->role,
                        'added_by'=>$nurse_online->added_by,
                        'updated_at'=>$nurse_online->updated_at,
                        'created_at'=>$nurse_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}