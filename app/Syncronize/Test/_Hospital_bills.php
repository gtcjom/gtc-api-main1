<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Hospital_bills extends Model
{ 
    public static function hospital_bills(){ 
        // syncronize hospital_bills table from offline to online   
        $hosp_offline = DB::table('hospital_bills')->get();  
        foreach($hosp_offline as $hosp_offline){  
            $hosp_offline_count = DB::connection('mysql2')->table('hospital_bills')->where('bill_id', $hosp_offline->bill_id)->get();
                if(count($hosp_offline_count) > 0){ 
                    if($hosp_offline->updated_at > $hosp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('hospital_bills')->where('bill_id', $hosp_offline->bill_id)->update([    
                            'bill_id'=> $hosp_offline->bill_id, 
                            'management_id'=>$hosp_offline->management_id,
                            'billing'=>$hosp_offline->billing,
                            'category'=>$hosp_offline->category,
                            'amount'=>$hosp_offline->amount,
                            'opd_amount'=>$hosp_offline->opd_amount,
                            'admitted_amount'=>$hosp_offline->admitted_amount,
                            'laboratory_category'=>$hosp_offline->laboratory_category,
                            'status'=>$hosp_offline->status,
                            'created_at'=>$hosp_offline->created_at,
                            'updated_at'=>$hosp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('hospital_bills')->where('bill_id', $hosp_offline_count[0]->bill_id)->update([  
                            'bill_id'=> $hosp_offline_count[0]->bill_id, 
                            'management_id'=>$hosp_offline_count[0]->management_id,
                            'billing'=>$hosp_offline_count[0]->billing,
                            'category'=>$hosp_offline_count[0]->category,
                            'amount'=>$hosp_offline_count[0]->amount,
                            'opd_amount'=>$hosp_offline_count[0]->opd_amount,
                            'admitted_amount'=>$hosp_offline_count[0]->admitted_amount,
                            'laboratory_category'=>$hosp_offline_count[0]->laboratory_category,
                            'status'=>$hosp_offline_count[0]->status,
                            'created_at'=>$hosp_offline_count[0]->created_at,
                            'updated_at'=>$hosp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('hospital_bills')->insert([    
                        'bill_id'=> $hosp_offline->bill_id, 
                        'management_id'=>$hosp_offline->management_id,
                        'billing'=>$hosp_offline->billing,
                        'category'=>$hosp_offline->category,
                        'amount'=>$hosp_offline->amount,
                        'opd_amount'=>$hosp_offline->opd_amount,
                        'admitted_amount'=>$hosp_offline->admitted_amount,
                        'laboratory_category'=>$hosp_offline->laboratory_category,
                        'status'=>$hosp_offline->status,
                        'created_at'=>$hosp_offline->created_at,
                        'updated_at'=>$hosp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize hospital_bills table from online to offline 
        $hosp_online = DB::connection('mysql2')->table('hospital_bills')->get();  
        foreach($hosp_online as $hosp_online){  
            $hosp_online_count = DB::table('hospital_bills')->where('bill_id', $hosp_online->bill_id)->get();
                if(count($hosp_online_count) > 0){
                    DB::table('hospital_bills')->where('bill_id', $hosp_online->bill_id)->update([  
                        'bill_id'=> $hosp_online->bill_id, 
                        'management_id'=>$hosp_online->management_id,
                        'billing'=>$hosp_online->billing,
                        'category'=>$hosp_online->category,
                        'amount'=>$hosp_online->amount,
                        'opd_amount'=>$hosp_online->opd_amount,
                        'admitted_amount'=>$hosp_online->admitted_amount,
                        'laboratory_category'=>$hosp_online->laboratory_category,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                }else{
                    DB::table('hospital_bills')->insert([    
                        'bill_id'=> $hosp_online->bill_id, 
                        'management_id'=>$hosp_online->management_id,
                        'billing'=>$hosp_online->billing,
                        'category'=>$hosp_online->category,
                        'amount'=>$hosp_online->amount,
                        'opd_amount'=>$hosp_online->opd_amount,
                        'admitted_amount'=>$hosp_online->admitted_amount,
                        'laboratory_category'=>$hosp_online->laboratory_category,
                        'status'=>$hosp_online->status,
                        'created_at'=>$hosp_online->created_at,
                        'updated_at'=>$hosp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}