<?php

namespace App\Services\MHRA;

use Illuminate\Support\Str;

class EncounterService
{


    /**
     * Create a new encounter
     *
     * @param array $data
     * @return array
     */

    public function create(array $data): array
    {


        return [

            'fullUrl' => $data['fullUrl'],

            'resource' => [
                'resourceType' => 'Encounter',
                'subject' => $data['subject'],
                'status' => $data['status'] ?? 'finished',
                'class' => $this->classV3Code($data['class']),
                'period' => [
                    'start' => $data['start'],
                    'end' => $data['end']
                ],
            ],
            'request' => [
                'method' => 'POST',
                'url' => 'Encounter'
            ]
        ];

    }

    private function classV3Code($code): array
    {
        //display loinc code list in array
        $data = [
            'AMB' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'AMB',
            ],
            'EMER' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'EMER',
            ],
            'IMP' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'IMP',
            ],
            'OUTP' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'OUTP',
            ],
            'VR' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'VR',
            ],
            'ACUTE' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'ACUTE',
            ],
            'NONAC' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'NONAC',
            ],
            'DAY' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'DAY',
            ],
            'CAT' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'CAT',
            ],
            'CON' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'CON',
            ],
        ];

        return $data[$code];
    }
}
