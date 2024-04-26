<?php

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class _Validator extends Model
{
    public static function getManagementName($manage_by, $main_mgmt_id)
    {
        return DB::table('management')
        ->select("name")
        ->where('management_id', $manage_by)
        ->first();
    }

    public static function verifyAccount($data)
    {
        if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
            return true;
        }
    }

    public static function checkProductIfExist($data)
    {
        $count = DB::table('pharmacyhospital_products')->where('product', $data['brand'])->where('pharmacy_id', $data['pharmacy_id'])->where('management_id', $data['management_id'])->get();
        if (count($count) > 0) {return true;}
    }

    public static function checkProductBatchIfExist($data)
    {
        $count = DB::table('pharmacyhospital_temporary_out')->where('product_id', $data['product_id'])->where('product', $data['brand'])->where('batch_no', $data['batch_no'])->get();
        if (count($count) > 0) {return true;}
    }

    public static function checkEmailInPatient($email)
    {
        $count = DB::table('patients')->where('email', $email)->get();
        if (count($count) > 0) {return true;}
    }

    public static function verifyUserId($data)
    {
        $count = DB::table('users')
            ->where('user_id', $data['user_id'])
            ->where('manage_by', $data['management_id'])
            ->get();
        if (count($count) > 0) {return true;}
    }

    public static function userIdAlredyLog($data){
        $count = DB::table('hospital_dtr_logs')
            ->where('user_id', $data['user_id'])
            ->where('management_id', $data['management_id'])
            ->whereDate($data['process'] == 'login' ? 'timein' : 'timeout', date('Y-m-d', strtotime($data['date_now'])))
            ->get();

        if (count($count) > 0) {return true;}
    }

    public static function checkActiveAppointment($patient_id){
        $query = DB::table('appointment_list')->where('patients_id', $patient_id)->where('is_complete', 0)->get();
        if (count($query) > 0) {return true;}
    }
    
    public static function checkPsychologyTestIfExist($data){
        $count = DB::table('cashier_patientbills_unpaid')->where('order_id', $data['test_id'])->where('patient_id', $data['patient_id'])->where('bill_name', $data['test'])->get();
        if (count($count) > 0) {return true;}
    }

    public static function checkImagingTestIfExist($data){
        $count = DB::table('imaging_center_unsaveorder')->where('imaging_order_id', $data['imaging_order_id'])->where('patients_id', $data['patient_id'])->where('imaging_order', $data['order'])->get();
        if (count($count) > 0) {return true;}
    }

    public static function checkPsychologyTestIfExistByMgmt($data)
    {
        $count = DB::table('psychology_test')->where('test', $data['test'])->where('department', 'psychology')->where('management_id', $data['management_id'])->get();
        if (count($count) > 0) {return true;}
    }

    public static function checkPhysicalExamTestIfExistByMgmt($data)
    {
        $count = DB::table('medical_examination_test')->where('test', $data['test'])->where('management_id', $data['management_id'])->get();
        if (count($count) > 0) {return true;}
    }

    public static function checkIfTraceNumberIsNoNewOrderInLaboratory($patient_id, $trace_number, $queue)
    {
        $chem_count = DB::table('laboratory_chemistry')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $fecal_count = DB::table('laboratory_fecal_analysis')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $hema_count = DB::table('laboratory_hematology')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $hepa_profile_count = DB::table('laboratory_hepatitis_profile')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $immu_count = DB::table('laboratory_immunology')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        // $medexam_count = DB::table('laboratory_medical_exam')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $microscopy_count = DB::table('laboratory_microscopy')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $misce_count = DB::table('laboratory_miscellaneous')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $glucose_count = DB::table('laboratory_oral_glucose')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $papsmear_count = DB::table('laboratory_papsmear')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $soro_count = DB::table('laboratory_sorology')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $stool_count = DB::table('laboratory_stooltest')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $thyroid_count = DB::table('laboratory_thyroid_profile')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $uri_count = DB::table('laboratory_urinalysis')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        // $ecg_count = DB::table('laboratory_ecg')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $cbc_count = DB::table('laboratory_cbc')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $covid_count = DB::table('laboratory_covid19_test')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $tumor_maker_count = DB::table('laboratory_tumor_maker')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $drugtest_count = DB::table('laboratory_drug_test')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();


        $all_count = ((int) $chem_count > 0 ? 1 : 0) + 
            ((int) $fecal_count > 0 ? 1 : 0) +
            ((int) $hema_count > 0 ? 1 : 0) +
            ((int) $hepa_profile_count > 0 ? 1 : 0) +
            ((int) $immu_count > 0 ? 1 : 0) +
            // ((int) $medexam_count > 0 ? 1 : 0) +
            ((int) $microscopy_count > 0 ? 1 : 0) +
            ((int) $misce_count > 0 ? 1 : 0) +
            ((int) $glucose_count > 0 ? 1 : 0) +
            ((int) $papsmear_count > 0 ? 1 : 0) +
            ((int) $soro_count > 0 ? 1 : 0) +
            ((int) $stool_count > 0 ? 1 : 0) +
            ((int) $thyroid_count > 0 ? 1 : 0) +
            ((int) $uri_count > 0 ? 1 : 0) +
            // ((int) $ecg_count > 0 ? 1 : 0) + 
            ((int) $cbc_count > 0 ? 1 : 0) +
            ((int) $covid_count > 0 ? 1 : 0) +
            ((int) $tumor_maker_count > 0 ? 1 : 0) +
            ((int) $drugtest_count > 0 ? 1 : 0)
        ;
            
        if ($all_count < 1) {
            return DB::table($queue)->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('type', 'laboratory')->delete();
        }
    }

    public static function checkIfTraceNumberIsNoNewOrderInLaboratoryVan($patient_id, $trace_number)
    {
        $chem_count = DB::table('laboratory_chemistry')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $fecal_count = DB::table('laboratory_fecal_analysis')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $hema_count = DB::table('laboratory_hematology')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $hepa_profile_count = DB::table('laboratory_hepatitis_profile')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $immu_count = DB::table('laboratory_immunology')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        // $medexam_count = DB::table('laboratory_medical_exam')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $microscopy_count = DB::table('laboratory_microscopy')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $misce_count = DB::table('laboratory_miscellaneous')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $glucose_count = DB::table('laboratory_oral_glucose')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $papsmear_count = DB::table('laboratory_papsmear')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $soro_count = DB::table('laboratory_sorology')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $stool_count = DB::table('laboratory_stooltest')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $thyroid_count = DB::table('laboratory_thyroid_profile')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $uri_count = DB::table('laboratory_urinalysis')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        // $ecg_count = DB::table('laboratory_ecg')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $cbc_count = DB::table('laboratory_cbc')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $covid_count = DB::table('laboratory_covid19_test')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $tumor_maker_count = DB::table('laboratory_tumor_maker')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $drugtest_count = DB::table('laboratory_drug_test')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();


        $all_count = ((int) $chem_count > 0 ? 1 : 0) + 
            ((int) $fecal_count > 0 ? 1 : 0) +
            ((int) $hema_count > 0 ? 1 : 0) +
            ((int) $hepa_profile_count > 0 ? 1 : 0) +
            ((int) $immu_count > 0 ? 1 : 0) +
            // ((int) $medexam_count > 0 ? 1 : 0) +
            ((int) $microscopy_count > 0 ? 1 : 0) +
            ((int) $misce_count > 0 ? 1 : 0) +
            ((int) $glucose_count > 0 ? 1 : 0) +
            ((int) $papsmear_count > 0 ? 1 : 0) +
            ((int) $soro_count > 0 ? 1 : 0) +
            ((int) $stool_count > 0 ? 1 : 0) +
            ((int) $thyroid_count > 0 ? 1 : 0) +
            ((int) $uri_count > 0 ? 1 : 0) +
            // ((int) $ecg_count > 0 ? 1 : 0) + 
            ((int) $cbc_count > 0 ? 1 : 0) +
            ((int) $covid_count > 0 ? 1 : 0) +
            ((int) $tumor_maker_count > 0 ? 1 : 0) +
            ((int) $drugtest_count > 0 ? 1 : 0)
        ;
            
        if ($all_count < 1) {
            return DB::table('mobile_van_queue')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('type', 'laboratory')->delete();
        }
    }

    public static function checkIfTraceNumberIsNoNewOrderInImagingVan($patient_id, $trace_number)
    {
        $xray_count = DB::table('imaging_center')->where('patients_id', $patient_id)->where('trace_number', $trace_number)->whereNull('imaging_result')->count();
        $all_count = ((int) $xray_count > 0 ? 1 : 0);
        
        if ($all_count < 1) {
            return DB::table('mobile_van_queue')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('type', 'imaging')->delete();
        }
    }

    public static function checkIfTraceNumberIsNoNewOrderInPEVan($patient_id, $trace_number)
    {
        $medexam_count = DB::table('laboratory_medical_exam')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $medcert = DB::table('doctors_medical_certificate_ordered')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $all_count = ((int) $medexam_count > 0 ? 1 : 0) + ((int) $medcert > 0 ? 1 : 0);
            
        if ($all_count < 1) {
            return DB::table('mobile_van_queue')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('type', 'doctor')->delete();
        }
    }

    public static function checkIfTraceNumberIsNoNewOrderInOtherVan($patient_id, $trace_number)
    {
        $sars_count = DB::table('laboratory_sars_cov')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $ecg_count = DB::table('laboratory_ecg')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $all_count = ((int) $sars_count > 0 ? 1 : 0) + ((int) $ecg_count > 0 ? 1 : 0);
        if ($all_count < 1) {
            return DB::table('mobile_van_queue')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('type', 'other')->delete();
        }
    }
    
    public static function checkIfTraceNumberIsNoNewOrderInPsychology($patient_id, $trace_number)
    {
        $audio = DB::table('psychology_audiometry')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $ishihara = DB::table('psychology_ishihara')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $neuro = DB::table('psychology_neuroexam')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();


        $all_count = ((int) $audio > 0 ? 1 : 0) + 
            ((int) $ishihara > 0 ? 1 : 0) +
            ((int) $neuro > 0 ? 1 : 0) 
        ;
            
        if ($all_count < 1) {
            return DB::table('patient_queue')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('type', 'psychology')->delete();
        }
    }

    public static function checkIfTraceNumberIsNoNewOrderInPsychologyVan($patient_id, $trace_number)
    {
        $audio = DB::table('psychology_audiometry')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $ishihara = DB::table('psychology_ishihara')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();
        $neuro = DB::table('psychology_neuroexam')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('order_status', '<>', 'completed')->count();


        $all_count = ((int) $audio > 0 ? 1 : 0) + 
            ((int) $ishihara > 0 ? 1 : 0) +
            ((int) $neuro > 0 ? 1 : 0) 
        ;
            
        if ($all_count < 1) {
            return DB::table('mobile_van_queue')->where('patient_id', $patient_id)->where('trace_number', $trace_number)->where('type', 'psychology')->delete();
        }
    }



}
