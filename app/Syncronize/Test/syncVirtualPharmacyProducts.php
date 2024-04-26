<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class syncVirtualPharmacyProducts extends Model
{ 
    public static function syncVirtualPharmacyProducts(){
        // syncronize virtual_pharmacy_product table from offline to online
        $offline = DB::table('virtual_pharmacy_product')->get();  
        foreach($offline as $offline){  
            $offline_count = DB::connection('mysql2')->table('virtual_pharmacy_product')->where('vpp_id', $offline->vpp_id)->get();
                if(count($offline_count) > 0){ 
                    if($offline->updated_at > $offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('virtual_pharmacy_product')->where('vpp_id', $offline->vpp_id)->update([    
                            'vpp_id'=> $offline->vpp_id,
                            'product_id'=>$offline->product_id,
                            'pharmacy_id'=>$offline->pharmacy_id,
                            'management_id'=>$offline->management_id,
                            'product'=>$offline->product,
                            'generic'=>$offline->generic,
                            'unit'=>$offline->unit,
                            'amount'=>$offline->amount,
                            'status'=>$offline->status,
                            'created_at'=>$offline->created_at,
                            'updated_at'=>$offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('virtual_pharmacy_product')->where('vpp_id', $offline_count[0]->vpp_id)->update([  
                            'vpp_id'=> $offline_count[0]->vpp_id,
                            'product_id'=>$offline_count[0]->product_id,
                            'pharmacy_id'=>$offline_count[0]->pharmacy_id,
                            'management_id'=>$offline_count[0]->management_id,
                            'product'=>$offline_count[0]->product,
                            'generic'=>$offline_count[0]->generic,
                            'unit'=>$offline_count[0]->unit,
                            'amount'=>$offline_count[0]->amount,
                            'status'=>$offline_count[0]->status,
                            'created_at'=>$offline_count[0]->created_at,
                            'updated_at'=>$offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('virtual_pharmacy_product')->insert([
                        'vpp_id'=> $offline->vpp_id,
                        'product_id'=>$offline->product_id,
                        'pharmacy_id'=>$offline->pharmacy_id,
                        'management_id'=>$offline->management_id,
                        'product'=>$offline->product,
                        'generic'=>$offline->generic,
                        'unit'=>$offline->unit,
                        'amount'=>$offline->amount,
                        'status'=>$offline->status,
                        'created_at'=>$offline->created_at,
                        'updated_at'=>$offline->updated_at
                    ]); 
                } 
        }

        // syncronize virtual_pharmacy_product table from online to offline 
        $online = DB::connection('mysql2')->table('virtual_pharmacy_product')->get();
        foreach($online as $online){  
            $online_count = DB::table('virtual_pharmacy_product')->where('vpp_id', $online->vpp_id)->get();
                if(count($online_count) > 0){
                    DB::table('virtual_pharmacy_product')->where('vpp_id', $online->vpp_id)->update([
                        'vpp_id'=> $online->vpp_id,
                        'product_id'=>$online->product_id,
                        'pharmacy_id'=>$online->pharmacy_id,
                        'management_id'=>$online->management_id,
                        'product'=>$online->product,
                        'generic'=>$online->generic,
                        'unit'=>$online->unit,
                        'amount'=>$online->amount,
                        'status'=>$online->status,
                        'created_at'=>$online->created_at,
                        'updated_at'=>$online->updated_at
                    ]); 
                }else{
                    DB::table('virtual_pharmacy_product')->insert([ 
                        'vpp_id'=> $online->vpp_id,
                        'product_id'=>$online->product_id,
                        'pharmacy_id'=>$online->pharmacy_id,
                        'management_id'=>$online->management_id,
                        'product'=>$online->product,
                        'generic'=>$online->generic,
                        'unit'=>$online->unit,
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