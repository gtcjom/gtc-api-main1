<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacy_branches extends Model
{ 
    public static function pharmacy_branches(){
        // syncronize pharmacy_branches table from offline to online
        $pharmacy_offline = DB::table('pharmacy_branches')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacy_branches')->where('pb_id', $pharmacy_offline->pb_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacy_branches')->where('pb_id', $pharmacy_offline->pb_id)->update([    
                            'pb_id'=> $pharmacy_offline->pb_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'branch_id'=>$pharmacy_offline->branch_id,
                            'user_id'=>$pharmacy_offline->user_id,
                            'branch_name'=>$pharmacy_offline->branch_name,
                            'branch_address'=>$pharmacy_offline->branch_address,
                            'braches_tin'=>$pharmacy_offline->braches_tin,
                            'status'=>$pharmacy_offline->status,
                            'role'=>$pharmacy_offline->role,
                            'created_at'=>$pharmacy_offline->created_at,
                            'updated_at'=>$pharmacy_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacy_branches')->where('pb_id', $pharmacy_offline_count[0]->pb_id)->update([  
                            'pb_id'=> $pharmacy_offline_count[0]->pb_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'branch_id'=>$pharmacy_offline_count[0]->branch_id,
                            'user_id'=>$pharmacy_offline_count[0]->user_id,
                            'branch_name'=>$pharmacy_offline_count[0]->branch_name,
                            'branch_address'=>$pharmacy_offline_count[0]->branch_address,
                            'braches_tin'=>$pharmacy_offline_count[0]->braches_tin,
                            'status'=>$pharmacy_offline_count[0]->status,
                            'role'=>$pharmacy_offline_count[0]->role,
                            'created_at'=>$pharmacy_offline_count[0]->created_at,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacy_branches')->insert([
                        'pb_id'=> $pharmacy_offline->pb_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'branch_id'=>$pharmacy_offline->branch_id,
                        'user_id'=>$pharmacy_offline->user_id,
                        'branch_name'=>$pharmacy_offline->branch_name,
                        'branch_address'=>$pharmacy_offline->branch_address,
                        'braches_tin'=>$pharmacy_offline->braches_tin,
                        'status'=>$pharmacy_offline->status,
                        'role'=>$pharmacy_offline->role,
                        'created_at'=>$pharmacy_offline->created_at,
                        'updated_at'=>$pharmacy_offline->updated_at
                    ]); 
                } 
        }

        // syncronize pharmacy_branches table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacy_branches')->get();
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacy_branches')->where('pb_id', $pharmacy_online->pb_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacy_branches')->where('pb_id', $pharmacy_online->pb_id)->update([
                        'pb_id'=> $pharmacy_online->pb_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'branch_id'=>$pharmacy_online->branch_id,
                        'user_id'=>$pharmacy_online->user_id,
                        'branch_name'=>$pharmacy_online->branch_name,
                        'branch_address'=>$pharmacy_online->branch_address,
                        'braches_tin'=>$pharmacy_online->braches_tin,
                        'status'=>$pharmacy_online->status,
                        'role'=>$pharmacy_online->role,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                }else{
                    DB::table('pharmacy_branches')->insert([ 
                        'pb_id'=> $pharmacy_online->pb_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'branch_id'=>$pharmacy_online->branch_id,
                        'user_id'=>$pharmacy_online->user_id,
                        'branch_name'=>$pharmacy_online->branch_name,
                        'branch_address'=>$pharmacy_online->branch_address,
                        'braches_tin'=>$pharmacy_online->braches_tin,
                        'status'=>$pharmacy_online->status,
                        'role'=>$pharmacy_online->role,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}