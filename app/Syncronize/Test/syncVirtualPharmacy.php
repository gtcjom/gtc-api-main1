<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncVirtualPharmacy extends Model
{ 
    public static function syncVirtualPharmacy(){
        // syncronize virtual_pharmacy table from offline to online
        $offline = DB::table('virtual_pharmacy')->get();  
        foreach($offline as $offline){  
            $offline_count = DB::connection('mysql2')->table('virtual_pharmacy')->where('vp_id', $offline->vp_id)->get();
                if(count($offline_count) > 0){ 
                    if($offline->updated_at > $offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_pharmacy')->where('vp_id', $offline->vp_id)->update([    
                            'vp_id'=> $offline->vp_id,
                            'virtual_pharmacy_id'=>$offline->virtual_pharmacy_id,
                            'management_id'=>$offline->management_id,
                            'user_id'=>$offline->user_id,
                            'name'=>$offline->name,
                            'branch'=>$offline->branch,
                            'address'=>$offline->address,
                            'logo'=>$offline->logo,
                            'tin'=>$offline->tin,
                            'vpharma_otp'=>$offline->vpharma_otp,
                            'status'=>$offline->status,
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_pharmacy')->where('vp_id', $offline_count[0]->vp_id)->update([  
                            'vp_id'=> $offline_count[0]->vp_id,
                            'virtual_pharmacy_id'=>$offline_count[0]->virtual_pharmacy_id,
                            'management_id'=>$offline_count[0]->management_id,
                            'user_id'=>$offline_count[0]->user_id,
                            'name'=>$offline_count[0]->name,
                            'branch'=>$offline_count[0]->branch,
                            'address'=>$offline_count[0]->address,
                            'logo'=>$offline_count[0]->logo,
                            'tin'=>$offline_count[0]->tin,
                            'vpharma_otp'=>$offline_count[0]->vpharma_otp,
                            'status'=>$offline_count[0]->status,
                            'created_at'=>$offline_count[0]->created_at,
                            'updated_at'=>$offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_pharmacy')->insert([
                        'vp_id'=> $offline->vp_id,
                        'virtual_pharmacy_id'=>$offline->virtual_pharmacy_id,
                        'management_id'=>$offline->management_id,
                        'user_id'=>$offline->user_id,
                        'name'=>$offline->name,
                        'branch'=>$offline->branch,
                        'address'=>$offline->address,
                        'logo'=>$offline->logo,
                        'tin'=>$offline->tin,
                        'vpharma_otp'=>$offline->vpharma_otp,
                        'status'=>$offline->status,
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_pharmacy table from online to offline 
        $online = DB::connection('mysql2')->table('virtual_pharmacy')->get();
        foreach($online as $online){  
            $online_count = DB::table('virtual_pharmacy')->where('vp_id', $online->vp_id)->get();
                if(count($online_count) > 0){
                    DB::table('virtual_pharmacy')->where('vp_id', $online->vp_id)->update([
                        'vp_id'=> $online->vp_id,
                        'virtual_pharmacy_id'=>$online->virtual_pharmacy_id,
                        'management_id'=>$online->management_id,
                        'user_id'=>$online->user_id,
                        'name'=>$online->name,
                        'branch'=>$online->branch,
                        'address'=>$online->address,
                        'logo'=>$online->logo,
                        'tin'=>$online->tin,
                        'vpharma_otp'=>$online->vpharma_otp,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_pharmacy')->insert([ 
                        'vp_id'=> $online->vp_id,
                        'virtual_pharmacy_id'=>$online->virtual_pharmacy_id,
                        'management_id'=>$online->management_id,
                        'user_id'=>$online->user_id,
                        'name'=>$online->name,
                        'branch'=>$online->branch,
                        'address'=>$online->address,
                        'logo'=>$online->logo,
                        'tin'=>$online->tin,
                        'vpharma_otp'=>$online->vpharma_otp,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}