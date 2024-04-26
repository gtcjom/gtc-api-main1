<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyclinic_history extends Model
{ 
    public static function pharmacyclinic_history(){ 
        // syncronize pharmacyclinic_history table from offline to online   
        $pharmacy_offline = DB::table('pharmacyclinic_history')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyclinic_history')->where('pch_id', $pharmacy_offline->pch_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyclinic_history')->where('pch_id', $pharmacy_offline->pch_id)->update([    
                            'pch_id'=> $pharmacy_offline->pch_id, 
                            'product_id'=>$pharmacy_offline->product_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'username'=>$pharmacy_offline->username,
                            'product'=>$pharmacy_offline->product,
                            'description'=>$pharmacy_offline->description,
                            'unit'=>$pharmacy_offline->unit,
                            'quantity'=>$pharmacy_offline->quantity,
                            'request_type'=>$pharmacy_offline->request_type,
                            'dr_no'=>$pharmacy_offline->dr_no,
                            'supplier'=>$pharmacy_offline->supplier,
                            'remarks'=>$pharmacy_offline->remarks,
                            'created_at'=>$pharmacy_offline->created_at,
                            'updated_at'=>$pharmacy_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyclinic_history')->where('pch_id', $pharmacy_offline_count[0]->pch_id)->update([  
                            'pch_id'=> $pharmacy_offline_count[0]->pch_id, 
                            'product_id'=>$pharmacy_offline_count[0]->product_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'username'=>$pharmacy_offline_count[0]->username,
                            'product'=>$pharmacy_offline_count[0]->product,
                            'description'=>$pharmacy_offline_count[0]->description,
                            'unit'=>$pharmacy_offline_count[0]->unit,
                            'quantity'=>$pharmacy_offline_count[0]->quantity,
                            'request_type'=>$pharmacy_offline_count[0]->request_type,
                            'dr_no'=>$pharmacy_offline_count[0]->dr_no,
                            'supplier'=>$pharmacy_offline_count[0]->supplier,
                            'remarks'=>$pharmacy_offline_count[0]->remarks,
                            'created_at'=>$pharmacy_offline_count[0]->created_at,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyclinic_history')->insert([ 
                        'pch_id'=> $pharmacy_offline->pch_id, 
                        'product_id'=>$pharmacy_offline->product_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'username'=>$pharmacy_offline->username,
                        'product'=>$pharmacy_offline->product,
                        'description'=>$pharmacy_offline->description,
                        'unit'=>$pharmacy_offline->unit,
                        'quantity'=>$pharmacy_offline->quantity,
                        'request_type'=>$pharmacy_offline->request_type,
                        'dr_no'=>$pharmacy_offline->dr_no,
                        'supplier'=>$pharmacy_offline->supplier,
                        'remarks'=>$pharmacy_offline->remarks,
                        'created_at'=>$pharmacy_offline->created_at,
                        'updated_at'=>$pharmacy_offline->updated_at
                    ]); 
                } 
        }

        // syncronize pharmacyclinic_history table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyclinic_history')->get();  
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyclinic_history')->where('pch_id', $pharmacy_online->pch_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyclinic_history')->where('pch_id', $pharmacy_online->pch_id)->update([
                        'pch_id'=> $pharmacy_online->pch_id, 
                        'product_id'=>$pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'username'=>$pharmacy_online->username,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'unit'=>$pharmacy_online->unit,
                        'quantity'=>$pharmacy_online->quantity,
                        'request_type'=>$pharmacy_online->request_type,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'supplier'=>$pharmacy_online->supplier,
                        'remarks'=>$pharmacy_online->remarks,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                }else{
                    DB::table('pharmacyclinic_history')->insert([ 
                        'pch_id'=> $pharmacy_online->pch_id, 
                        'product_id'=>$pharmacy_online->product_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'username'=>$pharmacy_online->username,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'unit'=>$pharmacy_online->unit,
                        'quantity'=>$pharmacy_online->quantity,
                        'request_type'=>$pharmacy_online->request_type,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'supplier'=>$pharmacy_online->supplier,
                        'remarks'=>$pharmacy_online->remarks,
                        'created_at'=>$pharmacy_online->created_at,
                        'updated_at'=>$pharmacy_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}