<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Gtc_cart_pharmacy_confirm extends Model
{ 
    public static function gtc_cart_pharmacy_confirm(){ 
        // syncronize gtc_cart_pharmacy_confirm table from offline to online   
        $gtc_offline = DB::table('gtc_cart_pharmacy_confirm')->get();  
        foreach($gtc_offline as $gtc_offline){  
            $gtc_offline_count = DB::connection('mysql2')->table('gtc_cart_pharmacy_confirm')->where('cart_pc', $gtc_offline->cart_pc)->get();
                if(count($gtc_offline_count) > 0){ 
                    if($gtc_offline->updated_at > $gtc_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('gtc_cart_pharmacy_confirm')->where('cart_pc', $gtc_offline->cart_pc)->update([    
                            'cart_pc'=> $gtc_offline->cart_pc, 
                            'order_no'=>$gtc_offline->order_no,
                            'patient_id'=>$gtc_offline->patient_id,
                            'product'=>$gtc_offline->product,
                            'product_id'=>$gtc_offline->product_id,
                            'type'=>$gtc_offline->type,
                            'dosage'=>$gtc_offline->dosage,
                            'quantity'=>$gtc_offline->quantity,
                            'original_qty'=>$gtc_offline->original_qty,
                            'price'=>$gtc_offline->price,
                            'order_status'=>$gtc_offline->order_status,
                            'is_rx'=>$gtc_offline->is_rx,
                            'doctor_id'=>$gtc_offline->doctor_id,
                            'delivery'=>$gtc_offline->delivery,
                            'delivery_fee'=>$gtc_offline->delivery_fee,
                            'status'=>$gtc_offline->status,
                            'order_toID'=>$gtc_offline->order_toID,
                            'vpharma_status'=>$gtc_offline->vpharma_status,
                            'created_at'=>$gtc_offline->created_at,
                            'updated_at'=>$gtc_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('gtc_cart_pharmacy_confirm')->where('cart_pc', $gtc_offline_count[0]->cart_pc)->update([  
                            'cart_pc'=> $gtc_offline_count[0]->cart_pc, 
                            'order_no'=>$gtc_offline_count[0]->order_no,
                            'patient_id'=>$gtc_offline_count[0]->patient_id,
                            'product'=>$gtc_offline_count[0]->product,
                            'product_id'=>$gtc_offline_count[0]->product_id,
                            'type'=>$gtc_offline_count[0]->type,
                            'dosage'=>$gtc_offline_count[0]->dosage,
                            'quantity'=>$gtc_offline_count[0]->quantity,
                            'original_qty'=>$gtc_offline_count[0]->original_qty,
                            'price'=>$gtc_offline_count[0]->price,
                            'order_status'=>$gtc_offline_count[0]->order_status,
                            'is_rx'=>$gtc_offline_count[0]->is_rx,
                            'doctor_id'=>$gtc_offline_count[0]->doctor_id,
                            'delivery'=>$gtc_offline_count[0]->delivery,
                            'delivery_fee'=>$gtc_offline_count[0]->delivery_fee,
                            'status'=>$gtc_offline_count[0]->status,
                            'order_toID'=>$gtc_offline_count[0]->order_toID,
                            'vpharma_status'=>$gtc_offline_count[0]->vpharma_status,
                            'created_at'=>$gtc_offline_count[0]->created_at,
                            'updated_at'=>$gtc_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('gtc_cart_pharmacy_confirm')->insert([    
                        'cart_pc'=> $gtc_offline->cart_pc, 
                        'order_no'=>$gtc_offline->order_no,
                        'patient_id'=>$gtc_offline->patient_id,
                        'product'=>$gtc_offline->product,
                        'product_id'=>$gtc_offline->product_id,
                        'type'=>$gtc_offline->type,
                        'dosage'=>$gtc_offline->dosage,
                        'quantity'=>$gtc_offline->quantity,
                        'original_qty'=>$gtc_offline->original_qty,
                        'price'=>$gtc_offline->price,
                        'order_status'=>$gtc_offline->order_status,
                        'is_rx'=>$gtc_offline->is_rx,
                        'doctor_id'=>$gtc_offline->doctor_id,
                        'delivery'=>$gtc_offline->delivery,
                        'delivery_fee'=>$gtc_offline->delivery_fee,
                        'status'=>$gtc_offline->status,
                        'order_toID'=>$gtc_offline->order_toID,
                        'vpharma_status'=>$gtc_offline->vpharma_status,
                        'created_at'=>$gtc_offline->created_at,
                        'updated_at'=>$gtc_offline->updated_at
                    ]); 
                } 
        }

        // syncronize gtc_cart_pharmacy_confirm table from online to offline 
        $gtc_online = DB::connection('mysql2')->table('gtc_cart_pharmacy_confirm')->get();  
        foreach($gtc_online as $gtc_online){  
            $gtc_online_count = DB::table('gtc_cart_pharmacy_confirm')->where('cart_pc', $gtc_online->cart_pc)->get();
                if(count($gtc_online_count) > 0){
                    DB::table('gtc_cart_pharmacy_confirm')->where('cart_pc', $gtc_online->cart_pc)->update([   
                        'cart_pc'=> $gtc_online->cart_pc, 
                        'order_no'=>$gtc_online->order_no,
                        'patient_id'=>$gtc_online->patient_id,
                        'product'=>$gtc_online->product,
                        'product_id'=>$gtc_online->product_id,
                        'type'=>$gtc_online->type,
                        'dosage'=>$gtc_online->dosage,
                        'quantity'=>$gtc_online->quantity,
                        'original_qty'=>$gtc_online->original_qty,
                        'price'=>$gtc_online->price,
                        'order_status'=>$gtc_online->order_status,
                        'is_rx'=>$gtc_online->is_rx,
                        'doctor_id'=>$gtc_online->doctor_id,
                        'delivery'=>$gtc_online->delivery,
                        'delivery_fee'=>$gtc_online->delivery_fee,
                        'status'=>$gtc_online->status,
                        'order_toID'=>$gtc_online->order_toID,
                        'vpharma_status'=>$gtc_online->vpharma_status,
                        'created_at'=>$gtc_online->created_at,
                        'updated_at'=>$gtc_online->updated_at
                    ]); 
                }else{
                    DB::table('gtc_cart_pharmacy_confirm')->insert([    
                        'cart_pc'=> $gtc_online->cart_pc, 
                        'order_no'=>$gtc_online->order_no,
                        'patient_id'=>$gtc_online->patient_id,
                        'product'=>$gtc_online->product,
                        'product_id'=>$gtc_online->product_id,
                        'type'=>$gtc_online->type,
                        'dosage'=>$gtc_online->dosage,
                        'quantity'=>$gtc_online->quantity,
                        'original_qty'=>$gtc_online->original_qty,
                        'price'=>$gtc_online->price,
                        'order_status'=>$gtc_online->order_status,
                        'is_rx'=>$gtc_online->is_rx,
                        'doctor_id'=>$gtc_online->doctor_id,
                        'delivery'=>$gtc_online->delivery,
                        'delivery_fee'=>$gtc_online->delivery_fee,
                        'status'=>$gtc_online->status,
                        'order_toID'=>$gtc_online->order_toID,
                        'vpharma_status'=>$gtc_online->vpharma_status,
                        'created_at'=>$gtc_online->created_at,
                        'updated_at'=>$gtc_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}