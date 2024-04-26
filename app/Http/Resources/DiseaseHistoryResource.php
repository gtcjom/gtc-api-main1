<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseHistoryResource extends JsonResource
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
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'date_started' => $this->date_started->format('m/d/Y'),
            'date_cured' => $this->date_cured?->format('m/d/Y') ?? "",
            'disease' => $this->whenLoaded('diseaseData'),
            'municipality' => $this->whenLoaded('municipalityData'),
            'barangay' => $this->whenLoaded('barangayData'),
            'patient' => PatientResource::make($this->whenLoaded('patient')),
        ];
    }
}
