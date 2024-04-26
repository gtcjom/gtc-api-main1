<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Encoder extends Model
{
    public static function getEncoderDocId($userid)
    {
        return DB::table('encoder')->where('user_id', $userid)->first();
    }

    public static function getEncoderDocUserIdId($doctorsid)
    {
        return DB::table('doctors')->where('doctors_id', $doctorsid)->first();
    }

    public static function hisSecretaryGetHeaderInfo($data)
    {
        return DB::table('encoder')->where('user_id', $data['user_id'])->first();
    }

    public static function hisSecretaryNewPatient($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        $patientid = 'p-' . rand(0, 999) . time();
        $userid = 'u-' . rand(0, 8888) . time();

        $doctorUserId = '';
        if (!empty($data['doctor'])) {
            $doctorUserId = _Encoder::getEncoderDocUserIdId($data['doctor'])->user_id;
        }

        if ($data['has_appointment'] == 'Yes') {
            $appid = 'app-' . time() . rand();
            $orderid = 'order-' . time() . rand(0, 99);
            $findate = date('Y-m-d H:i:s', strtotime($data['app_date']));

            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->insert([
                'appointment_id' => $appid,
                'patients_id' => $patientid,
                'encoders_id' => $data['user_id'],
                'doctors_id' => $doctorUserId,
                'services' => $data['app_service'],
                'amount' => $data['app_amount'],
                'app_date' => $findate,
                'app_reason' => $data['app_reason'],
                'apperance' => 'walk-in',
                'is_waiting' => 0,
                'is_complete' => 0,
                'is_remove' => 0,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')->insert([
                'epb_id' => 'epb-' . time() . rand(),
                'trace_number' => $appid,
                'doctors_id' => $doctorUserId,
                'patient_id' => $patientid,
                'bill_name' => $data['app_service'],
                'bill_amount' => $data['app_amount'],
                'bill_from' => 'appointment',
                'order_id' => $orderid,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('doctors_notification')->insert([
                'notif_id' => 'nid-' . rand(0, 99999),
                'order_id' => $appid,
                'patient_id' => $patientid,
                'doctor_id' => $doctorUserId,
                'category' => 'appointment',
                'department' => 'local-appointment',
                'is_view' => 0,
                'notification_from' => 'local',
                'message' => 'New local appointment added by secretary',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::connection('mysql')->table('patients_permission')->insert([
            'permission_id' => 'permission-' . time(),
            'doctors_id' => $doctorUserId,
            'patients_id' => $userid,
            'permission_on' => 'PROFILE',
            'permission_status' => 'approved',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::connection('mysql')->table('doctors_patients')->insert([
            'dp_id' => 'dp-' . time(),
            'management_id' => $data['management_id'],
            'doctors_userid' => $doctorUserId,
            'patient_userid' => $userid,
            'added_by' => $data['user_id'],
            'added_from' => 'local',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (!empty($data['weight'])) {
            DB::connection('mysql')->table('patients_weight_history')->insert([
                'pwh_id' => 'pwh-' . rand(0, 99999),
                'patient_id' => $patientid,
                'weight' => $data['weight'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['pulse'])) {
            DB::connection('mysql')->table('patients_pulse_history')->insert([
                'pph_id' => 'pph-' . rand(0, 99999),
                'patients_id' => $patientid,
                'pulse' => $data['pulse'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['temp'])) {
            DB::connection('mysql')->table('patients_temp_history')->insert([
                'pth_id' => 'pth-' . rand(0, 99999),
                'patients_id' => $patientid,
                'temp' => $data['temp'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['bp_systolic']) && !empty($data['bp_diastolic'])) {
            DB::connection('mysql')->table('patients_lab_history')->insert([
                'plh_id' => 'plh-' . rand(0, 99999),
                'patients_id' => $patientid,
                'systolic' => $data['bp_systolic'],
                'diastolic' => $data['bp_diastolic'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::connection('mysql')->table('patients')->insert([
            'patient_id' => $patientid,
            'management_id' => $data['management_id'],
            'user_id' => $userid,
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'middle' => $data['middlename'],
            'birthday' => date('Y-m-d', strtotime($data['birthday'])),
            'birthplace' => $data['birthplace'],
            'civil_status' => $data['civil_status'],
            'religion' => $data['religion'],
            'occupation' => $data['occupation'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'telephone' => $data['telephone'],
            'street' => $data['street'],
            'barangay' => $data['barangay'],
            'city' => $data['city'],
            'height' => $data['height'],
            'weight' => $data['weight'],
            'pulse' => $data['pulse'],
            'temperature' => $data['temp'],
            'blood_systolic' => $data['bp_systolic'],
            'blood_diastolic' => $data['bp_diastolic'],
            'blood_type' => $data['blood_type'],
            'image' => $filename,
            'join_category' => 'hosp-app',
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hisSecretaryGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM encoder WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisSecretaryUpdatePersonalInfo($data)
    {
        return DB::table('encoder')
            ->where('user_id', $data['user_id'])
            ->update([
                'name' => $data['fullname'],
                'address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisSecretaryUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('encoder')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisSecretaryUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisSecretaryUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisSecretaryPatientInfo($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')
            ->where('patient_id', $data['patient_id'])
            ->first();
    }

    public static function hisSecretaryGetAppointmentLocal($data)
    {
        $patientid = $data['patient_id'];

        $query = " SELECT *, doctors_id as did,
        (SELECT name from doctors where doctors_id = did limit 1) as name
        from appointment_list where patients_id = '$patientid' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        //     ->table('appointment_list')
        //     ->leftJoin('doctors', 'doctors.doctors_id', '=', 'appointment_list.doctors_id')
        //     ->select('appointment_list.*', 'doctors.name')
        //     ->where('appointment_list.patients_id', $data['patient_id'])
        //     ->get();
    }

    public static function hisSecretaryGetPatientInfo($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients')->where('patient_id', $data['patient_id'])->first();
    }

    public static function hisSecretaryUpdatePatientInfo($data)
    {
        date_default_timezone_set('Asia/Manila');

        if (!empty($data['weight'])) {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_weight_history')->insert([
                'pwh_id' => 'pwh-' . rand(0, 99999),
                'patient_id' => $data['patient_id'],
                'weight' => $data['weight'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['pulse'])) {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_pulse_history')->insert([
                'pph_id' => 'pph-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'pulse' => $data['pulse'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['temp'])) {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_temp_history')->insert([
                'pth_id' => 'pth-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'temp' => $data['temp'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['bp_systolic']) && !empty($data['bp_diastolic'])) {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_lab_history')->insert([
                'plh_id' => 'plh-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'systolic' => $data['bp_systolic'],
                'diastolic' => $data['bp_diastolic'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients')->where('patient_id', $data['patient_id'])->update([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'middle' => $data['middlename'],
            'birthday' => date('Y-m-d', strtotime($data['birthday'])),
            'birthplace' => $data['birthplace'],
            'civil_status' => $data['civil_status'],
            'religion' => $data['religion'],
            'occupation' => $data['occupation'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'telephone' => $data['telephone'],
            'street' => $data['street'],
            'barangay' => $data['barangay'],
            'city' => $data['city'],
            'height' => $data['height'],
            'weight' => $data['weight'],
            'pulse' => $data['pulse'],
            'temperature' => $data['temp'],
            'blood_systolic' => $data['bp_systolic'],
            'blood_diastolic' => $data['bp_diastolic'],
            'blood_type' => $data['blood_type'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hisSecretaryCreateAppointment($data)
    {
        date_default_timezone_set('Asia/Manila');

        $findate = date('Y-m-d H:i:s', strtotime($data['app_date']));
        $encoderId = '56456564564';
        $appid = 'app-' . time() . rand(0, 99);
        $orderid = 'order-' . time() . rand(0, 99);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->insert([
            'appointment_id' => $appid,
            'patients_id' => $data['patient_id'],
            'encoders_id' => $encoderId,
            'doctors_id' => $data['doctor'],
            'services' => $data['service'],
            'amount' => $data['fee'],
            'app_date' => $findate,
            'app_reason' => $data['reason'],
            'apperance' => 'walk-in',
            'is_waiting' => 0,
            'is_complete' => 0,
            'is_remove' => 0,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // add to permission
        $getDoctorsUserId = DB::table('doctors')->select('user_id')->where('doctors_id', $data['doctor'])->get();
        $getPatientUserId = DB::table('patients')->select('user_id')->where('patient_id', $data['patient_id'])->get();

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_permission')->insert([
            'permission_id' => 'permission-' . time() . rand(),
            'doctors_id' => count($getDoctorsUserId) > 0 ? $getDoctorsUserId[0]->user_id : null,
            'patients_id' => count($getPatientUserId) > 0 ? $getPatientUserId[0]->user_id : null,
            'permission_on' => 'PROFILE',
            'permission_status' => 'approved',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // add to bill
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')->insert([
            'epb_id' => 'epb-' . time() . rand(),
            'trace_number' => $appid,
            'doctors_id' => $data['doctor'],
            'patient_id' => $data['patient_id'],
            'bill_name' => $data['service'],
            'bill_amount' => $data['fee'],
            'bill_from' => 'appointment',
            'order_id' => $orderid,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hisSecretaryRescheduleAppointment($data)
    {
        date_default_timezone_set('Asia/Manila');

        $findate = date('Y-m-d H:i:s', strtotime($data['app_date']));
        $qry = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('appointment_list')
            ->where('appointment_id', $data['appointment_id'])
            ->first();

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->insert([
                'notif_id' => 'nid-' . rand(0, 99999),
                'order_id' => $data['appointment_id'],
                'patient_id' => $qry->patients_id,
                'doctor_id' => $qry->doctors_id,
                'category' => 'appointment',
                'department' => 'local-appointment',
                'is_view' => 0,
                'notification_from' => 'local',
                'message' => 'local appointment reshedule by secretary',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('appointment_list')
            ->where('appointment_id', $data['appointment_id'])
            ->update([
                'is_reschedule' => 1,
                'is_reschedule_date' => $findate,
                'is_reschedule_reason' => $data['reason'],
                'apperance' => 'walk-in',
                'is_waiting' => 0,
                'is_complete' => 0,
                'is_remove' => 0,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisSecretaryGetPatientsBillings($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')
            ->join('patients', 'patients.patient_id', '=', 'encoder_patientbills_unpaid.patient_id')
            ->select('encoder_patientbills_unpaid.*', 'patients.firstname as fname', 'patients.lastname as  lname')
            ->groupBy('encoder_patientbills_unpaid.patient_id')
            ->get();
    }

    public static function hisSecretaryGetPatientsBillingsDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function hisSecretaryBillingCancel($data)
    {
        return DB::connection('mysql')->table('encoder_patientbills_unpaid')
            ->where('epb_id', $data['cancel_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function hisSecretaryBillingSetAsPaid($data)
    {
        date_default_timezone_set('Asia/Manila');
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->get();

        $records = [];

        foreach ($query as $v) {
            $records[] = array(
                'epr_id' => rand(0, 9999) . '-' . time(),
                'trace_number' => $v->trace_number,
                'management_id' => $data['management_id'],
                'doctors_id' => $v->doctors_id,
                'patient_id' => $v->patient_id,
                'bill_name' => $v->bill_name,
                'bill_amount' => $v->bill_amount,
                'bill_from' => $v->bill_from,
                'bill_from' => $v->bill_from,
                'bill_payment' => $data['payment'],
                'bill_department' => $v->bill_department,
                'bill_total' => $data['amountto_pay'],
                'process_by' => $data['user_id'],
                'receipt_number' => $data['receipt_number'],
                'order_id' => $v->order_id,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );

            if ($v->bill_from == 'appointment') {
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('appointment_list')
                    ->where('appointment_id', $v->trace_number)
                    ->update([
                        'is_paid_bysecretary' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_records')
            ->insert($records);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function hisSecretaryGetReceiptHeader($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors')
            ->leftJoin('doctors_rxheader', 'doctors_rxheader.doctors_id', '=', 'doctors.doctors_id')
            ->where('doctors.doctors_id', $data['doctors_id'])
            ->get();
    }

    public static function hisSecretaryReceiptDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'encoder_patientbills_records.patient_id')
            ->select('encoder_patientbills_records.*', 'patients.firstname as fname', 'patients.lastname as  lname', 'patients.street as street', 'patients.barangay as barangay', 'patients.city as city')
            ->where('encoder_patientbills_records.receipt_number', $data['receipt_number'])
            ->get();
    }

    public static function hisSecretaryGetBillingRecords($data)
    {
        $query = " SELECT encoder_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment,  patients.firstname as fname, patients.lastname as lname, patients.street as street, patients.barangay as barangay, patients.city as city,
            (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where encoder_patientbills_records.receipt_number = encoder_patientbills_records.receipt_number and is_refund = 1) as totalrefund
        from encoder_patientbills_records, patients
        where encoder_patientbills_records.management_id = '" . $data['management_id'] . "'
        and patients.patient_id = encoder_patientbills_records.patient_id
        group by encoder_patientbills_records.receipt_number
        order by encoder_patientbills_records.created_at desc";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisSecretaryRefundOrderList($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_records')
            ->join('encoder', 'encoder.user_id', '=', 'encoder_patientbills_records.is_refund_by')
            ->select('encoder_patientbills_records.*', 'encoder.name as secname')
            ->where('encoder_patientbills_records.is_refund', 1)
            ->where('encoder_patientbills_records.management_id', $data['management_id'])
            ->get();
    }

    public static function hisSecretaryGetBillingRecordsDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_records')
            ->where('receipt_number', $data['receipt_id'])
            ->get();
    }

    public static function hisSecretaryRefundOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('encoder_patientbills_records')
            ->where('epr_id', $data['epr_id'])
            ->update([
                'is_refund' => 1,
                'is_refund_reason' => $data['refund_reason'],
                'is_refund_date' => date('Y-m-d H:i:s'),
                'is_refund_by' => $data['user_id'],
            ]);
    }

}
