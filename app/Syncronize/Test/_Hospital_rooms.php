<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_rooms extends Model
{ 
    public static function hospital_rooms(){ 
        // syncronize hospital_rooms table from offline to online   
        $hosp_offline = DB::table('hospital_rooms')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_rooms')->where('r_id', $hosp_offline->r_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_rooms')->where('r_id', $hosp_offline->r_id)->update([    
                            'r_id'=> $hosp_offline->r_id, 
                            'room_id'=>$hosp_offline->room_id,
                            'management_id'=>$hosp_offline->management_id,
                            'room_name'=>$hosp_offline->room_name,
                            'no_of_rooms'=>$hosp_offline->no_of_rooms,
                            'no_of_beds_per_room'=>$hosp_offline->no_of_beds_per_room,
                            'type'=>$hosp_offline->type,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_rooms')->where('r_id', $hosp_offline_count[0]->r_id)->update([  
                            'r_id'=> $hosp_offline_count[0]->r_id, 
                            'room_id'=>$hosp_offline_count[0]->room_id,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'room_name'=>$hosp_offline_count[0]->room_name,
                            'no_of_rooms'=>$hosp_offline_count[0]->no_of_rooms,
                            'no_of_beds_per_room'=>$hosp_offline_count[0]->no_of_beds_per_room,
                            'type'=>$hosp_offline_count[0]->type,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_rooms')->insert([    
                        'r_id'=> $hosp_offline->r_id, 
                        'room_id'=>$hosp_offline->room_id,
                        'management_id'=>$hosp_offline->management_id,
                        'room_name'=>$hosp_offline->room_name,
                        'no_of_rooms'=>$hosp_offline->no_of_rooms,
                        'no_of_beds_per_room'=>$hosp_offline->no_of_beds_per_room,
                        'type'=>$hosp_offline->type,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_rooms table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_rooms')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_rooms')->where('r_id', $hosp_online->r_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_rooms')->where('r_id', $hosp_online->r_id)->update([  
                        'r_id'=> $hosp_online->r_id, 
                        'room_id'=>$hosp_online->room_id,
                        'management_id'=>$hosp_online->management_id,
                        'room_name'=>$hosp_online->room_name,
                        'no_of_rooms'=>$hosp_online->no_of_rooms,
                        'no_of_beds_per_room'=>$hosp_online->no_of_beds_per_room,
                        'type'=>$hosp_online->type,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_rooms')->insert([    
                        'r_id'=> $hosp_online->r_id, 
                        'room_id'=>$hosp_online->room_id,
                        'management_id'=>$hosp_online->management_id,
                        'room_name'=>$hosp_online->room_name,
                        'no_of_rooms'=>$hosp_online->no_of_rooms,
                        'no_of_beds_per_room'=>$hosp_online->no_of_beds_per_room,
                        'type'=>$hosp_online->type,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}