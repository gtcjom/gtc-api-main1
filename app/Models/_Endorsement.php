<?php

namespace App\Models;

use DB;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _Endorsement extends Model
{
    use HasFactory;

    public static function getInformation($data){
        return DB::table('endorsement_account')
        ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'endorsement_account.user_id')
        ->select('endorsement_account.endorsement_id', 'endorsement_account.user_fullname as name', 'endorsement_account.image', 'endorsement_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
        ->where('endorsement_account.user_id', $data['user_id'])
        ->first();
    }

    public static function getLaboratoryIdByMgt($data)
    {
        return DB::table('laboratory_list')->where('management_id', $data['management_id'])->first();
    }

    public static function updateProfileImage($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('endorsement_account')
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

    public static function getQueueList($data)
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
            ->where('patient_queue.management_id', $data['management_id'])->where('patient_queue.type', $data['type'])->get();
    }

    public static function getLaboratoryOrder($data)
    {
        return DB::table('laboratory_items_laborder')
            ->where('laboratory_id', _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id)
            ->select('laboratory_items_laborder.*', 'laboratory_items_laborder.laborder as label', 'laboratory_items_laborder.laborder as value')
            ->groupBy('order_id')
            ->get();
    }

    public static function processLabOrder($data)
    {
        // $gagooooo = DB::table('cashier_patientbills_unpaid')->select('trace_number')->where('patient_id', $data['patient_id'])->get();
        // $trace_number = '';
        // if(count($gagooooo) > 0){
        //     $trace_number = $gagooooo[0]->trace_number;
        // }else{
        //     $trace_number = $data['trace_number'];
        // }

        $trace_number = $data['trace_number'];
        

        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('laborotary_id', _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id)
            ->get();
 
        $secrtry_orderunpaid = [];

        foreach ($query as $v) {
            $orderid = $v->laboratory_test_id;

            $secrtry_orderunpaid[] = array(
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'doctors_id' => $v->doctor_id,
                'patient_id' => $data['patient_id'],
                'management_id' => _Endorsement::getLaboratoryIdByMgt($data)->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $v->laboratory_test,
                'bill_amount' => $v->laboratory_rate,
                'bill_department' => $v->department,
                'bill_from' => 'laboratory',
                'order_id' => $orderid,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($v->department == 'hemathology') {

                if ($v->laboratory_test == 'cbc' || $v->laboratory_test == 'cbc platelet') {
                    // insert when order id not exist
                    $checkCBC = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_cbc')
                        ->where('order_id', $orderid)
                        ->where('order_status', 'new-order')
                        ->get();

                    if (count($checkCBC) < 1) {
                        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                            ->table('laboratory_cbc')
                            ->insert([
                                'lc_id' => 'lc-' . rand(0, 99999) . time(),
                                'order_id' => $orderid,
                                'patient_id' => $data['patient_id'],
                                'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                                'remarks' => $data['remarks'],
                                'order_status' => 'new-order',
                                'trace_number' => $trace_number,
                                'status' => 1,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                    }
                }
                else{
                    // insert when order id not exist
                    $checkorderIdinHema = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hematology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                    // insert to laboratory hema if order id not exist
                    if (count($checkorderIdinHema) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_hematology')
                        ->insert([
                            'lh_id' => 'lh-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
            
            if ($v->department == 'serology') {
                $checkorderIdinSoro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_sorology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_sorology')
                    ->insert([
                        'ls_id' => 'ls-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'clinical-microscopy') {
                $checkorderIdinMicro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_microscopy')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinMicro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_microscopy')
                    ->insert([
                        'lm_id' => 'lm-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'spicemen' => $data['mc_spicemen'],
                        'order_remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'fecal-analysis') {
                $checkorderIdinFecal = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_fecal_analysis')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinFecal) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_fecal_analysis')
                    ->insert([
                        'lfa_id' => 'lm-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'stool-test') {
                $checkorderIdinStool = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_stooltest')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinStool) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_stooltest')
                    ->insert([
                        'lf_id' => 'lf-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'clinical-chemistry') {
                $checkorderIdinChem = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_chemistry')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinChem) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_chemistry')
                    ->insert([
                        'lc_id' => 'lc-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'spicemen' => $data['cc_specimen'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'ecg') {
                $checkorderIdinEcg = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_ecg')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinEcg) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_ecg')
                    ->insert([
                        'le_id' => 'le-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'urinalysis') {
                $checkorderIdinUri = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_urinalysis')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinUri) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_urinalysis')
                    ->insert([
                        'lu_id' => 'lu-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'medical-exam') {
                $checkorderIdinMed = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_medical_exam')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinMed) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_medical_exam')
                    ->insert([
                        'lme_id' => 'le-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'papsmear-test') {
                $checkorderIdinPapsmear = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_papsmear')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinPapsmear) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_papsmear')
                    ->insert([
                        'ps_id' => 'ps-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'oral-glucose') {
                $checkorderIdinOralGlucose = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_oral_glucose')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinOralGlucose) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_oral_glucose')
                    ->insert([
                        'log_id' => 'log-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'thyroid-profile') {
                $checkorderIdinThyroid = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_thyroid_profile')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinThyroid) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_thyroid_profile')
                    ->insert([
                        'ltp_id' => 'ltp-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'immunology') {
                $checkorderIdinImmunology = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_immunology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinImmunology) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_immunology')
                    ->insert([
                        'li_id' => 'li-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'miscellaneous') {
                $checkorderIdinMiscellaneous = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_miscellaneous')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinMiscellaneous) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_miscellaneous')
                    ->insert([
                        'lm_id' => 'lm-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            if ($v->department == 'hepatitis-profile') {
                $checkorderIdinHepatitis = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hepatitis_profile')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinHepatitis) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hepatitis_profile')
                    ->insert([
                        'lhp_id' => 'lhp-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => $data['remarks'],
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($v->department == 'covid-19') {
                $checkorderIdinHepa = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_covid19_test')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_covid19_test')
                        ->insert([
                            'lct_id' => 'lct-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            if ($v->department == 'Tumor Maker') {
                $checkorderIdinHepa = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_tumor_maker')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_tumor_maker')
                        ->insert([
                            'ltm_id' => 'ltm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }

            if ($v->department == 'Drug Test') {
                $checkorderIdinHepa = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_drug_test')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_drug_test')
                        ->insert([
                            'ldt_id' => 'ldt-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $trace_number,
            'patient_id' => $data['patient_id'],
            'category' => 'laboratory',
            'department' => 'doctor',
            'message' => "New laboratory test added by endorsement.",
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('cashier_patientbills_unpaid')
        ->insert($secrtry_orderunpaid);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('laboratory_unsaveorder')
        ->where('patient_id', $data['patient_id'])
        ->where('laborotary_id', _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id)
        ->delete();
    }

    public static function paidLabOrderDetails($data)
    {

        date_default_timezone_set('Asia/Manila');
        return DB::table($data['table'])
            ->join('patients', 'patients.patient_id', '=', $data['table'] . '.' . 'patient_id')
            ->select($data['table'] . '.' . '*', 'patients.firstname as fname', 'patients.lastname as lname')
            ->where($data['table'] . '.' . 'trace_number', $data['trace_number'])
            ->where($data['table'] . '.' . 'patient_id', $data['patient_id'])
            ->get();
    }

    public static function getImagingDetails($data)
    {
        return DB::table('imaging')->where('management_id', $data['management_id'])->groupBy('imaging_id')->get();
    }

    public static function imagingOrderList($data)
    {
        return DB::table('imaging_order_menu')
            ->join('imaging', 'imaging.management_id', '=', 'imaging_order_menu.management_id')
            ->select('imaging.name', 'imaging_order_menu.*', 'imaging_order_menu.order_desc as label', 'imaging_order_menu.order_desc as value')
            ->where('imaging_order_menu.management_id', $data['vmanagementId'])
            ->get();
    }

    public static function getImagingOrderList($data)
    {
        return DB::table('imaging_center')
            ->where('manage_by', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->get();
    }

    public static function getPackagesList($data)
    {
        return DB::table('packages_charge')
            ->select('*', 'package_name as label', 'package_id as value')
            ->where('management_id', $data['management_id'])
            ->groupBy('package_id')
            ->orderBy('package_name', 'asc')
            ->get();
    }

    public static function getUnpaidListByPatientId($data)
    {
        return DB::table('packages_order_list_temp')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function savePackageOrderTemp($data)
    {
        return DB::table('packages_order_list_temp')->insert([
            'order_id' => 'order-' . rand(0, 9999) . time(),
            'trace_number' => $data['trace_number'],

            'package_id' => $data['package_id'],
            'management_id' => $data['management_id'],
            'patient_id' => $data['patient_id'],
            'package_name' => $data['billname'],
            'package_amount' => $data['rate'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function setAsDone($data)
    {
        return DB::table('patient_queue')
            ->where('patient_id', $data['patient_id'])
            ->where('type', 'endorsement')
            ->update([
                'type' => 'cashier',
                'transaction_type' => 'corporate',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getCompany($data)
    {
        return DB::table('management_accredited_companies')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getCompanyHMO($data)
    {
        return DB::table('management_accredited_company_hmo')
            ->where('management_id', $data['management_id'])
            ->where('company_id', $data['company_id'])
            ->get();
    }

    public static function updatePersonalInfo($data)
    {
        return DB::table('endorsement_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function endorsementGetPersonalInfoById($data){
        $query = "SELECT * FROM endorsement_account WHERE user_id = '".$data['user_id']."' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ); 
    }


    public static function addPEOrderTounsave($data)
    {

        $countCheck = DB::table('laboratory_unsaveorder')->where('laboratory_test_id', $data['laboratory_test_id'])->where('management_id', $data['management_id'])->get();

        if (count($countCheck) > 0) {
            return 2;
        }

        return DB::connection('mysql')
            ->table('laboratory_unsaveorder')
            ->insert([
                'lu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'doctor_id' => 'cashier-addons',
                'laborotary_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'laboratory_test_id' => $data['laboratory_test_id'],
                'laboratory_test' => $data['laboratory_test'],
                'laboratory_rate' => $data['laboratory_rate'],
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function processPEOrder($data)
    {
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('laborotary_id', _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id)
            ->get();

        $trace_number = $data['trace_number'];
        $secrtry_orderunpaid = [];

        foreach ($query as $v) {
            $orderid = $v->laboratory_test_id;

            $secrtry_orderunpaid[] = array(
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'doctors_id' => $v->doctor_id,
                'patient_id' => $data['patient_id'],
                'management_id' => _Endorsement::getLaboratoryIdByMgt($data)->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $v->laboratory_test,
                'bill_amount' => $v->laboratory_rate,
                'bill_department' => $v->department,
                'bill_from' => 'medical examination',
                'order_id' => $orderid,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($v->department == 'medical-exam') {
                $checkorderIdinMed = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_medical_exam')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinMed) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_medical_exam')
                        ->insert([
                            'lme_id' => 'le-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'doctor_id' => $data['doctors_id'],
                            'laboratory_id' => _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $trace_number,
            'patient_id' => $data['patient_id'],
            'category' => 'medical-exam',
            'department' => 'doctor',
            'message' => "New Pe order added by cashier.",
            'is_view' => 0,
            'notification_from' => 'local',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->insert($secrtry_orderunpaid);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('laborotary_id', _Endorsement::getLaboratoryIdByMgt($data)->laboratory_id)
            ->delete();
    }

    public static function PEUnpaidOrderByPatient($data)
    {

        $patientid = $data['patient_id'];

        $query = "SELECT * from cashier_patientbills_unpaid where patient_id = '$patientid' and bill_from = 'medical examination' group by trace_number order by created_at asc";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
        // return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        //     ->table('cashier_patientbills_unpaid')
        //     ->where('patient_id', $data['patient_id'])
        //     ->where('bill_from', 'laboratory')
        //     ->groupBy('trace_number')
        //     ->orderBy('created_at', 'desc')
        //     ->get();
    }

    public static function PEUnpaidOrderByPatientDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_unpaid')
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->orderBy('bill_name', 'asc')
            ->get();
    }

    public static function getDoctorsServices($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('doctors_appointment_services')
            ->where('doctors_id', $data['doctor_id'])
            ->where('management_id', $data['management_id'])
            ->orderBy('services', 'asc')
            ->get();
    } 
    

}
