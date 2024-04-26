<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacy extends Model
{ 
    public static function pharmacy(){ 
        // syncronize pharmacy table from offline to online   
        $pharmacy_offline = DB::table('pharmacy')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacy')->where('phmcy_id', $pharmacy_offline->phmcy_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacy')->where('phmcy_id', $pharmacy_offline->phmcy_id)->update([    
                            'phmcy_id'=> $pharmacy_offline->phmcy_id, 
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'user_id'=>$pharmacy_offline->user_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'name'=>$pharmacy_offline->name,
                            'company_name'=>$pharmacy_offline->company_name,
                            'address'=>$pharmacy_offline->address,
                            'tin_number'=>$pharmacy_offline->tin_number,
                            'email'=>$pharmacy_offline->email,
                            'contact'=>$pharmacy_offline->contact,
                            'status'=>$pharmacy_offline->status,
                            'role'=>$pharmacy_offline->role,
                            'added_by'=>$pharmacy_offline->added_by,
                            'pharmacy_type'=>$pharmacy_offline->pharmacy_type,
                            'company_logo'=>$pharmacy_offline->company_logo,
                            'created_at'=>$pharmacy_offline->created_at,
                            'updated_at'=>$pharmacy_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacy')->where('phmcy_id', $pharmacy_offline_count[0]->phmcy_id)->update([  
                            'phmcy_id'=> $pharmacy_offline_count[0]->phmcy_id, 
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'user_id'=>$pharmacy_offline_count[0]->user_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'name'=>$pharmacy_offline_count[0]->name,
                            'company_name'=>$pharmacy_offline_count[0]->company_name,
                            'address'=>$pharmacy_offline_count[0]->address,
                            'tin_number'=>$pharmacy_offline_count[0]->tin_number,
                            'email'=>$pharmacy_offline_count[0]->email,
                            'contact'=>$pharmacy_offline_count[0]->contact,
                            'status'=>$pharmacy_offline_count[0]->status,
                            'role'=>$pharmacy_offline_count[0]->role,
                            'added_by'=>$pharmacy_offline_count[0]->added_by,
                            'pharmacy_type'=>$pharmacy_offline_count[0]->pharmacy_type,
                            'company_logo'=>$pharmacy_offline_count[0]->company_logo,
                            'created_at'=>$pharmacy_offline_count[0]->created_at,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacy')->insert([ 
                        'phmcy_id'=> $pharmacy_offline->phmcy_id, 
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'user_id'=>$pharmacy_offline->user_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'name'=>$pharmacy_offline->name,
                        'company_name'=>$pharmacy_offline->company_name,
                        'address'=>$pharmacy_offline->address,
                        'tin_number'=>$pharmacy_offline->tin_number,
                        'email'=>$pharmacy_offline->email,
                        'contact'=>$pharmacy_offline->contact,
                        'status'=>$pharmacy_offline->status,
                        'role'=>$pharmacy_offline->role,
                        'added_by'=>$pharmacy_offline->added_by,
                        'pharmacy_type'=>$pharmacy_offline->pharmacy_type,
                        'company_logo'=>$pharmacy_offline->company_logo,
                        'created_at'=>$pharmacy_offline->created_at,
                        'updated_at'=>$pharmacy_offline->updated_at
                    ]); 
                } 
        }

        // syncronize pharmacy table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacy')->get();  
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacy')->where('phmcy_id', $pharmacy_online->phmcy_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacy')->where('phmcy_id', $pharmacy_online->phmcy_id)->update([
                        'phmcy_id'=> $pharmacy_online->phmcy_id, 
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'user_id'=>$pharmacy_online->user_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'name'=>$pharmacy_online->name,
                        'company_name'=>$pharmacy_online->company_name,
                        'address'=>$pharmacy_online->address,
                        'tin_number'=>$pharmacy_online->tin_number,
                        'email'=>$pharmacy_online->email,
                        'contact'=>$pharmacy_online->contact,
                        'status'=>$pharmacy_online->status,
                        'role'=>$pharmacy_online->role,
                        'added_by'=>$pharmacy_online->added_by,
                        'pharmacy_type'=>$pharmacy_online->pharmacy_type,
                        'company_logo'=>$pharmacy_online->company_logo,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                }else{
                    DB::table('pharmacy')->insert([ 
                        'phmcy_id'=> $pharmacy_online->phmcy_id, 
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'user_id'=>$pharmacy_online->user_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'name'=>$pharmacy_online->name,
                        'company_name'=>$pharmacy_online->company_name,
                        'address'=>$pharmacy_online->address,
                        'tin_number'=>$pharmacy_online->tin_number,
                        'email'=>$pharmacy_online->email,
                        'contact'=>$pharmacy_online->contact,
                        'status'=>$pharmacy_online->status,
                        'role'=>$pharmacy_online->role,
                        'added_by'=>$pharmacy_online->added_by,
                        'pharmacy_type'=>$pharmacy_online->pharmacy_type,
                        'company_logo'=>$pharmacy_online->company_logo,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}