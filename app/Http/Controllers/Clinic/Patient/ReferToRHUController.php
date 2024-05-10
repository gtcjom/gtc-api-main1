<?php

namespace App\Http\Controllers\Clinic\Patient;

use App\Events\AppointmentEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentDataResource;
use App\Models\AnesthesiaOrder;
use App\Models\AppointmentData;
use App\Models\LaboratoryOrder;
use App\Models\HealthUnit;
use App\Models\ItemInventory;
use App\Models\ItemUsage;
use App\Models\OperationProcedure;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\User;
use App\Models\Vital;
use App\Services\Cloud\PhoPatientCaseService;
use App\Services\Cloud\PmrfPatientService;
use App\Services\Cloud\SendToCloudService;
use App\Services\DiseaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReferToRHUController extends Controller
{


    public function getUserLocation()
    {
        $user = request()->user();
        $location = HealthUnit::first();
        if ($user->health_unit_id) {
            $location = HealthUnit::findOrFail($user->health_unit_id);
        } else {

            if (str_contains($user->type, 'RHU')) {
                $location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
            }
            if ($user->type == 'LMIS-BHS' || $user->type == 'BHS-BHW') {
                $location = HealthUnit::query()->where('type', '=', 'BHS')->where('barangay_id', '=', $user->barangay)->first();
            }
            if ($user->type == 'LMIS-CNOR') {
                $location = HealthUnit::query()->where('type', 'CNOR')->first();
            }
        }

        return $location;
    }


    public function approve(int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->status = 'approved';
        $appointment->save();


        return response()->json([
            'message' => 'Patient referred to RHU'
        ]);
    }
    public function acceptPatient(string $id, Request $request, PmrfPatientService $pmrfPatientService, PhoPatientCaseService $service)
    {
        $user = request()->user();

        $localCase = $service->downLoadAndSaveCase($id);

        if (!$localCase['success']) {
            return response()->json([
                'message' => $localCase['message']
            ], 407);
        }

        $service->updateCaseCloud($id, [
            'referral_accepted' => 1
        ]);


        $localCase = $localCase['data'];


        $patient = Patient::query()->where('cloud_id', $localCase->patient_cloud_id)->first();
        if (!$patient) {
            $cloud = $pmrfPatientService->getCloud([
                'id' => $localCase->patient_cloud_id,
                'pmrf_status' => 'done'
            ]);

            if ($cloud['total_count'] < 1) {

                return response()->json([
                    'message' => 'Patient not found not done yet'
                ], 404);
            }

            $patient = new Patient();
            $patient->fill((array)$cloud['data'][0]);
            $patient->cloud_id = $cloud['data'][0]->id;
            $patient->save();
        }


        $vitals = $localCase->vitals ? json_decode($localCase->vitals)[0] : [];


        $vital = new Vital();
        $vital->patient_id = $patient->id;
        $vital->temperature = $vitals->temperature;
        $vital->pulse = $vitals->pulse;
        $vital->blood_systolic = $vitals->blood_systolic;
        $vital->blood_diastolic = $vitals->blood_diastolic;
        $vital->respiratory = $vitals->respiratory;
        $vital->height = $vitals->height;
        $vital->weight = $vitals->weight;
        $vital->glucose = $vitals->glucose;
        $vital->uric_acid = $vitals->uric_acid;
        $vital->cholesterol = $vitals->cholesterol;
        $vital->bmi = $vitals->bmi;
        $vital->height_for_age = $vitals->height_for_age;
        $vital->weight_for_age = $vitals->weight_for_age;
        $vital->bloody_type = $vitals->bloody_type;
        $vital->oxygen_saturation = $vitals->oxygen_saturation;
        $vital->heart_rate = $vitals->heart_rate;
        $vital->regular_rhythm = $vitals->regular_rhythm;
        $vital->covid_19 = $vitals->covid_19;
        $vital->tb = $vitals->tb;
        $vital->save();


        $localCase = PatientCase::find($localCase->id);

        $referral_data = json_decode($localCase->referral_data, true);



        //
        //        $referral_data = [
        //            'patient_cloud_id' => $patient_cloud_id,
        //            'doctor_id' => $request->get('doctor_id'),
        //            'date' => $request->get('date'),
        //            'time' => $request->get('time'),
        //            'chief_complaint' => $request->get('pre_notes'),
        //            'reason_for_referral' => $request->get('reason'),
        //            'clinical_history' => $request->get('clinical_history'),
        //            'lab_findings' => $request->get('lab_findings'),
        //            'impression' => $request->get('impression'),
        //            'action_taken' => $request->get('action_taken'),
        //            'health_insurrance_coverage' => $request->get('health_insurrance_coverage'),
        //            'health_insurrance_coverage_if_yes_type' => $request->get('health_insurrance_coverage_if_yes_type'),
        //            'referred_by' => request()->user()->id->name ?? "",
        //        ]


        $appointment = new AppointmentData();
        $appointment->rhu_id = $localCase->health_unit_id;
        $appointment->status = 'pending-doctor-consultation';
        $appointment->referred_to = $request->get('doctor_id');
        $appointment->patient_id = $patient->id;
        $appointment->room_number = $request->get('room_number');
        $appointment->vital_id = $vital->id;
        $appointment->health_insurrance_coverage = $referral_data['health_insurrance_coverage'];
        $appointment->health_insurrance_coverage_if_yes_type = $referral_data['health_insurrance_coverage_if_yes_type'];
        $appointment->action_taken = $referral_data['action_taken'];
        $appointment->impression = $referral_data['impression'];
        $appointment->lab_findings = $referral_data['lab_findings'];
        $appointment->clinical_history = $referral_data['clinical_history'];
        $appointment->pre_notes = $referral_data['chief_complaint'];
        $appointment->reason = $referral_data['reason_for_referral'];
        $appointment->referred_by_name = $referral_data['referred_by'];
        $appointment->patient_selfie = $localCase->case_picture;



        $appointment->save();

        $localCase->appointment_id = $appointment->id;
        $localCase->save();





        /*$appointment = AppointmentData::query()->findOrFail($id);

        $appointment->accepted_by_id = $user->id;
        $appointment->rhu_id = $this->getRhuId()->id;
        $appointment->status = 'pending-doctor-consultation';
        $appointment->referred_to = $request->get('doctor_id');
        $appointment->room_number = $request->get('room_number');

        $appointment->save();*/


        return response()->json([
            'message' => 'Patient accepted!'
        ]);
    }

    public function doctorServePatient(int $id, Request $request)
    {
        $user = request()->user();
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->accepted_by_id = $user->id;
        $appointment->rhu_id = $this->getRhuId()->id;
        $appointment->status = 'in-sevince-doctor-consultation';
        $appointment->referred_to = $request->get('doctor_id');
        $appointment->room_number = $request->get('room_number');

        $appointment->save();


        return response()->json([
            'message' => 'Patient accepted!'
        ]);
    }

    public function getRhuQueue()
    {
        $user = request()->user();
        $appointments = AppointmentData::query()

            ->where('bhs_id', 0)
            ->where('rhu_id', $user->health_unit_id)
            ->whereIn('status', ['pending-doctor-consultation', 'pending'])->latest()
            ->get();

        return AppointmentDataResource::collection($appointments->load([
            'patient',
            'bhs',
            'rhu',
            'tb_symptoms',
            'vitals',
            'socialHistory',
            'environmentalHistory',
        ]));
    }


    public function getSPHQueue()
    {
        $user = request()->user();
        $appointments = AppointmentData::query()

            ->where('for_sph', 1)
            ->whereIn('status', ['pending-doctor-consultation', 'pending'])->latest()
            ->get();

        return AppointmentDataResource::collection($appointments->load([
            'patient',
            'bhs',
            'rhu',
            'tb_symptoms',
            'vitals',
            'socialHistory',
            'environmentalHistory',
        ]));
    }

    public function getMyPendingForConfirmation()
    {
        $user = request()->user();
        $appointments = AppointmentData::query()
            ->where('status', 'pending-doctor-confirmation')
            ->where('referred_to', $user->id)
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getPendingForDoctorPrescription()
    {

        $user = request()->user();
        $appointments = AppointmentData::query()
            ->where('status', 'pending-for-pharmacy-release')
            ->where('referred_to', $user->id)
            ->whereNull('prescribed_by')
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }
    public function getCashierPending()
    {
        $appointments = AppointmentData::query()
            ->where('for_cashier', 1)
            ->with(['patient'])
            ->get();
        return AppointmentDataResource::collection($appointments->load([
            'patient',
            'bhs',
            'rhu',
            'tb_symptoms',
            'vitals',
            'socialHistory',
            'environmentalHistory',
        ]));
    }
    public function getForResultReading()
    {
        $user = request()->user();
        $ids = LaboratoryOrder::query()
            ->where('doctor_id', $user->id)->where('order_status', 'for-result-reading')
            ->pluck('appointment_id');
        $appointments = AppointmentData::query()
            ->whereIn('id', $ids)
            ->whereNot('status', 'pending-for-pharmacy-medicine-release')
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }




    public function getNursePendingForAcceptance()
    {
        $user = request()->user();
        $location = $this->getUserLocation();
        if ($user->type == 'SPH-NURSE') {
            $appointments = AppointmentData::query()
                ->where('status', 'pending')
                ->where('for_sph', 1)
                // ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->get();
        } else {



            //create cloud client
            $client = new \GuzzleHttp\Client();
            $url = config('app.cloud_url') . '/api/tb-pending-for-acceptance?entity=' . config('app.entity') . '&entity_key=' . config('app.entity_key') . '&entity_unit=' . config('app.entity_unit');


            $response = $client->request('GET', $url);
            $appointments = json_decode($response->getBody()->getContents());



            $appointments = AppointmentData::query()
                ->where('status', 'pending-for-acceptance')
                ->where('rhu_id', $location->id)
                ->where('for_sph', 0)
                // ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->get();
        }

        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getMyPendingForConsultation()
    {
        $user = request()->user();
        // return $user->id;
        $appointments = AppointmentData::query()
            ->where('status', 'pending-doctor-consultation')
            ->where('referred_to', $user->id)
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }


    public function getInServiceConsultation()
    {
        $user = request()->user();
        // return $user->id;
        if ($user->type == 'RHU-DOCTOR') {

            $appointments = AppointmentData::query()
                ->whereIn('status', ['in-service-consultation', 'in-service-result-reading'])
                ->where('referred_to', $user->id)
                ->with(['patient'])
                ->get();
        } else if ($user->type == 'RHU-NURSE') {
            $appointments = AppointmentData::query()
                ->whereIn('status', ['in-service-consultation', 'in-service-result-reading'])
                ->where('rhu_id', $user->health_unit_id)
                ->with(['patient'])
                ->get();
        } else {
            $appointments = AppointmentData::query()
                ->whereIn('status', ['in-service-consultation', 'in-service-result-reading'])
                // ->where('for_sph', 1)
                ->with(['patient'])
                ->get();
        }
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getPendingForReadLabResult()
    {
        $user = request()->user();
        $appointments = AppointmentData::query()
            ->where('status', 'pending-for-doctor-read-lab-result')
            ->where('referred_to', $user->id)
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getPharmaPendingSignalRelease()
    {
        $user = request()->user();
        $location = $this->getUserLocation();
        if ($location->id == 1) {
            $appointments = AppointmentData::query()
                ->where('status', 'pending-for-pharmacy-release')
                // ->where('rhu_id', $location->id)
                ->whereNotNull('prescribed_by')
                ->where('for_sph', 1)
                ->with(['patient'])
                ->get();
        } else {
            $appointments = AppointmentData::query()
                ->where('status', 'pending-for-pharmacy-release')
                ->where('rhu_id', $user->health_unit_id)
                ->whereNotNull('prescribed_by')
                ->where('for_sph', 0)
                ->with(['patient'])
                ->latest()
                ->get();
        }
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }
    public function getPharmaPendingMedRelease()
    {
        $user = request()->user();
        $location = $this->getUserLocation();
        if ($user->health_unit_id == 1) {
            $appointments = AppointmentData::query()
                ->where('status', 'pending-for-pharmacy-medicine-release')
                ->where('for_sph', 1)
                ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->latest()
                ->get();
        } else {

            $appointments = AppointmentData::query()
                ->where('status', 'pending-for-pharmacy-medicine-release')
                ->where('rhu_id', $user->health_unit_id)
                ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->latest()
                ->get();
        }
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getAppointmentData($id)
    {
        // $user = request()->user();
        $appointments = AppointmentData::query()
            ->findOrFail($id);
        return response()->json([
            'data' => AppointmentDataResource::make($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getBHWPendingService()
    {
        // $user = request()->user();
        $location = $this->getUserLocation();
        $appointments = AppointmentData::query()
            ->where('status', 'pending')
            ->where('bhs_id', $location->id)
            ->whereNull('serviced_by')
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getBhwPendingMedsRelease()
    {
        // $user = request()->user();
        $location = $this->getUserLocation();
        $appointments = AppointmentData::query()
            ->where('status', 'pending-for-bhw-release')
            ->where('bhs_id', $location->id)
            ->whereNotNull('prescribed_by')
            ->whereNotNull('approved_by')
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }
    public function getRHUPendingMedsRelease()
    {
        // $user = request()->user();
        $location = $this->getUserLocation();
        $appointments = AppointmentData::query()
            ->where('status', 'pending-for-rhu-release')
            ->where('rhu_id', $location->id)
            ->whereNotNull('prescribed_by')
            ->whereNotNull('approved_by')
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getSPHPendingMedsRelease()
    {
        // $user = request()->user();
        $location = $this->getUserLocation();
        $appointments = AppointmentData::query()
            ->where('status', 'pending-for-sph-release')
            ->where('for_sph', 1)
            ->whereNotNull('prescribed_by')
            ->whereNotNull('approved_by')
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }


    public function getRhuId()
    {
        $user = request()->user();
        $location = HealthUnit::first();
        if ($user->health_unit_id) {
            $location = HealthUnit::findOrFail($user->health_unit_id);
        } else {

            if (str_contains($user->type, 'RHU')) {
                $location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
            }
            if ($user->type == 'LMIS-BHS' || $user->type == 'BHS-BHW') {
                $location = HealthUnit::query()->where('type', '=', 'BHS')->where('barangay_id', '=', $user->barangay)->first();
            }
            if ($user->type == 'LMIS-CNOR') {
                $location = HealthUnit::query()->where('type', 'CNOR')->first();
            }
        }

        return $location;
    }
    public function uploadOperatingProcedure(Request $request, int $id)
    {
        $user = request()->user();
        // $anesthesiaOrder = OperationProcedure::query()->findOrFail($id);
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->for_anesthesia = 0;
        $appointment->status = 'for-operating-procedure';
        // $appointment->referred_to = $request->get('doctor_id');
        $appointment->operation_date = $request->get('operation_date');
        $appointment->operation_time = $request->get('operation_time');
        $appointment->procedure = $request->get('procedure');
        $appointment->surgeon_assign = $request->get('surgeon_assign');
        $appointment->anesthesiologist_assign = $request->get('anesthesiologist_assign');
        $appointment->operation_status = $request->get('operation_status');
        $appointment->processed_by = $user->id;
        $appointment->save();


        return response()->json([
            'message' => 'Success'
        ]);
    }

    public function uploadXray(Request $request, int $id)
    {
        $user = request()->user();
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->for_xray = 0;
        $appointment->status = 'pending-for-doctor-read-lab-result';
        // $appointment->referred_to = $request->get('doctor_id');
        $appointment->xray_result = $request->file('xray')->store('xrays');
        $appointment->xray_result_by = $user->id;
        $appointment->save();


        return response()->json([
            'message' => 'Success'
        ]);
    }
    public function updateLabTest(Request $request, int $id)
    {
        $user = request()->user();

        $labOrder = LaboratoryOrder::query()->findOrFail($id);
        $appointment = AppointmentData::query()->findOrFail($labOrder->appointment_id);

        $appointment->for_lab = 0;
        // $appointment->referred_to = $request->get('doctor_id');

        //chemistry
        $appointment->fbs = $request->get('fbs');
        $appointment->rbs = $request->get('rbs');
        $appointment->creatinine = $request->get('creatinine');
        $appointment->uric_acid = $request->get('uric_acid');
        $appointment->sgot = $request->get('sgot');
        $appointment->sgpt = $request->get('sgpt');
        $appointment->alkaline_phos = $request->get('alkaline_phos');
        $appointment->ldh = $request->get('ldh');
        $appointment->ggt = $request->get('ggt');
        $appointment->magnesium = $request->get('magnesium');
        $appointment->phophorus = $request->get('phophorus');
        $appointment->amylase = $request->get('amylase');
        $appointment->csir_specimen_type = $request->get('csir_specimen_type');
        $appointment->csir_specimen_source = $request->get('csir_specimen_source');
        $appointment->csir_result = $request->get('csir_result');
        $appointment->csir_remarks = $request->get('csir_remarks');
        $appointment->cholesterol = $request->get('cholesterol');
        $appointment->triglyceride = $request->get('triglyceride');
        $appointment->hdl = $request->get('hdl');
        $appointment->ldl = $request->get('ldl');
        $appointment->hbac = $request->get('hbac');
        $appointment->sodium = $request->get('sodium');
        $appointment->potassium = $request->get('potassium');
        $appointment->calcium_total = $request->get('calcium_total');
        $appointment->calcium_ionized = $request->get('calcium_ionized');
        $appointment->ph = $request->get('ph');
        $appointment->chloride = $request->get('chloride');
        $appointment->total_bilirubin = $request->get('total_bilirubin');
        $appointment->direct_bilirubin = $request->get('direct_bilirubin');
        $appointment->indirect_bilirubin = $request->get('indirect_bilirubin');
        $appointment->total_protein = $request->get('total_protein');
        $appointment->albumin = $request->get('albumin');
        $appointment->globulin = $request->get('globulin');
        $appointment->ag_ratio = $request->get('ag_ratio');
        $appointment->urea = $request->get('urea');
        $appointment->glucose_load = $request->get('glucose_load');
        $appointment->blood_fbs = $request->get('blood_fbs');
        $appointment->blood_first_hour = $request->get('blood_first_hour');
        $appointment->blood_second_hour = $request->get('blood_second_hour');
        $appointment->blood_third_hour = $request->get('blood_third_hour');
        $appointment->urine_fasting = $request->get('urine_fasting');
        $appointment->urine_first_hour = $request->get('urine_first_hour');
        $appointment->urine_second_hour = $request->get('urine_second_hour');
        $appointment->urine_third_hour = $request->get('urine_third_hour');
        $appointment->gogct_result = $request->get('gogct_result');
        $appointment->ogtt_remark = $request->get('ogtt_remark');
        $appointment->hour_urine_volume = $request->get('hour_urine_volume');
        $appointment->serum_creatinine = $request->get('serum_creatinine');
        $appointment->urine_creatinine = $request->get('urine_creatinine');
        $appointment->hours_urine = $request->get('hours_urine');
        $appointment->creatinine_clearance = $request->get('creatinine_clearance');
        //hematology
        $appointment->hemoglobin = $request->get('hemoglobin');
        $appointment->hematocrit = $request->get('hematocrit');
        $appointment->rcbc = $request->get('rcbc');
        $appointment->wbc = $request->get('wbc');
        $appointment->mcv_hematology = $request->get('mcv_hematology');
        $appointment->mch_hematology = $request->get('mch_hematology');
        $appointment->mchc_hematology = $request->get('mchc_hematology');
        $appointment->erythrocyte_arte_hematology = $request->get('erythrocyte_arte_hematology');
        $appointment->neutrophils_hematology = $request->get('neutrophils_hematology');
        $appointment->lymphocytes_hematology = $request->get('lymphocytes_hematology');
        $appointment->monocytes_hematology = $request->get('monocytes_hematology');
        $appointment->eosinophils_hematology = $request->get('eosinophils_hematology');
        $appointment->basophils_hematology = $request->get('basophils_hematology');
        $appointment->platelet_count_hematology = $request->get('platelet_count_hematology');
        $appointment->clotting_time_hematology = $request->get('clotting_time_hematology');
        $appointment->bleeding_time_hematology = $request->get('bleeding_time_hematology');
        $appointment->reticulocyte_count_hematology = $request->get('reticulocyte_count_hematology');
        $appointment->pt_protime_hematology = $request->get('pt_protime_hematology');
        $appointment->control_protime_hematology = $request->get('control_protime_hematology');
        $appointment->activity_protime_hematology = $request->get('activity_protime_hematology');
        $appointment->ptt_aptt_hematology = $request->get('ptt_aptt_hematology');
        $appointment->control_aptt_hematology = $request->get('control_aptt_hematology');
        $appointment->ratio_aptt_hematology = $request->get('ratio_aptt_hematology');
        $appointment->inr_hematology = $request->get('inr_hematology');
        //microbiology
        $appointment->specimen_microbiology = $request->get('specimen_microbiology');
        $appointment->source_microbiology = $request->get('source_microbiology');
        $appointment->result_microbiology = $request->get('result_microbiology');
        $appointment->culture_isolate = $request->get('culture_isolate');
        $appointment->sensitive = $request->get('sensitive');
        $appointment->resistant = $request->get('resistant');
        $appointment->intermediate = $request->get('intermediate');
        $appointment->specimen_gram = $request->get('specimen_gram');
        $appointment->result_gram = $request->get('result_gram');
        $appointment->epithelial_cells = $request->get('epithelial_cells');
        $appointment->polymorphonuclears = $request->get('polymorphonuclears');
        $appointment->remarks_gram = $request->get('remarks_gram');
        $appointment->specimen_afb = $request->get('specimen_afb');
        $appointment->result_afb = $request->get('result_afb');
        $appointment->remarks_afb = $request->get('remarks_afb');
        $appointment->specimen_koh = $request->get('specimen_koh');
        $appointment->result_koh = $request->get('result_koh');
        $appointment->remarks_koh = $request->get('remarks_koh');
        //microscopy
        $appointment->color_fecalysis = $request->get('color_fecalysis');
        $appointment->consistency_fecalysis = $request->get('consistency_fecalysis');
        $appointment->rbc_fecalysis = $request->get('rbc_fecalysis');
        $appointment->wbc_fecalysis = $request->get('wbc_fecalysis');
        $appointment->fat_globules_fecalysis = $request->get('fat_globules_fecalysis');
        $appointment->yeast_cells_fecalysis = $request->get('yeast_cells_fecalysis');
        $appointment->fecal_occult_blood_fecalysis = $request->get('fecal_occult_blood_fecalysis');
        $appointment->ascaris_lumbricoides = $request->get('ascaris_lumbricoides');
        $appointment->triciuris_trichiura = $request->get('triciuris_trichiura');
        $appointment->hookworm = $request->get('hookworm');
        $appointment->entamoeba_histolytica_cyst = $request->get('entamoeba_histolytica_cyst');
        $appointment->entamoeba_histolytica_trophozoite = $request->get('entamoeba_histolytica_trophozoite');
        $appointment->entamoeba_coli_cyst = $request->get('entamoeba_coli_cyst');
        $appointment->entamoeba_coli_trophozoite = $request->get('entamoeba_coli_trophozoite');
        $appointment->giardia_lamblia_cyst = $request->get('giardia_lamblia_cyst');
        $appointment->giardia_lamblia_trophozoite = $request->get('giardia_lamblia_trophozoite');

        $appointment->color_urine = $request->get('color_urine');
        $appointment->transparency_urine = $request->get('transparency_urine');
        $appointment->reaction_urine = $request->get('reaction_urine');
        $appointment->gravity_urine = $request->get('gravity_urine');
        $appointment->glucose_urine = $request->get('glucose_urine');
        $appointment->protein_urine = $request->get('protein_urine');
        $appointment->wbc_urine = $request->get('wbc_urine');
        $appointment->rbc_urine = $request->get('rbc_urine');
        $appointment->epithelial_cells_urine = $request->get('epithelial_cells_urine');
        $appointment->bacteria_urine = $request->get('bacteria_urine');
        $appointment->mucus_thread_urine = $request->get('mucus_thread_urine');
        $appointment->amorphous_urates_urine = $request->get('amorphous_urates_urine');
        $appointment->amorphous_phosphates_urine = $request->get('amorphous_phosphates_urine');
        $appointment->calciun_oxalates_urine = $request->get('calciun_oxalates_urine');
        $appointment->triple_phosphates_urine = $request->get('triple_phosphates_urine');
        $appointment->uric_acid_urine = $request->get('uric_acid_urine');
        $appointment->others_urine = $request->get('others_urine');
        $appointment->hyaline_cast_urine = $request->get('hyaline_cast_urine');
        $appointment->wbc_cast_urine = $request->get('wbc_cast_urine');
        $appointment->rbc_cast_urine = $request->get('rbc_cast_urine');
        $appointment->granular_cast_urine = $request->get('granular_cast_urine');
        //serology
        $appointment->hbsag = $request->get('hbsag');
        $appointment->anti_hbs = $request->get('anti_hbs');
        $appointment->anti_hcv = $request->get('anti_hcv');
        $appointment->syphilis = $request->get('syphilis');
        $appointment->aso = $request->get('aso');
        $appointment->ra_rf = $request->get('ra_rf');
        $appointment->crp = $request->get('crp');
        $appointment->troponin = $request->get('troponin');
        $appointment->ck_mb = $request->get('ck_mb');
        $appointment->salmonella_typhi_h = $request->get('salmonella_typhi_h');
        $appointment->salmonella_typhi_ah = $request->get('salmonella_typhi_ah');
        $appointment->salmonella_typhi_bh = $request->get('salmonella_typhi_bh');
        $appointment->salmonella_typhi_ch = $request->get('salmonella_typhi_ch');
        $appointment->salmonella_paratyphi_o = $request->get('salmonella_paratyphi_o');
        $appointment->salmonella_paratyphi_ao = $request->get('salmonella_paratyphi_ao');
        $appointment->salmonella_paratyphi_bo = $request->get('salmonella_paratyphi_bo');
        $appointment->salmonella_typhi_co = $request->get('salmonella_typhi_co');
        $appointment->ns_dengue = $request->get('ns_dengue');
        $appointment->igg_dengue = $request->get('igg_dengue');
        $appointment->igm_dengue = $request->get('igm_dengue');
        $appointment->igg_typhoid = $request->get('igg_typhoid');
        $appointment->igm_typhoid = $request->get('igm_typhoid');

        //pregnancy
        $appointment->specimen_pregnancy_test = $request->get('specimen_pregnancy_test');
        $appointment->result_pregnancy_test = $request->get('result_pregnancy_test');

        $appointment->blood_typing = $request->get('blood_typing');

        $appointment->type_specimen = $request->get('type_specimen');
        $appointment->test_kit = $request->get('test_kit');
        $appointment->method = $request->get('method');
        $appointment->result = $request->get('result');
        $appointment->value = $request->get('value');

        $appointment->blood_type_crossmatch = $request->get('blood_type_crossmatch');
        $appointment->method_crossmatch = $request->get('method_crossmatch');
        $appointment->serial_number_crossmatch = $request->get('serial_number_crossmatch');
        $appointment->donor_blood_type = $request->get('donor_blood_type');
        $appointment->source_crossmatch = $request->get('source_crossmatch');
        $appointment->component_crossmatch = $request->get('component_crossmatch');
        $appointment->content_crossmatch = $request->get('content_crossmatch');
        $appointment->extract_date_crossmatch = $request->get('extract_date_crossmatch');
        $appointment->expiry_date_crossmatch = $request->get('expiry_date_crossmatch');
        $appointment->cossmatching_result_crossmatch = $request->get('cossmatching_result_crossmatch');

        $appointment->rhu_ultrasound_result = $request->get('rhu_ultrasound_result');
        $appointment->rhu_ultrasound_remarks = $request->get('rhu_ultrasound_remarks');
        $appointment->rhu_xray_result = $request->get('rhu_xray_result');
        $appointment->rhu_xray_remarks = $request->get('rhu_xray_remarks');



        $appointment->lab_result_by = $user->id;
        $appointment->save();

        $labOrder->order_status = 'for-result-reading';
        $labOrder->lab_result_notes = request('lab_result_notes');
        $labOrder->processed_by = $user->id;
        $labOrder->save();

        return response()->json([
            'message' => 'Success'
        ]);
    }

    public function referToRhu(Request $request, SendToCloudService $service, PhoPatientCaseService $phoPatientCaseService)
    {
        $location = $this->getUserLocation();
        $patient = Patient::query()->findOrFail($request->get('patient_id'));
        $address = $patient->province . ' ' . $patient->municipality . ' ' . $patient->barangay . ' ' . $patient->purok;
        $address = trim($address);
        //remove white spaces
        $address = preg_replace('/\s+/', ' ', $address);
        $fullname = $patient->fullName();
        $fullname = trim($fullname);
        $fullname = preg_replace('/\s+/', ' ', $fullname);
        $patient_cloud_id = $patient->cloud_id;
        $patientSelfie = null;
        if ($request->hasFile('patientSelfie')) {
            $patientSelfie = $request->file('patientSelfie')->store('patientSelfies');
        }



        //        if (!$patient_cloud_id) {
        //
        //            $cloudResult = $service->createPatient($patient->id);
        //            if (!$cloudResult['success']) {
        //                return response()->json([
        //                    'message' => 'Failed to connect to cloud'
        //                ], 500);
        //            }
        //            $patient->refresh();
        //            $patient_cloud_id = $patient->cloud_id;
        //        }

        try {
            DB::beginTransaction();
            $patientCase = new PatientCase();
            $patientCase->health_unit_id = $location->id;
            $patientCase->is_referral = 1;
            $patientCase->referred_type = 'rhu';
            $patientCase->referred_to = $request->get('rhu_id');
            $patientCase->patient_name = $fullname;
            $patientCase->dob = $patient->birthday;
            $patientCase->address = $address;
            $patientCase->gender = $patient->gender;
            $patientCase->patient_cloud_id = $patient_cloud_id;
            $patientCase->case_picture = $patientSelfie;
            $referral_data = [
                'patient_cloud_id' => $patient_cloud_id,
                'doctor_id' => $request->get('doctor_id'),
                'date' => $request->get('date'),
                'time' => $request->get('time'),
                'chief_complaint' => $request->get('pre_notes'),
                'reason_for_referral' => $request->get('reason'),
                'clinical_history' => $request->get('clinical_history'),
                'lab_findings' => $request->get('lab_findings'),
                'impression' => $request->get('impression'),
                'action_taken' => $request->get('action_taken'),
                'health_insurrance_coverage' => $request->get('health_insurrance_coverage'),
                'health_insurrance_coverage_if_yes_type' => $request->get('health_insurrance_coverage_if_yes_type'),
                'referred_by' => request()->user()->id->name ?? "",
            ];
            $patientCase->referral_data = json_encode($referral_data);
            $patientCase->save();
            $phoPatientCaseService->create($patientCase->id);
            DB::commit();
            return response()->json([
                'message' => 'Patient referred to RHU'
            ]);
        } catch (\Exception $e) {
            Log::alert('Failed to connect to cloud', [
                'error' => $e->getMessage()
            ]);
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to connect to cloud'
            ], 500);
        }



        /* $appointment = new AppointmentData();

        $appointment->rhu_id = request('rhu_id');
        $appointment->status = 'pending-for-acceptance';
        $appointment->referred_to = $request->get('doctor_id');
        $appointment->patient_id = $request->get('patient_id');
        $appointment->serviced_by = request()->user()->id;
        // $appointment->specimen_picture = $request->file('specimen')->store('specimens');

        $appointment->referred_by = $request->referred_by;
        $appointment->reason = $request->reason;
        $appointment->time = $request->time;
        $appointment->date = $request->date;
        $appointment->health_insurrance_coverage = $request->health_insurrance_coverage;
        $appointment->health_insurrance_coverage_if_yes_type = $request->health_insurrance_coverage_if_yes_type;
        $appointment->action_taken = $request->action_taken;
        $appointment->impression = $request->impression;
        $appointment->lab_findings = $request->lab_findings;
        $appointment->clinical_history = $request->clinical_history;


        $appointment->referred_by_name = request()->user()->name ?? "";
        $appointment->referred_type = "rhu";

        $appointment->save();

        $service->referToRhuCreate($appointment->id);*/

        // AppointmentEvent::dispatch($appointment->id, $appointment->rhu_id,  $appointment->referred_to);


        return response()->json([
            'message' => 'Patient referred to RHU'
        ]);
    }
    public function update(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->rhu_id = $this->getRhuId()->id;
        $appointment->status = 'pending-for-acceptance';
        // $appointment->referred_to = $request->get('doctor_id');
        $appointment->serviced_by = request()->user()->id;
        // $appointment->specimen_picture = $request->file('specimen')->store('specimens');

        $appointment->referred_by = $request->referred_by;
        $appointment->reason = $request->reason;
        $appointment->pre_notes = $request->pre_notes;

        $appointment->time = $request->time;
        $appointment->date = $request->date;
        $appointment->health_insurrance_coverage = $request->health_insurrance_coverage;
        $appointment->health_insurrance_coverage_if_yes_type = $request->health_insurrance_coverage_if_yes_type;
        $appointment->action_taken = $request->action_taken;
        $appointment->impression = $request->impression;
        $appointment->lab_findings = $request->lab_findings;
        $appointment->clinical_history = $request->clinical_history;

        $appointment->save();


        return response()->json([
            'message' => 'Patient referred to RHU'
        ]);
    }


    public function assignToDoctor(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->status = 'pending-doctor-consultation';
        $appointment->referred_to = $request->get('doctor_id');
        $appointment->serviced_by = request()->user()->id;

        if ($request->get('rhu_id')) {
            $appointment->rhu_id = $request->get('rhu_id');
        }
        if ($request->get('for_sph')) {
            $appointment->for_sph = 1;
        }
        if ($request->file('specimen')) {
            $appointment->specimen_picture = $request->file('specimen')->store('specimens');
        }
        $appointment->save();


        return response()->json([
            'message' => 'Patient referred to doctor'
        ]);
    }

    public function doctorAcceptPatient(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        if ($appointment->status == 'for-result-reading' || $appointment->forReadingLabOrders()->count()) {
            $appointment->status = 'in-service-result-reading';
        } else {
            $appointment->status = 'in-service-consultation';
        }
        $appointment->for_cashier = 1;
        $appointment->save();

        return response()->json([
            'message' => 'Success'
        ]);
    }
    public function doctorMarkPatientAsDone(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->status = 'done';
        $appointment->save();

        return response()->json([
            'message' => 'Success'
        ]);
    }

    public function referToDoctorForConfirmation(Request $request, PhoPatientCaseService $service, int $id)
    {
        $location = $this->getUserLocation();
        $specimen_picture = $request->file('specimen')->store('specimens');

        try {
            DB::beginTransaction();
            $appointment = AppointmentData::query()->findOrFail($id);

            $appointment->bhs_id = $location->id;
            $appointment->rhu_id = $request->get('rhu_id');
            $appointment->status = 'processing-in-cloud';
            $appointment->referred_to = $request->get('doctor_id');
            $appointment->serviced_by = request()->user()->id;
            $appointment->specimen_picture = $specimen_picture;


            $referral_data = [
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
            ];

            $patientCase = PatientCase::query()->where('appointment_id', $id)->firstOrFail();

            $patientCase->bhs_id = config('app.health_unit_id', '10');
            $patientCase->health_unit_id = config('app.health_unit_id', '10');
            $patientCase->rhu_id = $request->get('rhu_id');
            $patientCase->status = 'pending-doctor-confirmation';
            $patientCase->referred_to_doctor = $request->get('doctor_id');
            $patientCase->serviced_by = request()->user()->id;
            $patientCase->specimen_picture = $specimen_picture;
            $patientCase->referral_data = json_encode($referral_data);


            $patientCase->save();

            $service->create($patientCase->id);


            DB::commit();
        } catch (\Exception $e) {
            Storage::delete($specimen_picture);
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to connect to cloud'
            ], 500);
        }



        $appointment->save();

        $doctor = User::query()->findOrFail($request->get('doctor_id'));


        return response()->json([
            'message' => 'Patient referred to doctor'
        ]);
    }


    public function cloudReferToDoctorForConfirmation(Request $request, string $id)
    {

        $doctor = User::query()->findOrFail($request->get('doctor_id'));

        $data  = [
            'confirm_entity' => config('app.entity'),
            'confirm_entity_key' => config('app.entity_key'),
            'confirm_entity_unit' => config('app.entity_unit'),
            'status' => 'pending-doctor-confirmation',
            'referred_to_name' => $doctor->name,
            'approved_by_name' => auth()->user()->name
        ];


        $data = array_map(function ($value) {
            return [
                'name' => $value,
                'contents' => $value
            ];
        }, $data);

        $specimen_picture = $request->file('specimen')->store('specimens');
        $data[] = [
            'name' => 'specimen_picture',
            'contents' => fopen(storage_path('app/' . $specimen_picture), 'r'),
        ];

        try {
            $client = new \GuzzleHttp\Client();
            $url = config('app.cloud_url') . '/api/tb-for-doctor-confirmation';
            $response = $client->request('POST', $url, [
                'multipart' => $data
            ]);
            if ($response->getStatusCode() == 200) {
                return response()->json([
                    'message' => 'Success'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to connect to cloud'
            ]);
        }
    }
    public function referToSphDoctor(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->referred_to = $request->get('doctor_id');
        $appointment->sph_referred_to = $request->get('doctor_id');
        $appointment->sph_serviced_by = request()->user()->id;
        $appointment->status = 'pending-doctor-consultation';
        // $appointment->specimen_picture = $request->file('specimen')->store('specimens');
        $appointment->save();


        return response()->json([
            'message' => 'Patient referred to SPH Doctor'
        ]);
    }

    public function updateToSph(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->rhu_id = $request->get('rhu_id');
        $appointment->sph_referred_to = $request->get('doctor_id');
        $appointment->serviced_by = request()->user()->id;
        $appointment->save();


        return response()->json([
            'message' => 'Patient referred to SPH doctor'
        ]);
    }

    public function referToSph(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->for_sph = 1;
        $appointment->status = 'pending';
        $appointment->sph_status = 'pending';
        $appointment->serviced_by = request()->user()->id;
        $appointment->referred_by = request()->user()->id;

        $appointment->referred_by = $request->referred_by;
        $appointment->reason = $request->reason;
        $appointment->health_insurrance_coverage = $request->health_insurrance_coverage;
        $appointment->health_insurrance_coverage_if_yes_type = $request->health_insurrance_coverage_if_yes_type;
        $appointment->action_taken = $request->action_taken;
        $appointment->impression = $request->impression;
        $appointment->lab_findings = $request->lab_findings;
        $appointment->clinical_history = $request->clinical_history;

        $appointment->save();


        return response()->json([
            'message' => 'Patient referred to SPH'
        ]);
    }

    public function approveRhuReferrals(int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->sph_status = 'approved';
        $appointment->save();


        return response()->json([
            'message' => 'Patient approved.'
        ]);
    }

    public function medicine_release(DiseaseService $diseaseService, Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        // return $request->get('inventory_id');
        if ($request->get('inventory_id')) {

            foreach ($request->get('inventory_id') as  $key => $inventory_id) {


                $inventory = ItemInventory::query()->find($inventory_id);
                // $usage[] = [
                //     'inventory_id' => $inventory_id,
                //     'quantity' => $request->get('quantity')[$key],
                //     'appointment_id' => $id,
                //     'item_id' => $inventory->item_id,
                //     'type' => 'medicine',
                //     'details' => $request->get('details')[$key],
                // ];

                if (!is_null($inventory)) {
                    $inventory->quantity = $inventory->quantity - $request->get('quantity')[$key];

                    $inventory->save();
                }
            }
        }

        // ItemUsage::query()->insert($usage);

        // $appointment->post_notes = $request->get('notes');
        $appointment->status = 'released';
        $appointment->released_by = request()->user()->id;
        $appointment->sph_medicine_released = 1;
        $appointment->sph_medicine_released_by = request()->user()->id;

        $appointment->save();


        $history =  $diseaseService->createHistory($appointment->patient_id, [
            'disease' => 25,
        ]);

        return AppointmentDataResource::make($appointment);
    }


    public function satisfaction(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->satisfaction = $request->get('satisfaction');
        $appointment->status = 'rating';
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function selfie(Request $request, int $id)
    {
        $user = request()->user();
        $location = $this->getUserLocation();

        $appointment = AppointmentData::query()->findOrFail($id);

        $appointment->selfie = $request->file('selfie')->store('selfies');
        $appointment->is_done = 1;

        if ($user->type == 'SPH-PHAR') {
            $appointment->status = 'pending-for-billing';
        } else {
            $appointment->status = 'done';
            $appointment->released_by = $user->id;
        }
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function doctorMarkAsDone($id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->status = 'pending-for-pharmacy';
        $appointment->cleared = 1;

        $appointment->prescribed_by = request()->user()->id;
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }


    public function sendToDoctor($id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->status = 'pending-doctor-consultation';
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function sendToCashier($id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->status = 'pending-for-cashier';
        $appointment->cleared = 1;

        $appointment->released_by = request()->user()->id;
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }
    public function sendFromCashierToNurseForRelease($id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->status = 'pending-for-rhu-release';
        $appointment->cleared = 1;

        $appointment->approved_by = request()->user()->id;
        $appointment->released_by = request()->user()->id;
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function markAsPaid($id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->paid = 1;
        $appointment->status = 'done';
        $appointment->released_by = request()->user()->id;
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }



    public function getXrayPending()
    {
        $user = request()->user();
        $location = $this->getUserLocation();
        // return $location;
        if ($user->type == 'SPH-XRAY') {
            $appointments = AppointmentData::query()
                // ->where('status', 'pending-for-xray')
                ->where('for_sph', 1)
                ->where('for_xray', 1)
                // ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->get();
        } else {
            $appointments = AppointmentData::query()
                // ->where('status', 'pending-for-xray')
                ->where('rhu_id', $location->id)
                ->where('for_xray', 1)
                // ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->get();
        }
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }
    public function getLabOrderPending()
    {
        $user = request()->user();
        $location = $this->getUserLocation();
        if ($user->type == 'SPH-LAB') {
            $appointments = AppointmentData::query()
                // ->where('status', 'pending-for-xray')
                ->where('for_sph', 1)
                ->where('for_lab', 1)
                // ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->get();
        } else {
            $appointments = AppointmentData::query()
                // ->where('status', 'pending-for-xray')
                ->where('rhu_id', $location->id)
                ->where('for_lab', 1)
                // ->whereNotNull('prescribed_by')
                ->with(['patient'])
                ->get();
        }
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }


    public function getPendingBilling()
    {
        // $user = request()->user();
        // $location = $this->getUserLocation();
        $appointments = AppointmentData::query()
            ->where('status', 'pending-for-billing')
            ->with(['patient'])
            ->get();
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getPendingCashier()
    {
        $user = request()->user();
        // $location = $this->getUserLocation();
        if ($user->type == 'SPH-CASHIER') {
            $appointments = AppointmentData::query()
                ->whereIn('status', ['pending-for-cashier', 'pending-for-cashier-release'])
                ->where('for_sph', 1)
                ->with(['patient'])
                ->latest()
                ->get();
        }
        if ($user->type == 'RHU-CASHIER') {
            $appointments = AppointmentData::query()
                ->whereIn('status', ['pending-for-cashier', 'pending-for-cashier-release'])
                ->where('rhu_id', $user->health_unit_id)
                ->with(['patient'])
                ->latest()
                ->get();
        }
        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }

    public function getPendingAnesthesia()
    {
        $user = request()->user();
        $location = $this->getUserLocation();
        if ($user->type == 'HIS-ANESTHESIA') {
            $appointments = AppointmentData::query()
                // ->whereIn('status', ['pending-for-anesthesia', 'pending-for-anesthesia-release'])
                ->where('for_sph', 1)
                ->where('for_anesthesia', 1)
                ->with(['patient'])
                // ->latest()
                ->get();
        }

        return response()->json([
            'data' => AppointmentDataResource::collection($appointments->load([
                'patient',
                'bhs',
                'rhu',
                'tb_symptoms',
                'vitals',
                'socialHistory',
                'environmentalHistory',
            ])),
            'count' => $appointments->count()
        ]);
    }
}
