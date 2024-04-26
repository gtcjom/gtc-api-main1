<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class _Doctors_notes extends Model
{ 
    public static function doctors_notes(){ 
        // syncronize doctors_notes table from online to offline 
        $doctor_offline = DB::table('doctors_notes')->get();  
        foreach($doctor_offline as $doctor_offline){  
            $doctor_offline_count = DB::connection('mysql2')->table('doctors_notes')->where('notes_id', $doctor_offline->notes_id)->get();
                if(count($doctor_offline_count) > 0){  
                    if($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at){  
                        DB::connection('mysql2')->table('doctors_notes')->where('notes_id', $doctor_offline->notes_id)->update([ 
                            'notes_id'=>$doctor_offline->notes_id, 
                            'patients_id'=>$doctor_offline->patients_id, 
                            'doctors_id'=>$doctor_offline->doctors_id, 
                            'case_file'=>$doctor_offline->case_file, 
                            'initial_diagnosis'=>$doctor_offline->initial_diagnosis, 
                            'notes'=>$doctor_offline->notes, 
                            'status'=>$doctor_offline->status,
                            'created_at'=>$doctor_offline->created_at,
                            'updated_at'=>$doctor_offline->updated_at
                        ]);
                    } 
                    
                    else{
                        DB::table('doctors_notes')->where('notes_id', $doctor_offline_count[0]->notes_id)->update([ 
                            'notes_id'=>$doctor_offline_count[0]->notes_id, 
                            'patients_id'=>$doctor_offline_count[0]->patients_id, 
                            'doctors_id'=>$doctor_offline_count[0]->doctors_id, 
                            'case_file'=>$doctor_offline_count[0]->case_file, 
                            'initial_diagnosis'=>$doctor_offline_count[0]->initial_diagnosis, 
                            'notes'=>$doctor_offline_count[0]->notes, 
                            'status'=>$doctor_offline_count[0]->status,
                            'created_at'=>$doctor_offline_count[0]->created_at,
                            'updated_at'=>$doctor_offline_count[0]->updated_at
                        ]);
                    }
     
                }else{
                    DB::connection('mysql2')->table('doctors_notes')->insert([  
                        'notes_id'=>$doctor_offline->notes_id, 
                        'patients_id'=>$doctor_offline->patients_id, 
                        'doctors_id'=>$doctor_offline->doctors_id, 
                        'case_file'=>$doctor_offline->case_file, 
                        'initial_diagnosis'=>$doctor_offline->initial_diagnosis, 
                        'notes'=>$doctor_offline->notes, 
                        'status'=>$doctor_offline->status,
                        'created_at'=>$doctor_offline->created_at,
                        'updated_at'=>$doctor_offline->updated_at
                    ]); 
                } 
        } 

        // syncronize doctors_notes table from offline to online 
        $doctor_online = DB::connection('mysql2')->table('doctors_notes')->get();  
        foreach($doctor_online as $doctor_online){  
            $doctor_online_count = DB::table('doctors_notes')->where('notes_id', $doctor_online->notes_id)->get();
                if(count($doctor_online_count) > 0){
                    DB::table('doctors_notes')->where('notes_id', $doctor_online->notes_id)->update([ 
                        'notes_id'=>$doctor_online->notes_id, 
                        'patients_id'=>$doctor_online->patients_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'case_file'=>$doctor_online->case_file, 
                        'initial_diagnosis'=>$doctor_online->initial_diagnosis, 
                        'notes'=>$doctor_online->notes, 
                        'status'=>$doctor_online->status,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]);
                }else{
                    DB::table('doctors_notes')->insert([  
                        'notes_id'=>$doctor_online->notes_id, 
                        'patients_id'=>$doctor_online->patients_id, 
                        'doctors_id'=>$doctor_online->doctors_id, 
                        'case_file'=>$doctor_online->case_file, 
                        'initial_diagnosis'=>$doctor_online->initial_diagnosis, 
                        'notes'=>$doctor_online->notes, 
                        'status'=>$doctor_online->status,
                        'created_at'=>$doctor_online->created_at,
                        'updated_at'=>$doctor_online->updated_at
                    ]); 
                } 
        }   

        return true;
    } 
}