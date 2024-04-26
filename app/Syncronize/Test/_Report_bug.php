<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Report_bug extends Model
{  
    public static function report_bug(){
        // syncronize report_bug table from offline to online
        $bug_offline = DB::table('report_bug')->get();  
        foreach($bug_offline as $bug_offline){  
            $bug_offline_count = DB::connection('mysql2')->table('report_bug')->where('bug_id', $bug_offline->bug_id)->get();
                if(count($bug_offline_count) > 0){ 
                    if($bug_offline->updated_at > $bug_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('report_bug')->where('bug_id', $bug_offline->bug_id)->update([    
                            'bug_id'=> $bug_offline->bug_id,
                            'bug_details'=>$bug_offline->bug_details,
                            'image'=>$bug_offline->image,
                            'related_issues'=>$bug_offline->related_issues,
                            'response_status'=>$bug_offline->response_status,
                            'response_remarks'=>$bug_offline->response_remarks,
                            'reported_by'=>$bug_offline->reported_by,
                            'reported_from'=>$bug_offline->reported_from,
                            'status'=>$bug_offline->status,
                            'created_at'=>$bug_offline->created_at,
                            'updated_at'=>$bug_offline->updated_at
                        ]);
                    } 
                    else{
                        DB::table('report_bug')->where('bug_id', $bug_offline_count[0]->bug_id)->update([  
                            'bug_id'=> $bug_offline_count[0]->bug_id,
                            'bug_details'=>$bug_offline_count[0]->bug_details,
                            'image'=>$bug_offline_count[0]->image,
                            'related_issues'=>$bug_offline_count[0]->related_issues,
                            'response_status'=>$bug_offline_count[0]->response_status,
                            'response_remarks'=>$bug_offline_count[0]->response_remarks,
                            'reported_by'=>$bug_offline_count[0]->reported_by,
                            'reported_from'=>$bug_offline_count[0]->reported_from,
                            'status'=>$bug_offline_count[0]->status,
                            'created_at'=>$bug_offline_count[0]->created_at,
                            'updated_at'=>$bug_offline_count[0]->updated_at
                        ]);
                    }  
                }else{
                    DB::connection('mysql2')->table('report_bug')->insert([
                        'bug_id'=> $bug_offline->bug_id,
                        'bug_details'=>$bug_offline->bug_details,
                        'image'=>$bug_offline->image,
                        'related_issues'=>$bug_offline->related_issues,
                        'response_status'=>$bug_offline->response_status,
                        'response_remarks'=>$bug_offline->response_remarks,
                        'reported_by'=>$bug_offline->reported_by,
                        'reported_from'=>$bug_offline->reported_from,
                        'status'=>$bug_offline->status,
                        'created_at'=>$bug_offline->created_at,
                        'updated_at'=>$bug_offline->updated_at
                    ]); 
                } 
        }

        // syncronize report_bug table from online to offline 
        $bug_online = DB::connection('mysql2')->table('report_bug')->get();
        foreach($bug_online as $bug_online){  
            $bug_online_count = DB::table('report_bug')->where('bug_id', $bug_online->bug_id)->get();
                if(count($bug_online_count) > 0){
                    DB::table('report_bug')->where('bug_id', $bug_online->bug_id)->update([
                        'bug_id'=> $bug_online->bug_id,
                        'bug_details'=>$bug_online->bug_details,
                        'image'=>$bug_online->image,
                        'related_issues'=>$bug_online->related_issues,
                        'response_status'=>$bug_online->response_status,
                        'response_remarks'=>$bug_online->response_remarks,
                        'reported_by'=>$bug_online->reported_by,
                        'reported_from'=>$bug_online->reported_from,
                        'status'=>$bug_online->status,
                        'created_at'=>$bug_online->created_at,
                        'updated_at'=>$bug_online->updated_at
                    ]); 
                }else{
                    DB::table('report_bug')->insert([ 
                        'bug_id'=> $bug_online->bug_id,
                        'bug_details'=>$bug_online->bug_details,
                        'image'=>$bug_online->image,
                        'related_issues'=>$bug_online->related_issues,
                        'response_status'=>$bug_online->response_status,
                        'response_remarks'=>$bug_online->response_remarks,
                        'reported_by'=>$bug_online->reported_by,
                        'reported_from'=>$bug_online->reported_from,
                        'status'=>$bug_online->status,
                        'created_at'=>$bug_online->created_at,
                        'updated_at'=>$bug_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}