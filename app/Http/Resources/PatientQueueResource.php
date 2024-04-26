<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientQueueResource extends JsonResource
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
            'send_to' => $this->send_to,
            'status' => $this->status,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'number' => $this->number,
            'date' => $this->date_queue,
            'type_service' => $this->type_service,
            'priority' => $this->priority,
            'room_number' => $this->room_number,
            'clinic_id' => $this->clinic_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'clinic' => ClinicResources::make($this->whenLoaded('clinic')),
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'doctor' => UserResource::make($this->whenLoaded('doctor')),
        ];
    }
}
