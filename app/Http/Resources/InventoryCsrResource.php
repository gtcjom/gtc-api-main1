<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryCsrResource extends JsonResource
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
            'csr_date' => $this->csr_date,
            'csr_supplies' => $this->csr_supplies,
            'csr_stocks' => $this->csr_stocks,
            'csr_price' => $this->csr_price,
            'csr_status' => $this->csr_status,
        ];
    }
}
