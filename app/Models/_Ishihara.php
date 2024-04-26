<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Ishihara extends Model
{
    public static function getHeaderInfo($data)
    {
        // return DB::table('ishihara_test_accounts')
        //     ->select('management_id', 'ishihara_id', 'user_fullname as name', 'image', 'user_address as address')
        //     ->where('user_id', $data['user_id'])
        //     ->first();

            return DB::table('ishihara_test_accounts')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'ishihara_test_accounts.user_id')
            ->select('ishihara_test_accounts.ishihara_id', 'ishihara_test_accounts.user_fullname as name', 'ishihara_test_accounts.image', 'ishihara_test_accounts.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('ishihara_test_accounts.user_id', $data['user_id'])
            ->first();
    }

    public static function getNewPatients($data)
    {
        return DB::table('ishihara_test_orders')
            ->where('is_paid', 1)
            ->where('management_id', $data['management_id'])
            ->whereNull('order_result')
            ->get();
    }

    public static function getIshiharaTest($data)
    {
        return DB::table('ishihara_test_list')
            ->where('management_id', _Ishihara::getHeaderInfo($data)->management_id)
            ->get();
    }

    public static function newIshiharaTest($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('ishihara_test_list')->insert([
            'itl_id' => 'itl-' . rand(0, 9999) . time(),
            'ishihara_id' => _Ishihara::getHeaderInfo($data)->ishihara_id,
            'management_id' => _Ishihara::getHeaderInfo($data)->management_id,
            'test_id' => 'test-' . rand(0, 9999) . time(),
            'test' => $data['test'],
            'rate' => $data['rate'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getPatientWithOrder($data)
    {
        return DB::table('ishihara_test_orders')
            ->join('patients', 'patients.patient_id', '=', 'ishihara_test_orders.patient_id')
            ->select('ishihara_test_orders.created_at as order_date', 'ishihara_test_orders.*', 'patients.*')
            ->where('ishihara_test_orders.management_id', $data['management_id'])
            ->where('ishihara_test_orders.is_processed', 0)
            ->where('ishihara_test_orders.is_paid', 0)
            ->whereNull('ishihara_test_orders.order_result')
            ->get();
    }


    public static function hisishiharaGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM ishihara_test_accounts WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisishiharaUpdatePersonalInfo($data)
    {
        return DB::table('ishihara_test_accounts')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisishiharaUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('ishihara_test_accounts')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisishiharaUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisishiharaUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
