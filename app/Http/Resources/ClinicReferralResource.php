<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClinicReferralResource extends JsonResource
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
			'referral_date' => $this->referral_date,
			'notes' => $this->notes,
			'added_by' => $this->added_by,
			'from_clinic_id' => $this->from_clinic_id,
			'to_clinic_id' => $this->to_clinic_id,
			'diagnosis' => $this->diagnosis,
			'received_by' => $this->received_by,
			'date_received' => $this->date_received,
			'received_by' => $this->received_by,
			'status' => $this->status,
			'relationships' => [
				'patient' => PatientResource::make($this->whenLoaded('patient')),
				'fromClinic' => ClinicResources::make($this->whenLoaded('fromClinic')),
				'toClinic' => ClinicResources::make($this->whenLoaded('toClinic')),
				'addedBy' => UserResource::make($this->whenLoaded('addedBy')),
				'receivedBy' => UserResource::make($this->whenLoaded('receivedBy')),
			]
		];
    }
}
