<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Documentation extends Model
{
    public static function hisDocumentationGetHeaderInfo($data)
    {
        // return DB::table('encoder')
        //     ->select('encoder_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

            return DB::table('encoder')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'encoder.user_id')
            ->select('encoder.encoder_id', 'encoder.user_fullname as name', 'encoder.image', 'encoder.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('encoder.user_id', $data['user_id'])
            ->first();
    }

    public static function hisDocumentationGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM encoder WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisDocumentationUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('encoder')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisDocumentationUpdatePersonalInfo($data)
    {
        return DB::table('encoder')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisDocumentationUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisDocumentationUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getResultToEditOnline($data){
        $query = "SELECT *,

            (SELECT firstname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as lname

        FROM imaging_center WHERE edit_by_encoder = 1 AND radiologist_type = 'Telerad' AND imaging_result IS NOT NULL";
        $result = DB::connection('mysql2')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getResultToEditLocal($data){
        $query = "SELECT *,

            (SELECT firstname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = imaging_center.patients_id) as lname

        FROM imaging_center WHERE edit_by_encoder = 1 AND imaging_result IS NOT NULL";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function saveEditedResult($data){
        date_default_timezone_set('Asia/Manila');
        $update = DB::connection('mysql2')->table('imaging_center')
        ->where('imaging_center_id', $data['imaging_center_id'])
        ->update([
            'imaging_result' => $data['new_result'],
            'imaging_results_remarks' => $data['new_impression'],
            'is_viewed' => 1,
            'edit_by_encoder' => 2,
            'main_mgmt_id' => $data['main_mgmt_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if($update){
            $get = DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id',$data['imaging_center_id'])->first();

            return DB::connection('mysql')->table('imaging_center')
            ->where('imaging_center_id', $data['imaging_center_id'])
            ->update([
                'radiologist' => $get->radiologist,
                'radiologist_type' => $get->radiologist_type,
                'imaging_result' => $get->imaging_result,
                'imaging_results_remarks' => $get->imaging_results_remarks,
                'imaging_result_attachment' => $get->imaging_result_attachment,
                'is_viewed' => $get->is_viewed,
                'is_processed' => $get->is_processed,
                'processed_by' => $get->processed_by,
                'start_time' => $get->start_time,
                'end_time' => $get->end_time,
                'manage_by' => $get->manage_by,
                'main_mgmt_id' => $get->main_mgmt_id,
                'edit_by_encoder' => $get->edit_by_encoder,
                'order_from' => $get->order_from,
                'updated_at' => $get->updated_at
            ]);
        }
    }
    
    public static function saveEditedResultLocal($data){
        date_default_timezone_set('Asia/Manila');
        $update = DB::connection('mysql')->table('imaging_center')
        ->where('imaging_center_id', $data['imaging_center_id'])
        ->update([
            'imaging_result' => $data['new_result'],
            'imaging_results_remarks' => $data['new_impression'],
            'is_viewed' => 1,
            'edit_by_encoder' => 2,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }

    public static function getAllBraches($data){
        return DB::table('general_management_branches')
        ->join('management', 'management.management_id', '=', 'general_management_branches.management_id')
        ->select('management.*', 'general_management_branches.*', 'general_management_branches.management_id as value', 'management.name as label')
        ->where('general_management_branches.general_management_id', $data['main_management_id'])
        ->where('management.branch_type', '<>', 'hq')
        ->get();
    }
    
    public static function getResultToPrint($data){
        $main_mgmt_id = $data['main_mgmt_id'];
        $query = "SELECT *,

            (SELECT name FROM management WHERE management.management_id = imaging_center.manage_by ) as branchName,
            (SELECT firstname FROM patients WHERE patients.patient_id = imaging_center.patients_id ) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = imaging_center.patients_id ) as lname

        FROM imaging_center WHERE main_mgmt_id = '$main_mgmt_id' AND edit_by_encoder = 2 AND radiologist_type = 'Telerad' AND imaging_result IS NOT NULL ORDER BY created_at DESC ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPatientInfoPatientId($data){
        return DB::table('patients')
        ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
        ->select('patients.*', 'management_accredited_companies.company as company_name')
        ->where('patients.patient_id', $data['patient_id'])
        ->first();
    }
    
}
