<?php

namespace App\Http\Controllers\Cloud;

use App\Http\Controllers\Controller;
use App\Models\AppointmentData;
use App\Models\Item;
use App\Models\ItemInventory;
use App\Models\ItemUsage;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\Vital;
use App\Services\Cloud\PhoPatientCaseService;
use App\Services\Cloud\PmrfPatientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientCaseController extends Controller
{
    public function store(Request $request, PhoPatientCaseService $phoPatientCaseService)
    {


        $data = $request->all();
        $patient = Patient::query()->find( $data['patient_cloud_id']);

        if ($request->hasFile('specimen_picture')) {
            $data['specimen_picture'] = $request->file('specimen_picture')->store("specimen_pictures/$patient->id");
        }
        if($request->hasFile('case_picture')){

            $data['case_picture'] = $request->file('case_picture')->store("case_pictures/$patient->id");
            if($patient){
                $patient->avatar = $data['case_picture'];
                $patient->save();
            }
        }

       $case =  $phoPatientCaseService->store($data);

        return response()->json([
            'message' => 'Case created',
            'data' => $case
        ]);
    }

    public function getByDoctorAppointment()
    {
        \request()->validate([
            'doctor_id' => 'required'
        ]);
        $query = new PatientCase();
        $query->setConnection('mysql2');
        $result = $query->where('referred_to', request()->get('doctor_id'))->get();

        return [
            'data' => $result,
            'count' => $result->count()
        ];
    }

    public function getRhuReferrals()
    {
        $query = new PatientCase();
        $query->setConnection('mysql2');
        $result = $query
            ->with('patient')
            ->where('referred_type', 'rhu')
            ->where('referred_to', request()->get('health_unit_id'))
            ->where('is_referral', 1)
            ->where('referral_accepted',0)
            ->get();
        return [
            'data' => $result,
            'count' => $result->count()
        ];
    }

    public function show(PhoPatientCaseService $service, string $id)
    {
        return $service->findCloudCase($id);
    }

    public function updateVitals(PhoPatientCaseService $service, Request $request,string $id)
    {
        $data =$request->only([
            'temperature',
            'pulse',
            'blood_systolic',
            'blood_diastolic',
            'respiratory',
            'height',
            'weight',
            'glucose',
            'uric_acid',
            'cholesterol',
            'bmi',
            'height_for_age',
            'weight_for_age',
            'blood_type',
            'bloody_type',
            'oxygen_saturation',
            'heart_rate',
            'regular_rhythm',
            'covid_19',
            'tb',
            'update_by',
        ]);

        return $service->updateCaseVitals($id, $data);
    }

    public function updateCloudVital(PhoPatientCaseService $service, Request $request,string $id)
    {
        $data =$request->only([
            'temperature',
            'pulse',
            'blood_systolic',
            'blood_diastolic',
            'respiratory',
            'height',
            'weight',
            'glucose',
            'uric_acid',
            'cholesterol',
            'bmi',
            'height_for_age',
            'weight_for_age',
            'blood_type',
            'bloody_type',
            'oxygen_saturation',
            'heart_rate',
            'regular_rhythm',
            'covid_19',
            'tb',
            'update_by',
        ]);

        return $service->updateCloudVital($id, $data);
    }


    public function updateCase(Request $request, PhoPatientCaseService $service, string $id)
    {
        $data = $request->all();
        unset($data['_method']);

        //9bcc3519-1423-4e9c-9ae2-f198bf18a955
        return $service->updateCase($id, $data);
    }



    public function acceptDoctorCase(PhoPatientCaseService $service, PmrfPatientService $pmrfPatientService, string $id)
    {
        $localCase = PatientCase::find($id);
        if(!is_null($localCase)){
            return response()->json([
                'message' => 'Case already accepted'
            ], 400);
        }
        $data =  [
            'referral_accepted' => 1,
            'status' => 'pending-doctor-confirmation',
        ];

        $cloud = $service->updateCaseCloud($id,$data);

        if(!$cloud['success'])
            return response()->json(['message' => 'Unable to connect'], 400);

        $case = $cloud['data'];

        Log::alert('acceptDoctorCase', [
            'case' => $case
        ]);



        $localCase = PatientCase::find($id);
        if(is_null($localCase)){
            $localCase = new PatientCase();
            $localCase->id = $id;

        }

        foreach ($case as $key => $value) {
            $localCase->$key = $value;
        }
        $localCase->cloud_id = $id;
        $localCase->save();

        $patient = Patient::query()->where('cloud_id', $localCase->patient_cloud_id)->first();
        if(!$patient){

            $cloud = $pmrfPatientService->getCloud([
                'id' => $localCase->patient_cloud_id,
                'pmrf_status' => 'done'
            ]);

            if($cloud['total_count'] < 1){

                return response()->json([
                    'message' => 'Patient not found not done yet'
                ], 404);
            }

            $patient = new Patient();
            $patient->fill((array)$cloud['data'][0]);
            $patient->cloud_id = $cloud['data'][0]->id;
            $patient->save();
        }


        $vitals = $localCase->vitals ? json_decode($localCase->vitals)[0]: [];


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


        $appointment = new AppointmentData();
        $appointment->bhs_id = $localCase->health_unit_id;
        $appointment->status = 'pending-doctor-confirmation';
        $appointment->referred_to = $localCase->referred_to_doctor;
        $appointment->patient_id = $patient->id;
        $appointment->rhu_id = $localCase->rhu_id;
        $appointment->pre_notes = $localCase->chief_complaint;
        $appointment->post_notes = $localCase->history_of_present_illness;
        $appointment->specimen_picture = $localCase->specimen_picture;
        $appointment->vital_id = $vital->id;

        $appointment->save();
        $localCase->appointment_id = $appointment->id;
        $localCase->save();


    }



    public function getCaseList(PhoPatientCaseService $service)
    {

        return $service->getCaseList(\request()->all());
    }

    public function getServiceDone(PhoPatientCaseService $service)
    {
        return $service->getCloudCaseList([
            'status' => 'service-done',
            'bhs_id' => config('health_unit_id', '10')
        ]);
    }

    public function getServiceDoneSync(PhoPatientCaseService $service)
    {
        $result = $service->getCloudCaseList([
            'status' => 'service-done',
            'bhs_id' => config('health_unit_id', '10')
        ]);

        $cases = $result['data'];

        foreach ($cases as $case) {

            $localCase = PatientCase::query()->where('cloud_id', $case['id'])->first();
            if(is_null($localCase)){
                $localCase = new PatientCase();
            }
            foreach ($case as $key => $value) {
                if($key != 'cloud_id'){
                    $localCase->$key = $value;
                }
            }
            $localCase->save();
            $appointment = AppointmentData::query()->where('id', $localCase->appointment_id)->first();
            if($appointment){



                if($localCase->is_tb_positive){
                    $appointment->status = 'pending-for-bhw-release';
                    $appointment->procedure_code = $localCase->procedure_code;
                    $appointment->diagnosis_code = $localCase->diagnosis_code;
                    $appointment->is_tb_positive = $localCase->is_tb_positive;
                    $prescription = json_decode($localCase->prescription,true);
                    if(!is_null($prescription) ) {
                        $appointment->prescribed_by = $prescription['doctor_id'];
                        $appointment->approved_by = $prescription['doctor_id'];


                        $usage = [];

                        $codes = [];
                        foreach ($prescription['items'] as $item) {
                            $codes[] = $item['item_code'];
                        }


                        $items = Item::query()->whereIn('code', $codes)->get();
                        $inventories = ItemInventory::query()
                            ->whereIn('item_id', $items->pluck('id'))
                            ->where('location_id', $appointment->bhs_id)
                            ->get();

                        foreach ($prescription['items'] as $item) {
                            $itemModel = $items->where('code', $item['item_code'])->first();
                            $inventory = $inventories->where('item_id', $itemModel->id)->first();
                            $usage[] = [
                                'inventory_id' => $inventory?->id,
                                'quantity' => $item['quantity'] || 1,
                                'details' => $item['details'],
                                'appointment_id' => $appointment->id,
                                'item_id' => $itemModel->id,
                                'type' => 'prescription',
                            ];
                        }
                        ItemUsage::query()->insert($usage);


                    }

                }else{
                    $appointment->status = 'done';
                }

                $appointment->save();
                $service->updateCaseCloud($case['id'], ['status' => 'done']);

            }
        }
    }




}
