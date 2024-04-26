<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Other extends Model
{
    public static function hisOtherHeaderInfo($data){
        // return DB::table('other_account')
        //     ->select('oa_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

            return DB::table('other_account')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'other_account.user_id')
            ->select('other_account.oa_id', 'other_account.user_fullname as name', 'other_account.image', 'other_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('other_account.user_id', $data['user_id'])
            ->first();
    }

    public static function hisOtherGetPersonalInfoById($data){
        $query = "SELECT * FROM other_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisOtherUploadProfile($data, $filename){
        date_default_timezone_set('Asia/Manila');
        return DB::table('other_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisOtherUpdateUsername($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisOtherUpdatePassword($data){
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisOtherUpdatePersonalInfo($data){
        return DB::table('other_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
