<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Imaging_center_record extends Model
{ 
    public static function imaging_center_record(){ 
        // syncronize imaging_center_record table from offline to online   
        $imgng_offline = DB::table('imaging_center_record')->get();  
        foreach($imgng_offline as $imgng_offline){  
            $imgng_offline_count = DB::connection('mysql2')->table('imaging_center_record')->where('transaction_id', $imgng_offline->transaction_id)->get();
                if(count($imgng_offline_count) > 0){ 
                    if($imgng_offline->updated_at > $imgng_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('imaging_center_record')->where('transaction_id', $imgng_offline->transaction_id)->update([    
                            'transaction_id'=> $imgng_offline->transaction_id, 
                            'patients_id'=>$imgng_offline->patients_id,
                            'case_file'=>$imgng_offline->case_file,
                            'imaging_order'=>$imgng_offline->imaging_order,
                            'processed_by'=>$imgng_offline->processed_by,
                            'order_type'=>$imgng_offline->order_type,
                            'amount'=>$imgng_offline->amount,
                            'updated_at'=>$imgng_offline->updated_at,
                            'created_at'=>$imgng_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('imaging_center_record')->where('transaction_id', $imgng_offline_count[0]->transaction_id)->update([  
                            'transaction_id'=> $imgng_offline_count[0]->transaction_id, 
                            'patients_id'=>$imgng_offline_count[0]->patients_id,
                            'case_file'=>$imgng_offline_count[0]->case_file,
                            'imaging_order'=>$imgng_offline_count[0]->imaging_order,
                            'processed_by'=>$imgng_offline_count[0]->processed_by,
                            'order_type'=>$imgng_offline_count[0]->order_type,
                            'amount'=>$imgng_offline_count[0]->amount,
                            'updated_at'=>$imgng_offline_count[0]->updated_at,
                            'created_at'=>$imgng_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('imaging_center_record')->insert([ 
                        'transaction_id'=> $imgng_offline->transaction_id, 
                        'patients_id'=>$imgng_offline->patients_id,
                        'case_file'=>$imgng_offline->case_file,
                        'imaging_order'=>$imgng_offline->imaging_order,
                        'processed_by'=>$imgng_offline->processed_by,
                        'order_type'=>$imgng_offline->order_type,
                        'amount'=>$imgng_offline->amount,
                        'updated_at'=>$imgng_offline->updated_at,
                        'created_at'=>$imgng_offline->created_at
                    ]); 
                } 
        }

        // syncronize imaging_center_record table from online to offline 
        $imgng_online = DB::connection('mysql2')->table('imaging_center_record')->get();  
        foreach($imgng_online as $imgng_online){  
            $imgng_online_count = DB::table('imaging_center_record')->where('transaction_id', $imgng_online->transaction_id)->get();
                if(count($imgng_online_count) > 0){
                    DB::table('imaging_center_record')->where('transaction_id', $imgng_online->transaction_id)->update([  
                        'transaction_id'=> $imgng_online->transaction_id, 
                        'patients_id'=>$imgng_online->patients_id,
                        'case_file'=>$imgng_online->case_file,
                        'imaging_order'=>$imgng_online->imaging_order,
                        'processed_by'=>$imgng_online->processed_by,
                        'order_type'=>$imgng_online->order_type,
                        'amount'=>$imgng_online->amount,
                        'updated_at'=>$imgng_online->updated_at,
                        'created_at'=>$imgng_online->created_at
                    ]); 
                }else{
                    DB::table('imaging_center_record')->insert([    
                        'transaction_id'=> $imgng_online->transaction_id, 
                        'patients_id'=>$imgng_online->patients_id,
                        'case_file'=>$imgng_online->case_file,
                        'imaging_order'=>$imgng_online->imaging_order,
                        'processed_by'=>$imgng_online->processed_by,
                        'order_type'=>$imgng_online->order_type,
                        'amount'=>$imgng_online->amount,
                        'updated_at'=>$imgng_online->updated_at,
                        'created_at'=>$imgng_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}