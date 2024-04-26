<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Billing extends Model
{

    public static function hisBillingHeaderInfo($data)
    {
        // return DB::table('hospital_billing_account')
        //     ->select('buser_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

        return DB::table('hospital_billing_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'hospital_billing_account.user_id')
            ->select('hospital_billing_account.buser_id', 'hospital_billing_account.user_fullname as name', 'hospital_billing_account.image', 'hospital_billing_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('hospital_billing_account.user_id', $data['user_id'])
            ->first();
    }

    public static function hisBillingGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM hospital_billing_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisBillingUpdatePersonalInfo($data)
    {
        return DB::table('hospital_billing_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisBillingUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisBillingUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisBillingUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('hospital_billing_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getSoaManagmentPatient($data)
    {
        // return DB::table('patients')->where('management_id', $data['management_id'])->get();
        $management_id = $data['management_id'];

        $query = "SELECT *, patient_id as pid,
        (SELECT firstname from patients where patient_id = pid limit 1) as firstname,
           (SELECT middle from patients where patient_id = pid limit 1) as middle,
           (SELECT lastname from patients where patient_id = pid limit 1) as lastname,
           (SELECT birthday from patients where patient_id = pid limit 1) as birthday,
           (SELECT gender from patients where patient_id = pid limit 1) as gender,
           (SELECT street from patients where patient_id = pid limit 1) as street,
           (SELECT barangay from patients where patient_id = pid limit 1) as barangay,
           (SELECT city from patients where patient_id = pid limit 1) as city
        FROM hospital_admitted_patient WHERE management_id = '$management_id' GROUP BY patient_id ";

        // $query = " SELECT *, patient_id as pid,
        //     (SELECT firstname from patients where patient_id = pid limit 1) as firstname,
        //     (SELECT middle from patients where patient_id = pid limit 1) as middle,
        //     (SELECT lastname from patients where patient_id = pid limit 1) as lastname,
        //     (SELECT birthday from patients where patient_id = pid limit 1) as birthday,
        //     (SELECT gender from patients where patient_id = pid limit 1) as gender,
        //     (SELECT street from patients where patient_id = pid limit 1) as street,
        //     (SELECT barangay from patients where patient_id = pid limit 1) as barangay,
        //     (SELECT city from patients where patient_id = pid limit 1) as city,
        //     (SELECT company from patients where patient_id = pid limit 1) as company_id,

        //     (SELECT company from management_accredited_companies where management_accredited_companies.company_id = company_id limit 1) as company_name,

        //     (SELECT hmo from management_accredited_company_hmo where management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used limit 1) as hmoNameWCompany,
        //     (SELECT name from hmo_list where hmo_list.hl_id = cashier_patientbills_records.hmo_used limit 1) as hmoNameWOCompany

        // FROM cashier_patientbills_records WHERE charge_type = 'charge' AND is_charged_paid = 0 GROUP BY pid;
        // ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getSoaManagementPatientTransactions($data)
    {
        // return DB::table('cashier_patientbills_records')
        //     ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        //     ->select('cashier_patientbills_records.*', 'patients.*', 'cashier_patientbills_records.created_at as transaction_date')
        //     ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
        //     ->where('cashier_patientbills_records.charge_type', 'charge')
        //     ->where('cashier_patientbills_records.is_charged_paid', 0)
        //     ->where('cashier_patientbills_records.patient_id', $data['patient_id'])
        //     ->where('is_charged', 1)
        //     ->get();
        $main_mgmt_id = $data['main_mgmt_id'];
        $patient_id = $data['patient_id'];

        $query = "SELECT * FROM hospital_admitted_patient_billing_record WHERE patient_id = '$patient_id'";

        // $query = " SELECT *, patient_id as pid, created_at as transaction_date,

        //     (SELECT firstname from patients where patient_id = pid limit 1) as firstname,
        //     (SELECT middle from patients where patient_id = pid limit 1) as middle,
        //     (SELECT lastname from patients where patient_id = pid limit 1) as lastname,
        //     (SELECT birthday from patients where patient_id = pid limit 1) as birthday,
        //     (SELECT gender from patients where patient_id = pid limit 1) as gender,
        //     (SELECT street from patients where patient_id = pid limit 1) as street,
        //     (SELECT barangay from patients where patient_id = pid limit 1) as barangay,
        //     (SELECT city from patients where patient_id = pid limit 1) as city,
        //     (SELECT company from patients where patient_id = pid limit 1) as company_id,

        //     (SELECT company from management_accredited_companies where management_accredited_companies.company_id = company_id limit 1) as company_name,

        //     (SELECT hmo from management_accredited_company_hmo where management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used limit 1) as hmoNameWCompany,
        //     (SELECT name from hmo_list where hmo_list.hl_id = cashier_patientbills_records.hmo_used limit 1) as hmoNameWOCompany

        // FROM cashier_patientbills_records WHERE charge_type = 'charge' AND is_charged_paid = 0 AND main_mgmt_id = '$main_mgmt_id' AND patient_id = '$patient_id' ;
        // ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getSoaManagmentPatientInfo($data)
    {
        return DB::table('patients')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getManagementCompanies($data)
    {
        return DB::table('management_accredited_companies')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getCompaniesTransaction($data)
    {
        $from = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $to = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $dateFrom = date('Y-m-d H:i:s', strtotime($from));
        $dateTo = date('Y-m-d H:i:s', strtotime($to));

        // ->where('imaging_center.created_at','>=',$dateFrom)
        //     ->where('imaging_center.created_at','<=',$dateTo)

        $companyid = $data['company'];
        $hmo_category = $data['hmo_category'];

        // $query = "SELECT *, patient_id as pid,
        //     (SELECT company from management_accredited_companies where company_id = '$companyid' limit 1) as company_name,
        //     (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 1) as _transaction_paid_total,
        //     (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 0 ) as _transaction_charge_total,
        //     (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1) as _transaction_total,
        //     (SELECT ifnull(count(id), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1) as _transaction_count
        // FROM patients WHERE company = '$companyid' having _transaction_total > 0 ";

        $query = " SELECT *, patient_id as pid,
            (SELECT company from management_accredited_companies where company_id = '$companyid' limit 1) as company_name,
            (SELECT firstname from patients where patient_id = pid limit 1) as firstname,
            (SELECT middle from patients where patient_id = pid limit 1) as middle,
            (SELECT lastname from patients where patient_id = pid limit 1) as lastname,

            (SELECT hmo from management_accredited_company_hmo where management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used limit 1) as hmo_name,

            (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 1 and created_at >= '$from' and created_at <= '$to') as _transaction_paid_total,
            (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 0 and created_at >= '$from' and created_at <= '$to') as _transaction_charge_total,
            (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and created_at >= '$from' and created_at <= '$to') as _transaction_total,
            (SELECT ifnull(count(id), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1) as _transaction_count

        from cashier_patientbills_records where hmo_category = '$hmo_category' and created_at >= '$from' and created_at <= '$to'  group by pid having _transaction_total > 0;
        ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    public static function getCompaniesTrasactionByPatients($data)
    {
        $from = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $to = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $dateFrom = date('Y-m-d H:i:s', strtotime($from));
        $dateTo = date('Y-m-d H:i:s', strtotime($to));
        $companyid = $data['company'];
        $hmo_category = $data['hmo_category'];

        $query = "SELECT patient_id as pid, cashier_patientbills_records.*,

            (SELECT company from management_accredited_companies where company_id = '$companyid' limit 1) as company_name,
            (SELECT firstname from patients where patient_id = pid limit 1) as firstname,
            (SELECT middle from patients where patient_id = pid limit 1) as middle,
            (SELECT lastname from patients where patient_id = pid limit 1) as lastname,

            (SELECT hmo from management_accredited_company_hmo where mach_id = cashier_patientbills_records.hmo_used ) as hmo_name

        FROM cashier_patientbills_records WHERE hmo_category = '$hmo_category' and created_at >= '$from' and created_at <= '$to' ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function editFormInfo($data)
    {
        date_default_timezone_set('Asia/Manila');
        if (!empty($data['ffhi_id'])) {
            return DB::table('form_footer_header_information')
                ->where('ffhi_id', $data['ffhi_id'])
                ->where('management_id', $data['management_id'])
                ->where('main_mgmt_id', $data['main_mgmt_id'])
                ->update([
                    'subtitle1' => $data['subtitle1'],
                    'subtitle2' => $data['subtitle2'],
                    'subtitle3' => $data['subtitle3'],
                    'prepared_by' => $data['prepared_by'],
                    'checked_by' => $data['checked_by'],
                    'verified_by' => $data['verified_by'],
                    'noted_by' => $data['noted_by'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {
            return DB::table('form_footer_header_information')
                ->insert([
                    'ffhi_id' => 'ffhi-' . rand(0, 99) . time(),
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'used_to' => 'company-soa',
                    'subtitle1' => $data['subtitle1'],
                    'subtitle2' => $data['subtitle2'],
                    'subtitle3' => $data['subtitle3'],
                    'prepared_by' => $data['prepared_by'],
                    'checked_by' => $data['checked_by'],
                    'verified_by' => $data['verified_by'],
                    'noted_by' => $data['noted_by'],
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public static function getCurrentFormInformation($data)
    {
        return DB::table('form_footer_header_information')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('used_to', 'company-soa')
            ->first();
    }

    public static function getCompaniesHMOTransaction($data)
    {
        $from = date('Y-m-d', strtotime($data['date_from'])) . ' 00:00';
        $to = date('Y-m-d', strtotime($data['date_to'])) . ' 23:59';
        $dateFrom = date('Y-m-d H:i:s', strtotime($from));
        $dateTo = date('Y-m-d H:i:s', strtotime($to));

        // ->where('imaging_center.created_at','>=',$dateFrom)
        //     ->where('imaging_center.created_at','<=',$dateTo)

        $companyid = $data['hmo_id'];
        // $query = "SELECT *, patient_id as pid,
        //     (SELECT company from management_accredited_companies where company_id = '$companyid' limit 1) as company_name,
        //     (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 1) as _transaction_paid_total,
        //     (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 0 ) as _transaction_charge_total,
        //     (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1) as _transaction_total,
        //     (SELECT ifnull(count(id), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1) as _transaction_count
        // FROM patients WHERE company = '$companyid' having _transaction_total > 0 ";

        $query = " SELECT *, patient_id as pid,
            (SELECT hmo from management_accredited_company_hmo where mach_id = '$companyid' limit 1) as company_name,
            (SELECT firstname from patients where patient_id = pid limit 1) as firstname,
            (SELECT middle from patients where patient_id = pid limit 1) as middle,
            (SELECT lastname from patients where patient_id = pid limit 1) as lastname,
            (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 1 and created_at >= '$from' and created_at <= '$to') as _transaction_paid_total,
            (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and is_charged_paid = 0 and created_at >= '$from' and created_at <= '$to') as _transaction_charge_total,
            (SELECT ifnull(sum(bill_amount), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1 and created_at >= '$from' and created_at <= '$to') as _transaction_total,
            (SELECT ifnull(count(id), 0) from cashier_patientbills_records where patient_id = pid and is_charged = 1) as _transaction_count
        from cashier_patientbills_records where hmo_used = '$companyid' and created_at >= '$from' and created_at <= '$to'  group by pid having _transaction_total > 0;
        ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAdmittedPatientForBilling($data)
    {
        return DB::table('hospital_admitted_patient_forbillout')
            ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient_forbillout.patient_id')
            ->select('hospital_admitted_patient_forbillout.*', 'patients.firstname as fname', 'patients.lastname as  lname', 'patients.middle as  mname', 'patients.gender', 'patients.image', 'patients.birthday', 'patients.street as street', 'patients.barangay as barangay', 'patients.city as city')
            ->where('hospital_admitted_patient_forbillout.management_id', $data['management_id'])
            ->where('hospital_admitted_patient_forbillout.main_mgmt_id', $data['main_mgmt_id'])
            ->where('hospital_admitted_patient_forbillout.billout_status', '<>', 'discharged')
            ->groupBy('hospital_admitted_patient_forbillout.trace_number')
            ->get();
    }

    public static function getPatientsAdmittingBills($data)
    {
        return DB::table('hospital_admitted_patient_billing_record')
            ->leftJoin('hospital_admitted_patient_billing_record_philhealth', 'hospital_admitted_patient_billing_record_philhealth.trace_number', 'hospital_admitted_patient_billing_record.trace_number')
            ->select('hospital_admitted_patient_billing_record.*', 'hospital_admitted_patient_billing_record_philhealth.amount as philhealth_amount', 'hospital_admitted_patient_billing_record_philhealth.philhealth_caseno as philhealth_caseno', 'hospital_admitted_patient_billing_record_philhealth.philhealth as philhealth')
            ->where('hospital_admitted_patient_billing_record.patient_id', $data['patient_id'])
            ->where('hospital_admitted_patient_billing_record.trace_number', $data['trace_number'])
            ->where('hospital_admitted_patient_billing_record.management_id', $data['management_id'])
            ->where('hospital_admitted_patient_billing_record.billing_status', 'billing-unpaid')
            ->get();
    }

    public static function admittedPatientProcessBillingAddPhilhealth($data)
    {
        return DB::table('hospital_admitted_patient_billing_record_philhealth')
            ->insert([
                "patient_id" => $data["patient_id"],
                "trace_number" => $data["trace_number"],
                "management_id" => $data["management_id"],
                "philhealth" => $data["philhealth"],
                "philhealth_caseno" => $data["philhealth_caseno"],
                "remarks" => $data["remarks"],
                "amount" => $data["amount"],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function admittedPatientSentToCashier($data)
    {
        return DB::table('hospital_admitted_patient_forbillout')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->update([
                "billout_status" => "for-cashier",
                "cashier_remarks" => $data["remarks"],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function dischargedPatientListGroupByPatientId($data)
    {
        return DB::table('hospital_admitted_patient_forbillout')
            ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient_forbillout.patient_id')
            ->select('hospital_admitted_patient_forbillout.*', 'patients.firstname as fname', 'patients.lastname as  lname', 'patients.middle as  mname', 'patients.gender', 'patients.image', 'patients.birthday', 'patients.street as street', 'patients.barangay as barangay', 'patients.city as city')
            ->where('hospital_admitted_patient_forbillout.management_id', $data['management_id'])
            ->where('hospital_admitted_patient_forbillout.billout_status', 'discharged')
            ->groupBy('hospital_admitted_patient_forbillout.patient_id')
            ->get();
    }

    public static function dischargedPatientListByTracenumber($data)
    {
        return DB::table('hospital_admitted_patient_forbillout')
            ->where('patient_id', $data['patient_id'])
            ->groupBy('trace_number')
            ->get();
    }

    public static function dischargedPatientBillRecords($data)
    {
        return DB::table('hospital_admitted_patient_billing_record')
            ->leftJoin('hospital_admitted_patient_billing_record_philhealth', 'hospital_admitted_patient_billing_record_philhealth.trace_number', 'hospital_admitted_patient_billing_record.trace_number')
            ->leftJoin('hospital_admitted_patient_billing_payments_record', 'hospital_admitted_patient_billing_payments_record.trace_number', 'hospital_admitted_patient_billing_record.trace_number')
            ->select('hospital_admitted_patient_billing_record.*', 'hospital_admitted_patient_billing_record_philhealth.amount as philhealth_amount', 'hospital_admitted_patient_billing_payments_record.payment_amount as payment_amount')
            ->where('hospital_admitted_patient_billing_record_philhealth.patient_id', $data['patient_id'])
            ->where('hospital_admitted_patient_billing_record_philhealth.trace_number', $data['trace_number'])
            ->get();
    }
}
