<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Psychology extends Model
{
    public static function getHeaderInfo($data)
    {
        // return DB::table('psychology_account')
        //     ->select('management_id', 'psycho_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

        return DB::table('psychology_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'psychology_account.user_id')
            ->select('psychology_account.psycho_id', 'psychology_account.user_fullname as name', 'psychology_account.image', 'psychology_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('psychology_account.user_id', $data['user_id'])
            ->first();
    }

    public static function getPsychologyId($user_id)
    {
        return DB::table('psychology_account')->select('psycho_id')->where('user_id', $user_id)->first();
    }

    public static function getNewPatients($data)
    {
        return DB::table('psychology_test_orders')
            ->where('is_paid', 1)
            ->where('management_id', $data['management_id'])
            ->whereNull('order_result')
            ->get();
    }

    public static function getPsychologyTest($data)
    {
        return DB::table('psychology_test')
            ->where('psycho_id', _Psychology::getHeaderInfo($data)->psycho_id)
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function newPsychologyTest($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('psychology_test')->insert([
            'ptl_id' => 'ptl-' . rand(0, 9999) . time(),
            'psycho_id' => _Psychology::getHeaderInfo($data)->psycho_id,
            'management_id' => _Psychology::getHeaderInfo($data)->management_id,
            'test_id' => 'test-' . rand(0, 9999) . time(),
            'test' => $data['test'],
            'rate' => $data['rate'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    //changes like laboratory now
    // public static function getPatientWithOrder($data)
    // {
    //     return DB::table('psychology_test_orders')
    //         ->join('patients', 'patients.patient_id', '=', 'psychology_test_orders.patient_id')
    //         ->select('psychology_test_orders.created_at as order_date', 'psychology_test_orders.*', 'patients.*')
    //         ->where('psychology_test_orders.management_id', $data['management_id'])
    //         ->where('psychology_test_orders.is_processed', 0)
    //         ->where('psychology_test_orders.is_paid', 0)
    //         ->whereNull('psychology_test_orders.order_result')
    //         ->get();
    // }

    public static function getPatientWithOrder($data)
    {
        // $management_id = $data['management_id'];
        // $query = " SELECT patient_id AS pid,

        //     (SELECT concat(lastname,', ',firstname) FROM patients WHERE patient_id = pid) AS patient_name,
        //     (SELECT image FROM patients WHERE patient_id = pid) AS patient_image,
        //     (SELECT count(id) FROM psychology_neuroexam WHERE patient_id = pid AND order_status='new-order-paid') AS count_neuro,
        //     (SELECT count(id) FROM psychology_audiometry WHERE patient_id = pid AND order_status='new-order-paid') AS count_audio,
        //     (SELECT count(id) FROM psychology_ishihara WHERE patient_id = pid AND order_status='new-order-paid') AS count_ishihara,
        //     (SELECT IFNULL(sum(count_neuro + count_audio + count_ishihara), 0)) AS order_count

        // FROM cashier_patientbills_records WHERE management_id = '$management_id' GROUP BY patient_id ";

        // $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);

        $mngt = $data['management_id'];
        $query = " SELECT patient_id as pid,

            (SELECT concat(lastname,', ',firstname) from patients where patient_id = pid) as patient_name,
            (SELECT image from patients where patient_id = pid) as patient_image,
            (SELECT count(id) FROM psychology_neuroexam WHERE patient_id = pid AND order_status='new-order-paid') AS count_neuro,
            (SELECT count(id) FROM psychology_audiometry WHERE patient_id = pid AND order_status='new-order-paid') AS count_audio,
            (SELECT count(id) FROM psychology_ishihara WHERE patient_id = pid AND order_status='new-order-paid') AS count_ishihara,
            (SELECT IFNULL(sum(count_neuro + count_audio + count_ishihara), 0)) AS order_count

        from patient_queue where management_id = '$mngt' AND type = 'psychology' group by patient_id having order_count > 0";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }


    public static function getPatientWithOrderVan($data){
        $mngt = $data['management_id'];
        $query = " SELECT patient_id as pid,

            (SELECT concat(lastname,', ',firstname) from patients where patient_id = pid) as patient_name,
            (SELECT image from patients where patient_id = pid) as patient_image,
            (SELECT count(id) FROM psychology_neuroexam WHERE patient_id = pid AND order_status='new-order-paid') AS count_neuro,
            (SELECT count(id) FROM psychology_audiometry WHERE patient_id = pid AND order_status='new-order-paid') AS count_audio,
            (SELECT count(id) FROM psychology_ishihara WHERE patient_id = pid AND order_status='new-order-paid') AS count_ishihara,
            (SELECT IFNULL(sum(count_neuro + count_audio + count_ishihara), 0)) AS order_count

        from mobile_van_queue where management_id = '$mngt' AND type = 'psychology' group by patient_id having order_count > 0";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisPsychologyGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM psychology_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisPsychologyUpdatePersonalInfo($data)
    {
        return DB::table('psychology_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisPsychologyUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('psychology_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisPsychologyUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisPsychologyUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    //audiometric report
    public static function getOrderAudiometryNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_audiometry')
            ->join('patients', 'patients.patient_id', '=', 'psychology_audiometry.patient_id')
            ->select('psychology_audiometry.*', 'patients.*', 'psychology_audiometry.created_at as date_ordered')
            ->where('psychology_audiometry.patient_id', $data['patient_id'])
            ->where('psychology_audiometry.order_status', 'new-order-paid')
            ->groupBy('psychology_audiometry.trace_number')
            ->get();
    }
    public static function getOrderAudiometryNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_audiometry')
            ->join('patients', 'patients.patient_id', '=', 'psychology_audiometry.patient_id')
            ->select('psychology_audiometry.*', 'patients.*')
            ->where('psychology_audiometry.trace_number', $data['trace_number'])
            ->get();
    }
    public static function psychologyAudiometryOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_audiometry')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('psychology_id', (new _Psychology)::getPsychologyId($data['user_id'])->psycho_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveAudiometryOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];
        $order_count = $data['order_count'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('psychology_audiometry')
            ->where('order_id', $orderid[$i])
            ->where('trace_number', $data['trace_number'])
            ->update([
                'left_8000' => empty($data['left_8000'][$i]) ? null : $data['left_8000'][$i],
                'right_8000' => empty($data['right_8000'][$i]) ? null : $data['right_8000'][$i],
                'left_4000' => empty($data['left_4000']) ? null : $data['left_4000'][$i],
                'right_4000' => empty($data['right_4000'][$i]) ? null : $data['right_4000'][$i],
                'left_2000' => empty($data['left_2000'][$i]) ? null : $data['left_2000'][$i],
                'right_2000' => empty($data['right_2000'][$i]) ? null : $data['right_2000'][$i],
                'left_1000' => empty($data['left_1000'][$i]) ? null : $data['left_1000'][$i],
                'right_1000' => empty($data['right_1000'][$i]) ? null : $data['right_1000'][$i],
                'left_500' => empty($data['left_500'][$i]) ? null : $data['left_500'][$i],
                'right_500' => empty($data['right_500'][$i]) ? null : $data['right_500'][$i],
                'left_250' => empty($data['left_250'][$i]) ? null : $data['left_250'][$i],
                'right_250' => empty($data['right_250'][$i]) ? null : $data['right_250'][$i],
                'right_ear_interpret' => empty($data['right_ear_interpret'][$i]) ? null : $data['right_ear_interpret'][$i],
                'left_ear_interpret' => empty($data['left_ear_interpret'][$i]) ? null : $data['left_ear_interpret'][$i],
                'order_status' => 'completed',
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInPsychology($data['patient_id'], $data['trace_number']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInPsychologyVan($data['patient_id'], $data['trace_number']);
        }

        // if ((float) $order_count == 1) {
        //     DB::table($data['queue'])->where('patient_id', $data['patient_id'])->where('type', 'psychology')->delete();
        // }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'psychology',
            'department' => 'psychology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new psychology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteAudiometryOrderDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_audiometry')
            ->join('patients', 'patients.patient_id', '=', 'psychology_audiometry.patient_id')
            // ->select('psychology_audiometry.*', 'patients.*')
            ->select('psychology_audiometry.*', 'patients.firstname', 'patients.lastname', 'patients.birthday', 'patients.gender', 'patients.street', 'patients.barangay', 'patients.city', 'patients.image')
            ->where('psychology_audiometry.trace_number', $data['trace_number'])
            ->where('psychology_audiometry.order_status', 'completed')
            ->get();
    }

    //ishihara test
    public static function getOrderIshiharaNew($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_ishihara')
            ->join('patients', 'patients.patient_id', '=', 'psychology_ishihara.patient_id')
            ->select('psychology_ishihara.*', 'patients.*', 'psychology_ishihara.created_at as date_ordered')
            ->where('psychology_ishihara.patient_id', $data['patient_id'])
            ->where('psychology_ishihara.order_status', 'new-order-paid')
            ->groupBy('psychology_ishihara.trace_number')
            ->get();
    }
    public static function getOrderIshiharaNewDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_ishihara')
            ->join('patients', 'patients.patient_id', '=', 'psychology_ishihara.patient_id')
            ->select('psychology_ishihara.*', 'patients.*')
            ->where('psychology_ishihara.trace_number', $data['trace_number'])
            ->get();
    }
    public static function psychologyIshiharaOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_ishihara')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('psychology_id', (new _Psychology)::getPsychologyId($data['user_id'])->psycho_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveIshiharaOrderResult($data){
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];
        $order_count = $data['order_count'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('psychology_ishihara')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'type_person_1_2_3' => $data['type_person_1_2_3'],
                    'one' => empty($data['one'][$i]) ? null : $data['one'][$i],
                    'two' => empty($data['two']) ? null : $data['two'][$i],
                    'three' => empty($data['three'][$i]) ? null : $data['three'][$i],
                    'four' => empty($data['four'][$i]) ? null : $data['four'][$i],
                    'five' => empty($data['five'][$i]) ? null : $data['five'][$i],
                    'six' => empty($data['six'][$i]) ? null : $data['six'][$i],
                    'seven' => empty($data['seven'][$i]) ? null : $data['seven'][$i],
                    'eight' => empty($data['eight'][$i]) ? null : $data['eight'][$i],
                    'nine' => empty($data['nine'][$i]) ? null : $data['nine'][$i],
                    'ten' => empty($data['ten'][$i]) ? null : $data['ten'][$i],
                    'eleven' => empty($data['eleven'][$i]) ? null : $data['eleven'][$i],
                    'twelve' => empty($data['twelve'][$i]) ? null : $data['twelve'][$i],
                    'thirteen' => empty($data['thirteen'][$i]) ? null : $data['thirteen'][$i],
                    'fourteen' => empty($data['fourteen'][$i]) ? null : $data['fourteen'][$i],
                    'fifteen' => empty($data['fifteen'][$i]) ? null : $data['fifteen'][$i],
                    'sixteen' => empty($data['sixteen'][$i]) ? null : $data['sixteen'][$i],
                    'seventeen' => empty($data['seventeen'][$i]) ? null : $data['seventeen'][$i],
                    'eighteen' => empty($data['eighteen'][$i]) ? null : $data['eighteen'][$i],
                    'nineteen' => empty($data['nineteen'][$i]) ? null : $data['nineteen'][$i],
                    'twenty' => empty($data['twenty'][$i]) ? null : $data['twenty'][$i],
                    'twentyone' => empty($data['twentyone'][$i]) ? null : $data['twentyone'][$i],
                    'blindness_type' => empty($data['blindness_type']) ? null : $data['blindness_type'],
                    'twentytwo' => empty($data['twentytwo'][$i]) ? null : $data['twentytwo'][$i],
                    'twentythree' => empty($data['twentythree'][$i]) ? null : $data['twentythree'][$i],
                    'twentyfour' => empty($data['twentyfour'][$i]) ? null : $data['twentyfour'][$i],
                    'twentyfive' => empty($data['twentyfive'][$i]) ? null : $data['twentyfive'][$i],
                    'test_score' => $data['test_score'],
                    'order_status' => 'completed',
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInPsychology($data['patient_id'], $data['trace_number']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInPsychologyVan($data['patient_id'], $data['trace_number']);
        }

        // if ((float) $order_count == 1) {
        //     DB::table($data['queue'])->where('patient_id', $data['patient_id'])->where('type', 'psychology')->delete();
        // }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'psychology',
            'department' => 'psychology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new psychology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteIshiharaOrderDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_ishihara')
            ->join('patients', 'patients.patient_id', '=', 'psychology_ishihara.patient_id')
            // ->select('psychology_ishihara.*', 'patients.*')
            ->select('psychology_ishihara.*', 'patients.firstname', 'patients.lastname', 'patients.birthday', 'patients.gender', 'patients.street', 'patients.barangay', 'patients.city', 'patients.image')
            ->where('psychology_ishihara.trace_number', $data['trace_number'])
            ->where('psychology_ishihara.order_status', 'completed')
            ->get();
    }

    //neurology test
    public static function getOrderNeurologyNew($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_neuroexam')
            ->join('patients', 'patients.patient_id', '=', 'psychology_neuroexam.patient_id')
            ->select('psychology_neuroexam.*', 'patients.*', 'psychology_neuroexam.created_at as date_ordered')
            ->where('psychology_neuroexam.patient_id', $data['patient_id'])
            ->where('psychology_neuroexam.order_status', 'new-order-paid')
            ->groupBy('psychology_neuroexam.trace_number')
            ->get();
    }
    public static function getOrderNeurologyNewDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_neuroexam')
            ->join('patients', 'patients.patient_id', '=', 'psychology_neuroexam.patient_id')
            ->select('psychology_neuroexam.*', 'patients.*')
            ->where('psychology_neuroexam.trace_number', $data['trace_number'])
            ->get();
    }
    public static function psychologyNeurologyOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_neuroexam')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('psychology_id', (new _Psychology)::getPsychologyId($data['user_id'])->psycho_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveNeurologyOrderResult($data){
        date_default_timezone_set('Asia/Manila');
        
        $orderid = $data['order_id'];
        $order_count = $data['order_count'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('psychology_neuroexam')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'intel_level' =>  empty($data['intel_level']) ? null : $data['intel_level'],
                    'perseverance' => empty($data['perseverance']) ? null : $data['perseverance'],
                    'obedience' => empty($data['obedience']) ? null : $data['obedience'],
                    'self_discipline' => empty($data['self_discipline']) ? null : $data['self_discipline'],
                    'enthusiasm' => empty($data['enthusiasm']) ? null : $data['enthusiasm'],
                    'initiative' => empty($data['initiative']) ? null : $data['initiative'],
                    'cwbawa' => empty($data['cwbawa']) ? null : $data['cwbawa'],
                    'ttspai' => empty($data['ttspai']) ? null : $data['ttspai'],
                    'faces_reality' => empty($data['faces_reality']) ? null : $data['faces_reality'],
                    'confidence' => empty($data['confidence']) ? null : $data['confidence'],
                    'relaxed' => empty($data['relaxed']) ? null : $data['relaxed'],
                    'tough_mindedness' => empty($data['tough_mindedness']) ? null : $data['tough_mindedness'],
                    'adaptability' => empty($data['adaptability']) ? null : $data['adaptability'],
                    'practicality' => empty($data['practicality']) ? null : $data['practicality'],
                    'assertiveness' => empty($data['assertiveness']) ? null : $data['assertiveness'],
                    'independence' => empty($data['independence']) ? null : $data['independence'],
                    'resourcefulness' => empty($data['resourcefulness']) ? null : $data['resourcefulness'],
                    'rwpac_temmanship' => empty($data['rwpac_temmanship']) ? null : $data['rwpac_temmanship'],
                    'rwseaa_deference' => empty($data['rwseaa_deference']) ? null : $data['rwseaa_deference'],
                    'self_esteem' => empty($data['self_esteem']) ? null : $data['self_esteem'],
                    'aggressive_tendencies' => empty($data['aggressive_tendencies']) ? null : $data['aggressive_tendencies'],
                    'doetcco' => empty($data['doetcco']) ? null : $data['doetcco'],
                    'order_status' => 'completed',
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInPsychology($data['patient_id'], $data['trace_number']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInPsychologyVan($data['patient_id'], $data['trace_number']);
        }

        // if ((float) $order_count == 1) {
        //     DB::table($data['queue'])->where('patient_id', $data['patient_id'])->where('type', 'psychology')->delete();
        // }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'psychology',
            'department' => 'psychology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new psychology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteNeurologyOrderDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_neuroexam')
            ->join('patients', 'patients.patient_id', '=', 'psychology_neuroexam.patient_id')
            ->select('psychology_neuroexam.*', 'patients.firstname', 'patients.lastname', 'patients.birthday', 'patients.gender', 'patients.street', 'patients.barangay', 'patients.city', 'patients.image')
            ->where('psychology_neuroexam.trace_number', $data['trace_number'])
            ->where('psychology_neuroexam.order_status', 'completed')
            ->get();
    }

    public static function getPsychologyCompletedReport($data)
    {
        $psychology_id = (new _Psychology)::getPsychologyId($data['user_id'])->psycho_id;
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table($data['table'])
            ->join('patients', 'patients.patient_id', '=', $data['table'] . '.' . 'patient_id')
            ->select($data['table'] . '.' . '*', 'patients.firstname as fname', 'patients.lastname as lname')
            ->where($data['table'] . '.' . 'psychology_id', $psychology_id)
            ->where($data['table'] . '.' . 'order_status', 'completed')
            ->groupBy($data['table'] . '.' . 'trace_number')
            ->get();
    }

    public static function getPsychologyIdByMgt($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_account')
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getPsycologyOrder($data)
    {
        return DB::table('psychology_test')
            ->select('psychology_test.*', 'psychology_test.test as label', 'psychology_test.test as value')
            ->where('psycho_id', (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id)
            ->get();
    }

    public static function getUnsavePsycologyOrder($data)
    {
        return DB::table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function addPsycOrderTounsave($data)
    {
        return DB::table('psychology_unsaveorder')
            ->insert([
                'pu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],

                'doctor_id' => 'admission-order',
                'psychology_id' => (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id,
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

    public static function removePsyOrderFromUnsave($data)
    {
        return DB::table('psychology_unsaveorder')
            ->where('id', $data['removeid'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function processPsychologyOrder($data)
    {
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('psychology_id', (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id)
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
                'management_id' => (new _Psychology)::getPsychologyIdByMgt($data)->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'psychology_id' => (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id,
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
                            'psychology_id' => (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id,
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
                            'psychology_id' => (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id,
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
                            'psychology_id' => (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id,
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
            ->where('psychology_id', (new _Psychology)::getPsychologyIdByMgt($data)->psycho_id)
            ->delete();

    }

    public static function getPsychologyUnpaidList($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('bill_from', 'psychology')
            ->groupBy('trace_number')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getPsychologyUnpaidListDetails($data)
    {
        return DB::table('cashier_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function psychologyPaidOrderByPatient($data){
        $patient_id = $data['patient_id'];
        $query = "SELECT *,
            (SELECT count(doctors_notification.is_view) FROM doctors_notification WHERE doctors_notification.patient_id = '$patient_id' AND doctors_notification.category = 'psychology' AND doctors_notification.is_view = 0 ) as countPsychology
        from cashier_patientbills_records where `patient_id` = '$patient_id' and bill_from = 'psychology' GROUP BY trace_number ORDER BY created_at DESC ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAllPsychologyReport($data)
    {
        return DB::table('cashier_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
            ->where('cashier_patientbills_records.bill_from', 'psychology')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->groupBy('cashier_patientbills_records.trace_number')
            ->get();
    }

    public static function getAllPsychologyReportFilter($data)
    {
        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])).' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])).' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));
        $management_id = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];

        $query = "SELECT *,
            (SELECT firstname from patients where patients.patient_id = cashier_patientbills_records.patient_id limit 1) as firstname,
            (SELECT lastname from patients where patients.patient_id = cashier_patientbills_records.patient_id limit 1) as lastname
        from cashier_patientbills_records where management_id = '$management_id' AND main_mgmt_id = '$main_mgmt_id' AND bill_from = 'psychology' AND created_at >= '$strDaily' AND created_at <= '$lstDaily' GROUP BY trace_number ORDER BY created_at DESC ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

}
