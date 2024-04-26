<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
            'notes' => $this->notes,
            'status' => $this->status,
            'doctor_id' => $this->doctor_id,
            'remarks' => $this->remarks,
            'datetime' => $this->datetime,
            'date' => Carbon::parse($this->datetime)->format('Y-m-d'),
            'time' => Carbon::parse($this->datetime)->format('H:i:s a'),
            'patient_id' => $this->patient_id,
            'relationships' => [
                'patient' => PatientResource::make($this->whenLoaded('patient')),
                'added_by' => UserResource::make($this->whenLoaded('addedBy')),
                'doctor' => UserResource::make($this->whenLoaded('doctor')),
            ]
        ];
    }
}
