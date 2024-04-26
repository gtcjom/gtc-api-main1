<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorPrescriptionResource extends JsonResource
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
            'dp_id' => $this->dp_id,
            'prescription_id' => $this->prescription_id,
            'management_id' => $this->management_id,
            'patients_id' => $this->patients_id,
            'doctors_id' => $this->doctors_id,
			'prescription' => $this->prescription,
			'product_name' => $this->product_name,
			'product_amount' => $this->product_amount,
			'is_package' => $this->is_package,
			'brand' => $this->brand,
			'quantity' => $this->quantity,
			'quantity_claimed' => $this->quantity_claimed,
			'quantity_order' => $this->quantity_order,
			'quantity_order_batchno' => $this->quantity_order_batchno,
			'dosage' => $this->dosage,
			'per_day' => $this->per_day,
			'per_take' => $this->per_take,
			'remarks' => $this->remarks,
			'prescription_type' => $this->prescription_type,
			'pharmacy_id' => $this->pharmacy_id,
			'claim_id' => $this->claim_id,
			'trace_number' => $this->trace_number,
			'created_at' => $this->created_at,
			'relationships' => [
				'patient' => new PatientResource($this->whenLoaded('patient')),
				'doctor' => $this->whenLoaded('doctor')
			]
        ];
    }
}