<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'status' => $this->status,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'date' => $this->date, //->format('Y-m-d'),
            'date_formatted' => $this->date, //->format('M j, Y'),
            'time' => $this->date, //->format('H:i:s'),
            'time_formatted' => $this->date, //->format('g:i a'),
            'datetime' => $this->date, //->format('M j, Y g:i a'),
            'room_number' => $this->room_number,
            'doctor' => UserResource::make($this->whenLoaded('doctor')),
            'purpose' => $this->purpose,
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'clinic_id' => $this->clinic_id,
            'type_service' => $this->type_service,

        ];
    }
}
