<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncAccountingAccount extends Model
{ 
    public static function syncAccountingAccount(){ 
        // syncronize users table from offline to online 
        $offline = DB::table('accounting_account')->get();  
        foreach($offline as $offline){  
            $offline_online = DB::connection('mysql2')->table('accounting_account')->where('a_id', $offline->a_id)->get();
                if(count($offline_online) > 0){
                    if($offline->updated_at > $offline_online[0]->updated_at){  
                        DB::connection('mysql2')->table('accounting_account')->where('a_id', $offline->a_id)->update([
                            'a_id'=>$offline->a_id,
                            'accounting_id'=>$offline->accounting_id,
                            'user_id'=>$offline->user_id,
                            'management_id'=>$offline->management_id,
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
                        DB::table('accounting_account')->where('a_id', $offline_online[0]->a_id)->update([
                            'a_id'=>$offline_online[0]->a_id,
                            'accounting_id'=>$offline_online[0]->accounting_id,
                            'user_id'=>$offline_online[0]->user_id,
                            'management_id'=>$offline_online[0]->management_id,
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
                    DB::connection('mysql2')->table('accounting_account')->insert([
                        'a_id'=>$offline->a_id,
                        'accounting_id'=>$offline->accounting_id,
                        'user_id'=>$offline->user_id,
                        'management_id'=>$offline->management_id,
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

        // syncronize accounting_account table from online to offline 
        $offline = DB::connection('mysql2')->table('accounting_account')->get();  
        foreach($offline as $offline){  
            $offline_online = DB::table('accounting_account')->where('a_id', $offline->a_id)->get();
                if(count($offline_online) > 0){
                    DB::table('accounting_account')->where('a_id', $offline->a_id)->update([
                        'a_id'=>$offline->a_id,
                        'accounting_id'=>$offline->accounting_id,
                        'user_id'=>$offline->user_id,
                        'management_id'=>$offline->management_id,
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
     
                }else{
                    DB::table('accounting_account')->insert([
                        'a_id'=>$offline->a_id,
                        'accounting_id'=>$offline->accounting_id,
                        'user_id'=>$offline->user_id,
                        'management_id'=>$offline->management_id,
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
 
        return true;
    }
}