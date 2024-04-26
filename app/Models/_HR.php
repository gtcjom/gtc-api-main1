<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPayrollMail;


class _HR extends Model
{

    public static function hisHRHeaderInfo($data)
    {
        return DB::table('hr_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'hr_account.user_id')
            ->select('hr_account.h_id', 'hr_account.user_fullname as name', 'hr_account.image', 'hr_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('hr_account.user_id', $data['user_id'])
            ->first();
    }

    public static function hisHRGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM hr_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisHRUpdatePersonalInfo($data)
    {
        return DB::table('hr_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisHRUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisHRUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisHRUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('hr_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisHRGetAllUsersForSummary($data)
    {
        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_to']));

        $query = "SELECT user_id as UserID,
            (SELECT rate_classification FROM hospital_employee_details WHERE hospital_employee_details.user_id = UserID limit 1) as rate_class,
            (SELECT monthly_salary FROM hospital_employee_details WHERE hospital_employee_details.user_id = UserID limit 1) as mo_salary,
            (SELECT daily_salary FROM hospital_employee_details WHERE hospital_employee_details.user_id = UserID limit 1) as daily_salary,

            (SELECT user_fullname FROM accounting_account where accounting_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_accounting,
            (SELECT user_fullname FROM hospital_billing_account where hospital_billing_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_billing,
            (SELECT user_fullname FROM cashier where cashier.user_id = hospital_dtr_logs.user_id limit 1) as _name_cashier,
            (SELECT name FROM doctors where doctors.user_id = hospital_dtr_logs.user_id limit 1) as _name_doctors,
            (SELECT user_fullname FROM haptech_account where haptech_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_haptech,
            (SELECT user_fullname FROM hr_account where hr_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_hr,
            (SELECT user_fullname FROM imaging where imaging.user_id = hospital_dtr_logs.user_id limit 1) as _name_imaging,
            (SELECT user_fullname FROM laboratory_list where laboratory_list.user_id = hospital_dtr_logs.user_id limit 1) as _name_laboratory,
            (SELECT user_fullname FROM pharmacy where pharmacy.user_id = hospital_dtr_logs.user_id limit 1) as _name_pharmacy,
            (SELECT user_fullname FROM psychology_account where psychology_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_psychology,
            (SELECT name FROM radiologist where radiologist.user_id = hospital_dtr_logs.user_id limit 1) as _name_radiologist,
            (SELECT user_fullname FROM admission_account where admission_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_registration,
            (SELECT user_fullname FROM stockroom_acccount where stockroom_acccount.user_id = hospital_dtr_logs.user_id limit 1) as _name_stockroom,
            (SELECT user_fullname FROM triage_account where triage_account.user_id = hospital_dtr_logs.user_id limit 1) as _name_triage,
            (SELECT user_fullname FROM warehouse_accounts where warehouse_accounts.user_id = hospital_dtr_logs.user_id limit 1) as _name_warehouse,

            (SELECT CASE
                WHEN _name_accounting IS NOT NULL THEN _name_accounting
                WHEN _name_billing IS NOT NULL THEN _name_billing
                WHEN _name_cashier IS NOT NULL THEN _name_cashier
                WHEN _name_doctors  IS NOT NULL THEN _name_doctors
                WHEN _name_haptech  IS NOT NULL THEN _name_haptech
                WHEN _name_hr  IS NOT NULL THEN _name_hr
                WHEN _name_imaging  IS NOT NULL THEN _name_imaging
                WHEN _name_laboratory  IS NOT NULL THEN _name_laboratory
                WHEN _name_pharmacy  IS NOT NULL THEN _name_pharmacy
                WHEN _name_psychology  IS NOT NULL THEN _name_psychology
                WHEN _name_radiologist  IS NOT NULL THEN _name_radiologist
                WHEN _name_registration  IS NOT NULL THEN _name_registration
                WHEN _name_stockroom  IS NOT NULL THEN _name_stockroom
                WHEN _name_triage  IS NOT NULL THEN _name_triage
                WHEN _name_warehouse  IS NOT NULL THEN _name_warehouse
                ELSE user_id
            END) as _account_name,

            (SELECT IFNULL(COUNT(id) ,0) FROM hospital_dtr_logs WHERE timein >= '$date_from' AND timein <= '$date_to' AND hospital_dtr_logs.user_id = UserID AND management_id = '".$data['management_id']."' AND hospital_dtr_logs.timeout IS NOT NULL) as totalNoOfDays

        FROM hospital_dtr_logs WHERE timein >= '$date_from' AND timein <= '$date_to' AND management_id = '" . $data['management_id'] . "' ORDER BY created_at DESC ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

    }

    public static function hisHRGetPayslipDeductionByPeriod($data) {  
        return DB::table('hospital_employee_payroll_add')
            ->join('payroll_header', 'payroll_header.phi_id', '=', 'hospital_employee_payroll_add.header_id')
            ->select('hospital_employee_payroll_add.*', 'payroll_header.header as header_name')
            ->where('hospital_employee_payroll_add.user_id' , $data['employee_id'])
            ->where('hospital_employee_payroll_add.covered_period_start' , date('Y-m-d', strtotime($data['covered_period_start'])))
            ->where('hospital_employee_payroll_add.covered_period_end' , date('Y-m-d', strtotime($data['covered_period_end'])))
            ->where('hospital_employee_payroll_add.header_cat', 'Deduction')
            ->get();
    }

    public static function hisHRGetPayslipBonusByPeriod($data) {  
        return DB::table('hospital_employee_payroll_add')
        ->join('payroll_header', 'payroll_header.phi_id', '=', 'hospital_employee_payroll_add.header_id')
        ->select('hospital_employee_payroll_add.*', 'payroll_header.header as header_name')
        ->where('hospital_employee_payroll_add.user_id' , $data['employee_id'])
        ->where('hospital_employee_payroll_add.covered_period_start' , date('Y-m-d', strtotime($data['covered_period_start'])))
        ->where('hospital_employee_payroll_add.covered_period_end' , date('Y-m-d', strtotime($data['covered_period_end'])))
        ->where('hospital_employee_payroll_add.header_cat', 'Earning')
        ->get();
    }

    public static function hisHRGetPayrollHeaderList($data) {  
        return DB::table('payroll_header')
            ->select('*', 'phi_id as value', 'header as label')
            ->where('management_id' , $data['management_id'])
            ->orderBy('category', 'DESC')
            ->get();
    }

    public static function hisHRGetPayrollHeaderListByBracket($data) {  
        return DB::table('payroll_header')
            ->select('*', 'header as label', 'phi_id as value')
            ->where('management_id' , $data['management_id'])
            // ->where('category', $data['category'])
            ->orderBy('category', 'DESC')
            ->get();
    }

    public static function hisHRNewPayrollHeader($data) {  
        return DB::table('payroll_header')
            ->insert([
                'phi_id' => 'phi-'.rand(0, 99).time(),
                'management_id' => $data['management_id'],
                'header' => $data['header'],
                'category' => $data['category'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
    
    public static function hisHRAddPayslip($data) {  
        return DB::table('hospital_employee_payroll_add')
            ->insert([
                'hepa_id' => 'hepa-'.rand(0, 99).time(),
                'user_id' => $data['employee_id'],
                'management_id' => $data['management_id'],
                'header_id' => $data['header'],
                'header_cat' => $data['header_cat'],
                'amount' => $data['amount'],        
                'covered_period_start' => date('Y-m-d', strtotime($data['covered_period_start'])),           
                'covered_period_end' => date('Y-m-d', strtotime($data['covered_period_end'])),                                                 
                'added_by' => $data['user_id'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }

    public static function getEmpPayrollSummary($data){
        $dailyStrt = date('Y-m-d', strtotime($data['date_from'])).' 00:00';
        $dailyLst = date('Y-m-d', strtotime($data['date_to'])).' 23:59';
        $strDaily = date('Y-m-d H:i:s', strtotime($dailyStrt));
        $lstDaily = date('Y-m-d H:i:s', strtotime($dailyLst));
        $mgnt = $data['management_id'];

        $query ="SELECT *, header_id as _x_looter_id, sum(amount) as _x_total,
            (SELECT header from payroll_header where phi_id = _x_looter_id) as _x_looter
        from hospital_employee_payroll_add where covered_period_start >= '$strDaily' AND covered_period_end <= '$lstDaily' AND management_id = '$mgnt' GROUP BY `user_id`, `_x_looter_id` ORDER BY header_cat DESC
        ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisHRPayrollSendToEmail($data){  
        $dailyStrt = date('Y-m-d', strtotime($data['covered_period_start']));
        $dailyLst = date('Y-m-d', strtotime($data['covered_period_end']));

        $data2 = DB::table('hospital_employee_payroll_add')
        ->join('payroll_header', 'payroll_header.phi_id', '=', 'hospital_employee_payroll_add.header_id')
        ->select('hospital_employee_payroll_add.*', 'payroll_header.header as header_name')
        ->where('hospital_employee_payroll_add.user_id' , $data['employee_id'])
        ->where('hospital_employee_payroll_add.covered_period_start' , date('Y-m-d', strtotime($data['covered_period_start'])))
        ->where('hospital_employee_payroll_add.covered_period_end' , date('Y-m-d', strtotime($data['covered_period_end'])))
        ->orderBy('hospital_employee_payroll_add.header_cat', 'DESC')
        ->get();

        Mail::to($data['receivers_email'])->send(new SendPayrollMail($data, $data2));

        return true;
    }    

    public static function getAllBraches($data){
        return DB::table('general_management_branches')
            ->join('management', 'management.management_id', '=', 'general_management_branches.management_id')
            ->select('management.*', 'general_management_branches.*', 'general_management_branches.management_id as value', 'management.name as label')
            ->where('general_management_branches.general_management_id', $data['main_management_id'])
            ->get();
    }

    public static function getEditExistingBranch($data) {  
        return DB::table('management')
            ->where('m_id', $data['m_id'])
            ->update([
                'name' => $data['branch_name'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public static function addNewBranch($data) {  
        date_default_timezone_set('Asia/Manila');
        $management_id = 'm-'.rand(0,9).time();
        $user_id = 'u-' . time();

        $query = DB::table('management')
        ->insert([
            'm_id' => $management_id,
            'management_id' => $management_id,
            'user_id' => $user_id,
            'name' => $data['branch_name'],
            'address' => $data['branch_address'],  
            'branch_type' => $data['branch_type'], 
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if($query){
            DB::table('users')->insert([
                'user_id' => $user_id,
                'username' => $data['user_username'],
                'password' => Hash::make($data['user_pass']),
                'status' => 1,
                'type' => 'HMIS',
                'is_verify' => 1,
                'is_confirm' => 1,
                'manage_by' => $management_id,
                'main_mgmt_id' => $data['main_mgmt_id'],
                'remember_token' => Hash::make($data['user_pass']),
                'api_token' => Hash::make($data['user_pass']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::table('general_management_branches')
        ->insert([
            'gmb_id' => 'gmb-'.rand(0, 99).time(),
            'general_management_id' => $data['main_mgmt_id'],
            'management_id' => $management_id,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function getSpecificInfoOfuserForEdit($data){
        $database = '';
        $databasetWithId = '';

        if($data['type'] == 'HIS-Accounting'){
            $database = 'accounting_account';
            $databasetWithId = 'accounting_account.user_id';
        }
        
        if($data['type'] == 'HIS-Billing'){
            $database = 'hospital_billing_account';
            $databasetWithId = 'hospital_billing_account.user_id';
        }
        
        if($data['type'] == 'HIS-Cashier'){
            $database = `cashier`;
            $databasetWithId = 'cashier.user_id';
        }
        
        if($data['type'] == 'HIS-Documentation'){
            $database = 'encoder';
            $databasetWithId = 'encoder.user_id';
        }
        
        if($data['type'] == 'HIS-Endorsement'){
            $database = 'endorsement_account';
            $databasetWithId = 'endorsement_account.user_id';
        }
        
        if($data['type'] == 'HIS-Warehouse'){
            $database = 'warehouse_accounts';
            $databasetWithId = 'warehouse_accounts.user_id';
        }
        
        if($data['type'] == 'HIS-Hr'){
            $database = 'hr_account';
            $databasetWithId = 'hr_account.user_id';
        }
        
        if($data['type'] == 'HIS-Imaging'){
            $database = 'imaging';
            $databasetWithId = 'imaging.user_id';
        }
        
        if($data['type'] == 'HIS-Laboratory'){
            $database = 'laboratory_list';
            $databasetWithId = 'laboratory_list.user_id';
        }
        
        if($data['type'] == 'HIS-OM'){
            $database = 'operation_manager_account';
            $databasetWithId = 'operation_manager_account.user_id';
        }
        
        if($data['type'] == 'HIS-Nurse'){
            $database = 'nurses';
            $databasetWithId = 'nurses.user_id';
        }
        
        if($data['type'] == 'HIS-Psychology'){
            $database = 'psychology_account';
            $databasetWithId = 'psychology_account.user_id';
        }
        
        if($data['type'] == 'HIS-Receiving'){
            $database = 'receiving_account';
            $databasetWithId = 'receiving_account.user_id';
        }

        if($data['type'] == 'HIS-Registration'){
            $database = 'admission_account';
            $databasetWithId = 'admission_account.user_id';
        }

        if($data['type'] == 'HIS-Others'){
            $database = 'other_account';
            $databasetWithId = 'other_account.user_id';
        }

        return DB::table('hospital_employee_details')
        ->join($database, $databasetWithId, '=', "hospital_employee_details.user_id")
        ->where('hospital_employee_details.user_id', $data['user_id'])
        ->first();
    }
    
    public static function hisHRUpdateUserInfo($data) {
        $database = '';

        if($data['users_user_type'] == 'HIS-Accounting'){
            $database = 'accounting_account';
        }
        
        if($data['users_user_type'] == 'HIS-Billing'){
            $database = 'hospital_billing_account';
        }
        
        if($data['users_user_type'] == 'HIS-Cashier'){
            $database = `cashier`;
        }
        
        if($data['users_user_type'] == 'HIS-Documentation'){
            $database = 'encoder';
        }
        
        if($data['users_user_type'] == 'HIS-Endorsement'){
            $database = 'endorsement_account';
        }
        
        if($data['users_user_type'] == 'HIS-Warehouse'){
            $database = 'warehouse_accounts';
        }
        
        if($data['users_user_type'] == 'HIS-Hr'){
            $database = 'hr_account';
        }
        
        if($data['users_user_type'] == 'HIS-Imaging'){
            $database = 'imaging';
        }
        
        if($data['users_user_type'] == 'HIS-Laboratory'){
            $database = 'laboratory_list';
        }
        
        if($data['users_user_type'] == 'HIS-OM'){
            $database = 'operation_manager_account';
        }
        
        if($data['users_user_type'] == 'HIS-Nurse'){
            $database = 'nurses';
        }
        
        if($data['users_user_type'] == 'HIS-Psychology'){
            $database = 'psychology_account';
        }
        
        if($data['users_user_type'] == 'HIS-Receiving'){
            $database = 'receiving_account';
        }

        if($data['users_user_type'] == 'HIS-Registration'){
            $database = 'admission_account';
        }

        if($data['users_user_type'] == 'HIS-Others'){
            $database = 'other_account';
        }

        DB::table($database)
        ->where('user_id', $data['users_user_id'])
        ->update([
            'user_fullname' => $data['fullname'],
            'user_address' => $data['address'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('hospital_employee_details')
        ->where('user_id', $data['users_user_id'])
        ->update([
            'date_started' => date('Y-m-d H:i:s', strtotime($data['date_started'])),
            'date_birth' => date('Y-m-d H:i:s', strtotime($data['date_birth'])),
            'employee_status' => $data['status'],
            'civil_status' => $data['civil_status'],
            'contact' =>$data['contact'], 
            'position' => $data['position'], 
            'shared' => $data['shared'],
            'sick_leave' => $data['sick_leave'],
            'sick_leave_orig' => $data['sick_leave'],
            'vacation_leave' => $data['vacation_leave'],
            'vacation_leave_orig' => $data['vacation_leave'],
            'hazard_start_15' =>$data['hazard_start_15'],
            'hazard_16_end' => $data['hazard_16_end'],
            'rate_classification' => $data['rate_classification'],
            'monthly_salary' => $data['monthly_salary'],
            'daily_salary' => $data['daily_salary'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('users')
        ->where('user_id', $data['users_user_id'])
        ->update([
            'email' => $data['email'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function getSpecificInfoOfuserForEditDoc($data){
        $database = '';
        $databasetWithId = '';

        if($data['type'] == 'HIS-Radiologist'){
            $database = 'radiologist';
            $databasetWithId = 'radiologist.user_id';
        }
        
        if($data['type'] == 'HIS-Doctor'){
            $database = 'doctors';
            $databasetWithId = 'doctors.user_id';
        }

        return DB::table('users')
        ->join($database, $databasetWithId, '=', "users.user_id")
        ->where('users.user_id', $data['user_id'])
        ->first();
    }

    public static function hisHRUpdateUserInfoDoc($data) {
        if($data['users_user_type'] == 'HIS-Doctor'){
            DB::table('doctors')
            ->where('user_id', $data['users_user_id'])
            ->update([
                'name' => $data['fullname'],
                'address' => $data['address'],
                'gender' => $data['gender'],
                'contact_no' => $data['contact'],
                'birthday' => $data['date_birth'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        if($data['users_user_type'] == 'HIS-Radiologist'){
            DB::table('radiologist')
            ->where('user_id', $data['users_user_id'])
            ->update([
                'name' => $data['fullname'],
                'address' => $data['address'],
                'gender' => $data['gender'],
                'birthday' => $data['date_birth'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::table('users')
        ->where('user_id', $data['users_user_id'])
        ->update([
            'email' => $data['email'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }
    
}
