<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryPharmacyOrderResource extends JsonResource
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
            'doctor_id' => $this->doctor_id,
            'inventory_pharmacies_id' => $this->inventory_pharmacies_id,
            'date' => $this->date,
            // 'supplies' => $this->supplies,
            'quantity' => $this->quantity,
            'relationships' => [
                'patient' => PatientResource::make($this->whenLoaded('patient')),
                'inventory_pharmacies_id' => InventoryPharmacyResource::make($this->whenLoaded('inventoryPharmacy')),
                'doctor' => UserResource::make($this->whenLoaded('doctor')),
            ]
        ];
    }
}
