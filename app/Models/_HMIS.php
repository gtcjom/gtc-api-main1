<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _HMIS extends Model
{
    public static function getLaboratoryIdByMgt($management_id)
    {
        return DB::table('laboratory_list')->select('laboratory_id')->where('management_id', $management_id)->first();
    }

    public static function getImagingIdByMgt($management_id)
    {
        return DB::table('imaging')->select('imaging_id')->where('management_id', $management_id)->first();
    }

    public static function hmisGetHeaderInfo($data)
    {
        return DB::table('management')->select('m_id', 'name', 'image')->where('user_id', $data['user_id'])->first();
    }

    public static function hmisGetAllIncome($data)
    {
        $management_id = $data['management_id'];

        $query = "SELECT management_id,

            (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE bill_from = 'imaging' AND management_id = '$management_id' ) as incomeImaging,
            (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE bill_from = 'laboratory' AND management_id = '$management_id' ) as incomeLaboratory,
            (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE bill_from = 'doctor' AND management_id = '$management_id' ) as incomeDoctor,
            (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE bill_from = 'psychology' AND management_id = '$management_id' ) as incomePsychology,
            (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE bill_from = 'Other Test' AND management_id = '$management_id' ) as incomeOthers,
            (SELECT IFNULL(SUM(bill_amount), 0) FROM cashier_patientbills_records WHERE bill_from = 'packages' AND management_id = '$management_id' ) as incomePackages,

            (SELECT IFNULL(SUM(total), 0) FROM pharmacyhospital_sales WHERE management_id = '$management_id' ) as incomePharmacy

        from cashier_patientbills_records GROUP BY management_id ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hmisGetAllLabTest($data)
    {
        // return DB::connection('mysql')->table('laboratory_test')
        //     ->where('laboratory_id', (new _HMIS)::getLaboratoryIdByMgt($data['management_id'])->laboratory_id)
        //     ->where('status', 1)
        //     ->get();

        return DB::table('laboratory_items_laborder')
            ->where('management_id', $data['management_id'])
            ->where('laboratory_id', (new _HMIS)::getLaboratoryIdByMgt($data['management_id'])->laboratory_id)
            ->groupBy('order_id')
            ->orderBy('laborder', 'asc')
            ->get();
    }

    public static function himsSaveNewTest($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::connection('mysql')->table('laboratory_test')->insert([
            'lt_id' => 'lt-' . rand(0, 99999),
            'laboratory_id' => (new _HMIS)::getLaboratoryIdByMgt($data['management_id'])->laboratory_id,
            'laboratory_test' => $data['test'],
            'department' => $data['dept'],
            'laboratory_rate' => $data['rate'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function himsSalesResultLab($data)
    {
        return DB::connection('mysql')->table('cashier_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('cashier_patientbills_records.*', DB::raw('concat(patients.firstname," ",patients.lastname) as name'))
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.bill_from', 'laboratory')
            ->where('cashier_patientbills_records.status', 1)
            ->get();
    }

    public static function himsPendingPatientsLab($data)
    {
        $query = " SELECT patient_id as pid,

                (SELECT concat(lastname,', ',firstname) FROM patients where patient_id = pid) as patient_name,
                (SELECT concat(barangay,', ',city) FROM patients where patient_id = pid) as patient_address,

                (SELECT count(id) FROM laboratory_hematology where patient_id = pid AND order_status='new-order-paid') as count_hema,
                (SELECT count(id) FROM laboratory_sorology where patient_id = pid AND order_status='new-order-paid') as count_reso,
                (SELECT count(id) FROM laboratory_microscopy where patient_id = pid AND order_status='new-order-paid') as count_micro,
                (SELECT count(id) FROM laboratory_chemistry where patient_id = pid AND order_status='new-order-paid') as count_chem,
                (SELECT count(id) FROM laboratory_fecal_analysis where patient_id = pid AND order_status='new-order-paid') as count_fecal,

                (SELECT count(id) FROM laboratory_ecg where patient_id = pid AND order_status='new-order-paid') as count_ecg,
                (SELECT count(id) FROM laboratory_medical_exam where patient_id = pid AND order_status='new-order-paid') as count_med_exam,
                (SELECT count(id) FROM laboratory_papsmear where patient_id = pid AND order_status='new-order-paid') as count_papsmear,
                (SELECT count(id) FROM laboratory_stooltest where patient_id = pid AND order_status='new-order-paid') as count_stool,
                (SELECT count(id) FROM laboratory_urinalysis where patient_id = pid AND order_status='new-order-paid') as count_urin,
                (SELECT IFNULL(sum(count_hema + count_reso + count_micro + count_chem + count_fecal + count_ecg + count_med_exam + count_papsmear + count_stool + count_urin), 0)) as order_count

            FROM cashier_patientbills_records WHERE management_id = '" . $data['management_id'] . "' AND bill_from = 'laboratory' GROUP BY patient_id ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsSalesResultImg($data)
    {
        return DB::connection('mysql')->table('cashier_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('cashier_patientbills_records.*', DB::raw('concat(patients.firstname," ",patients.lastname) as name'))
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.bill_from', 'imaging')
            ->where('cashier_patientbills_records.status', 1)
            ->get();
    }

    public static function himsPendingPatientsImg($data)
    {
        $imaging_id = _HMIS::getImagingIdByMgt($data['management_id'])->imaging_id;
        $query = "
            SELECT patient_id as pid,

                (SELECT concat(lastname,', ',firstname) FROM patients where patient_id = pid) as patient_name,
                (SELECT concat(barangay,', ',city) FROM patients where patient_id = pid) as patient_address,
                (SELECT count(id) FROM imaging_center WHERE imaging_center = '$imaging_id' AND patients_id = pid AND imaging_result_attachment IS NULL ) as count_patient_pending

            FROM cashier_patientbills_records WHERE management_id = '" . $data['management_id'] . "' AND bill_from = 'imaging' GROUP BY patient_id
        ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsGetAllORByBillFrom($data)
    {
        $query = "SELECT *, patient_id as pid, receipt_number as receipt,

            (SELECT concat(lastname,', ',firstname) FROM patients where patient_id = pid) as patient_name,
            (SELECT concat(barangay,', ',city) FROM patients where patient_id = pid) as patient_address,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE receipt_number = receipt AND bill_from = '" . $data['bill_from'] . "') as total_payment

        FROM cashier_patientbills_records WHERE management_id = '" . $data['management_id'] . "' AND bill_from = '" . $data['bill_from'] . "' GROUP BY receipt_number ORDER BY patient_name ASC ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsGetReceiptInfoPrint($data)
    {
        $query = "SELECT *, patient_id as pid,

            (SELECT concat(lastname,', ',firstname) FROM patients where patient_id = pid limit 1) as patient_name,
            (SELECT concat(barangay,', ',city) FROM patients where patient_id = pid limit 1) as patient_address,
            (SELECT name FROM imaging where management_id = '" . $data['management_id'] . "' limit 1) as imaging_name,
            (SELECT address FROM imaging where management_id = '" . $data['management_id'] . "' limit 1) as imaging_address,
            (SELECT name FROM laboratory_list where management_id = '" . $data['management_id'] . "' limit 1) as lab_name,
            (SELECT address FROM laboratory_list where management_id = '" . $data['management_id'] . "' limit 1) as lab_address,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE receipt_number = '" . $data['receipt_number'] . "') as total_payment

        FROM cashier_patientbills_records WHERE receipt_number = '" . $data['receipt_number'] . "' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsGetAllActive($data)
    {
        $management_id = $data['branch'];
        $main_mgmt_id = $data['main_mgmt_id'];

        if ($management_id == 'All') {
            $query = "SELECT *, user_id as userID,
                (SELECT user_fullname FROM accounting_account where user_id = userID limit 1) as _name_accounting,
                (SELECT user_fullname FROM hospital_billing_account where user_id = userID limit 1) as _name_billing,
                (SELECT user_fullname FROM cashier where user_id = userID limit 1) as _name_cashier,
                (SELECT name FROM doctors where user_id = userID limit 1) as _name_doctors,
                (SELECT user_fullname FROM encoder where user_id = userID limit 1) as _name_encoder,
                (SELECT user_fullname FROM haptech_account where user_id = userID limit 1) as _name_haptech,
                (SELECT user_fullname FROM hr_account where user_id = userID limit 1) as _name_hr,
                (SELECT user_fullname FROM imaging where user_id = userID limit 1) as _name_imaging,
                (SELECT user_fullname FROM laboratory_list where user_id = userID limit 1) as _name_laboratory,
                (SELECT user_fullname FROM operation_manager_account where user_id = userID limit 1) as _name_om,
                (SELECT user_fullname FROM pharmacy where user_id = userID limit 1) as _name_pharmacy,
                (SELECT user_fullname FROM psychology_account where user_id = userID limit 1) as _name_psychology,
                (SELECT name FROM radiologist where user_id = userID limit 1) as _name_radiologist,
                (SELECT user_fullname FROM admission_account where user_id = userID limit 1) as _name_registration,
                (SELECT user_fullname FROM stockroom_acccount where user_id = userID limit 1) as _name_stockroom,
                (SELECT user_fullname FROM triage_account where user_id = userID limit 1) as _name_triage,
                (SELECT user_fullname FROM warehouse_accounts where user_id = userID limit 1) as _name_warehouse,
                (SELECT user_fullname FROM other_account where user_id = userID limit 1) as _name_other,

                (SELECT
                CASE
                    WHEN _name_accounting  IS NOT NULL THEN _name_accounting
                    WHEN _name_billing  IS NOT NULL THEN _name_billing
                    WHEN _name_cashier IS NOT NULL THEN _name_cashier
                    WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                    WHEN _name_encoder IS NOT NULL THEN _name_encoder
                    WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                    WHEN _name_hr  IS NOT NULL THEN _name_hr
                    WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                    WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                    WHEN _name_om IS NOT NULL THEN _name_om
                    WHEN _name_pharmacy IS NOT NULL THEN _name_pharmacy
                    WHEN _name_psychology IS NOT NULL THEN _name_psychology
                    WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                    WHEN _name_registration IS NOT NULL THEN _name_registration
                    WHEN _name_stockroom IS NOT NULL THEN _name_stockroom
                    WHEN _name_triage IS NOT NULL THEN _name_triage
                    WHEN _name_warehouse IS NOT NULL THEN _name_warehouse
                    WHEN _name_other IS NOT NULL THEN _name_other
                    ELSE user_id
                END
                ) as _account_name

            FROM hospital_dtr_logs WHERE main_mgmt_id = '$main_mgmt_id' ORDER BY timein DESC ";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        } else {
            $query = "SELECT *, user_id as userID,
                (SELECT user_fullname FROM accounting_account where user_id = userID limit 1) as _name_accounting,
                (SELECT user_fullname FROM hospital_billing_account where user_id = userID limit 1) as _name_billing,
                (SELECT user_fullname FROM cashier where user_id = userID limit 1) as _name_cashier,
                (SELECT name FROM doctors where user_id = userID limit 1) as _name_doctors,
                (SELECT user_fullname FROM encoder where user_id = userID limit 1) as _name_encoder,
                (SELECT user_fullname FROM haptech_account where user_id = userID limit 1) as _name_haptech,
                (SELECT user_fullname FROM hr_account where user_id = userID limit 1) as _name_hr,
                (SELECT user_fullname FROM imaging where user_id = userID limit 1) as _name_imaging,
                (SELECT user_fullname FROM laboratory_list where user_id = userID limit 1) as _name_laboratory,
                (SELECT user_fullname FROM operation_manager_account where user_id = userID limit 1) as _name_om,
                (SELECT user_fullname FROM pharmacy where user_id = userID limit 1) as _name_pharmacy,
                (SELECT user_fullname FROM psychology_account where user_id = userID limit 1) as _name_psychology,
                (SELECT name FROM radiologist where user_id = userID limit 1) as _name_radiologist,
                (SELECT user_fullname FROM admission_account where user_id = userID limit 1) as _name_registration,
                (SELECT user_fullname FROM stockroom_acccount where user_id = userID limit 1) as _name_stockroom,
                (SELECT user_fullname FROM triage_account where user_id = userID limit 1) as _name_triage,
                (SELECT user_fullname FROM warehouse_accounts where user_id = userID limit 1) as _name_warehouse,
                (SELECT user_fullname FROM other_account where user_id = userID limit 1) as _name_other,

                (SELECT
                CASE
                    WHEN _name_accounting  IS NOT NULL THEN _name_accounting
                    WHEN _name_billing  IS NOT NULL THEN _name_billing
                    WHEN _name_cashier IS NOT NULL THEN _name_cashier
                    WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                    WHEN _name_encoder IS NOT NULL THEN _name_encoder
                    WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                    WHEN _name_hr  IS NOT NULL THEN _name_hr
                    WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                    WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                    WHEN _name_om IS NOT NULL THEN _name_om
                    WHEN _name_pharmacy IS NOT NULL THEN _name_pharmacy
                    WHEN _name_psychology IS NOT NULL THEN _name_psychology
                    WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                    WHEN _name_registration IS NOT NULL THEN _name_registration
                    WHEN _name_stockroom IS NOT NULL THEN _name_stockroom
                    WHEN _name_triage IS NOT NULL THEN _name_triage
                    WHEN _name_warehouse IS NOT NULL THEN _name_warehouse
                    WHEN _name_other IS NOT NULL THEN _name_other
                    ELSE user_id
                END
                ) as _account_name

            FROM hospital_dtr_logs WHERE management_id = '$management_id' ORDER BY timein DESC ";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    public static function himsGetAllAccountList($data)
    {
        $management_id = $data['management_id'];
        $main_management_id = $data['main_management_id'];

        $database = '';
        $selectname = '';
        $selectimage = '';
        $selectuserid = '';

        if ($data['account'] == 'admitting') {
            $database = 'hospital_admitting_accounts';
            $selectname = 'hospital_admitting_accounts.user_fullname as name';
            $selectimage = 'hospital_admitting_accounts.image as image';
            $selectuserid = 'hospital_admitting_accounts.user_id';
        } else if ($data['account'] == 'accounting') {
            $database = 'accounting_account';
            $selectname = 'accounting_account.user_fullname as name';
            $selectimage = 'accounting_account.image as image';
            $selectuserid = 'accounting_account.user_id';
        } elseif ($data['account'] == 'billing') {
            $database = 'hospital_billing_account';
            $selectname = 'hospital_billing_account.user_fullname as name';
            $selectimage = 'hospital_billing_account.image as image';
            $selectuserid = 'hospital_billing_account.user_id';
        } elseif ($data['account'] == 'cashier') {
            $database = 'cashier';
            $selectname = 'cashier.user_fullname as name';
            $selectimage = 'cashier.image as image';
            $selectuserid = 'cashier.user_id';
        } elseif ($data['account'] == 'doctor') {
            $database = 'doctors';
            $selectname = 'doctors.name';
            $selectimage = 'doctors.image as image';
            $selectuserid = 'doctors.user_id';
        } elseif ($data['account'] == 'documentation') {
            $database = 'encoder';
            $selectname = 'encoder.user_fullname as name';
            $selectimage = 'encoder.image as image';
            $selectuserid = 'encoder.user_id';
        } elseif ($data['account'] == 'endorsement') {
            $database = 'endorsement_account';
            $selectname = 'endorsement_account.user_fullname as name';
            $selectimage = 'endorsement_account.image as image';
            $selectuserid = 'endorsement_account.user_id';
        } elseif ($data['account'] == 'haptech') {
            $database = 'haptech_account';
            $selectname = 'haptech_account.user_fullname as name';
            $selectimage = 'haptech_account.image as image';
            $selectuserid = 'haptech_account.user_id';
        } elseif ($data['account'] == 'hr') {
            $database = 'hr_account';
            $selectname = 'hr_account.user_fullname as name';
            $selectimage = 'hr_account.image as image';
            $selectuserid = 'hr_account.user_id';
        } elseif ($data['account'] == 'imaging') {
            $database = 'imaging';
            $selectname = 'imaging.user_fullname as name';
            $selectimage = 'imaging.image as image';
            $selectuserid = 'imaging.user_id';
        } elseif ($data['account'] == 'laboratory') {
            $database = 'laboratory_list';
            $selectname = 'laboratory_list.user_fullname as name';
            $selectimage = 'laboratory_list.image as image';
            $selectuserid = 'laboratory_list.user_id';
        } elseif ($data['account'] == 'om') {
            $database = 'operation_manager_account';
            $selectname = 'operation_manager_account.user_fullname as name';
            $selectimage = 'operation_manager_account.image as image';
            $selectuserid = 'operation_manager_account.user_id';
        } elseif ($data['account'] == 'nurse station') {
            $database = 'nurses';
            $selectname = 'nurses.user_fullname as name';
            $selectimage = 'nurses.image as image';
            $selectuserid = 'nurses.user_id';
        } elseif ($data['account'] == 'pharmacy') {
            $database = 'pharmacy';
            $selectname = 'pharmacy.user_fullname as name';
            $selectimage = 'pharmacy.image as image';
            $selectuserid = 'pharmacy.user_id';
        } elseif ($data['account'] == 'psychology') {
            $database = 'psychology_account';
            $selectname = 'psychology_account.user_fullname as name';
            $selectimage = 'psychology_account.image as image';
            $selectuserid = 'psychology_account.user_id';
        } elseif ($data['account'] == 'radiologist') {
            $database = 'radiologist';
            $selectname = 'radiologist.name';
            $selectimage = 'radiologist.image as image';
            $selectuserid = 'radiologist.user_id';
        } elseif ($data['account'] == 'receiving') {
            $database = 'receiving_account';
            $selectname = 'receiving_account.user_fullname as name';
            $selectimage = 'receiving_account.image as image';
            $selectuserid = 'receiving_account.user_id';
        } elseif ($data['account'] == 'registration') {
            $database = 'admission_account';
            $selectname = 'admission_account.user_fullname as name';
            $selectimage = 'admission_account.image as image';
            $selectuserid = 'admission_account.user_id';
        } elseif ($data['account'] == 'stockroom') {
            $database = 'stockroom_acccount';
            $selectname = 'stockroom_acccount.user_fullname as name';
            $selectimage = 'stockroom_acccount.image as image';
            $selectuserid = 'stockroom_acccount.user_id';
        } elseif ($data['account'] == 'triage') {
            $database = 'triage_account';
            $selectname = 'triage_account.user_fullname as name';
            $selectimage = 'triage_account.image as image';
            $selectuserid = 'triage_account.user_id';
        } elseif ($data['account'] == 'warehouse') {
            $database = 'warehouse_accounts';
            $selectname = 'warehouse_accounts.user_fullname as name';
            $selectimage = 'warehouse_accounts.image as image';
            $selectuserid = 'warehouse_accounts.user_id';
        } elseif ($data['account'] == 'others') {
            $database = 'other_account';
            $selectname = 'other_account.user_fullname as name';
            $selectimage = 'other_account.image as image';
            $selectuserid = 'other_account.user_id';
        }

        if ($management_id == 'All') {
            return DB::table($database)
                ->join('users', 'users.user_id', '=', $selectuserid)
                ->join('hospital_employee_details', 'hospital_employee_details.user_id', '=', $selectuserid)
                ->select($selectname, $selectimage, 'hospital_employee_details.position as position', 'users.user_id as user_id', 'users.main_mgmt_id as main_mgmt_id')
                ->where('users.main_mgmt_id', $main_management_id)
                ->get();
        } else {
            return DB::table($database)
                ->join('users', 'users.user_id', '=', $selectuserid)
                ->join('hospital_employee_details', 'hospital_employee_details.user_id', '=', $selectuserid)
                ->select($selectname, $selectimage, 'hospital_employee_details.position as position', 'users.user_id as user_id', 'users.main_mgmt_id as main_mgmt_id')
                ->where('users.manage_by', $management_id)
                ->get();
        }
    }

    public static function himsGetAllAccountActive($data)
    {
        $management_id = $data['branch'];
        $main_management_id = $data['main_management_id'];

        if ($management_id == 'All') {
            $query = "SELECT *,

                (SELECT count(accounting_account.id) FROM accounting_account WHERE accounting_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountAccounting,
                (SELECT count(hospital_billing_account.id) FROM hospital_billing_account WHERE hospital_billing_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountBilling,
                (SELECT count(cashier.id) FROM cashier WHERE cashier.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountCashier,
                (SELECT count(doctors.id) FROM doctors WHERE doctors.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountDoctor,

                (SELECT count(encoder.id) FROM encoder WHERE encoder.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountEncoder,

                (SELECT count(haptech_account.id) FROM haptech_account WHERE haptech_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountHaptech,
                (SELECT count(hr_account.id) FROM hr_account WHERE hr_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountHr,
                (SELECT count(imaging.id) FROM imaging WHERE imaging.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountImaging,
                (SELECT count(laboratory_list.id) FROM laboratory_list WHERE laboratory_list.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountLaboratory,

                (SELECT count(operation_manager_account.id) FROM operation_manager_account WHERE operation_manager_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountOM,

                (SELECT count(pharmacy.id) FROM pharmacy WHERE pharmacy.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountPharmacy,
                (SELECT count(psychology_account.id) FROM psychology_account WHERE psychology_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountPsychology,
                (SELECT count(radiologist.id) FROM radiologist WHERE radiologist.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountRadiologist,
                (SELECT count(admission_account.id) FROM admission_account WHERE admission_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountRegistration,
                (SELECT count(stockroom_acccount.id) FROM stockroom_acccount WHERE stockroom_acccount.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountStockroom,
                (SELECT count(triage_account.id) FROM triage_account WHERE triage_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountTriage,
                (SELECT count(warehouse_accounts.id) FROM warehouse_accounts WHERE warehouse_accounts.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountWarehouse,

                (SELECT count(other_account.id) FROM other_account WHERE other_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountOthers

            FROM hospital_dtr_logs WHERE hospital_dtr_logs.main_mgmt_id = '$main_management_id' AND hospital_dtr_logs.timeout IS NULL ORDER BY hospital_dtr_logs.timein DESC";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        } else {
            $query = "SELECT *,

                (SELECT count(accounting_account.id) FROM accounting_account WHERE accounting_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountAccounting,
                (SELECT count(hospital_billing_account.id) FROM hospital_billing_account WHERE hospital_billing_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountBilling,
                (SELECT count(cashier.id) FROM cashier WHERE cashier.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountCashier,
                (SELECT count(doctors.id) FROM doctors WHERE doctors.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountDoctor,

                (SELECT count(encoder.id) FROM encoder WHERE encoder.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountEncoder,

                (SELECT count(haptech_account.id) FROM haptech_account WHERE haptech_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountHaptech,
                (SELECT count(hr_account.id) FROM hr_account WHERE hr_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountHr,
                (SELECT count(imaging.id) FROM imaging WHERE imaging.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountImaging,
                (SELECT count(laboratory_list.id) FROM laboratory_list WHERE laboratory_list.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountLaboratory,

                (SELECT count(operation_manager_account.id) FROM operation_manager_account WHERE operation_manager_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountOM,

                (SELECT count(pharmacy.id) FROM pharmacy WHERE pharmacy.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountPharmacy,
                (SELECT count(psychology_account.id) FROM psychology_account WHERE psychology_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountPsychology,
                (SELECT count(radiologist.id) FROM radiologist WHERE radiologist.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountRadiologist,
                (SELECT count(admission_account.id) FROM admission_account WHERE admission_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountRegistration,
                (SELECT count(stockroom_acccount.id) FROM stockroom_acccount WHERE stockroom_acccount.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountStockroom,
                (SELECT count(triage_account.id) FROM triage_account WHERE triage_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountTriage,
                (SELECT count(warehouse_accounts.id) FROM warehouse_accounts WHERE warehouse_accounts.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountWarehouse,

                (SELECT count(other_account.id) FROM other_account WHERE other_account.user_id = hospital_dtr_logs.user_id AND hospital_dtr_logs.timeout IS NULL) as activeCountOthers

            FROM hospital_dtr_logs WHERE hospital_dtr_logs.management_id = '$management_id' AND hospital_dtr_logs.timeout IS NULL ORDER BY hospital_dtr_logs.timein DESC";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    public static function hismGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM management WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('management')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function himsUpdatePersonalInfo($data)
    {
        return DB::table('management')
            ->where('user_id', $data['user_id'])
            ->update([
                'name' => $data['fullname'],
                'address' => $data['address'],
                'tin' => $data['tin'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function himsUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function himsUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function himsGetAllUsersAccount($data)
    {
        date_default_timezone_set('Asia/Manila');
        $management_id = $data["branch"];
        $main_management_id = $data["main_management_id"];

        if ($management_id == "All") {
            $query = "SELECT *, user_id as userID,

                (SELECT rate_classification FROM hospital_employee_details WHERE user_id = userID limit 1) as employee_class,
                (SELECT monthly_salary FROM hospital_employee_details WHERE user_id = userID limit 1) as monthly_salary,
                (SELECT daily_salary FROM hospital_employee_details WHERE user_id = userID limit 1) as daily_salary,
                (SELECT name FROM management WHERE management.management_id = users.manage_by limit 1) as branch_type,

                (SELECT user_fullname FROM accounting_account where user_id = userID limit 1) as _name_accounting,
                (SELECT user_fullname FROM hospital_billing_account where user_id = userID limit 1) as _name_billing,
                (SELECT user_fullname FROM cashier where user_id = userID limit 1) as _name_cashier,
                (SELECT name FROM doctors where user_id = userID limit 1) as _name_doctors,
                (SELECT user_fullname FROM encoder where user_id = userID limit 1) as _name_encoder,
                (SELECT user_fullname FROM endorsement_account where user_id = userID limit 1) as _name_endorsement,
                (SELECT user_fullname FROM haptech_account where user_id = userID limit 1) as _name_haptech,
                (SELECT user_fullname FROM hr_account where user_id = userID limit 1) as _name_hr,
                (SELECT user_fullname FROM imaging where user_id = userID limit 1) as _name_imaging,
                (SELECT user_fullname FROM laboratory_list where user_id = userID limit 1) as _name_laboratory,
                (SELECT user_fullname FROM operation_manager_account where user_id = userID limit 1) as _name_om,
                (SELECT user_fullname FROM nurses where user_id = userID limit 1) as _name_ns,
                (SELECT user_fullname FROM pharmacy where user_id = userID limit 1) as _name_pharmacy,
                (SELECT user_fullname FROM psychology_account where user_id = userID limit 1) as _name_psychology,
                (SELECT name FROM radiologist where user_id = userID limit 1) as _name_radiologist,
                (SELECT user_fullname FROM receiving_account where user_id = userID limit 1) as _name_receiving,
                (SELECT user_fullname FROM admission_account where user_id = userID limit 1) as _name_registration,
                (SELECT user_fullname FROM stockroom_acccount where user_id = userID limit 1) as _name_stockroom,
                (SELECT user_fullname FROM triage_account where user_id = userID limit 1) as _name_triage,
                (SELECT user_fullname FROM warehouse_accounts where user_id = userID limit 1) as _name_warehouse,
                (SELECT user_fullname FROM other_account where user_id = userID limit 1) as _name_other,

                (SELECT
                CASE
                    WHEN _name_accounting IS NOT NULL THEN _name_accounting
                    WHEN _name_billing IS NOT NULL THEN _name_billing
                    WHEN _name_cashier IS NOT NULL THEN _name_cashier
                    WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                    WHEN _name_encoder IS NOT NULL THEN _name_encoder
                    WHEN _name_endorsement IS NOT NULL THEN _name_endorsement
                    WHEN _name_haptech IS NOT NULL THEN _name_haptech
                    WHEN _name_hr IS NOT NULL THEN _name_hr
                    WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                    WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                    WHEN _name_om IS NOT NULL THEN _name_om
                    WHEN _name_ns IS NOT NULL THEN _name_ns
                    WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                    WHEN _name_psychology IS NOT NULL THEN _name_psychology
                    WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                    WHEN _name_receiving  IS NOT NULL THEN _name_receiving
                    WHEN _name_registration IS NOT NULL THEN _name_registration
                    WHEN _name_stockroom IS NOT NULL THEN _name_stockroom
                    WHEN _name_triage IS NOT NULL THEN _name_triage
                    WHEN _name_warehouse IS NOT NULL THEN _name_warehouse
                    WHEN _name_other IS NOT NUll THEN _name_other
                    ELSE username
                END
                ) as _account_name


            FROM users WHERE main_mgmt_id = '$main_management_id' AND type <> 'HMIS' ";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        } else {
            $query = "SELECT *, user_id as userID,

                (SELECT rate_classification FROM hospital_employee_details WHERE user_id = userID limit 1) as employee_class,
                (SELECT monthly_salary FROM hospital_employee_details WHERE user_id = userID limit 1) as monthly_salary,
                (SELECT daily_salary FROM hospital_employee_details WHERE user_id = userID limit 1) as daily_salary,
                (SELECT name FROM management WHERE management.management_id = users.manage_by limit 1) as branch_type,

                (SELECT user_fullname FROM accounting_account where user_id = userID limit 1) as _name_accounting,
                (SELECT user_fullname FROM hospital_billing_account where user_id = userID limit 1) as _name_billing,
                (SELECT user_fullname FROM cashier where user_id = userID limit 1) as _name_cashier,
                (SELECT name FROM doctors where user_id = userID limit 1) as _name_doctors,
                (SELECT user_fullname FROM encoder where user_id = userID limit 1) as _name_encoder,
                (SELECT user_fullname FROM endorsement_account where user_id = userID limit 1) as _name_endorsement,
                (SELECT user_fullname FROM haptech_account where user_id = userID limit 1) as _name_haptech,
                (SELECT user_fullname FROM hr_account where user_id = userID limit 1) as _name_hr,
                (SELECT user_fullname FROM imaging where user_id = userID limit 1) as _name_imaging,
                (SELECT user_fullname FROM laboratory_list where user_id = userID limit 1) as _name_laboratory,
                (SELECT user_fullname FROM operation_manager_account where user_id = userID limit 1) as _name_om,
                (SELECT user_fullname FROM nurses where user_id = userID limit 1) as _name_ns,
                (SELECT user_fullname FROM pharmacy where user_id = userID limit 1) as _name_pharmacy,
                (SELECT user_fullname FROM psychology_account where user_id = userID limit 1) as _name_psychology,
                (SELECT name FROM radiologist where user_id = userID limit 1) as _name_radiologist,
                (SELECT user_fullname FROM receiving_account where user_id = userID limit 1) as _name_receiving,
                (SELECT user_fullname FROM admission_account where user_id = userID limit 1) as _name_registration,
                (SELECT user_fullname FROM stockroom_acccount where user_id = userID limit 1) as _name_stockroom,
                (SELECT user_fullname FROM triage_account where user_id = userID limit 1) as _name_triage,
                (SELECT user_fullname FROM warehouse_accounts where user_id = userID limit 1) as _name_warehouse,
                (SELECT
                CASE
                    WHEN _name_accounting IS NOT NULL THEN _name_accounting
                    WHEN _name_billing IS NOT NULL THEN _name_billing
                    WHEN _name_cashier IS NOT NULL THEN _name_cashier
                    WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                    WHEN _name_encoder IS NOT NULL THEN _name_encoder
                    WHEN _name_endorsement IS NOT NULL THEN _name_endorsement
                    WHEN _name_haptech IS NOT NULL THEN _name_haptech
                    WHEN _name_hr IS NOT NULL THEN _name_hr
                    WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                    WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                    WHEN _name_om IS NOT NULL THEN _name_om
                    WHEN _name_ns IS NOT NULL THEN _name_ns
                    WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                    WHEN _name_psychology IS NOT NULL THEN _name_psychology
                    WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                    WHEN _name_receiving  IS NOT NULL THEN _name_receiving
                    WHEN _name_registration IS NOT NULL THEN _name_registration
                    WHEN _name_stockroom IS NOT NULL THEN _name_stockroom
                    WHEN _name_triage IS NOT NULL THEN _name_triage
                    WHEN _name_warehouse IS NOT NULL THEN _name_warehouse
                    ELSE username
                END
                ) as _account_name

            FROM users WHERE manage_by = '$management_id' AND type <> 'HMIS' ";
            $result = DB::getPdo()->prepare($query);
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    public static function himsAddNewDepartmentAccount($data)
    {
        date_default_timezone_set('Asia/Manila');
        $type = '';
        $user_id = 'u-' . time();
        $accounting_id = 'aa-' . time();
        $billing_id = 'ba-' . time();
        $cashier_id = 'c-' . time();
        $doctors_id = 'd-' . time();
        $encoder_id = 'e-' . time();
        $endorsement_id = 'ea-' . time();
        $hr_id = 'hr-' . time();
        $hap_id = 'hap-' . time();
        $imaging_id = 'i-' . time();
        $lab_id = 'l_id-' . time();
        $om_id = 'om-' . time();
        $nurse_id = 'na-' . time();
        $psycho_id = 'psych-' . time();

        $phmcy_id = 'phmcy-' . time(); //off

        $rad_id = 'r-' . time();
        $rcv_id = 'rcv-' . time();
        $admission_id = 'ad-' . time();
        $stockroom_id = 'sa-' . time();
        $triage_id = 'ta-' . time();
        $warehouse_id = 'wa-' . time();
        $other_id = 'oi-' . time();

        $admitting_id = 'admitting-' . time();

        if ($data['department'] == 'admitting') {
            $type = 'HIS-Admitting';
            $query = DB::table('hospital_admitting_accounts')->where('management_id', $data['branch'])->get();

            DB::table('hospital_admitting_accounts')
                ->insert([
                    'haa_id' => $admitting_id,
                    'admitting_id' => count($query) > 0 ? $query[0]->admitting_id : $admitting_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS accounting',
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS accounting adresss',
                    'email' => $data['email'],
                    'role' => 'User',
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else if ($data['department'] == 'accounting') {
            $type = 'HIS-Accounting';
            $query = DB::table('accounting_account')->where('management_id', $data['branch'])->get();

            DB::table('accounting_account')
                ->insert([
                    'a_id' => $accounting_id,
                    'accounting_id' => count($query) > 0 ? $query[0]->accounting_id : $accounting_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS accounting',
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS accounting adresss',
                    'email' => $data['email'],
                    'role' => 'User',
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'billing') {
            $type = 'HIS-Billing';
            $query = DB::table('hospital_billing_account')->where('management_id', $data['branch'])->get();

            DB::table('hospital_billing_account')
                ->insert([
                    'bu_id' => $billing_id,
                    'buser_id' => count($query) > 0 ? $query[0]->buser_id : $billing_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS billing',
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS billing adresss',
                    'gender' => $data['gender'],
                    'role' => 'User',
                    'email' => $data['email'],
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'cashier') {
            $type = 'HIS-Cashier';
            $query = DB::table('cashier')->where('management_id', $data['branch'])->get();

            DB::table('cashier')
                ->insert([
                    'c_id' => $cashier_id,
                    'cashier_id' => count($query) > 0 ? $query[0]->cashier_id : $cashier_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS cashier',
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS cashier address',
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'doctor') {
            $type = 'HIS-Doctor';

            DB::table('doctors')
                ->insert([
                    'd_id' => $doctors_id,
                    'doctors_id' => 'doctor-' . rand(0, 99) . time(),
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'name' => $data['fullname'],
                    'address' => $data['address'],
                    'gender' => $data['gender'],
                    'contact_no' => $data['contact'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'status' => 1,
                    'role' => 'User',
                    'added_by' => $data['user_id'],
                    'online_appointment' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'encoder') {
            $type = 'HIS-Documentation';
            $query = DB::table('encoder')->where('management_id', $data['branch'])->get();

            DB::table('encoder')
                ->insert([
                    'e_id' => $encoder_id,
                    'encoder_id' => count($query) > 0 ? $query[0]->encoder_id : $encoder_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS documentation',
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS documentation address',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'endorsement') {
            $type = 'HIS-Endorsement';
            $query = DB::table('endorsement_account')->where('management_id', $data['branch'])->get();

            DB::table('endorsement_account')
                ->insert([
                    'ea_id' => $endorsement_id,
                    'endorsement_id' => count($query) > 0 ? $query[0]->endorsement_id : $endorsement_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS endorsement',
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS endorsement address',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'haptech') {
            $type = 'HIS-Haptech';
            $query = DB::table('haptech_account')->where('management_id', $data['branch'])->get();

            DB::table('haptech_account')
                ->insert([
                    'ha_id' => $hap_id,
                    'hap_id' => count($query) > 0 ? $query[0]->hap_id : $hap_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS haptech',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS haptech address',
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'hr') {
            $type = 'HIS-Hr';
            $query = DB::table('hr_account')->where('management_id', $data['branch'])->get();

            DB::table('hr_account')
                ->insert([
                    'h_id' => $hr_id,
                    'hr_id' => count($query) > 0 ? $query[0]->hr_id : $hr_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS hr',
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS hr address',
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'imaging') {
            $type = 'HIS-Imaging';
            $query = DB::table('imaging')->where('management_id', $data['branch'])->get();

            DB::table('imaging')
                ->insert([
                    'i_id' => $imaging_id,
                    'imaging_id' => count($query) > 0 ? $query[0]->imaging_id : $imaging_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS Imaging',
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS Imaging Address',
                    'allow_virtual' => 1,
                    'role' => 'User',
                    'email' => $data['email'],
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'laboratory') {
            $type = 'HIS-Laboratory';
            $query = DB::table('laboratory_list')->where('management_id', $data['branch'])->get();

            DB::table('laboratory_list')
                ->insert([
                    'l_id' => $lab_id,
                    'laboratory_id' => count($query) > 0 ? $query[0]->laboratory_id : $lab_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS laboratory',
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS laboratory address',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'role' => 'User',
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'om') {
            $type = 'HIS-OM';
            $query = DB::table('operation_manager_account')->where('management_id', $data['branch'])->get();

            DB::table('operation_manager_account')
                ->insert([
                    'm_id' => $om_id,
                    'om_id' => count($query) > 0 ? $query[0]->om_id : $om_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS operation manager',
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS operation manager address',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'nurse') {
            $type = 'HIS-Nurse';
            $query = DB::table('nurses')->where('management_id', $data['branch'])->get();

            DB::table('nurses')
                ->insert([
                    'n_id' => $nurse_id,
                    'nurse_id' => count($query) > 0 ? $query[0]->nurse_id : $nurse_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS nurse',
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS nurse address',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'pharmacy') {
            $type = 'HIS-Pharmacy';
            $query = DB::table('pharmacy')->where('management_id', $data['branch'])->get();

            DB::table('pharmacy')
                ->insert([
                    'phmcy_id' => $phmcy_id,
                    'pharmacy_id' => count($query) > 0 ? $query[0]->pharmacy_id : $phmcy_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS pharmacy',
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS pharmacy address',
                    'email' => $data['email'],
                    'status' => 1,
                    'role' => 'admin',
                    'added_by' => $data['user_id'],
                    'pharmacy_type' => 'clinic',
                    'pharmacy_category' => 'local',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'psychology') {
            $type = 'HIS-Psychology';
            $query = DB::table('psychology_account')->where('management_id', $data['branch'])->get();

            DB::table('psychology_account')
                ->insert([
                    'psy_id' => $psycho_id,
                    'psycho_id' => count($query) > 0 ? $query[0]->psycho_id : $psycho_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS psychology',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS psychology address',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'radiologist') {
            $type = 'HIS-Radiologist';

            DB::table('radiologist')
                ->insert([
                    'r_id' => $rad_id,
                    'radiologist_id' => 'radiologist-' . rand(0, 99) . time(),
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'name' => $data['fullname'],
                    'gender' => $data['gender'],
                    'address' => $data['address'],
                    'role' => 'User',
                    'email' => $data['email'],
                    'added_by' => $data['user_id'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'receiving') {
            $type = 'HIS-Receiving';
            $query = DB::table('receiving_account')->where('management_id', $data['branch'])->get();

            DB::table('receiving_account')
                ->insert([
                    'ra_id' => $rcv_id,
                    'rcv_id' => count($query) > 0 ? $query[0]->rcv_id : $rcv_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS receiving',
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS receiving address',
                    'gender' => $data['gender'],
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'registration') {
            $type = 'HIS-Registration';
            $query = DB::table('admission_account')->where('management_id', $data['branch'])->get();

            DB::table('admission_account')
                ->insert([
                    'ac_id' => $admission_id,
                    'admission_id' => count($query) > 0 ? $query[0]->admission_id : $admission_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS Registration',
                    'gender' => $data['gender'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS Registration adresss',
                    'role' => 'User',
                    'added_by' => $data['user_id'],
                    'email' => $data['email'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'stockroom') {
            $type = 'HIS-Stockroom';
            $query = DB::table('stockroom_acccount')->where('management_id', $data['branch'])->get();

            DB::table('stockroom_acccount')
                ->insert([
                    'sr_id' => $stockroom_id,
                    'stockroom_id' => count($query) > 0 ? $query[0]->stockroom_id : $stockroom_id,
                    'management_id' => $data['branch'],
                    'user_id' => $user_id,
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS stock room',
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS stock room address',
                    'email' => $data['email'],
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'triage') {
            $type = 'HIS-Triage';
            $query = DB::table('triage_account')->where('management_id', $data['branch'])->get();

            DB::table('triage_account')
                ->insert([
                    'ta_id' => $triage_id,
                    'triage_id' => count($query) > 0 ? $query[0]->triage_id : $triage_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS triage',
                    'gender' => $data['gender'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS triage address',
                    'email' => $data['email'],
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'warehouse') {
            $type = 'HIS-Warehouse';
            $query = DB::table('warehouse_accounts')->where('management_id', $data['branch'])->get();

            DB::table('warehouse_accounts')
                ->insert([
                    'wa_id' => $warehouse_id,
                    'warehouse_id' => count($query) > 0 ? $query[0]->warehouse_id : $warehouse_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS warehouse',
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS warehouse address',
                    'email' => $data['email'],
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } elseif ($data['department'] == 'others') {
            $type = 'HIS-Others';
            $query = DB::table('other_account')->where('management_id', $data['branch'])->get();

            DB::table('other_account')
                ->insert([
                    'o_id' => $other_id,
                    'oa_id' => count($query) > 0 ? $query[0]->oa_id : $other_id,
                    'user_id' => $user_id,
                    'management_id' => $data['branch'],
                    'user_fullname' => $data['fullname'],
                    'user_address' => $data['address'],
                    'name' => count($query) > 0 ? $query[0]->name : 'HIS other',
                    'gender' => $data['gender'],
                    'address' => count($query) > 0 ? $query[0]->address : 'HIS other address',
                    'email' => $data['email'],
                    'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                    'role' => 'User',
                    'status' => 1,
                    'added_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        if ($data['department'] != 'doctor' || $data['department'] != 'radiologist') {
            DB::table('hospital_employee_details')->insert([
                'hed_id' => 'hed-' . rand(0, 99) . time(),
                'user_id' => $user_id,
                'management_id' => $data['branch'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'date_started' => date('Y-m-d H:i:s', strtotime($data['date_started'])),
                'date_birth' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                'employee_status' => $data['status'],
                'civil_status' => $data['civil_status'],
                'contact' => $data['contact'],
                'position' => $data['position'],
                'shared' => $data['shared'],
                'sick_leave' => $data['sick_leave'],
                'sick_leave_orig' => $data['sick_leave'],
                'vacation_leave' => $data['vacation_leave'],
                'vacation_leave_orig' => $data['vacation_leave'],
                'hazard_start_15' => $data['hazard_start_15'],
                'hazard_16_end' => $data['hazard_16_end'],
                'rate_classification' => $data['rate_classification'],
                'monthly_salary' => $data['monthly_salary'],
                'daily_salary' => $data['daily_salary'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::table('users')
            ->insert([
                'user_id' => $user_id,
                'username' => $data['user_username'],
                'password' => Hash::make($data['user_pass']),
                'status' => 1,
                'type' => $type,
                'email' => $data['email'],
                'is_verify' => 1,
                'is_confirm' => 1,
                'manage_by' => $data['branch'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'remember_token' => Hash::make($data['user_pass']),
                'api_token' => Hash::make($data['user_pass']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function himsUpdateAccountToInactive($data)
    {
        date_default_timezone_set('Asia/Manila');
        if ($data['disable_type'] == 'Lateral') {
            $database = '';
            $getuser = DB::table('users')->select('type')->where('user_id', $data['users_id'])->first();
            if ($getuser->type == 'HIS-Accounting') {
                $database = 'accounting_account';
            }if ($getuser->type == 'HIS-Billing') {
                $database = 'hospital_billing_account';
            }if ($getuser->type == 'HIS-Cashier') {
                $database = 'cashier';
            }if ($getuser->type == 'HIS-Doctor') {
                $database = 'doctors';
            }if ($getuser->type == 'HIS-Haptech') {
                $database = 'haptech_account';
            }if ($getuser->type == 'HIS-Hr') {
                $database = 'hr_account';
            }if ($getuser->type == 'HIS-Imaging') {
                $database = 'imaging';
            }if ($getuser->type == 'HIS-Laboratory') {
                $database = 'laboratory_list';
            }if ($getuser->type == 'HIS-Pharmacy') {
                $database = 'pharmacy';
            }if ($getuser->type == 'HIS-Psychology') {
                $database = 'psychology_account';
            }if ($getuser->type == 'HIS-Radiologist') {
                $database = 'radiologist';
            }if ($getuser->type == 'HIS-Registration') {
                $database = 'admission_account';
            }if ($getuser->type == 'HIS-Stockroom') {
                $database = 'stockroom_acccount';
            }if ($getuser->type == 'HIS-Triage') {
                $database = 'triage_account';
            }if ($getuser->type == 'HIS-Warehouse') {
                $database = 'warehouse_accounts';
            }

            DB::table($database)
                ->where('user_id', $data['users_id'])
                ->update([
                    'management_id' => $data['branch'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            DB::table('clinic_resignation_type')->insert([
                'crt_id' => 'crt-' . rand(0, 99) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'old_management' => $data['user_old_management'],
                'user_id' => $data['users_id'],
                'resignation_type' => $data['disable_type'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            DB::table('hospital_employee_details')
                ->where('user_id', $data['users_id'])
                ->update([
                    'management_id' => $data['branch'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            return DB::table('users')
                ->where('user_id', $data['users_id'])
                ->update([
                    'manage_by' => $data['branch'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            DB::table('clinic_resignation_type')->insert([
                'crt_id' => 'crt-' . rand(0, 99) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'user_id' => $data['users_id'],
                'resignation_type' => $data['disable_type'],
                'resign_effect' => !empty($data['date_effect']) ? date('Y-m-d H:i:s', strtotime($data['date_effect'])) : null,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return DB::table('users')->where('user_id', $data['users_id'])->update([
                'status' => $data['status'] == 1 ? 0 : 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function himsGetIncomeReportByYear($data)
    {
        $management_id = $data['management_id'];
        $year = $data['year'];

        $query = "SELECT m_id AS managementId,
            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '01' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '01' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '01' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '01' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '01' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '01' AND bill_from = 'psychology') as jan_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '02' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '02' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '02' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '02' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '02' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '02' AND bill_from = 'psychology') as feb_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '03' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '03' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '03' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '03' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '03' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '03' AND bill_from = 'psychology') as mar_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '04' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '04' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '04' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '04' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '04' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '04' AND bill_from = 'psychology') as apr_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '05' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '05' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '05' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '05' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '05' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '05' AND bill_from = 'psychology') as may_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '06' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '06' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '06' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '06' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '06' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '06' AND bill_from = 'psychology') as jun_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '07' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '07' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '07' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '07' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '07' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '07' AND bill_from = 'psychology') as jul_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '08' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '08' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '08' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '08' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '08' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '08' AND bill_from = 'psychology') as aug_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '09' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '09' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '09' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '09' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '09' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '09' AND bill_from = 'psychology') as sep_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '10' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '10' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '10' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '10' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '10' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '10' AND bill_from = 'psychology') as oct_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '11' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '11' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '11' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '11' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '11' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '11' AND bill_from = 'psychology') as nov_income,

            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records
                WHERE management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '12' AND bill_from = 'laboratory'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '12' AND bill_from = 'imaging'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '12' AND bill_from = 'doctor'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '12' AND bill_from = 'packages'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '12' AND bill_from = 'Other Test'
                OR management_id = '$management_id' AND is_refund IS NULL AND YEAR(created_at) = '$year' AND MONTH(created_at) = '12' AND bill_from = 'psychology') as dec_income

        FROM management WHERE management.user_id = '" . $data['user_id'] . "'";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsGetAllEmployee($data)
    {
        $row = '';
        $value = '';

        if ($data['branch'] == 'All') {
            $row = 'main_mgmt_id';
            $value = $data['main_mgmt_id'];
        } else {
            $row = 'manage_by';
            $value = $data['branch'];
        }

        $query = "SELECT created_at, manage_by, type, user_id, username, user_id as lolId,
            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = users.user_id limit 1) as _name_accounting,
            (SELECT image FROM accounting_account where accounting_account.user_id = users.user_id limit 1) as _image_accounting,
            (SELECT email FROM accounting_account where accounting_account.user_id = users.user_id limit 1) as _email_accounting,
            (SELECT user_fullname FROM hospital_billing_account where hospital_billing_account.user_id = users.user_id limit 1) as _name_billing,
            (SELECT image FROM hospital_billing_account where hospital_billing_account.user_id = users.user_id limit 1) as _image_billing,
            (SELECT email FROM hospital_billing_account where hospital_billing_account.user_id = users.user_id limit 1) as _email_billing,
            (SELECT user_fullname FROM cashier where cashier.user_id = users.user_id limit 1) as _name_cashier,
            (SELECT image FROM cashier where cashier.user_id = users.user_id limit 1) as _image_cashier,
            (SELECT email FROM cashier where cashier.user_id = users.user_id limit 1) as _email_cashier,
            (SELECT name FROM doctors where doctors.user_id = users.user_id limit 1) as _name_doctors,
            (SELECT image FROM doctors where doctors.user_id = users.user_id limit 1) as _image_doctors,
            (SELECT user_fullname FROM encoder where encoder.user_id = users.user_id limit 1) as _name_encoder,
            (SELECT image FROM encoder where encoder.user_id = users.user_id limit 1) as _image_encoder,
            (SELECT email FROM encoder where encoder.user_id = users.user_id limit 1) as _email_encoder,
            (SELECT user_fullname FROM endorsement_account where endorsement_account.user_id = users.user_id limit 1) as _name_endorsement,
            (SELECT image FROM endorsement_account where endorsement_account.user_id = users.user_id limit 1) as _image_endorsement,
            (SELECT email FROM endorsement_account where endorsement_account.user_id = users.user_id limit 1) as _email_endorsement,
            (SELECT user_fullname FROM haptech_account where haptech_account.user_id = users.user_id limit 1) as _name_haptech,
            (SELECT image FROM haptech_account where haptech_account.user_id = users.user_id limit 1) as _image_haptech,
            (SELECT email FROM haptech_account where haptech_account.user_id = users.user_id limit 1) as _email_haptech,
            (SELECT user_fullname FROM hr_account where hr_account.user_id = users.user_id limit 1) as _name_hr,
            (SELECT image FROM hr_account where hr_account.user_id = users.user_id limit 1) as _image_hr,
            (SELECT email FROM hr_account where hr_account.user_id = users.user_id limit 1) as _email_hr,
            (SELECT user_fullname FROM imaging where imaging.user_id = users.user_id limit 1) as _name_imaging,
            (SELECT image FROM imaging where imaging.user_id = users.user_id limit 1) as _image_imaging,
            (SELECT email FROM imaging where imaging.user_id = users.user_id limit 1) as _email_imaging,
            (SELECT user_fullname FROM laboratory_list where laboratory_list.user_id = users.user_id limit 1) as _name_laboratory,
            (SELECT image FROM laboratory_list where laboratory_list.user_id = users.user_id limit 1) as _image_laboratory,
            (SELECT email FROM laboratory_list where laboratory_list.user_id = users.user_id limit 1) as _email_laboratory,
            (SELECT user_fullname FROM operation_manager_account where operation_manager_account.user_id = users.user_id limit 1) as _name_om,
            (SELECT image FROM operation_manager_account where operation_manager_account.user_id = users.user_id limit 1) as _image_om,
            (SELECT email FROM operation_manager_account where operation_manager_account.user_id = users.user_id limit 1) as _email_om,
            (SELECT user_fullname FROM nurses where nurses.user_id = users.user_id limit 1) as _name_ns,
            (SELECT image FROM nurses where nurses.user_id = users.user_id limit 1) as _image_ns,
            (SELECT email FROM nurses where nurses.user_id = users.user_id limit 1) as _email_ns,
            (SELECT user_fullname FROM pharmacy where pharmacy.user_id = users.user_id limit 1) as _name_pharmacy,
            (SELECT image FROM pharmacy where pharmacy.user_id = users.user_id limit 1) as _image_pharmacy,
            (SELECT email FROM pharmacy where pharmacy.user_id = users.user_id limit 1) as _email_pharmacy,
            (SELECT user_fullname FROM psychology_account where psychology_account.user_id = users.user_id limit 1) as _name_psychology,
            (SELECT image FROM psychology_account where psychology_account.user_id = users.user_id limit 1) as _image_psychology,
            (SELECT email FROM psychology_account where psychology_account.user_id = users.user_id limit 1) as _email_psychology,
            (SELECT name FROM radiologist where radiologist.user_id = users.user_id limit 1) as _name_radiologist,
            (SELECT image FROM radiologist where radiologist.user_id = users.user_id limit 1) as _image_radiologist,
            (SELECT email FROM radiologist where radiologist.user_id = users.user_id limit 1) as _email_radiologist,
            (SELECT user_fullname FROM receiving_account where receiving_account.user_id = users.user_id limit 1) as _name_receiving,
            (SELECT image FROM receiving_account where receiving_account.user_id = users.user_id limit 1) as _image_receiving,
            (SELECT email FROM receiving_account where receiving_account.user_id = users.user_id limit 1) as _email_receiving,
            (SELECT user_fullname FROM admission_account where admission_account.user_id = users.user_id limit 1) as _name_registration,
            (SELECT image FROM admission_account where admission_account.user_id = users.user_id limit 1) as _image_registration,
            (SELECT email FROM admission_account where admission_account.user_id = users.user_id limit 1) as _email_registration,
            (SELECT user_fullname FROM stockroom_acccount where stockroom_acccount.user_id = users.user_id limit 1) as _name_stockroom,
            (SELECT image FROM stockroom_acccount where stockroom_acccount.user_id = users.user_id limit 1) as _image_stockroom,
            (SELECT email FROM stockroom_acccount where stockroom_acccount.user_id = users.user_id limit 1) as _email_stockroom,
            (SELECT user_fullname FROM triage_account where triage_account.user_id = users.user_id limit 1) as _name_triage,
            (SELECT image FROM triage_account where triage_account.user_id = users.user_id limit 1) as _image_triage,
            (SELECT email FROM triage_account where triage_account.user_id = users.user_id limit 1) as _email_triage,
            (SELECT user_fullname FROM warehouse_accounts where warehouse_accounts.user_id = users.user_id limit 1) as _name_warehouse,
            (SELECT image FROM warehouse_accounts where warehouse_accounts.user_id = users.user_id limit 1) as _image_warehouse,
            (SELECT email FROM warehouse_accounts where warehouse_accounts.user_id = users.user_id limit 1) as _email_warehouse,
            (SELECT user_fullname FROM other_account where other_account.user_id = users.user_id limit 1) as _name_other,
            (SELECT image FROM other_account where other_account.user_id = users.user_id limit 1) as _image_other,
            (SELECT email FROM other_account where other_account.user_id = users.user_id limit 1) as _email_other,

            (SELECT
            CASE
                WHEN _name_accounting IS NOT NULL THEN _name_accounting
                WHEN _name_billing IS NOT NULL THEN _name_billing
                WHEN _name_cashier IS NOT NULL THEN _name_cashier
                WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                WHEN _name_encoder  IS NOT NULL THEN _name_encoder
                WHEN _name_endorsement IS NOT NULL THEN _name_endorsement
                WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                WHEN _name_hr  IS NOT NULL THEN _name_hr
                WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                WHEN _name_om  IS NOT NULL THEN _name_om
                WHEN _name_ns IS NOT NULL THEN _name_ns
                WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                WHEN _name_psychology IS NOT NULL THEN _name_psychology
                WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                WHEN _name_receiving  IS NOT NULL THEN _name_receiving
                WHEN _name_registration  IS NOT NULL THEN _name_registration
                WHEN _name_stockroom  IS NOT NULL THEN _name_stockroom
                WHEN _name_triage IS NOT NULL THEN _name_triage
                WHEN _name_warehouse  IS NOT NULL THEN _name_warehouse
                WHEN _name_other  IS NOT NULL THEN _name_other
                ELSE username
            END
            ) as _account_name,

            (SELECT CASE
                WHEN _email_accounting IS NOT NULL THEN _email_accounting
                WHEN _email_billing IS NOT NULL THEN _email_billing
                WHEN _email_cashier IS NOT NULL THEN _email_cashier
                WHEN _email_encoder  IS NOT NULL THEN _email_encoder
                WHEN _email_endorsement  IS NOT NULL THEN _email_endorsement
                WHEN _email_haptech  IS NOT NULL THEN _email_haptech
                WHEN _email_hr  IS NOT NULL THEN _email_hr
                WHEN _email_imaging  IS NOT NULL THEN _email_imaging
                WHEN _email_laboratory  IS NOT NULL THEN _email_laboratory
                WHEN _email_om  IS NOT NULL THEN _email_om
                WHEN _email_ns  IS NOT NULL THEN _email_ns
                WHEN _email_pharmacy  IS NOT NULL THEN _email_pharmacy
                WHEN _email_psychology  IS NOT NULL THEN _email_psychology
                WHEN _email_radiologist  IS NOT NULL THEN _email_radiologist
                WHEN _email_receiving  IS NOT NULL THEN _email_receiving
                WHEN _email_registration  IS NOT NULL THEN _email_registration
                WHEN _email_stockroom  IS NOT NULL THEN _email_stockroom
                WHEN _email_triage  IS NOT NULL THEN _email_triage
                WHEN _email_warehouse  IS NOT NULL THEN _email_warehouse
                WHEN _email_other  IS NOT NULL THEN _email_other
                ELSE user_id
            END) as _account_email

        FROM users WHERE $row = '$value' AND type <> 'HMIS' AND type <> 'HIS-Doctor' AND type <> 'HIS-Radiologist' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsGetAllEmployeeWithDate($data)
    {
        $management_id = $data['branch'];
        $main_mgmt_id = $data['main_mgmt_id'];

        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));

        $row = '';
        $value = '';

        if ($management_id == 'All') {
            $row = 'main_mgmt_id';
            $value = $main_mgmt_id;
        } else {
            $row = 'manage_by';
            $value = $management_id;
        }

        $query = "SELECT created_at, manage_by, type, user_id, username, user_id as lolId,

            (SELECT IFNULL(sum(amount), 0) FROM hospital_employee_payroll_add where hospital_employee_payroll_add.user_id = lolId AND hospital_employee_payroll_add.header_cat = 'Earning' AND hospital_employee_payroll_add.covered_period_start >= '$strDaily' AND hospital_employee_payroll_add.covered_period_end <= '$lstDaily' ) as _totalEarning,
            (SELECT IFNULL(sum(amount), 0) FROM hospital_employee_payroll_add where hospital_employee_payroll_add.user_id = lolId AND hospital_employee_payroll_add.header_cat = 'Deduction' AND hospital_employee_payroll_add.covered_period_start >= '$strDaily' AND hospital_employee_payroll_add.covered_period_end <= '$lstDaily' ) as _totalDeduction,

            (SELECT IFNULL(count(id), 0) FROM hospital_dtr_logs where hospital_dtr_logs.user_id = lolId AND hospital_dtr_logs.timein >= '$strDaily' AND hospital_dtr_logs.timein <= '$lstDaily' AND hospital_dtr_logs.timeout IS NOT NULL ) as _totalDays,

            (SELECT rate_classification FROM hospital_employee_details where hospital_employee_details.user_id = lolId ) as rateClassification,
            (SELECT monthly_salary FROM hospital_employee_details where hospital_employee_details.user_id = lolId ) as monthlySalary,
            (SELECT daily_salary FROM hospital_employee_details where hospital_employee_details.user_id = lolId ) as dailySalary,

            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = users.user_id limit 1) as _name_accounting,
            (SELECT email FROM accounting_account where accounting_account.user_id = users.user_id limit 1) as _email_accounting,
            (SELECT user_fullname FROM hospital_billing_account where hospital_billing_account.user_id = users.user_id limit 1) as _name_billing,
            (SELECT email FROM hospital_billing_account where hospital_billing_account.user_id = users.user_id limit 1) as _email_billing,
            (SELECT user_fullname FROM cashier where cashier.user_id = users.user_id limit 1) as _name_cashier,
            (SELECT email FROM cashier where cashier.user_id = users.user_id limit 1) as _email_cashier,
            (SELECT name FROM doctors where doctors.user_id = users.user_id limit 1) as _name_doctors,
            (SELECT user_fullname FROM encoder where encoder.user_id = users.user_id limit 1) as _name_encoder,
            (SELECT email FROM encoder where encoder.user_id = users.user_id limit 1) as _email_encoder,
            (SELECT user_fullname FROM endorsement_account where endorsement_account.user_id = users.user_id limit 1) as _name_endorsement,
            (SELECT email FROM endorsement_account where endorsement_account.user_id = users.user_id limit 1) as _email_endorsement,
            (SELECT user_fullname FROM haptech_account where haptech_account.user_id = users.user_id limit 1) as _name_haptech,
            (SELECT email FROM haptech_account where haptech_account.user_id = users.user_id limit 1) as _email_haptech,
            (SELECT user_fullname FROM hr_account where hr_account.user_id = users.user_id limit 1) as _name_hr,
            (SELECT email FROM hr_account where hr_account.user_id = users.user_id limit 1) as _email_hr,
            (SELECT user_fullname FROM imaging where imaging.user_id = users.user_id limit 1) as _name_imaging,
            (SELECT email FROM imaging where imaging.user_id = users.user_id limit 1) as _email_imaging,
            (SELECT user_fullname FROM laboratory_list where laboratory_list.user_id = users.user_id limit 1) as _name_laboratory,
            (SELECT email FROM laboratory_list where laboratory_list.user_id = users.user_id limit 1) as _email_laboratory,
            (SELECT user_fullname FROM operation_manager_account where operation_manager_account.user_id = users.user_id limit 1) as _name_om,
            (SELECT email FROM operation_manager_account where operation_manager_account.user_id = users.user_id limit 1) as _email_om,
            (SELECT user_fullname FROM nurses where nurses.user_id = users.user_id limit 1) as _name_ns,
            (SELECT email FROM nurses where nurses.user_id = users.user_id limit 1) as _email_ns,
            (SELECT user_fullname FROM pharmacy where pharmacy.user_id = users.user_id limit 1) as _name_pharmacy,
            (SELECT email FROM pharmacy where pharmacy.user_id = users.user_id limit 1) as _email_pharmacy,
            (SELECT user_fullname FROM psychology_account where psychology_account.user_id = users.user_id limit 1) as _name_psychology,
            (SELECT email FROM psychology_account where psychology_account.user_id = users.user_id limit 1) as _email_psychology,
            (SELECT name FROM radiologist where radiologist.user_id = users.user_id limit 1) as _name_radiologist,
            (SELECT email FROM radiologist where radiologist.user_id = users.user_id limit 1) as _email_radiologist,
            (SELECT user_fullname FROM receiving_account where receiving_account.user_id = users.user_id limit 1) as _name_receiving,
            (SELECT email FROM receiving_account where receiving_account.user_id = users.user_id limit 1) as _email_receiving,
            (SELECT user_fullname FROM admission_account where admission_account.user_id = users.user_id limit 1) as _name_registration,
            (SELECT email FROM admission_account where admission_account.user_id = users.user_id limit 1) as _email_registration,
            (SELECT user_fullname FROM stockroom_acccount where stockroom_acccount.user_id = users.user_id limit 1) as _name_stockroom,
            (SELECT email FROM stockroom_acccount where stockroom_acccount.user_id = users.user_id limit 1) as _email_stockroom,
            (SELECT user_fullname FROM triage_account where triage_account.user_id = users.user_id limit 1) as _name_triage,
            (SELECT email FROM triage_account where triage_account.user_id = users.user_id limit 1) as _email_triage,
            (SELECT user_fullname FROM warehouse_accounts where warehouse_accounts.user_id = users.user_id limit 1) as _name_warehouse,
            (SELECT email FROM warehouse_accounts where warehouse_accounts.user_id = users.user_id limit 1) as _email_warehouse,
            (SELECT user_fullname FROM other_account where other_account.user_id = users.user_id limit 1) as _name_other,
            (SELECT email FROM other_account where other_account.user_id = users.user_id limit 1) as _email_other,

            (SELECT
            CASE
                WHEN _name_accounting IS NOT NULL THEN _name_accounting
                WHEN _name_billing IS NOT NULL THEN _name_billing
                WHEN _name_cashier IS NOT NULL THEN _name_cashier
                WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                WHEN _name_encoder  IS NOT NULL THEN _name_encoder
                WHEN _name_endorsement IS NOT NULL THEN _name_endorsement
                WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                WHEN _name_hr  IS NOT NULL THEN _name_hr
                WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                WHEN _name_om  IS NOT NULL THEN _name_om
                WHEN _name_ns IS NOT NULL THEN _name_ns
                WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                WHEN _name_psychology IS NOT NULL THEN _name_psychology
                WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                WHEN _name_receiving  IS NOT NULL THEN _name_receiving
                WHEN _name_registration  IS NOT NULL THEN _name_registration
                WHEN _name_stockroom  IS NOT NULL THEN _name_stockroom
                WHEN _name_triage IS NOT NULL THEN _name_triage
                WHEN _name_warehouse  IS NOT NULL THEN _name_warehouse
                WHEN _name_other  IS NOT NULL THEN _name_other
                ELSE username
            END
            ) as _account_name,

            (SELECT CASE
                WHEN _email_accounting IS NOT NULL THEN _email_accounting
                WHEN _email_billing IS NOT NULL THEN _email_billing
                WHEN _email_cashier IS NOT NULL THEN _email_cashier
                WHEN _email_encoder  IS NOT NULL THEN _email_encoder
                WHEN _email_haptech  IS NOT NULL THEN _email_haptech
                WHEN _email_hr  IS NOT NULL THEN _email_hr
                WHEN _email_imaging  IS NOT NULL THEN _email_imaging
                WHEN _email_laboratory  IS NOT NULL THEN _email_laboratory
                WHEN _email_om  IS NOT NULL THEN _email_om
                WHEN _email_pharmacy  IS NOT NULL THEN _email_pharmacy
                WHEN _email_psychology  IS NOT NULL THEN _email_psychology
                WHEN _email_radiologist  IS NOT NULL THEN _email_radiologist
                WHEN _email_registration  IS NOT NULL THEN _email_registration
                WHEN _email_stockroom  IS NOT NULL THEN _email_stockroom
                WHEN _email_triage  IS NOT NULL THEN _email_triage
                WHEN _email_warehouse  IS NOT NULL THEN _email_warehouse
                WHEN _email_other  IS NOT NULL THEN _email_other
                ELSE user_id
            END) as _account_email

        FROM users WHERE $row = '$value' AND type <> 'HMIS' AND type <> 'HIS-Doctor' AND type <> 'HIS-Radiologist' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hmisGetEmployeeInfoPayroll($data)
    {

        $query = "SELECT *,

            (SELECT rate_classification from hospital_employee_details where hospital_employee_details.user_id = '" . $data['employee_id'] . "' limit 1) as rate_class,
            (SELECT monthly_salary from hospital_employee_details where hospital_employee_details.user_id = '" . $data['employee_id'] . "' limit 1) as mo_salary

        FROM hospital_dtr_logs WHERE management_id = '" . $data['management_id'] . "' AND user_id = '" . $data['employee_id'] . "' ORDER BY created_at DESC ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hmisGetPayrollReportByDate($data)
    {
        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_to']));
        $employee_id = $data['employee_id'];
        $management_id = $data['management_id'];

        $query = "SELECT *,

            (SELECT rate_classification FROM hospital_employee_details WHERE hospital_employee_details.user_id = '$employee_id' limit 1) as rate_class,
            (SELECT monthly_salary FROM hospital_employee_details WHERE hospital_employee_details.user_id = '$employee_id' limit 1) as mo_salary,
            (SELECT daily_salary FROM hospital_employee_details WHERE hospital_employee_details.user_id = '$employee_id' limit 1) as daily_salary,

            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_accounting,
            (SELECT email FROM accounting_account where accounting_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_accounting,
            (SELECT user_fullname FROM hospital_billing_account where hospital_billing_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_billing,
            (SELECT email FROM hospital_billing_account where hospital_billing_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_billing,
            (SELECT user_fullname FROM cashier where cashier.user_id = hospital_dtr_logs.user_id limit 1) as _name_cashier,
            (SELECT email FROM cashier where cashier.user_id = hospital_dtr_logs.user_id limit 1) as _email_cashier,
            (SELECT name FROM doctors where doctors.user_id = hospital_dtr_logs.user_id limit 1) as _name_doctors,

            (SELECT user_fullname FROM encoder where encoder.user_id = hospital_dtr_logs.user_id limit 1) as _name_encoder,
            (SELECT email FROM encoder where encoder.user_id = hospital_dtr_logs.user_id limit 1) as _email_encoder,
            (SELECT user_fullname FROM endorsement_account where endorsement_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_endorsement,
            (SELECT email FROM endorsement_account where endorsement_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_endorsement,

            (SELECT user_fullname FROM haptech_account where haptech_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_haptech,
            (SELECT email FROM haptech_account where haptech_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_haptech,
            (SELECT user_fullname FROM hr_account where hr_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_hr,
            (SELECT email FROM hr_account where hr_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_hr,
            (SELECT user_fullname FROM imaging where imaging.user_id = hospital_dtr_logs.user_id limit 1) as _name_imaging,
            (SELECT email FROM imaging where imaging.user_id = hospital_dtr_logs.user_id limit 1) as _email_imaging,
            (SELECT user_fullname FROM laboratory_list where laboratory_list.user_id = hospital_dtr_logs.user_id limit 1) as _name_laboratory,
            (SELECT email FROM laboratory_list where laboratory_list.user_id = hospital_dtr_logs.user_id limit 1) as _email_laboratory,
            (SELECT user_fullname FROM operation_manager_account where operation_manager_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_om,
            (SELECT email FROM operation_manager_account where operation_manager_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_om,
            (SELECT user_fullname FROM nurses where nurses.user_id = hospital_dtr_logs.user_id limit 1) as _name_ns,
            (SELECT email FROM nurses where nurses.user_id = hospital_dtr_logs.user_id limit 1) as _email_ns,
            (SELECT user_fullname FROM pharmacy where pharmacy.user_id = hospital_dtr_logs.user_id limit 1) as _name_pharmacy,
            (SELECT email FROM pharmacy where pharmacy.user_id = hospital_dtr_logs.user_id limit 1) as _email_pharmacy,
            (SELECT user_fullname FROM psychology_account where psychology_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_psychology,
            (SELECT email FROM psychology_account where psychology_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_psychology,
            (SELECT name FROM radiologist where radiologist.user_id = hospital_dtr_logs.user_id limit 1) as _name_radiologist,
            (SELECT email FROM radiologist where radiologist.user_id = hospital_dtr_logs.user_id limit 1) as _email_radiologist,
            (SELECT user_fullname FROM receiving_account where receiving_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_receiving,
            (SELECT email FROM receiving_account where receiving_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_receiving,
            (SELECT user_fullname FROM admission_account where admission_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_registration,
            (SELECT email FROM admission_account where admission_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_registration,
            (SELECT user_fullname FROM stockroom_acccount where stockroom_acccount.user_id = hospital_dtr_logs.user_id limit 1) as _name_stockroom,
            (SELECT email FROM stockroom_acccount where stockroom_acccount.user_id = hospital_dtr_logs.user_id limit 1) as _email_stockroom,
            (SELECT user_fullname FROM triage_account where triage_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_triage,
            (SELECT email FROM triage_account where triage_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_triage,
            (SELECT user_fullname FROM warehouse_accounts where warehouse_accounts.user_id = hospital_dtr_logs.user_id limit 1) as _name_warehouse,
            (SELECT email FROM warehouse_accounts where warehouse_accounts.user_id = hospital_dtr_logs.user_id limit 1) as _email_warehouse,
            (SELECT user_fullname FROM other_account where other_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_other,
            (SELECT email FROM other_account where other_account.user_id = hospital_dtr_logs.user_id limit 1) as _email_other,

            (SELECT CASE
                WHEN _name_accounting IS NOT NULL THEN _name_accounting
                WHEN _name_billing IS NOT NULL THEN _name_billing
                WHEN _name_cashier IS NOT NULL THEN _name_cashier
                WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                WHEN _name_encoder  IS NOT NULL THEN _name_encoder
                WHEN _name_endorsement  IS NOT NULL THEN _name_endorsement
                WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                WHEN _name_hr  IS NOT NULL THEN _name_hr
                WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                WHEN _name_om  IS NOT NULL THEN _name_om
                WHEN _name_ns IS NOT NULL THEN _name_ns
                WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                WHEN _name_psychology  IS NOT NULL THEN _name_psychology
                WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                WHEN _name_receiving  IS NOT NULL THEN _name_receiving
                WHEN _name_registration  IS NOT NULL THEN _name_registration
                WHEN _name_stockroom  IS NOT NULL THEN _name_stockroom
                WHEN _name_triage  IS NOT NULL THEN _name_triage
                WHEN _name_warehouse  IS NOT NULL THEN _name_warehouse
                WHEN _name_other  IS NOT NULL THEN _name_other

                ELSE user_id
            END) as _account_name,

            (SELECT CASE
                WHEN _email_accounting IS NOT NULL THEN _email_accounting
                WHEN _email_billing IS NOT NULL THEN _email_billing
                WHEN _email_cashier IS NOT NULL THEN _email_cashier
                WHEN _email_encoder IS NOT NULL THEN _email_encoder
                WHEN _email_endorsement IS NOT NULL THEN _email_endorsement
                WHEN _email_haptech  IS NOT NULL THEN _email_haptech
                WHEN _email_hr  IS NOT NULL THEN _email_hr
                WHEN _email_imaging  IS NOT NULL THEN _email_imaging
                WHEN _email_laboratory  IS NOT NULL THEN _email_laboratory
                WHEN _email_om  IS NOT NULL THEN _email_om
                WHEN _email_ns  IS NOT NULL THEN _email_ns
                WHEN _email_pharmacy  IS NOT NULL THEN _email_pharmacy
                WHEN _email_psychology  IS NOT NULL THEN _email_psychology
                WHEN _email_radiologist  IS NOT NULL THEN _email_radiologist
                WHEN _email_receiving  IS NOT NULL THEN _email_receiving
                WHEN _email_registration  IS NOT NULL THEN _email_registration
                WHEN _email_stockroom  IS NOT NULL THEN _email_stockroom
                WHEN _email_triage  IS NOT NULL THEN _email_triage
                WHEN _email_warehouse  IS NOT NULL THEN _email_warehouse
                WHEN _email_other  IS NOT NULL THEN _email_other
                ELSE user_id
            END) as _account_email

        FROM hospital_dtr_logs WHERE timein >= '$date_from' AND timein <= '$date_to' AND user_id = '$employee_id' AND management_id = '$management_id' ORDER BY created_at DESC ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    //07-05-2021
    public static function laboratorySalesFilterByDate($data)
    {
        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));

        $query = "SELECT *,
            (SELECT CONCAT(patients.firstname, ' ',patients.lastname) FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id ) as name
        FROM cashier_patientbills_records WHERE cashier_patientbills_records.created_at >= '$strDaily' AND cashier_patientbills_records.created_at <= '$lstDaily' AND cashier_patientbills_records.status = 1 AND cashier_patientbills_records.bill_from = 'laboratory' AND cashier_patientbills_records.management_id = '" . $data['management_id'] . "' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function imagingSalesFilterByDate($data)
    {
        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));

        $query = "SELECT *,
            (SELECT CONCAT(patients.firstname, ' ',patients.lastname) FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id ) as name
        FROM cashier_patientbills_records WHERE cashier_patientbills_records.created_at >= '$strDaily' AND cashier_patientbills_records.created_at <= '$lstDaily' AND cashier_patientbills_records.status = 1 AND cashier_patientbills_records.bill_from = 'imaging' AND cashier_patientbills_records.management_id = '" . $data['management_id'] . "' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function himsUpdateAccountRateClass($data)
    {
        return DB::table('hospital_employee_details')
            ->where('user_id', $data['users_user_id'])
            ->update([
                'rate_classification' => $data['rate_classification'],
                'monthly_salary' => !empty($data['monthly_salary']) ? $data['monthly_salary'] : null,
                'daily_salary' => !empty($data['daily_salary']) ? $data['daily_salary'] : null,
                'added_by' => $data['user_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getGeneralManagementBranches($data)
    {
        if ($data['type'] === 'clinic') {
            return DB::table('general_management_branches')
                ->join('management', 'management.management_id', '=', 'general_management_branches.management_id')
                ->where('general_management_branches.general_management_id', $data['main_management_id'])
                ->where("management.branch_type", '<>', 'hq')
            // ->where("management.branch_type", '<>', 'van')
                ->get();
        }
        return DB::table('general_management_branches')
            ->join('management', 'management.management_id', '=', 'general_management_branches.management_id')
            ->where('general_management_branches.general_management_id', $data['main_management_id'])
            ->get();

    }

    public static function getForLeaveApproval($data)
    {
        $mngt = $data['main_mgmt_id'];

        $query = "SELECT *,
            (SELECT name FROM management where management.management_id = clinic_leave_application.management_id limit 1) as branch_name,
            (SELECT name FROM management where management.management_id = clinic_leave_application.management_id limit 1) as branch_name,
            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = clinic_leave_application.user_id limit 1) as _name_accounting,
            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = clinic_leave_application.user_id limit 1) as _name_accounting,
            (SELECT user_fullname FROM hospital_billing_account where hospital_billing_account.user_id = clinic_leave_application.user_id limit 1) as _name_billing,
            (SELECT user_fullname FROM cashier where cashier.user_id = clinic_leave_application.user_id limit 1) as _name_cashier,
            (SELECT name FROM doctors where doctors.user_id = clinic_leave_application.user_id limit 1) as _name_doctors,
            (SELECT user_fullname FROM encoder where encoder.user_id = clinic_leave_application.user_id limit 1) as _name_encoder,
            (SELECT user_fullname FROM haptech_account where haptech_account.user_id = clinic_leave_application.user_id limit 1) as _name_haptech,
            (SELECT user_fullname FROM hr_account where hr_account.user_id = clinic_leave_application.user_id limit 1) as _name_hr,
            (SELECT user_fullname FROM imaging where imaging.user_id = clinic_leave_application.user_id limit 1) as _name_imaging,
            (SELECT user_fullname FROM laboratory_list where laboratory_list.user_id = clinic_leave_application.user_id limit 1) as _name_laboratory,
            (SELECT user_fullname FROM operation_manager_account where operation_manager_account.user_id = clinic_leave_application.user_id limit 1) as _name_om,
            (SELECT user_fullname FROM pharmacy where pharmacy.user_id = clinic_leave_application.user_id limit 1) as _name_pharmacy,
            (SELECT user_fullname FROM psychology_account where psychology_account.user_id = clinic_leave_application.user_id limit 1) as _name_psychology,
            (SELECT name FROM radiologist where radiologist.user_id = clinic_leave_application.user_id limit 1) as _name_radiologist,
            (SELECT user_fullname FROM admission_account where admission_account.user_id = clinic_leave_application.user_id limit 1) as _name_registration,
            (SELECT user_fullname FROM stockroom_acccount where stockroom_acccount.user_id = clinic_leave_application.user_id limit 1) as _name_stockroom,
            (SELECT user_fullname FROM triage_account where triage_account.user_id = clinic_leave_application.user_id limit 1) as _name_triage,
            (SELECT user_fullname FROM warehouse_accounts where warehouse_accounts.user_id = clinic_leave_application.user_id limit 1) as _name_warehouse,
            (SELECT user_fullname FROM other_account where other_account.user_id = clinic_leave_application.user_id limit 1) as _name_other,

            (SELECT
                CASE
                    WHEN _name_accounting IS NOT NULL THEN _name_accounting
                    WHEN _name_billing IS NOT NULL THEN _name_billing
                    WHEN _name_cashier IS NOT NULL THEN _name_cashier
                    WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                    WHEN _name_encoder IS NOT NULL THEN _name_encoder
                    WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                    WHEN _name_hr  IS NOT NULL THEN _name_hr
                    WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                    WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                    WHEN _name_om IS NOT NULL THEN _name_om
                    WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                    WHEN _name_psychology IS NOT NULL THEN _name_psychology
                    WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                    WHEN _name_registration  IS NOT NULL THEN _name_registration
                    WHEN _name_stockroom  IS NOT NULL THEN _name_stockroom
                    WHEN _name_triage IS NOT NULL THEN _name_triage
                    WHEN _name_warehouse  IS NOT NULL THEN _name_warehouse
                    WHEN _name_other IS NOT NULL THEN _name_other
                    ELSE user_id
                END
            ) as usersfname
        from clinic_leave_application where main_mgmt_id = '$mngt' AND noted_by_des = 'approve' AND noted_by IS NOT NULL AND approved_by IS NULL ORDER BY created_at ASC ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function saveLeaveDecision($data)
    {
        if ($data['noted_by_des'] == 'disapprove') {
            $query = DB::table('hospital_employee_details')->select('sick_leave', 'vacation_leave')->where('user_id', $data['users_id'])->first();

            if ($data['leave_type'] == 'sickleave') {
                DB::table('hospital_employee_details')
                    ->where('user_id', $data['users_id'])
                    ->update([
                        'sick_leave' => (float) $query->sick_leave + (float) $data['no_days'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            if ($data['leave_type'] == 'vacationleave') {
                DB::table('hospital_employee_details')
                    ->where('user_id', $data['users_id'])
                    ->update([
                        'vacation_leave' => (float) $query->vacation_leave + (float) $data['no_days'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            return DB::table('clinic_leave_application')
                ->where('cla_id', $data['cla_id'])
                ->update([
                    'approved_by' => $data['user_id'],
                    'approved_by_des' => $data['approved_by_des'],
                    'disapprove_reason' => $data['disapprove_reason'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            return DB::table('clinic_leave_application')
                ->where('cla_id', $data['cla_id'])
                ->update([
                    'approved_by' => $data['user_id'],
                    'approved_by_des' => $data['approved_by_des'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getHIMSDoctorList($data)
    {
        return DB::table('doctors')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getHIMSServicesByDocId($data)
    {
        return DB::table('doctors_appointment_services')
            ->where('doctors_id', $data['doctors_id'])
            ->get();
    }

    public static function getHIMSDoctorSales($data)
    {
        return DB::connection('mysql')->table('cashier_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('cashier_patientbills_records.*', DB::raw('concat(patients.firstname," ",patients.lastname) as name'))
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.bill_from', 'doctor')
            ->where('cashier_patientbills_records.status', 1)
            ->get();
    }

    public static function doctorSalesFilterByDate($data)
    {
        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));

        $query = "SELECT *,
            (SELECT CONCAT(patients.firstname, ' ',patients.lastname) FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id ) as name
        FROM cashier_patientbills_records WHERE cashier_patientbills_records.created_at >= '$strDaily' AND cashier_patientbills_records.created_at <= '$lstDaily' AND cashier_patientbills_records.status = 1 AND cashier_patientbills_records.bill_from = 'doctor' AND cashier_patientbills_records.management_id = '" . $data['management_id'] . "' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getHIMSPsychologySales($data)
    {
        return DB::connection('mysql')->table('cashier_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
            ->select('cashier_patientbills_records.*', DB::raw('concat(patients.firstname," ",patients.lastname) as name'))
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.bill_from', 'psychology')
            ->where('cashier_patientbills_records.status', 1)
            ->get();
    }

    public static function psychologySalesFilterByDate($data)
    {
        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));

        $query = "SELECT *,
            (SELECT CONCAT(patients.firstname, ' ',patients.lastname) FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id ) as name
        FROM cashier_patientbills_records WHERE cashier_patientbills_records.created_at >= '$strDaily' AND cashier_patientbills_records.created_at <= '$lstDaily' AND cashier_patientbills_records.status = 1 AND cashier_patientbills_records.bill_from = 'psychology' AND cashier_patientbills_records.management_id = '" . $data['management_id'] . "' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function haptechAddNewDoctorAccount($data)
    {
        date_default_timezone_set('Asia/Manila');
        $type = 'HIS-Doctor';
        $user_id = 'u-' . time();
        $doctors_id = 'd-' . time();

        DB::table('doctors')
            ->insert([
                'd_id' => $doctors_id,
                'doctors_id' => 'doctor-' . rand(0, 99) . time(),
                'management_id' => $data['branch'],
                'user_id' => $user_id,
                'name' => $data['fullname'],
                'address' => $data['address'],
                'gender' => $data['gender'],
                'contact_no' => $data['contact'],
                'birthday' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
                'status' => 1,
                'role' => 'User',
                'added_by' => $data['user_id'],
                'online_appointment' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::table('users')
            ->insert([
                'user_id' => $user_id,
                'username' => $data['user_username'],
                'password' => Hash::make($data['user_pass']),
                'status' => 1,
                'type' => $type,
                'email' => $data['email'],
                'is_verify' => 1,
                'is_confirm' => 1,
                'manage_by' => $data['branch'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'remember_token' => Hash::make($data['user_pass']),
                'api_token' => Hash::make($data['user_pass']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getForItemApprovalByID($data)
    {
        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->leftJoin('pharmacyclinic_warehouse_brand', 'pharmacyclinic_warehouse_brand.brand_id', '=', 'pharmacyclinic_warehouse_inventory_temp_exclusive.product_name')
            ->select('pharmacyclinic_warehouse_inventory_temp_exclusive.*', 'pharmacyclinic_warehouse_brand.brand as brandName')
            ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.warehouse_id', _Accounting::getPharmacyWarehouseId($data)->warehouse_id)
            ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.management_id', _Accounting::getPharmacyWarehouseId($data)->management_id)
            ->whereNotNull('request_id')
            ->where('accounting_approve', 1)
            ->where('supplier_approve', 1)
            ->whereNull('owner_approve')
            ->where('pharmacyclinic_warehouse_inventory_temp_exclusive.type', 'OUT')
            ->where('request_id', $data['request_id'])
            ->get();
    }

    public static function updateItemApprovalByID($data)
    {
        $warehouse_id = _Accounting::getPharmacyWarehouseId($data)->warehouse_id;
        $management_id = _Accounting::getPharmacyWarehouseId($data)->management_id;

        return DB::table('pharmacyclinic_warehouse_inventory_temp_exclusive')
            ->where('warehouse_id', $warehouse_id)
            ->where('management_id', $management_id)
            ->where('request_id', $data['request_id'])
            ->update([
                'owner_approve' => $data['owner_approve'],
                'owner_disapprove_reason' => $data['owner_disapprove_reason'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

}
