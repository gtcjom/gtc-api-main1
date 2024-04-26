<?php

namespace App\Services\MHRA;

use Illuminate\Support\Str;

class ConditionService
{

    public function create(array $data)
    {
        return [
            'fullUrl' => 'urn:uuid:'.Str::uuid(),
            'resource' => [
                'resourceType' => 'Condition',
                'clinicalStatus' => [
                    'coding' => [
                        $this->clinicalStatusCode($data['clinical_status'])
                    ]
                ],
                'subject' => $data['subject'],
                'encounter' => $data['encounter'],
                'onsetDateTime' => $data['onset_date'],
                'recordedDate' => $data['recorded_date'],
                'code' => [
                    'coding' => [
                        $this->conditionCode($data['code'])
                    ]
                ]
            ],
        ];
    }

    private function clinicalStatusCode($code): array
    {
        //display loinc code list in array
        $data = [
            'active' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                'code' => 'active',
                'display' => 'Active'
            ],
            'inactive' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                'code' => 'inactive',
                'display' => 'Inactive'
            ],
            'resolved' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                'code' => 'resolved',
                'display' => 'Resolved'
            ]
        ];

        return $data[$code];
    }

    private function conditionCode($code): array
    {
        //display loinc code list in array
        $data = [
            'asthma' => [
                'system' => 'http://snomed.info/sct',
                'code' => '195967001',
                'display' => 'Asthma'
            ],
            'hypertension' => [
                'system' => 'http://snomed.info/sct',
                'code' => '38341003',
                'display' => 'Hypertension'
            ],
            'diabetes' => [
                'system' => 'http://snomed.info/sct',
                'code' => '73211009',
                'display' => 'Diabetes'
            ],
            'covid' => [
                'system' => 'http://snomed.info/sct',
                'code' => '840539006',
                'display' => 'COVID-19'
            ],
            'cancer' => [
                'system' => 'http://snomed.info/sct',
                'code' => '363346000',
                'display' => 'Cancer'
            ],
            'tuberculosis' => [
                'system' => 'http://snomed.info/sct',
                'code' => '154283005',
                'display' => 'Tuberculosis'
            ],
            'cough' => [
                'system' => 'http://snomed.info/sct',
                'code' => '49727002',
                'display' => 'Cough'
            ],
        ];

        return $data[$code];
    }



}
