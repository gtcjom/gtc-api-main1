<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OperatingRoomChartResource extends JsonResource
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
			'clinic_id' => $this->clinic_id,
			'patient_id' => $this->patient_id,
			'date' => $this->date,
			'time' => $this->time,
			'procedure' => $this->procedure,
			'status' => $this->status,
			'room_id' => $this->room_id,
			'priority' => $this->priority,
			'room_number' => $this->room_number,
			'appointment_id' => $this->appointment_id,
			'relationships' => [
				'clinic' => ClinicResources::make($this->whenLoaded('clinic')),
				'room' => OperatingRoomResource::make($this->whenLoaded('room')),
				'patient' => PatientResource::make($this->whenLoaded('patient')),
				'appointment' => AppointmentResource::make($this->whenLoaded('appointment')),
				'healthcare_professionals' => OperatingRoomHealthcareProfessionalResource::collection($this->whenLoaded('healthcareProfessionals'))
			]
		];
	}
}
