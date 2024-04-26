<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Gtc_cart extends Model
{ 
    public static function gtc_cart(){ 
        // syncronize gtc_cart table from offline to online   
        $gtc_offline = DB::table('gtc_cart')->get();  
        foreach($gtc_offline as $gtc_offline){  
            $gtc_offline_count = DB::connection('mysql2')->table('gtc_cart')->where('cart_id', $gtc_offline->cart_id)->get();
                if(count($gtc_offline_count) > 0){ 
                    if($gtc_offline->updated_at > $gtc_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('gtc_cart')->where('cart_id', $gtc_offline->cart_id)->update([    
                            'cart_id'=> $gtc_offline->cart_id, 
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
                            'rx_number'=>$gtc_offline->rx_number,
                            'doctor_id'=>$gtc_offline->doctor_id,
                            'delivery'=>$gtc_offline->delivery,
                            'status'=>$gtc_offline->status,
                            'order_toID'=>$gtc_offline->order_toID,
                            'created_at'=>$gtc_offline->created_at,
                            'updated_at'=>$gtc_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('gtc_cart')->where('cart_id', $gtc_offline_count[0]->cart_id)->update([  
                            'cart_id'=> $gtc_offline_count[0]->cart_id, 
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
                            'rx_number'=>$gtc_offline_count[0]->rx_number,
                            'doctor_id'=>$gtc_offline_count[0]->doctor_id,
                            'delivery'=>$gtc_offline_count[0]->delivery,
                            'status'=>$gtc_offline_count[0]->status,
                            'order_toID'=>$gtc_offline_count[0]->order_toID,
                            'created_at'=>$gtc_offline_count[0]->created_at,
                            'updated_at'=>$gtc_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('gtc_cart')->insert([    
                        'cart_id'=> $gtc_offline->cart_id, 
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
                        'rx_number'=>$gtc_offline->rx_number,
                        'doctor_id'=>$gtc_offline->doctor_id,
                        'delivery'=>$gtc_offline->delivery,
                        'status'=>$gtc_offline->status,
                        'order_toID'=>$gtc_offline->order_toID,
                        'created_at'=>$gtc_offline->created_at,
                        'updated_at'=>$gtc_offline->updated_at
                    ]); 
                } 
        }

        // syncronize gtc_cart table from online to offline 
        $gtc_online = DB::connection('mysql2')->table('gtc_cart')->get();  
        foreach($gtc_online as $gtc_online){  
            $gtc_online_count = DB::table('gtc_cart')->where('cart_id', $gtc_online->cart_id)->get();
                if(count($gtc_online_count) > 0){
                    DB::table('gtc_cart')->where('cart_id', $gtc_online->cart_id)->update([    
                        'cart_id'=> $gtc_online->cart_id, 
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
                        'rx_number'=>$gtc_online->rx_number,
                        'doctor_id'=>$gtc_online->doctor_id,
                        'delivery'=>$gtc_online->delivery,
                        'status'=>$gtc_online->status,
                        'order_toID'=>$gtc_online->order_toID,
                        'created_at'=>$gtc_online->created_at,
                        'updated_at'=>$gtc_online->updated_at
                    ]); 
                }else{
                    DB::table('gtc_cart')->insert([    
                        'cart_id'=> $gtc_online->cart_id, 
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
                        'rx_number'=>$gtc_online->rx_number,
                        'doctor_id'=>$gtc_online->doctor_id,
                        'delivery'=>$gtc_online->delivery,
                        'status'=>$gtc_online->status,
                        'order_toID'=>$gtc_online->order_toID,
                        'created_at'=>$gtc_online->created_at,
                        'updated_at'=>$gtc_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}