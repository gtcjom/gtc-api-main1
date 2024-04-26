<?php

namespace App\Services\Cloud;

use App\Models\AppointmentData;
use App\Models\CloudUnSent;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class SendToCloudService
{


    public function sentToCloud(int $id)
    {
        $patient = Patient::find($id);
        if($patient->cloud_id){
            return $this->updatePatient($id);
        }
    }
    public function updatePatient(int $id)
    {
        $patient = Patient::find($id);

        //get all patient properties
        $patientData = $patient->toArray();
        //remove id
        unset($patientData['id']);
        unset($patientData['created_at']);
        unset($patientData['updated_at']);


        //create guzzle post request
        $client = new \GuzzleHttp\Client();
        try {
            $url = config('app.cloud_url') . '/api/patients/update';
            $response = $client->request(
                'POST',
                $url,
                [
                    'form_params' => $patientData
                ]
            );
            if ($response->getStatusCode() == 200) {

                $body = json_decode($response->getBody());
                $patient->cloud_id = $body->data->cloud_id;
                $patient->last_updated = $body->data->last_updated;
                $patient->unsent = 0;
                $patient->save();
                return [
                    'message' => 'Patient updated successfully',
                    'success' => true
                ];
            }
            $patient->unsent = 1;
            $patient->save();

            $this->storeToUnSend('patient', 'update', $id);
            return [
                'message' => 'Unable to update patient',
                'error' => 'Error occurred while updating patient',
                'success' => false
            ];
        } catch (\Exception $e) {
            $patient->unsent = 1;
            $patient->save();

            $this->storeToUnSend('patient', 'update', $id);

            return [
                'message' => 'Unable to connect cloud',
                'error' => $e->getMessage(),
                'success' => false
            ];
        }
    }
    public function createPatient(int $id)
    {
        $patient = Patient::find($id);

        //get all patient properties
        $patientData = $patient->toArray();
        //remove id, created_at, updated_at
        unset($patientData['id']);
        unset($patientData['created_at']);
        unset($patientData['updated_at']);

        $client = new \GuzzleHttp\Client();
        try {
            $url = config('app.cloud_url') . '/api/patients';
            $response = $client->request(
                'POST',
                $url,
                [
                    'form_params' => $patientData
                ]
            );
            if ($response->getStatusCode() == 200) {
                $patient->unsent = 0;
                $patient->save();
                return [
                    'message' => 'Patient created successfully',
                    'success' => true
                ];
            }
            $patient->unsent = 1;
            $body = json_decode($response->getBody());
            $patient->cloud_id = $body->data->cloud_id;
            $patient->last_updated = $body->data->last_updated;
            $patient->save();
            $this->storeToUnSend('patient', 'create', $id);
            return [
                'message' => 'Unable to create patient',
                'error' => 'Error occurred while updating patient',
                'success' => false
            ];
        } catch (\Exception $e) {
            $patient->unsent = 1;
            $patient->save();
            $this->storeToUnSend('patient', 'create', $id);

            return [
                'message' => 'Unable to connect cloud',
                'error' => $e->getMessage(),
                'success' => false
            ];
        }
    }


    public function patientVerify($id): array
    {
        $patient = Patient::find($id);

        if(is_null($patient->cloud_id)){
            return $this->createPatient($id);
        }

        $client = new \GuzzleHttp\Client();
        try {
            $data = [
                'cloud_id' => $patient->cloud_id,
                'entity' => config('app.entity'),
                'entity_key' => config('app.entity_key'),
                'entity_unit' => config('app.entity_unit'),
                'verified' => true,
                'verifiedAt' => $patient->verified_at,
                'verifiedBy' => $patient->verified_by,
                'verifiedByEntity' => $patient->verified_by_entity
            ];
            $url = config('app.cloud_url') . '/api/patients/verified/'. $patient->cloud_id;
            $response = $client->request(
                'POST',
                $url,
                [
                    'form_params' => $data
                ]
            );
            if ($response->getStatusCode() == 200) {
                $patient->unsent = 0;
                $patient->save();
                return [
                    'message' => 'Patient sync successfully',
                    'success' => true
                ];
            }
            $patient->unsent = 1;
            $patient->save();
            $this->storeToUnSend('patient', 'verify', $id);
            return [
                'message' => 'Unable to sync patient',
                'error' => 'Error occurred while updating patient',
                'success' => false
            ];
        } catch (\Exception $e) {
            $patient->unsent = 1;
            $patient->save();
            $this->storeToUnSend('patient', 'verify', $id);

            return [
                'message' => 'Unable to connect cloud',
                'error' => $e->getMessage(),
                'success' => false
            ];
        }
    }



    public function storeToUnSend(string $type, string $category, int $typeId)
    {
        return CloudUnSent::query()->firstOrCreate([
            'type' => $type,
            'category' => $category,
            'type_id' => $typeId
        ]);
    }

    public function referToRhuCreate(int $appointment_id)
    {
        $appointment = AppointmentData::query()->with([
            'patient','referredToDoctor','servicedBy','rhu.municipality', 'rhu.barangay'
        ])->find($appointment_id);
        $patient = $appointment->patient;

        $appointment->refer_to_doctor_name = $appointment->referredToDoctor?->name ?? "";
        $appointment->patient_name = $patient->fullName() ?? "";
        $appointment->serviced_by_name = $appointment->servicedBy?->name ?? "";
        $appointment->save();

        if(is_null($patient->cloud_id)){
           $this->createPatient($patient->id);
        }

        $client = new \GuzzleHttp\Client();

        $municipality = \App\Models\Municipality::query()->find($appointment->rhu->municipality_id);
        $barangay = \App\Models\Barangay::query()->find($appointment->rhu->barangay_id);


        $data  = [
            'patient_id' => $patient->cloud_id,
            'entity' => config('app.entity'),
            'entity_key' => config('app.entity_key'),
            'entity_unit' => config('app.entity_unit'),
            'referred_by' => $appointment->referred_by,
            'reason' => $appointment->reason,
            'time' => $appointment->time,
            'date' => $appointment->date,
            'health_insurrance_coverage' => $appointment->health_insurrance_coverage,
            'health_insurrance_coverage_if_yes_type' => $appointment->health_insurrance_coverage_if_yes_type,
            'action_taken' => $appointment->action_taken,
            'impression' => $appointment->impression,
            'lab_findings' => $appointment->lab_findings,
            'clinical_history' => $appointment->clinical_history,
            'refer_to_doctor_name' => $appointment->refer_to_doctor_name,
            'patient_name' => $appointment->patient_name,
            'referred_by_name' => $appointment->referred_by_name,
            'referred_type' => $appointment->referred_type,
            'pre_notes' => $appointment->pre_notes,
            'status' => $appointment->status,
            'serviced_by_name' => $appointment->serviced_by_name,
            'refer_type' => $appointment->refer_type,
            'refer_to_key' => $municipality?->name ?? "",
            'refer_to_unit' => $barangay?->name ?? "",

        ];


        try {
            $url = config('app.cloud_url') . '/api/patients/refer-to-rhu';
            $response = $client->request(
                'POST',
                $url,
                [
                    'form_params' => $data
                ]
            );
            if ($response->getStatusCode() == 200) {

                $body = json_decode($response->getBody());
                $appointment->cloud_id = $body->data->id;
                $appointment->last_updated = $body->data->updated_at;
                $appointment->save();
                return [
                    'message' => 'Patient referred successfully',
                    'success' => true
                ];
            }

            $this->storeToUnSend('appointment', 'create', $appointment_id);
            return [
                'message' => 'Unable to refer patient',
                'error' => 'Error occurred while referring patient',
                'success' => false
            ];
        } catch (\Exception $e) {

            $this->storeToUnSend('appointment', 'create', $appointment_id);

            return [
                'message' => 'Unable to connect cloud',
                'error' => $e->getMessage(),
                'success' => false
            ];
        }
    }

    public function referToRhuUpdate(int $appointment_id)
    {
        $appointment = AppointmentData::query()->with(['patient'])->find($appointment_id);
        $patient = $appointment->patient;
        $appointment->refer_to_doctor_name = $appointment->referredToDoctor?->name ?? "";
        $appointment->patient_name = $patient->fullName() ?? "";
        $appointment->serviced_by_name = $appointment->servicedBy?->name ?? "";
        $appointment->save();

        if(is_null($patient->cloud_id)){
            $this->createPatient($patient->id);
        }

        $client = new \GuzzleHttp\Client();

        $data  = [
            'patient_id' => $patient->cloud_id,
            'entity' => config('app.entity'),
            'entity_key' => config('app.entity_key'),
            'entity_unit' => config('app.entity_unit'),
            'referred_by' => $appointment->referred_by,
            'reason' => $appointment->reason,
            'time' => $appointment->time,
            'date' => $appointment->date,
            'health_insurrance_coverage' => $appointment->health_insurrance_coverage,
            'health_insurrance_coverage_if_yes_type' => $appointment->health_insurrance_coverage_if_yes_type,
            'action_taken' => $appointment->action_taken,
            'impression' => $appointment->impression,
            'lab_findings' => $appointment->lab_findings,
            'clinical_history' => $appointment->clinical_history,
            'refer_to_doctor_name' => $appointment->refer_to_doctor_name,
            'patient_name' => $appointment->patient_name,
            'referred_by_name' => $appointment->referred_by_name,
            'referred_type' => $appointment->referred_type,
            'pre_notes' => $appointment->pre_notes,
            'status' => $appointment->status,
            'serviced_by_name' => $appointment->serviced_by_name,
            'approved_by_name' => $appointment->approved_by_name
        ];


        $data = array_map(function($value){
            return [
                'name' => $value,
                'contents' => $value
            ];
        }, $data);

        if($appointment->specimen_picture){
            $data[] = [
                'name' => 'specimen_picture',
                'contents' => fopen(storage_path('app/public/'.$appointment->specimen_picture), 'r')
            ];
        }





        try {
            $url = config('app.cloud_url') . '/api/patients/refer-to-rhu/'. $appointment->cloud_id;
            $response = $client->request(
                'POST',
                $url,
                [
                    'multipart' => $data
                ]
            );
            if ($response->getStatusCode() == 200) {

                $body = json_decode($response->getBody());
                $appointment->cloud_id = $body->data->id;
                $appointment->last_updated = $body->data->updated_at;
                $appointment->save();
                return [
                    'message' => 'Patient referred successfully',
                    'success' => true
                ];
            }

            $this->storeToUnSend('appointment', 'create', $appointment_id);
            return [
                'message' => 'Unable to refer patient',
                'error' => 'Error occurred while referring patient',
                'success' => false
            ];
        } catch (\Exception $e) {

            $this->storeToUnSend('appointment', 'create', $appointment_id);

            return [
                'message' => 'Unable to connect cloud',
                'error' => $e->getMessage(),
                'success' => false
            ];
        }
    }



    public function downloadAndSave($url)
    {

        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Create a new Guzzle client instance
        $client = new \GuzzleHttp\Client();

        try {
            // Send a GET request to the provided URL
            $response = $client->get($url);

            // Get the contents of the response
            $imageContent = $response->getBody()->getContents();

            // Generate a unique filename
            $filename = uniqid() . '_' . basename($url);

            // Save the image to the storage directory
            Storage::put('images/' . $filename, $imageContent);

            // Return the filename or URL to the saved image
            return Storage::url('images/' . $filename);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function referToDoctorForConfirmation(array $data, string $path)
    {
        $appointment = AppointmentData::find($data['appointment_id']);

        $appointment->status = 'pending-doctor-confirmation';
        $appointment->referred_to_name = $data['referred_to_name'];
        $appointment->serviced_by_name = $data['serviced_by_name'];
        $appointment->specimen_picture = $path;
        $appointment->save();

        if (is_null($appointment->patient->cloud_id)) {
            $this->createPatient($appointment->patient->id);
        }

        $client = new \GuzzleHttp\Client();

        $data = [

        ];
    }



}
