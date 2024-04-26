<?php

namespace App\Services\Cloud;

use App\Models\Patient;

class PmrfPatientService
{

    public function get(array $where)
    {


        $done_at = null;

        if(isset($where['pmrf_done_at'])){
            $done_at = $where['pmrf_done_at'];
            unset($where['pmrf_done_at']);
        }


        $patient = new Patient();

        $result = $patient->setConnection('mysql2')->where($where)

            ->whereNotNull('pmrf_done_at')
            ->when(!is_null($done_at), function($query) use ($done_at){
                return $query->where('pmrf_done_at', '>',$done_at);
            })
            ->paginate();

        return [
            'total_count' => $result->total(),
            'data' => $result->items()
        ];

    }




    public function getCloud(array $where)
    {

        $client = new \GuzzleHttp\Client();
        $url = config('app.pho_url') . '/api/get-pmrf-done-list';

        $response = $client->request(
            'GET',
            $url,
            [
                'query' => $where
            ]
        );

        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody());
            return [
                'total_count' => $body->total_count,
                'data' => $body->data
            ];
        }

        return [
            'message' => 'Unable to retrieve data from cloud',
            'error' => 'Error occurred while fetching data from cloud',
            'success' => false
        ];

    }


    public function sync(array $cloudPatients)
    {
        foreach ($cloudPatients as $cloudPatient) {
            $patient = Patient::query()->where('id', $cloudPatient->id)->first();
            if (is_null($patient)) {
                $patient = new Patient();
            }

            $patient->fill((array)$cloudPatient);
            $patient->cloud_id = $cloudPatient->id;
            $patient->save();
        }
    }

}
