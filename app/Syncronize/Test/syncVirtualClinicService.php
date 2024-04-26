<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncVirtualClinicService extends Model
{ 
    public function syncVirtualClinicService(){
        // syncronize virtual_clinic_services table from offline to online
        $offline = DB::table('virtual_clinic_services')->get();  
        foreach($offline as $offline){  
            $offline_count = DB::connection('mysql2')->table('virtual_clinic_services')->where('vcs_id', $offline->vcs_id)->get();
                if(count($offline_count) > 0){ 
                    if($offline->updated_at > $offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_clinic_services')->where('vcs_id', $offline->vcs_id)->update([    
                            'vcs_id'=> $offline->vcs_id,
                            'fees_id'=>$offline->fees_id,
                            'doctors_user_id'=>$offline->doctors_user_id,
                            'secretary_id'=>$offline->secretary_id,
                            'clinic_id'=>$offline->clinic_id,
                            'service'=>$offline->service,
                            'amount'=>$offline->amount,
                            'status'=>$offline->status,
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_clinic_services')->where('vcs_id', $offline_count[0]->vcs_id)->update([  
                            'vcs_id'=> $offline_count[0]->vcs_id,
                            'fees_id'=>$offline_count[0]->fees_id,
                            'doctors_user_id'=>$offline_count[0]->doctors_user_id,
                            'secretary_id'=>$offline_count[0]->secretary_id,
                            'clinic_id'=>$offline_count[0]->clinic_id,
                            'service'=>$offline_count[0]->service,
                            'amount'=>$offline_count[0]->amount,
                            'status'=>$offline_count[0]->status,
                            'created_at'=>$offline_count[0]->created_at,
                            'updated_at'=>$offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_clinic_services')->insert([
                        'vcs_id'=> $offline->vcs_id,
                        'fees_id'=>$offline->fees_id,
                        'doctors_user_id'=>$offline->doctors_user_id,
                        'secretary_id'=>$offline->secretary_id,
                        'clinic_id'=>$offline->clinic_id,
                        'service'=>$offline->service,
                        'amount'=>$offline->amount,
                        'status'=>$offline->status,
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_clinic_services table from online to offline 
        $online = DB::connection('mysql2')->table('virtual_clinic_services')->get();
        foreach($online as $online){  
            $online_count = DB::table('virtual_clinic_services')->where('vcs_id', $online->vcs_id)->get();
                if(count($online_count) > 0){
                    DB::table('virtual_clinic_services')->where('vcs_id', $online->vcs_id)->update([
                        'vcs_id'=> $online->vcs_id,
                        'fees_id'=>$online->fees_id,
                        'doctors_user_id'=>$online->doctors_user_id,
                        'secretary_id'=>$online->secretary_id,
                        'clinic_id'=>$online->clinic_id,
                        'service'=>$online->service,
                        'amount'=>$online->amount,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_clinic_services')->insert([ 
                        'vcs_id'=> $online->vcs_id,
                        'fees_id'=>$online->fees_id,
                        'doctors_user_id'=>$online->doctors_user_id,
                        'secretary_id'=>$online->secretary_id,
                        'clinic_id'=>$online->clinic_id,
                        'service'=>$online->service,
                        'amount'=>$online->amount,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}