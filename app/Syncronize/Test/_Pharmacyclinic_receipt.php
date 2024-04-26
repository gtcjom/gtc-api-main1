<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Pharmacyclinic_receipt extends Model
{ 
    public static function pharmacyclinic_receipt(){
        // syncronize pharmacyclinic_receipt table from offline to online
        $pharmacy_offline = DB::table('pharmacyclinic_receipt')->get();  
        foreach($pharmacy_offline as $pharmacy_offline){  
            $pharmacy_offline_count = DB::connection('mysql2')->table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_offline->pcr_id)->get();
                if(count($pharmacy_offline_count) > 0){ 
                    if($pharmacy_offline->updated_at > $pharmacy_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_offline->pcr_id)->update([    
                            'pcr_id'=> $pharmacy_offline->pcr_id,
                            'receipt_id'=>$pharmacy_offline->receipt_id,
                            'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                            'management_id'=>$pharmacy_offline->management_id,
                            'username'=>$pharmacy_offline->username,
                            'name_customer'=>$pharmacy_offline->name_customer,
                            'address_customer'=>$pharmacy_offline->address_customer,
                            'tin_customer'=>$pharmacy_offline->tin_customer,
                            'product'=>$pharmacy_offline->product,
                            'description'=>$pharmacy_offline->description,
                            'unit'=>$pharmacy_offline->unit,
                            'quantity'=>$pharmacy_offline->quantity,
                            'srp'=>$pharmacy_offline->srp,
                            'total'=>$pharmacy_offline->total,
                            'amount_paid'=>$pharmacy_offline->amount_paid,
                            'payment_change'=>$pharmacy_offline->payment_change,
                            'dr_no'=>$pharmacy_offline->dr_no,
                            'updated_at'=>$pharmacy_offline->updated_at,
                            'created_at'=>$pharmacy_offline->created_at
                        ]);
                    } 
                    else{
                        DB::table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_offline_count[0]->pcr_id)->update([  
                            'pcr_id'=> $pharmacy_offline_count[0]->pcr_id,
                            'receipt_id'=>$pharmacy_offline_count[0]->receipt_id,
                            'pharmacy_id'=>$pharmacy_offline_count[0]->pharmacy_id,
                            'management_id'=>$pharmacy_offline_count[0]->management_id,
                            'username'=>$pharmacy_offline_count[0]->username,
                            'name_customer'=>$pharmacy_offline_count[0]->name_customer,
                            'address_customer'=>$pharmacy_offline_count[0]->address_customer,
                            'tin_customer'=>$pharmacy_offline_count[0]->tin_customer,
                            'product'=>$pharmacy_offline_count[0]->product,
                            'description'=>$pharmacy_offline_count[0]->description,
                            'unit'=>$pharmacy_offline_count[0]->unit,
                            'quantity'=>$pharmacy_offline_count[0]->quantity,
                            'srp'=>$pharmacy_offline_count[0]->srp,
                            'total'=>$pharmacy_offline_count[0]->total,
                            'amount_paid'=>$pharmacy_offline_count[0]->amount_paid,
                            'payment_change'=>$pharmacy_offline_count[0]->payment_change,
                            'dr_no'=>$pharmacy_offline_count[0]->dr_no,
                            'updated_at'=>$pharmacy_offline_count[0]->updated_at,
                            'created_at'=>$pharmacy_offline_count[0]->created_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('pharmacyclinic_receipt')->insert([
                        'pcr_id'=> $pharmacy_offline->pcr_id,
                        'receipt_id'=>$pharmacy_offline->receipt_id,
                        'pharmacy_id'=>$pharmacy_offline->pharmacy_id,
                        'management_id'=>$pharmacy_offline->management_id,
                        'username'=>$pharmacy_offline->username,
                        'name_customer'=>$pharmacy_offline->name_customer,
                        'address_customer'=>$pharmacy_offline->address_customer,
                        'tin_customer'=>$pharmacy_offline->tin_customer,
                        'product'=>$pharmacy_offline->product,
                        'description'=>$pharmacy_offline->description,
                        'unit'=>$pharmacy_offline->unit,
                        'quantity'=>$pharmacy_offline->quantity,
                        'srp'=>$pharmacy_offline->srp,
                        'total'=>$pharmacy_offline->total,
                        'amount_paid'=>$pharmacy_offline->amount_paid,
                        'payment_change'=>$pharmacy_offline->payment_change,
                        'dr_no'=>$pharmacy_offline->dr_no,
                        'updated_at'=>$pharmacy_offline->updated_at,
                        'created_at'=>$pharmacy_offline->created_at
                    ]); 
                } 
        }

        // syncronize pharmacyclinic_receipt table from online to offline 
        $pharmacy_online = DB::connection('mysql2')->table('pharmacyclinic_receipt')->get();
        foreach($pharmacy_online as $pharmacy_online){  
            $pharmacy_online_count = DB::table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_online->pcr_id)->get();
                if(count($pharmacy_online_count) > 0){
                    DB::table('pharmacyclinic_receipt')->where('pcr_id', $pharmacy_online->pcr_id)->update([
                        'pcr_id'=> $pharmacy_online->pcr_id,
                        'receipt_id'=>$pharmacy_online->receipt_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'username'=>$pharmacy_online->username,
                        'name_customer'=>$pharmacy_online->name_customer,
                        'address_customer'=>$pharmacy_online->address_customer,
                        'tin_customer'=>$pharmacy_online->tin_customer,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'unit'=>$pharmacy_online->unit,
                        'quantity'=>$pharmacy_online->quantity,
                        'srp'=>$pharmacy_online->srp,
                        'total'=>$pharmacy_online->total,
                        'amount_paid'=>$pharmacy_online->amount_paid,
                        'payment_change'=>$pharmacy_online->payment_change,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'updated_at'=>$pharmacy_online->updated_at,
                        'created_at'=>$pharmacy_online->created_at
                    ]); 
                }else{
                    DB::table('pharmacyclinic_receipt')->insert([ 
                        'pcr_id'=> $pharmacy_online->pcr_id,
                        'receipt_id'=>$pharmacy_online->receipt_id,
                        'pharmacy_id'=>$pharmacy_online->pharmacy_id,
                        'management_id'=>$pharmacy_online->management_id,
                        'username'=>$pharmacy_online->username,
                        'name_customer'=>$pharmacy_online->name_customer,
                        'address_customer'=>$pharmacy_online->address_customer,
                        'tin_customer'=>$pharmacy_online->tin_customer,
                        'product'=>$pharmacy_online->product,
                        'description'=>$pharmacy_online->description,
                        'unit'=>$pharmacy_online->unit,
                        'quantity'=>$pharmacy_online->quantity,
                        'srp'=>$pharmacy_online->srp,
                        'total'=>$pharmacy_online->total,
                        'amount_paid'=>$pharmacy_online->amount_paid,
                        'payment_change'=>$pharmacy_online->payment_change,
                        'dr_no'=>$pharmacy_online->dr_no,
                        'updated_at'=>$pharmacy_online->updated_at,
                        'created_at'=>$pharmacy_online->created_at
                    ]); 
                } 
        }   

        return true;
    } 
}