<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientPrescriptionResource extends JsonResource
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
			'prescription' => $this->prescription,
			'quantity' => $this->quantity,
			'type' => $this->type,
			'added_by_id' => $this->add_by_id,
			'doctor_id' => $this->doctor_id,
			'remarks' => $this->remarks,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'relationships' => [
				'patient' => PatientResource::make($this->whenLoaded('patient')),
				'doctor' => $this->whenLoaded('doctor'),
				'added_by' => UserResource::make($this->whenLoaded('addedBy')),
			]
		];
    }
}
