<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Cashier_receipt extends Model
{ 
    public static function cashier_receipt(){ 
        // syncronize cashier_receipt table from offline to online  
        $cashier_offline = DB::table('cashier_receipt')->get();  
        foreach($cashier_offline as $cashier_offline){  
            $cashier_offline_count = DB::connection('mysql2')->table('cashier_receipt')->where('receipt_id', $cashier_offline->receipt_id)->get();
                if(count($cashier_offline_count) > 0){
                    if($cashier_offline->updated_at > $cashier_offline_count[0]->updated_at){
                        DB::connection('mysql2')->table('cashier_receipt')->where('receipt_id', $cashier_offline->receipt_id)->update([      
                            'receipt_id'=>$cashier_offline->receipt_id,
                            'cashier_id'=>$cashier_offline->cashier_id,
                            'management_id'=>$cashier_offline->management_id,
                            'patient_id'=>$cashier_offline->patient_id, 
                            'billing_id'=>$cashier_offline->billing_id,
                            'case_file'=>$cashier_offline->case_file, 
                            'username'=>$cashier_offline->username,
                            'name_customer'=>$cashier_offline->name_customer,
                            'address_customer'=>$cashier_offline->address_customer,
                            'tin_customer'=>$cashier_offline->tin_customer,
                            'quantity'=>$cashier_offline->quantity,
                            'total'=>$cashier_offline->total,
                            'amount_paid'=>$cashier_offline->amount_paid,
                            'payment_change'=>$cashier_offline->payment_change,
                            'invoice'=>$cashier_offline->invoice,
                            'balance'=>$cashier_offline->balance,
                            'payment_status'=>$cashier_offline->payment_status,
                            'status'=>$cashier_offline->status,
                            'category_dept'=>$cashier_offline->category_dept,
                            'created_at'=>$cashier_offline->created_at,
                            'updated_at'=>$cashier_offline->updated_at
                        ]);  
                    } 
                    
                    else{
                        DB::table('cashier_receipt')->where('receipt_id', $cashier_offline_count[0]->receipt_id)->update([  
                            'receipt_id'=>$cashier_offline_count[0]->receipt_id,
                            'cashier_id'=>$cashier_offline_count[0]->cashier_id,
                            'management_id'=>$cashier_offline_count[0]->management_id,
                            'patient_id'=>$cashier_offline_count[0]->patient_id, 
                            'billing_id'=>$cashier_offline_count[0]->billing_id,
                            'case_file'=>$cashier_offline_count[0]->case_file, 
                            'username'=>$cashier_offline_count[0]->username,
                            'name_customer'=>$cashier_offline_count[0]->name_customer,
                            'address_customer'=>$cashier_offline_count[0]->address_customer,
                            'tin_customer'=>$cashier_offline_count[0]->tin_customer,
                            'quantity'=>$cashier_offline_count[0]->quantity,
                            'total'=>$cashier_offline_count[0]->total,
                            'amount_paid'=>$cashier_offline_count[0]->amount_paid,
                            'payment_change'=>$cashier_offline_count[0]->payment_change,
                            'invoice'=>$cashier_offline_count[0]->invoice,
                            'balance'=>$cashier_offline_count[0]->balance,
                            'payment_status'=>$cashier_offline_count[0]->payment_status,
                            'status'=>$cashier_offline_count[0]->status,
                            'category_dept'=>$cashier_offline_count[0]->category_dept,
                            'created_at'=>$cashier_offline_count[0]->created_at,
                            'updated_at'=>$cashier_offline_count[0]->updated_at
                        ]);
                    } 
                }
                
                else{ 
                    DB::connection('mysql2')->table('cashier_receipt')->insert([
                        'receipt_id'=>$cashier_offline->receipt_id,
                        'cashier_id'=>$cashier_offline->cashier_id,
                        'management_id'=>$cashier_offline->management_id,
                        'patient_id'=>$cashier_offline->patient_id, 
                        'billing_id'=>$cashier_offline->billing_id,
                        'case_file'=>$cashier_offline->case_file, 
                        'username'=>$cashier_offline->username,
                        'name_customer'=>$cashier_offline->name_customer,
                        'address_customer'=>$cashier_offline->address_customer,
                        'tin_customer'=>$cashier_offline->tin_customer,
                        'quantity'=>$cashier_offline->quantity,
                        'total'=>$cashier_offline->total,
                        'amount_paid'=>$cashier_offline->amount_paid,
                        'payment_change'=>$cashier_offline->payment_change,
                        'invoice'=>$cashier_offline->invoice,
                        'balance'=>$cashier_offline->balance,
                        'payment_status'=>$cashier_offline->payment_status,
                        'status'=>$cashier_offline->status,
                        'category_dept'=>$cashier_offline->category_dept,
                        'created_at'=>$cashier_offline->created_at,
                        'updated_at'=>$cashier_offline->updated_at
                    ]);  
                } 
        } 
     
        // syncronize cashier_receipt table from online to offline
        $cashier_online = DB::connection('mysql2')->table('cashier_receipt')->get();  
        foreach($cashier_online as $cashier_online){  
            $cashier_online_count = DB::table('cashier_receipt')->where('receipt_id', $cashier_online->receipt_id)->get();
                if(count($cashier_online_count) > 0){
                    DB::table('cashier_receipt')->where('receipt_id', $cashier_online->receipt_id)->update([   
                        'receipt_id'=>$cashier_online->receipt_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'management_id'=>$cashier_online->management_id,
                        'patient_id'=>$cashier_online->patient_id, 
                        'billing_id'=>$cashier_online->billing_id,
                        'case_file'=>$cashier_online->case_file, 
                        'username'=>$cashier_online->username,
                        'name_customer'=>$cashier_online->name_customer,
                        'address_customer'=>$cashier_online->address_customer,
                        'tin_customer'=>$cashier_online->tin_customer,
                        'quantity'=>$cashier_online->quantity,
                        'total'=>$cashier_online->total,
                        'amount_paid'=>$cashier_online->amount_paid,
                        'payment_change'=>$cashier_online->payment_change,
                        'invoice'=>$cashier_online->invoice,
                        'balance'=>$cashier_online->balance,
                        'payment_status'=>$cashier_online->payment_status,
                        'status'=>$cashier_online->status,
                        'category_dept'=>$cashier_online->category_dept,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                }else{
                    DB::table('cashier_receipt')->insert([
                        'receipt_id'=>$cashier_online->receipt_id,
                        'cashier_id'=>$cashier_online->cashier_id,
                        'management_id'=>$cashier_online->management_id,
                        'patient_id'=>$cashier_online->patient_id, 
                        'billing_id'=>$cashier_online->billing_id,
                        'case_file'=>$cashier_online->case_file, 
                        'username'=>$cashier_online->username,
                        'name_customer'=>$cashier_online->name_customer,
                        'address_customer'=>$cashier_online->address_customer,
                        'tin_customer'=>$cashier_online->tin_customer,
                        'quantity'=>$cashier_online->quantity,
                        'total'=>$cashier_online->total,
                        'amount_paid'=>$cashier_online->amount_paid,
                        'payment_change'=>$cashier_online->payment_change,
                        'invoice'=>$cashier_online->invoice,
                        'balance'=>$cashier_online->balance,
                        'payment_status'=>$cashier_online->payment_status,
                        'status'=>$cashier_online->status,
                        'category_dept'=>$cashier_online->category_dept,
                        'created_at'=>$cashier_online->created_at,
                        'updated_at'=>$cashier_online->updated_at
                    ]); 
                } 
        } 
        
        return true;
    } 
}