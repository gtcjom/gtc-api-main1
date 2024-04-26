<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Laboratory extends Model
{
    public static function hislabGetHeaderInfo($data)
    {
        // return DB::table('laboratory_list')
        //     ->select('laboratory_id', 'user_fullname as name', 'image')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

            return DB::table('laboratory_list')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'laboratory_list.user_id')
            ->select('laboratory_list.laboratory_id', 'laboratory_list.user_fullname as name', 'laboratory_list.image', 'laboratory_list.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('laboratory_list.user_id', $data['user_id'])
            ->first();
    }

    public static function getLaboratoryId($user_id)
    {
        return DB::table('laboratory_list')->select('laboratory_id')->where('user_id', $user_id)->first();
    }

    public static function laboratoryCounts($patient_id)
    {
        $query = "SELECT lab_id,
        (SELECT count(id) from laboratory where laboratory.patients_id = '$patient_id' and is_processed = 1 and laboratory_results is not null) as count_processed,
        (SELECT count(id) from laboratory where laboratory.patients_id = '$patient_id' and is_processed = 1 and is_pending = 0 and laboratory_results is null) as count_ongoing,
        (SELECT count(id) from laboratory where laboratory.patients_id = '$patient_id' and is_processed = 0 and is_pending = 0 and laboratory_results is null) as count_unprocess,
        (SELECT count(id) from laboratory where laboratory.patients_id = '$patient_id' and is_pending = 1) as count_pending
        from laboratory where laboratory.patients_id = '" . $patient_id . "' limit 1";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function laboratoryWithResult($patient_id)
    {
        return DB::table('laboratory')->where('patients_id', $patient_id)->whereNotNull('laboratory_results')->where('is_processed', 1)->get();
    }

    public static function laboratoryOrderDetails($lab_id)
    {
        return DB::table('laboratory')
            ->join('patients', 'patients.patient_id', '=', 'laboratory.patients_id')
            ->select('laboratory.*', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.birthday', 'patients.gender')
            ->where('laboratory.lab_id', $lab_id)
            ->get();
    }

    public static function laboratoryOngoing($patient_id)
    {
        return DB::table('laboratory')
            ->where('patients_id', $patient_id)
            ->whereNull('laboratory_results')
            ->where('is_processed', 1)
            ->where('is_pending', 0)->get();
    }

    public static function laboratoryUnprocess($patient_id)
    {
        return DB::table('laboratory')
            ->where('patients_id', $patient_id)
            ->whereNull('laboratory_results')
            ->where('is_processed', 0)
            ->where('is_pending', 0)->get();
    }

    public static function laboratoryPending($patient_id)
    {
        return DB::table('laboratory')->where('patients_id', $patient_id)->whereNull('laboratory_results')->where('is_processed', 0)->where('is_pending', 1)->get();
    }

    public static function laboratoryDetails($management_id)
    {
        return DB::table('laboratory_list')->where('management_id', $management_id)->limit(1)->get();
    }

    // public static function createOrder($data){
    //     date_default_timezone_set('Asia/Manila');

    //     $order = implode(', ', $data['order']);
    //     return DB::connection($data['connection'] == 'online'  ? 'mysql2' : 'mysql')->table('laboratory')->insert([
    //         'lab_id' => 'lab-'.rand(0, 99999),
    //         'laboratory_id' => $data['laboratory_id'],
    //         'patients_id' => $data['patient_id'],
    //         'doctors_id' => _Doctor::getDoctorsId($data['doctors_id'])->doctors_id,
    //         'doctors_remarks' => $data['remarks'],
    //         'laboratory_orders' => $order,
    //         'is_processed' => 0,
    //         'is_pending' => 0,
    //         'created_at' => date('Y-m-d H:i:s'),
    //         'updated_at' => date('Y-m-d H:i:s')
    //     ]);
    // }

    public static function getOrderDetails($orderId)
    {
        return DB::table('laboratory_test')->where('lt_id', $orderId)->first();
    }

    public static function addLabOrder($data)
    {
        date_default_timezone_set('Asia/Manila');

        $order = (new _Laboratory)::getOrderDetails($data['order_id']);
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')->insert([
            'epb_id' => 'epb-' . rand(0, 99999),
            'trace_number' => $data['order_id'],
            'patient_id' => $data['patient_id'],
            'doctors_id' => _Doctor::getDoctorsId($data['user_id']),
            'bill_name' => $order->laboratory_test,
            'bill_amount' => $order->laboratory_rate,
            'bill_from' => 'laboratory',
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function cancelLabOrder($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')
            ->where('epb_id', $data['cancel_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function getNewOrder($laboratory_id)
    {
        $query = "SELECT lab_id, laboratory_orders, created_at, doctors_remarks,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = laboratory.patients_id ) as patient_name,
        (SELECT concat(name) from doctors where doctors.doctors_id = laboratory.doctors_id ) as doctors_name
        from laboratory where laboratory.laboratory_id = '$laboratory_id' and is_processed = 0 and is_pending = 0 and laboratory_results is null";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPendingOrder($laboratory_id)
    {
        $query = "SELECT lab_id, laboratory_orders, pending_reason, pending_date,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = laboratory.patients_id ) as patient_name
        from laboratory where laboratory.laboratory_id = '$laboratory_id' and is_processed = 0 and is_pending = 1 and laboratory_results is null";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getProcessingOrder($laboratory_id)
    {
        $query = "SELECT patients_id, lab_id, laboratory_orders, created_at, doctors_remarks, start_time,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = laboratory.patients_id ) as patient_name,
        (SELECT concat(name) from doctors where doctors.doctors_id = laboratory.doctors_id ) as doctors_name
        from laboratory where laboratory.laboratory_id = '$laboratory_id' and is_processed = 1 and is_pending = 0 and laboratory_results is null";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function orderSetProcess($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory')->where('lab_id', $data['lab_id'])->update([
            'is_processed' => 1,
            'is_pending' => 0,
            'processed_by' => $data['user_id'],
            'start_time' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function orderSetPending($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory')->where('lab_id', $data['lab_id'])->update([
            'is_processed' => 0,
            'is_pending' => 1,
            'pending_by' => $data['user_id'],
            'pending_date' => date('Y-m-d H:i:s'),
            'pending_reason' => $data['pending_reason'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getLaboratoryByPatient($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory')
            ->where('patients_id', _Patient::getPatientId($data['user_id']))
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public static function addResult($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');

        if (!empty($data['calcium'])) {
            (new _Laboratory)::newCalcium($data);
        }

        if (!empty($data['chloride'])) {
            (new _Laboratory)::newChloride($data);
        }

        if (!empty($data['creatinine'])) {
            (new _Laboratory)::newCreatinine($data);
        }

        if (!empty($data['hdl'])) {
            (new _Laboratory)::newHDL($data);
        }

        if (!empty($data['ldl'])) {
            (new _Laboratory)::newLDL($data);
        }

        if (!empty($data['lithium'])) {
            (new _Laboratory)::newLithium($data);
        }

        if (!empty($data['magnesium'])) {
            (new _Laboratory)::newMagnesium($data);
        }

        if (!empty($data['potassium'])) {
            (new _Laboratory)::newPotassium($data);
        }

        if (!empty($data['protein'])) {
            (new _Laboratory)::newProtein($data);
        }

        if (!empty($data['sodium'])) {
            (new _Laboratory)::newSodium($data);
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory')->where('lab_id', $data['lab_id'])->update([
            'is_processed' => 1,
            'laboratory_results' => $data['result'],
            'laboratory_remarks' => $data['remarks'],
            'laboratory_attachment' => $filename,
            'is_viewed' => 0,
            'time_end' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCounts($laboratory_id)
    {
        $query = "SELECT id,
        (SELECT IFNULL(count(id), 0) from laboratory where laboratory.laboratory_id = '$laboratory_id' and is_processed = 0 and is_pending = 0 and laboratory_results is null ) as newCount,
        (SELECT IFNULL(count(id), 0) from laboratory where laboratory.laboratory_id = '$laboratory_id' and is_processed = 1 and is_pending = 0 and laboratory_results is null ) as processingCount,
        (SELECT IFNULL(count(id), 0) from laboratory where laboratory.laboratory_id = '$laboratory_id' and is_processed = 0 and is_pending = 1 and laboratory_results is null ) as pendingCount
        from laboratory where laboratory.laboratory_id = '$laboratory_id' limit 1";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAllRecords($laboratory_id)
    {

        $query = "SELECT *,
        (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = laboratory.patients_id ) as patient_name,
        (SELECT concat(name) from doctors where doctors.doctors_id = laboratory.doctors_id ) as doctors_name
        from laboratory where laboratory_id = '$laboratory_id' and laboratory_results is not null ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    // new data for graph...
    // new calcuim
    public static function newCalcium($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_calcium')->insert([
            'phc_id' => 'phc-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'calcium' => $data['calcium'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // new chloride
    public static function newChloride($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_chloride')->insert([
            'phc_id' => 'phc-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'chloride' => $data['chloride'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // new creatinine
    public static function newCreatinine($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_creatinine')->insert([
            'phc_id' => 'phc-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'creatinine' => $data['creatinine'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // new newHDL
    public static function newHDL($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_hdl')->insert([
            'phh_id' => 'phh-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'high_density_lipoproteins' => $data['hdl'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // new newLDL
    public static function newLDL($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_ldl')->insert([
            'phl_id' => 'phl-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'low_density_lipoprotein' => $data['ldl'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // new Lithium
    public static function newLithium($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_lithium')->insert([
            'phl_id' => 'phl-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'lithium' => $data['lithium'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // new Magnessium
    public static function newMagnesium($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_magnessium')->insert([
            'phm_id' => 'phm-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'magnessium' => $data['magnesium'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // new potassium
    public static function newPotassium($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_potassium')->insert([
            'php_id' => 'php-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'potassium' => $data['potassium'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function newProtein($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_protein')->insert([
            'php_id' => 'php-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'protein' => $data['protein'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function newSodium($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_history_sodium')->insert([
            'phs_id' => 'phs-' . rand(0, 99999),
            'patient_id' => $data['patient_id'],
            'sodium' => $data['sodium'],
            'added_by' => $data['user_id'],
            'adder_type' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getAllTest($data)
    {
        return DB::connection('mysql')->table('laboratory_test')
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->where('status', 1)
            ->get();
    }

    public static function saveNewTest($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')->table('laboratory_test')->insert([
            'lt_id' => 'lt-' . rand(0, 99999),
            'laboratory_id' => (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id,
            'laboratory_test' => $data['test'],
            'department' => $data['dept'],
            'laboratory_rate' => $data['rate'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function editTest($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')->table('laboratory_test')
            ->where('lt_id', $data['id'])
            ->update([
                // 'laboratory_test' => $data['test'],
                'laboratory_rate' => $data['rate'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getUnpaidLabOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('encoder_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('doctors_id', _Doctor::getDoctorsId($data['user_id'])->doctors_id)
            ->where('bill_from', 'laboratory')
            ->get();
    }

    // new laboratory modules

    public static function getOrderHemathologyNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hematology')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_hematology.patient_id')
            ->select('laboratory_hematology.*', 'patients.*', 'laboratory_hematology.created_at as date_ordered')
            ->where('laboratory_hematology.patient_id', $data['patient_id'])
            ->where('laboratory_hematology.order_status', 'new-order-paid')
            ->groupBy('laboratory_hematology.trace_number')
            ->get();
    }

    public static function getOrderHemathologyNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hematology')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_hematology.patient_id')
            ->select('laboratory_hematology.*', 'patients.*')
            ->where('laboratory_hematology.trace_number', $data['trace_number'])
            ->get();
    }

    public static function saveHemaOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_hematology')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'hemoglobin' => empty($data['hemoglobin'][$i]) ? null : $data['hemoglobin'][$i],
                    'hemoglobin_remarks' => empty($data['hemoglobin_remarks'][$i]) ? null : $data['hemoglobin_remarks'][$i],

                    'hematocrit' => empty($data['hematocrit'][$i]) ? null : $data['hematocrit'][$i],
                    'hematocrit_remarks' => empty($data['hematocrit_remarks'][$i]) ? null : $data['hematocrit_remarks'][$i],

                    'rbc' => empty($data['rbc']) ? null : $data['rbc'][$i],
                    'rbc_remarks' => empty($data['rbc_remarks']) ? null : $data['rbc_remarks'][$i],

                    'wbc' => empty($data['wbc'][$i]) ? null : $data['wbc'][$i],
                    'wbc_remarks' => empty($data['wbc_remarks'][$i]) ? null : $data['wbc_remarks'][$i],

                    'platelet_count' => empty($data['platelet_count'][$i]) ? null : $data['platelet_count'][$i],
                    'platelet_count_remarks' => empty($data['platelet_count_remarks'][$i]) ? null : $data['platelet_count_remarks'][$i],

                    'differential_count' => empty($data['differential_count'][$i]) ? null : $data['differential_count'][$i],
                    'differential_count_remarks' => empty($data['differential_count_remarks'][$i]) ? null : $data['differential_count_remarks'][$i],

                    'neutrophil' => empty($data['neutrophil'][$i]) ? null : $data['neutrophil'][$i],
                    'neutrophil_remarks' => empty($data['neutrophil_remarks'][$i]) ? null : $data['neutrophil_remarks'][$i],

                    'lymphocyte' => empty($data['lymphocyte'][$i]) ? null : $data['lymphocyte'][$i],
                    'lymphocyte_remarks' => empty($data['lymphocyte_remarks'][$i]) ? null : $data['lymphocyte_remarks'][$i],

                    'monocyte' => empty($data['monocyte'][$i]) ? null : $data['monocyte'][$i],
                    'monocyte_remarks' => empty($data['monocyte_remarks'][$i]) ? null : $data['monocyte_remarks'][$i],

                    'eosinophil' => empty($data['eosinophil'][$i]) ? null : $data['eosinophil'][$i],
                    'eosinophil_remarks' => empty($data['eosinophil_remarks'][$i]) ? null : $data['eosinophil_remarks'][$i],

                    'basophil' => empty($data['basophil'][$i]) ? null : $data['basophil'][$i],
                    'basophil_remarks' => empty($data['basophil_remarks'][$i]) ? null : $data['basophil_remarks'][$i],

                    'bands' => empty($data['bands'][$i]) ? null : $data['bands'][$i],
                    'bands_remarks' => empty($data['bands_remarks'][$i]) ? null : $data['bands_remarks'][$i],

                    'abo_blood_type_and_rh_type' => empty($data['abo_blood_type_and_rh_type'][$i]) ? null : $data['abo_blood_type_and_rh_type'][$i],
                    'abo_blood_type_and_rh_type_remarks' => empty($data['abo_blood_type_and_rh_type_remarks'][$i]) ? null : $data['abo_blood_type_and_rh_type_remarks'][$i],

                    'bleeding_time' => empty($data['bleeding_time'][$i]) ? null : $data['bleeding_time'][$i],
                    'bleeding_time_remarks' => empty($data['bleeding_time_remarks'][$i]) ? null : $data['bleeding_time_remarks'][$i],

                    'clotting_time' => empty($data['clotting_time'][$i]) ? null : $data['clotting_time'][$i],
                    'clotting_time_remarks' => empty($data['clotting_time_remarks'][$i]) ? null : $data['clotting_time_remarks'][$i],

                    'mcv' => empty($data['mcv'][$i]) ? null : $data['mcv'][$i],
                    'mcv_remarks' => empty($data['mcv_remarks'][$i]) ? null : $data['mcv_remarks'][$i],

                    'mch' => empty($data['mch'][$i]) ? null : $data['mch'][$i],
                    'mch_remarks' => empty($data['mch_remarks'][$i]) ? null : $data['mch_remarks'][$i],

                    'mchc' => empty($data['mchc'][$i]) ? null : $data['mchc'][$i],
                    'mchc_remarks' => empty($data['mchc_remarks'][$i]) ? null : $data['mchc_remarks'][$i],

                    'rdw' => empty($data['rdw'][$i]) ? null : $data['rdw'][$i],
                    'rdw_remarks' => empty($data['rdw_remarks'][$i]) ? null : $data['rdw_remarks'][$i],

                    'mpv' => empty($data['mpv'][$i]) ? null : $data['mpv'][$i],
                    'mpv_remarks' => empty($data['mpv_remarks'][$i]) ? null : $data['mpv_remarks'][$i],

                    'pdw' => empty($data['pdw'][$i]) ? null : $data['pdw'][$i],
                    'pdw_remarks' => empty($data['pdw_remarks'][$i]) ? null : $data['pdw_remarks'][$i],

                    'pct' => empty($data['pct'][$i]) ? null : $data['pct'][$i],
                    'pct_remarks' => empty($data['pct_remarks'][$i]) ? null : $data['pct_remarks'][$i],

                    'blood_typing_with_rh' => empty($data['blood_typing_with_rh'][$i]) ? null : $data['blood_typing_with_rh'][$i],
                    'blood_typing_with_rh_remarks' => empty($data['blood_typing_with_rh_remarks'][$i]) ? null : $data['blood_typing_with_rh_remarks'][$i],

                    'ct_bt' => empty($data['ct_bt'][$i]) ? null : $data['ct_bt'][$i],
                    'ct_bt_remarks' => empty($data['ct_bt_remarks'][$i]) ? null : $data['ct_bt_remarks'][$i],

                    'esr' => empty($data['esr'][$i]) ? null : $data['esr'][$i],
                    'esr_remarks' => empty($data['esr_remarks'][$i]) ? null : $data['esr_remarks'][$i],

                    'ferritin' => empty($data['ferritin'][$i]) ? null : $data['ferritin'][$i],
                    'ferritin_remarks' => empty($data['ferritin_remarks'][$i]) ? null : $data['ferritin_remarks'][$i],

                    'aptt' => empty($data['aptt'][$i]) ? null : $data['aptt'][$i],
                    'aptt_remarks' => empty($data['aptt_remarks'][$i]) ? null : $data['aptt_remarks'][$i],

                    'peripheral_smear' => empty($data['peripheral_smear'][$i]) ? null : $data['peripheral_smear'][$i],
                    'peripheral_smear_remarks' => empty($data['peripheral_smear_remarks'][$i]) ? null : $data['peripheral_smear_remarks'][$i],

                    'protime' => empty($data['protime'][$i]) ? null : $data['protime'][$i],
                    'protime_remarks' => empty($data['protime_remarks'][$i]) ? null : $data['protime_remarks'][$i],

                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        } 

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function setHemaOrderPending($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hematology')
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 1,
                'is_processed' => 0,
                'is_pending_reason' => $data['reason'],
                'is_pending_date' => date('Y-m-d H:i:s'),
                'is_pending_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getCompleteHemathologyOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_hematology.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_hematology WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);


        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hematology')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_hematology.patient_id')
        //     ->select('laboratory_hematology.*', 'patients.*')
        //     ->where('laboratory_hematology.trace_number', $data['trace_number'])
        //     ->where('laboratory_hematology.order_status', 'completed')
        //     ->get();
    }

    public static function setHemaOrderProcessed($data)
    {

        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        // loop or order by order id from trace number
        $ordersItems = DB::table('laboratory_items_laborder')
            ->select('item_id')
            ->where('order_id', $data['order_id'])
            ->get();

        foreach ($ordersItems as $key => $x) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT _total_batch_qty_in - _total_batch_qty_out ) as _total_batch_qty_available,
                        (SELECT _total_qty_in - _total_qty_out ) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='$x->item_id'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => 1,
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hematology')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getOrderSorologyNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_sorology')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_sorology.patient_id')
            ->select('laboratory_sorology.*', 'patients.*', 'laboratory_sorology.created_at as date_ordered')
            ->where('laboratory_sorology.patient_id', $data['patient_id'])
            ->where('laboratory_sorology.order_status', 'new-order-paid')
            ->groupBy('laboratory_sorology.trace_number')
            ->get();
    }

    public static function getOrderSorologyNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_sorology')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_sorology.patient_id', )
            ->select('laboratory_sorology.*', 'patients.*')
            ->where('laboratory_sorology.trace_number', $data['trace_number'])
            ->get();
    }

    public static function saveSorologyOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_sorology')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'hbsag' => empty($data['hbsag'][$i]) ? null : $data['hbsag'][$i],
                    'hav' => empty($data['hav'][$i]) ? null : $data['hav'][$i],
                    'hcv' => empty($data['hcv'][$i]) ? null : $data['hcv'][$i],
                    'anti_hbc_igm' => empty($data['anti_hbc_igm'][$i]) ? null : $data['anti_hbc_igm'][$i],
                    'beta_hcg_quali' => empty($data['beta_hcg_quali'][$i]) ? null : $data['beta_hcg_quali'][$i],
                    'h_pylori' => empty($data['h_pylori'][$i]) ? null : $data['h_pylori'][$i],
                    'typhidot' => empty($data['typhidot'][$i]) ? null : $data['typhidot'][$i], 
                    'syphilis_test_result' => empty($data['syphilis_test_result'][$i]) ? null : $data['syphilis_test_result'][$i],
                    'hact' => empty($data['hact'][$i]) ? null : $data['hact'][$i],
                    'ana' => empty($data['ana'][$i]) ? null : $data['ana'][$i],
                    'dengue_test_result' => empty($data['dengue_test_result'][$i]) ? null : $data['dengue_test_result'][$i],
                    'remarks' => $data['remarks'],
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        } 

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'category' => 'laboratory',
            'department' => 'serology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new serology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function setSorologyOrderProcessed($data)
    {

        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        // loop or order by order id from trace number
        $ordersItems = DB::table('laboratory_items_laborder')
            ->select('item_id')
            ->where('order_id', $data['order_id'])
            ->get();

        foreach ($ordersItems as $key => $x) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                            (SELECT _total_batch_qty_in - _total_batch_qty_out ) as _total_batch_qty_available,
                            (SELECT _total_qty_in - _total_qty_out ) as _total_qty_available
                            FROM laboratory_items_monitoring WHERE item_id ='$x->item_id'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => 1,
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_sorology')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function setSorologyOrderPending($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_sorology')
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 1,
                'is_processed' => 0,
                'is_pending_reason' => $data['reason'],
                'is_pending_date' => date('Y-m-d H:i:s'),
                'is_pending_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getCompleteSoroOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_sorology.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_sorology WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_sorology')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_sorology.patient_id')
        //     ->select('laboratory_sorology.*', 'patients.*')
        //     ->where('laboratory_sorology.trace_number', $data['trace_number'])
        //     ->where('laboratory_sorology.order_status', 'completed')
        //     ->get();
    }

    // clinical microscopy
    public static function getOrderClinicalMicroscopyNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_microscopy')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_microscopy.patient_id')
            ->select('laboratory_microscopy.*', 'patients.*', 'laboratory_microscopy.created_at as date_ordered')
            ->where('laboratory_microscopy.patient_id', $data['patient_id'])
            ->where('laboratory_microscopy.order_status', 'new-order-paid')
            ->groupBy('laboratory_microscopy.trace_number')
            ->get();
    }

    public static function getOrderClinicalMicroscopyNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_microscopy')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_microscopy.patient_id')
            ->select('laboratory_microscopy.*', 'patients.*')
            ->where('laboratory_microscopy.trace_number', $data['trace_number'])
            ->get();
    }

    public static function setClinicMicrosopyOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        // loop or order by order id from trace number
        $ordersItems = DB::table('laboratory_items_laborder')
            ->select('item_id')
            ->where('order_id', $data['order_id'])
            ->get();

        foreach ($ordersItems as $key => $x) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                    (SELECT _total_batch_qty_in - _total_batch_qty_out ) as _total_batch_qty_available,
                    (SELECT _total_qty_in - _total_qty_out ) as _total_qty_available
                    FROM laboratory_items_monitoring WHERE item_id ='$x->item_id'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => 1,
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_microscopy')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function setClinicMicrosopyOrderPending($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_microscopy')
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 1,
                'is_processed' => 0,
                'is_pending_reason' => $data['reason'],
                'is_pending_date' => date('Y-m-d H:i:s'),
                'is_pending_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveClinicalMicroscopyOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];
        $sample = [];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_microscopy')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'chemical_test_color' => empty($data['chemecal_test'][$i]) ? null : (empty($data['color'][$i]) ? null : $data['color'][$i]),
                    'chemical_test_transparency' => empty($data['chemecal_test'][$i]) ? null : (empty($data['transparency'][$i]) ? null : $data['transparency'][$i]),
                    'chemical_test_ph' => empty($data['chemecal_test'][$i]) ? null : (empty($data['ph'][$i]) ? null : $data['ph'][$i]),
                    'chemical_test_spicific_gravity' => empty($data['chemecal_test'][$i]) ? null : (empty($data['specific_gravity'][$i]) ? null : $data['specific_gravity'][$i]),
                    'chemical_test_glucose' => empty($data['chemecal_test'][$i]) ? null : (empty($data['glucose'][$i]) ? null : $data['glucose'][$i]),
                    'chemical_test_albumin' => empty($data['chemecal_test'][$i]) ? null : (empty($data['albumin'][$i]) ? null : $data['albumin'][$i]),

                    'microscopic_test_squamous' => empty($data['microscopic_test'][$i]) ? null : (empty($data['squamous'][$i]) ? null : $data['squamous'][$i]),
                    'microscopic_test_pus' => empty($data['microscopic_test'][$i]) ? null : (empty($data['pus'][$i]) ? null : $data['pus'][$i]),
                    'microscopic_test_redblood' => empty($data['microscopic_test'][$i]) ? null : (empty($data['redblood'][$i]) ? null : $data['redblood'][$i]),
                    'microscopic_test_hyaline' => empty($data['microscopic_test'][$i]) ? null : (empty($data['hyaline'][$i]) ? null : $data['hyaline'][$i]),
                    'microscopic_test_wbc' => empty($data['microscopic_test'][$i]) ? null : (empty($data['wbc_cast'][$i]) ? null : $data['wbc_cast'][$i]),
                    'microscopic_test_rbc' => empty($data['microscopic_test'][$i]) ? null : (empty($data['rbc_cast'][$i]) ? null : $data['rbc_cast'][$i]),
                    'microscopic_test_fine_granular' => empty($data['microscopic_test'][$i]) ? null : (empty($data['fine_granualar'][$i]) ? null : $data['fine_granualar'][$i]),
                    'microscopic_test_coarse_granular' => empty($data['microscopic_test'][$i]) ? null : (empty($data['coarse_granualar'][$i]) ? null : $data['coarse_granualar'][$i]),
                    'microscopic_test_calcium_oxalate' => empty($data['microscopic_test'][$i]) ? null : (empty($data['crystal_oxalate'][$i]) ? null : $data['crystal_oxalate'][$i]),
                    'microscopic_test_triple_phospahte' => empty($data['microscopic_test'][$i]) ? null : (empty($data['triple_phosphate'][$i]) ? null : $data['triple_phosphate'][$i]),
                    'microscopic_test_leucine_tyrosine' => empty($data['microscopic_test'][$i]) ? null : (empty($data['leucine_tyrocine'][$i]) ? null : $data['leucine_tyrocine'][$i]),
                    'microscopic_test_ammonium_biurate' => empty($data['microscopic_test'][$i]) ? null : (empty($data['ammoniume'][$i]) ? null : $data['ammoniume'][$i]),
                    'microscopic_test_amorphous_urates' => empty($data['microscopic_test'][$i]) ? null : (empty($data['amorphous_urates'][$i]) ? null : $data['amorphous_urates'][$i]),
                    'microscopic_test_amorphous_phosphates' => empty($data['microscopic_test'][$i]) ? null : (empty($data['amorphous_phosphate'][$i]) ? null : $data['amorphous_phosphate'][$i]),
                    'microscopic_test_uricacid' => empty($data['microscopic_test'][$i]) ? null : (empty($data['uric_acid'][$i]) ? null : $data['uric_acid'][$i]),
                    'microscopic_test_mucus_thread' => empty($data['microscopic_test'][$i]) ? null : (empty($data['mucus_thread'][$i]) ? null : $data['mucus_thread'][$i]),
                    'microscopic_test_bacteria' => empty($data['microscopic_test'][$i]) ? null : (empty($data['bacteria'][$i]) ? null : $data['bacteria'][$i]),
                    'microscopic_test_yeast' => empty($data['microscopic_test'][$i]) ? null : (empty($data['yeast'][$i]) ? null : $data['yeast'][$i]),

                    'pregnancy_test_hcg_result' => empty($data['pregnancy_test_enable'][$i]) ? null : (empty($data['pregnancy_test'][$i]) ? null : $data['pregnancy_test'][$i]),

                    'micral_test_result' => empty($data['micral_test_result'][$i]) ? null : strip_tags($data['micral_test_result'][$i]),
                    'occult_blood_test_result' => empty($data['occult_blood_test_result'][$i]) ? null : strip_tags($data['occult_blood_test_result'][$i]),
                    'seminalysis_result' => empty($data['seminalysis_result'][$i]) ? null : strip_tags($data['seminalysis_result'][$i]),


                    'result_remarks' => $data['result_remarks'],
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        } 

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'clinical-microscopy',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new clinical microscopy test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteClinicalMicroscopyOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_microscopy.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_microscopy WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);


        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_microscopy')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_microscopy.patient_id')
        //     ->select('laboratory_microscopy.*', 'patients.*')
        //     ->where('laboratory_microscopy.trace_number', $data['trace_number'])
        //     ->where('laboratory_microscopy.order_status', 'completed')
        //     ->get();
    }

    public static function getOrderFecalAnalysisNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_fecal_analysis')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_fecal_analysis.patient_id')
            ->select('laboratory_fecal_analysis.*', 'patients.*', 'laboratory_fecal_analysis.created_at as date_ordered')
            ->where('laboratory_fecal_analysis.patient_id', $data['patient_id'])
            ->where('laboratory_fecal_analysis.order_status', 'new-order-paid')
            ->groupBy('laboratory_fecal_analysis.order_id')
            ->get();
    }

    public static function getOrderFecalAnalysisNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_fecal_analysis')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_fecal_analysis.patient_id')
            ->select('laboratory_fecal_analysis.*', 'patients.*')
            ->where('laboratory_fecal_analysis.trace_number', $data['trace_number'])
            ->get();
    }

    public static function setFecalAnalysisOrderProcessed($data)
    {

        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        // loop or order by order id from trace number
        $ordersItems = DB::table('laboratory_items_laborder')
            ->select('item_id')
            ->where('order_id', $data['order_id'])
            ->get();

        foreach ($ordersItems as $key => $x) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                    (SELECT _total_batch_qty_in - _total_batch_qty_out ) as _total_batch_qty_available,
                    (SELECT _total_qty_in - _total_qty_out ) as _total_qty_available
                    FROM laboratory_items_monitoring WHERE item_id ='$x->item_id'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => 1,
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_fecal_analysis')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function setFecalAnalysisOrderPending($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_fecal_analysis')
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 1,
                'is_processed' => 0,
                'is_pending_reason' => $data['reason'],
                'is_pending_date' => date('Y-m-d H:i:s'),
                'is_pending_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveFecalAnalysisOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_fecal_analysis')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'cellular_elements_color' => empty($data['color'][$i]) ? null : $data['color'][$i],
                    'cellular_elements_consistency' => empty($data['consistency'][$i]) ? null : $data['consistency'][$i],
                    'cellular_elements_pus' => empty($data['pus'][$i]) ? null : $data['pus'][$i],
                    'cellular_elements_rbc' => empty($data['rbc'][$i]) ? null : $data['rbc'][$i],
                    'cellular_elements_fat_globules' => empty($data['fat'][$i]) ? null : $data['fat'][$i],
                    'cellular_elements_occultblood' => empty($data['occult'][$i]) ? null : $data['occult'][$i],
                    'cellular_elements_bacteria' => empty($data['bacteria'][$i]) ? null : $data['bacteria'][$i],
                    'cellular_elements_result' => empty($data['fecal_result'][$i]) ? null : $data['fecal_result'][$i],

                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }       

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'fecal-analysis',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new fecal analysis test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteFecalAnalysisOrderDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_fecal_analysis')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_fecal_analysis.patient_id')
            ->select('laboratory_fecal_analysis.*', 'patients.*')
            ->where('laboratory_fecal_analysis.trace_number', $data['trace_number'])
            ->where('laboratory_fecal_analysis.order_status', 'completed')
            ->get();
    }

    //clinical chemistry
    public static function getOrderClinicalChemistryNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_chemistry')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_chemistry.patient_id')
            ->select('laboratory_chemistry.*', 'patients.*', 'laboratory_chemistry.created_at as date_ordered')
            ->where('laboratory_chemistry.patient_id', $data['patient_id'])
            ->where('laboratory_chemistry.order_status', 'new-order-paid')
            ->groupBy('laboratory_chemistry.trace_number')
            ->get();
    }

    public static function getOrderClinicalChemistryNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_chemistry')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_chemistry.patient_id')
            ->select('laboratory_chemistry.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip')
            ->where('laboratory_chemistry.trace_number', $data['trace_number'])
            ->get();
    }

    public static function setClinicChemistryOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        // loop or order by order id from trace number
        $ordersItems = DB::table('laboratory_items_laborder')
            ->select('item_id')
            ->where('order_id', $data['order_id'])
            ->get();

        foreach ($ordersItems as $key => $x) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                    (SELECT _total_batch_qty_in - _total_batch_qty_out ) as _total_batch_qty_available,
                    (SELECT _total_qty_in - _total_qty_out ) as _total_qty_available
                    FROM laboratory_items_monitoring WHERE item_id ='$x->item_id'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => 1,
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_chemistry')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function setClinicChemistryOrderPending($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_chemistry')
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 1,
                'is_processed' => 0,
                'is_pending_reason' => $data['reason'],
                'is_pending_date' => date('Y-m-d H:i:s'),
                'is_pending_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveClinicalChemistryOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_chemistry')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'glucose' => empty($data['glucose'][$i]) ? null : $data['glucose'][$i],
                    'glucose_remarks' => empty($data['glucose_remarks'][$i]) ? null : $data['glucose_remarks'][$i],
                    'creatinine' => empty($data['creatinine'][$i]) ? null : $data['creatinine'][$i],
                    'creatinine_remarks' => empty($data['creatinine_remarks'][$i]) ? null : $data['creatinine_remarks'][$i],
                    'uric_acid' => empty($data['uric_acid'][$i]) ? null : $data['uric_acid'][$i],
                    'uric_acid_remarks' => empty($data['uric_acid_remarks'][$i]) ? null : $data['uric_acid_remarks'][$i],
                    'cholesterol' => empty($data['cholesterol'][$i]) ? null : $data['cholesterol'][$i],
                    'cholesterol_remarks' => empty($data['cholesterol_remarks'][$i]) ? null : $data['cholesterol_remarks'][$i],
                    'triglyceride' => empty($data['triglyceride'][$i]) ? null : $data['triglyceride'][$i],
                    'triglyceride_remarks' => empty($data['triglyceride_remarks'][$i]) ? null : $data['triglyceride_remarks'][$i],
                    'hdl_cholesterol' => empty($data['hdl_cholesterol'][$i]) ? null : $data['hdl_cholesterol'][$i],
                    'hdl_cholesterol_remarks' => empty($data['hdl_cholesterol_remarks'][$i]) ? null : $data['hdl_cholesterol_remarks'][$i],
                    'ldl_cholesterol' => empty($data['ldl_cholesterol'][$i]) ? null : $data['ldl_cholesterol'][$i],
                    'ldl_cholesterol_remarks' => empty($data['ldl_cholesterol_remarks'][$i]) ? null : $data['ldl_cholesterol_remarks'][$i],
                    'sgot' => empty($data['sgot'][$i]) ? null : $data['sgot'][$i],
                    'sgot_remarks' => empty($data['sgot_remarks'][$i]) ? null : $data['sgot_remarks'][$i],
                    'sgpt' => empty($data['sgpt'][$i]) ? null : $data['sgpt'][$i],
                    'sgpt_remarks' => empty($data['sgpt_remarks'][$i]) ? null : $data['sgpt_remarks'][$i],
                    'bun' => empty($data['bun'][$i]) ? null : $data['bun'][$i],
                    'bun_remarks' => empty($data['bun_remarks'][$i]) ? null : $data['bun_remarks'][$i],
                    'soduim' => empty($data['soduim'][$i]) ? null : $data['soduim'][$i],
                    'soduim_remarks' => empty($data['soduim_remarks'][$i]) ? null : $data['soduim_remarks'][$i],
                    'potassium' => empty($data['potassium'][$i]) ? null : $data['potassium'][$i],
                    'potassium_remarks' => empty($data['potassium_remarks'][$i]) ? null : $data['potassium_remarks'][$i],
                    'hba1c' => empty($data['hba1c'][$i]) ? null : $data['hba1c'][$i],
                    'hba1c_remarks' => empty($data['hba1c_remarks'][$i]) ? null : $data['hba1c_remarks'][$i],
                    'alkaline_phosphatase' => empty($data['alkaline_phosphatase'][$i]) ? null : $data['alkaline_phosphatase'][$i],
                    'alkaline_phosphatase_remarks' => empty($data['alkaline_phosphatase_remarks'][$i]) ? null : $data['alkaline_phosphatase_remarks'][$i],
                    'albumin' => empty($data['albumin'][$i]) ? null : $data['albumin'][$i],
                    'albumin_remarks' => empty($data['albumin_remarks'][$i]) ? null : $data['albumin_remarks'][$i],
                    'calcium' => empty($data['calcium'][$i]) ? null : $data['calcium'][$i],
                    'calcium_remarks' => empty($data['calcium_remarks'][$i]) ? null : $data['calcium_remarks'][$i],
                    'magnesium' => empty($data['magnesium'][$i]) ? null : $data['magnesium'][$i],
                    'magnesium_remarks' => empty($data['magnesium_remarks'][$i]) ? null : $data['magnesium_remarks'][$i],
                    'chloride' => empty($data['chloride'][$i]) ? null : $data['chloride'][$i],
                    'chloride_remarks' => empty($data['chloride_remarks'][$i]) ? null : $data['chloride_remarks'][$i],
                    'fbs' => empty($data['fbs'][$i]) ? null : $data['fbs'][$i],
                    'fbs_remarks' => empty($data['fbs_remarks'][$i]) ? null : $data['fbs_remarks'][$i],

                    'serum_uric_acid' => empty($data['serum_uric_acid'][$i]) ? null : $data['serum_uric_acid'][$i],
                    'serum_uric_acid_remarks' => empty($data['serum_uric_acid_remarks'][$i]) ? null : $data['serum_uric_acid_remarks'][$i],

                    'lipid_profile' => empty($data['lipid_profile'][$i]) ? null : $data['lipid_profile'][$i],
                    'lipid_profile_remarks' => empty($data['lipid_profile_remarks'][$i]) ? null : $data['lipid_profile_remarks'][$i],

                    'ldh' => empty($data['ldh'][$i]) ? null : $data['ldh'][$i],
                    'ldh_remarks' => empty($data['ldh_remarks'][$i]) ? null : $data['ldh_remarks'][$i],

                    'tpag_ratio' => empty($data['tpag_ratio'][$i]) ? null : $data['tpag_ratio'][$i],
                    'tpag_ratio_remarks' => empty($data['tpag_ratio_remarks'][$i]) ? null : $data['tpag_ratio_remarks'][$i],

                    'bilirubin' => empty($data['bilirubin'][$i]) ? null : $data['bilirubin'][$i],
                    'bilirubin_remarks' => empty($data['bilirubin_remarks'][$i]) ? null : $data['bilirubin_remarks'][$i],

                    'total_protein' => empty($data['total_protein'][$i]) ? null : $data['total_protein'][$i],
                    'total_protein_remarks' => empty($data['total_protein_remarks'][$i]) ? null : $data['total_protein_remarks'][$i],

                    'potassium_kplus' => empty($data['potassium_kplus'][$i]) ? null : $data['potassium_kplus'][$i],
                    'potassium_kplus_remarks' => empty($data['potassium_kplus_remarks'][$i]) ? null : $data['potassium_kplus_remarks'][$i],

                    'na_plus_kplus' => empty($data['na_plus_kplus'][$i]) ? null : $data['na_plus_kplus'][$i],
                    'na_plus_kplus_remarks' => empty($data['na_plus_kplus_remarks'][$i]) ? null : $data['na_plus_kplus_remarks'][$i],

                    'ggt' => empty($data['ggt'][$i]) ? null : $data['ggt'][$i],
                    'ggt_remarks' => empty($data['ggt_remarks'][$i]) ? null : $data['ggt_remarks'][$i],

                    'cholinesterase' => empty($data['cholinesterase'][$i]) ? null : $data['cholinesterase'][$i],
                    'cholinesterase_remarks' => empty($data['cholinesterase_remarks'][$i]) ? null : $data['cholinesterase_remarks'][$i],

                    'phosphorous' => empty($data['phosphorous'][$i]) ? null : $data['phosphorous'][$i],
                    'phosphorous_remarks' => empty($data['phosphorous_remarks'][$i]) ? null : $data['phosphorous_remarks'][$i],

                    'rbs' => empty($data['rbs'][$i]) ? null : $data['rbs'][$i],
                    'rbs_remarks' => empty($data['rbs_remarks'][$i]) ? null : $data['rbs_remarks'][$i],

                    'vldl' => empty($data['vldl'][$i]) ? null : $data['vldl'][$i],
                    'vldl_remarks' => empty($data['vldl_remarks'][$i]) ? null : $data['vldl_remarks'][$i],

                    'rbc_cholinesterase' => empty($data['rbc_cholinesterase'][$i]) ? null : $data['rbc_cholinesterase'][$i],
                    'rbc_cholinesterase_remarks' => empty($data['rbc_cholinesterase_remarks'][$i]) ? null : $data['rbc_cholinesterase_remarks'][$i],

                    'crp' => empty($data['crp'][$i]) ? null : $data['crp'][$i],
                    'crp_remarks' => empty($data['crp_remarks'][$i]) ? null : $data['crp_remarks'][$i],

                    'pro_calcitonin' => empty($data['pro_calcitonin'][$i]) ? null : $data['pro_calcitonin'][$i],
                    'pro_calcitonin_crp_remarks' => empty($data['pro_calcitonin_crp_remarks'][$i]) ? null : $data['pro_calcitonin_crp_remarks'][$i],

                    'ogct_take_one_50grm_baseline' => empty($data['ogct_take_one_50grm_baseline'][$i]) ? null : $data['ogct_take_one_50grm_baseline'][$i],
                    'ogct_take_one_50grm_first_hour' => empty($data['ogct_take_one_50grm_first_hour'][$i]) ? null : $data['ogct_take_one_50grm_first_hour'][$i],
                    'ogct_take_one_50grm_second_hour' => empty($data['ogct_take_one_50grm_second_hour'][$i]) ? null : $data['ogct_take_one_50grm_second_hour'][$i],

                    'ogct_take_one_75grm_baseline' => empty($data['ogct_take_one_75grm_baseline'][$i]) ? null : $data['ogct_take_one_75grm_baseline'][$i],
                    'ogct_take_one_75grm_first_hour' => empty($data['ogct_take_one_75grm_first_hour'][$i]) ? null : $data['ogct_take_one_75grm_first_hour'][$i],
                    'ogct_take_one_75grm_second_hour' => empty($data['ogct_take_one_75grm_second_hour'][$i]) ? null : $data['ogct_take_one_75grm_second_hour'][$i],

                    'ogct_take_two_100grm_baseline' => empty($data['ogct_take_two_100grm_baseline'][$i]) ? null : $data['ogct_take_two_100grm_baseline'][$i],
                    'ogct_take_two_100grm_first_hour' => empty($data['ogct_take_two_100grm_first_hour'][$i]) ? null : $data['ogct_take_two_100grm_first_hour'][$i],
                    'ogct_take_two_100grm_second_hour' => empty($data['ogct_take_two_100grm_second_hour'][$i]) ? null : $data['ogct_take_two_100grm_second_hour'][$i],

                    'ogct_take_two_75grm_baseline' => empty($data['ogct_take_two_75grm_baseline'][$i]) ? null : $data['ogct_take_two_75grm_baseline'][$i],
                    'ogct_take_two_75grm_first_hour' => empty($data['ogct_take_two_75grm_first_hour'][$i]) ? null : $data['ogct_take_two_75grm_first_hour'][$i],
                    'ogct_take_two_75grm_second_hour' => empty($data['ogct_take_two_75grm_second_hour'][$i]) ? null : $data['ogct_take_two_75grm_second_hour'][$i],

                    'ogct_take_three_100grm_baseline' => empty($data['ogct_take_three_100grm_baseline'][$i]) ? null : $data['ogct_take_three_100grm_baseline'][$i],
                    'ogct_take_three_100grm_first_hour' => empty($data['ogct_take_three_100grm_first_hour'][$i]) ? null : $data['ogct_take_three_100grm_first_hour'][$i],
                    'ogct_take_three_100grm_second_hour' => empty($data['ogct_take_three_100grm_second_hour'][$i]) ? null : $data['ogct_take_three_100grm_second_hour'][$i],

                    'ogct_take_four_100grm_baseline' => empty($data['ogct_take_four_100grm_baseline'][$i]) ? null : $data['ogct_take_four_100grm_baseline'][$i],
                    'ogct_take_four_100grm_first_hour' => empty($data['ogct_take_four_100grm_first_hour'][$i]) ? null : $data['ogct_take_four_100grm_first_hour'][$i],
                    'ogct_take_four_100grm_second_hour' => empty($data['ogct_take_four_100grm_second_hour'][$i]) ? null : $data['ogct_take_four_100grm_second_hour'][$i],

                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }


        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'clinical-chemistry',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new clinical test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteChemOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_chemistry.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_chemistry WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);


        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_chemistry')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_chemistry.patient_id')
        //     ->select('laboratory_chemistry.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
        //     ->where('laboratory_chemistry.trace_number', $data['trace_number'])
        //     ->where('laboratory_chemistry.order_status', 'completed')
        //     ->get();
    }

    public static function getNewOrderCountByDept($data)
    {

        $laboratoryid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        $query = "SELECT id,
            (SELECT count(id) from laboratory_hematology where laboratory_id = '$laboratoryid' and order_status = 'new-order-paid') as hemecount,
            (SELECT count(id) from laboratory_sorology where laboratory_id = '$laboratoryid' and order_status = 'new-order-paid') as serologycount,
            (SELECT count(id) from laboratory_microscopy where laboratory_id = '$laboratoryid' and order_status = 'new-order-paid') as microcount,
            (SELECT count(id) from laboratory_chemistry where laboratory_id = '$laboratoryid' and order_status = 'new-order-paid') as chemcount,
        (SELECT count(id) from laboratory_fecal_analysis where laboratory_id = '$laboratoryid' and order_status = 'new-order-paid') as fecalcount
        from laboratory_list where laboratory_id = '$laboratoryid' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getLaboratoryCompletedReport($data)
    {

        $laboratoryid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        if (!empty($data['date_from']) && $data['date_to']) {

            $from = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
            $to = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
            $dateFrom = date('Y-m-d H:i:s', strtotime($from));
            $dateTo = date('Y-m-d H:i:s', strtotime($to));

            return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table($data['table'])
                ->join('patients', 'patients.patient_id', '=', $data['table'] . '.' . 'patient_id')
                ->select($data['table'] . '.' . '*', 'patients.firstname as fname', 'patients.lastname as lname')
                ->where($data['table'] . '.' . 'laboratory_id', $laboratoryid)
                ->where($data['table'] . '.' . 'order_status', 'completed')
                ->groupBy($data['table'] . '.' . 'trace_number')
                ->where($data['table'] . '.' . 'created_at', '>=', $dateFrom)
                ->where($data['table'] . '.' . 'created_at', '<=', $dateTo)
                ->get();

        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table($data['table'])
            ->join('patients', 'patients.patient_id', '=', $data['table'] . '.' . 'patient_id')
            ->select($data['table'] . '.' . '*', 'patients.firstname as fname', 'patients.lastname as lname')
            ->where($data['table'] . '.' . 'laboratory_id', $laboratoryid)
            ->where($data['table'] . '.' . 'order_status', 'completed')
            ->groupBy($data['table'] . '.' . 'trace_number')
            ->get();
    }

    public static function getLabPatientsWithNewOrder($data)
    {
        $mngt = $data['management_id'];
        // $query = " SELECT patient_id as pid,
        //         (SELECT concat(lastname,', ',firstname) from patients where patient_id = pid) as patient_name,
        //         (SELECT image from patients where patient_id = pid) as patient_image,

        //         (SELECT count(id) from laboratory_hematology where patient_id = pid and order_status='new-order-paid') as count_hema,
        //         (SELECT count(id) from laboratory_cbc where patient_id = pid and order_status='new-order-paid') as count_hema_cbc,
        //         (SELECT count(id) from laboratory_sorology where patient_id = pid and order_status='new-order-paid') as count_reso,
        //         (SELECT count(id) from laboratory_microscopy where patient_id = pid and order_status='new-order-paid') as count_micro,
        //         (SELECT count(id) from laboratory_chemistry where patient_id = pid and order_status='new-order-paid') as count_chem,
        //         (SELECT count(id) from laboratory_fecal_analysis where patient_id = pid and order_status='new-order-paid') as count_fecal,
        //         (SELECT count(id) from laboratory_stooltest where patient_id = pid and order_status='new-order-paid') as count_stool,
        //         (SELECT count(id) from laboratory_urinalysis where patient_id = pid and order_status='new-order-paid') as count_urinalysis,
        //         (SELECT count(id) from laboratory_papsmear where patient_id = pid and order_status='new-order-paid') as count_papsmear,
        //         (SELECT count(id) from laboratory_oral_glucose where patient_id = pid and order_status='new-order-paid') as count_oral,
        //         (SELECT count(id) from laboratory_thyroid_profile where patient_id = pid and order_status='new-order-paid') as count_thyroid,
        //         (SELECT count(id) from laboratory_immunology where patient_id = pid and order_status='new-order-paid') as count_immunology,
        //         (SELECT count(id) from laboratory_miscellaneous where patient_id = pid and order_status='new-order-paid') as count_miscellaneous,
        //         (SELECT count(id) from laboratory_hepatitis_profile where patient_id = pid and order_status='new-order-paid') as count_hepatitis,
        //         (SELECT count(id) from laboratory_ecg where patient_id = pid and order_status='new-order-paid') as count_ecg,
        //         (SELECT count(id) from laboratory_medical_exam where patient_id = pid and order_status='new-order-paid') as count_medical_exam,

        //         (SELECT IFNULL(sum(count_hema + count_hema_cbc + count_reso + count_micro + count_chem + count_fecal + count_stool + count_urinalysis + count_papsmear + count_oral + count_thyroid + count_immunology + count_miscellaneous + count_hepatitis + count_ecg + count_medical_exam), 0)) as order_count

        // from cashier_patientbills_records where management_id = '$mngt' group by patient_id having order_count > 0 ";

        // $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);

        $query = " SELECT patient_id as pid,

                (SELECT concat(lastname,', ',firstname) from patients where patient_id = pid) as patient_name,
                (SELECT image from patients where patient_id = pid) as patient_image,

                (SELECT count(id) from laboratory_hematology where patient_id = pid and order_status='new-order-paid') as count_hema,
                (SELECT count(id) from laboratory_cbc where patient_id = pid and order_status='new-order-paid') as count_hema_cbc,
                (SELECT count(id) from laboratory_sorology where patient_id = pid and order_status='new-order-paid') as count_reso,
                (SELECT count(id) from laboratory_microscopy where patient_id = pid and order_status='new-order-paid') as count_micro,
                (SELECT count(id) from laboratory_chemistry where patient_id = pid and order_status='new-order-paid') as count_chem,
                (SELECT count(id) from laboratory_fecal_analysis where patient_id = pid and order_status='new-order-paid') as count_fecal,
                (SELECT count(id) from laboratory_stooltest where patient_id = pid and order_status='new-order-paid') as count_stool,
                (SELECT count(id) from laboratory_urinalysis where patient_id = pid and order_status='new-order-paid') as count_urinalysis,
                (SELECT count(id) from laboratory_papsmear where patient_id = pid and order_status='new-order-paid') as count_papsmear,
                (SELECT count(id) from laboratory_oral_glucose where patient_id = pid and order_status='new-order-paid') as count_oral,
                (SELECT count(id) from laboratory_thyroid_profile where patient_id = pid and order_status='new-order-paid') as count_thyroid,
                (SELECT count(id) from laboratory_immunology where patient_id = pid and order_status='new-order-paid') as count_immunology,
                (SELECT count(id) from laboratory_miscellaneous where patient_id = pid and order_status='new-order-paid') as count_miscellaneous,
                (SELECT count(id) from laboratory_hepatitis_profile where patient_id = pid and order_status='new-order-paid') as count_hepatitis,
                (SELECT count(id) from laboratory_ecg where patient_id = pid and order_status='new-order-paid') as count_ecg,
                -- (SELECT count(id) from laboratory_medical_exam where patient_id = pid and order_status='new-order-paid') as count_medical_exam, 
                (SELECT count(id) from laboratory_covid19_test where patient_id = pid and order_status='new-order-paid') as count_covid_test,
                (SELECT count(id) from laboratory_tumor_maker where patient_id = pid and order_status='new-order-paid') as count_tumor_maker,
                (SELECT count(id) from laboratory_drug_test where patient_id = pid and order_status='new-order-paid') as count_drug_test,

                (SELECT IFNULL(sum(count_hema + count_hema_cbc + count_reso + count_micro + count_chem + count_fecal + count_stool + count_urinalysis + count_papsmear + count_oral + count_thyroid + count_immunology + count_miscellaneous + count_hepatitis + count_ecg + count_covid_test + count_tumor_maker + count_drug_test), 0)) as order_count

        from patient_queue where management_id = '$mngt' AND type = 'laboratory' group by patient_id having order_count > 0";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getLaboratoryByPatientTest($data)
    {
        $patient_ID = _Patient::getPatientId($data['user_id']);
        $query = "SELECT laboratory_formheader.*, encoder_patientbills_records.order_id, encoder_patientbills_records.order_id as ORDERID, encoder_patientbills_records.id, encoder_patientbills_records.created_at, encoder_patientbills_records.doctors_id, encoder_patientbills_records.patient_id, laboratory_chemistry.glucose, laboratory_chemistry.creatinine, laboratory_chemistry.uric_acid, laboratory_chemistry.cholesterol, laboratory_chemistry.triglyceride, laboratory_chemistry.hdl_cholesterol, laboratory_chemistry.ldl_cholesterol, laboratory_chemistry.sgot, laboratory_chemistry.sgpt, laboratory_hematology.hemoglobin,laboratory_hematology.hematocrit, laboratory_hematology.rbc, laboratory_hematology.wbc, laboratory_hematology.platelet_count, laboratory_hematology.differential_count, laboratory_hematology.neutrophil, laboratory_hematology.lymphocyte, laboratory_hematology.monocyte, laboratory_hematology.eosinophil, laboratory_hematology.basophil, laboratory_hematology.bands, laboratory_hematology.abo_blood_type_and_rh_type, laboratory_hematology.bleeding_time, laboratory_hematology.clotting_time, laboratory_microscopy.chemical_test, laboratory_microscopy.microscopic_test, laboratory_microscopy.pregnancy_test_hcg, laboratory_sorology.hbsag, laboratory_sorology.hav,laboratory_sorology.hcv, laboratory_sorology.vdrl_rpr, laboratory_fecal_analysis.fecal_analysis,

            (SELECT IFNULL(count(is_view), 0) FROM patients_notification WHERE patients_notification.is_view = 0 AND patients_notification.order_id = encoder_patientbills_records.order_id ) as unreadCount,
            (SELECT IFNULL(count(id), 0) from encoder_patientbills_records where encoder_patientbills_records.bill_department = 'clinical-chemistry' and encoder_patientbills_records.order_id = ORDERID) as count_chem,
            (SELECT IFNULL(count(id), 0) from encoder_patientbills_records where encoder_patientbills_records.bill_department = 'hemathology' and encoder_patientbills_records.order_id = ORDERID) as count_hema,
            (SELECT IFNULL(count(id), 0) from encoder_patientbills_records where encoder_patientbills_records.bill_department = 'clinical-microscopy' and encoder_patientbills_records.order_id = ORDERID) as count_micro,
            (SELECT IFNULL(count(id), 0) from encoder_patientbills_records where encoder_patientbills_records.bill_department = 'serology' and encoder_patientbills_records.order_id = ORDERID) as count_sero,
            (SELECT IFNULL(count(id), 0) from encoder_patientbills_records where encoder_patientbills_records.bill_department = 'fecal-analysis' and encoder_patientbills_records.order_id = ORDERID) as count_feca

        from encoder_patientbills_records
        LEFT JOIN laboratory_chemistry  ON encoder_patientbills_records.order_id = laboratory_chemistry.order_id
        LEFT JOIN laboratory_hematology ON encoder_patientbills_records.order_id = laboratory_hematology.order_id
        LEFT JOIN laboratory_microscopy ON encoder_patientbills_records.order_id = laboratory_microscopy.order_id
        LEFT JOIN laboratory_sorology ON encoder_patientbills_records.order_id = laboratory_sorology.order_id
        LEFT JOIN laboratory_fecal_analysis ON encoder_patientbills_records.order_id = laboratory_fecal_analysis.order_id
        LEFT JOIN laboratory_formheader ON laboratory_formheader.management_id = encoder_patientbills_records.management_id
        WHERE encoder_patientbills_records.patient_id = '$patient_ID'
        GROUP BY encoder_patientbills_records.order_id ORDER BY encoder_patientbills_records.created_at DESC";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getLabFormHeader($data)
    {
        return DB::table('laboratory_formheader')->where('management_id', $data['management_id'])->first();
    }

    public static function hislabGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM laboratory_list WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hislabUpdatePersonalInfo($data)
    {
        return DB::table('laboratory_list')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'lic_no' => $data['lic_no'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hislabUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_list')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hislabUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hislabUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    // new laboratory

    public static function newLaboratoryItem($data)
    {

        date_default_timezone_set('Asia/Manila');

        $item_id = 'agent-' . rand(0, 9999) . time();

        return DB::table('laboratory_items')
            ->insert([
                'management_id' => $data['management_id'],
                'laboratory_id' => (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id,
                'item_id' => $item_id,
                'item' => $data['item'],
                'description' => $data['description'],
                'supplier' => $data['supplier'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function laboratoryOrderUsesThisItem($data)
    {
        return DB::table('laboratory_items_laborder')
            ->where('item_id', $data['item_id'])
            ->groupBy('order_id')
            ->get();
    }

    public static function laboratoryItemList($data)
    {

        // prev
        // $management_id = $data['management_id'];
        // $labid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        // $query = "SELECT *, item as label, item_id as value, item_id as itemId,
        // (SELECT IFNULL(count(id), 0) from laboratory_items_laborder where item_id = itemId) as _order_user
        // FROM laboratory_items WHERE management_id = '$management_id' and status = 1 and laboratory_id = '$labid' order by item asc";
        // $result = DB::connection('mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);


        $management_id = $data['management_id'];
        $labid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        $query = "SELECT *, description as label, item_id as value, item_id as itemId,
            (SELECT IFNULL(count(id), 0) from laboratory_items_laborder where item_id = itemId) as _order_user
        FROM laboratory_items WHERE management_id = '$management_id' and status = 1 and laboratory_id = '$labid' order by item asc";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }


    

    public static function laboratoryItemListByBatches($data)
    {
        $management_id = $data['management_id'];
        $itemid = $data['item_id'];
        $labid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        $query = "SELECT *, batch_number as value, batch_number as label
        FROM laboratory_items_monitoring WHERE management_id = '$management_id' and laboratory_id = '$labid' and item_id = '$itemid' group by batch_number";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function laboratoryItemDeliveryTemp($data)
    {
        date_default_timezone_set('Asia/Manila');

        $item_id = 'agent-' . rand(0, 9999) . time();

        return DB::table('laboratory_items_temp_dr')->insert([
            'management_id' => $data['management_id'],
            'laboratory_id' => (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id,
            'item_id' => $data['item_id'],
            'batch_number' => $data['batch_number'],
            'qty' => $data['qty'],
            'mfg_date' => date('Y-m-d', strtotime($data['mfg_date'])),
            'expiration_date' => date('Y-m-d', strtotime($data['expiration_date'])),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function laboratoryItemDeliveryTempList($data)
    {
        return DB::table('laboratory_items_temp_dr')
            ->join('laboratory_items', "laboratory_items.item_id", '=', "laboratory_items_temp_dr.item_id")
            ->select('laboratory_items.*', 'laboratory_items_temp_dr.*', 'laboratory_items_temp_dr.id as  temp_id')
            ->where('laboratory_items_temp_dr.management_id', $data['management_id'])
            ->where('laboratory_items_temp_dr.laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->get();
    }

    public static function laboratoryItemDeliveryTempRemove($data)
    {
        return DB::table('laboratory_items_temp_dr')
            ->where('id', $data['remove_id'])
            ->delete();
    }

    public static function laboratoryItemDeliveryTempProcess($data)
    {
        $items = DB::table('laboratory_items_temp_dr')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->get();

        $gtc = [];
        foreach ($items as $v) {
            $gtc[] = array(
                'lrm_id' => 'lrm-' . time() . rand(0, 99958),
                'item_id' => $v->item_id,
                'management_id' => $v->management_id,
                'laboratory_id' => $v->laboratory_id,
                'qty' => $v->qty,
                'dr_number' => $data['dr_number'],
                'batch_number' => $v->batch_number,
                'mfg_date' => $v->mfg_date,
                'expiration_date' => $v->expiration_date,
                'type' => 'IN',
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('laboratory_items_monitoring')->insert($gtc);

        return DB::table('laboratory_items_temp_dr')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->delete();
    }

    public static function getLaboratoryItemsInventory($data)
    {
        $management_id = $data['management_id'];
        $labid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        $query = "SELECT *, item_id as xid,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = xid and type = 'IN' ) as _total_qty_in,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = xid and type = 'OUT' ) as _total_qty_out,
        (SELECT _total_qty_in - _total_qty_out ) as _total_qty_available
        FROM laboratory_items WHERE management_id = '$management_id' and laboratory_id = '$labid' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getItemMonitoring($data)
    {
        $management_id = $data['management_id'];
        $labid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        $query = "SELECT *, item_id as xid,
        (SELECT item from laboratory_items where item_id = xid ) as item,
        (SELECT description from laboratory_items where item_id = xid ) as description,
        (SELECT supplier from laboratory_items where item_id = xid ) as supplier
        FROM laboratory_items_monitoring WHERE management_id = '$management_id' and laboratory_id = '$labid' order by id desc";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function saveTempOrderNoItem($data)
    {
        return DB::table('laboratory_items_laborder_tempnoitem')->insert([
            'order_id' => 'order-' . rand(0, 99999) . time(),
            'management_id' => $data['management_id'],
            'laboratory_id' => (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id,
            'category' => $data['dept'],
            'laborder' => $data['test'],
            'rate' => $data["rate"],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getLabOrderTemp($data)
    {
        return DB::table('laboratory_items_laborder_tempnoitem')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->get();
    }

    public static function getOrdersItems($data)
    {
        return DB::table('laboratory_items_laborder_tempitems')
            ->join('laboratory_items', 'laboratory_items.item_id', 'laboratory_items_laborder_tempitems.item_id')
            ->where('laboratory_items_laborder_tempitems.management_id', $data['management_id'])
            ->where('laboratory_items_laborder_tempitems.laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->where('laboratory_items_laborder_tempitems.order_id', $data['order_id'])
            ->get();
    }

    public static function saveOrderItem($data)
    {
        return DB::table('laboratory_items_laborder_tempitems')
            ->insert([
                'management_id' => $data['management_id'],
                'laboratory_id' => (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id,
                'item_id' => $data['selected_item'],
                'order_id' => $data['order_id'],
                'category' => $data['category'],
                'laborder' => $data['laborder'],
                'rate' => $data['rate'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function processTempOrderWithItems($data)
    {
        $query = DB::table('laboratory_items_laborder_tempitems')
            ->join('laboratory_items', 'laboratory_items.item_id', 'laboratory_items_laborder_tempitems.item_id')
            ->where('laboratory_items_laborder_tempitems.management_id', $data['management_id'])
            ->where('laboratory_items_laborder_tempitems.laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->where('laboratory_items_laborder_tempitems.order_id', $data['order_id'])
            ->get();

        $gtc = [];
        foreach ($query as $x) {
            $gtc[] = array(
                "lil_id" => "lil-" . rand() . time(),
                "laboratory_id" => $x->laboratory_id,
                "management_id" => $x->management_id,
                "order_id" => $x->order_id,
                "category" => $x->category,
                "laborder" => $x->laborder,
                "rate" => $x->rate,
                "item_id" => $x->item_id,
                "item" => $x->item,
                "description" => $x->description,
                "supplier" => $x->supplier,
                "status" => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('laboratory_items_laborder')->insert($gtc);

        DB::table('laboratory_items_laborder_tempitems')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->where('order_id', $data['order_id'])
            ->delete();

        return DB::table('laboratory_items_laborder_tempnoitem')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->where('order_id', $data['order_id'])
            ->delete();
    }

    public static function getLaboratoryOrderList($data)
    {
        return DB::table('laboratory_items_laborder')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->groupBy('order_id')
            ->orderBy('laborder', 'asc')
            ->get();
    }

    public static function getLaboratoryOrderListItems($data)
    {

        $labid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        $query =  "SELECT *, item_id as itemId,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                    (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                  FROM laboratory_items_laborder WHERE management_id = '" . $data['management_id'] . "' AND  laboratory_id = '$labid' and order_id = '" . $data['order_id'] . "' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::table('laboratory_items_laborder')
        //     ->where('management_id', $data['management_id'])
        //     ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
        //     ->where('order_id', $data['order_id'])
        //     ->get();
    }

    public static function laboratoryHemathologyOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hematology')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function laboratorySerologyOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_sorology')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function laboratoryMicroscopyOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
            $today = date('Y-m-d');
            $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                    (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                    (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                    (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                    FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

            $result = DB::connection('mysql')->getPdo()->prepare($query);
            $result->execute();
            $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

            if (count($availblebatch) > 0) {
                $itemstoOut[] = array(
                    'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                    'item_id' => $availblebatch[0]->item_id,
                    'management_id' => $availblebatch[0]->management_id,
                    'laboratory_id' => $availblebatch[0]->laboratory_id,
                    'qty' => $data['qty'][$i],
                    'dr_number' => $availblebatch[0]->dr_number,
                    'batch_number' => $availblebatch[0]->batch_number,
                    'mfg_date' => $availblebatch[0]->mfg_date,
                    'expiration_date' => $availblebatch[0]->expiration_date,
                    'type' => 'OUT',
                    'remarks' => $data['remarks'],
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            } else {
                return 'order-cannot-process-reagent-notfound';
            }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_microscopy')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function laboratoryFecalAnalysisOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_fecal_analysis')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function laboratoryChemistryOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_chemistry')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    // new method for stooltest
    public static function getOrderStoolTestNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_stooltest')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_stooltest.patient_id')
            ->select('laboratory_stooltest.*', 'patients.*', 'laboratory_stooltest.created_at as date_ordered')
            ->where('laboratory_stooltest.patient_id', $data['patient_id'])
            ->where('laboratory_stooltest.order_status', 'new-order-paid')
            ->groupBy('laboratory_stooltest.trace_number')
            ->get();
    }

    public static function getOrderStoolTestNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_stooltest')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_stooltest.patient_id', )
            ->select('laboratory_stooltest.*', 'patients.*')
            ->where('laboratory_stooltest.trace_number', $data['trace_number'])
            ->get();
    }

    public static function laboratoryStooltestOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_stooltest')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveStoolTestOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_stooltest')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'color' => empty($data['color'][$i]) ? null : $data['color'][$i],
                    'consistency' => empty($data['consistency'][$i]) ? null : $data['consistency'][$i],
                    'occult_blood_result' => empty($data['occult_blood'][$i]) ? null : $data['occult_blood'][$i],
                    'dfs' => empty($data['dfs'][$i]) ? null : $data['dfs'][$i],
                    'kt' => empty($data['kt'][$i]) ? null : $data['kt'][$i],
                    'kk' => empty($data['kk'][$i]) ? null : $data['kk'][$i],
                    'dfs_ascaris' => empty($data['dfs_ascaris'][$i]) ? null : $data['dfs_ascaris'][$i],
                    'dfs_hookworm' => empty($data['dfs_hookworm'][$i]) ? null : $data['dfs_hookworm'][$i],
                    'dfs_blastocystis' => empty($data['dfs_blasto'][$i]) ? null : $data['dfs_blasto'][$i],
                    'dfs_giardia_lamblia_cyst' => empty($data['dfs_giadia_cyst'][$i]) ? null : $data['dfs_giadia_cyst'][$i],
                    'dfs_giardia_lamblia_trophozoite' => empty($data['dfs_giadia_trophozoite'][$i]) ? null : $data['dfs_giadia_trophozoite'][$i],
                    'dfs_trichusris_trichuira' => empty($data['dfs_trichuris'][$i]) ? null : $data['dfs_trichuris'][$i],
                    'dfs_entamoeba_lamblia_cyst' => empty($data['dfs_estamoeba_cyst'][$i]) ? null : $data['dfs_estamoeba_cyst'][$i],
                    'dfs_entamoeba_lamblia_trophozoite' => empty($data['dfs_estamoeba_trophozoite'][$i]) ? null : $data['dfs_estamoeba_trophozoite'][$i],
                    'kt_ascaris' => empty($data['kt_ascaris'][$i]) ? null : $data['kt_ascaris'][$i],
                    'kt_hookworm' => empty($data['kt_hookworm'][$i]) ? null : $data['kt_hookworm'][$i],
                    'kt_blastocystis' => empty($data['kt_blasto'][$i]) ? null : $data['kt_blasto'][$i],
                    'kt_giardia_lamblia_cyst' => empty($data['kt_giadia_cyst'][$i]) ? null : $data['kt_giadia_cyst'][$i],
                    'kt_giardia_lamblia_trophozoite' => empty($data['kt_giadia_trophozoite'][$i]) ? null : $data['kt_giadia_trophozoite'][$i],
                    'kt_trichusris_trichuira' => empty($data['kt_trichuris'][$i]) ? null : $data['kt_trichuris'][$i],
                    'kt_entamoeba_lamblia_cyst' => empty($data['kt_estamoeba_cyst'][$i]) ? null : $data['kt_estamoeba_cyst'][$i],
                    'kt_entamoeba_lamblia_trophozoite' => empty($data['kt_estamoeba_trophozoite'][$i]) ? null : $data['kt_estamoeba_trophozoite'][$i],
                    'kk_ascaris' => empty($data['kk_ascaris'][$i]) ? null : $data['kk_ascaris'][$i],
                    'kk_hookworm' => empty($data['kk_hookworm'][$i]) ? null : $data['kk_hookworm'][$i],
                    'kk_blastocystis' => empty($data['kk_blasto'][$i]) ? null : $data['kk_blasto'][$i],
                    'kk_giardia_lamblia_cyst' => empty($data['kk_giadia_cyst'][$i]) ? null : $data['kk_giadia_cyst'][$i],
                    'kk_giardia_lamblia_trophozoite' => empty($data['kk_giadia_trophozoite'][$i]) ? null : $data['kk_giadia_trophozoite'][$i],
                    'kk_trichusris_trichuira' => empty($data['kk_trichuris'][$i]) ? null : $data['kk_trichuris'][$i],
                    'kk_entamoeba_lamblia_cyst' => empty($data['kk_estamoeba_cyst'][$i]) ? null : $data['kk_estamoeba_cyst'][$i],
                    'kk_entamoeba_lamblia_trophozoite' => empty($data['kk_estamoeba_trophozoite'][$i]) ? null : $data['kk_estamoeba_trophozoite'][$i],
                    'others' => empty($data['others'][$i]) ? null : $data['others'][$i],
                    'pus_cells' => empty($data['pus_cells'][$i]) ? null : $data['pus_cells'][$i],
                    'reb_blood_cells' => empty($data['rbc'][$i]) ? null : $data['rbc'][$i],
                    'fat_globules' => empty($data['fat_globules'][$i]) ? null : $data['fat_globules'][$i],
                    'yeast_cells' => empty($data['yeast_cells'][$i]) ? null : $data['yeast_cells'][$i],
                    'bacteria' => empty($data['bacteria'][$i]) ? null : $data['bacteria'][$i],
                    'oil_droplets' => empty($data['oil_droplets'][$i]) ? null : $data['oil_droplets'][$i],
                    'undigested_foods_paticles' => empty($data['undigested_food'][$i]) ? null : $data['undigested_food'][$i],

                    'enterobius_vermicularis' => empty($data['enterobius_vermicularis'][$i]) ? null : $data['enterobius_vermicularis'][$i],
                    'entamoeba_coli' => empty($data['entamoeba_coli'][$i]) ? null : $data['entamoeba_coli'][$i],
                    'entamoeba_histolytica' => empty($data['entamoeba_histolytica'][$i]) ? null : $data['entamoeba_histolytica'][$i],
                    'hpylori_stool' => empty($data['hpylori_stool'][$i]) ? null : $data['hpylori_stool'][$i],

                    'remarks' => empty($data['remarks'][$i]) ? null : $data['remarks'][$i],
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteStoolTestOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_stooltest.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_stooltest WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);



        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_stooltest')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_stooltest.patient_id')
        //     ->select('laboratory_stooltest.*', 'patients.*', 'laboratory_stooltest.remarks as lab_remarks')
        //     ->where('laboratory_stooltest.trace_number', $data['trace_number'])
        //     ->where('laboratory_stooltest.order_status', 'completed')
        //     ->get();
    }

    //papsmear
    public static function getOrderPapsmearTestNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_papsmear')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_papsmear.patient_id')
            ->select('laboratory_papsmear.*', 'patients.*', 'laboratory_papsmear.created_at as date_ordered')
            ->where('laboratory_papsmear.patient_id', $data['patient_id'])
            ->where('laboratory_papsmear.order_status', 'new-order-paid')
            ->groupBy('laboratory_papsmear.trace_number')
            ->get();
    }
    public static function getOrderPapsmearTestNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_papsmear')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_papsmear.patient_id', )
            ->select('laboratory_papsmear.*', 'patients.*')
            ->where('laboratory_papsmear.trace_number', $data['trace_number'])
            ->get();
    }
    public static function laboratoryPapsmeartestOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_papsmear')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function savePapsmearTestOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_papsmear')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'cervix' => empty($data['cervix'][$i]) ? NULL : $data['cervix'][$i],
                    'vagina' => empty($data['vagina'][$i]) ? NULL : $data['vagina'][$i],
                    'age' => empty($data['age'][$i]) ? NULL : $data['age'][$i],
                    'hormones' => empty($data['hormones'][$i]) ? NULL : $data['hormones'][$i],
                    'iud' => empty($data['iud'][$i]) ? NULL : $data['iud'][$i],
                    'lmp' => empty($data['lmp'][$i]) ? NULL : $data['lmp'][$i],
                    'satisfactory_evaluation' => empty($data['satisfactory_evaluation'][$i]) ? NULL : $data['satisfactory_evaluation'][$i],
                    'satisfactory_no_lmp' => empty($data['satisfactory_no_lmp'][$i]) ? NULL : $data['satisfactory_no_lmp'][$i],
                    'unsatisfactory' => empty($data['unsatisfactory'][$i]) ? NULL : $data['unsatisfactory'][$i],
                    'with_normal_limits' => empty($data['with_normal_limits'][$i]) ? NULL : $data['with_normal_limits'][$i],
                    'benign_cell_changes' => empty($data['benign_cell_changes'][$i]) ? NULL : $data['benign_cell_changes'][$i],
                    'epithelial_cell_abno' => empty($data['epithelial_cell_abno'][$i]) ? NULL : $data['epithelial_cell_abno'][$i],
                    'infection_trichomonas' => empty($data['infection_trichomonas'][$i]) ? NULL : $data['infection_trichomonas'][$i],
                    'infection_fungi' => empty($data['infection_fungi'][$i]) ? NULL : $data['infection_fungi'][$i],
                    'infection_predominance' => empty($data['infection_predominance'][$i]) ? NULL : $data['infection_predominance'][$i],
                    'infection_cellular' => empty($data['infection_cellular'][$i]) ? NULL : $data['infection_cellular'][$i],
                    'infection_others' => empty($data['infection_others'][$i]) ? NULL : $data['infection_others'][$i],
                    'reactive_inflammation' => empty($data['reactive_inflammation'][$i]) ? NULL : $data['reactive_inflammation'][$i],
                    'reactive_atrophy' => empty($data['reactive_atrophy'][$i]) ? NULL : $data['reactive_atrophy'][$i],
                    'reactive_follicular' => empty($data['reactive_follicular'][$i]) ? NULL : $data['reactive_follicular'][$i],
                    'reactive_radiation' => empty($data['reactive_radiation'][$i]) ? NULL : $data['reactive_radiation'][$i],
                    'reactive_iud' => empty($data['reactive_iud'][$i]) ? NULL : $data['reactive_iud'][$i],
                    'reactive_des' => empty($data['reactive_des'][$i]) ? NULL : $data['reactive_des'][$i],
                    'reactive_others' => empty($data['reactive_others'][$i]) ? NULL : $data['reactive_others'][$i],
                    'maturation_suggest' => empty($data['maturation_suggest'][$i]) ? NULL : $data['maturation_suggest'][$i],
                    'squamous_typical' => empty($data['squamous_typical'][$i]) ? NULL : $data['squamous_typical'][$i],
                    'squamous_low' => empty($data['squamous_low'][$i]) ? NULL : $data['squamous_low'][$i],
                    'squamous_low_hpv' => empty($data['squamous_low_hpv'][$i]) ? NULL : $data['squamous_low_hpv'][$i],
                    'squamous_low_mild' => empty($data['squamous_low_mild'][$i]) ? NULL : $data['squamous_low_mild'][$i],
                    'squamous_high' => empty($data['squamous_high'][$i]) ? NULL : $data['squamous_high'][$i],
                    'squamous_high_moderate' => empty($data['squamous_high_moderate'][$i]) ? NULL : $data['squamous_high_moderate'][$i],
                    'squamous_high_severe' => empty($data['squamous_high_severe'][$i]) ? NULL : $data['squamous_high_severe'][$i],
                    'squamous_high_carcinoma' => empty($data['squamous_high_carcinoma'][$i]) ? NULL : $data['squamous_high_carcinoma'][$i],
                    'squamous_cell' => empty($data['squamous_cell'][$i]) ? NULL : $data['squamous_cell'][$i],
                    'giandulare_endomentrial' => empty($data['giandulare_endomentrial'][$i]) ? NULL : $data['giandulare_endomentrial'][$i],
                    'giandulare_typical' => empty($data['giandulare_typical'][$i]) ? NULL : $data['giandulare_typical'][$i],
                    'giandulare_endocervical' => empty($data['giandulare_endocervical'][$i]) ? NULL : $data['giandulare_endocervical'][$i],
                    'giandulare_endometrial' => empty($data['giandulare_endometrial'][$i]) ? NULL : $data['giandulare_endometrial'][$i],
                    'giandulare_extraiterine' => empty($data['giandulare_extraiterine'][$i]) ? NULL : $data['giandulare_extraiterine'][$i],
                    'giandulare_adenocarcinoma' => empty($data['giandulare_adenocarcinoma'][$i]) ? NULL : $data['giandulare_adenocarcinoma'][$i],
                    'giandulare_adeno_specify' => empty($data['giandulare_adeno_specify'][$i]) ? NULL : $data['giandulare_adeno_specify'][$i],
                    'other_malignant' => empty($data['other_malignant'][$i]) ? NULL : $data['other_malignant'][$i],
                    'hormonal_compatible' => empty($data['hormonal_compatible'][$i]) ? NULL : $data['hormonal_compatible'][$i],
                    'hormonal_incompatible' => empty($data['hormonal_incompatible'][$i]) ? NULL : $data['hormonal_incompatible'][$i],
                    'hormonal_incompatible_spec' => empty($data['hormonal_incompatible_spec'][$i]) ? NULL : $data['hormonal_incompatible_spec'][$i],
                    'hormonal_non_possible' => empty($data['hormonal_non_possible'][$i]) ? NULL : $data['hormonal_non_possible'][$i],
                    'non_possible_specify' => empty($data['non_possible_specify'][$i]) ? NULL : $data['non_possible_specify'][$i],
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'papsmear',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new papsmear test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompletePapsmearOrderDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_papsmear')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_papsmear.patient_id')
            ->select('laboratory_papsmear.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
            ->where('laboratory_papsmear.trace_number', $data['trace_number'])
            ->where('laboratory_papsmear.order_status', 'completed')
            ->get();
    }

    //urinalysis
    public static function getOrderUrinalysis($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_urinalysis')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_urinalysis.patient_id')
            ->select('laboratory_urinalysis.*', 'patients.*', 'laboratory_urinalysis.created_at as date_ordered')
            ->where('laboratory_urinalysis.patient_id', $data['patient_id'])
            ->where('laboratory_urinalysis.order_status', 'new-order-paid')
            ->groupBy('laboratory_urinalysis.trace_number')
            ->get();
    }
    public static function getOrderUrinalysisDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_urinalysis')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_urinalysis.patient_id', )
            ->select('laboratory_urinalysis.*', 'patients.*')
            ->where('laboratory_urinalysis.trace_number', $data['trace_number'])
            ->get();
    }
    public static function saveUrinalysisOrderResult($data){
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_urinalysis')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'color' => empty($data['color'][$i]) ? null : $data['color'][$i],
                    'transparency' => empty($data['transparency'][$i]) ? null : $data['transparency'][$i],
                    'reaction' => empty($data['reaction'][$i]) ? null : $data['reaction'][$i],
                    'sp_gravity' => empty($data['sp_gravity'][$i]) ? null : $data['sp_gravity'][$i],
                    'albumin' => empty($data['albumin'][$i]) ? null : $data['albumin'][$i],
                    'sugar' => empty($data['sugar'][$i]) ? null : $data['sugar'][$i],
                    'pus_cell' => empty($data['pus_cell'][$i]) ? null : $data['pus_cell'][$i],
                    'rbc' => empty($data['rbc'][$i]) ? null : $data['rbc'][$i],
                    'epithelial_cell' => empty($data['epithelial_cell'][$i]) ? null : $data['epithelial_cell'][$i],
                    'mucus_threads' => empty($data['mucus_threads'][$i]) ? null : $data['mucus_threads'][$i],
                    'renal_cell' => empty($data['renal_cell'][$i]) ? null : $data['renal_cell'][$i],
                    'yeast_cell' => empty($data['yeast_cell'][$i]) ? null : $data['yeast_cell'][$i],
                    'hyaline' => empty($data['hyaline'][$i]) ? null : $data['hyaline'][$i],
                    'rbc_cast' => empty($data['rbc_cast'][$i]) ? null : $data['rbc_cast'][$i],
                    'wbc_cast' => empty($data['wbc_cast'][$i]) ? null : $data['wbc_cast'][$i],
                    'coarse_granular_cast' => empty($data['coarse_granular_cast'][$i]) ? null : $data['coarse_granular_cast'][$i],
                    'fine_granular_cast' => empty($data['fine_granular_cast'][$i]) ? null : $data['fine_granular_cast'][$i],
                    'pus_in_clumps' => empty($data['pus_in_clumps'][$i]) ? null : $data['pus_in_clumps'][$i],
                    'rbc_in_clumps' => empty($data['rbc_in_clumps'][$i]) ? null : $data['rbc_in_clumps'][$i],
                    'calcium_oxalate' => empty($data['calcium_oxalate'][$i]) ? null : $data['calcium_oxalate'][$i],
                    'uric_acid' => empty($data['uric_acid'][$i]) ? null : $data['uric_acid'][$i],
                    'amorphous_phosphate' => empty($data['amorphous_phosphate'][$i]) ? null : $data['amorphous_phosphate'][$i],
                    'amorphous_urate' => empty($data['amorphous_urate'][$i]) ? null : $data['amorphous_urate'][$i],
                    'calcium_carbonate' => empty($data['calcium_carbonate'][$i]) ? null : $data['calcium_carbonate'][$i],
                    'ammonium_biurate' => empty($data['ammonium_biurate'][$i]) ? null : $data['ammonium_biurate'][$i],
                    'triple_phosphate' => empty($data['triple_phosphate'][$i]) ? null : $data['triple_phosphate'][$i],
                    'spermatozoa' => empty($data['spermatozoa'][$i]) ? null : $data['spermatozoa'][$i],
                    'trichomonas_vaginalis' => empty($data['trichomonas_vaginalis'][$i]) ? null : $data['trichomonas_vaginalis'][$i],
                    'micral_test' => empty($data['micral_test'][$i]) ? null : $data['micral_test'][$i],
                    'urine_ketone' => empty($data['urine_ketone'][$i]) ? null : $data['urine_ketone'][$i],
                    'others' => empty($data['others'][$i]) ? null : $data['others'][$i],

                    'glucose' => empty($data['glucose'][$i]) ? null : $data['glucose'][$i],
                    'protein' => empty($data['protein'][$i]) ? null : $data['protein'][$i],
                    'ph' => empty($data['ph'][$i]) ? null : $data['ph'][$i],
                    'bacteria' => empty($data['bacteria'][$i]) ? null : $data['bacteria'][$i],

                    'remarks' => empty($data['remarks'][$i]) ? null : $data['remarks'][$i],
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function setUrinalysisOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');
        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
                // check available qty from item in order from trace number
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_urinalysis')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function getCompleteUrinalysisOrderDetails($data){
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_urinalysis.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_urinalysis WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);




        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_urinalysis')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_urinalysis.patient_id')
        //     ->select('laboratory_urinalysis.*', 'patients.*', 'laboratory_urinalysis.uric_acid as uricacid', 'laboratory_urinalysis.remarks as order_remarks')
        //     ->where('laboratory_urinalysis.trace_number', $data['trace_number'])
        //     ->where('laboratory_urinalysis.order_status', 'completed')
        //     ->get();
    }

    //ecg
    public static function getOrderEcg($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_ecg')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_ecg.patient_id')
            ->select('laboratory_ecg.*', 'patients.*', 'laboratory_ecg.created_at as date_ordered')
            ->where('laboratory_ecg.patient_id', $data['patient_id'])
            ->where('laboratory_ecg.order_status', 'new-order-paid')
            ->groupBy('laboratory_ecg.trace_number')
            ->get();
    }
    public static function getOrderEcgDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_ecg')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_ecg.patient_id', )
            ->select('laboratory_ecg.*', 'patients.*')
            ->where('laboratory_ecg.trace_number', $data['trace_number'])
            ->get();
    }
    public static function setEcgOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');
        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            if(((int) $data['qty'][$i]) > 0){
            // check available qty from item in order from trace number 
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            } 
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_ecg')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveEcgOrderResult($data, $filename){
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];

        DB::table('laboratory_ecg')
            ->where('order_id', $orderid)
            ->where('trace_number', $data['trace_number'])
            ->update([
                'atrial_ventricular_rate' => empty($data['atrial_ventricular_rate']) ? null : $data['atrial_ventricular_rate'],
                'rhythm' => empty($data['rhythm']) ? null : $data['rhythm'],
                'axis' => empty($data['axis']) ? null : $data['axis'],
                'p_wave' => empty($data['p_wave']) ? null : $data['p_wave'],
                'pr_interval' => empty($data['pr_interval']) ? null : $data['pr_interval'],
                'qrs' => empty($data['qrs']) ? null : $data['qrs'],
                'qt_interval' => empty($data['qt_interval']) ? null : $data['qt_interval'],
                'qrs_complex' => empty($data['qrs_complex']) ? null : $data['qrs_complex'],
                'st_segment' => empty($data['st_segment']) ? null : $data['st_segment'],
                'interpretation' => empty($data['interpretation']) ? null : $data['interpretation'],
                'image' => $filename,
                'others' => empty($data['others']) ? null : $data['others'],
                'remarks' => empty($data['remarks']) ? null : $data['remarks'],
                'order_status' => 'completed',
                'medtech' => $data['user_id'],
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteEcgOrderDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_ecg')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_ecg.patient_id')
            ->select('laboratory_ecg.*', 'patients.*', 'laboratory_ecg.remarks as order_remarks', 'laboratory_ecg.image as order_image')
            ->where('laboratory_ecg.trace_number', $data['trace_number'])
            ->where('laboratory_ecg.order_status', 'completed')
            ->get();
    }

    //medical
    public static function getOrderMedicalExam($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_medical_exam')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_medical_exam.patient_id')
            ->select('laboratory_medical_exam.*', 'patients.*', 'laboratory_medical_exam.created_at as date_ordered')
            ->where('laboratory_medical_exam.patient_id', $data['patient_id'])
            ->where('laboratory_medical_exam.order_status', 'new-order-paid')
            ->groupBy('laboratory_medical_exam.trace_number')
            ->get();
    }
    public static function getOrderMedicalExamDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_medical_exam')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_medical_exam.patient_id', )
            ->select('laboratory_medical_exam.*', 'patients.*')
            ->where('laboratory_medical_exam.trace_number', $data['trace_number'])
            ->get();
    }
    public static function setMedicalExamOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');
        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,

                        (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                        (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_medical_exam')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveMedicalExamOrderResult($data){
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];
        $pmh = "";
        $fam_history = "";
        if(!empty($data['pmh'])){
            $pmh = implode(',', $data['pmh']);
        }else{
            $pmh = NULL;
        }

        if(!empty($data['fam_history'])){
            $fam_history = implode(',', $data['fam_history']);
        }else{
            $fam_history = NULL;
        }

        DB::table('laboratory_medical_exam')
            ->where('order_id', $orderid)
            ->where('trace_number', $data['trace_number'])
            ->update([
                'nature_of_exam' => (empty($data['nature_of_examination']) ? null : $data['nature_of_examination'] == 'Others') ? $data['nature_of_examination_other_specify'] : $data['nature_of_examination'],
                'pmh' => $pmh,
                'pmh_other_specify' => str_contains($pmh, 'others') ? $data['pmh_other_specify'] : null,
                'fam_history' => $fam_history,
                'fam_history_other_specify' => str_contains($fam_history, 'others') ? $data['fam_hist_other_specify'] : null,
                'prev_operation' => empty($data['prev_operation_hospital']) ? null : $data['prev_operation_hospital'],
                'personal_history_smoke' => empty($data['ph_smoking']) ? null : $data['ph_smoking'],
                'personal_history_smoke_qty' => empty($data['ph_smoking_perday']) ? null : $data['ph_smoking_perday'],
                'personal_history_smoke_no_year' => empty($data['ph_smoking_no_of_year']) ? null : $data['ph_smoking_no_of_year'],
                'personal_history_alchohol' => empty($data['ph_alcohol']) ? null : $data['ph_alcohol'],
                'personal_history_drug' => empty($data['ph_drug_abuse']) ? null : $data['ph_drug_abuse'],
                'allergy_foods' => empty($data['allergies_of_foods']) ? null : $data['allergies_of_foods'],
                'allergy_drugs' => empty($data['allergies_of_drugs']) ? null : $data['allergies_of_drugs'],
                'menstrual_hist_lmp' => empty($data['menstrual_lmp']) ? null : $data['menstrual_lmp'],
                'menstrual_hist_pmp' => empty($data['menstrual_pmp']) ? null : $data['menstrual_pmp'],
                'menstrual_hist_g' => empty($data['menstrual_g']) ? null : $data['menstrual_g'],
                'menstrual_hist_p' => empty($data['menstrual_p']) ? null : $data['menstrual_p'],
                'menstrual_hist_other' => empty($data['menstrual_other']) ? null : $data['menstrual_other'],
                'medication' => empty($data['medication']) ? null : $data['medication'],
                'pe_bp' => empty($data['bp']) ? null : $data['bp'],
                'pe_pr' => empty($data['pr']) ? null : $data['pr'],
                'pe_ht' => empty($data['ht']) ? null : $data['ht'],
                'pe_wt' => empty($data['wt']) ? null : $data['wt'],
                'pe_bmi' => empty($data['bmi']) ? null : $data['bmi'],
                'pe_range' => empty($data['range']) ? null : $data['range'],
                'visual_acuity_od_near' => empty($data['od_near']) ? null : $data['od_near'],
                'visual_acuity_od_far' => empty($data['od_far']) ? null : $data['od_far'],
                'visual_acuity_os_near' => empty($data['os_near']) ? null : $data['os_near'],
                'visual_acuity_os_far' => empty($data['os_far']) ? null : $data['os_far'],

                'pe_hearing_ad' => empty($data['hearing_ad']) ? null : $data['hearing_ad'],
                'pe_hearing_as' => empty($data['hearing_as']) ? null : $data['hearing_as'],
                'pe_skin' => empty($data['skin']) ? null : $data['skin'],
                'pe_heent' => empty($data['heent']) ? null : $data['heent'],
                'pe_neck' => empty($data['neck']) ? null : $data['neck'],
                'pe_chest' => empty($data['chest']) ? null : $data['chest'],
                'pe_cardio' => empty($data['cardio']) ? null : $data['cardio'],
                'pe_abdomen' => empty($data['abdomen']) ? null : $data['abdomen'],
                'pe_genitourinary' => empty($data['genitourinary']) ? null : $data['genitourinary'],
                'pe_genitalia' => empty($data['genitalia']) ? null : $data['genitalia'],
                'pe_linguinal' => empty($data['inguinal']) ? null : $data['inguinal'],
                'pe_extremities' => empty($data['extremities']) ? null : $data['extremities'],
                'pe_reflexes' => empty($data['reflexes']) ? null : $data['reflexes'],
                'pe_neurology' => empty($data['neuro']) ? null : $data['neuro'],
                // 'pe_other'
                'classification_a' => empty($data['classification_a']) ? null : $data['classification_a'],
                'classification_b' => empty($data['classification_b']) ? null : $data['classification_b'],
                'classification_c' => empty($data['classification_c']) ? null : $data['classification_c'],
                'classification_d' => empty($data['classification_d']) ? null : $data['classification_d'],
                'classification_other' => empty($data['classification_e']) ? null : $data['classification_e'],
                'impression' => empty($data['impression']) ? null : $data['impression'],
                'recommendations' => empty($data['recommendations']) ? null : $data['recommendations'],
                'annual_pe_reg_time' => empty($data['registration_time']) ? null : $data['registration_time'],
                'annual_pe_vital_time' => empty($data['vital_signs_time']) ? null : $data['vital_signs_time'],
                'annual_pe_cbc_time' => empty($data['cbc_time']) ? null : $data['cbc_time'],
                'annual_pe_urinalysis_time' => empty($data['urinalysis_time']) ? null : $data['urinalysis_time'],
                'annual_pe_fecal_time' => empty($data['fecalysis_time']) ? null : $data['fecalysis_time'],
                'annual_pe_xray_time' => empty($data['xray_time']) ? null : $data['xray_time'],
                'annual_pe_ecg_time' => empty($data['ecg_time']) ? null : $data['ecg_time'],
                'annual_pe_papsmear_time' => empty($data['papsmear_time']) ? null : $data['papsmear_time'],
                'annual_pe_eye_check_time' => empty($data['eye_checkup_time']) ? null : $data['eye_checkup_time'],
                'annual_pe_dental_check_time' => empty($data['dental_checkup_time']) ? null : $data['dental_checkup_time'],
                'annual_pe_exam_time' => empty($data['pe_time']) ? null : $data['pe_time'],
                'annual_pe_exit_time' => empty($data['exit_signature_time']) ? null : $data['exit_signature_time'],

                // 'remarks' => ,
                'order_status' => 'completed',
                'medtech' => $data['user_id'],
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteMedicalExamOrderDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_medical_exam')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_medical_exam.patient_id')
            ->select('laboratory_medical_exam.*', 'patients.*')
            ->where('laboratory_medical_exam.trace_number', $data['trace_number'])
            ->where('laboratory_medical_exam.order_status', 'completed')
            ->get();
    }

    //oral glucose
    public static function getOrderOralGlucoseTestNew($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_oral_glucose')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_oral_glucose.patient_id')
            ->select('laboratory_oral_glucose.*', 'patients.*', 'laboratory_oral_glucose.created_at as date_ordered')
            ->where('laboratory_oral_glucose.patient_id', $data['patient_id'])
            ->where('laboratory_oral_glucose.order_status', 'new-order-paid')
            ->groupBy('laboratory_oral_glucose.trace_number')
            ->get();
    }
    public static function getOralGlucoseTestNewDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_oral_glucose')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_oral_glucose.patient_id', )
            ->select('laboratory_oral_glucose.*', 'patients.*')
            ->where('laboratory_oral_glucose.trace_number', $data['trace_number'])
            ->get();
    }
    public static function laboratoryOralGlucosetestOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_oral_glucose')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveOralGlucosetestOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_oral_glucose')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'baseline' => empty($data['baseline'][$i]) ? null : $data['baseline'][$i],
                    'baseline_remarks' => empty($data['baseline_remarks'][$i]) ? null : $data['baseline_remarks'][$i],
                    'first_hour' => empty($data['first_hour'][$i]) ? null : $data['first_hour'][$i],
                    'first_hour_remarks' => empty($data['first_hour_remarks'][$i]) ? null : $data['first_hour_remarks'][$i],
                    'second_hour' => empty($data['second_hour'][$i]) ? null : $data['second_hour'][$i],
                    'second_hour_remarks' => empty($data['second_hour_remarks'][$i]) ? null : $data['second_hour_remarks'][$i],
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'oral-glucose',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new oral glucose test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteOralGlucoseOrderDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_oral_glucose')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_oral_glucose.patient_id')
            ->select('laboratory_oral_glucose.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
            ->where('laboratory_oral_glucose.trace_number', $data['trace_number'])
            ->where('laboratory_oral_glucose.order_status', 'completed')
            ->get();
    }
    
    //thyroid profile
    public static function getOrderThyroidProfileTestNew($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_thyroid_profile')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_thyroid_profile.patient_id')
            ->select('laboratory_thyroid_profile.*', 'patients.*', 'laboratory_thyroid_profile.created_at as date_ordered')
            ->where('laboratory_thyroid_profile.patient_id', $data['patient_id'])
            ->where('laboratory_thyroid_profile.order_status', 'new-order-paid')
            ->groupBy('laboratory_thyroid_profile.trace_number')
            ->get();
    }
    public static function getThyroidProfileTestNewDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_thyroid_profile')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_thyroid_profile.patient_id', )
            ->select('laboratory_thyroid_profile.*', 'patients.*')
            ->where('laboratory_thyroid_profile.trace_number', $data['trace_number'])
            ->get();
    }
    public static function laboratoryThyroidProfiletestOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_thyroid_profile')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveThyroidProfileTestOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_thyroid_profile')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    't3' => empty($data['t3'][$i]) ? null : $data['t3'][$i],
                    't3_remarks' => empty($data['t3_remarks'][$i]) ? null : $data['t3_remarks'][$i],

                    't4' => empty($data['t4'][$i]) ? null : $data['t4'][$i],
                    't4_remarks' => empty($data['t4_remarks'][$i]) ? null : $data['t4_remarks'][$i],

                    'tsh' => empty($data['tsh']) ? null : $data['tsh'][$i],
                    'tsh_remarks' => empty($data['tsh_remarks']) ? null : $data['tsh_remarks'][$i],

                    'ft4' => empty($data['ft4'][$i]) ? null : $data['ft4'][$i],
                    'ft4_remarks' => empty($data['ft4_remarks'][$i]) ? null : $data['ft4_remarks'][$i],

                    'ft3' => empty($data['ft3'][$i]) ? null : $data['ft3'][$i],
                    'ft3_remarks' => empty($data['ft3_remarks'][$i]) ? null : $data['ft3_remarks'][$i],
                    't3t4' => empty($data['t3t4'][$i]) ? null : $data['t3t4'][$i],
                    't3t4_remarks' => empty($data['t3t4_remarks'][$i]) ? null : $data['t3t4_remarks'][$i],

                    'fht' => empty($data['fht'][$i]) ? null : $data['fht'][$i],
                    'fht_remarks' => empty($data['fht_remarks'][$i]) ? null : $data['fht_remarks'][$i],

                    
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'thyroid-profile',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new thyroid profile test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteThyroidProfileOrderDetails($data){
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_thyroid_profile.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_thyroid_profile WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);




        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_thyroid_profile')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_thyroid_profile.patient_id')
        //     ->select('laboratory_thyroid_profile.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
        //     ->where('laboratory_thyroid_profile.trace_number', $data['trace_number'])
        //     ->where('laboratory_thyroid_profile.order_status', 'completed')
        //     ->get();
    }
    
    //immunology
    public static function getOrderImmunologyTestNew($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_immunology')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_immunology.patient_id')
            ->select('laboratory_immunology.*', 'patients.*', 'laboratory_immunology.created_at as date_ordered')
            ->where('laboratory_immunology.patient_id', $data['patient_id'])
            ->where('laboratory_immunology.order_status', 'new-order-paid')
            ->groupBy('laboratory_immunology.trace_number')
            ->get();
    }
    public static function getImmunologyTestNewDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_immunology')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_immunology.patient_id', )
            ->select('laboratory_immunology.*', 'patients.*')
            ->where('laboratory_immunology.trace_number', $data['trace_number'])
            ->get();
    }
    public static function laboratoryImmunologytestOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_immunology')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    public static function saveImmunologyTestOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_immunology')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'alpha_fetoprotein' => empty($data['alpha_fetoprotein'][$i]) ? null : $data['alpha_fetoprotein'][$i],
                    'remarks' => empty($data['remarks'][$i]) ? null : $data['remarks'][$i],
                    'order_status' => 'completed',
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'medtech' => $data['user_id'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'immunology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new immunology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteImmunologyOrderDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_immunology')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_immunology.patient_id')
            ->select('laboratory_immunology.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
            ->where('laboratory_immunology.trace_number', $data['trace_number'])
            ->where('laboratory_immunology.order_status', 'completed')
            ->get();
    }

    //miscellaneous
    public static function getOrderMiscellaneousTestNew($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_miscellaneous')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_miscellaneous.patient_id')
            ->select('laboratory_miscellaneous.*', 'patients.*', 'laboratory_miscellaneous.created_at as date_ordered')
            ->where('laboratory_miscellaneous.patient_id', $data['patient_id'])
            ->where('laboratory_miscellaneous.order_status', 'new-order-paid')
            ->groupBy('laboratory_miscellaneous.trace_number')
            ->get();
    }
    public static function getMiscellaneousTestNewDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_miscellaneous')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_miscellaneous.patient_id', )
            ->select('laboratory_miscellaneous.*', 'patients.*')
            ->where('laboratory_miscellaneous.trace_number', $data['trace_number'])
            ->get();
    }
    public static function laboratoryMiscellaneoustestOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_miscellaneous')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    
    
    public static function saveMiscellaneousOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_miscellaneous')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'speciment' => empty($data['specimen'][$i]) ? null : $data['specimen'][$i],
                    'test' => empty($data['test'][$i]) ? null : $data['test'][$i],
                    'result' => empty($data['result'][$i]) ? null : $data['result'][$i],

                    'pregnancy_test_urine_result' => empty($data['pregnancy_test_urine_result'][$i]) ? null : strip_tags($data['pregnancy_test_urine_result'][$i]),
                    'pregnancy_test_serum_result' => empty($data['pregnancy_test_serum_result'][$i]) ? null : strip_tags($data['pregnancy_test_serum_result'][$i]),
                    'papsmear_test_result' => empty($data['papsmear_test_result'][$i]) ? null : strip_tags($data['papsmear_test_result'][$i]),
                    'papsmear_test_with_gramstain_result' => empty($data['papsmear_test_with_gramstain_result'][$i]) ? null : strip_tags($data['papsmear_test_with_gramstain_result'][$i]),

                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remoeve patient in queee if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteMiscellaneousOrderDetails($data){
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_miscellaneous.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_miscellaneous WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);


        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_miscellaneous')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_miscellaneous.patient_id')
        //     ->select('laboratory_miscellaneous.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
        //     ->where('laboratory_miscellaneous.trace_number', $data['trace_number'])
        //     ->where('laboratory_miscellaneous.order_status', 'completed')
        //     ->get();
    }
    
    //hepatitis
    public static function getOrderHepatitisProfileTestNew($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hepatitis_profile')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_hepatitis_profile.patient_id')
            ->select('laboratory_hepatitis_profile.*', 'patients.*', 'laboratory_hepatitis_profile.created_at as date_ordered')
            ->where('laboratory_hepatitis_profile.patient_id', $data['patient_id'])
            ->where('laboratory_hepatitis_profile.order_status', 'new-order-paid')
            ->groupBy('laboratory_hepatitis_profile.trace_number')
            ->get();
    }
    public static function getHepatitisProfileTestNewDetails($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hepatitis_profile')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_hepatitis_profile.patient_id', )
            ->select('laboratory_hepatitis_profile.*', 'patients.*')
            ->where('laboratory_hepatitis_profile.trace_number', $data['trace_number'])
            ->get();
    }
    public static function laboratoryHepatitisProfiletestOrderProcessed($data){
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hepatitis_profile')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
    
    public static function saveHepatitisProfileOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_hepatitis_profile')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'hbsag_quali' => empty($data['hbsag_quali'][$i]) ? null : $data['hbsag_quali'][$i],
                    'hbsag_quali_remarks' => empty($data['hbsag_quali_remarks'][$i]) ? null : $data['hbsag_quali_remarks'][$i],
                    'antihbs_quali' => empty($data['antihbs_quali'][$i]) ? null : $data['antihbs_quali'][$i],
                    'antihbs_quali_remarks' => empty($data['antihbs_quali_remarks'][$i]) ? null : $data['antihbs_quali_remarks'][$i],
                    'antihcv_quali' => empty($data['antihcv_quali'][$i]) ? null : $data['antihcv_quali'][$i],
                    'antihcv_quali_remarks' => empty($data['antihcv_quali_remarks'][$i]) ? null : $data['antihcv_quali_remarks'][$i],
                    'hbsag_quanti' => empty($data['hbsag_quanti'][$i]) ? null : $data['hbsag_quanti'][$i],
                    'hbsag_quanti_remarks' => empty($data['hbsag_quanti_remarks'][$i]) ? null : $data['hbsag_quanti_remarks'][$i],
                    'antihbs_quanti' => empty($data['antihbs_quanti'][$i]) ? null : $data['antihbs_quanti'][$i],
                    'antihbs_quanti_remarks' => empty($data['antihbs_quanti_remarks'][$i]) ? null : $data['antihbs_quanti_remarks'][$i],
                    'hbeaag' => empty($data['hbeaag'][$i]) ? null : $data['hbeaag'][$i],
                    'hbeaag_remarks' => empty($data['hbeaag_remarks'][$i]) ? null : $data['hbeaag_remarks'][$i],
                    'antihbe' => empty($data['antihbe'][$i]) ? null : $data['antihbe'][$i],
                    'antihbe_remarks' => empty($data['antihbe_remarks'][$i]) ? null : $data['antihbe_remarks'][$i],
                    'antihbc_igm' => empty($data['antihbc_igm'][$i]) ? null : $data['antihbc_igm'][$i],
                    'antihbc_igm_remarks' => empty($data['antihbc_igm_remarks'][$i]) ? null : $data['antihbc_igm_remarks'][$i],
                    'antihav_igm' => empty($data['antihav_igm'][$i]) ? null : $data['antihav_igm'][$i],
                    'antihav_igm_remarks' => empty($data['antihav_igm_remarks'][$i]) ? null : $data['antihav_igm_remarks'][$i],
                    'anti_havigm_igg' => empty($data['anti_havigm_igg'][$i]) ? null : $data['anti_havigm_igg'][$i],
                    'anti_havigm_igg_remarks' => empty($data['anti_havigm_igg_remarks'][$i]) ? null : $data['anti_havigm_igg_remarks'][$i],
                    'antihbc_iggtotal' => empty($data['antihbc_iggtotal'][$i]) ? null : $data['antihbc_iggtotal'][$i],
                    'antihbc_iggtotal_remarks' => empty($data['antihbc_iggtotal_remarks'][$i]) ? null : $data['antihbc_iggtotal_remarks'][$i],
                    // 'remarks' => ,
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remoeve patient in queee if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
    public static function getCompleteHepatitisProfileOrderDetails($data){
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_hepatitis_profile.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_hepatitis_profile WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);



        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_hepatitis_profile')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_hepatitis_profile.patient_id')
        //     ->select('laboratory_hepatitis_profile.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
        //     ->where('laboratory_hepatitis_profile.trace_number', $data['trace_number'])
        //     ->where('laboratory_hepatitis_profile.order_status', 'completed')
        //     ->get();
    }

    // 8-16-2021
    public static function getItemMonitoringBatches($data)
    {
        $management_id = $data['management_id'];
        $labid = (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id;

        $query = "SELECT *, item_id as xid,  batch_number as xbatch_number,
        (SELECT item from laboratory_items where item_id = xid ) as item,
        (SELECT description from laboratory_items where item_id = xid ) as description,
        (SELECT supplier from laboratory_items where item_id = xid ) as supplier,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = xid and batch_number = xbatch_number and type ='OUT' ) as _batch_qty_out,
        (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = xid and batch_number = xbatch_number and type ='IN' ) as _batch_qty_in,
        (SELECT GREATEST(0, _batch_qty_in - _batch_qty_out )) as _batch_qty_available
        FROM laboratory_items_monitoring WHERE management_id = '$management_id' and laboratory_id = '$labid' and type='IN' group by batch_number order by id desc";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function getSpecimenList($data){
        return DB::table('receiving_specimen')
        ->where('patient_id', $data['patient_id'])
        ->where('trace_number', $data['trace_number'])
        ->get();
    }

    public static function getOrderCBCNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_cbc')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_cbc.patient_id')
            ->select('laboratory_cbc.*', 'patients.*', 'laboratory_cbc.created_at as date_ordered')
            ->where('laboratory_cbc.patient_id', $data['patient_id'])
            ->where('laboratory_cbc.order_status', 'new-order-paid')
            ->groupBy('laboratory_cbc.trace_number')
            ->get();
    }

    public static function getOrderCBCNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_cbc')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_cbc.patient_id')
            ->select('laboratory_cbc.*', 'patients.*')
            ->where('laboratory_cbc.trace_number', $data['trace_number'])
            ->get();
    }

    public static function laboratoryCBCOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_cbc')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveCBCOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_cbc')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'wbc' => empty($data['wbc'][$i]) ? null : $data['wbc'][$i],
                    'lym' => empty($data['lym'][$i]) ? null : $data['lym'][$i],
                    'mid' => empty($data['mid']) ? null : $data['mid'][$i],
                    'neut' => empty($data['neut'][$i]) ? null : $data['neut'][$i],
                    'rbc' => empty($data['rbc'][$i]) ? null : $data['rbc'][$i],
                    'hgb' => empty($data['hgb'][$i]) ? null : $data['hgb'][$i],
                    'hct' => empty($data['hct'][$i]) ? null : $data['hct'][$i],
                    'mcv' => empty($data['mcv'][$i]) ? null : $data['mcv'][$i],
                    'mch' => empty($data['mch'][$i]) ? null : $data['mch'][$i],
                    'mchc' => empty($data['mchc'][$i]) ? null : $data['mchc'][$i],
                    'rdw_sd' => empty($data['rdw_sd'][$i]) ? null : $data['rdw_sd'][$i],
                    'rdw_cv' => empty($data['rdw_cv'][$i]) ? null : $data['rdw_cv'][$i],
                    'mpv' => empty($data['mpv'][$i]) ? null : $data['mpv'][$i],
                    'pdw' => empty($data['pdw'][$i]) ? null : $data['pdw'][$i],
                    'pct' => empty($data['pct'][$i]) ? null : $data['pct'][$i],
                    'plt' => empty($data['plt'][$i]) ? null : $data['plt'][$i],
                    'p_lcr' => empty($data['p_lcr'][$i]) ? null : $data['p_lcr'][$i],

                    'monocytes' => empty($data['monocytes'][$i]) ? null : $data['monocytes'][$i],
                    'eosinophils' => empty($data['eosinophils'][$i]) ? null : $data['eosinophils'][$i],
                    'basophils' => empty($data['basophils'][$i]) ? null : $data['basophils'][$i],
                    'reticulocyte' => empty($data['reticulocyte'][$i]) ? null : $data['reticulocyte'][$i],
                    'esr_male' => empty($data['esr_male'][$i]) ? null : $data['esr_male'][$i],
                    'esr_female' => empty($data['esr_female'][$i]) ? null : $data['esr_female'][$i],
                    'clotting_time' => empty($data['clotting_time'][$i]) ? null : $data['clotting_time'][$i],
                    'bleeding_time' => empty($data['bleeding_time'][$i]) ? null : $data['bleeding_time'][$i],
                    'blood_type' => empty($data['blood_type'][$i]) ? null : $data['blood_type'][$i],

                    'remarks' => empty($data['remarks'][$i]) ? null : $data['remarks'][$i],
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remove patient in queue if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteCBCOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_cbc.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_cbc WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);


        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_cbc')
        // ->join('patients', 'patients.patient_id', '=', 'laboratory_cbc.patient_id')

        // ->leftJoin('laboratory_list', 'laboratory_list.user_id', '=', 'laboratory_cbc.medtech')
        // ->leftJoin('laboratory_formheader', 'laboratory_formheader.management_id', '=', $data['management_id'])
        
        // ->select('laboratory_cbc.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image', 'laboratory_list.user_fullname as medtech', 'laboratory_formheader.pathologist', 'laboratory_formheader.pathologist_lcn', 'laboratory_formheader.chief_medtech', 'laboratory_formheader.chief_medtech_lci')
        // ->where('laboratory_cbc.trace_number', $data['trace_number'])
        // ->where('laboratory_cbc.order_status', 'completed')
        // ->get();
    }

    public static function getOrderCovidTestNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_covid19_test')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_covid19_test.patient_id')
            ->select('laboratory_covid19_test.*', 'patients.*', 'laboratory_covid19_test.created_at as date_ordered')
            ->where('laboratory_covid19_test.patient_id', $data['patient_id'])
            ->where('laboratory_covid19_test.order_status', 'new-order-paid')
            ->groupBy('laboratory_covid19_test.trace_number')
            ->get();
    }

    public static function getOrderCovidTestNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_covid19_test')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_covid19_test.patient_id')
            ->select('laboratory_covid19_test.*', 'patients.*')
            ->where('laboratory_covid19_test.trace_number', $data['trace_number'])
            ->get();
    }

    public static function laboratoryCovid19OrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_covid19_test')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveCovid19TestOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_covid19_test')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'rapid_test_result' => empty($data['rapid_test_result'][$i]) ? null : $data['rapid_test_result'][$i],
                    'rapid_test_result_remarks' => empty($data['rapid_test_result_remarks'][$i]) ? null : $data['rapid_test_result_remarks'][$i],

                    'antigen_test_result' => empty($data['antigen_test_result']) ? null : $data['antigen_test_result'][$i],
                    'antigen_test_result_remarks' => empty($data['antigen_test_result_remarks'][$i]) ? null : $data['antigen_test_result_remarks'][$i],
                    
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remoeve patient in queee if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteCovid19OrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_covid19_test.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_covid19_test WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_covid19_test')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_covid19_test.patient_id')
        //     ->select('laboratory_covid19_test.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
        //     ->where('laboratory_covid19_test.trace_number', $data['trace_number'])
        //     ->where('laboratory_covid19_test.order_status', 'completed')
        //     ->get();
    }

    public static function getOrderTumorMakerTestNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_tumor_maker')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_tumor_maker.patient_id')
            ->select('laboratory_tumor_maker.*', 'patients.*', 'laboratory_tumor_maker.created_at as date_ordered')
            ->where('laboratory_tumor_maker.patient_id', $data['patient_id'])
            ->where('laboratory_tumor_maker.order_status', 'new-order-paid')
            ->groupBy('laboratory_tumor_maker.trace_number')
            ->get();
    }

    public static function getOrderTumorMakerTestNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_tumor_maker')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_tumor_maker.patient_id')
            ->select('laboratory_tumor_maker.*', 'patients.*')
            ->where('laboratory_tumor_maker.trace_number', $data['trace_number'])
            ->get();
    }

    public static function laboratoryTumorMakerOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_tumor_maker')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveTumorMakerOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_tumor_maker')
                ->where('order_id', $orderid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'aso_result' => empty($data['aso_result'][$i]) ? null : strip_tags($data['aso_result'][$i]),
                    'biopsy_result' => empty($data['biopsy_result'][$i]) ? null : strip_tags($data['biopsy_result'][$i]),
                    'c3_result' => empty($data['c3_result']) ? null : strip_tags($data['c3_result'][$i]),
                    'ca_125_result' => empty($data['ca_125_result'][$i]) ? null : strip_tags($data['ca_125_result'][$i]),
                    'cea_result' => empty($data['cea_result'][$i]) ? null : strip_tags($data['cea_result'][$i]),
                    'psa_prostate_result' => empty($data['psa_prostate_result'][$i]) ? null : strip_tags($data['psa_prostate_result'][$i]),
                    'afp_result' => empty($data['afp_result'][$i]) ? null : strip_tags($data['afp_result'][$i]),
                    'order_status' => 'completed',
                    'medtech' => $data['user_id'],
                    'is_processed_time_end' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        // remoeve patient in queee if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteTumorMakerOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_tumor_maker.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_tumor_maker WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);



        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_tumor_maker')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_tumor_maker.patient_id')
        //     ->select('laboratory_tumor_maker.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
        //     ->where('laboratory_tumor_maker.trace_number', $data['trace_number'])
        //     ->where('laboratory_tumor_maker.order_status', 'completed')
        //     ->get();
    }

    public static function getOrderDrugTestTestNew($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_drug_test')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_drug_test.patient_id')
            ->select('laboratory_drug_test.*', 'patients.*', 'laboratory_drug_test.created_at as date_ordered')
            ->where('laboratory_drug_test.patient_id', $data['patient_id'])
            ->where('laboratory_drug_test.order_status', 'new-order-paid')
            ->groupBy('laboratory_drug_test.trace_number')
            ->get();
    }

    public static function getOrderDrugTestTestNewDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_drug_test')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_drug_test.patient_id')
            ->select('laboratory_drug_test.*', 'patients.*')
            ->where('laboratory_drug_test.trace_number', $data['trace_number'])
            ->get();
    }

    public static function laboratoryDrugTestOrderProcessed($data)
    {
        date_default_timezone_set('Asia/Manila');

        $itemstoOut = [];

        for ($i = 0; $i < count($data['item_id']); $i++) {
            // check available qty from item in order from trace number
            if(((int) $data['qty'][$i]) > 0){
                $today = date('Y-m-d');
                $query = "SELECT *, item_id as itemId, batch_number as batchNumber,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'IN' ) as _total_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'IN' ) as _total_batch_qty_in,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and type = 'OUT' ) as _total_qty_out,
                            (SELECT IFNULL(sum(qty), 0) from laboratory_items_monitoring where item_id = itemId and batch_number = batchNumber and type = 'OUT' ) as _total_batch_qty_out,
                            (SELECT GREATEST(0, _total_batch_qty_in - _total_batch_qty_out )) as _total_batch_qty_available,
                            (SELECT GREATEST(0, _total_qty_in - _total_qty_out )) as _total_qty_available
                        FROM laboratory_items_monitoring WHERE item_id ='" . $data['item_id'][$i] . "'  and expiration_date > '$today' having _total_batch_qty_available != 0 order by expiration_date asc limit 1";

                $result = DB::connection('mysql')->getPdo()->prepare($query);
                $result->execute();
                $availblebatch = $result->fetchAll(\PDO::FETCH_OBJ);

                if (count($availblebatch) > 0) {
                    $itemstoOut[] = array(
                        'lrm_id' => 'lrm-' . rand(0, 9999) . time(),
                        'item_id' => $availblebatch[0]->item_id,
                        'management_id' => $availblebatch[0]->management_id,
                        'laboratory_id' => $availblebatch[0]->laboratory_id,
                        'qty' => $data['qty'][$i],
                        'dr_number' => $availblebatch[0]->dr_number,
                        'batch_number' => $availblebatch[0]->batch_number,
                        'mfg_date' => $availblebatch[0]->mfg_date,
                        'expiration_date' => $availblebatch[0]->expiration_date,
                        'type' => 'OUT',
                        'remarks' => $data['remarks'],
                        'added_by' => $data['user_id'],
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                } else {
                    return 'order-cannot-process-reagent-notfound';
                }
            }
        }

        DB::table('laboratory_items_monitoring')->insert($itemstoOut);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_drug_test')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('laboratory_id', (new _Laboratory)::getLaboratoryId($data['user_id'])->laboratory_id)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function saveDrugTestOrderResult($data)
    {
        date_default_timezone_set('Asia/Manila');

        $orderid = $data['order_id'];

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_drug_test')
            ->where('order_id', $orderid[$i])
            ->where('trace_number', $data['trace_number'])
            ->update([

                'two_panels_result' => empty($data['two_panels_result'][$i]) ? null : strip_tags($data['two_panels_result'][$i]),
                'three_panels_result' => empty($data['three_panels_result'][$i]) ? null : strip_tags($data['three_panels_result'][$i]),
                'five_panels_result' => empty($data['five_panels_result']) ? null : strip_tags($data['five_panels_result'][$i]),

                'order_status' => 'completed',
                'medtech' => $data['user_id'],
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // remoeve patient in queee if trace_number is no new remaining
        if($data['process_for'] == 'clinic'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratory($data['patient_id'], $data['trace_number'], $data['queue']);
        }
        if($data['process_for'] == 'mobile-van'){
            _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $data['trace_number']);
        }
        
        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getCompleteDrugTestOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_drug_test.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_drug_test WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);


        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_drug_test')
        //     ->join('patients', 'patients.patient_id', '=', 'laboratory_drug_test.patient_id')
        //     ->select('laboratory_drug_test.*', 'patients.birthday', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.barangay', 'patients.gender', 'patients.city', 'patients.street', 'patients.zip', 'patients.image')
        //     ->where('laboratory_drug_test.trace_number', $data['trace_number'])
        //     ->where('laboratory_drug_test.order_status', 'completed')
        //     ->get();
    }

    public static function getLabItemProductDescriptions($data){
        return DB::table('laboratory_items')
            ->select('*', 'description as value', 'description as label')
            ->where('management_id', $data['management_id'])
            ->where('item', $data['item'])
            ->orderBy('description', 'ASC')
            ->get();
    }

    public static function getLabItemProducts($data){
        return DB::table('laboratory_items')
            ->select('id', 'item_id as value', 'item as label')
            ->where('management_id', $data['management_id'])
            ->groupBy('laboratory_items.item')
            ->get();
    }

    public static function editResultPrintLayout($data){
        date_default_timezone_set('Asia/Manila');
        if(!empty($data['lfh_id'])){
            return DB::table('laboratory_formheader')
            ->where('lfh_id', $data['lfh_id'])
            ->where('management_id', $data['management_id'])
            ->update([
                'name' => $data['name'],
                'address' => $data['address'],
                'contact_number' => $data['contact_number'],
                'pathologist' => $data['pathologist'],
                'pathologist_lcn' => $data['pathologist_lcn'],
                'chief_medtech' => $data['chief_medtech'],
                'chief_medtech_lci' => $data['chief_medtech_lci'],
                'medtech' => $data['medtech'],
                'medtect_lci' => $data['medtect_lci'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }else{
            return DB::table('laboratory_formheader')
            ->insert([
                'lfh_id' => 'lfh-'.rand(0, 99).time(),
                'management_id' => $data['management_id'],
                'name' => $data['name'],
                'address' => $data['address'],
                'contact_number' => $data['contact_number'],
                'pathologist' => $data['pathologist'],
                'pathologist_lcn' => $data['pathologist_lcn'],
                'chief_medtech' => $data['chief_medtech'],
                'chief_medtech_lci' => $data['chief_medtech_lci'],
                'medtech' => $data['medtech'],
                'medtect_lci' => $data['medtect_lci'],
                'logo' => 'bmcdc_logo.png',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public static function getCurrentFormInformationResult($data)
    {
        return DB::table('laboratory_formheader')
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getAllLaboratoryReport($data)
    {
        return DB::table('cashier_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('patients.firstname', 'patients.lastname', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
            ->where('cashier_patientbills_records.bill_from', 'laboratory')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->groupBy('cashier_patientbills_records.trace_number')
            ->get();
    }

    public static function getAllTestByTracerNumber($data)
    {
        return DB::table('cashier_patientbills_records')
            ->select('bill_department as label', 'bill_department as value', 'patient_id')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('trace_number', $data['order_id'])
            ->where('bill_from', 'laboratory')
            ->groupBy('bill_department')
            ->get();
    }

    public static function getAllSpecTestByDepartment($data)
    {
        return DB::table('cashier_patientbills_records')
            ->select('cashier_patientbills_records.bill_name as label', 'cashier_patientbills_records.bill_name as value', 'cashier_patientbills_records.*')
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
            ->where('cashier_patientbills_records.trace_number', $data['order_id'])
            ->where('cashier_patientbills_records.bill_department', $data['department'])
            ->where('cashier_patientbills_records.bill_from', 'laboratory')
            ->groupBy('cashier_patientbills_records.bill_name')
            ->get();
    }

    public static function getLaboratoryGetToEditResult($data)
    {
        $database = $data['database'];
        $order_id = $data['order_id'];
        $trace_number = $data['trace_number'];

        return DB::table($database)
            ->where('order_id', $order_id)
            ->where('trace_number', $trace_number)
            ->where('order_status', 'completed')
            ->first();
    }

    public static function editResultHemaCBCConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_cbc')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'wbc' => $data['wbc'],
            'hct' => $data['hct'],
            'mpv' => $data['mpv'],
            'lym' => $data['lym'],
            'mcv' => $data['mcv'],
            'pdw' => $data['pdw'],
            'mid' => $data['mid'],
            'mch' => $data['mch'],
            'pct' => $data['pct'],
            'neut' => $data['neut'],
            'mchc' => $data['mchc'],
            'plt' => $data['plt'],
            'rbc' => $data['rbc'],
            'rdw_sd' => $data['rdw_sd'],
            'p_lcr' => $data['p_lcr'],
            'hgb' => $data['hgb'],
            'rdw_cv' => $data['rdw_cv'],
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultSerologyConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_sorology')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'hbsag' => $data['hbsag'],
            'hav' => $data['hav'],
            'hcv' => $data['hcv'],
            'vdrl_rpr' => $data['vdrl_rpr'],
            'anti_hbc_igm' => $data['anti_hbc_igm'],
            'beta_hcg_quali' => $data['beta_hcg_quali'],
            'h_pylori' => $data['h_pylori'],
            'typhidot' => $data['typhidot'],
            'hact' => $data['hact'],
            'ana' => $data['ana'],
            'dengue_test_result' => $data['dengue_test_result'],
            'syphilis_test_result' => $data['syphilis_test_result'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultHemaConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_hematology')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'hemoglobin' => $data['hemoglobin'],
            'hemoglobin_remarks' => $data['hemoglobin_remarks'],
            'hematocrit' => $data['hematocrit'],
            'hematocrit_remarks' => $data['hematocrit_remarks'],
            'rbc' => $data['rbc'],
            'rbc_remarks' => $data['rbc_remarks'],
            'wbc' => $data['wbc'],
            'wbc_remarks' => $data['wbc_remarks'],
            'platelet_count' => $data['platelet_count'],
            'platelet_count_remarks' => $data['platelet_count_remarks'],
            'differential_count' => $data['differential_count'],
            'differential_count_remarks' => $data['differential_count_remarks'],
            'neutrophil' => $data['neutrophil'],
            'neutrophil_remarks' => $data['neutrophil_remarks'],
            'lymphocyte' => $data['lymphocyte'],
            'lymphocyte_remarks' => $data['lymphocyte_remarks'],
            'monocyte' => $data['monocyte'],
            'monocyte_remarks' => $data['monocyte_remarks'],
            'eosinophil' => $data['eosinophil'],
            'eosinophil_remarks' => $data['eosinophil_remarks'],
            'basophil' => $data['basophil'],
            'basophil_remarks' => $data['basophil_remarks'],
            'bands' => $data['bands'],
            'bands_remarks' => $data['bands_remarks'],
            'abo_blood_type_and_rh_type' => $data['abo_blood_type_and_rh_type'],
            'abo_blood_type_and_rh_type_remarks' => $data['abo_blood_type_and_rh_type_remarks'],
            'bleeding_time' => $data['bleeding_time'],
            'bleeding_time_remarks' => $data['bleeding_time_remarks'],
            'clotting_time' => $data['clotting_time'],
            'clotting_time_remarks' => $data['clotting_time_remarks'],
            'mcv' => $data['mcv'],
            'mcv_remarks' => $data['mcv_remarks'],
            'mch' => $data['mch'],
            'mch_remarks' => $data['mch_remarks'],
            'mchc' => $data['mchc'],
            'mchc_remarks' => $data['mchc_remarks'],
            'rdw' => $data['rdw'],
            'rdw_remarks' => $data['rdw_remarks'],
            'mpv' => $data['mpv'],
            'mpv_remarks' => $data['mpv_remarks'],
            'pdw' => $data['pdw'],
            'pdw_remarks' => $data['pdw_remarks'],
            'pct' => $data['pct'],
            'pct_remarks' => $data['pct_remarks'],
            'blood_typing_with_rh' => $data['blood_typing_with_rh'],
            'blood_typing_with_rh_remarks' => $data['blood_typing_with_rh_remarks'],
            'ct_bt' => $data['ct_bt'],
            'ct_bt_remarks' => $data['ct_bt_remarks'],
            'esr' => $data['esr'],
            'esr_remarks' => $data['esr_remarks'],
            'ferritin' => $data['ferritin'],
            'ferritin_remarks' => $data['ferritin_remarks'],
            'aptt' => $data['aptt'],
            'aptt_remarks' => $data['aptt_remarks'],
            'peripheral_smear' => $data['peripheral_smear'],
            'peripheral_smear_remarks' => $data['peripheral_smear_remarks'],
            'protime' => $data['protime'],
            'protime_remarks' => $data['protime_remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultClinicalMicroConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_microscopy')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'chemical_test_color' => $data['color'],
            'chemical_test_transparency' => $data['transparency'],
            'chemical_test_ph' => $data['ph'],
            'chemical_test_spicific_gravity' => $data['specific_gravity'],
            'chemical_test_glucose' => $data['glucose'],
            'chemical_test_albumin' => $data['albumin'],
            'microscopic_test_squamous' => $data['squamous'],
            'microscopic_test_pus' => $data['pus'],
            'microscopic_test_redblood' => $data['redblood'],
            'microscopic_test_hyaline' => $data['hyaline'],
            'microscopic_test_wbc' => $data['wbc_cast'],
            'microscopic_test_rbc' => $data['rbc_cast'],
            'microscopic_test_fine_granular' => $data['fine_granualar'],
            'microscopic_test_coarse_granular' => $data['coarse_granualar'],
            'microscopic_test_calcium_oxalate' => $data['crystal_oxalate'],
            'microscopic_test_triple_phospahte' => $data['triple_phosphate'],
            'microscopic_test_leucine_tyrosine' => $data['leucine_tyrocine'],
            'microscopic_test_ammonium_biurate' => $data['ammoniume'],
            'microscopic_test_amorphous_urates' => $data['amorphous_urates'],
            'microscopic_test_amorphous_phosphates' => $data['amorphous_phosphate'],
            'microscopic_test_uricacid' => $data['uric_acid'],
            'microscopic_test_mucus_thread' => $data['mucus_thread'],
            'microscopic_test_bacteria' => $data['bacteria'],
            'microscopic_test_yeast' => $data['yeast'],
            'pregnancy_test_hcg_result' => $data['pregnancy_test'],
            'micral_test_result' => $data['micral_test_result'],
            'occult_blood_test_result' => $data['occult_blood_test_result'],
            'seminalysis_result' => $data['seminalysis_result'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultClinicalChemistryConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_chemistry')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'glucose' => $data['glucose'],
            'glucose_remarks' => $data['glucose_remarks'],
            'fbs' => $data['fbs'],
            'fbs_remarks' => $data['fbs_remarks'],
            'creatinine' => $data['creatinine'],
            'creatinine_remarks' => $data['creatinine_remarks'],
            'uric_acid' => $data['uric_acid'],
            'uric_acid_remarks' => $data['uric_acid_remarks'],
            'cholesterol' => $data['cholesterol'],
            'cholesterol_remarks' => $data['cholesterol_remarks'],
            'triglyceride' => $data['triglyceride'],
            'triglyceride_remarks' => $data['triglyceride_remarks'],
            'hdl_cholesterol' => $data['hdl_cholesterol'],
            'hdl_cholesterol_remarks' => $data['hdl_cholesterol_remarks'],
            'ldl_cholesterol' => $data['ldl_cholesterol'],
            'ldl_cholesterol_remarks' => $data['ldl_cholesterol_remarks'],
            'sgot' => $data['sgot'],
            'sgot_remarks' => $data['sgot_remarks'],
            'sgpt' => $data['sgpt'],
            'sgpt_remarks' => $data['sgpt_remarks'],
            'bun' => $data['bun'],
            'bun_remarks' => $data['bun_remarks'],
            'soduim' => $data['soduim'],
            'soduim_remarks' => $data['soduim_remarks'],
            'potassium' => $data['potassium'],
            'potassium_remarks' => $data['potassium_remarks'],
            'hba1c' => $data['hba1c'],
            'hba1c_remarks' => $data['hba1c_remarks'],
            'alkaline_phosphatase' => $data['alkaline_phosphatase'],
            'alkaline_phosphatase_remarks' => $data['alkaline_phosphatase_remarks'],
            'albumin' => $data['albumin'],
            'albumin_remarks' => $data['albumin_remarks'],
            'calcium' => $data['calcium'],
            'calcium_remarks' => $data['calcium_remarks'],
            'magnesium' => $data['magnesium'],
            'magnesium_remarks' => $data['magnesium_remarks'],
            'chloride' => $data['chloride'],
            'chloride_remarks' => $data['chloride_remarks'],
            'serum_uric_acid' => $data['serum_uric_acid'],
            'serum_uric_acid_remarks' => $data['serum_uric_acid_remarks'],
            'lipid_profile' => $data['lipid_profile'],
            'lipid_profile_remarks' => $data['lipid_profile_remarks'],
            'ldh' => $data['ldh'],
            'ldh_remarks' => $data['ldh_remarks'],
            'tpag_ratio' => $data['tpag_ratio'],
            'tpag_ratio_remarks' => $data['tpag_ratio_remarks'],
            'bilirubin' => $data['bilirubin'],
            'bilirubin_remarks' => $data['bilirubin_remarks'],
            'total_protein' => $data['total_protein'],
            'total_protein_remarks' => $data['total_protein_remarks'],
            'potassium_kplus' => $data['potassium_kplus'],
            'potassium_kplus_remarks' => $data['potassium_kplus_remarks'],
            'na_plus_kplus' => $data['na_plus_kplus'],
            'na_plus_kplus_remarks' => $data['na_plus_kplus_remarks'],
            'ggt' => $data['ggt'],
            'ggt_remarks' => $data['ggt_remarks'],
            'cholinesterase' => $data['cholinesterase'],
            'cholinesterase_remarks' => $data['cholinesterase_remarks'],
            'phosphorous' => $data['phosphorous'],
            'phosphorous_remarks' => $data['phosphorous_remarks'],
            'rbs' => $data['rbs'],
            'rbs_remarks' => $data['rbs_remarks'],
            'vldl' => $data['vldl'],
            'vldl_remarks' => $data['vldl_remarks'],
            'rbc_cholinesterase' => $data['rbc_cholinesterase'],
            'rbc_cholinesterase_remarks' => $data['rbc_cholinesterase_remarks'],
            'pro_calcitonin' => $data['pro_calcitonin'],
            'pro_calcitonin_crp_remarks' => $data['pro_calcitonin_crp_remarks'],
            'ogct_take_one_50grm' => $data['ogct_take_one_50grm'],
            'ogct_take_one_50grm_baseline' => $data['ogct_take_one_50grm_baseline'],
            'ogct_take_one_50grm_first_hour' => $data['ogct_take_one_50grm_first_hour'],
            'ogct_take_one_50grm_second_hour' => $data['ogct_take_one_50grm_second_hour'],
            'ogct_take_one_75grm' => $data['ogct_take_one_75grm'],
            'ogct_take_one_75grm_baseline' => $data['ogct_take_one_75grm_baseline'],
            'ogct_take_one_75grm_first_hour' => $data['ogct_take_one_75grm_first_hour'],
            'ogct_take_one_75grm_second_hour' => $data['ogct_take_one_75grm_second_hour'],
            'ogct_take_two_100grm' => $data['ogct_take_two_100grm'],
            'ogct_take_two_100grm_baseline' => $data['ogct_take_two_100grm_baseline'],
            'ogct_take_two_100grm_first_hour' => $data['ogct_take_two_100grm_first_hour'],
            'ogct_take_two_100grm_second_hour' => $data['ogct_take_two_100grm_second_hour'],
            'ogct_take_two_75grm' => $data['ogct_take_two_75grm'],
            'ogct_take_two_75grm_baseline' => $data['ogct_take_two_75grm_baseline'],
            'ogct_take_two_75grm_first_hour' => $data['ogct_take_two_75grm_first_hour'],
            'ogct_take_two_75grm_second_hour' => $data['ogct_take_two_75grm_second_hour'],
            'ogct_take_three_100grm' => $data['ogct_take_three_100grm'],
            'ogct_take_three_100grm_baseline' => $data['ogct_take_three_100grm_baseline'],
            'ogct_take_three_100grm_first_hour' => $data['ogct_take_three_100grm_first_hour'],
            'ogct_take_three_100grm_second_hour' => $data['ogct_take_three_100grm_second_hour'],
            'ogct_take_four_100grm' => $data['ogct_take_four_100grm'],
            'ogct_take_four_100grm_baseline' => $data['ogct_take_four_100grm_baseline'],
            'ogct_take_four_100grm_first_hour' => $data['ogct_take_four_100grm_first_hour'],
            'ogct_take_four_100grm_second_hour' => $data['ogct_take_four_100grm_second_hour'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function editResultStoolConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_stooltest')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'color' => $data['color'],
            'consistency' => $data['consistency'],
            'occult_blood_result' => $data['occult_blood_result'],
            'dfs_ascaris' => $data['dfs_ascaris'],
            'dfs_hookworm' => $data['dfs_hookworm'],
            'dfs_blastocystis' => $data['dfs_blastocystis'],
            'dfs_giardia_lamblia_cyst' => $data['dfs_giardia_lamblia_cyst'],
            'dfs_giardia_lamblia_trophozoite' => $data['dfs_giardia_lamblia_trophozoite'],
            'dfs_trichusris_trichuira' => $data['dfs_trichusris_trichuira'],
            'dfs_entamoeba_lamblia_cyst' => $data['dfs_entamoeba_lamblia_cyst'],
            'dfs_entamoeba_lamblia_trophozoite' => $data['dfs_entamoeba_lamblia_trophozoite'],
            'kt_ascaris' => $data['kt_ascaris'],
            'kt_hookworm' => $data['kt_hookworm'],
            'kt_blastocystis' => $data['kt_blastocystis'],
            'kt_giardia_lamblia_cyst' => $data['kt_giardia_lamblia_cyst'],
            'kt_giardia_lamblia_trophozoite' => $data['kt_giardia_lamblia_trophozoite'],
            'kt_trichusris_trichuira' => $data['kt_trichusris_trichuira'],
            'kt_entamoeba_lamblia_cyst' => $data['kt_entamoeba_lamblia_cyst'],
            'kt_entamoeba_lamblia_trophozoite' => $data['kt_entamoeba_lamblia_trophozoite'],
            'kk_ascaris' => $data['kk_ascaris'],
            'kk_hookworm' => $data['kk_hookworm'],
            'kk_blastocystis' => $data['kk_blastocystis'],
            'kk_giardia_lamblia_cyst' => $data['kk_giardia_lamblia_cyst'],
            'kk_giardia_lamblia_trophozoite' => $data['kk_giardia_lamblia_trophozoite'],
            'kk_trichusris_trichuira' => $data['kk_trichusris_trichuira'],
            'kk_entamoeba_lamblia_cyst' => $data['kk_entamoeba_lamblia_cyst'],
            'kk_entamoeba_lamblia_trophozoite' => $data['kk_entamoeba_lamblia_trophozoite'],
            'others' => $data['others'],
            'pus_cells' => $data['pus_cells'],
            'reb_blood_cells' => $data['reb_blood_cells'],
            'fat_globules' => $data['fat_globules'],
            'bacteria' => $data['bacteria'],
            'oil_droplets' => $data['oil_droplets'],
            'undigested_foods_paticles' => $data['undigested_foods_paticles'],
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultUrinalysisConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_urinalysis')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'color' => $data['color'],
            'transparency' => $data['transparency'],
            'reaction' => $data['reaction'],
            'sp_gravity' => $data['sp_gravity'],
            'albumin' => $data['albumin'],
            'sugar' => $data['sugar'],
            'pus_cell' => $data['pus_cell'],
            'rbc' => $data['rbc'],
            'epithelial_cell' => $data['epithelial_cell'],
            'mucus_threads' => $data['mucus_threads'],
            'renal_cell' => $data['renal_cell'],
            'yeast_cell' => $data['yeast_cell'],
            'hyaline' => $data['hyaline'],
            'rbc_cast' => $data['rbc_cast'],
            'wbc_cast' => $data['wbc_cast'],
            'coarse_granular_cast' => $data['coarse_granular_cast'],
            'fine_granular_cast' => $data['fine_granular_cast'],
            'pus_in_clumps' => $data['pus_in_clumps'],
            'rbc_in_clumps' => $data['rbc_in_clumps'],
            'calcium_oxalate' => $data['calcium_oxalate'],
            'uric_acid' => $data['uric_acid'],
            'amorphous_phosphate' => $data['amorphous_phosphate'],
            'amorphous_urate' => $data['amorphous_urate'],
            'calcium_carbonate' => $data['calcium_carbonate'],
            'ammonium_biurate' => $data['ammonium_biurate'],
            'triple_phosphate' => $data['triple_phosphate'],
            'spermatozoa' => $data['spermatozoa'],
            'trichomonas_vaginalis' => $data['trichomonas_vaginalis'],
            'micral_test' => $data['micral_test'],
            'urine_ketone' => $data['urine_ketone'],
            'others' => $data['others'],
            'remarks' => $data['remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultThyroidProfConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_thyroid_profile')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            't3' => $data['t3'],
            't3_remarks' => $data['t3_remarks'],
            't4' => $data['t4'],
            't4_remarks' => $data['t4_remarks'],
            'tsh' => $data['tsh'],
            'tsh_remarks' => $data['tsh_remarks'],
            'ft4' => $data['ft4'],
            'ft4_remarks' => $data['ft4_remarks'],
            'ft3' => $data['ft3'],
            'ft3_remarks' => $data['ft3_remarks'],
            't3t4' => $data['t3t4'],
            't3t4_remarks' => $data['t3t4_remarks'],
            'fht' => $data['fht'],
            'fht_remarks' => $data['fht_remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultMiscellaneousConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_miscellaneous')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'speciment' => $data['speciment'],
            'test' => $data['test'],
            'result' => $data['result'],
            'pregnancy_test_urine_result' => $data['pregnancy_test_urine_result'],
            'pregnancy_test_serum_result' => $data['pregnancy_test_serum_result'],
            'papsmear_test_result' => $data['papsmear_test_result'],
            'papsmear_test_with_gramstain_result' => $data['papsmear_test_with_gramstain_result'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultHepatitisProfileConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_hepatitis_profile')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'hbsag_quali' => $data['hbsag_quali'],
            'hbsag_quali_remarks' => $data['hbsag_quali_remarks'],
            'antihbs_quali' => $data['antihbs_quali'],
            'antihbs_quali_remarks' => $data['antihbs_quali_remarks'],
            'antihcv_quali' => $data['antihcv_quali'],
            'antihcv_quali_remarks' => $data['antihcv_quali_remarks'],
            'hbsag_quanti' => $data['hbsag_quanti'],
            'hbsag_quanti_remarks' => $data['hbsag_quanti_remarks'],
            'antihbs_quanti' => $data['antihbs_quanti'],
            'antihbs_quanti_remarks' => $data['antihbs_quanti_remarks'],
            'hbeaag' => $data['hbeaag'],
            'hbeaag_remarks' => $data['hbeaag_remarks'],
            'antihbe' => $data['antihbe'],
            'antihbe_remarks' => $data['antihbe_remarks'],
            'antihbc_igm' => $data['antihbc_igm'],
            'antihbc_igm_remarks' => $data['antihbc_igm_remarks'],
            'antihav_igm' => $data['antihav_igm'],
            'antihav_igm_remarks' => $data['antihav_igm_remarks'],
            'anti_havigm_igg' => $data['anti_havigm_igg'],
            'anti_havigm_igg_remarks' => $data['anti_havigm_igg_remarks'],
            'antihbc_iggtotal' => $data['antihbc_iggtotal'],
            'antihbc_iggtotal_remarks' => $data['antihbc_iggtotal_remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultCovid19Confirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_covid19_test')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'rapid_test_result' => $data['rapid_test_result'],
            'rapid_test_result_remarks' => $data['rapid_test_result_remarks'],
            'antigen_test_result' => $data['antigen_test_result'],
            'antigen_test_result_remarks' => $data['antigen_test_result_remarks'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultTumorMakerConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_tumor_maker')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'aso_result' => $data['aso_result'],
            'biopsy_result' => $data['biopsy_result'],
            'c3_result' => $data['c3_result'],
            'ca_125_result' => $data['ca_125_result'],
            'cea_result' => $data['cea_result'],
            'psa_prostate_result' => $data['psa_prostate_result'],
            'afp_result' => $data['afp_result'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function editResultDrugTestConfirm($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('laboratory_drug_test')
        ->where('order_id', $data['order_id'])
        ->where('trace_number', $data['trace_number'])
        ->update([
            'two_panels_result' => $data['two_panels_result'],
            'three_panels_result' => $data['three_panels_result'],
            'five_panels_result' => $data['five_panels_result'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function getAllLaboratoryReportFilter($data)
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
        from cashier_patientbills_records where management_id = '$management_id' AND main_mgmt_id = '$main_mgmt_id' AND bill_from = 'laboratory' AND created_at >= '$strDaily' AND created_at <= '$lstDaily' GROUP BY trace_number ORDER BY created_at DESC ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function getCompleteMedCertOrderDetails($data){
        return DB::table('doctors_medical_certificate_ordered')
        ->join('patients', 'patients.patient_id', '=', 'doctors_medical_certificate_ordered.patient_id')
        ->join('doctors', 'doctors.doctors_id', '=', 'doctors_medical_certificate_ordered.doctors_id')
        ->select('doctors_medical_certificate_ordered.*', 'patients.firstname', 'patients.lastname', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city', 'doctors.name as doctor_name', 'doctors.specialization as doctor_specialization', 'doctors.cil_umn as doctor_lic')
        ->where('doctors_medical_certificate_ordered.patient_id', $data['patient_id'])
        ->where('doctors_medical_certificate_ordered.trace_number', $data['trace_number'])
        ->whereNotNull('doctors_medical_certificate_ordered.diagnosis_findings')
        ->orderBy('doctors_medical_certificate_ordered.created_at', 'DESC')
        ->get();
    }
    
    public static function getCompleteSarsCovOrderDetails($data)
    {
        $trace_number = $data['trace_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, medtech as medicalTechnologist,

            (SELECT birthday FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as birthday,
            (SELECT firstname FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as middle,
            (SELECT barangay FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as barangay,
            (SELECT gender FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as gender,
            (SELECT city FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as city,
            (SELECT street FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as street,
            (SELECT zip FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as zip,
            (SELECT image FROM patients WHERE patients.patient_id = laboratory_sars_cov.patient_id LIMIT 1) as image,

            (SELECT user_fullname FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as medtech,
            (SELECT lic_no FROM laboratory_list WHERE laboratory_list.user_id = medicalTechnologist LIMIT 1) as lic_no,

            (SELECT pathologist FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist,
            (SELECT pathologist_lcn FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as pathologist_lcn,
            (SELECT chief_medtech FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech,
            (SELECT chief_medtech_lci FROM laboratory_formheader WHERE laboratory_formheader.management_id = '$management_id' LIMIT 1) as chief_medtech_lci

        FROM laboratory_sars_cov WHERE trace_number = '$trace_number' AND order_status = 'completed' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function getItemReagents($data){
        return DB::table('laboratory_items')
        ->where('management_id', $data['management_id'])
        ->get();
    }

    public static function createRequestItemReagents($data){
        $query = DB::table('laboratory_items')->where('item_id', $data['item_id'])->first();
        $lab_id = _Laboratory::getLaboratoryId($data['user_id'])->laboratory_id;

        return DB::table('laboratory_request_item_temp')
        ->insert([
            'lrit_id' => 'lrit-'.rand(0, 99).time(),
            'management_id' => $data['management_id'],
            'laboratory_id' => $lab_id,
            'item' => $query->item,
            'item_haptech_id' => $query->item_haptech_id,
            'description' => $query->description,
            'unit' => $query->unit,
            'supplier' => $query->supplier,
            'msrp' => $query->msrp,
        ]);
    }
    
    public static function getItemRequestTemp($data){
        return DB::table('laboratory_request_item_temp')
        ->where('management_id', $data['management_id'])
        ->get();
    }
    
    public static function removeRequestItemReagents($data){
        $lab_id = _Laboratory::getLaboratoryId($data['user_id'])->laboratory_id;

        return DB::table('laboratory_request_item_temp')
        ->where('lrit_id', $data['lrit_id'])
        ->where('management_id', $data['management_id'])
        ->where('laboratory_id', $lab_id)
        ->delete();
    }

    public static function confirmRequestItemReagents($data){
        $lab_id = _Laboratory::getLaboratoryId($data['user_id'])->laboratory_id;
        $query = DB::table('laboratory_request_item_temp')->where('management_id', $data['management_id'])->where('laboratory_id', $lab_id)->get();
        $records = [];
        $request_id = 'req-'.rand(0, 99).time();

        foreach ($query as $v) {
            $records[] = array(
                'lri_id' => rand(0, 9999) . '-' . time(),
                'request_id' => $request_id,
                'management_id' => $v->management_id,
                'laboratory_id' => $v->laboratory_id,
                'item' => $v->item,
                'item_haptech_id' => $v->item_haptech_id,
                'description' => $v->description,
                'unit' => $v->unit,
                'supplier' => $v->supplier,
                'msrp' => $v->msrp,
                'mark_as_okay' => 0,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
        }

        DB::table('laboratory_request_item')->insert($records);

        return DB::table('laboratory_request_item_temp')
        ->where('management_id', $data['management_id'])
        ->where('laboratory_id', $lab_id)
        ->delete();
    }

    public static function getItemRequestConfirm($data){
        return DB::table('laboratory_request_item')
        ->where('management_id', $data['management_id'])
        ->groupBy('request_id')
        ->get();
    }

    //electrolytes
    public static function getOrderClinicalChemistryNewElectro($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_electrolytes')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_electrolytes.patient_id')
            ->select('laboratory_electrolytes.*', 'patients.*', 'laboratory_electrolytes.created_at as date_ordered')
            ->where('laboratory_electrolytes.patient_id', $data['patient_id'])
            ->where('laboratory_electrolytes.order_status', 'new-order-paid')
            ->groupBy('laboratory_electrolytes.trace_number')
            ->get();
    }
    
}
