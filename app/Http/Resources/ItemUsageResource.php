<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemUsageResource extends JsonResource
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
            'appointment_id' => $this->appointment_id,
            'created_at' => $this->created_at,
            'details' => $this->details,
            'inventory_id' => $this->inventory_id,
            'item_id' => $this->item_id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'updated_at' => $this->updated_at,
            'item' => $this->item,
        ];
    }
}
