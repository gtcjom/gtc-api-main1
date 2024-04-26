<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _OperatingRoom extends Model
{
    use HasFactory;

    public static function getOrIdByManagementId($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('operating_room_account')->where('management_id', $data['management_id'])->first();
    }

    public static function newOperatingRoomService($data)
    {

        date_default_timezone_set('Asia/Manila');

        return DB::table('operating_room_services')->insert([
            'ors_id' => 'ors-' . rand(0, 99999) . time(),
            'management_id' => $data['management_id'],
            'or_id' => _OperatingRoom::getOrIdByManagementId($data)->or_id,
            'service' => $data['service'],
            'rate' => $data['rate'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getAllOperatingRoomService($data)
    {
        return DB::table('operating_room_services')
            ->where('management_id', $data['management_id'])
            ->where('or_id', _OperatingRoom::getOrIdByManagementId($data)->or_id)
            ->get();
    }

    public static function getAllPatientForOR($data)
    {
        $mgmt_id = $data['management_id'];

        $query = "SELECT *, patient_id as pId,
        (SELECT firstname from patients where patient_id = pId) as firstname,
        (SELECT lastname from patients where patient_id = pId) as lastname,
        (SELECT image from patients where patient_id = pId) as image,
        (SELECT birthday from patients where patient_id = pId) as birthday,
        (SELECT street from patients where patient_id = pId) as street,
        (SELECT barangay from patients where patient_id = pId) as barangay,
        (SELECT city from patients where patient_id = pId) as city

        from emergency_room_patients where management_id = '$mgmt_id' and process_status ='or-nurse' and is_rod_received = 1 ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
        // from emergency_room_patients where management_id = '$mgmt_id' and process_status ='or-room' and is_rod_received = 1 ";
    }

    public static function getOrHeaderInfo($data)
    {
        return DB::table('operating_room_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'operating_room_account.user_id')
            ->select('operating_room_account.or_id', 'operating_room_account.user_fullname as name', 'operating_room_account.image', 'operating_room_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('operating_room_account.user_id', $data['user_id'])
            ->first();
    }

    public static function getOrSpecRole($data)
    {
        return DB::table('operating_room_account')
            ->where('user_id', $data['user_id'])
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getPhamacyListByTypeGroupById($data)
    {
        return DB::table('pharmacy')
            ->where('management_id', $data['management_id'])
            ->where('pharmacy_type', $data['type'])
            ->groupBy('pharmacy_id')
            ->get();
    }

    public static function getPharmaProductList($data)
    {
        return DB::table('pharmacyhospital_products')
            ->select('*', 'product as label', 'product_id as value')
            ->where('management_id', $data['management_id'])
            ->where('pharmacy_id', $data['pharmacy_id'])
            ->get();
    }

    public static function getServiceDetailsById($data)
    {
        return DB::table('operating_room_services')
            ->where('management_id', $data['management_id'])
            ->where('ors_id', $data['service_id'])
            ->get();
    }

    public static function addItemToUsave($data)
    {

        date_default_timezone_set('Asia/Manila');
        return DB::table('operating_room_patients_items_temp')
            ->insert([
                'management_id' => $data['management_id'],
                'or_id' => _OperatingRoom::getOrIdByManagementId($data)->or_id,
                'patient_id' => $data['patient_id'],
                'rod_doctors_id' => $data['rod_doctors_id'],
                'trace_number' => $data['trace_number'],
                'item_id' => $data['item_id'],
                'item' => $data['item'],
                'qty' => $data['quantity'],
                'item_fromid' => $data['pharmacy_id'],
                'item_fromtype' => $data['type'],
                'process_by' => $data['user_id'],
            ]);
    }

    public static function getItemFromUsave($data)
    {
        return DB::table('operating_room_patients_items_temp')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function removeItemFromUsave($data)
    {
        return DB::table('operating_room_patients_items_temp')
            ->where('id', $data['remove_id'])
            ->delete();
    }

    public static function processUnsaveOrder($data)
    {

        date_default_timezone_set('Asia/Manila');
        $unsaveItems = DB::table('operating_room_patients_items_temp')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();

        $orders = [];
        foreach ($unsaveItems as $v) {
            $orders[] = array(
                'orpi_id' => 'orpi-' . rand(0, 99999) . '-' . time(),
                'management_id' => $v->management_id,
                'or_id' => $v->or_id,
                'patient_id' => $v->patient_id,
                'rod_doctors_id' => $v->rod_doctors_id,
                'trace_number' => $v->trace_number,
                'item_id' => $v->item_id,
                'item' => $v->item,
                'item_fromid' => $v->item_fromid,
                'item_fromtype' => $v->item_fromtype,
                'qty' => $v->qty,
                'is_processed' => 0,
                'process_by' => $v->process_by,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('operating_room_patients_items')->insert($orders);

        return DB::table('operating_room_patients_items_temp')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->delete();
    }

    public static function getProcessedOrderItems($data)
    {
        return DB::table('operating_room_patients_items')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function getAllPatientInRoom($data)
    {
        $mgmt_id = $data['management_id'];

        $query = "SELECT *, patient_id as pId, or_assigned_doctor as _docid,
        -- or_service_usedid as _sid,
        (SELECT firstname from patients where patient_id = pId) as firstname,
        (SELECT lastname from patients where patient_id = pId) as lastname,
        (SELECT image from patients where patient_id = pId) as image,
        (SELECT birthday from patients where patient_id = pId) as birthday,
        (SELECT street from patients where patient_id = pId) as street,
        (SELECT barangay from patients where patient_id = pId) as barangay,
        (SELECT city from patients where patient_id = pId) as city,
        (SELECT name from doctors where doctors_id = _docid) as docname

        -- (SELECT service from operating_room_services where ors_id = _sid) as service_used


        from operating_room_patients where management_id = '$mgmt_id' and or_completed = 0 and is_or_started  = 1";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function sentPatientToRoomAndStartOp($data)
    {

        date_default_timezone_set('Asia/Manila');

        DB::table('emergency_room_patients')
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'process_status' => 'or-room',
                'sent_to_or_room' => 1,
                'is_ornurse_processed' => 1,
                'is_docu_processed' => 1,
                'is_ornurse_processed_date' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::table('operating_room_patients')
            ->insert([
                'orp_id' => 'orp-' . rand(0, 9999) . time(),
                'management_id' => $data['management_id'],
                'or_id' => _OperatingRoom::getOrIdByManagementId($data)->or_id,
                'patient_id' => $data['patient_id'],
                'rod_doctors_id' => $data['is_rod_doctor_id'],
                'trace_number' => $data['trace_number'],
                'or_service_usedid' => $data['or_service_usedid'],
                'is_or_started' => 1,
                'or_started_on' => date('Y-m-d H:i:s', strtotime($data['date_started'])),
                'or_assigned_doctor' => $data['doctor_assign'],
                'or_completed' => 0,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function setOperationComplete($data)
    {
        DB::table('emergency_room_patients')
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'process_status' => 'or-completed',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::table('operating_room_patients')
            ->where('orp_id', $data['orp_id'])
            ->update([
                'or_completed' => 1,
                'or_completed_status' => $data['remarks'],
                'completed_on' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllCompletedOperation($data)
    {
        $mgmt_id = $data['management_id'];

        $query = "SELECT *, patient_id as pId, or_service_usedid as _sid,
        (SELECT firstname from patients where patient_id = pId) as firstname,
        (SELECT lastname from patients where patient_id = pId) as lastname,
        (SELECT image from patients where patient_id = pId) as image,
        (SELECT birthday from patients where patient_id = pId) as birthday,
        (SELECT street from patients where patient_id = pId) as street,
        (SELECT barangay from patients where patient_id = pId) as barangay,
        (SELECT city from patients where patient_id = pId) as city,
        (SELECT service from operating_room_services where ors_id = _sid) as service_used


        from operating_room_patients where management_id = '$mgmt_id' and or_completed = 1 and is_or_started  = 1";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function orAccountUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('operating_room_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function orAccountUpdatePersonalInfo($data)
    {
        return DB::table('operating_room_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getOrInfoById($data)
    {
        $query = "SELECT * FROM operating_room_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAllPatientsOROrders($data)
    {
        return DB::table('operating_room_patients')
        // ->leftJoin("operating_room_services", "operating_room_services.ors_id", "=", "operating_room_patients.or_service_usedid")
            ->leftJoin("doctors", "doctors.doctors_id", "=", "operating_room_patients.or_assigned_doctor")
        // ->select('operating_room_services.service as or_services', 'operating_room_patients.*')
            ->select('operating_room_patients.*', 'doctors.name as docname')
            ->where('operating_room_patients.patient_id', $data['patient_id'])
            ->orderBy('operating_room_patients.created_at', 'DESC')
            ->get();
    }

    public static function getOrderDetails($data)
    {
        return DB::table('operating_room_patients_items')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function updateConfirmedOrderUsage($data)
    {
        date_default_timezone_set('Asia/Manila');

        $item_fromid = $data['item_fromid'];

        for ($i = 0; $i < count($item_fromid); $i++) {
            DB::table('operating_room_patients_items')
                ->where('item_fromid', $item_fromid[$i])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'qty_used' => $data['qty_used'][$i],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $total = (int) $data['qty'][$i] - (int) $data['qty_used'][$i];

            if ((int) $total == 0) {
                //WHOLE SALES
                $query = DB::table('pharmacyhospital_products')->join('pharmacyhospital_inventory', 'pharmacyhospital_inventory.product_id', '=', 'pharmacyhospital_products.product_id')->select('pharmacyhospital_products.*', 'pharmacyhospital_inventory.unit')->where('pharmacyhospital_products.pharmacy_id', $item_fromid[$i])->first();

                DB::table('pharmacyhospital_sales')
                    ->insert([
                        'sales_id' => 'si-' . rand(0, 9) . time(),
                        'product_id' => $query->product_id,
                        'pharmacy_id' => $query->pharmacy_id,
                        'management_id' => $data['management_id'],
                        'username' => $data['user_id'],
                        'product' => $query->product,
                        'description' => $query->description,
                        'unit' => $query->unit,
                        'quantity' => (int) $data['qty'][$i],
                        'total' => (int) $data['qty'][$i] * (int) $query->srp,
                        'dr_no' => 'NO DR',
                        'sales_from' => 'local',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
            }
            if ((int) $data['qty'][$i] > $data['qty_used'][$i] && (int) $data['qty'][$i] != $data['qty_used'][$i]) {
                $query = DB::table('pharmacyhospital_products')->join('pharmacyhospital_inventory', 'pharmacyhospital_inventory.product_id', '=', 'pharmacyhospital_products.product_id')->select('pharmacyhospital_products.*', 'pharmacyhospital_inventory.unit')->where('pharmacyhospital_products.pharmacy_id', $item_fromid[$i])->first();
                //SALES $data['qty_used']
                if ((int) $data['qty_used'][$i] != 0) {
                    DB::table('pharmacyhospital_sales')
                        ->insert([
                            'sales_id' => 'si-' . rand(0, 9) . time(),
                            'product_id' => $query->product_id,
                            'pharmacy_id' => $query->pharmacy_id,
                            'management_id' => $data['management_id'],
                            'username' => $data['user_id'],
                            'product' => $query->product,
                            'description' => $query->description,
                            'unit' => $query->unit,
                            'quantity' => (int) $data['qty_used'][$i],
                            'total' => (int) $data['qty_used'][$i] * (int) $query->srp,
                            'dr_no' => 'NO DR',
                            'sales_from' => 'local',
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                }

                //RETURN $total
                DB::table('pharmacyhospital_history')
                    ->insert([
                        'pch_id' => 'pch-' . rand(0, 9) . time(),
                        'product_id' => $query->product_id,
                        'pharmacy_id' => $query->pharmacy_id,
                        'management_id' => $data['management_id'],
                        'username' => $data['user_id'],
                        'product' => $query->product,
                        'description' => $query->description,
                        'unit' => $query->unit,
                        'quantity' => (float) $total,
                        'request_type' => 'IN',
                        'dr_no' => 'NO DR',
                        'supplier' => $data['supplier'],
                        'remarks' => 'Return item',
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }

        return DB::table('operating_room_patients')
            ->where('trace_number', $data['trace_number'])
            ->update([
                'is_confirm_if_return' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllDoctorList($data)
    {
        return DB::table('doctors')->where('management_id', $data['management_id'])->get();
    }

    public static function insertSendPatientToBilling($data)
    {
        $query = DB::connection('mysql')->table('operating_room_patients_items')
            ->join('operating_room_patients', 'operating_room_patients.trace_number', '=', 'operating_room_patients_items.trace_number')
            ->select('operating_room_patients_items.qty_used', 'operating_room_patients_items.item', 'operating_room_patients_items.amount', 'operating_room_patients.is_confirm_if_return', 'operating_room_patients.or_assigned_doctor')
            ->where('operating_room_patients_items.trace_number', $data['trace_number'])
            ->where('operating_room_patients_items.patient_id', $data['patient_id'])
            ->where('operating_room_patients.is_confirm_if_return', '<>', 0)
            ->get();

        $billing = [];

        foreach ($query as $v) {
            if ((int) $v->qty_used > 0) {
                $billing[] = array(
                    'bltp_id' => rand(0, 9999) . '-' . time(),
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'patient_id' => $data['patient_id'],
                    'doctor_id' => $v->or_assigned_doctor,
                    'trace_number' => $data['trace_number'],
                    'description' => $v->item,
                    'quantity' => (float) $v->qty_used,
                    'price' => (float) $v->amount,
                    'amount_to_pay' => (float) $v->qty_used * (float) $v->amount,
                    'payment_status' => 'Unpaid',
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        DB::connection('mysql')->table('billing_list_to_paid')->insert([
            'bltp_id' => rand(0, 9999) . '-' . time(),
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $query[0]->or_assigned_doctor,
            'trace_number' => $data['trace_number'],
            'description' => "Doctor PF",
            'quantity' => 1,
            'amount_to_pay' => null,
            'is_edit_by_doctor' => 0,
            'payment_status' => 'Unpaid',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::connection('mysql')->table('billing_list_to_paid')->insert($billing);

    }

    public static function getDoctorsListByManagement($data)
    {
        return DB::table('doctors')->where('management_id', $data['management_id'])->get();
    }

    public static function getChartFrontPage($data)
    {
        return DB::table('csqmmh_chart_frontpage')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newChartFrontPage($data)
    {

        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_frontpage')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'admitting_dx' => $data['admitting_dx'],
                    'admitting_doctors' => $data['admitting_doctors'],
                    'admission_datetime' => empty($data['admission_datetime']) ? null : date('Y-m-d H:i:s', strtotime($data['admission_datetime'])),
                    'discharged_datetime' => empty($data['discharged_datetime']) ? null : date('Y-m-d H:i:s', strtotime($data['discharged_datetime'])),
                    'checklist_front_sheet' => $data['checklist_front_sheet'],

                    'checklist_covid_form' => $data['checklist_covid_form'],
                    'checklist_surgical_consent' => $data['checklist_surgical_consent'],
                    'checklist_cardio_clearance' => $data['checklist_cardio_clearance'],
                    'checklist_laboratory' => $data['checklist_laboratory'],
                    'checklist_periorative_record' => $data['checklist_periorative_record'],
                    'checklist_medication_treatment_sheet' => $data['checklist_medication_treatment_sheet'],
                    'checklist_doctor_consultation_form' => $data['checklist_doctor_consultation_form'],
                    'checklist_who_surgical_checklist' => $data['checklist_who_surgical_checklist'],
                    'checklist_operative_record' => $data['checklist_operative_record'],
                    'checklist_anesthesia_record' => $data['checklist_anesthesia_record'],
                    'checklist_doctors_order' => $data['checklist_doctors_order'],
                    'checklist_pacu_nursenotes' => $data['checklist_pacu_nursenotes'],
                    'checklist_discharged_instruction' => $data['checklist_discharged_instruction'],
                    'checklist_medical_abstract' => $data['checklist_medical_abstract'],
                    'checklist_nurse_bedsidenotes' => $data['checklist_nurse_bedsidenotes'],
                    'checklist_phic_cf4' => $data['checklist_phic_cf4'],
                    'checklist_billing' => $data['checklist_billing'],
                    'nurse_incharge' => $data['nurse_incharge'],
                    'med_record_associates' => $data['med_record_associates'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_frontpage')->insert([
            'cdc_id' => 'cdc-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'case_no' => $data['case_no'],
            'trace_number' => $data['trace_number'],
            'admitting_dx' => $data['admitting_dx'],
            'admitting_doctors' => $data['admitting_doctors'],
            'admission_datetime' => empty($data['admission_datetime']) ? null : date('Y-m-d H:i:s', strtotime($data['admission_datetime'])),
            'discharged_datetime' => empty($data['discharged_datetime']) ? null : date('Y-m-d H:i:s', strtotime($data['discharged_datetime'])),
            'checklist_front_sheet' => $data['checklist_front_sheet'],

            'checklist_covid_form' => $data['checklist_covid_form'],
            'checklist_surgical_consent' => $data['checklist_surgical_consent'],
            'checklist_cardio_clearance' => $data['checklist_cardio_clearance'],
            'checklist_laboratory' => $data['checklist_laboratory'],
            'checklist_periorative_record' => $data['checklist_periorative_record'],
            'checklist_medication_treatment_sheet' => $data['checklist_medication_treatment_sheet'],
            'checklist_doctor_consultation_form' => $data['checklist_doctor_consultation_form'],
            'checklist_who_surgical_checklist' => $data['checklist_who_surgical_checklist'],
            'checklist_operative_record' => $data['checklist_operative_record'],
            'checklist_anesthesia_record' => $data['checklist_anesthesia_record'],
            'checklist_doctors_order' => $data['checklist_doctors_order'],
            'checklist_pacu_nursenotes' => $data['checklist_pacu_nursenotes'],
            'checklist_discharged_instruction' => $data['checklist_discharged_instruction'],
            'checklist_medical_abstract' => $data['checklist_medical_abstract'],
            'checklist_nurse_bedsidenotes' => $data['checklist_nurse_bedsidenotes'],
            'checklist_phic_cf4' => $data['checklist_phic_cf4'],
            'checklist_billing' => $data['checklist_billing'],
            'nurse_incharge' => $data['nurse_incharge'],
            'med_record_associates' => $data['med_record_associates'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getChartInformationSheet($data)
    {
        return DB::table('csqmmh_chart_information_sheet')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newChartInformationSheet($data)
    {

        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_information_sheet')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'case_no' => $data['case_no'],
                    'hospital_no' => $data['hospital_no'],
                    'forwarded_to' => $data['forwarded_to'],
                    'cash_trans_date' => date('Y-m-d', strtotime($data['cash_trans_date'])),
                    'ctas' => $data['ctas'],
                    'nationality' => $data['nationality'],
                    'patient_category' => $data['patient_category'],
                    'employer' => $data['employer'],
                    'employer_address' => $data['employer_address'],
                    'employer_no' => $data['employer_no'],
                    'fathers' => $data['fathers'],
                    'fathers_address' => $data['fathers_address'],
                    'fathers_contact' => $data['fathers_contact'],
                    'mothers' => $data['mothers'],
                    'mothers_address' => $data['mothers_address'],
                    'mothers_contact' => $data['mothers_contact'],
                    'attending_physician' => $data['attending_physician'],
                    'reffered_by' => $data['reffered_by'],
                    'allergic_to' => $data['allergic_to'],
                    'hosipitalization_hmo_coverage' => $data['hosipitalization_hmo_coverage'],
                    'insurance' => $data['insurance'],
                    'name_of_informant' => $data['name_of_informant'],
                    // 'date_furnished' => date('Y-m-d', strtotime($data['date_furnished'])),
                    'address_of_informant' => $data['address_of_informant'],
                    'informant_contact_no' => $data['informant_contact_no'],
                    'relation_to_patient' => $data['relation_to_patient'],
                    'chief_complaint' => $data['chief_complaint'],
                    'diagnosis' => $data['diagnosis'],
                    'surgical_procedures' => $data['surgical_procedures'],
                    'other_operations' => $data['other_operations'],
                    'other_operations_disposition' => $data['other_operations_disposition'],
                    'other_operations_results' => $data['other_operations_results'],
                    'other_operations_biopsy' => $data['other_operations_biopsy'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_information_sheet')->insert([
            'cpis_id' => 'cpis-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],
            'hospital_no' => $data['hospital_no'],
            'patient_no' => $data['patient_no'],
            'forwarded_to' => $data['forwarded_to'],
            'cash_trans_date' => date('Y-m-d', strtotime($data['cash_trans_date'])),
            'ctas' => $data['ctas'],
            'nationality' => $data['nationality'],
            'patient_category' => $data['patient_category'],
            'employer' => $data['employer'],
            'employer_address' => $data['employer_address'],
            'employer_no' => $data['employer_no'],
            'fathers' => $data['fathers'],
            'fathers_address' => $data['fathers_address'],
            'fathers_contact' => $data['fathers_contact'],
            'mothers' => $data['mothers'],
            'mothers_address' => $data['mothers_address'],
            'mothers_contact' => $data['mothers_contact'],
            'attending_physician' => $data['attending_physician'],
            'reffered_by' => $data['reffered_by'],
            'allergic_to' => $data['allergic_to'],
            'hosipitalization_hmo_coverage' => $data['hosipitalization_hmo_coverage'],
            'insurance' => $data['insurance'],
            'date_furnished' => date('Y-m-d', strtotime($data['date_furnished'])),
            'address_of_informant' => $data['address_of_informant'],
            'relation_to_patient' => $data['relation_to_patient'],
            'chief_complaint' => $data['chief_complaint'],
            'diagnosis' => $data['diagnosis'],
            'surgical_procedures' => $data['surgical_procedures'],
            'other_operations' => $data['other_operations'],
            'other_operations_disposition' => $data['other_operations_disposition'],
            'other_operations_results' => $data['other_operations_results'],
            'other_operations_biopsy' => $data['other_operations_biopsy'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function getSurgeryContent($data)
    {
        return DB::table('csqmmh_chart_consent_of_surgery')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newSurgeryContent($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_consent_of_surgery')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'doctors_id' => $data['doctors_id'],
                    'date_now' => !empty($data['date_now']) ? date('Y-m-d H:i:s', strtotime($data['date_now'])) : null,
                    'op_doctor' => $data['op_doctor'],
                    'op_permission' => $data['op_permission'],
                    'op_procedure' => $data['op_procedure'],
                    'op_procedure_desc' => $data['op_procedure_desc'],
                    'op_procedure_complications' => $data['op_procedure_complications'],
                    'op_anesthesia' => $data['op_anesthesia'],
                    'op_anesthesia_desc' => $data['op_anesthesia_desc'],
                    'op_blood_admin' => $data['op_blood_admin'],
                    'op_under_pnp_pro' => $data['op_under_pnp_pro'],
                    'op_health_profession' => $data['op_health_profession'],
                    'op_physician_incharge' => $data['op_physician_incharge'],
                    'op_physician_incharge_datetime' => !empty($data['op_physician_incharge_datetime']) ? date('Y-m-d H:i:s', strtotime($data['op_physician_incharge_datetime'])) : null,
                    'op_interpreter' => $data['op_interpreter'],
                    'op_interpreter_datetime' => !empty($data['op_interpreter_datetime']) ? date('Y-m-d H:i:s', strtotime($data['op_interpreter_datetime'])) : null,
                    'op_legal_rep' => $data['op_legal_rep'],
                    'op_legal_rep_age' => $data['op_legal_rep_age'],
                    'op_legal_rep_relation' => $data['op_legal_rep_relation'],
                    'op_legal_rep_datetime' => !empty($data['op_legal_rep_datetime']) ? date('Y-m-d H:i:s', strtotime($data['op_legal_rep_datetime'])) : null,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_consent_of_surgery')->insert([
            'cccos_id' => 'cccos-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],
            'doctors_id' => $data['doctors_id'],
            'date_now' => !empty($data['date_now']) ? date('Y-m-d H:i:s', strtotime($data['date_now'])) : null,
            'op_doctor' => $data['op_doctor'],
            'op_permission' => $data['op_permission'],
            'op_procedure' => $data['op_procedure'],
            'op_procedure_desc' => $data['op_procedure_desc'],
            'op_procedure_complications' => $data['op_procedure_complications'],
            'op_anesthesia' => $data['op_anesthesia'],
            'op_anesthesia_desc' => $data['op_anesthesia_desc'],
            'op_blood_admin' => $data['op_blood_admin'],
            'op_under_pnp_pro' => $data['op_under_pnp_pro'],
            'op_health_profession' => $data['op_health_profession'],
            'op_physician_incharge' => $data['op_physician_incharge'],
            'op_physician_incharge_datetime' => !empty($data['op_physician_incharge_datetime']) ? date('Y-m-d H:i:s', strtotime($data['op_physician_incharge_datetime'])) : null,
            'op_interpreter' => $data['op_interpreter'],
            'op_interpreter_datetime' => !empty($data['op_interpreter_datetime']) ? date('Y-m-d H:i:s', strtotime($data['op_interpreter_datetime'])) : null,
            'op_legal_rep' => $data['op_legal_rep'],
            'op_legal_rep_age' => $data['op_legal_rep_age'],
            'op_legal_rep_relation' => $data['op_legal_rep_relation'],
            'op_legal_rep_datetime' => !empty($data['op_legal_rep_datetime']) ? date('Y-m-d H:i:s', strtotime($data['op_legal_rep_datetime'])) : null,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function getCardioPulmononary($data)
    {
        return DB::table('csqmmh_chart_cardio_clearance')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newCardioPulmononary($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_cardio_clearance')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'attending_surgeon' => $data['attending_surgeon'],
                    'anesthesiologist' => $data['anesthesiologist'],
                    'proposed_surgery' => $data['proposed_surgery'],
                    'cardio_notes_hpn' => $data['cardio_notes_hpn'],
                    'cardio_notes_chest_pains' => $data['cardio_notes_chest_pains'],
                    'cardio_notes_exert' => $data['cardio_notes_exert'],
                    'cardio_notes_ortho' => $data['cardio_notes_ortho'],
                    'cardio_notes_parox' => $data['cardio_notes_parox'],
                    'cardio_notes_ankle' => $data['cardio_notes_ankle'],
                    'cardio_notes_medication' => $data['cardio_notes_medication'],
                    'cardio_notes_physical_exam' => $data['cardio_notes_physical_exam'],
                    'cardio_notes_recommendations' => $data['cardio_notes_recommendations'],
                    'cardio_notes_cardiologist_name' => $data['cardio_notes_cardiologist_name'],
                    'pulmo_notes_cough' => $data['pulmo_notes_cough'],
                    'pulmo_notes_cough_long' => $data['pulmo_notes_cough_long'],
                    'pulmo_notes_fever' => $data['pulmo_notes_fever'],
                    'pulmo_notes_hxptb' => $data['pulmo_notes_hxptb'],
                    'pulmo_notes_hxptb_when' => $data['pulmo_notes_hxptb_when'],
                    'pulmo_notes_hxptb_treated' => $data['pulmo_notes_hxptb_treated'],
                    'pulmo_notes_asthma' => $data['pulmo_notes_asthma'],
                    'pulmo_notes_asthma_treated' => $data['pulmo_notes_asthma_treated'],
                    'pulmo_notes_smoker' => $data['pulmo_notes_smoker'],
                    'pulmo_notes_smoker_packyears' => $data['pulmo_notes_smoker_packyears'],
                    'pulmo_notes_last_stick_smoked_when' => $data['pulmo_notes_last_stick_smoked_when'],
                    'pulmo_notes_recommendations' => $data['pulmo_notes_recommendations'],
                    'pulmo_notes_pe' => $data['pulmo_notes_pe'],
                    'pulmonologist_name' => $data['pulmonologist_name'],

                    'other_diabetes' => $data['other_diabetes'],
                    'other_diabetes_long' => $data['other_diabetes_long'],
                    'other_renal_failure' => $data['other_renal_failure'],
                    'other_anemia' => $data['other_anemia'],
                    'other_stroke' => $data['other_stroke'],
                    'other_stroke_when' => $data['other_stroke_when'],
                    'other_allergies' => $data['other_allergies'],
                    'other_allergies_what' => $data['other_allergies_what'],
                    'other_meds' => $data['other_meds'],
                    'other_on_dialysis' => $data['other_on_dialysis'],
                    'other_bleeding_tendencies' => $data['other_bleeding_tendencies'],
                    'other_nod_aware' => $data['other_nod_aware'],
                    'other_datetime' => !empty($data['other_datetime']) ? date('Y-m-d H:i:s', strtotime($data['other_datetime'])) : null,

                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_cardio_clearance')->insert([
            'cccc_id' => 'cccc-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'attending_surgeon' => $data['attending_surgeon'],
            'anesthesiologist' => $data['anesthesiologist'],
            'proposed_surgery' => $data['proposed_surgery'],
            'cardio_notes' => $data['cardio_notes'],
            'cardio_notes_hpn' => $data['cardio_notes_hpn'],
            'cardio_notes_chest_pains' => $data['cardio_notes_chest_pains'],
            'cardio_notes_exert' => $data['cardio_notes_exert'],
            'cardio_notes_ortho' => $data['cardio_notes_ortho'],
            'cardio_notes_parox' => $data['cardio_notes_parox'],
            'cardio_notes_ankle' => $data['cardio_notes_ankle'],
            'cardio_notes_medication' => $data['cardio_notes_medication'],
            'cardio_notes_physical_exam' => $data['cardio_notes_physical_exam'],
            'cardio_notes_recommendations' => $data['cardio_notes_recommendations'],
            'cardio_notes_cardiologist_name' => $data['cardio_notes_cardiologist_name'],
            'pulmo_notes' => $data['pulmo_notes'],
            'pulmo_notes_cough' => $data['pulmo_notes_cough'],
            'pulmo_notes_cough_long' => $data['pulmo_notes_cough_long'],
            'pulmo_notes_fever' => $data['pulmo_notes_fever'],
            'pulmo_notes_hxptb' => $data['pulmo_notes_hxptb'],
            'pulmo_notes_hxptb_when' => $data['pulmo_notes_hxptb_when'],
            'pulmo_notes_hxptb_treated' => $data['pulmo_notes_hxptb_treated'],
            'pulmo_notes_asthma' => $data['pulmo_notes_asthma'],
            'pulmo_notes_asthma_treated' => $data['pulmo_notes_asthma_treated'],
            'pulmo_notes_smoker' => $data['pulmo_notes_smoker'],
            'pulmo_notes_smoker_packyears' => $data['pulmo_notes_smoker_packyears'],
            'pulmo_notes_last_stick_smoked_when' => $data['pulmo_notes_last_stick_smoked_when'],
            'pulmo_notes_recommendations' => $data['pulmo_notes_recommendations'],
            'pulmo_notes_pe' => $data['pulmo_notes_pe'],

            'other_diabetes' => $data['other_diabetes'],
            'other_diabetes_long' => $data['other_diabetes_long'],
            'other_renal_failure' => $data['other_renal_failure'],
            'other_anemia' => $data['other_anemia'],
            'other_stroke' => $data['other_stroke'],
            'other_stroke_when' => $data['other_stroke_when'],
            'other_allergies' => $data['other_allergies'],
            'other_allergies_what' => $data['other_allergies_what'],
            'other_meds' => $data['other_meds'],
            'other_on_dialysis' => $data['other_on_dialysis'],
            'other_bleeding_tendencies' => $data['other_bleeding_tendencies'],
            'other_nod_aware' => $data['other_nod_aware'],
            'other_datetime' => !empty($data['other_datetime']) ? date('Y-m-d H:i:s', strtotime($data['other_datetime'])) : null,

            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function getChartBilling($data)
    {
        return DB::table('hospital_admitted_patient_billing_record')
            ->leftJoin('hospital_admitted_patient_billing_payments_record', 'hospital_admitted_patient_billing_payments_record.trace_number', '=', 'hospital_admitted_patient_billing_record.trace_number')
            ->select('hospital_admitted_patient_billing_record.*', 'hospital_admitted_patient_billing_payments_record.philhealth_amount', 'hospital_admitted_patient_billing_payments_record.payment_amount')
            ->where('hospital_admitted_patient_billing_record.patient_id', $data['patient_id'])
            ->where('hospital_admitted_patient_billing_record.trace_number', $data['trace_number'])
            ->get();
        // return DB::table('csqmmh_chart_billing')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newChartBilling($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('csqmmh_chart_billing')->insert([
            'ccb_id' => 'cccc-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],
            'category' => $data['category'],
            'bill_name' => $data['bill_name'],
            'actual_charges' => $data['actual_charges'],
            'discount' => $data['discount'],
            'philhealth' => $data['philhealth'],
            'pnp' => $data['pnp'],
            'balance' => $data['balance'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getBedsideNotes($data)
    {
        return DB::table('csqmmh_chart_nurses_bedsidenotes')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function getBedsideNotesTable($data)
    {
        return DB::table('csqmmh_chart_nurses_bedsidenotes_table')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newBedsideNotes($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('csqmmh_chart_nurses_bedsidenotes')->insert([
            'cnb_id' => 'cnb-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],

            'notes_datetime' => date('Y-m-d H:i:s', strtotime($data['notes_datetime'])),
            'notes' => strip_tags($data['notes']),

            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getMedicalAbstract($data)
    {
        return DB::table('csqmmh_chart_medical_abstract')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newMedicalAbstract($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_medical_abstract')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'attending_physician' => $data['attending_physician'],
                    'chief_complaint' => $data['chief_complaint'],
                    'history_of_present_illness' => $data['history_of_present_illness'],
                    'physical_examination' => $data['physical_examination'],
                    'laboratories' => $data['laboratories'],
                    'admitting_dignosis' => $data['admitting_dignosis'],
                    'procedure_done' => $data['procedure_done'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_medical_abstract')->insert([
            'ccma_id' => 'ccma-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'attending_physician' => $data['attending_physician'],
            'chief_complaint' => $data['chief_complaint'],
            'history_of_present_illness' => $data['history_of_present_illness'],
            'physical_examination' => $data['physical_examination'],
            'laboratories' => $data['laboratories'],
            'admitting_dignosis' => $data['admitting_dignosis'],
            'procedure_done' => $data['procedure_done'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function getClinicalSummary($data)
    {
        return DB::table('csqmmh_chart_clinical_summary')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newClinicalSummary($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_clinical_summary')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'datetime_admission' => date('Y-m-d H:i:s', strtotime($data['datetime_admission'])),
                    'datetime_discharge' => date('Y-m-d H:i:s', strtotime($data['datetime_discharge'])),
                    "attending_physician" => $data['attending_physician'],
                    "co_mgt_physician" => $data['co_mgt_physician'],
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

        return DB::table('csqmmh_chart_clinical_summary')->insert([
            'cccs_id' => 'cccs-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'case_no' => $data['case_no'],
            'trace_number' => $data['trace_number'],
            'datetime_admission' => date('Y-m-d H:i:s', strtotime($data['datetime_admission'])),
            'datetime_discharge' => date('Y-m-d H:i:s', strtotime($data['datetime_discharge'])),
            "attending_physician" => $data['attending_physician'],
            "co_mgt_physician" => $data['co_mgt_physician'],
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

    //dennis
    public static function getChartLaboratory($data)
    {
        return DB::table('csqmmh_chart_laboratory')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartLaboratory($data)
    {
        if (!empty($data['ccl_id'])) {
            return DB::table('csqmmh_chart_laboratory')
                ->where('ccl_id', $data['ccl_id'])
                ->update([
                    'patient_name' => $data['patient_name'],
                    'age' => $data['age'],
                    'gender' => $data['gender'],
                    'attending_physician' => $data['attending_physician'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        if (empty($data['ccl_id'])) {
            return DB::table('csqmmh_chart_laboratory')
                ->insert([
                    'ccl_id' => 'ccl-' . rand(0, 9) . time(),
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'case_no' => null,
                    'trace_number' => $data['trace_number'],
                    'patient_name' => $data['patient_name'],
                    'age' => $data['age'],
                    'gender' => $data['gender'],
                    'attending_physician' => $data['attending_physician'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getChartPeriOperative($data)
    {
        return DB::table('csqmmh_chart_perioperative_form')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartPeriOperative($data)
    {
        if (!empty($data['ccpf_id'])) {
            return DB::table('csqmmh_chart_perioperative_form')
                ->where('ccpf_id', $data['ccpf_id'])
                ->update([
                    'name' => $data['name'],
                    'age_sex_status' => $data['age_sex_status'],
                    'address' => $data['address'],
                    'date_admitted' => !empty($data['date_admitted']) ? date('Y-m-d', strtotime($data['date_admitted'])) : null,
                    'religion' => $data['religion'],
                    'pre_operative' => $data['pre_operative'],
                    'proposed_surgery' => $data['proposed_surgery'],
                    'date_time' => !empty($data['date_time']) ? date('Y-m-d H:i:s', strtotime($data['date_time'])) : null,
                    'case_class_clean' => $data['case_class_clean'],
                    'case_class_dirty' => $data['case_class_dirty'],
                    'case_elective' => $data['case_elective'],
                    'case_stat' => $data['case_stat'],
                    'consent_sign' => $data['consent_sign'],
                    'surgical_history' => $data['surgical_history'],
                    'surgical_date_year' => !empty($data['surgical_date_year']) ? date('Y-m-d', strtotime($data['surgical_date_year'])) : null,
                    'height' => $data['height'],
                    'weight' => $data['weight'],
                    'smoker' => $data['smoker'],
                    'renal' => $data['renal'],
                    'alcoholic' => $data['alcoholic'],
                    'neuro' => $data['neuro'],
                    'respiratory' => $data['respiratory'],
                    'cancer' => $data['cancer'],
                    'hematology' => $data['hematology'],
                    'cardiac' => $data['cardiac'],
                    'hypertension' => $data['hypertension'],
                    'hepatic' => $data['hepatic'],
                    'ortho_restriction' => $data['ortho_restriction'],
                    'diabetes' => $data['diabetes'],
                    'others_medications' => $data['others_medications'],
                    'psychosocial_calm' => $data['psychosocial_calm'],
                    'psychosocial_apprehensive' => $data['psychosocial_apprehensive'],
                    'psychosocial_restless' => $data['psychosocial_restless'],
                    'psychosocial_crying' => $data['psychosocial_crying'],
                    'psychosocial_others' => $data['psychosocial_others'],
                    'level_alert' => $data['level_alert'],
                    'level_asleep' => $data['level_asleep'],
                    'level_drowsy' => $data['level_drowsy'],
                    'level_unresponsive' => $data['level_unresponsive'],
                    'level_sedated' => $data['level_sedated'],
                    'level_others' => $data['level_others'],
                    'pattern_communicative' => $data['pattern_communicative'],
                    'pattern_quiet' => $data['pattern_quiet'],
                    'pattern_others' => $data['pattern_others'],
                    'pattern_adatation_surgical_patient' => $data['pattern_adatation_surgical_patient'],
                    'pattern_adatation_surgical_family' => $data['pattern_adatation_surgical_family'],
                    'comments' => $data['comments'],
                    'pre_op_visit' => $data['pre_op_visit'],
                    'pre_op_visit_nod' => $data['pre_op_visit_nod'],
                    'date_time_of_visit' => !empty($data['date_time_of_visit']) ? date('Y-m-d H:i:s', strtotime($data['date_time_of_visit'])) : null,
                    'or_date' => !empty($data['or_date']) ? date('Y-m-d', strtotime($data['or_date'])) : null,
                    'or_suite_no' => $data['or_suite_no'],
                    'patient_verbal' => $data['patient_verbal'],
                    'patient_chart' => $data['patient_chart'],
                    'patient_name_band' => $data['patient_name_band'],
                    'or_calm' => $data['or_calm'],
                    'or_apprehensive' => $data['or_apprehensive'],
                    'or_restless' => $data['or_restless'],
                    'or_crying' => $data['or_crying'],
                    'or_talkative' => $data['or_talkative'],
                    'or_others_current' => $data['or_others_current'],
                    'or_alert' => $data['or_alert'],
                    'or_asleep' => $data['or_asleep'],
                    'or_drowsy' => $data['or_drowsy'],
                    'or_unresponsive' => $data['or_unresponsive'],
                    'or_sedated' => $data['or_sedated'],
                    'or_others_level' => $data['or_others_level'],
                    'or_consent_verified' => $data['or_consent_verified'],
                    'or_npo_verified' => $data['or_npo_verified'],
                    'or_jewelry_verified' => $data['or_jewelry_verified'],
                    'or_nail_verified' => $data['or_nail_verified'],
                    'or_undergarments_verified' => $data['or_undergarments_verified'],
                    'or_others_verified' => $data['or_others_verified'],
                    'or_none_devices' => $data['or_none_devices'],
                    'or_ngt_devices' => $data['or_ngt_devices'],
                    'or_et_devices' => $data['or_et_devices'],
                    'or_ivf_devices' => $data['or_ivf_devices'],
                    'site' => $data['site'],
                    'level' => $data['level'],
                    'or_bt_devices' => $data['or_bt_devices'],
                    'or_urine_devices' => $data['or_urine_devices'],
                    'or_others_devices' => $data['or_others_devices'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        if (empty($data['ccpf_id'])) {
            return DB::table('csqmmh_chart_perioperative_form')
                ->insert([
                    'ccpf_id' => 'ccpf-' . rand(0, 9) . time(),
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'case_no' => null,
                    'trace_number' => $data['trace_number'],
                    'name' => $data['name'],
                    'age_sex_status' => $data['age_sex_status'],
                    'address' => $data['address'],
                    'date_admitted' => !empty($data['date_admitted']) ? date('Y-m-d', strtotime($data['date_admitted'])) : null,
                    'religion' => $data['religion'],
                    'pre_operative' => $data['pre_operative'],
                    'proposed_surgery' => $data['proposed_surgery'],
                    'date_time' => !empty($data['date_time']) ? date('Y-m-d H:i:s', strtotime($data['date_time'])) : null,
                    'case_class_clean' => $data['case_class_clean'],
                    'case_class_dirty' => $data['case_class_dirty'],
                    'case_elective' => $data['case_elective'],
                    'case_stat' => $data['case_stat'],
                    'consent_sign' => !empty($data['consent_sign']) ? $data['consent_sign'] : null,
                    'surgical_history' => $data['surgical_history'],
                    'surgical_date_year' => !empty($data['surgical_date_year']) ? date('Y-m-d', strtotime($data['surgical_date_year'])) : null,
                    'height' => $data['height'],
                    'weight' => $data['weight'],
                    'smoker' => $data['smoker'],
                    'renal' => $data['renal'],
                    'alcoholic' => $data['alcoholic'],
                    'neuro' => $data['neuro'],
                    'respiratory' => $data['respiratory'],
                    'cancer' => $data['cancer'],
                    'hematology' => $data['hematology'],
                    'cardiac' => $data['cardiac'],
                    'hypertension' => $data['hypertension'],
                    'hepatic' => $data['hepatic'],
                    'ortho_restriction' => $data['ortho_restriction'],
                    'diabetes' => $data['diabetes'],
                    'others_medications' => $data['others_medications'],
                    'psychosocial_calm' => $data['psychosocial_calm'],
                    'psychosocial_apprehensive' => $data['psychosocial_apprehensive'],
                    'psychosocial_restless' => $data['psychosocial_restless'],
                    'psychosocial_crying' => $data['psychosocial_crying'],
                    'psychosocial_others' => $data['psychosocial_others'],
                    'level_alert' => $data['level_alert'],
                    'level_asleep' => $data['level_asleep'],
                    'level_drowsy' => $data['level_drowsy'],
                    'level_unresponsive' => $data['level_unresponsive'],
                    'level_sedated' => $data['level_sedated'],
                    'level_others' => $data['level_others'],
                    'pattern_communicative' => $data['pattern_communicative'],
                    'pattern_quiet' => $data['pattern_quiet'],
                    'pattern_others' => $data['pattern_others'],
                    'pattern_adatation_surgical_patient' => $data['pattern_adatation_surgical_patient'],
                    'pattern_adatation_surgical_family' => $data['pattern_adatation_surgical_family'],
                    'comments' => $data['comments'],
                    'pre_op_visit' => $data['pre_op_visit'],
                    'pre_op_visit_nod' => $data['pre_op_visit_nod'],
                    'date_time_of_visit' => !empty($data['date_time_of_visit']) ? date('Y-m-d H:i:s', strtotime($data['date_time_of_visit'])) : null,
                    'or_date' => !empty($data['or_date']) ? date('Y-m-d', strtotime($data['or_date'])) : null,
                    'or_suite_no' => $data['or_suite_no'],
                    'patient_verbal' => $data['patient_verbal'],
                    'patient_chart' => $data['patient_chart'],
                    'patient_name_band' => $data['patient_name_band'],
                    'or_calm' => $data['or_calm'],
                    'or_apprehensive' => $data['or_apprehensive'],
                    'or_restless' => $data['or_restless'],
                    'or_crying' => $data['or_crying'],
                    'or_talkative' => $data['or_talkative'],
                    'or_others_current' => $data['or_others_current'],
                    'or_alert' => $data['or_alert'],
                    'or_asleep' => $data['or_asleep'],
                    'or_drowsy' => $data['or_drowsy'],
                    'or_unresponsive' => $data['or_unresponsive'],
                    'or_sedated' => $data['or_sedated'],
                    'or_others_level' => $data['or_others_level'],
                    'or_consent_verified' => $data['or_consent_verified'],
                    'or_npo_verified' => $data['or_npo_verified'],
                    'or_jewelry_verified' => $data['or_jewelry_verified'],
                    'or_nail_verified' => $data['or_nail_verified'],
                    'or_undergarments_verified' => $data['or_undergarments_verified'],
                    'or_others_verified' => $data['or_others_verified'],
                    'or_none_devices' => $data['or_none_devices'],
                    'or_ngt_devices' => $data['or_ngt_devices'],
                    'or_et_devices' => $data['or_et_devices'],
                    'or_ivf_devices' => $data['or_ivf_devices'],
                    'site' => $data['site'],
                    'level' => $data['level'],
                    'or_bt_devices' => $data['or_bt_devices'],
                    'or_urine_devices' => $data['or_urine_devices'],
                    'or_others_devices' => $data['or_others_devices'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getChartPostOperative($data)
    {
        return DB::table('csqmmh_chart_postoperative_evaluation_form')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartPostOperative($data)
    {
        if (!empty($data['ccpef_id'])) {
            return DB::table('csqmmh_chart_postoperative_evaluation_form')
                ->where('ccpef_id', $data['ccpef_id'])
                ->update([
                    'discharge_pacu' => $data['discharge_pacu'],
                    'discharge_room' => $data['discharge_room'],
                    'discharge_icu' => $data['discharge_icu'],
                    'discharge_morgue' => $data['discharge_morgue'],
                    'level_awake' => $data['level_awake'],
                    'level_sedated' => $data['level_sedated'],
                    'level_unconscious' => $data['level_unconscious'],
                    'level_semi_unconscious' => $data['level_semi_unconscious'],
                    'level_expired' => $data['level_expired'],
                    'level_others' => $data['level_others'],
                    'skin_pinkish' => $data['skin_pinkish'],
                    'skin_pale' => $data['skin_pale'],
                    'skin_warm' => $data['skin_warm'],
                    'skin_cool' => $data['skin_cool'],
                    'skin_cyanotic' => $data['skin_cyanotic'],
                    'skin_others' => $data['skin_others'],
                    'trans_et' => $data['trans_et'],
                    'trans_tracheostomy' => $data['trans_tracheostomy'],
                    'trans_epid' => $data['trans_epid'],
                    'trans_ngt' => $data['trans_ngt'],
                    'trans_urine' => $data['trans_urine'],
                    'trans_drain' => $data['trans_drain'],
                    'trans_ivf' => $data['trans_ivf'],
                    'trans_others' => $data['trans_others'],
                    'operation_done' => $data['operation_done'],
                    'post_op_diagnosis' => $data['post_op_diagnosis'],
                    'endorsed_to' => $data['endorsed_to'],
                    'endorsed_by' => $data['endorsed_by'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        if (empty($data['ccpef_id'])) {
            return DB::table('csqmmh_chart_postoperative_evaluation_form')
                ->insert([
                    'ccpef_id' => 'ccpef-' . rand(0, 9) . time(),
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'case_no' => null,
                    'trace_number' => $data['trace_number'],
                    'discharge_pacu' => $data['discharge_pacu'],
                    'discharge_room' => $data['discharge_room'],
                    'discharge_icu' => $data['discharge_icu'],
                    'discharge_morgue' => $data['discharge_morgue'],
                    'level_awake' => $data['level_awake'],
                    'level_sedated' => $data['level_sedated'],
                    'level_unconscious' => $data['level_unconscious'],
                    'level_semi_unconscious' => $data['level_semi_unconscious'],
                    'level_expired' => $data['level_expired'],
                    'level_others' => $data['level_others'],
                    'skin_pinkish' => $data['skin_pinkish'],
                    'skin_pale' => $data['skin_pale'],
                    'skin_warm' => $data['skin_warm'],
                    'skin_cool' => $data['skin_cool'],
                    'skin_cyanotic' => $data['skin_cyanotic'],
                    'skin_others' => $data['skin_others'],
                    'trans_et' => $data['trans_et'],
                    'trans_tracheostomy' => $data['trans_tracheostomy'],
                    'trans_epid' => $data['trans_epid'],
                    'trans_ngt' => $data['trans_ngt'],
                    'trans_urine' => $data['trans_urine'],
                    'trans_drain' => $data['trans_drain'],
                    'trans_ivf' => $data['trans_ivf'],
                    'trans_others' => $data['trans_others'],
                    'operation_done' => $data['operation_done'],
                    'post_op_diagnosis' => $data['post_op_diagnosis'],
                    'endorsed_to' => $data['endorsed_to'],
                    'endorsed_by' => $data['endorsed_by'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getChartSurgicalMember($data)
    {
        return DB::table('csqmmh_chart_surgical_team_member')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartSurgicalMember($data)
    {
        if (!empty($data['ccstm_id'])) {
            return DB::table('csqmmh_chart_surgical_team_member')
                ->where('ccstm_id', $data['ccstm_id'])
                ->update([
                    'in_room' => $data['in_room'],
                    'surgeon_one' => $data['surgeon_one'],
                    'induction' => $data['induction'],
                    'surgeon_two' => $data['surgeon_two'],
                    'procedure_start' => $data['procedure_start'],
                    'anesthesiologist' => $data['anesthesiologist'],
                    'cutting_time' => $data['cutting_time'],
                    'anesthesiologist_two' => $data['anesthesiologist_two'],
                    'closing' => $data['closing'],
                    'scrub_nurse_one' => $data['scrub_nurse_one'],
                    'procedure_finish' => $data['procedure_finish'],
                    'scrub_nurse_two' => $data['scrub_nurse_two'],
                    'out_room' => $data['out_room'],
                    'circulating_nurse' => $data['circulating_nurse'],
                    'type_general' => $data['type_general'],
                    'type_spinal' => $data['type_spinal'],
                    'type_epidural' => $data['type_epidural'],
                    'type_local' => $data['type_local'],
                    'type_others' => $data['type_others'],
                    'type_anesthesia_used' => $data['type_anesthesia_used'],
                    'devices_none' => $data['devices_none'],
                    'devices_ngt' => $data['devices_ngt'],
                    'devices_et' => $data['devices_et'],
                    'devices_ivf' => $data['devices_ivf'],
                    'devices_ivf_site' => $data['devices_ivf_site'],
                    'devices_bt' => $data['devices_bt'],
                    'devices_drain' => $data['devices_drain'],
                    'devices_urine_catheter' => $data['devices_urine_catheter'],
                    'devices_others' => $data['devices_others'],
                    'pos_supine' => $data['pos_supine'],
                    'pos_prone' => $data['pos_prone'],
                    'pos_lateral' => $data['pos_lateral'],
                    'pos_lithotomy' => $data['pos_lithotomy'],
                    'pos_jackknife' => $data['pos_jackknife'],
                    'pos_others' => $data['pos_others'],
                    'elec_monopolar' => $data['elec_monopolar'],
                    'elec_grounding_pad' => $data['elec_grounding_pad'],
                    'elec_bipolar' => $data['elec_bipolar'],
                    'elec_skin_prep_site' => $data['elec_skin_prep_site'],
                    'elec_betadine_cleanser' => $data['elec_betadine_cleanser'],
                    'elec_betadine_antiseptec' => $data['elec_betadine_antiseptec'],
                    'elec_others' => $data['elec_others'],
                    'initial_count' => $data['initial_count'],
                    'sponges_correct' => $data['initial_sponges_correct'],
                    'sponges_lacking' => $data['initial_sponges_lacking'],
                    'needles_correct' => $data['initial_needles_correct'],
                    'needles_lacking' => $data['initial_needles_lacking'],
                    'instruments_correct' => $data['initial_instrument_correct'],
                    'instruments_lacking' => $data['initial_instrument_lacking'],
                    'if_lacking_xray' => $data['if_lacking_xray'],
                    'if_lacking_xray_no' => $data['if_lacking_xray_no'],
                    'if_lacking_xray_no_sn' => $data['if_lacking_xray_no_sn'],
                    'if_lacking_xray_no_cn' => $data['if_lacking_xray_no_cn'],
                    'initial_count_two' => $data['initial_count_two'],
                    'sponges_correct2' => $data['wa_sponges_correct2'],
                    'sponges_lacking2' => $data['wa_sponges_lacking2'],
                    'needles_correct2' => $data['wa_needles_correct2'],
                    'needles_lacking2' => $data['wa_needles_lacking2'],
                    'instruments_correct2' => $data['wa_instrument_correct2'],
                    'instruments_lacking2' => $data['wa_instrument_lacking2'],
                    'if_lacking_xray2' => $data['wa_if_lacking_xray2'],
                    'if_lacking_xray_no2' => $data['wa_if_lacking_xray_no2'],
                    'if_lacking_xray_no_sn2' => $data['wa_if_lacking_xray_no_sn2'],
                    'if_lacking_xray_no_cn2' => $data['wa_if_lacking_xray_no_cn2'],
                    'surgeon_notified_counts' => $data['surgeon_notified_counts'],
                    'special_endorsement' => $data['special_endorsement'],
                    'specimen' => $data['specimen'],
                    'surgeon_three' => $data['surgeon_three'],
                    'circulating' => $data['circulating'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        if (empty($data['ccstm_id'])) {
            return DB::table('csqmmh_chart_surgical_team_member')
                ->insert([
                    'ccstm_id' => 'ccpef-' . rand(0, 9) . time(),
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'case_no' => null,
                    'trace_number' => $data['trace_number'],
                    'in_room' => $data['in_room'],
                    'surgeon_one' => $data['surgeon_one'],
                    'induction' => $data['induction'],
                    'surgeon_two' => $data['surgeon_two'],
                    'procedure_start' => $data['procedure_start'],
                    'anesthesiologist' => $data['anesthesiologist'],
                    'cutting_time' => $data['cutting_time'],
                    'anesthesiologist_two' => $data['anesthesiologist_two'],
                    'closing' => $data['closing'],
                    'scrub_nurse_one' => $data['scrub_nurse_one'],
                    'procedure_finish' => $data['procedure_finish'],
                    'scrub_nurse_two' => $data['scrub_nurse_two'],
                    'out_room' => $data['out_room'],
                    'circulating_nurse' => $data['circulating_nurse'],
                    'type_general' => $data['type_general'],
                    'type_spinal' => $data['type_spinal'],
                    'type_epidural' => $data['type_epidural'],
                    'type_local' => $data['type_local'],
                    'type_others' => $data['type_others'],
                    'type_anesthesia_used' => $data['type_anesthesia_used'],
                    'devices_none' => $data['devices_none'],
                    'devices_ngt' => $data['devices_ngt'],
                    'devices_et' => $data['devices_et'],
                    'devices_ivf' => $data['devices_ivf'],
                    'devices_ivf_site' => $data['devices_ivf_site'],
                    'devices_bt' => $data['devices_bt'],
                    'devices_drain' => $data['devices_drain'],
                    'devices_urine_catheter' => $data['devices_urine_catheter'],
                    'devices_others' => $data['devices_others'],
                    'pos_supine' => $data['pos_supine'],
                    'pos_prone' => $data['pos_prone'],
                    'pos_lateral' => $data['pos_lateral'],
                    'pos_lithotomy' => $data['pos_lithotomy'],
                    'pos_jackknife' => $data['pos_jackknife'],
                    'pos_others' => $data['pos_others'],
                    'elec_monopolar' => $data['elec_monopolar'],
                    'elec_grounding_pad' => $data['elec_grounding_pad'],
                    'elec_bipolar' => $data['elec_bipolar'],
                    'elec_skin_prep_site' => $data['elec_skin_prep_site'],
                    'elec_betadine_cleanser' => $data['elec_betadine_cleanser'],
                    'elec_betadine_antiseptec' => $data['elec_betadine_antiseptec'],
                    'elec_others' => $data['elec_others'],
                    'initial_count' => $data['initial_count'],
                    'sponges_correct' => $data['initial_sponges_correct'],
                    'sponges_lacking' => $data['initial_sponges_lacking'],
                    'needles_correct' => $data['initial_needles_correct'],
                    'needles_lacking' => $data['initial_needles_lacking'],
                    'instruments_correct' => $data['initial_instrument_correct'],
                    'instruments_lacking' => $data['initial_instrument_lacking'],
                    'if_lacking_xray' => $data['if_lacking_xray'],
                    'if_lacking_xray_no' => $data['if_lacking_xray_no'],
                    'if_lacking_xray_no_sn' => $data['if_lacking_xray_no_sn'],
                    'initial_count_two' => $data['initial_count_two'],
                    'sponges_correct2' => $data['wa_sponges_correct2'],
                    'sponges_lacking2' => $data['wa_sponges_lacking2'],
                    'needles_correct2' => $data['wa_needles_correct2'],
                    'needles_lacking2' => $data['wa_needles_lacking2'],
                    'instruments_correct2' => $data['wa_instrument_correct2'],
                    'instruments_lacking2' => $data['wa_instrument_lacking2'],
                    'if_lacking_xray2' => $data['wa_if_lacking_xray2'],
                    'if_lacking_xray_no2' => $data['wa_if_lacking_xray_no2'],
                    'if_lacking_xray_no_sn2' => $data['wa_if_lacking_xray_no_sn2'],
                    'surgeon_notified_counts' => $data['surgeon_notified_counts'],
                    'special_endorsement' => $data['special_endorsement'],
                    'specimen' => $data['specimen'],
                    'surgeon_three' => $data['surgeon_three'],
                    'circulating' => $data['circulating'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getChartDoctorConsultation($data)
    {
        return DB::table('csqmmh_chart_doctors_consultation_form')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getChartDoctorConsultationTable($data)
    {
        return DB::table('csqmmh_chart_doctors_consultation_form_table')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartDoctorConsultation($data)
    {
        date_default_timezone_set('Asia/Manila');
        $ccdcf_id = 'ccdcf-' . rand(0, 9) . time();
        $date = $data['date'];
        $consultation_notes = $data['consultation_notes'];
        $arrayData = [];

        if ($data['status'] == 'for-new') {
            if (!empty($consultation_notes)) {
                for ($i = 0; $i < count($date); $i++) {
                    $arrayData[] = array(
                        'ccdcft_id' => 'ccdcft-' . rand(0, 9) . time(),
                        'ccdcf_id' => $ccdcf_id,
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'date' => date('Y-m-d', strtotime($date[$i])),
                        'consultation_notes' => $consultation_notes[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }

            DB::table('csqmmh_chart_doctors_consultation_form_table')->insert($arrayData);

            return DB::table('csqmmh_chart_doctors_consultation_form')->insert([
                'ccdcf_id' => $ccdcf_id,
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'name' => $data['name'],
                'address' => $data['address'],
                'age' => $data['age'],
                'sex' => $data['sex'],
                'telephone' => $data['telephone'],
                'diagnosis' => $data['diagnosis'],
                'operation' => $data['operation'],
                'referred_by' => $data['referred_by'],
                'allergy' => $data['allergy'],
                'blank_text' => $data['blank_text'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {

            if (!empty($consultation_notes)) {
                for ($i = 0; $i < count($date); $i++) {
                    $arrayData[] = array(
                        'ccdcft_id' => 'ccdcft-' . rand(0, 9) . time(),
                        'ccdcf_id' => $data['ccdcf_id'],
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'date' => date('Y-m-d', strtotime($date[$i])),
                        'consultation_notes' => $consultation_notes[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }

            DB::table('csqmmh_chart_doctors_consultation_form_table')->insert($arrayData);

            return DB::table('csqmmh_chart_doctors_consultation_form')->where('ccdcf_id', $data['ccdcf_id'])->update([
                'name' => $data['name'],
                'address' => $data['address'],
                'age' => $data['age'],
                'sex' => $data['sex'],
                'telephone' => $data['telephone'],
                'diagnosis' => $data['diagnosis'],
                'operation' => $data['operation'],
                'referred_by' => $data['referred_by'],
                'allergy' => $data['allergy'],
                'blank_text' => $data['blank_text'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        }

    }

    public static function removeChartDoctorConsultation($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_doctors_consultation_form_table')
            ->where('ccdcft_id', $data['ccdcft_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function getChartOperativeRecord($data)
    {
        return DB::table('csqmmh_chart_operative_record')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartOperativeRecord($data)
    {
        if (!empty($data['ccor_id'])) {
            return DB::table('csqmmh_chart_operative_record')
                ->where('ccor_id', $data['ccor_id'])
                ->update([
                    'operative_date' => date('Y-m-d', strtotime($data['operative_date'])),
                    'name' => $data['name'],
                    'age' => $data['age'],
                    'gender' => $data['gender'],
                    'civil_status' => $data['civil_status'],
                    'room_no' => $data['room_no'],
                    'admission_no' => $data['admission_no'],
                    'preoperative_diagnosis' => $data['preoperative_diagnosis'],
                    'preoperative_condition' => $data['preoperative_condition'],
                    'surgeon' => $data['surgeon'],
                    'scrub_nurse' => $data['scrub_nurse'],
                    'anesthesiologist' => $data['anesthesiologist'],
                    'circulating_nurse' => $data['circulating_nurse'],
                    'technique' => $data['technique'],
                    'anesthetics' => $data['anesthetics'],
                    'transfusion' => $data['transfusion'],
                    'name_operation' => $data['name_operation'],
                    'rvs_code' => $data['rvs_code'],
                    'operation_started' => $data['operation_started'],
                    'operation_ended' => $data['operation_ended'],
                    'operative_finding' => $data['operative_finding'],
                    'technique_operation' => $data['technique_operation'],
                    'post_operative_diagnosis' => $data['post_operative_diagnosis'],
                    'post_operative_condition' => $data['post_operative_condition'],
                    'hispatology' => $data['hispatology'],
                    'sponge_count' => $data['sponge_count'],
                    'signature_physician' => $data['signature_physician'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        if (empty($data['ccor_id'])) {
            return DB::table('csqmmh_chart_operative_record')
                ->insert([
                    'ccor_id' => 'ccdc-' . rand(0, 9) . time(),
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'case_no' => null,
                    'trace_number' => $data['trace_number'],
                    'operative_date' => date('Y-m-d', strtotime($data['operative_date'])),
                    'name' => $data['name'],
                    'age' => $data['age'],
                    'gender' => $data['gender'],
                    'civil_status' => $data['civil_status'],
                    'room_no' => $data['room_no'],
                    'admission_no' => $data['admission_no'],
                    'preoperative_diagnosis' => $data['preoperative_diagnosis'],
                    'preoperative_condition' => $data['preoperative_condition'],
                    'surgeon' => $data['surgeon'],
                    'scrub_nurse' => $data['scrub_nurse'],
                    'anesthesiologist' => $data['anesthesiologist'],
                    'circulating_nurse' => $data['circulating_nurse'],
                    'technique' => $data['technique'],
                    'anesthetics' => $data['anesthetics'],
                    'transfusion' => $data['transfusion'],
                    'name_operation' => $data['name_operation'],
                    'rvs_code' => $data['rvs_code'],
                    'operation_started' => $data['operation_started'],
                    'operation_ended' => $data['operation_ended'],
                    'operative_finding' => $data['operative_finding'],
                    'technique_operation' => $data['technique_operation'],
                    'post_operative_diagnosis' => $data['post_operative_diagnosis'],
                    'post_operative_condition' => $data['post_operative_condition'],
                    'hispatology' => $data['hispatology'],
                    'sponge_count' => $data['sponge_count'],
                    'signature_physician' => $data['signature_physician'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getChartDoctorsOrder($data)
    {
        return DB::table('csqmmh_chart_doctors_order_form')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getChartDoctorsOrderTable($data)
    {
        return DB::table('csqmmh_chart_doctors_order_form_table')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartDoctorsOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        $date_time = $data['date_time'];
        $progress_name = $data['progress_name'];
        $order_name = $data['order_name'];
        $ccdof_id = 'ccdof-' . rand(0, 9) . time();
        $arrayData = [];

        if ($data['status'] == 'for-new') {
            if (!empty($data['date_time'])) {
                for ($i = 0; $i < count($date_time); $i++) {
                    $arrayData[] = array(
                        'ccdoft_id' => 'ccdoft-' . rand(0, 9) . time(),
                        'ccdof_id' => $ccdof_id,
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'date_time' => date('Y-m-d H:i:s', strtotime($date_time[$i])),
                        'progress_name' => strip_tags($progress_name[$i]),
                        'order_name' => strip_tags($order_name[$i]),
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }
            DB::table('csqmmh_chart_doctors_order_form_table')->insert($arrayData);

            return DB::table('csqmmh_chart_doctors_order_form')->insert([
                'ccdof_id' => $ccdof_id,
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'name' => $data['name'],
                'age' => $data['age'],
                'sex' => $data['sex'],
                'room_bed' => $data['room_bed'],
                'case_no_form' => $data['case_no_form'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            if (!empty($data['date_time'])) {
                for ($i = 0; $i < count($date_time); $i++) {
                    $arrayData[] = array(
                        'ccdoft_id' => 'ccdoft-' . rand(0, 9) . time(),
                        'ccdof_id' => $data['ccdof_id'],
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'date_time' => date('Y-m-d H:i:s', strtotime($date_time[$i])),
                        'progress_name' => strip_tags($progress_name[$i]),
                        'order_name' => strip_tags($order_name[$i]),
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }
            DB::table('csqmmh_chart_doctors_order_form_table')->insert($arrayData);

            return DB::table('csqmmh_chart_doctors_order_form')->where('ccdof_id', $data['ccdof_id'])->update([
                'name' => $data['name'],
                'age' => $data['age'],
                'sex' => $data['sex'],
                'room_bed' => $data['room_bed'],
                'case_no_form' => $data['case_no_form'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function removeChartDoctorsOrder($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_doctors_order_form_table')
            ->where('ccdoft_id', $data['ccdoft_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function getChartPostAnesthesiaCareUnit($data)
    {
        return DB::table('csqmmh_chart_post_anesthesia_care')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getChartPostAnesthesiaCareUnitTable($data)
    {
        return DB::table('csqmmh_chart_post_anesthesia_care_table')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartPostAnesthesiaCareUnit($data)
    {
        date_default_timezone_set('Asia/Manila');
        $date_time = $data['date_time'];
        $bp = $data['bp'];
        $temp = $data['temp'];
        $pr = $data['pr'];
        $rr = $data['rr'];
        $o2_sat = $data['o2_sat'];
        $remarks = $data['remarks'];
        $ivf_solution = $data['ivf_solution'];
        $ivf_date_time = $data['ivf_date_time'];
        $ivf_remarks = $data['ivf_remarks'];
        $arrayData = [];
        $ccpac_id = 'ccpac-' . rand(0, 9) . time();

        if ($data['status'] == 'for-new') {

            if (!empty($data['date_time'])) {
                for ($i = 0; $i < count($date_time); $i++) {
                    $arrayData[] = array(
                        'ccpact_id' => 'ccpact-' . rand(0, 9) . time(),
                        'ccpac_id' => $ccpac_id,
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'date_time' => date('Y-m-d H:i:s', strtotime($date_time[$i])),
                        'bp' => $bp[$i],
                        'temp' => $temp[$i],
                        'pr' => $pr[$i],
                        'rr' => $rr[$i],
                        'o2_sat' => $o2_sat[$i],
                        'remarks' => $remarks[$i],
                        'ivf_solution' => $ivf_solution[$i],
                        'ivf_date_time' => date('Y-m-d H:i:s', strtotime($ivf_date_time[$i])),
                        'ivf_remarks' => $ivf_remarks[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }

            DB::table('csqmmh_chart_post_anesthesia_care_table')->insert($arrayData);
            return DB::table('csqmmh_chart_post_anesthesia_care')->insert([
                'ccpac_id' => $ccpac_id,
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'patient_name' => $data['patient_name'],
                'age_sex' => $data['age_sex'],
                'attending_physician' => $data['attending_physician'],
                'signature_pacu_nurse' => $data['signature_pacu_nurse'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        } else {
            if (!empty($data['date_time'])) {
                for ($i = 0; $i < count($date_time); $i++) {
                    $arrayData[] = array(
                        'ccpact_id' => 'ccpact-' . rand(0, 9) . time(),
                        'ccpac_id' => $data['ccpac_id'],
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'date_time' => date('Y-m-d H:i:s', strtotime($date_time[$i])),
                        'bp' => $bp[$i],
                        'temp' => $temp[$i],
                        'pr' => $pr[$i],
                        'rr' => $rr[$i],
                        'o2_sat' => $o2_sat[$i],
                        'remarks' => $remarks[$i],
                        'ivf_solution' => $ivf_solution[$i],
                        'ivf_date_time' => date('Y-m-d H:i:s', strtotime($ivf_date_time[$i])),
                        'ivf_remarks' => $ivf_remarks[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }

            DB::table('csqmmh_chart_post_anesthesia_care_table')->insert($arrayData);

            return DB::table('csqmmh_chart_post_anesthesia_care')->where('ccpac_id', $data['ccpac_id'])->update([
                'patient_name' => $data['patient_name'],
                'age_sex' => $data['age_sex'],
                'attending_physician' => $data['attending_physician'],
                'signature_pacu_nurse' => $data['signature_pacu_nurse'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function removeChartPostAnesthesiaCareUnit($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_post_anesthesia_care_table')
            ->where('ccpact_id', $data['ccpact_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function getChartJPDrainMonitoring($data)
    {
        return DB::table('csqmmh_chart_jp_drain_monitoring')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getChartJPDrainMonitoringTable($data)
    {
        return DB::table('csqmmh_chart_jp_drain_monitoring_table')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartJPDrainMonitoring($data)
    {
        date_default_timezone_set('Asia/Manila');
        $jp_date_time = $data['jp_date_time'];
        $volume_ml_cc = $data['volume_ml_cc'];
        $arrayData = [];
        $ccjd_id = 'ccjdt-' . rand(0, 9) . time();

        if ($data['status'] == 'for-new') {
            if (!empty($data['jp_date_time'])) {
                for ($i = 0; $i < count($jp_date_time); $i++) {
                    $arrayData[] = array(
                        'ccjdt_id' => 'ccjdt-' . rand(0, 9) . time(),
                        'ccjd_id' => $ccjd_id,
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'jp_date_time' => date('Y-m-d H:i:s', strtotime($jp_date_time[$i])),
                        'volume_ml_cc' => $volume_ml_cc[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }
            DB::table('csqmmh_chart_jp_drain_monitoring_table')->insert($arrayData);

            return DB::table('csqmmh_chart_jp_drain_monitoring')->insert([
                'ccjd_id' => $ccjd_id,
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'patient_name' => $data['patient_name'],
                'doctor' => $data['doctor'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            if (!empty($data['date_time'])) {
                for ($i = 0; $i < count($date_time); $i++) {
                    $arrayData[] = array(
                        'ccjdt_id' => 'ccjdt-' . rand(0, 9) . time(),
                        'ccjd_id' => $data['ccjd_id'],
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'jp_date_time' => date('Y-m-d H:i:s', strtotime($jp_date_time[$i])),
                        'volume_ml_cc' => $volume_ml_cc[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }

            DB::table('csqmmh_chart_jp_drain_monitoring_table')->insert($arrayData);

            return DB::table('csqmmh_chart_jp_drain_monitoring')->where('ccjd_id', $data['ccjd_id'])->update([
                'patient_name' => $data['patient_name'],
                'doctor' => $data['doctor'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function removeChartJPDrainMonitoring($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_jp_drain_monitoring_table')
            ->where('ccjdt_id', $data['ccjdt_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function getChartAttendanceSheet($data)
    {
        return DB::table('csqmmh_chart_attemdance_sheet')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function updateChartAttendanceSheet($data)
    {
        if (!empty($data['ccas_id'])) {
            return DB::table('csqmmh_chart_attemdance_sheet')
                ->where('ccas_id', $data['ccas_id'])
                ->update([
                    'patient_name' => $data['patient_name'],
                    'age' => $data['age'],
                    'sex' => $data['sex'],
                    'birthdate' => !empty($data['birthdate']) ? date('Y-m-d', strtotime($data['birthdate'])) : null,
                    'case_number' => $data['case_number'],
                    'surgeon_name' => $data['surgeon_name'],
                    'operation_name' => $data['operation_name'],
                    'receive_nurse' => $data['receive_nurse'],
                    'anesthesia_resident' => $data['anesthesia_resident'],
                    'circulating_nurse' => $data['circulating_nurse'],
                    'circulating_nurse_confirm' => $data['circulating_nurse_confirm'],
                    'identity' => $data['identity'],
                    'procedure_site' => $data['procedure_site'],
                    'consent' => $data['consent'],
                    'site_marked' => $data['site_marked'],
                    'history' => $data['history'],
                    'assessment' => $data['assessment'],
                    'diagnostic_radiologic' => $data['diagnostic_radiologic'],
                    'blood_product' => $data['blood_product'],
                    'any_special' => $data['any_special'],
                    'signature_receiving' => $data['signature_receiving'],
                    'confirm_identity' => $data['confirm_identity'],
                    'site_marked_procedure' => $data['site_marked_procedure'],
                    'patient_allergies' => $data['patient_allergies'],
                    'diff_airway' => $data['diff_airway'],
                    'risk_blood' => $data['risk_blood'],
                    'blood_units' => $data['blood_units'],
                    'two_intravenous' => $data['two_intravenous'],
                    'anesthesia_machine' => $data['anesthesia_machine'],
                    'pulse_oximeter' => $data['pulse_oximeter'],
                    'all_member' => $data['all_member'],
                    'introduction_team' => $data['introduction_team'],
                    'informed_consent' => $data['informed_consent'],
                    'site_mark_visible' => $data['site_mark_visible'],
                    'relevant_images' => $data['relevant_images'],
                    'non_routine_steps' => $data['non_routine_steps'],
                    'case_duration' => $data['case_duration'],
                    'anticipated_blood_loss' => $data['anticipated_blood_loss'],
                    'specific_concerns' => $data['specific_concerns'],
                    'sterilization' => $data['sterilization'],
                    'antibiotic' => $data['antibiotic'],
                    'before_incision' => $data['before_incision'],
                    'issues_concern' => $data['issues_concern'],
                    'score' => $data['score'],
                    'score_word' => $data['score_word'],
                    'operative_procedure' => $data['operative_procedure'],
                    'sponges' => $data['sponges'],
                    'instruments' => $data['instruments'],
                    'needles' => $data['needles'],
                    'sharps' => $data['sharps'],
                    'identified_specimen' => $data['identified_specimen'],
                    'problems_to_address' => $data['problems_to_address'],
                    'concerns_for_recovery' => $data['concerns_for_recovery'],
                    'signature_circulating' => $data['signature_circulating'],
                    'signature_anesthesiologist' => $data['signature_anesthesiologist'],
                    'signature_surgeon' => $data['signature_surgeon'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
        if (empty($data['ccas_id'])) {
            return DB::table('csqmmh_chart_attemdance_sheet')
                ->insert([
                    'ccas_id' => 'ccas-' . rand(0, 9) . time(),
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'case_no' => null,
                    'trace_number' => $data['trace_number'],
                    'patient_name' => $data['patient_name'],
                    'age' => $data['age'],
                    'sex' => $data['sex'],
                    'birthdate' => !empty($data['birthdate']) ? date('Y-m-d', strtotime($data['birthdate'])) : null,
                    'case_number' => $data['case_number'],
                    'surgeon_name' => $data['surgeon_name'],
                    'operation_name' => $data['operation_name'],
                    'receive_nurse' => $data['receive_nurse'],
                    'anesthesia_resident' => $data['anesthesia_resident'],
                    'circulating_nurse' => $data['circulating_nurse'],
                    'circulating_nurse_confirm' => $data['circulating_nurse_confirm'],
                    'identity' => $data['identity'],
                    'procedure_site' => $data['procedure_site'],
                    'consent' => $data['consent'],
                    'site_marked' => $data['site_marked'],
                    'history' => $data['history'],
                    'assessment' => $data['assessment'],
                    'diagnostic_radiologic' => $data['diagnostic_radiologic'],
                    'blood_product' => $data['blood_product'],
                    'any_special' => $data['any_special'],
                    'signature_receiving' => $data['signature_receiving'],
                    'confirm_identity' => $data['confirm_identity'],
                    'site_marked_procedure' => $data['site_marked_procedure'],
                    'patient_allergies' => $data['patient_allergies'],
                    'diff_airway' => $data['diff_airway'],
                    'risk_blood' => $data['risk_blood'],
                    'blood_units' => $data['blood_units'],
                    'two_intravenous' => $data['two_intravenous'],
                    'anesthesia_machine' => $data['anesthesia_machine'],
                    'pulse_oximeter' => $data['pulse_oximeter'],
                    'all_member' => $data['all_member'],
                    'introduction_team' => $data['introduction_team'],
                    'informed_consent' => $data['informed_consent'],
                    'site_mark_visible' => $data['site_mark_visible'],
                    'relevant_images' => $data['relevant_images'],
                    'non_routine_steps' => $data['non_routine_steps'],
                    'case_duration' => $data['case_duration'],
                    'anticipated_blood_loss' => $data['anticipated_blood_loss'],
                    'specific_concerns' => $data['specific_concerns'],
                    'sterilization' => $data['sterilization'],
                    'antibiotic' => $data['antibiotic'],
                    'before_incision' => $data['before_incision'],
                    'issues_concern' => $data['issues_concern'],
                    'score' => $data['score'],
                    'score_word' => $data['score_word'],
                    'operative_procedure' => $data['operative_procedure'],
                    'sponges' => $data['sponges'],
                    'instruments' => $data['instruments'],
                    'needles' => $data['needles'],
                    'sharps' => $data['sharps'],
                    'identified_specimen' => $data['identified_specimen'],
                    'problems_to_address' => $data['problems_to_address'],
                    'concerns_for_recovery' => $data['concerns_for_recovery'],
                    'signature_circulating' => $data['signature_circulating'],
                    'signature_anesthesiologist' => $data['signature_anesthesiologist'],
                    'signature_surgeon' => $data['signature_surgeon'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getAllTraceNoList($data)
    {
        return DB::table('appointment_list')
            ->join('patients', 'patients.patient_id', '=', 'appointment_list.patients_id')
            ->select('appointment_list.*', "app_reason as reason", 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city')
            ->where('appointment_list.patients_id', $data['patient_id'])
            ->where('appointment_list.management_id', $data['management_id'])
            ->get();

        // return DB::table('hospital_admitted_patient')
        //     ->join('patients', 'patients.patient_id', '=', 'hospital_admitted_patient.patient_id')
        //     ->select('hospital_admitted_patient.*', 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city')
        //     ->where('hospital_admitted_patient.patient_id', $data['patient_id'])
        //     ->where('hospital_admitted_patient.management_id', $data['management_id'])
        //     ->groupBy('hospital_admitted_patient.trace_number')
        //     ->get();
    }

    public static function getAllCaseRecordList($data)
    {
        return DB::table('csqmmh_chart_clinic_ob')
            ->join('patients', 'patients.patient_id', '=', 'csqmmh_chart_clinic_ob.patient_id')
            ->select('csqmmh_chart_clinic_ob.*', 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city')
            ->where('csqmmh_chart_clinic_ob.patient_id', $data['patient_id'])
            ->where('csqmmh_chart_clinic_ob.management_id', $data['management_id'])
            ->where('csqmmh_chart_clinic_ob.main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getAllCaseRecordListDetails($data)
    {
        return DB::table('csqmmh_chart_clinic_ob')
            ->join('patients', 'patients.patient_id', '=', 'csqmmh_chart_clinic_ob.patient_id')
            ->select('csqmmh_chart_clinic_ob.*', 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city')
            ->where('csqmmh_chart_clinic_ob.patient_id', $data['patient_id'])
            ->where('csqmmh_chart_clinic_ob.management_id', $data['management_id'])
            ->where('csqmmh_chart_clinic_ob.main_mgmt_id', $data['main_mgmt_id'])
            ->where('csqmmh_chart_clinic_ob.ccas_id', $data['ccas_id'])
            ->get();
    }

    public static function createChartCaseRecord($data)
    {
        date_default_timezone_set('Asia/Manila');
        $ccco_id = 'ccco-' . rand(0, 9) . time();
        $pregnancy = $data['pregnancy'];
        $date = $data['date'];
        $duration = $data['duration'];
        $baby = $data['baby'];
        $delivery = $data['delivery'];
        $complications = $data['complications'];

        $arrayData = [];

        if (!empty($data['date'])) {
            for ($i = 0; $i < count($date); $i++) {
                $arrayData[] = array(
                    'cccop_id' => 'cccop-' . rand(0, 9) . time(),
                    'ccco_id' => $ccco_id,
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'pregnancy' => $pregnancy[$i],
                    'date' => date('Y-m-d', strtotime($date[$i])),
                    'duration' => $duration[$i],
                    'baby' => $baby[$i],
                    'delivery' => $delivery[$i],
                    'complications' => $complications[$i],
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        DB::table('csqmmh_chart_clinic_ob')->insert([
            'ccco_id' => $ccco_id,
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'case_no' => null,
            'date_case' => date('Y-m-d', strtotime($data['date_case'])),
            'name' => $data['name'],
            'age' => $data['age'],
            'address' => $data['address'],
            'telphone_no' => $data['telphone_no'],
            'occupation' => $data['occupation'],
            'marital_status' => $data['marital_status'],
            'partner_name' => $data['partner_name'],
            'partner_age' => $data['partner_age'],
            'partner_duration' => $data['partner_duration'],
            'partner_occupation' => $data['partner_occupation'],
            'past_diseases' => $data['past_diseases'],
            'hpn' => $data['hpn'],
            'dm' => $data['dm'],
            'ba' => $data['ba'],
            'ca' => $data['ca'],
            'thyroid' => $data['thyroid'],
            'cardiac' => $data['cardiac'],
            'blood_dyscrasia' => $data['blood_dyscrasia'],
            'ptb' => $data['ptb'],
            'allergies' => $data['allergies'],
            'prev_hospital' => $data['prev_hospital'],
            'prev_surgeries' => $data['prev_surgeries'],
            'fam_hist' => $data['fam_hist'],
            'fam_hpn' => $data['fam_hpn'],
            'fam_dm' => $data['fam_dm'],
            'fam_ba' => $data['fam_ba'],
            'fam_ca' => $data['fam_ca'],
            'congenital_anomalies' => $data['congenital_anomalies'],
            'others' => $data['others'],
            'personal_social' => $data['personal_social'],
            'menarche' => $data['menarche'],
            'personal_duration' => $data['personal_duration'],
            'cycle' => $data['cycle'],
            'amount' => $data['amount'],
            'dysmenorrhea' => $data['dysmenorrhea'],
            'pain_score' => $data['pain_score'],
            'abuse' => $data['abuse'],
            'coitarche' => $data['coitarche'],
            'no_sex_partners' => $data['no_sex_partners'],
            'pmp' => $data['pmp'],
            'lmp' => $data['lmp'],
            'contraceptive' => $data['contraceptive'],
            'last_papsmear' => $data['last_papsmear'],
            'obstetrical_record' => $data['obstetrical_record'],
            'review_of_system' => $data['review_of_system'],
            'sheent' => $data['sheent'],
            'chest_lungs' => $data['chest_lungs'],
            'abdomen' => $data['abdomen'],
            'genitourinary' => $data['genitourinary'],
            'muskuloskeletal' => $data['muskuloskeletal'],
            'cns_psychiatric' => $data['cns_psychiatric'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('csqmmh_chart_clinic_ob_pregnancy')->insert($arrayData);

    }

    //jhomar
    public static function getDischargedIns($data)
    {
        return DB::table('csqmmh_chart_discharge_instruction_medsheet')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }
    public static function newDischargedIns($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_discharge_instruction_medsheet')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    'case_no' => $data['case_no'],
                    'attending_physician' => $data['attending_physician'],
                    'date_ofconsult' => date('Y-m-d', strtotime($data['date_ofconsult'])),
                    'diagnosis' => strip_tags($data['diagnosis']),
                    'medicines' => htmlentities($data['medicines']),
                    'instructions' => htmlentities($data['instructions']),
                    'follow_up_checkup' => $data['follow_up_checkup'],
                    'received_by' => $data['received_by'],
                    'received_on' => date('Y-m-d', strtotime($data['received_on'])),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_discharge_instruction_medsheet')->insert([
            'ccdim_id' => 'ccdim-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],

            'date_ofconsult' => date('Y-m-d', strtotime($data['date_ofconsult'])),
            'attending_physician' => $data['attending_physician'],
            'diagnosis' => strip_tags($data['diagnosis']),
            'medicines' => htmlentities($data['medicines']),
            'instructions' => htmlentities($data['instructions']),
            'follow_up_checkup' => $data['follow_up_checkup'],
            'received_by' => $data['received_by'],
            'received_on' => date('Y-m-d', strtotime($data['received_on'])),

            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function getAddressoGraph($data)
    {
        return DB::table('csqmmh_chart_addressograph')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function newAddressoGraph($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['chart_type'] == 'for-update') {
            return DB::table('csqmmh_chart_addressograph')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([

                    "room_no" => $data["room_no"],
                    "physician" => $data["physician"],
                    "allergic_to" => $data["allergic_to"],
                    "year" => $data["year"],
                    "date_order" => date('Y-m-d', strtotime($data["date_order"])),
                    "renewal_date" => date('Y-m-d', strtotime($data["renewal_date"])),

                    "medication" => $data["medication"],
                    "dosage" => $data["dosage"],
                    "route_ofadmission" => $data["route_ofadmission"],
                    "frequency" => $data["frequency"],
                    "year" => $data["year"],

                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_addressograph')->insert([
            'cca_id' => 'cca-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],

            "room_no" => $data["room_no"],
            "physician" => $data["physician"],
            "allergic_to" => $data["allergic_to"],
            "year" => $data["year"],
            "date_order" => date('Y-m-d', strtotime($data["date_order"])),
            "renewal_date" => date('Y-m-d', strtotime($data["renewal_date"])),

            "medication" => $data["medication"],
            "dosage" => $data["dosage"],
            "route_ofadmission" => $data["route_ofadmission"],
            "frequency" => $data["frequency"],

            "year" => $data["year"],

            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }
    public static function sentPatientToDocu($data)
    {
        return DB::table('emergency_room_patients')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->update([
            'process_status' => 'or-documentation',
            'is_ornurse_processed' => 1,
            'is_ornurse_processed_date' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function editChartDoctorConsultation($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_doctors_consultation_form_table')
            ->where('ccdcft_id', $data['ccdcft_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'date' => date('Y-m-d', strtotime($data['date'])),
                'consultation_notes' => $data['consultation_notes'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function editChartDoctorsOrder($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_doctors_order_form_table')
            ->where('ccdoft_id', $data['ccdoft_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'date_time' => date('Y-m-d H:i:s', strtotime($data['date_time'])),
                'progress_name' => $data['progress_name'],
                'order_name' => $data['order_name'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function editChartPostAnesthesiaCareUnit($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_post_anesthesia_care_table')
            ->where('ccpact_id', $data['ccpact_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'date_time' => date('Y-m-d H:i:s', strtotime($data['date_time'])),
                'bp' => $data['bp'],
                'temp' => $data['temp'],
                'pr' => $data['pr'],
                'rr' => $data['rr'],
                'o2_sat' => $data['o2_sat'],
                'remarks' => $data['remarks'],
                'ivf_solution' => $data['ivf_solution'],
                'ivf_date_time' => date('Y-m-d H:i:s', strtotime($data['ivf_date_time'])),
                'ivf_remarks' => $data['ivf_remarks'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function editChartJPDrainMonitoring($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_jp_drain_monitoring_table')
            ->where('ccjdt_id', $data['ccjdt_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'jp_date_time' => date('Y-m-d H:i:s', strtotime($data['jp_date_time'])),
                'volume_ml_cc' => $data['volume_ml_cc'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function removeChartBedSideNotes($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_nurses_bedsidenotes_table')
            ->where('cnbt_id', $data['cnbt_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function editChartBedSideNotes($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_nurses_bedsidenotes_table')
            ->where('cnbt_id', $data['cnbt_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'notes_datetime' => date('Y-m-d H:i:s', strtotime($data['notes_datetime'])),
                'notes' => $data['notes'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function createChartBedsideNotes($data)
    {
        date_default_timezone_set('Asia/Manila');
        $notes_datetime = $data['notes_datetime'];
        $notes = $data['notes'];
        $arrayData = [];
        $cnb_id = 'cnb-' . rand(0, 9) . time();

        if ($data['status'] == 'for-new') {
            if (!empty($data['notes_datetime'])) {
                for ($i = 0; $i < count($notes_datetime); $i++) {
                    $arrayData[] = array(
                        'cnbt_id' => 'cnbt-' . rand(0, 9) . time(),
                        'cnb_id' => $cnb_id,
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'notes_datetime' => date('Y-m-d H:i:s', strtotime($notes_datetime[$i])),
                        'notes' => $notes[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }
            DB::table('csqmmh_chart_nurses_bedsidenotes_table')->insert($arrayData);
            return DB::table('csqmmh_chart_nurses_bedsidenotes')->insert([
                'cnb_id' => $cnb_id,
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'case_no' => $data['case_no'],
                'trace_number' => $data['trace_number'],
                'attending_physician' => $data['attending_physician'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            if (!empty($data['notes_datetime'])) {
                for ($i = 0; $i < count($notes_datetime); $i++) {
                    $arrayData[] = array(
                        'cnbt_id' => 'cnbt-' . rand(0, 9) . time(),
                        'cnb_id' => $data['cnb_id'],
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'management_id' => $data['management_id'],
                        'patient_id' => $data['patient_id'],
                        'trace_number' => $data['trace_number'],
                        'notes_datetime' => date('Y-m-d H:i:s', strtotime($notes_datetime[$i])),
                        'notes' => $notes[$i],
                        'status' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                }
            }
            DB::table('csqmmh_chart_nurses_bedsidenotes_table')->insert($arrayData);
            return DB::table('csqmmh_chart_nurses_bedsidenotes')->where('cnb_id', $data['cnb_id'])->update([
                'case_no' => $data['case_no'],
                'attending_physician' => $data['attending_physician'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function createDoctorsConsultationTable($data)
    {
        $date = $data['date'];
        $consultation_notes = $data['consultation_notes'];
        if (!empty($data['date'])) {
            for ($i = 0; $i < count($date); $i++) {
                $arrayData[] = array(
                    'ccdcft_id' => 'ccdcft-' . rand(0, 9) . time(),
                    'ccdcf_id' => $data['ccdcf_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'trace_number' => $data['trace_number'],
                    'date' => date('Y-m-d', strtotime($date[$i])),
                    'consultation_notes' => $consultation_notes[$i],
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }
            DB::table('csqmmh_chart_doctors_consultation_form_table')->insert($arrayData);
        }
        return true;
        // $notes_datetime = $data['notes_datetime'];
        // return DB::connection('mysql')->table('csqmmh_chart_doctors_consultation_form_table')
        //     ->insert([
        //         'ccdcft_id' => 'ccdcft-' . rand(0, 9) . time(),
        //         'ccdcf_id' => $data['ccdcf_id'],
        //         'main_mgmt_id' => $data['main_mgmt_id'],
        //         'management_id' => $data['management_id'],
        //         'patient_id' => $data['patient_id'],
        //         'trace_number' => $data['trace_number'],
        //         'date' => date('Y-m-d', strtotime($data['date'])),
        //         'consultation_notes' => $data['consultation_note'],
        //         'status' => 1,
        //         'updated_at' => date('Y-m-d H:i:s'),
        //         'created_at' => date('Y-m-d H:i:s'),
        //     ]);
    }

    public static function createDoctorsOrderTable($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_doctors_order_form_table')
            ->insert([
                'ccdoft_id' => 'ccdoft-' . rand(0, 9) . time(),
                'ccdof_id' => $data['ccdof_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'date_time' => date('Y-m-d H:i:s', strtotime($data['date_time'])),
                'progress_name' => $data['progress_name'],
                'order_name' => $data['order_name'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function createPostAnethesiaCareUnitTable($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_post_anesthesia_care_table')
            ->insert([
                'ccpact_id' => 'ccpact-' . rand(0, 9) . time(),
                'ccpac_id' => $data['ccpac_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'date_time' => date('Y-m-d H:i:s', strtotime($data['date_time'])),
                'bp' => $data['bp'],
                'temp' => $data['temp'],
                'pr' => $data['pr'],
                'rr' => $data['rr'],
                'o2_sat' => $data['o2_sat'],
                'remarks' => $data['remarks'],
                'ivf_solution' => $data['ivf_solution'],
                'ivf_date_time' => date('Y-m-d H:i:s', strtotime($data['ivf_date_time'])),
                'ivf_remarks' => $data['ivf_remarks'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function createJPDrainMonitoringTable($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_jp_drain_monitoring_table')
            ->insert([
                'ccjdt_id' => 'ccjdt-' . rand(0, 9) . time(),
                'ccjd_id' => $data['ccjd_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'jp_date_time' => date('Y-m-d H:i:s', strtotime($data['jp_date_time'])),
                'volume_ml_cc' => $data['volume_ml_cc'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function createBedSideNotesTable($data)
    {
        return DB::connection('mysql')->table('csqmmh_chart_nurses_bedsidenotes_table')
            ->insert([
                'cnbt_id' => 'cnbt-' . rand(0, 9) . time(),
                'cnb_id' => $data['cnb_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'notes_datetime' => date('Y-m-d H:i:s', strtotime($data['notes_datetime'])),
                'notes' => $data['notes'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getMedicalSheetChart($data)
    {
        return DB::table('csqmmh_chart_medical_treatment_sheet')->where('patient_id', $data['patient_id'])->where('trace_number', $data['trace_number'])->get();
    }

    public static function createMedicalSheet($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['status'] == 'for-update') {
            return DB::table('csqmmh_chart_medical_treatment_sheet')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    "room_no" => $data["room_no"],
                    "physician" => $data["physician"],
                    "allergic_to" => $data["allergic_to"],

                    "date_order" => !empty($data['date_order']) ? date('Y-m-d', strtotime($data["date_order"])) : null,
                    "renewal_date" => !empty($data['renewal_date']) ? date('Y-m-d', strtotime($data["renewal_date"])) : null,
                    "medication" => $data["medication"],

                    "date_order2" => !empty($data['date_order2']) ? date('Y-m-d', strtotime($data["date_order2"])) : null,
                    "renewal_date2" => !empty($data['renewal_date2']) ? date('Y-m-d', strtotime($data["renewal_date2"])) : null,
                    "medication2" => $data["medication2"],

                    "date_order3" => !empty($data['date_order3']) ? date('Y-m-d', strtotime($data["date_order3"])) : null,
                    "renewal_date3" => !empty($data['renewal_date3']) ? date('Y-m-d', strtotime($data["renewal_date3"])) : null,
                    "medication3" => $data["medication3"],

                    "date_order4" => !empty($data['date_order4']) ? date('Y-m-d', strtotime($data["date_order4"])) : null,
                    "renewal_date4" => !empty($data['renewal_date4']) ? date('Y-m-d', strtotime($data["renewal_date4"])) : null,
                    "medication4" => $data["medication4"],

                    "date_order5" => !empty($data['date_order5']) ? date('Y-m-d', strtotime($data["date_order5"])) : null,
                    "renewal_date5" => !empty($data['renewal_date5']) ? date('Y-m-d', strtotime($data["renewal_date5"])) : null,
                    "medication5" => $data["medication5"],

                    "date_order6" => !empty($data['date_order6']) ? date('Y-m-d', strtotime($data["date_order6"])) : null,
                    "renewal_date6" => !empty($data['renewal_date6']) ? date('Y-m-d', strtotime($data["renewal_date6"])) : null,
                    "medication6" => $data["medication6"],

                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_medical_treatment_sheet')->insert([
            'ccmt_id' => 'ccmt-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],
            "room_no" => $data["room_no"],
            "physician" => $data["physician"],
            "allergic_to" => $data["allergic_to"],

            "date_order" => !empty($data['date_order']) ? date('Y-m-d', strtotime($data["date_order"])) : null,
            "renewal_date" => !empty($data['renewal_date']) ? date('Y-m-d', strtotime($data["renewal_date"])) : null,
            "medication" => $data["medication"],

            "date_order2" => !empty($data['date_order2']) ? date('Y-m-d', strtotime($data["date_order2"])) : null,
            "renewal_date2" => !empty($data['renewal_date2']) ? date('Y-m-d', strtotime($data["renewal_date2"])) : null,
            "medication2" => $data["medication2"],

            "date_order3" => !empty($data['date_order3']) ? date('Y-m-d', strtotime($data["date_order3"])) : null,
            "renewal_date3" => !empty($data['renewal_date3']) ? date('Y-m-d', strtotime($data["renewal_date3"])) : null,
            "medication3" => $data["medication3"],

            "date_order4" => !empty($data['date_order4']) ? date('Y-m-d', strtotime($data["date_order4"])) : null,
            "renewal_date4" => !empty($data['renewal_date4']) ? date('Y-m-d', strtotime($data["renewal_date4"])) : null,
            "medication4" => $data["medication4"],

            "date_order5" => !empty($data['date_order5']) ? date('Y-m-d', strtotime($data["date_order5"])) : null,
            "renewal_date5" => !empty($data['renewal_date5']) ? date('Y-m-d', strtotime($data["renewal_date5"])) : null,
            "medication5" => $data["medication5"],

            "date_order6" => !empty($data['date_order6']) ? date('Y-m-d', strtotime($data["date_order6"])) : null,
            "renewal_date6" => !empty($data['renewal_date6']) ? date('Y-m-d', strtotime($data["renewal_date6"])) : null,
            "medication6" => $data["medication6"],

            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // 03-07-2022
    public static function getChartCovid19Checklist($data)
    {
        return DB::table('csqmmh_chart_asc_covid19_triage_checklist')
            ->where('management_id', $data['management_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function createCovid19Checklist($data)
    {
        $ccact_id = $data['ccact_id'];
        if ($data['status'] == 'for-new') {
            return DB::table('csqmmh_chart_asc_covid19_triage_checklist')
                ->insert([
                    'ccact_id' => 'ccact-' . rand(0, 9) . time(),
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'management_id' => $data['management_id'],
                    'patient_id' => $data['patient_id'],
                    'trace_number' => $data['trace_number'],

                    'rank_area' => $data['rank_area'],
                    'case_definition' => $data['case_definition'],
                    'severity_class' => $data['severity_class'],
                    'disposition_plan' => $data['disposition_plan'],
                    'physician' => $data['physician'],
                    'sickness' => $data['sickness'],
                    'others' => $data['others'],
                    'temp' => $data['temp'],
                    'bp' => $data['bp'],
                    'rr' => $data['rr'],
                    'hr' => $data['hr'],
                    'o2_sat' => $data['o2_sat'],
                    'weight' => $data['weight'],
                    'height' => $data['height'],
                    'bmi' => $data['bmi'],

                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            return DB::table('csqmmh_chart_asc_covid19_triage_checklist')
                ->where('ccact_id', $data['ccact_id'])
                ->update([
                    'rank_area' => $data['rank_area'],
                    'case_definition' => $data['case_definition'],
                    'severity_class' => $data['severity_class'],
                    'disposition_plan' => $data['disposition_plan'],
                    'physician' => $data['physician'],
                    'sickness' => $data['sickness'],
                    'others' => $data['others'],
                    'temp' => $data['temp'],
                    'bp' => $data['bp'],
                    'rr' => $data['rr'],
                    'hr' => $data['hr'],
                    'o2_sat' => $data['o2_sat'],
                    'weight' => $data['weight'],
                    'height' => $data['height'],
                    'bmi' => $data['bmi'],

                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

        }
    }

    public static function updateChartTempAndPulse($data)
    {
        date_default_timezone_set('Asia/Manila');
        $cca_id = 'cca-' . rand(0, 9) . time();
        $arrayData = [];

        if ($data['status'] == 'for-new') {
            return DB::table('csqmmh_chart_addressograph')->insert([
                'cca_id' => $cca_id,
                'main_mgmt_id' => $data['main_mgmt_id'],
                'management_id' => $data['management_id'],
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'case_no' => $data['case_no'],
                'physician' => $data['physician'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            return DB::table('csqmmh_chart_addressograph')
                ->where('cca_id', $data['cca_id'])
                ->update([
                    'case_no' => $data['case_no'],
                    'physician' => $data['physician'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getMedicalSheetStatChart($data)
    {
        return DB::table('csqmmh_chart_medical_treatment_sheet_stat')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function createMedicalSheetStat($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['status'] == 'for-update') {
            return DB::table('csqmmh_chart_medical_treatment_sheet_stat')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    "room_no" => $data["room_no"],
                    "physician" => $data["physician"],
                    "allergic_to" => $data["allergic_to"],
                    "date_order" => !empty($data['date_order']) ? date('Y-m-d', strtotime($data["date_order"])) : null,
                    "renewal_date" => !empty($data['renewal_date']) ? date('Y-m-d', strtotime($data["renewal_date"])) : null,
                    "medication" => $data["medication"],
                    "date_order2" => !empty($data['date_order2']) ? date('Y-m-d', strtotime($data["date_order2"])) : null,
                    "renewal_date2" => !empty($data['renewal_date2']) ? date('Y-m-d', strtotime($data["renewal_date2"])) : null,
                    "medication2" => $data["medication2"],
                    "date_order3" => !empty($data['date_order3']) ? date('Y-m-d', strtotime($data["date_order3"])) : null,
                    "renewal_date3" => !empty($data['renewal_date3']) ? date('Y-m-d', strtotime($data["renewal_date3"])) : null,
                    "medication3" => $data["medication3"],
                    "date_order4" => !empty($data['date_order4']) ? date('Y-m-d', strtotime($data["date_order4"])) : null,
                    "renewal_date4" => !empty($data['renewal_date4']) ? date('Y-m-d', strtotime($data["renewal_date4"])) : null,
                    "medication4" => $data["medication4"],
                    "date_order5" => !empty($data['date_order5']) ? date('Y-m-d', strtotime($data["date_order5"])) : null,
                    "renewal_date5" => !empty($data['renewal_date5']) ? date('Y-m-d', strtotime($data["renewal_date5"])) : null,
                    "medication5" => $data["medication5"],
                    "date_order6" => !empty($data['date_order6']) ? date('Y-m-d', strtotime($data["date_order6"])) : null,
                    "renewal_date6" => !empty($data['renewal_date6']) ? date('Y-m-d', strtotime($data["renewal_date6"])) : null,
                    "medication6" => $data["medication6"],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_medical_treatment_sheet_stat')->insert([
            'ccmts_id' => 'ccmts-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],
            "room_no" => $data["room_no"],
            "physician" => $data["physician"],
            "allergic_to" => $data["allergic_to"],
            "date_order" => !empty($data['date_order']) ? date('Y-m-d', strtotime($data["date_order"])) : null,
            "renewal_date" => !empty($data['renewal_date']) ? date('Y-m-d', strtotime($data["renewal_date"])) : null,
            "medication" => $data["medication"],
            "date_order2" => !empty($data['date_order2']) ? date('Y-m-d', strtotime($data["date_order2"])) : null,
            "renewal_date2" => !empty($data['renewal_date2']) ? date('Y-m-d', strtotime($data["renewal_date2"])) : null,
            "medication2" => $data["medication2"],
            "date_order3" => !empty($data['date_order3']) ? date('Y-m-d', strtotime($data["date_order3"])) : null,
            "renewal_date3" => !empty($data['renewal_date3']) ? date('Y-m-d', strtotime($data["renewal_date3"])) : null,
            "medication3" => $data["medication3"],
            "date_order4" => !empty($data['date_order4']) ? date('Y-m-d', strtotime($data["date_order4"])) : null,
            "renewal_date4" => !empty($data['renewal_date4']) ? date('Y-m-d', strtotime($data["renewal_date4"])) : null,
            "medication4" => $data["medication4"],
            "date_order5" => !empty($data['date_order5']) ? date('Y-m-d', strtotime($data["date_order5"])) : null,
            "renewal_date5" => !empty($data['renewal_date5']) ? date('Y-m-d', strtotime($data["renewal_date5"])) : null,
            "medication5" => $data["medication5"],
            "date_order6" => !empty($data['date_order6']) ? date('Y-m-d', strtotime($data["date_order6"])) : null,
            "renewal_date6" => !empty($data['renewal_date6']) ? date('Y-m-d', strtotime($data["renewal_date6"])) : null,
            "medication6" => $data["medication6"],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getMedicalSheetPRNChart($data)
    {
        return DB::table('csqmmh_chart_medical_treatment_sheet_prn')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function createMedicalSheetPRN($data)
    {
        date_default_timezone_set('Asia/Manila');

        if ($data['status'] == 'for-update') {
            return DB::table('csqmmh_chart_medical_treatment_sheet_prn')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->update([
                    "room_no" => $data["room_no"],
                    "physician" => $data["physician"],
                    "allergic_to" => $data["allergic_to"],
                    "date_order" => !empty($data['date_order']) ? date('Y-m-d', strtotime($data["date_order"])) : null,
                    "renewal_date" => !empty($data['renewal_date']) ? date('Y-m-d', strtotime($data["renewal_date"])) : null,
                    "medication" => $data["medication"],
                    "date_order2" => !empty($data['date_order2']) ? date('Y-m-d', strtotime($data["date_order2"])) : null,
                    "renewal_date2" => !empty($data['renewal_date2']) ? date('Y-m-d', strtotime($data["renewal_date2"])) : null,
                    "medication2" => $data["medication2"],
                    "date_order3" => !empty($data['date_order3']) ? date('Y-m-d', strtotime($data["date_order3"])) : null,
                    "renewal_date3" => !empty($data['renewal_date3']) ? date('Y-m-d', strtotime($data["renewal_date3"])) : null,
                    "medication3" => $data["medication3"],
                    "date_order4" => !empty($data['date_order4']) ? date('Y-m-d', strtotime($data["date_order4"])) : null,
                    "renewal_date4" => !empty($data['renewal_date4']) ? date('Y-m-d', strtotime($data["renewal_date4"])) : null,
                    "medication4" => $data["medication4"],
                    "date_order5" => !empty($data['date_order5']) ? date('Y-m-d', strtotime($data["date_order5"])) : null,
                    "renewal_date5" => !empty($data['renewal_date5']) ? date('Y-m-d', strtotime($data["renewal_date5"])) : null,
                    "medication5" => $data["medication5"],
                    "date_order6" => !empty($data['date_order6']) ? date('Y-m-d', strtotime($data["date_order6"])) : null,
                    "renewal_date6" => !empty($data['renewal_date6']) ? date('Y-m-d', strtotime($data["renewal_date6"])) : null,
                    "medication6" => $data["medication6"],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return DB::table('csqmmh_chart_medical_treatment_sheet_prn')->insert([
            'ccmp_id' => 'ccmp-' . rand(0, 9999) . time(),
            'main_mgmt_id' => $data['main_mgmt_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'case_no' => $data['case_no'],
            "room_no" => $data["room_no"],
            "physician" => $data["physician"],
            "allergic_to" => $data["allergic_to"],
            "date_order" => !empty($data['date_order']) ? date('Y-m-d', strtotime($data["date_order"])) : null,
            "renewal_date" => !empty($data['renewal_date']) ? date('Y-m-d', strtotime($data["renewal_date"])) : null,
            "medication" => $data["medication"],
            "date_order2" => !empty($data['date_order2']) ? date('Y-m-d', strtotime($data["date_order2"])) : null,
            "renewal_date2" => !empty($data['renewal_date2']) ? date('Y-m-d', strtotime($data["renewal_date2"])) : null,
            "medication2" => $data["medication2"],
            "date_order3" => !empty($data['date_order3']) ? date('Y-m-d', strtotime($data["date_order3"])) : null,
            "renewal_date3" => !empty($data['renewal_date3']) ? date('Y-m-d', strtotime($data["renewal_date3"])) : null,
            "medication3" => $data["medication3"],
            "date_order4" => !empty($data['date_order4']) ? date('Y-m-d', strtotime($data["date_order4"])) : null,
            "renewal_date4" => !empty($data['renewal_date4']) ? date('Y-m-d', strtotime($data["renewal_date4"])) : null,
            "medication4" => $data["medication4"],
            "date_order5" => !empty($data['date_order5']) ? date('Y-m-d', strtotime($data["date_order5"])) : null,
            "renewal_date5" => !empty($data['renewal_date5']) ? date('Y-m-d', strtotime($data["renewal_date5"])) : null,
            "medication5" => $data["medication5"],
            "date_order6" => !empty($data['date_order6']) ? date('Y-m-d', strtotime($data["date_order6"])) : null,
            "renewal_date6" => !empty($data['renewal_date6']) ? date('Y-m-d', strtotime($data["renewal_date6"])) : null,
            "medication6" => $data["medication6"],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

}
