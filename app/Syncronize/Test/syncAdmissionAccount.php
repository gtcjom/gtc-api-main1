<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncAdmissionAccount extends Model
{ 
    public static function syncAdmissionAccount(){
        // syncronize users table from offline to online 
        $offline = DB::table('admission_account')->get();  
        foreach($offline as $offline){  
            $offline_online = DB::connection('mysql2')->table('admission_account')->where('ac_id', $offline->ac_id)->get();
                if(count($offline_online) > 0){ 
                    if($offline->updated_at > $offline_online[0]->updated_at){  
                        DB::connection('mysql2')->table('admission_account')->where('ac_id', $offline->ac_id)->update([ 
                            'ac_id'=>$offline->ac_id,
                            'admission_id'=>$offline->admission_id,
                            'management_id	'=>$offline->management_id,
                            'user_id'=>$offline->user_id, 
                            'name'=>$offline->name,
                            'gender'=>$offline->gender,
                            'birthday'=>$offline->birthday,
                            'address'=>$offline->address,
                            'role'=>$offline->role,
                            'added_by'=>$offline->added_by,
                            'status'=>$offline->status,
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('admission_account')->where('ac_id', $offline_online[0]->ac_id)->update([ 
                            'ac_id'=>$offline_online[0]->ac_id,
                            'admission_id'=>$offline_online[0]->admission_id,
                            'management_id	'=>$offline_online[0]->management_id,
                            'user_id'=>$offline_online[0]->user_id, 
                            'name'=>$offline_online[0]->name,
                            'gender'=>$offline_online[0]->gender,
                            'birthday'=>$offline_online[0]->birthday,
                            'address'=>$offline_online[0]->address,
                            'role'=>$offline_online[0]->role,
                            'added_by'=>$offline_online[0]->added_by,
                            'status'=>$offline_online[0]->status,
                            'created_at'=>$offline_online[0]->created_at,
                            'updated_at'=>$offline_online[0]->updated_at
                        ]);
                    }

                }else{
                    DB::connection('mysql2')->table('admission_account')->insert([
                        'ac_id'=>$offline->ac_id,
                        'admission_id'=>$offline->admission_id,
                        'management_id	'=>$offline->management_id,
                        'user_id'=>$offline->user_id, 
                        'name'=>$offline->name,
                        'gender'=>$offline->gender,
                        'birthday'=>$offline->birthday,
                        'address'=>$offline->address,
                        'role'=>$offline->role,
                        'added_by'=>$offline->added_by,
                        'status'=>$offline->status,
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at
                    ]); 
                } 
        } 

        // syncronize active_users table from online to offline 
        $online = DB::connection('mysql2')->table('admission_account')->get();  
        foreach($online as $online){  
            $online_online = DB::table('admission_account')->where('ac_id', $online->ac_id)->get();
                if(count($online_online) > 0){
                    DB::table('admission_account')->where('ac_id', $online->ac_id)->update([
                        'ac_id'=>$online->ac_id,
                        'admission_id'=>$online->admission_id,
                        'management_id	'=>$online->management_id,
                        'user_id'=>$online->user_id, 
                        'name'=>$online->name,
                        'gender'=>$online->gender,
                        'birthday'=>$online->birthday,
                        'address'=>$online->address,
                        'role'=>$online->role,
                        'added_by'=>$online->added_by,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]);
     
                }else{
                    DB::table('admission_account')->insert([
                        'ac_id'=>$online->ac_id,
                        'admission_id'=>$online->admission_id,
                        'management_id	'=>$online->management_id,
                        'user_id'=>$online->user_id, 
                        'name'=>$online->name,
                        'gender'=>$online->gender,
                        'birthday'=>$online->birthday,
                        'address'=>$online->address,
                        'role'=>$online->role,
                        'added_by'=>$online->added_by,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                } 
        }
        return true;
    }
}