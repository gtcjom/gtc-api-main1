<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;
use App\Models\_Cashier;
use App\Models\_Laboratory;
use App\Models\_Validator;
use App\Models\_LaboratoryOrder;
use App\Models\_LaboratoryOrderPackage;


class _Van extends Model
{
    public static function getInformation($data){
        return DB::table('endorsement_account')
        ->leftJoin('hospital_employee_details', 'hospital_employee_details.user_id', '=', 'endorsement_account.user_id')
        ->select('endorsement_account.ea_id', 'endorsement_account.user_fullname as name', 'endorsement_account.image', 'endorsement_account.user_address as address', 'hospital_employee_details.sick_leave as SLCredit', 'hospital_employee_details.vacation_leave as VLCredit')
        ->where('endorsement_account.user_id', $data['user_id'])
        ->first();
    }

    public static function getLaboratoryIdByMgtId($management_id){
        return DB::table('laboratory_list')->where('management_id', $management_id)->first();
    }

    public static function hisVanGetPersonalInfoById($data)
    {
        $query = "SELECT * FROM endorsement_account WHERE user_id = '" . $data['user_id'] . "' limit 1";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function hisVanEndtUploadProfile($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        return DB::table('endorsement_account')
            ->where('user_id', $data['user_id'])
            ->update([
                'image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function hisVanEndtUpdatePersonalInfo($data)
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

    public static function laboratoryCountQueue($v, $data){
        $laboratoryQueue = DB::table('mobile_van_queue')->where('patient_id', $data['patient_id'])->where('type', 'laboratory')->get();

        if (count($laboratoryQueue) < 1) {
            DB::table('mobile_van_queue')
            ->insert([
                'mv_id' => 'mv-'.rand(0, 99).time(),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'type' => 'laboratory',
                'trace_number' => $v->trace_number,
                'priority_sequence'=> 6,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function imagingCountQueue($v, $data){
        $imagingQueue = DB::table('mobile_van_queue')->where('patient_id', $data['patient_id'])->where('type', 'imaging')->get();

        if (count($imagingQueue) < 1) {
            DB::table('mobile_van_queue')
            ->insert([
                'mv_id' => 'mv-'.rand(0, 99).time(),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'type' => 'imaging',
                'trace_number' => $v->trace_number,
                'priority_sequence'=> 7,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        return true;
    }

    public static function psychologyCountQueue($v, $data){
        $psychologyQueue = DB::table('mobile_van_queue')->where('patient_id', $data['patient_id'])->where('type', 'psychology')->get();

        if (count($psychologyQueue) < 1) {
            DB::table('mobile_van_queue')
            ->insert([
                'mv_id' => 'mv-'.rand(0, 99).time(),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'type' => 'psychology',
                'trace_number' => $v->trace_number,
                'priority_sequence'=> 8,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        return true;
    }

    public static function othersCountQueue($v, $data){
        $otherQueue = DB::table('mobile_van_queue')->where('patient_id', $data['patient_id'])->where('type', 'other')->get();
        if (count($otherQueue) < 1) {
            DB::table('mobile_van_queue')
            ->insert([
                'mv_id' => 'mv-'.rand(0, 99).time(),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'type' => 'other',
                'trace_number' => $v->trace_number,
                'priority_sequence'=> 10,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        return true;
    }

    

    public static function doctorCountQueue($v, $data){
        $doctorQueue = DB::table('mobile_van_queue')->where('patient_id', $data['patient_id'])->where('type', 'doctor')->get();

        if (count($doctorQueue) < 1) {
            DB::table('mobile_van_queue')
            ->insert([
                'mv_id' => 'mv-'.rand(0, 99).time(),
                'patient_id' => $data['patient_id'],
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'doctor_id' => $data['doctor'],
                'type' => 'doctor',
                'trace_number' => $v->trace_number,
                'priority_sequence'=> 3,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function hisVanNewPatient($data, $filename){
        date_default_timezone_set('Asia/Manila');
        $patientid = 'p-' . rand(0, 999) . time();
        $userid = 'u-' . rand(0, 8888) . time();
        
        if (!empty($data['weight'])) {
            DB::connection('mysql')->table('patients_weight_history')->insert([
                'pwh_id' => 'pwh-' . rand(0, 99).time(),
                'patient_id' => $patientid,
                'weight' => $data['weight'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['pulse'])) {
            DB::connection('mysql')->table('patients_pulse_history')->insert([
                'pph_id' => 'pph-' . rand(0, 99).time(),
                'patients_id' => $patientid,
                'pulse' => $data['pulse'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['temp'])) {
            DB::connection('mysql')->table('patients_temp_history')->insert([
                'pth_id' => 'pth-' . rand(0, 99).time(),
                'patients_id' => $patientid,
                'temp' => $data['temp'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['bp_systolic']) && !empty($data['bp_diastolic'])) {
            DB::connection('mysql')->table('patients_lab_history')->insert([
                'plh_id' => 'plh-' . rand(0, 99).time(),
                'patients_id' => $patientid,
                'systolic' => $data['bp_systolic'],
                'diastolic' => $data['bp_diastolic'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

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
            'height' => !empty($data['height']) ? $data['height'] : NULL,
            'weight' => !empty($data['weight']) ? $data['weight'] : NULL,
            'pulse' => !empty($data['pulse']) ? $data['pulse'] : NULL,
            'lmp' => date('Y-m-d', strtotime($data['lmp'])),
            'temperature' => !empty($data['temp']) ? $data['temp'] : NULL,
            'blood_systolic' => !empty($data['bp_systolic']) ? $data['bp_systolic'] : NULL,
            'blood_diastolic' => !empty($data['bp_diastolic']) ? $data['bp_diastolic'] : NULL,
            'blood_type' => !empty($data['blood_type']) ? $data['blood_type'] : NULL,
            'image' => $filename,
            'patient_condition' => !empty($data['patient_condition']) ? $data['patient_condition'] : NULL,
            'join_category' => 'hosp-app',
            'added_by' => $data['user_id'],
            'added_from' => 'mobile-van',
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function vanGetPatientInformation($data){
        return DB::connection('mysql')->table('patients')
        ->where('patient_id', $data['patient_id'])
        ->first();
    }

    public static function vanEditPatientVital($data)
    {
        date_default_timezone_set('Asia/Manila');
        if (!empty($data['weight'])) {
            DB::connection('mysql')->table('patients_weight_history')->insert([
                'pwh_id' => 'pwh-' . rand(0, 99).time(),
                'patient_id' => $data['patient_id'],
                'weight' => $data['weight'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['pulse'])) {
            DB::connection('mysql')->table('patients_pulse_history')->insert([
                'pph_id' => 'pph-' . rand(0, 99).time(),
                'patients_id' => $data['patient_id'],
                'pulse' => $data['pulse'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['temp'])) {
            DB::connection('mysql')->table('patients_temp_history')->insert([
                'pth_id' => 'pth-' . rand(0, 99).time(),
                'patients_id' => $data['patient_id'],
                'temp' => $data['temp'],
                'added_by' => $data['user_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if (!empty($data['bp_systolic']) && !empty($data['bp_diastolic'])) {
            DB::connection('mysql')->table('patients_lab_history')->insert([
                'plh_id' => 'plh-' . rand(0, 99).time(),
                'patients_id' => $data['patient_id'],
                'systolic' => $data['bp_systolic'],
                'diastolic' => $data['bp_diastolic'],
                'added_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])
        ->update([
            'height' => $data['height'],
            'weight' => $data['weight'],
            'pulse' => $data['pulse'],
            'lmp' => !empty($data['lmp']) ? date('Y-m-d', strtotime($data['lmp'])) : NULL,
            'temperature' => $data['temp'],
            'blood_systolic' => $data['bp_systolic'],
            'blood_diastolic' => $data['bp_diastolic'],
            'blood_type' => $data['blood_type'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public static function savePackageOrderTemp($data)
    {
        $existingTraceNo = DB::table('cashier_patientbills_unpaid')->select('trace_number')->where('patient_id', $data['patient_id'])->get();
        $trace_number = '';

        if(count($existingTraceNo) > 0){
            $trace_number = $existingTraceNo[0]->trace_number;
        }else{
            $trace_number = $data['trace_number'];
        }

        $lab_id = _Cashier::getLaboratoryIdByMgt($data)->laboratory_id;
        return DB::table('cashier_patientbills_unpaid')->insert([
            'cpb_id' => 'cpb-'.rand(0, 9999) . time(),
            'trace_number' => $trace_number,
            'doctors_id' => 'order-from-mobile',
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'laboratory_id' => $data['package_id'],
            'bill_name' => $data['billname'],
            'bill_amount' => $data['rate'],
            'bill_department' => 'packages',
            'bill_from' => 'packages',
            'order_id' => $data['order_id'],
            'remarks' => NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function vanBillingSetAsPaid($data){
        date_default_timezone_set('Asia/Manila');

        $imaging_id = _Cashier::getImagingIdByMgtId($data['management_id'])->imaging_id;

        $query = DB::connection('mysql')->table('cashier_patientbills_unpaid')
        ->where('management_id', $data['management_id'])
        ->where('patient_id', $data['patient_id'])
        ->get();

        $records = [];
        $imagingcenter = [];

        foreach ($query as $v) {
            $records[] = array(
                'cpr_id' => rand(0, 9999) . '-' . time(),
                'trace_number' => $v->trace_number,
                'management_id' => $data['management_id'],
                'main_mgmt_id' => $data['main_mgmt_id'],
                'doctors_id' => !empty($data['doctor']) ? $data['doctor'] : $v->doctors_id,
                'patient_id' => $v->patient_id,
                'charge_type' => 'charge',
                'hmo_used' => $data['payment_type'] == 'hmo' ? $data['hmo'] : $data['patient_company'],
                'hmo_category' => $data['payment_type'] == 'hmo' ? 'hmo' : 'company',
                'bill_name' => $v->bill_name,
                'bill_amount' => $v->bill_amount,
                'bill_from' => $v->bill_from,
                'bill_payment' => $data['amountto_pay'],
                'bill_department' => $v->bill_department,
                'bill_total' => $data['amountto_pay'],
                'note' => $data['note'],
                'process_by' => $data['user_id'],
                'receipt_number' => $data['receipt_number'],
                'order_id' => $v->order_id,
                'is_charged_paid' => 0,
                'is_charged' => 1,
                'can_be_discounted' => 0,
                'order_from' => 'mobile-van',
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            
            if ($v->bill_from == "packages") {
                _Van::newPackagesOrder($v, $data);

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
            if ($v->bill_from == 'laboratory') {
                _Van::laboratoryCountQueue($v, $data);

                if ($v->bill_department == 'hemathology') {
                    _LaboratoryOrder::newHemathologyOrder($v, $data);
                }
                if ($v->bill_department == 'serology') {
                    _LaboratoryOrder::newSorologyOrder($v, $data);
                }
                if ($v->bill_department == 'clinical-microscopy') {
                    _LaboratoryOrder::newClinicMicroscopyOrder($v, $data);
                }
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
                _Van::imagingCountQueue($v, $data);

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
                    'order_from' => 'mobile',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }
            if ($v->bill_from == 'psychology') {
                _Van::psychologyCountQueue($v, $data);

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
            if ($v->bill_from == 'Other Test') {
                _Van::othersCountQueue($v, $data);

                if ($v->bill_name == 'SARS-CoV-2 Antigen') {
                    _LaboratoryOrder::newSarsCovOrder($v, $data);
                }

                if ($v->bill_name == 'ECG') {
                    _LaboratoryOrder::newECGNewOrder($v, $data);
                }
            }
        }

        DB::connection('mysql')->table('imaging_center')->insert($imagingcenter);

        DB::connection('mysql')->table('cashier_patientbills_records')->insert($records);

        return DB::connection('mysql')->table('cashier_patientbills_unpaid')
        ->where('management_id', $data['management_id'])
        ->where('patient_id', $data['patient_id'])
        ->delete();
    }

    public static function newPackagesOrder($v, $data)
    {
        $getProductDetails = DB::table('packages_charge')->where('package_id', $v->laboratory_id)->get();
        $getImagingId = DB::table('imaging')->select('imaging_id')->where('management_id', $data['management_id'])->get();
        $getLaboratoryId = DB::table('laboratory_list')->select('laboratory_id')->where('management_id', $data['management_id'])->get();

        foreach ($getProductDetails as $k) {
            //LABORATORY INSIDE PACKAGE
            if ($k->department == 'laboratory') {
            
                _Van::laboratoryCountQueue($v, $data);

                if ($k->category == 'hemathology') {
                    _LaboratoryOrderPackage::newHemathologyOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'serology') {
                    _LaboratoryOrderPackage::newSorologyOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'clinical-microscopy') {
                    _LaboratoryOrderPackage::newClinicMicroscopyOrderPackage($k, $v, $getLaboratoryId);
                }

                //DILI IPA APIL
                if ($k->category == 'stool-test') {
                    _LaboratoryOrderPackage::newFecalAnalysisOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'clinical-chemistry') {
                    _LaboratoryOrderPackage::newClinicChemistryOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'ecg') {
                    _LaboratoryOrderPackage::newECGOrderPackage($k, $v, $getLaboratoryId);
                }

                //DILI IPA APIL
                if ($k->category == 'urinalysis') {
                    _LaboratoryOrderPackage::newUrinalysisOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'medical-exam') {
                    _LaboratoryOrderPackage::newMedicalExamOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'papsmear-test') {
                    _LaboratoryOrderPackage::newPapsmearTestOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'oral-glucose') {
                    _LaboratoryOrderPackage::newOralGlucoseOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'thyroid-profile') {
                    _LaboratoryOrderPackage::newThyroidProfileOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'immunology') {
                    _LaboratoryOrderPackage::newImmunologyOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'miscellaneous') {
                    _LaboratoryOrderPackage::newMiscellaneousOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'hepatitis-profile') {
                    _LaboratoryOrderPackage::newHepatitisProfileOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'covid-19') {
                    _LaboratoryOrderPackage::newCovid19TestOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'Tumor Maker') {
                    _LaboratoryOrderPackage::newTumorMakerTestOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'Drug Test') {
                    _LaboratoryOrderPackage::newDrugTestTestOrderPackage($k, $v, $getLaboratoryId);
                }
            }

            //IMAGING INSIDE PACKAGE
            if ($k->department == 'imaging') {
                _Van::imagingCountQueue($v, $data);

                DB::table('imaging_center')->insert([
                    'imaging_center_id' => 'rand-' . rand(0, 9999) . time(),
                    'patients_id' => $v->patient_id,
                    'doctors_id' => $v->doctors_id,
                    'trace_number' => $v->trace_number,
                    // 'imaging_order' => $v->bill_name,
                    'imaging_order' => $k->order_name,
                    'imaging_center' => count($getImagingId) > 0 ? $getImagingId[0]->imaging_id : null,
                    'is_viewed' => 1,
                    'manage_by' => $data['management_id'],
                    'main_mgmt_id' => $data['main_mgmt_id'],
                    'order_from' => 'local',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            //OTHERS TEST INSIDE PACKAGE INCLUDING DOCTOR LIKE MED CERT, PE
            if ($k->department == 'others') {
                _Van::newOtherTestPackage($k, $v, $getLaboratoryId, $data); 
            }
        }

        return true;
    }

    public static function newOtherTestPackage($k, $v, $getLaboratoryId, $data)
    {
        $patient_userid = DB::table('patients')->select('user_id')->where('patient_id', $v->patient_id)->first();
        $doctor_userid = DB::table('doctors')->select('user_id')->where('doctors_id', $data['doctor'])->first();

        if ($k->order_name == 'Physical Examination') {
            _Van::doctorCountQueue($v, $data);

            DB::table('laboratory_medical_exam')->insert([
                'lme_id' => 'lme-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'doctor_id' => $data['doctor'],
                'medical_exam' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Medical Certificate') {
            _Van::doctorCountQueue($v, $data);

            $checkPermission = DB::table('patients_permission')
            ->where('patients_id', $v->patient_id)
            ->where('doctors_id', $data['doctor'])
            ->where('permission_status', 'approved')
            ->get();

            if (count($checkPermission) < 1) {
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('patients_permission')
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
                'trace_number' => $v->trace_number,
                'service_id' => $k->order_name,
                'service_name' => $k->order_name,
                'service_rate' => $k->order_amount,
                'order_status' => 'new-order-paid',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function vanPatientListQueuing($data){
        $management_id = $data['management_id'];
        $company = $data['company'];
        $main_mgmt_id = $data['main_mgmt_id'];

        $query = "SELECT mobile_van_queue.*, patients.company,

            (SELECT firstname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as middle,
            (SELECT image FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as image,
            (SELECT IFNULL(COUNT(id), 0) FROM imaging_center WHERE imaging_center.patients_id = mobile_van_queue.patient_id AND imaging_center.imaging_result_attachment IS NULL) as count_xray,

            (SELECT IFNULL(COUNT(id), 0) FROM laboratory_urinalysis WHERE laboratory_urinalysis.patient_id = mobile_van_queue.patient_id AND laboratory_urinalysis.order_status = 'new-order-paid' ) as URINALYSIS,
            (SELECT IFNULL(COUNT(id), 0) FROM laboratory_stooltest WHERE laboratory_stooltest.patient_id = mobile_van_queue.patient_id AND laboratory_stooltest.order_status = 'new-order-paid' ) as STOOL,
            (SELECT URINALYSIS + STOOL ) as count_laboratory,

            (SELECT IFNULL(COUNT(id), 0) FROM laboratory_ecg WHERE laboratory_ecg.patient_id = mobile_van_queue.patient_id AND laboratory_ecg.order_status = 'new-order-paid' ) as ECG,
            (SELECT IFNULL(COUNT(id), 0) FROM laboratory_sars_cov WHERE laboratory_sars_cov.patient_id = mobile_van_queue.patient_id AND laboratory_sars_cov.order_status = 'new-order-paid' ) as SARS,
            (SELECT ECG + SARS ) as count_other,

            (SELECT IFNULL(COUNT(id), 0) FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.patient_id = mobile_van_queue.patient_id AND doctors_medical_certificate_ordered.order_status = 'new-order-paid' ) as MEDCERT,
            (SELECT IFNULL(COUNT(id), 0) FROM laboratory_medical_exam WHERE laboratory_medical_exam.patient_id = mobile_van_queue.patient_id AND laboratory_medical_exam.order_status = 'new-order-paid' ) as PEXAM,
            (SELECT MEDCERT + PEXAM ) as count_doctor

        FROM mobile_van_queue, patients WHERE patients.patient_id = mobile_van_queue.patient_id AND patients.company = '$company' AND mobile_van_queue.management_id = '$management_id' AND mobile_van_queue.main_mgmt_id = '$main_mgmt_id' GROUP BY mobile_van_queue.patient_id ORDER BY mobile_van_queue.id ASC ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }


    public static function getVanUrineTest($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_urinalysis')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_urinalysis.patient_id')
            ->select('laboratory_urinalysis.*', 'patients.*')
            ->where('laboratory_urinalysis.patient_id', $data['patient_id'])
            ->where('laboratory_urinalysis.order_status', 'new-order-paid')
            ->get();
    }

    public static function getVanStoolTest($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_stooltest')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_stooltest.patient_id')
            ->select('laboratory_stooltest.*', 'patients.*')
            ->where('laboratory_stooltest.patient_id', $data['patient_id'])
            ->where('laboratory_stooltest.order_status', 'new-order-paid')
            ->get();
    }

    public static function updateProcessVanUrineTest($data){
        $getraceno = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('laboratory_urinalysis')
        ->select('trace_number')
        ->where('laboratory_urinalysis.patient_id', $data['patient_id'])
        ->where('laboratory_urinalysis.order_status', 'new-order-paid')
        ->first();

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_urinalysis')
        ->where('trace_number', $getraceno->trace_number)
        ->where('laboratory_id', (new _Van)::getLaboratoryIdByMgtId($data['management_id'])->laboratory_id)
        ->update([
            'is_pending' => 0,
            'is_processed' => 1,
            'is_processed_time_start' => date('Y-m-d H:i:s'),
            'is_processed_by' => $data['user_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function updateProcessVanStoolTest($data){
        $getraceno = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('laboratory_stooltest')
        ->select('trace_number')
        ->where('laboratory_stooltest.patient_id', $data['patient_id'])
        ->where('laboratory_stooltest.order_status', 'new-order-paid')
        ->first();

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->table('laboratory_stooltest')
        ->where('trace_number', $getraceno->trace_number)
        ->where('laboratory_id', (new _Van)::getLaboratoryIdByMgtId($data['management_id'])->laboratory_id)
        ->update([
            'is_pending' => 0,
            'is_processed' => 1,
            'is_processed_time_start' => date('Y-m-d H:i:s'),
            'is_processed_by' => $data['user_id'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function saveUrinalysisOrderResult($data){
        $getraceno = DB::connection('mysql')
        ->table('laboratory_urinalysis')
        ->select('trace_number')
        ->where('laboratory_urinalysis.patient_id', $data['patient_id'])
        ->where('laboratory_urinalysis.order_status', 'new-order-paid')
        ->first();

        $orderid = $data['order_id'];
        date_default_timezone_set('Asia/Manila');
        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_urinalysis')
            ->where('order_id', $orderid[$i])
            ->where('trace_number', $getraceno->trace_number)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'color' => empty($data['color'][$i]) ? null : $data['color'][$i],
                'transparency' => empty($data['transparency'][$i]) ? null : $data['transparency'][$i],
                'reaction' => empty($data['reaction'][$i]) ? null : $data['reaction'][$i],
                'sp_gravity' => empty($data['sp_gravity'][$i]) ? null : $data['sp_gravity'][$i],
                'albumin' => empty($data['albumin'][$i]) ? null : $data['albumin'][$i],
                'sugar' => empty($data['sugar'][$i]) ? null : $data['sugar'][$i],
                'pus_cell' => empty($data['pus_cell'][$i]) ? null : $data['pus_cell'][$i],
                'rbc' => empty($data['rbc'][$i]) ? null : $data['rbc'][$i],
                'epithelial_cell' => empty($data['epithelial_cell'][$i]) ? null : $data['epithelial_cell'][$i],
                'mucus_threads' => empty($data['mucus_threads'][$i]) ? null : $data['mucus_threads'][$i],
                'renal_cell' => empty($data['renal_cell'][$i]) ? null : $data['renal_cell'][$i],
                'yeast_cell' => empty($data['yeast_cell'][$i]) ? null : $data['yeast_cell'][$i],
                'hyaline' => empty($data['hyaline'][$i]) ? null : $data['hyaline'][$i],
                'rbc_cast' => empty($data['rbc_cast'][$i]) ? null : $data['rbc_cast'][$i],
                'wbc_cast' => empty($data['wbc_cast'][$i]) ? null : $data['wbc_cast'][$i],
                'coarse_granular_cast' => empty($data['coarse_granular_cast'][$i]) ? null : $data['coarse_granular_cast'][$i],
                'fine_granular_cast' => empty($data['fine_granular_cast'][$i]) ? null : $data['fine_granular_cast'][$i],
                'pus_in_clumps' => empty($data['pus_in_clumps'][$i]) ? null : $data['pus_in_clumps'][$i],
                'rbc_in_clumps' => empty($data['rbc_in_clumps'][$i]) ? null : $data['rbc_in_clumps'][$i],
                'calcium_oxalate' => empty($data['calcium_oxalate'][$i]) ? null : $data['calcium_oxalate'][$i],
                'uric_acid' => empty($data['uric_acid'][$i]) ? null : $data['uric_acid'][$i],
                'amorphous_phosphate' => empty($data['amorphous_phosphate'][$i]) ? null : $data['amorphous_phosphate'][$i],
                'amorphous_urate' => empty($data['amorphous_urate'][$i]) ? null : $data['amorphous_urate'][$i],
                'calcium_carbonate' => empty($data['calcium_carbonate'][$i]) ? null : $data['calcium_carbonate'][$i],
                'ammonium_biurate' => empty($data['ammonium_biurate'][$i]) ? null : $data['ammonium_biurate'][$i],
                'triple_phosphate' => empty($data['triple_phosphate'][$i]) ? null : $data['triple_phosphate'][$i],
                'spermatozoa' => empty($data['spermatozoa'][$i]) ? null : $data['spermatozoa'][$i],
                'trichomonas_vaginalis' => empty($data['trichomonas_vaginalis'][$i]) ? null : $data['trichomonas_vaginalis'][$i],
                'micral_test' => empty($data['micral_test'][$i]) ? null : $data['micral_test'][$i],
                'urine_ketone' => empty($data['urine_ketone'][$i]) ? null : $data['urine_ketone'][$i],
                'others' => empty($data['others'][$i]) ? null : $data['others'][$i],
                'remarks' => empty($data['remarks'][$i]) ? null : $data['remarks'][$i],
                'order_status' => 'completed',
                'medtech' => $data['medtech'],
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $getraceno->trace_number);
        return true;
    }


    public static function saveStoolOrderResult($data){
        $getraceno = DB::connection('mysql')
        ->table('laboratory_stooltest')
        ->select('trace_number')
        ->where('laboratory_stooltest.patient_id', $data['patient_id'])
        ->where('laboratory_stooltest.order_status', 'new-order-paid')
        ->first();

        $orderid = $data['order_id'];
        date_default_timezone_set('Asia/Manila');
        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_stooltest')
            ->where('order_id', $orderid[$i])
            ->where('trace_number', $getraceno->trace_number)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],
                'color' => empty($data['color'][$i]) ? null : $data['color'][$i],
                'consistency' => empty($data['consistency'][$i]) ? null : $data['consistency'][$i],
                'occult_blood_result' => empty($data['occult_blood'][$i]) ? null : $data['occult_blood'][$i],
                'dfs' => empty($data['dfs'][$i]) ? null : $data['dfs'][$i],
                'kt' => empty($data['kt'][$i]) ? null : $data['kt'][$i],
                'kk' => empty($data['kk'][$i]) ? null : $data['kk'][$i],
                'dfs_ascaris' => empty($data['dfs_ascaris'][$i]) ? null : $data['dfs_ascaris'][$i],
                'dfs_hookworm' => empty($data['dfs_hookworm'][$i]) ? null : $data['dfs_hookworm'][$i],
                'dfs_blastocystis' => empty($data['dfs_blasto'][$i]) ? null : $data['dfs_blasto'][$i],
                'dfs_giardia_lamblia_cyst' => empty($data['dfs_giadia_cyst'][$i]) ? null : $data['dfs_giadia_cyst'][$i],
                'dfs_giardia_lamblia_trophozoite' => empty($data['dfs_giadia_trophozoite'][$i]) ? null : $data['dfs_giadia_trophozoite'][$i],
                'dfs_trichusris_trichuira' => empty($data['dfs_trichuris'][$i]) ? null : $data['dfs_trichuris'][$i],
                'dfs_entamoeba_lamblia_cyst' => empty($data['dfs_estamoeba_cyst'][$i]) ? null : $data['dfs_estamoeba_cyst'][$i],
                'dfs_entamoeba_lamblia_trophozoite' => empty($data['dfs_estamoeba_trophozoite'][$i]) ? null : $data['dfs_estamoeba_trophozoite'][$i],
                'kt_ascaris' => empty($data['kt_ascaris'][$i]) ? null : $data['kt_ascaris'][$i],
                'kt_hookworm' => empty($data['kt_hookworm'][$i]) ? null : $data['kt_hookworm'][$i],
                'kt_blastocystis' => empty($data['kt_blasto'][$i]) ? null : $data['kt_blasto'][$i],
                'kt_giardia_lamblia_cyst' => empty($data['kt_giadia_cyst'][$i]) ? null : $data['kt_giadia_cyst'][$i],
                'kt_giardia_lamblia_trophozoite' => empty($data['kt_giadia_trophozoite'][$i]) ? null : $data['kt_giadia_trophozoite'][$i],
                'kt_trichusris_trichuira' => empty($data['kt_trichuris'][$i]) ? null : $data['kt_trichuris'][$i],
                'kt_entamoeba_lamblia_cyst' => empty($data['kt_estamoeba_cyst'][$i]) ? null : $data['kt_estamoeba_cyst'][$i],
                'kt_entamoeba_lamblia_trophozoite' => empty($data['kt_estamoeba_trophozoite'][$i]) ? null : $data['kt_estamoeba_trophozoite'][$i],
                'kk_ascaris' => empty($data['kk_ascaris'][$i]) ? null : $data['kk_ascaris'][$i],
                'kk_hookworm' => empty($data['kk_hookworm'][$i]) ? null : $data['kk_hookworm'][$i],
                'kk_blastocystis' => empty($data['kk_blasto'][$i]) ? null : $data['kk_blasto'][$i],
                'kk_giardia_lamblia_cyst' => empty($data['kk_giadia_cyst'][$i]) ? null : $data['kk_giadia_cyst'][$i],
                'kk_giardia_lamblia_trophozoite' => empty($data['kk_giadia_trophozoite'][$i]) ? null : $data['kk_giadia_trophozoite'][$i],
                'kk_trichusris_trichuira' => empty($data['kk_trichuris'][$i]) ? null : $data['kk_trichuris'][$i],
                'kk_entamoeba_lamblia_cyst' => empty($data['kk_estamoeba_cyst'][$i]) ? null : $data['kk_estamoeba_cyst'][$i],
                'kk_entamoeba_lamblia_trophozoite' => empty($data['kk_estamoeba_trophozoite'][$i]) ? null : $data['kk_estamoeba_trophozoite'][$i],
                'others' => empty($data['others'][$i]) ? null : $data['others'][$i],
                'pus_cells' => empty($data['pus_cells'][$i]) ? null : $data['pus_cells'][$i],
                'reb_blood_cells' => empty($data['rbc'][$i]) ? null : $data['rbc'][$i],
                'fat_globules' => empty($data['fat_globules'][$i]) ? null : $data['fat_globules'][$i],
                'yeast_cells' => empty($data['yeast_cells'][$i]) ? null : $data['yeast_cells'][$i],
                'bacteria' => empty($data['bacteria'][$i]) ? null : $data['bacteria'][$i],
                'oil_droplets' => empty($data['oil_droplets'][$i]) ? null : $data['oil_droplets'][$i],
                'undigested_foods_paticles' => empty($data['undigested_food'][$i]) ? null : $data['undigested_food'][$i],
                'remarks' => empty($data['remarks'][$i]) ? null : $data['remarks'][$i],
                'order_status' => 'completed',
                'medtech' => $data['medtech'],
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        _Validator::checkIfTraceNumberIsNoNewOrderInLaboratoryVan($data['patient_id'], $getraceno->trace_number);
        return true;
    }

    public static function vanPatientListToPrintResult($data){
        // $query = "SELECT * FROM patients WHERE patients.added_from = 'mobile-van' AND patients.company  = '".$data['company']."' ORDER BY id ASC ";
        // $result = DB::connection('mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);
        $company = $data['company'];
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('cashier_patientbills_records')
        ->join('patients','patients.patient_id', '=', 'cashier_patientbills_records.patient_id')
        ->select('cashier_patientbills_records.*', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.image')
        ->where('patients.company', $company)
        ->where('patients.added_from', 'mobile-van')
        ->groupBy('cashier_patientbills_records.patient_id')
        ->get();



        // $query = "SELECT mobile_van_queue.*, patients.company,

        //     (SELECT firstname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as firstname,
        //     (SELECT lastname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as lastname,
        //     (SELECT middle FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as middle,
        //     (SELECT image FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as image,
        //     (SELECT IFNULL(COUNT(id), 0) FROM imaging_center WHERE imaging_center.patients_id = mobile_van_queue.patient_id AND imaging_center.imaging_result_attachment IS NULL) as count_xray,

        //     (SELECT IFNULL(COUNT(id), 0) FROM laboratory_urinalysis WHERE laboratory_urinalysis.patient_id = mobile_van_queue.patient_id AND laboratory_urinalysis.order_status = 'new-order-paid' ) as URINALYSIS,
        //     (SELECT IFNULL(COUNT(id), 0) FROM laboratory_stooltest WHERE laboratory_stooltest.patient_id = mobile_van_queue.patient_id AND laboratory_stooltest.order_status = 'new-order-paid' ) as STOOL,
        //     (SELECT URINALYSIS + STOOL ) as count_laboratory,

        //     (SELECT IFNULL(COUNT(id), 0) FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.patient_id = mobile_van_queue.patient_id AND doctors_medical_certificate_ordered.order_status = 'new-order-paid' ) as MEDCERT,
        //     (SELECT IFNULL(COUNT(id), 0) FROM laboratory_medical_exam WHERE laboratory_medical_exam.patient_id = mobile_van_queue.patient_id AND laboratory_medical_exam.order_status = 'new-order-paid' ) as PEXAM,
        //     (SELECT MEDCERT + PEXAM ) as count_doctor

        // FROM mobile_van_queue, patients WHERE patients.patient_id = mobile_van_queue.patient_id AND patients.company = '$company' AND mobile_van_queue.management_id = '$management_id' AND mobile_van_queue.main_mgmt_id = '$main_mgmt_id' GROUP BY mobile_van_queue.patient_id ORDER BY mobile_van_queue.id ASC ";
        // $result = DB::connection('mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);
    }



    public static function getMobileVanPatientsWithNewOrder($data)
    {
        $mngt = $data['management_id'];
        $query = " SELECT patient_id as pid,

                (SELECT concat(lastname,', ',firstname) from patients where patient_id = pid) as patient_name,
                (SELECT image from patients where patient_id = pid) as patient_image,

                (SELECT count(id) from laboratory_hematology where patient_id = pid and order_status='new-order-paid') as count_hema,
                (SELECT count(id) from laboratory_cbc where patient_id = pid and order_status='new-order-paid') as count_hema_cbc,
                (SELECT count(id) from laboratory_sorology where patient_id = pid and order_status='new-order-paid') as count_reso,
                (SELECT count(id) from laboratory_microscopy where patient_id = pid and order_status='new-order-paid') as count_micro,
                (SELECT count(id) from laboratory_chemistry where patient_id = pid and order_status='new-order-paid') as count_chem,
                (SELECT count(id) from laboratory_fecal_analysis where patient_id = pid and order_status='new-order-paid') as count_fecal,
                (SELECT count(id) from laboratory_stooltest where patient_id = pid and order_status='new-order-paid') as count_stool,
                (SELECT count(id) from laboratory_urinalysis where patient_id = pid and order_status='new-order-paid') as count_urinalysis,
                (SELECT count(id) from laboratory_papsmear where patient_id = pid and order_status='new-order-paid') as count_papsmear,
                (SELECT count(id) from laboratory_oral_glucose where patient_id = pid and order_status='new-order-paid') as count_oral,
                (SELECT count(id) from laboratory_thyroid_profile where patient_id = pid and order_status='new-order-paid') as count_thyroid,
                (SELECT count(id) from laboratory_immunology where patient_id = pid and order_status='new-order-paid') as count_immunology,
                (SELECT count(id) from laboratory_miscellaneous where patient_id = pid and order_status='new-order-paid') as count_miscellaneous,
                (SELECT count(id) from laboratory_hepatitis_profile where patient_id = pid and order_status='new-order-paid') as count_hepatitis,
                (SELECT count(id) from laboratory_ecg where patient_id = pid and order_status='new-order-paid') as count_ecg,
                -- (SELECT count(id) from laboratory_medical_exam where patient_id = pid and order_status='new-order-paid') as count_medical_exam,
                (SELECT count(id) from laboratory_covid19_test where patient_id = pid and order_status='new-order-paid') as count_covid_test,
                (SELECT count(id) from laboratory_tumor_maker where patient_id = pid and order_status='new-order-paid') as count_tumor_maker,
                (SELECT count(id) from laboratory_drug_test where patient_id = pid and order_status='new-order-paid') as count_drug_test,

                (SELECT IFNULL(sum(count_hema + count_hema_cbc + count_reso + count_micro + count_chem + count_fecal + count_stool + count_urinalysis + count_papsmear + count_oral + count_thyroid + count_immunology + count_miscellaneous + count_hepatitis + count_ecg + count_covid_test + count_tumor_maker + count_drug_test), 0)) as order_count

        from mobile_van_queue where management_id = '$mngt' AND type = 'laboratory' group by patient_id having order_count > 0";

        $result = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    // public static function vanPatientListQueuing($data)
    // {
    //     $management_id = $data['management_id'];
    //     $company = $data['company'];
    //     $main_mgmt_id = $data['main_mgmt_id'];

    //     $query = "SELECT mobile_van_queue.*, patients.company,

    //         (SELECT firstname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as firstname,
    //         (SELECT lastname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as lastname,
    //         (SELECT middle FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as middle,
    //         (SELECT image FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as image,
    //         (SELECT IFNULL(COUNT(id), 0) FROM imaging_center WHERE imaging_center.patients_id = mobile_van_queue.patient_id AND imaging_center.imaging_result_attachment IS NULL) as count_xray,

    //         (SELECT IFNULL(COUNT(id), 0) FROM laboratory_urinalysis WHERE laboratory_urinalysis.patient_id = mobile_van_queue.patient_id AND laboratory_urinalysis.order_status = 'new-order-paid' ) as URINALYSIS,
    //         (SELECT IFNULL(COUNT(id), 0) FROM laboratory_stooltest WHERE laboratory_stooltest.patient_id = mobile_van_queue.patient_id AND laboratory_stooltest.order_status = 'new-order-paid' ) as STOOL,
    //         (SELECT URINALYSIS + STOOL ) as count_laboratory,

    //         (SELECT IFNULL(COUNT(id), 0) FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.patient_id = mobile_van_queue.patient_id AND doctors_medical_certificate_ordered.order_status = 'new-order-paid' ) as MEDCERT,
    //         (SELECT IFNULL(COUNT(id), 0) FROM laboratory_medical_exam WHERE laboratory_medical_exam.patient_id = mobile_van_queue.patient_id AND laboratory_medical_exam.order_status = 'new-order-paid' ) as PEXAM,
    //         (SELECT MEDCERT + PEXAM ) as count_doctor

    //     FROM mobile_van_queue, patients WHERE patients.patient_id = mobile_van_queue.patient_id AND patients.company = '$company' AND mobile_van_queue.management_id = '$management_id' AND mobile_van_queue.main_mgmt_id = '$main_mgmt_id' GROUP BY mobile_van_queue.patient_id ORDER BY mobile_van_queue.id ASC ";
    //     $result = DB::connection('mysql')->getPdo()->prepare($query);
    //     $result->execute();
    //     return $result->fetchAll(\PDO::FETCH_OBJ);
    // }

    public static function getMobileVanPatientNewPEOrder($data)
    {
        $pid = $data['patient_id'];

        $query = "SELECT * from laboratory_medical_exam where order_status = 'new-order-paid' and patient_id = '$pid' group by trace_number";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getMobileVanPatientNewXRAYOrder($data)
    {
        $pid = $data['patient_id'];

        $query = "SELECT imaging_center_id, imaging_order, created_at, imaging_remarks, trace_number,

            (SELECT type from imaging_order_menu where imaging_order_menu.order_desc = imaging_center.imaging_order ) as imaging_type,
            (SELECT concat(lastname,' ',firstname) from patients where patients.patient_id = imaging_center.patients_id ) as patient_name,
            (SELECT concat(name) from doctors where doctors.doctors_id = imaging_center.doctors_id ) as doctors_name

        from imaging_center where imaging_center.patients_id = '$pid' and imaging_result is null and imaging_result_attachment is null";

        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getMobileVanPatientNewXRAYOrderAddResult($data, $filename)
    {
        date_default_timezone_set('Asia/Manila');
        $order_array = [];
        $query = DB::connection('mysql')
            ->table('imaging_center')
            ->where('imaging_center_id', $data['imaging_center_id'])
            ->update([
                'radiologist' => $data['radiologist'],
                'radiologist_type' => $data['radiologist_type'],
                'imaging_result_attachment' => $data['imaging_type'] == 'xray' ? $filename : NULL,
                'imaging_results_remarks' => strip_tags($data['impressions']),
                'imaging_result' => strip_tags($data['result']),
                'processed_by' => $data['user_id'],
                'start_time' => date('Y-m-d H:i:s'),
                'file_no' => $data['file_no'],
                'edit_by_encoder' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($query) {
            $nullCount = DB::connection('mysql')->table('imaging_center')
                ->where('patients_id', $data['patient_id'])
            // ->whereNull('imaging_result_attachment')
                ->whereNull('radiologist')
                ->get();

            if (count($nullCount) < 1) {
                DB::table('patient_queue')
                    ->where('patient_id', $data['patient_id'])
                    ->where('type', 'imaging')
                    ->delete();
            }
        }

        if ($query) {
            if ($data['radiologist_type'] == 'Telerad') {
                $result = DB::connection('mysql')->table('imaging_center')->where('imaging_center_id', $data['imaging_center_id'])->first();
                $addpatient = DB::connection('mysql')->table('patients')->where('patient_id', $data['patient_id'])->first();
                $checkpatient = DB::connection('mysql2')->table('patients')->where('patient_id', $data['patient_id'])->get();
                if (count($checkpatient) < 1) {
                    DB::connection('mysql2')->table('patients')->insert([
                        'patient_id' => $addpatient->patient_id,
                        'encoders_id' => $addpatient->encoders_id,
                        'doctors_id' => $addpatient->doctors_id,
                        'management_id' => $addpatient->management_id,
                        'main_mgmt_id' => $addpatient->main_mgmt_id,
                        'user_id' => $addpatient->user_id,
                        'firstname' => $addpatient->firstname,
                        'lastname' => $addpatient->lastname,
                        'middle' => $addpatient->middle,
                        'email' => $addpatient->email,
                        'mobile' => $addpatient->mobile,
                        'telephone' => $addpatient->telephone,
                        'birthday' => $addpatient->birthday,
                        'birthplace' => $addpatient->birthplace,
                        'gender' => $addpatient->gender,
                        'civil_status' => $addpatient->civil_status,
                        'religion' => $addpatient->religion,
                        'height' => $addpatient->height,
                        'weight' => $addpatient->weight,
                        'occupation' => $addpatient->occupation,
                        'street' => $addpatient->street,
                        'barangay' => $addpatient->barangay,
                        'city' => $addpatient->city,
                        'municipality' => $addpatient->municipality,
                        'tin' => $addpatient->tin,
                        'philhealth' => $addpatient->philhealth,
                        'company' => $addpatient->company,
                        'zip' => $addpatient->zip,
                        'blood_type' => $addpatient->blood_type,
                        'blood_systolic' => $addpatient->blood_systolic,
                        'blood_diastolic' => $addpatient->blood_diastolic,
                        'temperature' => $addpatient->temperature,
                        'pulse' => $addpatient->pulse,
                        'rispiratory' => $addpatient->rispiratory,
                        'glucose' => $addpatient->glucose,
                        'uric_acid' => $addpatient->uric_acid,
                        'hepatitis' => $addpatient->hepatitis,
                        'tuberculosis' => $addpatient->tuberculosis,
                        'dengue' => $addpatient->dengue,
                        'cholesterol' => $addpatient->cholesterol,
                        'allergies' => $addpatient->allergies,
                        'medication' => $addpatient->medication,
                        'covid_19' => $addpatient->covid_19,
                        'swine_flu' => $addpatient->swine_flu,
                        'hiv' => $addpatient->hiv,
                        'asf' => $addpatient->asf,
                        'vacinated' => $addpatient->vacinated,
                        'pro' => $addpatient->pro,
                        'remarks' => $addpatient->remarks,
                        'image' => $addpatient->image,
                        'status' => $addpatient->status,
                        'doctors_response' => $addpatient->doctors_response,
                        'is_edited_bydoc' => $addpatient->is_edited_bydoc,
                        'package_selected' => $addpatient->package_selected,
                        'join_category' => $addpatient->join_category,
                        'added_by' => $addpatient->added_by,
                        'created_at' => $addpatient->created_at,
                        'updated_at' => $addpatient->updated_at,
                    ]);
                }

                DB::connection('mysql2')->table('imaging_center')->insert([
                    'imaging_center_id' => $result->imaging_center_id,
                    'patients_id' => $result->patients_id,
                    'doctors_id' => $result->doctors_id,
                    'radiologist' => $result->radiologist,
                    'radiologist_type' => $result->radiologist_type,
                    'imaging_order' => $result->imaging_order,
                    'imaging_remarks' => $result->imaging_remarks,
                    'imaging_center' => $result->imaging_center,
                    'imaging_result' => $result->imaging_result,
                    'imaging_result_attachment' => $result->imaging_result_attachment,
                    'is_viewed' => $result->is_viewed,
                    'is_processed' => $result->is_processed,
                    'processed_by' => $result->processed_by,
                    'start_time' => $result->start_time,
                    'manage_by' => $result->manage_by,
                    'edit_by_encoder' => $result->edit_by_encoder,
                    'order_from' => $result->order_from,
                    'created_at' => $result->created_at,
                    'updated_at' => $result->updated_at,
                ]);
            }
        }

        if ($query) {
            $order_array[] = array(
                'transaction_id' => 'trnsct-' . rand(0, 9999) . time(),
                'patients_id' => $data['patient_id'],
                'imaging_order' => $data['imaging_order'],
                'processed_by' => $data['user_id'],
                'order_type' => $data['radiologist_type'],
                'amount' => $data['radiologist_type'] == 'Telerad' ? 200 : 150,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
        }

        _Validator::checkIfTraceNumberIsNoNewOrderInImagingVan($data['patient_id'], $data['trace_number']);

        return DB::table('imaging_center_record')->insert($order_array);
    }

    
    public static function getMobileVanPatientNewMedCertOrder($data)
    {
        $pid = $data['patient_id'];

        $query = "SELECT * from doctors_medical_certificate_ordered where patient_id = '$pid' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function getAllPatientRecordImagingForPrintVan($data)
    {
        // $patient_id = $data['patient_id'];
        // $management_id = $data['management_id'];

        $query = "SELECT *,
            (SELECT order_name FROM packages_charge WHERE packages_charge.package_name = imaging_center.imaging_order AND packages_charge.department = 'imaging' LIMIT 1) as orderDescPackage,
            (SELECT firstname FROM patients WHERE patients.patient_id = imaging_center.patients_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = imaging_center.patients_id LIMIT 1) as lastname,
            (SELECT order_cost FROM imaging_order_menu WHERE imaging_order_menu.order_desc = imaging_center.imaging_order LIMIT 1) as imagingCostReg,
            (SELECT order_cost FROM imaging_order_menu WHERE imaging_order_menu.order_desc = orderDescPackage LIMIT 1) as imagingCostPackage
        FROM imaging_center WHERE patients_id = '".$data['patient_id']."' AND manage_by = '".$data['management_id']."' AND imaging_result IS NOT NULL ";

        // $query = "SELECT * from doctors_medical_certificate_ordered where patient_id = '$pid' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);


        // return DB::connection('mysql')
        // ->table('imaging_center')
        // ->get();

    }

    public static function vanGetPatientList($data)
    {
        $company = $data['company'];
        if($data['company'] == 'all'){
            return DB::connection('mysql')
            ->table('patients')
            ->select('firstname', 'lastname', 'patient_id', 'image', 'middle')
            ->where('added_from', 'mobile-van')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->get();
        }else{
            return DB::connection('mysql')
            ->table('patients')
            ->select('firstname', 'lastname', 'patient_id', 'image', 'middle')
            ->where('added_from', 'mobile-van')
            ->where('main_mgmt_id', $data['main_mgmt_id'])
            ->where('company', $company)
            ->orderBy('lastname', 'ASC')
            ->get();
        }
    }

    public static function addLabOrderTounsave($data){
        $existingTraceNo = DB::table('cashier_patientbills_unpaid')->select('trace_number')->where('patient_id', $data['patient_id'])->get();
        $trace_number = '';
        $orderid = $data['laboratory_test_id'];
        $department = $data['department'];
        $laboratory_test = $data['laboratory_test'];
        
        if(count($existingTraceNo) > 0){
            $trace_number = $existingTraceNo[0]->trace_number;
        }else{
            $trace_number = $data['trace_number'];
        }

        if ($department == 'hemathology') {
            if ($laboratory_test == 'cbc' || $laboratory_test == 'cbc platelet') {
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
                            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                            'remarks' => 'ok',
                            'order_status' => 'new-order',
                            'trace_number' => $trace_number,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            else{
                $checkorderIdinHema = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hematology')
                    ->where('order_id', $orderid)
                    ->where('order_status', 'new-order')
                    ->get();

                if (count($checkorderIdinHema) < 1) {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hematology')
                    ->insert([
                        'lh_id' => 'lh-' . rand(0, 9999) . time(),
                        'order_id' => $orderid,
                        'patient_id' => $data['patient_id'],
                        'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => NULL,
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            
        }
        if ($department == 'serology') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'clinical-microscopy') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'order_remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'fecal-analysis') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'stool-test') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'clinical-chemistry') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'ecg') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'urinalysis') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'medical-exam') {
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
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'papsmear-test') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'oral-glucose') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'thyroid-profile') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'immunology') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'miscellaneous') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'hepatitis-profile') {
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
                    'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($department == 'covid-19') {
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
                        'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => 'ok',
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }
        if ($department == 'Tumor Maker') {
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
                        'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => 'ok',
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }
        if ($department == 'Drug Test') {
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
                        'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                        'remarks' => 'ok',
                        'order_status' => 'new-order',
                        'trace_number' => $trace_number,
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }

        return DB::table('cashier_patientbills_unpaid')->insert([
            'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
            'trace_number' => $trace_number,
            'doctors_id' => 'order-from-mobile',
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
            'bill_name' => $data['laboratory_test'],
            'bill_amount' => $data['laboratory_rate'],
            'bill_department' => $data['department'],
            'bill_from' => 'laboratory',
            'order_id' => $data['laboratory_test_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
    
    public static function vanBillingCancel($data)
    {
        $department = $data['bill_department'];
        $bill_name = $data['bill_name'];

        //LABORATORY
        if ($department == 'hemathology') {
                if ($bill_name == 'cbc' || $bill_name == 'cbc platelet') {
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_cbc')
                    ->where('trace_number', $data['trace_number'])
                    ->where('order_id', $data['order_id'])
                    ->where('order_status', 'new-order')
                    ->delete();
                }
                else{
                    DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                    ->table('laboratory_hematology')
                    ->where('trace_number', $data['trace_number'])
                    ->where('order_id', $data['order_id'])
                    ->where('order_status', 'new-order')
                    ->delete();
                }
                
        }
        if ($department == 'serology') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_sorology')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'clinical-microscopy') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_microscopy')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'fecal-analysis') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_fecal_analysis')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'stool-test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_stooltest')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'clinical-chemistry') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_chemistry')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'ecg') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_ecg')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'urinalysis') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_urinalysis')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'medical-exam') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_medical_exam')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'papsmear-test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_papsmear')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'oral-glucose') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_oral_glucose')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'thyroid-profile') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_thyroid_profile')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'immunology') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_immunology')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'miscellaneous') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_miscellaneous')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'hepatitis-profile') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_hepatitis_profile')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'covid-19') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_covid19_test')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'Tumor Maker') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_tumor_maker')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        if ($department == 'Drug Test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_drug_test')
            ->where('trace_number', $data['trace_number'])
            ->where('order_id', $data['order_id'])
            ->where('order_status', 'new-order')
            ->delete();
        }
        //PSYCHOLOGY
        if ($department == 'psychology') {
            if($data['bill_name'] == 'Audiometry'){
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('psychology_audiometry')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->delete();
            }
            if($data['bill_name'] == 'Neuro Examination'){
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('psychology_neuroexam')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->delete();
            }
            if($data['bill_name'] == 'Ishihara'){
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('psychology_ishihara')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('order_status', 'new-order')
                ->delete();
            }
            
        }

        return DB::connection('mysql')->table('cashier_patientbills_unpaid')
        ->where('cpb_id', $data['cancel_id'])
        ->where('patient_id', $data['patient_id'])
        ->delete();
    }

    public static function imagingOrderList($data){
        // return DB::table('imaging_order_menu')
        //     ->join('imaging', 'imaging.management_id', '=', 'imaging_order_menu.management_id')
        //     ->select('imaging.name', 'imaging_order_menu.*', 'imaging_order_menu.order_desc as label', 'imaging_order_menu.order_desc as value')
        //     ->where('imaging_order_menu.management_id', $data['management_id'])
        //     ->get();

        $management_id = $data['management_id'];
        $query = "SELECT *, order_desc as label, order_desc as value,
            (SELECT name FROM imaging WHERE imaging.management_id = '$management_id' LIMIT 1) as name
        FROM imaging_order_menu WHERE management_id = '$management_id' ";

        // $query = "SELECT * from doctors_medical_certificate_ordered where patient_id = '$pid' ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);  
    }

    public static function addImagingOrderTounsave($data){
        $existingTraceNo = DB::table('cashier_patientbills_unpaid')->select('trace_number')->where('patient_id', $data['patient_id'])->get();
        $trace_number = '';
        if(count($existingTraceNo) > 0){
            $trace_number = $existingTraceNo[0]->trace_number;
        }else{
            $trace_number = $data['trace_number'];
        }

        return DB::connection('mysql')->table('cashier_patientbills_unpaid')
        ->insert([
            'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
            'trace_number' => $trace_number,
            'doctors_id' => 'order-from-mobile',
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
            'bill_name' => $data['order'],
            'bill_amount' => $data['amount'],
            'bill_department' => 'imaging',
            'bill_from' => 'imaging',
            'order_id' => $data['imaging_order_id'],
            'remarks' => $data['remarks'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getPsychologyList($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_test')
            ->select('psychology_test.*', 'psychology_test.test as label', 'psychology_test.test as value')
            ->where('psycho_id', _Cashier::getPsychologyIdByMgt($data)->psycho_id)
            ->get();
    }

    public static function addPsychologyOrderTounsave($data)
    {
        $existingTraceNo = DB::table('cashier_patientbills_unpaid')->select('trace_number')->where('patient_id', $data['patient_id'])->get();
        $trace_number = '';

        if(count($existingTraceNo) > 0){
            $trace_number = $existingTraceNo[0]->trace_number;
        }else{
            $trace_number = $data['trace_number'];
        }

        $bill_name = $data['psychology_test'];
        $orderid = $data['psychology_test_id'];

        if ($bill_name == 'Audiometry') {
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
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($bill_name == 'Neuro Examination') {
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
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($bill_name == 'Ishihara') {
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
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return DB::connection('mysql')
        ->table('cashier_patientbills_unpaid')
        ->insert([
            'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
            'trace_number' => $trace_number,
            'doctors_id' => 'order-from-mobile',
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
            'bill_name' => $data['psychology_test'],
            'bill_amount' => $data['psychology_rate'],
            'bill_department' => 'psychology',
            'bill_from' => 'psychology',
            'order_id' => $data['psychology_test_id'],
            'remarks' => $data['remarks'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function getOtherList($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('other_order_test')
            ->select('other_order_test.*', 'other_order_test.order_name as label', 'other_order_test.order_name as value')
            ->where('management_id', $data['management_id'])
            ->get();
    }

    public static function addOtherOrderToUnsave($data)
    {
        $existingTraceNo = DB::table('cashier_patientbills_unpaid')->select('trace_number')->where('patient_id', $data['patient_id'])->get();
        $trace_number = '';

        if(count($existingTraceNo) > 0){
            $trace_number = $existingTraceNo[0]->trace_number;
        }else{
            $trace_number = $data['trace_number'];
        }

        $bill_name = $data['order_name'];
        $orderid = $data['order_id'];

        if ($bill_name == 'SARS-CoV-2 Antigen') {
            $checkorderIdinOthers = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sars_cov')
                ->where('order_id', $orderid)
                ->where('order_status', 'new-order')
                ->get();

            if (count($checkorderIdinOthers) < 1) {
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sars_cov')
                ->insert([
                    'lsc_id' => 'lsc-' . rand(0, 9999) . time(),
                    'order_id' => $orderid,
                    'patient_id' => $data['patient_id'],
                    'laboratory_id' => _Van::getLaboratoryIdByMgtId($data['management_id'])->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ($bill_name == 'ECG') {
            $checkorderIdinOthers2 = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_ecg')
                ->where('order_id', $orderid)
                ->where('order_status', 'new-order')
                ->get();

            if (count($checkorderIdinOthers2) < 1) {
                DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_ecg')
                ->insert([
                    'le_id' => 'le-' . rand(0, 9999) . time(),
                    'order_id' => $orderid,
                    'patient_id' => $data['patient_id'],
                    'laboratory_id' => _Van::getLaboratoryIdByMgtId($data['management_id'])->laboratory_id,
                    'remarks' => 'ok',
                    'order_status' => 'new-order',
                    'trace_number' => $trace_number,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return DB::connection('mysql')
        ->table('cashier_patientbills_unpaid')
        ->insert([
            'cpb_id' => 'cpb-' . rand(0, 9999) . time(),
            'trace_number' => $trace_number,
            'doctors_id' => 'order-from-mobile',
            'patient_id' => $data['patient_id'],
            'management_id' => $data['management_id'],
            'main_mgmt_id' => $data['main_mgmt_id'],
            'laboratory_id' => _Van::getLaboratoryIdByMgtId($data['management_id'])->laboratory_id,
            'bill_name' => $data['order_name'],
            'bill_amount' => $data['order_amount'],
            'bill_department' => 'Other Test',
            'bill_from' => 'Other Test',
            'order_id' => $data['order_id'],
            'remarks' => $data['remarks'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }




















































    public static function getVanPatientNewMedCertOrder($data)
    {
        $pid = $data['patient_id'];
        $management_id = $data['management_id'];

        $query = "SELECT * FROM doctors_medical_certificate_ordered WHERE management_id = '$management_id' AND patient_id = '$pid' AND diagnosis_findings IS NULL ";
        $result = DB::getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getMobileVanPatientNewMedCertOrderFirstDesc($data)
    {
        $pid = $data['patient_id'];
        return DB::connection('mysql')
        ->table('doctors_medical_certificate_ordered')
        ->join('patients', 'patients.patient_id', '=', 'doctors_medical_certificate_ordered.patient_id')
        ->select('doctors_medical_certificate_ordered.*', 'patients.firstname', 'patients.lastname', 'patients.birthday', 'patients.street', 'patients.barangay', 'patients.city')
        ->where('doctors_medical_certificate_ordered.patient_id', $pid)
        ->orderBy('doctors_medical_certificate_ordered.issued_at', 'DESC')
        ->first();
    }

    public static function getMedicalTechVanByBranch($data){
        // $management_id = $data['management_id'];
        // $query = "SELECT *, management_id as mgmtId, 
        //     (SELECT pathologist FROM laboratory_formheader WHERE management_id = mgmtId LIMIT 1) as pathologist,
        //     (SELECT pathologist_lcn FROM laboratory_formheader WHERE management_id = mgmtId LIMIT 1) as pathologist_lcn,
        //     (SELECT chief_medtech FROM laboratory_formheader WHERE management_id = mgmtId LIMIT 1) as chief_medtech,
        //     (SELECT chief_medtech_lci FROM laboratory_formheader WHERE management_id = mgmtId LIMIT 1) as chief_medtech_lci
        // FROM laboratory_list WHERE management_id = '$management_id' ";
        // $result = DB::getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);

        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('laboratory_list')
        ->where('management_id', $data['management_id'])
        ->get();
    }

    public static function getRadiologistVanByBranch($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('radiologist')
        ->where('management_id', $data['management_id'])
        ->get();
    }
    
    public static function getAllDoctorList($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('users')
        ->join('doctors', 'doctors.user_id', '=', 'users.user_id')
        ->leftJoin('management', 'management.management_id', '=', 'users.manage_by')
        ->select("doctors.*", "users.type", "management.name as branch")
        ->where('main_mgmt_id', $data['main_mgmt_id'])
        ->where("type", "HIS-Doctor")
        ->get();
    }
    
    public static function vanClinicalSummaryPatientListQueuing($data){
        // $management_id = $data['management_id'];
        // $company = $data['company'];
        // $main_mgmt_id = $data['main_mgmt_id'];

        // $query = "SELECT mobile_van_queue.*, patients.company,

        //     (SELECT firstname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as firstname,
        //     (SELECT lastname FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as lastname,
        //     (SELECT middle FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as middle,
        //     (SELECT image FROM patients WHERE patients.patient_id = mobile_van_queue.patient_id) as image,

        //     (SELECT IFNULL(COUNT(id), 0) FROM imaging_center WHERE imaging_center.patients_id = mobile_van_queue.patient_id AND imaging_center.imaging_result_attachment IS NULL) as count_xray,
        //     (SELECT IFNULL(COUNT(id), 0) FROM laboratory_urinalysis WHERE laboratory_urinalysis.patient_id = mobile_van_queue.patient_id AND laboratory_urinalysis.order_status = 'new-order-paid' ) as URINALYSIS,
        //     (SELECT IFNULL(COUNT(id), 0) FROM laboratory_stooltest WHERE laboratory_stooltest.patient_id = mobile_van_queue.patient_id AND laboratory_stooltest.order_status = 'new-order-paid' ) as STOOL,
        //     (SELECT URINALYSIS + STOOL ) as count_laboratory,
        //     (SELECT IFNULL(COUNT(id), 0) FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.patient_id = mobile_van_queue.patient_id AND doctors_medical_certificate_ordered.order_status = 'new-order-paid' ) as MEDCERT,
        //     (SELECT IFNULL(COUNT(id), 0) FROM laboratory_medical_exam WHERE laboratory_medical_exam.patient_id = mobile_van_queue.patient_id AND laboratory_medical_exam.order_status = 'new-order-paid' ) as PEXAM,
        //     (SELECT MEDCERT + PEXAM ) as count_doctor

        // FROM mobile_van_queue, patients WHERE patients.patient_id = mobile_van_queue.patient_id AND patients.company = '$company' AND mobile_van_queue.management_id = '$management_id' AND mobile_van_queue.main_mgmt_id = '$main_mgmt_id' GROUP BY mobile_van_queue.patient_id ORDER BY mobile_van_queue.id ASC ";
        // $result = DB::connection('mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);





        $management_id = $data['management_id'];
        $company = $data['company'];
        $main_mgmt_id = $data['main_mgmt_id'];

        // $query = "SELECT cashier_patientbills_records.*
        // FROM cashier_patientbills_records WHERE cashier_patientbills_records.management_id = '$management_id' AND cashier_patientbills_records.main_mgmt_id = '$main_mgmt_id' GROUP BY cashier_patientbills_records.trace_number ";
        // $result = DB::connection('mysql')->getPdo()->prepare($query);
        // $result->execute();
        // return $result->fetchAll(\PDO::FETCH_OBJ);

        $query = "SELECT cashier_patientbills_records.*, patients.company,
            (SELECT firstname FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id LIMIT 1) as firstname,
            (SELECT lastname FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id LIMIT 1) as lastname,
            (SELECT middle FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id LIMIT 1) as middle,
            (SELECT image FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id LIMIT 1) as image,
            (SELECT birthday FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id LIMIT 1) as birthday,
            (SELECT gender FROM patients WHERE patients.patient_id = cashier_patientbills_records.patient_id LIMIT 1) as gender,


            (SELECT name FROM doctors WHERE doctors.doctors_id = cashier_patientbills_records.doctors_id LIMIT 1) as doctorName,
            (SELECT cil_umn FROM doctors WHERE doctors.doctors_id = cashier_patientbills_records.doctors_id LIMIT 1) as doctorLic,


            (SELECT hcv FROM laboratory_sorology WHERE laboratory_sorology.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as hepaResult,
            (SELECT hbsag_quali FROM laboratory_hepatitis_profile WHERE laboratory_hepatitis_profile.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as hbsagResult,
            (SELECT fbs FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as fbsResult,
            (SELECT cholesterol FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as cholesterolResult,
            (SELECT triglyceride FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as triglycerideResult,
            (SELECT hdl_cholesterol FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as hdlResult,
            (SELECT ldl_cholesterol FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as ldlResult,
            (SELECT serum_uric_acid FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as uricResult,
            (SELECT creatinine FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as creaResult,
            (SELECT sgpt FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as sgptResult,
            (SELECT sgot FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as sgotResult,
            (SELECT hba1c FROM laboratory_chemistry WHERE laboratory_chemistry.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as hba1cResult,
            

            (SELECT remarks FROM laboratory_cbc WHERE laboratory_cbc.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as cbcRemarks,
            (SELECT remarks FROM laboratory_urinalysis WHERE laboratory_urinalysis.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as urinalysisRemarks,
            (SELECT remarks FROM laboratory_stooltest WHERE laboratory_stooltest.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as fecalysisRemarks,


            (SELECT pe_bp FROM laboratory_medical_exam WHERE laboratory_medical_exam.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as pe_bp,
            (SELECT pe_ht FROM laboratory_medical_exam WHERE laboratory_medical_exam.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as pe_ht,
            (SELECT pe_wt FROM laboratory_medical_exam WHERE laboratory_medical_exam.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as pe_wt,
            (SELECT pe_bmi FROM laboratory_medical_exam WHERE laboratory_medical_exam.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as pe_bmi,
            (SELECT pe_range FROM laboratory_medical_exam WHERE laboratory_medical_exam.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as pe_range,
            (SELECT medication FROM laboratory_medical_exam WHERE laboratory_medical_exam.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as pe_medication,


            (SELECT file_no FROM imaging_center WHERE imaging_center.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as xray_file_no,
            (SELECT imaging_results_remarks FROM imaging_center WHERE imaging_center.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as xray_impression,


            (SELECT interpretation FROM laboratory_ecg WHERE laboratory_ecg.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as ecgRemarks,


            (SELECT medcert_content FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as med_medication,
            (SELECT diagnosis_findings FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as med_diagnosis,
            (SELECT recommendation FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as med_recommendation,
            (SELECT remarks FROM doctors_medical_certificate_ordered WHERE doctors_medical_certificate_ordered.trace_number = cashier_patientbills_records.trace_number LIMIT 1) as med_remarks 

        FROM cashier_patientbills_records, patients WHERE patients.patient_id = cashier_patientbills_records.patient_id AND patients.company = '$company' AND cashier_patientbills_records.management_id = '$management_id' AND cashier_patientbills_records.main_mgmt_id = '$main_mgmt_id' GROUP BY cashier_patientbills_records.trace_number ORDER BY cashier_patientbills_records.id ASC ";
        $result = DB::connection('mysql')->getPdo()->prepare($query);
        $result->execute();
        return $result->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public static function vanSaveMedicalExamOrderResult($data){
        date_default_timezone_set('Asia/Manila');
        $orderid = $data['order_id'];
        $pmh = "";
        $fam_history = "";
        if(!empty($data['pmh'])){
            $pmh = implode(',', $data['pmh']);
        }else{
            $pmh = NULL;
        }

        if(!empty($data['fam_history'])){
            $fam_history = implode(',', $data['fam_history']);
        }else{
            $fam_history = NULL;
        }

        DB::table('laboratory_medical_exam')
            ->where('order_id', $orderid)
            ->where('trace_number', $data['trace_number'])
            ->update([
                'nature_of_exam' => (empty($data['nature_of_examination']) ? null : $data['nature_of_examination'] == 'Others') ? $data['nature_of_examination_other_specify'] : $data['nature_of_examination'],
                'pmh' => $pmh,
                'pmh_other_specify' => str_contains($pmh, 'others') ? $data['pmh_other_specify'] : null,
                'fam_history' => $fam_history,
                'fam_history_other_specify' => str_contains($fam_history, 'others') ? $data['fam_hist_other_specify'] : null,
                'prev_operation' => empty($data['prev_operation_hospital']) ? null : $data['prev_operation_hospital'],
                'personal_history_smoke' => empty($data['ph_smoking']) ? null : $data['ph_smoking'],
                'personal_history_smoke_qty' => empty($data['ph_smoking_perday']) ? null : $data['ph_smoking_perday'],
                'personal_history_smoke_no_year' => empty($data['ph_smoking_no_of_year']) ? null : $data['ph_smoking_no_of_year'],
                'personal_history_alchohol' => empty($data['ph_alcohol']) ? null : $data['ph_alcohol'],
                'personal_history_drug' => empty($data['ph_drug_abuse']) ? null : $data['ph_drug_abuse'],
                'allergy_foods' => empty($data['allergies_of_foods']) ? null : $data['allergies_of_foods'],
                'allergy_drugs' => empty($data['allergies_of_drugs']) ? null : $data['allergies_of_drugs'],
                'menstrual_hist_lmp' => empty($data['menstrual_lmp']) ? null : $data['menstrual_lmp'],
                'menstrual_hist_pmp' => empty($data['menstrual_pmp']) ? null : $data['menstrual_pmp'],
                'menstrual_hist_g' => empty($data['menstrual_g']) ? null : $data['menstrual_g'],
                'menstrual_hist_p' => empty($data['menstrual_p']) ? null : $data['menstrual_p'],
                'menstrual_hist_other' => empty($data['menstrual_other']) ? null : $data['menstrual_other'],
                'medication' => empty($data['medication']) ? null : $data['medication'],
                'pe_bp' => empty($data['bp']) ? null : $data['bp'],
                'pe_pr' => empty($data['pr']) ? null : $data['pr'],
                'pe_ht' => empty($data['ht']) ? null : $data['ht'],
                'pe_wt' => empty($data['wt']) ? null : $data['wt'],
                'pe_bmi' => empty($data['bmi']) ? null : $data['bmi'],
                'pe_range' => empty($data['range']) ? null : $data['range'],
                'visual_acuity_od_near' => empty($data['od_near']) ? null : $data['od_near'],
                'visual_acuity_od_far' => empty($data['od_far']) ? null : $data['od_far'],
                'visual_acuity_os_near' => empty($data['os_near']) ? null : $data['os_near'],
                'visual_acuity_os_far' => empty($data['os_far']) ? null : $data['os_far'],
                'pe_hearing_ad' => empty($data['hearing_ad']) ? null : $data['hearing_ad'],
                'pe_hearing_as' => empty($data['hearing_as']) ? null : $data['hearing_as'],
                'pe_skin' => empty($data['skin']) ? null : $data['skin'],
                'pe_heent' => empty($data['heent']) ? null : $data['heent'],
                'pe_neck' => empty($data['neck']) ? null : $data['neck'],
                'pe_chest' => empty($data['chest']) ? null : $data['chest'],
                'pe_cardio' => empty($data['cardio']) ? null : $data['cardio'],
                'pe_abdomen' => empty($data['abdomen']) ? null : $data['abdomen'],
                'pe_genitourinary' => empty($data['genitourinary']) ? null : $data['genitourinary'],
                'pe_genitalia' => empty($data['genitalia']) ? null : $data['genitalia'],
                'pe_linguinal' => empty($data['inguinal']) ? null : $data['inguinal'],
                'pe_extremities' => empty($data['extremities']) ? null : $data['extremities'],
                'pe_reflexes' => empty($data['reflexes']) ? null : $data['reflexes'],
                'pe_neurology' => empty($data['neuro']) ? null : $data['neuro'],
                // 'pe_other'
                'classification_a' => empty($data['classification_a']) ? null : $data['classification_a'],
                'classification_b' => empty($data['classification_b']) ? null : $data['classification_b'],
                'classification_c' => empty($data['classification_c']) ? null : $data['classification_c'],
                'classification_d' => empty($data['classification_d']) ? null : $data['classification_d'],
                'classification_other' => empty($data['classification_e']) ? null : $data['classification_e'],
                'impression' => empty($data['impression']) ? null : $data['impression'],
                'recommendations' => empty($data['recommendations']) ? null : $data['recommendations'],
                'annual_pe_reg_time' => empty($data['registration_time']) ? null : $data['registration_time'],
                'annual_pe_vital_time' => empty($data['vital_signs_time']) ? null : $data['vital_signs_time'],
                'annual_pe_cbc_time' => empty($data['cbc_time']) ? null : $data['cbc_time'],
                'annual_pe_urinalysis_time' => empty($data['urinalysis_time']) ? null : $data['urinalysis_time'],
                'annual_pe_fecal_time' => empty($data['fecalysis_time']) ? null : $data['fecalysis_time'],
                'annual_pe_xray_time' => empty($data['xray_time']) ? null : $data['xray_time'],
                'annual_pe_ecg_time' => empty($data['ecg_time']) ? null : $data['ecg_time'],
                'annual_pe_papsmear_time' => empty($data['papsmear_time']) ? null : $data['papsmear_time'],
                'annual_pe_eye_check_time' => empty($data['eye_checkup_time']) ? null : $data['eye_checkup_time'],
                'annual_pe_dental_check_time' => empty($data['dental_checkup_time']) ? null : $data['dental_checkup_time'],
                'annual_pe_exam_time' => empty($data['pe_time']) ? null : $data['pe_time'],
                'annual_pe_exit_time' => empty($data['exit_signature_time']) ? null : $data['exit_signature_time'],

                // 'remarks' => ,
                'order_status' => 'completed',
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // remoeve patient in queee if trace_number is no new remaining
        _Validator::checkIfTraceNumberIsNoNewOrderInPEVan($data['patient_id'], $data['trace_number']);

        return DB::table('doctors_notification')->insert([
            'notif_id' => 'nid-' . rand(0, 99999),
            'order_id' => $data['trace_number'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctors_id'],
            'category' => 'laboratory',
            'department' => 'hemathology',
            'is_view' => 0,
            'notification_from' => 'local',
            'message' => 'new hemathology test result',
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function vanSetMedCertOrderCompleted($data)
    {
        date_default_timezone_set('Asia/Manila');

        $update = DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        ->table('doctors_medical_certificate_ordered')
        ->where('lmc_id', $data['lmc_id'])
        ->update([
            'diagnosis_findings' => strip_tags($data['diagnosis']),
            'recommendation' => strip_tags($data['recommendation']),
            'remarks' => strip_tags($data['remarks']),
            'issued_at' => date('Y-m-d', strtotime($data['issued_at'])),
            'order_status' => 'completed',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if($update){
            _Validator::checkIfTraceNumberIsNoNewOrderInPEVan($data['patient_id'], $data['trace_number']);
        }
        return true;

    }

    public static function getMobileVanPatientNewECGOrder($data)
    {
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_ecg')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_ecg.patient_id')
            ->select('laboratory_ecg.*', 'patients.*')
            ->where('laboratory_ecg.patient_id', $data['patient_id'])
            ->where('laboratory_ecg.order_status', 'new-order-paid')
            ->get();
    }

    public static function saveECGOrderResult($data){
        $orderid = $data['order_id'];
        date_default_timezone_set('Asia/Manila');

        $getraceno = DB::connection('mysql')
        ->table('laboratory_ecg')
        ->select('trace_number')
        ->where('laboratory_ecg.patient_id', $data['patient_id'])
        ->where('laboratory_ecg.order_status', 'new-order-paid')
        ->first();

        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_ecg')
            ->where('order_id', $orderid[$i])
            ->where('trace_number', $getraceno->trace_number)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],

                'atrial_ventricular_rate' => empty($data['atrial_ventricular_rate'][$i]) ? null : $data['atrial_ventricular_rate'][$i],
                'rhythm' => empty($data['rhythm'][$i]) ? null : $data['rhythm'][$i],
                'axis' => empty($data['axis'][$i]) ? null : $data['axis'][$i],
                'p_wave' => empty($data['p_wave'][$i]) ? null : $data['p_wave'][$i],
                'pr_interval' => empty($data['pr_interval'][$i]) ? null : $data['pr_interval'][$i],
                'qrs' => empty($data['qrs'][$i]) ? null : $data['qrs'][$i],
                'qt_interval' => empty($data['qt_interval'][$i]) ? null : $data['qt_interval'][$i],
                'qrs_complex' => empty($data['qrs_complex'][$i]) ? null : $data['qrs_complex'][$i],
                'st_segment' => empty($data['st_segment'][$i]) ? null : $data['st_segment'][$i],
                'others' => empty($data['others'][$i]) ? null : $data['others'][$i],
                'interpretation' => empty($data['interpretation'][$i]) ? null : $data['interpretation'][$i],

                'order_status' => 'completed',
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        _Validator::checkIfTraceNumberIsNoNewOrderInOtherVan($data['patient_id'], $getraceno->trace_number);
        return true;
    }
    
    public static function getVanSarsCovTest($data){
        return DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_sars_cov')
            ->join('patients', 'patients.patient_id', '=', 'laboratory_sars_cov.patient_id')
            ->select('laboratory_sars_cov.*', 'patients.*')
            ->where('laboratory_sars_cov.patient_id', $data['patient_id'])
            ->where('laboratory_sars_cov.order_status', 'new-order-paid')
            ->get();
    }

    public static function saveSarsCovOrderResult($data){
        $getraceno = DB::connection('mysql')
        ->table('laboratory_sars_cov')
        ->select('trace_number')
        ->where('laboratory_sars_cov.patient_id', $data['patient_id'])
        ->where('laboratory_sars_cov.order_status', 'new-order-paid')
        ->first();

        $orderid = $data['order_id'];
        date_default_timezone_set('Asia/Manila');
        for ($i = 0; $i < count($orderid); $i++) {
            DB::table('laboratory_sars_cov')
            ->where('order_id', $orderid[$i])
            ->where('trace_number', $getraceno->trace_number)
            ->update([
                'is_pending' => 0,
                'is_processed' => 1,
                'is_processed_time_start' => date('Y-m-d H:i:s'),
                'is_processed_by' => $data['user_id'],

                'sars_cov_result' => empty($data['sars_cov_result'][$i]) ? null : $data['sars_cov_result'][$i],
                
                'order_status' => 'completed',
                'is_processed_time_end' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        _Validator::checkIfTraceNumberIsNoNewOrderInOtherVan($data['patient_id'], $getraceno->trace_number);
        return true;
    }

}
