<?php

namespace App\Models;

use App\Models\_Doctor;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class _Admission extends Model
{
    public static function getEncoderDocUserIdId($doctorsid)
    {
        return DB::table('doctors')->where('doctors_id', $doctorsid)->first();
    }

    public static function getAdmissionIdByUserId($userid)
    {
        return DB::table('admission_account')->where('user_id', $userid)->first();
    }

    public static function hisadmissionGetHeaderInfo($data)
    {
        // return DB::table('admission_account')
        //     ->select('admission_id', 'user_fullname as name', 'image')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

        return DB::table('admission_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'admission_account.user_id')
            ->select('admission_account.admission_id', 'admission_account.user_fullname as name', 'admission_account.image', 'admission_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('admission_account.user_id', $data['user_id'])
            ->first();
    }

    public static function hisadmissionGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM admission_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisadmissionUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('admission_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisadmissionUpdatePersonalInfo($data)
    {
        return DB::table('admission_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisadmissionUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisadmissionUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisadmissionGetPatientList($data)
    {
        // by filter
        // $company = $data['company'];
        // if($data['company'] == 'all'){
        //     return DB::connection('mysql')
        //     ->table('patients')
        //     ->select('firstname', 'lastname', 'patient_id', 'image', 'middle')
        //     ->get();
        // }else{
        //     return DB::connection('mysql')
        //     ->table('patients')
        //     ->select('firstname', 'lastname', 'patient_id', 'image', 'middle')
        //     ->where('main_mgmt_id', $data['main_mgmt_id'])
        //     ->where('company', $company)
        //     ->orderBy('lastname', 'ASC')
        //     ->get();
        // }

        return DB::connection('mysql')
            ->table('patients')
            ->select('firstname', 'lastname', 'patient_id', 'image', 'middle')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
        // ->where('company', $company)
            ->orderBy('lastname', 'ASC')
            ->get();

//old
        // return DB::connection('mysql')
        // ->table('doctors_patients')
        // ->join('patients', 'patients.user_id', '=' , 'doctors_patients.patient_userid')
        // ->select('doctors_patients.doctors_userid','patients.firstname', 'patients.lastname', 'patients.patient_id', 'patients.image', 'patients.middle')
        // ->where('doctors_patients.management_id' ,$data['management_id'])
        // ->orderBy('patients.lastname', 'ASC')
        // ->get();
    }

    public static function hisadmissionGetAllDoctors($data)
    {
        return DB::table('doctors')->select('doctors_id as value', 'name as label')->where('management_id', $data['management_id'])->get();
    }

    public static function hisadmissionNewPatient($data)
    {
        date_default_timezone_set('Asia/Manila');
        $patientid = 'p-' . rand(0, 999) . time();
        $userid = 'u-' . rand(0, 8888) . time();

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

        if ($data['transaction_type'] == 'reg-cashier-hmo' || $data['transaction_type'] == 'reg-doctor') {
            DB::table('patient_queue')->insert([
                'pq_id' => 'pq-' . time() . rand(0, 99999),
                'patient_id' => $patientid,
                'trace_number' => $data['trace_number'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'type' => 'cashier',
                'priority_sequence' => 4,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::table('patient_queue')->insert([
            'pq_id' => 'pq-' . time() . rand(0, 99999),
            'patient_id' => $patientid,
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'type' => $data['transaction_type'] == 'reg-endorse' ? 'endorsement' : ($data['transaction_type'] == 'reg-cashier' ? 'cashier' : 'nursing-station'),
            'trace_number' => $data['trace_number'],
            'priority_sequence' => $data['transaction_type'] == 'reg-endorse' ? 2 : ($data['transaction_type'] == 'reg-cashier' ? 4 : 1),
            'patient_sent_to' => $data['patient_sent_to'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('patients_contacttracing')->insert([
            'pct_id' => 'pct-' . time() . rand(0, 99999),
            'patient_id' => $patientid,
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'temperature' => $data['temp'],
            'sickness' => !empty($data['sickness']) ? $data['sickness'] : null,
            'purpose' => $data['purpose_ofvisit'],
            'allergies' => $data['allergies'],
            'last_xray_taken' => $data['last_xray_check'],
            'last_xray_result' => $data['last_xray_result'],
            'history_of_travel_date' => !empty($data['history_of_travel_date']) ? date('Y-m-d', strtotime($data['history_oftravel_date'])) : null,
            'history_of_travel_days' => $data['history_oftravel_days'],
            'history_of_travel_place' => $data['history_oftravel_place'],
            'contact_with_puipum' => $data['contact_withpuipum'],
            'contact_with_positive' => $data['contact_withpositive'],
            'crt_purpose' => $data['crt_purpose'],
            'crt_requestedby' => $data['requested_by'],
            'work_company' => $data['complete_company'],
            'contact_information' => $data['contact_information'],
            'allow_quarantine_ifpositive' => $data['quarantine_ifpositive'],
            'triage_staff' => $data['user_id'],
            'transaction_type' => $data['transaction_type'] == 'reg-endorse' ? 'Charge' : ($data['transaction_type'] == 'reg-cashier-hmo' ? 'Charge' : 'Cash'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::connection('mysql')->table('patients')->insert([
            'patient_id' => $patientid,
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
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
            'philhealth' => $data['philhealth'],
            'company' => $data['company'],
            'temperature' => $data['temp'],
            // 'image' => $filename,
            'join_category' => 'hosp-app',
            'added_by' => $data['user_id'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function hisadmissionGetPatientInformation($data)
    {
        $patient = DB::connection('mysql')->table('patients')
            ->leftJoin('patients_contacttracing', 'patients_contacttracing.patient_id', '=', 'patients.patient_id')
            ->select('patients.*', 'patients_contacttracing.sickness', 'patients_contacttracing.temperature', 'patients_contacttracing.created_at as latestCCRecord', 'patients_contacttracing.purpose', 'patients_contacttracing.allergies', 'patients_contacttracing.last_xray_taken', 'patients_contacttracing.last_xray_result', 'patients_contacttracing.history_of_travel_date', 'patients_contacttracing.history_of_travel_days', 'patients_contacttracing.history_of_travel_place', 'patients_contacttracing.contact_with_puipum', 'patients_contacttracing.contact_with_positive', 'patients_contacttracing.crt_purpose', 'patients_contacttracing.crt_requestedby', 'patients_contacttracing.work_company', 'patients_contacttracing.contact_information', 'patients_contacttracing.allow_quarantine_ifpositive', 'patients_contacttracing.triage_staff')
            ->where('patients.patient_id', $data['patient_id'])
            ->orderBy('patients.lastname', 'ASC')
            ->orderBy('patients_contacttracing.created_at', 'DESC')
            ->first();

            $patient->avatar =  is_null($patient->avatar) ? "": Storage::url($patient->avatar);
        return $patient;

        // return DB::connection('mysql')->table('patients')
        //     ->leftJoin('patients_contacttracing', 'patients_contacttracing.patient_id', '=', 'patients.patient_id')
        //     ->select('patients.*', 'patients_contacttracing.sickness', 'patients_contacttracing.temperature', 'patients_contacttracing.created_at as latestCCRecord')
        //     // ->whereNull('patients_contacttracing.purpose')
        //     ->where('patients.patient_id', $data['patient_id'])
        //     ->orderBy('patients.lastname', 'ASC')
        //     ->orderBy('patients_contacttracing.created_at', 'DESC')
        //     ->first();
    }

    public static function hisadmissionGetPatientInformationTriage($data)
    {
        return DB::connection('mysql')->table('patients')
            ->join('patients_contacttracing', 'patients_contacttracing.patient_id', '=', 'patients.patient_id')
            ->leftJoin('triage_account', 'triage_account.user_id', '=', 'patients_contacttracing.triage_staff')
            ->select('patients.*', 'patients_contacttracing.pct_id as contactTracingID', 'patients_contacttracing.sickness', 'patients_contacttracing.created_at as latestCCRecord', 'patients_contacttracing.temperature as latestCCTemp', 'patients_contacttracing.purpose', 'patients_contacttracing.crt_purpose', 'triage_account.user_fullname as triageName')
            ->whereNull('patients_contacttracing.purpose')
            ->whereNull('patients_contacttracing.crt_purpose')
            ->where('patients.patient_id', $data['patient_id'])
            ->groupBy('patients_contacttracing.patient_id')
            ->orderBy('patients_contacttracing.created_at', 'DESC')
            ->first();
    }

    public static function hisadmissionGetPatientInfo($data)
    {
        return DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])->first();
    }

    public static function hisadmissionUpdatePatientInfo($data)
    {
        date_default_timezone_set('Asia/Manila');

        // if (!empty($data['weight'])) {
        //     DB::connection('mysql')->table('patients_weight_history')->insert([
        //         'pwh_id' => 'pwh-' . rand(0, 99).time(),
        //         'patient_id' => $data['patient_id'],
        //         'weight' => $data['weight'],
        //         'added_by' => $data['user_id'],
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ]);
        // }

        // if (!empty($data['pulse'])) {
        //     DB::connection('mysql')->table('patients_pulse_history')->insert([
        //         'pph_id' => 'pph-' . rand(0, 99).time(),
        //         'patients_id' => $data['patient_id'],
        //         'pulse' => $data['pulse'],
        //         'added_by' => $data['user_id'],
        //         'status' => 1,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ]);
        // }

        // if (!empty($data['temp'])) {
        //     DB::connection('mysql')->table('patients_temp_history')->insert([
        //         'pth_id' => 'pth-' . rand(0, 99).time(),
        //         'patients_id' => $data['patient_id'],
        //         'temp' => $data['temp'],
        //         'added_by' => $data['user_id'],
        //         'status' => 1,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ]);
        // }

        // if (!empty($data['bp_systolic']) && !empty($data['bp_diastolic'])) {
        //     DB::connection('mysql')->table('patients_lab_history')->insert([
        //         'plh_id' => 'plh-' . rand(0, 99).time(),
        //         'patients_id' => $data['patient_id'],
        //         'systolic' => $data['bp_systolic'],
        //         'diastolic' => $data['bp_diastolic'],
        //         'added_by' => $data['user_id'],
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ]);
        // }

        return DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])->update([
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
            'philhealth' => $data['philhealth'],
            'company' => $data['company'],
            // 'height' => $data['height'],
            // 'weight' => $data['weight'],
            // 'pulse' => $data['pulse'],
            // 'temperature' => $data['temp'],
            // 'blood_systolic' => $data['bp_systolic'],
            // 'blood_diastolic' => $data['bp_diastolic'],
            // 'blood_type' => $data['blood_type'],
            'patient_condition' => $data['patient_condition'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getImagingDetails($data)
    {
        return DB::table('imaging')->where('management_id', $data['management_id'])->groupBy('imaging_id')->get();
    }

    public static function getContactTracingRecord($data)
    {
        return DB::table('patients_contacttracing')->where('patient_id', $data['patient_id'])->orderBy('id', "desc")->get();
    }

    public static function imagingOrderList($data)
    {
        return DB::table('imaging_order_menu')
            ->where('management_id', $data['vmanagementId'])
            ->get();
    }

    public static function imagingOrderSelectedDetails($data)
    {
        return DB::connection('mysql')->table('imaging_order_menu')
            ->where('order_id', $data['order_id'])
            ->first();
    }

    public static function imagingAddOrderUnsavelist($data)
    {
        return DB::table('imaging_center_unsaveorder')
            ->where('patients_id', $data['patient_id'])
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function imagingAddOrder($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::table('imaging_center_unsaveorder')->insert([
            'icu_id' => 'icu-' . rand(0, 9999) . time(),
            'patients_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'doctors_id' => 'admission-order',
            'imaging_order_id' => $data['imaging_order_id'],
            'imaging_order' => $data['order'],
            'imaging_order_remarks' => $data['remarks'],
            'amount' => $data['amount'],
            'management_id' => $data['imaging_center'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
            'order_from' => 'local',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function imagingOrderUnsaveProcess($data)
    {
        $unsave = DB::table('imaging_center_unsaveorder')
            ->where('management_id', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->get();

        $process = [];

        foreach ($unsave as $v) {
            $process[] = array(
                'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
                'trace_number' => $v->trace_number,
                'doctors_id' => $v->doctors_id,
                'patient_id' => $v->patients_id,
                'management_id' => $v->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => $v->laboratory_id,
                'bill_name' => $v->imaging_order,
                'bill_amount' => $v->amount,
                'bill_department' => 'imaging',
                'bill_from' => 'imaging',
                'order_id' => $v->imaging_order_id,
                'remarks' => $v->imaging_order_remarks,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
            );
        }

        DB::table('cashier_patientbills_unpaid')
            ->insert($process);

        return DB::table('imaging_center_unsaveorder')
            ->where('management_id', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->delete();
    }

    public static function getImagingOrderList($data)
    {
        return DB::table('imaging_center')
            ->where('manage_by', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->get();
    }

    public static function getUnsaveLabOrder($data)
    {
        return DB::connection('mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function addLabOrderTounsave($data)
    {
        return DB::connection('mysql')
            ->table('laboratory_unsaveorder')
            ->insert([
                'lu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'doctor_id' => 'admission-order',
                'laborotary_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'laboratory_test_id' => $data['laboratory_test_id'],
                'laboratory_test' => $data['laboratory_test'],
                'laboratory_rate' => $data['laboratory_rate'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function processLabOrder($data)
    {
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('laborotary_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
            ->get();

        $trace_number = $data['trace_number'];
        $secrtry_orderunpaid = [];

        foreach ($query as $v) {
            $orderid = $v->laboratory_test_id;

            $secrtry_orderunpaid[] = array(
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'doctors_id' => $v->doctor_id,
                'patient_id' => $data['patient_id'],
                'management_id' => _Doctor::getLaboratoryIdByMgt($data)->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $v->laboratory_test,
                'bill_amount' => $v->laboratory_rate,
                'bill_department' => $v->department,
                'bill_from' => 'laboratory',
                'order_id' => $orderid,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($v->department == 'hemathology') {
                if ($v->laboratory_test == 'cbc' || $v->laboratory_test == 'cbc platelet') {
                    // insert when order id not exist
                    $checkCBC = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_cbc')
                        ->where('order_id', $orderid)
                        ->where('order_status', 'new-order')
                        ->get();

                    if (count($checkCBC) < 1) {
                        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                            ->table('laboratory_cbc')
                            ->insert([
                                'lc_id' => 'lc-' . rand(0, 99999) . time(),
                                'order_id' => $orderid,
                                'patient_id' => $data['patient_id'],
                                'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                                'remarks' => $data['remarks'],
                                'order_status' => 'new-order',
                                'trace_number' => $trace_number,
                                'status' => 1,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                    }
                }

                // insert when order id not exist
                $checkorderIdinHema = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hematology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory hema if order id not exist
                if (count($checkorderIdinHema) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_hematology')
                        ->insert([
                            'lh_id' => 'lh-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'serology') {
                // insert when order id not exist
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_sorology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory soro if order id not exist
                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_sorology')
                        ->insert([
                            'ls_id' => 'ls-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'clinical-microscopy') {
                // insert when order id not exist
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_microscopy')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory microscopyclinical if order id not exist
                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_microscopy')
                        ->insert([
                            'lm_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'spicemen' => $data['mc_spicemen'],
                            'order_remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'fecal-analysis') {
                // insert when order id not exist
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_fecal_analysis')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory microscopyclinical if order id not exist
                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_fecal_analysis')
                        ->insert([
                            'lfa_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'stool-test') {
                // insert when order id not exist
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_stooltest')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory microscopyclinical if order id not exist
                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_stooltest')
                        ->insert([
                            'lf_id' => 'lf-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'clinical-chemistry') {
                // insert when order id not exist
                $checkorderIdinChem = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_chemistry')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory chemistryclinical if order id not exist
                if (count($checkorderIdinChem) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_chemistry')
                        ->insert([
                            'lc_id' => 'lc-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'spicemen' => $data['cc_specimen'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'ecg') {
                // insert when order id not exist
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_ecg')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory microscopyclinical if order id not exist
                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_ecg')
                        ->insert([
                            'le_id' => 'le-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'urinalysis') {
                // insert when order id not exist
                $checkorderIdinUri = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_urinalysis')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory chemistryclinical if order id not exist
                if (count($checkorderIdinUri) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_urinalysis')
                        ->insert([
                            'lu_id' => 'lu-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'medical-exam') {
                // insert when order id not exist
                $checkorderIdinMed = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_medical_exam')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory microscopyclinical if order id not exist
                if (count($checkorderIdinMed) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_medical_exam')
                        ->insert([
                            'lme_id' => 'le-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'papsmear-test') {
                // insert when order id not exist
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_papsmear')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory microscopyclinical if order id not exist
                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_papsmear')
                        ->insert([
                            'ps_id' => 'ps-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            //new

            if ($v->department == 'oral-glucose') {
                $checkorderIdinOralGlucose = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_oral_glucose')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinOralGlucose) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_oral_glucose')
                        ->insert([
                            'log_id' => 'log-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'thyroid-profile') {
                $checkorderIdinThyroid = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_thyroid_profile')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinThyroid) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_thyroid_profile')
                        ->insert([
                            'ltp_id' => 'ltp-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'immunology') {
                $checkorderIdinImmunology = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_immunology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinImmunology) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_immunology')
                        ->insert([
                            'li_id' => 'li-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'miscellaneous') {
                // insert when order id not exist
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_papsmear')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                // insert to laboratory microscopyclinical if order id not exist
                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_miscellaneous')
                        ->insert([
                            'lm_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'hepatitis-profile') {
                $checkorderIdinHepatitis = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hepatitis_profile')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinHepatitis) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_hepatitis_profile')
                        ->insert([
                            'lhp_id' => 'lhp-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $trace_number,
            'patient_id' => $data['patient_id'],
            'category' => 'laboratory',
            'department' => 'doctor',
            'message' => "New laboratory test added by doctor.",
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->insert($secrtry_orderunpaid);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('laborotary_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
            ->delete();
    }

    // public static function processLabOrder($data)
    // {
    //     $query = DB::connection('mysql')
    //         ->table('laboratory_unsaveorder')
    //         ->where('patient_id', $data['patient_id'])
    //         ->where('laborotary_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
    //         ->get();

    //     $orderid = 'order-' . rand(0, 9999) . time();
    //     $secrtry_orderunpaid = [];

    //     foreach ($query as $v) {
    //         $secrtry_orderunpaid[] = array(
    //             'cpb_id' => 'epb-' . rand(0, 9999) . time(),
    //             'trace_number' => $v->laboratory_test_id,
    //             'doctors_id' => $v->doctor_id,
    //             'patient_id' => $data['patient_id'],
    //             'management_id' => _Doctor::getLaboratoryIdByMgt($data)->management_id,
    //             'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
    //             'bill_name' => $v->laboratory_test,
    //             'bill_amount' => $v->laboratory_rate,
    //             'bill_department' => $v->department,
    //             'bill_from' => 'laboratory',
    //             'order_id' => $orderid,
    //             'remarks' => $data['remarks'],
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'updated_at' => date('Y-m-d H:i:s'),
    //         );

    //         if ($v->department == 'hemathology') {
    //             // insert when order id not exist
    //             $checkorderIdinHema = DB::connection('mysql')
    //                 ->table('laboratory_hematology')
    //                 ->where('order_id', $orderid)
    //                 ->where('order_status', 'new-order')
    //                 ->get();

    //             // insert to laboratory hema if order id not exist
    //             if (count($checkorderIdinHema) < 1) {
    //                 DB::connection('mysql')
    //                     ->table('laboratory_hematology')
    //                     ->insert([
    //                         'lh_id' => 'lh-' . rand(0, 9999) . time(),
    //                         'order_id' => $orderid,
    //                         'doctor_id' => 'order-from-admission',
    //                         'patient_id' => $data['patient_id'],
    //                         'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
    //                         'remarks' => $data['remarks'],
    //                         'order_status' => 'new-order',
    //                         'status' => 1,
    //                         'created_at' => date('Y-m-d H:i:s'),
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //             }
    //         }

    //         if ($v->department == 'serology') {
    //             // insert when order id not exist
    //             $checkorderIdinSoro = DB::connection('mysql')
    //                 ->table('laboratory_sorology')
    //                 ->where('order_id', $orderid)
    //                 ->where('order_status', 'new-order')
    //                 ->get();

    //             // insert to laboratory soro if order id not exist
    //             if (count($checkorderIdinSoro) < 1) {
    //                 DB::connection('mysql')
    //                     ->table('laboratory_sorology')
    //                     ->insert([
    //                         'ls_id' => 'ls-' . rand(0, 9999) . time(),
    //                         'order_id' => $orderid,
    //                         'doctor_id' => 'order-from-admission',
    //                         'patient_id' => $data['patient_id'],
    //                         'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
    //                         'remarks' => $data['remarks'],
    //                         'order_status' => 'new-order',
    //                         'status' => 1,
    //                         'created_at' => date('Y-m-d H:i:s'),
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //             }
    //         }
    //         if ($v->department == 'clinical-microscopy') {
    //             // insert when order id not exist
    //             $checkorderIdinSoro = DB::connection('mysql')
    //                 ->table('laboratory_microscopy')
    //                 ->where('order_id', $orderid)
    //                 ->where('order_status', 'new-order')
    //                 ->get();

    //             // insert to laboratory microscopyclinical if order id not exist
    //             if (count($checkorderIdinSoro) < 1) {
    //                 DB::connection('mysql')
    //                     ->table('laboratory_microscopy')
    //                     ->insert([
    //                         'lm_id' => 'lm-' . rand(0, 9999) . time(),
    //                         'order_id' => $orderid,
    //                         'doctor_id' => 'order-from-admission',
    //                         'patient_id' => $data['patient_id'],
    //                         'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
    //                         'spicemen' => $data['mc_spicemen'],
    //                         'order_remarks' => $data['remarks'],
    //                         'order_status' => 'new-order',
    //                         'status' => 1,
    //                         'created_at' => date('Y-m-d H:i:s'),
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //             }
    //         }
    //         if ($v->department == 'clinical-chemistry') {
    //             // insert when order id not exist
    //             $checkorderIdinChem = DB::connection('mysql')
    //                 ->table('laboratory_chemistry')
    //                 ->where('order_id', $orderid)
    //                 ->where('order_status', 'new-order')
    //                 ->get();

    //             // insert to laboratory chemistryclinical if order id not exist
    //             if (count($checkorderIdinChem) < 1) {
    //                 DB::connection('mysql')
    //                     ->table('laboratory_chemistry')
    //                     ->insert([
    //                         'lc_id' => 'lc-' . rand(0, 9999) . time(),
    //                         'order_id' => $orderid,
    //                         'doctor_id' => 'order-from-admission',
    //                         'patient_id' => $data['patient_id'],
    //                         'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
    //                         'remarks' => $data['remarks'],
    //                         'spicemen' => $data['cc_specimen'],
    //                         'order_status' => 'new-order',
    //                         'status' => 1,
    //                         'created_at' => date('Y-m-d H:i:s'),
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //             }
    //         }
    //         if ($v->department == 'fecal-analysis') {
    //             // insert when order id not exist
    //             $checkorderIdinSoro = DB::connection('mysql')
    //                 ->table('laboratory_fecal_analysis')
    //                 ->where('order_id', $orderid)
    //                 ->where('order_status', 'new-order')
    //                 ->get();

    //             // insert to laboratory microscopyclinical if order id not exist
    //             if (count($checkorderIdinSoro) < 1) {
    //                 DB::connection('mysql')
    //                     ->table('laboratory_fecal_analysis')
    //                     ->insert([
    //                         'lfa_id' => 'lm-' . rand(0, 9999) . time(),
    //                         'order_id' => $orderid,
    //                         'doctor_id' => 'order-from-admission',
    //                         'patient_id' => $data['patient_id'],
    //                         'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
    //                         'remarks' => $data['remarks'],
    //                         'order_status' => 'new-order',
    //                         'status' => 1,
    //                         'created_at' => date('Y-m-d H:i:s'),
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //             }
    //         }
    //         if ($v->department == 'clinical-chemistry') {
    //             // insert when order id not exist
    //             $checkorderIdinChem = DB::connection('mysql')
    //                 ->table('laboratory_chemistry')
    //                 ->where('order_id', $orderid)
    //                 ->where('order_status', 'new-order')
    //                 ->get();

    //             // insert to laboratory chemistryclinical if order id not exist
    //             if (count($checkorderIdinChem) < 1) {
    //                 DB::connection('mysql')
    //                     ->table('laboratory_chemistry')
    //                     ->insert([
    //                         'lc_id' => 'lc-' . rand(0, 9999) . time(),
    //                         'order_id' => $orderid,
    //                         'doctor_id' => 'order-from-admission',
    //                         'patient_id' => $data['patient_id'],
    //                         'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
    //                         'remarks' => $data['remarks'],
    //                         'spicemen' => $data['cc_specimen'],
    //                         'order_status' => 'new-order',
    //                         'status' => 1,
    //                         'created_at' => date('Y-m-d H:i:s'),
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //             }
    //         }
    //     }

    //     DB::connection('mysql')->table('patients_notification')->insert([
    //         'notif_id' => 'nid-' . rand(0, 99) . time(),
    //         'order_id' => $orderid,
    //         'patient_id' => $data['patient_id'],
    //         'doctor_id' => 'order-from-admission',
    //         'category' => 'laboratory',
    //         'department' => 'doctor',
    //         'message' => "New laboratory test added by doctor.",
    //         'is_view' => 0,
    //         'notification_from' => 'virtual',
    //         'status' => 1,
    //         'updated_at' => date('Y-m-d H:i:s'),
    //         'created_at' => date('Y-m-d H:i:s'),
    //     ]);

    //     DB::connection('mysql')
    //         ->table('cashier_patientbills_unpaid')
    //         ->insert($secrtry_orderunpaid);

    //     return DB::connection('mysql')
    //         ->table('laboratory_unsaveorder')
    //         ->where('patient_id', $data['patient_id'])
    //         ->where('doctor_id', 'order-from-admission')
    //         ->where('laborotary_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
    //         ->delete();
    // }

    public static function laboratoryPaidOrderByPatient($data)
    {
        $query = "SELECT *,
            (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = '" . $data['patient_id'] . "' AND doctors_notification.category = 'laboratory' AND doctors_notification.is_view = 0 ) as countLaboratory
        from cashier_patientbills_records where `patient_id` = '" . $data['patient_id'] . "' and bill_from = 'laboratory' GROUP BY trace_number ORDER BY created_at DESC ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function laboratoryUnpaidOrderByPatient($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('bill_from', 'laboratory')
            ->groupBy('trace_number')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function laboratoryUnpaidOrderByPatientDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function hisadmissionGetPatientListQueue($data)
    {
        return DB::connection('mysql')
            ->table('patients')
            ->join('patients_contacttracing', 'patients_contacttracing.patient_id', '=', 'patients.patient_id')
            ->select('patients.patient_id', 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle')
            ->where('patients.management_id', $data['management_id'])
            ->whereNull('patients_contacttracing.purpose')
            ->whereNull('patients_contacttracing.crt_purpose')
            ->groupBy('patients_contacttracing.patient_id')
            ->orderBy('patients_contacttracing.created_at', 'DESC')
            ->get();
    }

    public static function hisadmissionUpdatePatientContactTracing($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')->table('patients_contacttracing')->where('pct_id', $data['pct_id'])
            ->update([
                'purpose' => $data['purpose_ofvisit'],
                'allergies' => $data['allergies'],
                'last_xray_taken' => $data['last_xray_check'],
                'last_xray_result' => $data['last_xray_result'],
                'history_of_travel_date' => date('Y-m-d', strtotime($data['history_oftravel_date'])),
                'history_of_travel_days' => $data['history_oftravel_days'],
                'history_of_travel_place' => $data['history_oftravel_place'],
                'contact_with_puipum' => $data['contact_withpuipum'],
                'contact_with_positive' => $data['contact_withpositive'],
                'crt_purpose' => $data['crt_purpose'],
                'crt_requestedby' => $data['requested_by'],
                'work_company' => $data['complete_company'],
                'contact_information' => $data['contact_information'],
                'allow_quarantine_ifpositive' => $data['quarantine_ifpositive'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    // method for ishihara
    public static function getIshiharaTestList($data)
    {
        return DB::table('ishihara_test_list')
            ->select('*', 'test as label', 'test_id as value')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function newIshiharaOrder($data)
    {
        date_default_timezone_set('Asia/Manila');

        $trace_number = 'trace-' . rand(0, 9999) . time();
        DB::table('cashier_patientbills_unpaid')->insert([
            'cpb_id' => 'cpb-' . rand() . time(),
            'trace_number' => $trace_number,
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'laboratory_id' => $data['test_id'],
            'bill_name' => $data['test'],
            'bill_amount' => $data['rate'],
            'bill_department' => 'ishihara',
            'bill_from' => 'ishihara',
            'order_id' => $data['test_id'],
            // 'remarks' =>  no remarks in form,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('ishihara_test_orders')->insert([
            'ito_orders' => 'ito-' . rand(0, 9989) . time(),
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'order_id' => $data['test_id'],
            'order_name' => $data['test'],
            'order_rate' => $data['rate'],
            'is_paid' => 0,
            'is_processed' => 0,
            'trace_number' => $trace_number,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getOrderList($data)
    {
        return DB::table('ishihara_test_orders')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function hisAdmissionCreateAppointment($data)
    {
        date_default_timezone_set('Asia/Manila');

        $findate = date('Y-m-d H:i:s', strtotime($data['app_date']));
        $admission_id = _Admission::getAdmissionIdByUserId($data['user_id'])->admission_id;
        $appid = 'app-' . time() . rand(0, 99);
        $orderid = 'order-' . time() . rand(0, 99);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->insert([
            'appointment_id' => $appid,
            'patients_id' => $data['patient_id'],
            'encoders_id' => $admission_id,
            'doctors_id' => $data['doctor'],
            'services' => $data['service'],
            'amount' => $data['fee'],
            'app_date' => $findate,
            'app_reason' => $data['reason'],
            'appearance' => 'walk-in',
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

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->insert([
                'cpb_id' => 'cpb-' . time() . rand(),
                'trace_number' => $appid,
                'doctors_id' => $data['doctor'],
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'bill_name' => $data['service'],
                'bill_amount' => $data['fee'],
                'bill_department' => 'appointment',
                'bill_from' => 'appointment',
                'order_id' => $orderid,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')->insert([
        //     'epb_id' => 'epb-' . time() . rand(),
        //     'trace_number' => $appid,
        //     'doctors_id' => $data['doctor'],
        //     'patient_id' => $data['patient_id'],
        //     'bill_name' => $data['service'],
        //     'bill_amount' => $data['fee'],
        //     'bill_from' => 'appointment',
        //     'order_id' => $orderid,
        //     'updated_at' => date('Y-m-d H:i:s'),
        //     'created_at' => date('Y-m-d H:i:s'),
        // ]);
    }

    public static function hisAdmissionRescheduleAppointment($data)
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
                'message' => 'local appointment reshedule by registration',
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

    public static function getPackagesList($data)
    {
        return DB::table('packages_charge')
            ->select('*', 'package_name as label', 'package_id as value')
            ->where('management_id', $data['management_id'])
            ->groupBy('package_id')
            ->orderBy('package_name', 'asc')
            ->get();
    }

    public static function getUnpaidListByPatientId($data)
    {
        return DB::table('packages_order_list_temp')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function savePackageOrderTemp($data)
    {
        return DB::table('packages_order_list_temp')->insert([
            'order_id' => 'order-' . rand(0, 9999) . time(),
            'trace_number' => 'trace-' . rand(0, 9999) . time(),
            'package_id' => $data['package_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'package_name' => $data['billname'],
            'package_amount' => $data['rate'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function deleteOrder($data)
    {
        return DB::table('packages_order_list_temp')
            ->where('id', $data['id'])
            ->delete();
    }

    public static function saveOrderProcess($data)
    {
        $query = DB::table('packages_order_list_temp')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();

        $unpaid = [];

        foreach ($query as $x) {
            $unpaid = array(
                "cpb_id" => 'cpb-' . rand(0, 9999) . time(),
                "trace_number" => $data['trace_number'],
                "doctors_id" => 'order-from-admission',
                "patient_id" => $x->patient_id,
                "management_id" => $x->management_id,
                "laboratory_id" => $x->package_id,
                "bill_name" => $x->package_name,
                "bill_amount" => $x->package_amount,
                "bill_department" => 'packages',
                "bill_from" => 'packages',
                "order_id" => $x->order_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('cashier_patientbills_unpaid')->insert($unpaid);

        return DB::table('packages_order_list_temp')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function getUnpaidOrderList($data)
    {
        return DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getPaidOrderList($data)
    {
        return DB::table('packages_order_list')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getCompanyAccreditedList($data)
    {
        return DB::table('management_accredited_companies')
            ->select('*', 'company_id as value', 'company as label')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->orderBy('company', 'asc')
            ->get();
    }

    //7-13-2021
    public static function getPsychologyTestList($data)
    {
        return DB::table('psychology_test')
            ->select('*', 'test as label', 'test_id as value')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function newPsychologyOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        $trace_number = 'trace-' . rand(0, 9999) . time();

        DB::table('cashier_patientbills_unpaid')->insert([
            'cpb_id' => 'cpb-' . rand() . time(),
            'trace_number' => $trace_number,
            'doctors_id' => 'admission-order',
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'bill_name' => $data['test'],
            'bill_amount' => $data['rate'],
            'bill_department' => 'psychology',
            'bill_from' => 'psychology',
            'order_id' => $data['test_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('psychology_test_orders')->insert([
            'pto_orders' => 'pto-' . rand(0, 9989) . time(),
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'order_id' => $data['test_id'],
            'order_name' => $data['test'],
            'order_rate' => $data['rate'],
            'is_paid' => 0,
            'is_processed' => 0,
            'trace_number' => $trace_number,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getPsychologyOrderList($data)
    {
        return DB::table('psychology_test_orders')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getUnpaidImagingOrder($data)
    {
        return DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('bill_department', 'imaging')
            ->get();
    }

    public static function getAllCensus($data)
    {
        $management_id = $data['management_id'];

        /*$query = "SELECT *,
            (SELECT firstname from patients where patients.patient_id = patients_contacttracing.patient_id ) as firstname,
            (SELECT lastname from patients where patients.patient_id = patients_contacttracing.patient_id ) as lastname,
            (SELECT middle from patients where patients.patient_id = patients_contacttracing.patient_id ) as middlename
        from patients_contacttracing where `management_id` = '$management_id' ORDER BY created_at DESC ";*/

        $query = "SELECT patients_contacttracing.*, patients.firstname, patients.lastname , patients.middle as middlename
                    FROM patients_contacttracing
                    INNER JOIN patients
                    ON patients_contacttracing.patient_id = patients.id;
                    ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAllCensusFilterByDate($data)
    {
        $management_id = $data['management_id'];
        $from = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $to = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $dateFrom = date('Y-m-d H:i:s', strtotime($from));
        $dateTo = date('Y-m-d H:i:s', strtotime($to));

        return DB::table('patients_contacttracing')
            ->join('patients', 'patients.patient_id', '=', 'patients_contacttracing.patient_id')
            ->select('patients_contacttracing.*', 'patients.firstname as firstname', 'patients.lastname as lastname', 'patients.middle as middlename')
            ->where('patients_contacttracing.management_id', $management_id)
            ->where('patients_contacttracing.created_at', '>=', $dateFrom)
            ->where('patients_contacttracing.created_at', '<=', $dateTo)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getUnsavePsycOrder($data)
    {
        return DB::connection('mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function addPsycOrderTounsave($data)
    {
        return DB::connection('mysql')
            ->table('psychology_unsaveorder')
            ->insert([
                'pu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'doctor_id' => 'admission-order',
                'psychology_id' => _Doctor::getPsychologyIdByMgt($data)->psycho_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'psychology_test_id' => $data['psychology_test_id'],
                'psychology_test' => $data['psychology_test'],
                'psychology_rate' => $data['psychology_rate'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function processPsychologyOrder($data)
    {
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('psychology_id', _Doctor::getPsychologyIdByMgt($data)->psycho_id)
            ->get();

        $trace_number = 'order-' . rand(0, 9999) . time();
        $secrtry_orderunpaid = [];

        foreach ($query as $v) {
            $orderid = $v->psychology_test_id;

            $secrtry_orderunpaid[] = array(
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'doctors_id' => $v->doctor_id,
                'patient_id' => $data['patient_id'],
                'management_id' => _Doctor::getPsychologyIdByMgt($data)->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'psychology_id' => _Doctor::getPsychologyIdByMgt($data)->psycho_id,
                'bill_name' => $v->psychology_test,
                'bill_amount' => $v->psychology_rate,
                'bill_department' => $v->department,
                'bill_from' => 'psychology',
                'order_id' => $orderid,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($v->psychology_test == 'Audiometry') {
                $checkorderIdinAudio = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('psychology_audiometry')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinAudio) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('psychology_audiometry')
                        ->insert([
                            'pa_id' => 'pa-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'psychology_id' => _Doctor::getPsychologyIdByMgt($data)->psycho_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }

            if ($v->psychology_test == 'Neuro Examination') {
                $checkorderIdinNeuro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('psychology_neuroexam')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinNeuro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('psychology_neuroexam')
                        ->insert([
                            'pn_id' => 'pn-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'psychology_id' => _Doctor::getPsychologyIdByMgt($data)->psycho_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }

            if ($v->psychology_test == 'Ishihara') {
                $checkorderIdinIshihara = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('psychology_ishihara')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinIshihara) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('psychology_ishihara')
                        ->insert([
                            'pi_id' => 'pi-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'psychology_id' => _Doctor::getPsychologyIdByMgt($data)->psycho_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $trace_number,
            'patient_id' => $data['patient_id'],
            'category' => 'psychology',
            'department' => 'doctor',
            'message' => "New psychology test.",
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->insert($secrtry_orderunpaid);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('psychology_id', _Doctor::getPsychologyIdByMgt($data)->psycho_id)
            ->delete();

    }

    public static function psychologyUnpaidOrderByPatient($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('bill_from', 'psychology')
            ->groupBy('trace_number')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function psychologyPaidOrderByPatient($data)
    {
        $patient_id = $data['patient_id'];
        $query = "SELECT *,
            (SELECT count(doctors_notification.is_view) FROM doctors_notification WHERE doctors_notification.patient_id = '$patient_id' AND doctors_notification.category = 'psychology' AND doctors_notification.is_view = 0 ) as countPsychology
        from cashier_patientbills_records where `patient_id` = '$patient_id' and bill_from = 'psychology' GROUP BY trace_number ORDER BY created_at DESC ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function admissionAddNewContactTracing($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection('mysql')->table('patients_temp_history')->insert([
            'pth_id' => 'pth-' . rand(0, 99999),
            'patients_id' => $data['patient_id'],
            'temp' => $data['temp'],
            'added_by' => $data['user_id'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($data['transaction_type'] == 'reg-cashier-hmo' || $data['transaction_type'] == 'reg-doctor') {
            DB::table('patient_queue')->insert([
                'pq_id' => 'pq-' . time() . rand(0, 99999),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'trace_number' => $data['trace_number'],
                'type' => 'cashier',
                'priority_sequence' => 4,
                'patient_sent_to' => $data['patient_sent_to'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::table('patient_queue')->insert([
            'pq_id' => 'pq-' . time() . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'trace_number' => $data['trace_number'],
            'type' => $data['transaction_type'] == 'reg-endorse' ? 'endorsement' : ($data['transaction_type'] == 'reg-cashier' ? 'cashier' : 'nursing-station'),
            'priority_sequence' => $data['transaction_type'] == 'reg-endorse' ? 2 : ($data['transaction_type'] == 'reg-cashier' ? 4 : 1),
            'patient_sent_to' => $data['patient_sent_to'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('patients_contacttracing')->insert([
            'pct_id' => 'pct-' . time() . rand(0, 99),
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'temperature' => $data['temp'],
            'sickness' => !empty($data['sickness']) ? $data['sickness'] : null,
            'purpose' => $data['purpose_ofvisit'],
            'allergies' => $data['allergies'],
            'last_xray_taken' => $data['last_xray_check'],
            'last_xray_result' => $data['last_xray_result'],
            'history_of_travel_date' => date('Y-m-d', strtotime($data['history_oftravel_date'])),
            'history_of_travel_days' => $data['history_oftravel_days'],
            'history_of_travel_place' => $data['history_oftravel_place'],
            'contact_with_puipum' => $data['contact_withpuipum'],
            'contact_with_positive' => $data['contact_withpositive'],
            'crt_purpose' => $data['crt_purpose'],
            'crt_requestedby' => $data['requested_by'],
            'work_company' => $data['complete_company'],
            'contact_information' => $data['contact_information'],
            'allow_quarantine_ifpositive' => $data['quarantine_ifpositive'],
            'triage_staff' => $data['user_id'],
            'transaction_type' => $data['transaction_type'] == 'reg-endorse' ? 'Charge' : ($data['transaction_type'] == 'reg-cashier-hmo' ? 'Charge' : 'Cash'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function paidPsychologyOrderDetails($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table($data['table'])
            ->join('patients', 'patients.patient_id', '=', $data['table'] . '.' . 'patient_id')
            ->select($data['table'] . '.' . '*', 'patients.firstname as fname', 'patients.lastname as lname')
            ->where($data['table'] . '.' . 'trace_number', $data['trace_number'])
            ->where($data['table'] . '.' . 'patient_id', $data['patient_id'])
            ->get();
    }

    public static function getQueuingList($data)
    {
        $query = "SELECT *, patient_id as pId, priority_sequence as pSeq,
            (SELECT priority_sequence from patient_queue  where patient_id = pId order by priority_sequence asc limit 1) as prioritySequence,
            (SELECT firstname from patients  where patient_id = pId) as firstname,
            (SELECT lastname from patients  where patient_id = pId) as lastname,
            (SELECT name from doctors where doctors.doctors_id = patient_queue.doctor_id and patient_queue.type = 'doctor' ) as doctorsName
        from patient_queue having pSeq = prioritySequence";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function createNewQueueForAdditional($data)
    {
        return DB::table('patient_queue')
            ->insert([
                'pq_id' => 'pq-' . time() . rand(0, 99999),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'trace_number' => $data['trace_number'],
                'type' => 'cashier',
                'priority_sequence' => 4,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllCompanyListRegistration($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection('mysql')->table('management_accredited_companies')
            ->where("main_mgmt_id", $data['main_mgmt_id'])
            ->get();

    }

    public static function getAllPatientListByCompanyId($data)
    {
        date_default_timezone_set('Asia/Manila');
        $main_mgmt_id = $data['main_mgmt_id'];
        $company_id = $data['company_id'];

        $query = "SELECT *,
            (SELECT name FROM management WHERE management.management_id = patients.management_id  LIMIT 1) as branchAdded
        FROM patients WHERE main_mgmt_id = '$main_mgmt_id' AND company = '$company_id' ORDER BY lastname ASC ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection('mysql')
        // ->table('patients')
        // ->leftJoin('')
        // ->where("main_mgmt_id", $data['main_mgmt_id'])
        // ->where("company", $data['company_id'])
        // ->orderBy("lastname", "ASC")
        // ->get();
    }

    public static function getOthersTestList($data)
    {
        return DB::connection('mysql')->table('other_order_test')
            ->where("management_id", $data['management_id'])
            ->get();
    }

    public static function hisadmissionGetPatientContactTracing($data)
    {
        $patient_id = $data['patient_id'];
        $query = "SELECT *,
            (SELECT company from patients where patients.patient_id = '$patient_id' LIMIT 1) as company
        from patients_contacttracing where `patient_id` = '$patient_id' ORDER BY created_at DESC ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

}
