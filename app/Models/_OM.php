<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _OM extends Model
{
    public static function hisOMHeaderInfo($data){
        // return DB::table('operation_manager_account')
        //     ->select('om_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

            return DB::table('operation_manager_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'operation_manager_account.user_id')
            ->select('operation_manager_account.om_id', 'operation_manager_account.user_fullname as name', 'operation_manager_account.image', 'operation_manager_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('operation_manager_account.user_id', $data['user_id'])
            ->first();
    }

    public static function hisOMGetPersonalInfoById($data){
        $query = "SELECT * FROM operation_manager_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisOMUploadProfile($data, $filename){
        date_default_timezone_set('Asia/Manila');
        return DB::table('operation_manager_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisOMUpdateUsername($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisOMUpdatePassword($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisOMUpdatePersonalInfo($data){
        return DB::table('operation_manager_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
