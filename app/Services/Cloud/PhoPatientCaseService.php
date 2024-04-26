<?php

namespace App\Services\Cloud;

use App\Models\PatientCase;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PhoPatientCaseService
{

    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    public function create(string $id)
    {
        $patientCase = PatientCase::query()->find($id);

        $data = $patientCase->toArray();

        try {
            $client = new \GuzzleHttp\Client();
            $url = config('app.pho_url') . '/api/send-case-to-cloud';





            //multipart data
            $multipart = [];
            foreach ($data as $key => $value) {
                if($key != 'specimen_picture' || $key != 'case_picture'){
                    $multipart[] = [
                        'name' => $key,
                        'contents' => $value
                    ];
                }
            }
            //specimen_picture
            if ($patientCase->specimen_picture) {

                $multipart[] = [
                    'name' => 'specimen_picture',
                    'contents' => fopen(storage_path('app/public/' . $patientCase->specimen_picture), 'r')
                ];
            }

            if($patientCase->case_picture){
                $multipart[] = [
                    'name' => 'case_picture',
                    'contents' => fopen(storage_path('app/public/' . $patientCase->case_picture), 'r')
                ];
            }

            $response = $client->request('POST', $url, [
                'multipart' => $multipart
            ]);





            if($response->getStatusCode() != 200){
                throw new \Exception('Failed to send case to cloud');
            }

            $body = json_decode($response->getBody(), true);


            $patientCase->cloud_id = $body['data']['id'];
            $patientCase->save();

        }catch (\Exception $e) {
            Log::alert('Failed to send case to cloud: ', [
                'message' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to send case to cloud');
        }






    }


    public function store(array $data)
    {
        $patientCase = new PatientCase();
        $patientCase->setConnection('mysql2');

        foreach ($data as $key => $value) {
            $patientCase->$key = $value;
        }

        $patientCase->save();


        return $patientCase;
    }

    public function downLoadCase(array $data)
    {
        $patientCase =  PatientCase::firstOrNew($data['id']);
        foreach ($data as $key => $value) {
            if($key != 'specimen_picture_url')
            $patientCase->$key = $value;
        }

        if(is_null($patientCase->specimen_picture) && isset($data['specimen_picture_url'])){
            $specimen_picture = $this->downloadAndSave($data['specimen_picture_url']);
            if($specimen_picture){
                $patientCase->specimen_picture = $specimen_picture;
            }
        }



    }

    public function downloadAndSave($url): bool|string
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
            $path = 'images/' . $filename;
            // Save the image to the storage directory
             Storage::put($path, $imageContent);

             return $path;

        } catch (\Exception $e) {
            // Handle any errors
            return false;
        }
    }


    public function referrals($health_unit_id, $referred_type)
    {
        $query = new PatientCase();
        $query->setConnection('mysql2');
        $result = $query->where('is_referral', 1)
            ->where('referred_type', $referred_type)
            ->where('referred_to', $health_unit_id)
            ->get();

        return [
            'data' => $result,
            'count' => $result->count()
        ];
    }


    public function getListOfReferrals($health_unit_id)
    {
        $client = new \GuzzleHttp\Client();
        $url = config('app.pho_url') . '/api/get-rhu-referrals';
        try{
            $response = $client->request('GET', $url, [
                'query' => [
                    'health_unit_id' => $health_unit_id,
                ]
            ]);

            if($response->getStatusCode() != 200){
               return [
                   'data' => [],
                   'count' => 0,
                   'message' => 'Failed to get list of referrals'
               ];
            }

            return json_decode($response->getBody()->getContents(), true);
        }catch (\Exception $e){
            Log::alert('Failed to get list of referrals: ' ,[
                'message' => $e->getMessage(),
            ]);
            return [
                'data' => [],
                'count' => 0,
                'message' => 'Failed to get list of referrals'
            ];
        }

    }


    public function getListOfReadings($health_unit_id,$doctor_id)
    {
        $client = new \GuzzleHttp\Client();
        $url = config('app.pho_url') . '/api/get-rhu-doctor-reading';
        try{
            $response = $client->request('GET', $url, [
                'query' => [
                    'health_unit_id' => $health_unit_id,
                    'doctor_id' => $doctor_id
                ]
            ]);

            if($response->getStatusCode() != 200){
                return [
                    'data' => [],
                    'count' => 0,
                    'message' => 'Failed to get list of reading'
                ];
            }

            return json_decode($response->getBody()->getContents(), true);
        }catch (\Exception $e){
            Log::alert('Failed to get list of reading: ' ,[
                'message' => $e->getMessage(),
            ]);
            return [
                'data' => [],
                'count' => 0,
                'message' => 'Failed to get list of reading'
            ];
        }

    }


    public function updateCaseVitals(string $id, array $data)
    {
        $patientCase = new PatientCase();
        $patientCase->setConnection('mysql2');
        $patientCase = $patientCase->find($id);

        $vitals = $patientCase->vitals ?? [];
        $vitals[] = $data;
        $patientCase->vitals = json_encode($vitals);
        $patientCase->save();

        return $patientCase;
    }


    public function updateCloudVital(string $id, array $data)
    {
        $client = new \GuzzleHttp\Client();
        $url = config('app.pho_url') . '/api/update-vitals/'.$id;
        $dataPatch = $data;
        $data['_method'] = 'PATCH';
        try{
            $response = $client->requestAsync('POST', $url, [
                'json' => $data
            ])->then(function ($response){
                return $response;
            })->wait();

            if($response->getStatusCode() != 200){
                return [
                    'message' => 'Failed to update vitals',
                    'success' => false
                ];
            }

            return [
                'message' => 'Vitals updated successfully',
                'success' => true
            ];
        }catch (\Exception $e){
            Log::alert('Failed to update vitals: ' ,[
                'message' => $e->getMessage(),
            ]);
            return [
                'message' => 'Failed to update vitals',
                'success' => false
            ];
        }
    }


    public function updateCase(string $id,array $data)
    {
        $patientCase = new PatientCase();
        $patientCase->setConnection('mysql2');
        $patientCase = $patientCase->find($id);

        foreach ($data as $key => $value) {
            $patientCase->$key = $value;
        }

        $patientCase->save();

        if($patientCase->specimen_picture){
            $patientCase->specimen_picture = Storage::url($patientCase->specimen_picture);
        }

        if($patientCase->case_picture){
            $patientCase->case_picture = Storage::url($patientCase->case_picture);
        }

        return $patientCase;
    }



    public function updateCaseCloud(string $id, array $data)
    {




        $client = new \GuzzleHttp\Client();
        $url = config('app.pho_url') . '/api/update-case/'.$id;


        $data['_method'] = 'PATCH';
        try{
            $response = $client->requestAsync('POST', $url, [
                'json' => $data
            ])->then(function ($response){
                return $response;
            })->wait();

            if($response->getStatusCode() != 200){
                return [
                    'message' => 'Failed to update Case',
                    'success' => false
                ];
            }

            $body = json_decode($response->getBody(), true);

            return [
                'message' => 'Case updated successfully',
                'success' => true,
                'data' => $body
            ];
        }catch (\Exception $e){
            Log::alert('Failed to update Case: ' ,[
                'message' => $e->getMessage(),
            ]);
            return [
                'message' => 'Failed to update Case',
                'success' => false
            ];
        }
    }


    public function getCaseList(array $where)
    {
        $patientCase = new PatientCase();
        $result = $patientCase->setConnection('mysql2')->where($where)->get();
        return [
            'data' => $result,
            'count' => $result->count()
        ];
    }

    public function getCloudCaseList(array $where)
    {
        $client = new \GuzzleHttp\Client();
        $url = config('app.pho_url') . '/api/get-case-list';


        try{
            $response = $client->request('GET', $url, [
                'query' => $where
            ]);

            if($response->getStatusCode() != 200){
                return [
                    'data' => [],
                    'count' => 0,
                    'message' => 'Failed to get list of case'
                ];
            }

            return json_decode($response->getBody()->getContents(), true);
        }catch (\Exception $e){
            Log::alert('Failed to get list of case: ' ,[
                'message' => $e->getMessage(),
            ]);
            return [
                'data' => [],
                'count' => 0,
                'message' => 'Failed to get list of case'
            ];
        }
    }



    public function findCloudCase(string $id)
    {
        $patientCase = new PatientCase();
        $patientCase->setConnection('mysql2');
        return [
            'data' => $patientCase->findOrFail($id)
        ];
    }

    public function downLoadAndSaveCase(string $id)
    {
        $client = new \GuzzleHttp\Client();
        $url = config('app.pho_url') . '/api/get-case/'.$id;
        try{
            $response = $client->request('GET', $url);

            if($response->getStatusCode() != 200){
                return [
                    'message' => 'Failed to download case',
                    'success' => false
                ];
            }

            $result = json_decode($response->getBody());

            $caseCloud = $result->data;
            $case = PatientCase::query()->find($id);

            if(!is_null($case)){

                return [
                    'message' => 'Already downloaded case',
                    'success' => false
                ];
            }

            if(!$case){
                $case = new PatientCase();
            }

            foreach ($caseCloud as $key => $value) {
                if($key != 'specimen_picture_url')
                $case->$key = $value;
            }
            $case->id = $id;
            $case->cloud_id = $id;
            $case->save();


            return [
                'message' => 'Case downloaded successfully',
                'data' => $case,
                'success' => true

            ];
        }catch (\Exception $e){
            Log::alert('Failed to download case: ' ,[
                'message' => $e->getMessage(),
            ]);
            return [
                'message' => 'Failed to download case',
                'success' => false
            ];
        }
    }



}
