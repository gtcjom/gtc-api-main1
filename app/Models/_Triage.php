<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;

class _Triage extends Model
{
    public static function histriageGetHeaderInfo($data){
        // return DB::table('triage_account')
        // ->select('triage_id', 'user_fullname as name', 'image', 'user_address as address')
        // ->where('user_id', $data['user_id'])
        // ->first();

        return DB::table('triage_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'triage_account.user_id')
            ->select('triage_account.triage_id', 'triage_account.user_fullname as name', 'triage_account.image', 'triage_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('triage_account.user_id', $data['user_id'])
            ->first();
    }

    public static function histriageGetPersonalInfoById($data){
        $query = "SELECT * FROM triage_account WHERE user_id = '".$data['user_id']."' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

    public static function histriageUploadProfile($data, $filename){
        date_default_timezone_set('Asia/Manila');
        return DB::table('triage_account')
        ->where('user_id', $data['user_id'])
        ->update([
            'image' => $filename,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function histriageUpdatePersonalInfo($data){
        return DB::table('triage_account')
        ->where('user_id', $data['user_id'])
        ->update([
            'user_fullname' => $data['fullname'],
            'user_address' => $data['address'],
            'email' => $data['email'],  
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function histriageUpdateUsername($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
        ->where('user_id', $data['user_id'])
        ->update([ 
            'username' => $data['new_username'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function histriageUpdatePassword($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
        ->where('user_id', $data['user_id'])
        ->update([ 
            'password' => Hash::make($data['new_password']),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function histriageNewPatient($data, $filename){
        date_default_timezone_set('Asia/Manila');
        $patientid = 'p-'.rand(0, 999).time();
        $userid = 'u-'.rand(0, 8888).time();

        if( !empty($data['temp'])){
            DB::connection('mysql')->table('patients_temp_history')->insert([
                'pth_id' => 'pth-'.rand(0, 99999),
                'patients_id' => $patientid,
                'temp' => $data['temp'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }  

        DB::table('patients_contacttracing')->insert([
            'pct_id' => 'pct-' . time() . rand(0, 99999),
            'temperature' => $data['temp'],
            'patient_id' => $patientid,
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'sickness' => !empty($data['sickness']) ? $data['sickness'] : NULL,
            'triage_staff' => $data['user_id'],
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
            'height' => $data['height'], 
            'weight' => $data['weight'], 
            'pulse' => $data['pulse'], 
            'temperature' => $data['temp'], 
            'blood_systolic' => $data['bp_systolic'],  
            'blood_diastolic' => $data['bp_diastolic'],  
            'blood_type' => $data['blood_type'], 
            'image' => $filename,
            'join_category' => 'hosp-app',
            'added_by' => $data['user_id'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function histriageGetIncompleteList($data){
        // return DB::connection('mysql')
        //     ->table('patients_contacttracing')
        //     ->join('patients', 'patients.patient_id', '=' , 'patients_contacttracing.patient_id')
        //     ->select('patients_contacttracing.pct_id','patients.firstname', 'patients.lastname', 'patients.patient_id', 'patients.image', 'patients.middle')
        //     ->whereNull('patients_contacttracing.purpose')
        //     ->where('patients.management_id' ,$data['management_id'])
        //     ->orderBy('patients.lastname', 'ASC')
        //     ->get();

            return DB::connection('mysql')
            ->table('patients')
            ->select('firstname', 'lastname', 'patient_id', 'image', 'middle')
            ->where('management_id' ,$data['management_id'])
            ->orderBy('lastname', 'ASC')
            ->get();
    }

    public static function histriageGetPatientInformation($data){
        return DB::connection('mysql')->table('patients')
        ->leftJoin('patients_contacttracing', 'patients_contacttracing.patient_id', '=', 'patients.patient_id')
        ->select('patients.*', 'patients_contacttracing.sickness', 'patients_contacttracing.temperature', 'patients_contacttracing.created_at as latestCCRecord')
        // ->whereNull('patients_contacttracing.purpose')
        ->where('patients.patient_id', $data['patient_id'])
        ->orderBy('patients.lastname', 'ASC')
        ->orderBy('patients_contacttracing.created_at', 'DESC')
        ->first();
    }

    public static function histriageUpdatePatientInfo($data){
        date_default_timezone_set('Asia/Manila'); 

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
            'updated_at' => date('Y-m-d H:i:s'), 
        ]);
    }

    public static function histriageAddNewContactTracing($data){
        date_default_timezone_set('Asia/Manila');
        
        DB::connection('mysql')->table('patients_temp_history')->insert([
            'pth_id' => 'pth-'.rand(0, 99999),
            'patients_id' => $data['patient_id'],
            'temp' => $data['new_temp'],
            'added_by' => $data['user_id'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('patients_contacttracing')->insert([
            'pct_id' => 'pct-' . time() . rand(0, 99999),
            'temperature' => $data['new_temp'],
            'patient_id' => $data['patient_id'],
            'sickness' => $data['sickness'],
            'triage_staff' => $data['user_id'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
