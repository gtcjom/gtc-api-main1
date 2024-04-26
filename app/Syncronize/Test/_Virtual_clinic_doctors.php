<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Virtual_clinic_doctors extends Model
{ 
    public static function virtual_clinic_doctors(){
        // syncronize virtual_clinic_doctors table from offline to online
        $virtual_offline = DB::table('virtual_clinic_doctors')->get();  
        foreach($virtual_offline as $virtual_offline){  
            $virtual_offline_count = DB::connection('mysql2')->table('virtual_clinic_doctors')->where('vcd_id', $virtual_offline->vcd_id)->get();
                if(count($virtual_offline_count) > 0){ 
                    if($virtual_offline->updated_at > $virtual_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_clinic_doctors')->where('vcd_id', $virtual_offline->vcd_id)->update([    
                            'vcd_id'=> $virtual_offline->vcd_id,
                            'clinic_id'=>$virtual_offline->clinic_id,
                            'doctors_id'=>$virtual_offline->doctors_id,
                            'status'=>$virtual_offline->status,
                            'created_at'=>$virtual_offline->created_at,
                            'updated_at'=>$virtual_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_clinic_doctors')->where('vcd_id', $virtual_offline_count[0]->vcd_id)->update([  
                            'vcd_id'=> $virtual_offline_count[0]->vcd_id,
                            'clinic_id'=>$virtual_offline_count[0]->clinic_id,
                            'doctors_id'=>$virtual_offline_count[0]->doctors_id,
                            'status'=>$virtual_offline_count[0]->status,
                            'created_at'=>$virtual_offline_count[0]->created_at,
                            'updated_at'=>$virtual_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_clinic_doctors')->insert([
                        'vcd_id'=> $virtual_offline->vcd_id,
                        'clinic_id'=>$virtual_offline->clinic_id,
                        'doctors_id'=>$virtual_offline->doctors_id,
                        'status'=>$virtual_offline->status,
                        'created_at'=>$virtual_offline->created_at,
                        'updated_at'=>$virtual_offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_clinic_doctors table from online to offline 
        $virtual_online = DB::connection('mysql2')->table('virtual_clinic_doctors')->get();
        foreach($virtual_online as $virtual_online){  
            $virtual_online_count = DB::table('virtual_clinic_doctors')->where('vcd_id', $virtual_online->vcd_id)->get();
                if(count($virtual_online_count) > 0){
                    DB::table('virtual_clinic_doctors')->where('vcd_id', $virtual_online->vcd_id)->update([
                        'vcd_id'=> $virtual_online->vcd_id,
                        'clinic_id'=>$virtual_online->clinic_id,
                        'doctors_id'=>$virtual_online->doctors_id,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_clinic_doctors')->insert([ 
                        'vcd_id'=> $virtual_online->vcd_id,
                        'clinic_id'=>$virtual_online->clinic_id,
                        'doctors_id'=>$virtual_online->doctors_id,
                        'status'=>$virtual_online->status,
                        'created_at'=>$virtual_online->created_at,
                        'updated_at'=>$virtual_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}