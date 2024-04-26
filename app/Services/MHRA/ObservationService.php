<?php

namespace App\Services\MHRA;

    use Illuminate\Support\Str;

    class ObservationService
    {
        public function create(array $data)
        {

            $valueQuantityList = [
               'weight',
                'height',
                'temperature',
            ];




            if(in_array($data['code'], $valueQuantityList)){
                $value  = [
                    'name' => 'valueQuantity',
                    'value' => [
                        'value' => $data['value'],
                        'unit' => $data['unit'],
                        'system' => 'http://unitsofmeasure.org',
                        'code' => $data['unit_code']
                    ]
                ];

                $code = [
                    'name' => 'coding',
                    'value' => [
                        $this->loincCode($data['code'])
                    ]
                ];

            }else{
                $value = [
                    'name' => 'valueString',
                    'value' => (string) $data['value']
                ];

                $code = [
                    'name' => 'text',
                    'value' => Str::ucfirst($data['code'])
                ];
            }



            return [
                'fullUrl' => 'urn:uuid:'.Str::uuid(),
                'resource' => [
                    'resourceType' => 'Observation',
                    'status' => 'final',
                    'subject' => $data['subject'],
                    'encounter' => $data['encounter'],
                    'effectiveDateTime' => $data['date'],
                    'code' => [
                        $code['name'] => $code['value']
                    ],
                    $value['name'] => $value['value']

                ],
                'request' => [
                    'method' => 'POST',
                    'url' => 'Observation'
                ]
            ];

        }


        public function make(array $subject , array $encounter, array $data)
        {
            $observations = [];

            foreach ($data as $item) {
                $observations[] = $this->create([
                    'subject' => $subject,
                    'encounter' => $encounter,
                    'date' => date('Y-m-d'),
                    'code' => $item['code'],
                    'value' => $item['value'],
                    'unit' => $item['unit'],
                    'unit_code' => $item['unit_code']
                ]);
            }

            return $observations;
        }





        public function loincCode($code): array
        {
            //display loinc code list in array
            $data = [
                'height' => [
                    'system' => 'http://loinc.org',
                    'code' => '8302-2',
                    'display' => 'Body height'
                ],
                'weight' => [
                    'system' => 'http://loinc.org',
                    'code' => '29463-7',
                    'display' => 'Body weight'
                ],
                'temperature' => [
                    'system' => 'http://loinc.org',
                    'code' => '8310-5',
                    'display' => 'Body temperature'
                ],
                'blood_pressure' => [
                    'system' => 'http://loinc.org',
                    'code' => '85354-9',
                    'display' => 'Blood pressure'
                ],
                'pulse' => [
                    'system' => 'http://loinc.org',
                    'code' => '8867-4',
                    'display' => 'Heart rate'
                ],
                'respiratory_rate' => [
                    'system' => 'http://loinc.org',
                    'code' => '9279-1',
                    'display' => 'Respiratory rate'
                ],
                'oxygen_saturation' => [
                    'system' => 'http://loinc.org',
                    'code' => '2708-6',
                    'display' => 'Oxygen saturation'
                ],
                'head_circumference' => [
                    'system' => 'http://loinc.org',
                    'code' => '8287-5',
                    'display' => 'Head circumference'
                ],
                'body_mass_index' => [
                    'system' => 'http://loinc.org',
                    'code' => '39156-5',
                    'display' => 'Body mass index'
                ],
                'body_surface_area' => [
                    'system' => 'http://loinc.org',
                    'code' => '60621009',
                    'display' => 'Body surface area'
                ],
                'waist_circumference' => [
                    'system' => 'http://loinc.org',
                    'code' => '56115-9',
                    'display' => 'Waist circumference'
                ],
                'hip_circumference' => [
                    'system' => 'http://loinc.org',
                    'code' => '56116-7',
                    'display' => 'Hip circumference'
                ],
                'waist_to_hip_ratio' => [
                    'system' => 'http://loinc.org',
                    'code' => '56117-5',
                    'display' => 'Waist to hip ratio'
                ],
                'blood_glucose' => [
                    'system' => 'http://loinc.org',
                    'code' => '15074-8',
                    'display' => 'Glucose'
                ],
                'total_cholesterol' => [
                    'system' => 'http://loinc.org',
                    'code' => '2093-3',
                    'display' => 'Cholesterol'
                ],
                'systolic_blood_pressure' => [
                    'system' => 'http://loinc.org',
                    'code' => '8480-6',
                    'display' => 'Systolic blood pressure'
                ],
                'bmi' => [
                    'system' => 'http://loinc.org',
                    'code' => '39156-5',
                    'display' => 'Body mass index (BMI) [Ratio]'
                ],
            ];

            if(!array_key_exists($code, $data)){
                return [
                    'text' => $code
                ];
            }

            return $data[$code];
        }
    }

