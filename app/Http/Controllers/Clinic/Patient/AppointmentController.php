<?php

namespace App\Http\Controllers\Clinic\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentDataResource;
use App\Http\Resources\UserResource;
use App\Models\AppointmentData;
use App\Models\EnvironmentalHistory;
use App\Models\HealthUnit;
use App\Models\MedicalSurgicalHistory;
use App\Models\Patient;
use App\Models\PatientAppointmentSymptoms;
use App\Models\PatientCase;
use App\Models\PatientGeneralHistory;
use App\Models\SocialHistory;
use App\Models\User;
use App\Services\Cloud\PhoPatientCaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{


    public function index()
    {
        $appointments = AppointmentData::query()
            ->when(request()->has('status'), fn ($q) => $q->where('status', request()->get('status')))
            ->with(['patient', 'bhs', 'rhu', 'vitals', 'tb_symptoms'])
            ->latest()
            ->paginate(10);
        return AppointmentDataResource::collection($appointments);
    }
    public function doneTbPatients()
    {

        $appointments = AppointmentData::query()
            ->where('status', 'done')
            ->with(['patient', 'bhs', 'tb_symptoms', 'vitals', 'prescriptions'])
            ->latest()
            ->paginate(10);
        return AppointmentDataResource::collection($appointments);
    }
    public function getAppointments($id)
    {

        $appointments = AppointmentData::query()
            ->when(request()->has('status'), fn ($q) => $q->where('status', request()->get('status')))
            ->when(request()->has('type'), fn ($q) => $q->where('type', request()->get('type')))
            ->where('patient_id', $id)
            ->with(['patient', 'bhs', 'tb_symptoms', 'vitals', 'prescriptions'])
            ->latest()
            ->paginate(10);
        return AppointmentDataResource::collection($appointments);
    }

    public function getPatientPendingCashier($id)
    {
        // return $id;
        $appointment = AppointmentData::query()
            ->where("patient_id", $id)
            // ->where('status', 'pending-for-cashier')
            ->with([
                'patient', 'patient.barangayData',
                'patient.purokData',
                'patient.municipalityData'
            ])
            ->first();
        return AppointmentDataResource::make($appointment);
    }

    public function getSphPatientReferrals()
    {
        $user = request()->user();
        $appointments = AppointmentData::query()
            ->where('sph_status', request()->has('status') ? '=' : '!=', request()->has('status') ? request()->get('status') : 'pending')
            ->where('for_sph', 1)
            ->with(['patient', 'bhs', 'tb_symptoms', 'vitals', 'prescriptions'])
            ->latest()
            ->paginate(10);
        return AppointmentDataResource::collection($appointments);
    }

    public function getSphDoctorPatientReferrals()
    {
        $user = request()->user();
        $appointments = AppointmentData::query()
            ->where('sph_status', request()->has('status') ? '=' : '!=', request()->has('status') ? request()->get('status') : 'done')
            ->where('sph_referred_to', $user->id)
            ->with(['patient', 'bhs', 'tb_symptoms', 'vitals', 'prescriptions'])
            ->latest()
            ->paginate(10);
        return AppointmentDataResource::collection($appointments);
    }
    public function getDoctorPatientReferrals()
    {
        $user = request()->user();
        $appointments = AppointmentData::query()
            ->where('status', request()->has('status'))
            ->where('referred_to', $user->id)
            ->with(['patient', 'bhs', 'tb_symptoms', 'vitals', 'prescriptions'])
            ->latest()
            ->paginate(10);
        return AppointmentDataResource::collection($appointments);
    }

    public function getRhuAppointments()
    {
        $appointments = AppointmentData::query()
            ->when(
                request()->has('status'),
                fn ($q) =>  $q->where('status', request()->get('status'))
            )
            ->where('rhu_id', $this->getUserLocation()->id)
            ->with(['patient', 'bhs', 'tb_symptoms'])->paginate(10);
        return AppointmentDataResource::collection($appointments);
    }

    public function store(Request $request)
    {
        $user = request()->user();
        $appointment = new AppointmentData();
        $appointment->patient_id = $request->patient_id;
        if ($user->type == 'BHW-BHS') {
            $appointment->bhs_id = $this->getUserLocation()->id;
            $appointment->rhu_id = $this->getRhuId()->id;
        }
        if ($user->type == 'RHU-NURSE') {
            $appointment->bhs_id = 0;
            $appointment->rhu_id = $user->health_unit_id;
        }
        if ($user->type == 'SPH-NURSE') {
            $appointment->bhs_id = 0;
            $appointment->rhu_id = 0;
            $appointment->for_sph = 1;
        }
        if ($user->type == 'HIS-ER') {
            $appointment->for_sph = 1;
        }
        if ($request->bhs_id) {
            $appointment->bhs_id = $request->bhs_id;
        }
        if ($request->rhu_id) {
            $appointment->rhu_id = $request->rhu_id;
        }
        if ($request->for_sph) {
            $appointment->for_sph = $request->for_sph;
        }
        $appointment->mode_of_consultation = $request->mode_of_consultation;
        $appointment->phic_no = $request->phic_no;
        $appointment->pre_notes = $request->notes;
        $appointment->post_notes = $request->disease;
        // $appointment->patient_selfie = $request->file('patient_selfie')->store('patient_selfie');

        $appointment->status = 'pending';
        $appointment->save();
        $this->createCase($request, $appointment);
        $appointment->load(['patient', 'bhs']);



        // if ($request->get('patient_selfie')) {
        $patient = Patient::query()->findOrFail($appointment->patient_id);
        $patient->avatar = $request->file('patient_selfie')->store('patients/avatar');
        $patient->save();
        // }

        $patientSymptoms = new PatientAppointmentSymptoms();
        $patientSymptoms->appointment_id = $appointment->id;
        $patientSymptoms->patient_id = $patient->id;
        $patientSymptoms->chest_pain_discomfort_heaviness = $request->chest_pain_discomfort_heaviness;
        $patientSymptoms->difficulty_breathing = $request->difficulty_breathing;
        $patientSymptoms->deizure_convulsion = $request->deizure_convulsion || '';
        $patientSymptoms->unconscious_restless_lethargic = $request->unconscious_restless_lethargic;
        $patientSymptoms->not_oriented_to_time_person_place = $request->not_oriented_to_time_person_place;
        $patientSymptoms->bluish_discoloration_of_skin_lips = $request->bluish_discoloration_of_skin_lips;
        $patientSymptoms->act_of_self_harm_suicide = $request->act_of_self_harm_suicide;
        $patientSymptoms->acute_fracture_dislocation_injuries = $request->acute_fracture_dislocation_injuries;
        $patientSymptoms->signs_of_abuse = $request->signs_of_abuse;
        $patientSymptoms->severe_abdominal_pain = $request->severe_abdominal_pain;
        $patientSymptoms->persistent_vomiting = $request->persistent_vomiting;
        $patientSymptoms->persistent_diarrhea = $request->persistent_diarrhea;
        $patientSymptoms->unable_to_tolerate_fluids = $request->unable_to_tolerate_fluids;
        $patientSymptoms->save();


        $generalHistory = new PatientGeneralHistory();
        $generalHistory->appointment_id = $appointment->id;
        $generalHistory->patient_id = $patient->id;
        $generalHistory->patient_id = $request->patient_id;
        $generalHistory->hypertension = $request->hypertension;
        $generalHistory->stroke = $request->stroke;
        $generalHistory->heart_disease = $request->heart_disease;
        $generalHistory->high_cholesterol = $request->high_cholesterol;
        $generalHistory->bleeding_disorders = $request->bleeding_disorders;
        $generalHistory->diabetes = $request->diabetes;
        $generalHistory->kidney_disease = $request->kidney_disease;
        $generalHistory->liver_disease = $request->liver_disease;
        $generalHistory->copd = $request->copd;
        $generalHistory->asthma = $request->asthma;

        $generalHistory->mental_neurological_substance_abuse = $request->mental_neurological_substance_abuse;

        $generalHistory->mental_neurological_substance_abuse_details = $request->mental_neurological_substance_abuse_details;

        $generalHistory->cancer = $request->cancer;
        $generalHistory->cancer_details = $request->cancer_details;
        $generalHistory->others = $request->others;
        $generalHistory->others_details = $request->others_details;
        $generalHistory->save();



        $medicalSurgicalHistory = new MedicalSurgicalHistory();
        $medicalSurgicalHistory->appointment_id = $appointment->id;
        $medicalSurgicalHistory->patient_id = $patient->id;
        $medicalSurgicalHistory->asthma_history = $request->asthma_history;
        $medicalSurgicalHistory->asthma_history_details = $request->asthma_history_details;
        $medicalSurgicalHistory->allergies = $request->allergies;
        $medicalSurgicalHistory->allergies_details = $request->allergies_details;
        $medicalSurgicalHistory->allergies_to_medicine = $request->allergies_to_medicine;
        $medicalSurgicalHistory->allergies_to_medicine_details = $request->allergies_to_medicine_details;
        $medicalSurgicalHistory->immunization = $request->immunization;
        $medicalSurgicalHistory->immunization_details = $request->immunization_details;
        $medicalSurgicalHistory->injuries_accidents = $request->injuries_accidents;
        $medicalSurgicalHistory->injuries_accidents_details = $request->injuries_accidents_details;
        $medicalSurgicalHistory->hearing_problems = $request->hearing_problems;
        $medicalSurgicalHistory->hearing_problems_details = $request->hearing_problems_details;
        $medicalSurgicalHistory->vision_problems = $request->vision_problems;
        $medicalSurgicalHistory->vision_problems_details = $request->vision_problems_details;
        $medicalSurgicalHistory->heart_disease_history = $request->heart_disease_history;
        $medicalSurgicalHistory->heart_disease_history_details = $request->heart_disease_history_details;
        $medicalSurgicalHistory->neurological_substance_use_conditions = $request->neurological_substance_use_conditions;
        $medicalSurgicalHistory->neurological_substance_use_conditions_details = $request->neurological_substance_use_conditions_details;
        $medicalSurgicalHistory->cancer_history = $request->cancer_history;
        $medicalSurgicalHistory->cancer_history_details = $request->cancer_history_details;
        $medicalSurgicalHistory->other_organ_disorders = $request->other_organ_disorders;
        $medicalSurgicalHistory->other_organ_disorders_details = $request->other_organ_disorders_details;
        $medicalSurgicalHistory->previous_hospitalizations = $request->previous_hospitalizations;
        $medicalSurgicalHistory->previous_hospitalizations_details = $request->previous_hospitalizations_details;
        $medicalSurgicalHistory->previous_surgeries = $request->previous_surgeries;
        $medicalSurgicalHistory->previous_surgeries_details = $request->previous_surgeries_details;
        $medicalSurgicalHistory->other_medical_surgical_history = $request->other_medical_surgical_history;
        $medicalSurgicalHistory->other_medical_surgical_history_details = $request->other_medical_surgical_history_details;
        $medicalSurgicalHistory->save();

        $socialHistory = new SocialHistory();
        $socialHistory->appointment_id = $appointment->id;
        $socialHistory->patient_id = $patient->id;
        $socialHistory->intake_high_sugar = $request->intake_high_sugar;
        $socialHistory->intake_high_sugar_details = $request->intake_high_sugar_details;
        $socialHistory->take_supplements = $request->take_supplements;
        $socialHistory->take_supplements_details = $request->take_supplements_details;
        $socialHistory->deworming_6months = $request->deworming_6months;
        $socialHistory->deworming_6months_details = $request->deworming_6months_details;
        $socialHistory->flouride_toothpaste = $request->flouride_toothpaste;
        $socialHistory->last_dental_check_up = $request->last_dental_check_up;
        $socialHistory->physical_activity = $request->physical_activity;
        $socialHistory->daily_screen_time = $request->daily_screen_time;
        $socialHistory->violence_injuries = $request->violence_injuries;
        $socialHistory->violence_injuries_details = $request->violence_injuries_details;
        $socialHistory->bully_harassment = $request->bully_harassment;
        $socialHistory->bully_harassment_details = $request->bully_harassment_details;
        $socialHistory->save();

        $environmentalHistory = new EnvironmentalHistory();
        $environmentalHistory->appointment_id = $appointment->id;
        $environmentalHistory->patient_id = $patient->id;
        $environmentalHistory->safe_water = $request->safe_water;
        $environmentalHistory->satisfactory_waste_disposal = $request->satisfactory_waste_disposal;
        $environmentalHistory->prolong_exposure_biomass_fuel = $request->prolong_exposure_biomass_fuel;
        $environmentalHistory->exposure_tabacco_vape = $request->exposure_tabacco_vape;
        $environmentalHistory->exposure_tabacco_vape_details = $request->exposure_tabacco_vape_details;
        $environmentalHistory->save();


        $appointment->load(['socialHistory', 'environmentalHistory']);





        return AppointmentDataResource::make($appointment);
    }
    public function getRhuId()
    {
        $user = request()->user();
        $location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
        return $location;
    }

    public function tbApproveMedsForRelease(PhoPatientCaseService $service, $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        if ($appointment->bhs_id > 0) {

            $appointment->status = 'pending-for-billing-release';
        } else if ($appointment->rhu_id > 0) {
            $appointment->status = 'pending-for-billing-release';
            // $appointment->status = 'pending-for-rhu-release';
        } else if ($appointment->for_sph > 1) {
            $appointment->status = 'pending-for-billing-release';
            // $appointment->status = 'pending-for-rhu-release';
        }
        $appointment->approved_by = request()->user()->id;
        $appointment->approved_by_name = request()->user()->name;
        $appointment->save();

        $case = PatientCase::query()->where('appointment_id', $id)->first();
        if ($appointment->status == 'pending-for-billing-release') {
            $case->status = 'service-done';
            $case->save();
            $service->updateCaseCloud($case->id, [
                'prescription' => $case->prescription,
                'diagnosis_code' => $case->diagnosis_code,
                'is_tb_positive' => $case->is_tb_positive,
                'procedure_code' => $case->procedure_code,
                'status' => 'service-done',
            ]);
        }


        return AppointmentDataResource::make($appointment);
    }

    public function cloudTbApproveMedsForRelease($id)
    {
        $user = request()->user();


        $data = [
            'status' => 'pending-for-billing-release',
            'approved_by_name' => $user->name,
        ];

        try {
            $client = new \GuzzleHttp\Client();
            $url = config('app.cloud_url') . '/api/tb-approve-release-medication/' . $id;
            $response = $client->request('POST', $url, [
                'form_data' => $data
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

    public function update(Request $request, int $id)
    {
        $appointment = AppointmentData::query()->with(['patient', 'bhs'])->findOrFail($id);

        $isPositive = $request->boolean('isPositive');

        if ($isPositive) {
            $appointment->referable = 1;
            $appointment->is_tb_positive = 1;
        } else {
            $appointment->is_tb_positive = 0;
            $appointment->is_done = 1;
            $appointment->status = 'done';
        }

        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function getAllDoctors()
    {
        $user = request()->user();

        $doctors = User::query()->whereIn('type', ['rhu-doctor', 'sph-doctor', 'doctor'])->where('municipality', $user->municipality)->get();

        return UserResource::collection($doctors);
    }
    public function getDoctorsByLocation()
    {
        $user = request()->user();

        $doctors = User::query()->whereIn('type', ['rhu-doctor', 'RHU-DOCTOR', 'SPH-DOCTOR', 'sph-doctor'])->where('health_unit_id', request('health_unit_id'))->get();

        return UserResource::collection($doctors);
    }
    public function getRHUDoctors()
    {
        $user = request()->user();

        $doctors = User::query()->whereIn('type', ['rhu-doctor', 'RHU-DOCTOR'])->get();

        return UserResource::collection($doctors);
    }
    public function tbDoctors()
    {
        $user = request()->user();

        $doctors = User::query()->where('type', 'rhu-doctor')
            ->where('municipality', $user->municipality)
            ->get();

        return UserResource::collection($doctors);
    }



    public function getSphTbDoctors()
    {
        $user = request()->user();

        $doctors = User::query()->where('type', 'sph-doctor')->get();

        return UserResource::collection($doctors);
    }


    public function xRayOrder(int $id)
    {
        $appointment = AppointmentData::query()->with(['patient'])->findOrFail($id);
        $appointment->status = 'pending-for-xray';
        $appointment->for_xray = 1;

        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function labOrder(int $id)
    {
        $appointment = AppointmentData::query()->with(['patient'])->findOrFail($id);
        $appointment->status = 'pending-for-lab-order';
        $appointment->for_lab = 1;
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function createCase(Request $request, AppointmentData $appointmentData)
    {
        $patient = Patient::query()->findOrFail($request->patient_id);
        $address = $patient->province . ' ' . $patient->municipality . ' ' . $patient->barangay . ' ' . $patient->purok;
        $address = trim($address);
        //remove white spaces
        $address = preg_replace('/\s+/', ' ', $address);
        $fullname = $patient->fullName();
        $fullname = trim($fullname);
        $fullname = preg_replace('/\s+/', ' ', $fullname);
        $patientCase = new PatientCase();
        $patientCase->patient_id = $patient->id;
        $patientCase->patient_cloud_id = $patient->cloud_id;
        $patientCase->cloud_id = $appointmentData->cloud_id;
        $patientCase->appointment_id = $appointmentData->id;
        $patientCase->patient_name = $fullname;
        $patientCase->dob = $patient->birthday;
        $patientCase->address = $address;
        $patientCase->case_picture = $appointmentData->patient_selfie;
        $patientCase->mode_of_consultation = $request->mode_of_consultation;
        $patientCase->consultation_type = $request->disease;
        $patientCase->chief_complaint = $request->notes;
        $patientCase->history_of_present_illness = $request->history;
        $patientCase->gender = $patient->gender;

        $generalHistory = [

            'hypertension' => $request->boolean('hypertension'),
            'stroke' => $request->boolean('stroke'),
            'heart_disease' => $request->boolean('heart_disease'),
            'high_cholesterol'  => $request->boolean('high_cholesterol'),
            'bleeding_disorders' => $request->boolean('bleeding_disorders'),
            'diabetes' => $request->boolean('diabetes'),
            'kidney_disease' => $request->boolean('kidney_disease'),
            'liver_disease' => $request->boolean('liver_disease'),
            'copd' => $request->boolean('copd'),
            'asthma' => $request->boolean('asthma'),
            'mental_neurological_substance_abuse' => $request->mental_neurological_substance_abuse_details,
            'cancer' => $request->cancer_details,
            'others' => $request->others_details,
        ];

        $generalHistoryData = [];
        foreach ($generalHistory as $key => $value) {
            if ($value) {
                $generalHistoryData[$key] = Str::ucfirst(str_replace('_', ' ', $key));
            }
        }

        $patientCase->general_history = json_encode($generalHistoryData);

        $medicalAndSurgicalHistory = [
            'asthma_history' => $request->boolean('asthma_history'),
            'allergies' => $request->boolean('allergies'),
            'allergies_to_medicine' => $request->boolean('allergies_to_medicine'),
            'immunization' => $request->boolean('immunization'),
            'injuries_accidents' => $request->boolean('injuries_accidents'),
            'hearing_problems' => $request->boolean('hearing_problems'),
            'vision_problems' => $request->boolean('vision_problems'),
            'heart_disease_history' => $request->boolean('heart_disease_history'),
            'neurological_substance_use_conditions' => $request->boolean('neurological_substance_use_conditions'),
            'cancer_history' => $request->boolean('cancer_history'),
            'other_organ_disorders' => $request->boolean('other_organ_disorders'),
            'previous_hospitalizations' => $request->boolean('previous_hospitalizations'),
            'previous_surgeries' => $request->boolean('previous_surgeries'),
            'other_medical_surgical_history' => $request->boolean('other_medical_surgical_history'),
        ];

        $medicalAndSurgicalHistoryData = [];
        foreach ($medicalAndSurgicalHistory as $key => $value) {
            if ($value) {
                $medicalAndSurgicalHistoryData[$key] = $request->get("{$key}_details");
            }
        }

        $patientCase->medical_and_surgical_history = json_encode($medicalAndSurgicalHistoryData);


        $personalSocialHistory = [
            'physical_activity' => $request->physical_activity,
            'daily_screen_time' => $request->daily_screen_time,
            'violence_injuries' => $request->violence_injuries_details,
            'bully_harassment' => $request->bully_harassment_details,
        ];

        $personalSocialHistory['diet_feeding_nutrition'] = [
            'intake_high_sugar' => $request->intake_high_sugar,
            'take_supplements' => $request->take_supplements_details ?? "No",
            'deworming_6months' => $request->deworming_6months_details ?? "No"
        ];
        $personalSocialHistory['oral_health'] = [
            'flouride_toothpaste' => $request->flouride_toothpaste,
            'last_dental_check_up' => $request->last_dental_check_up,
        ];

        $personalSocialHistory = json_encode($personalSocialHistory);
        $patientCase->personal_social_history = $personalSocialHistory;


        $environmentalHistory = [
            'safe_water' => $request->safe_water,
            'satisfactory_waste_disposal' => $request->satisfactory_waste_disposal,
            'prolong_exposure_biomass_fuel' => $request->prolong_exposure_biomass_fuel,
            'exposure_tabacco_vape' => $request->exposure_tabacco_vape_details ?? "No",
        ];
        $environmentalHistory = json_encode($environmentalHistory);
        $patientCase->environmental_history = $environmentalHistory;
        $patient_symptoms = [
            'chest_pain_discomfort_heaviness' => $request->boolean('chest_pain_discomfort_heaviness'),
            'difficulty_breathing' => $request->boolean('difficulty_breathing'),
            'deizure_convulsion' => $request->boolean('deizure_convulsion'),
            'unconscious_restless_lethargic' => $request->boolean('unconscious_restless_lethargic'),
            'not_oriented_to_time_person_place' => $request->boolean('not_oriented_to_time_person_place'),
            'bluish_discoloration_of_skin_lips' => $request->boolean('bluish_discoloration_of_skin_lips'),
            'act_of_self_harm_suicide' => $request->boolean('act_of_self_harm'),
            'signs_of_abuse' => $request->boolean('signs_of_abuse'),
            'severe_abdominal_pain' => $request->boolean('severe_abdominal_pain'),
            'persistent_vomiting' => $request->boolean('persistent_vomiting'),
            'persistent_diarrhea' => $request->boolean('persistent_diarrhea'),
            'unable_to_tolerate_fluids' => $request->boolean('unable_to_tolerate_fluids'),
        ];

        $patient_symptoms = json_encode($patient_symptoms);
        $patientCase->patient_symptoms = $patient_symptoms;
        $entities = [];
        $address = config('app.entity_key', '') . ' ' . config('app.entity_unit', '');
        $address = preg_replace('/\s+/', ' ', $address);
        $entities[] = [
            'type' => config('app.entity'),
            'address' => trim($address),
            'appointment_id' => $appointmentData->id,
            'patient_id' => $patient->id,
        ];
        $patientCase->entities = json_encode($entities);
        $patientCase->phic_id = $request->phic_no;

        $tb_symptoms = [
            'cough_for_3_weeks_or_longer' => $request->boolean('cough_for_3_weeks_or_longer'),
            'coughing_up_blood_or_mucus' => $request->boolean('coughing_up_blood_or_mucus'),
            'pain_with_breathing_or_coughing' => $request->boolean('pain_with_breathing_or_coughing'),
            'chest_pain' => $request->boolean('chest_pain'),
            'fever' => $request->boolean('fever'),
            'chills' => $request->boolean('chills'),
            'night_sweats' => $request->boolean('night_sweats'),
            'weight_loss' => $request->boolean('weight_loss'),
            'not_wanting_to_eat' => $request->boolean('not_wanting_to_eat'),
            'not_feeling_well_in_general' => $request->boolean('not_feeling_well_in_general'),
            'tiredness' => $request->boolean('tiredness'),

        ];
        $tb_symptoms = json_encode($tb_symptoms);
        $patientCase->tb_symptoms = $tb_symptoms;


        $patientCase->save();
    }
}
