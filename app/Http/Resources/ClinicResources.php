<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ClinicResources extends JsonResource
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
            'name' => $this->clinic,
            'lat' => $this->latitude,
            'lng' => $this->longitude,
            'image' => is_null($this->image) ? "" : Storage::url($this->image),
            'street' => $this->street,
            'purok' => $this->purok,
            'region' => $this->region,
            'barangay' => $this->barangay,
            'province' => $this->province,
            'municipality' => $this->municipality,
            'purok_id' => $this->purok_id,
            'barangay_id' => $this->barangay_id,
            'municipality_id' => $this->municipality_id,
            'type' => $this->type,
            'tuberculosis' => $this->tuberculosis,
            'animal_bites' => $this->animal_bites,
            'hypertension' => $this->hypertension,
            'doctors' => UserResource::collection($this->whenLoaded('doctors'))
        ];
    }
}
