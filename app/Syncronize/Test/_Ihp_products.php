<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Ihp_products extends Model
{ 
    public static function ihp_products(){ 
        // syncronize ihp_products table from offline to online   
        $ihp_offline = DB::table('ihp_products')->get();  
        foreach($ihp_offline as $ihp_offline){  
            $ihp_offline_count = DB::connection('mysql2')->table('ihp_products')->where('ip_id', $ihp_offline->ip_id)->get();
                if(count($ihp_offline_count) > 0){ 
                    if($ihp_offline->updated_at > $ihp_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('ihp_products')->where('ip_id', $ihp_offline->ip_id)->update([    
                            'ip_id'=> $ihp_offline->ip_id, 
                            'ihp_id'=>$ihp_offline->ihp_id,
                            'serial_number'=>$ihp_offline->serial_number,
                            'sold_by'=>$ihp_offline->sold_by,
                            'patient_id'=>$ihp_offline->patient_id,
                            'sold_date'=>$ihp_offline->sold_date,
                            'created_at'=>$ihp_offline->created_at,
                            'updated_at'=>$ihp_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('ihp_products')->where('ip_id', $ihp_offline_count[0]->ip_id)->update([  
                            'ip_id'=> $ihp_offline_count[0]->ip_id, 
                            'ihp_id'=>$ihp_offline_count[0]->ihp_id,
                            'serial_number'=>$ihp_offline_count[0]->serial_number,
                            'sold_by'=>$ihp_offline_count[0]->sold_by,
                            'patient_id'=>$ihp_offline_count[0]->patient_id,
                            'sold_date'=>$ihp_offline_count[0]->sold_date,
                            'created_at'=>$ihp_offline_count[0]->created_at,
                            'updated_at'=>$ihp_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('ihp_products')->insert([ 
                        'ip_id'=> $ihp_offline->ip_id, 
                        'ihp_id'=>$ihp_offline->ihp_id,
                        'serial_number'=>$ihp_offline->serial_number,
                        'sold_by'=>$ihp_offline->sold_by,
                        'patient_id'=>$ihp_offline->patient_id,
                        'sold_date'=>$ihp_offline->sold_date,
                        'created_at'=>$ihp_offline->created_at,
                        'updated_at'=>$ihp_offline->updated_at
                    ]); 
                } 
        }

        // syncronize ihp_products table from online to offline 
        $ihp_online = DB::connection('mysql2')->table('ihp_products')->get();  
        foreach($ihp_online as $ihp_online){  
            $ihp_online_count = DB::table('ihp_products')->where('ip_id', $ihp_online->ip_id)->get();
                if(count($ihp_online_count) > 0){
                    DB::table('ihp_products')->where('ip_id', $ihp_online->ip_id)->update([  
                        'ip_id'=> $ihp_online->ip_id, 
                        'ihp_id'=>$ihp_online->ihp_id,
                        'serial_number'=>$ihp_online->serial_number,
                        'sold_by'=>$ihp_online->sold_by,
                        'patient_id'=>$ihp_online->patient_id,
                        'sold_date'=>$ihp_online->sold_date,
                        'created_at'=>$ihp_online->created_at,
                        'updated_at'=>$ihp_online->updated_at
                    ]); 
                }else{
                    DB::table('ihp_products')->insert([    
                        'ip_id'=> $ihp_online->ip_id, 
                        'ihp_id'=>$ihp_online->ihp_id,
                        'serial_number'=>$ihp_online->serial_number,
                        'sold_by'=>$ihp_online->sold_by,
                        'patient_id'=>$ihp_online->patient_id,
                        'sold_date'=>$ihp_online->sold_date,
                        'created_at'=>$ihp_online->created_at,
                        'updated_at'=>$ihp_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}