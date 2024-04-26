<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientVitalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'temperature' => $this->temperature,
            'blood_pressure' => $this->blood_pressure,
            'respiratory' => $this->respiratory,
            'uric_acid' => $this->uric_acid,
            'cholesterol' => $this->cholesterol,
            'glucose' => $this->glucose,
            'pulse' => $this->pulse,
            'weight' => $this->weight,
            'height' => $this->height,
            'patient_id' => $this->patient_id,
            'added_by_id' => $this->added_by_id,
            'blood_systolic' => $this->blood_systolic,
            'blood_diastolic' => $this->blood_diastolic,
            'bmi' => $this->bmi,
            'height_for_age' => $this->height_for_age,
            'weight_for_age' => $this->weight_for_age,
            'blood_type' => $this->blood_type,
            'bloody_type' => $this->bloody_type,
            'oxygen_saturation' => $this->oxygen_saturation,
            'heart_rate' => $this->heart_rate,
            'regular_rhythm' => $this->regular_rhythm,
            'covid_19' => $this->covid_19,
            'tb' => $this->tb,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'relationships' => [
                'patient' => PatientResource::make($this->whenLoaded('patient')),
                'added_by' => UserResource::make($this->whenLoaded('addedBy')),
            ]
        ];
    }
}
