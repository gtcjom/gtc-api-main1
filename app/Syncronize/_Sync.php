<?php

namespace App\Syncronize;

use DB;
use Illuminate\Database\Eloquent\Model;

class _Sync extends Model
{

    public function __construct()
    {
        set_time_limit(800);
    }

    public static function syncAppointmentList()
    {

        // syncronize users table from offline to online
        $al_online_list_offline = DB::table('appointment_list')->get();
        foreach ($al_online_list_offline as $al_offline) {
            $al_offline_count = DB::connection('mysql2')->table('appointment_list')->where('appointment_id', $al_offline->appointment_id)->get();
            if (count($al_offline_count) > 0) {
                if ($al_offline->updated_at > $al_offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('appointment_list')->where('appointment_id', $al_offline->appointment_id)->update([
                        'patients_id' => $al_offline->patients_id,
                        'encoders_id' => $al_offline->encoders_id,
                        'doctors_id' => $al_offline->doctors_id,
                        'services' => $al_offline->services,
                        'amount' => $al_offline->amount,
                        'app_date' => $al_offline->app_date,
                        'app_date_end' => $al_offline->app_date_end,
                        'app_reason' => $al_offline->app_reason,
                        'is_reschedule' => $al_offline->is_reschedule,
                        'is_reschedule_date' => $al_offline->is_reschedule_date,
                        'is_reschedule_reason' => $al_offline->is_reschedule_reason,
                        'apperance' => $al_offline->apperance,
                        'is_waiting' => $al_offline->is_waiting,
                        'is_waiting_reason' => $al_offline->is_waiting_reason,
                        'is_complete' => $al_offline->is_complete,
                        'is_remove' => $al_offline->is_remove,
                        'is_remove_reason' => $al_offline->is_remove_reason,
                        'is_remove_date' => $al_offline->is_remove_date,
                        'referred_by' => $al_offline->referred_by,
                        'is_paid_bysecretary' => $al_offline->is_paid_bysecretary,
                        'status' => $al_offline->status,
                        'updated_at' => $al_offline->updated_at,
                        'created_at' => $al_offline->created_at,
                    ]);
                } else {
                    DB::table('appointment_list')->where('appointment_id', $al_offline_count[0]->appointment_id)->update([
                        'patients_id' => $al_offline_count[0]->patients_id,
                        'encoders_id' => $al_offline_count[0]->encoders_id,
                        'doctors_id' => $al_offline_count[0]->doctors_id,
                        'services' => $al_offline_count[0]->services,
                        'amount' => $al_offline_count[0]->amount,
                        'app_date' => $al_offline_count[0]->app_date,
                        'app_date_end' => $al_offline_count[0]->app_date_end,
                        'app_reason' => $al_offline_count[0]->app_reason,
                        'is_reschedule' => $al_offline_count[0]->is_reschedule,
                        'is_reschedule_date' => $al_offline_count[0]->is_reschedule_date,
                        'is_reschedule_reason' => $al_offline_count[0]->is_reschedule_reason,
                        'apperance' => $al_offline_count[0]->apperance,
                        'is_waiting' => $al_offline_count[0]->is_waiting,
                        'is_waiting_reason' => $al_offline_count[0]->is_waiting_reason,
                        'is_complete' => $al_offline_count[0]->is_complete,
                        'is_remove' => $al_offline_count[0]->is_remove,
                        'is_remove_reason' => $al_offline_count[0]->is_remove_reason,
                        'is_remove_date' => $al_offline_count[0]->is_remove_date,
                        'referred_by' => $al_offline_count[0]->referred_by,
                        'is_paid_bysecretary' => $al_offline_count[0]->is_paid_bysecretary,
                        'status' => $al_offline_count[0]->status,
                        'updated_at' => $al_offline_count[0]->updated_at,
                        'created_at' => $al_offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('appointment_list')->insert([
                    'appointment_id' => $al_offline->appointment_id,
                    'patients_id' => $al_offline->patients_id,
                    'encoders_id' => $al_offline->encoders_id,
                    'doctors_id' => $al_offline->doctors_id,
                    'services' => $al_offline->services,
                    'amount' => $al_offline->amount,
                    'app_date' => $al_offline->app_date,
                    'app_date_end' => $al_offline->app_date_end,
                    'is_reschedule' => $al_offline->is_reschedule,
                    'is_reschedule_date' => $al_offline->is_reschedule_date,
                    'is_reschedule_reason' => $al_offline->is_reschedule_reason,
                    'apperance' => $al_offline->apperance,
                    'is_waiting' => $al_offline->is_waiting,
                    'is_waiting_reason' => $al_offline->is_waiting_reason,
                    'is_complete' => $al_offline->is_complete,
                    'is_remove' => $al_offline->is_remove,
                    'is_remove_reason' => $al_offline->is_remove_reason,
                    'is_remove_date' => $al_offline->is_remove_date,
                    'referred_by' => $al_offline->referred_by,
                    'is_paid_bysecretary' => $al_offline->is_paid_bysecretary,
                    'status' => $al_offline->status,
                    'updated_at' => $al_offline->updated_at,
                    'created_at' => $al_offline->created_at,
                ]);
            }
        }

        // syncronize users table from online to offline
        $al_online_list = DB::connection('mysql2')->table('appointment_list')->get();
        foreach ($al_online_list as $al_online) {
            $al_online_online = DB::table('appointment_list')->where('appointment_id', $al_online->appointment_id)->get();
            if (count($al_online_online) > 0) {
                DB::table('appointment_list')->where('appointment_id', $al_online->appointment_id)->update([
                    'patients_id' => $al_online->patients_id,
                    'encoders_id' => $al_online->encoders_id,
                    'doctors_id' => $al_online->doctors_id,
                    'services' => $al_online->services,
                    'amount' => $al_online->amount,
                    'app_date' => $al_online->app_date,
                    'app_date_end' => $al_online->app_date_end,
                    'app_reason' => $al_online->app_reason,
                    'is_reschedule' => $al_online->is_reschedule,
                    'is_reschedule_date' => $al_online->is_reschedule_date,
                    'is_reschedule_reason' => $al_online->is_reschedule_reason,
                    'apperance' => $al_online->apperance,
                    'is_waiting' => $al_online->is_waiting,
                    'is_waiting_reason' => $al_online->is_waiting_reason,
                    'is_complete' => $al_online->is_complete,
                    'is_remove' => $al_online->is_remove,
                    'is_remove_reason' => $al_online->is_remove_reason,
                    'is_remove_date' => $al_online->is_remove_date,
                    'referred_by' => $al_online->referred_by,
                    'is_paid_bysecretary' => $al_online->is_paid_bysecretary,
                    'status' => $al_online->status,
                    'updated_at' => $al_online->updated_at,
                    'created_at' => $al_online->created_at,
                ]);

            } else {
                DB::table('appointment_list')->insert([
                    'appointment_id' => $al_online->appointment_id,
                    'patients_id' => $al_online->patients_id,
                    'encoders_id' => $al_online->encoders_id,
                    'doctors_id' => $al_online->doctors_id,
                    'services' => $al_online->services,
                    'amount' => $al_online->amount,
                    'app_date' => $al_online->app_date,
                    'app_date_end' => $al_online->app_date_end,
                    'app_reason' => $al_online->app_reason,
                    'is_reschedule' => $al_online->is_reschedule,
                    'is_reschedule_date' => $al_online->is_reschedule_date,
                    'is_reschedule_reason' => $al_online->is_reschedule_reason,
                    'apperance' => $al_online->apperance,
                    'is_waiting' => $al_online->is_waiting,
                    'is_waiting_reason' => $al_online->is_waiting_reason,
                    'is_complete' => $al_online->is_complete,
                    'is_remove' => $al_online->is_remove,
                    'is_remove_reason' => $al_online->is_remove_reason,
                    'is_remove_date' => $al_online->is_remove_date,
                    'referred_by' => $al_online->referred_by,
                    'is_paid_bysecretary' => $al_online->is_paid_bysecretary,
                    'status' => $al_online->status,
                    'updated_at' => $al_online->updated_at,
                    'created_at' => $al_online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncAppointmentSettings()
    {
        // syncronize users table from offline to online
        $as_offline = DB::table('appointment_settings')->get();
        foreach ($as_offline as $as) {
            $as_count = DB::connection('mysql2')->table('appointment_settings')->where('app_settings_id', $as->app_settings_id)->get();
            if (count($as_count) > 0) {
                if ($as->updated_at > $as_count[0]->updated_at) {
                    DB::connection('mysql2')->table('appointment_settings')->where('app_settings_id', $as->app_settings_id)->update([
                        'encoder_id' => $as->encoder_id,
                        'doctors_id' => $as->doctors_id,
                        'app_time_start' => $as->app_time_start,
                        'app_time_close' => $as->app_time_close,
                        'app_duration' => $as->app_duration,
                        'updated_at' => $as->updated_at,
                        'created_at' => $as->created_at,
                    ]);
                } else {
                    DB::table('appointment_settings')->where('app_settings_id', $as_count[0]->app_settings_id)->update([
                        'encoder_id' => $as_count[0]->encoder_id,
                        'doctors_id' => $as_count[0]->doctors_id,
                        'app_time_start' => $as_count[0]->app_time_start,
                        'app_time_close' => $as_count[0]->app_time_close,
                        'app_duration' => $as_count[0]->app_duration,
                        'updated_at' => $as_count[0]->updated_at,
                        'created_at' => $as_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('appointment_settings')->insert([
                    'app_settings_id' => $as->app_settings_id,
                    'encoder_id' => $as->encoder_id,
                    'doctors_id' => $as->doctors_id,
                    'app_time_start' => $as->app_time_start,
                    'app_time_close' => $as->app_time_close,
                    'app_duration' => $as->app_duration,
                    'updated_at' => $as->updated_at,
                    'created_at' => $as->created_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $as_online = DB::connection('mysql2')->table('appointment_settings')->get();
        foreach ($as_online as $as_online) {
            $as_online_count = DB::table('appointment_settings')->where('app_settings_id', $as_online->app_settings_id)->get();
            if (count($as_online_count) > 0) {
                DB::table('appointment_settings')->where('app_settings_id', $as_online->app_settings_id)->update([
                    'encoder_id' => $as_online->encoder_id,
                    'doctors_id' => $as_online->doctors_id,
                    'app_time_start' => $as_online->app_time_start,
                    'app_time_close' => $as_online->app_time_close,
                    'app_duration' => $as_online->app_duration,
                    'updated_at' => $as_online->updated_at,
                    'created_at' => $as_online->created_at,
                ]);

            } else {
                DB::table('appointment_settings')->insert([
                    'app_settings_id' => $as_online->app_settings_id,
                    'encoder_id' => $as_online->encoder_id,
                    'doctors_id' => $as_online->doctors_id,
                    'app_time_start' => $as_online->app_time_start,
                    'app_time_close' => $as_online->app_time_close,
                    'app_duration' => $as_online->app_duration,
                    'updated_at' => $as_online->updated_at,
                    'created_at' => $as_online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctors()
    {
        // syncronize appointment_settings table from online to offline
        $doctor_offline_List = DB::table('doctors')->get();
        foreach ($doctor_offline_List as $doctor_offline) {
            $doctor_offline_count = DB::connection('mysql2')->table('doctors')->where('d_id', $doctor_offline->d_id)->get();
            if (count($doctor_offline_count) > 0) {
                if ($doctor_offline->updated_at > $doctor_offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors')->where('d_id', $doctor_offline->d_id)->update([
                        'doctors_id' => $doctor_offline->doctors_id,
                        'management_id' => $doctor_offline->management_id,
                        'user_id' => $doctor_offline->user_id,
                        'name' => $doctor_offline->name,
                        'address' => $doctor_offline->address,
                        'gender' => $doctor_offline->gender,
                        'contact_no' => $doctor_offline->contact_no,
                        'birthday' => $doctor_offline->birthday,
                        'specialization' => $doctor_offline->specialization,
                        'image' => $doctor_offline->image,
                        'image_signature' => $doctor_offline->image_signature,
                        'cil_umn' => $doctor_offline->cil_umn,
                        'ead_mun' => $doctor_offline->ead_mun,
                        'status' => $doctor_offline->status,
                        'role' => $doctor_offline->role,
                        'added_by' => $doctor_offline->added_by,
                        'online_appointment' => $doctor_offline->online_appointment,
                        'created_at' => $doctor_offline->created_at,
                        'updated_at' => $doctor_offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors')->where('d_id', $doctor_offline_count[0]->d_id)->update([
                        'doctors_id' => $doctor_offline_count[0]->doctors_id,
                        'management_id' => $doctor_offline_count[0]->management_id,
                        'user_id' => $doctor_offline_count[0]->user_id,
                        'name' => $doctor_offline_count[0]->name,
                        'address' => $doctor_offline_count[0]->address,
                        'gender' => $doctor_offline_count[0]->gender,
                        'contact_no' => $doctor_offline_count[0]->contact_no,
                        'birthday' => $doctor_offline_count[0]->birthday,
                        'specialization' => $doctor_offline_count[0]->specialization,
                        'image' => $doctor_offline_count[0]->image,
                        'image_signature' => $doctor_offline_count[0]->image_signature,
                        'cil_umn' => $doctor_offline_count[0]->cil_umn,
                        'ead_mun' => $doctor_offline_count[0]->ead_mun,
                        'status' => $doctor_offline_count[0]->status,
                        'role' => $doctor_offline_count[0]->role,
                        'added_by' => $doctor_offline_count[0]->added_by,
                        'online_appointment' => $doctor_offline_count[0]->online_appointment,
                        'created_at' => $doctor_offline_count[0]->created_at,
                        'updated_at' => $doctor_offline_count[0]->updated_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('doctors')->insert([
                    'd_id' => $doctor_offline->d_id,
                    'doctors_id' => $doctor_offline->doctors_id,
                    'management_id' => $doctor_offline->management_id,
                    'user_id' => $doctor_offline->user_id,
                    'name' => $doctor_offline->name,
                    'address' => $doctor_offline->address,
                    'gender' => $doctor_offline->gender,
                    'contact_no' => $doctor_offline->contact_no,
                    'birthday' => $doctor_offline->birthday,
                    'specialization' => $doctor_offline->specialization,
                    'image' => $doctor_offline->image,
                    'image_signature' => $doctor_offline->image_signature,
                    'cil_umn' => $doctor_offline->cil_umn,
                    'ead_mun' => $doctor_offline->ead_mun,
                    'status' => $doctor_offline->status,
                    'role' => $doctor_offline->role,
                    'added_by' => $doctor_offline->added_by,
                    'online_appointment' => $doctor_offline->online_appointment,
                    'created_at' => $doctor_offline->created_at,
                    'updated_at' => $doctor_offline->updated_at,
                ]);
            }
        }

        // syncronize appointment_settings table from offline to online
        $doctor_online_List = DB::connection('mysql2')->table('doctors')->get();
        foreach ($doctor_online_List as $doctor_online) {
            $doctor_online_count = DB::table('doctors')->where('d_id', $doctor_online->d_id)->get();
            if (count($doctor_online_count) > 0) {
                DB::table('doctors')->where('d_id', $doctor_online->d_id)->update([
                    'doctors_id' => $doctor_online->doctors_id,
                    'management_id' => $doctor_online->management_id,
                    'user_id' => $doctor_online->user_id,
                    'name' => $doctor_online->name,
                    'address' => $doctor_online->address,
                    'gender' => $doctor_online->gender,
                    'contact_no' => $doctor_online->contact_no,
                    'birthday' => $doctor_online->birthday,
                    'specialization' => $doctor_online->specialization,
                    'image' => $doctor_online->image,
                    'image_signature' => $doctor_online->image_signature,
                    'cil_umn' => $doctor_online->cil_umn,
                    'ead_mun' => $doctor_online->ead_mun,
                    'status' => $doctor_online->status,
                    'role' => $doctor_online->role,
                    'added_by' => $doctor_online->added_by,
                    'online_appointment' => $doctor_online->online_appointment,
                    'created_at' => $doctor_online->created_at,
                    'updated_at' => $doctor_online->updated_at,
                ]);

            } else {
                DB::table('doctors')->insert([
                    'd_id' => $doctor_online->d_id,
                    'doctors_id' => $doctor_online->doctors_id,
                    'management_id' => $doctor_online->management_id,
                    'user_id' => $doctor_online->user_id,
                    'name' => $doctor_online->name,
                    'address' => $doctor_online->address,
                    'gender' => $doctor_online->gender,
                    'contact_no' => $doctor_online->contact_no,
                    'birthday' => $doctor_online->birthday,
                    'specialization' => $doctor_online->specialization,
                    'image' => $doctor_online->image,
                    'image_signature' => $doctor_online->image_signature,
                    'cil_umn' => $doctor_online->cil_umn,
                    'ead_mun' => $doctor_online->ead_mun,
                    'status' => $doctor_online->status,
                    'role' => $doctor_online->role,
                    'added_by' => $doctor_online->added_by,
                    'online_appointment' => $doctor_online->online_appointment,
                    'created_at' => $doctor_online->created_at,
                    'updated_at' => $doctor_online->updated_at,
                ]);
            }
        }
        return true;
    }

    public static function syncDoctorsAppointmentService()
    {
        // syncronize appointment_settings table from online to offline
        $doctor_service_offline = DB::table('doctors_appointment_services')->get();
        foreach ($doctor_service_offline as $doctor_service_offline) {
            $doctor_service_offline_count = DB::connection('mysql2')->table('doctors_appointment_services')->where('service_id', $doctor_service_offline->service_id)->get();
            if (count($doctor_service_offline_count) > 0) {
                if ($doctor_service_offline->updated_at > $doctor_service_offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_appointment_services')->where('service_id', $doctor_service_offline->service_id)->update([
                        'doctors_id' => $doctor_service_offline->doctors_id,
                        'services' => $doctor_service_offline->services,
                        'amount' => $doctor_service_offline->amount,
                        'status' => $doctor_service_offline->status,
                        'created_at' => $doctor_service_offline->created_at,
                        'updated_at' => $doctor_service_offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_appointment_services')->where('service_id', $doctor_service_offline_count[0]->service_id)->update([
                        'doctors_id' => $doctor_service_offline_count[0]->doctors_id,
                        'services' => $doctor_service_offline_count[0]->services,
                        'amount' => $doctor_service_offline_count[0]->amount,
                        'status' => $doctor_service_offline_count[0]->status,
                        'created_at' => $doctor_service_offline_count[0]->created_at,
                        'updated_at' => $doctor_service_offline_count[0]->updated_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('doctors_appointment_services')->insert([
                    'service_id' => $doctor_service_offline->service_id,
                    'doctors_id' => $doctor_service_offline->doctors_id,
                    'services' => $doctor_service_offline->services,
                    'amount' => $doctor_service_offline->amount,
                    'status' => $doctor_service_offline->status,
                    'created_at' => $doctor_service_offline->created_at,
                    'updated_at' => $doctor_service_offline->updated_at,
                ]);
            }
        }

        // syncronize appointment_settings table from offline to online
        $doctor_doctors_service = DB::connection('mysql2')->table('doctors_appointment_services')->get();
        foreach ($doctor_doctors_service as $doctor_service_online) {
            $doctor_service_online_count = DB::table('doctors_appointment_services')->where('service_id', $doctor_service_online->service_id)->get();
            if (count($doctor_service_online_count) > 0) {
                DB::table('doctors_appointment_services')->where('service_id', $doctor_service_online->service_id)->update([
                    'doctors_id' => $doctor_service_online->doctors_id,
                    'services' => $doctor_service_online->services,
                    'amount' => $doctor_service_online->amount,
                    'status' => $doctor_service_online->status,
                    'created_at' => $doctor_service_online->created_at,
                    'updated_at' => $doctor_service_online->updated_at,
                ]);
            } else {
                DB::table('doctors_appointment_services')->insert([
                    'service_id' => $doctor_service_online->service_id,
                    'doctors_id' => $doctor_service_online->doctors_id,
                    'services' => $doctor_service_online->services,
                    'amount' => $doctor_service_online->amount,
                    'status' => $doctor_service_online->status,
                    'created_at' => $doctor_service_online->created_at,
                    'updated_at' => $doctor_service_online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsComments()
    {
        // syncronize appointment_settings table from online to offline
        $doctor_service_offline = DB::table('doctors_comments')->get();
        foreach ($doctor_service_offline as $doctor_service_offline) {
            $doctor_service_offline_count = DB::connection('mysql2')->table('doctors_comments')->where('dc_id', $doctor_service_offline->dc_id)->get();
            if (count($doctor_service_offline_count) > 0) {
                if ($doctor_service_offline->updated_at > $doctor_service_offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_comments')->where('dc_id', $doctor_service_offline->dc_id)->update([
                        'doctors_id' => $doctor_service_offline->doctors_id,
                        'patient_id' => $doctor_service_offline->patient_id,
                        'comment' => $doctor_service_offline->comment,
                        'comment_status' => $doctor_service_offline->comment_status,
                        'status' => $doctor_service_offline->status,
                        'created_at' => $doctor_service_offline->created_at,
                        'updated_at' => $doctor_service_offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_comments')->where('dc_id', $doctor_service_offline_count[0]->dc_id)->update([
                        'doctors_id' => $doctor_service_offline_count[0]->doctors_id,
                        'patient_id' => $doctor_service_offline_count[0]->patient_id,
                        'comment' => $doctor_service_offline_count[0]->comment,
                        'comment_status' => $doctor_service_offline_count[0]->comment_status,
                        'status' => $doctor_service_offline_count[0]->status,
                        'created_at' => $doctor_service_offline_count[0]->created_at,
                        'updated_at' => $doctor_service_offline_count[0]->updated_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('doctors_comments')->insert([
                    'dc_id' => $doctor_service_offline->dc_id,
                    'doctors_id' => $doctor_service_offline->doctors_id,
                    'patient_id' => $doctor_service_offline->patient_id,
                    'comment' => $doctor_service_offline->comment,
                    'comment_status' => $doctor_service_offline->comment_status,
                    'status' => $doctor_service_offline->status,
                    'created_at' => $doctor_service_offline->created_at,
                    'updated_at' => $doctor_service_offline->updated_at,
                ]);
            }
        }

        // syncronize appointment_settings table from offline to online
        $doctor_doctors_service = DB::connection('mysql2')->table('doctors_comments')->get();
        foreach ($doctor_doctors_service as $doctor_service_online) {
            $doctor_service_online_count = DB::table('doctors_comments')->where('dc_id', $doctor_service_online->dc_id)->get();
            if (count($doctor_service_online_count) > 0) {
                DB::table('doctors_comments')->where('dc_id', $doctor_service_online->dc_id)->update([
                    'doctors_id' => $doctor_service_online->doctors_id,
                    'patient_id' => $doctor_service_online->patient_id,
                    'comment' => $doctor_service_online->comment,
                    'comment_status' => $doctor_service_online->comment_status,
                    'status' => $doctor_service_online->status,
                    'created_at' => $doctor_service_online->created_at,
                    'updated_at' => $doctor_service_online->updated_at,
                ]);
            } else {
                DB::table('doctors_comments')->insert([
                    'dc_id' => $doctor_service_online->dc_id,
                    'doctors_id' => $doctor_service_online->doctors_id,
                    'patient_id' => $doctor_service_online->patient_id,
                    'comment' => $doctor_service_online->comment,
                    'comment_status' => $doctor_service_online->comment_status,
                    'status' => $doctor_service_online->status,
                    'created_at' => $doctor_service_online->created_at,
                    'updated_at' => $doctor_service_online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsNotes()
    {
        // syncronize appointment_settings table from offline to online
        $notes_offline_List = DB::table('doctors_notes')->get();
        foreach ($notes_offline_List as $notes_ofline) {
            $notes_ofline_count = DB::connection('mysql2')->table('doctors_notes')->where('notes_id', $notes_ofline->notes_id)->get();
            if (count($notes_ofline_count) > 0) {
                if ($notes_ofline->updated_at > $notes_ofline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_notes')->where('notes_id', $notes_ofline->notes_id)->update([
                        'patients_id' => $notes_ofline->patients_id,
                        'doctors_id' => $notes_ofline->doctors_id,
                        'case_file' => $notes_ofline->case_file,
                        'initial_diagnosis' => $notes_ofline->initial_diagnosis,
                        'notes' => $notes_ofline->notes,
                        'status' => $notes_ofline->status,
                        'created_at' => $notes_ofline->created_at,
                        'updated_at' => $notes_ofline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_notes')->where('notes_id', $notes_ofline_count[0]->notes_id)->update([
                        'patients_id' => $notes_ofline_count[0]->patients_id,
                        'doctors_id' => $notes_ofline_count[0]->doctors_id,
                        'case_file' => $notes_ofline_count[0]->case_file,
                        'initial_diagnosis' => $notes_ofline_count[0]->initial_diagnosis,
                        'notes' => $notes_ofline_count[0]->notes,
                        'status' => $notes_ofline_count[0]->status,
                        'created_at' => $notes_ofline_count[0]->created_at,
                        'updated_at' => $notes_ofline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('doctors_notes')->insert([
                    'notes_id' => $notes_ofline->notes_id,
                    'patients_id' => $notes_ofline->patients_id,
                    'doctors_id' => $notes_ofline->doctors_id,
                    'case_file' => $notes_ofline->case_file,
                    'initial_diagnosis' => $notes_ofline->initial_diagnosis,
                    'notes' => $notes_ofline->notes,
                    'status' => $notes_ofline->status,
                    'created_at' => $notes_ofline->created_at,
                    'updated_at' => $notes_ofline->updated_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $notes_online_List = DB::connection('mysql2')->table('doctors_notes')->get();
        foreach ($notes_online_List as $notes_online) {
            $notes_online_count = DB::table('doctors_notes')->where('notes_id', $notes_online->notes_id)->get();
            if (count($notes_online_count) > 0) {
                DB::table('doctors_notes')->where('notes_id', $notes_online->notes_id)->update([
                    'patients_id' => $notes_online->patients_id,
                    'doctors_id' => $notes_online->doctors_id,
                    'case_file' => $notes_online->case_file,
                    'initial_diagnosis' => $notes_online->initial_diagnosis,
                    'notes' => $notes_online->notes,
                    'status' => $notes_online->status,
                    'created_at' => $notes_online->created_at,
                    'updated_at' => $notes_online->updated_at,
                ]);

            } else {
                DB::table('doctors_notes')->insert([
                    'notes_id' => $notes_online->notes_id,
                    'patients_id' => $notes_online->patients_id,
                    'doctors_id' => $notes_online->doctors_id,
                    'case_file' => $notes_online->case_file,
                    'initial_diagnosis' => $notes_online->initial_diagnosis,
                    'notes' => $notes_online->notes,
                    'status' => $notes_online->status,
                    'created_at' => $notes_online->created_at,
                    'updated_at' => $notes_online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsNotesCanvas()
    {
        // syncronize appointment_settings table from offline to online
        $dnotes_canvas_offline = DB::table('doctors_notes_canvas')->get();
        foreach ($dnotes_canvas_offline as $dnotes_canvas) {
            $dnotes_canvas_count = DB::connection('mysql2')->table('doctors_notes_canvas')->where('dnc_id', $dnotes_canvas->dnc_id)->get();
            if (count($dnotes_canvas_count) > 0) {
                if ($dnotes_canvas->updated_at > $dnotes_canvas_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_notes_canvas')->where('dnc_id', $dnotes_canvas->dnc_id)->update([
                        'patient_id' => $dnotes_canvas->patient_id,
                        'doctors_id' => $dnotes_canvas->doctors_id,
                        'canvas' => $dnotes_canvas->canvas,
                        'status' => $dnotes_canvas->status,
                        'created_at' => $dnotes_canvas->created_at,
                        'updated_at' => $dnotes_canvas->updated_at,
                    ]);
                } else {
                    DB::table('doctors_notes_canvas')->where('dnc_id', $dnotes_canvas_count[0]->dnc_id)->update([
                        'patient_id' => $dnotes_canvas_count[0]->patient_id,
                        'doctors_id' => $dnotes_canvas_count[0]->doctors_id,
                        'canvas' => $dnotes_canvas_count[0]->canvas,
                        'status' => $dnotes_canvas_count[0]->status,
                        'created_at' => $dnotes_canvas_count[0]->created_at,
                        'updated_at' => $dnotes_canvas_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('doctors_notes_canvas')->insert([
                    'dnc_id' => $dnotes_canvas->dnc_id,
                    'patient_id' => $dnotes_canvas->patient_id,
                    'doctors_id' => $dnotes_canvas->doctors_id,
                    'canvas' => $dnotes_canvas->canvas,
                    'status' => $dnotes_canvas->status,
                    'created_at' => $dnotes_canvas->created_at,
                    'updated_at' => $dnotes_canvas->updated_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $doctors_notes_online = DB::connection('mysql2')->table('doctors_notes_canvas')->get();
        foreach ($doctors_notes_online as $dnotes_canvas_online) {
            $dnotes_canvas_online_count = DB::table('doctors_notes_canvas')->where('dnc_id', $dnotes_canvas_online->dnc_id)->get();
            if (count($dnotes_canvas_online_count) > 0) {
                DB::table('doctors_notes_canvas')->where('dnc_id', $dnotes_canvas_online->dnc_id)->update([
                    'patient_id' => $dnotes_canvas_online->patient_id,
                    'doctors_id' => $dnotes_canvas_online->doctors_id,
                    'canvas' => $dnotes_canvas_online->canvas,
                    'status' => $dnotes_canvas_online->status,
                    'created_at' => $dnotes_canvas_online->created_at,
                    'updated_at' => $dnotes_canvas_online->updated_at,
                ]);

            } else {
                DB::table('doctors_notes_canvas')->insert([
                    'dnc_id' => $dnotes_canvas_online->dnc_id,
                    'patient_id' => $dnotes_canvas_online->patient_id,
                    'doctors_id' => $dnotes_canvas_online->doctors_id,
                    'canvas' => $dnotes_canvas_online->canvas,
                    'status' => $dnotes_canvas_online->status,
                    'created_at' => $dnotes_canvas_online->created_at,
                    'updated_at' => $dnotes_canvas_online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsNotification()
    {
        // syncronize doctors_notification table from offline to online
        $dnotif_offline = DB::table('doctors_notification')->get();
        foreach ($dnotif_offline as $dnotif) {
            $dnotif_count = DB::connection('mysql2')->table('doctors_notification')->where('notif_id', $dnotif->notif_id)->get();
            if (count($dnotif_count) > 0) {
                if ($dnotif->updated_at > $dnotif_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_notification')->where('notif_id', $dnotif->notif_id)->update([
                        'order_id' => $dnotif->order_id,
                        'patient_id' => $dnotif->patient_id,
                        'doctor_id' => $dnotif->doctor_id,
                        'category' => $dnotif->category,
                        'department' => $dnotif->department,
                        'is_view' => $dnotif->is_view,
                        'status' => $dnotif->status,
                        'created_at' => $dnotif->created_at,
                        'updated_at' => $dnotif->updated_at,
                    ]);
                } else {
                    DB::table('doctors_notification')->where('notif_id', $dnotif_count[0]->notif_id)->update([
                        'order_id' => $dnotif_count[0]->order_id,
                        'patient_id' => $dnotif_count[0]->patient_id,
                        'doctor_id' => $dnotif_count[0]->doctor_id,
                        'category' => $dnotif_count[0]->category,
                        'department' => $dnotif_count[0]->department,
                        'is_view' => $dnotif_count[0]->is_view,
                        'status' => $dnotif_count[0]->status,
                        'created_at' => $dnotif_count[0]->created_at,
                        'updated_at' => $dnotif_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('doctors_notification')->insert([
                    'notif_id' => $dnotif->notif_id,
                    'order_id' => $dnotif->order_id,
                    'patient_id' => $dnotif->patient_id,
                    'doctor_id' => $dnotif->doctor_id,
                    'category' => $dnotif->category,
                    'department' => $dnotif->department,
                    'is_view' => $dnotif->is_view,
                    'status' => $dnotif->status,
                    'created_at' => $dnotif->created_at,
                    'updated_at' => $dnotif->updated_at,
                ]);
            }
        }

        // syncronize doctors_notification table from online to offline
        $dnotification_online = DB::connection('mysql2')->table('doctors_notification')->get();
        foreach ($dnotification_online as $dnotif_online) {
            $dnotif_online_count = DB::table('doctors_notification')->where('notif_id', $dnotif_online->notif_id)->get();
            if (count($dnotif_online_count) > 0) {
                DB::table('doctors_notification')->where('notif_id', $dnotif_online->notif_id)->update([
                    'order_id' => $dnotif_online->order_id,
                    'patient_id' => $dnotif_online->patient_id,
                    'doctor_id' => $dnotif_online->doctor_id,
                    'category' => $dnotif_online->category,
                    'department' => $dnotif_online->department,
                    'is_view' => $dnotif_online->is_view,
                    'status' => $dnotif_online->status,
                    'created_at' => $dnotif_online->created_at,
                    'updated_at' => $dnotif_online->updated_at,
                ]);

            } else {
                DB::table('doctors_notification')->insert([
                    'notif_id' => $dnotif_online->notif_id,
                    'order_id' => $dnotif_online->order_id,
                    'patient_id' => $dnotif_online->patient_id,
                    'doctor_id' => $dnotif_online->doctor_id,
                    'category' => $dnotif_online->category,
                    'department' => $dnotif_online->department,
                    'is_view' => $dnotif_online->is_view,
                    'status' => $dnotif_online->status,
                    'created_at' => $dnotif_online->created_at,
                    'updated_at' => $dnotif_online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsPatients()
    {
        // syncronize doctors_patients table from offline to online
        $offline_query = DB::table('doctors_patients')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('doctors_patients')->where('dp_id', $offline->dp_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_patients')->where('dp_id', $offline->dp_id)->update([
                        'doctors_userid' => $offline->doctors_userid,
                        'patient_userid' => $offline->patient_userid,
                        'added_by' => $offline->added_by,
                        'added_from' => $offline->added_from,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_patients')->where('dp_id', $offline_count[0]->dp_id)->update([
                        'doctors_userid' => $offline_count[0]->doctors_userid,
                        'patient_userid' => $offline_count[0]->patient_userid,
                        'added_by' => $offline_count[0]->added_by,
                        'added_from' => $offline_count[0]->added_from,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('doctors_patients')->insert([
                    'dp_id' => $offline->dp_id,
                    'doctors_userid' => $offline->doctors_userid,
                    'patient_userid' => $offline->patient_userid,
                    'added_by' => $offline->added_by,
                    'added_from' => $offline->added_from,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize doctors_patients table from online to offline
        $online_query = DB::connection('mysql2')->table('doctors_patients')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('doctors_patients')->where('dp_id', $online->dp_id)->get();
            if (count($online_count) > 0) {
                DB::table('doctors_patients')->where('dp_id', $online->dp_id)->update([
                    'doctors_userid' => $online->doctors_userid,
                    'patient_userid' => $online->patient_userid,
                    'added_by' => $online->added_by,
                    'added_from' => $online->added_from,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);

            } else {
                DB::table('doctors_patients')->insert([
                    'dp_id' => $online->dp_id,
                    'doctors_userid' => $online->doctors_userid,
                    'patient_userid' => $online->patient_userid,
                    'added_by' => $online->added_by,
                    'added_from' => $online->added_from,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsPrescriptions()
    {
        // syncronize doctors_prescription table from offline to online
        $offline_query = DB::table('doctors_prescription')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('doctors_prescription')->where('dp_id', $offline->dp_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_prescription')->where('dp_id', $offline->dp_id)->update([
                        'prescription_id' => $offline->prescription_id,
                        'management_id' => $offline->management_id,
                        'patients_id' => $offline->patients_id,
                        'case_file' => $offline->case_file,
                        'doctors_id' => $offline->doctors_id,
                        'prescription' => $offline->prescription,
                        'product_name' => $offline->product_name,
                        'product_amount' => $offline->product_amount,
                        'is_package' => $offline->is_package,
                        'brand' => $offline->brand,
                        'quantity' => $offline->quantity,
                        'type' => $offline->type,
                        'dosage' => $offline->dosage,
                        'per_day' => $offline->per_day,
                        'per_take' => $offline->per_take,
                        'remarks' => $offline->remarks,
                        'prescription_type' => $offline->prescription_type,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'claim_id' => $offline->claim_id,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_prescription')->where('dp_id', $offline_count[0]->dp_id)->update([
                        'prescription_id' => $offline_count[0]->prescription_id,
                        'management_id' => $offline_count[0]->management_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'case_file' => $offline_count[0]->case_file,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'prescription' => $offline_count[0]->prescription,
                        'product_name' => $offline_count[0]->product_name,
                        'product_amount' => $offline_count[0]->product_amount,
                        'is_package' => $offline_count[0]->is_package,
                        'brand' => $offline_count[0]->brand,
                        'quantity' => $offline_count[0]->quantity,
                        'type' => $offline_count[0]->type,
                        'dosage' => $offline_count[0]->dosage,
                        'per_day' => $offline_count[0]->per_day,
                        'per_take' => $offline_count[0]->per_take,
                        'remarks' => $offline_count[0]->remarks,
                        'prescription_type' => $offline_count[0]->prescription_type,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'claim_id' => $offline_count[0]->claim_id,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('doctors_prescription')->insert([
                    'dp_id' => $offline->dp_id,
                    'prescription_id' => $offline->prescription_id,
                    'management_id' => $offline->management_id,
                    'patients_id' => $offline->patients_id,
                    'case_file' => $offline->case_file,
                    'doctors_id' => $offline->doctors_id,
                    'prescription' => $offline->prescription,
                    'product_name' => $offline->product_name,
                    'product_amount' => $offline->product_amount,
                    'is_package' => $offline->is_package,
                    'brand' => $offline->brand,
                    'quantity' => $offline->quantity,
                    'type' => $offline->type,
                    'dosage' => $offline->dosage,
                    'per_day' => $offline->per_day,
                    'per_take' => $offline->per_take,
                    'remarks' => $offline->remarks,
                    'prescription_type' => $offline->prescription_type,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'claim_id' => $offline->claim_id,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize doctors_prescription table from online to offline
        $online_query = DB::connection('mysql2')->table('doctors_prescription')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('doctors_prescription')->where('dp_id', $online->dp_id)->get();
            if (count($online_count) > 0) {
                DB::table('doctors_prescription')->where('dp_id', $online->dp_id)->update([
                    'prescription_id' => $online->prescription_id,
                    'management_id' => $online->management_id,
                    'patients_id' => $online->patients_id,
                    'case_file' => $online->case_file,
                    'doctors_id' => $online->doctors_id,
                    'prescription' => $online->prescription,
                    'product_name' => $online->product_name,
                    'product_amount' => $online->product_amount,
                    'is_package' => $online->is_package,
                    'brand' => $online->brand,
                    'quantity' => $online->quantity,
                    'type' => $online->type,
                    'dosage' => $online->dosage,
                    'per_day' => $online->per_day,
                    'per_take' => $online->per_take,
                    'remarks' => $online->remarks,
                    'prescription_type' => $online->prescription_type,
                    'pharmacy_id' => $online->pharmacy_id,
                    'claim_id' => $online->claim_id,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('doctors_prescription')->insert([
                    'dp_id' => $online->dp_id,
                    'prescription_id' => $online->prescription_id,
                    'management_id' => $online->management_id,
                    'patients_id' => $online->patients_id,
                    'case_file' => $online->case_file,
                    'doctors_id' => $online->doctors_id,
                    'prescription' => $online->prescription,
                    'product_name' => $online->product_name,
                    'product_amount' => $online->product_amount,
                    'is_package' => $online->is_package,
                    'brand' => $online->brand,
                    'quantity' => $online->quantity,
                    'type' => $online->type,
                    'dosage' => $online->dosage,
                    'per_day' => $online->per_day,
                    'per_take' => $online->per_take,
                    'remarks' => $online->remarks,
                    'prescription_type' => $online->prescription_type,
                    'pharmacy_id' => $online->pharmacy_id,
                    'claim_id' => $online->claim_id,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsSpecializationList()
    {
        // syncronize doctors_specialization_list table from offline to online
        $offline_query = DB::table('doctors_specialization_list')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('doctors_specialization_list')->where('spec_id', $offline->spec_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_specialization_list')->where('spec_id', $offline->spec_id)->update([
                        'specialization' => $offline->specialization,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_specialization_list')->where('spec_id', $offline_count[0]->spec_id)->update([
                        'specialization' => $offline_count[0]->specialization,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('doctors_specialization_list')->insert([
                    'spec_id' => $offline->spec_id,
                    'specialization' => $offline->specialization,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize doctors_specialization_list table from online to offline
        $online_query = DB::connection('mysql2')->table('doctors_specialization_list')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('doctors_specialization_list')->where('spec_id', $online->spec_id)->get();
            if (count($online_count) > 0) {
                DB::table('doctors_specialization_list')->where('spec_id', $online->spec_id)->update([
                    'specialization' => $online->specialization,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('doctors_specialization_list')->insert([
                    'spec_id' => $online->spec_id,
                    'specialization' => $online->specialization,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsTreatmentPlan()
    {
        // syncronize doctors_treatment_plan table from offline to online
        $offline_query = DB::table('doctors_treatment_plan')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('doctors_treatment_plan')->where('dtp_id', $offline->dtp_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_treatment_plan')->where('dtp_id', $offline->dtp_id)->update([
                        'treatment_id' => $offline->treatment_id,
                        'management_id' => $offline->management_id,
                        'doctors_id' => $offline->doctors_id,
                        'patient_id' => $offline->patient_id,
                        'treatment_plan' => $offline->treatment_plan,
                        'date' => $offline->date,
                        'type' => $offline->type,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_treatment_plan')->where('dtp_id', $offline_count[0]->dtp_id)->update([
                        'treatment_id' => $offline_count[0]->treatment_id,
                        'management_id' => $offline_count[0]->management_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'treatment_plan' => $offline_count[0]->treatment_plan,
                        'date' => $offline_count[0]->date,
                        'type' => $offline_count[0]->type,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('doctors_treatment_plan')->insert([
                    'dtp_id' => $offline->dtp_id,
                    'treatment_id' => $offline->treatment_id,
                    'management_id' => $offline->management_id,
                    'doctors_id' => $offline->doctors_id,
                    'patient_id' => $offline->patient_id,
                    'treatment_plan' => $offline->treatment_plan,
                    'date' => $offline->date,
                    'type' => $offline->type,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize doctors_treatment_plan table from online to offline
        $online_query = DB::connection('mysql2')->table('doctors_treatment_plan')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('doctors_treatment_plan')->where('dtp_id', $online->dtp_id)->get();
            if (count($online_count) > 0) {
                DB::table('doctors_treatment_plan')->where('dtp_id', $online->dtp_id)->update([
                    'treatment_id' => $online->treatment_id,
                    'management_id' => $online->management_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'treatment_plan' => $online->treatment_plan,
                    'date' => $online->date,
                    'type' => $online->type,
                    'status' => $online->status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('doctors_treatment_plan')->insert([
                    'dtp_id' => $online->dtp_id,
                    'treatment_id' => $online->treatment_id,
                    'management_id' => $online->management_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'treatment_plan' => $online->treatment_plan,
                    'date' => $online->date,
                    'type' => $online->type,
                    'status' => $online->status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncEncoder()
    {
        // syncronize appointment_settings table from offline to online
        $offline_query = DB::table('encoder')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('encoder')->where('encoder_id', $offline->encoder_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('encoder')->where('encoder_id', $offline->encoder_id)->update([
                        'doctors_id' => $offline->doctors_id,
                        'management_id' => $offline->management_id,
                        'user_id' => $offline->user_id,
                        'name' => $offline->name,
                        'address' => $offline->address,
                        'gender' => $offline->gender,
                        'birthday' => $offline->birthday,
                        'image' => $offline->image,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('encoder')->where('encoder_id', $offline_count[0]->encoder_id)->update([
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'management_id' => $offline_count[0]->management_id,
                        'user_id' => $offline_count[0]->user_id,
                        'name' => $offline_count[0]->name,
                        'address' => $offline_count[0]->address,
                        'gender' => $offline_count[0]->gender,
                        'birthday' => $offline_count[0]->birthday,
                        'image' => $offline_count[0]->image,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('encoder')->insert([
                    'encoder_id' => $offline->encoder_id,
                    'doctors_id' => $offline->doctors_id,
                    'management_id' => $offline->management_id,
                    'user_id' => $offline->user_id,
                    'name' => $offline->name,
                    'address' => $offline->address,
                    'gender' => $offline->gender,
                    'birthday' => $offline->birthday,
                    'image' => $offline->image,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $online_query = DB::connection('mysql2')->table('encoder')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('encoder')->where('encoder_id', $online->encoder_id)->get();
            if (count($online_count) > 0) {
                DB::table('encoder')->where('encoder_id', $online->encoder_id)->update([
                    'doctors_id' => $online->doctors_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'image' => $online->image,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('encoder')->insert([
                    'encoder_id' => $online->encoder_id,
                    'doctors_id' => $online->doctors_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'image' => $online->image,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncEncoderPatientBillsRecord()
    {
        // syncronize appointment_settings table from offline to online
        $offline_query = DB::table('encoder_patientbills_records')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('encoder_patientbills_records')->where('epr_id', $offline->epr_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('encoder_patientbills_records')->where('epr_id', $offline->epr_id)->update([
                        'trace_number' => $offline->trace_number,
                        'management_id' => $offline->management_id,
                        'doctors_id' => $offline->doctors_id,
                        'patient_id' => $offline->patient_id,
                        'bill_name' => $offline->bill_name,
                        'bill_amount' => $offline->bill_amount,
                        'bill_from' => $offline->bill_from,
                        'bill_payment' => $offline->bill_payment,
                        'bill_department' => $offline->bill_department,
                        'bill_total' => $offline->bill_total,
                        'process_by' => $offline->process_by,
                        'receipt_number' => $offline->receipt_number,
                        'order_id' => $offline->order_id,
                        'is_refund' => $offline->is_refund,
                        'is_refund_reason' => $offline->is_refund_reason,
                        'is_refund_date' => $offline->is_refund_date,
                        'is_refund_by' => $offline->is_refund_by,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('encoder_patientbills_records')->where('epr_id', $offline_count[0]->epr_id)->update([
                        'trace_number' => $offline_count[0]->trace_number,
                        'management_id' => $offline_count[0]->management_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'bill_name' => $offline_count[0]->bill_name,
                        'bill_amount' => $offline_count[0]->bill_amount,
                        'bill_from' => $offline_count[0]->bill_from,
                        'bill_payment' => $offline_count[0]->bill_payment,
                        'bill_department' => $offline_count[0]->bill_department,
                        'bill_total' => $offline_count[0]->bill_total,
                        'process_by' => $offline_count[0]->process_by,
                        'receipt_number' => $offline_count[0]->receipt_number,
                        'order_id' => $offline_count[0]->order_id,
                        'is_refund' => $offline_count[0]->is_refund,
                        'is_refund_reason' => $offline_count[0]->is_refund_reason,
                        'is_refund_date' => $offline_count[0]->is_refund_date,
                        'is_refund_by' => $offline_count[0]->is_refund_by,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('encoder_patientbills_records')->insert([
                    'epr_id' => $offline->epr_id,
                    'trace_number' => $offline->trace_number,
                    'management_id' => $offline->management_id,
                    'doctors_id' => $offline->doctors_id,
                    'patient_id' => $offline->patient_id,
                    'bill_name' => $offline->bill_name,
                    'bill_amount' => $offline->bill_amount,
                    'bill_from' => $offline->bill_from,
                    'bill_payment' => $offline->bill_payment,
                    'bill_department' => $offline->bill_department,
                    'bill_total' => $offline->bill_total,
                    'process_by' => $offline->process_by,
                    'receipt_number' => $offline->receipt_number,
                    'order_id' => $offline->order_id,
                    'is_refund' => $offline->is_refund,
                    'is_refund_reason' => $offline->is_refund_reason,
                    'is_refund_date' => $offline->is_refund_date,
                    'is_refund_by' => $offline->is_refund_by,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $online_query = DB::connection('mysql2')->table('encoder_patientbills_records')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('encoder_patientbills_records')->where('epr_id', $online->epr_id)->get();
            if (count($online_count) > 0) {
                DB::table('encoder_patientbills_records')->where('epr_id', $online->epr_id)->update([
                    'trace_number' => $online->trace_number,
                    'management_id' => $online->management_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'bill_name' => $online->bill_name,
                    'bill_amount' => $online->bill_amount,
                    'bill_from' => $online->bill_from,
                    'bill_payment' => $online->bill_payment,
                    'bill_department' => $online->bill_department,
                    'bill_total' => $online->bill_total,
                    'process_by' => $online->process_by,
                    'receipt_number' => $online->receipt_number,
                    'order_id' => $online->order_id,
                    'is_refund' => $online->is_refund,
                    'is_refund_reason' => $online->is_refund_reason,
                    'is_refund_date' => $online->is_refund_date,
                    'is_refund_by' => $online->is_refund_by,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('encoder_patientbills_records')->insert([
                    'epr_id' => $online->epr_id,
                    'trace_number' => $online->trace_number,
                    'management_id' => $online->management_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'bill_name' => $online->bill_name,
                    'bill_amount' => $online->bill_amount,
                    'bill_from' => $online->bill_from,
                    'bill_payment' => $online->bill_payment,
                    'bill_department' => $online->bill_department,
                    'bill_total' => $online->bill_total,
                    'process_by' => $online->process_by,
                    'receipt_number' => $online->receipt_number,
                    'order_id' => $online->order_id,
                    'is_refund' => $online->is_refund,
                    'is_refund_reason' => $online->is_refund_reason,
                    'is_refund_date' => $online->is_refund_date,
                    'is_refund_by' => $online->is_refund_by,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncEncoderPatientBillsUnpaid()
    {
        // syncronize appointment_settings table from offline to online
        $offline_query = DB::table('encoder_patientbills_unpaid')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('encoder_patientbills_unpaid')->where('epb_id', $offline->epb_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('encoder_patientbills_unpaid')->where('epb_id', $offline->epb_id)->update([
                        'trace_number' => $offline->trace_number,
                        'doctors_id' => $offline->doctors_id,
                        'patient_id' => $offline->patient_id,
                        'bill_name' => $offline->bill_name,
                        'bill_amount' => $offline->bill_amount,
                        'bill_department' => $offline->bill_department,
                        'bill_from' => $offline->bill_from,
                        'order_id' => $offline->order_id,
                        'remarks' => $offline->remarks,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('encoder_patientbills_unpaid')->where('epb_id', $offline_count[0]->epb_id)->update([
                        'trace_number' => $offline_count[0]->trace_number,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'bill_name' => $offline_count[0]->bill_name,
                        'bill_amount' => $offline_count[0]->bill_amount,
                        'bill_department' => $offline_count[0]->bill_department,
                        'bill_from' => $offline_count[0]->bill_from,
                        'order_id' => $offline_count[0]->order_id,
                        'remarks' => $offline_count[0]->remarks,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }

            } else {
                DB::connection('mysql2')->table('encoder_patientbills_unpaid')->insert([
                    'epb_id' => $offline->epb_id,
                    'trace_number' => $offline->trace_number,
                    'doctors_id' => $offline->doctors_id,
                    'patient_id' => $offline->patient_id,
                    'bill_name' => $offline->bill_name,
                    'bill_amount' => $offline->bill_amount,
                    'bill_department' => $offline->bill_department,
                    'bill_from' => $offline->bill_from,
                    'order_id' => $offline->order_id,
                    'remarks' => $offline->remarks,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $online_query = DB::connection('mysql2')->table('encoder_patientbills_unpaid')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('encoder_patientbills_unpaid')->where('epb_id', $online->epb_id)->get();
            if (count($online_count) > 0) {
                DB::table('encoder_patientbills_unpaid')->where('epb_id', $online->epb_id)->update([
                    'trace_number' => $online->trace_number,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'bill_name' => $online->bill_name,
                    'bill_amount' => $online->bill_amount,
                    'bill_department' => $online->bill_department,
                    'bill_from' => $online->bill_from,
                    'order_id' => $online->order_id,
                    'remarks' => $online->remarks,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('encoder_patientbills_unpaid')->insert([
                    'epb_id' => $online->epb_id,
                    'trace_number' => $online->trace_number,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'bill_name' => $online->bill_name,
                    'bill_amount' => $online->bill_amount,
                    'bill_department' => $online->bill_department,
                    'bill_from' => $online->bill_from,
                    'order_id' => $online->order_id,
                    'remarks' => $online->remarks,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncImaging()
    {
        // syncronize appointment_settings table from offline to online
        $imaging_offline_List = DB::table('imaging')->get();
        foreach ($imaging_offline_List as $imaging_offline) {
            $imaging_offline_count = DB::connection('mysql2')->table('imaging')->where('i_id', $imaging_offline->i_id)->get();
            if (count($imaging_offline_count) > 0) {
                if ($imaging_offline->updated_at > $imaging_offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('imaging')->where('i_id', $imaging_offline->i_id)->update([
                        'imaging_id' => $imaging_offline->imaging_id,
                        'management_id' => $imaging_offline->management_id,
                        'user_id' => $imaging_offline->user_id,
                        'name' => $imaging_offline->name,
                        'gender' => $imaging_offline->gender,
                        'birthday' => $imaging_offline->birthday,
                        'address' => $imaging_offline->address,
                        'role' => $imaging_offline->role,
                        'added_by' => $imaging_offline->added_by,
                        'address' => $imaging_offline->address,
                        'created_at' => $imaging_offline->created_at,
                        'updated_at' => $imaging_offline->updated_at,
                    ]);
                } else {
                    DB::table('imaging')->where('i_id', $imaging_offline_count[0]->i_id)->update([
                        'imaging_id' => $imaging_offline_count[0]->imaging_id,
                        'management_id' => $imaging_offline_count[0]->management_id,
                        'user_id' => $imaging_offline_count[0]->user_id,
                        'name' => $imaging_offline_count[0]->name,
                        'gender' => $imaging_offline_count[0]->gender,
                        'birthday' => $imaging_offline_count[0]->birthday,
                        'address' => $imaging_offline_count[0]->address,
                        'role' => $imaging_offline_count[0]->role,
                        'added_by' => $imaging_offline_count[0]->added_by,
                        'address' => $imaging_offline_count[0]->address,
                        'created_at' => $imaging_offline_count[0]->created_at,
                        'updated_at' => $imaging_offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('imaging')->insert([
                    'i_id' => $imaging_offline->i_id,
                    'imaging_id' => $imaging_offline->imaging_id,
                    'management_id' => $imaging_offline->management_id,
                    'user_id' => $imaging_offline->user_id,
                    'name' => $imaging_offline->name,
                    'gender' => $imaging_offline->gender,
                    'birthday' => $imaging_offline->birthday,
                    'address' => $imaging_offline->address,
                    'role' => $imaging_offline->role,
                    'added_by' => $imaging_offline->added_by,
                    'address' => $imaging_offline->address,
                    'created_at' => $imaging_offline->created_at,
                    'updated_at' => $imaging_offline->updated_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $imaging_online_List = DB::connection('mysql2')->table('imaging')->get();
        foreach ($imaging_online_List as $imaging_online) {
            $imaging_online_count = DB::table('imaging')->where('i_id', $imaging_online->i_id)->get();
            if (count($imaging_online_count) > 0) {
                DB::table('imaging')->where('i_id', $imaging_online->i_id)->update([
                    'imaging_id' => $imaging_online->imaging_id,
                    'management_id' => $imaging_online->management_id,
                    'user_id' => $imaging_online->user_id,
                    'name' => $imaging_online->name,
                    'gender' => $imaging_online->gender,
                    'birthday' => $imaging_online->birthday,
                    'address' => $imaging_online->address,
                    'role' => $imaging_online->role,
                    'added_by' => $imaging_online->added_by,
                    'address' => $imaging_online->address,
                    'created_at' => $imaging_online->created_at,
                    'updated_at' => $imaging_online->updated_at,
                ]);
            } else {
                DB::table('imaging')->insert([
                    'i_id' => $imaging_online->i_id,
                    'imaging_id' => $imaging_online->imaging_id,
                    'management_id' => $imaging_online->management_id,
                    'user_id' => $imaging_online->user_id,
                    'name' => $imaging_online->name,
                    'gender' => $imaging_online->gender,
                    'birthday' => $imaging_online->birthday,
                    'address' => $imaging_online->address,
                    'role' => $imaging_online->role,
                    'added_by' => $imaging_online->added_by,
                    'address' => $imaging_online->address,
                    'created_at' => $imaging_online->created_at,
                    'updated_at' => $imaging_online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncImagingCenter()
    {
        // syncronize imaging_center table from offline to online
        $offline_query = DB::table('imaging_center')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id', $offline->imaging_center_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('imaging_center')->where('imaging_center_id', $offline->imaging_center_id)->update([
                        'patients_id' => $offline->patients_id,
                        'doctors_id' => $offline->doctors_id,
                        'ward_nurse_id' => $offline->ward_nurse_id,
                        'case_file' => $offline->case_file,
                        'radiologist' => $offline->radiologist,
                        'radiologist_type' => $offline->radiologist_type,
                        'request_ward' => $offline->request_ward,
                        'request_doctor' => $offline->request_doctor,
                        'charge_slip' => $offline->charge_slip,
                        'additional_charge_slip' => $offline->additional_charge_slip,
                        'number_shots' => $offline->number_shots,
                        'additional_number_shots' => $offline->additional_number_shots,
                        'imaging_order' => $offline->imaging_order,
                        'imaging_remarks' => $offline->imaging_remarks,
                        'imaging_center' => $offline->imaging_center,
                        'imaging_result' => $offline->imaging_result,
                        'imaging_result_screenshot' => $offline->imaging_result_screenshot,
                        'imaging_results_remarks' => $offline->imaging_results_remarks,
                        'imaging_result_attachment' => $offline->imaging_result_attachment,
                        'stitch_order_request' => $offline->stitch_order_request,
                        'stitch_reason_request' => $offline->stitch_reason_request,
                        'stitch_result_attachment' => $offline->stitch_result_attachment,
                        'is_viewed' => $offline->is_viewed,
                        'is_processed' => $offline->is_processed,
                        'processed_by' => $offline->processed_by,
                        'start_time' => $offline->start_time,
                        'end_time' => $offline->end_time,
                        'is_pending' => $offline->is_pending,
                        'pending_reason' => $offline->pending_reason,
                        'pending_date' => $offline->pending_date,
                        'pending_by' => $offline->pending_by,
                        'manage_by' => $offline->manage_by,
                        'order_from' => $offline->order_from,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('imaging_center')->where('imaging_center_id', $offline_count[0]->imaging_center_id)->update([
                        'patients_id' => $offline_count[0]->patients_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'ward_nurse_id' => $offline_count[0]->ward_nurse_id,
                        'case_file' => $offline_count[0]->case_file,
                        'radiologist' => $offline_count[0]->radiologist,
                        'radiologist_type' => $offline_count[0]->radiologist_type,
                        'request_ward' => $offline_count[0]->request_ward,
                        'request_doctor' => $offline_count[0]->request_doctor,
                        'charge_slip' => $offline_count[0]->charge_slip,
                        'additional_charge_slip' => $offline_count[0]->additional_charge_slip,
                        'number_shots' => $offline_count[0]->number_shots,
                        'additional_number_shots' => $offline_count[0]->additional_number_shots,
                        'imaging_order' => $offline_count[0]->imaging_order,
                        'imaging_remarks' => $offline_count[0]->imaging_remarks,
                        'imaging_center' => $offline_count[0]->imaging_center,
                        'imaging_result' => $offline_count[0]->imaging_result,
                        'imaging_result_screenshot' => $offline_count[0]->imaging_result_screenshot,
                        'imaging_results_remarks' => $offline_count[0]->imaging_results_remarks,
                        'imaging_result_attachment' => $offline_count[0]->imaging_result_attachment,
                        'stitch_order_request' => $offline_count[0]->stitch_order_request,
                        'stitch_reason_request' => $offline_count[0]->stitch_reason_request,
                        'stitch_result_attachment' => $offline_count[0]->stitch_result_attachment,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'is_processed' => $offline_count[0]->is_processed,
                        'processed_by' => $offline_count[0]->processed_by,
                        'start_time' => $offline_count[0]->start_time,
                        'end_time' => $offline_count[0]->end_time,
                        'is_pending' => $offline_count[0]->is_pending,
                        'pending_reason' => $offline_count[0]->pending_reason,
                        'pending_date' => $offline_count[0]->pending_date,
                        'pending_by' => $offline_count[0]->pending_by,
                        'manage_by' => $offline_count[0]->manage_by,
                        'order_from' => $offline_count[0]->order_from,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('imaging_center')->insert([
                    'imaging_center_id' => $offline->imaging_center_id,
                    'patients_id' => $offline->patients_id,
                    'doctors_id' => $offline->doctors_id,
                    'ward_nurse_id' => $offline->ward_nurse_id,
                    'case_file' => $offline->case_file,
                    'radiologist' => $offline->radiologist,
                    'radiologist_type' => $offline->radiologist_type,
                    'request_ward' => $offline->request_ward,
                    'request_doctor' => $offline->request_doctor,
                    'charge_slip' => $offline->charge_slip,
                    'additional_charge_slip' => $offline->additional_charge_slip,
                    'number_shots' => $offline->number_shots,
                    'additional_number_shots' => $offline->additional_number_shots,
                    'imaging_order' => $offline->imaging_order,
                    'imaging_remarks' => $offline->imaging_remarks,
                    'imaging_center' => $offline->imaging_center,
                    'imaging_result' => $offline->imaging_result,
                    'imaging_result_screenshot' => $offline->imaging_result_screenshot,
                    'imaging_results_remarks' => $offline->imaging_results_remarks,
                    'imaging_result_attachment' => $offline->imaging_result_attachment,
                    'stitch_order_request' => $offline->stitch_order_request,
                    'stitch_reason_request' => $offline->stitch_reason_request,
                    'stitch_result_attachment' => $offline->stitch_result_attachment,
                    'is_viewed' => $offline->is_viewed,
                    'is_processed' => $offline->is_processed,
                    'processed_by' => $offline->processed_by,
                    'start_time' => $offline->start_time,
                    'end_time' => $offline->end_time,
                    'is_pending' => $offline->is_pending,
                    'pending_reason' => $offline->pending_reason,
                    'pending_date' => $offline->pending_date,
                    'pending_by' => $offline->pending_by,
                    'manage_by' => $offline->manage_by,
                    'order_from' => $offline->order_from,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize imaging_center table from online to offline
        $online_query = DB::connection('mysql2')->table('imaging_center')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('imaging_center')->where('imaging_center_id', $online->imaging_center_id)->get();
            if (count($online_count) > 0) {
                DB::table('imaging_center')->where('imaging_center_id', $online->imaging_center_id)->update([
                    'patients_id' => $online->patients_id,
                    'doctors_id' => $online->doctors_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'radiologist' => $online->radiologist,
                    'radiologist_type' => $online->radiologist_type,
                    'request_ward' => $online->request_ward,
                    'request_doctor' => $online->request_doctor,
                    'charge_slip' => $online->charge_slip,
                    'additional_charge_slip' => $online->additional_charge_slip,
                    'number_shots' => $online->number_shots,
                    'additional_number_shots' => $online->additional_number_shots,
                    'imaging_order' => $online->imaging_order,
                    'imaging_remarks' => $online->imaging_remarks,
                    'imaging_center' => $online->imaging_center,
                    'imaging_result' => $online->imaging_result,
                    'imaging_result_screenshot' => $online->imaging_result_screenshot,
                    'imaging_results_remarks' => $online->imaging_results_remarks,
                    'imaging_result_attachment' => $online->imaging_result_attachment,
                    'stitch_order_request' => $online->stitch_order_request,
                    'stitch_reason_request' => $online->stitch_reason_request,
                    'stitch_result_attachment' => $online->stitch_result_attachment,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'processed_by' => $online->processed_by,
                    'start_time' => $online->start_time,
                    'end_time' => $online->end_time,
                    'is_pending' => $online->is_pending,
                    'pending_reason' => $online->pending_reason,
                    'pending_date' => $online->pending_date,
                    'pending_by' => $online->pending_by,
                    'manage_by' => $online->manage_by,
                    'order_from' => $online->order_from,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('imaging_center')->insert([
                    'imaging_center_id' => $online->imaging_center_id,
                    'patients_id' => $online->patients_id,
                    'doctors_id' => $online->doctors_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'radiologist' => $online->radiologist,
                    'radiologist_type' => $online->radiologist_type,
                    'request_ward' => $online->request_ward,
                    'request_doctor' => $online->request_doctor,
                    'charge_slip' => $online->charge_slip,
                    'additional_charge_slip' => $online->additional_charge_slip,
                    'number_shots' => $online->number_shots,
                    'additional_number_shots' => $online->additional_number_shots,
                    'imaging_order' => $online->imaging_order,
                    'imaging_remarks' => $online->imaging_remarks,
                    'imaging_center' => $online->imaging_center,
                    'imaging_result' => $online->imaging_result,
                    'imaging_result_screenshot' => $online->imaging_result_screenshot,
                    'imaging_results_remarks' => $online->imaging_results_remarks,
                    'imaging_result_attachment' => $online->imaging_result_attachment,
                    'stitch_order_request' => $online->stitch_order_request,
                    'stitch_reason_request' => $online->stitch_reason_request,
                    'stitch_result_attachment' => $online->stitch_result_attachment,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'processed_by' => $online->processed_by,
                    'start_time' => $online->start_time,
                    'end_time' => $online->end_time,
                    'is_pending' => $online->is_pending,
                    'pending_reason' => $online->pending_reason,
                    'pending_date' => $online->pending_date,
                    'pending_by' => $online->pending_by,
                    'manage_by' => $online->manage_by,
                    'order_from' => $online->order_from,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncImagingCenterRecord()
    {
        // syncronize imaging_center_record table from offline to online
        $offline_query = DB::table('imaging_center_record')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('imaging_center_record')->where('transaction_id', $offline->transaction_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('imaging_center_record')->where('transaction_id', $offline->transaction_id)->update([
                        'patients_id' => $offline->patients_id,
                        'case_file' => $offline->case_file,
                        'imaging_order' => $offline->imaging_order,
                        'processed_by' => $offline->processed_by,
                        'order_type' => $offline->order_type,
                        'amount' => $offline->amount,
                        'record_from' => $offline->record_from,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('imaging_center_record')->where('transaction_id', $offline_count[0]->transaction_id)->update([
                        'patients_id' => $offline_count[0]->patients_id,
                        'case_file' => $offline_count[0]->case_file,
                        'imaging_order' => $offline_count[0]->imaging_order,
                        'processed_by' => $offline_count[0]->processed_by,
                        'order_type' => $offline_count[0]->order_type,
                        'amount' => $offline_count[0]->amount,
                        'record_from' => $offline_count[0]->record_from,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('imaging_center_record')->insert([
                    'transaction_id' => $offline->transaction_id,
                    'patients_id' => $offline->patients_id,
                    'case_file' => $offline->case_file,
                    'imaging_order' => $offline->imaging_order,
                    'processed_by' => $offline->processed_by,
                    'order_type' => $offline->order_type,
                    'amount' => $offline->amount,
                    'record_from' => $offline->record_from,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize imaging_center_record table from online to offline
        $online_query = DB::connection('mysql2')->table('imaging_center_record')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('imaging_center_record')->where('transaction_id', $online->transaction_id)->get();
            if (count($online_count) > 0) {
                DB::table('imaging_center_record')->where('transaction_id', $online->transaction_id)->update([
                    'patients_id' => $online->patients_id,
                    'case_file' => $online->case_file,
                    'imaging_order' => $online->imaging_order,
                    'processed_by' => $online->processed_by,
                    'order_type' => $online->order_type,
                    'amount' => $online->amount,
                    'record_from' => $online->record_from,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('imaging_center_record')->insert([
                    'transaction_id' => $online->transaction_id,
                    'patients_id' => $online->patients_id,
                    'case_file' => $online->case_file,
                    'imaging_order' => $online->imaging_order,
                    'processed_by' => $online->processed_by,
                    'order_type' => $online->order_type,
                    'amount' => $online->amount,
                    'record_from' => $online->record_from,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratory()
    {
        // syncronize laboratory table from offline to online
        $offline_query = DB::table('laboratory')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory')->where('lab_id', $offline->lab_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory')->where('lab_id', $offline->lab_id)->update([
                        'laboratory_id' => $offline->laboratory_id,
                        'patients_id' => $offline->patients_id,
                        'doctors_id' => $offline->doctors_id,
                        'ward_nurse_id' => $offline->ward_nurse_id,
                        'case_file' => $offline->case_file,
                        'doctors_remarks' => $offline->doctors_remarks,
                        'laboratory_orders' => $offline->laboratory_orders,
                        'laboratory_results' => $offline->laboratory_results,
                        'laboratory_result_image' => $offline->laboratory_result_image,
                        'laboratory_remarks' => $offline->laboratory_remarks,
                        'laboratory_attachment' => $offline->laboratory_attachment,
                        'is_viewed' => $offline->is_viewed,
                        'is_processed' => $offline->is_processed,
                        'processed_by' => $offline->processed_by,
                        'start_time' => $offline->start_time,
                        'time_end' => $offline->time_end,
                        'is_pending' => $offline->is_pending,
                        'pending_reason' => $offline->pending_reason,
                        'pending_date' => $offline->pending_date,
                        'pending_by' => $offline->pending_by,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory')->where('lab_id', $offline_count[0]->lab_id)->update([
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'ward_nurse_id' => $offline_count[0]->ward_nurse_id,
                        'case_file' => $offline_count[0]->case_file,
                        'doctors_remarks' => $offline_count[0]->doctors_remarks,
                        'laboratory_orders' => $offline_count[0]->laboratory_orders,
                        'laboratory_results' => $offline_count[0]->laboratory_results,
                        'laboratory_result_image' => $offline_count[0]->laboratory_result_image,
                        'laboratory_remarks' => $offline_count[0]->laboratory_remarks,
                        'laboratory_attachment' => $offline_count[0]->laboratory_attachment,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'is_processed' => $offline_count[0]->is_processed,
                        'processed_by' => $offline_count[0]->processed_by,
                        'start_time' => $offline_count[0]->start_time,
                        'time_end' => $offline_count[0]->time_end,
                        'is_pending' => $offline_count[0]->is_pending,
                        'pending_reason' => $offline_count[0]->pending_reason,
                        'pending_date' => $offline_count[0]->pending_date,
                        'pending_by' => $offline_count[0]->pending_by,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('laboratory')->insert([
                    'lab_id' => $offline->lab_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'patients_id' => $offline->patients_id,
                    'doctors_id' => $offline->doctors_id,
                    'ward_nurse_id' => $offline->ward_nurse_id,
                    'case_file' => $offline->case_file,
                    'doctors_remarks' => $offline->doctors_remarks,
                    'laboratory_orders' => $offline->laboratory_orders,
                    'laboratory_results' => $offline->laboratory_results,
                    'laboratory_result_image' => $offline->laboratory_result_image,
                    'laboratory_remarks' => $offline->laboratory_remarks,
                    'laboratory_attachment' => $offline->laboratory_attachment,
                    'is_viewed' => $offline->is_viewed,
                    'is_processed' => $offline->is_processed,
                    'processed_by' => $offline->processed_by,
                    'start_time' => $offline->start_time,
                    'time_end' => $offline->time_end,
                    'is_pending' => $offline->is_pending,
                    'pending_reason' => $offline->pending_reason,
                    'pending_date' => $offline->pending_date,
                    'pending_by' => $offline->pending_by,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory table from online to offline
        $online_query = DB::connection('mysql2')->table('laboratory')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('laboratory')->where('lab_id', $online->lab_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory')->where('lab_id', $online->lab_id)->update([
                    'laboratory_id' => $online->laboratory_id,
                    'patients_id' => $online->patients_id,
                    'doctors_id' => $online->doctors_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'doctors_remarks' => $online->doctors_remarks,
                    'laboratory_orders' => $online->laboratory_orders,
                    'laboratory_results' => $online->laboratory_results,
                    'laboratory_result_image' => $online->laboratory_result_image,
                    'laboratory_remarks' => $online->laboratory_remarks,
                    'laboratory_attachment' => $online->laboratory_attachment,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'processed_by' => $online->processed_by,
                    'start_time' => $online->start_time,
                    'time_end' => $online->time_end,
                    'is_pending' => $online->is_pending,
                    'pending_reason' => $online->pending_reason,
                    'pending_date' => $online->pending_date,
                    'pending_by' => $online->pending_by,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('laboratory')->insert([
                    'lab_id' => $online->lab_id,
                    'laboratory_id' => $online->laboratory_id,
                    'patients_id' => $online->patients_id,
                    'doctors_id' => $online->doctors_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'doctors_remarks' => $online->doctors_remarks,
                    'laboratory_orders' => $online->laboratory_orders,
                    'laboratory_results' => $online->laboratory_results,
                    'laboratory_result_image' => $online->laboratory_result_image,
                    'laboratory_remarks' => $online->laboratory_remarks,
                    'laboratory_attachment' => $online->laboratory_attachment,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'processed_by' => $online->processed_by,
                    'start_time' => $online->start_time,
                    'time_end' => $online->time_end,
                    'is_pending' => $online->is_pending,
                    'pending_reason' => $online->pending_reason,
                    'pending_date' => $online->pending_date,
                    'pending_by' => $online->pending_by,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratoryChemistry()
    {
        // syncronize laboratory_chemistry table from offline to online
        $offline_query = DB::table('laboratory_chemistry')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_chemistry')->where('lc_id', $offline->lc_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_chemistry')->where('lc_id', $offline->lc_id)->update([
                        'order_id' => $offline->order_id,
                        'doctor_id' => $offline->doctor_id,
                        'patient_id' => $offline->patient_id,
                        'laboratory_id' => $offline->laboratory_id,
                        'ward_nurse_id' => $offline->ward_nurse_id,
                        'case_file' => $offline->case_file,
                        'is_viewed' => $offline->is_viewed,
                        'is_processed' => $offline->is_processed,
                        'is_processed_by' => $offline->is_processed_by,
                        'is_processed_time_start' => $offline->is_processed_time_start,
                        'is_processed_time_end' => $offline->is_processed_time_end,
                        'is_pending' => $offline->is_pending,
                        'is_pending_reason' => $offline->is_pending_reason,
                        'is_pending_date' => $offline->is_pending_date,
                        'is_pending_by' => $offline->is_pending_by,
                        'spicemen' => $offline->spicemen,
                        'glucose' => $offline->glucose,
                        'creatinine' => $offline->creatinine,
                        'uric_acid' => $offline->uric_acid,
                        'cholesterol' => $offline->cholesterol,
                        'triglyceride' => $offline->triglyceride,
                        'hdl_cholesterol' => $offline->hdl_cholesterol,
                        'ldl_cholesterol' => $offline->ldl_cholesterol,
                        'sgot' => $offline->sgot,
                        'sgpt' => $offline->sgpt,
                        'remarks' => $offline->remarks,
                        'order_status' => $offline->order_status,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_chemistry')->where('lc_id', $offline_count[0]->lc_id)->update([
                        'order_id' => $offline_count[0]->order_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'ward_nurse_id' => $offline_count[0]->ward_nurse_id,
                        'case_file' => $offline_count[0]->case_file,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'is_processed' => $offline_count[0]->is_processed,
                        'is_processed_by' => $offline_count[0]->is_processed_by,
                        'is_processed_time_start' => $offline_count[0]->is_processed_time_start,
                        'is_processed_time_end' => $offline_count[0]->is_processed_time_end,
                        'is_pending' => $offline_count[0]->is_pending,
                        'is_pending_reason' => $offline_count[0]->is_pending_reason,
                        'is_pending_date' => $offline_count[0]->is_pending_date,
                        'is_pending_by' => $offline_count[0]->is_pending_by,
                        'spicemen' => $offline_count[0]->spicemen,
                        'glucose' => $offline_count[0]->glucose,
                        'creatinine' => $offline_count[0]->creatinine,
                        'uric_acid' => $offline_count[0]->uric_acid,
                        'cholesterol' => $offline_count[0]->cholesterol,
                        'triglyceride' => $offline_count[0]->triglyceride,
                        'hdl_cholesterol' => $offline_count[0]->hdl_cholesterol,
                        'ldl_cholesterol' => $offline_count[0]->ldl_cholesterol,
                        'sgot' => $offline_count[0]->sgot,
                        'sgpt' => $offline_count[0]->sgpt,
                        'remarks' => $offline_count[0]->remarks,
                        'order_status' => $offline_count[0]->order_status,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('laboratory_chemistry')->insert([
                    'lc_id' => $offline->lc_id,
                    'order_id' => $offline->order_id,
                    'doctor_id' => $offline->doctor_id,
                    'patient_id' => $offline->patient_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'ward_nurse_id' => $offline->ward_nurse_id,
                    'case_file' => $offline->case_file,
                    'is_viewed' => $offline->is_viewed,
                    'is_processed' => $offline->is_processed,
                    'is_processed_by' => $offline->is_processed_by,
                    'is_processed_time_start' => $offline->is_processed_time_start,
                    'is_processed_time_end' => $offline->is_processed_time_end,
                    'is_pending' => $offline->is_pending,
                    'is_pending_reason' => $offline->is_pending_reason,
                    'is_pending_date' => $offline->is_pending_date,
                    'is_pending_by' => $offline->is_pending_by,
                    'spicemen' => $offline->spicemen,
                    'glucose' => $offline->glucose,
                    'creatinine' => $offline->creatinine,
                    'uric_acid' => $offline->uric_acid,
                    'cholesterol' => $offline->cholesterol,
                    'triglyceride' => $offline->triglyceride,
                    'hdl_cholesterol' => $offline->hdl_cholesterol,
                    'ldl_cholesterol' => $offline->ldl_cholesterol,
                    'sgot' => $offline->sgot,
                    'sgpt' => $offline->sgpt,
                    'remarks' => $offline->remarks,
                    'order_status' => $offline->order_status,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory table from online to offline
        $online_query = DB::connection('mysql2')->table('laboratory_chemistry')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('laboratory_chemistry')->where('lc_id', $online->lc_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_chemistry')->where('lc_id', $online->lc_id)->update([
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'spicemen' => $online->spicemen,
                    'glucose' => $online->glucose,
                    'creatinine' => $online->creatinine,
                    'uric_acid' => $online->uric_acid,
                    'cholesterol' => $online->cholesterol,
                    'triglyceride' => $online->triglyceride,
                    'hdl_cholesterol' => $online->hdl_cholesterol,
                    'ldl_cholesterol' => $online->ldl_cholesterol,
                    'sgot' => $online->sgot,
                    'sgpt' => $online->sgpt,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('laboratory_chemistry')->insert([
                    'lc_id' => $online->lc_id,
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'spicemen' => $online->spicemen,
                    'glucose' => $online->glucose,
                    'creatinine' => $online->creatinine,
                    'uric_acid' => $online->uric_acid,
                    'cholesterol' => $online->cholesterol,
                    'triglyceride' => $online->triglyceride,
                    'hdl_cholesterol' => $online->hdl_cholesterol,
                    'ldl_cholesterol' => $online->ldl_cholesterol,
                    'sgot' => $online->sgot,
                    'sgpt' => $online->sgpt,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratoryFecal()
    {
        // syncronize laboratory_fecal_analysis table from offline to online
        $offline_query = DB::table('laboratory_fecal_analysis')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_fecal_analysis')->where('lfa_id', $offline->lfa_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_fecal_analysis')->where('lfa_id', $offline->lfa_id)->update([
                        'order_id' => $offline->order_id,
                        'doctor_id' => $offline->doctor_id,
                        'patient_id' => $offline->patient_id,
                        'laboratory_id' => $offline->laboratory_id,
                        'ward_nurse_id' => $offline->ward_nurse_id,
                        'case_file' => $offline->case_file,
                        'is_viewed' => $offline->is_viewed,
                        'is_processed' => $offline->is_processed,
                        'is_processed_by' => $offline->is_processed_by,
                        'is_processed_time_start' => $offline->is_processed_time_start,
                        'is_processed_time_end' => $offline->is_processed_time_end,
                        'is_pending' => $offline->is_pending,
                        'is_pending_reason' => $offline->is_pending_reason,
                        'is_pending_date' => $offline->is_pending_date,
                        'is_pending_by' => $offline->is_pending_by,
                        'fecal_analysis' => $offline->fecal_analysis,
                        'cellular_elements_color' => $offline->cellular_elements_color,
                        'cellular_elements_consistency' => $offline->cellular_elements_consistency,
                        'cellular_elements_pus' => $offline->cellular_elements_pus,
                        'cellular_elements_rbc' => $offline->cellular_elements_rbc,
                        'cellular_elements_fat_globules' => $offline->cellular_elements_fat_globules,
                        'cellular_elements_occultblood' => $offline->cellular_elements_occultblood,
                        'cellular_elements_bacteria' => $offline->cellular_elements_bacteria,
                        'cellular_elements_result' => $offline->cellular_elements_result,
                        'remarks' => $offline->remarks,
                        'order_status' => $offline->order_status,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_fecal_analysis')->where('lfa_id', $offline_count[0]->lfa_id)->update([
                        'order_id' => $offline_count[0]->order_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'ward_nurse_id' => $offline_count[0]->ward_nurse_id,
                        'case_file' => $offline_count[0]->case_file,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'is_processed' => $offline_count[0]->is_processed,
                        'is_processed_by' => $offline_count[0]->is_processed_by,
                        'is_processed_time_start' => $offline_count[0]->is_processed_time_start,
                        'is_processed_time_end' => $offline_count[0]->is_processed_time_end,
                        'is_pending' => $offline_count[0]->is_pending,
                        'is_pending_reason' => $offline_count[0]->is_pending_reason,
                        'is_pending_date' => $offline_count[0]->is_pending_date,
                        'is_pending_by' => $offline_count[0]->is_pending_by,
                        'fecal_analysis' => $offline_count[0]->fecal_analysis,
                        'cellular_elements_color' => $offline_count[0]->cellular_elements_color,
                        'cellular_elements_consistency' => $offline_count[0]->cellular_elements_consistency,
                        'cellular_elements_pus' => $offline_count[0]->cellular_elements_pus,
                        'cellular_elements_rbc' => $offline_count[0]->cellular_elements_rbc,
                        'cellular_elements_fat_globules' => $offline_count[0]->cellular_elements_fat_globules,
                        'cellular_elements_occultblood' => $offline_count[0]->cellular_elements_occultblood,
                        'cellular_elements_bacteria' => $offline_count[0]->cellular_elements_bacteria,
                        'cellular_elements_result' => $offline_count[0]->cellular_elements_result,
                        'remarks' => $offline_count[0]->remarks,
                        'order_status' => $offline_count[0]->order_status,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('laboratory_fecal_analysis')->insert([
                    'lfa_id' => $offline->lfa_id,
                    'order_id' => $offline->order_id,
                    'doctor_id' => $offline->doctor_id,
                    'patient_id' => $offline->patient_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'ward_nurse_id' => $offline->ward_nurse_id,
                    'case_file' => $offline->case_file,
                    'is_viewed' => $offline->is_viewed,
                    'is_processed' => $offline->is_processed,
                    'is_processed_by' => $offline->is_processed_by,
                    'is_processed_time_start' => $offline->is_processed_time_start,
                    'is_processed_time_end' => $offline->is_processed_time_end,
                    'is_pending' => $offline->is_pending,
                    'is_pending_reason' => $offline->is_pending_reason,
                    'is_pending_date' => $offline->is_pending_date,
                    'is_pending_by' => $offline->is_pending_by,
                    'fecal_analysis' => $offline->fecal_analysis,
                    'cellular_elements_color' => $offline->cellular_elements_color,
                    'cellular_elements_consistency' => $offline->cellular_elements_consistency,
                    'cellular_elements_pus' => $offline->cellular_elements_pus,
                    'cellular_elements_rbc' => $offline->cellular_elements_rbc,
                    'cellular_elements_fat_globules' => $offline->cellular_elements_fat_globules,
                    'cellular_elements_occultblood' => $offline->cellular_elements_occultblood,
                    'cellular_elements_bacteria' => $offline->cellular_elements_bacteria,
                    'cellular_elements_result' => $offline->cellular_elements_result,
                    'remarks' => $offline->remarks,
                    'order_status' => $offline->order_status,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory table from online to offline
        $online_query = DB::connection('mysql2')->table('laboratory_fecal_analysis')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('laboratory_fecal_analysis')->where('lfa_id', $online->lfa_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_fecal_analysis')->where('lfa_id', $online->lfa_id)->update([
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'fecal_analysis' => $online->fecal_analysis,
                    'cellular_elements_color' => $online->cellular_elements_color,
                    'cellular_elements_consistency' => $online->cellular_elements_consistency,
                    'cellular_elements_pus' => $online->cellular_elements_pus,
                    'cellular_elements_rbc' => $online->cellular_elements_rbc,
                    'cellular_elements_fat_globules' => $online->cellular_elements_fat_globules,
                    'cellular_elements_occultblood' => $online->cellular_elements_occultblood,
                    'cellular_elements_bacteria' => $online->cellular_elements_bacteria,
                    'cellular_elements_result' => $online->cellular_elements_result,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('laboratory_fecal_analysis')->insert([
                    'lfa_id' => $online->lfa_id,
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'fecal_analysis' => $online->fecal_analysis,
                    'cellular_elements_color' => $online->cellular_elements_color,
                    'cellular_elements_consistency' => $online->cellular_elements_consistency,
                    'cellular_elements_pus' => $online->cellular_elements_pus,
                    'cellular_elements_rbc' => $online->cellular_elements_rbc,
                    'cellular_elements_fat_globules' => $online->cellular_elements_fat_globules,
                    'cellular_elements_occultblood' => $online->cellular_elements_occultblood,
                    'cellular_elements_bacteria' => $online->cellular_elements_bacteria,
                    'cellular_elements_result' => $online->cellular_elements_result,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;

    }

    public static function syncLaboratoryFormheader()
    {
        // syncronize laboratory_formheader table from offline to online
        $offline_query = DB::table('laboratory_formheader')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_formheader')->where('lfh_id', $offline->lfh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_formheader')->where('lfh_id', $offline->lfh_id)->update([
                        'management_id' => $offline->management_id,
                        'name' => $offline->name,
                        'address' => $offline->address,
                        'contact_number' => $offline->contact_number,
                        'pathologist' => $offline->pathologist,
                        'pathologist_lcn' => $offline->pathologist_lcn,
                        'pathologist_signature' => $offline->pathologist_signature,
                        'medtech' => $offline->medtech,
                        'medtect_lci' => $offline->medtect_lci,
                        'medtect_signature' => $offline->medtect_signature,
                        'logo' => $offline->logo,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_formheader')->where('lfh_id', $offline_count[0]->lfh_id)->update([
                        'management_id' => $offline_count[0]->management_id,
                        'name' => $offline_count[0]->name,
                        'address' => $offline_count[0]->address,
                        'contact_number' => $offline_count[0]->contact_number,
                        'pathologist' => $offline_count[0]->pathologist,
                        'pathologist_lcn' => $offline_count[0]->pathologist_lcn,
                        'pathologist_signature' => $offline_count[0]->pathologist_signature,
                        'medtech' => $offline_count[0]->medtech,
                        'medtect_lci' => $offline_count[0]->medtect_lci,
                        'medtect_signature' => $offline_count[0]->medtect_signature,
                        'logo' => $offline_count[0]->logo,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);

                }

            } else {
                DB::connection('mysql2')->table('laboratory_formheader')->insert([
                    'lfh_id' => $offline->lfh_id,
                    'management_id' => $offline->management_id,
                    'name' => $offline->name,
                    'address' => $offline->address,
                    'contact_number' => $offline->contact_number,
                    'pathologist' => $offline->pathologist,
                    'pathologist_lcn' => $offline->pathologist_lcn,
                    'pathologist_signature' => $offline->pathologist_signature,
                    'medtech' => $offline->medtech,
                    'medtect_lci' => $offline->medtect_lci,
                    'medtect_signature' => $offline->medtect_signature,
                    'logo' => $offline->logo,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory_formheader table from online to offline
        $offline_query = DB::connection('mysql2')->table('laboratory_formheader')->get();
        foreach ($offline_query as $online) {
            $online_count = DB::table('laboratory_formheader')->where('lfh_id', $online->lfh_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_formheader')->where('lfh_id', $online->lfh_id)->update([
                    'management_id' => $online->management_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'contact_number' => $online->contact_number,
                    'pathologist' => $online->pathologist,
                    'pathologist_lcn' => $online->pathologist_lcn,
                    'pathologist_signature' => $online->pathologist_signature,
                    'medtech' => $online->medtech,
                    'medtect_lci' => $online->medtect_lci,
                    'medtect_signature' => $online->medtect_signature,
                    'logo' => $online->logo,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);

            } else {
                DB::table('laboratory_formheader')->insert([
                    'lfh_id' => $online->lfh_id,
                    'management_id' => $online->management_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'contact_number' => $online->contact_number,
                    'pathologist' => $online->pathologist,
                    'pathologist_lcn' => $online->pathologist_lcn,
                    'pathologist_signature' => $online->pathologist_signature,
                    'medtech' => $online->medtech,
                    'medtect_lci' => $online->medtect_lci,
                    'medtect_signature' => $online->medtect_signature,
                    'logo' => $online->logo,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratoryHemathology()
    {
        // syncronize laboratory_hematology table from offline to online
        $offline_query = DB::table('laboratory_hematology')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_hematology')->where('lh_id', $offline->lh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_hematology')->where('lh_id', $offline->lh_id)->update([
                        'order_id' => $offline->order_id,
                        'doctor_id' => $offline->doctor_id,
                        'patient_id' => $offline->patient_id,
                        'laboratory_id' => $offline->laboratory_id,
                        'ward_nurse_id' => $offline->ward_nurse_id,
                        'case_file' => $offline->case_file,
                        'is_viewed' => $offline->is_viewed,
                        'is_processed' => $offline->is_processed,
                        'is_processed_by' => $offline->is_processed_by,
                        'is_processed_time_start' => $offline->is_processed_time_start,
                        'is_processed_time_end' => $offline->is_processed_time_end,
                        'is_pending' => $offline->is_pending,
                        'is_pending_reason' => $offline->is_pending_reason,
                        'is_pending_date' => $offline->is_pending_date,
                        'is_pending_by' => $offline->is_pending_by,
                        'hemoglobin' => $offline->hemoglobin,
                        'hematocrit' => $offline->hematocrit,
                        'rbc' => $offline->rbc,
                        'wbc' => $offline->wbc,
                        'platelet_count' => $offline->platelet_count,
                        'differential_count' => $offline->differential_count,
                        'neutrophil' => $offline->neutrophil,
                        'lymphocyte' => $offline->lymphocyte,
                        'monocyte' => $offline->monocyte,
                        'eosinophil' => $offline->eosinophil,
                        'basophil' => $offline->basophil,
                        'bands' => $offline->bands,
                        'abo_blood_type_and_rh_type' => $offline->abo_blood_type_and_rh_type,
                        'bleeding_time' => $offline->bleeding_time,
                        'clotting_time' => $offline->clotting_time,
                        'pathologist' => $offline->pathologist,
                        'medical_technologist' => $offline->medical_technologist,
                        'remarks' => $offline->remarks,
                        'order_status' => $offline->order_status,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_hematology')->where('lh_id', $offline_count[0]->lh_id)->update([
                        'order_id' => $offline_count[0]->order_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'ward_nurse_id' => $offline_count[0]->ward_nurse_id,
                        'case_file' => $offline_count[0]->case_file,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'is_processed' => $offline_count[0]->is_processed,
                        'is_processed_by' => $offline_count[0]->is_processed_by,
                        'is_processed_time_start' => $offline_count[0]->is_processed_time_start,
                        'is_processed_time_end' => $offline_count[0]->is_processed_time_end,
                        'is_pending' => $offline_count[0]->is_pending,
                        'is_pending_reason' => $offline_count[0]->is_pending_reason,
                        'is_pending_date' => $offline_count[0]->is_pending_date,
                        'is_pending_by' => $offline_count[0]->is_pending_by,
                        'hemoglobin' => $offline_count[0]->hemoglobin,
                        'hematocrit' => $offline_count[0]->hematocrit,
                        'rbc' => $offline_count[0]->rbc,
                        'wbc' => $offline_count[0]->wbc,
                        'platelet_count' => $offline_count[0]->platelet_count,
                        'differential_count' => $offline_count[0]->differential_count,
                        'neutrophil' => $offline_count[0]->neutrophil,
                        'lymphocyte' => $offline_count[0]->lymphocyte,
                        'monocyte' => $offline_count[0]->monocyte,
                        'eosinophil' => $offline_count[0]->eosinophil,
                        'basophil' => $offline_count[0]->basophil,
                        'bands' => $offline_count[0]->bands,
                        'abo_blood_type_and_rh_type' => $offline_count[0]->abo_blood_type_and_rh_type,
                        'bleeding_time' => $offline_count[0]->bleeding_time,
                        'clotting_time' => $offline_count[0]->clotting_time,
                        'pathologist' => $offline_count[0]->pathologist,
                        'medical_technologist' => $offline_count[0]->medical_technologist,
                        'remarks' => $offline_count[0]->remarks,
                        'order_status' => $offline_count[0]->order_status,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);

                }

            } else {
                DB::connection('mysql2')->table('laboratory_hematology')->insert([
                    'lh_id' => $offline->lh_id,
                    'order_id' => $offline->order_id,
                    'doctor_id' => $offline->doctor_id,
                    'patient_id' => $offline->patient_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'ward_nurse_id' => $offline->ward_nurse_id,
                    'case_file' => $offline->case_file,
                    'is_viewed' => $offline->is_viewed,
                    'is_processed' => $offline->is_processed,
                    'is_processed_by' => $offline->is_processed_by,
                    'is_processed_time_start' => $offline->is_processed_time_start,
                    'is_processed_time_end' => $offline->is_processed_time_end,
                    'is_pending' => $offline->is_pending,
                    'is_pending_reason' => $offline->is_pending_reason,
                    'is_pending_date' => $offline->is_pending_date,
                    'is_pending_by' => $offline->is_pending_by,
                    'hemoglobin' => $offline->hemoglobin,
                    'hematocrit' => $offline->hematocrit,
                    'rbc' => $offline->rbc,
                    'wbc' => $offline->wbc,
                    'platelet_count' => $offline->platelet_count,
                    'differential_count' => $offline->differential_count,
                    'neutrophil' => $offline->neutrophil,
                    'lymphocyte' => $offline->lymphocyte,
                    'monocyte' => $offline->monocyte,
                    'eosinophil' => $offline->eosinophil,
                    'basophil' => $offline->basophil,
                    'bands' => $offline->bands,
                    'abo_blood_type_and_rh_type' => $offline->abo_blood_type_and_rh_type,
                    'bleeding_time' => $offline->bleeding_time,
                    'clotting_time' => $offline->clotting_time,
                    'pathologist' => $offline->pathologist,
                    'medical_technologist' => $offline->medical_technologist,
                    'remarks' => $offline->remarks,
                    'order_status' => $offline->order_status,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory_hematology table from online to offline
        $offline_query = DB::connection('mysql2')->table('laboratory_hematology')->get();
        foreach ($offline_query as $online) {
            $online_count = DB::table('laboratory_hematology')->where('lh_id', $online->lh_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_hematology')->where('lh_id', $online->lh_id)->update([
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'hemoglobin' => $online->hemoglobin,
                    'hematocrit' => $online->hematocrit,
                    'rbc' => $online->rbc,
                    'wbc' => $online->wbc,
                    'platelet_count' => $online->platelet_count,
                    'differential_count' => $online->differential_count,
                    'neutrophil' => $online->neutrophil,
                    'lymphocyte' => $online->lymphocyte,
                    'monocyte' => $online->monocyte,
                    'eosinophil' => $online->eosinophil,
                    'basophil' => $online->basophil,
                    'bands' => $online->bands,
                    'abo_blood_type_and_rh_type' => $online->abo_blood_type_and_rh_type,
                    'bleeding_time' => $online->bleeding_time,
                    'clotting_time' => $online->clotting_time,
                    'pathologist' => $online->pathologist,
                    'medical_technologist' => $online->medical_technologist,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);

            } else {
                DB::table('laboratory_hematology')->insert([
                    'lh_id' => $online->lh_id,
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'hemoglobin' => $online->hemoglobin,
                    'hematocrit' => $online->hematocrit,
                    'rbc' => $online->rbc,
                    'wbc' => $online->wbc,
                    'platelet_count' => $online->platelet_count,
                    'differential_count' => $online->differential_count,
                    'neutrophil' => $online->neutrophil,
                    'lymphocyte' => $online->lymphocyte,
                    'monocyte' => $online->monocyte,
                    'eosinophil' => $online->eosinophil,
                    'basophil' => $online->basophil,
                    'bands' => $online->bands,
                    'abo_blood_type_and_rh_type' => $online->abo_blood_type_and_rh_type,
                    'bleeding_time' => $online->bleeding_time,
                    'clotting_time' => $online->clotting_time,
                    'pathologist' => $online->pathologist,
                    'medical_technologist' => $online->medical_technologist,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratoryList()
    {
        // syncronize appointment_settings table from offline to online
        $offline_query = DB::table('laboratory_list')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_list')->where('l_id', $offline->l_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_list')->where('l_id', $offline->l_id)->update([
                        'laboratory_id' => $offline->laboratory_id,
                        'management_id' => $offline->management_id,
                        'user_id' => $offline->user_id,
                        'name' => $offline->name,
                        'gender' => $offline->gender,
                        'birthday' => $offline->birthday,
                        'role' => $offline->role,
                        'added_by' => $offline->added_by,
                        'address' => $offline->address,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_list')->where('l_id', $offline_count[0]->l_id)->update([
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'management_id' => $offline_count[0]->management_id,
                        'user_id' => $offline_count[0]->user_id,
                        'name' => $offline_count[0]->name,
                        'gender' => $offline_count[0]->gender,
                        'birthday' => $offline_count[0]->birthday,
                        'role' => $offline_count[0]->role,
                        'added_by' => $offline_count[0]->added_by,
                        'address' => $offline_count[0]->address,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('laboratory_list')->insert([
                    'l_id' => $offline->l_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'management_id' => $offline->management_id,
                    'user_id' => $offline->user_id,
                    'name' => $offline->name,
                    'gender' => $offline->gender,
                    'birthday' => $offline->birthday,
                    'role' => $offline->role,
                    'added_by' => $offline->added_by,
                    'address' => $offline->address,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize appointment_settings table from online to offline
        $online_query = DB::connection('mysql2')->table('laboratory_list')->get();
        foreach ($online_query as $online) {
            $online_count = DB::table('laboratory_list')->where('l_id', $online->l_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_list')->where('l_id', $online->l_id)->update([
                    'laboratory_id' => $online->laboratory_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'role' => $online->role,
                    'added_by' => $online->added_by,
                    'address' => $online->address,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('laboratory_list')->insert([
                    'l_id' => $online->l_id,
                    'management_id' => $online->management_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'role' => $online->role,
                    'added_by' => $online->added_by,
                    'address' => $online->address,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratoryMicroscopy()
    {
        // syncronize laboratory_microscopy table from offline to online
        $offline_query = DB::table('laboratory_microscopy')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_microscopy')->where('lm_id', $offline->lm_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_microscopy')->where('lm_id', $offline->lm_id)->update([
                        'order_id' => $offline->order_id,
                        'doctor_id' => $offline->doctor_id,
                        'patient_id' => $offline->patient_id,
                        'laboratory_id' => $offline->laboratory_id,
                        'is_viewed' => $offline->is_viewed,
                        'is_processed' => $offline->is_processed,
                        'is_processed_by' => $offline->is_processed_by,
                        'is_processed_time_start' => $offline->is_processed_time_start,
                        'is_processed_time_end' => $offline->is_processed_time_end,
                        'is_pending' => $offline->is_pending,
                        'is_pending_reason' => $offline->is_pending_reason,
                        'is_pending_date' => $offline->is_pending_date,
                        'is_pending_by' => $offline->is_pending_by,
                        'spicemen' => $offline->spicemen,
                        'chemical_test' => $offline->chemical_test,
                        'chemical_test_color' => $offline->chemical_test_color,
                        'chemical_test_transparency' => $offline->chemical_test_transparency,
                        'chemical_test_ph' => $offline->chemical_test_ph,
                        'chemical_test_spicific_gravity' => $offline->chemical_test_spicific_gravity,
                        'chemical_test_glucose' => $offline->chemical_test_glucose,
                        'chemical_test_albumin' => $offline->chemical_test_albumin,
                        'microscopic_test' => $offline->microscopic_test,
                        'microscopic_test_squamous' => $offline->microscopic_test_squamous,
                        'microscopic_test_pus' => $offline->microscopic_test_pus,
                        'microscopic_test_redblood' => $offline->microscopic_test_redblood,
                        'microscopic_test_hyaline' => $offline->microscopic_test_hyaline,
                        'microscopic_test_wbc' => $offline->microscopic_test_wbc,
                        'microscopic_test_rbc' => $offline->microscopic_test_rbc,
                        'microscopic_test_fine_granular' => $offline->microscopic_test_fine_granular,
                        'microscopic_test_coarse_granular' => $offline->microscopic_test_coarse_granular,
                        'microscopic_test_calcium_oxalate' => $offline->microscopic_test_calcium_oxalate,
                        'microscopic_test_triple_phospahte' => $offline->microscopic_test_triple_phospahte,
                        'microscopic_test_leucine_tyrosine' => $offline->microscopic_test_leucine_tyrosine,
                        'microscopic_test_ammonium_biurate' => $offline->microscopic_test_ammonium_biurate,
                        'microscopic_test_amorphous_urates' => $offline->microscopic_test_amorphous_urates,
                        'microscopic_test_amorphous_phosphates' => $offline->microscopic_test_amorphous_phosphates,
                        'microscopic_test_uricacid' => $offline->microscopic_test_uricacid,
                        'microscopic_test_mucus_thread' => $offline->microscopic_test_mucus_thread,
                        'microscopic_test_bacteria' => $offline->microscopic_test_bacteria,
                        'microscopic_test_yeast' => $offline->microscopic_test_yeast,
                        'pregnancy_test_hcg' => $offline->pregnancy_test_hcg,
                        'pregnancy_test_hcg_result' => $offline->pregnancy_test_hcg_result,
                        'order_status' => $offline->order_status,
                        'order_remarks' => $offline->order_remarks,
                        'result_remarks' => $offline->result_remarks,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_microscopy')->where('lm_id', $offline_count[0]->lm_id)->update([
                        'order_id' => $offline_count[0]->order_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'is_processed' => $offline_count[0]->is_processed,
                        'is_processed_by' => $offline_count[0]->is_processed_by,
                        'is_processed_time_start' => $offline_count[0]->is_processed_time_start,
                        'is_processed_time_end' => $offline_count[0]->is_processed_time_end,
                        'is_pending' => $offline_count[0]->is_pending,
                        'is_pending_reason' => $offline_count[0]->is_pending_reason,
                        'is_pending_date' => $offline_count[0]->is_pending_date,
                        'is_pending_by' => $offline_count[0]->is_pending_by,
                        'spicemen' => $offline_count[0]->spicemen,
                        'chemical_test' => $offline_count[0]->chemical_test,
                        'chemical_test_color' => $offline_count[0]->chemical_test_color,
                        'chemical_test_transparency' => $offline_count[0]->chemical_test_transparency,
                        'chemical_test_ph' => $offline_count[0]->chemical_test_ph,
                        'chemical_test_spicific_gravity' => $offline_count[0]->chemical_test_spicific_gravity,
                        'chemical_test_glucose' => $offline_count[0]->chemical_test_glucose,
                        'chemical_test_albumin' => $offline_count[0]->chemical_test_albumin,
                        'microscopic_test' => $offline_count[0]->microscopic_test,
                        'microscopic_test_squamous' => $offline_count[0]->microscopic_test_squamous,
                        'microscopic_test_pus' => $offline_count[0]->microscopic_test_pus,
                        'microscopic_test_redblood' => $offline_count[0]->microscopic_test_redblood,
                        'microscopic_test_hyaline' => $offline_count[0]->microscopic_test_hyaline,
                        'microscopic_test_wbc' => $offline_count[0]->microscopic_test_wbc,
                        'microscopic_test_rbc' => $offline_count[0]->microscopic_test_rbc,
                        'microscopic_test_fine_granular' => $offline_count[0]->microscopic_test_fine_granular,
                        'microscopic_test_coarse_granular' => $offline_count[0]->microscopic_test_coarse_granular,
                        'microscopic_test_calcium_oxalate' => $offline_count[0]->microscopic_test_calcium_oxalate,
                        'microscopic_test_triple_phospahte' => $offline_count[0]->microscopic_test_triple_phospahte,
                        'microscopic_test_leucine_tyrosine' => $offline_count[0]->microscopic_test_leucine_tyrosine,
                        'microscopic_test_ammonium_biurate' => $offline_count[0]->microscopic_test_ammonium_biurate,
                        'microscopic_test_amorphous_urates' => $offline_count[0]->microscopic_test_amorphous_urates,
                        'microscopic_test_amorphous_phosphates' => $offline_count[0]->microscopic_test_amorphous_phosphates,
                        'microscopic_test_uricacid' => $offline_count[0]->microscopic_test_uricacid,
                        'microscopic_test_mucus_thread' => $offline_count[0]->microscopic_test_mucus_thread,
                        'microscopic_test_bacteria' => $offline_count[0]->microscopic_test_bacteria,
                        'microscopic_test_yeast' => $offline_count[0]->microscopic_test_yeast,
                        'pregnancy_test_hcg' => $offline_count[0]->pregnancy_test_hcg,
                        'pregnancy_test_hcg_result' => $offline_count[0]->pregnancy_test_hcg_result,
                        'order_status' => $offline_count[0]->order_status,
                        'order_remarks' => $offline_count[0]->order_remarks,
                        'result_remarks' => $offline_count[0]->result_remarks,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);

                }

            } else {
                DB::connection('mysql2')->table('laboratory_microscopy')->insert([
                    'lm_id' => $offline->lm_id,
                    'order_id' => $offline->order_id,
                    'doctor_id' => $offline->doctor_id,
                    'patient_id' => $offline->patient_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'is_viewed' => $offline->is_viewed,
                    'is_processed' => $offline->is_processed,
                    'is_processed_by' => $offline->is_processed_by,
                    'is_processed_time_start' => $offline->is_processed_time_start,
                    'is_processed_time_end' => $offline->is_processed_time_end,
                    'is_pending' => $offline->is_pending,
                    'is_pending_reason' => $offline->is_pending_reason,
                    'is_pending_date' => $offline->is_pending_date,
                    'is_pending_by' => $offline->is_pending_by,
                    'spicemen' => $offline->spicemen,
                    'chemical_test' => $offline->chemical_test,
                    'chemical_test_color' => $offline->chemical_test_color,
                    'chemical_test_transparency' => $offline->chemical_test_transparency,
                    'chemical_test_ph' => $offline->chemical_test_ph,
                    'chemical_test_spicific_gravity' => $offline->chemical_test_spicific_gravity,
                    'chemical_test_glucose' => $offline->chemical_test_glucose,
                    'chemical_test_albumin' => $offline->chemical_test_albumin,
                    'microscopic_test' => $offline->microscopic_test,
                    'microscopic_test_squamous' => $offline->microscopic_test_squamous,
                    'microscopic_test_pus' => $offline->microscopic_test_pus,
                    'microscopic_test_redblood' => $offline->microscopic_test_redblood,
                    'microscopic_test_hyaline' => $offline->microscopic_test_hyaline,
                    'microscopic_test_wbc' => $offline->microscopic_test_wbc,
                    'microscopic_test_rbc' => $offline->microscopic_test_rbc,
                    'microscopic_test_fine_granular' => $offline->microscopic_test_fine_granular,
                    'microscopic_test_coarse_granular' => $offline->microscopic_test_coarse_granular,
                    'microscopic_test_calcium_oxalate' => $offline->microscopic_test_calcium_oxalate,
                    'microscopic_test_triple_phospahte' => $offline->microscopic_test_triple_phospahte,
                    'microscopic_test_leucine_tyrosine' => $offline->microscopic_test_leucine_tyrosine,
                    'microscopic_test_ammonium_biurate' => $offline->microscopic_test_ammonium_biurate,
                    'microscopic_test_amorphous_urates' => $offline->microscopic_test_amorphous_urates,
                    'microscopic_test_amorphous_phosphates' => $offline->microscopic_test_amorphous_phosphates,
                    'microscopic_test_uricacid' => $offline->microscopic_test_uricacid,
                    'microscopic_test_mucus_thread' => $offline->microscopic_test_mucus_thread,
                    'microscopic_test_bacteria' => $offline->microscopic_test_bacteria,
                    'microscopic_test_yeast' => $offline->microscopic_test_yeast,
                    'pregnancy_test_hcg' => $offline->pregnancy_test_hcg,
                    'pregnancy_test_hcg_result' => $offline->pregnancy_test_hcg_result,
                    'order_status' => $offline->order_status,
                    'order_remarks' => $offline->order_remarks,
                    'result_remarks' => $offline->result_remarks,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory_microscopy table from online to offline
        $offline_query = DB::connection('mysql2')->table('laboratory_microscopy')->get();
        foreach ($offline_query as $online) {
            $online_count = DB::table('laboratory_microscopy')->where('lm_id', $online->lm_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_microscopy')->where('lm_id', $online->lm_id)->update([
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'spicemen' => $online->spicemen,
                    'chemical_test' => $online->chemical_test,
                    'chemical_test_color' => $online->chemical_test_color,
                    'chemical_test_transparency' => $online->chemical_test_transparency,
                    'chemical_test_ph' => $online->chemical_test_ph,
                    'chemical_test_spicific_gravity' => $online->chemical_test_spicific_gravity,
                    'chemical_test_glucose' => $online->chemical_test_glucose,
                    'chemical_test_albumin' => $online->chemical_test_albumin,
                    'microscopic_test' => $online->microscopic_test,
                    'microscopic_test_squamous' => $online->microscopic_test_squamous,
                    'microscopic_test_pus' => $online->microscopic_test_pus,
                    'microscopic_test_redblood' => $online->microscopic_test_redblood,
                    'microscopic_test_hyaline' => $online->microscopic_test_hyaline,
                    'microscopic_test_wbc' => $online->microscopic_test_wbc,
                    'microscopic_test_rbc' => $online->microscopic_test_rbc,
                    'microscopic_test_fine_granular' => $online->microscopic_test_fine_granular,
                    'microscopic_test_coarse_granular' => $online->microscopic_test_coarse_granular,
                    'microscopic_test_calcium_oxalate' => $online->microscopic_test_calcium_oxalate,
                    'microscopic_test_triple_phospahte' => $online->microscopic_test_triple_phospahte,
                    'microscopic_test_leucine_tyrosine' => $online->microscopic_test_leucine_tyrosine,
                    'microscopic_test_ammonium_biurate' => $online->microscopic_test_ammonium_biurate,
                    'microscopic_test_amorphous_urates' => $online->microscopic_test_amorphous_urates,
                    'microscopic_test_amorphous_phosphates' => $online->microscopic_test_amorphous_phosphates,
                    'microscopic_test_uricacid' => $online->microscopic_test_uricacid,
                    'microscopic_test_mucus_thread' => $online->microscopic_test_mucus_thread,
                    'microscopic_test_bacteria' => $online->microscopic_test_bacteria,
                    'microscopic_test_yeast' => $online->microscopic_test_yeast,
                    'pregnancy_test_hcg' => $online->pregnancy_test_hcg,
                    'pregnancy_test_hcg_result' => $online->pregnancy_test_hcg_result,
                    'order_status' => $online->order_status,
                    'order_remarks' => $online->order_remarks,
                    'result_remarks' => $online->result_remarks,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);

            } else {
                DB::table('laboratory_microscopy')->insert([
                    'lm_id' => $online->lm_id,
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'spicemen' => $online->spicemen,
                    'chemical_test' => $online->chemical_test,
                    'chemical_test_color' => $online->chemical_test_color,
                    'chemical_test_transparency' => $online->chemical_test_transparency,
                    'chemical_test_ph' => $online->chemical_test_ph,
                    'chemical_test_spicific_gravity' => $online->chemical_test_spicific_gravity,
                    'chemical_test_glucose' => $online->chemical_test_glucose,
                    'chemical_test_albumin' => $online->chemical_test_albumin,
                    'microscopic_test' => $online->microscopic_test,
                    'microscopic_test_squamous' => $online->microscopic_test_squamous,
                    'microscopic_test_pus' => $online->microscopic_test_pus,
                    'microscopic_test_redblood' => $online->microscopic_test_redblood,
                    'microscopic_test_hyaline' => $online->microscopic_test_hyaline,
                    'microscopic_test_wbc' => $online->microscopic_test_wbc,
                    'microscopic_test_rbc' => $online->microscopic_test_rbc,
                    'microscopic_test_fine_granular' => $online->microscopic_test_fine_granular,
                    'microscopic_test_coarse_granular' => $online->microscopic_test_coarse_granular,
                    'microscopic_test_calcium_oxalate' => $online->microscopic_test_calcium_oxalate,
                    'microscopic_test_triple_phospahte' => $online->microscopic_test_triple_phospahte,
                    'microscopic_test_leucine_tyrosine' => $online->microscopic_test_leucine_tyrosine,
                    'microscopic_test_ammonium_biurate' => $online->microscopic_test_ammonium_biurate,
                    'microscopic_test_amorphous_urates' => $online->microscopic_test_amorphous_urates,
                    'microscopic_test_amorphous_phosphates' => $online->microscopic_test_amorphous_phosphates,
                    'microscopic_test_uricacid' => $online->microscopic_test_uricacid,
                    'microscopic_test_mucus_thread' => $online->microscopic_test_mucus_thread,
                    'microscopic_test_bacteria' => $online->microscopic_test_bacteria,
                    'microscopic_test_yeast' => $online->microscopic_test_yeast,
                    'pregnancy_test_hcg' => $online->pregnancy_test_hcg,
                    'pregnancy_test_hcg_result' => $online->pregnancy_test_hcg_result,
                    'order_status' => $online->order_status,
                    'order_remarks' => $online->order_remarks,
                    'result_remarks' => $online->result_remarks,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratorySorology()
    {
        // syncronize laboratory_sorology table from offline to online
        $offline_query = DB::table('laboratory_sorology')->get();
        foreach ($offline_query as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_sorology')->where('ls_id', $offline->ls_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_sorology')->where('ls_id', $offline->ls_id)->update([
                        'order_id' => $offline->order_id,
                        'doctor_id' => $offline->doctor_id,
                        'patient_id' => $offline->patient_id,
                        'laboratory_id' => $offline->laboratory_id,
                        'ward_nurse_id' => $offline->ward_nurse_id,
                        'case_file' => $offline->case_file,
                        'is_viewed' => $offline->is_viewed,
                        'is_processed' => $offline->is_processed,
                        'is_processed_by' => $offline->is_processed_by,
                        'is_processed_time_start' => $offline->is_processed_time_start,
                        'is_processed_time_end' => $offline->is_processed_time_end,
                        'is_pending' => $offline->is_pending,
                        'is_pending_reason' => $offline->is_pending_reason,
                        'is_pending_date' => $offline->is_pending_date,
                        'is_pending_by' => $offline->is_pending_by,
                        'hbsag' => $offline->hbsag,
                        'hav' => $offline->hav,
                        'hcv' => $offline->hcv,
                        'vdrl_rpr' => $offline->vdrl_rpr,
                        'remarks' => $offline->remarks,
                        'order_status' => $offline->order_status,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_sorology')->where('ls_id', $offline_count[0]->ls_id)->update([
                        'order_id' => $offline_count[0]->order_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'ward_nurse_id' => $offline_count[0]->ward_nurse_id,
                        'case_file' => $offline_count[0]->case_file,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'is_processed' => $offline_count[0]->is_processed,
                        'is_processed_by' => $offline_count[0]->is_processed_by,
                        'is_processed_time_start' => $offline_count[0]->is_processed_time_start,
                        'is_processed_time_end' => $offline_count[0]->is_processed_time_end,
                        'is_pending' => $offline_count[0]->is_pending,
                        'is_pending_reason' => $offline_count[0]->is_pending_reason,
                        'is_pending_date' => $offline_count[0]->is_pending_date,
                        'is_pending_by' => $offline_count[0]->is_pending_by,
                        'hbsag' => $offline_count[0]->hbsag,
                        'hav' => $offline_count[0]->hav,
                        'hcv' => $offline_count[0]->hcv,
                        'vdrl_rpr' => $offline_count[0]->vdrl_rpr,
                        'remarks' => $offline_count[0]->remarks,
                        'order_status' => $offline_count[0]->order_status,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);

                }

            } else {
                DB::connection('mysql2')->table('laboratory_sorology')->insert([
                    'ls_id' => $offline->ls_id,
                    'order_id' => $offline->order_id,
                    'doctor_id' => $offline->doctor_id,
                    'patient_id' => $offline->patient_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'ward_nurse_id' => $offline->ward_nurse_id,
                    'case_file' => $offline->case_file,
                    'is_viewed' => $offline->is_viewed,
                    'is_processed' => $offline->is_processed,
                    'is_processed_by' => $offline->is_processed_by,
                    'is_processed_time_start' => $offline->is_processed_time_start,
                    'is_processed_time_end' => $offline->is_processed_time_end,
                    'is_pending' => $offline->is_pending,
                    'is_pending_reason' => $offline->is_pending_reason,
                    'is_pending_date' => $offline->is_pending_date,
                    'is_pending_by' => $offline->is_pending_by,
                    'hbsag' => $offline->hbsag,
                    'hav' => $offline->hav,
                    'hcv' => $offline->hcv,
                    'vdrl_rpr' => $offline->vdrl_rpr,
                    'remarks' => $offline->remarks,
                    'order_status' => $offline->order_status,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory_sorology table from online to offline
        $offline_query = DB::connection('mysql2')->table('laboratory_sorology')->get();
        foreach ($offline_query as $online) {
            $online_count = DB::table('laboratory_sorology')->where('ls_id', $online->ls_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_sorology')->where('ls_id', $online->ls_id)->update([
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'hbsag' => $online->hbsag,
                    'hav' => $online->hav,
                    'hcv' => $online->hcv,
                    'vdrl_rpr' => $online->vdrl_rpr,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);

            } else {
                DB::table('laboratory_sorology')->insert([
                    'ls_id' => $online->ls_id,
                    'order_id' => $online->order_id,
                    'doctor_id' => $online->doctor_id,
                    'patient_id' => $online->patient_id,
                    'laboratory_id' => $online->laboratory_id,
                    'ward_nurse_id' => $online->ward_nurse_id,
                    'case_file' => $online->case_file,
                    'is_viewed' => $online->is_viewed,
                    'is_processed' => $online->is_processed,
                    'is_processed_by' => $online->is_processed_by,
                    'is_processed_time_start' => $online->is_processed_time_start,
                    'is_processed_time_end' => $online->is_processed_time_end,
                    'is_pending' => $online->is_pending,
                    'is_pending_reason' => $online->is_pending_reason,
                    'is_pending_date' => $online->is_pending_date,
                    'is_pending_by' => $online->is_pending_by,
                    'hbsag' => $online->hbsag,
                    'hav' => $online->hav,
                    'hcv' => $online->hcv,
                    'vdrl_rpr' => $online->vdrl_rpr,
                    'remarks' => $online->remarks,
                    'order_status' => $online->order_status,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratoryLest()
    {
        // syncronize laboratory_test table from offline to online
        $offline = DB::table('laboratory_test')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_test')->where('lt_id', $offline->lt_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_test')->where('lt_id', $offline->lt_id)->update([
                        'lt_id' => $offline->lt_id,
                        'laboratory_id' => $offline->laboratory_id,
                        'laboratory_test' => $offline->laboratory_test,
                        'laboratory_rate' => $offline->laboratory_rate,
                        'department' => $offline->department,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('laboratory_test')->where('lt_id', $offline_count[0]->lt_id)->update([
                        'lt_id' => $offline_count[0]->lt_id,
                        'laboratory_id' => $offline_count[0]->laboratory_id,
                        'laboratory_test' => $offline_count[0]->laboratory_test,
                        'laboratory_rate' => $offline_count[0]->laboratory_rate,
                        'department' => $offline_count[0]->department,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('laboratory_test')->insert([
                    'lt_id' => $offline->lt_id,
                    'laboratory_id' => $offline->laboratory_id,
                    'laboratory_test' => $offline->laboratory_test,
                    'laboratory_rate' => $offline->laboratory_rate,
                    'department' => $offline->department,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize laboratory_test table from online to offline
        $online = DB::connection('mysql2')->table('laboratory_test')->get();
        foreach ($online as $online) {
            $online_count = DB::table('laboratory_test')->where('lt_id', $online->lt_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_test')->where('lt_id', $online->lt_id)->update([
                    'lt_id' => $online->lt_id,
                    'laboratory_id' => $online->laboratory_id,
                    'laboratory_test' => $online->laboratory_test,
                    'laboratory_rate' => $online->laboratory_rate,
                    'department' => $online->department,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('laboratory_test')->insert([
                    'lt_id' => $online->lt_id,
                    'laboratory_id' => $online->laboratory_id,
                    'laboratory_test' => $online->laboratory_test,
                    'laboratory_rate' => $online->laboratory_rate,
                    'department' => $online->department,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncLaboratoryUnsaveOrder()
    {
        // syncronize laboratory_unsaveorder table from offline to online
        $offline = DB::table('laboratory_unsaveorder')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('laboratory_unsaveorder')->where('lu_id', $offline->lu_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('laboratory_unsaveorder')->where('lu_id', $offline->lu_id)->update([
                        'lu_id' => $offline->lu_id,
                        'patient_id' => $offline->patient_id,
                        'doctor_id' => $offline->doctor_id,
                        'laborotary_id' => $offline->laborotary_id,
                        'management_id' => $offline->management_id,
                        'department' => $offline->department,
                        'laboratory_test_id' => $offline->laboratory_test_id,
                        'laboratory_test' => $offline->laboratory_test,
                        'laboratory_rate' => $offline->laboratory_rate,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('laboratory_unsaveorder')->where('lu_id', $offline_count[0]->lu_id)->update([
                        'lu_id' => $offline_count[0]->lu_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'laborotary_id' => $offline_count[0]->laborotary_id,
                        'management_id' => $offline_count[0]->management_id,
                        'department' => $offline_count[0]->department,
                        'laboratory_test_id' => $offline_count[0]->laboratory_test_id,
                        'laboratory_test' => $offline_count[0]->laboratory_test,
                        'laboratory_rate' => $offline_count[0]->laboratory_rate,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('laboratory_unsaveorder')->insert([
                    'lu_id' => $offline->lu_id,
                    'patient_id' => $offline->patient_id,
                    'doctor_id' => $offline->doctor_id,
                    'laborotary_id' => $offline->laborotary_id,
                    'management_id' => $offline->management_id,
                    'department' => $offline->department,
                    'laboratory_test_id' => $offline->laboratory_test_id,
                    'laboratory_test' => $offline->laboratory_test,
                    'laboratory_rate' => $offline->laboratory_rate,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize laboratory_unsaveorder table from online to offline
        $online = DB::connection('mysql2')->table('laboratory_unsaveorder')->get();
        foreach ($online as $online) {
            $online_count = DB::table('laboratory_unsaveorder')->where('lu_id', $online->lu_id)->get();
            if (count($online_count) > 0) {
                DB::table('laboratory_unsaveorder')->where('lu_id', $online->lu_id)->update([
                    'lu_id' => $online->lu_id,
                    'patient_id' => $online->patient_id,
                    'doctor_id' => $online->doctor_id,
                    'laborotary_id' => $online->laborotary_id,
                    'management_id' => $online->management_id,
                    'department' => $online->department,
                    'laboratory_test_id' => $online->laboratory_test_id,
                    'laboratory_test' => $online->laboratory_test,
                    'laboratory_rate' => $online->laboratory_rate,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('laboratory_unsaveorder')->insert([
                    'lu_id' => $online->lu_id,
                    'patient_id' => $online->patient_id,
                    'doctor_id' => $online->doctor_id,
                    'laborotary_id' => $online->laborotary_id,
                    'management_id' => $online->management_id,
                    'department' => $online->department,
                    'laboratory_test_id' => $online->laboratory_test_id,
                    'laboratory_test' => $online->laboratory_test,
                    'laboratory_rate' => $online->laboratory_rate,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncManagement()
    {
        // syncronize management table from offline to online
        $offline = DB::table('management')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('management')->where('m_id', $offline->m_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('management')->where('m_id', $offline->m_id)->update([
                        'm_id' => $offline->m_id,
                        'management_id' => $offline->management_id,
                        'user_id' => $offline->user_id,
                        'name' => $offline->name,
                        'address' => $offline->address,
                        'tin' => $offline->tin,
                        'business_style' => $offline->business_style,
                        'tax_type' => $offline->tax_type,
                        'logo' => $offline->logo,
                        'header' => $offline->header,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('management')->where('m_id', $offline_count[0]->m_id)->update([
                        'm_id' => $offline_count[0]->m_id,
                        'management_id' => $offline_count[0]->management_id,
                        'user_id' => $offline_count[0]->user_id,
                        'name' => $offline_count[0]->name,
                        'address' => $offline_count[0]->address,
                        'tin' => $offline_count[0]->tin,
                        'business_style' => $offline_count[0]->business_style,
                        'tax_type' => $offline_count[0]->tax_type,
                        'logo' => $offline_count[0]->logo,
                        'header' => $offline_count[0]->header,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('management')->insert([
                    'm_id' => $offline->m_id,
                    'management_id' => $offline->management_id,
                    'user_id' => $offline->user_id,
                    'name' => $offline->name,
                    'address' => $offline->address,
                    'tin' => $offline->tin,
                    'business_style' => $offline->business_style,
                    'tax_type' => $offline->tax_type,
                    'logo' => $offline->logo,
                    'header' => $offline->header,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize management table from online to offline
        $online = DB::connection('mysql2')->table('management')->get();
        foreach ($online as $online) {
            $lab_online_count = DB::table('management')->where('m_id', $online->m_id)->get();
            if (count($lab_online_count) > 0) {
                DB::table('management')->where('m_id', $online->m_id)->update([
                    'm_id' => $online->m_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'tin' => $online->tin,
                    'business_style' => $online->business_style,
                    'tax_type' => $online->tax_type,
                    'logo' => $online->logo,
                    'header' => $online->header,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('management')->insert([
                    'm_id' => $online->m_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'tin' => $online->tin,
                    'business_style' => $online->business_style,
                    'tax_type' => $online->tax_type,
                    'logo' => $online->logo,
                    'header' => $online->header,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncManagementMonitoring()
    {
        // syncronize management_monitoring table from offline to online
        $offline = DB::table('management_monitoring')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('management_monitoring')->where('monitoring_id', $offline->monitoring_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('management_monitoring')->where('monitoring_id', $offline->monitoring_id)->update([
                        'monitoring_id' => $offline->monitoring_id,
                        'city' => $offline->city,
                        'population' => $offline->population,
                    ]);
                } else {
                    DB::table('management_monitoring')->where('monitoring_id', $offline_count[0]->monitoring_id)->update([
                        'monitoring_id' => $offline_count[0]->monitoring_id,
                        'city' => $offline_count[0]->city,
                        'population' => $offline_count[0]->population,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('management_monitoring')->insert([
                    'monitoring_id' => $offline->monitoring_id,
                    'city' => $offline->city,
                    'population' => $offline->population,
                ]);
            }
        }

        // syncronize management_monitoring table from online to offline
        $offline = DB::connection('mysql2')->table('management_monitoring')->get();
        foreach ($offline as $offline) {
            $lab_online_count = DB::table('management_monitoring')->where('monitoring_id', $offline->monitoring_id)->get();
            if (count($lab_online_count) > 0) {
                DB::table('management_monitoring')->where('monitoring_id', $offline->monitoring_id)->update([
                    'monitoring_id' => $offline->monitoring_id,
                    'city' => $offline->city,
                    'population' => $offline->population,
                ]);
            } else {
                DB::table('management_monitoring')->insert([
                    'monitoring_id' => $offline->monitoring_id,
                    'city' => $offline->city,
                    'population' => $offline->population,
                ]);
            }
        }

        return true;
    }

    public static function syncMessages()
    {
        // syncronize messages table from offline to online
        $offline = DB::table('messages')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('messages')->where('message_id', $offline->message_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('messages')->where('message_id', $offline->message_id)->update([
                        'message_id' => $offline->message_id,
                        'name' => $offline->name,
                        'email' => $offline->email,
                        'messages' => $offline->messages,
                        'regarding' => $offline->regarding,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('messages')->where('message_id', $offline_count[0]->message_id)->update([
                        'message_id' => $offline_count[0]->message_id,
                        'name' => $offline_count[0]->name,
                        'email' => $offline_count[0]->email,
                        'messages' => $offline_count[0]->messages,
                        'regarding' => $offline_count[0]->regarding,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('messages')->insert([
                    'message_id' => $offline->message_id,
                    'name' => $offline->name,
                    'email' => $offline->email,
                    'messages' => $offline->messages,
                    'regarding' => $offline->regarding,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize messages table from online to offline
        $online = DB::connection('mysql2')->table('messages')->get();
        foreach ($online as $online) {
            $lab_online_count = DB::table('messages')->where('message_id', $online->message_id)->get();
            if (count($lab_online_count) > 0) {
                DB::table('messages')->where('message_id', $online->message_id)->update([
                    'message_id' => $online->message_id,
                    'name' => $online->name,
                    'email' => $online->email,
                    'messages' => $online->messages,
                    'regarding' => $online->regarding,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('messages')->insert([
                    'message_id' => $online->message_id,
                    'name' => $online->name,
                    'email' => $online->email,
                    'messages' => $online->messages,
                    'regarding' => $online->regarding,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncMessagesFromUser()
    {
        // syncronize message_from_users table from offline to online
        $offline = DB::table('message_from_users')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('message_from_users')->where('msg_id', $offline->msg_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('message_from_users')->where('msg_id', $offline->msg_id)->update([
                        'msg_id' => $offline->msg_id,
                        'fullname' => $offline->fullname,
                        'email' => $offline->email,
                        'msg' => $offline->msg,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('message_from_users')->where('msg_id', $offline_count[0]->msg_id)->update([
                        'msg_id' => $offline_count[0]->msg_id,
                        'fullname' => $offline_count[0]->fullname,
                        'email' => $offline_count[0]->email,
                        'msg' => $offline_count[0]->msg,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('message_from_users')->insert([
                    'msg_id' => $offline->msg_id,
                    'fullname' => $offline->fullname,
                    'email' => $offline->email,
                    'msg' => $offline->msg,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize message_from_users table from online to offline
        $online = DB::connection('mysql2')->table('message_from_users')->get();
        foreach ($online as $online) {
            $lab_online_count = DB::table('message_from_users')->where('msg_id', $online->msg_id)->get();
            if (count($lab_online_count) > 0) {
                DB::table('message_from_users')->where('msg_id', $online->msg_id)->update([
                    'msg_id' => $online->msg_id,
                    'fullname' => $online->fullname,
                    'email' => $online->email,
                    'msg' => $online->msg,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('message_from_users')->insert([
                    'msg_id' => $online->msg_id,
                    'fullname' => $online->fullname,
                    'email' => $online->email,
                    'msg' => $online->msg,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatients()
    {
        // syncronize patients table from offline to online
        $offline = DB::table('patients')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients')->where('patient_id', $offline->patient_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients')->where('patient_id', $offline->patient_id)->update([
                        'encoders_id' => $offline->encoders_id,
                        'doctors_id' => $offline->doctors_id,
                        'management_id' => $offline->management_id,
                        'user_id' => $offline->user_id,
                        'firstname' => $offline->firstname,
                        'lastname' => $offline->lastname,
                        'middle' => $offline->middle,
                        'email' => $offline->email,
                        'mobile' => $offline->mobile,
                        'telephone' => $offline->telephone,
                        'birthday' => $offline->birthday,
                        'birthplace' => $offline->birthplace,
                        'gender' => $offline->gender,
                        'civil_status' => $offline->civil_status,
                        'religion' => $offline->religion,
                        'height' => $offline->height,
                        'weight' => $offline->weight,
                        'occupation' => $offline->occupation,
                        'street' => $offline->street,
                        'barangay' => $offline->barangay,
                        'city' => $offline->city,
                        'tin' => $offline->tin,
                        'zip' => $offline->zip,
                        'blood_type' => $offline->blood_type,
                        'blood_systolic' => $offline->blood_systolic,
                        'blood_diastolic' => $offline->blood_diastolic,
                        'temperature' => $offline->temperature,
                        'pulse' => $offline->pulse,
                        'rispiratory' => $offline->rispiratory,
                        'glucose' => $offline->glucose,
                        'uric_acid' => $offline->uric_acid,
                        'hepatitis' => $offline->hepatitis,
                        'tuberculosis' => $offline->tuberculosis,
                        'dengue' => $offline->dengue,
                        'cholesterol' => $offline->cholesterol,
                        'allergies' => $offline->allergies,
                        'medication' => $offline->medication,
                        'remarks' => $offline->remarks,
                        'image' => $offline->image,
                        'status' => $offline->status,
                        'doctors_response' => $offline->doctors_response,
                        'is_edited_bydoc' => $offline->is_edited_bydoc,
                        'package_selected' => $offline->package_selected,
                        'join_category' => $offline->join_category,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients')->where('patient_id', $offline_count[0]->patient_id)->update([
                        'encoders_id' => $offline_count[0]->encoders_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'management_id' => $offline_count[0]->management_id,
                        'user_id' => $offline_count[0]->user_id,
                        'firstname' => $offline_count[0]->firstname,
                        'lastname' => $offline_count[0]->lastname,
                        'middle' => $offline_count[0]->middle,
                        'email' => $offline_count[0]->email,
                        'mobile' => $offline_count[0]->mobile,
                        'telephone' => $offline_count[0]->telephone,
                        'birthday' => $offline_count[0]->birthday,
                        'birthplace' => $offline_count[0]->birthplace,
                        'gender' => $offline_count[0]->gender,
                        'civil_status' => $offline_count[0]->civil_status,
                        'religion' => $offline_count[0]->religion,
                        'height' => $offline_count[0]->height,
                        'weight' => $offline_count[0]->weight,
                        'occupation' => $offline_count[0]->occupation,
                        'street' => $offline_count[0]->street,
                        'barangay' => $offline_count[0]->barangay,
                        'city' => $offline_count[0]->city,
                        'tin' => $offline_count[0]->tin,
                        'zip' => $offline_count[0]->zip,
                        'blood_type' => $offline_count[0]->blood_type,
                        'blood_systolic' => $offline_count[0]->blood_systolic,
                        'blood_diastolic' => $offline_count[0]->blood_diastolic,
                        'temperature' => $offline_count[0]->temperature,
                        'pulse' => $offline_count[0]->pulse,
                        'rispiratory' => $offline_count[0]->rispiratory,
                        'glucose' => $offline_count[0]->glucose,
                        'uric_acid' => $offline_count[0]->uric_acid,
                        'hepatitis' => $offline_count[0]->hepatitis,
                        'tuberculosis' => $offline_count[0]->tuberculosis,
                        'dengue' => $offline_count[0]->dengue,
                        'cholesterol' => $offline_count[0]->cholesterol,
                        'allergies' => $offline_count[0]->allergies,
                        'medication' => $offline_count[0]->medication,
                        'remarks' => $offline_count[0]->remarks,
                        'image' => $offline_count[0]->image,
                        'status' => $offline_count[0]->status,
                        'doctors_response' => $offline_count[0]->doctors_response,
                        'is_edited_bydoc' => $offline_count[0]->is_edited_bydoc,
                        'package_selected' => $offline_count[0]->package_selected,
                        'join_category' => $offline_count[0]->join_category,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients')->insert([
                    'patient_id' => $offline->patient_id,
                    'encoders_id' => $offline->encoders_id,
                    'doctors_id' => $offline->doctors_id,
                    'management_id' => $offline->management_id,
                    'user_id' => $offline->user_id,
                    'firstname' => $offline->firstname,
                    'lastname' => $offline->lastname,
                    'middle' => $offline->middle,
                    'email' => $offline->email,
                    'mobile' => $offline->mobile,
                    'telephone' => $offline->telephone,
                    'birthday' => $offline->birthday,
                    'birthplace' => $offline->birthplace,
                    'gender' => $offline->gender,
                    'civil_status' => $offline->civil_status,
                    'religion' => $offline->religion,
                    'height' => $offline->height,
                    'weight' => $offline->weight,
                    'occupation' => $offline->occupation,
                    'street' => $offline->street,
                    'barangay' => $offline->barangay,
                    'city' => $offline->city,
                    'tin' => $offline->tin,
                    'zip' => $offline->zip,
                    'blood_type' => $offline->blood_type,
                    'blood_systolic' => $offline->blood_systolic,
                    'blood_diastolic' => $offline->blood_diastolic,
                    'temperature' => $offline->temperature,
                    'pulse' => $offline->pulse,
                    'rispiratory' => $offline->rispiratory,
                    'glucose' => $offline->glucose,
                    'uric_acid' => $offline->uric_acid,
                    'hepatitis' => $offline->hepatitis,
                    'tuberculosis' => $offline->tuberculosis,
                    'dengue' => $offline->dengue,
                    'cholesterol' => $offline->cholesterol,
                    'allergies' => $offline->allergies,
                    'medication' => $offline->medication,
                    'remarks' => $offline->remarks,
                    'image' => $offline->image,
                    'status' => $offline->status,
                    'doctors_response' => $offline->doctors_response,
                    'is_edited_bydoc' => $offline->is_edited_bydoc,
                    'package_selected' => $offline->package_selected,
                    'join_category' => $offline->join_category,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients table from online to offline
        $online = DB::connection('mysql2')->table('patients')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients')->where('patient_id', $online->patient_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients')->where('patient_id', $online->patient_id)->update([
                    'encoders_id' => $online->encoders_id,
                    'doctors_id' => $online->doctors_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'firstname' => $online->firstname,
                    'lastname' => $online->lastname,
                    'middle' => $online->middle,
                    'email' => $online->email,
                    'mobile' => $online->mobile,
                    'telephone' => $online->telephone,
                    'birthday' => $online->birthday,
                    'birthplace' => $online->birthplace,
                    'gender' => $online->gender,
                    'civil_status' => $online->civil_status,
                    'religion' => $online->religion,
                    'height' => $online->height,
                    'weight' => $online->weight,
                    'occupation' => $online->occupation,
                    'street' => $online->street,
                    'barangay' => $online->barangay,
                    'city' => $online->city,
                    'tin' => $online->tin,
                    'zip' => $online->zip,
                    'blood_type' => $online->blood_type,
                    'blood_systolic' => $online->blood_systolic,
                    'blood_diastolic' => $online->blood_diastolic,
                    'temperature' => $online->temperature,
                    'pulse' => $online->pulse,
                    'rispiratory' => $online->rispiratory,
                    'glucose' => $online->glucose,
                    'uric_acid' => $online->uric_acid,
                    'hepatitis' => $online->hepatitis,
                    'tuberculosis' => $online->tuberculosis,
                    'dengue' => $online->dengue,
                    'cholesterol' => $online->cholesterol,
                    'allergies' => $online->allergies,
                    'medication' => $online->medication,
                    'remarks' => $online->remarks,
                    'image' => $online->image,
                    'status' => $online->status,
                    'doctors_response' => $online->doctors_response,
                    'is_edited_bydoc' => $online->is_edited_bydoc,
                    'package_selected' => $online->package_selected,
                    'join_category' => $online->join_category,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients')->insert([
                    'patient_id' => $online->patient_id,
                    'encoders_id' => $online->encoders_id,
                    'doctors_id' => $online->doctors_id,
                    'management_id' => $online->management_id,
                    'user_id' => $online->user_id,
                    'firstname' => $online->firstname,
                    'lastname' => $online->lastname,
                    'middle' => $online->middle,
                    'email' => $online->email,
                    'mobile' => $online->mobile,
                    'telephone' => $online->telephone,
                    'birthday' => $online->birthday,
                    'birthplace' => $online->birthplace,
                    'gender' => $online->gender,
                    'civil_status' => $online->civil_status,
                    'religion' => $online->religion,
                    'height' => $online->height,
                    'weight' => $online->weight,
                    'occupation' => $online->occupation,
                    'street' => $online->street,
                    'barangay' => $online->barangay,
                    'city' => $online->city,
                    'tin' => $online->tin,
                    'zip' => $online->zip,
                    'blood_type' => $online->blood_type,
                    'blood_systolic' => $online->blood_systolic,
                    'blood_diastolic' => $online->blood_diastolic,
                    'temperature' => $online->temperature,
                    'pulse' => $online->pulse,
                    'rispiratory' => $online->rispiratory,
                    'glucose' => $online->glucose,
                    'uric_acid' => $online->uric_acid,
                    'hepatitis' => $online->hepatitis,
                    'tuberculosis' => $online->tuberculosis,
                    'dengue' => $online->dengue,
                    'cholesterol' => $online->cholesterol,
                    'allergies' => $online->allergies,
                    'medication' => $online->medication,
                    'remarks' => $online->remarks,
                    'image' => $online->image,
                    'status' => $online->status,
                    'doctors_response' => $online->doctors_response,
                    'is_edited_bydoc' => $online->is_edited_bydoc,
                    'package_selected' => $online->package_selected,
                    'join_category' => $online->join_category,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsAddedBy()
    {
        // syncronize patients_added_by table from offline to online
        $offline = DB::table('patients_added_by')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_added_by')->where('added_id', $offline->added_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_added_by')->where('added_id', $offline->added_id)->update([
                        'added_id' => $offline->added_id,
                        'patient_id' => $offline->patient_id,
                        'case_file' => $offline->case_file,
                        'management_id' => $offline->management_id,
                        'department' => $offline->department,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'doctors_on_duty' => $offline->doctors_on_duty,
                        'is_rod_recieved' => $offline->is_rod_recieved,
                        'nurse_recieved_date' => $offline->nurse_recieved_date,
                        'rod_recieved_date' => $offline->rod_recieved_date,
                        'patient_status' => $offline->patient_status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_added_by')->where('added_id', $offline_count[0]->added_id)->update([
                        'added_id' => $offline_count[0]->added_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'case_file' => $offline_count[0]->case_file,
                        'management_id' => $offline_count[0]->management_id,
                        'department' => $offline_count[0]->department,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'doctors_on_duty' => $offline_count[0]->doctors_on_duty,
                        'is_rod_recieved' => $offline_count[0]->is_rod_recieved,
                        'nurse_recieved_date' => $offline_count[0]->nurse_recieved_date,
                        'rod_recieved_date' => $offline_count[0]->rod_recieved_date,
                        'patient_status' => $offline_count[0]->patient_status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_added_by')->insert([
                    'added_id' => $offline->added_id,
                    'patient_id' => $offline->patient_id,
                    'case_file' => $offline->case_file,
                    'management_id' => $offline->management_id,
                    'department' => $offline->department,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'doctors_on_duty' => $offline->doctors_on_duty,
                    'is_rod_recieved' => $offline->is_rod_recieved,
                    'nurse_recieved_date' => $offline->nurse_recieved_date,
                    'rod_recieved_date' => $offline->rod_recieved_date,
                    'patient_status' => $offline->patient_status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_added_by table from online to offline
        $online = DB::connection('mysql2')->table('patients_added_by')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_added_by')->where('added_id', $online->added_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_added_by')->where('added_id', $online->added_id)->update([
                    'added_id' => $online->added_id,
                    'patient_id' => $online->patient_id,
                    'case_file' => $online->case_file,
                    'management_id' => $online->management_id,
                    'department' => $online->department,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'doctors_on_duty' => $online->doctors_on_duty,
                    'is_rod_recieved' => $online->is_rod_recieved,
                    'nurse_recieved_date' => $online->nurse_recieved_date,
                    'rod_recieved_date' => $online->rod_recieved_date,
                    'patient_status' => $online->patient_status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_added_by')->insert([
                    'added_id' => $online->added_id,
                    'patient_id' => $online->patient_id,
                    'case_file' => $online->case_file,
                    'management_id' => $online->management_id,
                    'department' => $online->department,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'doctors_on_duty' => $online->doctors_on_duty,
                    'is_rod_recieved' => $online->is_rod_recieved,
                    'nurse_recieved_date' => $online->nurse_recieved_date,
                    'rod_recieved_date' => $online->rod_recieved_date,
                    'patient_status' => $online->patient_status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsCholesterolHistory()
    {
        // syncronize patients_cholesterol_history table from offline to online
        $offline = DB::table('patients_cholesterol_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_cholesterol_history')->where('cholesterol_id', $offline->cholesterol_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_cholesterol_history')->where('cholesterol_id', $offline->cholesterol_id)->update([
                        'patients_id' => $offline->patients_id,
                        'cholesterol' => $offline->cholesterol,
                        'added_by' => $offline->added_by,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_cholesterol_history')->where('cholesterol_id', $offline_count[0]->cholesterol_id)->update([
                        'patients_id' => $offline_count[0]->patients_id,
                        'cholesterol' => $offline_count[0]->cholesterol,
                        'added_by' => $offline_count[0]->added_by,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_cholesterol_history')->insert([
                    'cholesterol_id' => $offline->cholesterol_id,
                    'patients_id' => $offline->patients_id,
                    'cholesterol' => $offline->cholesterol,
                    'added_by' => $offline->added_by,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_cholesterol_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_cholesterol_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_cholesterol_history')->where('cholesterol_id', $online->cholesterol_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_cholesterol_history')->where('cholesterol_id', $online->cholesterol_id)->update([
                    'patients_id' => $online->patients_id,
                    'cholesterol' => $online->cholesterol,
                    'added_by' => $online->added_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_cholesterol_history')->insert([
                    'cholesterol_id' => $online->cholesterol_id,
                    'patients_id' => $online->patients_id,
                    'cholesterol' => $online->cholesterol,
                    'added_by' => $online->added_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsCredit()
    {
        // syncronize patients_credit table from offline to online
        $offline = DB::table('patients_credit')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_credit')->where('loadout_id', $offline->loadout_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_credit')->where('loadout_id', $offline->loadout_id)->update([
                        'loadout_id' => $offline->loadout_id,
                        'user_id' => $offline->user_id,
                        'account_no' => $offline->account_no,
                        'credit' => $offline->credit,
                        'trace_no' => $offline->trace_no,
                        'purchase_on' => $offline->purchase_on,
                        'process_by' => $offline->process_by,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_credit')->where('loadout_id', $offline_count[0]->loadout_id)->update([
                        'loadout_id' => $offline_count[0]->loadout_id,
                        'user_id' => $offline_count[0]->user_id,
                        'account_no' => $offline_count[0]->account_no,
                        'credit' => $offline_count[0]->credit,
                        'trace_no' => $offline_count[0]->trace_no,
                        'purchase_on' => $offline_count[0]->purchase_on,
                        'process_by' => $offline_count[0]->process_by,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_credit')->insert([
                    'loadout_id' => $offline->loadout_id,
                    'user_id' => $offline->user_id,
                    'account_no' => $offline->account_no,
                    'credit' => $offline->credit,
                    'trace_no' => $offline->trace_no,
                    'purchase_on' => $offline->purchase_on,
                    'process_by' => $offline->process_by,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_credit table from online to offline
        $online = DB::connection('mysql2')->table('patients_credit')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_credit')->where('loadout_id', $online->loadout_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_credit')->where('loadout_id', $online->loadout_id)->update([
                    'loadout_id' => $online->loadout_id,
                    'user_id' => $online->user_id,
                    'account_no' => $online->account_no,
                    'credit' => $online->credit,
                    'trace_no' => $online->trace_no,
                    'purchase_on' => $online->purchase_on,
                    'process_by' => $online->process_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_credit')->insert([
                    'loadout_id' => $online->loadout_id,
                    'user_id' => $online->user_id,
                    'account_no' => $online->account_no,
                    'credit' => $online->credit,
                    'trace_no' => $online->trace_no,
                    'purchase_on' => $online->purchase_on,
                    'process_by' => $online->process_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsCreditTransaction()
    {
        // syncronize patients_credit_transaction table from offline to online
        $offline = DB::table('patients_credit_transaction')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_credit_transaction')->where('transaction_id', $offline->transaction_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_credit_transaction')->where('transaction_id', $offline->transaction_id)->update([
                        'transaction_id' => $offline->transaction_id,
                        'reference_no' => $offline->reference_no,
                        'patient_id' => $offline->patient_id,
                        'doctors_id' => $offline->doctors_id,
                        'doctors_service_id' => $offline->doctors_service_id,
                        'transaction_cost' => $offline->transaction_cost,
                        'transaction_status' => $offline->transaction_status,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_credit_transaction')->where('transaction_id', $offline_count[0]->transaction_id)->update([
                        'transaction_id' => $offline_count[0]->transaction_id,
                        'reference_no' => $offline_count[0]->reference_no,
                        'patient_id' => $offline_count[0]->patient_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'doctors_service_id' => $offline_count[0]->doctors_service_id,
                        'transaction_cost' => $offline_count[0]->transaction_cost,
                        'transaction_status' => $offline_count[0]->transaction_status,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_credit_transaction')->insert([
                    'transaction_id' => $offline->transaction_id,
                    'reference_no' => $offline->reference_no,
                    'patient_id' => $offline->patient_id,
                    'doctors_id' => $offline->doctors_id,
                    'doctors_service_id' => $offline->doctors_service_id,
                    'transaction_cost' => $offline->transaction_cost,
                    'transaction_status' => $offline->transaction_status,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_credit_transaction table from online to offline
        $online = DB::connection('mysql2')->table('patients_credit_transaction')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_credit_transaction')->where('transaction_id', $online->transaction_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_credit_transaction')->where('transaction_id', $online->transaction_id)->update([
                    'transaction_id' => $online->transaction_id,
                    'reference_no' => $online->reference_no,
                    'patient_id' => $online->patient_id,
                    'doctors_id' => $online->doctors_id,
                    'doctors_service_id' => $online->doctors_service_id,
                    'transaction_cost' => $online->transaction_cost,
                    'transaction_status' => $online->transaction_status,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_credit_transaction')->insert([
                    'transaction_id' => $online->transaction_id,
                    'reference_no' => $online->reference_no,
                    'patient_id' => $online->patient_id,
                    'doctors_id' => $online->doctors_id,
                    'doctors_service_id' => $online->doctors_service_id,
                    'transaction_cost' => $online->transaction_cost,
                    'transaction_status' => $online->transaction_status,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsDiagnosis()
    {
        // syncronize patients_diagnosis table from offline to online
        $offline = DB::table('patients_diagnosis')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_diagnosis')->where('pd_id', $offline->pd_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_diagnosis')->where('pd_id', $offline->pd_id)->update([
                        'pd_id' => $offline->pd_id,
                        'patient_id' => $offline->patient_id,
                        'doctor_id' => $offline->doctor_id,
                        'diagnosis' => $offline->diagnosis,
                        'remarks' => $offline->remarks,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_diagnosis')->where('pd_id', $offline_count[0]->pd_id)->update([
                        'pd_id' => $offline_count[0]->pd_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'diagnosis' => $offline_count[0]->diagnosis,
                        'remarks' => $offline_count[0]->remarks,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_diagnosis')->insert([
                    'pd_id' => $offline->pd_id,
                    'patient_id' => $offline->patient_id,
                    'doctor_id' => $offline->doctor_id,
                    'diagnosis' => $offline->diagnosis,
                    'remarks' => $offline->remarks,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_diagnosis table from online to offline
        $online = DB::connection('mysql2')->table('patients_diagnosis')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_diagnosis')->where('pd_id', $online->pd_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_diagnosis')->where('pd_id', $online->pd_id)->update([
                    'pd_id' => $online->pd_id,
                    'patient_id' => $online->patient_id,
                    'doctor_id' => $online->doctor_id,
                    'diagnosis' => $online->diagnosis,
                    'remarks' => $online->remarks,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_diagnosis')->insert([
                    'pd_id' => $online->pd_id,
                    'patient_id' => $online->patient_id,
                    'doctor_id' => $online->doctor_id,
                    'diagnosis' => $online->diagnosis,
                    'remarks' => $online->remarks,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsDiets()
    {
        // syncronize patients_diets table from offline to online
        $offline = DB::table('patients_diets')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_diets')->where('pd_id', $offline->pd_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_diets')->where('pd_id', $offline->pd_id)->update([
                        'pd_id' => $offline->pd_id,
                        'patient_id' => $offline->patient_id,
                        'doctor_id' => $offline->doctor_id,
                        'meals' => $offline->meals,
                        'description' => $offline->description,
                        'is_suggested' => $offline->is_suggested,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_diets')->where('pd_id', $offline_count[0]->pd_id)->update([
                        'pd_id' => $offline_count[0]->pd_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'doctor_id' => $offline_count[0]->doctor_id,
                        'meals' => $offline_count[0]->meals,
                        'description' => $offline_count[0]->description,
                        'is_suggested' => $offline_count[0]->is_suggested,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_diets')->insert([
                    'pd_id' => $offline->pd_id,
                    'patient_id' => $offline->patient_id,
                    'doctor_id' => $offline->doctor_id,
                    'meals' => $offline->meals,
                    'description' => $offline->description,
                    'is_suggested' => $offline->is_suggested,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_diets table from online to offline
        $online = DB::connection('mysql2')->table('patients_diets')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_diets')->where('pd_id', $online->pd_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_diets')->where('pd_id', $online->pd_id)->update([
                    'pd_id' => $online->pd_id,
                    'patient_id' => $online->patient_id,
                    'doctor_id' => $online->doctor_id,
                    'meals' => $online->meals,
                    'description' => $online->description,
                    'is_suggested' => $online->is_suggested,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_diets')->insert([
                    'pd_id' => $online->pd_id,
                    'patient_id' => $online->patient_id,
                    'doctor_id' => $online->doctor_id,
                    'meals' => $online->meals,
                    'description' => $online->description,
                    'is_suggested' => $online->is_suggested,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsDischarged()
    {
        // syncronize patients_discharged table from offline to online
        $offline = DB::table('patients_discharged')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_discharged')->where('dis_id', $offline->dis_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_discharged')->where('dis_id', $offline->dis_id)->update([
                        'dis_id' => $offline->dis_id,
                        'patient_id' => $offline->patient_id,
                        'management_id' => $offline->management_id,
                        'cashier_id' => $offline->cashier_id,
                        'receipt_id' => $offline->receipt_id,
                        'case_file' => $offline->case_file,
                        'full_name' => $offline->full_name,
                        'full_address' => $offline->full_address,
                        'total' => $offline->total,
                        'amount_paid' => $offline->amount_paid,
                        'balance' => $offline->balance,
                        'status' => $offline->status,
                        'reason' => $offline->reason,
                        'screenshot' => $offline->screenshot,
                        'invoice' => $offline->invoice,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_discharged')->where('dis_id', $offline_count[0]->dis_id)->update([
                        'dis_id' => $offline_count[0]->dis_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'management_id' => $offline_count[0]->management_id,
                        'cashier_id' => $offline_count[0]->cashier_id,
                        'receipt_id' => $offline_count[0]->receipt_id,
                        'case_file' => $offline_count[0]->case_file,
                        'full_name' => $offline_count[0]->full_name,
                        'full_address' => $offline_count[0]->full_address,
                        'total' => $offline_count[0]->total,
                        'amount_paid' => $offline_count[0]->amount_paid,
                        'balance' => $offline_count[0]->balance,
                        'status' => $offline_count[0]->status,
                        'reason' => $offline_count[0]->reason,
                        'screenshot' => $offline_count[0]->screenshot,
                        'invoice' => $offline_count[0]->invoice,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_discharged')->insert([
                    'dis_id' => $offline->dis_id,
                    'patient_id' => $offline->patient_id,
                    'management_id' => $offline->management_id,
                    'cashier_id' => $offline->cashier_id,
                    'receipt_id' => $offline->receipt_id,
                    'case_file' => $offline->case_file,
                    'full_name' => $offline->full_name,
                    'full_address' => $offline->full_address,
                    'total' => $offline->total,
                    'amount_paid' => $offline->amount_paid,
                    'balance' => $offline->balance,
                    'status' => $offline->status,
                    'reason' => $offline->reason,
                    'screenshot' => $offline->screenshot,
                    'invoice' => $offline->invoice,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_discharged table from online to offline
        $online = DB::connection('mysql2')->table('patients_discharged')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_discharged')->where('dis_id', $online->dis_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_discharged')->where('dis_id', $online->dis_id)->update([
                    'dis_id' => $online->dis_id,
                    'patient_id' => $online->patient_id,
                    'management_id' => $online->management_id,
                    'cashier_id' => $online->cashier_id,
                    'receipt_id' => $online->receipt_id,
                    'case_file' => $online->case_file,
                    'full_name' => $online->full_name,
                    'full_address' => $online->full_address,
                    'total' => $online->total,
                    'amount_paid' => $online->amount_paid,
                    'balance' => $online->balance,
                    'status' => $online->status,
                    'reason' => $online->reason,
                    'screenshot' => $online->screenshot,
                    'invoice' => $online->invoice,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_discharged')->insert([
                    'dis_id' => $online->dis_id,
                    'patient_id' => $online->patient_id,
                    'management_id' => $online->management_id,
                    'cashier_id' => $online->cashier_id,
                    'receipt_id' => $online->receipt_id,
                    'case_file' => $online->case_file,
                    'full_name' => $online->full_name,
                    'full_address' => $online->full_address,
                    'total' => $online->total,
                    'amount_paid' => $online->amount_paid,
                    'balance' => $online->balance,
                    'status' => $online->status,
                    'reason' => $online->reason,
                    'screenshot' => $online->screenshot,
                    'invoice' => $online->invoice,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsFamilyHistories()
    {
        // syncronize patients_family_histories table from offline to online
        $offline = DB::table('patients_family_histories')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_family_histories')->where('pfh_id', $offline->pfh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_family_histories')->where('pfh_id', $offline->pfh_id)->update([
                        'pfh_id' => $offline->pfh_id,
                        'patient_id' => $offline->patient_id,
                        'name' => $offline->name,
                        'address' => $offline->address,
                        'birthday' => $offline->birthday,
                        'occupation' => $offline->occupation,
                        'health_status' => $offline->health_status,
                        'category' => $offline->category,
                        'is_deceased' => $offline->is_deceased,
                        'is_deceased_reason' => $offline->is_deceased_reason,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_family_histories')->where('pfh_id', $offline_count[0]->pfh_id)->update([
                        'pfh_id' => $offline_count[0]->pfh_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'name' => $offline_count[0]->name,
                        'address' => $offline_count[0]->address,
                        'birthday' => $offline_count[0]->birthday,
                        'occupation' => $offline_count[0]->occupation,
                        'health_status' => $offline_count[0]->health_status,
                        'category' => $offline_count[0]->category,
                        'is_deceased' => $offline_count[0]->is_deceased,
                        'is_deceased_reason' => $offline_count[0]->is_deceased_reason,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_family_histories')->insert([
                    'pfh_id' => $offline->pfh_id,
                    'patient_id' => $offline->patient_id,
                    'name' => $offline->name,
                    'address' => $offline->address,
                    'birthday' => $offline->birthday,
                    'occupation' => $offline->occupation,
                    'health_status' => $offline->health_status,
                    'category' => $offline->category,
                    'is_deceased' => $offline->is_deceased,
                    'is_deceased_reason' => $offline->is_deceased_reason,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_family_histories table from online to offline
        $online = DB::connection('mysql2')->table('patients_family_histories')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_family_histories')->where('pfh_id', $online->pfh_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_family_histories')->where('pfh_id', $online->pfh_id)->update([
                    'pfh_id' => $online->pfh_id,
                    'patient_id' => $online->patient_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'birthday' => $online->birthday,
                    'occupation' => $online->occupation,
                    'health_status' => $online->health_status,
                    'category' => $online->category,
                    'is_deceased' => $online->is_deceased,
                    'is_deceased_reason' => $online->is_deceased_reason,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_family_histories')->insert([
                    'pfh_id' => $online->pfh_id,
                    'patient_id' => $online->patient_id,
                    'name' => $online->name,
                    'address' => $online->address,
                    'birthday' => $online->birthday,
                    'occupation' => $online->occupation,
                    'health_status' => $online->health_status,
                    'category' => $online->category,
                    'is_deceased' => $online->is_deceased,
                    'is_deceased_reason' => $online->is_deceased_reason,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsFamilyHistory()
    {
        // syncronize patients_family_history table from offline to online
        $offline = DB::table('patients_family_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_family_history')->where('dph_id', $offline->dph_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_family_history')->where('dph_id', $offline->dph_id)->update([
                        'dph_id' => $offline->dph_id,
                        'doctors_id' => $offline->doctors_id,
                        'patient_id' => $offline->patient_id,
                        'family_history' => $offline->family_history,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_family_history')->where('dph_id', $offline_count[0]->dph_id)->update([
                        'dph_id' => $offline_count[0]->dph_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'family_history' => $offline_count[0]->family_history,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_family_history')->insert([
                    'dph_id' => $offline->dph_id,
                    'doctors_id' => $offline->doctors_id,
                    'patient_id' => $offline->patient_id,
                    'family_history' => $offline->family_history,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_family_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_family_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_family_history')->where('dph_id', $online->dph_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_family_history')->where('dph_id', $online->dph_id)->update([
                    'dph_id' => $online->dph_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'family_history' => $online->family_history,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_family_history')->insert([
                    'dph_id' => $online->dph_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'family_history' => $online->family_history,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsGlucoseHistory()
    {
        // syncronize patients_glucose_history table from offline to online
        $offline = DB::table('patients_glucose_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_glucose_history')->where('pgh_id', $offline->pgh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_glucose_history')->where('pgh_id', $offline->pgh_id)->update([
                        'pgh_id' => $offline->pgh_id,
                        'patients_id' => $offline->patients_id,
                        'glucose' => $offline->glucose,
                        'added_by' => $offline->added_by,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_glucose_history')->where('pgh_id', $offline_count[0]->pgh_id)->update([
                        'pgh_id' => $offline_count[0]->pgh_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'glucose' => $offline_count[0]->glucose,
                        'added_by' => $offline_count[0]->added_by,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_glucose_history')->insert([
                    'pgh_id' => $offline->pgh_id,
                    'patients_id' => $offline->patients_id,
                    'glucose' => $offline->glucose,
                    'added_by' => $offline->added_by,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_glucose_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_glucose_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_glucose_history')->where('pgh_id', $online->pgh_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_glucose_history')->where('pgh_id', $online->pgh_id)->update([
                    'pgh_id' => $online->pgh_id,
                    'patients_id' => $online->patients_id,
                    'glucose' => $online->glucose,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_glucose_history')->insert([
                    'pgh_id' => $online->pgh_id,
                    'patients_id' => $online->patients_id,
                    'glucose' => $online->glucose,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientsHistory()
    {
        // syncronize patients_history table from offline to online
        $offline = DB::table('patients_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history')->where('ph_id', $offline->ph_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history')->where('ph_id', $offline->ph_id)->update([
                        'ph_id' => $offline->ph_id,
                        'patient_id' => $offline->patient_id,
                        'street' => $offline->street,
                        'barangay' => $offline->barangay,
                        'city' => $offline->city,
                        'zip' => $offline->zip,
                        'height' => $offline->height,
                        'weight' => $offline->weight,
                        'occupation' => $offline->occupation,
                        'allergies' => $offline->allergies,
                        'medication' => $offline->medication,
                        'remarks' => $offline->remarks,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_history')->where('ph_id', $offline_count[0]->ph_id)->update([
                        'ph_id' => $offline_count[0]->ph_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'street' => $offline_count[0]->street,
                        'barangay' => $offline_count[0]->barangay,
                        'city' => $offline_count[0]->city,
                        'zip' => $offline_count[0]->zip,
                        'height' => $offline_count[0]->height,
                        'weight' => $offline_count[0]->weight,
                        'occupation' => $offline_count[0]->occupation,
                        'allergies' => $offline_count[0]->allergies,
                        'medication' => $offline_count[0]->medication,
                        'remarks' => $offline_count[0]->remarks,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history')->insert([
                    'ph_id' => $offline->ph_id,
                    'patient_id' => $offline->patient_id,
                    'street' => $offline->street,
                    'barangay' => $offline->barangay,
                    'city' => $offline->city,
                    'zip' => $offline->zip,
                    'height' => $offline->height,
                    'weight' => $offline->weight,
                    'occupation' => $offline->occupation,
                    'allergies' => $offline->allergies,
                    'medication' => $offline->medication,
                    'remarks' => $offline->remarks,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history')->where('ph_id', $online->ph_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history')->where('ph_id', $online->ph_id)->update([
                    'ph_id' => $online->ph_id,
                    'patient_id' => $online->patient_id,
                    'street' => $online->street,
                    'barangay' => $online->barangay,
                    'city' => $online->city,
                    'zip' => $online->zip,
                    'height' => $online->height,
                    'weight' => $online->weight,
                    'occupation' => $online->occupation,
                    'allergies' => $online->allergies,
                    'medication' => $online->medication,
                    'remarks' => $online->remarks,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_history')->insert([
                    'ph_id' => $online->ph_id,
                    'patient_id' => $online->patient_id,
                    'street' => $online->street,
                    'barangay' => $online->barangay,
                    'city' => $online->city,
                    'zip' => $online->zip,
                    'height' => $online->height,
                    'weight' => $online->weight,
                    'occupation' => $online->occupation,
                    'allergies' => $online->allergies,
                    'medication' => $online->medication,
                    'remarks' => $online->remarks,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryAttachment()
    {
        // syncronize patients_history_attachment table from offline to online
        $offline = DB::table('patients_history_attachment')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_attachment')->where('pha_id', $offline->pha_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_attachment')->where('pha_id', $offline->pha_id)->update([
                        'pha_id' => $offline->pha_id,
                        'history_attachment_id' => $offline->history_attachment_id,
                        'patient_id' => $offline->patient_id,
                        'attachment' => $offline->attachment,
                        'remarks' => $offline->remarks,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_attachment')->where('pha_id', $offline_count[0]->pha_id)->update([
                        'pha_id' => $offline_count[0]->pha_id,
                        'history_attachment_id' => $offline_count[0]->history_attachment_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'attachment' => $offline_count[0]->attachment,
                        'remarks' => $offline_count[0]->remarks,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_attachment')->insert([
                    'pha_id' => $offline->pha_id,
                    'history_attachment_id' => $offline->history_attachment_id,
                    'patient_id' => $offline->patient_id,
                    'attachment' => $offline->attachment,
                    'remarks' => $offline->remarks,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_attachment table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_attachment')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_attachment')->where('pha_id', $online->pha_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_attachment')->where('pha_id', $online->pha_id)->update([
                    'pha_id' => $online->pha_id,
                    'history_attachment_id' => $online->history_attachment_id,
                    'patient_id' => $online->patient_id,
                    'attachment' => $online->attachment,
                    'remarks' => $online->remarks,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_attachment')->insert([
                    'pha_id' => $online->pha_id,
                    'history_attachment_id' => $online->history_attachment_id,
                    'patient_id' => $online->patient_id,
                    'attachment' => $online->attachment,
                    'remarks' => $online->remarks,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryCalcium()
    {
        // syncronize patients_history_calcium table from offline to online
        $offline = DB::table('patients_history_calcium')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_calcium')->where('phc_id', $offline->phc_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_calcium')->where('phc_id', $offline->phc_id)->update([
                        'phc_id' => $offline->phc_id,
                        'patient_id' => $offline->patient_id,
                        'calcium' => $offline->calcium,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_calcium')->where('phc_id', $offline_count[0]->phc_id)->update([
                        'phc_id' => $offline_count[0]->phc_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'calcium' => $offline_count[0]->calcium,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_calcium')->insert([
                    'phc_id' => $offline->phc_id,
                    'patient_id' => $offline->patient_id,
                    'calcium' => $offline->calcium,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_calcium table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_calcium')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_calcium')->where('phc_id', $online->phc_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_calcium')->where('phc_id', $online->phc_id)->update([
                    'phc_id' => $online->phc_id,
                    'patient_id' => $online->patient_id,
                    'calcium' => $online->calcium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_calcium')->insert([
                    'phc_id' => $online->phc_id,
                    'patient_id' => $online->patient_id,
                    'calcium' => $online->calcium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryChloride()
    {
        // syncronize patients_history_chloride table from offline to online
        $offline = DB::table('patients_history_chloride')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_chloride')->where('phc_id', $offline->phc_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_chloride')->where('phc_id', $offline->phc_id)->update([
                        'phc_id' => $offline->phc_id,
                        'patient_id' => $offline->patient_id,
                        'chloride' => $offline->chloride,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_chloride')->where('phc_id', $offline_count[0]->phc_id)->update([
                        'phc_id' => $offline_count[0]->phc_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'chloride' => $offline_count[0]->chloride,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_chloride')->insert([
                    'phc_id' => $offline->phc_id,
                    'patient_id' => $offline->patient_id,
                    'chloride' => $offline->chloride,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_chloride table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_chloride')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_chloride')->where('phc_id', $online->phc_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_chloride')->where('phc_id', $online->phc_id)->update([
                    'phc_id' => $online->phc_id,
                    'patient_id' => $online->patient_id,
                    'chloride' => $online->chloride,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_chloride')->insert([
                    'phc_id' => $online->phc_id,
                    'patient_id' => $online->patient_id,
                    'chloride' => $online->chloride,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryCreatinine()
    {
        // syncronize patients_history_creatinine table from offline to online
        $offline = DB::table('patients_history_creatinine')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_creatinine')->where('phc_id', $offline->phc_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_creatinine')->where('phc_id', $offline->phc_id)->update([
                        'phc_id' => $offline->phc_id,
                        'patient_id' => $offline->patient_id,
                        'creatinine' => $offline->creatinine,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_creatinine')->where('phc_id', $offline_count[0]->phc_id)->update([
                        'phc_id' => $offline_count[0]->phc_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'creatinine' => $offline_count[0]->creatinine,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_creatinine')->insert([
                    'phc_id' => $offline->phc_id,
                    'patient_id' => $offline->patient_id,
                    'creatinine' => $offline->creatinine,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_creatinine table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_creatinine')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_creatinine')->where('phc_id', $online->phc_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_creatinine')->where('phc_id', $online->phc_id)->update([
                    'phc_id' => $online->phc_id,
                    'patient_id' => $online->patient_id,
                    'creatinine' => $online->creatinine,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_creatinine')->insert([
                    'phc_id' => $online->phc_id,
                    'patient_id' => $online->patient_id,
                    'creatinine' => $online->creatinine,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryHDL()
    {
        // syncronize patients_history_hdl table from offline to online
        $offline = DB::table('patients_history_hdl')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_hdl')->where('phh_id', $offline->phh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_hdl')->where('phh_id', $offline->phh_id)->update([
                        'phh_id' => $offline->phh_id,
                        'patient_id' => $offline->patient_id,
                        'high_density_lipoproteins' => $offline->high_density_lipoproteins,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_hdl')->where('phh_id', $offline_count[0]->phh_id)->update([
                        'phh_id' => $offline_count[0]->phh_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'high_density_lipoproteins' => $offline_count[0]->high_density_lipoproteins,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_hdl')->insert([
                    'phh_id' => $offline->phh_id,
                    'patient_id' => $offline->patient_id,
                    'high_density_lipoproteins' => $offline->high_density_lipoproteins,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_hdl table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_hdl')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_hdl')->where('phh_id', $online->phh_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_hdl')->where('phh_id', $online->phh_id)->update([
                    'phh_id' => $online->phh_id,
                    'patient_id' => $online->patient_id,
                    'high_density_lipoproteins' => $online->high_density_lipoproteins,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_hdl')->insert([
                    'phh_id' => $online->phh_id,
                    'patient_id' => $online->patient_id,
                    'high_density_lipoproteins' => $online->high_density_lipoproteins,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryLDL()
    {
        // syncronize patients_history_ldl table from offline to online
        $offline = DB::table('patients_history_ldl')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_ldl')->where('phl_id', $offline->phl_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_ldl')->where('phl_id', $offline->phl_id)->update([
                        'phl_id' => $offline->phl_id,
                        'patient_id' => $offline->patient_id,
                        'low_density_lipoprotein' => $offline->low_density_lipoprotein,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_ldl')->where('phl_id', $offline_count[0]->phl_id)->update([
                        'phl_id' => $offline_count[0]->phl_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'low_density_lipoprotein' => $offline_count[0]->low_density_lipoprotein,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_ldl')->insert([
                    'phl_id' => $offline->phl_id,
                    'patient_id' => $offline->patient_id,
                    'low_density_lipoprotein' => $offline->low_density_lipoprotein,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_ldl table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_ldl')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_ldl')->where('phl_id', $online->phl_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_ldl')->where('phl_id', $online->phl_id)->update([
                    'phl_id' => $online->phl_id,
                    'patient_id' => $online->patient_id,
                    'low_density_lipoprotein' => $online->low_density_lipoprotein,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_ldl')->insert([
                    'phl_id' => $online->phl_id,
                    'patient_id' => $online->patient_id,
                    'low_density_lipoprotein' => $online->low_density_lipoprotein,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryLithium()
    {
        // syncronize patients_history_lithium table from offline to online
        $offline = DB::table('patients_history_lithium')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_lithium')->where('phl_id', $offline->phl_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_lithium')->where('phl_id', $offline->phl_id)->update([
                        'phl_id' => $offline->phl_id,
                        'patient_id' => $offline->patient_id,
                        'lithium' => $offline->lithium,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_lithium')->where('phl_id', $offline_count[0]->phl_id)->update([
                        'phl_id' => $offline_count[0]->phl_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'lithium' => $offline_count[0]->lithium,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_lithium')->insert([
                    'phl_id' => $offline->phl_id,
                    'patient_id' => $offline->patient_id,
                    'lithium' => $offline->lithium,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_lithium table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_lithium')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_lithium')->where('phl_id', $online->phl_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_lithium')->where('phl_id', $online->phl_id)->update([
                    'phl_id' => $online->phl_id,
                    'patient_id' => $online->patient_id,
                    'lithium' => $online->lithium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_lithium')->insert([
                    'phl_id' => $online->phl_id,
                    'patient_id' => $online->patient_id,
                    'lithium' => $online->lithium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryMagnessium()
    {
        // syncronize patients_history_magnessium table from offline to online
        $offline = DB::table('patients_history_magnessium')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_magnessium')->where('phm_id', $offline->phm_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_magnessium')->where('phm_id', $offline->phm_id)->update([
                        'phm_id' => $offline->phm_id,
                        'patient_id' => $offline->patient_id,
                        'magnessium' => $offline->magnessium,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_magnessium')->where('phm_id', $offline_count[0]->phm_id)->update([
                        'phm_id' => $offline_count[0]->phm_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'magnessium' => $offline_count[0]->magnessium,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_magnessium')->insert([
                    'phm_id' => $offline->phm_id,
                    'patient_id' => $offline->patient_id,
                    'magnessium' => $offline->magnessium,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_magnessium table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_magnessium')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_magnessium')->where('phm_id', $online->phm_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_magnessium')->where('phm_id', $online->phm_id)->update([
                    'phm_id' => $online->phm_id,
                    'patient_id' => $online->patient_id,
                    'magnessium' => $online->magnessium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_magnessium')->insert([
                    'phm_id' => $online->phm_id,
                    'patient_id' => $online->patient_id,
                    'magnessium' => $online->magnessium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryPotassium()
    {
        // syncronize patients_history_potassium table from offline to online
        $offline = DB::table('patients_history_potassium')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_potassium')->where('php_id', $offline->php_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_potassium')->where('php_id', $offline->php_id)->update([
                        'php_id' => $offline->php_id,
                        'patient_id' => $offline->patient_id,
                        'potassium' => $offline->potassium,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_potassium')->where('php_id', $offline_count[0]->php_id)->update([
                        'php_id' => $offline_count[0]->php_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'potassium' => $offline_count[0]->potassium,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_potassium')->insert([
                    'php_id' => $offline->php_id,
                    'patient_id' => $offline->patient_id,
                    'potassium' => $offline->potassium,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_potassium table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_potassium')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_potassium')->where('php_id', $online->php_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_potassium')->where('php_id', $online->php_id)->update([
                    'php_id' => $online->php_id,
                    'patient_id' => $online->patient_id,
                    'potassium' => $online->potassium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_potassium')->insert([
                    'php_id' => $online->php_id,
                    'patient_id' => $online->patient_id,
                    'potassium' => $online->potassium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryProtein()
    {
        // syncronize patients_history_protein table from offline to online
        $offline = DB::table('patients_history_protein')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_protein')->where('php_id', $offline->php_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_protein')->where('php_id', $offline->php_id)->update([
                        'php_id' => $offline->php_id,
                        'patient_id' => $offline->patient_id,
                        'protein' => $offline->protein,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_protein')->where('php_id', $offline_count[0]->php_id)->update([
                        'php_id' => $offline_count[0]->php_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'protein' => $offline_count[0]->protein,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_protein')->insert([
                    'php_id' => $offline->php_id,
                    'patient_id' => $offline->patient_id,
                    'protein' => $offline->protein,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_protein table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_protein')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_protein')->where('php_id', $online->php_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_protein')->where('php_id', $online->php_id)->update([
                    'php_id' => $online->php_id,
                    'patient_id' => $online->patient_id,
                    'protein' => $online->protein,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_protein')->insert([
                    'php_id' => $online->php_id,
                    'patient_id' => $online->patient_id,
                    'protein' => $online->protein,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistorySoduim()
    {
        // syncronize patients_history_sodium table from offline to online
        $offline = DB::table('patients_history_sodium')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_history_sodium')->where('phs_id', $offline->phs_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_history_sodium')->where('phs_id', $offline->phs_id)->update([
                        'phs_id' => $offline->phs_id,
                        'patient_id' => $offline->patient_id,
                        'sodium' => $offline->sodium,
                        'added_by' => $offline->added_by,
                        'adder_type' => $offline->adder_type,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_history_sodium')->where('phs_id', $offline_count[0]->phs_id)->update([
                        'phs_id' => $offline_count[0]->phs_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'sodium' => $offline_count[0]->sodium,
                        'added_by' => $offline_count[0]->added_by,
                        'adder_type' => $offline_count[0]->adder_type,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_history_sodium')->insert([
                    'phs_id' => $offline->phs_id,
                    'patient_id' => $offline->patient_id,
                    'sodium' => $offline->sodium,
                    'added_by' => $offline->added_by,
                    'adder_type' => $offline->adder_type,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_history_sodium table from online to offline
        $online = DB::connection('mysql2')->table('patients_history_sodium')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_history_sodium')->where('phs_id', $online->phs_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_history_sodium')->where('phs_id', $online->phs_id)->update([
                    'phs_id' => $online->phs_id,
                    'patient_id' => $online->patient_id,
                    'sodium' => $online->sodium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_history_sodium')->insert([
                    'phs_id' => $online->phs_id,
                    'patient_id' => $online->patient_id,
                    'sodium' => $online->sodium,
                    'added_by' => $online->added_by,
                    'adder_type' => $online->adder_type,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientHistoryLabBP()
    {
        // syncronize patients_lab_history table from offline to online
        $offline = DB::table('patients_lab_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_lab_history')->where('plh_id', $offline->plh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_lab_history')->where('plh_id', $offline->plh_id)->update([
                        'plh_id' => $offline->plh_id,
                        'patients_id' => $offline->patients_id,
                        'systolic' => $offline->systolic,
                        'diastolic' => $offline->diastolic,
                        'added_by' => $offline->added_by,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_lab_history')->where('plh_id', $offline_count[0]->plh_id)->update([
                        'plh_id' => $offline_count[0]->plh_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'systolic' => $offline_count[0]->systolic,
                        'diastolic' => $offline_count[0]->diastolic,
                        'added_by' => $offline_count[0]->added_by,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_lab_history')->insert([
                    'plh_id' => $offline->plh_id,
                    'patients_id' => $offline->patients_id,
                    'systolic' => $offline->systolic,
                    'diastolic' => $offline->diastolic,
                    'added_by' => $offline->added_by,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_lab_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_lab_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_lab_history')->where('plh_id', $online->plh_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_lab_history')->where('plh_id', $online->plh_id)->update([
                    'plh_id' => $online->plh_id,
                    'patients_id' => $online->patients_id,
                    'systolic' => $online->systolic,
                    'diastolic' => $online->diastolic,
                    'added_by' => $online->added_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_lab_history')->insert([
                    'plh_id' => $online->plh_id,
                    'patients_id' => $online->patients_id,
                    'systolic' => $online->systolic,
                    'diastolic' => $online->diastolic,
                    'added_by' => $online->added_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientPainHistory()
    {
        // syncronize patients_pain_history table from offline to online
        $offline = DB::table('patients_pain_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_pain_history')->where('pph_id', $offline->pph_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_pain_history')->where('pph_id', $offline->pph_id)->update([
                        'pph_id' => $offline->pph_id,
                        'patient_id' => $offline->patient_id,
                        'pain_position_x' => $offline->pain_position_x,
                        'pain_position_y' => $offline->pain_position_y,
                        'pain_level' => $offline->pain_level,
                        'description' => $offline->description,
                        'facing' => $offline->facing,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_pain_history')->where('pph_id', $offline_count[0]->pph_id)->update([
                        'pph_id' => $offline_count[0]->pph_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'pain_position_x' => $offline_count[0]->pain_position_x,
                        'pain_position_y' => $offline_count[0]->pain_position_y,
                        'pain_level' => $offline_count[0]->pain_level,
                        'description' => $offline_count[0]->description,
                        'facing' => $offline_count[0]->facing,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_pain_history')->insert([
                    'pph_id' => $offline->pph_id,
                    'patient_id' => $offline->patient_id,
                    'pain_position_x' => $offline->pain_position_x,
                    'pain_position_y' => $offline->pain_position_y,
                    'pain_level' => $offline->pain_level,
                    'description' => $offline->description,
                    'facing' => $offline->facing,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_pain_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_pain_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_pain_history')->where('pph_id', $online->pph_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_pain_history')->where('pph_id', $online->pph_id)->update([
                    'pph_id' => $online->pph_id,
                    'patient_id' => $online->patient_id,
                    'pain_position_x' => $online->pain_position_x,
                    'pain_position_y' => $online->pain_position_y,
                    'pain_level' => $online->pain_level,
                    'description' => $online->description,
                    'facing' => $online->facing,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_pain_history')->insert([
                    'pph_id' => $online->pph_id,
                    'patient_id' => $online->patient_id,
                    'pain_position_x' => $online->pain_position_x,
                    'pain_position_y' => $online->pain_position_y,
                    'pain_level' => $online->pain_level,
                    'description' => $online->description,
                    'facing' => $online->facing,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientPermission()
    {
        // syncronize patients_permission table from offline to online
        $offline = DB::table('patients_permission')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_permission')->where('permission_id', $offline->permission_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_permission')->where('permission_id', $offline->permission_id)->update([
                        'permission_id' => $offline->permission_id,
                        'patients_id' => $offline->patients_id,
                        'doctors_id' => $offline->doctors_id,
                        'permission_on' => $offline->permission_on,
                        'permission_status' => $offline->permission_status,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_permission')->where('permission_id', $offline_count[0]->permission_id)->update([
                        'permission_id' => $offline_count[0]->permission_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'permission_on' => $offline_count[0]->permission_on,
                        'permission_status' => $offline_count[0]->permission_status,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_permission')->insert([
                    'permission_id' => $offline->permission_id,
                    'patients_id' => $offline->patients_id,
                    'doctors_id' => $offline->doctors_id,
                    'permission_on' => $offline->permission_on,
                    'permission_status' => $offline->permission_status,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_permission table from online to offline
        $online = DB::connection('mysql2')->table('patients_permission')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_permission')->where('permission_id', $online->permission_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_permission')->where('permission_id', $online->permission_id)->update([
                    'permission_id' => $online->permission_id,
                    'patients_id' => $online->patients_id,
                    'doctors_id' => $online->doctors_id,
                    'permission_on' => $online->permission_on,
                    'permission_status' => $online->permission_status,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_permission')->insert([
                    'permission_id' => $online->permission_id,
                    'patients_id' => $online->patients_id,
                    'doctors_id' => $online->doctors_id,
                    'permission_on' => $online->permission_on,
                    'permission_status' => $online->permission_status,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientPersonalMedication()
    {
        // syncronize patients_personal_medication table from offline to online
        $offline = DB::table('patients_personal_medication')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_personal_medication')->where('ppm_id', $offline->ppm_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_personal_medication')->where('ppm_id', $offline->ppm_id)->update([
                        'ppm_id' => $offline->ppm_id,
                        'patient_id' => $offline->patient_id,
                        'meals' => $offline->meals,
                        'description' => $offline->description,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_personal_medication')->where('ppm_id', $offline_count[0]->ppm_id)->update([
                        'ppm_id' => $offline_count[0]->ppm_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'meals' => $offline_count[0]->meals,
                        'description' => $offline_count[0]->description,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_personal_medication')->insert([
                    'ppm_id' => $offline->ppm_id,
                    'patient_id' => $offline->patient_id,
                    'meals' => $offline->meals,
                    'description' => $offline->description,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_personal_medication table from online to offline
        $online = DB::connection('mysql2')->table('patients_personal_medication')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_personal_medication')->where('ppm_id', $online->ppm_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_personal_medication')->where('ppm_id', $online->ppm_id)->update([
                    'ppm_id' => $online->ppm_id,
                    'patient_id' => $online->patient_id,
                    'meals' => $online->meals,
                    'description' => $online->description,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_personal_medication')->insert([
                    'ppm_id' => $online->ppm_id,
                    'patient_id' => $online->patient_id,
                    'meals' => $online->meals,
                    'description' => $online->description,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientPulseHistory()
    {
        // syncronize patients_pulse_history table from offline to online
        $offline = DB::table('patients_pulse_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_pulse_history')->where('pph_id', $offline->pph_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_pulse_history')->where('pph_id', $offline->pph_id)->update([
                        'pph_id' => $offline->pph_id,
                        'patients_id' => $offline->patients_id,
                        'pulse' => $offline->pulse,
                        'added_by' => $offline->added_by,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_pulse_history')->where('pph_id', $offline_count[0]->pph_id)->update([
                        'pph_id' => $offline_count[0]->pph_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'pulse' => $offline_count[0]->pulse,
                        'added_by' => $offline_count[0]->added_by,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_pulse_history')->insert([
                    'pph_id' => $offline->pph_id,
                    'patients_id' => $offline->patients_id,
                    'pulse' => $offline->pulse,
                    'added_by' => $offline->added_by,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_pulse_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_pulse_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_pulse_history')->where('pph_id', $online->pph_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_pulse_history')->where('pph_id', $online->pph_id)->update([
                    'pph_id' => $online->pph_id,
                    'patients_id' => $online->patients_id,
                    'pulse' => $online->pulse,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_pulse_history')->insert([
                    'pph_id' => $online->pph_id,
                    'patients_id' => $online->patients_id,
                    'pulse' => $online->pulse,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientRespiratoryHistory()
    {
        // syncronize patients_respiratory_history table from offline to online
        $offline = DB::table('patients_respiratory_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_respiratory_history')->where('prh_id', $offline->prh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_respiratory_history')->where('prh_id', $offline->prh_id)->update([
                        'prh_id' => $offline->prh_id,
                        'patients_id' => $offline->patients_id,
                        'respiratory' => $offline->respiratory,
                        'added_by' => $offline->added_by,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patients_respiratory_history')->where('prh_id', $offline_count[0]->prh_id)->update([
                        'prh_id' => $offline_count[0]->prh_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'respiratory' => $offline_count[0]->respiratory,
                        'added_by' => $offline_count[0]->added_by,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_respiratory_history')->insert([
                    'prh_id' => $offline->prh_id,
                    'patients_id' => $offline->patients_id,
                    'respiratory' => $offline->respiratory,
                    'added_by' => $offline->added_by,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patients_respiratory_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_respiratory_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_respiratory_history')->where('prh_id', $online->prh_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_respiratory_history')->where('prh_id', $online->prh_id)->update([
                    'prh_id' => $online->prh_id,
                    'patients_id' => $online->patients_id,
                    'respiratory' => $online->respiratory,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patients_respiratory_history')->insert([
                    'prh_id' => $online->prh_id,
                    'patients_id' => $online->patients_id,
                    'respiratory' => $online->respiratory,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientTempHistory()
    {
        // syncronize patients_temp_history table from offline to online
        $offline = DB::table('patients_temp_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_temp_history')->where('pth_id', $offline->pth_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_temp_history')->where('pth_id', $offline->pth_id)->update([
                        'pth_id' => $offline->pth_id,
                        'patients_id' => $offline->patients_id,
                        'temp' => $offline->temp,
                        'added_by' => $offline->added_by,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_temp_history')->where('pth_id', $offline_count[0]->pth_id)->update([
                        'pth_id' => $offline_count[0]->pth_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'temp' => $offline_count[0]->temp,
                        'added_by' => $offline_count[0]->added_by,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_temp_history')->insert([
                    'pth_id' => $offline->pth_id,
                    'patients_id' => $offline->patients_id,
                    'temp' => $offline->temp,
                    'added_by' => $offline->added_by,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_temp_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_temp_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_temp_history')->where('pth_id', $online->pth_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_temp_history')->where('pth_id', $online->pth_id)->update([
                    'pth_id' => $online->pth_id,
                    'patients_id' => $online->patients_id,
                    'temp' => $online->temp,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_temp_history')->insert([
                    'pth_id' => $online->pth_id,
                    'patients_id' => $online->patients_id,
                    'temp' => $online->temp,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientUricAcidHistory()
    {
        // syncronize patients_uric_acid_history table from offline to online
        $offline = DB::table('patients_uric_acid_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_uric_acid_history')->where('uric_acid_id', $offline->uric_acid_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_uric_acid_history')->where('uric_acid_id', $offline->uric_acid_id)->update([
                        'uric_acid_id' => $offline->uric_acid_id,
                        'patients_id' => $offline->patients_id,
                        'uric_acid' => $offline->uric_acid,
                        'added_by' => $offline->added_by,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_uric_acid_history')->where('uric_acid_id', $offline_count[0]->uric_acid_id)->update([
                        'uric_acid_id' => $offline_count[0]->uric_acid_id,
                        'patients_id' => $offline_count[0]->patients_id,
                        'uric_acid' => $offline_count[0]->uric_acid,
                        'added_by' => $offline_count[0]->added_by,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_uric_acid_history')->insert([
                    'uric_acid_id' => $offline->uric_acid_id,
                    'patients_id' => $offline->patients_id,
                    'uric_acid' => $offline->uric_acid,
                    'added_by' => $offline->added_by,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_uric_acid_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_uric_acid_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_uric_acid_history')->where('uric_acid_id', $online->uric_acid_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_uric_acid_history')->where('uric_acid_id', $online->uric_acid_id)->update([
                    'uric_acid_id' => $online->uric_acid_id,
                    'patients_id' => $online->patients_id,
                    'uric_acid' => $online->uric_acid,
                    'added_by' => $online->added_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_uric_acid_history')->insert([
                    'uric_acid_id' => $online->uric_acid_id,
                    'patients_id' => $online->patients_id,
                    'uric_acid' => $online->uric_acid,
                    'added_by' => $online->added_by,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientWeightHistory()
    {
        // syncronize patients_weight_history table from offline to online
        $offline = DB::table('patients_weight_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patients_weight_history')->where('pwh_id', $offline->pwh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patients_weight_history')->where('pwh_id', $offline->pwh_id)->update([
                        'pwh_id' => $offline->pwh_id,
                        'patient_id' => $offline->patient_id,
                        'weight' => $offline->weight,
                        'added_by' => $offline->added_by,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('patients_weight_history')->where('pwh_id', $offline_count[0]->pwh_id)->update([
                        'pwh_id' => $offline_count[0]->pwh_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'weight' => $offline_count[0]->weight,
                        'added_by' => $offline_count[0]->added_by,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patients_weight_history')->insert([
                    'pwh_id' => $offline->pwh_id,
                    'patient_id' => $offline->patient_id,
                    'weight' => $offline->weight,
                    'added_by' => $offline->added_by,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize patients_weight_history table from online to offline
        $online = DB::connection('mysql2')->table('patients_weight_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patients_weight_history')->where('pwh_id', $online->pwh_id)->get();
            if (count($online_count) > 0) {
                DB::table('patients_weight_history')->where('pwh_id', $online->pwh_id)->update([
                    'pwh_id' => $online->pwh_id,
                    'patient_id' => $online->patient_id,
                    'weight' => $online->weight,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('patients_weight_history')->insert([
                    'pwh_id' => $online->pwh_id,
                    'patient_id' => $online->patient_id,
                    'weight' => $online->weight,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPatientShareImages()
    {
        // syncronize patient_sharedimages table from offline to online
        $offline = DB::table('patient_sharedimages')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('patient_sharedimages')->where('psi_id', $offline->psi_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('patient_sharedimages')->where('psi_id', $offline->psi_id)->update([
                        'psi_id' => $offline->psi_id,
                        'patient_id' => $offline->patient_id,
                        'image' => $offline->image,
                        'category' => $offline->category,
                        'type' => $offline->type,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('patient_sharedimages')->where('psi_id', $offline_count[0]->psi_id)->update([
                        'psi_id' => $offline_count[0]->psi_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'image' => $offline_count[0]->image,
                        'category' => $offline_count[0]->category,
                        'type' => $offline_count[0]->type,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('patient_sharedimages')->insert([
                    'psi_id' => $offline->psi_id,
                    'patient_id' => $offline->patient_id,
                    'image' => $offline->image,
                    'category' => $offline->category,
                    'type' => $offline->type,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize patient_sharedimages table from online to offline
        $online = DB::connection('mysql2')->table('patient_sharedimages')->get();
        foreach ($online as $online) {
            $online_count = DB::table('patient_sharedimages')->where('psi_id', $online->psi_id)->get();
            if (count($online_count) > 0) {
                DB::table('patient_sharedimages')->where('psi_id', $online->psi_id)->update([
                    'psi_id' => $online->psi_id,
                    'patient_id' => $online->patient_id,
                    'image' => $online->image,
                    'category' => $online->category,
                    'type' => $online->type,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('patient_sharedimages')->insert([
                    'psi_id' => $online->psi_id,
                    'patient_id' => $online->patient_id,
                    'image' => $online->image,
                    'category' => $online->category,
                    'type' => $online->type,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacy()
    {
        // syncronize pharmacy table from offline to online
        $offline = DB::table('pharmacy')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacy')->where('phmcy_id', $offline->phmcy_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacy')->where('phmcy_id', $offline->phmcy_id)->update([
                        'phmcy_id' => $offline->phmcy_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'user_id' => $offline->user_id,
                        'management_id' => $offline->management_id,
                        'name' => $offline->name,
                        'company_name' => $offline->company_name,
                        'address' => $offline->address,
                        'tin_number' => $offline->tin_number,
                        'email' => $offline->email,
                        'contact' => $offline->contact,
                        'status' => $offline->status,
                        'role' => $offline->role,
                        'added_by' => $offline->added_by,
                        'pharmacy_type' => $offline->pharmacy_type,
                        'company_logo' => $offline->company_logo,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('pharmacy')->where('phmcy_id', $offline_count[0]->phmcy_id)->update([
                        'phmcy_id' => $offline_count[0]->phmcy_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'user_id' => $offline_count[0]->user_id,
                        'management_id' => $offline_count[0]->management_id,
                        'name' => $offline_count[0]->name,
                        'company_name' => $offline_count[0]->company_name,
                        'address' => $offline_count[0]->address,
                        'tin_number' => $offline_count[0]->tin_number,
                        'email' => $offline_count[0]->email,
                        'contact' => $offline_count[0]->contact,
                        'status' => $offline_count[0]->status,
                        'role' => $offline_count[0]->role,
                        'added_by' => $offline_count[0]->added_by,
                        'pharmacy_type' => $offline_count[0]->pharmacy_type,
                        'company_logo' => $offline_count[0]->company_logo,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacy')->insert([
                    'phmcy_id' => $offline->phmcy_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'user_id' => $offline->user_id,
                    'management_id' => $offline->management_id,
                    'name' => $offline->name,
                    'company_name' => $offline->company_name,
                    'address' => $offline->address,
                    'tin_number' => $offline->tin_number,
                    'email' => $offline->email,
                    'contact' => $offline->contact,
                    'status' => $offline->status,
                    'role' => $offline->role,
                    'added_by' => $offline->added_by,
                    'pharmacy_type' => $offline->pharmacy_type,
                    'company_logo' => $offline->company_logo,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize pharmacy table from online to offline
        $online = DB::connection('mysql2')->table('pharmacy')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacy')->where('phmcy_id', $online->phmcy_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacy')->where('phmcy_id', $online->phmcy_id)->update([
                    'phmcy_id' => $online->phmcy_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'user_id' => $online->user_id,
                    'management_id' => $online->management_id,
                    'name' => $online->name,
                    'company_name' => $online->company_name,
                    'address' => $online->address,
                    'tin_number' => $online->tin_number,
                    'email' => $online->email,
                    'contact' => $online->contact,
                    'status' => $online->status,
                    'role' => $online->role,
                    'added_by' => $online->added_by,
                    'pharmacy_type' => $online->pharmacy_type,
                    'company_logo' => $online->company_logo,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('pharmacy')->insert([
                    'phmcy_id' => $online->phmcy_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'user_id' => $online->user_id,
                    'management_id' => $online->management_id,
                    'name' => $online->name,
                    'company_name' => $online->company_name,
                    'address' => $online->address,
                    'tin_number' => $online->tin_number,
                    'email' => $online->email,
                    'contact' => $online->contact,
                    'status' => $online->status,
                    'role' => $online->role,
                    'added_by' => $online->added_by,
                    'pharmacy_type' => $online->pharmacy_type,
                    'company_logo' => $online->company_logo,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyClinicHistory()
    {
        // syncronize pharmacyclinic_history table from offline to online
        $offline = DB::table('pharmacyclinic_history')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacyclinic_history')->where('pch_id', $offline->pch_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacyclinic_history')->where('pch_id', $offline->pch_id)->update([
                        'pch_id' => $offline->pch_id,
                        'product_id' => $offline->product_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'management_id' => $offline->management_id,
                        'username' => $offline->username,
                        'product' => $offline->product,
                        'description' => $offline->description,
                        'unit' => $offline->unit,
                        'quantity' => $offline->quantity,
                        'request_type' => $offline->request_type,
                        'dr_no' => $offline->dr_no,
                        'supplier' => $offline->supplier,
                        'remarks' => $offline->remarks,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('pharmacyclinic_history')->where('pch_id', $offline_count[0]->pch_id)->update([
                        'pch_id' => $offline_count[0]->pch_id,
                        'product_id' => $offline_count[0]->product_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'management_id' => $offline_count[0]->management_id,
                        'username' => $offline_count[0]->username,
                        'product' => $offline_count[0]->product,
                        'description' => $offline_count[0]->description,
                        'unit' => $offline_count[0]->unit,
                        'quantity' => $offline_count[0]->quantity,
                        'request_type' => $offline_count[0]->request_type,
                        'dr_no' => $offline_count[0]->dr_no,
                        'supplier' => $offline_count[0]->supplier,
                        'remarks' => $offline_count[0]->remarks,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacyclinic_history')->insert([
                    'pch_id' => $offline->pch_id,
                    'product_id' => $offline->product_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'management_id' => $offline->management_id,
                    'username' => $offline->username,
                    'product' => $offline->product,
                    'description' => $offline->description,
                    'unit' => $offline->unit,
                    'quantity' => $offline->quantity,
                    'request_type' => $offline->request_type,
                    'dr_no' => $offline->dr_no,
                    'supplier' => $offline->supplier,
                    'remarks' => $offline->remarks,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize pharmacyclinic_history table from online to offline
        $online = DB::connection('mysql2')->table('pharmacyclinic_history')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacyclinic_history')->where('pch_id', $online->pch_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacyclinic_history')->where('pch_id', $online->pch_id)->update([
                    'pch_id' => $online->pch_id,
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'username' => $online->username,
                    'product' => $online->product,
                    'description' => $online->description,
                    'unit' => $online->unit,
                    'quantity' => $online->quantity,
                    'request_type' => $online->request_type,
                    'dr_no' => $online->dr_no,
                    'supplier' => $online->supplier,
                    'remarks' => $online->remarks,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('pharmacyclinic_history')->insert([
                    'pch_id' => $online->pch_id,
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'username' => $online->username,
                    'product' => $online->product,
                    'description' => $online->description,
                    'unit' => $online->unit,
                    'quantity' => $online->quantity,
                    'request_type' => $online->request_type,
                    'dr_no' => $online->dr_no,
                    'supplier' => $online->supplier,
                    'remarks' => $online->remarks,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyClinicInventory()
    {
        // syncronize pharmacyclinic_inventory table from offline to online
        $offline = DB::table('pharmacyclinic_inventory')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacyclinic_inventory')->where('inventory_id', $offline->inventory_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacyclinic_inventory')->where('inventory_id', $offline->inventory_id)->update([
                        'inventory_id' => $offline->inventory_id,
                        'management_id' => $offline->management_id,
                        'product_id' => $offline->product_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'dr_no' => $offline->dr_no,
                        'quantity' => $offline->quantity,
                        'unit' => $offline->unit,
                        'starting_quantity' => $offline->starting_quantity,
                        'manufacture_date' => $offline->manufacture_date,
                        'batch_no' => $offline->batch_no,
                        'expiry_date' => $offline->expiry_date,
                        'request_type' => $offline->request_type,
                        'comment' => $offline->comment,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('pharmacyclinic_inventory')->where('inventory_id', $offline_count[0]->inventory_id)->update([
                        'inventory_id' => $offline_count[0]->inventory_id,
                        'management_id' => $offline_count[0]->management_id,
                        'product_id' => $offline_count[0]->product_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'dr_no' => $offline_count[0]->dr_no,
                        'quantity' => $offline_count[0]->quantity,
                        'unit' => $offline_count[0]->unit,
                        'starting_quantity' => $offline_count[0]->starting_quantity,
                        'manufacture_date' => $offline_count[0]->manufacture_date,
                        'batch_no' => $offline_count[0]->batch_no,
                        'expiry_date' => $offline_count[0]->expiry_date,
                        'request_type' => $offline_count[0]->request_type,
                        'comment' => $offline_count[0]->comment,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacyclinic_inventory')->insert([
                    'inventory_id' => $offline->inventory_id,
                    'management_id' => $offline->management_id,
                    'product_id' => $offline->product_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'dr_no' => $offline->dr_no,
                    'quantity' => $offline->quantity,
                    'unit' => $offline->unit,
                    'starting_quantity' => $offline->starting_quantity,
                    'manufacture_date' => $offline->manufacture_date,
                    'batch_no' => $offline->batch_no,
                    'expiry_date' => $offline->expiry_date,
                    'request_type' => $offline->request_type,
                    'comment' => $offline->comment,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize pharmacyclinic_inventory table from online to offline
        $online = DB::connection('mysql2')->table('pharmacyclinic_inventory')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacyclinic_inventory')->where('inventory_id', $online->inventory_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacyclinic_inventory')->where('inventory_id', $online->inventory_id)->update([
                    'inventory_id' => $online->inventory_id,
                    'management_id' => $online->management_id,
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'dr_no' => $online->dr_no,
                    'quantity' => $online->quantity,
                    'unit' => $online->unit,
                    'starting_quantity' => $online->starting_quantity,
                    'manufacture_date' => $online->manufacture_date,
                    'batch_no' => $online->batch_no,
                    'expiry_date' => $online->expiry_date,
                    'request_type' => $online->request_type,
                    'comment' => $online->comment,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('pharmacyclinic_inventory')->insert([
                    'inventory_id' => $online->inventory_id,
                    'management_id' => $online->management_id,
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'dr_no' => $online->dr_no,
                    'quantity' => $online->quantity,
                    'unit' => $online->unit,
                    'starting_quantity' => $online->starting_quantity,
                    'manufacture_date' => $online->manufacture_date,
                    'batch_no' => $online->batch_no,
                    'expiry_date' => $online->expiry_date,
                    'request_type' => $online->request_type,
                    'comment' => $online->comment,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyClinicProducts()
    {
        // syncronize pharmacyclinic_products table from offline to online
        $offline = DB::table('pharmacyclinic_products')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacyclinic_products')->where('product_id', $offline->product_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacyclinic_products')->where('product_id', $offline->product_id)->update([
                        'product_id' => $offline->product_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'management_id' => $offline->management_id,
                        'product' => $offline->product,
                        'description' => $offline->description,
                        'supplier' => $offline->supplier,
                        'unit' => $offline->unit,
                        'unit_price' => $offline->unit_price,
                        'srp' => $offline->srp,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('pharmacyclinic_products')->where('product_id', $offline_count[0]->product_id)->update([
                        'product_id' => $offline_count[0]->product_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'management_id' => $offline_count[0]->management_id,
                        'product' => $offline_count[0]->product,
                        'description' => $offline_count[0]->description,
                        'supplier' => $offline_count[0]->supplier,
                        'unit' => $offline_count[0]->unit,
                        'unit_price' => $offline_count[0]->unit_price,
                        'srp' => $offline_count[0]->srp,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacyclinic_products')->insert([
                    'product_id' => $offline->product_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'management_id' => $offline->management_id,
                    'product' => $offline->product,
                    'description' => $offline->description,
                    'supplier' => $offline->supplier,
                    'unit' => $offline->unit,
                    'unit_price' => $offline->unit_price,
                    'srp' => $offline->srp,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize pharmacyclinic_products table from online to offline
        $online = DB::connection('mysql2')->table('pharmacyclinic_products')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacyclinic_products')->where('product_id', $online->product_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacyclinic_products')->where('product_id', $online->product_id)->update([
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'product' => $online->product,
                    'description' => $online->description,
                    'supplier' => $online->supplier,
                    'unit' => $online->unit,
                    'unit_price' => $online->unit_price,
                    'srp' => $online->srp,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('pharmacyclinic_products')->insert([
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'product' => $online->product,
                    'description' => $online->description,
                    'supplier' => $online->supplier,
                    'unit' => $online->unit,
                    'unit_price' => $online->unit_price,
                    'srp' => $online->srp,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyClinicProductsPackage()
    {
        // syncronize pharmacyclinic_products_package table from offline to online
        $offline = DB::table('pharmacyclinic_products_package')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacyclinic_products_package')->where('ppp_id', $offline->ppp_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacyclinic_products_package')->where('ppp_id', $offline->ppp_id)->update([
                        'ppp_id' => $offline->ppp_id,
                        'package_id' => $offline->package_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'management_id' => $offline->management_id,
                        'package' => $offline->package,
                        'amount' => $offline->amount,
                        'product_id' => $offline->product_id,
                        'product_qty' => $offline->product_qty,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('pharmacyclinic_products_package')->where('ppp_id', $offline_count[0]->ppp_id)->update([
                        'ppp_id' => $offline_count[0]->ppp_id,
                        'package_id' => $offline_count[0]->package_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'management_id' => $offline_count[0]->management_id,
                        'package' => $offline_count[0]->package,
                        'amount' => $offline_count[0]->amount,
                        'product_id' => $offline_count[0]->product_id,
                        'product_qty' => $offline_count[0]->product_qty,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacyclinic_products_package')->insert([
                    'ppp_id' => $offline->ppp_id,
                    'package_id' => $offline->package_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'management_id' => $offline->management_id,
                    'package' => $offline->package,
                    'amount' => $offline->amount,
                    'product_id' => $offline->product_id,
                    'product_qty' => $offline->product_qty,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize pharmacyclinic_products_package table from online to offline
        $online = DB::connection('mysql2')->table('pharmacyclinic_products_package')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacyclinic_products_package')->where('ppp_id', $online->ppp_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacyclinic_products_package')->where('ppp_id', $online->ppp_id)->update([
                    'ppp_id' => $online->ppp_id,
                    'package_id' => $online->package_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'package' => $online->package,
                    'amount' => $online->amount,
                    'product_id' => $online->product_id,
                    'product_qty' => $online->product_qty,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('pharmacyclinic_products_package')->insert([
                    'ppp_id' => $online->ppp_id,
                    'package_id' => $online->package_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'package' => $online->package,
                    'amount' => $online->amount,
                    'product_id' => $online->product_id,
                    'product_qty' => $online->product_qty,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyClinicProductsShare()
    {
        // syncronize pharmacyclinic_products_share table from offline to online
        $offline = DB::table('pharmacyclinic_products_share')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacyclinic_products_share')->where('pcps_id', $offline->pcps_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacyclinic_products_share')->where('pcps_id', $offline->pcps_id)->update([
                        'doctors_id' => $offline->doctors_id,
                        'management_id' => $offline->management_id,
                        'product_id' => $offline->product_id,
                        'share_percent' => $offline->share_percent,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('pharmacyclinic_products_share')->where('pcps_id', $offline_count[0]->pcps_id)->update([
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'management_id' => $offline_count[0]->management_id,
                        'product_id' => $offline_count[0]->product_id,
                        'share_percent' => $offline_count[0]->share_percent,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacyclinic_products_share')->insert([
                    'pcps_id' => $offline->pcps_id,
                    'doctors_id' => $offline->doctors_id,
                    'management_id' => $offline->management_id,
                    'product_id' => $offline->product_id,
                    'share_percent' => $offline->share_percent,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize pharmacyclinic_products_share table from online to offline
        $online = DB::connection('mysql2')->table('pharmacyclinic_products_share')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacyclinic_products_share')->where('pcps_id', $online->pcps_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacyclinic_products_share')->where('pcps_id', $online->pcps_id)->update([
                    'doctors_id' => $online->doctors_id,
                    'management_id' => $online->management_id,
                    'product_id' => $online->product_id,
                    'share_percent' => $online->share_percent,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('pharmacyclinic_products_share')->insert([
                    'pcps_id' => $online->pcps_id,
                    'doctors_id' => $online->doctors_id,
                    'management_id' => $online->management_id,
                    'product_id' => $online->product_id,
                    'share_percent' => $online->share_percent,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyClinicReceipt()
    {
        // syncronize pharmacyclinic_receipt table from offline to online
        $offline = DB::table('pharmacyclinic_receipt')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacyclinic_receipt')->where('pcr_id', $offline->pcr_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacyclinic_receipt')->where('pcr_id', $offline->pcr_id)->update([
                        'pcr_id' => $offline->pcr_id,
                        'receipt_id' => $offline->receipt_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'management_id' => $offline->management_id,
                        'username' => $offline->username,
                        'name_customer' => $offline->name_customer,
                        'address_customer' => $offline->address_customer,
                        'tin_customer' => $offline->tin_customer,
                        'product' => $offline->product,
                        'description' => $offline->description,
                        'unit' => $offline->unit,
                        'quantity' => $offline->quantity,
                        'srp' => $offline->srp,
                        'total' => $offline->total,
                        'amount_paid' => $offline->amount_paid,
                        'payment_change' => $offline->payment_change,
                        'dr_no' => $offline->dr_no,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('pharmacyclinic_receipt')->where('pcr_id', $offline_count[0]->pcr_id)->update([
                        'pcr_id' => $offline_count[0]->pcr_id,
                        'receipt_id' => $offline_count[0]->receipt_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'management_id' => $offline_count[0]->management_id,
                        'username' => $offline_count[0]->username,
                        'name_customer' => $offline_count[0]->name_customer,
                        'address_customer' => $offline_count[0]->address_customer,
                        'tin_customer' => $offline_count[0]->tin_customer,
                        'product' => $offline_count[0]->product,
                        'description' => $offline_count[0]->description,
                        'unit' => $offline_count[0]->unit,
                        'quantity' => $offline_count[0]->quantity,
                        'srp' => $offline_count[0]->srp,
                        'total' => $offline_count[0]->total,
                        'amount_paid' => $offline_count[0]->amount_paid,
                        'payment_change' => $offline_count[0]->payment_change,
                        'dr_no' => $offline_count[0]->dr_no,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacyclinic_receipt')->insert([
                    'pcr_id' => $offline->pcr_id,
                    'receipt_id' => $offline->receipt_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'management_id' => $offline->management_id,
                    'username' => $offline->username,
                    'name_customer' => $offline->name_customer,
                    'address_customer' => $offline->address_customer,
                    'tin_customer' => $offline->tin_customer,
                    'product' => $offline->product,
                    'description' => $offline->description,
                    'unit' => $offline->unit,
                    'quantity' => $offline->quantity,
                    'srp' => $offline->srp,
                    'total' => $offline->total,
                    'amount_paid' => $offline->amount_paid,
                    'payment_change' => $offline->payment_change,
                    'dr_no' => $offline->dr_no,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize pharmacyclinic_receipt table from online to offline
        $online = DB::connection('mysql2')->table('pharmacyclinic_receipt')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacyclinic_receipt')->where('pcr_id', $online->pcr_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacyclinic_receipt')->where('pcr_id', $online->pcr_id)->update([
                    'pcr_id' => $online->pcr_id,
                    'receipt_id' => $online->receipt_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'username' => $online->username,
                    'name_customer' => $online->name_customer,
                    'address_customer' => $online->address_customer,
                    'tin_customer' => $online->tin_customer,
                    'product' => $online->product,
                    'description' => $online->description,
                    'unit' => $online->unit,
                    'quantity' => $online->quantity,
                    'srp' => $online->srp,
                    'total' => $online->total,
                    'amount_paid' => $online->amount_paid,
                    'payment_change' => $online->payment_change,
                    'dr_no' => $online->dr_no,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('pharmacyclinic_receipt')->insert([
                    'pcr_id' => $online->pcr_id,
                    'receipt_id' => $online->receipt_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'username' => $online->username,
                    'name_customer' => $online->name_customer,
                    'address_customer' => $online->address_customer,
                    'tin_customer' => $online->tin_customer,
                    'product' => $online->product,
                    'description' => $online->description,
                    'unit' => $online->unit,
                    'quantity' => $online->quantity,
                    'srp' => $online->srp,
                    'total' => $online->total,
                    'amount_paid' => $online->amount_paid,
                    'payment_change' => $online->payment_change,
                    'dr_no' => $online->dr_no,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyClinicSales()
    {
        // syncronize pharmacyclinic_sales table from offline to online
        $offline = DB::table('pharmacyclinic_sales')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacyclinic_sales')->where('sales_id', $offline->sales_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacyclinic_sales')->where('sales_id', $offline->sales_id)->update([
                        'sales_id' => $offline->sales_id,
                        'product_id' => $offline->product_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'management_id' => $offline->management_id,
                        'username' => $offline->username,
                        'product' => $offline->product,
                        'description' => $offline->description,
                        'unit' => $offline->unit,
                        'quantity' => $offline->quantity,
                        'total' => $offline->total,
                        'dr_no' => $offline->dr_no,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('pharmacyclinic_sales')->where('sales_id', $offline_count[0]->sales_id)->update([
                        'sales_id' => $offline_count[0]->sales_id,
                        'product_id' => $offline_count[0]->product_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'management_id' => $offline_count[0]->management_id,
                        'username' => $offline_count[0]->username,
                        'product' => $offline_count[0]->product,
                        'description' => $offline_count[0]->description,
                        'unit' => $offline_count[0]->unit,
                        'quantity' => $offline_count[0]->quantity,
                        'total' => $offline_count[0]->total,
                        'dr_no' => $offline_count[0]->dr_no,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacyclinic_sales')->insert([
                    'sales_id' => $offline->sales_id,
                    'product_id' => $offline->product_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'management_id' => $offline->management_id,
                    'username' => $offline->username,
                    'product' => $offline->product,
                    'description' => $offline->description,
                    'unit' => $offline->unit,
                    'quantity' => $offline->quantity,
                    'total' => $offline->total,
                    'dr_no' => $offline->dr_no,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize pharmacyclinic_sales table from online to offline
        $online = DB::connection('mysql2')->table('pharmacyclinic_sales')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacyclinic_sales')->where('sales_id', $online->sales_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacyclinic_sales')->where('sales_id', $online->sales_id)->update([
                    'sales_id' => $online->sales_id,
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'username' => $online->username,
                    'product' => $online->product,
                    'description' => $online->description,
                    'unit' => $online->unit,
                    'quantity' => $online->quantity,
                    'total' => $online->total,
                    'dr_no' => $online->dr_no,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('pharmacyclinic_sales')->insert([
                    'sales_id' => $online->sales_id,
                    'product_id' => $online->product_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'username' => $online->username,
                    'product' => $online->product,
                    'description' => $online->description,
                    'unit' => $online->unit,
                    'quantity' => $online->quantity,
                    'total' => $online->total,
                    'dr_no' => $online->dr_no,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncPharmacyBranches()
    {
        // syncronize pharmacy_branches table from offline to online
        $offline = DB::table('pharmacy_branches')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('pharmacy_branches')->where('pb_id', $offline->pb_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('pharmacy_branches')->where('pb_id', $offline->pb_id)->update([
                        'pb_id' => $offline->pb_id,
                        'pharmacy_id' => $offline->pharmacy_id,
                        'management_id' => $offline->management_id,
                        'branch_id' => $offline->branch_id,
                        'user_id' => $offline->user_id,
                        'branch_name' => $offline->branch_name,
                        'branch_address' => $offline->branch_address,
                        'braches_tin' => $offline->braches_tin,
                        'status' => $offline->status,
                        'role' => $offline->role,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('pharmacy_branches')->where('pb_id', $offline_count[0]->pb_id)->update([
                        'pb_id' => $offline_count[0]->pb_id,
                        'pharmacy_id' => $offline_count[0]->pharmacy_id,
                        'management_id' => $offline_count[0]->management_id,
                        'branch_id' => $offline_count[0]->branch_id,
                        'user_id' => $offline_count[0]->user_id,
                        'branch_name' => $offline_count[0]->branch_name,
                        'branch_address' => $offline_count[0]->branch_address,
                        'braches_tin' => $offline_count[0]->braches_tin,
                        'status' => $offline_count[0]->status,
                        'role' => $offline_count[0]->role,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('pharmacy_branches')->insert([
                    'pb_id' => $offline->pb_id,
                    'pharmacy_id' => $offline->pharmacy_id,
                    'management_id' => $offline->management_id,
                    'branch_id' => $offline->branch_id,
                    'user_id' => $offline->user_id,
                    'branch_name' => $offline->branch_name,
                    'branch_address' => $offline->branch_address,
                    'braches_tin' => $offline->braches_tin,
                    'status' => $offline->status,
                    'role' => $offline->role,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize pharmacy_branches table from online to offline
        $online = DB::connection('mysql2')->table('pharmacy_branches')->get();
        foreach ($online as $online) {
            $online_count = DB::table('pharmacy_branches')->where('pb_id', $online->pb_id)->get();
            if (count($online_count) > 0) {
                DB::table('pharmacy_branches')->where('pb_id', $online->pb_id)->update([
                    'pb_id' => $online->pb_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'branch_id' => $online->branch_id,
                    'user_id' => $online->user_id,
                    'branch_name' => $online->branch_name,
                    'branch_address' => $online->branch_address,
                    'braches_tin' => $online->braches_tin,
                    'status' => $online->status,
                    'role' => $online->role,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('pharmacy_branches')->insert([
                    'pb_id' => $online->pb_id,
                    'pharmacy_id' => $online->pharmacy_id,
                    'management_id' => $online->management_id,
                    'branch_id' => $online->branch_id,
                    'user_id' => $online->user_id,
                    'branch_name' => $online->branch_name,
                    'branch_address' => $online->branch_address,
                    'braches_tin' => $online->braches_tin,
                    'status' => $online->status,
                    'role' => $online->role,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncRadiologist()
    {
        // syncronize radiologist table from offline to online
        $offline = DB::table('radiologist')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('radiologist')->where('r_id', $offline->r_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('radiologist')->where('r_id', $offline->r_id)->update([
                        'r_id' => $offline->r_id,
                        'radiologist_id' => $offline->radiologist_id,
                        'user_id' => $offline->user_id,
                        'management_id' => $offline->management_id,
                        'name' => $offline->name,
                        'gender' => $offline->gender,
                        'birthday' => $offline->birthday,
                        'address' => $offline->address,
                        'role' => $offline->role,
                        'added_by' => $offline->added_by,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('radiologist')->where('r_id', $offline_count[0]->r_id)->update([
                        'r_id' => $offline_count[0]->r_id,
                        'radiologist_id' => $offline_count[0]->radiologist_id,
                        'user_id' => $offline_count[0]->user_id,
                        'management_id' => $offline_count[0]->management_id,
                        'name' => $offline_count[0]->name,
                        'gender' => $offline_count[0]->gender,
                        'birthday' => $offline_count[0]->birthday,
                        'address' => $offline_count[0]->address,
                        'role' => $offline_count[0]->role,
                        'added_by' => $offline_count[0]->added_by,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('radiologist')->insert([
                    'r_id' => $offline->r_id,
                    'radiologist_id' => $offline->radiologist_id,
                    'user_id' => $offline->user_id,
                    'management_id' => $offline->management_id,
                    'name' => $offline->name,
                    'gender' => $offline->gender,
                    'birthday' => $offline->birthday,
                    'address' => $offline->address,
                    'role' => $offline->role,
                    'added_by' => $offline->added_by,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize radiologist table from online to offline
        $online = DB::connection('mysql2')->table('radiologist')->get();
        foreach ($online as $online) {
            $online_count = DB::table('radiologist')->where('r_id', $online->r_id)->get();
            if (count($online_count) > 0) {
                DB::table('radiologist')->where('r_id', $online->r_id)->update([
                    'r_id' => $online->r_id,
                    'radiologist_id' => $online->radiologist_id,
                    'user_id' => $online->user_id,
                    'management_id' => $online->management_id,
                    'name' => $online->name,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'address' => $online->address,
                    'role' => $online->role,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('radiologist')->insert([
                    'r_id' => $online->r_id,
                    'radiologist_id' => $online->radiologist_id,
                    'user_id' => $online->user_id,
                    'management_id' => $online->management_id,
                    'name' => $online->name,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'address' => $online->address,
                    'role' => $online->role,
                    'added_by' => $online->added_by,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncTeleRadiologist()
    {
        // syncronize teleradiologist table from offline to online
        $offline = DB::table('teleradiologist')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('teleradiologist')->where('telerad_id', $offline->telerad_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('teleradiologist')->where('telerad_id', $offline->telerad_id)->update([
                        'telerad_id' => $offline->telerad_id,
                        'user_id' => $offline->user_id,
                        'name' => $offline->name,
                        'gender' => $offline->gender,
                        'birthday' => $offline->birthday,
                        'address' => $offline->address,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('teleradiologist')->where('telerad_id', $offline_count[0]->telerad_id)->update([
                        'telerad_id' => $offline_count[0]->telerad_id,
                        'user_id' => $offline_count[0]->user_id,
                        'name' => $offline_count[0]->name,
                        'gender' => $offline_count[0]->gender,
                        'birthday' => $offline_count[0]->birthday,
                        'address' => $offline_count[0]->address,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('teleradiologist')->insert([
                    'telerad_id' => $offline->telerad_id,
                    'user_id' => $offline->user_id,
                    'name' => $offline->name,
                    'gender' => $offline->gender,
                    'birthday' => $offline->birthday,
                    'address' => $offline->address,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize teleradiologist table from online to offline
        $online = DB::connection('mysql2')->table('teleradiologist')->get();
        foreach ($online as $online) {
            $online_count = DB::table('teleradiologist')->where('telerad_id', $online->telerad_id)->get();
            if (count($online_count) > 0) {
                DB::table('teleradiologist')->where('telerad_id', $online->telerad_id)->update([
                    'telerad_id' => $online->telerad_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'address' => $online->address,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('teleradiologist')->insert([
                    'telerad_id' => $online->telerad_id,
                    'user_id' => $online->user_id,
                    'name' => $online->name,
                    'gender' => $online->gender,
                    'birthday' => $online->birthday,
                    'address' => $online->address,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncTeleRadiologistChat()
    {
        // syncronize teleradiologist_chat table from offline to online
        $offline = DB::table('teleradiologist_chat')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('teleradiologist_chat')->where('chat_id', $offline->chat_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('teleradiologist_chat')->where('chat_id', $offline->chat_id)->update([
                        'chat_id' => $offline->chat_id,
                        'sender_user_id' => $offline->sender_user_id,
                        'receiver_user_id' => $offline->receiver_user_id,
                        'message' => $offline->message,
                        'is_viewed' => $offline->is_viewed,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('teleradiologist_chat')->where('chat_id', $offline_count[0]->chat_id)->update([
                        'chat_id' => $offline_count[0]->chat_id,
                        'sender_user_id' => $offline_count[0]->sender_user_id,
                        'receiver_user_id' => $offline_count[0]->receiver_user_id,
                        'message' => $offline_count[0]->message,
                        'is_viewed' => $offline_count[0]->is_viewed,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('teleradiologist_chat')->insert([
                    'chat_id' => $offline->chat_id,
                    'sender_user_id' => $offline->sender_user_id,
                    'receiver_user_id' => $offline->receiver_user_id,
                    'message' => $offline->message,
                    'is_viewed' => $offline->is_viewed,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize teleradiologist_chat table from online to offline
        $online = DB::connection('mysql2')->table('teleradiologist_chat')->get();
        foreach ($online as $online) {
            $online_count = DB::table('teleradiologist_chat')->where('chat_id', $online->chat_id)->get();
            if (count($online_count) > 0) {
                DB::table('teleradiologist_chat')->where('chat_id', $online->chat_id)->update([
                    'chat_id' => $online->chat_id,
                    'sender_user_id' => $online->sender_user_id,
                    'receiver_user_id' => $online->receiver_user_id,
                    'message' => $online->message,
                    'is_viewed' => $online->is_viewed,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('teleradiologist_chat')->insert([
                    'chat_id' => $online->chat_id,
                    'sender_user_id' => $online->sender_user_id,
                    'receiver_user_id' => $online->receiver_user_id,
                    'message' => $online->message,
                    'is_viewed' => $online->is_viewed,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncUsers()
    {
        // syncronize users table from offline to online
        $offline = DB::table('users')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('users')->where('user_id', $offline->user_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('users')->where('user_id', $offline->user_id)->update([
                        'user_id' => $offline->user_id,
                        'username' => $offline->username,
                        'password' => $offline->password,
                        'status' => $offline->status,
                        'type' => $offline->type,
                        'email' => $offline->email,
                        'is_verify' => $offline->is_verify,
                        'is_confirm' => $offline->is_confirm,
                        'manage_by' => $offline->manage_by,
                        'remember_token' => $offline->remember_token,
                        'is_disable' => $offline->is_disable,
                        'api_token' => $offline->api_token,
                        'is_disable_msg' => $offline->is_disable_msg,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('users')->where('user_id', $offline_count[0]->user_id)->update([
                        'user_id' => $offline_count[0]->user_id,
                        'username' => $offline_count[0]->username,
                        'password' => $offline_count[0]->password,
                        'status' => $offline_count[0]->status,
                        'type' => $offline_count[0]->type,
                        'email' => $offline_count[0]->email,
                        'is_verify' => $offline_count[0]->is_verify,
                        'is_confirm' => $offline_count[0]->is_confirm,
                        'manage_by' => $offline_count[0]->manage_by,
                        'remember_token' => $offline_count[0]->remember_token,
                        'is_disable' => $offline_count[0]->is_disable,
                        'api_token' => $offline_count[0]->api_token,
                        'is_disable_msg' => $offline_count[0]->is_disable_msg,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('users')->insert([
                    'user_id' => $offline->user_id,
                    'username' => $offline->username,
                    'password' => $offline->password,
                    'status' => $offline->status,
                    'type' => $offline->type,
                    'email' => $offline->email,
                    'is_verify' => $offline->is_verify,
                    'is_confirm' => $offline->is_confirm,
                    'manage_by' => $offline->manage_by,
                    'remember_token' => $offline->remember_token,
                    'is_disable' => $offline->is_disable,
                    'api_token' => $offline->api_token,
                    'is_disable_msg' => $offline->is_disable_msg,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize users table from online to offline
        $online = DB::connection('mysql2')->table('users')->get();
        foreach ($online as $online) {
            $online_count = DB::table('users')->where('user_id', $online->user_id)->get();
            if (count($online_count) > 0) {
                DB::table('users')->where('user_id', $online->user_id)->update([
                    'user_id' => $online->user_id,
                    'username' => $online->username,
                    'password' => $online->password,
                    'status' => $online->status,
                    'type' => $online->type,
                    'email' => $online->email,
                    'is_verify' => $online->is_verify,
                    'is_confirm' => $online->is_confirm,
                    'manage_by' => $online->manage_by,
                    'remember_token' => $online->remember_token,
                    'is_disable' => $online->is_disable,
                    'api_token' => $online->api_token,
                    'is_disable_msg' => $online->is_disable_msg,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('users')->insert([
                    'user_id' => $online->user_id,
                    'username' => $online->username,
                    'password' => $online->password,
                    'status' => $online->status,
                    'type' => $online->type,
                    'email' => $online->email,
                    'is_verify' => $online->is_verify,
                    'is_confirm' => $online->is_confirm,
                    'manage_by' => $online->manage_by,
                    'remember_token' => $online->remember_token,
                    'is_disable' => $online->is_disable,
                    'api_token' => $online->api_token,
                    'is_disable_msg' => $online->is_disable_msg,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncUsersGeolocation()
    {
        // syncronize users_geolocation table from offline to online
        $offline = DB::table('users_geolocation')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('users_geolocation')->where('geo_id', $offline->geo_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('users_geolocation')->where('geo_id', $offline->geo_id)->update([
                        'geo_id' => $offline->geo_id,
                        'user_id' => $offline->user_id,
                        'latitude' => $offline->latitude,
                        'longitude' => $offline->longitude,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('users_geolocation')->where('geo_id', $offline_count[0]->geo_id)->update([
                        'geo_id' => $offline_count[0]->geo_id,
                        'user_id' => $offline_count[0]->user_id,
                        'latitude' => $offline_count[0]->latitude,
                        'longitude' => $offline_count[0]->longitude,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('users_geolocation')->insert([
                    'geo_id' => $offline->geo_id,
                    'user_id' => $offline->user_id,
                    'latitude' => $offline->latitude,
                    'longitude' => $offline->longitude,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize users_geolocation table from online to offline
        $online = DB::connection('mysql2')->table('users_geolocation')->get();
        foreach ($online as $online) {
            $online_count = DB::table('users_geolocation')->where('geo_id', $online->geo_id)->get();
            if (count($online_count) > 0) {
                DB::table('users_geolocation')->where('geo_id', $online->geo_id)->update([
                    'geo_id' => $online->geo_id,
                    'user_id' => $online->user_id,
                    'latitude' => $online->latitude,
                    'longitude' => $online->longitude,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('users_geolocation')->insert([
                    'geo_id' => $online->geo_id,
                    'user_id' => $online->user_id,
                    'latitude' => $online->latitude,
                    'longitude' => $online->longitude,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncUsersSubscription()
    {
        // syncronize users_subscription table from offline to online
        $offline = DB::table('users_subscription')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('users_subscription')->where('subscription_id', $offline->subscription_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('users_subscription')->where('subscription_id', $offline->subscription_id)->update([
                        'subscription_id' => $offline->subscription_id,
                        'user_id' => $offline->user_id,
                        'subscription' => $offline->subscription,
                        'subscription_length_month' => $offline->subscription_length_month,
                        'subscription_amount' => $offline->subscription_amount,
                        'subscription_status' => $offline->subscription_status,
                        'is_processby' => $offline->is_processby,
                        'is_approvedby' => $offline->is_approvedby,
                        'payment_link' => $offline->payment_link,
                        'subscription_started' => $offline->subscription_started,
                        'subscription_end' => $offline->subscription_end,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('users_subscription')->where('subscription_id', $offline_count[0]->subscription_id)->update([
                        'subscription_id' => $offline_count[0]->subscription_id,
                        'user_id' => $offline_count[0]->user_id,
                        'subscription' => $offline_count[0]->subscription,
                        'subscription_length_month' => $offline_count[0]->subscription_length_month,
                        'subscription_amount' => $offline_count[0]->subscription_amount,
                        'subscription_status' => $offline_count[0]->subscription_status,
                        'is_processby' => $offline_count[0]->is_processby,
                        'is_approvedby' => $offline_count[0]->is_approvedby,
                        'payment_link' => $offline_count[0]->payment_link,
                        'subscription_started' => $offline_count[0]->subscription_started,
                        'subscription_end' => $offline_count[0]->subscription_end,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('users_subscription')->insert([
                    'subscription_id' => $offline->subscription_id,
                    'user_id' => $offline->user_id,
                    'subscription' => $offline->subscription,
                    'subscription_length_month' => $offline->subscription_length_month,
                    'subscription_amount' => $offline->subscription_amount,
                    'subscription_status' => $offline->subscription_status,
                    'is_processby' => $offline->is_processby,
                    'is_approvedby' => $offline->is_approvedby,
                    'payment_link' => $offline->payment_link,
                    'subscription_started' => $offline->subscription_started,
                    'subscription_end' => $offline->subscription_end,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize users_subscription table from online to offline
        $online = DB::connection('mysql2')->table('users_subscription')->get();
        foreach ($online as $online) {
            $online_count = DB::table('users_subscription')->where('subscription_id', $online->subscription_id)->get();
            if (count($online_count) > 0) {
                DB::table('users_subscription')->where('subscription_id', $online->subscription_id)->update([
                    'subscription_id' => $online->subscription_id,
                    'user_id' => $online->user_id,
                    'subscription' => $online->subscription,
                    'subscription_length_month' => $online->subscription_length_month,
                    'subscription_amount' => $online->subscription_amount,
                    'subscription_status' => $online->subscription_status,
                    'is_processby' => $online->is_processby,
                    'is_approvedby' => $online->is_approvedby,
                    'payment_link' => $online->payment_link,
                    'subscription_started' => $online->subscription_started,
                    'subscription_end' => $online->subscription_end,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('users_subscription')->insert([
                    'subscription_id' => $online->subscription_id,
                    'user_id' => $online->user_id,
                    'subscription' => $online->subscription,
                    'subscription_length_month' => $online->subscription_length_month,
                    'subscription_amount' => $online->subscription_amount,
                    'subscription_status' => $online->subscription_status,
                    'is_processby' => $online->is_processby,
                    'is_approvedby' => $online->is_approvedby,
                    'payment_link' => $online->payment_link,
                    'subscription_started' => $online->subscription_started,
                    'subscription_end' => $online->subscription_end,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncVirtualAppointment()
    {
        // syncronize virtual_appointment table from offline to online
        $offline = DB::table('virtual_appointment')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('virtual_appointment')->where('appointment_id', $offline->appointment_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('virtual_appointment')->where('appointment_id', $offline->appointment_id)->update([
                        'appointment_id' => $offline->appointment_id,
                        'reference_no' => $offline->reference_no,
                        'doctors_id' => $offline->doctors_id,
                        'patient_id' => $offline->patient_id,
                        'doctors_service_id' => $offline->doctors_service_id,
                        'doctors_service' => $offline->doctors_service,
                        'doctors_service_amount' => $offline->doctors_service_amount,
                        'appointment_date' => $offline->appointment_date,
                        'appointment_reason' => $offline->appointment_reason,
                        'attachment' => $offline->attachment,
                        'appointment_status' => $offline->appointment_status,
                        'consumed_time' => $offline->consumed_time,
                        'appointment_done_on' => $offline->appointment_done_on,
                        'process_done_by' => $offline->process_done_by,
                        'is_process' => $offline->is_process,
                        'is_process_on' => $offline->is_process_on,
                        'process_message' => $offline->process_message,
                        'is_reschedule' => $offline->is_reschedule,
                        'is_reschedule_date' => $offline->is_reschedule_date,
                        'is_reschedule_reason' => $offline->is_reschedule_reason,
                        'payment_status' => $offline->payment_status,
                        'status' => $offline->status,
                        'updated_at' => $offline->updated_at,
                        'created_at' => $offline->created_at,
                    ]);
                } else {
                    DB::table('virtual_appointment')->where('appointment_id', $offline_count[0]->appointment_id)->update([
                        'appointment_id' => $offline_count[0]->appointment_id,
                        'reference_no' => $offline_count[0]->reference_no,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'doctors_service_id' => $offline_count[0]->doctors_service_id,
                        'doctors_service' => $offline_count[0]->doctors_service,
                        'doctors_service_amount' => $offline_count[0]->doctors_service_amount,
                        'appointment_date' => $offline_count[0]->appointment_date,
                        'appointment_reason' => $offline_count[0]->appointment_reason,
                        'attachment' => $offline_count[0]->attachment,
                        'appointment_status' => $offline_count[0]->appointment_status,
                        'consumed_time' => $offline_count[0]->consumed_time,
                        'appointment_done_on' => $offline_count[0]->appointment_done_on,
                        'process_done_by' => $offline_count[0]->process_done_by,
                        'is_process' => $offline_count[0]->is_process,
                        'is_process_on' => $offline_count[0]->is_process_on,
                        'process_message' => $offline_count[0]->process_message,
                        'is_reschedule' => $offline_count[0]->is_reschedule,
                        'is_reschedule_date' => $offline_count[0]->is_reschedule_date,
                        'is_reschedule_reason' => $offline_count[0]->is_reschedule_reason,
                        'payment_status' => $offline_count[0]->payment_status,
                        'status' => $offline_count[0]->status,
                        'updated_at' => $offline_count[0]->updated_at,
                        'created_at' => $offline_count[0]->created_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('virtual_appointment')->insert([
                    'appointment_id' => $offline->appointment_id,
                    'reference_no' => $offline->reference_no,
                    'doctors_id' => $offline->doctors_id,
                    'patient_id' => $offline->patient_id,
                    'doctors_service_id' => $offline->doctors_service_id,
                    'doctors_service' => $offline->doctors_service,
                    'doctors_service_amount' => $offline->doctors_service_amount,
                    'appointment_date' => $offline->appointment_date,
                    'appointment_reason' => $offline->appointment_reason,
                    'attachment' => $offline->attachment,
                    'appointment_status' => $offline->appointment_status,
                    'consumed_time' => $offline->consumed_time,
                    'appointment_done_on' => $offline->appointment_done_on,
                    'process_done_by' => $offline->process_done_by,
                    'is_process' => $offline->is_process,
                    'is_process_on' => $offline->is_process_on,
                    'process_message' => $offline->process_message,
                    'is_reschedule' => $offline->is_reschedule,
                    'is_reschedule_date' => $offline->is_reschedule_date,
                    'is_reschedule_reason' => $offline->is_reschedule_reason,
                    'payment_status' => $offline->payment_status,
                    'status' => $offline->status,
                    'updated_at' => $offline->updated_at,
                    'created_at' => $offline->created_at,
                ]);
            }
        }

        // syncronize virtual_appointment table from online to offline
        $online = DB::connection('mysql2')->table('virtual_appointment')->get();
        foreach ($online as $online) {
            $online_count = DB::table('virtual_appointment')->where('appointment_id', $online->appointment_id)->get();
            if (count($online_count) > 0) {
                DB::table('virtual_appointment')->where('appointment_id', $online->appointment_id)->update([
                    'appointment_id' => $online->appointment_id,
                    'reference_no' => $online->reference_no,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'doctors_service_id' => $online->doctors_service_id,
                    'doctors_service' => $online->doctors_service,
                    'doctors_service_amount' => $online->doctors_service_amount,
                    'appointment_date' => $online->appointment_date,
                    'appointment_reason' => $online->appointment_reason,
                    'attachment' => $online->attachment,
                    'appointment_status' => $online->appointment_status,
                    'consumed_time' => $online->consumed_time,
                    'appointment_done_on' => $online->appointment_done_on,
                    'process_done_by' => $online->process_done_by,
                    'is_process' => $online->is_process,
                    'is_process_on' => $online->is_process_on,
                    'process_message' => $online->process_message,
                    'is_reschedule' => $online->is_reschedule,
                    'is_reschedule_date' => $online->is_reschedule_date,
                    'is_reschedule_reason' => $online->is_reschedule_reason,
                    'payment_status' => $online->payment_status,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            } else {
                DB::table('virtual_appointment')->insert([
                    'appointment_id' => $online->appointment_id,
                    'reference_no' => $online->reference_no,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'doctors_service_id' => $online->doctors_service_id,
                    'doctors_service' => $online->doctors_service,
                    'doctors_service_amount' => $online->doctors_service_amount,
                    'appointment_date' => $online->appointment_date,
                    'appointment_reason' => $online->appointment_reason,
                    'attachment' => $online->attachment,
                    'appointment_status' => $online->appointment_status,
                    'consumed_time' => $online->consumed_time,
                    'appointment_done_on' => $online->appointment_done_on,
                    'process_done_by' => $online->process_done_by,
                    'is_process' => $online->is_process,
                    'is_process_on' => $online->is_process_on,
                    'process_message' => $online->process_message,
                    'is_reschedule' => $online->is_reschedule,
                    'is_reschedule_date' => $online->is_reschedule_date,
                    'is_reschedule_reason' => $online->is_reschedule_reason,
                    'payment_status' => $online->payment_status,
                    'status' => $online->status,
                    'updated_at' => $online->updated_at,
                    'created_at' => $online->created_at,
                ]);
            }
        }

        return true;
    }

    public static function syncVirtualAppointmentNotification()
    {
        // syncronize virtual_appointment_notification table from offline to online
        $offline = DB::table('virtual_appointment_notification')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('virtual_appointment_notification')->where('notif_id', $offline->notif_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('virtual_appointment_notification')->where('notif_id', $offline->notif_id)->update([
                        'notif_id' => $offline->notif_id,
                        'appointment_id' => $offline->appointment_id,
                        'doctors_id' => $offline->doctors_id,
                        'patient_id' => $offline->patient_id,
                        'notification_msg' => $offline->notification_msg,
                        'is_read' => $offline->is_read,
                        'notification_type' => $offline->notification_type,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('virtual_appointment_notification')->where('notif_id', $offline_count[0]->notif_id)->update([
                        'notif_id' => $offline_count[0]->notif_id,
                        'appointment_id' => $offline_count[0]->appointment_id,
                        'doctors_id' => $offline_count[0]->doctors_id,
                        'patient_id' => $offline_count[0]->patient_id,
                        'notification_msg' => $offline_count[0]->notification_msg,
                        'is_read' => $offline_count[0]->is_read,
                        'notification_type' => $offline_count[0]->notification_type,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('virtual_appointment_notification')->insert([
                    'notif_id' => $offline->notif_id,
                    'appointment_id' => $offline->appointment_id,
                    'doctors_id' => $offline->doctors_id,
                    'patient_id' => $offline->patient_id,
                    'notification_msg' => $offline->notification_msg,
                    'is_read' => $offline->is_read,
                    'notification_type' => $offline->notification_type,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize virtual_appointment_notification table from online to offline
        $online = DB::connection('mysql2')->table('virtual_appointment_notification')->get();
        foreach ($online as $online) {
            $online_count = DB::table('virtual_appointment_notification')->where('notif_id', $online->notif_id)->get();
            if (count($online_count) > 0) {
                DB::table('virtual_appointment_notification')->where('notif_id', $online->notif_id)->update([
                    'notif_id' => $online->notif_id,
                    'appointment_id' => $online->appointment_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'notification_msg' => $online->notification_msg,
                    'is_read' => $online->is_read,
                    'notification_type' => $online->notification_type,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('virtual_appointment_notification')->insert([
                    'notif_id' => $online->notif_id,
                    'appointment_id' => $online->appointment_id,
                    'doctors_id' => $online->doctors_id,
                    'patient_id' => $online->patient_id,
                    'notification_msg' => $online->notification_msg,
                    'is_read' => $online->is_read,
                    'notification_type' => $online->notification_type,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

    public static function syncDoctorsRxHeader()
    {
        // syncronize doctors_rxheader table from offline to online
        $offline = DB::table('doctors_rxheader')->get();
        foreach ($offline as $offline) {
            $offline_count = DB::connection('mysql2')->table('doctors_rxheader')->where('drxh_id', $offline->drxh_id)->get();
            if (count($offline_count) > 0) {
                if ($offline->updated_at > $offline_count[0]->updated_at) {
                    DB::connection('mysql2')->table('doctors_rxheader')->where('drxh_id', $offline->drxh_id)->update([
                        'drxh_id' => $offline->drxh_id,
                        'header' => $offline->header,
                        'sub_header' => $offline->sub_header,
                        'location' => $offline->location,
                        'contact_no' => $offline->contact_no,
                        'days_open' => $offline->days_open,
                        'status' => $offline->status,
                        'created_at' => $offline->created_at,
                        'updated_at' => $offline->updated_at,
                    ]);
                } else {
                    DB::table('doctors_rxheader')->where('drxh_id', $offline_count[0]->drxh_id)->update([
                        'drxh_id' => $offline_count[0]->drxh_id,
                        'header' => $offline_count[0]->header,
                        'sub_header' => $offline_count[0]->sub_header,
                        'location' => $offline_count[0]->location,
                        'contact_no' => $offline_count[0]->contact_no,
                        'days_open' => $offline_count[0]->days_open,
                        'status' => $offline_count[0]->status,
                        'created_at' => $offline_count[0]->created_at,
                        'updated_at' => $offline_count[0]->updated_at,
                    ]);
                }
            } else {
                DB::connection('mysql2')->table('doctors_rxheader')->insert([
                    'drxh_id' => $offline->drxh_id,
                    'header' => $offline->header,
                    'sub_header' => $offline->sub_header,
                    'location' => $offline->location,
                    'contact_no' => $offline->contact_no,
                    'days_open' => $offline->days_open,
                    'status' => $offline->status,
                    'created_at' => $offline->created_at,
                    'updated_at' => $offline->updated_at,
                ]);
            }
        }

        // syncronize doctors_rxheader table from online to offline
        $online = DB::connection('mysql2')->table('doctors_rxheader')->get();
        foreach ($online as $online) {
            $online_count = DB::table('doctors_rxheader')->where('drxh_id', $online->drxh_id)->get();
            if (count($online_count) > 0) {
                DB::table('doctors_rxheader')->where('drxh_id', $online->drxh_id)->update([
                    'drxh_id' => $online->drxh_id,
                    'header' => $online->header,
                    'sub_header' => $online->sub_header,
                    'location' => $online->location,
                    'contact_no' => $online->contact_no,
                    'days_open' => $online->days_open,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            } else {
                DB::table('doctors_rxheader')->insert([
                    'drxh_id' => $online->drxh_id,
                    'sub_header' => $online->sub_header,
                    'location' => $online->location,
                    'contact_no' => $online->contact_no,
                    'days_open' => $online->days_open,
                    'status' => $online->status,
                    'created_at' => $online->created_at,
                    'updated_at' => $online->updated_at,
                ]);
            }
        }

        return true;
    }

}
