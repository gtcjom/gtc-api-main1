<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientAllergyResource extends JsonResource
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
			'date' => $this->date,
			'description' => $this->description,
			'added_by' => $this->added_by,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'relationships' => [
				'patient' => PatientResource::make($this->whenLoaded('patient')),
				'added_by' => UserResource::make($this->whenLoaded('addedBy')),
			]
		];
    }
}
