<?php

namespace App\Models;

use App\Models\_LaboratoryOrder;
use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;

class _Cashier extends Model
{
    public static function getLaboratoryIdByMgt($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_list')->where('management_id', $data['management_id'])->first();
    }

    public static function getPsychologyIdByMgt($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('psychology_account')->where('management_id', $data['management_id'])->first();
    }

    public static function hiscashierGetHeaderInfo($data)
    {
        return DB::table('cashier')
            ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'cashier.user_id')
            ->select('cashier.cashier_id', 'cashier.user_fullname as name', 'cashier.image', 'cashier.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
            ->where('cashier.user_id', $data['user_id'])
            ->first();
    }

    public static function doctorCountQueue($data)
    {
        $doctorQueue = DB::table('patient_queue')->where('patient_id', $data['patient_id'])->where('type', 'doctor')->get();

        if (count($doctorQueue) < 1) {
            DB::table('patient_queue')
                ->insert([
                    'pq_id' => 'pq-' . rand(0, 99) . time(),
                    'patient_id' => $data['patient_id'],
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'doctor_id' => $data['doctor'],
                    'type' => 'doctor',
                    'priority_sequence' => 3,
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return true;
    }

    public static function laboratoryCountQueue($v, $data)
    {
        $laboratoryQueue = DB::table('patient_queue')->where('patient_id', $data['patient_id'])->where('type', 'laboratory')->get();

        if (count($laboratoryQueue) < 1) {
            // DB::table('patient_queue')
            // ->insert([
            //     'pq_id' => 'pq-'.rand(0, 99).time(),
            //     'patient_id' => $data['patient_id'],
            //     'management_id' => $data['management_id'],
            //     'main_mgmt_id' => $data['main_mgmt_id'],
            //     'type' => 'receiving',
            //     'trace_number' => $v->trace_number,
            //     'priority_sequence'=> 5,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
            DB::table('patient_queue')
                ->insert([
                    'pq_id' => 'pq-' . rand(0, 99) . time(),
                    'patient_id' => $data['patient_id'],
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'type' => 'laboratory',
                    'trace_number' => $v->trace_number,
                    'priority_sequence' => 6,
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return true;
    }

    public static function imagingCountQueue($data, $trace_number)
    {
        $imagingQueue = DB::table('patient_queue')->where('patient_id', $data['patient_id'])->where('type', 'imaging')->get();

        if (count($imagingQueue) < 1) {
            DB::table('patient_queue')
                ->insert([
                    'pq_id' => 'pq-' . rand(0, 99) . time(),
                    'patient_id' => $data['patient_id'],
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'trace_number' => $trace_number,

                    'type' => 'imaging',
                    'priority_sequence' => 7,
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return true;
    }

    public static function psychologyCountQueue($data, $trace_number)
    {
        $psychologyQueue = DB::table('patient_queue')->where('patient_id', $data['patient_id'])->where('type', 'psychology')->get();

        if (count($psychologyQueue) < 1) {
            DB::table('patient_queue')
                ->insert([
                    'pq_id' => 'pq-' . rand(0, 99) . time(),
                    'patient_id' => $data['patient_id'],
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'trace_number' => $trace_number,

                    'type' => 'psychology',
                    'priority_sequence' => 8,
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return true;
    }

    public static function getImagingIdByMgtId($management_id)
    {
        return DB::table('imaging')->where('management_id', $management_id)->first();
    }

    public static function hiscashierGetPatientsBillings($data)
    {
        return DB::connection('mysql')
            ->table('cashier_patientbills_unpaid')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_unpaid.patient_id')
            ->select('cashier_patientbills_unpaid.*', 'patients.firstname as fname', 'patients.lastname as lname', 'patients.*')
            ->where('cashier_patientbills_unpaid.management_id', $data['management_id'])
            ->groupBy('cashier_patientbills_unpaid.patient_id')
            ->get();
    }

    public static function getPatientsBillingsDetails($data)
    {
        return DB::connection('mysql')
            ->table('encoder_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function hiscashierBillingCancel($data)
    {
        if ($data['bill_from'] == 'laboratory') {
            _Cashier::checkAndDeleteLabOrders($data);
        }
        return DB::connection('mysql')->table('cashier_patientbills_unpaid')
            ->where('cpb_id', $data['cancel_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function checkAndDeleteLabOrders($data)
    {
        if ($data['bill_department'] == 'hemathology') {
            if ($data['bill_name'] == 'cbc' || $data['bill_name'] == 'cbc platelet') {
                DB::table('laboratory_cbc')
                    ->where('order_id', $data['order_id'])
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $data['trace_number'])
                    ->delete();
            } else {
                DB::table('laboratory_hematology')
                    ->where('order_id', $data['order_id'])
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $data['trace_number'])
                    ->delete();
            }
        }
        if ($data['bill_department'] == 'serology') {
            DB::table('laboratory_sorology')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'clinical-microscopy') {
            DB::table('laboratory_microscopy')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'fecal-analysis') {
            DB::table('laboratory_fecal_analysis')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'stool-test') {
            DB::table('laboratory_stooltest')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'clinical-chemistry') {
            DB::table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'urinalysis') {
            DB::table('laboratory_urinalysis')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'ecg') {
            DB::table('laboratory_ecg')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'medical-exam') {
            DB::table('laboratory_medical_exam')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'papsmear-test') {
            DB::table('laboratory_papsmear')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'oral-glucose') {
            DB::table('laboratory_oral_glucose')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'thyroid-profile') {
            DB::table('laboratory_thyroid_profile')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'immunology') {
            DB::table('laboratory_immunology')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'miscellaneous') {
            DB::table('laboratory_miscellaneous')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'hepatitis-profile') {
            DB::table('laboratory_hepatitis_profile')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'Tumor Maker') {
            DB::table('laboratory_tumor_maker')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
        if ($data['bill_department'] == 'Drug Test') {
            DB::table('laboratory_drug_test')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->where('patient_id', $data['patient_id'])
                ->where('trace_number', $data['trace_number'])
                ->delete();
        }
    }

    public static function hiscashierBillingSetAsPaid($data)
    {
        date_default_timezone_set('Asia/Manila');
        $imaging_id = _Cashier::getImagingIdByMgtId($data['management_id'])->imaging_id;

        $query = DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();

        $records = [];
        $billout = [];
        $orderToLab = [];
        $hemoglobin = [];
        $imagingcenter = [];

        foreach ($query as $v) {
            $cpr_id = rand(0, 9999) . '-' . time();
            $records[] = array(
                'cpr_id' => $cpr_id,
                'trace_number' => $v->trace_number,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'doctors_id' => !empty($data['doctor']) ? $data['doctor'] : $v->doctors_id,
                'patient_id' => $v->patient_id,
                // 'charge_type' => $data['transaction_type'] == 'corporate' ? ($data['payment_type'] != 'cash' ? ($data['hmo'] == 'direct' ? 'direct' : 'hmo') : 'direct') : 'walkin',
                'charge_type' => $data['payment_type'] == 'cash' ? 'cash' : ($data['payment_type'] == 'branch' ? 'cash' : 'charge'),
                // 'hmo_used' => $data['transaction_type'] == 'corporate' ? ($data['payment_type'] != 'cash' ? ($data['hmo'] == 'direct' ? $data['patient_company'] : $data['hmo']) : $data['patient_company']) : NULL,
                'hmo_used' => $data['payment_type'] == 'cash' ? null : ($data['payment_type'] == 'branch' ? null : ($data['payment_type'] == 'hmo' ? $data['hmo'] : ($data['payment_type'] == 'direct' ? $data['patient_company'] : $data['company_hmo']))),
                'hmo_category' => $data['payment_type'] == 'cash' ? null : ($data['payment_type'] == 'branch' ? null : ($data['payment_type'] == 'hmo' ? 'clinic-hmo' : ($data['payment_type'] == 'direct-company' ? 'company' : 'hmo'))),
                'bill_name' => $v->bill_name,
                'bill_amount' => $v->bill_amount,
                'bill_from' => $v->bill_from,
                'bill_payment' => $data['payment_type'] == 'cash' ? $data['payment'] : $data['amountto_pay'],
                'bill_department' => $v->bill_department,
                'bill_total' => $data['amountto_pay'],
                'transaction_category' => !empty($data['transaction_category']) ? $data['transaction_category'] : 'regular',
                'home_service' => $data['home_service'],
                'discount' => $data['discount'],
                'discount_reason' => $data['discount_reason'],
                'note' => $data['note'],
                'process_by' => $data['user_id'],
                'receipt_number' => $data['receipt_number'],
                'order_id' => $v->order_id,
                'request_physician' => $data['request_physician'],
                'is_charged_paid' => $data['payment_type'] == 'cash' ? 1 : ($data['payment_type'] == 'branch' ? 1 : 0),
                'is_charged' => $data['payment_type'] == 'cash' ? 0 : ($data['payment_type'] == 'branch' ? 0 : 1),
                'is_report_generate' => 0,
                'can_be_discounted' => $v->can_be_discounted,
                'order_from' => $data['payment_type'] == 'direct-company' ? 'mobile-van' : ($data['payment_type'] == 'van' ? 'mobile-van' : 'clinic'),
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );

            if ($data['payment_type'] == 'branch') {
                $billout[] = array(
                    'csat_id' => 'csat-' . rand(0, 9) . time(),
                    'cpr_id' => $cpr_id,
                    'trace_number' => $v->trace_number,
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'doctors_id' => !empty($data['doctor']) ? $data['doctor'] : $v->doctors_id,
                    'patient_id' => $v->patient_id,
                    'hmo_used' => $data['payment_type'] == 'cash' ? null : ($data['payment_type'] == 'branch' ? null : ($data['payment_type'] == 'hmo' ? $data['hmo'] : ($data['payment_type'] == 'direct' ? $data['patient_company'] : $data['company_hmo']))),
                    'hmo_category' => $data['payment_type'] == 'cash' ? null : ($data['payment_type'] == 'branch' ? null : ($data['payment_type'] == 'hmo' ? 'clinic-hmo' : ($data['payment_type'] == 'direct-company' ? 'company' : 'hmo'))),
                    'charge_type' => $data['payment_type'] == 'cash' ? 'cash' : ($data['payment_type'] == 'branch' ? 'cash' : 'charge'),
                    'bill_name' => $v->bill_name,
                    'bill_amount' => $v->bill_amount,
                    'bill_from' => $v->bill_from,
                    'bill_payment' => $data['payment_type'] == 'cash' ? $data['payment'] : $data['amountto_pay'],
                    'bill_department' => $v->bill_department,
                    'bill_total' => $data['amountto_pay'],
                    'transaction_category' => !empty($data['transaction_category']) ? $data['transaction_category'] : 'regular',
                    'home_service' => $data['home_service'],
                    'discount' => $data['discount'],
                    'discount_reason' => $data['discount_reason'],
                    'note' => $data['note'],
                    'process_by' => $data['user_id'],
                    'receipt_number' => $data['receipt_number'],
                    'order_id' => $v->order_id,
                    'request_physician' => $data['request_physician'],
                    'is_charged_paid' => $data['payment_type'] == 'cash' ? 1 : ($data['payment_type'] == 'branch' ? 1 : 0),
                    'is_charged' => $data['payment_type'] == 'cash' ? 0 : ($data['payment_type'] == 'branch' ? 0 : 1),
                    'is_report_generate' => 0,
                    'can_be_discounted' => $v->can_be_discounted,
                    'order_from' => $data['payment_type'] == 'direct-company' ? 'mobile-van' : ($data['payment_type'] == 'van' ? 'mobile-van' : 'clinic'),
                    'bill_out_branch' => $data['bill_out_branch'],
                    'bill_date' => date('Y-m-d H:i:s'),
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }

            if ($v->bill_from == "packages") {
                _LaboratoryOrder::newPackagesOrder($v, $data);
                DB::table('packages_order_list')->insert([
                    'pol_id' => 'pol-' . rand() . '-' . time(),
                    'order_id' => $v->order_id,
                    'trace_number' => $v->trace_number,
                    'package_id' => $v->laboratory_id,
                    'management_id' => $v->management_id,
                    'patient_id' => $v->patient_id,
                    'package_name' => $v->bill_name,
                    'package_amount' => $v->bill_amount,
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if ($v->bill_from == 'appointment') {
                DB::table('appointment_list')
                    ->where('appointment_id', $v->trace_number)
                    ->update([
                        'is_paid_bysecretary' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            if ($v->bill_department == 'medical-exam') {
                _Cashier::laboratoryCountQueue($v, $data);
                _LaboratoryOrder::newMedicalExamOrder($v, $data);
            }

            if ($v->bill_department == 'doctor-services') {
                $patient_userid = DB::table('patients')->select('user_id')->where('patient_id', $v->patient_id)->first();
                $doctor_userid = DB::table('doctors')->select('user_id')->where('doctors_id', $v->doctors_id)->first();

                $checkPermission = DB::table('patients_permission')
                    ->where('patients_id', $v->patient_id)
                    ->where('doctors_id', $v->doctors_id)
                    ->where('permission_status', 'approved')
                    ->get();

                if (count($checkPermission) < 1) {
                    DB::table('patients_permission')
                        ->insert([
                            'permission_id' => 'permission-' . rand(0, 9999),
                            'patients_id' => $patient_userid->user_id,
                            'doctors_id' => $doctor_userid->user_id,
                            'permission_on' => 'PROFILE',
                            'status' => 1,
                            'permission_status' => 'approved',
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                }

                DB::table('doctors_medical_certificate_ordered')->insert([
                    'lmc_id' => 'lmc-' . rand(0, 9999) . time(),
                    'patient_id' => $v->patient_id,
                    'doctors_id' => $v->doctors_id,
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'service_id' => $v->order_id,
                    'service_name' => $v->bill_name,
                    'service_rate' => $v->bill_amount,
                    'order_status' => 'new-order-paid',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if ($v->bill_department == 'Other Test') {
                $patient_userid = DB::table('patients')->select('user_id')->where('patient_id', $v->patient_id)->first();
                $doctor_userid = DB::table('doctors')->select('user_id')->where('doctors_id', $data['doctor'])->first();

                if ($v->bill_name == 'Physical Examination') {
                    DB::table('laboratory_medical_exam')
                        ->where('order_id', $v->order_id)
                        ->where('patient_id', $v->patient_id)
                        ->where('trace_number', $v->trace_number)
                        ->update([
                            'doctor_id' => $data['doctor'],
                            'medical_exam' => 1,
                            'order_status' => 'new-order-paid',
                        ]);
                }

                if ($v->bill_name == 'Medical Certificate') {

                    $checkPermission = DB::table('patients_permission')
                        ->where('patients_id', $v->patient_id)
                        ->where('doctors_id', $data['doctor'])
                        ->where('permission_status', 'approved')
                        ->get();

                    if (count($checkPermission) < 1) {
                        DB::table('patients_permission')
                            ->insert([
                                'permission_id' => 'permission-' . rand(0, 9999),
                                'patients_id' => $patient_userid->user_id,
                                'doctors_id' => $doctor_userid->user_id,
                                'permission_on' => 'PROFILE',
                                'status' => 1,
                                'permission_status' => 'approved',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                    }

                    DB::table('doctors_medical_certificate_ordered')->insert([
                        'lmc_id' => 'lmc-' . rand(0, 9999) . time(),
                        'patient_id' => $v->patient_id,
                        'doctors_id' => $data['doctor'],
                        'management_id' => $data['management_id'],
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'service_id' => $v->order_id,
                        'service_name' => $v->bill_name,
                        'service_rate' => $v->bill_amount,
                        'order_status' => 'new-order-paid',
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($v->bill_from == 'laboratory') {
                _Cashier::laboratoryCountQueue($v, $data);

                if ($v->bill_department == 'hemathology') {
                    _LaboratoryOrder::newHemathologyOrder($v, $data);
                }
                if ($v->bill_department == 'serology') {
                    _LaboratoryOrder::newSorologyOrder($v, $data);
                }
                if ($v->bill_department == 'clinical-microscopy') {
                    _LaboratoryOrder::newClinicMicroscopyOrder($v, $data);
                }
                // if ($v->bill_department == 'fecal-analysis') {
                //     _LaboratoryOrder::newFecalAnalysisOrder($v, $data);
                // }
                if ($v->bill_department == 'clinical-chemistry') {
                    _LaboratoryOrder::newClinicChemistryOrder($v, $data);
                }
                if ($v->bill_department == 'stool-test') {
                    _LaboratoryOrder::newStoolTestOrder($v, $data);
                }
                if ($v->bill_department == 'papsmear-test') {
                    _LaboratoryOrder::newPapsmearTestOrder($v, $data);
                }
                if ($v->bill_department == 'urinalysis') {
                    _LaboratoryOrder::newUrinalysisOrder($v, $data);
                }
                if ($v->bill_department == 'ecg') {
                    _LaboratoryOrder::newECGOrder($v, $data);
                }
                // if ($v->bill_department == 'medical-exam') {
                //     _LaboratoryOrder::newMedicalExamOrder($v, $data);
                // }
                if ($v->bill_department == 'oral-glucose') {
                    _LaboratoryOrder::newOralGlucoseOrder($v, $data);
                }
                if ($v->bill_department == 'thyroid-profile') {
                    _LaboratoryOrder::newThyroidProfileOrder($v, $data);
                }
                if ($v->bill_department == 'immunology') {
                    _LaboratoryOrder::newImmunologyOrder($v, $data);
                }
                if ($v->bill_department == 'miscellaneous') {
                    _LaboratoryOrder::newMiscellaneousOrder($v, $data);
                }
                if ($v->bill_department == 'hepatitis-profile') {
                    _LaboratoryOrder::newHepatitisProfileOrder($v, $data);
                }
                if ($v->bill_department == 'covid-19') {
                    _LaboratoryOrder::newCovid19TestOrder($v, $data);
                }
                if ($v->bill_department == 'Tumor Maker') {
                    _LaboratoryOrder::newTumorMakerTestOrder($v, $data);
                }
                if ($v->bill_department == 'Drug Test') {
                    _LaboratoryOrder::newDrugTestTestOrder($v, $data);
                }
            }

            if ($v->bill_from == 'imaging') {
                _Cashier::imagingCountQueue($data, $v->trace_number);

                $imagingcenter[] = array(
                    'imaging_center_id' => rand(0, 999) . '-' . time(),
                    'patients_id' => $v->patient_id,
                    'doctors_id' => $v->doctors_id,
                    'trace_number' => $v->trace_number,
                    'imaging_order' => $v->bill_name,
                    'imaging_center' => $imaging_id,
                    'is_viewed' => 1,
                    'manage_by' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'order_from' => 'local',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }

            if ($v->bill_from == 'psychology') {
                _Cashier::psychologyCountQueue($data, $v->trace_number);

                if ($v->bill_name == 'Ishihara') {
                    _LaboratoryOrder::newIshiharaProfileOrder($v, $data);
                }

                if ($v->bill_name == 'Audiometry') {
                    _LaboratoryOrder::newAudiometryProfileOrder($v, $data);
                }

                if ($v->bill_name == 'Neuro Examination') {
                    _LaboratoryOrder::newNeuroProfileOrder($v, $data);
                }
            }
        }

        DB::table('patients_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99) . time(),
            'order_id' => $query[0]->order_id,
            'patient_id' => $query[0]->patient_id,
            'doctor_id' => $query[0]->doctors_id,
            'category' => 'laboratory',
            'department' => 'doctor-secretary',
            'message' => "cashier confirm your payment.",
            'is_view' => 0,
            'notification_from' => 'virtual',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('imaging_center')->insert($imagingcenter);

        DB::table('cashier_patientbills_records')->insert($records);

        DB::table('cashier_statement_of_account_temp')->insert($billout);

        DB::table('patient_queue')->where('patient_id', $data['patient_id'])->where('type', 'cashier')->delete();

        return DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function hiscashierGetBillingRecords($data)
    {
        $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment,  patients.firstname as fname, patients.lastname as lname, patients.street as street, patients.barangay as barangay, patients.city as city,
            (SELECT IFNULL(sum(rate), 0) from laboratory_items_laborder where laboratory_items_laborder.order_id = cashier_patientbills_records.order_id AND laboratory_items_laborder.can_be_discounted = 0 ) as totalnotdiscounted,
            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = cashier_patientbills_records.receipt_number and is_refund = 1) as totalrefund

        from cashier_patientbills_records, patients
        where cashier_patientbills_records.management_id = '" . $data['management_id'] . "'
        and patients.patient_id = cashier_patientbills_records.patient_id
        group by cashier_patientbills_records.receipt_number
        order by cashier_patientbills_records.created_at desc";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hiscashierRefundOrderList($data)
    {
        return DB::connection('mysql')->table('cashier_patientbills_records')
            ->where('is_refund', 1)
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function hiscashierGetBillingRecordsDetails($data)
    {
        return DB::connection('mysql')->table('cashier_patientbills_records')
            ->where('management_id', $data['management_id'])
            ->where('receipt_number', $data['receipt_id'])
            ->where('trace_number', $data['trace_number'])
            ->get();
    }

    public static function hiscashierRefundOrder($data)
    {
        date_default_timezone_set('Asia/Manila');
        if ($data['department'] == 'hemathology') {
            _LaboratoryOrder::refundHemathologyOrder($data);
        }

        if ($data['department'] == 'clinical-microscopy') {
            _LaboratoryOrder::refundClinicMicroscopyOrder($data);
        }

        if ($data['department'] == 'fecal-analysis') {
            _LaboratoryOrder::refundFecalAnalysisOrder($data);
        }

        if ($data['department'] == 'serology') {
            _LaboratoryOrder::refundSorologyOrder($data);
        }

        if ($data['department'] == 'clinical-chemistry') {
            _LaboratoryOrder::refundClinicChemistryOrder($data);
        }

        if ($data['department'] == 'stool-test') {
            _LaboratoryOrder::refundStoolTestOrder($data);
        }

        if ($data['department'] == 'urinalysis') {
            _LaboratoryOrder::refundUrinalysisOrder($data);
        }

        if ($data['department'] == 'ecg') {
            _LaboratoryOrder::refundECGOrder($data);
        }

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('cashier_patientbills_records')
            ->where('cpr_id', $data['cpr_id'])
            ->update([
                'is_refund' => 1,
                'is_refund_reason' => $data['refund_reason'],
                'is_refund_date' => date('Y-m-d H:i:s'),
                'is_refund_by' => $data['user_id'],
            ]);
    }

    public static function hiscashierGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM cashier WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hiscashierUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('cashier')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hiscashierUpdatePersonalInfo($data)
    {
        return DB::table('cashier')
            ->where('user_id', $data['user_id'])
            ->update([
                'user_fullname' => $data['fullname'],
                'user_address' => $data['address'],
                'email' => $data['email'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hiscashierUpdateUsername($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'username' => $data['new_username'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hiscashierUpdatePassword($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('users')
            ->where('user_id', $data['user_id'])
            ->update([
                'password' => Hash::make($data['new_password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hiscashierGetPatientsBillingsDetails($data)
    {
        $query = "SELECT *, patient_id as patientId,
            ( SELECT company from patients where patient_id = patientId) as patientcompany,
            ( SELECT company from management_accredited_companies where management_accredited_companies.company_id = patientcompany) as company
        from cashier_patientbills_unpaid where management_id = '" . $data['management_id'] . "' and patient_id = '" . $data['patient_id'] . "' ";
        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);

        // return DB::connection('mysql')->table('cashier_patientbills_unpaid')
        //     ->where('management_id', $data['management_id'])
        //     ->where('patient_id', $data['patient_id'])
        //     ->get();
    }

    public static function hiscashierReceiptDetails($data)
    {
        // return DB::connection('mysql')->table('cashier_patientbills_records')
        //     ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')

        //     ->leftJoin('cashier', 'cashier.user_id', '=', 'cashier_patientbills_records.process_by')

        //     ->leftJoin('laboratory_items_laborder', 'laboratory_items_laborder.order_id', '=', 'cashier_patientbills_records.order_id')
        //     ->select('cashier_patientbills_records.*', 'cashier.user_fullname as cashierFName', 'patients.firstname as fname', 'patients.lastname as  lname', 'patients.middle as  mname', 'patients.gender', 'patients.birthday', 'patients.street as street', 'patients.barangay as barangay', 'patients.city as city', 'laboratory_items_laborder.can_be_discounted')
        //     ->where('cashier_patientbills_records.receipt_number', $data['receipt_number'])
        //     ->where('cashier_patientbills_records.management_id', $data['management_id'])
        //     ->get();

        $query = "SELECT *, patient_id as patientId, order_id as orderId, process_by as processBy,

            ( SELECT user_fullname from cashier where cashier.user_id = processBy limit 1) as cashier_name,

            (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
            (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

            ( SELECT firstname from patients where patients.patient_id = patientId limit 1) as fname,
            ( SELECT lastname from patients where patients.patient_id = patientId limit 1) as lname,
            ( SELECT middle from patients where patients.patient_id = patientId limit 1) as mname,
            ( SELECT gender from patients where patients.patient_id = patientId limit 1) as gender,
            ( SELECT birthday from patients where patients.patient_id = patientId limit 1) as birthday,
            ( SELECT street from patients where patients.patient_id = patientId limit 1) as street,
            ( SELECT barangay from patients where patients.patient_id = patientId limit 1) as barangay,
            ( SELECT city from patients where patients.patient_id = patientId limit 1) as city

        from cashier_patientbills_records where cashier_patientbills_records.receipt_number = '" . $data['receipt_number'] . "' AND cashier_patientbills_records.management_id = '" . $data['management_id'] . "' ";
        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function refundOrderList($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('cashier_patientbills_records')
            ->where('is_refund', 1)
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getBillingRecordsDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('cashier_patientbills_records')
            ->where('receipt_number', $data['receipt_id'])
            ->where('order_id', $data['order_id'])
            ->get();
    }

    public static function getBillingRecords($data)
    {
        // $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment,  patients.firstname as fname, patients.lastname as lname, patients.street as street, patients.barangay as barangay, patients.city as city,

        //     (SELECT IFNULL(count(can_be_discounted), 0) from laboratory_items_laborder where laboratory_items_laborder.order_id = cashier_patientbills_records.order_id AND laboratory_items_laborder.can_be_discounted = 0) as can_be_discounted,

        //     -- (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = cashier_patientbills_records.receipt_number AND laboratory_items_laborder.can_be_discounted = 0 LIMIT 1) as totalnodiscount,

        //     (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = cashier_patientbills_records.receipt_number and is_refund = 1) as totalrefund

        // from cashier_patientbills_records, patients
        // where cashier_patientbills_records.management_id = '" . $data['management_id'] . "'
        // and patients.patient_id = cashier_patientbills_records.patient_id
        // group by cashier_patientbills_records.receipt_number
        // order by cashier_patientbills_records.created_at desc";

        // $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);

        $management_id = $data['management_id'];
        $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.street as street, patients.barangay as barangay, patients.city as city, cashier_patientbills_records.management_id as mgmtID,

            (SELECT user_fullname FROM cashier WHERE cashier.management_id = mgmtID LIMIT 1) as cashierName,

            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,


            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_statement_of_account_temp ) as totalSoaTemp,


            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

        FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hiscashierGetHeaderReceipt($data)
    {
        return DB::table('cashier')
            ->select('cashier_id', 'name', 'email', 'address')
            ->where('user_id', $data['user_id'])
            ->first();
    }

    public static function cashierAddNewAddOns($data)
    {
        return DB::table("cashier_patientbills_unpaid")->insert([
            'cpb_id' => 'cpb-' . rand(0, 99) . time(),
            'trace_number' => 'trace-' . rand(0, 99) . time(),
            'doctors_id' => 'cashier-addons',
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'bill_name' => $data['addon_description'],
            'bill_amount' => $data['addon_amount'],
            'bill_department' => $data['department'],
            'bill_from' => $data['department'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getPatientsList($data)
    {
        return DB::table('patients')
            ->select('firstname', 'lastname', 'patient_id', 'image', 'middle')
            ->where('management_id', $data['management_id'])
            ->orderBy('lastname', 'ASC')
            ->get();
    }

    public static function getPatientInformation($data)
    {
        return DB::connection('mysql')->table('patients')
            ->where('patients.patient_id', $data['patient_id'])
            ->orderBy('patients.lastname', 'ASC')
            ->first();
    }

    public static function getPackageList($data)
    {
        return DB::table('packages_charge')
            ->select('packages_charge.*', 'packages_charge.package_name as label', 'packages_charge.package_id as value')
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
            // 'trace_number' => 'trace-' . rand(0, 9999) . time(),
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

    public static function saveOrderProcess($data)
    {
        $query = DB::table('packages_order_list_temp')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();

        $unpaid = [];

        foreach ($query as $x) {
            $unpaid = array(
                "cpb_id" => 'cpb-' . rand(0, 9999) . time(),
                "trace_number" => $data['trace_number'],
                "doctors_id" => 'order-from-cashier',
                "patient_id" => $x->patient_id,
                "management_id" => $x->management_id,
                "main_mgmt_id" => $data['main_mgmt_id'],
                "laboratory_id" => $x->package_id,
                "bill_name" => $x->package_name,
                "bill_amount" => $x->package_amount,
                "bill_department" => 'packages',
                "bill_from" => 'packages',
                "order_id" => $x->order_id,
                'can_be_discounted' => 1,
                'remarks' => 'Package is available, Now processing...',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('cashier_patientbills_unpaid')->insert($unpaid);

        return DB::table('packages_order_list_temp')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function getUnpaidOrderList($data)
    {
        return DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('bill_department', 'packages')
            ->where('bill_from', 'packages')
            ->get();
    }

    public static function getPaidOrderList($data)
    {
        return DB::table('packages_order_list')
            ->join('cashier_patientbills_records', 'cashier_patientbills_records.trace_number', '=', 'packages_order_list.trace_number')
            ->select('packages_order_list.*', 'cashier_patientbills_records.is_charged as charge_status')
            ->where('cashier_patientbills_records.is_charged', '<>', '0')
            ->where('packages_order_list.management_id', $data['management_id'])
            ->where('packages_order_list.patient_id', $data['patient_id'])
            ->get();
    }

    public static function getAllHmoList($data)
    {
        return DB::table('management_accredited_company_hmo')
            ->select('*', 'hmo as label', 'mach_id as value')
            ->where('company_id', $data['company_id'])
            ->orderBy('hmo', 'ASC')
            ->get();
    }

    public static function getAllCashierOnQueue($data)
    {
        return DB::table('patient_queue')
            ->leftJoin('patients', 'patients.patient_id', '=', 'patient_queue.patient_id')
            ->select('patient_queue.*', 'patients.firstname', 'patients.lastname', 'patients.image', 'patients.middle', 'patient_queue.patient_id', 'patient_queue.created_at', 'patients.company', 'patient_queue.transaction_type')
            ->where('patient_queue.type', 'cashier')
            ->groupBy('patient_queue.patient_id')
            ->get();
    }

    public static function getAllCashierBillingDetails($data)
    {
        $management_id = $data['management_id'];
        $patient_id = $data['patient_id'];

        // return DB::connection('mysql')
        //     ->table('cashier_patientbills_unpaid')
        //     ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_unpaid.patient_id')
        //     ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
        //     ->select('cashier_patientbills_unpaid.*', 'patients.firstname as fname', 'patients.lastname as lname', 'patients.*', 'management_accredited_companies.company as accredited_company')
        //     ->where('cashier_patientbills_unpaid.patient_id', $data['patient_id'])
        //     ->where('cashier_patientbills_unpaid.management_id', $data['management_id'])
        //     ->get();

        $query = " SELECT *,
            (SELECT laboratory_id FROM cashier_patientbills_unpaid WHERE bill_department = 'packages' AND patient_id = '$patient_id' limit 1) as packageID,
            (SELECT IFNULL(count(id), 0) FROM packages_charge WHERE packages_charge.package_id = packageID AND packages_charge.order_name = 'Physical Examination') as countPackagePhysicalExam,
            (SELECT IFNULL(count(id), 0) FROM packages_charge WHERE packages_charge.package_id = packageID AND packages_charge.order_name = 'Medical Certificate') as countPackageMedicalCert,
            (SELECT IFNULL(count(id), 0) FROM cashier_patientbills_unpaid WHERE bill_from = 'doctor' AND patient_id = '$patient_id' ) as countDoctor,
            (SELECT IFNULL(count(id), 0) FROM cashier_patientbills_unpaid WHERE bill_name = 'Physical Examination' AND bill_department = 'Other Test' AND patient_id = '$patient_id' ) as countPhysicalExam,
            (SELECT IFNULL(count(id), 0) FROM cashier_patientbills_unpaid WHERE bill_name = 'Medical Certificate' AND bill_department = 'Other Test' AND patient_id = '$patient_id' ) as countMedicalCert,
            (SELECT countPackagePhysicalExam+countPackageMedicalCert+countDoctor+countMedicalCert+countPhysicalExam) as totalOtherToDoctor,

            (SELECT firstname FROM patients WHERE patients.patient_id = cashier_patientbills_unpaid.patient_id limit 1) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = cashier_patientbills_unpaid.patient_id limit 1) as lname,
            (SELECT company FROM patients WHERE patients.patient_id = cashier_patientbills_unpaid.patient_id limit 1) as companyyy,
            (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = companyyy limit 1) as accredited_company,
            (SELECT can_be_discounted FROM laboratory_items_laborder WHERE laboratory_items_laborder.order_id = cashier_patientbills_unpaid.order_id limit 1) as can_be_discounted

        FROM cashier_patientbills_unpaid WHERE management_id = '$management_id' AND patient_id = '$patient_id' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    //8-25-2021
    public static function getLabOrderDeptDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_items_laborder')
            ->select('laboratory_items_laborder.*', 'laboratory_items_laborder.laborder as label', 'laboratory_items_laborder.laborder as value')
            ->where('laboratory_id', _Cashier::getLaboratoryIdByMgt($data)->laboratory_id)
            ->groupBy('order_id')
            ->get();
    }

    public static function addLabOrderTounsave($data)
    {
        $query = DB::table('laboratory_items_laborder')->select('can_be_discounted')->where('order_id', $data['laboratory_test_id'])->first();

        return DB::connection('mysql')
            ->table('laboratory_unsaveorder')
            ->insert([
                'lu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'doctor_id' => 'cashier-addons',
                'laborotary_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'laboratory_test_id' => $data['laboratory_test_id'],
                'laboratory_test' => $data['laboratory_test'],
                'laboratory_rate' => $data['laboratory_rate'],
                'can_be_discounted' => $query->can_be_discounted,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function removeLabOrderFromUnsave($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('id', $data['removeid'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function processLabOrder($data)
    {
        $trace_number = $data['trace_number'];

        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('laborotary_id', _Cashier::getLaboratoryIdByMgt($data)->laboratory_id)
            ->get();

        $secrtry_orderunpaid = [];

        foreach ($query as $v) {
            $orderid = $v->laboratory_test_id;

            $secrtry_orderunpaid[] = array(
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'doctors_id' => $v->doctor_id,
                'patient_id' => $data['patient_id'],
                'management_id' => $v->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $v->laboratory_test,
                'bill_amount' => $v->laboratory_rate,
                'bill_department' => $v->department,
                'bill_from' => 'laboratory',
                'order_id' => $orderid,
                'can_be_discounted' => $v->can_be_discounted,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($v->department == 'hemathology') {
                if ($v->laboratory_test == 'cbc' || $v->laboratory_test == 'cbc platelet') {

                    $checkCBC = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_cbc')
                        ->where('order_id', $orderid)
                        ->where('order_status', 'new-order')
                        ->where('patient_id', $data['patient_id'])
                        ->where('trace_number', $trace_number)
                        ->get();

                    if (count($checkCBC) < 1) {
                        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                            ->table('laboratory_cbc')
                            ->insert([
                                'lc_id' => 'lc-' . rand(0, 99999) . time(),
                                'order_id' => $orderid,
                                'patient_id' => $data['patient_id'],
                                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                                'remarks' => $data['remarks'],
                                'order_status' => 'new-order',
                                'trace_number' => $trace_number,
                                'status' => 1,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                    }
                } else {
                    $checkorderIdinHema = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_hematology')
                        ->where('order_id', $orderid)
                        ->where('order_status', 'new-order')
                        ->where('patient_id', $data['patient_id'])
                        ->where('trace_number', $trace_number)
                        ->get();

                    if (count($checkorderIdinHema) < 1) {
                        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                            ->table('laboratory_hematology')
                            ->insert([
                                'lh_id' => 'lh-' . rand(0, 9999) . time(),
                                'order_id' => $orderid,
                                'patient_id' => $data['patient_id'],
                                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinSoro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_sorology')
                        ->insert([
                            'ls_id' => 'ls-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinMicro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_microscopy')
                        ->insert([
                            'lm_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinFecal) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_fecal_analysis')
                        ->insert([
                            'lfa_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinStool) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_stooltest')
                        ->insert([
                            'lf_id' => 'lf-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinChem) < 1) {
                    if ($v->laboratory_test != 'soduim' && $v->laboratory_test != 'Potassium (k+)' && $v->laboratory_test != 'potassium' && $v->laboratory_test != 'calcium' && $v->laboratory_test != 'magnesium' && $v->laboratory_test != 'chloride' && $v->laboratory_test != 'NA+/K+' && $v->laboratory_test != 'Phosphorous') {
                        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                            ->table('laboratory_chemistry')
                            ->insert([
                                'lc_id' => 'lc-' . rand(0, 9999) . time(),
                                'order_id' => $orderid,
                                'patient_id' => $data['patient_id'],
                                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
            }
            if ($v->department == 'ecg') {
                $checkorderIdinEcg = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_ecg')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinEcg) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_ecg')
                        ->insert([
                            'le_id' => 'le-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinUri) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_urinalysis')
                        ->insert([
                            'lu_id' => 'lu-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinMed) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_medical_exam')
                        ->insert([
                            'lme_id' => 'le-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinPapsmear) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_papsmear')
                        ->insert([
                            'ps_id' => 'ps-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinOralGlucose) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_oral_glucose')
                        ->insert([
                            'log_id' => 'log-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinThyroid) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_thyroid_profile')
                        ->insert([
                            'ltp_id' => 'ltp-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinImmunology) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_immunology')
                        ->insert([
                            'li_id' => 'li-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinMiscellaneous) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_miscellaneous')
                        ->insert([
                            'lm_id' => 'lm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepatitis) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_hepatitis_profile')
                        ->insert([
                            'lhp_id' => 'lhp-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_covid19_test')
                        ->insert([
                            'lct_id' => 'lct-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_tumor_maker')
                        ->insert([
                            'ltm_id' => 'ltm-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                    ->where('patient_id', $data['patient_id'])
                    ->where('trace_number', $trace_number)
                    ->get();

                if (count($checkorderIdinHepa) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('laboratory_drug_test')
                        ->insert([
                            'ldt_id' => 'ldt-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
            'message' => "New laboratory test added by cashier.",
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
            ->where('laborotary_id', _Cashier::getLaboratoryIdByMgt($data)->laboratory_id)
            ->delete();
    }

    public static function getImagingDetails($data)
    {
        return DB::table('imaging')->where('management_id', $data['management_id'])->groupBy('imaging_id')->first();
    }

    public static function imagingOrderList($data)
    {
        // return DB::table('imaging_order_menu')
        //     ->join('imaging', 'imaging.management_id', '=', 'imaging_order_menu.management_id')
        //     ->select('imaging.name', 'imaging_order_menu.*', 'imaging_order_menu.order_desc as label', 'imaging_order_menu.order_desc as value')
        //     ->where('imaging_order_menu.management_id', $data['vmanagementId'])
        //     ->get();

        $management_id = $data['vmanagementId'];
        $query = "SELECT *, order_desc as label, order_desc as value,
            (SELECT name FROM imaging WHERE imaging.management_id = '$management_id' LIMIT 1) as name
        FROM imaging_order_menu WHERE management_id = '$management_id' ";

        // $query = "SELECT * from doctors_medical_certificate_ordered where patient_id = '$pid' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function imagingOrderSelectedDetails($data)
    {
        return DB::connection('mysql')->table('imaging_order_menu')
            ->where('order_id', $data['order_id'])
            ->first();
    }

    public static function imagingAddOrderUnsavelist($data)
    {
        return DB::table('imaging_center_unsaveorder')
            ->where('patients_id', $data['patient_id'])
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function imagingAddOrder($data)
    {
        date_default_timezone_set('Asia/Manila');

        return DB::table('imaging_center_unsaveorder')->insert([
            'icu_id' => 'icu-' . rand(0, 9999) . time(),
            'patients_id' => $data['patient_id'],
            'doctors_id' => 'cashier-addons',
            'imaging_order_id' => $data['imaging_order_id'],
            'imaging_order' => $data['order'],
            'imaging_order_remarks' => $data['remarks'],
            'amount' => $data['amount'],
            'management_id' => $data['imaging_center'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'trace_number' => $data['trace_number'],
            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
            'order_from' => 'local',
            'can_be_discounted' => 1,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function imagingOrderUnsaveProcess($data)
    {
        $unsave = DB::table('imaging_center_unsaveorder')
            ->where('management_id', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->get();

        $process = [];

        foreach ($unsave as $v) {
            $process[] = array(
                'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
                'trace_number' => $v->icu_id,
                'doctors_id' => $v->doctors_id,
                'patient_id' => $v->patients_id,
                'management_id' => $v->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => $v->laboratory_id,
                'bill_name' => $v->imaging_order,
                'bill_amount' => $v->amount,
                'trace_number' => $v->trace_number,
                'bill_department' => 'imaging',
                'bill_from' => 'imaging',
                'order_id' => $v->imaging_order_id,
                'can_be_discounted' => 1,
                'remarks' => $v->imaging_order_remarks,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
            );
        }

        DB::table('cashier_patientbills_unpaid')
            ->insert($process);

        return DB::table('imaging_center_unsaveorder')
            ->where('management_id', $data['management_id'])
            ->where('patients_id', $data['patient_id'])
            ->delete();
    }

    public static function getPsycOrderDeptDetails($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_test')
            ->select('psychology_test.*', 'psychology_test.test as label', 'psychology_test.test as value')
            ->where('psycho_id', _Cashier::getPsychologyIdByMgt($data)->psycho_id)
            ->get();
    }

    public static function getUnsavePsycOrder($data)
    {
        return DB::connection('mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function addPsycOrderTounsave($data)
    {
        return DB::connection('mysql')
            ->table('psychology_unsaveorder')
            ->insert([
                'pu_id' => rand(0, 9999) . time(),
                'patient_id' => $data['patient_id'],
                'trace_number' => $data['trace_number'],
                'doctor_id' => 'cashier-addons',
                'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'department' => $data['department'],
                'psychology_test_id' => $data['psychology_test_id'],
                'psychology_test' => $data['psychology_test'],
                'psychology_rate' => $data['psychology_rate'],
                'can_be_discounted' => 1,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function removePsyOrderFromUnsave($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_unsaveorder')
            ->where('id', $data['removeid'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public static function processPsychologyOrder($data)
    {
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('psychology_id', _Cashier::getPsychologyIdByMgt($data)->psycho_id)
            ->get();

        // $trace_number = 'order-' . rand(0, 9999) . time();
        $trace_number = $data['trace_number'];
        $secrtry_orderunpaid = [];

        foreach ($query as $v) {
            $orderid = $v->psychology_test_id;

            $secrtry_orderunpaid[] = array(
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'doctors_id' => $v->doctor_id,
                'patient_id' => $data['patient_id'],
                'management_id' => _Cashier::getPsychologyIdByMgt($data)->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
                'bill_name' => $v->psychology_test,
                'bill_amount' => $v->psychology_rate,
                'bill_department' => $v->department,
                'bill_from' => 'psychology',
                'order_id' => $orderid,
                'can_be_discounted' => 1,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($v->psychology_test == 'Audiometry') {
                $checkorderIdinAudio = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('psychology_audiometry')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinAudio) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('psychology_audiometry')
                        ->insert([
                            'pa_id' => 'pa-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }

            if ($v->psychology_test == 'Neuro Examination') {
                $checkorderIdinNeuro = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('psychology_neuroexam')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinNeuro) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('psychology_neuroexam')
                        ->insert([
                            'pn_id' => 'pn-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
                            'remarks' => $data['remarks'],
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }

            if ($v->psychology_test == 'Ishihara') {
                $checkorderIdinIshihara = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('psychology_ishihara')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinIshihara) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                        ->table('psychology_ishihara')
                        ->insert([
                            'pi_id' => 'pi-' . rand(0, 9999) . time(),
                            'order_id' => $orderid,
                            'patient_id' => $data['patient_id'],
                            'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
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
            'category' => 'psychology',
            'department' => 'doctor',
            'message' => "New psychology test.",
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
            ->table('psychology_unsaveorder')
            ->where('patient_id', $data['patient_id'])
            ->where('psychology_id', _Cashier::getPsychologyIdByMgt($data)->psycho_id)
            ->delete();

    }

    public static function getAllReport($data)
    {
        return DB::table('cashier_patientbills_records')
            ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        // ->leftJoin('management_accredited_company_hmo', 'management_accredited_company_hmo.mach_id', '=', 'cashier_patientbills_records.hmo_used')
            ->leftJoin('hmo_list', 'hmo_list.hl_id', '=', 'cashier_patientbills_records.hmo_used')
            ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
            ->select('cashier_patientbills_records.*', 'patients.firstname', 'patients.lastname', 'patients.company', 'management_accredited_companies.company as company_complete_name', 'hmo_list.name as hmo_complete_name')
            ->where('cashier_patientbills_records.management_id', $data['management_id'])
            ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
            ->groupBy('cashier_patientbills_records.receipt_number')
            ->orderBy('cashier_patientbills_records.created_at', 'DESC')
            ->get();
    }

    public static function getAllReportByFilter($data)
    {
        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_to']));
        $category = $data['category'];
        $cash_charge = $data['cash_charge'];
        $management_id = $data['management_id'];

        if ($category == 'all') {
            if ($cash_charge == 'all') {
                $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.company as CompanyID,

                    (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
                    (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = CompanyID LIMIT 1) company_complete_name,
                    (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

                    (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,
                    (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

                FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id AND cashier_patientbills_records.created_at BETWEEN '$date_from' and '$date_to' GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

                $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
                $result->execute();
                return $result->fetchAll(\PDO::FETCH_OBJ);
            } elseif ($cash_charge == 'cash') {
                $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.company as CompanyID,

                    (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
                    (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = CompanyID LIMIT 1) company_complete_name,
                    (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

                    (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,
                    (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

                FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id AND cashier_patientbills_records.created_at BETWEEN '$date_from' and '$date_to' AND cashier_patientbills_records.is_charged = 0 AND cashier_patientbills_records.is_charged_paid = 1 AND cashier_patientbills_records.hmo_category IS NULL GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

                $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
                $result->execute();
                return $result->fetchAll(\PDO::FETCH_OBJ);
            } else {
                $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.company as CompanyID,

                    (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
                    (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = CompanyID LIMIT 1) company_complete_name,
                    (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

                    (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,
                    (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

                FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id AND cashier_patientbills_records.created_at BETWEEN '$date_from' and '$date_to' AND cashier_patientbills_records.is_charged <> 0 AND cashier_patientbills_records.hmo_category IS NOT NULL GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

                $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
                $result->execute();
                return $result->fetchAll(\PDO::FETCH_OBJ);
            }
        }
        // elseif($category == 'corporate'){
        //     if($cash_charge == 'all'){
        //         //corporate all
        //         // return DB::table('cashier_patientbills_records')
        //         // ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        //         // ->leftJoin('management_accredited_company_hmo', 'management_accredited_company_hmo.mach_id', '=', 'cashier_patientbills_records.hmo_used')
        //         // ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
        //         // ->select('cashier_patientbills_records.*', 'patients.firstname', 'patients.lastname', 'patients.company', 'management_accredited_companies.company as company_complete_name', 'management_accredited_company_hmo.hmo as hmo_complete_name')
        //         // ->where('cashier_patientbills_records.management_id', $data['management_id'])
        //         // ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
        //         // ->whereDate('cashier_patientbills_records.created_at', '>=' , $date_from)
        //         // ->whereDate('cashier_patientbills_records.created_at', '<=' , $date_to)
        //         // ->where('cashier_patientbills_records.is_charged', 1)
        //         // ->groupBy('cashier_patientbills_records.receipt_number')
        //         // ->orderBy('cashier_patientbills_records.created_at', 'DESC')
        //         // ->get();

        //         $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.company as CompanyID,
        //             (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
        //             (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = CompanyID LIMIT 1) company_complete_name,
        //             (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

        //             (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,
        //             (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

        //         FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id AND cashier_patientbills_records.created_at BETWEEN '$date_from' and '$date_to' AND cashier_patientbills_records.is_charged = 1 GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

        //         $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        //         $result->execute();
        //         return $result->fetchAll(\PDO::FETCH_OBJ);
        //     }
        //     elseif($cash_charge == 'cash'){
        //         //corporate cash
        //         // return DB::table('cashier_patientbills_records')
        //         // ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        //         // ->leftJoin('management_accredited_company_hmo', 'management_accredited_company_hmo.mach_id', '=', 'cashier_patientbills_records.hmo_used')
        //         // ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
        //         // ->select('cashier_patientbills_records.*', 'patients.firstname', 'patients.lastname', 'patients.company', 'management_accredited_companies.company as company_complete_name', 'management_accredited_company_hmo.hmo as hmo_complete_name')
        //         // ->where('cashier_patientbills_records.management_id', $data['management_id'])
        //         // ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
        //         // ->whereDate('cashier_patientbills_records.created_at', '>=' , $date_from)
        //         // ->whereDate('cashier_patientbills_records.created_at', '<=' , $date_to)
        //         // ->whereNotNull('cashier_patientbills_records.hmo_used')
        //         // ->where('cashier_patientbills_records.charge_type', 'direct')
        //         // ->where('cashier_patientbills_records.is_charged', 1)
        //         // ->where('cashier_patientbills_records.is_charged_paid', 1)
        //         // ->groupBy('cashier_patientbills_records.receipt_number')
        //         // ->orderBy('cashier_patientbills_records.created_at', 'DESC')
        //         // ->get();

        //         $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.company as CompanyID,
        //             (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
        //             (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = CompanyID LIMIT 1) company_complete_name,
        //             (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

        //             (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,
        //             (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

        //         FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id AND cashier_patientbills_records.created_at BETWEEN '$date_from' and '$date_to' AND cashier_patientbills_records.charge_type = 'direct' AND cashier_patientbills_records.is_charged = 1 AND cashier_patientbills_records.is_charged_paid = 1 AND cashier_patientbills_records.hmo_used IS NOT NULL GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

        //         $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        //         $result->execute();
        //         return $result->fetchAll(\PDO::FETCH_OBJ);
        //     }
        //     else{
        //         //corporate charge
        //         // return DB::table('cashier_patientbills_records')
        //         // ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        //         // ->leftJoin('management_accredited_company_hmo', 'management_accredited_company_hmo.mach_id', '=', 'cashier_patientbills_records.hmo_used')
        //         // ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
        //         // ->select('cashier_patientbills_records.*', 'patients.firstname', 'patients.lastname', 'patients.company', 'management_accredited_companies.company as company_complete_name', 'management_accredited_company_hmo.hmo as hmo_complete_name')
        //         // ->where('cashier_patientbills_records.management_id', $data['management_id'])
        //         // ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
        //         // ->whereDate('cashier_patientbills_records.created_at', '>=' , $date_from)
        //         // ->whereDate('cashier_patientbills_records.created_at', '<=' , $date_to)
        //         // ->whereNotNull('cashier_patientbills_records.hmo_used')
        //         // ->where('cashier_patientbills_records.is_charged', 1)
        //         // ->where('cashier_patientbills_records.is_charged_paid', 0)
        //         // ->groupBy('cashier_patientbills_records.receipt_number')
        //         // ->orderBy('cashier_patientbills_records.created_at', 'DESC')
        //         // ->get();

        //         $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.company as CompanyID,
        //             (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
        //             (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = CompanyID LIMIT 1) company_complete_name,
        //             (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

        //             (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,
        //             (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

        //         FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id AND cashier_patientbills_records.created_at BETWEEN '$date_from' and '$date_to' AND cashier_patientbills_records.is_charged = 1 AND cashier_patientbills_records.is_charged_paid = 0 AND cashier_patientbills_records.hmo_used IS NOT NULL GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

        //         $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        //         $result->execute();
        //         return $result->fetchAll(\PDO::FETCH_OBJ);
        //     }
        // }else{
        //     if($cash_charge == 'cash'){
        //         //walk in all
        //         // return DB::table('cashier_patientbills_records')
        //         // ->join('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        //         // ->leftJoin('management_accredited_company_hmo', 'management_accredited_company_hmo.mach_id', '=', 'cashier_patientbills_records.hmo_used')
        //         // ->leftJoin('management_accredited_companies', 'management_accredited_companies.company_id', '=', 'patients.company')
        //         // ->select('cashier_patientbills_records.*', 'patients.firstname', 'patients.lastname', 'patients.company', 'management_accredited_companies.company as company_complete_name', 'management_accredited_company_hmo.hmo as hmo_complete_name')
        //         // ->where('cashier_patientbills_records.management_id', $data['management_id'])
        //         // ->where('cashier_patientbills_records.main_mgmt_id', $data['main_mgmt_id'])
        //         // ->whereDate('cashier_patientbills_records.created_at', '>=' , $date_from)
        //         // ->whereDate('cashier_patientbills_records.created_at', '<=' , $date_to)
        //         // ->whereNull('cashier_patientbills_records.hmo_used')
        //         // ->where('cashier_patientbills_records.charge_type', 'direct')
        //         // ->where('cashier_patientbills_records.is_charged', 0)
        //         // ->where('cashier_patientbills_records.is_charged_paid', 1)
        //         // ->groupBy('cashier_patientbills_records.receipt_number')
        //         // ->orderBy('cashier_patientbills_records.created_at', 'DESC')
        //         // ->get();

        //         $query = " SELECT cashier_patientbills_records.*, IFNULL(sum(bill_amount), 0) as totalpayment, cashier_patientbills_records.receipt_number as rcptNo, patients.firstname as fname, patients.lastname as lname, patients.company as CompanyID,

        //             (SELECT hmo FROM management_accredited_company_hmo WHERE management_accredited_company_hmo.mach_id = cashier_patientbills_records.hmo_used LIMIT 1) hmo_complete_name,
        //             (SELECT company FROM management_accredited_companies WHERE management_accredited_companies.company_id = CompanyID LIMIT 1) company_complete_name,
        //             (SELECT name FROM hmo_list WHERE hmo_list.hl_id = cashier_patientbills_records.hmo_used LIMIT 1) clinic_hmo_complete_name,

        //             (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE cashier_patientbills_records.receipt_number = rcptNo AND cashier_patientbills_records.can_be_discounted = 0) as totalnotdiscount,
        //             (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where cashier_patientbills_records.receipt_number = rcptNo and is_refund = 1) as totalrefund

        //         FROM cashier_patientbills_records, patients WHERE cashier_patientbills_records.management_id = '$management_id' AND patients.patient_id = cashier_patientbills_records.patient_id AND cashier_patientbills_records.created_at BETWEEN '$date_from' and '$date_to' AND cashier_patientbills_records.charge_type = 'direct' AND cashier_patientbills_records.is_charged = 0 AND cashier_patientbills_records.is_charged_paid = 1 AND cashier_patientbills_records.hmo_used IS NOT NULL GROUP BY cashier_patientbills_records.receipt_number ORDER BY cashier_patientbills_records.created_at DESC";

        //         $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        //         $result->execute();
        //         return $result->fetchAll(\PDO::FETCH_OBJ);
        //     }
        // }
    }

    public static function deleteOrder($data)
    {
        return DB::table('packages_order_list_temp')
            ->where('id', $data['id'])
            ->delete();
    }

    // new route on bmcdc opening 9-18-2021
    public static function getUnsavePEOrder($data)
    {
        $patientid = $data['patient_id'];
        $dpt = $data['department'];

        $query = "SELECT * from laboratory_unsaveorder where patient_id = '$patientid' and department = '$dpt'";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
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
                'laborotary_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
            ->where('laborotary_id', _Cashier::getLaboratoryIdByMgt($data)->laboratory_id)
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
                'management_id' => _Cashier::getLaboratoryIdByMgt($data)->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
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
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                            'doctor_id' => $data['doctors_id'],
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
            'message' => "New Pe order added by cashier.",
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
            ->where('laborotary_id', _Cashier::getLaboratoryIdByMgt($data)->laboratory_id)
            ->delete();
    }

    public static function getDoctorsList($data)
    {
        return DB::table('doctors')->where('management_id', $data['management_id'])->get();
    }

    public static function handleNewDoctorsServiceOrder($data)
    {

        date_default_timezone_set('Asia/Manila');
        return DB::table('cashier_patientbills_unpaid')->insert(
            [
                'cpb_id' => 'epb-' . rand(0, 9999) . time(),
                'trace_number' => 'trace-' . rand(0, 9999) . '-' . time(),
                'doctors_id' => $data['doctor_id'],
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $data['services'],
                'bill_amount' => $data['service_rate'],
                'bill_department' => 'doctor-services',
                'bill_from' => 'doctor-service',
                'order_id' => $data['doctor_services_id'],
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public static function getUnpaidDoctorServiceOrder($data)
    {
        return DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('bill_department', 'doctor-services')
            ->get();
    }

    public static function casherGetAllLocalDoctors($data)
    {
        return DB::table('doctors')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function casherGetDoctorDetailsById($data)
    {
        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_to']));
        $strDaily = date('Y-m-d H:i:s', strtotime($date_from));
        $lstDaily = date('Y-m-d H:i:s', strtotime($date_to));

        if ($data['date'] === 'all') {
            if ($data['charge_type'] === 'all') {
                return DB::table('cashier_patientbills_records')
                    ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
                    ->leftJoin('doctors', 'doctors.doctors_id', '=', 'cashier_patientbills_records.doctors_id')
                    ->select('cashier_patientbills_records.*', 'patients.firstname as pfirstname', 'patients.lastname as plastname', 'doctors.share_rate')
                    ->where('cashier_patientbills_records.management_id', $data['management_id'])
                    ->where('cashier_patientbills_records.doctors_id', $data['doctors_id'])
                    ->where('cashier_patientbills_records.bill_from', '<>', 'psychology')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'laboratory')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'imaging')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'packages')
                    ->get();
            } else {
                return DB::table('cashier_patientbills_records')
                    ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
                    ->leftJoin('doctors', 'doctors.doctors_id', '=', 'cashier_patientbills_records.doctors_id')
                    ->select('cashier_patientbills_records.*', 'patients.firstname as pfirstname', 'patients.lastname as plastname', 'doctors.share_rate')
                    ->where('cashier_patientbills_records.management_id', $data['management_id'])
                    ->where('cashier_patientbills_records.doctors_id', $data['doctors_id'])
                    ->where('cashier_patientbills_records.charge_type', $data['charge_type'])
                    ->where('cashier_patientbills_records.bill_from', '<>', 'psychology')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'laboratory')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'imaging')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'packages')
                    ->get();
            }

        } else {
            if ($data['charge_type'] === 'all') {
                return DB::table('cashier_patientbills_records')
                    ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
                    ->leftJoin('doctors', 'doctors.doctors_id', '=', 'cashier_patientbills_records.doctors_id')
                    ->select('cashier_patientbills_records.*', 'patients.firstname as pfirstname', 'patients.lastname as plastname', 'doctors.share_rate')
                    ->where('cashier_patientbills_records.management_id', $data['management_id'])
                    ->where('cashier_patientbills_records.doctors_id', $data['doctors_id'])
                    ->where('cashier_patientbills_records.created_at', '>=', $strDaily)
                    ->where('cashier_patientbills_records.created_at', '<=', $lstDaily)
                    ->where('cashier_patientbills_records.bill_from', '<>', 'psychology')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'laboratory')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'imaging')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'packages')
                    ->get();
            } else {
                return DB::table('cashier_patientbills_records')
                    ->leftJoin('patients', 'patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
                    ->leftJoin('doctors', 'doctors.doctors_id', '=', 'cashier_patientbills_records.doctors_id')
                    ->select('cashier_patientbills_records.*', 'patients.firstname as pfirstname', 'patients.lastname as plastname', 'doctors.share_rate')
                    ->where('cashier_patientbills_records.management_id', $data['management_id'])
                    ->where('cashier_patientbills_records.doctors_id', $data['doctors_id'])
                    ->where('cashier_patientbills_records.charge_type', $data['charge_type'])
                    ->where('cashier_patientbills_records.created_at', '>=', $strDaily)
                    ->where('cashier_patientbills_records.created_at', '<=', $lstDaily)
                    ->where('cashier_patientbills_records.bill_from', '<>', 'psychology')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'laboratory')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'imaging')
                    ->where('cashier_patientbills_records.bill_from', '<>', 'packages')
                    ->get();
            }

        }
    }

    //new

    public static function getOtherTestList($data)
    {
        return DB::table('other_order_test')
            ->select('other_order_test.*', 'other_order_test.order_name as label', 'other_order_test.order_id as value')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function getOtherTestListUnpaid($data)
    {
        return DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('bill_department', $data['department'])
            ->get();
    }

    public static function saveOtherTestToUnpaid($data)
    {
        date_default_timezone_set('Asia/Manila');
        // $trace_number = 'trace-' . rand(0, 9999) . '-' . time();
        $trace_number = $data['trace_number'];

        if ($data['order_name'] == 'Physical Examination') {
            $checkorderIdinMed = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_medical_exam')
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->get();

            if (count($checkorderIdinMed) < 1) {
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_medical_exam')
                    ->insert([
                        'lme_id' => 'le-' . rand(0, 9999) . time(),
                        'order_id' => $data['order_id'],
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }
        return DB::table('cashier_patientbills_unpaid')->insert(
            [
                'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
                'trace_number' => $trace_number,
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $data['order_name'],
                'bill_amount' => $data['order_amount'],
                'bill_department' => $data['department'],
                'bill_from' => 'Other Test',
                'order_id' => $data['order_id'],
                'can_be_discounted' => 1,
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public static function removeUnpaidOrderTest($data)
    {
        return DB::table('cashier_patientbills_unpaid')
            ->where('cpb_id', $data['cpb_id'])
            ->delete();
    }

    public static function getDoctorServiceList($data)
    {
        return DB::table('doctors_appointment_services')
            ->select('doctors_appointment_services.*', 'doctors_appointment_services.services as label', 'doctors_appointment_services.service_id as value')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function saveDoctorServiceToUnpaid($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('cashier_patientbills_unpaid')->insert(
            [
                'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
                'trace_number' => $data['trace_number'],
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                'bill_name' => $data['services'],
                'bill_amount' => $data['amount'],
                'bill_department' => $data['department'],
                'bill_from' => 'doctor',
                'order_id' => $data['order_id'],
                'remarks' => $data['remarks'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public static function getAllHMOListNotBaseInCompany($data)
    {
        return DB::table('hmo_list')
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function createNewHMO($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('hmo_list')->insert([
            'hl_id' => 'hl-' . rand(0, 9999) . time(),
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'name' => $data['name'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateExistingHMOInfo($data)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('hmo_list')
            ->where('hl_id', $data['id'])
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->update([
                'name' => $data['name'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllDoctorGeneratedRecord($data)
    {
        return DB::table('doctor_salary_record')
            ->join('doctors', 'doctors.doctors_id', '=', 'doctor_salary_record.doctor_id')
            ->select('doctor_salary_record.*', 'doctors.name as doctor_name')
            ->where('doctor_salary_record.doctor_id', $data['doctors_id'])
            ->where('doctor_salary_record.management_id', $data['management_id'])
            ->where('doctor_salary_record.main_mgmt_id', $data['main_mgmt_id'])
            ->get();
    }

    public static function cashierCreateSalaryRecord($data)
    {

        date_default_timezone_set('Asia/Manila');
        $from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $to = date('Y-m-d 23:59:59', strtotime($data['date_to']));
        $strDaily = date('Y-m-d H:i:s', strtotime($from));
        $lstDaily = date('Y-m-d H:i:s', strtotime($to));
        $date_from = date('Y-m-d', strtotime($data['date_from']));
        $date_to = date('Y-m-d', strtotime($data['date_to']));

        $request = DB::table('cashier_patientbills_records')
            ->where('doctors_id', $data['doctors_id'])
            ->where('management_id', $data['management_id'])
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('cashier_patientbills_records.created_at', '>=', $strDaily)
            ->where('cashier_patientbills_records.created_at', '<=', $lstDaily)
            ->update([
                'is_report_generate' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($request) {
            return DB::table('doctor_salary_record')->insert([
                'dsr_id' => 'dsr-' . rand(0, 9999) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'doctor_id' => $data['doctors_id'],
                'type' => $data['type'],
                'doctor_share' => $data['doctor_share'],
                'company_share' => $data['company_share'],
                'original_amount' => $data['original_amount'],
                'date_from' => $date_from,
                'date_to' => $date_to,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function getLaboratoryList($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_items_laborder')
            ->select('laboratory_items_laborder.*', 'laboratory_items_laborder.laborder as label', 'laboratory_items_laborder.laborder as value')
            ->where('laboratory_id', _Cashier::getLaboratoryIdByMgt($data)->laboratory_id)
            ->groupBy('order_id')
            ->get();
    }

    public static function getAllOrdersByTraceNumberToEdit($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_records')
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function deletePatientTestById($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_records')
            ->where('trace_number', $data['trace_number'])
            ->where('cpr_id', $data['cpr_id'])
            ->delete();
    }

    public static function getCompositionPackage($data)
    {
        return DB::table('packages_charge')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function updateToSendOutPatientTestById($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_records')
            ->where('trace_number', $data['trace_number'])
            ->where('cpr_id', $data['cpr_id'])
            ->update([
                'is_send_out' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getFilterByDateClinicSOA($data)
    {
        date_default_timezone_set('Asia/Manila');
        $from = date('Y-m-d 00:00:00', strtotime($data['date_from']));
        $to = date('Y-m-d 23:59:59', strtotime($data['date_to']));
        $strDaily = date('Y-m-d H:i:s', strtotime($from));
        $lstDaily = date('Y-m-d H:i:s', strtotime($to));

        if ($data['cash_charge'] == 'all') {
            return DB::table('cashier_patientbills_records')
                ->where('cashier_patientbills_records.management_id', $data['management_id'])
                ->where('cashier_patientbills_records.created_at', '>=', $strDaily)
                ->where('cashier_patientbills_records.created_at', '<=', $lstDaily)
                ->get();
        }
        if ($data['cash_charge'] == 'cash') {
            return DB::table('cashier_patientbills_records')
                ->where('cashier_patientbills_records.management_id', $data['management_id'])
                ->where('cashier_patientbills_records.is_charged', 0)
                ->where('cashier_patientbills_records.created_at', '>=', $strDaily)
                ->where('cashier_patientbills_records.created_at', '<=', $lstDaily)
                ->get();
        }
        if ($data['cash_charge'] == 'charge') {
            return DB::table('cashier_patientbills_records')
                ->where('cashier_patientbills_records.management_id', $data['management_id'])
                ->where('cashier_patientbills_records.is_charged', '<>', 0)
                ->where('cashier_patientbills_records.created_at', '>=', $strDaily)
                ->where('cashier_patientbills_records.created_at', '<=', $lstDaily)
                ->get();
        }
    }

    public static function updateToBillOutPatientTestById($data)
    {
        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_records')
            ->where('trace_number', $data['trace_number'])
            ->where('cpr_id', $data['cpr_id'])
            ->first();

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_records')
            ->where('trace_number', $data['trace_number'])
            ->where('cpr_id', $data['cpr_id'])
            ->update([
                'is_bill_out' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_statement_of_account_temp')
            ->insert([
                'csat_id' => 'csat-' . rand(0, 9) . time(),
                'cpr_id' => $query->cpr_id,
                'trace_number' => $query->trace_number,
                'management_id' => $query->management_id,
                'main_mgmt_id' => $query->main_mgmt_id,
                'doctors_id' => $query->doctors_id,
                'patient_id' => $query->patient_id,
                'hmo_used' => $query->hmo_used,
                'hmo_category' => $query->hmo_category,
                'charge_type' => $query->charge_type,
                'bill_name' => $query->bill_name,
                'bill_amount' => $query->bill_amount,
                'bill_from' => $query->bill_from,
                'bill_payment' => $query->bill_payment,
                'bill_department' => $query->bill_department,
                'bill_total' => $query->bill_total,
                'transaction_category' => $query->transaction_category,
                'home_service' => $query->home_service,
                'discount' => $query->discount,
                'discount_reason' => $query->discount_reason,
                'note' => $query->note,
                'process_by' => $query->process_by,
                'receipt_number' => $query->receipt_number,
                'order_id' => $query->order_id,
                'request_physician' => $query->request_physician,
                'is_charged_paid' => $query->is_charged_paid,
                'is_charged' => $query->is_charged,
                'is_refund' => $query->is_refund,
                'is_refund_reason' => $query->is_refund_reason,
                'is_refund_date' => $query->is_refund_date,
                'is_refund_by' => $query->is_refund_by,
                'is_send_out' => $query->is_send_out,
                'is_bill_out' => $query->is_bill_out,
                'is_report_generate' => $query->is_report_generate,
                'can_be_discounted' => $query->can_be_discounted,
                'order_from' => $query->order_from,
                // 'bill_out_branch' => $data['bill_out_branch'],
                'bill_date' => date('Y-m-d H:i:s'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getAllSOATempList($data)
    {
        $management_id = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];

        $query = " SELECT *,

            (SELECT firstname FROM patients WHERE patients.patient_id = cashier_statement_of_account_temp.patient_id LIMIT 1) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = cashier_statement_of_account_temp.patient_id LIMIT 1) as lname,
            (SELECT street FROM patients WHERE patients.patient_id = cashier_statement_of_account_temp.patient_id LIMIT 1) as street,
            (SELECT barangay FROM patients WHERE patients.patient_id = cashier_statement_of_account_temp.patient_id LIMIT 1) as barangay,
            (SELECT city FROM patients WHERE patients.patient_id = cashier_statement_of_account_temp.patient_id LIMIT 1) as city,
            (SELECT IFNULL(sum(rate), 0) FROM laboratory_items_laborder WHERE laboratory_items_laborder.order_id = cashier_statement_of_account_temp.order_id AND laboratory_items_laborder.can_be_discounted = 0 ) as totalnotdiscounted

        FROM cashier_statement_of_account_temp WHERE bill_out_branch = '$management_id' AND main_mgmt_id = '$main_mgmt_id' ";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function removeSOATempList($data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_records')
            ->where('trace_number', $data['trace_number'])
            ->where('cpr_id', $data['cpr_id'])
            ->update([
                'is_bill_out' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_statement_of_account_temp')
            ->where('csat_id', $data['csat_id'])
            ->delete();
    }

    public static function addAllToSOAList($data)
    {
        $soa_id = rand(0, 99) . time();
        $soa = [];

        $query = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_statement_of_account_temp')
            ->where('management_id', $data['management_id'])
            ->get();

        foreach ($query as $v) {
            $soa[] = array(
                'csa_id' => 'csa-' . rand(0, 9) . time(),
                'soa_id' => $soa_id,
                'cpr_id' => $v->cpr_id,
                'trace_number' => $v->trace_number,
                'management_id' => $v->management_id,
                'main_mgmt_id' => $v->main_mgmt_id,
                'doctors_id' => $v->doctors_id,
                'patient_id' => $v->patient_id,
                'hmo_used' => $v->hmo_used,
                'hmo_category' => $v->hmo_category,
                'charge_type' => $v->charge_type,
                'bill_name' => $v->bill_name,
                'bill_amount' => $v->bill_amount,
                'bill_from' => $v->bill_from,
                'bill_payment' => $v->bill_payment,
                'bill_department' => $v->bill_department,
                'bill_total' => $v->bill_total,
                'transaction_category' => $v->transaction_category,
                'home_service' => $v->home_service,
                'discount' => $v->discount,
                'discount_reason' => $v->discount_reason,
                'note' => $v->note,
                'process_by' => $v->process_by,
                'receipt_number' => $v->receipt_number,
                'order_id' => $v->order_id,
                'request_physician' => $v->request_physician,
                'is_charged_paid' => $v->is_charged_paid,
                'is_charged' => $v->is_charged,
                'is_refund' => $v->is_refund,
                'is_refund_reason' => $v->is_refund_reason,
                'is_refund_date' => $v->is_refund_date,
                'is_refund_by' => $v->is_refund_by,
                'is_send_out' => $v->is_send_out,
                'is_bill_out' => $v->is_bill_out,
                'is_report_generate' => $v->is_report_generate,
                'can_be_discounted' => $v->can_be_discounted,
                'order_from' => $v->order_from,
                'bill_out_branch' => $data['bill_out_branch'],
                'bill_date' => $v->bill_date,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_statement_of_account')
            ->insert($soa);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_statement_of_account_temp')
            ->where('management_id', $data['management_id'])
            ->delete();

    }

    public static function getAllSOAList($data)
    {
        $management_id = $data['management_id'];
        $bill_out_branch = $data['bill_out_branch'];
        $query = " SELECT *,

            (SELECT firstname FROM patients WHERE patients.patient_id = cashier_statement_of_account.patient_id LIMIT 1) as fname,
            (SELECT lastname FROM patients WHERE patients.patient_id = cashier_statement_of_account.patient_id LIMIT 1) as lname,
            (SELECT street FROM patients WHERE patients.patient_id = cashier_statement_of_account.patient_id LIMIT 1) as street,
            (SELECT barangay FROM patients WHERE patients.patient_id = cashier_statement_of_account.patient_id LIMIT 1) as barangay,
            (SELECT city FROM patients WHERE patients.patient_id = cashier_statement_of_account.patient_id LIMIT 1) as city,
            (SELECT IFNULL(sum(rate), 0) FROM laboratory_items_laborder WHERE laboratory_items_laborder.order_id = cashier_statement_of_account.order_id AND laboratory_items_laborder.can_be_discounted = 0 ) as totalnotdiscounted

        FROM cashier_statement_of_account WHERE bill_out_branch = '$bill_out_branch' AND management_id = '$management_id' GROUP BY soa_id ORDER BY created_at DESC";

        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getSOADetailsById($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_statement_of_account')
            ->join("patients", "patients.patient_id", "=", "cashier_statement_of_account.patient_id")
            ->leftJoin("management", "management.management_id", "=", "cashier_statement_of_account.bill_out_branch")
            ->select("cashier_statement_of_account.*", "patients.lastname as lname", "patients.firstname as fname", "management.name as branch_name")
            ->where('cashier_statement_of_account.management_id', $data['management_id'])
            ->where('cashier_statement_of_account.soa_id', $data['soa_id'])
            ->get();
    }

    public static function getAllSalesByFilterDate($data)
    {
        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_start']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_start']));

        $query = " SELECT *, patient_id as patientID,

            (SELECT COUNT(company) FROM patients WHERE patients.patient_id = patientID AND company IS NOT NULL) as patientCompany,


            (SELECT IFNULL(sum(bill_amount + bill_amount * IFNULL( home_service, 0) - bill_amount * IFNULL( discount, 0)), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged != 0) as totalCharge,


            (SELECT IFNULL(sum(bill_amount + bill_amount * IFNULL( home_service, 0) - bill_amount * IFNULL( discount, 0)), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged = 0 AND can_be_discounted = 1) as totalCashDiscounted,

            (SELECT IFNULL(sum(bill_amount + bill_amount * IFNULL( home_service, 0)), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged = 0 AND can_be_discounted <> 1) as totalCashNotDiscounted,

            (SELECT sum(totalCashDiscounted + totalCashNotDiscounted)) as totalCash,


            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged != 0 AND hmo_category = 'company') as totalChargeCorporate,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged != 0 AND hmo_category != 'company' AND hmo_category IS NOT NULL) as totalChargeHMO,


            (SELECT IFNULL(sum(bill_amount - bill_amount * IFNULL( discount, 0)), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged = 0 AND home_service IS NULL AND can_be_discounted = 1 HAVING patientCompany < 1) as totalCashWalkin1,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged = 0 AND home_service IS NULL AND can_be_discounted <> 1 HAVING patientCompany < 1) as totalCashWalkin2,
            (SELECT sum(totalCashWalkin1 + totalCashWalkin2)) as totalCashWalkin,

            (SELECT IFNULL(sum(bill_amount - bill_amount * IFNULL( discount, 0)), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged = 0 AND home_service IS NULL AND can_be_discounted = 1 HAVING patientCompany > 0) as totalCashCorporate1,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged = 0 AND home_service IS NULL AND can_be_discounted <> 1 HAVING patientCompany > 0) as totalCashCorporate2,

            (SELECT sum(totalCashCorporate1 + totalCashCorporate2)) as totalCashCorporate,


            (SELECT IFNULL(sum(bill_amount + bill_amount * IFNULL( home_service, 0)), 0) FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to' AND is_charged = 0 AND home_service IS NOT NULL ) as totalCashHomeService


        FROM cashier_patientbills_records WHERE created_at BETWEEN '$date_from' and '$date_to'";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getAllSalesExpenseByFilterDate($data)
    {
        $date_from = date('Y-m-d 00:00:00', strtotime($data['date_start']));
        $date_to = date('Y-m-d 23:59:59', strtotime($data['date_start']));
        $management_id = $data['management_id'];
        $main_mgmt_id = $data['main_mgmt_id'];

        $query = " SELECT * FROM cashier_sales_expenses WHERE date_start BETWEEN '$date_from' and '$date_to' AND management_id = '$management_id' AND main_mgmt_id = '$main_mgmt_id' ";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function createSalesExpensesByDate($data)
    {
        $date_start = date('Y-m-d', strtotime($data['date_start']));
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_sales_expenses')
            ->insert([
                'cse_id' => 'cse-' . rand(0, 9) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'date_start' => $date_start,
                'type' => $data['type'],
                'name_desc' => $data['name_desc'],
                'amount' => $data['amount'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function archivePatientTransactionByID($data)
    {
        $date_start = date('Y-m-d', strtotime($data['date_start']));

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_sales_expenses')
            ->insert([
                'cse_id' => 'cse-' . rand(0, 9) . time(),
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'date_start' => $date_start,
                'type' => $data['type'],
                'name_desc' => $data['name_desc'],
                'amount' => $data['amount'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patient_queue')
            ->where("trace_number", $data['trace_number'])
            ->where("patient_id", $data['patient_id'])
            ->delete();

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('cashier_patientbills_records')
            ->where("trace_number", $data['trace_number'])
            ->where("management_id", $data['management_id'])
            ->where("main_mgmt_id", $data['main_mgmt_id'])
            ->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function deletePatientQueueById($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('patient_queue')
            ->where("patient_id", $data['patient_id'])
            ->delete();
    }

    public static function getBillingRecordsNotGroup($data)
    {
        $management_id = $data['management_id'];
        $query = " SELECT *, receipt_number as rcptNo, management_id as mgmtID,

            (SELECT user_fullname FROM cashier WHERE cashier.management_id = mgmtID LIMIT 1) as cashierName,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE receipt_number = rcptNo) as totalpayment,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_patientbills_records WHERE receipt_number = rcptNo AND can_be_discounted = 0) as totalnotdiscount,
            (SELECT IFNULL(sum(bill_amount), 0) FROM cashier_statement_of_account_temp ) as totalSoaTemp,
            (SELECT IFNULL(sum(bill_amount), 0) from cashier_patientbills_records where receipt_number = rcptNo and is_refund = 1) as totalrefund

        FROM cashier_patientbills_records WHERE management_id = '$management_id' ORDER BY created_at DESC";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    /*** add billling to admitted patient **/
    public static function addBillToAdmittedPatients($data)
    {

        date_default_timezone_set('Asia/Manila');
        $imaging_id = _Cashier::getImagingIdByMgtId($data['management_id'])->imaging_id;

        $query = DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->get();

        $values = [];
        $records = [];

        foreach ($query as $item) {
            $values[] = array(
                "dbr_id" => "dbr-" . rand(99999, 99999999) . time(),
                "order_id" => $item->order_id,
                "patient_id" => $item->patient_id,
                "trace_number" => $item->trace_number,
                "management_id" => $item->management_id,
                "main_mgmt_id" => $item->main_mgmt_id,
                "bill_name" => $item->bill_name,
                "bill_amount" => $item->bill_amount,
                "bill_from" => $item->bill_from,
                "bill_payment" => "billing",
                "bill_department" => $item->bill_department,
                "transaction_category" => "admitted-patient",
                "process_by" => $data["user_id"],
                "request_physician" => $data['selectedDoctor'],
                "billing_status" => "billing-unpaid",
                "status" => 1,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            );

            $records[] = array(
                'cpr_id' => "cpr-" . rand(99999, 99999999) . time(),
                'trace_number' => $item->trace_number,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'doctors_id' => !empty($data['doctor']) ? $data['doctor'] : $item->doctors_id,
                'patient_id' => $item->patient_id,
                'charge_type' => 'admitted-billing',
                'bill_name' => $item->bill_name,
                'bill_amount' => $item->bill_amount,
                'bill_from' => $item->bill_from,
                'bill_payment' => 0,
                'bill_department' => $item->bill_department,
                'bill_total' => $data['amountto_pay'],
                'transaction_category' => 'billing-unpaid',
                'process_by' => $data['user_id'],
                'order_id' => $item->order_id,
                'request_physician' => $data['selectedDoctor'],
                'is_charged_paid' => 0,
                'is_charged' => 0,
                'is_report_generate' => 0,
                'order_from' => 'hospital',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        }

        DB::table('hospital_admitted_patient_billing_record')->insert($values);
        DB::table('cashier_patientbills_records')->insert($records);

        $orderToLab = [];
        $hemoglobin = [];
        $imagingcenter = [];

        foreach ($query as $v) {
            $cpr_id = rand(0, 9999) . '-' . time();

            if ($v->bill_from == "packages") {
                _LaboratoryOrder::newPackagesOrder($v, $data);
                DB::table('packages_order_list')->insert([
                    'pol_id' => 'pol-' . rand() . '-' . time(),
                    'order_id' => $v->order_id,
                    'trace_number' => $v->trace_number,
                    'package_id' => $v->laboratory_id,
                    'management_id' => $v->management_id,
                    'patient_id' => $v->patient_id,
                    'package_name' => $v->bill_name,
                    'package_amount' => $v->bill_amount,
                    'status' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if ($v->bill_from == 'appointment') {
                DB::table('appointment_list')
                    ->where('appointment_id', $v->trace_number)
                    ->update([
                        'is_paid_bysecretary' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            if ($v->bill_department == 'medical-exam') {
                _Cashier::laboratoryCountQueue($v, $data);
                _LaboratoryOrder::newMedicalExamOrder($v, $data);
            }

            if ($v->bill_department == 'doctor-services') {
                $patient_userid = DB::table('patients')->select('user_id')->where('patient_id', $v->patient_id)->first();
                $doctor_userid = DB::table('doctors')->select('user_id')->where('doctors_id', $v->doctors_id)->first();

                $checkPermission = DB::table('patients_permission')
                    ->where('patients_id', $v->patient_id)
                    ->where('doctors_id', $v->doctors_id)
                    ->where('permission_status', 'approved')
                    ->get();

                if (count($checkPermission) < 1) {
                    DB::table('patients_permission')
                        ->insert([
                            'permission_id' => 'permission-' . rand(0, 9999),
                            'patients_id' => $patient_userid->user_id,
                            'doctors_id' => $doctor_userid->user_id,
                            'permission_on' => 'PROFILE',
                            'status' => 1,
                            'permission_status' => 'approved',
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                }

                DB::table('doctors_medical_certificate_ordered')->insert([
                    'lmc_id' => 'lmc-' . rand(0, 9999) . time(),
                    'patient_id' => $v->patient_id,
                    'doctors_id' => $v->doctors_id,
                    'management_id' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'service_id' => $v->order_id,
                    'service_name' => $v->bill_name,
                    'service_rate' => $v->bill_amount,
                    'order_status' => 'new-order-paid',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            if ($v->bill_department == 'Other Test') {
                $patient_userid = DB::table('patients')->select('user_id')->where('patient_id', $v->patient_id)->first();
                $doctor_userid = DB::table('doctors')->select('user_id')->where('doctors_id', $data['doctor'])->first();

                if ($v->bill_name == 'Physical Examination') {
                    DB::table('laboratory_medical_exam')
                        ->where('order_id', $v->order_id)
                        ->where('patient_id', $v->patient_id)
                        ->where('trace_number', $v->trace_number)
                        ->update([
                            'doctor_id' => $data['doctor'],
                            'medical_exam' => 1,
                            'order_status' => 'new-order-paid',
                        ]);
                }

                if ($v->bill_name == 'Medical Certificate') {

                    $checkPermission = DB::table('patients_permission')
                        ->where('patients_id', $v->patient_id)
                        ->where('doctors_id', $data['doctor'])
                        ->where('permission_status', 'approved')
                        ->get();

                    if (count($checkPermission) < 1) {
                        DB::table('patients_permission')
                            ->insert([
                                'permission_id' => 'permission-' . rand(0, 9999),
                                'patients_id' => $patient_userid->user_id,
                                'doctors_id' => $doctor_userid->user_id,
                                'permission_on' => 'PROFILE',
                                'status' => 1,
                                'permission_status' => 'approved',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                    }

                    DB::table('doctors_medical_certificate_ordered')->insert([
                        'lmc_id' => 'lmc-' . rand(0, 9999) . time(),
                        'patient_id' => $v->patient_id,
                        'doctors_id' => $data['doctor'],
                        'management_id' => $data['management_id'],
                        'main_mgmt_id' => $data['main_mgmt_id'],
                        'service_id' => $v->order_id,
                        'service_name' => $v->bill_name,
                        'service_rate' => $v->bill_amount,
                        'order_status' => 'new-order-paid',
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            if ($v->bill_from == 'laboratory') {
                _Cashier::laboratoryCountQueue($v, $data);

                if ($v->bill_department == 'hemathology') {
                    _LaboratoryOrder::newHemathologyOrder($v, $data);
                }
                if ($v->bill_department == 'serology') {
                    _LaboratoryOrder::newSorologyOrder($v, $data);
                }
                if ($v->bill_department == 'clinical-microscopy') {
                    _LaboratoryOrder::newClinicMicroscopyOrder($v, $data);
                }
                // if ($v->bill_department == 'fecal-analysis') {
                //     _LaboratoryOrder::newFecalAnalysisOrder($v, $data);
                // }
                if ($v->bill_department == 'clinical-chemistry') {
                    _LaboratoryOrder::newClinicChemistryOrder($v, $data);
                }
                if ($v->bill_department == 'stool-test') {
                    _LaboratoryOrder::newStoolTestOrder($v, $data);
                }
                if ($v->bill_department == 'papsmear-test') {
                    _LaboratoryOrder::newPapsmearTestOrder($v, $data);
                }
                if ($v->bill_department == 'urinalysis') {
                    _LaboratoryOrder::newUrinalysisOrder($v, $data);
                }
                if ($v->bill_department == 'ecg') {
                    _LaboratoryOrder::newECGOrder($v, $data);
                }
                // if ($v->bill_department == 'medical-exam') {
                //     _LaboratoryOrder::newMedicalExamOrder($v, $data);
                // }
                if ($v->bill_department == 'oral-glucose') {
                    _LaboratoryOrder::newOralGlucoseOrder($v, $data);
                }
                if ($v->bill_department == 'thyroid-profile') {
                    _LaboratoryOrder::newThyroidProfileOrder($v, $data);
                }
                if ($v->bill_department == 'immunology') {
                    _LaboratoryOrder::newImmunologyOrder($v, $data);
                }
                if ($v->bill_department == 'miscellaneous') {
                    _LaboratoryOrder::newMiscellaneousOrder($v, $data);
                }
                if ($v->bill_department == 'hepatitis-profile') {
                    _LaboratoryOrder::newHepatitisProfileOrder($v, $data);
                }
                if ($v->bill_department == 'covid-19') {
                    _LaboratoryOrder::newCovid19TestOrder($v, $data);
                }
                if ($v->bill_department == 'Tumor Maker') {
                    _LaboratoryOrder::newTumorMakerTestOrder($v, $data);
                }
                if ($v->bill_department == 'Drug Test') {
                    _LaboratoryOrder::newDrugTestTestOrder($v, $data);
                }
            }

            if ($v->bill_from == 'imaging') {
                _Cashier::imagingCountQueue($data, $v->trace_number);

                $imagingcenter[] = array(
                    'imaging_center_id' => rand(0, 999) . '-' . time(),
                    'patients_id' => $v->patient_id,
                    'doctors_id' => $v->doctors_id,
                    'trace_number' => $v->trace_number,
                    'imaging_order' => $v->bill_name,
                    'imaging_center' => $imaging_id,
                    'is_viewed' => 1,
                    'manage_by' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'order_from' => 'local',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }

            if ($v->bill_from == 'psychology') {
                _Cashier::psychologyCountQueue($data, $v->trace_number);

                if ($v->bill_name == 'Ishihara') {
                    _LaboratoryOrder::newIshiharaProfileOrder($v, $data);
                }

                if ($v->bill_name == 'Audiometry') {
                    _LaboratoryOrder::newAudiometryProfileOrder($v, $data);
                }

                if ($v->bill_name == 'Neuro Examination') {
                    _LaboratoryOrder::newNeuroProfileOrder($v, $data);
                }
            }
        }

        DB::table('imaging_center')->insert($imagingcenter);

        DB::table('patient_queue')->where('patient_id', $data['patient_id'])->where('type', 'cashier')->delete();

        return DB::table('cashier_patientbills_unpaid')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->delete();
    }

    public function getPatientListForDischarge($data)
    {
        return DB::table('cashier_patients_fordischarge')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function getAdmittedPatientForDischarge($data)
    {
        return DB::table('hospital_admitted_patient_forbillout')
            ->join('patients', 'patients.patient_id', '=', 'hospital_admitted_patient_forbillout.patient_id')
            ->select('hospital_admitted_patient_forbillout.*', 'patients.firstname as firstname', 'patients.lastname as lastname', 'patients.image')
            ->where('hospital_admitted_patient_forbillout.management_id', $data['management_id'])
            ->where('hospital_admitted_patient_forbillout.billout_status', 'for-cashier')
            ->groupBy('hospital_admitted_patient_forbillout.trace_number')
            ->get();
    }

    public static function dischargedPatientFromAdmitting($data)
    {

        /** set patient bill paid **/
        DB::table('hospital_admitted_patient_billing_record')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->update([
                'billing_status' => 'admitted-paid',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        /** update billout patient to discharged **/
        DB::table('hospital_admitted_patient_forbillout')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->update([
                'billout_status' => 'discharged',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        /** set / update as paid ***/
        DB::table('cashier_patientbills_records')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->update([
                'is_charged_paid' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        /** add billing payment records ***/
        DB::table('hospital_admitted_patient_billing_payments_record')->insert([
            'patient_id' => $data['patient_id'],
            'trace_number' => $data['trace_number'],
            'management_id' => $data['management_id'],
            'philhealth_caseno' => $data['philhealth_caseno'],
            'philhealth' => $data['philhealth'],
            'philhealth_amount' => $data['philhealth_amount'],
            'billing_amount' => $data['amount'],
            'payment_amount' => $data['payment'],
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return DB::table('hospital_admitted_patient')
            ->where('management_id', $data['management_id'])
            ->where('patient_id', $data['patient_id'])
            ->where('trace_number', $data['trace_number'])
            ->update([
                'nurse_department' => 'discharged',
                'discharge_remarks' => $data['remarks'],
                'discharge_on' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function dischargedPatientListGroupByPatientId($data)
    {
        return DB::table('hospital_admitted_patient_forbillout')
            ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient_forbillout.patient_id')
            ->select('hospital_admitted_patient_forbillout.*', 'patients.firstname as fname', 'patients.lastname as  lname', 'patients.middle as  mname', 'patients.gender', 'patients.image', 'patients.birthday', 'patients.street as street', 'patients.barangay as barangay', 'patients.city as city')
            ->where('hospital_admitted_patient_forbillout.management_id', $data['management_id'])
            ->where('hospital_admitted_patient_forbillout.billout_status', 'discharged')
            ->groupBy('hospital_admitted_patient_forbillout.patient_id')
            ->get();
    }

    public static function getDischargeSlipToPatient($data)
    {
        return DB::table('hospital_admitted_patient_discharged_slip')
            ->where('trace_number', $data['trace_number'])
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function addDischargeSlipToPatient($data)
    {
        return DB::table('hospital_admitted_patient_discharged_slip')
            ->insert([
                'patient_id' => $data["patient_id"],
                'trace_number' => $data["trace_number"],
                'management_id' => $data["management_id"],
                'date_admitted' => date("Y-m-d H:i:s", strtotime($data["date_admitted"])),
                'admitted_reason' => $data["admitted_reason"],
                'diagnosis' => $data["diagnosis"],
                'treatment' => $data["treatment"],
                'discharged_date' => date("Y-m-d H:i:s", strtotime($data["discharged_date"])),
                'discharge_approved_by_md' => $data["is_md_approved_discharged"],
                'discharge_reason' => $data["discharged_reason"],
                'discharged_md' => $data["discharged_md"],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function getDischargedSlipPatientInfo($data)
    {
        return DB::table('patients')
            ->where('patient_id', $data['patient_id'])
            ->get();
    }

    public static function getPhilhealthRecord($data)
    {
        return DB::table('hospital_admitted_patient_billing_record_philhealth')
            ->leftJoin('patients', 'patients.patient_id', '=', 'hospital_admitted_patient_billing_record_philhealth.patient_id')
            ->select('hospital_admitted_patient_billing_record_philhealth.*', 'patients.firstname as firstname', 'patients.lastname as lastname')
            ->where('hospital_admitted_patient_billing_record_philhealth.management_id', $data['management_id'])
            ->get();
    }
}
