<?php

namespace App\Services\MHRA;

class FireServerService
{

    private $base_url = 'https://iol.hie.fhi360.lanexcorp.com:5000';
    public function sendPost(array $data,string $params)
    {
        $client = new \GuzzleHttp\Client();
        $url =  $this->base_url . '/fhir'.$params;
        $response = $client->request(
            'POST',
            $url,
            [
                'cert' => storage_path('app/MHRA/fullchain.pem'),
                'ssl_key' => storage_path('app/MHRA/privkey.pem'),
                'json' => $data
            ]
        );

//        $body = json_decode($response->getBody());
//
//        return $body;
//
//        $curl = curl_init();
//        $curl_post_data = json_encode($data);
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => $this->base_url . '/fhir/',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_SSLCERT => storage_path('app/MHRA/fullchain.pem'),
//            CURLOPT_SSLKEY => storage_path('app/MHRA/privkey.pem'),
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => $curl_post_data,
//            CURLOPT_HTTPHEADER => array(
//                "Content-Type: application/json"
//            ),
//        ));
//
//        $response = curl_exec($curl);
//
//        //get status code
//        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//
//        curl_close($curl);
//
//        return [
//            'response' => json_decode($response),
//            'status_code' => $status_code
//        ];
//        return $response;


        //get response body
       return json_decode($response->getBody());
    }

    public function getList()
    {
        $client = new \GuzzleHttp\Client();
        $url =  "https://iol.hie.fhi360.lanexcorp.com:5000/fhir/Patient";
        $response = $client->request(
            'GET',
            $url,
            [
                'cert' => storage_path('app/MHRA/fullchain.pem'),
                'ssl_key' => storage_path('app/MHRA/privkey.pem')
            ]
        );
        return json_decode($response->getBody());
    }
}
