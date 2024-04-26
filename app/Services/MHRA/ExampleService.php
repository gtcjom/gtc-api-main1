<?php

namespace App\Services\MHRA;

use Illuminate\Support\Str;

class ExampleService
{

    private $base_url = 'https://iol.hie.fhi360.lanexcorp.com:5000';

    public function bundle()
    {

//        {
//            "fullUrl": "urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae875",
//      "resource": {
//            "resourceType": "Encounter",
//        "subject": {
//                "reference": "Patient/urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae876"
//        },
//        "status": "in-progress",
//        "class": {
//                "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
//          "code": "AMB"
//        },
//        "period": {
//                "start": "2024-03-01",
//          "end": "2024-03-01"
//        }
//      },
//      "request": {
//            "method": "POST",
//        "url": "Encounter"
//      }
//    },
//        {
//            "fullUrl": "urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae874",
//      "resource": {
//            "resourceType": "Observation",
//        "status": "final",
//        "subject": {
//                "reference": "Patient/urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae876"
//        },
//        "encounter": {
//                "reference": "Encounter/urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae875"
//        },
//        "effectiveDateTime": "2024-03-01",
//        "code": {
//                "coding": [
//            {
//                "system": "http://loinc.org",
//              "code": "8302-2",
//              "display": "Body height"
//            }
//          ]
//        },
//        "valueQuantity": {
//                "value": 180,
//          "unit": "cm",
//          "system": " ",
//          "code": "cm"
//        }
//      },
//      "request": {
//            "method": "POST",
//        "url": "Observation"
//      }
//    },
//        {
//            "fullUrl": "urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae873",
//      "resource": {
//            "resourceType": "Observation",
//        "status": "final",
//        "subject": {
//                "reference": "Patient/urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae876"
//        },
//        "encounter": {
//                "reference": "Encounter/urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae875"
//        },
//        "effectiveDateTime": "2024-03-01",
//        "code": {
//                "coding": [
//            {
//                "system": "http://loinc.org",
//              "code": "29463-7",
//              "display": "Body weight"
//            }
//          ]
//        },
//        "valueQuantity": {
//                "value": 75,
//          "unit": "kg",
//          "system": "http://unitsofmeasure.org",
//          "code": "kg"
//        }
//      },
//      "request": {
//            "method": "POST",
//        "url": "Observation"
//      }
//    },


        //convert to data array

//        $data = [
//            [
//                'fullUrl' => 'urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae875',
//                'resource' => [
//                    'resourceType' => 'Encounter',
//                    'subject' => [
//                        'reference' => 'Patient/20047'
//                    ],
//                    'status' => 'in-progress',
//                    'class' => [
//                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
//                        'code' => 'AMB'
//                    ],
//                    'period' => [
//                        'start' => '2024-03-01',
//                        'end' => '2024-03-01'
//                    ]
//                ],
//                'request' => [
//                    'method' => 'POST',
//                    'url' => 'Encounter'
//                ]
//            ],
//            [
//                'fullUrl' => 'urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae874',
//                'resource' => [
//                    'resourceType' => 'Observation',
//                    'status' => 'final',
//                    'subject' => [
//                        'reference' => 'Patient/20047'
//                    ],
//                    'encounter' => [
//                        'reference' => 'Encounter/urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae875'
//                    ],
//                    'effectiveDateTime' => '2024-03-01',
//                    'code' => [
//                        'coding' => [
//                            [
//                                'system' => 'http://loinc.org',
//                                'code' => '8302-2',
//                                'display' => 'Body height'
//                            ]
//                        ]
//                    ],
//                    'valueQuantity' => [
//                        'value' => 180,
//                        'unit' => 'cm',
//                        'system' => ' ',
//                        'code' => 'cm'
//                    ]
//                ],
//                'request' => [
//                    'method' => 'POST',
//                    'url' => 'Observation'
//                ]
//            ],
//        ];

        $uuid = Str::uuid();
        $data = [
            "resourceType" => "Bundle",
            "type" => "transaction",
            "entry" => [
                [
                    "fullUrl" => "urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae872",
                    'resource' => [
                    'resourceType' => 'Encounter',
                    'subject' => [
                        'reference' => 'Patient/20047'
                    ],
                    'status' => 'in-progress',
                    'class' => [
                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                        'code' => 'AMB'
                    ],
                    'period' => [
                        'start' => '2024-03-01',
                        'end' => '2024-03-01'
                    ]
                ],
                'request' => [
                    'method' => 'POST',
                    'url' => 'Encounter'
                ]
            ],
                [
                'fullUrl' => 'urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae874',
                'resource' => [
                    'resourceType' => 'Observation',
                    'status' => 'final',
                    'subject' => [
                        'reference' => 'Patient/20047'
                    ],
                    'encounter' => [
                        'reference' => 'Encounter/urn:uuid:48f4ff0f-c3c5-42fc-b839-a025966ae872'
                    ],
                    'effectiveDateTime' => '2024-03-01',
                    'code' => [
                        'coding' => [
                            [
                                'system' => 'http://loinc.org',
                                'code' => '8302-2',
                                'display' => 'Body height'
                            ]
                        ]
                    ],
                    'valueQuantity' => [
                        'value' => 180,
                        'unit' => 'cm',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'cm'
                    ]
                ],
                'request' => [
                    'method' => 'POST',
                    'url' => 'Observation'
                ]
            ],

                ]
        ];

        return $data;


        $curl = curl_init();
        $curl_post_data = json_encode($data);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . '/fhir/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSLCERT => storage_path('app/MHRA/fullchain.pem'),
            CURLOPT_SSLKEY => storage_path('app/MHRA/privkey.pem'),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $curl_post_data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        //get status code
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return [
            'response' => json_decode($response),
            'status_code' => $status_code
        ];
        return $response;


        $bundle = [
            'resourceType' => 'Bundle',
            'type' => 'transaction',
            'entry' => $data
        ];

//        $fire = new FireServerService();
//        return $fire->sendPost($bundle, '/');
    }
}
