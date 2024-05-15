<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryPharmacyResource extends JsonResource
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
            'pharmacy_date' => $this->pharmacy_date,
            'pharmacy_supplies' => $this->pharmacy_supplies,
            'pharmacy_stocks' => $this->pharmacy_stocks,
            'pharmacy_price' => $this->pharmacy_price,
            'pharmacy_status' => $this->pharmacy_status,
        ];
    }
}
