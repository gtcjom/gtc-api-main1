<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Management extends Model
{ 
    public static function management(){ 
        // syncronize management table from offline to online   
        $mgt_offline = DB::table('management')->get();  
        foreach($mgt_offline as $mgt_offline){  
            $mgt_offline_count = DB::connection('mysql2')->table('management')->where('m_id', $mgt_offline->m_id)->get();
                if(count($mgt_offline_count) > 0){ 
                    if($mgt_offline->updated_at > $mgt_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('management')->where('m_id', $mgt_offline->m_id)->update([    
                            'm_id'=> $mgt_offline->m_id, 
                            'management_id'=>$mgt_offline->management_id,
                            'user_id'=>$mgt_offline->user_id,
                            'name'=>$mgt_offline->name,
                            'address'=>$mgt_offline->address,
                            'tin'=>$mgt_offline->tin,
                            'business_style'=>$mgt_offline->business_style,
                            'tax_type'=>$mgt_offline->tax_type,
                            'logo'=>$mgt_offline->logo,
                            'header'=>$mgt_offline->header,
                            'created_at'=>$mgt_offline->created_at,
                            'updated_at'=>$mgt_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('management')->where('m_id', $mgt_offline_count[0]->m_id)->update([  
                            'm_id'=> $mgt_offline_count[0]->m_id, 
                            'management_id'=>$mgt_offline_count[0]->management_id,
                            'user_id'=>$mgt_offline_count[0]->user_id,
                            'name'=>$mgt_offline_count[0]->name,
                            'address'=>$mgt_offline_count[0]->address,
                            'tin'=>$mgt_offline_count[0]->tin,
                            'business_style'=>$mgt_offline_count[0]->business_style,
                            'tax_type'=>$mgt_offline_count[0]->tax_type,
                            'logo'=>$mgt_offline_count[0]->logo,
                            'header'=>$mgt_offline_count[0]->header,
                            'created_at'=>$mgt_offline_count[0]->created_at,
                            'updated_at'=>$mgt_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('management')->insert([ 
                        'm_id'=> $mgt_offline->m_id, 
                        'management_id'=>$mgt_offline->management_id,
                        'user_id'=>$mgt_offline->user_id,
                        'name'=>$mgt_offline->name,
                        'address'=>$mgt_offline->address,
                        'tin'=>$mgt_offline->tin,
                        'business_style'=>$mgt_offline->business_style,
                        'tax_type'=>$mgt_offline->tax_type,
                        'logo'=>$mgt_offline->logo,
                        'header'=>$mgt_offline->header,
                        'created_at'=>$mgt_offline->created_at,
                        'updated_at'=>$mgt_offline->updated_at
                    ]); 
                } 
        }

        // syncronize management table from online to offline 
        $mgt_online = DB::connection('mysql2')->table('management')->get();  
        foreach($mgt_online as $mgt_online){  
            $lab_online_count = DB::table('management')->where('m_id', $mgt_online->m_id)->get();
                if(count($lab_online_count) > 0){
                    DB::table('management')->where('m_id', $mgt_online->m_id)->update([  
                        'm_id'=> $mgt_online->m_id, 
                        'management_id'=>$mgt_online->management_id,
                        'user_id'=>$mgt_online->user_id,
                        'name'=>$mgt_online->name,
                        'address'=>$mgt_online->address,
                        'tin'=>$mgt_online->tin,
                        'business_style'=>$mgt_online->business_style,
                        'tax_type'=>$mgt_online->tax_type,
                        'logo'=>$mgt_online->logo,
                        'header'=>$mgt_online->header,
                        'created_at'=>$mgt_online->created_at,
                        'updated_at'=>$mgt_online->updated_at
                    ]); 
                }else{
                    DB::table('management')->insert([    
                        'm_id'=> $mgt_online->m_id, 
                        'management_id'=>$mgt_online->management_id,
                        'user_id'=>$mgt_online->user_id,
                        'name'=>$mgt_online->name,
                        'address'=>$mgt_online->address,
                        'tin'=>$mgt_online->tin,
                        'business_style'=>$mgt_online->business_style,
                        'tax_type'=>$mgt_online->tax_type,
                        'logo'=>$mgt_online->logo,
                        'header'=>$mgt_online->header,
                        'created_at'=>$mgt_online->created_at,
                        'updated_at'=>$mgt_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}