<?php

namespace App\Services;

use App\Models\Vital;
use Illuminate\Http\Request;
use App\Models\HealthHistory;
use App\Models\Patient;

class VitalService
{


    public function get(Patient|int $patient)
    {
        if( $patient instanceof Patient){
            $patient_id = $patient->id;
        }else{
            $patient_id = $patient;
        }
        return Vital::query()->where('patient_id',$patient_id)->first();
    }


    public function update(Request $request, Patient|int $patient)
    {
        if( $patient instanceof Patient){
            $patient_id = $patient->id;
        }else{
            $patient_id = $patient;
        }

        $vital = Vital::query()->firstOrNew([
            'patient_id' => $patient_id
        ]);
        $vital->temperature = $request->get('temperature');
        $vital->glucose = $request->get('glucose');
        $vital->uric_acid = $request->get('uric_acid');
        $vital->bp_systolic = $request->get('bp_systolic');
        $vital->bp_diastolic = $request->get('bp_diastolic');
        $vital->respiratory = $request->get('respiratory');
        $vital->pulse = $request->get('pulse');
        $vital->cholesterol = $request->get('cholesterol');
        $vital->weight = $request->get('weight');
        $vital->height = $request->get('height');
        // $history->hepatitis = $request->get('hepatitis');
        // $history->tuberculosis = $request->get('tuberculosis');
        // $history->dengue = $request->get('dengue');
        $vital->save();

        $history = new HealthHistory();
        $history->temperature = $request->get('temperature');
        $history->glucose = $request->get('glucose');
        $history->uric_acid = $request->get('uric_acid');
        $history->bp_systolic = $request->get('bp_systolic');
        $history->bp_diastolic = $request->get('bp_diastolic');
        $history->respiratory = $request->get('respiratory');
        $history->pulse = $request->get('pulse');
        $history->cholesterol = $request->get('cholesterol');
        $history->weight = $request->get('weight');
        $history->height = $request->get('height');
        $history->hepatitis = $request->get('hepatitis',0);
        $history->tuberculosis = $request->get('tuberculosis',0);
        $history->dengue = $request->get('dengue',0);
        $history->patient_id = $patient_id;
        $history->record_by_id = $request->user()->id;
        $history->save();

        return $vital;
    }

	public function store(Request $request, int $id)
	{
		Vital::create([
			'temperature' => $request->temperature,
			'respiratory' => $request->respiratory,
			'uric_acid' => $request->uric_acid,
			'cholesterol' => $request->cholesterol,
			'glucose' => $request->glucose,
			'pulse' => $request->pulse,
			'weight' => $request->weight,
			'height' => $request->height,
			'patient_id' => $id,
			'added_by_id' => $request->user()->id,
			'blood_systolic' => $request->blood_systolic,
			'blood_diastolic' => $request->blood_diastolic,
		]);
	}
}