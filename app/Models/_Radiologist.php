<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;

class _Radiologist extends Model
{
    public static function getRadiologistId($userid){
        return DB::table('radiologist')->where('user_id', $userid)->first();
    }
    
    public static function getPatientForReview($data){
        $type = $data['type'];
        $management_id = $data['management_id'];
        $id = _Radiologist::getRadiologistId($data['user_id'])->radiologist_id;

        $query = "SELECT imaging_order, imaging_center_id, imaging_result_attachment, created_at, 
            (SELECT type FROM imaging_order_menu WHERE imaging_order_menu.order_desc = imaging_center.imaging_order LIMIT 1) as Order_Type,
            (SELECT concat(lastname,', ',firstname) FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_name,
            (SELECT image FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_image,
            (SELECT gender FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_gender,
            (SELECT birthday FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_birthday,
            (SELECT concat(street,' ',barangay,' ',city) FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_address
        FROM imaging_center WHERE imaging_center.radiologist = '$id' AND imaging_center.manage_by = '$management_id' AND imaging_center.imaging_result IS NULL HAVING Order_Type = '$type' ORDER BY created_at ASC";
            
        $result = DB::getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function getPatientReviewed($data){
        $id = _Radiologist::getRadiologistId($data['user_id'])->radiologist_id;

        $query = "SELECT created_at, imaging_order, imaging_center_id,
        (SELECT concat(lastname,', ',firstname) from patients where patients.patient_id = imaging_center.patients_id) as patient_name
        from imaging_center where radiologist = '$id' and imaging_result is not null order by created_at desc";
            
        $result = DB::getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getOrderDetails($data){
        $query = "SELECT imaging_order, imaging_center_id, imaging_result_attachment, created_at, 
        (SELECT concat(firstname,' ',lastname) from patients where patients.patient_id = imaging_center.patients_id) as patient_name,
        (SELECT gender from patients where patients.patient_id = imaging_center.patients_id) as patient_gender,
        (SELECT birthday from patients where patients.patient_id = imaging_center.patients_id) as patient_birthday,
        (SELECT concat(street,' ',barangay,' ',city) from patients where patients.patient_id = imaging_center.patients_id) as patient_address
        from imaging_center where imaging_center_id = '".$data['orderId']."' ";
            
        $result = DB::getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function saveOrderResult($data){
        date_default_timezone_set('Asia/Manila');
        $qry = DB::connection('mysql')->table('imaging_center')->where('imaging_center_id', $data['orderId'])->first();
        
        // if $saveResult
        DB::table('imaging_center')
        ->where('imaging_center_id', $data['orderId']) 
        ->update([  
            'is_processed' => 1,
            'is_viewed' => 3,
            'end_time' => date('Y-m-d H:i:s'),
            'imaging_result' => $data['result'],
            'imaging_results_remarks' => $data['impression'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // if($saveResult){
        //     $nullCount = DB::connection('mysql')->table('imaging_center')->where('patients_id', $qry->patients_id)->whereNull('imaging_result')->get();
        //     if(count($nullCount) < 1){
        //         DB::table('patient_queue')
        //         ->where('patient_id', $qry->patients_id)
        //         ->where('type', 'imaging')
        //         ->delete();
        //     }
        // }

        return DB::connection('mysql')->table('doctors_notification')->insert([
            'notif_id' => 'nid-'.rand(0, 99999),
            'order_id' => $qry->imaging_center_id,
            'patient_id' => $qry->patients_id,
            'doctor_id' => $qry->doctors_id,
            'category' => 'imaging',
            'department' => 'processed',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new processed order from local imaging.',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]); 
    }

    public static function getPatientReviewedByDate($data){
        $date_from = date('Y-m-d 00:00:00', strtotime($data['from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['to']));


        $id = _Radiologist::getRadiologistId($data['user_id'])->radiologist_id;

        $query = "SELECT created_at, imaging_order, imaging_center_id,
        (SELECT concat(firstname,' ',lastname) from patients where patients.patient_id = imaging_center.patients_id) as patient_name
        from imaging_center where radiologist = '$id' and created_at >= '$date_from' and created_at <= '$date_to' and imaging_result is not null order by created_at desc";
            
        $result = DB::getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function radiologistGetHeaderInfo($data){
        // return DB::table('radiologist')
        // ->select('r_id', 'name', 'image', 'address')
        // ->where('user_id', $data['user_id'])
        // ->first();

        return DB::table('radiologist')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'radiologist.user_id')
            ->select('radiologist.r_id', 'radiologist.name', 'radiologist.image', 'radiologist.address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('radiologist.user_id', $data['user_id'])
            ->first();
    }

    public static function hisradGetPersonalInfoById($data){
        $query = "SELECT * FROM radiologist WHERE user_id = '".$data['user_id']."' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function hisradUploadProfile($data, $filename){
        date_default_timezone_set('Asia/Manila');
        return DB::table('radiologist')
        ->where('user_id', $data['user_id'])
        ->update([
            'image' => $filename,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
    
    public static function hisradUpdatePersonalInfo($data){
        return DB::table('radiologist')
        ->where('user_id', $data['user_id'])
        ->update([
            'name' => $data['fullname'],
            'address' => $data['address'],
            'email' => $data['email'],  
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function hisradUpdateUsername($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
        ->where('user_id', $data['user_id'])
        ->update([ 
            'username' => $data['new_username'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function hisradUpdatePassword($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
        ->where('user_id', $data['user_id'])
        ->update([ 
            'password' => Hash::make($data['new_password']),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function getPatientForReviewUltraSound($data){
        $type = $data['type'];
        $management_id = $data['management_id'];
        $id = _Radiologist::getRadiologistId($data['user_id'])->radiologist_id;

        $query = "SELECT imaging_order, imaging_center_id, imaging_result_attachment, created_at, 
            (SELECT type FROM imaging_order_menu WHERE imaging_order_menu.order_desc = imaging_center.imaging_order LIMIT 1) as Order_Type,
            (SELECT concat(lastname,', ',firstname) FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_name,
            (SELECT image FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_image,
            (SELECT gender FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_gender,
            (SELECT birthday FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_birthday,
            (SELECT concat(street,' ',barangay,' ',city) FROM patients WHERE patients.patient_id = imaging_center.patients_id) as patient_address
        FROM imaging_center WHERE imaging_center.radiologist = '$id' AND imaging_center.manage_by = '$management_id' AND imaging_center.imaging_result IS NULL HAVING Order_Type = '$type' ORDER BY created_at ASC ";
            
        $result = DB::getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }
    

    public static function saveOrderUltraSoundResult($data){
        date_default_timezone_set('Asia/Manila');
        $qry = DB::connection('mysql')->table('imaging_center')->where('imaging_center_id', $data['orderId'])->first();
        
        DB::table('imaging_center')
        ->where('imaging_center_id', $data['orderId']) 
        ->update([  
            'is_processed' => 1,
            'is_viewed' => 3,
            'end_time' => date('Y-m-d H:i:s'),
            'imaging_result' => $data['result'],
            'imaging_results_remarks' => $data['impression'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return DB::connection('mysql')->table('doctors_notification')->insert([
            'notif_id' => 'nid-'.rand(0, 99999),
            'order_id' => $qry->imaging_center_id,
            'patient_id' => $qry->patients_id,
            'doctor_id' => $qry->doctors_id,
            'category' => 'imaging',
            'department' => 'processed',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new processed order from local imaging.',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]); 
    }
    
}
