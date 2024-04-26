<?php

namespace App\Http\Controllers\Clinic\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentDataResource;
use App\Models\AppointmentData;
use App\Models\HealthUnit;
use App\Models\Item;
use App\Models\ItemInventory;
use App\Models\ItemUsage;
use App\Models\LaboratoryOrder;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Services\Cloud\PhoPatientCaseService;
use App\Services\DiseaseService;
use Illuminate\Http\Request;

class LaboratoryItemUsageController extends Controller
{

    public function prescribe(Request $request, int $id)
    {
        // return;
        $usage = [];
        $user = request()->user();
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->prescribed_by = $user->id;
        $appointment->rhu_id = $user->health_unit_id;
        if ($request->type == 'bhs') {
            $appointment->status = 'pending-for-pharmacy-release';
        } else {
            $appointment->status = 'pending-for-pharmacy-medicine-release';
        }
        if (request('diagnosis_code')) {
            $appointment->diagnosis_code = request('diagnosis_code');
        }
        if (request('procedure_code')) {
            $appointment->procedure_code = request('procedure_code');
        }
        $appointment->save();
        $case = PatientCase::query()->where('appointment_id', $id)->first();

        if(is_null($case)){
            $case = new PatientCase();
            $case->appointment_id = $id;

            $patient = Patient::query()->findOrFail($appointment->patient_id);
            $case->patient_id = $patient->id;
            $address = $patient->province . ' ' . $patient->municipality . ' ' . $patient->barangay . ' ' . $patient->purok;
            $address = trim($address);
            //remove white spaces
            $address = preg_replace('/\s+/', ' ', $address);
            $fullname = $patient->fullName();
            $fullname = trim($fullname);
            $fullname = preg_replace('/\s+/', ' ', $fullname);
            $case->patient_name = $fullname;
            $case->address = $address;
            $case->dob = $patient->birthday;
            $case->gender = $patient->gender;
            $case->rhu_id = config('app.entity_key','3');
        }

        $items = Item::query()->whereIn('id', $request->get('items'))->get();
        $prescription = [
            'items' => [],
            'doctor' => $user->name,
            'doctor_id' => $user->id,
        ];
        foreach ($request->get('items') as  $key => $item_id) {


            $item = $items->where('id', $item_id)->first();
            // $inventory = ItemInventory::query()->where('location_id', $this->getUserLocation()->id)->where('item_id', $item_id)->first();
            $usage[] = [
                'inventory_id' => $request->get('inventory_id')[$key],
                'quantity' => $request->get('quantity')[$key] || 1,
                'details' => $request->get('sig')[$key],
                'appointment_id' => $id,
                'item_id' => $item_id,
                'type' => 'prescription',
            ];
            $prescription['items'][] = [
                'item_code' => $item->code,
                'quantity' => $request->get('quantity')[$key],
                'item_name' => $item->name,
                'type' => 'prescription',
                'updated_by' => $user->name,
                'updated_at' => now()->toDateTimeString(),
                'details' => $request->get('sig')[$key],
            ];

            // $inventory->quantity = $inventory->quantity - $request->get('quantity')[$key];

            // $inventory->save();
        }

        $case->prescription = json_encode($prescription);
        $case->diagnosis_code = request('diagnosis_code');
        $case->procedure_code = request('procedure_code');
        $case->save();

        //prescribe here

        ItemUsage::query()->insert($usage);

        LaboratoryOrder::query()->where('appointment_id', $id)->update(['order_status' => 'done']);

        return AppointmentDataResource::make($appointment);
    }


