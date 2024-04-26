<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\_Patient;
use App\Models\_Doctor;

class _Appointment extends Model
{
    public static function doctorsList($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors')->where('online_appointment', 1)->get();
    }

    public static function checkActiveAppointment($patient_id){
        $query = DB::table('appointment_list')->where('patients_id', $patient_id)->where('is_complete', 0)->get();
        if(count($query) > 0) { return true; }
    }

    public static function doctorsInformation($data){
        $query = "SELECT * from doctors where `user_id` = '".$data['doctors_id']."' and online_appointment = 1";
         
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    

    public static function doctorsServices($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_appointment_services')->where('doctors_id', $data['doctors_id'])->get();
    }

    public static function doctorsServicesDetails($service_id){
        return DB::table('doctors_appointment_services')->where('service_id', $service_id)->first();
    }

    public static function checkActiveOnlineAppointment($data){
        $query = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_appointment')
        ->where('doctors_id', $data['doctors_id'])
        ->where('patient_id', $data['user_id'])
        ->whereNull('appointment_done_on')->get();
        if(count($query) > 0) { return true; }
    }

    public static function requestAppointment($data, $attachment){
        date_default_timezone_set('Asia/Manila');

        $date = date('Y-m-d', strtotime($data['appointment_date']));
        $appointment_date = $date.' '.$data['appointment_time'];
        $ref_number = time().rand(0, 999);
        $service = _Appointment::doctorsServicesDetails($data['appointment_type']); 
        $appid = 'app-'.rand(0, 99999);
        $patient_id = _Patient::getPatientId($data['user_id']);
        $doctorsid = (new _Doctor)::getDoctorsId($data['doctors_id'])->doctors_id;

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->insert([
                'notif_id' => 'nid-'.rand(0, 99999),
                'order_id' => $appid,
                'patient_id' => $patient_id,
                'doctor_id' => $doctorsid,
                'category' => 'appointment',
                'department' => 'virtual-appointment-new',
                'is_view' => 0,
                'notification_from' => 'virtual',
                'message' => 'new virtual appointment request from patient',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]); 

        DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')
            ->table('virtual_appointment')
            ->insert([
                'appointment_id' =>$appid ,
                'reference_no' => $ref_number,
                'doctors_id' => $data['doctors_id'],
                'patient_id' => $data['user_id'],
                'doctors_service_id' => $data['appointment_type'], 
                'doctors_service' => $service->services,
                'doctors_service_amount' => $service->amount,
                'appointment_date' => date('Y-m-d H:i:s', strtotime($appointment_date)),
                'appointment_reason' => $data['appointment_reason'],
                'attachment' => $attachment,
                'appointment_status' => 'new',
                'payment_status' => $data['credit'] < $service->amount ? 'Unpaid' : 'Paid',
                'is_process' => 0,
                'status' => 1,
                'updated_at' =>date('Y-m-d H:i:s') ,
                'created_at' =>date('Y-m-d H:i:s') ,
            ]);

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('patients_credit_transaction')->insert([
            'transaction_id' => 'transaction-'.rand(0, 99999),
            'reference_no' => $ref_number,
            'patient_id' => $data['user_id'],
            'doctors_id' => $data['doctors_id'],
            'doctors_service_id' => $data['appointment_type'],
            'transaction_cost' => $service->amount,
            'transaction_status' => 'Unapproved',
            'status' => 1,
            'updated_at' =>date('Y-m-d H:i:s') ,
            'created_at' =>date('Y-m-d H:i:s') ,
        ]);
    }

    public static function getApprovedCount($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_appointment')
                ->where('patient_id', $data['user_id'])
                ->where('is_process', 1)
                ->where('appointment_status', 'approved')
                ->count('id');
    }

    public static function getAppointmentList($data){
        $user_id = $data['user_id'];

        $query = "SELECT *,
        (SELECT `services` from doctors_appointment_services where doctors_appointment_services.service_id = virtual_appointment.doctors_service_id limit 1) as services,
        (SELECT `amount` from doctors_appointment_services where doctors_appointment_services.service_id = virtual_appointment.doctors_service_id limit 1) as credit_fee,
        (SELECT `name` from doctors where doctors.user_id = virtual_appointment.doctors_id limit 1) as doctors_name,
        (SELECT `specialization` from doctors where doctors.user_id = virtual_appointment.doctors_id limit 1) as doctors_specialization
        from virtual_appointment where virtual_appointment.patient_id = '$user_id' ";
         
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAppointmentDetail($data){

        date_default_timezone_set('Asia/Manila');

        if($data['connection'] == 'online'){
            DB::connection('mysql2')
            ->table('doctors_notification')
            ->where('order_id', $data['appointment_id'])
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $query = "SELECT *,
        (SELECT `services` from doctors_appointment_services where doctors_appointment_services.service_id = virtual_appointment.doctors_service_id) as services,
        (SELECT `amount` from doctors_appointment_services where doctors_appointment_services.service_id = virtual_appointment.doctors_service_id) as credit_fee
        from virtual_appointment where virtual_appointment.appointment_id = '".$data['appointment_id']."' ";
         
        // $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);   
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getNextAppointment($data){
        date_default_timezone_set('Asia/Manila');
        // $current_date = date('Y-m-d H:i:s');
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_appointment')
            ->select('appointment_date')
            ->where('appointment_status' , 'approved')
            ->where('patient_id', $data['user_id'])
            // ->where('appointment_date','>', $current_date)
            ->limit(1)->get();
    } 

    public static function getApproveAppointment($data){   
        $user_id = $data['user_id']; 
        $query = "SELECT *,
        (SELECT concat(lastname,', ', firstname) from patients where patients.user_id = virtual_appointment.patient_id) as patient_name,
        (SELECT patient_id from patients where patients.user_id = virtual_appointment.patient_id) as patientid_old,
        (SELECT image from patients where patients.user_id = virtual_appointment.patient_id) as patient_image,
        (SELECT IFNULL(count(id), 0) from virtual_appointment_notification where virtual_appointment_notification.doctors_id = '$user_id' and is_read = 0 and notification_type = 'inbox') as unreadNotification
        from virtual_appointment where virtual_appointment.doctors_id = '$user_id' and appointment_status = 'approved' and is_process = 1 order by appointment_date asc ";
        
         $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query); 

        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getRequestAppointmentList($data){  

        DB::connection('mysql2')->table('doctors_notification')
        ->where('department', 'virtual-appointment-new')
        ->where('notification_from', 'virtual')
        ->where('is_view', 0)
        ->where('doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
        ->update([
            'is_view' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $query = "SELECT *,
        (SELECT services from doctors_appointment_services where doctors_appointment_services.service_id = virtual_appointment.doctors_service_id limit 1) as appointment_type,
        (SELECT concat(lastname,', ', firstname) from patients where patients.user_id = virtual_appointment.patient_id) as patient_name,
        (SELECT patient_id from patients where patients.user_id = virtual_appointment.patient_id) as patientId
        from virtual_appointment where virtual_appointment.doctors_id = '".$data['user_id']."' and appointment_status = 'new' and is_process = 0 order by appointment_date asc "; 
         
        $result = DB::connection('mysql2')->getPdo()->prepare($query); 
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);  
    }

    public static function appointmentAction($data){
        date_default_timezone_set('Asia/Manila');
        
        if($data['connection'] == 'online'){
            if($data['appaction']=='approved'){ 
                // notify doctors for new apporved appointment
                $qry = DB::connection('mysql2')->table('virtual_appointment')
                    ->where('appointment_id', $data['appointment_id'])
                    ->first();
                $patient = DB::connection('mysql2')->table('patients')->select('patient_id')->where('user_id', $qry->patient_id)->first();

                DB::connection('mysql2')->table('doctors_notification')->insert([
                    'notif_id' => 'nid-'.rand(0, 99999),
                    'order_id' => $qry->appointment_id,
                    'patient_id' =>  $patient->patient_id,
                    'doctor_id' => (new _Doctor)::getDoctorsId($qry->doctors_id)->doctors_id,
                    'category' => 'appointment',
                    'department' => 'virtual-appointment',
                    'is_view' => 0,
                    'notification_from' => 'virtual', 
                    'status' => 1, 
                    'message' => 'new virtual appointment approved by doctor', 
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]); 

                DB::connection('mysql2')->table('patients_credit_transaction')
                ->where('reference_no', $data['reference_no'])
                ->update([
                    'transaction_status' => 'approved',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            return DB::connection('mysql2')->table('virtual_appointment')
                ->where('appointment_id', $data['appointment_id'])
                ->update([
                    'appointment_status' => $data['appaction'], 
                    'is_process' => 1,
                    'is_process_on'=> date('Y-m-d H:i:s'),
                    'process_message' => $data['appmessage'],
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        if($data['appaction']=='approved'){
            DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('patients_credit_transaction')
            ->where('reference_no', $data['reference_no'])
            ->update([
                'transaction_status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_appointment')
            ->where('appointment_id', $data['appointment_id'])
            ->update([
                'appointment_status' => $data['appaction'], 
                'is_process' => 1,
                'is_process_on'=> date('Y-m-d H:i:s'),
                'process_message' => $data['appmessage'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public static function sendNotificationMsg($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('virtual_appointment_notification') 
            ->insert([
                'notif_id' => 'notif-'.rand(0, 9999),
                'appointment_id' => $data['appid'],
                'doctors_id' => $data['user_id'],
                'patient_id' => $data['patient_id'],
                'notification_msg' => $data['message'],
                'is_read' => 0,
                'status' => 1,
                'notification_type' => 'sent',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s') 
            ]);
    }

    public static function appointmentNotificationMsg($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_appointment_notification')
            ->where('doctors_id',$data['doctors_id'])
            ->where('patient_id',$data['patient_id'])
            ->get();
    }

    public static function appointmentNotifByPatient($data){
        $user_id = _Patient::getPatientId($data['user_id']);

        $query = "SELECT *,
        (SELECT `name` from doctors where doctors.user_id = virtual_appointment_notification.doctors_id) as doctors_name
        from virtual_appointment_notification where virtual_appointment_notification.patient_id = '$user_id' order by id desc ";
        
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function appointmentNofitReply($data){

        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_appointment_notification')->insert([
            'notif_id' => 'notif-'.rand(0, 8889),
            'appointment_id' => $data['appointment_id'],
            'doctors_id' => $data['doctors_id'],
            'patient_id' => $data['patient_id'],
            'notification_msg' => $data['notification'],
            'notification_type' => 'inbox',
            'is_read' => 0,
            'status' => 1, 
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function appointmentNotifDetails($data){
        // set notif to read
        _Appointment::appointmentNotifSetasRead($data['notifid']);
        
        $query = "SELECT *,
        (SELECT `name` from doctors where doctors.user_id = virtual_appointment_notification.doctors_id) as doctors_name
        from virtual_appointment_notification where virtual_appointment_notification.notif_id = '".$data['notifid']."' ";
        
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function appointmentNotifSetasRead($notifid){
        date_default_timezone_set('Asia/Manila');
        return DB::table('virtual_appointment_notification')
        ->where('notif_id', $notifid)
        ->where('is_read', 0)
        ->update([
            'is_read' => 1,
            'updated_at' => date('Y-m-d, H:i:s') 
        ]);
    }

    public static function appointmentNotifByPatientUnread($data){
     
        $user_id = _Patient::getPatientId($data['user_id']);
        $query = "SELECT *,
        (SELECT `name` from doctors where doctors.user_id = virtual_appointment_notification.doctors_id) as doctors_name
        from virtual_appointment_notification where virtual_appointment_notification.patient_id = '".$user_id."' and is_read = 0 and notification_type='sent' order by created_at desc";
        
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function appointmentNotifByPatientUnreadNew($data){
        $user_id = _Patient::getPatientId($data['user_id']);

        $query = "SELECT *,
        (SELECT `name` from doctors where doctors.user_id = virtual_appointment_notification.doctors_id) as doctors_name
        from virtual_appointment_notification where virtual_appointment_notification.patient_id = '".$user_id."' and id > '".$data['unread_lastid']."' and is_read = 0  and notification_type='sent' order by created_at desc";
        
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    // public static function appointmentCreatedRoom($data){
    //     return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_is_online')
    //     ->join('doctors', 'doctors.user_id','=','virtual_is_online.doctors_id')
    //     ->select('virtual_is_online.*', 'doctors.name as doctors_name')
    //     ->where('virtual_is_online.patient_id', $data['user_id'])
    //     ->whereNotNull('virtual_is_online.room_number')
    //     ->whereNotNull('virtual_is_online.doctors_webrtc_id')
    //     ->where('virtual_is_online.checkup_status', 'incomplete')
    //     ->first();
    // }

    public static function appointmentCreatedRoom($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_call')
            ->join('doctors', 'doctors.user_id','=','virtual_call.doctors_userid')
            ->select('virtual_call.*', 'doctors.name as doctors_name')
            ->where('virtual_call.patient_userid', $data['user_id']) 
            ->get();
    }


    public static function appointmentSetDone($data){
        date_default_timezone_set('Asia/Manila');
        // set appointment complete
        DB::connection($data['connection'] =='online' ? 'mysql2' : 'mysql')->table('virtual_is_online')->where('appointment_id', $data['appointment_id'])->update([
            'checkup_status' => 'complete',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // transaction set done
        DB::connection($data['connection'] =='online' ? 'mysql2' : 'mysql')->table('patients_credit_transaction')
        ->where('reference_no', $data['reference_no'])
        ->update([
            'transaction_status' => 'successful',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // appointment set successful
        return DB::connection($data['connection'] =='online' ? 'mysql2' : 'mysql')->table('virtual_appointment')
        ->where('reference_no', $data['reference_no'])
        ->where('appointment_id', $data['appointment_id'])
        ->update([
            'appointment_status' => 'successful',
            'consumed_time' => $data['consumed_time'],
            'appointment_done_on' => date('Y-m-d H:i:s'),
            'process_done_by' => $data['user_id'].'-'.$data['username'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public static function getappointmentByDoctor($data){
        $query = "SELECT *,
        (SELECT concat(lastname,', ',firstname) from patients where patients.user_id = virtual_appointment.patient_id) as patients,
        (SELECT services from doctors_appointment_services where doctors_appointment_services.service_id = virtual_appointment.doctors_service_id limit 1) as services,
        (SELECT amount from doctors_appointment_services where doctors_appointment_services.service_id = virtual_appointment.doctors_service_id limit 1) as token
        from virtual_appointment where virtual_appointment.doctors_id = '".$data['user_id']."'";
        
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPatientsLocalRecord($data){  
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('appointment_list')
        ->where('patients_id', $data['patient_id'])
        ->where('doctors_id', (new _Doctor)::getDoctorsId($data['user_id'])->doctors_id)
        ->get();
    }

    public static function getPatientsVirtualRecord($data){ 
        $patient = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('patients')->select('user_id')->where('patient_id', $data['patient_id'])->get();
        if(count($patient) > 0){
            return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')
                ->table('virtual_appointment')
                ->where('patient_id', $patient[0]->user_id)
                ->where('doctors_id', $data['user_id'])
                ->get();
        }else{ return []; }
        
    }

    public static function getDoctorsLocalAppointment($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('appointment_list') 
        ->select('app_date as date', 'patients_id as title')
        ->where('doctors_id', (new _Doctor)::getDoctorsId($data['user_id'])->doctors_id)
        ->get();
    }

    public static function createLocalappointment($data){ 
        date_default_timezone_set('Asia/Manila');  
            
        DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('patients')->where('patient_id', $data['patient_id'])->update([ 
            'height'=>$data['height'],
            'weight'=>$data['weight'], 
            'allergies'=>$data['allergies'],
            'medication'=>$data['medication'],
            'remarks'=>$data['app_reason'], 
            'updated_at'=>date('Y-m-d H:i:s'), 
        ]); 

        $appdate = date('Y-m-d', strtotime($data['app_date']));
        $apptime = date('H:i:s', strtotime($data['app_time']));
        $findate = date('Y-m-d H:i:s', strtotime($appdate.''.$apptime)); 
        $appid = 'app-'.time().rand();

        DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('appointment_list')->insert([
            'appointment_id' => $appid,
            'patients_id' => $data['patient_id'],
            'doctors_id' => (new _Doctor)::getDoctorsId($data['doctors_id'])->doctors_id,
            'services' => $data['service'],
            'amount' => $data['service_fee'],
            'app_date' => $findate, 
            'app_reason' => $data['app_reason'],
            'apperance' => 'walk-in',
            'is_waiting' => 0, 
            'is_complete' => 0,
            'is_remove' => 0,  
            'status' => 1,
            'updated_at'=>date('Y-m-d H:i:s'),
            'created_at'=>date('Y-m-d H:i:s')
        ]);
    
        // add to bill
        DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')->insert([
            'epb_id' => 'epb-'.time().rand(),
            'trace_number' => $appid,
            'doctors_id' => (new _Doctor)::getDoctorsId($data['doctors_id'])->doctors_id,
            'patient_id' => $data['patient_id'],
            'bill_name' => $data['service'],
            'bill_amount'  => $data['service_fee'],
            'bill_from'  => 'appointment',
            'updated_at'=>date('Y-m-d H:i:s'),
            'created_at'=>date('Y-m-d H:i:s')
        ]);

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('patients_history')->insert([
            'ph_id'=>'ph-'.rand(0, 999999),
            'patient_id'=>$data['patient_id'],  
            'street'=>$data['old_street'],
            'barangay'=>$data['old_brgy'],
            'city'=>$data['old_city'],
            'zip'=>$data['old_zip'],
            'height'=>$data['old_height'],
            'weight'=>$data['old_weight'],
            'occupation'=>$data['old_occupation'],
            'allergies'=>$data['old_alergies'],
            'medication'=>$data['old_medication'],
            'remarks'=>$data['old_reason'],
            'updated_at'=>date('Y-m-d H:i:s'),
            'created_at'=>date('Y-m-d H:i:s')
        ]);  
    } 

    public static function getClinicListVirtual($data){
        // return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('virtual_clinic')
        //     ->join('clinic', 'clinic.clinic_id' ,'=', 'virtual_clinic.clinic_id')
        //     ->select('virtual_clinic.*', 'clinic.*')    
        //     ->where('virtual_clinic.status', 1)
        //     ->get();

        $query = "SELECT *, 
        (SELECT IFNULL(sum(id), 0) from virtual_clinic_doctors where virtual_clinic_doctors.clinic_id = virtual_clinic.clinic_id ) as total_doctors
        from virtual_clinic JOIN  clinic where virtual_clinic.clinic_id = clinic.clinic_id and virtual_clinic.status = 1";
         
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getClinicDetails($data){
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic')->where('clinic_id', $data['clinic_id'])->first();
    }

    public static function sendInquiry($data){

        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic_inquiries')->insert([
            'clinic_id' => $data['clinic_id'],
            'patient_id' => $data['patient_id'],
            'message' => $data['message'],
            'send_by' => $data['send_by'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    //jhomar
    // public static function setAsRead($data){

    //     date_default_timezone_set('Asia/Manila');

    //     return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic_inquiries')
    //         ->where('clinic_id', $data['clinic_id'])
    //         ->where('patient_id', $data['patient_id']) 
    //         ->update([
    //             'is_read' => 1,
    //             'updated_at' => date('Y-m-d H:i:s') 
    //         ]);
    // }
    public static function setAsRead($data){
        date_default_timezone_set('Asia/Manila');
        $patient_id = _Patient::getPatientId($data['patient_id']);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')
            ->where('patient_id', $patient_id)
            ->where('category', 'appointment')
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic_inquiries')
            ->where('clinic_id', $data['clinic_id'])
            ->where('patient_id', $data['patient_id']) 
            ->update([
                'is_read' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }
    //jhomar
    // public static function getInquiries($data){

    //     _Appointment::setAsRead($data);

    //     return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic_inquiries')
    //         ->where('clinic_id', $data['clinic_id'])
    //         ->where('patient_id', $data['patient_id'])
    //         ->get();
    // }
    public static function getInquiries($data){
        
        _Appointment::setAsRead($data);
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic_inquiries')
            ->where('clinic_id', $data['clinic_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getInquiryLastMsg($data){
        date_default_timezone_set('Asia/Manila'); 
        
        _Appointment::setInquiryAsRead($data);

        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic_inquiries')
            ->where('clinic_id', $data['clinic_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('id','>', $data['lastmessage_id'])
            ->get();
    }

    public static function setInquiryAsRead($data){

        date_default_timezone_set('Asia/Manila'); 
        
        return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('clinic_inquiries')
            ->where('clinic_id', $data['clinic_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('send_by', 'Clinic')
            ->update([
                'is_read' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public static function getClinicDoctorsList($data){
        $query = "SELECT *, 
        (SELECT name from doctors where doctors.user_id = clinic_doctorslist.doctor_userid) as doctors_name,
        (SELECT specialization from doctors where doctors.user_id = clinic_doctorslist.doctor_userid) as doctors_spicialty,
        (SELECT image from doctors where doctors.user_id = clinic_doctorslist.doctor_userid) as doctors_image
        from clinic_doctorslist where `clinic_id` = '".$data['clinic_id']."' order by doctors_name asc";
         
        $result = DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->getPdo()->prepare($query);  
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);  
    }

    public static function setAppointmentAsViewByDoctor($data){

        date_default_timezone_set('Asia/Manila'); 

        return DB::table('doctors_notification')
            ->where('order_id', $data['appointment_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('category', 'appointment')
            ->where('department', 'local-appointment')
            ->update([ 
                'is_view' => 1, 
                'updated_at' => date('Y-m-d H:i:s'), 
            ]); 
    }
}  