<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseholdResource extends JsonResource
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
            'head_id' => $this->head_id,
            'province' => $this->province,
            'zone' => $this->zone,
            'barangay_id' => $this->barangay,
            'purok' => $this->whenLoaded('purokData'),
            'respondent' => $this->respondent,
            'street' => $this->street,
            'house_number' => $this->house_number,
            'surveyor_id' => $this->surveyor_id,
            'date_interview' => $this->date_interview,
            'house_id' => $this->house_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'altitude' => $this->altitude,
            'accuracy' => $this->accuracy,
            'municipality' => $this->whenLoaded('municipality'),
            'barangay' => $this->whenLoaded('barangayData'),
            'houseCharacteristics' => $this->whenLoaded('houseCharacteristics'),
            'houseHoldCharacteristics' => $this->whenLoaded('houseHoldCharacteristics'),
            'sanitation' => $this->whenLoaded('sanitation'),
            'housing' => $this->whenLoaded('housing'),
            'waste' => $this->whenLoaded('waste'),
            'calamity' => $this->whenLoaded('calamity'),
            'income' => $this->whenLoaded('income'),
            'rawAnswer' => $this->whenLoaded('rawAnswer'),
            'members' => PatientResource::collection($this->whenLoaded('members')),
        ];
    }
}
