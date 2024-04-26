<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _Admitting extends Model
{
    use HasFactory;

    public static function getAdmittingInfo($data)
    {
        return DB::table('hospital_admitting_accounts')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'hospital_admitting_accounts.user_id')
            ->select('hospital_admitting_accounts.admitting_id', 'hospital_admitting_accounts.user_fullname as name', 'hospital_admitting_accounts.image', 'hospital_admitting_accounts.user_address as address', 'hospital_admitting_accounts.management_id', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('hospital_admitting_accounts.user_id', $data['user_id'])
            ->first();
    }

    public static function AdmittingGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM hospital_admitting_accounts WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function AdmittingUpdatePersonalInfo($data)
    {
        return DB::table('hospital_admitting_accounts')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function AdmittingUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function AdmittingUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function AdmittingUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('hospital_admitting_accounts')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getHospitalRoooms($data)
    {
        $query = "SELECT *,
        (SELECT count(id) FROM hospital_admitted_patient WHERE room_id = hospital_rooms.room_id AND nurse_department != 'discharged') as _unavailable_count
        FROM hospital_rooms WHERE management_id = '" . $data['management_id'] . "' AND status = 1";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getHospitalRooomDetails($data)
    {
        return DB::table('hospital_rooms')
            ->where('room_id', $data['room_id'])
            ->where('management_id', $data['management_id'])
            ->first();
    }

    public static function getHospitalRooomBedsDetails($data)
    {
        return DB::table('hospital_rooms_beds')
            ->where('room_id', $data['room_id'])
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getPatientsForAdmit($data)
    {
        return DB::table('hospital_admission')
            ->leftJoin('patients', 'patients.patient_id', 'hospital_admission.patient_id')
            ->select('hospital_admission.*', 'patients.firstname', 'patients.lastname', 'patients.image')
            ->where('hospital_admission.management_id', $data['management_id'])
            ->where('hospital_admission.is_admitted', 0)
            ->where('hospital_admission.status', 1)
            ->get();
    }

    public static function getRoomNumberList($data)
    {
        return DB::table('hospital_rooms_list')
            ->where('room_id', $data['room_id'])
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getRoomsBedsList($data)
    {
        $room_id = $data['room_id'];
        $room_number = $data['room_number'];
        $management_id = $data['management_id'];

        $query = "SELECT *, room_id as roomId, room_number as roomNumber, bed_number as roomBedNumber,
        (SELECT count(id) FROM hospital_admitted_patient WHERE room_id = roomId AND room_number = roomNumber AND room_bed_number = roomBedNumber AND nurse_department != 'discharged') as _use_room_count
        FROM hospital_rooms_beds WHERE management_id = '$management_id' AND room_id = '$room_id' AND room_number = '$room_number' ";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function handleAdmitPatientCheckIfAdmitted($data)
    {
        $room_id = $data['room_id'];
        $room_number = $data['room_number'];
        $management_id = $data['management_id'];

        $check = DB::table('hospital_rooms_list')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();

        return count($check) ? true : false;
    }

    public static function handleAdmitPatient($data)
    {
        DB::table('hospital_admission')
            ->where('trace_number', $data["trace_number"])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'is_admitted' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        /*** add room to billing ****/
        DB::table('hospital_admitted_patient_billing_record')->insert([
            "dbr_id" => "dbr-" . rand(99999, 99999999) . time(),
            "room_id" => $data["room_id"],
            "room_number_no" => $data["room_number"],
            "room_bed_no" => $data["room_bed_number"],
            "patient_id" => $data["patient_id"],
            "trace_number" => $data["trace_number"],
            "management_id" => $data["management_id"],
            "main_mgmt_id" => $data["main_mgmt_id"],
            "bill_name" => $data["bill_name"],
            "bill_amount" => $data["bill_amount"],
            "bill_from" => "room",
            "bill_payment" => "billing",
            "bill_department" => "hospital-room",
            "transaction_category" => "admitted-patient",
            "note" => $data["reason"],
            "process_by" => $data["user_id"],
            "request_physician" => $data['md'],
            "billing_status" => "billing-unpaid",
            "status" => 1,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        return DB::table('hospital_admitted_patient')->insert([
            "had_id" => 'had-' . rand(99999, 99999999) . '-' . time(),
            "admit_id" => 'admit-' . rand(99999, 99999999) . '-' . time(),
            "patient_id" => $data["patient_id"],
            "trace_number" => $data["trace_number"],
            "management_id" => $data["management_id"],
            "room_id" => $data["room_id"],
            "room_number" => $data["room_number"],
            "room_bed_number" => $data["room_bed_number"],
            "process_by" => $data["user_id"],
            "admitted_by" => $data["user_id"],
            "admitted_on" => date('Y-m-d H:i:s'),
            "reason" => $data["reason"],
            "medical_doctor_assign" => $data["md"],
            "nurse_department" => $data["nurse_type"],
            "status" => 1,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getRoomsListByRoomId($data)
    {
        return DB::table('hospital_rooms_list')
            ->where('room_id', $data['room_id'])
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getRoomsBedByRoomId($data)
    {
        $query = "SELECT *,
        (SELECT count(id) FROM hospital_admitted_patient WHERE room_id = hospital_rooms_beds.room_id AND room_bed_number = hospital_rooms_beds.bed_number AND room_number = hospital_rooms_beds.room_number AND nurse_department != 'discharged') as _unavailable_count
        FROM hospital_rooms_beds WHERE room_id = '" . $data['room_id'] . "' AND room_number = '" . $data['room_number'] . "' AND status = 1";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

}
