<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Csr_packages extends Model
{ 
    public static function csr_packages(){ 
        // syncronize csr_packages table from offline to online  
        $csr_offline = DB::table('csr_packages')->get();  
        foreach($csr_offline as $csr_offline){  
            $csr_offline_count = DB::connection('mysql2')->table('csr_packages')->where('sp_id', $csr_offline->sp_id)->get();
                if(count($csr_offline_count) > 0){
                    if($csr_offline->updated_at > $csr_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('csr_packages')->where('sp_id', $csr_offline->sp_id)->update([      
                            'sp_id'=>$csr_offline->sp_id,
                            'package_id'=>$csr_offline->package_id,
                            'csr_id'=>$csr_offline->csr_id,
                            'management_id'=>$csr_offline->management_id,
                            'name'=>$csr_offline->name,
                            'description'=>$csr_offline->description, 
                            'inclusive_item'=>$csr_offline->inclusive_item,
                            'batch'=>$csr_offline->batch,
                            'invoice'=>$csr_offline->invoice,
                            'quantity'=>$csr_offline->quantity,
                            'msrp'=>$csr_offline->msrp,
                            'srp'=>$csr_offline->srp,
                            'unit'=>$csr_offline->unit,
                            'supplier'=>$csr_offline->supplier,
                            'manufactured_date'=>$csr_offline->manufactured_date,
                            'expiration_date'=>$csr_offline->expiration_date,
                            'added_by'=>$csr_offline->added_by,
                            'remarks'=>$csr_offline->remarks,
                            'status'=>$csr_offline->status,
                            'updated_at'=>$csr_offline->updated_at,
                            'created_at'=>$csr_offline->created_at
                        ]);  
                    } 
                    else{
                        DB::table('csr_packages')->where('sp_id', $csr_offline_count[0]->sp_id)->update([  
                            'sp_id'=>$csr_offline_count[0]->sp_id,
                            'package_id'=>$csr_offline_count[0]->package_id,
                            'csr_id'=>$csr_offline_count[0]->csr_id,
                            'management_id'=>$csr_offline_count[0]->management_id,
                            'name'=>$csr_offline_count[0]->name,
                            'description'=>$csr_offline_count[0]->description, 
                            'inclusive_item'=>$csr_offline_count[0]->inclusive_item,
                            'batch'=>$csr_offline_count[0]->batch,
                            'invoice'=>$csr_offline_count[0]->invoice,
                            'quantity'=>$csr_offline_count[0]->quantity,
                            'msrp'=>$csr_offline_count[0]->msrp,
                            'srp'=>$csr_offline_count[0]->srp,
                            'unit'=>$csr_offline_count[0]->unit,
                            'supplier'=>$csr_offline_count[0]->supplier,
                            'manufactured_date'=>$csr_offline_count[0]->manufactured_date,
                            'expiration_date'=>$csr_offline_count[0]->expiration_date,
                            'added_by'=>$csr_offline_count[0]->added_by,
                            'remarks'=>$csr_offline_count[0]->remarks,
                            'status'=>$csr_offline_count[0]->status,
                            'updated_at'=>$csr_offline_count[0]->updated_at,
                            'created_at'=>$csr_offline_count[0]->created_at
                        ]);
                    } 
                }
                else{ 
                    DB::connection('mysql2')->table('csr_packages')->insert([
                        'sp_id'=>$csr_offline->sp_id,
                        'package_id'=>$csr_offline->package_id,
                        'csr_id'=>$csr_offline->csr_id,
                        'management_id'=>$csr_offline->management_id,
                        'name'=>$csr_offline->name,
                        'description'=>$csr_offline->description, 
                        'inclusive_item'=>$csr_offline->inclusive_item,
                        'batch'=>$csr_offline->batch,
                        'invoice'=>$csr_offline->invoice,
                        'quantity'=>$csr_offline->quantity,
                        'msrp'=>$csr_offline->msrp,
                        'srp'=>$csr_offline->srp,
                        'unit'=>$csr_offline->unit,
                        'supplier'=>$csr_offline->supplier,
                        'manufactured_date'=>$csr_offline->manufactured_date,
                        'expiration_date'=>$csr_offline->expiration_date,
                        'added_by'=>$csr_offline->added_by,
                        'remarks'=>$csr_offline->remarks,
                        'status'=>$csr_offline->status,
                        'updated_at'=>$csr_offline->updated_at,
                        'created_at'=>$csr_offline->created_at
                    ]);  
                } 
        } 
     
        // syncronize csr_packages table from online to offline
        $csr_online = DB::connection('mysql2')->table('csr_packages')->get();
        foreach($csr_online as $csr_online){  
            $csr_online_count = DB::table('csr_packages')->where('sp_id', $csr_online->sp_id)->get();
                if(count($csr_online_count) > 0){
                    DB::table('csr_packages')->where('sp_id', $csr_online->sp_id)->update([   
                        'sp_id'=>$csr_online->sp_id,
                        'package_id'=>$csr_online->package_id,
                        'csr_id'=>$csr_online->csr_id,
                        'management_id'=>$csr_online->management_id,
                        'name'=>$csr_online->name,
                        'description'=>$csr_online->description, 
                        'inclusive_item'=>$csr_online->inclusive_item,
                        'batch'=>$csr_online->batch,
                        'invoice'=>$csr_online->invoice,
                        'quantity'=>$csr_online->quantity,
                        'msrp'=>$csr_online->msrp,
                        'srp'=>$csr_online->srp,
                        'unit'=>$csr_online->unit,
                        'supplier'=>$csr_online->supplier,
                        'manufactured_date'=>$csr_online->manufactured_date,
                        'expiration_date'=>$csr_online->expiration_date,
                        'added_by'=>$csr_online->added_by,
                        'remarks'=>$csr_online->remarks,
                        'status'=>$csr_online->status,
                        'updated_at'=>$csr_online->updated_at,
                        'created_at'=>$csr_online->created_at
                    ]); 
                }else{
                    DB::table('csr_packages')->insert([
                        'sp_id'=>$csr_online->sp_id,
                        'package_id'=>$csr_online->package_id,
                        'csr_id'=>$csr_online->csr_id,
                        'management_id'=>$csr_online->management_id,
                        'name'=>$csr_online->name,
                        'description'=>$csr_online->description, 
                        'inclusive_item'=>$csr_online->inclusive_item,
                        'batch'=>$csr_online->batch,
                        'invoice'=>$csr_online->invoice,
                        'quantity'=>$csr_online->quantity,
                        'msrp'=>$csr_online->msrp,
                        'srp'=>$csr_online->srp,
                        'unit'=>$csr_online->unit,
                        'supplier'=>$csr_online->supplier,
                        'manufactured_date'=>$csr_online->manufactured_date,
                        'expiration_date'=>$csr_online->expiration_date,
                        'added_by'=>$csr_online->added_by,
                        'remarks'=>$csr_online->remarks,
                        'status'=>$csr_online->status,
                        'updated_at'=>$csr_online->updated_at,
                        'created_at'=>$csr_online->created_at
                    ]); 
                } 
        } 
        return true;
    } 
}