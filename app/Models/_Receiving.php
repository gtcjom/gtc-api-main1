<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _Receiving extends Model
{
    use HasFactory;

    public static function getInformation($data){
        return DB::table('receiving_account')
        ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'receiving_account.user_id')
        ->select('receiving_account.rcv_id', 'receiving_account.user_fullname as name', 'receiving_account.image', 'receiving_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
        ->where('receiving_account.user_id', $data['user_id'])
        ->first();
    }

    public static function getPatientQueue($data)
    {
        return DB::table('patient_queue')
            ->leftJoin('patients', 'patients.patient_id', '=', 'patient_queue.patient_id')
            ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
            ->select('patient_queue.*',
                'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.image', 'patients.email',
                'patients.mobile',
                'patients.telephone',
                'patients.birthday',
                'patients.gender',
                'patients.street',
                'patients.barangay',
                'patients.city',
                'patients.company',
                'management_accredited_companies.company as _company_name'
            )
            ->where('patient_queue.management_id', $data['management_id'])
            ->where('patient_queue.type', $data['type'])
            ->get();
    }

    public static function newSpecimen($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('receiving_specimen')->insert([
            'rs_id' => 'sr-' . rand(0, 8888) . time(),
            'specimen_id' => 'specimen-' . rand(0, 8888) . time(),
            'patient_id' => $data['patient_id'],
            'specimen' => $data['specimen'],
            'trace_number' => $data['trace_number'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function specimentList($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('receiving_specimen')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function specimentRemove($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('receiving_specimen')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('id', $data['id'])
            ->delete();
    }

    public static function setAsDone($data)
    {

        $checkcount = DB::table('patient_queue')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->where('type', 'laboratory')
            ->get();

        if (count($checkcount)) {
            return DB::table('patient_queue')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('type', 'receiving')
                ->delete();
        } else {
            return DB::table('patient_queue')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('type', 'receiving')
                ->update([
                    'type' => 'laboratory',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

        }

    }

    public static function updatePersonalInfo($data)
    {
        return DB::table('receiving_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateProfileImage($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('receiving_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function receivingGetPersonalInfoById($data){
        $query = "SELECT * FROM receiving_account WHERE user_id = '".$data['user_id']."' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }

}
