<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Encoder extends Model
{ 
    public static function encoder(){ 
        // syncronize encoder table from offline to online   
        $enc_offline_List = DB::table('encoder')->get();  
        foreach($enc_offline_List as $enc_offline){  
            $enc_offline_count = DB::connection('mysql2')->table('encoder')->where('encoder_id', $enc_offline->encoder_id)->get();
                if(count($enc_offline_count) > 0){ 
                    if($enc_offline->updated_at > $enc_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('encoder')->where('encoder_id', $enc_offline->encoder_id)->update([    
                            'encoder_id'=> $enc_offline->encoder_id, 
                            'doctors_id'=>$enc_offline->doctors_id,
                            'management_id'=>$enc_offline->management_id,
                            'user_id'=>$enc_offline->user_id,
                            'name'=>$enc_offline->name,
                            'address'=>$enc_offline->address,
                            'gender'=>$enc_offline->gender,
                            'birthday'=>$enc_offline->birthday,
                            'image'=>$enc_offline->image,
                            'status'=>$enc_offline->status,
                            'updated_at'=>$enc_offline->updated_at,
                            'created_at'=>$enc_offline->created_at
                        ]);
                    } 
                    
                    else{
                        DB::table('encoder')->where('encoder_id', $enc_offline_count[0]->encoder_id)->update([     
                            'encoder_id'=> $enc_offline_count[0]->encoder_id, 
                            'doctors_id'=>$enc_offline_count[0]->doctors_id,
                            'management_id'=>$enc_offline_count[0]->management_id,
                            'user_id'=>$enc_offline_count[0]->user_id,
                            'name'=>$enc_offline_count[0]->name,
                            'address'=>$enc_offline_count[0]->address,
                            'gender'=>$enc_offline_count[0]->gender,
                            'birthday'=>$enc_offline_count[0]->birthday,
                            'image'=>$enc_offline_count[0]->image,
                            'status'=>$enc_offline_count[0]->status,
                            'updated_at'=>$enc_offline_count[0]->updated_at,
                            'created_at'=>$enc_offline_count[0]->created_at
                        ]);
                    }  
                     
                }else{
                    DB::connection('mysql2')->table('encoder')->insert([    
                        'encoder_id'=>$enc_offline->encoder_id,
                        'doctors_id'=>$enc_offline->doctors_id,
                        'management_id'=>$enc_offline->management_id,
                        'user_id'=>$enc_offline->user_id,
                        'name'=>$enc_offline->name,
                        'address'=>$enc_offline->address,
                        'gender'=>$enc_offline->gender,
                        'birthday'=>$enc_offline->birthday,
                        'image'=>$enc_offline->image,
                        'status'=>$enc_offline->status,
                        'updated_at'=>$enc_offline->updated_at,
                        'created_at'=>$enc_offline->created_at
                    ]); 
                } 
        }

        // syncronize encoder table from online to offline 
        $enc_online_List = DB::connection('mysql2')->table('encoder')->get();  
        foreach($enc_online_List as $enc_online){  
            $enc_online_count = DB::table('encoder')->where('encoder_id', $enc_online->encoder_id)->get();
                if(count($enc_online_count) > 0){
                    DB::table('encoder')->where('encoder_id', $enc_online->encoder_id)->update([     
                        'encoder_id'=> $enc_online->encoder_id, 
                        'doctors_id'=>$enc_online->doctors_id,
                        'management_id'=>$enc_online->management_id,
                        'user_id'=>$enc_online->user_id,
                        'name'=>$enc_online->name,
                        'address'=>$enc_online->address,
                        'gender'=>$enc_online->gender,
                        'birthday'=>$enc_online->birthday,
                        'image'=>$enc_online->image,
                        'status'=>$enc_online->status,
                        'updated_at'=>$enc_online->updated_at,
                        'created_at'=>$enc_online->created_at
                    ]); 
                }else{
                    DB::table('encoder')->insert([    
                        'encoder_id'=>$enc_online->encoder_id,
                        'doctors_id'=>$enc_online->doctors_id,
                        'management_id'=>$enc_online->management_id,
                        'user_id'=>$enc_online->user_id,
                        'name'=>$enc_online->name,
                        'address'=>$enc_online->address,
                        'gender'=>$enc_online->gender,
                        'birthday'=>$enc_online->birthday,
                        'image'=>$enc_online->image,
                        'status'=>$enc_online->status,
                        'updated_at'=>$enc_online->updated_at,
                        'created_at'=>$enc_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}