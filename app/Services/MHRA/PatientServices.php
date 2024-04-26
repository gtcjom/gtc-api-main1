<?php

namespace App\Services\MHRA;

use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PatientServices
{


    public function createPatient(int $patientId)
    {

        $patient = Patient::query()
            ->whereNotNull('philhealth')
            ->whereNull('fire_id')
            ->findOrFail($patientId);

        $data = [
            "resourceType" => "Patient",
            "meta" => [
                "profile" => [
                    "https://hie.doh.gov.ph/fhir/StructureDefinition/HIEPatientV1"
                ]
            ],
            "identifier" => [
                [
                    "use" => "official",
                    "system" => "https://hie.doh.gov.ph/id/philhealth",
                    "value" => $patient->philhealth
                ]
            ],
            "name" => [
                [
                    "use" => "official",
                    "family" => $patient->lastname,
                    "given" => [
                        $patient->firstname
                    ],
                    "suffix" => [
                        $patient->suffix ?? ""
                    ],
                    "extension" => [
                        [
                            "url" => "https://hie.doh.gov.ph/fhir/StructureDefinition/middleName",
                            "valueString" => $patient->middle ?? ""
                        ]
                    ]
                ]
            ],
            "telecom" => [
                [
                    "system" => "phone",
                    "value" => $patient->mobile ?? ""
                ],
                [
                    "system" => "email",
                    "value" => $patient->email ?? "",
                ],
            ],
            "gender" => Str::lower($patient->gender),
            "birthDate" => $patient->birthday,
            "address" => [
                [
                    "extension" => [
                        [
                            "url" => "https://hie.doh.gov.ph/fhir/StructureDefinition/NSCBAddress",
                            "extension" => [
                                [
                                    "url" => "regionCode",
                                    "valueCoding" => [
                                        'system' => 'http://hie.doh.gov.ph/nscb-location',
                                        'code' => '070000000',
                                        'display' => 'Region 7'
                                    ]
                                ],
                                [
                                    "url" => "provinceCode",
                                    "valueCoding" => [
                                        'system' => 'http://hie.doh.gov.ph/nscb-location',
                                        'code' => '072200000',
                                        'display' => 'Cebu'
                                    ]
                                ],
                                [
                                    "url" => "cityCode",
                                    "valueCoding" => [
                                        'system' => 'http://hie.doh.gov.ph/nscb-location',
                                        'code' => '072217000',
                                        'display' => 'Cebu City (Capital)'
                                    ]
                                ],
                                [
                                    "url" => "barangayCode",
                                    "valueCoding" => [
                                        'system' => 'http://hie.doh.gov.ph/nscb-location',
                                        'code' => '072217081',
                                        'display' => 'Talamban'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "line" => [
                        $patient->address_block,
                    ],
                ]
            ]
        ];


        $fireServerService = new FireServerService();
        $response = $fireServerService->sendPost($data, '/Patient');
        Log::info('Patient created', ['response' => $response]);
        return $response;

    }


    /**
     * @throws \Exception
     */
    private function regionCode($code): array
    {
        //display loinc code list in array
        $data = [
            'r7' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '070000000',
                'display' => 'Region 7'
            ],
            'r8' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '080000000',
                'display' => 'Region 8'
            ],
            'r9' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '090000000',
                'display' => 'Region 9'
            ],
            'r10' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '100000000',
                'display' => 'Region 10'
            ],
            'r11' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '110000000',
                'display' => 'Region 11'
            ],
            'r12' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '120000000',
                'display' => 'REGION XII (SOCCSKSARGEN)'
            ],
        ];

        if (!array_key_exists($code, $data)) {

            throw new \Exception('Code not supported');
        }

        return $data[$code];
    }

    /**
     * @throws \Exception
     */
    private function saranganiProvinceCode($code): array
    {
        //display loinc code list in array
        $data = [
            'sarangani' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '120000000',
                'display' => 'Sarangani'
            ]
        ];

        //if code is not found in data key throw exception
        if (!array_key_exists($code, $data)) {

            throw new \Exception('Code not supported');
        }


        return $data[$code];

    }

    private function cityCode($code): array
    {
        //display sarangani city code list in array

        $data = [
            'alabel' => [
                'system' => 'http://hie.doh.gov.ph/nscb-location',
                'code' => '120100000',
                'display' => 'Alabel'
            ],

        ];

        if (!array_key_exists($code, $data)) {
            throw new \Exception('Code not supported');
        }

        return $data[$code];

    }

    private function barangayCode($code): array
    {
        $data = [
            'alabel' => [

                'alegria' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701001',
                    'display' => 'Alegria'
                ],
                'bagacay' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701002',
                    'display' => 'Bagacay'
                ],
                'baluntay' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701003',
                    'display' => 'Baluntay'
                ],
                'datal_anggas' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701004',
                    'display' => 'Datal Anggas'
                ],
                'domolok' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701005',
                    'display' => 'Domolok'
                ],
                'kawas' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701006',
                    'display' => 'Kawas'
                ],
                'ladol' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701007',
                    'display' => 'Ladol'
                ],
                'maribulan' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701008',
                    'display' => 'Maribulan'
                ],
                'new_canaan' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701009',
                    'display' => 'New Canaan'
                ],
                'pag_asa' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701010',
                    'display' => 'Pag-Asa'
                ],
                'paraiso' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701011',
                    'display' => 'Paraiso'
                ],
                'poblacion' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701012',
                    'display' => 'Poblacion'
                ],
                'spring' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701013',
                    'display' => 'Spring'
                ],
                'tokawal' => [
                    'system' => 'http://hie.doh.gov.ph/nscb-location',
                    'code' => '082701014',
                    'display' => 'Tokawal'
                ],
            ]
        ];

        if (!array_key_exists($code, $data['alabel'])) {
            throw new \Exception('Code not supported');
        }

        return $data['alabel'][$code];
    }

}
