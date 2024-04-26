<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_patients_bill extends Model
{ 
    public static function hospital_patients_bill(){ 
        // syncronize hospital_patients_bill table from offline to online   
        $hosp_offline = DB::table('hospital_patients_bill')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_patients_bill')->where('patient_bill_id', $hosp_offline->patient_bill_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_patients_bill')->where('patient_bill_id', $hosp_offline->patient_bill_id)->update([    
                            'patient_bill_id'=> $hosp_offline->patient_bill_id, 
                            'bill_id'=>$hosp_offline->bill_id,
                            'management_id'=>$hosp_offline->management_id,
                            'doctors_id'=>$hosp_offline->doctors_id,
                            'patient_id'=>$hosp_offline->patient_id,
                            'is_paid'=>$hosp_offline->is_paid,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_patients_bill')->where('patient_bill_id', $hosp_offline_count[0]->patient_bill_id)->update([  
                            'patient_bill_id'=> $hosp_offline_count[0]->patient_bill_id, 
                            'bill_id'=>$hosp_offline_count[0]->bill_id,
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'doctors_id'=>$hosp_offline_count[0]->doctors_id,
                            'patient_id'=>$hosp_offline_count[0]->patient_id,
                            'is_paid'=>$hosp_offline_count[0]->is_paid,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_patients_bill')->insert([    
                        'patient_bill_id'=> $hosp_offline->patient_bill_id, 
                        'bill_id'=>$hosp_offline->bill_id,
                        'management_id'=>$hosp_offline->management_id,
                        'doctors_id'=>$hosp_offline->doctors_id,
                        'patient_id'=>$hosp_offline->patient_id,
                        'is_paid'=>$hosp_offline->is_paid,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_patients_bill table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_patients_bill')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_patients_bill')->where('patient_bill_id', $hosp_online->patient_bill_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_patients_bill')->where('patient_bill_id', $hosp_online->patient_bill_id)->update([  
                        'patient_bill_id'=> $hosp_online->patient_bill_id, 
                        'bill_id'=>$hosp_online->bill_id,
                        'management_id'=>$hosp_online->management_id,
                        'doctors_id'=>$hosp_online->doctors_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'is_paid'=>$hosp_online->is_paid,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_patients_bill')->insert([    
                        'patient_bill_id'=> $hosp_online->patient_bill_id, 
                        'bill_id'=>$hosp_online->bill_id,
                        'management_id'=>$hosp_online->management_id,
                        'doctors_id'=>$hosp_online->doctors_id,
                        'patient_id'=>$hosp_online->patient_id,
                        'is_paid'=>$hosp_online->is_paid,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}