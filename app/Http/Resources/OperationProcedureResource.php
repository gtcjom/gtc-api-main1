<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OperationProcedureResource extends JsonResource
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
            'operation_number' => $this->operation_number,
            'operation_date' => $this->operation_date,
            'appointment_id' => $this->appointment_id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'health_unit_id' => $this->health_unit_id,
            'operation_time' => $this->operation_time,
            'operation_notes' => $this->operation_notes,
            'procedure' => $this->procedure,
            'operation_status' => $this->operation_status,
            'relationships' => [
                'healthUnit' => HealthUnitResource::make($this->whenLoaded('healthUnit')),
                'patient' => PatientResource::make($this->whenLoaded('patient')),
                'doctor' => UserResource::make($this->whenLoaded('doctor')),
                'clinic' => ClinicResources::make($this->whenLoaded('clinic')),
            ]
        ];
    }
}
