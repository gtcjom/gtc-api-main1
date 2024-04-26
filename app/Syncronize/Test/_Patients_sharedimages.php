<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_sharedimages extends Model
{ 
    public static function patient_sharedimages(){ 
        // syncronize patient_sharedimages table from offline to online   
        $patient_offline = DB::table('patient_sharedimages')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patient_sharedimages')->where('psi_id', $patient_offline->psi_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patient_sharedimages')->where('psi_id', $patient_offline->psi_id)->update([    
                            'psi_id'=> $patient_offline->psi_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'image'=>$patient_offline->image,
                            'category'=>$patient_offline->category,
                            'type'=>$patient_offline->type,
                            'status'=>$patient_offline->status,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patient_sharedimages')->where('psi_id', $patient_offline_count[0]->psi_id)->update([  
                            'psi_id'=> $patient_offline_count[0]->psi_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'image'=>$patient_offline_count[0]->image,
                            'category'=>$patient_offline_count[0]->category,
                            'type'=>$patient_offline_count[0]->type,
                            'status'=>$patient_offline_count[0]->status,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patient_sharedimages')->insert([ 
                        'psi_id'=> $patient_offline->psi_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'image'=>$patient_offline->image,
                        'category'=>$patient_offline->category,
                        'type'=>$patient_offline->type,
                        'status'=>$patient_offline->status,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patient_sharedimages table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patient_sharedimages')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patient_sharedimages')->where('psi_id', $patient_online->psi_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patient_sharedimages')->where('psi_id', $patient_online->psi_id)->update([
                        'psi_id'=> $patient_online->psi_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'image'=>$patient_online->image,
                        'category'=>$patient_online->category,
                        'type'=>$patient_online->type,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patient_sharedimages')->insert([ 
                        'psi_id'=> $patient_online->psi_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'image'=>$patient_online->image,
                        'category'=>$patient_online->category,
                        'type'=>$patient_online->type,
                        'status'=>$patient_online->status,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}