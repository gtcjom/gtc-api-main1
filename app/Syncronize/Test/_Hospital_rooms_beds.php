<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_rooms_beds extends Model
{ 
    public static function hospital_rooms_beds(){ 
        // syncronize hospital_rooms_beds table from offline to online   
        $hosp_offline = DB::table('hospital_rooms_beds')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_rooms_beds')->where('hrb_id', $hosp_offline->hrb_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_rooms_beds')->where('hrb_id', $hosp_offline->hrb_id)->update([    
                            'hrb_id'=> $hosp_offline->hrb_id, 
                            'bed_id'=>$hosp_offline->bed_id,
                            'room_id'=>$hosp_offline->room_id,
                            'room_number'=>$hosp_offline->room_number,
                            'management_id'=>$hosp_offline->management_id,
                            'bed_number'=>$hosp_offline->bed_number,
                            'amount'=>$hosp_offline->amount,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_rooms_beds')->where('hrb_id', $hosp_offline_count[0]->hrb_id)->update([  
                            'hrb_id'=> $hosp_offline_count[0]->hrb_id, 
                            'bed_id'=>$hosp_offline_count[0]->bed_id,
                            'room_id'=>$hosp_offline_count[0]->room_id,
                            'room_number'=>$hosp_offline_count[0]->room_number,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'bed_number'=>$hosp_offline_count[0]->bed_number,
                            'amount'=>$hosp_offline_count[0]->amount,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_rooms_beds')->insert([    
                        'hrb_id'=> $hosp_offline->hrb_id, 
                        'bed_id'=>$hosp_offline->bed_id,
                        'room_id'=>$hosp_offline->room_id,
                        'room_number'=>$hosp_offline->room_number,
                        'management_id'=>$hosp_offline->management_id,
                        'bed_number'=>$hosp_offline->bed_number,
                        'amount'=>$hosp_offline->amount,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_rooms_beds table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_rooms_beds')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_rooms_beds')->where('hrb_id', $hosp_online->hrb_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_rooms_beds')->where('hrb_id', $hosp_online->hrb_id)->update([  
                        'hrb_id'=> $hosp_online->hrb_id, 
                        'bed_id'=>$hosp_online->bed_id,
                        'room_id'=>$hosp_online->room_id,
                        'room_number'=>$hosp_online->room_number,
                        'management_id'=>$hosp_online->management_id,
                        'bed_number'=>$hosp_online->bed_number,
                        'amount'=>$hosp_online->amount,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_rooms_beds')->insert([    
                        'hrb_id'=> $hosp_online->hrb_id, 
                        'bed_id'=>$hosp_online->bed_id,
                        'room_id'=>$hosp_online->room_id,
                        'room_number'=>$hosp_online->room_number,
                        'management_id'=>$hosp_online->management_id,
                        'bed_number'=>$hosp_online->bed_number,
                        'amount'=>$hosp_online->amount,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}