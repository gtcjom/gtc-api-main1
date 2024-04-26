<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\_Cashier;

class _LaboratoryOrderPackage extends Model
{
    public static function newHemathologyOrderPackage($k, $v, $getLaboratoryId)
    {
        // hemoglobin
        if ($k->order_name == 'hemoglobin') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hemoglobin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // hematocrit
        if ($k->order_name == 'hematocrit') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hematocrit' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // rbc
        if ($k->order_name == 'rbc') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'rbc' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // wbc
        if ($k->order_name == 'wbc') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'wbc' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // platelet_count
        if ($k->order_name == 'platelet count') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'platelet_count' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // differential_count
        if ($k->order_name == 'differential count') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'differential_count' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // neutrophil
        if ($k->order_name == 'neutrophil') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'neutrophil' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // lymphocyte
        if ($k->order_name == 'lymphocyte') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'lymphocyte' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'monocyte') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'monocyte' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // eosinophil
        if ($k->order_name == 'eosinophil') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'eosinophil' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // basophil
        if ($k->order_name == 'basophil') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'basophil' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // bands
        if ($k->order_name == 'bands') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'bands' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // abo_blood_type_and_rh_type
        if ($k->order_name == 'abo blood type / rh type') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'abo_blood_type_and_rh_type' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // bleeding_time
        if ($k->order_name == 'bleeding time') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'bleeding_time' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // clotting_time
        if ($k->order_name == 'clotting time') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'clotting_time' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // mcv
        if ($k->order_name == 'mcv') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'mcv' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // mch
        if ($k->order_name == 'mch') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'mch' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // mchc
        if ($k->order_name == 'mchc') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'mchc' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // rdw
        if ($k->order_name == 'rdw') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'rdw' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // mpv
        if ($k->order_name == 'mpv') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'mpv' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // pdw
        if ($k->order_name == 'pdw') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'pdw' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // pct
        if ($k->order_name == 'pct') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'pct' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        // blood_typing_with_rh
        if ($k->order_name == 'Blood Typing W/ RH') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'blood_typing_with_rh' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // ct_bt
        if ($k->order_name == 'CT/BT') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ct_bt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // esr
        if ($k->order_name == 'ESR') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'esr' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // ferritin
        if ($k->order_name == 'Ferritin') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ferritin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }
        // aptt
        if ($k->order_name == 'APTT') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'aptt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // peripheral_smear
        if ($k->order_name == 'Peripheral Smear') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'peripheral_smear' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // protime
        if ($k->order_name == 'Protime') {
            DB::table('laboratory_hematology')->insert([
                'lh_id' => 'lh-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'protime' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // cbc //
        if ($k->order_name == 'cbc') {
            DB::table('laboratory_cbc')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'cbc' => $k->order_name == 'cbc' ? 1 : null,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        // cbc_platelet
        if ($k->order_name == 'cbc platelet') {
            DB::table('laboratory_cbc')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newSorologyOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'Hepatitis B surface Antigen (HBsAg)') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hbsag' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'HAV (Hepatitis A Virus) IgG/IgM') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hav' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'HCV (Hepatitis C Virus)') {

            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hcv' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($k->order_name == 'VDRL/RPR') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'vdrl_rpr' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'ANTI-HBC IGM') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'anti_hbc_igm' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'BETA HCG (QUALI)') {

            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'beta_hcg_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'TYPHIDOT') {

            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'typhidot' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'H. PYLORI') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'h_pylori' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'VDRL/SYPHILIS TEST') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'syphilis_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'VDRL/Syphilis Test') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'syphilis_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'ANA') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ana' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'DENGUE TEST') {
            DB::table('laboratory_sorology')->insert([
                'ls_id' => 'ls-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newClinicMicroscopyOrderPackage($k, $v, $getLaboratoryId)
    {

        if ($k->order_name == 'chemical test') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'chemical_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'microscopic test') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'microscopic_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'pregnancy test (HCG)') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'pregnancy_test_hcg' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Micral Test') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'micral_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Semenalysis') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'seminalysis_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Occult Blood') {
            DB::table('laboratory_microscopy')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newFecalAnalysisOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'fecalysis') {
            DB::table('laboratory_stooltest')->insert([
                'lf_id' => 'lf-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newClinicChemistryOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'fbs') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'fbs' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'glucose') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'glucose' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'creatinine') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'creatinine' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'uric acid') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'uric_acid' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'cholesterol') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'cholesterol' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'triglyceride') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'triglyceride' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'hdl cholesterol') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hdl_cholesterol' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'ldl cholesterol') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ldl_cholesterol' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'SGOT') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'sgot' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'SGPT') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'sgpt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'bun') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'bun' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'soduim') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'soduim' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }
        if ($k->order_name == 'potassium') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'potassium' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }
        if ($k->order_name == 'hba1c') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hba1c' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'alkaline_phosphatase') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'alkaline_phosphatase' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'albumin') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'albumin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'calcium') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'calcium' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }
        if ($k->order_name == 'magnesium') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'magnesium' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }
        if ($k->order_name == 'chloride') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'chloride' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }

        if ($k->order_name == 'Serum Uric Acid') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'serum_uric_acid' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Lipid Profile') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'lipid_profile' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'LDH') {

            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ldh' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'TPAG Ratio') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'tpag_ratio' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Bilirubin (Total/Direct)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'bilirubin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Total Protein') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'total_protein' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Potassium (k+)') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'potassium_kplus' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }

        if ($k->order_name == 'NA+/K+') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'na_plus_kplus' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }

        if ($k->order_name == 'GGT') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ggt' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Cholinesterase') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'cholinesterase' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($k->order_name == 'Phosphorous') {
            _LaboratoryOrderPackage::insertElectroIfNotExist($v, $data);

            // DB::table('laboratory_chemistry')->insert([
            //     'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
            //     'order_id' => $k->order_id,
            //     'patient_id' => $v->patient_id,
            //     'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            //     'phosphorous' => 'new-order',
            //     'order_status' => 'new-order-paid',
            //     'trace_number' => $v->trace_number,
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            // ]);
        }

        if ($k->order_name == 'RBS') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'rbs' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'VLDL') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'vldl' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'RBC Cholinesterases') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'rbc_cholinesterase' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'CRP') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'crp' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'PRO CALCITONIN') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'pro_calcitonin' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'OGCT 1 TAKE (50 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ogct_take_one_50grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'OGCT 1 TAKE (75 GRM)') {

            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ogct_take_one_75grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($k->order_name == 'OGTT 2 TAKES (100 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ogct_take_two_100grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'OGTT 2 TAKES (75 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ogct_take_two_75grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'OGTT 3 TAKES (100 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ogct_take_three_100grm' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'OGTT 4 TAKES (100 GRM)') {
            DB::table('laboratory_chemistry')->insert([
                'lc_id' => 'lc-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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
                // 'order_id' => $k->order_id,
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

    public static function newECGOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'ecg') {
            DB::table('laboratory_ecg')->insert([
                'le_id' => 'le-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newUrinalysisOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'urinalysis') {
            DB::table('laboratory_urinalysis')->insert([
                'lu_id' => 'lu-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newMedicalExamOrderPackage($k, $v, $getLaboratoryId)
    {

        DB::table('laboratory_medical_exam')->insert([
            'lme_id' => 'lme-' . rand(0, 9999) . '-' . time(),
            'order_id' => $k->order_id,
            'patient_id' => $v->patient_id,
            'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
            'medical_exam' => 1,
            'order_status' => 'new-order-paid',
            'trace_number' => $v->trace_number,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return true;
    }

    public static function newPapsmearTestOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'Papsmear (Female 35yo & up)') {
            DB::table('laboratory_papsmear')->insert([
                'ps_id' => 'ps-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newOralGlucoseOrderPackage($k, $v, $getLaboratoryId)
    {

        if ($k->order_name == 'Baseline') {

            DB::table('laboratory_oral_glucose')->insert([
                'log_id' => 'log-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'baseline' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'First Hour') {
            DB::table('laboratory_oral_glucose')->insert([
                'log_id' => 'log-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'first_hour' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Second Hour') {
            DB::table('laboratory_oral_glucose')->insert([
                'log_id' => 'log-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newThyroidProfileOrderPackage($k, $v, $getLaboratoryId)
    {

        if ($k->order_name == 'T3') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

                't3' => 'new-order',

                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'T4') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

                't4' => 'new-order',

                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'TSH') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

                'tsh' => 'new-order',

                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'FT4') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

                'ft4' => 'new-order',

                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($k->order_name == 'FT3') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

                'ft3' => 'new-order',

                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'T3T4') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

                't3t4' => 'new-order',

                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'FHT') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

                'fht' => 'new-order',

                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'T3-T4-TSH') {
            DB::table('laboratory_thyroid_profile')->insert([
                'ltp_id' => 'ltp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

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

    public static function newImmunologyOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'Alpha Fetoprotein') {
            DB::table('laboratory_immunology')->insert([
                'li_id' => 'li-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

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

    public static function newMiscellaneousOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'Pregnancy Test (Urine)') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'pregnancy_test_urine' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

        }

        if ($k->order_name == 'Pregnancy Test (Serum)') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'pregnancy_test_serum' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Papsmear') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'papsmear_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Papsmear with Gramstain') {
            DB::table('laboratory_miscellaneous')->insert([
                'lm_id' => 'lm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,

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

    public static function newHepatitisProfileOrderPackage($k, $v, $getLaboratoryId)
    {

        if ($k->order_name == 'HBsAg(Quali)') {

            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hbsag_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HBs(Quali)') {

            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'antihbs_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HCV(Quali)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'antihcv_quali' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'HBsAG(Quanti)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hbsag_quanti' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HBs(Quanti)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'antihbs_quanti' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'HBeAg') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'hbeaag' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HBe') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'antihbe' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HBc(IgM)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'antihbc_igm' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HAV(IgM)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'antihav_igm' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HAVIGM/IGG') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'anti_havigm_igg' => 'new-order',
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Anti-HBc(IgG Total)') {
            DB::table('laboratory_hepatitis_profile')->insert([
                'lhp_id' => 'lhp-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newCovid19TestOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'Covid Rapid Test - Antibody') {
            DB::table('laboratory_covid19_test')->insert([
                'lct_id' => 'lct-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'rapid_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Covid Rapid Test - Antigen') {

            DB::table('laboratory_covid19_test')->insert([
                'lct_id' => 'lct-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newTumorMakerTestOrderPackage($k, $v, $getLaboratoryId)
    {

        if ($k->order_name == 'ASO') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'aso' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Biopsy') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'biopsy' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'C3') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'c3' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'CA 125 (OVARY)') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'ca_125' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'CEA') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'cea' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'PSA (PROSTATE)') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'psa_prostate' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'AFP') {
            DB::table('laboratory_tumor_maker')->insert([
                'ltm_id' => 'ltm-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newDrugTestTestOrderPackage($k, $v, $getLaboratoryId)
    {
        if ($k->order_name == 'Drug Test (2 Panels)') {
            DB::table('laboratory_drug_test')->insert([
                'ldt_id' => 'ldt-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'two_panels' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Drug Test (3 Panels)') {
            DB::table('laboratory_drug_test')->insert([
                'ldt_id' => 'ldt-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
                'three_panels' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Drug Test (5 Panels)') {
            DB::table('laboratory_drug_test')->insert([
                'ldt_id' => 'ldt-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'laboratory_id' => count($getLaboratoryId) > 0 ? $getLaboratoryId[0]->laboratory_id : null,
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

    public static function newPsychologyOrderPackage($k, $v, $data)
    {
        if ($k->order_name == 'Ishihara') {
            DB::table('psychology_ishihara')->insert([
                'pi_id' => 'pi-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
                'ishihara_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Audiometry') {
            DB::table('psychology_audiometry')->insert([
                'pa_id' => 'pa-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
                'audiometry_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($k->order_name == 'Neuro Examination') {
            DB::table('psychology_neuroexam')->insert([
                'pn_id' => 'pn-' . rand(0, 9999) . '-' . time(),
                'order_id' => $k->order_id,
                'patient_id' => $v->patient_id,
                'psychology_id' => _Cashier::getPsychologyIdByMgt($data)->psycho_id,
                'neuroexam_test' => 1,
                'order_status' => 'new-order-paid',
                'trace_number' => $v->trace_number,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return true;
    }

    public static function newOtherTestPackage($k, $v, $getLaboratoryId, $data)
    {
        $patient_userid = DB::table('patients')->select('user_id')->where('patient_id', $v->patient_id)->first();
        $doctor_userid = DB::table('doctors')->select('user_id')->where('doctors_id', $data['doctor'])->first();

        if ($k->order_name == 'Physical Examination') {
            // _Cashier::doctorCountQueue($data);

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
            // _Cashier::doctorCountQueue($data);

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

}
