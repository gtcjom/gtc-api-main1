<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Teleradiologist extends Model
{ 
    public static function teleradiologist(){
        // syncronize teleradiologist table from offline to online
        $telerad_offline = DB::table('teleradiologist')->get();  
        foreach($telerad_offline as $telerad_offline){  
            $telerad_offline_count = DB::connection('mysql2')->table('teleradiologist')->where('telerad_id', $telerad_offline->telerad_id)->get();
                if(count($telerad_offline_count) > 0){ 
                    if($telerad_offline->updated_at > $telerad_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('teleradiologist')->where('telerad_id', $telerad_offline->telerad_id)->update([    
                            'telerad_id'=> $telerad_offline->telerad_id,
                            'user_id'=>$telerad_offline->user_id,
                            'name'=>$telerad_offline->name,
                            'gender'=>$telerad_offline->gender,
                            'birthday'=>$telerad_offline->birthday,
                            'address'=>$telerad_offline->address,
                            'status'=>$telerad_offline->status,
                            'created_at'=>$telerad_offline->created_at,
                            'updated_at'=>$telerad_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('teleradiologist')->where('telerad_id', $telerad_offline_count[0]->telerad_id)->update([  
                            'telerad_id'=> $telerad_offline_count[0]->telerad_id,
                            'user_id'=>$telerad_offline_count[0]->user_id,
                            'name'=>$telerad_offline_count[0]->name,
                            'gender'=>$telerad_offline_count[0]->gender,
                            'birthday'=>$telerad_offline_count[0]->birthday,
                            'address'=>$telerad_offline_count[0]->address,
                            'status'=>$telerad_offline_count[0]->status,
                            'created_at'=>$telerad_offline_count[0]->created_at,
                            'updated_at'=>$telerad_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('teleradiologist')->insert([
                        'telerad_id'=> $telerad_offline->telerad_id,
                        'user_id'=>$telerad_offline->user_id,
                        'name'=>$telerad_offline->name,
                        'gender'=>$telerad_offline->gender,
                        'birthday'=>$telerad_offline->birthday,
                        'address'=>$telerad_offline->address,
                        'status'=>$telerad_offline->status,
                        'created_at'=>$telerad_offline->created_at,
                        'updated_at'=>$telerad_offline->updated_at
                    ]); 
                } 
        }

        // syncronize teleradiologist table from online to offline 
        $telerad_online = DB::connection('mysql2')->table('teleradiologist')->get();
        foreach($telerad_online as $telerad_online){  
            $telerad_online_count = DB::table('teleradiologist')->where('telerad_id', $telerad_online->telerad_id)->get();
                if(count($telerad_online_count) > 0){
                    DB::table('teleradiologist')->where('telerad_id', $telerad_online->telerad_id)->update([
                        'telerad_id'=> $telerad_online->telerad_id,
                        'user_id'=>$telerad_online->user_id,
                        'name'=>$telerad_online->name,
                        'gender'=>$telerad_online->gender,
                        'birthday'=>$telerad_online->birthday,
                        'address'=>$telerad_online->address,
                        'status'=>$telerad_online->status,
                        'created_at'=>$telerad_online->created_at,
                        'updated_at'=>$telerad_online->updated_at
                    ]); 
                }else{
                    DB::table('teleradiologist')->insert([ 
                        'telerad_id'=> $telerad_online->telerad_id,
                        'user_id'=>$telerad_online->user_id,
                        'name'=>$telerad_online->name,
                        'gender'=>$telerad_online->gender,
                        'birthday'=>$telerad_online->birthday,
                        'address'=>$telerad_online->address,
                        'status'=>$telerad_online->status,
                        'created_at'=>$telerad_online->created_at,
                        'updated_at'=>$telerad_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}