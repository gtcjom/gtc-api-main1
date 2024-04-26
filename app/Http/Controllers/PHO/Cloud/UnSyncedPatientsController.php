<?php

namespace App\Http\Controllers\PHO\Cloud;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\Cloud\PatientService;
use Illuminate\Support\Facades\Log;

class UnSyncedPatientsController extends Controller
{


    public function update(PatientService $patientServices)
    {
        $patientServices->synced();
        return response()->json([
            'message' => 'Patients synced successfully'
        ], 200);
    }


    public function verifyPatient(PatientService $patientServices, $id)
    {
        $patientServices->verifyPatientData($id);
        return response()->json([
            'message' => 'Patients verified successfully'
        ], 200);
    }


    public function count(PatientService $patientServices)
    {
        $result = $patientServices->countUnSynced();
        if ($result['success']) {
            return response()->json([
                'count' => $result['count']
            ], 200);
        }
        return response()->json($result['message'], 500);
    }


    public function show(PatientService $patientServices, string $id)
    {
        return $patientServices->show($id)['data']->dependents[0]->firstname;
    }
    public function unsyncPatients()
    {
        $lastUpdated = Patient::query()->latest('last_updated')->first();
        $lastSync = $lastUpdated?->last_updated;
        $client = new \GuzzleHttp\Client();
        //send request get request to cloud

        try {
            $url = config('app.cloud_url') . '/api/patients/un-synced-list?entity_type='
                . config('app.entity')
                . '&entity_key=' . config('app.entity_key')
                . '&lastSync=' . $lastSync
                . '&entity_unit=' . config('app.entity_unit')
                . '&page=' . 1;
            $response = $client->request(
                'GET',
                $url
            );

            if ($response->getStatusCode() == 200) {
                //get response body
                //return response
                $body = json_decode($response->getBody());
                $cloudPatients = $body->data;

                return $cloudPatients;
            }
        } catch (\Exception $e) {

            Log::alert('Unable to connect cloud', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
        }
    }
}
