<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Billing extends Model
{ 
    public static function syncronize_billingTable(){
        // syncronize billing table from offline to online 
        $b_offline = DB::table('billing')->get();  
        foreach($b_offline as $b){  
            $b_count = DB::connection('mysql2')->table('billing')->where('b_id', $b->b_id)->get();
                if(count($b_count) > 0){ 
                    if($b->updated_at > $b_count[0]->updated_at){  
                        DB::connection('mysql2')->table('billing')->where('b_id', $b->b_id)->update([  
                            'b_id'=>$b->b_id,
                            'billing_id'=>$b->billing_id,
                            'encoder_id'=>$b->encoder_id,
                            'management_id'=>$b->management_id,
                            'claim_id'=>$b->claim_id,
                            'billing'=>$b->billing,
                            'product_id'=>$b->product_id,
                            'is_package'=>$b->is_package,
                            'brand'=>$b->brand,
                            'amount'=>$b->amount,
                            'quantity'=>$b->quantity,
                            'status'=>$b->status,
                            'is_viewed'=>$b->is_viewed,
                            'release_status'=>$b->release_status,
                            'created_at'=>$b->created_at,
                            'updated_at'=>$b->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('billing')->where('b_id', $b_count[0]->b_id)->update([  
                            'b_id'=>$b_count[0]->b_id,
                            'billing_id'=>$b_count[0]->billing_id,
                            'encoder_id'=>$b_count[0]->encoder_id,
                            'management_id'=>$b_count[0]->management_id,
                            'claim_id'=>$b_count[0]->claim_id,
                            'billing'=>$b_count[0]->billing,
                            'product_id'=>$b_count[0]->product_id,
                            'is_package'=>$b_count[0]->is_package,
                            'brand'=>$b_count[0]->brand,
                            'amount'=>$b_count[0]->amount,
                            'quantity'=>$b_count[0]->quantity,
                            'status'=>$b_count[0]->status,
                            'is_viewed'=>$b_count[0]->is_viewed,
                            'release_status'=>$b_count[0]->release_status,
                            'created_at'=>$b_count[0]->created_at,
                            'updated_at'=>$b_count[0]->updated_at
                        ]);
                    }
                }else{
                    DB::connection('mysql2')->table('billing')->insert([ 
                        'b_id'=>$b->b_id,
                        'billing_id'=>$b->billing_id,
                        'encoder_id'=>$b->encoder_id,
                        'management_id'=>$b->management_id,
                        'claim_id'=>$b->claim_id,
                        'billing'=>$b->billing,
                        'product_id'=>$b->product_id,
                        'is_package'=>$b->is_package,
                        'brand'=>$b->brand,
                        'amount'=>$b->amount,
                        'quantity'=>$b->quantity,
                        'status'=>$b->status,
                        'is_viewed'=>$b->is_viewed,
                        'release_status'=>$b->release_status,
                        'created_at'=>$b->created_at,
                        'updated_at'=>$b->updated_at
                    ]); 
                } 
        }

        // syncronize billing table from online to offline 
        $b_online = DB::connection('mysql2')->table('billing')->get();  
        foreach($b_online as $b_online){  
            $b_online_count = DB::table('billing')->where('b_id', $b_online->b_id)->get();
                if(count($b_online_count) > 0){
                    DB::table('billing')->where('b_id', $b_online->b_id)->update([  
                        'b_id'=>$b_online->b_id,
                        'billing_id'=>$b_online->billing_id,
                        'encoder_id'=>$b_online->encoder_id,
                        'management_id'=>$b_online->management_id,
                        'claim_id'=>$b_online->claim_id,
                        'billing'=>$b_online->billing,
                        'product_id'=>$b_online->product_id,
                        'is_package'=>$b_online->is_package,
                        'brand'=>$b_online->brand,
                        'amount'=>$b_online->amount,
                        'quantity'=>$b_online->quantity,
                        'status'=>$b_online->status,
                        'is_viewed'=>$b_online->is_viewed,
                        'release_status'=>$b_online->release_status,
                        'created_at'=>$b_online->created_at,
                        'updated_at'=>$b_online->updated_at
                    ]);
     
                }else{
                    DB::table('billing')->insert([ 
                        'b_id'=>$b_online->b_id,
                        'billing_id'=>$b_online->billing_id,
                        'encoder_id'=>$b_online->encoder_id,
                        'management_id'=>$b_online->management_id,
                        'claim_id'=>$b_online->claim_id,
                        'billing'=>$b_online->billing,
                        'product_id'=>$b_online->product_id,
                        'is_package'=>$b_online->is_package,
                        'brand'=>$b_online->brand,
                        'amount'=>$b_online->amount,
                        'quantity'=>$b_online->quantity,
                        'status'=>$b_online->status,
                        'is_viewed'=>$b_online->is_viewed,
                        'release_status'=>$b_online->release_status,
                        'created_at'=>$b_online->created_at,
                        'updated_at'=>$b_online->updated_at
                    ]); 
                } 
        } 

        return true;
    }
}