<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeleMedicineScheduleResource extends JsonResource
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
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'date' => $this->date,
            'slot_id' => $this->slot_id,
            'status' => $this->status,
            'uuid' => $this->uuid,
            'channel_name' => $this->channel_name,
            'notes' => $this->notes,
            'token' => $this->token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'patient' => $this->patient,
            'doctor' => $this->doctor ? UserResource::make($this->doctor) : null,
            'slot' => $this->slot,
        ];
    }
}
