<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncVirtualClinic extends Model
{ 
    public static function syncVirtualClinic(){
        // syncronize virtual_clinic table from offline to online
        $offline = DB::table('virtual_clinic')->get();  
        foreach($offline as $offline){  
            $offline_count = DB::connection('mysql2')->table('virtual_clinic')->where('vda_id', $offline->vda_id)->get();
                if(count($offline_count) > 0){ 
                    if($offline->updated_at > $offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_clinic')->where('vda_id', $offline->vda_id)->update([    
                            'vda_id'=> $offline->vda_id,
                            'clinic_id'=>$offline->clinic_id,
                            'status'=>$offline->status,
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_clinic')->where('vda_id', $offline_count[0]->vda_id)->update([  
                            'vda_id'=> $offline_count[0]->vda_id,
                            'clinic_id'=>$offline_count[0]->clinic_id,
                            'status'=>$offline_count[0]->status,
                            'created_at'=>$offline_count[0]->created_at,
                            'updated_at'=>$offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_clinic')->insert([
                        'vda_id'=> $offline->vda_id,
                        'clinic_id'=>$offline->clinic_id,
                        'status'=>$offline->status,
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_clinic table from online to offline 
        $online = DB::connection('mysql2')->table('virtual_clinic')->get();
        foreach($online as $online){  
            $online_count = DB::table('virtual_clinic')->where('vda_id', $online->vda_id)->get();
                if(count($online_count) > 0){
                    DB::table('virtual_clinic')->where('vda_id', $online->vda_id)->update([
                        'vda_id'=> $online->vda_id,
                        'clinic_id'=>$online->clinic_id,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_clinic')->insert([ 
                        'vda_id'=> $online->vda_id,
                        'clinic_id'=>$online->clinic_id,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}