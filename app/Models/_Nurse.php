<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Nurse extends Model
{
    public static function getNurseIdByUserId($userid)
    {
        return DB::table('nurses')->where('user_id', $userid)->first();
    }

    public static function hisNurseGetHeaderInfo($data)
    {
        return DB::table('nurses')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'nurses.user_id')
            ->select('nurses.nurse_id', 'nurses.user_fullname as name', 'nurses.image', 'nurses.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('nurses.user_id', $data['user_id'])
            ->first();
    }

    public static function hisNurseGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM nurses WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisNurseUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('nurses')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisNurseUpdatePersonalInfo($data)
    {
        return DB::table('nurses')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisNurseUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisNurseUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllNurseOnQueue($data)
    {
        if ($data["department"] == "admitted-patient") {
            return DB::table('hospital_admitted_patient')
                ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient.patient_id')
                ->select('patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle', 'hospital_admitted_patient.patient_id', 'hospital_admitted_patient.*')
                ->where('hospital_admitted_patient.management_id', $data["management_id"])
                ->where('hospital_admitted_patient.nurse_department', $data["department"] == "admitted-patient" ? "room-department" : "ward-department")
                ->get();
        }

        return DB::table('patient_queue')
            ->leftJoin('patients', 'patients.patient_id', '=', 'patient_queue.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle', 'patient_queue.patient_id', 'patient_queue.*')
            ->where('patient_queue.type', 'nursing-station')
            ->where('patient_queue.patient_sent_to', $data['department'])
            ->get();
    }

    public static function nurseGetPatientInformation($data)
    {
        return DB::table('patients')
            ->where('patient_id', $data['patient_id'])
            ->orderBy('lastname', 'ASC')
            ->first();
    }

    public static function nurseUpdatePatientInfo($data)
    {
        date_default_timezone_set('Asia/Manila');
        if (!empty($data['weight'])) {
            DB::connection('mysql')->table('patients_weight_history')
                ->insert([
                    'pwh_id' => 'pwh-' . rand(0, 99) . time(),
                    'patient_id' => $data['patient_id'],
                    'weight' => $data['weight'],
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        if (!empty($data['pulse'])) {
            DB::connection('mysql')->table('patients_pulse_history')
                ->insert([
                    'pph_id' => 'pph-' . rand(0, 99) . time(),
                    'patients_id' => $data['patient_id'],
                    'pulse' => $data['pulse'],
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        if (!empty($data['temp'])) {
            DB::connection('mysql')->table('patients_temp_history')
                ->insert([
                    'pth_id' => 'pth-' . rand(0, 99) . time(),
                    'patients_id' => $data['patient_id'],
                    'temp' => $data['temp'],
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        if (!empty($data['bp_systolic']) && !empty($data['bp_diastolic'])) {
            DB::connection('mysql')->table('patients_lab_history')
                ->insert([
                    'plh_id' => 'plh-' . rand(0, 99) . time(),
                    'patients_id' => $data['patient_id'],
                    'systolic' => $data['bp_systolic'],
                    'diastolic' => $data['bp_diastolic'],
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'height' => $data['height'],
                'weight' => $data['weight'],
                'pulse' => $data['pulse'],
                'lmp' => date('Y-m-d', strtotime($data['lmp'])),
                'temperature' => $data['temp'],
                'blood_systolic' => $data['bp_systolic'],
                'blood_diastolic' => $data['bp_diastolic'],
                'blood_type' => $data['blood_type'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function nurseGetAllDoctors($data)
    {
        return DB::table('doctors')->select('doctors_id as value', 'name as label')->where('management_id', $data['management_id'])->get();
    }

    public static function nurseCreateAppointment($data)
    {
        date_default_timezone_set('Asia/Manila');

        $findate = date('Y-m-d H:i:s', strtotime($data['app_date']));
        $nurse_id = _Nurse::getNurseIdByUserId($data['user_id'])->nurse_id;
        $orderid = 'order-' . time() . rand(0, 99);

        // add to permission
        $getDoctorsUserId = DB::table('doctors')->select('user_id')->where('doctors_id', $data['doctor'])->get();
        $getPatientUserId = DB::table('patients')->select('user_id')->where('patient_id', $data['patient_id'])->get();

        DB::table('patient_queue')->insert([
            'pq_id' => 'pq-' . time() . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'doctor_id' => $data['doctor'],
            'trace_number' => $data['trace_number'],

            // 'type' => 'cashier',
            // 'priority_sequence'=> 3,
            'type' => 'doctor',
            'priority_sequence' => 3,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->insert([
            'appointment_id' => $data['trace_number'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patients_id' => $data['patient_id'],
            'encoders_id' => $nurse_id,
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
                'trace_number' => $data['trace_number'],
                'doctors_id' => $data['doctor'],
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'bill_name' => $data['service'],
                'bill_amount' => $data['fee'],
                'bill_department' => 'appointment',
                'bill_from' => 'appointment',
                'order_id' => $orderid,
                'can_be_discounted' => 1,
                'remarks' => 'Service is available, Now processing...',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function nurseRescheduleAppointment($data)
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
                'message' => 'local appointment reshedule by nurse',
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

    public static function nurseNewQueueToCashier($data)
    {
        return DB::table('patient_queue')
            ->where('patient_id', $data['patient_id'])
            ->where('type', 'nursing-station')
            ->delete();
    }

    public static function getNotes($data)
    {
        $patient_id = $data['patient_id'];
        $query = "SELECT *,

            (SELECT name FROM doctors WHERE doctors.user_id = doctors_notes.doctors_id limit 1) as doctors_name

        FROM doctors_notes WHERE patients_id = '$patient_id' AND status = 1 ORDER BY id DESC ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getTreatmentPlan($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_treatment_plan')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('type', $data['type'])
            ->where('status', 1)
            ->orderBy('id', 'desc')->get();
    }

    public static function hisNurseUploadPatientProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('patients')
            ->where('patient_id', $data['patient_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getCompletedMedCert($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_medical_certificate_ordered')
            ->leftJoin('patients', 'patients.patient_id', '=', 'doctors_medical_certificate_ordered.patient_id')
            ->leftJoin('doctors', 'doctors.doctors_id', '=', 'doctors_medical_certificate_ordered.doctors_id')
            ->select('doctors_medical_certificate_ordered.*', 'doctors_medical_certificate_ordered.created_at as transaction_date', 'patients.lastname', 'patients.firstname', 'patients.birthday', 'patients.gender', 'patients.civil_status', 'patients.street', 'patients.barangay', 'patients.city', 'doctors.name as doctor_name', 'doctors.cil_umn as doctor_lic', 'doctors.specialization as doctor_specialization')
            ->where('doctors_medical_certificate_ordered.management_id', $data['management_id'])
            ->where('doctors_medical_certificate_ordered.order_status', 'completed')
            ->get();
    }

    public static function getDoctorsInfo($data)
    {
        return DB::table('doctors')->where('doctors_id', $data['doctor_id'])->get();
    }

    public static function getPatientInformation($data)
    {
        return DB::table('patients')->where('patient_id', $data['patient_id'])->get();
    }

    public static function updatePatientProfPic($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')
            ->table('patients')
            ->where('patient_id', $data['patient_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getCompletedMedCertById($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_medical_certificate_ordered')
            ->leftJoin('patients', 'patients.patient_id', '=', 'doctors_medical_certificate_ordered.patient_id')
            ->leftJoin('doctors', 'doctors.doctors_id', '=', 'doctors_medical_certificate_ordered.doctors_id')
            ->select('doctors_medical_certificate_ordered.*', 'doctors_medical_certificate_ordered.created_at as transaction_date', 'patients.lastname', 'patients.firstname', 'patients.birthday', 'patients.gender', 'patients.civil_status', 'patients.street', 'patients.barangay', 'patients.city', 'doctors.name as doctor_name', 'doctors.cil_umn as doctor_lic', 'doctors.specialization as doctor_specialization')
            ->where('doctors_medical_certificate_ordered.management_id', $data['management_id'])
            ->where('doctors_medical_certificate_ordered.patient_id', $data['patient_id'])
            ->where('doctors_medical_certificate_ordered.order_status', 'completed')
            ->get();
    }

    public static function getAllHistoryIllnessList($data)
    {
        return DB::table('clinic_all_history_pe')
            ->join('patients', 'patients.patient_id', '=', 'clinic_all_history_pe.patient_id')
            ->select('clinic_all_history_pe.*', 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city')
            ->where('clinic_all_history_pe.patient_id', $data['patient_id'])
            ->where('clinic_all_history_pe.management_id', $data['management_id'])
            ->where('clinic_all_history_pe.main_mgmt_id', $data['main_mgmt_id'])
            ->orderBy("clinic_all_history_pe.created_at", 'DESC')
            ->get();
    }

    public static function createAllHistoryIllnessList($data)
    {
        return DB::table('clinic_all_history_pe')->insert([
            'cahp_id' => 'cahp-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'datetime_admission' => date('Y-m-d H:i:s', strtotime($data['datetime_admission'])),
            'datetime_discharge' => date('Y-m-d H:i:s', strtotime($data['datetime_discharge'])),
            "attending_physician" => $data['attending_physician'],
            "admitting_impression" => $data['admitting_impression'],
            "chief_complaint" => $data['chief_complaint'],
            "pertinent_pe" => $data['pertinent_pe'],
            "past_history" => $data['past_history'],
            "fam_history" => $data['fam_history'],
            "medications" => $data['medications'],
            "procedures" => $data['procedures'],
            "laboratory_results" => $data['laboratory_results'],
            "course_inthe_ward" => $data['course_inthe_ward'],
            "recommendation" => $data['recommendation'],
            "condition_on_discharge" => $data['condition_on_discharge'],
            "final_diagnosis" => $data['final_diagnosis'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateAllHistoryIllnessList($data)
    {
        return DB::table('clinic_all_history_pe')
            ->where('patient_id', $data['patient_id'])
            ->where('cahp_id', $data['cahp_id'])
            ->update([
                'datetime_admission' => date('Y-m-d H:i:s', strtotime($data['datetime_admission'])),
                'datetime_discharge' => date('Y-m-d H:i:s', strtotime($data['datetime_discharge'])),
                "attending_physician" => $data['attending_physician'],
                "admitting_impression" => $data['admitting_impression'],
                "chief_complaint" => $data['chief_complaint'],
                "pertinent_pe" => $data['pertinent_pe'],
                "past_history" => $data['past_history'],
                "fam_history" => $data['fam_history'],
                "medications" => $data['medications'],
                "procedures" => $data['procedures'],
                "laboratory_results" => $data['laboratory_results'],
                "course_inthe_ward" => $data['course_inthe_ward'],
                "recommendation" => $data['recommendation'],
                "condition_on_discharge" => $data['condition_on_discharge'],
                "final_diagnosis" => $data['final_diagnosis'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllAdmittedPatient($data)
    {
        return DB::table('hospital_admitted_patient')
            ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle', 'hospital_admitted_patient.patient_id', 'hospital_admitted_patient.*')
            ->where('hospital_admitted_patient.management_id', $data['management_id'])
            ->where('hospital_admitted_patient.nurse_department', $data['department'])
            ->get();
    }

    public static function sentPatientToDischarge($data)
    {
        return DB::table('hospital_admitted_patient')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->update([
                // "discharge_on" => date('Y-m-d H:i:s'), // update if discharged
                // "discharge_remarks" => $data["remarks"], // update if discharged
                'nurse_department' => "discharge-department",
                "discharge_by" => $data["discharge_by"],
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
    }

    public static function sentPatientToBillout($data)
    {
        DB::table('hospital_admitted_patient_forbillout')
            ->insert([
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'remarks' => $data['remarks'],
                'billout_status' => 'for-billing',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::table('hospital_admitted_patient')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->update([
                'nurse_department' => "billing-department",
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getPatientListForOperation($data)
    {
        return DB::table('hospital_admitted_patient_foroperation')
            ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient_foroperation.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle', 'hospital_admitted_patient_foroperation.patient_id', 'hospital_admitted_patient_foroperation.*')
            ->where('hospital_admitted_patient_foroperation.management_id', $data['management_id'])
            ->where('hospital_admitted_patient_foroperation.nurse_assign', $data['department'])
            ->get();
    }

    public static function setOrPatientToPacuNurse($data)
    {
        return DB::table('hospital_admitted_patient_foroperation')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('management_id', $data['management_id'])
            ->update([
                'operation_status' => 'for-pacu',
                'nurse_assign' => 'pacu-nurse',
                'remarks' => $data['remarks'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function setPatientForMonitoring($data)
    {
        return DB::table('hospital_admitted_patient_foroperation')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('management_id', $data['management_id'])
            ->update([
                'operation_status' => 'for-monotoring',
                'nurse_assign' => $data['monitor_by'],
                'remarks' => $data['remarks'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAdmittedPatientDetails($data)
    {
        return DB::table('hospital_admitted_patient')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }
}
