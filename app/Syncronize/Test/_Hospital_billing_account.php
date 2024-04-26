<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_billing_account extends Model
{ 
    public static function hospital_billing_account(){ 
        // syncronize hospital_billing_account table from offline to online   
        $hosp_offline = DB::table('hospital_billing_account')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_billing_account')->where('bu_id', $hosp_offline->bu_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_billing_account')->where('bu_id', $hosp_offline->bu_id)->update([    
                            'bu_id'=> $hosp_offline->bu_id, 
                            'buser_id'=>$hosp_offline->buser_id,
                            'management_id'=>$hosp_offline->management_id,
                            'user_id'=>$hosp_offline->user_id,
                            'name'=>$hosp_offline->name,
                            'address'=>$hosp_offline->address,
                            'gender'=>$hosp_offline->gender,
                            'birthday'=>$hosp_offline->birthday,
                            'role'=>$hosp_offline->role,
                            'added_by'=>$hosp_offline->added_by,
                            'status'=>$hosp_offline->status,
                            'updated_at'=>$hosp_offline->updated_at,
                            'created_at'=>$hosp_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_billing_account')->where('bu_id', $hosp_offline_count[0]->bu_id)->update([  
                            'bu_id'=> $hosp_offline_count[0]->bu_id, 
                            'buser_id'=>$hosp_offline_count[0]->buser_id,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'user_id'=>$hosp_offline_count[0]->user_id,
                            'name'=>$hosp_offline_count[0]->name,
                            'address'=>$hosp_offline_count[0]->address,
                            'gender'=>$hosp_offline_count[0]->gender,
                            'birthday'=>$hosp_offline_count[0]->birthday,
                            'role'=>$hosp_offline_count[0]->role,
                            'added_by'=>$hosp_offline_count[0]->added_by,
                            'status'=>$hosp_offline_count[0]->status,
                            'updated_at'=>$hosp_offline_count[0]->updated_at,
                            'created_at'=>$hosp_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_billing_account')->insert([    
                        'bu_id'=> $hosp_offline->bu_id, 
                        'buser_id'=>$hosp_offline->buser_id,
                        'management_id'=>$hosp_offline->management_id,
                        'user_id'=>$hosp_offline->user_id,
                        'name'=>$hosp_offline->name,
                        'address'=>$hosp_offline->address,
                        'gender'=>$hosp_offline->gender,
                        'birthday'=>$hosp_offline->birthday,
                        'role'=>$hosp_offline->role,
                        'added_by'=>$hosp_offline->added_by,
                        'status'=>$hosp_offline->status,
                        'updated_at'=>$hosp_offline->updated_at,
                        'created_at'=>$hosp_offline->created_at
                    ]); 
                } 
        }

        // syncronize hospital_billing_account table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_billing_account')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_billing_account')->where('bu_id', $hosp_online->bu_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_billing_account')->where('bu_id', $hosp_online->bu_id)->update([  
                        'bu_id'=> $hosp_online->bu_id, 
                        'buser_id'=>$hosp_online->buser_id,
                        'management_id'=>$hosp_online->management_id,
                        'user_id'=>$hosp_online->user_id,
                        'name'=>$hosp_online->name,
                        'address'=>$hosp_online->address,
                        'gender'=>$hosp_online->gender,
                        'birthday'=>$hosp_online->birthday,
                        'role'=>$hosp_online->role,
                        'added_by'=>$hosp_online->added_by,
                        'status'=>$hosp_online->status,
                        'updated_at'=>$hosp_online->updated_at,
                        'created_at'=>$hosp_online->created_at
                    ]); 
                }else{
                    DB::table('hospital_billing_account')->insert([    
                        'bu_id'=> $hosp_online->bu_id, 
                        'buser_id'=>$hosp_online->buser_id,
                        'management_id'=>$hosp_online->management_id,
                        'user_id'=>$hosp_online->user_id,
                        'name'=>$hosp_online->name,
                        'address'=>$hosp_online->address,
                        'gender'=>$hosp_online->gender,
                        'birthday'=>$hosp_online->birthday,
                        'role'=>$hosp_online->role,
                        'added_by'=>$hosp_online->added_by,
                        'status'=>$hosp_online->status,
                        'updated_at'=>$hosp_online->updated_at,
                        'created_at'=>$hosp_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}