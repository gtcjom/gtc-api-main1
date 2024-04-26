<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\_Cashier;


class _LaboratoryOrder extends Model
{
    public static function newHemathologyOrder($v, $data)
    {
        if ($v->bill_name == 'hemoglobin') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hemoglobin' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'hematocrit') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hematocrit' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'rbc') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'rbc' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'wbc') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'wbc' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'platelet count') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'platelet_count' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'differential count') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'differential_count' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'neutrophil') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'neutrophil' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'lymphocyte') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'lymphocyte' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'monocyte') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'monocyte' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'eosinophil') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'eosinophil' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'basophil') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'basophil' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'bands') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'bands' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'abo blood type / rh type') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'abo_blood_type_and_rh_type' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'bleeding time') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'bleeding_time' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'clotting time') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'clotting_time' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'mcv') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'mcv' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'mch') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'mch' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'mchc') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'mchc' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'rdw') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'rdw' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'mpv') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'mpv' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'pdw') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'pdw' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'pct') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'pct' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Blood Typing W/ RH') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'blood_typing_with_rh' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'CT/BT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ct_bt' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'ESR') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'esr' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Ferritin') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ferritin' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'APTT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'aptt' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Peripheral Smear') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'peripheral_smear' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Protime') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'protime' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }


        if ($v->bill_name == 'cbc') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_cbc')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'cbc' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'cbc platelet') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_cbc')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'cbc_platelet' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }


        return true;
    }

    public static function newSorologyOrder($v, $data)
    {

        if ($v->bill_name == 'Hepatitis B surface Antigen (HBsAg)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hbsag' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'HAV (Hepatitis A Virus) IgG/IgM') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hav' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        

        if ($v->bill_name == 'VDRL/RPR') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'vdrl_rpr' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'ANTI-HBC IGM') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'anti_hbc_igm' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'HCV (Hepatitis C Virus)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hcv' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'BETA HCG (QUALI)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'beta_hcg_quali' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'H. PYLORI') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'h_pylori' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'TYPHIDOT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'typhidot' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'VDRL/Syphilis Test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'syphilis_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'HACT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hact' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'ANA') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ana' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'DENGUE TEST') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'dengue_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newClinicMicroscopyOrder($v, $data)
    {
        if ($v->bill_name == 'chemical test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'chemical_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'microscopic test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->update([
                    'microscopic_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'pregnancy test (HCG)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->update([
                    'pregnancy_test_hcg' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Micral Test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->update([
                    'micral_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Semenalysis') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->update([
                    'seminalysis_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Occult Blood') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->update([
                    'occult_blood_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }
        return true;
    }

    public static function newFecalAnalysisOrder($v, $data)
    {
        if ($v->bill_name == 'fecal analysis') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_fecal_analysis')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'fecal_analysis' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newStoolTestOrder($v, $data)
    {
        if ($v->bill_name == 'fecalysis') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_stooltest')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'fecalysis' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newPapsmearTestOrder($v, $data)
    {
        if ($v->bill_name == 'Papsmear (Female 35yo & up)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_papsmear')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'papsmear_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newClinicChemistryOrder($v, $data)
    {
        if ($v->bill_name == 'fbs') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'fbs' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'glucose') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'glucose' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'creatinine') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'creatinine' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'uric acid') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'uric_acid' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'cholesterol') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'cholesterol' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'triglyceride') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'triglyceride' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'hdl cholesterol') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hdl_cholesterol' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'ldl cholesterol') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ldl_cholesterol' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'SGOT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'sgot' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'SGPT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'sgpt' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'bun') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'bun' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'soduim') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'soduim' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }
        if ($v->bill_name == 'potassium') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'potassium' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }
        if ($v->bill_name == 'hba1c') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hba1c' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'alkaline_phosphatase') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'alkaline_phosphatase' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'albumin') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'albumin' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'calcium') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'calcium' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }
        if ($v->bill_name == 'magnesium') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'magnesium' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }
        if ($v->bill_name == 'chloride') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'chloride' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }

        if ($v->bill_name == 'Serum Uric Acid') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'serum_uric_acid' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Lipid Profile') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'lipid_profile' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        } 

        if ($v->bill_name == 'LDH') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ldh' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'TPAG Ratio') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'tpag_ratio' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Bilirubin (Total/Direct)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'bilirubin' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Total Protein') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'total_protein' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Potassium (k+)') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'potassium_kplus' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }

        if ($v->bill_name == 'NA+/K+') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'na_plus_kplus' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }

        if ($v->bill_name == 'GGT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ggt' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Cholinesterase') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'cholinesterase' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Phosphorous') {
            _LaboratoryOrder::insertElectroIfNotExist($v, $data);

            // DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            //     ->table('laboratory_chemistry')
            //     ->where('order_id', $v->order_id)
            // // ->where('doctor_id', $v->doctors_id)
            //     ->where('patient_id', $v->patient_id)
            //     ->where('trace_number', $v->trace_number)
            //     ->update([
            //         'phosphorous' => 'new-order',
            //         'order_status' => 'new-order-paid',
            //     ]);
        }

        if ($v->bill_name == 'RBS') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'rbs' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'VLDL') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'vldl' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'RBC Cholinesterases') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'rbc_cholinesterase' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'CRP') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'crp' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'PRO CALCITONIN') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'pro_calcitonin' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'OGCT 1 TAKE (50 GRM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ogct_take_one_50grm' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'OGCT 1 TAKE (75 GRM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ogct_take_one_75grm' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'OGTT 2 TAKES (100 GRM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ogct_take_two_100grm' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'OGTT 2 TAKES (75 GRM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ogct_take_two_75grm' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'OGTT 3 TAKES (100 GRM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ogct_take_three_100grm' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'OGTT 4 TAKES (100 GRM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ogct_take_four_100grm' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        //DETECTS IF NEEDS  TO ADD IN ELECTROLYTES TABLE 04-11-2022
        // if($v->bill_name == 'soduim' || $v->bill_name == 'potassium' || $v->bill_name == 'calcium' || $v->bill_name == 'magnesium' || $v->bill_name == 'chloride' || $v->bill_name == 'Potassium (k+)' || $v->bill_name == 'NA+/K+' || $v->bill_name == 'Phosphorous'){
        //     $checkIfExistingTransactionIdInElectro = DB::table('')->where('')->get();
        //     if(

        //     )
        //     DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
        //     ->table('laboratory_electrolytes')
        //     ->insert([
        //         'le_id' => 'le-' . rand(0, 9999) . time(),
        //         // 'order_id' => $v->order_id,
        //         'patient_id' => $v->patient_id,
        //         'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
        //         'order_status' => 'new-order',
        //         'trace_number' => $v->trace_number,
        //         'status' => 1,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ]);
        // }

        return true;
    }

    public static function insertElectroIfNotExist($v, $data){
        $checkIfExistingTransactionIdInElectro = DB::table('laboratory_electrolytes')
        ->where('patient_id', $v->patient_id)
        ->where('trace_number', $v->trace_number)
        ->get();

        if(count($checkIfExistingTransactionIdInElectro) < 1){
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_electrolytes')
            ->insert([
                'le_id' => 'le-' . rand(0, 9) . time(),
                // 'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => _Cashier::getLaboratoryIdByMgt($data)->laboratory_id,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newUrinalysisOrder($v, $data)
    {
        if ($v->bill_name == 'urinalysis') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_urinalysis')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'urinalysis' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newECGOrder($v, $data)
    {
        if ($v->bill_name == 'ecg') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_ecg')
                ->where('order_id', $v->order_id)
            // ->where('doctor_id', $v->doctors_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ecg_test' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newMedicalExamOrder($v, $data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_medical_exam')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'medical_exam' => 1,
                'order_status' => 'new-order-paid',
            ]);

        return true;
    }

    public static function newOralGlucoseOrder($v, $data)
    {

        if ($v->bill_name == 'Baseline') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_oral_glucose')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'baseline' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);

             
        }

        if ($v->bill_name == 'First Hour') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_oral_glucose')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'first_hour' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);

             
        }

        if ($v->bill_name == 'Second Hour') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_oral_glucose')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'second_hour' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);

             
        }

        return true;

    }

    public static function newThyroidProfileOrder($v, $data)
    {
        if ($v->bill_name == 'T3') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    't3' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'T4') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    't4' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'TSH') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'tsh' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'FT4') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ft4' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }
        if ($v->bill_name == 'FT3') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ft3' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'T3T4') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    't3t4' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'FHT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'fht' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'T3-T4-TSH') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_thyroid_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    't3' => 'new-order',
                    't4' => 'new-order',
                    'tsh' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newImmunologyOrder($v, $data){
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_immunology')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'immunology_test' => 1,
                'order_status' => 'new-order-paid',
            ]);

        return true;
    }

    public static function newMiscellaneousOrder($v, $data)
    {
        if($v->bill_name == 'Pregnancy Test (Urine)'){
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_miscellaneous')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'pregnancy_test_urine' => 1,
                'order_status' => 'new-order-paid',
            ]);
        }
        
        if($v->bill_name == 'Pregnancy Test (Serum)'){
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_miscellaneous')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'pregnancy_test_serum' => 1,
                'order_status' => 'new-order-paid',
            ]);
        }

        if($v->bill_name == 'Papsmear'){
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_miscellaneous')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'papsmear_test' => 1,
                'order_status' => 'new-order-paid',
            ]);
        }
        
        if($v->bill_name == 'Papsmear with Gramstain'){
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_miscellaneous')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'papsmear_test_with_gramstain' => 1,
                'order_status' => 'new-order-paid',
            ]);
        }

        return true;
    }

    public static function newHepatitisProfileOrder($v, $data)
    {
        if ($v->bill_name == 'HBsAg(Quali)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hbsag_quali' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HBs(Quali)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antihbs_quali' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HCV(Quali)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antihcv_quali' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'HBsAG(Quanti)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hbsag_quanti' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HBs(Quanti)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antihbs_quanti' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'HBeAg') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'hbeaag' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HBe') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antihbe' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HBc(IgM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antihbc_igm' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HAV(IgM)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antihav_igm' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HAVIGM/IGG') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'anti_havigm_igg' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Anti-HBc(IgG Total)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hepatitis_profile')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antihbc_iggtotal' => 'new-order',
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    //new psychology test
    public static function newIshiharaProfileOrder($v, $data){
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_ishihara')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'ishihara_test' => 1,
                'order_status' => 'new-order-paid',
            ]);

        return true;
    }

    public static function newAudiometryProfileOrder($v, $data){
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_audiometry')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'audiometry_test' => 1,
                'order_status' => 'new-order-paid',
            ]);

        return true;
    }

    public static function newNeuroProfileOrder($v, $data){
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('psychology_neuroexam')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'neuroexam_test' => 1,
                'order_status' => 'new-order-paid',
            ]);

        return true;
    }
    //end new

    // covid 19 test
    public static function newCovid19TestOrder($v, $data)
    {

        if ($v->bill_name == 'Covid Rapid Test - Antibody') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_covid19_test')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'rapid_test' => 1, // Antibody
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Covid Rapid Test - Antigen') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_covid19_test')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'antigen_test' => 1, // antigen
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    // new tumok maker test

    public static function newTumorMakerTestOrder($v, $data)
    {

        if ($v->bill_name == 'ASO') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_tumor_maker')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'aso' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Biopsy') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_tumor_maker')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'biopsy' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'C3') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_tumor_maker')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'c3' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'CA 125 (OVARY)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_tumor_maker')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'ca_125' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'CEA') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_tumor_maker')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'cea' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'PSA (PROSTATE)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_tumor_maker')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'psa_prostate' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'AFP') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_tumor_maker')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'afp' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }

    public static function newDrugTestTestOrder($v, $data)
    {

        if ($v->bill_name == 'Drug Test (2 Panels)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_drug_test')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'two_panels' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Drug Test (3 Panels)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_drug_test')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'three_panels' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        if ($v->bill_name == 'Drug Test (5 Panels)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_drug_test')
                ->where('order_id', $v->order_id)
                ->where('patient_id', $v->patient_id)
                ->where('trace_number', $v->trace_number)
                ->update([
                    'five_panels' => 1,
                    'order_status' => 'new-order-paid',
                ]);
        }

        return true;
    }
    
    // refunddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd
    public static function refundHemathologyOrder($data)
    {
        if ($data['bill_name'] == 'hemoglobin') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'hemoglobin' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'hematocrit') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'hematocrit' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'rbc') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'rbc' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'wbc') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->update([
                    'wbc' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'platelet count') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'platelet_count' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'differential count') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('trace_number', $data['trace_number'])
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'differential_count' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'neutrophil') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'neutrophil' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'lymphocyte') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'lymphocyte' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'monocyte') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'monocyte' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'eosinophil') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'eosinophil' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'basophil') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'basophil' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'bleeding time') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'bleeding_time' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'clotting time') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'clotting_time' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'mcv') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'mcv' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'mch') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'mch' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'mchc') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'mchc' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'rdw') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'rdw' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'mpv') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'mpv' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'pdw') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'pdw' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'pct') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_hematology')
                ->where('order_id', $data['order_id'])
                ->where('trace_number', $data['trace_number'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'pct' => 'refund',
                ]);
        }

        return true;
    }

    public static function refundClinicMicroscopyOrder($data)
    {
        if ($data['bill_name'] == 'chemical test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'chemical_test' => 2,
                ]);
        }
        if ($data['bill_name'] == 'microscopic test') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'microscopic_test' => 2,
                ]);
        }
        if ($data['bill_name'] == 'pregnancy test (HCG)') {

            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_microscopy')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'pregnancy_test_hcg' => 2,
                ]);
        }

        return true;
    }

    public static function refundSorologyOrder($data)
    {

        if ($data['bill_name'] == 'Hepatitis B surface Antigen (HBsAg)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'hbsag' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'HAV (Hepatitis A Virus) IgG/IgM') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'hav' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'HCV (Hepatitis C Virus)') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'hcv' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'VDRL/RPR') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_sorology')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'vdrl_rpr' => 'refund',
                ]);
        }

        return true;
    }

    public static function refundFecalAnalysisOrder($data)
    {
        if ($data['bill_name'] == 'fecal analysis') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_fecal_analysis')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'fecal_analysis' => 2,
                ]);
        }

        return true;
    }

    public static function refundClinicChemistryOrder($data)
    {
        if ($data['bill_name'] == 'fbs') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'fbs' => 'refund',
                ]);
        }
        
        if ($data['bill_name'] == 'glucose') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'glucose' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'creatinine') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'creatinine' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'uric acid') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'uric_acid' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'cholesterol') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'cholesterol' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'triglyceride') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'triglyceride' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'hdl cholesterol') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'hdl_cholesterol' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'ldl cholesterol') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'ldl_cholesterol' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'SGOT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'sgot' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'SGPT') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'sgpt' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'bun') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'bun' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'soduim') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'soduim' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'potassium') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'potassium' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'hba1c') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'hba1c' => 'refund',
                ]);
        }
        if ($data['bill_name'] == 'alkaline_phosphatase') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'alkaline_phosphatase' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'albumin') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'albumin' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'calcium') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'calcium' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'magnesium') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'magnesium' => 'refund',
                ]);
        }

        if ($data['bill_name'] == 'chloride') {
            DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
                ->table('laboratory_chemistry')
                ->where('order_id', $data['order_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->where('patient_id', $data['patient_id'])
                ->update([
                    'chloride' => 'refund',
                ]);
        }

        return true;
    }

    public static function refundECGOrder($data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_ecg')
            ->where('order_id', $data['order_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'ecg_test' => 2,
            ]);

        return true;
    }

    public static function refundStoolTestOrder($data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_stooltest')
            ->where('order_id', $data['order_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'fecalysis' => 2,
            ]);

        return true;
    }

    public static function refundUrinalysisOrder($data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_urinalysis')
            ->where('order_id', $data['order_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'urinalysis' => 2,
            ]);

        return true;
    } 

    public static function refundMedicalExamOrder($data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_medical_exam')
            ->where('order_id', $data['order_id'])
            ->where('patient_id', $data['patient_id'])
            ->update([
                'medical_exam' => 2,
            ]);

        return true;
    }

    // new package order
    public static function newPackagesOrder($v, $data)
    {
        $getProductDetails = DB::table('packages_charge')->where('package_id', $v->laboratory_id)->get();
        $getImagingId = DB::table('imaging')->select('imaging_id')->where('management_id', $data['management_id'])->get();
        $getLaboratoryId = DB::table('laboratory_list')->select('laboratory_id')->where('management_id', $data['management_id'])->get();

        foreach ($getProductDetails as $k) {
            // save doctor order
            // if($k->department == 'doctor'){
            //     if($data['transaction_type'] == 'corporate'){
            //         _Cashier::doctorCountQueue($data);
            //     }
            //     // FOR PHYSICAL EXAMINATION
            // }

            // save laboratory order
            if ($k->department == 'laboratory') {
                _Cashier::laboratoryCountQueue($v, $data);

                if ($k->category == 'hemathology') {
                    _LaboratoryOrderPackage::newHemathologyOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'serology') {
                    _LaboratoryOrderPackage::newSorologyOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'clinical-microscopy') {
                    _LaboratoryOrderPackage::newClinicMicroscopyOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'stool-test') {
                    _LaboratoryOrderPackage::newFecalAnalysisOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'clinical-chemistry') {
                    _LaboratoryOrderPackage::newClinicChemistryOrderPackage($k, $v, $getLaboratoryId);
                }

                if ($k->category == 'ecg') {
                    _LaboratoryOrderPackage::newECGOrderPackage($k, $v, $getLaboratoryId);
                }

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

            // save imaging order
            if ($k->department == 'imaging') {
                _Cashier::imagingCountQueue($data, $v->trace_number);

                DB::table('imaging_center')->insert([
                    'imaging_center_id' => 'rand-' . rand(0, 9999) . time(),
                    'patients_id' => $v->patient_id,
                    'doctors_id' => $v->doctors_id,
                    'imaging_order' => $v->bill_name,
                    'imaging_center' => count($getImagingId) > 0 ? $getImagingId[0]->imaging_id : null,
                    'is_viewed' => 1,
                    'manage_by' => $data['management_id'],
                    'order_from' => 'local',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // /psychology
            if ($k->department == 'psychology') {
                _Cashier::psychologyCountQueue($data);
                _LaboratoryOrderPackage::newPsychologyOrderPackage($k, $v, $data);
            }

            // /doctor
            if ($k->department == 'doctor') {
                // undecided what to run and running
            } 

            // /others - no order form
            if ($k->department == 'others') {
                _LaboratoryOrderPackage::newOtherTestPackage($k, $v, $getLaboratoryId, $data); 
            }
        }

        return true;
    }

    //01-21-2022
    //new psychology test
    public static function newSarsCovOrder($v, $data){
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_sars_cov')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'sars_cov' => 1,
                'order_status' => 'new-order-paid',
            ]);

        return true;
    }

    public static function newECGNewOrder($v, $data)
    {
        DB::connection($data['connection'] == 'online' ? 'mysql2' : 'mysql')
            ->table('laboratory_ecg')
            ->where('order_id', $v->order_id)
            ->where('patient_id', $v->patient_id)
            ->where('trace_number', $v->trace_number)
            ->update([
                'ecg_test' => 1,
                'order_status' => 'new-order-paid',
            ]);

        return true;
    }

    //end new
}
