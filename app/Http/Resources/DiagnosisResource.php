<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosisResource extends JsonResource
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
			'added_by_id' => $this->added_by_id,
			'title' => $this->title,
			'description' => $this->description,
			'datetime' => $this->datetime,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'relationships' => [
				'diagnosis_list' => $this->diagnosisList,
				'patient' => PatientResource::make($this->whenLoaded('patient')),
				'added_by' => UserResource::make($this->whenLoaded('addedBy')),
			]
		];
    }
}
