<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
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
            'respondent' => $this->respondent,
            'surveyor' => $this->surveyor,
            'date_interview' => $this->date_interview,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'altitude' => $this->altitude,
            'accuracy' => $this->accuracy,
            'houseHold' => $this->whenLoaded('houseHold'),
            'houseCharacteristics' => $this->whenLoaded('houseCharacteristics'),
            'houseHoldCharacteristics' => $this->whenLoaded('houseHoldCharacteristics'),
            'houseHoldMembers' => $this->whenLoaded('houseHoldMembers'),
            'sanitation' => $this->whenLoaded('sanitation'),
            'housing' => $this->whenLoaded('housing'),
            'waste' => $this->whenLoaded('waste'),
            'calamity' => $this->whenLoaded('calamity'),
            'income' => $this->whenLoaded('income')
        ];
    }
}
