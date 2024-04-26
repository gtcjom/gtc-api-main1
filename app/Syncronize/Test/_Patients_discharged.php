<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Patients_discharged extends Model
{ 
    public  static function patients_discharged(){ 
        // syncronize patients_discharged table from offline to online   
        $patient_offline = DB::table('patients_discharged')->get();  
        foreach($patient_offline as $patient_offline){  
            $patient_offline_count = DB::connection('mysql2')->table('patients_discharged')->where('dis_id', $patient_offline->dis_id)->get();
                if(count($patient_offline_count) > 0){ 
                    if($patient_offline->updated_at > $patient_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('patients_discharged')->where('dis_id', $patient_offline->dis_id)->update([    
                            'dis_id'=> $patient_offline->dis_id, 
                            'patient_id'=>$patient_offline->patient_id,
                            'management_id'=>$patient_offline->management_id,
                            'cashier_id'=>$patient_offline->cashier_id,
                            'receipt_id'=>$patient_offline->receipt_id,
                            'case_file'=>$patient_offline->case_file,
                            'full_name'=>$patient_offline->full_name,
                            'full_address'=>$patient_offline->full_address,
                            'total'=>$patient_offline->total,
                            'amount_paid'=>$patient_offline->amount_paid,
                            'balance'=>$patient_offline->balance,
                            'status'=>$patient_offline->status,
                            'reason'=>$patient_offline->reason,
                            'screenshot'=>$patient_offline->screenshot,
                            'invoice'=>$patient_offline->invoice,
                            'created_at'=>$patient_offline->created_at,
                            'updated_at'=>$patient_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('patients_discharged')->where('dis_id', $patient_offline_count[0]->dis_id)->update([  
                            'dis_id'=> $patient_offline_count[0]->dis_id, 
                            'patient_id'=>$patient_offline_count[0]->patient_id,
                            'management_id'=>$patient_offline_count[0]->management_id,
                            'cashier_id'=>$patient_offline_count[0]->cashier_id,
                            'receipt_id'=>$patient_offline_count[0]->receipt_id,
                            'case_file'=>$patient_offline_count[0]->case_file,
                            'full_name'=>$patient_offline_count[0]->full_name,
                            'full_address'=>$patient_offline_count[0]->full_address,
                            'total'=>$patient_offline_count[0]->total,
                            'amount_paid'=>$patient_offline_count[0]->amount_paid,
                            'balance'=>$patient_offline_count[0]->balance,
                            'status'=>$patient_offline_count[0]->status,
                            'reason'=>$patient_offline_count[0]->reason,
                            'screenshot'=>$patient_offline_count[0]->screenshot,
                            'invoice'=>$patient_offline_count[0]->invoice,
                            'created_at'=>$patient_offline_count[0]->created_at,
                            'updated_at'=>$patient_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('patients_discharged')->insert([ 
                        'dis_id'=> $patient_offline->dis_id, 
                        'patient_id'=>$patient_offline->patient_id,
                        'management_id'=>$patient_offline->management_id,
                        'cashier_id'=>$patient_offline->cashier_id,
                        'receipt_id'=>$patient_offline->receipt_id,
                        'case_file'=>$patient_offline->case_file,
                        'full_name'=>$patient_offline->full_name,
                        'full_address'=>$patient_offline->full_address,
                        'total'=>$patient_offline->total,
                        'amount_paid'=>$patient_offline->amount_paid,
                        'balance'=>$patient_offline->balance,
                        'status'=>$patient_offline->status,
                        'reason'=>$patient_offline->reason,
                        'screenshot'=>$patient_offline->screenshot,
                        'invoice'=>$patient_offline->invoice,
                        'created_at'=>$patient_offline->created_at,
                        'updated_at'=>$patient_offline->updated_at
                    ]); 
                } 
        }

        // syncronize patients_discharged table from online to offline 
        $patient_online = DB::connection('mysql2')->table('patients_discharged')->get();  
        foreach($patient_online as $patient_online){  
            $patient_online_count = DB::table('patients_discharged')->where('dis_id', $patient_online->dis_id)->get();
                if(count($patient_online_count) > 0){
                    DB::table('patients_discharged')->where('dis_id', $patient_online->dis_id)->update([
                        'dis_id'=> $patient_online->dis_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'management_id'=>$patient_online->management_id,
                        'cashier_id'=>$patient_online->cashier_id,
                        'receipt_id'=>$patient_online->receipt_id,
                        'case_file'=>$patient_online->case_file,
                        'full_name'=>$patient_online->full_name,
                        'full_address'=>$patient_online->full_address,
                        'total'=>$patient_online->total,
                        'amount_paid'=>$patient_online->amount_paid,
                        'balance'=>$patient_online->balance,
                        'status'=>$patient_online->status,
                        'reason'=>$patient_online->reason,
                        'screenshot'=>$patient_online->screenshot,
                        'invoice'=>$patient_online->invoice,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                }else{
                    DB::table('patients_discharged')->insert([    
                        'dis_id'=> $patient_online->dis_id, 
                        'patient_id'=>$patient_online->patient_id,
                        'management_id'=>$patient_online->management_id,
                        'cashier_id'=>$patient_online->cashier_id,
                        'receipt_id'=>$patient_online->receipt_id,
                        'case_file'=>$patient_online->case_file,
                        'full_name'=>$patient_online->full_name,
                        'full_address'=>$patient_online->full_address,
                        'total'=>$patient_online->total,
                        'amount_paid'=>$patient_online->amount_paid,
                        'balance'=>$patient_online->balance,
                        'status'=>$patient_online->status,
                        'reason'=>$patient_online->reason,
                        'screenshot'=>$patient_online->screenshot,
                        'invoice'=>$patient_online->invoice,
                        'created_at'=>$patient_online->created_at,
                        'updated_at'=>$patient_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}