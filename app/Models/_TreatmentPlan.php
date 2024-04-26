<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class _TreatmentPlan extends Model
{
    public static function getTreatmentPlan($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_treatment_plan')
        ->where('doctors_id', $data['user_id'])
        ->where('patient_id', $data['patient_id'])
        ->where('type', $data['type'])
        ->where('status', 1)
        ->orderBy('id', 'desc')->get();
    }

    public static function saveTreatmentPlan($data){

        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_treatment_plan')->insert([
            'dtp_id' => 'dtp-'.rand(0, 9999).time(),
            'treatment_id' => 'treatment-'.rand(0, 999).time(),
             'management_id' => $data['management_id'],
             'doctors_id' => $data['user_id'],
             'patient_id' => $data['patient_id'],
             'treatment_plan' => $data['treatmentplan'],
             'date' => date('Y-m-d h:i:s', strtotime($data['plan_date'])),
             'status' => 1,
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s'),
        ]); 
    }

    public static function updateTreatmentPlan($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_treatment_plan')
            ->where('doctors_id', $data['user_id'])
            ->where('id', $data['id'])
            ->update([  
             'treatment_plan' => $data['treatmentplan'], 
             'updated_at' => date('Y-m-d H:i:s'),
        ]); 
    }

    public static function deleteTreatmentPlan($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_treatment_plan')
            ->where('doctors_id', $data['user_id'])
            ->where('id', $data['id'])
            ->update([  
             'status' => 0, 
             'updated_at' => date('Y-m-d H:i:s'),
        ]); 
    }

    public static function canvasTreatmentPlan($data, $filename){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_treatment_plan')->insert([
            'dtp_id' => 'dtp-'.rand(0, 9999).time(),
            'treatment_id' => 'treatment-'.rand(0, 999).time(),
             'management_id' => $data['management_id'],
             'doctors_id' => $data['user_id'],
             'patient_id' => $data['patient_id'],
             'treatment_plan' => $filename,
             'type' => 'file',
             'date' => date('Y-m-d h:i:s', strtotime($data['plan_date'])),
             'status' => 1,
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s'),
        ]); 
    }
    
}
