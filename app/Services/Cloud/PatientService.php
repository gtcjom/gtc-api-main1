<?php

namespace App\Services\Cloud;

use App\Jobs\SyncedPatientJob;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PatientService
{

    public function countUnSynced(): array
    {
        $lastUpdated = Patient::query()->latest('last_updated')->first();
        $lastSync = $lastUpdated?->last_updated;
        //init guzzle client
        $client = new \GuzzleHttp\Client();
        //send request get request to cloud


        try {
            $url = config('app.cloud_url') . '/api/patients/un-synced-count?entity_type='
                . config('app.entity')
                . '&entity_key=' . config('app.entity_key')
                . '&entity_unit=' . config('app.entity_unit')
                . '&lastSync=' . $lastSync;
            $response = $client->request(
                'GET',
                $url
            );





            //check status code
            if ($response->getStatusCode() == 200) {
                //get response body
                $body = json_decode($response->getBody());
                //return response
                return [
                    'count' => $body,
                    'success' => true
                ];
            }


            return [
                'message' => 'Unable to retrieve data from cloud',
                'error' => 'Error occurred while fetching data from cloud',
                'success' => false
            ];
        } catch (\Exception $e) {

            Log::alert('Unable to connect cloud', ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return [
                'message' => 'Unable to connect cloud',
                'error' => $e->getMessage(),
                'success' => false
            ];
        }

        return [
            'message' => 'Unable to connect cloud',
            'error' => 'Error occurred while fetching data from cloud',
            'success' => false
        ];
    }


    public function synced()
    {

        $count = $this->countUnSynced();

        if ($count['success'] && $count['count'] > 0) {
            $lastUpdated = Patient::query()->latest('last_updated')->first();
            $lastSync = $lastUpdated?->last_updated;

            for ($i = 1; $i <= $count['count']; $i++) {
                SyncedPatientJob::dispatchSync($lastSync, $i);
            }
        }
    }

    public function verifyPatientData($id)
    {
        $user = request()->user();
        $patient = Patient::query()->findOrFail($id);
        $patient->verified = 1;
        $patient->verified_at = Carbon::now()->toDateTimeString();
        $patient->verified_by = $user?->username ?? 'admin';
        $patient->verified_by_entity = 'BHS';
        $patient->save();

        $cloud = new SendToCloudService();

        return $cloud->patientVerify($id);
    }

    public function show(string $id)
    {
        $client = new \GuzzleHttp\Client();
        //send request get request to cloud

        try {
            $url = config('app.cloud_url') . '/api/patients/' . $id;
            $response = $client->request(
                'GET',
                $url
            );





            //check status code
            if ($response->getStatusCode() == 200) {
                //get response body
                $body = json_decode($response->getBody());
                //return response
                return [
                    'data' => $body->data,
                    'success' => true
                ];
            }


            return [
                'message' => 'Unable to retrieve data from cloud',
                'error' => 'Error occurred while fetching data from cloud',
                'success' => false
            ];
        } catch (\Exception $e) {

            Log::alert('Unable to connect cloud', ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return [
                'message' => 'Unable to connect cloud',
                'error' => $e->getMessage(),
                'success' => false
            ];
        }

        return [
            'message' => 'Unable to connect cloud',
            'error' => 'Error occurred while fetching data from cloud',
            'success' => false
        ];
    }
}
