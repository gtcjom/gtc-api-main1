<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImagingResource extends JsonResource
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
            'description' => $this->description,
            'type' => $this->type,
            'image' => is_null($this->image) ? "": Storage::url($this->image),
            'requested_by' => $this->requested_by,
            'requested_at' => $this->requested_at,
            'processed_by' => $this->processed_by,
            'processed_at' => $this->processed_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
			'relationships' => [
				'patient' => PatientResource::make($this->whenLoaded('patient')),
				'processedBy' => UserResource::make($this->whenLoaded('processedBy')),
			]
        ];
    }
}
