<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class _Doctor extends Model
{
    public static function movePatientToList($data)
    {
        date_default_timezone_set('Asia/Manila');

        $ptnt = [];
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients')->select('user_id')->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)->get();

        foreach ($query as $v) {
            $check = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_patients')
                ->select('dp_id')
                ->where('doctors_userid', $data['user_id'])
                ->where('patient_userid', $v->user_id)
                ->get();

            if (count($check) < 1) { // move if no result
                $ptnt[] = array(
                    'dp_id' => 'db-' . time() . rand(0, 99958),
                    'doctors_userid' => $data['user_id'],
                    'patient_userid' => $v->user_id,
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'added_from' => 'local',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_patients')->insert($ptnt);
        return _Doctor::getDoctorsId($data['user_id'])->doctors_id;
    }

    public static function getDoctorsId($userid)
    {
        return DB::table('doctors')->select('doctors_id', 'image', 'name')->where('user_id', $userid)->first();
    }

    public static function createRoom($data)
    {

        date_default_timezone_set('Asia/Manila');

        $check = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_is_online')
            ->where('checkup_status', 'incomplete')
            ->where('doctors_id', $data['user_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('appointment_id', $data['appointment_id'])
            ->get();

        if (count($check) > 0) {
            return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('virtual_is_online')
                ->where('checkup_status', 'incomplete')
                ->where('doctors_id', $data['user_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'doctors_webrtc_id' => $data['_your_web_rtc_id'],
                    'patient_webrtc_id' => null,
                    'room_number' => $data['room_number'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('virtual_is_online')
                ->insert([
                    'online_id' => 'online-' . rand(0, 8888) . time(),
                    'appointment_id' => $data['appointment_id'],
                    'patient_id' => $data['patient_id'],
                    'doctors_id' => $data['user_id'],
                    'doctors_webrtc_id' => $data['_your_web_rtc_id'],
                    'patient_webrtc_id' => null,
                    'room_number' => $data['room_number'],
                    'checkup_status' => 'incomplete',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function removeRoom($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_is_online')
            ->where('checkup_status', 'incomplete')
            ->where('doctors_id', $data['user_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('room_number', $data['room_number'])
            ->update([
                'doctors_webrtc_id' => null,
                'patient_webrtc_id' => null,
                'room_number' => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function appointmentDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_appointment')
            ->join('patients', 'patients.user_id', '=', 'virtual_appointment.patient_id')
            ->select('virtual_appointment.*', 'virtual_appointment.appointment_id', 'virtual_appointment.doctors_id', 'virtual_appointment.patient_id', 'virtual_appointment.reference_no', 'patients.patient_id as patientold_id', 'patients.firstname', 'patients.lastname')
            ->where('virtual_appointment.appointment_id', $data['appointment_id'])
            ->get();
    }

    public static function getPatientWebRtcId($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_is_online')
            ->where('patient_id', $data['patient_id'])
            ->where('checkup_status', 'incomplete')
            ->where('doctors_id', $data['user_id'])
            ->where('room_number', $data['room_number'])
            ->whereNotNull('patient_webrtc_id')
            ->get();
    }

    public static function getPersonalInfo($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors')
            ->where('user_id', $data['user_id'])->get();
    }

    public static function getPatients($data)
    {
        // return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('patients')
        //     ->select('firstname', 'lastname', 'patient_id', 'image')
        //     ->where('doctors_id', (new _Doctor)::getDoctorsId($data['user_id'])->doctors_id)
        //     ->orderBy('lastname', 'asc')->get();
        // return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('doctors_patients')
        //     ->join('patients', 'patients.user_id', '=' , 'doctors_patients.patient_userid')
        //     ->select('doctors_patients.doctors_userid','patients.firstname', 'patients.lastname', 'patients.patient_id', 'patients.image')
        //     ->where('doctors_patients.doctors_userid' ,$data['user_id'])
        //     ->orderBy('patients.lastname', 'asc')
        //     ->get();

        $query = "SELECT doctors_patients.doctors_userid, patients.firstname, patients.lastname, patients.patient_id, patients.patient_id as PatientID, patients.image,
            (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = PatientID AND doctors_notification.category = 'laboratory' and doctors_notification.is_view = 0) as count_laboratory,
            (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = PatientID  AND doctors_notification.category = 'imaging' and doctors_notification.is_view = 0) as count_imaging
        FROM doctors_patients INNER JOIN patients ON patients.user_id = doctors_patients.patient_userid WHERE doctors_patients.doctors_userid = '" . $data['user_id'] . "' GROUP BY patients.patient_id ORDER BY patients.lastname ASC ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPatientInformation($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])->get();
    }

    public static function getWeight($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_weight_history')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getBloodPressure($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_lab_history')->where('patients_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getTemperature($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_temp_history')->where('patients_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getGlucose($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_glucose_history')->where('patients_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getRespiratory($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_respiratory_history')->where('patients_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getPulse($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_pulse_history')->where('patients_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getCholesterol($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_cholesterol_history')->where('patients_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getUricacid($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_uric_acid_history')->where('patients_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getChloride($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_chloride')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getCreatinine($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_creatinine')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getHDL($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_hdl')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getLDL($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_ldl')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getLithium($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_lithium')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getMagnesium($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_magnessium')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getPotasium($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_potassium')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getProtein($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_protein')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function getSodium($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_sodium')->where('patient_id', $data['patient_id'])->orderBy('created_at', 'desc')->get();
    }

    public static function newBp($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_lab_history')->insert([
            'plh_id' => 'plh-' . rand(0, 99999),
            'patients_id' => $data['patient_id'],
            'systolic' => $data['systolic'],
            'diastolic' => $data['diastolic'],
            'added_by' => $data['user_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])->update([
            'blood_systolic' => $data['systolic'],
            'blood_diastolic' => $data['diastolic'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function newWeight($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_weight_history')->insert([
            'pwh_id' => 'pwh-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'weight' => $data['weight'],
            'added_by' => $data['user_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])->update([
            'weight' => $data['weight'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function newHepatitis($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'hepatitis' => $data['hepatitis'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newTuberculosis($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'tuberculosis' => $data['tuberculosis'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newDengue($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'dengue' => $data['dengue'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newLMP($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'lmp' => date('Y-m-d', strtotime($data['lmp'])),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newTemp($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_temp_history')
            ->insert([
                'pth_id' => 'pth-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'temp' => $data['temperature'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'temperature' => $data['temperature'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newGlucose($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_glucose_history')
            ->insert([
                'pgh_id' => 'pgh-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'glucose' => $data['glucose'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'glucose' => $data['glucose'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newUricacid($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->
            table('patients_uric_acid_history')
            ->insert([
                'uric_acid_id' => 'uai-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'uric_acid' => $data['uricacid'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->
            table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'uric_acid' => $data['uricacid'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newCholesterol($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_cholesterol_history')
            ->insert([
                'cholesterol_id' => 'choles-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'cholesterol' => $data['cholesterol'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'cholesterol' => $data['cholesterol'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newPulse($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_pulse_history')
            ->insert([
                'pph_id' => 'pph-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'pulse' => $data['pulse'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'pulse' => $data['pulse'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newRespiratory($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_respiratory_history')
            ->insert([
                'prh_id' => 'prh-' . rand(0, 99999),
                'patients_id' => $data['patient_id'],
                'respiratory' => $data['respiratory'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'rispiratory' => $data['respiratory'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function patientHistory($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history')->where('patient_id', $data['patient_id'])->get();
    }

    public static function requestPermissionToPatient($data)
    {

        date_default_timezone_set('Asia/Manila');

        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_permission')
            ->where('patients_id', $data['patient_id'])
            ->where('doctors_id', $data['user_id'])
            ->get();

        if (count($query) > 0) {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('patients_permission')
                ->where('patients_id', $data['patient_id'])
                ->where('doctors_id', $data['user_id'])
                ->update([
                    'permission_on' => 'PROFILE',
                    'permission_status' => 'forapproval',
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('patients_permission')
                ->insert([
                    'permission_id' => 'permission-' . rand(0, 9999),
                    'patients_id' => $data['patient_id'],
                    'doctors_id' => $data['user_id'],
                    'permission_on' => 'PROFILE',
                    'status' => 1,
                    'permission_status' => 'forapproval',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_checkup_msg')
            ->insert([
                'vcm_id' => rand(0, 999) . time(),
                'senders_id' => $data['user_id'],
                'receivers_id' => $data['patient_id'],
                'message' => 'Request permission to view your profile.',
                'updated_at' => date('Y-m-d H:i:s'),
                'type' => 'permission',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getProfilePermission($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_permission')
            ->where('patients_id', $data['patient_id'])
            ->where('doctors_id', $data['doctors_id'])
            ->where('permission_status', 'approved')
            ->where('status', 1)
            ->get();
    }

    public static function savePatient($data)
    {
        date_default_timezone_set('Asia/Manila');
        $patientid = 'p-' . rand(0, 999) . time();
        $userid = 'u-' . rand(0, 8888) . time();

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_permission')->insert([
            'permission_id' => 'permission-' . time(),
            'doctors_id' => $data['user_id'],
            'patients_id' => $userid,
            'permission_on' => 'PROFILE',
            'permission_status' => 'approved',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_patients')->insert([
            'dp_id' => 'dp-' . time(),
            'doctors_userid' => $data['user_id'],
            'patient_userid' => $userid,
            'added_by' => $data['user_id'],
            'added_from' => 'local',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients')->insert([
            'patient_id' => $patientid,
            'doctors_id' => (new _Doctor)::getDoctorsId($data['user_id'])->doctors_id,
            'user_id' => $userid,
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'middle' => $data['middle'],
            'birthday' => date('Y-m-d', strtotime($data['birthday'])),
            'birthplace' => $data['birthplace'],
            'civil_status' => $data['civil'],
            'religion' => $data['religion'],
            'occupation' => $data['occupation'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'telephone' => $data['telephone'],
            'street' => $data['street'],
            'barangay' => $data['barangay'],
            'city' => $data['city'],
            'join_category' => 'clinic-app',
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getLocalAppList($data)
    {

        $doctorsid = (new _Doctor)::getDoctorsId($data['user_id'])->doctors_id;
        $query = "SELECT *,
        (SELECT concat(lastname,', ', firstname) from patients where patients.patient_id = appointment_list.patients_id) as patients_name
        from appointment_list where `doctors_id` = '$doctorsid'";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getIncompleteAppList($data)
    {

        $doctorsid = (new _Doctor)::getDoctorsId($data['user_id'])->doctors_id;
        $query = "SELECT *,
        (SELECT concat(lastname,', ', firstname) from patients where patients.patient_id = appointment_list.patients_id) as patients_name,
        (SELECT image from patients where patients.patient_id = appointment_list.patients_id) as patients_image
        from appointment_list where `doctors_id` = '$doctorsid' and is_complete = 0";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getRequestCredit($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('user_request_credit')->where('user_id', $data['user_id'])->get();
    }

    public static function getcommentsForApproval($data)
    {
        $query = "SELECT *,
        (SELECT concat(lastname,', ', firstname) from patients where patients.user_id = doctors_comments.patient_id) as patient_name
        from doctors_comments where `doctors_id` = '" . $data['user_id'] . "' and comment_status = '" . $data['comment_status'] . "' and status = 1";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function requestCreditSave($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('user_request_credit')->insert([
            'urc_id' => rand(0, 999) . time(),
            'user_id' => $data['user_id'],
            'request_token' => $data['credit'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function approveCommentSave($data)
    {

        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->where('order_id', $data['app_id'])
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_comments')->where('id', $data['comment_id'])->update([
            'comment_status' => 'approved',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function forapproveCommentDelete($data)
    {

        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->where('order_id', $data['app_id'])
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_comments')->where('id', $data['comment_id'])->update([
            'status' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getAppointmentLocalDetails($data)
    {

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->where('order_id', $data['app_id'])
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        $query = "SELECT *,
            (SELECT concat(IFNULL(lastname, ''),', ', IFNULL(firstname, '')) from patients where patients.patient_id = appointment_list.patients_id) as patients_name,
            (SELECT email from patients where patients.patient_id = appointment_list.patients_id) as email,
            (SELECT mobile from patients where patients.patient_id = appointment_list.patients_id) as mobile,
            (SELECT telephone from patients where patients.patient_id = appointment_list.patients_id) as telephone
        from appointment_list where `appointment_id` = '" . $data['app_id'] . "'";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function setLocalAppComplete($data)
    {
        date_default_timezone_set('Asia/Manila');
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->where('appointment_id', $data['appid'])->get();

        if (count($query) > 0) {
            $getpateint_userid = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients')->select('user_id')->where('patient_id', $query[0]->patients_id)->first();
            $getdoctors_userid = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors')->select('user_id')->where('doctors_id', $query[0]->doctors_id)->first();

            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('doctors_patients')->insert([
                'dp_id' => 'dp-' . time(),
                'doctors_userid' => $getdoctors_userid->user_id,
                'patient_userid' => $getpateint_userid->user_id,
                'added_by' => $data['user_id'],
                'added_from' => 'local',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::connection('mysql')->table('patient_queue')
            ->where('patient_id', $query[0]->patients_id)
            ->where('type', 'doctor')
            ->delete();

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->where('appointment_id', $data['appid'])->update([
            'is_complete' => 1,
            'app_date_end' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function setLocalAppRechedule($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->where('appointment_id', $data['appid'])->update([
            'is_complete' => 0,
            'is_reschedule' => 1,
            'is_reschedule_reason' => $data['resched_reason'],
            'is_reschedule_date' => date('Y-m-d H:i:s', strtotime($data['resched_date'] . ' ' . $data['resched_time'])),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getNotificationsList($data)
    {
        $query = "SELECT *,
        (SELECT concat(lastname,', ', firstname) from patients where patients.patient_id = virtual_appointment_notification.patient_id) as patients_name
        from virtual_appointment_notification where `doctors_id` = '" . $data['user_id'] . "' order by id desc";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getNotificationsMsg($data)
    {

        date_default_timezone_set('Asia/Manila');
        if (!$data['isread']) {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('virtual_appointment_notification')->where('notif_id', $data['notif_id'])->update([
                'is_read' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_appointment_notification')->select('notification_msg', 'created_at')->where('notif_id', $data['notif_id'])->get();
    }

    public static function reschedVirtualAppointment($data)
    {
        date_default_timezone_set('Asia/Manila');
        $qry = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_appointment')
            ->where('appointment_id', $data['appid'])
            ->first();

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->insert([
                'notif_id' => 'nid-' . rand(0, 99999),
                'order_id' => $qry->appointment_id,
                'patient_id' => $qry->patient_id,
                'doctor_id' => $qry->doctor_id,
                'category' => 'appointment',
                'department' => 'virtual-appointment',
                'is_view' => 0,
                'notification_from' => 'virtual',
                'message' => 'new virtual appointment reschedule',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_appointment')->where('appointment_id', $data['appid'])
            ->update([
                'is_reschedule' => 1,
                'is_reschedule_date' => date('Y-m-d H:i:s', strtotime($data['resched_date'] . ' ' . $data['resched_time'])),
                'is_reschedule_reason' => $data['resched_reason'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getPatientContactInfo($data)
    {

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')
            ->select('email', 'mobile', 'telephone')
            ->where('user_id', $data['patient_id'])
            ->get();
    }

    public static function newAllergies($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients')->where('patient_id', $data['patient_id'])
            ->update([
                'allergies' => $data['allergies'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newDiagnosis($data)
    {
        date_default_timezone_set('Asia/Manila');

        $diagnosis = implode(', ', $data['diagnosis']);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_diagnosis')
            ->insert([
                'pd_id' => 'pd-' . rand(0, 888) . time(),
                'patient_id' => $data['patient_id'],
                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'diagnosis' => $diagnosis,
                'remarks' => $data['diagnosis_remarks'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    //jhomar
    // public static function getDiagnosis($data){
    //     return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')
    //         ->table('patients_diagnosis')->where('patient_id', $data['patient_id'])->orderBy('id', 'desc')->get();
    // }

    public static function getDiagnosis($data)
    {

        if ($data['user'] == 'patient') {
            $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients')->select('patient_id')->where('user_id', $data['user_id'])->first();
            $final = $query->patient_id;
        }
        if ($data['user'] == 'doctor') {
            $final = $data['patient_id'];
        }
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_diagnosis')->where('patient_id', $data['patient_id'])->orderBy('id', 'desc')->get();
    }

    public static function getPatientSharedImages($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patient_sharedimages')
            ->where('patient_id', $data['patient_id'])
            ->where('type', $data['type'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function newPatientPrelab($data)
    {
        if (!empty($data['temperature'])) {
            _Doctor::newTemp($data);
        }

        if (!empty($data['systolic']) && !empty($data['diastolic'])) {
            _Doctor::newBp($data);
        }

        if (!empty($data['pulse'])) {
            _Doctor::newPulse($data);
        }

        if (!empty($data['weight'])) {
            _Doctor::newWeight($data);
        }

        if (!empty($data['respiratory'])) {
            _Doctor::newRespiratory($data);
        }

        if (!empty($data['glucose'])) {
            _Doctor::newGlucose($data);
        }

        if (!empty($data['uricacid'])) {
            _Doctor::newUricacid($data);
        }

        if (!empty($data['cholesterol'])) {
            _Doctor::newCholesterol($data);
        }

        if (!empty($data['hepatitis'])) {
            _Doctor::newHepatitis($data);
        }

        if (!empty($data['tuberculosis'])) {
            _Doctor::newTuberculosis($data);
        }

        if (!empty($data['dengue'])) {
            _Doctor::newDengue($data);
        }

        if (!empty($data['lmp'])) {
            _Doctor::newLMP($data);
        }

        return true;

    }

    public static function getPatientSharedImagesDates($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patient_sharedimages')
            ->select('created_at as shared_date')
            ->where('patient_id', $data['patient_id'])
            ->where('type', $data['type'])
            ->groupBy(DB::raw('Date(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getPatientSharedImagesDatesDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patient_sharedimages')
            ->where('patient_id', $data['patient_id'])
            ->whereDate('created_at', date('Y-m-d', strtotime($data['selectedDate'])))
            ->where('type', $data['type'])
            ->get();
    }

    public static function getPatientFamilyHistory($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_family_histories')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getRxDoctorsRx($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors')
            ->leftJoin('doctors_rxheader', 'doctors_rxheader.doctors_id', '=', 'doctors.doctors_id')
            ->where('doctors.doctors_id', $data['doctors_id'])
            ->get();
    }

    public static function newDietSave($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_diets')
            ->insert([
                'pd_id' => rand(0, 999) . time(),
                'patient_id' => $data['patient_id'],
                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'meals' => $data['meals'],
                'description' => $data['description'],
                'is_suggested' => 1,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getPersonalDiet($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_diets')
            ->where('patient_id', $data['patient_id'])
            ->whereNull('doctor_id')
            ->where('is_suggested', 0)
            ->get();
    }

    public static function getPersonalDietByDate($data)
    {

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_diets')
            ->where('patient_id', $data['patient_id'])
            ->whereNull('doctor_id')
            ->where('is_suggested', 0)
            ->groupBy(DB::raw('Date(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getSuggestedDietByDate($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_diets')
            ->where('patient_id', $data['patient_id'])
            ->where('doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('is_suggested', 1)
            ->groupBy(DB::raw('Date(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getSuggestedDiet($data)
    {

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_diets')
            ->where('patient_id', $data['patient_id'])
            ->where('doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('is_suggested', 1)
            ->get();
    }

    public static function getAllPatients($data)
    {
        $query = "SELECT *,
        (SELECT concat(lastname,', ',firstname) from patients where patients.user_id = doctors_patients.patient_userid) as patients_name,
        (SELECT patient_id from patients where patients.user_id = doctors_patients.patient_userid) as patient_id
        from doctors_patients where `doctors_userid` = '" . $data['user_id'] . "' order by patients_name asc";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPermissionByPatient($data)
    {

        $patientUserId = _Doctor::getPatientInformation($data)[0]->user_id;

        $query = "SELECT *,
            (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = '" . $data['patient_id'] . "' AND doctors_notification.category = 'laboratory' AND doctors_notification.is_view = 0 ) as countLaboratory,
            (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = '" . $data['patient_id'] . "' AND doctors_notification.category = 'imaging' AND doctors_notification.is_view = 0 ) as countImaging
        from patients_permission where `doctors_id` = '" . $data['user_id'] . "' AND permission_status = 'approved' AND status = 1 AND patients_id = '$patientUserId' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')
        //     ->table('patients_permission')
        //     ->where('doctors_id', $data['user_id'])
        //     ->where('permission_status', 'approved')
        //     ->where('status', 1)
        //     ->where('patients_id', $patientUserId)
        //     ->get();
    }

    public static function getBodyPain($data)
    {

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patients_pain_history')
            ->join('patients', 'patients.patient_id', '=', 'patients_pain_history.patient_id')
            ->select('patients_pain_history.*', 'patients.gender')
            ->where('patients_pain_history.status', 1)
            ->where('patients_pain_history.patient_id', $data['patient_id'])
            ->get();
    }

    public static function getLaboratoryIdByMgt($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_list')->where('management_id', $data['management_id'])->first();
    }

    public static function getPsychologyIdByMgt($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_account')->where('management_id', $data['management_id'])->first();
    }

    public static function getLaboratoryTest($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_test')
            ->select(DB::raw("CONCAT(laboratory_test, '-',laboratory_rate) as label"), 'lt_id as value')
            ->where('laboratory_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
            ->get();
    }

    public static function changeAppointmentTypeLocal($data)
    {

        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')
            ->where('trace_number', $data['appointment_id'])
            ->update([
                'bill_name' => $data['service'],
                'bill_amount' => $data['amount'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('appointment_list')->where('appointment_id', $data['appointment_id'])->update([
            'services' => $data['service'],
            'amount' => $data['amount'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getRefDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_appointment')
            ->join('patients', 'patients.user_id', '=', 'virtual_appointment.patient_id')
            ->select('virtual_appointment.*', 'patients.patient_id as patientId')
            ->where('virtual_appointment.doctors_id', $data['user_id'])
            ->where('virtual_appointment.reference_no', $data['ref_number'])
            ->first();
    }

    public static function createVcallRoom($data)
    {
        date_default_timezone_set('Asia/Manila');

        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_call')
            ->where('doctors_userid', $data['doctors_userid'])
            ->where('patient_userid', $data['patient_userid'])
            ->where('ref_number', $data['ref_number'])
            ->get();

        if (count($query)) {
            return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('virtual_call')
                ->where('doctors_userid', $data['doctors_userid'])
                ->where('ref_number', $data['ref_number'])
                ->update([
                    'room_number' => $data['room_number'],
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_call')
            ->insert([
                'vc_id' => 'vc-' . rand(0, 8889) . time(),
                'ref_number' => $data['ref_number'],
                'patient_userid' => $data['patient_userid'],
                'doctors_userid' => $data['doctors_userid'],
                'room_number' => $data['room_number'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function onlineAppSetAsDone($data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_appointment')
            ->where('appointment_id', $data['app_id'])
            ->where('reference_no', $data['app_ref_number'])
            ->where('doctors_id', $data['user_id'])
            ->update([
                'appointment_status' => 'successful',
                'consumed_time' => $data['app_consumetime'],
                'appointment_done_on' => date('Y-m-d H:i:s', strtotime($data['app_doneon'])),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_appointment_notification')
            ->insert([
                'notif_id' => 'notif-' . rand(0, 9999) . time(),
                'appointment_id' => $data['app_id'],
                'doctors_id' => $data['user_id'],
                'patient_id' => $data['patient_id'],
                'notification_msg' => $data['message'],
                'is_read' => 0,
                'notification_type' => 'sent',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function deleteVcallRoom($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_call')
            ->where('ref_number', $data['app_ref_number'])
            ->where('doctors_userid', $data['user_id'])
            ->delete();
    }

    public static function deleteAllVcallRoom($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('virtual_call')
            ->where('doctors_userid', $data['user_id'])
            ->delete();
    }

    // new laboratory with form base on docdonassco result form
    public static function getLabOrderDeptDetails($data)
    {
        // return  DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')
        //     ->table('laboratory_test')
        //     ->where('laboratory_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
        //     ->orderBy('laboratory_test', 'asc')
        //     ->get();

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_items_laborder')
            ->where('laboratory_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
            ->groupBy('order_id')
            ->get();

    }

    public static function getUnsaveLabOrder($data)
    {
        $patientid = $data['patient_id'];
        $doctorsid = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        $x = $data['department'] == 'medical-exam' ? "department = 'medical-exam'" : "department != 'medical-exam'";

        $query = "SELECT * from laboratory_unsaveorder where patient_id = '$patientid' and doctor_id = '$doctorsid' and $x";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        //     ->table('laboratory_unsaveorder')
        //     ->where('patient_id', $data['patient_id'])
        //     ->where('doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
        //     ->get();
    }

    public static function addLabOrderTounsave($data)
    {
        $query = DB::table('laboratory_items_laborder')->select('can_be_discounted')->where('order_id', $data['laboratory_test_id'])->first();

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->insert([
                'lu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'laborotary_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'laboratory_test_id' => $data['laboratory_test_id'],
                'laboratory_test' => $data['laboratory_test'],
                'laboratory_rate' => $data['laboratory_rate'],
                'can_be_discounted' => $query->can_be_discounted,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function removeLabOrderFromUnsave($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('id', $data['removeid'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    // new laboratory
    public static function processLabOrder($data)
    {
        $trace_number = $data['trace_number'];

        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('laborotary_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
            ->get();

        $queryCountQueueDB = DB::connection('mysql')
            ->table('patient_queue')
            ->where('patient_id', $data['patient_id'])
            ->where('type', 'cashier')
            ->get();

        $secrtry_orderunpaid = [];

        foreach ($query as $v) {
            $orderid = $v->laboratory_test_id;

            $secrtry_orderunpaid[] = array(
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'doctors_id' => $v->doctor_id,
                'patient_id' => $data['patient_id'],
                'management_id' => $v->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $v->laboratory_test,
                'bill_amount' => $v->laboratory_rate,
                'bill_department' => $v->department,
                'bill_from' => 'laboratory',
                'order_id' => $orderid,
                'can_be_discounted' => $v->can_be_discounted,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($v->department == 'hemathology') {
                if ($v->laboratory_test == 'cbc' || $v->laboratory_test == 'cbc platelet') {
                    $checkCBC = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_cbc')
                        ->where('order_id', $orderid)
                        ->where('order_status', 'new-order')
                        ->where('patient_id', $data['patient_id'])
                        ->where('trace_number', $trace_number)
                        ->get();

                    if (count($checkCBC) < 1) {
                        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                            ->table('laboratory_cbc')
                            ->insert([
                                'lc_id' => 'lc-' . rand(0, 99999) . time(),
                                'order_id' => $orderid,
                                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                } else {
                    $checkorderIdinHema = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_hematology')
                        ->where('order_id', $orderid)
                        ->where('order_status', 'new-order')
                        ->where('patient_id', $data['patient_id'])
                        ->where('trace_number', $trace_number)
                        ->get();

                    if (count($checkorderIdinHema) < 1) {
                        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                            ->table('laboratory_hematology')
                            ->insert([
                                'lh_id' => 'lh-' . rand(0, 9999) . time(),
                                'order_id' => $orderid,
                                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            if ($v->department == 'serology') {
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_sorology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_sorology')
                        ->insert([
                            'ls_id' => 'ls-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_microscopy')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_microscopy')
                        ->insert([
                            'lm_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_fecal_analysis')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_fecal_analysis')
                        ->insert([
                            'lfa_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_stooltest')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_stooltest')
                        ->insert([
                            'lf_id' => 'lf-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinChem = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_chemistry')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinChem) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_chemistry')
                        ->insert([
                            'lc_id' => 'lc-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            if ($v->department == 'urinalysis') {
                $checkorderIdinChem = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_urinalysis')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinChem) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_urinalysis')
                        ->insert([
                            'lu_id' => 'lu-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            if ($v->department == 'ecg') {
                $checkorderIdinChem = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_ecg')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinChem) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_ecg')
                        ->insert([
                            'le_id' => 'le-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinChem = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_medical_exam')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinChem) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_medical_exam')
                        ->insert([
                            'lme_id' => 'lme-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinPapsmear = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_papsmear')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinPapsmear) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_papsmear')
                        ->insert([
                            'ps_id' => 'ps-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            if ($v->department == 'oral-glucose') {
                $checkorderIdinPapsmear = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_oral_glucose')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinPapsmear) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_oral_glucose')
                        ->insert([
                            'log_id' => 'log-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinThyroid) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_thyroid_profile')
                        ->insert([
                            'ltp_id' => 'ltp-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinImmunology) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_immunology')
                        ->insert([
                            'li_id' => 'li-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinPapsmear = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_miscellaneous')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinPapsmear) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_miscellaneous')
                        ->insert([
                            'lm_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
                $checkorderIdinHepa = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hepatitis_profile')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_hepatitis_profile')
                        ->insert([
                            'lhp_id' => 'lhp-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            if ($v->department == 'covid-19') {
                $checkorderIdinHepa = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_covid19_test')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_covid19_test')
                        ->insert([
                            'lct_id' => 'lct-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            if ($v->department == 'Tumor Maker') {
                $checkorderIdinHepa = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_tumor_maker')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_tumor_maker')
                        ->insert([
                            'ltm_id' => 'ltm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            if ($v->department == 'Drug Test') {
                $checkorderIdinHepa = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_drug_test')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_drug_test')
                        ->insert([
                            'ldt_id' => 'ldt-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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

        if (count($queryCountQueueDB) < 1) {
            DB::connection('mysql')->table('patient_queue')->insert([
                'pq_id' => 'pq-' . rand(0, 99) . time(),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'type' => 'cashier',
                'trace_number' => $trace_number,
                'priority_sequence' => 4,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $trace_number,
            'patient_id' => $data['patient_id'],
            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
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
            ->where('doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('laborotary_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
            ->delete();
    }

    public static function laboratoryUnpaidOrderByPatient($data)
    {

        $patientid = $data['patient_id'];
        $type = $data['bill_from'];

        $x = $data['department'] == 'medical-exam' ? "bill_department = 'medical-exam'" : "bill_department != 'medical-exam'";

        $query = "SELECT * from cashier_patientbills_unpaid where patient_id = '$patientid' and $x and bill_from = '$type' group by trace_number order by created_at asc";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        //     ->table('cashier_patientbills_unpaid')
        //     ->where('patient_id', $data['patient_id'])
        //     ->where('bill_from', 'laboratory')
        //     ->groupBy('trace_number')
        //     ->orderBy('created_at', 'desc')
        //     ->get();
    }

    public static function laboratoryUnpaidOrderByPatientDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('bill_from', $data['bill_from'])
            ->orderBy('bill_name', 'asc')
            ->get();
    }

    public static function laboratoryPaidOrderByPatient($data)
    {
        // $patientUserId = _Doctor::getPatientInformation($data)[0]->user_id;
        // $doc_id = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        // $query = "SELECT *,
        //     (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = '" . $data['patient_id'] . "' AND doctors_notification.category = 'laboratory' AND doctors_notification.is_view = 0 ) as countLaboratory
        // from cashier_patientbills_records where `patient_id` = '" . $data['patient_id'] . "' and bill_from = 'laboratory' AND doctors_id = '$doc_id' GROUP BY trace_number ORDER BY created_at DESC ";

        $patientid = $data['patient_id'];
        $x = $data['department'] == 'medical-exam' ? "bill_department = 'medical-exam'" : "bill_department != 'medical-exam'";

        $query = "SELECT *,
            (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = '$patientid' AND doctors_notification.category = 'laboratory' AND doctors_notification.is_view = 0 ) as countLaboratory
        from cashier_patientbills_records where `patient_id` = '$patientid' and $x and bill_from = 'laboratory' GROUP BY trace_number ORDER BY created_at DESC ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // $query = "SELECT *,
        //     (SELECT count(doctors_notification.is_view) from doctors_notification where doctors_notification.patient_id = '" . $data['patient_id'] . "' AND doctors_notification.category = 'laboratory' AND doctors_notification.is_view = 0 ) as countLaboratory
        // from cashier_patientbills_records where `patient_id` = '" . $data['patient_id'] . "' and bill_from = 'laboratory' GROUP BY trace_number ORDER BY created_at DESC ";

        // $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')
        //     ->table('encoder_patientbills_records')
        //         ->where('patient_id', $data['patient_id'])
        //         ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
        //         ->groupBy('order_id')
        //         ->orderBy('created_at', 'desc')
        //         ->get();
    }

    public static function paidLabOrderDetails($data)
    {

        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->where('order_id', $data['trace_number'])
            ->where('is_view', 0)
            ->where('notification_from', $data['connection'] == 'online' ? 'virtual' : 'local')
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table($data['table'])
            ->join('patients', 'patients.patient_id', '=', $data['table'] . '.' . 'patient_id')
            ->select($data['table'] . '.' . '*', 'patients.firstname as fname', 'patients.lastname as lname')
            ->where($data['table'] . '.' . 'trace_number', $data['trace_number'])
            ->where($data['table'] . '.' . 'patient_id', $data['patient_id'])
            ->get();
    }

    public static function notificationUnreadOrders($data)
    {

        $doc_id = _Doctor::getDoctorsId($data['user_id'])->doctors_id;
        $notification_from = $data['connection'] == 'online' ? 'virtual' : 'local';

        $query = "SELECT *,
            (SELECT count(is_view) from doctors_notification where category = 'laboratory' and is_view = 0) as count_laboratory,
            (SELECT count(is_view) from doctors_notification where category = 'imaging' and is_view = 0) as count_imaging
        from doctors_notification where `doctor_id` = '$doc_id'
        AND notification_from = '$notification_from'
        and is_view = 0 GROUP BY order_id ORDER BY created_at DESC";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function docNotifUpdate($data)
    {
        date_default_timezone_set('Asia/Manila');
        $doc_id = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        return DB::table('doctors_notification')
            ->where('order_id', $data['order_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('doctor_id', $doc_id)
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getBillingRecordByDate($data)
    {

        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_to']));

        $query = " SELECT encoder_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment,  patients.firstname as fname, patients.lastname as lname, patients.street as street, patients.barangay as barangay, patients.city as city,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records
            where encoder_patientbills_records.receipt_number = encoder_patientbills_records.receipt_number
            and encoder_patientbills_records.is_refund = 1
            and encoder_patientbills_records.created_at >= '$date_from'
            and encoder_patientbills_records.created_at <= '$date_to'
        ) as totalrefund

        from encoder_patientbills_records, patients
        where encoder_patientbills_records.management_id = '" . $data['management_id'] . "'
        and patients.patient_id = encoder_patientbills_records.patient_id
        and encoder_patientbills_records.created_at >= '$date_from'
        and encoder_patientbills_records.created_at <= '$date_to'
        group by encoder_patientbills_records.receipt_number order by encoder_patientbills_records.created_at desc";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPrescriptionIncomeReport($data)
    {

        $doctorsid = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        $query = " SELECT doctors_prescription.*, patients.firstname , patients.lastname
            from doctors_prescription, patients
            where doctors_prescription.doctors_id = '$doctorsid'
            and patients.patient_id = doctors_prescription.patients_id
            order by doctors_prescription.created_at desc
        ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getVirtualImagingList($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('imaging')
            ->where('allow_virtual', 1)
            ->get();
    }

    public static function getVirtualImagingOrderList($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('imaging_order_menu')
            ->select(DB::raw("CONCAT(order_desc,' - ',order_cost, ' - shot: ', order_shots) as label"), "order_desc as value", "order_shots as shots")
            ->where('management_id', $data['vmanagementId'])
            ->get();
    }

    public static function getUnviewNotification($data)
    {

        $doctorsid = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->leftJoin('patients', 'patients.patient_id', '=', 'doctors_notification.patient_id')
            ->select('doctors_notification.*', 'patients.firstname', 'patients.lastname')
            ->where('doctor_id', $doctorsid)
            ->where('is_view', 0)
            ->where('notification_from', 'local') 
            ->get();
    }

    public static function getBillingPrescriptionByDate($data)
    {

        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_to']));
        $doctorsid = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        $query = " SELECT doctors_prescription.*, patients.firstname , patients.lastname
            from doctors_prescription, patients
            where doctors_prescription.doctors_id = '$doctorsid'
            and patients.patient_id = doctors_prescription.patients_id
            and doctors_prescription.prescription_type = '" . $data['prescription_type'] . "'
            and doctors_prescription.created_at >= '$date_from'
            and doctors_prescription.created_at <= '$date_to'
        ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function setNotifAsView($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_notification')
            ->where('order_id', $data['order_id'])
            ->update([
                'is_view' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllUnreadMsgFromPatient($data)
    {
        $query = "SELECT id AS NumberOfUnreadMsg FROM virtual_checkup_msg WHERE receivers_id = '" . $data['user_id'] . "' AND senders_id = '" . $data['senders_id'] . "' and unread = 0 ";
        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    // new routes and fullcalendar
    public static function getSidebarHeaderInformation($data)
    {
        // return DB::table('doctors')->where('user_id', $data['user_id'])->first();

        return DB::table('doctors')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'doctors.user_id')
            ->select('doctors.*', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('doctors.user_id', $data['user_id'])
            ->first();
    }

    public static function getFullcalendarAppointmentCount($data)
    {

        switch ($data['status']) {
            case 'new':
                $local = DB::table('appointment_list')
                    ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
                    ->where('is_complete', 0)
                    ->where('is_paid_bysecretary', 0)
                    ->get();

                $virtual = DB::connection('mysql2')->table('virtual_appointment')
                    ->where('doctors_id', $data['user_id'])
                    ->where('appointment_status', 'new')
                    ->get();

                return $local->merge($virtual);

                break;

            case 'approved':
                $local = DB::table('appointment_list')
                    ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
                    ->where('is_complete', 0)
                    ->where('is_paid_bysecretary', 1)
                    ->get();

                $virtual = DB::connection('mysql2')->table('virtual_appointment')
                    ->where('doctors_id', $data['user_id'])
                    ->where('appointment_status', 'approved')
                    ->get();

                return $local->merge($virtual);

                break;

            case 'completed':

                $local = DB::table('appointment_list')
                    ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
                    ->where('is_complete', 1)
                    ->where('is_paid_bysecretary', 1)
                    ->get();

                $virtual = DB::connection('mysql2')->table('virtual_appointment')
                    ->where('doctors_id', $data['user_id'])
                    ->where('appointment_status', 'successful')
                    ->get();

                return $local->merge($virtual);

                break;

            default:
                return [];
                break;
        }

    }

    public static function getDoctorsIncomeReportByYear($data)
    {
        $query = "SELECT doctors_id AS doctorId,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '01') as jan_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '02') as feb_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '03') as mar_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '04') as apr_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '05') as may_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '06') as jun_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '07') as jul_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '08') as aug_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '09') as sep_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '10') as oct_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '11') as nov_income,
        (SELECT IFNULL(sum(bill_amount), 0) from encoder_patientbills_records where doctors_id = doctorId  and is_refund = 0 and YEAR(created_at) = '" . $data['year'] . "' and MONTH(created_at) = '12') as dec_income
        FROM doctors where doctors.user_id = '" . $data['user_id'] . "'";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getFullcalendarAppointmentListLocal($data)
    {
        $query = DB::raw("(CASE WHEN is_reschedule='1' THEN is_reschedule_date ELSE app_date END) as date");
        return DB::table('appointment_list')
            ->join('patients', 'patients.patient_id', '=', 'appointment_list.patients_id')
            ->select('appointment_list.*', $query, DB::raw('concat(COALESCE(services, ""), " - " , COALESCE(firstname, " ", lastname)) as title'), 'patients.firstname', 'patients.lastname')
            ->where('appointment_list.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->get();
    }

    public static function getFullcalendarAppointmentListVirtual($data)
    {
        $query = DB::raw("(CASE WHEN is_reschedule='1' THEN is_reschedule_date ELSE appointment_date END) as date");
        return DB::connection('mysql2')->table('virtual_appointment')
            ->join('patients', 'patients.user_id', '=', 'virtual_appointment.patient_id')
            ->select('virtual_appointment.*', $query, DB::raw('concat(COALESCE(doctors_service, ""), " - " , COALESCE(firstname, " ", lastname)) as title'), 'patients.firstname', 'patients.lastname')
            ->where('virtual_appointment.doctors_id', $data['user_id'])
            ->get();
    }

    public static function updateFullcalendarAppointment($data)
    {
        date_default_timezone_set('Asia/Manila');

        $newdate = date('Y-m-d H:i:s', strtotime($data['resched_date'] . ' ' . $data['resched_time']));

        if ($data['connection'] == 'online') {

            return DB::connection('mysql2')
                ->table('virtual_appointment')
                ->where('appointment_id', $data['appid'])
                ->update([
                    'is_reschedule' => 1,
                    'is_reschedule_date' => $newdate,
                    'is_reschedule_reason' => $data['resched_reason'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

        } else {

            return DB::table('appointment_list')
                ->where('appointment_id', $data['appid'])
                ->update([
                    'is_reschedule' => 1,
                    'is_reschedule_date' => $newdate,
                    'is_reschedule_reason' => $data['resched_reason'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

        }
    }

    public static function getLocalAppointmentListByStatus($data)
    {
        switch ($data['status']) {

            case 'completed':
                return DB::table('appointment_list')
                    ->leftJoin('patients', 'patients.patient_id', 'appointment_list.patients_id')
                    ->select('appointment_list.*', 'patients.firstname', 'patients.lastname')
                    ->where('appointment_list.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
                    ->where('appointment_list.is_complete', 1)
                    ->where('appointment_list.is_paid_bysecretary', 1)
                    ->get();
                break;

            case 'approved':
                return DB::table('appointment_list')
                    ->leftJoin('patients', 'patients.patient_id', 'appointment_list.patients_id')
                    ->select('appointment_list.*', 'patients.firstname', 'patients.lastname')
                    ->where('appointment_list.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
                    ->where('appointment_list.is_complete', 0)
                    ->where('appointment_list.is_paid_bysecretary', 1)
                    ->get();
                break;

            case 'new':
                return DB::table('appointment_list')
                    ->leftJoin('patients', 'patients.patient_id', 'appointment_list.patients_id')
                    ->select('appointment_list.*', 'patients.firstname', 'patients.lastname')
                    ->where('appointment_list.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
                    ->where('appointment_list.is_complete', 0)
                    ->where('appointment_list.is_paid_bysecretary', 0)
                    ->get();
                break;

            default:
                return DB::table('appointment_list')
                    ->leftJoin('patients', 'patients.patient_id', 'appointment_list.patients_id')
                    ->select('appointment_list.*', 'patients.firstname', 'patients.lastname')
                    ->where('appointment_list.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)->get();
                break;
        }
    }

    public static function getVirtualAppointmentListByStatus($data)
    {
        switch ($data['status']) {

            case 'completed':
                return DB::connection('mysql2')->table('virtual_appointment')
                    ->leftJoin('patients', 'patients.user_id', 'virtual_appointment.patient_id')
                    ->select('virtual_appointment.*', 'patients.firstname', 'patients.lastname')
                    ->where('virtual_appointment.doctors_id', $data['user_id'])
                    ->where('virtual_appointment.appointment_status', 'successful')
                    ->get();
                break;

            case 'approved':
                return DB::connection('mysql2')->table('virtual_appointment')
                    ->leftJoin('patients', 'patients.user_id', 'virtual_appointment.patient_id')
                    ->select('virtual_appointment.*', 'patients.firstname', 'patients.lastname')
                    ->where('virtual_appointment.doctors_id', $data['user_id'])
                    ->where('virtual_appointment.appointment_status', 'approved')
                    ->get();
                break;

            case 'new':
                return DB::connection('mysql2')->table('virtual_appointment')
                    ->leftJoin('patients', 'patients.user_id', 'virtual_appointment.patient_id')
                    ->select('virtual_appointment.*', 'patients.firstname', 'patients.lastname')
                    ->where('virtual_appointment.doctors_id', $data['user_id'])
                    ->where('virtual_appointment.appointment_status', 'new')
                    ->get();
                break;

            default:
                return DB::connection('mysql2')->table('virtual_appointment')
                    ->leftJoin('patients', 'patients.user_id', 'virtual_appointment.patient_id')
                    ->select('virtual_appointment.*', 'patients.firstname', 'patients.lastname')
                    ->where('virtual_appointment.doctors_id', $data['user_id'])->get();
                break;
        }
    }

    public static function getTodaysAppointmentListLocal($data)
    {

        date_default_timezone_set('Asia/Manila');

        $doctorsid = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        $currentDate = date('Y-m-d');

        $query = "SELECT appointment_list.*, concat(COALESCE(services, ''), ' - ' , COALESCE(lastname, ''),', ', COALESCE(firstname, '')) as title,
        CASE
        WHEN appointment_list.is_reschedule='1' and Date(appointment_list.is_reschedule_date) = '$currentDate' THEN appointment_list.is_reschedule_date
        WHEN appointment_list.is_reschedule='0' and Date(appointment_list.app_date) = '$currentDate' THEN appointment_list.app_date
        END as date
        from appointment_list, patients where patients.patient_id = appointment_list.patients_id and appointment_list.doctors_id =  '$doctorsid' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    public static function getTodaysAppointmentListVirtual($data)
    {

        date_default_timezone_set('Asia/Manila');
        $currentDate = date('Y-m-d');

        $query = "SELECT virtual_appointment.*, concat(COALESCE(doctors_service, ''), ' - ' , COALESCE(lastname, ''),', ', COALESCE(firstname, '')) as title,
        CASE
        WHEN virtual_appointment.is_reschedule='1' and Date(virtual_appointment.is_reschedule_date) = '$currentDate' THEN virtual_appointment.is_reschedule_date
        WHEN virtual_appointment.is_reschedule='0' and Date(virtual_appointment.appointment_date) = '$currentDate' THEN virtual_appointment.appointment_date
        END as date
        from virtual_appointment, patients where patients.user_id = virtual_appointment.patient_id and virtual_appointment.doctors_id =  '" . $data['user_id'] . "' ";

        $result = DB::connection('mysql2')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    public static function getHemathologyGraphData($data)
    {
        return DB::connection($data['connection'] == 'connection' ? 'mysql2' : 'mysql')
            ->table('laboratory_hematology')
            ->where('patient_id', $data['patient_id'])
            ->where('order_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getChemistryGraphData($data)
    {
        return DB::connection($data['connection'] == 'connection' ? 'mysql2' : 'mysql')
            ->table('laboratory_chemistry')
            ->where('patient_id', $data['patient_id'])
            ->where('order_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getClinicalMicroscopyData($data)
    {
        return DB::connection($data['connection'] == 'connection' ? 'mysql2' : 'mysql')
            ->table('laboratory_microscopy')
            ->where('patient_id', $data['patient_id'])
            ->where('order_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getLaboratoryPrintHeaderByMng($data)
    {
        return DB::table('laboratory_formheader')->where('management_id', $data['labownermngid'])->first();
    }

    public static function updateUsername($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::table('users')->where('user_id', $data['user_id'])->update([
            'username' => $data['new_username'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updatePassword($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::table('users')->where('user_id', $data['user_id'])->update([
            'password' => Hash::make($data['new_password']),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function personalMedicationListByDate($data)
    {
        if ($data['connection'] == 'online') {
            return DB::connection('mysql2')->table('patients_personal_medication')
                ->where('patient_id', $data['patient_id'])
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_personal_medication')
            ->where('patient_id', $data['patient_id'])
            ->groupBy(DB::raw('Date(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function imagingAddOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        $queryCountQueueDB = DB::connection('mysql')->table('patient_queue')->where('patient_id', $data['patient_id'])->where('type', 'cashier')->get();

        if (count($queryCountQueueDB) < 1) {
            DB::connection('mysql')->table('patient_queue')->insert([
                'pq_id' => 'pq-' . rand(0, 99) . time(),
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'type' => 'cashier',
                'priority_sequence' => 4,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::table('imaging_center_unsaveorder')->insert([
            'icu_id' => 'icu-' . rand(0, 9999) . time(),
            'patients_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'doctors_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
            'imaging_order_id' => $data['imaging_order_id'],
            'imaging_order' => $data['order'],
            'imaging_order_remarks' => $data['remarks'],
            'amount' => $data['amount'],
            'management_id' => $data['imaging_center'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
            'order_from' => 'local',
            'can_be_discounted' => 1,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function imagingAddOrderUnsavelist($data)
    {
        return DB::table('imaging_center_unsaveorder')
            ->where('patients_id', $data['patient_id'])
            ->where('management_id', $data['management_id'])
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->get();
    }

    public static function imagingOrderUnsaveDelete($data)
    {
        return DB::table('imaging_center_unsaveorder')
            ->where('icu_id', $data['icu_id'])->delete();
    }

    public static function imagingOrderUnsaveProcess($data)
    {
        $unsave = DB::table('imaging_center_unsaveorder')
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('management_id', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->get();

        $process = [];

        foreach ($unsave as $v) {
            $process[] = array(
                'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
                // 'trace_number' => $v->icu_id,
                'doctors_id' => $v->doctors_id,
                'patient_id' => $v->patients_id,
                'trace_number' => $data['trace_number'],
                'management_id' => $v->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => $v->laboratory_id,
                'bill_name' => $v->imaging_order,
                'bill_amount' => $v->amount,
                'bill_department' => 'imaging',
                'bill_from' => 'imaging',
                'order_id' => $v->imaging_order_id,
                'can_be_discounted' => $v->can_be_discounted,
                'remarks' => $v->imaging_order_remarks,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
            );
        }

        DB::table('cashier_patientbills_unpaid')
            ->insert($process);

        return DB::table('imaging_center_unsaveorder')
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('management_id', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->delete();
    }

    public static function getQueuingPatients($data)
    {

        $query = DB::raw("(CASE WHEN is_reschedule='1' THEN is_reschedule_date ELSE app_date END) as appointment_date");
        return DB::table('appointment_list')
            ->join('patients', 'patients.patient_id', '=', 'appointment_list.patients_id')
            ->select('appointment_list.*', $query, 'patients.firstname', 'patients.lastname', 'patients.middle')
            ->where('appointment_list.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('appointment_list.is_complete', 0)
            ->get();

    }

    public static function doctorUpdatePersonalInfo($data)
    {
        return DB::table('doctors')
            ->where('user_id', $data['user_id'])
            ->update([
                'name' => $data['fullname'],
                'address' => $data['address'],
                'gender' => $data['gender'],
                'contact_no' => $data['contact_no'],
                'birthday' => date('Y-m-d', strtotime($data['birthday'])),
                'specialization' => !empty($data['specialization']) ? $data['specialization'] : null,
                'cil_umn' => !empty($data['license_no']) ? $data['license_no'] : null,
                'ead_mun' => !empty($data['dea_no']) ? $data['dea_no'] : null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function doctorUpdateProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctors')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllDoctorsServices($data)
    {
        date_default_timezone_set('Asia/Manila');
        $doctors_id = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        return DB::table('doctors_appointment_services')
            ->where('doctors_id', $doctors_id)
            ->get();
    }

    public static function addNewServiceDoctor($data)
    {
        date_default_timezone_set('Asia/Manila');
        $doctors_id = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        return DB::table('doctors_appointment_services')
            ->insert([
                'service_id' => 'sid-' . rand(0, 99) . time(),
                'doctors_id' => $doctors_id,
                'management_id' => $data['management_id'],
                'services' => $data['service'],
                'amount' => $data['service_amount'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateExistingServiceById($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctors_appointment_services')
            ->where('service_id', $data['service_id'])
            ->update([
                'services' => $data['service'],
                'amount' => $data['service_amount'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllServiceBByDoctorId($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctors_appointment_services')
            ->select('*', 'services as label', 'service_id as value')
            ->where('doctors_id', $data['doctors_id'])
            ->get();
    }

    public static function getPsycOrderDeptDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_test')
            ->where('psycho_id', _Doctor::getPsychologyIdByMgt($data)->psycho_id)
            ->get();
    }

    public static function removePsyOrderFromUnsave($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_unsaveorder')
            ->where('id', $data['removeid'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function addPsycOrderTounsave($data)
    {
        $doctors_id = _Doctor::getDoctorsId($data['user_id'])->doctors_id;

        return DB::connection('mysql')
            ->table('psychology_unsaveorder')
            ->insert([
                'pu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'doctor_id' => $doctors_id,
                'psychology_id' => _Doctor::getPsychologyIdByMgt($data)->psycho_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'psychology_test_id' => $data['psychology_test_id'],
                'psychology_test' => $data['psychology_test'],
                'psychology_rate' => $data['psychology_rate'],
                'can_be_discounted' => 1,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getUnsavePsycOrder($data)
    {
        return DB::connection('mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function processPsychologyOrder($data)
    {
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('psychology_id', _Doctor::getPsychologyIdByMgt($data)->psycho_id)
            ->get();

        $queryCountQueueDB = DB::connection('mysql')
            ->table('patient_queue')
            ->where('patient_id', $data['patient_id'])
            ->where('type', 'cashier')
            ->get();

        // $trace_number = 'order-' . rand(0, 9999) . time();
        $trace_number = $data['trace_number'];
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
                'can_be_discounted' => $v->can_be_discounted,
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

        if (count($queryCountQueueDB) < 1) {
            DB::connection('mysql')->table('patient_queue')->insert([
                'pq_id' => 'pq-' . rand(0, 99) . time(),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'trace_number' => $trace_number,
                'type' => 'cashier',
                'priority_sequence' => 4,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
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

    public static function newPhysicalExam($data)
    {
        DB::table('laboratory_medical_exam')->insert([
            'lme_id' => 'lme-' . rand(0, 9999) . time(),
            'order_id' => $orderid,
            'doctor_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
            'patient_id' => $data['patient_id'],
            'laboratory_id' => _Doctor::getLaboratoryIdByMgt($data)->laboratory_id,
            'remarks' => $data['remarks'],
            'order_status' => 'new-order',
            'trace_number' => $data['trace_number'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('cashier_patientbills_unpaid')->insert([
            'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
            'trace_number' => $v->icu_id,
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
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function newPEOrderList($data)
    {
        return DB::table('cashier_patientbills_records')
            ->join('laboratory_medical_exam', 'laboratory_medical_exam.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')

            ->select('cashier_patientbills_records.*', 'cashier_patientbills_records.created_at  as transaction_date', 'laboratory_medical_exam.*', 'patients.firstname', 'patients.lastname')
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])

        // ->where('cashier_patientbills_records.bill_from', 'medical examination')

        // ->where('cashier_patientbills_records.bill_from', 'Other Test')
        // ->where('cashier_patientbills_records.bill_name', 'Physical Examination') //added now

            ->where('laboratory_medical_exam.order_status', 'new-order-paid')
            ->where('laboratory_medical_exam.doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->groupBy('laboratory_medical_exam.patient_id')
            ->get();

        // return DB::table('cashier_patientbills_records')
        //     ->join('laboratory_medical_exam', 'laboratory_medical_exam.patient_id', '=', 'cashier_patientbills_records.patient_id')
        //     ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        //     ->select('cashier_patientbills_records.*', 'laboratory_medical_exam.*', 'patients.firstname', 'patients.lastname')
        //     ->where('cashier_patientbills_records.management_id', $data['management_id'])
        //     ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
        //     ->where('bill_name', 'medical-exam')
        //     ->where('laboratory_medical_exam.order_status', 'new-order-paid')
        //     ->groupBy('cashier_patientbills_records.patient_id')
        //     ->get();
    }

    public static function setPEOrderProcess($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_medical_exam')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', _Doctor::getLaboratoryIdByMgt($data)->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getNewMedCertOrderAll($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_medical_certificate_ordered')
            ->leftJoin('patients', 'patients.patient_id', '=', 'doctors_medical_certificate_ordered.patient_id')
            ->select('doctors_medical_certificate_ordered.*', 'doctors_medical_certificate_ordered.created_at as transaction_date', 'patients.lastname', 'patients.firstname')
            ->where('doctors_medical_certificate_ordered.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('doctors_medical_certificate_ordered.order_status', 'new-order-paid')
            ->get();
    }

    public static function getNewMedCertOrder($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_medical_certificate_ordered')
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('patient_id', $data['patient_id'])
            ->where('order_status', 'new-order-paid')
            ->get();
    }

    public static function setMedCertOrderCompleted($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_medical_certificate_ordered')
            ->where('lmc_id', $data['lmc_id'])
            ->update([
                'diagnosis_findings' => strip_tags($data['diagnosis']),
                'recommendation' => strip_tags($data['recommendation']),
                'remarks' => strip_tags($data['remarks']),
                'issued_at' => date('Y-m-d', strtotime($data['issued_at'])),
                'order_status' => 'completed',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function newPatientMedicalCertificate($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_medical_certificate_ordered')
            ->insert([
                'lmc_id' => 'lmc-' . rand(1, 99999) . '-' . time(),
                'patient_id' => $data['patient_id'],
                'doctors_id' => _Doctor::getDoctorsId($data['user_id'])->doctors_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'diagnosis_findings' => strip_tags($data['diagnosis']),
                'recommendation' => strip_tags($data['recommendation']),
                'remarks' => strip_tags($data['remarks']),
                'issued_at' => date('Y-m-d', strtotime($data['issued_at'])),
                'order_status' => 'completed',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getPatientMedicalCertificateList($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_medical_certificate_ordered')
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateConsultationRate($data)
    {
        date_default_timezone_set('Asia/Manila');

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('appointment_list')
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('patients_id', $data['patient_id'])
            ->where('appointment_id', $data['trace_number'])
            ->update([
                'amount' => $data['new_rate'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('bill_department', 'appointment')
            ->update([
                'bill_amount' => $data['new_rate'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllServicesMadamada($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctors_appointment_services')
            ->select('*', 'services as label', 'service_id as value')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getAllSalaryRecord($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctor_salary_record')
            ->where('doctor_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function doctorUpdateToReceivedSalaryRecord($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('doctor_salary_record')
            ->where('dsr_id', $data['dsr_id'])
            ->update([
                'is_received' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function doctorResultAttachment($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patient_sharedimages')->insert([
            'psi_id' => 'psi-' . rand(0, 8889) . time(),
            'patient_id' => $data['patient_id'],
            'image' => $filename,
            'type' => $data['type'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getPatientDocAttachImageDates($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patient_sharedimages')
            ->select('created_at as doc_attach_date')
            ->where('patient_id', $data['patient_id'])
            ->where('type', $data['type'])
            ->groupBy(DB::raw('Date(created_at)'))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getPatientDocAttachImageDatesDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patient_sharedimages')
            ->where('patient_id', $data['patient_id'])
            ->whereDate('created_at', date('Y-m-d', strtotime($data['selectedDate'])))
            ->where('type', $data['type'])
            ->get();
    }

    public static function getPatientAdmittingRecord($data)
    {
        return DB::table('hospital_admission')
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])->first();
    }

    public static function checkPatientToAdmitting($data)
    {
        return DB::table('hospital_admission')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function sentPatientToAdmitting($data)
    {
        return DB::table('hospital_admission')->insert([
            'adm_id' => 'adm-' . rand(0, 9999) . time(),
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'doctors_id' => $data['user_id'],
            'trace_number' => $data['trace_number'],
            'doctors_order' => $data['doctors_order'],
            'nurse_assign' => $data['nurse_assign'],
            'is_admitted' => 0,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getAdmittedPatientAssignByMd($data)
    {
        return DB::table('hospital_admitted_patient')
            ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient.patient_id')
            ->leftJoin('hospital_rooms', 'hospital_rooms.room_id', '=', 'hospital_admitted_patient.room_id')
            ->select('hospital_admitted_patient.*', 'hospital_rooms.room_name', 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle', 'hospital_admitted_patient.patient_id', 'hospital_admitted_patient.created_at')
            ->where('hospital_admitted_patient.medical_doctor_assign', $data['user_id'])
            ->where('hospital_admitted_patient.nurse_department', '!=', "discharge-department")
            ->where('hospital_admitted_patient.nurse_department', '!=', "discharged")
            ->get();
    }

    public static function checkPatientForOperation($data)
    {
        return DB::table('hospital_admitted_patient_foroperation')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('nurse_assign', '!=', 'discharged')
            ->get();
    }

    public static function sentPatientForOperation($data)
    {
        DB::table('hospital_admission')->insert([
            'adm_id' => 'adm-' . rand(0, 9999) . time(),
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'doctors_id' => $data['user_id'],
            'trace_number' => $data['trace_number'],
            'doctors_order' => $data['doctors_order'],
            'is_admitted' => 0,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('hospital_admitted_patient_foroperation')->insert([
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'management_id' => $data['management_id'],
            'doctors_order' => $data['doctors_order'],
            'operation_status' => 'for-preparation',
            'nurse_assign' => 'or-nurse',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getAppointmentRecordsAll($data)
    {
        $date_from = date('Y-m-d 00:00:00', strtotime($data['dateFrom']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['dateTo']));

        return DB::table('appointment_list')
            ->leftJoin('patients', 'patients.patient_id', 'appointment_list.patients_id')
            ->leftJoin('nurses', 'nurses.nurse_id', 'appointment_list.encoders_id')
            ->select('appointment_list.*', 'patients.firstname', 'patients.lastname', 'patients.gender', 'patients.birthday', 'patients.city', 'patients.barangay',
                'patients.philhealth',
                'patients.blood_systolic',
                'patients.blood_diastolic',
                'patients.temperature',
                'patients.rispiratory',
                'nurses.user_fullname as seen_by')
            ->where('appointment_list.doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('appointment_list.management_id', $data['management_id'])
            ->where('appointment_list.created_at', ">=", $date_from)
            ->where('appointment_list.created_at', "<=", $date_to)
            ->get();
    }
}
