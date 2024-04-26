<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'unit_measurement' => $this->unit_measurement,
            'type' => $this->type,
            'qty_left' => $this->qtyLeft(),
            'inventory' => $this->inventory,
            $this->mergeWhen($this->item_uuid, fn () => [
                'supplier' => $this->supplier,
                'item_uuid' => $this->item_uuid,
            ]),
        ];
    }
}
