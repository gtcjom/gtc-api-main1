<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
// use App\Models\_Cashier;

class _LaboratoryOrderLabOrderVan extends Model
{
    public static function newHemathologyLabOrderVan($v)
    {
        // hemoglobin
        if ($v->bill_name == 'hemoglobin') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hemoglobin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // hematocrit
        if ($v->bill_name == 'hematocrit') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hematocrit' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // rbc
        if ($v->bill_name == 'rbc') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'rbc' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // wbc
        if ($v->bill_name == 'wbc') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'wbc' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // platelet_count
        if ($v->bill_name == 'platelet count') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'platelet_count' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // differential_count
        if ($v->bill_name == 'differential count') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'differential_count' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // neutrophil
        if ($v->bill_name == 'neutrophil') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'neutrophil' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // lymphocyte
        if ($v->bill_name == 'lymphocyte') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'lymphocyte' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'monocyte') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'monocyte' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // eosinophil
        if ($v->bill_name == 'eosinophil') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'eosinophil' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // basophil
        if ($v->bill_name == 'basophil') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'basophil' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // bands
        if ($v->bill_name == 'bands') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'bands' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // abo_blood_type_and_rh_type
        if ($v->bill_name == 'abo blood type / rh type') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'abo_blood_type_and_rh_type' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // bleeding_time
        if ($v->bill_name == 'bleeding time') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'bleeding_time' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // clotting_time
        if ($v->bill_name == 'clotting time') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'clotting_time' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // mcv
        if ($v->bill_name == 'mcv') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'mcv' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // mch
        if ($v->bill_name == 'mch') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'mch' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // mchc
        if ($v->bill_name == 'mchc') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'mchc' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // rdw
        if ($v->bill_name == 'rdw') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'rdw' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // mpv
        if ($v->bill_name == 'mpv') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'mpv' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // pdw
        if ($v->bill_name == 'pdw') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'pdw' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // pct
        if ($v->bill_name == 'pct') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'pct' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        // blood_typing_with_rh
        if ($v->bill_name == 'Blood Typing W/ RH') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'blood_typing_with_rh' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // ct_bt
        if ($v->bill_name == 'CT/BT') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ct_bt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // esr
        if ($v->bill_name == 'ESR') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'esr' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // ferritin
        if ($v->bill_name == 'Ferritin') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ferritin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // aptt
        if ($v->bill_name == 'APTT') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'aptt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // peripheral_smear
        if ($v->bill_name == 'Peripheral Smear') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'peripheral_smear' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // protime
        if ($v->bill_name == 'Protime') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'protime' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // cbc //
        if ($v->bill_name == 'cbc') {
            DB::table('laboratory_cbc')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'cbc' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // cbc_platelet
        if ($v->bill_name == 'cbc platelet') {
            DB::table('laboratory_cbc')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'cbc_platelet' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newSorologyLabOrderVan($v)
    {
        if ($v->bill_name == 'Hepatitis B surface Antigen (HBsAg)') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hbsag' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'HAV (Hepatitis A Virus) IgG/IgM') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hav' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'HCV (Hepatitis C Virus)') {

            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hcv' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($v->bill_name == 'VDRL/RPR') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'vdrl_rpr' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'ANTI-HBC IGM') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'anti_hbc_igm' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'BETA HCG (QUALI)') {

            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'beta_hcg_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'TYPHIDOT') {

            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'typhidot' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'H. PYLORI') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'h_pylori' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'VDRL/SYPHILIS TEST') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'syphilis_test' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'VDRL/Syphilis Test') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'syphilis_test' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'ANA') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ana' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'DENGUE TEST') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'dengue_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newClinicMicroscopyLabOrderVan($v)
    {

        if ($v->bill_name == 'chemical test') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'chemical_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'microscopic test') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'microscopic_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'pregnancy test (HCG)') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'pregnancy_test_hcg' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Micral Test') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'micral_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Semenalysis') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'seminalysis_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Occult Blood') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'occult_blood_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newClinicChemistryLabOrderVan($v)
    {
        if ($v->bill_name == 'fbs') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'fbs' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'glucose') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'glucose' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'creatinine') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'creatinine' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'uric acid') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'uric_acid' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'cholesterol') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'cholesterol' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'triglyceride') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'triglyceride' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'hdl cholesterol') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hdl_cholesterol' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'ldl cholesterol') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ldl_cholesterol' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'SGOT') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'sgot' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'SGPT') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'sgpt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'bun') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'bun' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'soduim') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'soduim' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'potassium') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'potassium' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'hba1c') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hba1c' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'alkaline_phosphatase') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'alkaline_phosphatase' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'albumin') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'albumin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'calcium') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'calcium' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'magnesium') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'magnesium' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'chloride') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'chloride' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Serum Uric Acid') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'serum_uric_acid' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Lipid Profile') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'lipid_profile' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'LDH') {

            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ldh' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'TPAG Ratio') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'tpag_ratio' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Bilirubin (Total/Direct)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'bilirubin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Total Protein') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'total_protein' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Potassium (k+)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'potassium_kplus' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'NA+/K+') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'na_plus_kplus' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($v->bill_name == 'GGT') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ggt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Cholinesterase') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'cholinesterase' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($v->bill_name == 'Phosphorous') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'phosphorous' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'RBS') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'rbs' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'VLDL') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'vldl' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'RBC Cholinesterases') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'rbc_cholinesterase' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'CRP') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'crp' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'PRO CALCITONIN') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'pro_calcitonin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'OGCT 1 TAKE (50 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ogct_take_one_50grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'OGCT 1 TAKE (75 GRM)') {

            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ogct_take_one_75grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($v->bill_name == 'OGTT 2 TAKES (100 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ogct_take_two_100grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'OGTT 2 TAKES (75 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ogct_take_two_75grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'OGTT 3 TAKES (100 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ogct_take_three_100grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'OGTT 4 TAKES (100 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ogct_take_four_100grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newFecalAnalysisLabOrderVan($v)
    {
        if ($v->bill_name == 'fecalysis') {
            DB::table('laboratory_stooltest')->insert([
                'lf_id' => 'lf-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'fecalysis' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newPapsmearTestLabOrderVan($v)
    {
        if ($v->bill_name == 'Papsmear (Female 35yo & up)') {
            DB::table('laboratory_papsmear')->insert([
                'ps_id' => 'ps-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'papsmear_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newUrinalysisLabOrderVan($v)
    {
        if ($v->bill_name == 'urinalysis') {
            DB::table('laboratory_urinalysis')->insert([
                'lu_id' => 'lu-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'urinalysis' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newECGLabOrderVan($v)
    {
        if ($v->bill_name == 'ecg') {
            DB::table('laboratory_ecg')->insert([
                'le_id' => 'le-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ecg_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newOralGlucoseLabOrderVan($v)
    {
        if ($v->bill_name == 'Baseline') {

            DB::table('laboratory_oral_glucose')->insert([
                'log_id' => 'log-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'baseline' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'First Hour') {
            DB::table('laboratory_oral_glucose')->insert([
                'log_id' => 'log-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'first_hour' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Second Hour') {
            DB::table('laboratory_oral_glucose')->insert([
                'log_id' => 'log-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'second_hour' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newThyroidProfileLabOrderVan($v)
    {
        if ($v->bill_name == 'T3') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                't3' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'T4') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                't4' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'TSH') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'tsh' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'FT4') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ft4' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($v->bill_name == 'FT3') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ft3' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'T3T4') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                't3t4' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'FHT') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'fht' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'T3-T4-TSH') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                't3' => 'new-order',
                't4' => 'new-order',
                'tsh' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newImmunologyLabOrderVan($v)
    {
        if ($v->bill_name == 'Alpha Fetoprotein') {
            DB::table('laboratory_immunology')->insert([
                'li_id' => 'li-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'immunology_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newMiscellaneousLabOrderVan($v)
    {
        if ($v->bill_name == 'Pregnancy Test (Urine)') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'pregnancy_test_urine' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($v->bill_name == 'Pregnancy Test (Serum)') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'pregnancy_test_serum' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Papsmear') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'papsmear_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Papsmear with Gramstain') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'papsmear_test_with_gramstain' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newHepatitisProfileLabOrderVan($v)
    {
        if ($v->bill_name == 'HBsAg(Quali)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hbsag_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HBs(Quali)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antihbs_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HCV(Quali)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antihcv_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'HBsAG(Quanti)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hbsag_quanti' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HBs(Quanti)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antihbs_quanti' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'HBeAg') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'hbeaag' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HBe') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antihbe' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HBc(IgM)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antihbc_igm' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HAV(IgM)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antihav_igm' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HAVIGM/IGG') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'anti_havigm_igg' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Anti-HBc(IgG Total)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antihbc_iggtotal' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newCovid19TestLabOrderVan($v)
    {
        if ($v->bill_name == 'Covid Rapid Test - Antibody') {
            DB::table('laboratory_covid19_test')->insert([
                'lct_id' => 'lct-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'rapid_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Covid Rapid Test - Antigen') {
            DB::table('laboratory_covid19_test')->insert([
                'lct_id' => 'lct-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'antigen_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newTumorMakerTestLabOrderVan($v)
    {

        if ($v->bill_name == 'ASO') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'aso' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Biopsy') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'biopsy' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'C3') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'c3' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'CA 125 (OVARY)') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'ca_125' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'CEA') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'cea' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'PSA (PROSTATE)') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'psa_prostate' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'AFP') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'afp' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newDrugTestTestLabOrderVan($v)
    {
        if ($v->bill_name == 'Drug Test (2 Panels)') {
            DB::table('laboratory_drug_test')->insert([
                'ldt_id' => 'ldt-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'two_panels' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Drug Test (3 Panels)') {
            DB::table('laboratory_drug_test')->insert([
                'ldt_id' => 'ldt-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'three_panels' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($v->bill_name == 'Drug Test (5 Panels)') {
            DB::table('laboratory_drug_test')->insert([
                'ldt_id' => 'ldt-' . rand(0, 9999) . '-' . time(),
                'order_id' => $v->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => $v->laboratory_id,
                'five_panels' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

}