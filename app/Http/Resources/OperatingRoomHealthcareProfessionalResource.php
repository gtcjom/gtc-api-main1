<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OperatingRoomHealthcareProfessionalResource extends JsonResource
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
			'operating_room_chart_id' => $this->operating_room_chart_id,
			'doctor_id' => $this->doctor_id,
			'title' => $this->title,
			'relationships' => [
				'operating_room_chart' => OperatingRoomChartResource::make($this->whenLoaded('operatingRoomChart')),
				'doctor' => UserResource::make($this->doctor),
			]
		];
    }
}
