<?php

namespace App\Services\MHRA;

use App\Models\Patient;
use App\Models\Vital;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LevelService
{

    public function makeLevelOne(int $patient_id)
    {
        $patient = Patient::query()->findOrFail($patient_id);

        if(is_null($patient->fire_id) || empty($patient->fire_id)){
            Log::alert('Patient has no fire_id', ['patient_id' => $patient_id]);
            return;
        }

        $encounterService = new EncounterService();
        $observationService = new ObservationService();


        $subject =  [
            'reference' => 'Patient/'.$patient->fire_id
        ];
        $encounter_id  = 'urn:uuid:'.Str::uuid();

        $encounter_data = [
            'fullUrl' => $encounter_id,
            'subject' => $subject,
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d'),
            'class' => 'AMB',
            'status' => 'finished',
        ];

        $entries = [];

        $encounter = $encounterService->create($encounter_data);
        $entries[] = $encounter;

        $lastVital = Vital::query()->where('patient_id', $patient_id)->latest()->first();



        $observations = $observationService->make($subject, [
            'reference' => $encounter_id
            ],
            [
            [
                'code' => 'temperature',
                'value' => $lastVital->temperature,
                'unit' => 'Cel',
                'unit_code' => 'Cel'
            ],
            [
                'code' => 'respiratory',
                'value' => $lastVital->respiratory,
                'unit' => 'BPM',
                'unit_code' => 'BPM'
            ],
            [
                'code' => 'Blood Pressure',
                'value' => $lastVital->blood_pressure,
                'unit' => 'mmHg',
                'unit_code' => 'mmHg'
            ],
//            [
//                'code' => 'oxygen_saturation',
//                'value' => $lastVital->oxygen_saturation,
//                'unit' => '%',
//                'unit_code' => '%'
//            ],
            [
                'code' => 'heart_rate',
                'value' => $lastVital->pulse,
                'unit' => 'BPM',
                'unit_code' => 'BPM'
            ],
            [
                'code' => 'weight',
                'value' => $lastVital->weight,
                'unit' => 'kg',
                'unit_code' => 'kg'
            ],
            [
                'code' => 'height',
                'value' => $lastVital->height,
                'unit' => 'cm',
                'unit_code' => 'cm'
            ],
            [
                'code' => 'bmi',
                'value' => $this->calcBMI($lastVital->weight, $lastVital->height),
                'unit' => 'kg/m2',
                'unit_code' => 'kg/m2'
            ],
        ]);

        foreach ($observations as $observation) {
            $entries[] = $observation;
        }


        $bundleService = new BundleService();
        $bundle = $bundleService->make($entries);





        $fireService = new FireServerService();
        return $fireService->sendPost($bundle, '/');





    }


    public function calcBMI(float $weight, float $height): string
    {
        $height = $height / 100;
        return number_format($weight / ($height * $height), 3);
    }

}
