<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TuberculosisProgramResource extends JsonResource
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
			'patient_name' => $this->patient->fullName(),
            'address' => $this->address,
            'program' => $this->program,
            'barangay_id' => $this->barangay_id,
            'brgy_refferal_date' => $this->brgy_refferal_date,
            'refer_by_brgy_asst' => $this->refer_by_brgy_asst,
            'brgy_notes' => $this->brgy_notes,
            'barangay_clinic_id' => $this->barangay_clinic_id,
            'date_received_by_rhu' => $this->date_received_by_rhu,
            'rhu' => $this->rhu,
            'rhu_refferal_date' => $this->rhu_refferal_date,
            'rhu_notes' => $this->rhu_notes,
            'municipality_clinic_id' => $this->municipality_clinic_id,
            'refer_by_rhu' => $this->refer_by_rhu,
            'hospital_id' => $this->hospital_id,
            'date_received_by_sph' => $this->date_received_by_sph,
            'status' => $this->status,
            'program_status' => $this->program_status,
            'date_approved' => $this->date_approved,
            'approved_by' => $this->approved_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
			'relationships' => [
				'patient' => PatientResource::make($this->whenLoaded('patient')),
				'refer_by_barangay' => UserResource::make($this->whenLoaded('referByBarangay')),
				'refer_by_rhu' => UserResource::make($this->whenLoaded('referByRhu')),
				'approved_by' => UserResource::make($this->whenLoaded('approvedBy')),
				'barangay' => UserResource::make($this->whenLoaded('barangay')),
				'barangay_clinic' => $this->whenLoaded('barangayClinic'),
				'municipality_clinic' => $this->whenLoaded('municipalityClinic'),
                'doctor'
        	]
		];
    }
}
