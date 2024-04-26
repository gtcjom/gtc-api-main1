<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;
use App\_Doctor;

class _Imaging extends Model
{
    public static function getImagingId($user_id){
        return DB::table('imaging')->where('user_id', $user_id)->first();
    }

    public static function imagingDetails($management_id){
        return DB::table('imaging')->where('management_id', $management_id)->groupBy('imaging_id')->get();
    }

    public static function imagingByPatient($patient_id){
        return DB::table('imaging_center')->where('patients_id', $patient_id)->get();
    }

    public static function getImagingIdByMgntVirtual($management_id){ // get imaging id virtaul using managmenet id
        return DB::connection('mysql2')->table('imaging')->where('management_id', $management_id)->first();
    }

    public static function createOrder($data){

        date_default_timezone_set('Asia/Manila');
        $imaging_center_id = 'imaging-'.rand(0, 99999);

        $order = implode(', ', $data['order']);
        $imaging_id = $data['connection'] == 'online' ? 
                _Imaging::getImagingIdByMgntVirtual($data['imaging_center'])->imaging_id
            :   $data['imaging_center'];
        $order_from  = $data['connection'] == 'online'  ? 'virtual' : 'local';

        if($data['connection'] == 'online'){
            // insert patients online if not exist in virtual
            $query = DB::connection('mysql2')
                    ->table('patients')
                    ->where('patient_id', $data['patient_id'])
                    ->get();

            if(count($query) > 0){ }
            else{
                $pats = DB::connection('mysql')
                    ->table('patients')
                    ->where('patient_id', $data['patient_id'])
                    ->first();

                DB::connection('mysql2')->table('patients')->insert([
                    'patient_id' => $pats->patient_id,
                    'encoders_id' => $pats->encoders_id,
                    'doctors_id' => $pats->doctors_id,
                    'management_id' => $pats->management_id,
                    'user_id' => $pats->user_id,
                    'firstname' => $pats->firstname,
                    'lastname' => $pats->lastname,
                    'middle' => $pats->middle,
                    'email' => $pats->email,
                    'mobile' => $pats->mobile,
                    'telephone' => $pats->telephone,
                    'birthday' => $pats->birthday,
                    'birthplace' => $pats->birthplace,
                    'gender' => $pats->gender,
                    'civil_status' => $pats->civil_status,
                    'religion' => $pats->religion,
                    'height' => $pats->height,
                    'weight' => $pats->weight,
                    'occupation' => $pats->occupation,
                    'street' => $pats->street,
                    'barangay' => $pats->barangay,
                    'city' => $pats->city,
                    'tin' => $pats->tin,
                    'created_at' => $pats->created_at,
                    'updated_at' => $pats->updated_at
                ]);
            }
        }

        DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')
        ->table('patients_notification')
        ->insert([
            'notif_id' => 'nid-'.rand(0, 99).time(),
            'order_id' => $imaging_center_id,
            'patient_id' => $data['patient_id'],
            'doctor_id' => _Doctor::getDoctorsId($data['doctors_id'])->doctors_id,
            'category' => 'imaging',
            'department' => 'doctor-imaging',
            'message' => 'new imaging test added by doctor',
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('imaging_center')->insert([
            'imaging_center_id' => $imaging_center_id,
            'patients_id' => $data['patient_id'],
            'doctors_id' => _Doctor::getDoctorsId($data['doctors_id'])->doctors_id,
            'imaging_order' => $order,
            'imaging_remarks' => $data['remarks'],
            'imaging_center' => $imaging_id,    
            'number_shots' => $data['totalshot'],
            'is_processed' => 0,
            'is_viewed' => 1,
            'is_pending' => 0,
            'order_from' => $order_from,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function imagingCounts($data){
        $orderfrom = $data['connection']=='online' ? 'virtual' : 'local';
        $query = "SELECT imaging_center_id,
        (SELECT count(id) from imaging_center where imaging_center.patients_id = '".$data['patient_id']."' and order_from ='$orderfrom' and is_processed = 1 and imaging_result is not null) as count_processed,
        (SELECT count(id) from imaging_center where imaging_center.patients_id = '".$data['patient_id']."' and order_from ='$orderfrom' and is_processed = 1 and is_pending = 0 and imaging_result is null) as count_ongoing,
        (SELECT count(id) from imaging_center where imaging_center.patients_id = '".$data['patient_id']."' and order_from ='$orderfrom' and is_processed = 0 and is_pending = 0 and imaging_result is null) as count_unprocess,
        (SELECT count(id) from imaging_center where imaging_center.patients_id = '".$data['patient_id']."' and order_from ='$orderfrom' and is_pending = 1) as count_pending
        from imaging_center where imaging_center.patients_id = '".$data['patient_id']."' limit 1";
        
        $result = DB::connection($data['connection']=='online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function imagingUnprocess($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('imaging_center')
            ->where('patients_id', $data['patient_id'])
            ->whereNull('imaging_result')
            ->where('is_processed', 0) 
            ->where('order_from', $data['connection'] == 'online' ? 'virtual' : 'local')
            ->where('is_pending', 0)->get();
    }  

    public static function imagingOngoing($data){
        return DB::connection($data['connection']=='online' ? 'mysql2' : 'mysql')
            ->table('imaging_center')
            ->where('patients_id', $data['patient_id'])
            ->whereNull('imaging_result')
            ->where('order_from', $data['connection'] == 'online' ? 'virtual' : 'local')
            ->where('is_processed', 1)
            ->where('is_pending', 0)->get();
    }

    public static function imagingProcessed($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('imaging_center')
            ->where('patients_id', $data['patient_id'])
            ->whereNotNull('imaging_result')
            ->where('is_processed', 1)
            ->where('order_from', $data['connection'] == 'online' ? 'virtual' : 'local')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function imagingPending($data){
        return DB::connection($data['connection']=='online' ? 'mysql2' : 'mysql')
            ->table('imaging_center')
            ->where('patients_id', $data['patient_id'])
            ->whereNull('imaging_result')
            ->where('is_processed', 0)
            ->where('order_from', $data['connection'] == 'online' ? 'virtual' : 'local')
            ->where('is_pending', 1)->get();
    }

    public static function imagingOrderDetails($data){

        date_default_timezone_set('Asia/Manila');
        
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->where('order_id', $data['imaging_id'])
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('imaging_center')
        ->where('imaging_center_id', $data['imaging_id'])
        ->get();
    }

    public static function getOngoingOrder($data){
        return DB::connection($data['connection']=='online' ? 'mysql2' : 'mysql')
            ->table('imaging_center')
            ->where('patients_id', $data['patient_id'])
            ->whereNull('imaging_result')
            ->where('is_processed', 1)
            ->where('is_pending', 0)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getNewOrder($imaging_id){
        $query = "SELECT imaging_center_id, imaging_order, created_at, imaging_remarks,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = imaging_center.patients_id ) as patient_name,
        (SELECT concat(name) from doctors where doctors.doctors_id = imaging_center.doctors_id ) as doctors_name
        from imaging_center where imaging_center.imaging_center = '$imaging_id' and is_processed = 0 and is_pending = 0 and imaging_result is null";
        
        $result = DB::getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function getPendingOrder($imaging_id){
        $query = "SELECT imaging_center_id, imaging_order, created_at, imaging_remarks, pending_reason, pending_date,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = imaging_center.patients_id ) as patient_name,
        (SELECT concat(name) from doctors where doctors.doctors_id = imaging_center.doctors_id ) as doctors_name
        from imaging_center where imaging_center.imaging_center = '$imaging_id' and is_processed = 0 and is_pending = 1 and imaging_result is null order by created_at desc";
        
        $result = DB::getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getProcessingOrder($imaging_id){
        $query = "SELECT imaging_center_id, imaging_order, created_at, imaging_remarks, start_time,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = imaging_center.patients_id ) as patient_name,
        (SELECT concat(name) from doctors where doctors.doctors_id = imaging_center.doctors_id ) as doctors_name
        from imaging_center where imaging_center.imaging_center = '$imaging_id' and is_processed = 1 and is_pending = 0 and imaging_result is null and imaging_result_attachment is null";
        
        $result = DB::getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }


    public static function orderSetProcess($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('imaging_center')->where('imaging_center_id', $data['imaging_center_id'])->update([
            'is_processed' => 1,
            'is_pending' => 0,
            'processed_by' => $data['user_id'],
            'start_time' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
 
    public static function orderSetPending($data){
        date_default_timezone_set('Asia/Manila');

        $qry = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('imaging_center')->where('imaging_center_id', $data['imaging_center_id'])->first();

        DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_notification')->insert([
            'notif_id' => 'nid-'.rand(0, 99999),
            'order_id' => $qry->imaging_center_id,
            'patient_id' => $qry->patients_id,
            'doctor_id' => $qry->doctors_id,
            'category' => 'imaging',
            'department' => 'pending',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new pending order from local imaging.',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]); 

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('imaging_center')->where('imaging_center_id', $data['imaging_center_id'])->update([
            'is_processed' => 0,
            'is_pending' => 1,
            'pending_by' => $data['user_id'],
            'pending_date' => date('Y-m-d H:i:s'),
            'pending_reason' => $data['pending_reason'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function addResult($data, $filename){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('imaging_center')->where('imaging_center_id', $data['imaging_center_id'])->update([ 
            'is_processed' => 1, 
            'imaging_result' =>$data['result'],
            'imaging_results_remarks' =>$data['remarks'],
            'imaging_result_attachment' =>$filename,
            'is_viewed'=>0, 
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }

    public static function getAllRecords($imaging_id){  
        $query = "SELECT *,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = imaging_center.patients_id ) as patient_name,
        (SELECT concat(name) from doctors where doctors.doctors_id = imaging_center.doctors_id ) as doctors_name
        from imaging_center where imaging_center.imaging_center = '$imaging_id' and order_from ='local' order  by created_at desc ";
         
        $result = DB::getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getCounts($imaging_id){
        $query = "SELECT id,
        (SELECT IFNULL(count(id), 0) from imaging_center where imaging_center.imaging_center = '$imaging_id' and is_processed = 0 and is_pending = 0 and order_from ='local' and imaging_result is null ) as newCount,
        (SELECT IFNULL(count(id), 0) from imaging_center where imaging_center.imaging_center = '$imaging_id' and is_processed = 1 and is_pending = 0 and order_from ='local' and imaging_result is null and imaging_result_attachment is null) as processingCount,
        (SELECT IFNULL(count(id), 0) from imaging_center where imaging_center.imaging_center = '$imaging_id' and is_processed = 0 and is_pending = 1 and order_from ='local' and imaging_result is null ) as pendingCount
        from imaging_center where imaging_center.order_from ='local' and imaging_center.imaging_center = '$imaging_id' limit 1 ";
        
        $result = DB::getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getImagingOrderReadByTelerad($data){ 

        $imaging_id = _Imaging::getImagingId($data['user_id'])->imaging_id;
        $query = "SELECT *,
        (SELECT concat(firstname,' ', lastname) from patients where patients.patient_id = patients_id) as patient_name,
        (SELECT name from teleradiologist where teleradiologist.telerad_id = radiologist) as telerad
        from  imaging_center where imaging_center = '$imaging_id' and radiologist_type = 'Telerad' order by created_at desc";
        
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  // use when imaging install in liveserver
        // $result = DB::connection('mysql2')->getPdo()->prepare($query); use when imaging is install locally
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getTeleradist($data) { 
        $query = "SELECT `user_id` as telerad_userid, telerad_id, name, address, birthday, gender from teleradiologist where status = 1";
        
        $result = DB::connection('mysql2')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }


    public static function getTeleradConversation($data){
        date_default_timezone_set('Asia/Manila');  
        $senders_id = $data['telerad_userid'];
        $receivers_id = $data['user_id'];  

        DB::connection('mysql2')->table('teleradiologist_chat')
            ->where('sender_user_id' ,$senders_id)
            ->where( 'receiver_user_id', $receivers_id)
            ->update([
                'is_viewed' => 1, 
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        $query = "SELECT * from teleradiologist_chat where 
        sender_user_id = '$senders_id'
        and 
        receiver_user_id = '$receivers_id' 
        OR 
        sender_user_id = '$receivers_id' 
        and
        receiver_user_id ='$senders_id' ";
       
        $result = DB::connection('mysql2')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function sendMessage($data){
        date_default_timezone_set('Asia/Manila');   

        return DB::connection('mysql2')->table('teleradiologist_chat')->insert([
            'chat_id' => 'chat-'.rand(0, 9999).time(),
            'sender_user_id' => $data['user_id'],
            'receiver_user_id' => $data['telerad_userid'],
            'message' => $data['message'],
            'is_viewed' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    } 

    public static function validateOrder($data){
        date_default_timezone_set('Asia/Manila'); 

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('imaging_center')
            ->where('imaging_center_id', $data['orderId'])
            ->get();
    }

    public static function sentToRadiologist($data, $filename){
        $query = DB::table('imaging_center')
            ->where('imaging_center_id', $data['orderId']) 
            ->update([ 
                'radiologist' => $data['radiologist'],
                'radiologist_type' => $data['type'],
                'imaging_result_attachment' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        $qry = DB::connection('mysql')->table('imaging_center')->where('imaging_center_id', $data['orderId'])->first();

        DB::connection('mysql')->table('doctors_notification')->insert([
            'notif_id' => 'nid-'.rand(0, 99999),
            'order_id' => $qry->imaging_center_id,
            'patient_id' => $qry->patients_id,
            'doctor_id' => $qry->doctors_id,
            'category' => 'imaging',
            'department' => 'ongoing',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new ongoing order from local imaging.',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]); 

        if($query){
            $qry = DB::table('imaging_center')->select('patients_id')->where('imaging_center_id', $data['orderId'])->first();
            if($data['type'] =='Telerad'){
                _Imaging::syncroniseImagingOrder($data['orderId']);
                _Imaging::syncroniseImagePatient($qry->patients_id); 
            }

            return true;
        }
    } 

    // public static function getRadiologist($data){
    //     return DB::table('radiologist')->select('radiologist_id')->where('management_id', $data['management_id'])->get();
    // }

    public static function getRadiologist($data){ 
        return DB::table('radiologist')
        ->where('management_id' , $data['management_id']) 
        ->orderBy('name', 'asc') 
        ->get();
    }


    public static function syncroniseImagingOrder($imaging_center_id){
        date_default_timezone_set('Asia/Manila');
        $query = DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id',$imaging_center_id)->get();   
        $imaging_info = DB::table('imaging_center')->where('imaging_center_id',$imaging_center_id)->first();
        if(count($query) < 1){
            DB::connection('mysql2')->table('imaging_center')->insert([
                'imaging_center_id' => $imaging_center_id,
                'patients_id' =>$imaging_info->patients_id,
                'doctors_id' =>$imaging_info->doctors_id,
                'ward_nurse_id' =>$imaging_info->ward_nurse_id,
                'radiologist' =>$imaging_info->radiologist,
                'radiologist_type' =>$imaging_info->radiologist_type, 
                'case_file' =>$imaging_info->case_file,
                'request_ward' =>$imaging_info->request_ward,
                'number_shots' => $imaging_info->number_shots,
                'charge_slip' =>$imaging_info->charge_slip, 
                'request_doctor' =>$imaging_info->request_doctor,
                'imaging_order' =>$imaging_info->imaging_order,
                'imaging_remarks' =>$imaging_info->imaging_remarks,
                'imaging_center' =>$imaging_info->imaging_center,
                'imaging_result' =>$imaging_info->imaging_result,
                'imaging_result_screenshot' =>$imaging_info->imaging_result_screenshot,
                'imaging_results_remarks' =>$imaging_info->imaging_results_remarks,
                'imaging_result_attachment' =>$imaging_info->imaging_result_attachment,
                'is_viewed' =>$imaging_info->is_viewed, 
                'is_processed' =>$imaging_info->is_processed, 
                'order_from' =>$imaging_info->order_from, 
                'manage_by' =>$imaging_info->manage_by, 
                'created_at' =>$imaging_info->created_at, 
                'updated_at' =>$imaging_info->updated_at
            ]);
        }else{
            DB::connection('mysql2')->table('imaging_center')
                ->where('imaging_center_id',$imaging_center_id)
                ->update([ 
                    'radiologist' =>$imaging_info->radiologist,
                    'radiologist_type' =>$imaging_info->radiologist_type,
                    'case_file' =>$imaging_info->case_file,
                    'request_ward' =>$imaging_info->request_ward,
                    'charge_slip' =>$imaging_info->charge_slip, 
                    'number_shots' => $imaging_info->number_shots,
                    'request_doctor' =>$imaging_info->request_doctor,
                    'imaging_order' =>$imaging_info->imaging_order,
                    'imaging_remarks' =>$imaging_info->imaging_remarks,
                    'imaging_center' =>$imaging_info->imaging_center,
                    'imaging_result' =>$imaging_info->imaging_result,
                    'imaging_result_screenshot' =>$imaging_info->imaging_result_screenshot,
                    'imaging_results_remarks' =>$imaging_info->imaging_results_remarks,
                    'imaging_result_attachment' =>$imaging_info->imaging_result_attachment,
                    'is_viewed' =>$imaging_info->is_viewed, 
                    'order_from' =>$imaging_info->order_from,  
                    'is_processed' =>$imaging_info->is_processed,  
                    'updated_at' =>$imaging_info->updated_at
                ]);
        }
    }

    public static function syncroniseImagePatient($patient_id){
        date_default_timezone_set('Asia/Manila');
        $query = DB::connection('mysql2')->table('patients')->where('patient_id',$patient_id)->get();
        if(count($query) < 1){
            $patient_info = DB::table('patients')->where('patients.patient_id',$patient_id)->first();
            DB::connection('mysql2')->table('patients')->insert([
                'patient_id' => $patient_id,
                'encoders_id' =>$patient_info->encoders_id,
                'doctors_id' =>$patient_info->doctors_id,
                'user_id' =>$patient_info->user_id,
                'firstname' =>$patient_info->firstname,
                'lastname' =>$patient_info->lastname,
                'middle' =>$patient_info->middle,
                'email' =>$patient_info->email,
                'mobile' =>$patient_info->mobile,
                'telephone' =>$patient_info->telephone,
                'birthday' =>$patient_info->birthday,
                'birthplace' =>$patient_info->birthplace,
                'gender' =>$patient_info->gender,
                'created_at' =>$patient_info->created_at,
                'updated_at' =>$patient_info->updated_at, 
            ]);
        }
    }

    public static function getNewFindings($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('imaging_center')
            ->select('id')
            ->where('imaging_center', _Imaging::getImagingId($data['user_id'])->imaging_id)
            ->where('radiologist_type', $data['connection'] == 'online' ? 'Telerad' : 'In-House')
            ->where('is_viewed', 3)
            ->get(); 
    }


    public static function newFindingsSetAsRead($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('imaging_center')
            ->where('imaging_center', _Imaging::getImagingId($data['user_id'])->imaging_id)
            ->where('imaging_center_id', $data['imaging_center_id']) 
            ->where('is_viewed', 3)
            ->update([
                'is_viewed' => 1,
                'updated_at' => date('Y-m-d H:i:s'), 
            ]); 
    }

    //new 123456789
    public static function hisimagingGetHeaderInfo($data){
        // return DB::table('imaging')
        // ->select('imaging_id', 'user_fullname as name', 'image')
        // ->where('user_id', $data['user_id'])
        // ->first();

        return DB::table('imaging')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'imaging.user_id')
            ->select('imaging.imaging_id', 'imaging.user_fullname as name', 'imaging.image', 'imaging.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('imaging.user_id', $data['user_id'])
            ->first();
    }

    public static function hisimagingGetPersonalInfoById($data){
        $query = "SELECT * FROM imaging WHERE user_id = '".$data['user_id']."' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function hisimagingUploadProfile($data, $filename){
        date_default_timezone_set('Asia/Manila');
        return DB::table('imaging')
        ->where('user_id', $data['user_id'])
        ->update([
            'image' => $filename,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hisimagingUpdatePersonalInfo($data){
        return DB::table('imaging')
        ->where('user_id', $data['user_id'])
        ->update([
            'user_fullname' => $data['fullname'],
            'user_address' => $data['address'],
            'email' => $data['email'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function hisimagingUpdateUsername($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
        ->where('user_id', $data['user_id'])
        ->update([ 
            'username' => $data['new_username'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function hisimagingUpdatePassword($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
        ->where('user_id', $data['user_id'])
        ->update([ 
            'password' => Hash::make($data['new_password']),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function hisimagingGetAllTest($data){
        return DB::connection('mysql')->table('imaging_order_menu')
        ->where('management_id', $data['management_id'])
        ->where('status', 1)
        ->get();
    }

    public static function hisimagingSaveNewTest($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')->table('imaging_order_menu')->insert([
            'order_id' => 'oi-'.rand(0, 99).time(),
            'management_id' => $data['management_id'],
            'order_desc' => $data['order'],
            'type' => $data['type'],
            'order_cost' => $data['rate'],
            'order_shots' => $data['shots'],
            'status' => 1,
            'create_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]); 
    }

    public static function hisimagingEditTest($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')->table('imaging_order_menu')
        ->where('order_id', $data['id']) 
        ->update([ 
            'order_shots' => $data['order_shot'],
            'type' => $data['type'],
            'order_cost' => $data['rate'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]); 
    }

    public static function hisimagingGetPatientForImaging($data){   
        return DB::table('imaging_center')
            ->leftJoin('patients', 'patients.patient_id', '=' , 'imaging_center.patients_id')
            ->leftJoin('imaging_order_menu', 'imaging_order_menu.order_desc', '=' , 'imaging_center.imaging_order')
            ->select('imaging_center.patients_id', 'imaging_center.case_file', 'imaging_center.imaging_center_id', DB::raw('concat(patients.firstname," ",patients.lastname) as name'), 'patients.image', 'patients.firstname', 'patients.lastname', 'patients.middle')
            ->where('imaging_center.imaging_center', _Imaging::getImagingId($data['user_id'])->imaging_id)
            ->where('imaging_center.manage_by', $data['management_id'])
            ->where('imaging_order_menu.type', $data['type'])
            ->whereNull('imaging_center.radiologist')
            ->where('imaging_center.is_viewed', 1)
            ->where('imaging_center.order_from', 'local')
            ->whereNull('imaging_center.imaging_result_attachment')
            ->groupBy('imaging_center.patients_id')
            ->get();  
    }   

    public static function hisimagingGetPatientInformation($data){ 
        return DB::table('imaging_center')
        ->join('patients', 'patients.patient_id' ,'=', 'imaging_center.patients_id')
        ->leftJoin('imaging_order_menu', 'imaging_order_menu.order_desc', '=' , 'imaging_center.imaging_order')
        ->select('imaging_center.*', 'patients.firstname as fname','patients.lastname as lname' ,'patients.city as city','patients.barangay as barangay' ,'patients.gender as gender','patients.birthday as birthday', 'patients.mobile as mobile', 'patients.telephone as telephone')   
        ->where('imaging_center.manage_by', $data['management_id'])
        ->where('imaging_center.patients_id', $data['patient_id'])
        ->where('imaging_order_menu.type', $data['type'])
        ->whereNull('imaging_center.radiologist')
        ->where('imaging_center.is_viewed', 1)
        ->where('imaging_center.order_from', 'local')
        ->whereNull('imaging_center.imaging_result_attachment')
        ->get();
    }

    public static function hisimagingOrderAddResult($data, $filename){ 
        date_default_timezone_set('Asia/Manila');
        $order_array=[];

        $query = DB::connection('mysql')
        ->table('imaging_center')
        ->where('imaging_center_id', $data['imaging_center_id'])
        ->update([
            'radiologist'=> $data['radiologist'],
            'radiologist_type'=> $data['radiologist_type'],
            'imaging_result_attachment' => $filename,
            'processed_by' => $data['user_id'],
            'start_time' => date('Y-m-d H:i:s'),
            'edit_by_encoder' => 1,
            'referring_physician' => $data['referring_physician'],
            'file_no' => $data['file_no'],
            'clinical_data' => $data['clinical_data'],
            'updated_at' =>date('Y-m-d H:i:s')
        ]);

        if($query){
            $nullCount = DB::connection('mysql')->table('imaging_center')
            ->where('patients_id', $data['patient_id'])
            // ->whereNull('imaging_result_attachment')
            ->whereNull('radiologist')
            ->get();

            if(count($nullCount) < 1){
                DB::table('patient_queue')
                ->where('patient_id', $data['patient_id'])
                ->where('type', 'imaging')
                ->delete();
            }
        }

        if($query){
            if($data['radiologist_type'] == 'Telerad'){
                $result = DB::connection('mysql')->table('imaging_center')->where('imaging_center_id', $data['imaging_center_id'])->first();
                $addpatient = DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])->first();
                $checkpatient = DB::connection('mysql2')->table('patients')->where('patient_id', $data['patient_id'])->get();
                if(count($checkpatient) < 1){
                    DB::connection('mysql2')->table('patients')->insert([
                        'patient_id' => $addpatient->patient_id,
                        'encoders_id' => $addpatient->encoders_id,
                        'doctors_id' => $addpatient->doctors_id,
                        'management_id' => $addpatient->management_id,
                        'main_mgmt_id' => $addpatient->main_mgmt_id,
                        'user_id' => $addpatient->user_id,
                        'firstname' => $addpatient->firstname,
                        'lastname' => $addpatient->lastname,
                        'middle' => $addpatient->middle,
                        'email' => $addpatient->email,
                        'mobile' => $addpatient->mobile,
                        'telephone' => $addpatient->telephone,
                        'birthday' => $addpatient->birthday,
                        'birthplace' => $addpatient->birthplace,
                        'gender' => $addpatient->gender,
                        'civil_status' => $addpatient->civil_status,
                        'religion' => $addpatient->religion,
                        'height' => $addpatient->height,
                        'weight' => $addpatient->weight,
                        'occupation' => $addpatient->occupation,
                        'street' => $addpatient->street,
                        'barangay' => $addpatient->barangay,
                        'city' => $addpatient->city,
                        'municipality' => $addpatient->municipality,
                        'tin' => $addpatient->tin,
                        'philhealth' => $addpatient->philhealth,
                        'company' => $addpatient->company,
                        'zip' => $addpatient->zip,
                        'blood_type' => $addpatient->blood_type,
                        'blood_systolic' => $addpatient->blood_systolic,
                        'blood_diastolic' => $addpatient->blood_diastolic,
                        'temperature' => $addpatient->temperature,
                        'pulse' => $addpatient->pulse,
                        'rispiratory' => $addpatient->rispiratory,
                        'glucose' => $addpatient->glucose,
                        'uric_acid' => $addpatient->uric_acid,
                        'hepatitis' => $addpatient->hepatitis,
                        'tuberculosis' => $addpatient->tuberculosis,
                        'dengue' => $addpatient->dengue,
                        'cholesterol' => $addpatient->cholesterol,
                        'allergies' => $addpatient->allergies,
                        'medication' => $addpatient->medication,
                        'covid_19' => $addpatient->covid_19,
                        'swine_flu' => $addpatient->swine_flu,
                        'hiv' => $addpatient->hiv,
                        'asf' => $addpatient->asf,
                        'vacinated' => $addpatient->vacinated,
                        'pro' => $addpatient->pro,
                        'remarks' => $addpatient->remarks,
                        'image' => $addpatient->image,
                        'status' => $addpatient->status,
                        'doctors_response' => $addpatient->doctors_response,
                        'is_edited_bydoc' => $addpatient->is_edited_bydoc,
                        'package_selected' => $addpatient->package_selected,
                        'join_category' => $addpatient->join_category,
                        'added_by' => $addpatient->added_by,
                        'created_at' => $addpatient->created_at,
                        'updated_at' => $addpatient->updated_at
                    ]);
                }

                DB::connection('mysql2')->table('imaging_center')->insert([
                    'imaging_center_id' => $result->imaging_center_id,
                    'patients_id' => $result->patients_id,
                    'doctors_id' => $result->doctors_id,
                    'radiologist' => $result->radiologist,
                    'radiologist_type' => $result->radiologist_type,
                    'imaging_order' => $result->imaging_order,
                    'imaging_remarks' => $result->imaging_remarks,
                    'imaging_center' => $result->imaging_center,
                    'imaging_result' => $result->imaging_result,
                    'imaging_result_attachment' => $result->imaging_result_attachment,
                    'is_viewed' => $result->is_viewed,
                    'is_processed' => $result->is_processed,
                    'processed_by' => $result->processed_by,
                    'start_time' => $result->start_time,
                    'manage_by' => $result->manage_by,
                    'edit_by_encoder' => $result->edit_by_encoder,
                    'order_from' => $result->order_from,
                    'created_at' => $result->created_at,
                    'updated_at' => $result->updated_at
                ]);
            }
        }

        if($query){
            $order_array[]=array(
                'transaction_id' => 'trnsct-'.rand(0, 9999).time(),
                'patients_id' => $data['patient_id'],
                'imaging_order' => $data['imaging_order'],
                'processed_by' => $data['user_id'],
                'order_type' => $data['radiologist_type'],
                'amount'=> $data['radiologist_type'] == 'Telerad' ? 200 : 150,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            );
        }

        return DB::table('imaging_center_record')->insert($order_array);
    }

    public static function imagingOrderList ($data){
        return DB::table('imaging_order_menu')
            ->where('management_id', $data['vmanagementId'])
            ->get();
    }

    public static function imagingOrderSelectedDetails ($data){
        return DB::connection('mysql')->table('imaging_order_menu')
            ->where('order_id', $data['order_id'])
            ->first();
    }

    public static function hisimagingUploadImagingAttach($data, $filename){
        return DB::table('imaging_center')
        ->where('imaging_center_id',$data['imaging_center_id']) 
        ->update([
            'imaging_result_attachment' =>$filename,
        ]);
    }

    public static function hisimagingGetAllLocalRadiologist ($data){
        return DB::table('radiologist')
            ->select('radiologist_id as values', 'name as label')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function hisimagingGetAllTeleRadiologist ($data){
        return DB::connection('mysql2')->table('teleradiologist')
            ->select('telerad_id as values', 'name as label')
            ->where('status', 1)
            ->get();
    }

    public static function hisimagingGetNewOrder($data){   
        $imagingid = _Imaging::getImagingId($data['user_id'])->imaging_id; 
        
        $query = "SELECT *,
            (SELECT concat(patients.lastname,', ',patients.firstname) from patients where patients.patient_id = imaging_center.patients_id) as name
        from imaging_center where imaging_result_attachment is null and order_from = 'virtual' and is_viewed = 1 and radiologist is null and imaging_center = '$imagingid' "; 

        $result = DB::connection('mysql2')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function hisimagingGetImagingOrderReports($data){  
        return DB::connection('mysql2')
            ->table('imaging_center')
            ->join('patients', 'patients.patient_id' ,'=', 'imaging_center.patients_id') 
            ->select('imaging_center.*', 'patients.firstname as fname','patients.lastname as lname' ,'patients.city as city','patients.barangay as barangay' ,'patients.gender as gender','patients.birthday as birthday')
            ->where('imaging_center.imaging_center' ,_Imaging::getImagingId($data['user_id'])->imaging_id)
            ->whereNotNull('imaging_center.imaging_result_attachment')
            ->where('order_from', 'virtual')
            ->orderBy('is_viewed', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function hisimagingGetReportDetails($data){   
        $id = $data['selectedAttachmentID'];
        DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id', $id)
        ->update([
            'is_viewed' =>1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $query = "SELECT *,
            (SELECT concat(patients.firstname,' ',patients.lastname) from patients where patients.patient_id = imaging_center.patients_id limit 1) as name,
            (SELECT gender from patients where patients.patient_id = imaging_center.patients_id limit 1) as gender,
            (SELECT mobile from patients where patients.patient_id = imaging_center.patients_id limit 1) as mobile,
            (SELECT telephone from patients where patients.patient_id = imaging_center.patients_id limit 1) as telephone,
            (SELECT birthday from patients where patients.patient_id = imaging_center.patients_id limit 1) as birthday
        from imaging_center where imaging_center_id = '$id' ";

        $result = DB::connection('mysql2')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function getImagingOrderReportsByDate($data){  
        date_default_timezone_set('Asia/Manila');

        $from = date('Y-m-d', strtotime($data['date_from'])).' 00:00'; 
        $to = date('Y-m-d', strtotime($data['date_to'])).' 23:59';
        $dateFrom = date('Y-m-d H:i:s', strtotime($from));
        $dateTo = date('Y-m-d H:i:s', strtotime($to));

        return DB::connection('mysql2')
            ->table('imaging_center')
            ->join('patients', 'patients.patient_id' ,'=', 'imaging_center.patients_id') 
            ->select('imaging_center.*', 'patients.firstname as fname','patients.lastname as lname' ,'patients.city as city','patients.barangay as barangay' ,'patients.gender as gender','patients.birthday as birthday')
            ->where('imaging_center.imaging_center' ,_Imaging::getImagingId($data['user_id'])->imaging_id)
            ->whereNotNull('imaging_center.imaging_result_attachment')
            ->where('order_from', 'virtual')
            ->where('imaging_center.created_at','>=',$dateFrom)
            ->where('imaging_center.created_at','<=',$dateTo)
            ->orderBy('is_viewed', 'desc')
            ->orderBy('created_at', 'desc') 
            ->get();
    }

    public static function getPatientInformation($data){
        return DB::connection('mysql2')
            ->table('patients')
            ->where('patient_id', $data['patient_id'])
            ->first();
    }

    public static function getImagingOrderInformation($data){
        return DB::connection('mysql2')
            ->table('imaging_center')
            ->leftJoin('doctors', 'doctors.doctors_id','=','imaging_center.doctors_id')
            ->select('imaging_center.*', 'doctors.name as doctor_name')
            ->where('imaging_center_id', $data['imaging_center_id'])
            ->first();
    }

    public static function getTeleradiologistList($data){ 
        return DB::connection('mysql2')
        ->table('teleradiologist')
        ->orderBy('name', 'asc') 
        ->get();
    }

    public static function setImagingOrderRadiologist($data, $filename){ 
        DB::connection('mysql2')
        ->table('patients_notification')
        ->insert([
            'notif_id' => 'nid-'.rand(0, 99).time(),
            'order_id' => $data['imaging_center_id'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'imaging',
            'department' => 'imaging-admin',
            'message' => 'the ordered imaging has an attachment',
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // update notification of doctors
        DB::connection('mysql2')->table('doctors_notification')->insert([
            'notif_id' => 'nid-'.rand(0, 99999),
            'order_id' => $data['imaging_center_id'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'imaging',
            'department' => 'ongoing',
            'is_view' => 0,
            'notification_from' => 'virtual',
            'message' => 'new ongoing imaging test',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]); 

        DB::connection('mysql2')
            ->table('imaging_center') 
            ->where('imaging_center_id', $data['imaging_center_id'])
            ->update([
                'radiologist' => $data['radiologistType'] == 'Telerad' ? $data['teleradiologist'] :  $data['radiologist'],
                'radiologist_type' => $data['radiologistType'],
                'imaging_result_attachment' => $filename,
                'processed_by' => $data['user_id'],
                'is_processed' => 1,
                'is_pending' => 0,
                'start_time' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        $orders = explode(', ', $data['imaging_order']);

        $order_array = [];
        foreach($orders  as $order){
            $order_array[] = array(
                'transaction_id' => 'trnsct-'.rand(0, 9999).time(),
                'patients_id' => $data['patient_id'],
                'case_file' => $data['case_file'],
                'imaging_order' => str_ireplace(',','',$order),
                'processed_by' => $data['user_id'],
                'order_type' => $data['radiologistType'],
                'amount'=> $data['radiologistType'] == 'Telerad' ? 200 : 150 ,
                'case_file'=> 0,
                'record_from' => 'virtual',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            );
        }

        return DB::connection('mysql2')->table('imaging_center_record')->insert($order_array);
    }

    public static function getPatientReviewed($data){  
        $imaging_id = _Imaging::getImagingId($data['user_id'])->imaging_id;
        if(!empty($data['date_from']) && $data['date_to']){
            $dailyStrt = date('Y-m-d', strtotime($data['date_from'])).' 00:00';
            $dailyLst = date('Y-m-d', strtotime($data['date_to'])).' 23:59';
            $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
            $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));

            //return DB::table('imaging_center')
            //     ->leftJoin('patients', 'patients.patient_id', '=', 'imaging_center.patients_id')
            //     ->leftJoin('imaging_order_menu', 'imaging_order_menu.order_desc', '=', 'imaging_center.imaging_order')
            //     ->select('imaging_center.*', 'patients.firstname', 'patients.lastname', 'imaging_order_menu.order_cost as imagingCost')
            //     ->where('imaging_center.imaging_center', _Imaging::getImagingId($data['user_id'])->imaging_id)
            //     ->whereNotNull('imaging_center.imaging_result_attachment')
            //     ->whereDate('imaging_center.created_at', '>=' , $date_from)
            //     ->whereDate('imaging_center.created_at', '<=' , $date_to)
            //     ->get(); 

            $query = "SELECT *,
                (SELECT order_name from packages_charge where packages_charge.package_name = imaging_center.imaging_order AND packages_charge.department = 'imaging' limit 1) as orderDescPackage,
                (SELECT firstname from patients where patients.patient_id = imaging_center.patients_id limit 1) as firstname,
                (SELECT lastname from patients where patients.patient_id = imaging_center.patients_id limit 1) as lastname,
                (SELECT order_cost from imaging_order_menu where imaging_order_menu.order_desc = imaging_center.imaging_order limit 1) as imagingCostReg,
                (SELECT order_cost from imaging_order_menu where imaging_order_menu.order_desc = orderDescPackage limit 1) as imagingCostPackage
            from imaging_center where imaging_center = '$imaging_id' AND created_at >= '$strDaily' AND created_at <= '$lstDaily' AND imaging_result IS NOT NULL ORDER BY created_at DESC ";
    
            $result = DB::connection('mysql')->getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ); 
        }

        $query = "SELECT *,
            (SELECT order_name from packages_charge where packages_charge.package_name = imaging_center.imaging_order AND packages_charge.department = 'imaging' limit 1) as orderDescPackage,
            (SELECT firstname from patients where patients.patient_id = imaging_center.patients_id limit 1) as firstname,
            (SELECT lastname from patients where patients.patient_id = imaging_center.patients_id limit 1) as lastname,
            (SELECT order_cost from imaging_order_menu where imaging_order_menu.order_desc = imaging_center.imaging_order limit 1) as imagingCostReg,
            (SELECT order_cost from imaging_order_menu where imaging_order_menu.order_desc = orderDescPackage limit 1) as imagingCostPackage
        from imaging_center where imaging_center = '$imaging_id' AND imaging_result IS NOT NULL ORDER BY created_at DESC ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 

        // return DB::table('imaging_center')
        //     ->leftJoin('patients', 'patients.patient_id', '=', 'imaging_center.patients_id')
        //     ->leftJoin('imaging_order_menu', 'imaging_order_menu.order_desc', '=', 'imaging_center.imaging_order')
        //     ->select('imaging_center.*', 'patients.firstname', 'patients.lastname', 'imaging_order_menu.order_cost as imagingCost')
        //     ->where('imaging_center.imaging_center', _Imaging::getImagingId($data['user_id'])->imaging_id)
        //     ->whereNotNull('imaging_center.imaging_result_attachment')
        //     ->get(); 
    }

    public static function imagingGetPatientListQueue($data){
        return DB::table('patient_queue')
        ->join("imaging_center", "imaging_center.patients_id", "=", "patient_queue.patient_id")
        ->join('patients', 'patients.patient_id', '=', 'patient_queue.patient_id')
        ->select('patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle', 'patient_queue.patient_id', 'patient_queue.created_at', 'patients.company', 'imaging_center.patients_id', 'imaging_center.case_file', 'imaging_center.imaging_center_id', DB::raw('concat(patients.firstname," ",patients.lastname) as name'))
        ->where('imaging_center.imaging_center', _Imaging::getImagingId($data['user_id'])->imaging_id)
        ->where('imaging_center.manage_by', $data['management_id'])
        ->whereNull('imaging_center.radiologist')
        ->where('imaging_center.is_viewed', 1)
        ->where('imaging_center.order_from', 'local')
        ->whereNull('imaging_center.imaging_result_attachment')
        ->where('patient_queue.type', 'imaging')
        ->groupBy('patient_queue.patient_id')
        ->get();
    }

    public static function getResultToEditOnline($data){
        $query = "SELECT *,

            (SELECT firstname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as lname

        FROM imaging_center WHERE edit_by_encoder = 1 AND imaging_result IS NOT NULL";
        $result = DB::connection('mysql2')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getImagingPrintableHeader($data){ 
        return DB::connection('mysql')
        ->table('management')
        ->where('management_id', $data['management_id']) 
        ->first();
    }
    

    public static function getPrintOrder($data){ 
        return DB::connection('mysql')
        ->table('imaging_center')
        ->join('patients', 'patients.patient_id', '=', 'imaging_center.patients_id')
        ->leftJoin('imaging_order_menu', 'imaging_order_menu.order_desc', '=', 'imaging_center.imaging_order')
        ->leftJoin('radiologist', 'radiologist.radiologist_id', '=', 'imaging_center.radiologist')
        ->select('imaging_center.*', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.image', 'patients.gender', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city', 'imaging_order_menu.type as imaging_type', 'radiologist.name as radiologistName', 'radiologist.image_signature as imageSignature')
        ->where('imaging_center_id', $data['imaging_center_id']) 
        ->first();
    }

    public static function editConfirmedResultImaging($data){
        date_default_timezone_set('Asia/Manila');

        return DB::table("imaging_center")
        ->where('imaging_center_id', $data['imaging_center_id'])
        ->update([
            'imaging_result' => strip_tags($data['new_result']),
            'imaging_results_remarks' => strip_tags($data['new_impression']),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function getAllToAddResult($data){
        $radiologist = $data['radiologist'];
        $radiologist_type = $data['radiologist_type'];
        $management_id = $data['management_id'];
        // $query = "SELECT *,
        //     (SELECT firstname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as fname,
        //     (SELECT lastname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as lname
        // FROM imaging_center WHERE edit_by_encoder = 1 AND radiologist = '$radiologist' ";
        $query = "SELECT *, imaging_order as Imgng_Order,
            (SELECT type FROM imaging_order_menu WHERE imaging_order_menu.order_desc = Imgng_Order) as imaging_typee,
            (SELECT firstname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as lname
        FROM imaging_center WHERE edit_by_encoder = 1 AND manage_by = '$management_id' AND imaging_result IS NULL ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function saveNewFlowEditedResult($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')->table('imaging_center')
        ->where('imaging_center_id', $data['imaging_center_id'])
        ->update([
            'imaging_result' => strip_tags($data['new_result']),
            'imaging_results_remarks' => strip_tags($data['new_impression']),
            'is_viewed' => 1,
            'is_processed' => 1,
            'processed_by' => $data['user_id'],
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s'),
            'manage_by' => $data['management_id'],
            'edit_by_encoder' => 2,
            'print_category' => $data['print_category'],
            'template_name' => $data['template_name'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'order_from' => 'local',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hisimagingGetPatientForImagingUltraSound($data){   
        return DB::table('imaging_center')
            ->leftJoin('patients', 'patients.patient_id', '=' , 'imaging_center.patients_id')
            ->leftJoin('imaging_order_menu', 'imaging_order_menu.order_desc', '=' , 'imaging_center.imaging_order')
            ->select('imaging_center.patients_id', 'imaging_center.case_file', 'imaging_center.imaging_center_id', DB::raw('concat(patients.firstname," ",patients.lastname) as name'), 'patients.image', 'patients.firstname', 'patients.lastname', 'patients.middle')
            ->where('imaging_center.imaging_center', _Imaging::getImagingId($data['user_id'])->imaging_id)
            ->where('imaging_center.manage_by', $data['management_id'])
            ->where('imaging_order_menu.type', $data['type'])
            ->whereNull('imaging_center.radiologist')
            ->where('imaging_center.is_viewed', 1)
            ->where('imaging_center.order_from', 'local')
            // ->whereNull('imaging_center.imaging_result_attachment')
            ->groupBy('imaging_center.patients_id')
            ->get();  
    }

    public static function hisimagingOrderAddResultUltraSound($data){ 
        date_default_timezone_set('Asia/Manila');
        $order_array=[];

        $query = DB::connection('mysql')
        ->table('imaging_center')
        ->where('imaging_center_id', $data['imaging_center_id'])
        ->update([
            'radiologist'=> $data['radiologist'],
            'radiologist_type'=> $data['radiologist_type'],
            // 'imaging_result_attachment' => $filename,
            'processed_by' => $data['user_id'],
            'start_time' => date('Y-m-d H:i:s'),
            'referring_physician' => $data['referring_physician'],
            'file_no' => $data['file_no'],
            'edit_by_encoder' => 1,
            'updated_at' =>date('Y-m-d H:i:s')
        ]);

        if($query){
            $nullCount = DB::connection('mysql')->table('imaging_center')
            ->where('patients_id', $data['patient_id'])
            // ->whereNull('imaging_result_attachment')
            ->whereNull('radiologist')
            ->get();

            if(count($nullCount) < 1){
                DB::table('patient_queue')
                ->where('patient_id', $data['patient_id'])
                ->where('type', 'imaging')
                ->delete();
            }
        }

        if($query){
            $order_array[]=array(
                'transaction_id' => 'trnsct-'.rand(0, 9999).time(),
                'patients_id' => $data['patient_id'],
                'imaging_order' => $data['imaging_order'],
                'processed_by' => $data['user_id'],
                'order_type' => $data['radiologist_type'],
                'amount'=> $data['radiologist_type'] == 'Telerad' ? 200 : 150,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            );
        }

        return DB::table('imaging_center_record')->insert($order_array);
    }

    public static function getCurrentFormInformationResult($data)
    {
        return DB::table('imaging_formheader')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->first();
    }

    public static function editResultPrintLayout($data){
        date_default_timezone_set('Asia/Manila');
        if(!empty($data['ifh_id'])){
            return DB::table('imaging_formheader')
            ->where('ifh_id', $data['ifh_id'])
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->update([
                'name' => $data['name'],
                'address' => $data['address'],
                'contact_number' => $data['contact_number'],
                'radiologist' => $data['radiologist'],
                'radiologist_lcn' => $data['radiologist_lcn'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }else{
            return DB::table('imaging_formheader')
            ->insert([
                'ifh_id' => 'ifh-'.rand(0, 99).time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'name' => $data['name'],
                'address' => $data['address'],
                'contact_number' => $data['contact_number'],
                'radiologist' => $data['radiologist'],
                'radiologist_lcn' => $data['radiologist_lcn'],
                'logo' => 'bmcdc_logo.png',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public static function getImagingFormHeader($data)
    {
        return DB::table('imaging_formheader')->where('management_id', $data['management_id'])->first();
    }

    public static function getOrderImagingDetailsPrint($data)
    {
        // return DB::table('imaging_center')
        // ->leftJoin('imaging_order_menu', 'imaging_order_menu.order_desc', '=', 'imaging_center.imaging_order')
        // ->select('imaging_center.*', 'imaging_order_menu.type as imaging_type')
        // ->where('imaging_center.main_mgmt_id', $data['main_mgmt_id'])
        // ->where('imaging_center.trace_number', $data['trace_number'])
        // ->whereNotNull('imaging_center.imaging_result')
        // ->get();

        $trace_number = $data['trace_number'];
        $main_mgmt_id = $data['main_mgmt_id'];

        $query = "SELECT *, imaging_order as Imgng_Order,

            (SELECT name FROM radiologist WHERE radiologist.radiologist_id = imaging_center.radiologist LIMIT 1) as radiologist_name,
            (SELECT type FROM imaging_order_menu WHERE imaging_order_menu.order_desc = Imgng_Order LIMIT 1) as imaging_type

        FROM imaging_center WHERE main_mgmt_id = '$main_mgmt_id' AND trace_number = '$trace_number' AND imaging_result IS NOT NULL ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

}
