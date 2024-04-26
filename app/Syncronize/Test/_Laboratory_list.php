<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory_list extends Model
{ 
    public static function laboratory_list(){ 
        // syncronize laboratory_list table from offline to online   
        $lab_offline = DB::table('laboratory_list')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory_list')->where('l_id', $lab_offline->l_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory_list')->where('l_id', $lab_offline->l_id)->update([    
                            'l_id'=> $lab_offline->l_id, 
                            'laboratory_id'=>$lab_offline->laboratory_id,
                            'management_id'=>$lab_offline->management_id,
                            'user_id'=>$lab_offline->user_id,
                            'name'=>$lab_offline->name,
                            'address'=>$lab_offline->address,
                            'gender'=>$lab_offline->gender,
                            'birthday'=>$lab_offline->birthday,
                            'role'=>$lab_offline->role,
                            'added_by'=>$lab_offline->added_by,
                            'created_at'=>$lab_offline->created_at,
                            'updated_at'=>$lab_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory_list')->where('l_id', $lab_offline_count[0]->l_id)->update([  
                            'l_id'=> $lab_offline_count[0]->l_id, 
                            'laboratory_id'=>$lab_offline_count[0]->laboratory_id,
                            'management_id'=>$lab_offline_count[0]->management_id,
                            'user_id'=>$lab_offline_count[0]->user_id,
                            'name'=>$lab_offline_count[0]->name,
                            'address'=>$lab_offline_count[0]->address,
                            'gender'=>$lab_offline_count[0]->gender,
                            'birthday'=>$lab_offline_count[0]->birthday,
                            'role'=>$lab_offline_count[0]->role,
                            'added_by'=>$lab_offline_count[0]->added_by,
                            'created_at'=>$lab_offline_count[0]->created_at,
                            'updated_at'=>$lab_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory_list')->insert([ 
                        'l_id'=> $lab_offline->l_id, 
                        'laboratory_id'=>$lab_offline->laboratory_id,
                        'management_id'=>$lab_offline->management_id,
                        'user_id'=>$lab_offline->user_id,
                        'name'=>$lab_offline->name,
                        'address'=>$lab_offline->address,
                        'gender'=>$lab_offline->gender,
                        'birthday'=>$lab_offline->birthday,
                        'role'=>$lab_offline->role,
                        'added_by'=>$lab_offline->added_by,
                        'created_at'=>$lab_offline->created_at,
                        'updated_at'=>$lab_offline->updated_at
                    ]); 
                } 
        }

        // syncronize laboratory_list table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory_list')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory_list')->where('l_id', $lab_online->l_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory_list')->where('l_id', $lab_online->l_id)->update([  
                        'l_id'=> $lab_online->l_id, 
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'management_id'=>$lab_online->management_id,
                        'user_id'=>$lab_online->user_id,
                        'name'=>$lab_online->name,
                        'address'=>$lab_online->address,
                        'gender'=>$lab_online->gender,
                        'birthday'=>$lab_online->birthday,
                        'role'=>$lab_online->role,
                        'added_by'=>$lab_online->added_by,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory_list')->insert([    
                        'l_id'=> $lab_online->l_id, 
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'management_id'=>$lab_online->management_id,
                        'user_id'=>$lab_online->user_id,
                        'name'=>$lab_online->name,
                        'address'=>$lab_online->address,
                        'gender'=>$lab_online->gender,
                        'birthday'=>$lab_online->birthday,
                        'role'=>$lab_online->role,
                        'added_by'=>$lab_online->added_by,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}