    public function cloudPrescribe(Request $request, int $id)
    {
        // return;
        $usage = [];
        $user = request()->user();

        if ($request->type == 'bhs') {
            $status = 'pending-for-pharmacy-release';
        } else {
            $status = 'pending-for-pharmacy-medicine-release';
        }



        $cloudViewUsage = [];


        $items = Item::query()->whereIn('id', $request->get('items'))->get();







        foreach ($request->get('items') as  $key => $item_id) {

            $item = $items->where('id', $item_id)->first();
            $cloudViewUsage[] = [
                'quantity' => $request->get('quantity')[$key],
                'details' => $request->get('sig')[$key],
                'cloud_id' => $id,
                'type' => 'prescription',
                'item_name' => $item->name,
                'prescribe_entity' => config('app.entity'),
                'prescribe_entity_key' => config('app.entity_key'),
                'prescribe_entity_unit' => config('app.entity_unit'),
            ];


            $usage[] = [
                'inventory_id' => $request->get('inventory_id')[$key],
                'quantity' => $request->get('quantity')[$key],
                'details' => $request->get('sig')[$key],
                'cloud_id' => $id,
                'item_id' => $item_id,
                'type' => 'prescription',
            ];

            // $inventory->quantity = $inventory->quantity - $request->get('quantity')[$key];

            // $inventory->save();
        }

        $client = new \GuzzleHttp\Client();
        $url = config('app.cloud_url') . '/api/tb-prescribe/' . $id;
        $data = [
            'status' => $status,
            'cloudViewUsage' => $cloudViewUsage,
            'prescribed_by_name' => $user->name,
        ];

        try {
            $response = $client->request('POST', $url, [
                'form_data' => $data
            ]);
            ItemUsage::query()->insert($usage);

            if ($response->getStatusCode() == 200) {
                return response()->json(['message' => 'Prescription sent to cloud'], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error in cloud'], 500);
        }
    }




    public function update(Request $request, int $id)
    {
        $usage = [];

        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->item_used = true;
        $appointment->save();

        $patientCase = PatientCase::query()->where('appointment_id', $id)->first();
        $user = request()->user();
        $caseItem = $patientCase?->case_items ? json_decode($patientCase->case_items) : [];
        $items = Item::query()->whereIn('id', $request->get('items'))->get();
        foreach ($request->get('items') as  $key => $item_id) {
            $item = $items->where('id', $item_id)->first();
            $inventory = ItemInventory::query()->where('location_id', $request->get('health_unit_id') ? $request->get('health_unit_id') : $user->health_user_id)->where('item_id', $item_id)->first();
            $usage[] = [
                'inventory_id' => $inventory->id,
                'quantity' => $request->get('quantity')[$key],
                'appointment_id' => $id,
                'item_id' => $item_id,
                'type' => 'laboratory',
            ];
            $caseItem[] = [
                'item_code' => $item->code,
                'quantity' => $request->get('quantity')[$key],
                'item_name' => $item->name,
                'type' => 'laboratory',
                'updated_by' => $user->name,
                'updated_at' => now()->toDateTimeString()
            ];

            $inventory->quantity = $inventory->quantity - $request->get('quantity')[$key];

            $inventory->save();
        }

        $patientCase->case_items = json_encode($caseItem);
        $patientCase->save();

        ItemUsage::query()->insert($usage);

        return AppointmentDataResource::make($appointment);
    }

    public function setTbPositive(DiseaseService $diseaseService, $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->is_tb_positive = 1;
        // $appointment->prescribed_by = request()->user()->id;
        $appointment->sph_status = 'pending-for-pharmacy-release';
        $appointment->status = 'pending-for-pharmacy-release';
        $appointment->save();
        $case = PatientCase::query()->where('appointment_id', $id)->first();
        $case->is_tb_positive = 1;
        $case->save();

        $history =  $diseaseService->createHistory($appointment->patient_id, [
            'disease' => 25,
        ]);

        //cloud positive tb
        return AppointmentDataResource::make($appointment);
    }

    public function signalTbPositive(DiseaseService $diseaseService, $id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->is_tb_positive = 1;
        $appointment->sph_status = 'pending-for-pharmacy-signal';
        $appointment->status = 'pending-for-pharmacy-signal';
        $appointment->save();

        return AppointmentDataResource::make($appointment);
    }

    public function setTbNegative(PhoPatientCaseService $service,$id)
    {
        $appointment = AppointmentData::query()->findOrFail($id);
        $appointment->is_tb_positive = 0;
        $appointment->status = 'done';
        $appointment->sph_status = 'done';
        $appointment->save();
        $case = PatientCase::query()->where('appointment_id', $id)->first();
        $case->is_tb_positive = 0;
        $case->status = 'done';
        $case->save();
        $service->updateCaseCloud($case->id,[
            'status' => 'service-done',
            'is_tb_positive' => 0,
        ]);
        return AppointmentDataResource::make($appointment);
    }

    public function getUserLocation()
    {
        $user = request()->user();
        $location = HealthUnit::first();
        if (str_contains($user->type, 'RHU')) {
            $location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
        }
        if ($user->type == 'LMIS-BHS' || $user->type == 'BHS-BHW') {
            $location = HealthUnit::query()->where('type', '=', 'BHS')->where('barangay_id', '=', $user->barangay)->first();
        }
        if ($user->type == 'LMIS-CNOR') {
            $location = HealthUnit::query()->where('type', 'CNOR')->first();
        }
        return $location;
    }
}
