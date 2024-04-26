<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Laboratory_test extends Model
{ 
    public static function laboratory_test(){ 
        // syncronize laboratory_test table from offline to online   
        $lab_offline = DB::table('laboratory_test')->get();  
        foreach($lab_offline as $lab_offline){  
            $lab_offline_count = DB::connection('mysql2')->table('laboratory_test')->where('lt_id', $lab_offline->lt_id)->get();
                if(count($lab_offline_count) > 0){ 
                    if($lab_offline->updated_at > $lab_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('laboratory_test')->where('lt_id', $lab_offline->lt_id)->update([    
                            'lt_id'=> $lab_offline->lt_id, 
                            'laboratory_id'=>$lab_offline->laboratory_id,
                            'laboratory_test'=>$lab_offline->laboratory_test,
                            'laboratory_rate'=>$lab_offline->laboratory_rate,
                            'department'=>$lab_offline->department,
                            'status'=>$lab_offline->status,
                            'created_at'=>$lab_offline->created_at,
                            'updated_at'=>$lab_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('laboratory_test')->where('lt_id', $lab_offline_count[0]->lt_id)->update([  
                            'lt_id'=> $lab_offline_count[0]->lt_id, 
                            'laboratory_id'=>$lab_offline_count[0]->laboratory_id,
                            'laboratory_test'=>$lab_offline_count[0]->laboratory_test,
                            'laboratory_rate'=>$lab_offline_count[0]->laboratory_rate,
                            'department'=>$lab_offline_count[0]->department,
                            'status'=>$lab_offline_count[0]->status,
                            'created_at'=>$lab_offline_count[0]->created_at,
                            'updated_at'=>$lab_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('laboratory_test')->insert([ 
                        'lt_id'=> $lab_offline->lt_id, 
                        'laboratory_id'=>$lab_offline->laboratory_id,
                        'laboratory_test'=>$lab_offline->laboratory_test,
                        'laboratory_rate'=>$lab_offline->laboratory_rate,
                        'department'=>$lab_offline->department,
                        'status'=>$lab_offline->status,
                        'created_at'=>$lab_offline->created_at,
                        'updated_at'=>$lab_offline->updated_at
                    ]); 
                } 
        }

        // syncronize laboratory_test table from online to offline 
        $lab_online = DB::connection('mysql2')->table('laboratory_test')->get();  
        foreach($lab_online as $lab_online){  
            $lab_online_count = DB::table('laboratory_test')->where('lt_id', $lab_online->lt_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('laboratory_test')->where('lt_id', $lab_online->lt_id)->update([  
                        'lt_id'=> $lab_online->lt_id, 
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'laboratory_test'=>$lab_online->laboratory_test,
                        'laboratory_rate'=>$lab_online->laboratory_rate,
                        'department'=>$lab_online->department,
                        'status'=>$lab_online->status,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                }else{
                    DB::table('laboratory_test')->insert([    
                        'lt_id'=> $lab_online->lt_id, 
                        'laboratory_id'=>$lab_online->laboratory_id,
                        'laboratory_test'=>$lab_online->laboratory_test,
                        'laboratory_rate'=>$lab_online->laboratory_rate,
                        'department'=>$lab_online->department,
                        'status'=>$lab_online->status,
                        'created_at'=>$lab_online->created_at,
                        'updated_at'=>$lab_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}