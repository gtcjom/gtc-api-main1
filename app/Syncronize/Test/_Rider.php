<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Rider extends Model
{ 
    public static function rider(){
        // syncronize rider table from offline to online
        $rider_offline = DB::table('rider')->get();  
        foreach($rider_offline as $rider_offline){  
            $rider_offline_count = DB::connection('mysql2')->table('rider')->where('rider_id', $rider_offline->rider_id)->get();
                if(count($rider_offline_count) > 0){ 
                    if($rider_offline->updated_at > $rider_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('rider')->where('rider_id', $rider_offline->rider_id)->update([    
                            'rider_id'=> $rider_offline->rider_id,
                            'user_id'=>$rider_offline->user_id,
                            'firstname'=>$rider_offline->firstname,
                            'lastname'=>$rider_offline->lastname,
                            'middlename'=>$rider_offline->middlename,
                            'address'=>$rider_offline->address,
                            'email'=>$rider_offline->email,
                            'contact_no'=>$rider_offline->contact_no,
                            'logo'=>$rider_offline->logo,
                            'sss'=>$rider_offline->sss,
                            'philhealth'=>$rider_offline->philhealth,
                            'birthday'=>$rider_offline->birthday,
                            'status'=>$rider_offline->status,
                            'created_at'=>$rider_offline->created_at,
                            'updated_at'=>$rider_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('rider')->where('rider_id', $rider_offline_count[0]->rider_id)->update([  
                            'rider_id'=> $rider_offline_count[0]->rider_id,
                            'user_id'=>$rider_offline_count[0]->user_id,
                            'firstname'=>$rider_offline_count[0]->firstname,
                            'lastname'=>$rider_offline_count[0]->lastname,
                            'middlename'=>$rider_offline_count[0]->middlename,
                            'address'=>$rider_offline_count[0]->address,
                            'email'=>$rider_offline_count[0]->email,
                            'contact_no'=>$rider_offline_count[0]->contact_no,
                            'logo'=>$rider_offline_count[0]->logo,
                            'sss'=>$rider_offline_count[0]->sss,
                            'philhealth'=>$rider_offline_count[0]->philhealth,
                            'birthday'=>$rider_offline_count[0]->birthday,
                            'status'=>$rider_offline_count[0]->status,
                            'created_at'=>$rider_offline_count[0]->created_at,
                            'updated_at'=>$rider_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('rider')->insert([
                        'rider_id'=> $rider_offline->rider_id,
                        'user_id'=>$rider_offline->user_id,
                        'firstname'=>$rider_offline->firstname,
                        'lastname'=>$rider_offline->lastname,
                        'middlename'=>$rider_offline->middlename,
                        'address'=>$rider_offline->address,
                        'email'=>$rider_offline->email,
                        'contact_no'=>$rider_offline->contact_no,
                        'logo'=>$rider_offline->logo,
                        'sss'=>$rider_offline->sss,
                        'philhealth'=>$rider_offline->philhealth,
                        'birthday'=>$rider_offline->birthday,
                        'status'=>$rider_offline->status,
                        'created_at'=>$rider_offline->created_at,
                        'updated_at'=>$rider_offline->updated_at
                    ]); 
                } 
        }

        // syncronize rider table from online to offline 
        $rider_online = DB::connection('mysql2')->table('rider')->get();
        foreach($rider_online as $rider_online){  
            $rider_online_count = DB::table('rider')->where('rider_id', $rider_online->rider_id)->get();
                if(count($rider_online_count) > 0){
                    DB::table('rider')->where('rider_id', $rider_online->rider_id)->update([
                        'rider_id'=> $rider_online->rider_id,
                        'user_id'=>$rider_online->user_id,
                        'firstname'=>$rider_online->firstname,
                        'lastname'=>$rider_online->lastname,
                        'middlename'=>$rider_online->middlename,
                        'address'=>$rider_online->address,
                        'email'=>$rider_online->email,
                        'contact_no'=>$rider_online->contact_no,
                        'logo'=>$rider_online->logo,
                        'sss'=>$rider_online->sss,
                        'philhealth'=>$rider_online->philhealth,
                        'birthday'=>$rider_online->birthday,
                        'status'=>$rider_online->status,
                        'created_at'=>$rider_online->created_at,
                        'updated_at'=>$rider_online->updated_at
                    ]); 
                }else{
                    DB::table('rider')->insert([ 
                        'rider_id'=> $rider_online->rider_id,
                        'user_id'=>$rider_online->user_id,
                        'firstname'=>$rider_online->firstname,
                        'lastname'=>$rider_online->lastname,
                        'middlename'=>$rider_online->middlename,
                        'address'=>$rider_online->address,
                        'email'=>$rider_online->email,
                        'contact_no'=>$rider_online->contact_no,
                        'logo'=>$rider_online->logo,
                        'sss'=>$rider_online->sss,
                        'philhealth'=>$rider_online->philhealth,
                        'birthday'=>$rider_online->birthday,
                        'status'=>$rider_online->status,
                        'created_at'=>$rider_online->created_at,
                        'updated_at'=>$rider_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}