<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Csr_account extends Model
{ 
    public static function csr_account(){ 
        // syncronize csr_account table from offline to online  
        $csr_offline = DB::table('csr_account')->get();  
        foreach($csr_offline as $csr_offline){  
            $csr_offline_count = DB::connection('mysql2')->table('csr_account')->where('ca_id', $csr_offline->ca_id)->get();
                if(count($csr_offline_count) > 0){
                    if($csr_offline->updated_at > $csr_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('csr_account')->where('ca_id', $csr_offline->ca_id)->update([      
                            'ca_id'=>$csr_offline->ca_id,
                            'csr_id'=>$csr_offline->csr_id,
                            'user_id'=>$csr_offline->user_id,
                            'management_id'=>$csr_offline->management_id, 
                            'name'=>$csr_offline->name,
                            'gender'=>$csr_offline->gender, 
                            'birthday'=>$csr_offline->birthday,
                            'address'=>$csr_offline->address,
                            'role'=>$csr_offline->role,
                            'added_by'=>$csr_offline->added_by,
                            'status'=>$csr_offline->status,
                            'created_at'=>$csr_offline->created_at,
                            'updated_at'=>$csr_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('csr_account')->where('ca_id', $csr_offline_count[0]->ca_id)->update([  
                            'ca_id'=>$csr_offline_count[0]->ca_id,
                            'csr_id'=>$csr_offline_count[0]->csr_id,
                            'user_id'=>$csr_offline_count[0]->user_id,
                            'management_id'=>$csr_offline_count[0]->management_id, 
                            'name'=>$csr_offline_count[0]->name,
                            'gender'=>$csr_offline_count[0]->gender, 
                            'birthday'=>$csr_offline_count[0]->birthday,
                            'address'=>$csr_offline_count[0]->address,
                            'role'=>$csr_offline_count[0]->role,
                            'added_by'=>$csr_offline_count[0]->added_by,
                            'status'=>$csr_offline_count[0]->status,
                            'created_at'=>$csr_offline_count[0]->created_at,
                            'updated_at'=>$csr_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('csr_account')->insert([
                        'ca_id'=>$csr_offline->ca_id,
                        'csr_id'=>$csr_offline->csr_id,
                        'user_id'=>$csr_offline->user_id,
                        'management_id'=>$csr_offline->management_id, 
                        'name'=>$csr_offline->name,
                        'gender'=>$csr_offline->gender, 
                        'birthday'=>$csr_offline->birthday,
                        'address'=>$csr_offline->address,
                        'role'=>$csr_offline->role,
                        'added_by'=>$csr_offline->added_by,
                        'status'=>$csr_offline->status,
                        'created_at'=>$csr_offline->created_at,
                        'updated_at'=>$csr_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize csr_account table from online to offline
        $csr_online = DB::connection('mysql2')->table('csr_account')->get();  
        foreach($csr_online as $csr_online){  
            $csr_online_count = DB::table('csr_account')->where('ca_id', $csr_online->ca_id)->get();
                if(count($csr_online_count) > 0){
                    DB::table('csr_account')->where('ca_id', $csr_online->ca_id)->update([   
                        'ca_id'=>$csr_online->ca_id,
                        'csr_id'=>$csr_online->csr_id,
                        'user_id'=>$csr_online->user_id,
                        'management_id'=>$csr_online->management_id, 
                        'name'=>$csr_online->name,
                        'gender'=>$csr_online->gender, 
                        'birthday'=>$csr_online->birthday,
                        'address'=>$csr_online->address,
                        'role'=>$csr_online->role,
                        'added_by'=>$csr_online->added_by,
                        'status'=>$csr_online->status,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                }else{
                    DB::table('csr_account')->insert([
                        'ca_id'=>$csr_online->ca_id,
                        'csr_id'=>$csr_online->csr_id,
                        'user_id'=>$csr_online->user_id,
                        'management_id'=>$csr_online->management_id, 
                        'name'=>$csr_online->name,
                        'gender'=>$csr_online->gender, 
                        'birthday'=>$csr_online->birthday,
                        'address'=>$csr_online->address,
                        'role'=>$csr_online->role,
                        'added_by'=>$csr_online->added_by,
                        'status'=>$csr_online->status,
                        'created_at'=>$csr_online->created_at,
                        'updated_at'=>$csr_online->updated_at
                    ]); 
                } 
        } 
        return true;
    } 
}