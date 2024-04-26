<?php

namespace App\Http\Resources;

use App\Models\Municipality;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthUnitResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'municipality_id' => $this->municipality_id,
            'barangay_id' => $this->barangay_id,
            'status' => $this->status,
            'region' => $this->region,
            'province' => $this->province,
            'street' => $this->street,
            'purok' => $this->purok,
            'zip' => $this->zip,
            'rooms' => $this->rooms,
            'municipality' => $this->municipality, //MunicipalityResource::make($this->municipality),
            'barangay' => $this->barangay, //BarangayResource::make($this->barangay),
        ];
    }
}
