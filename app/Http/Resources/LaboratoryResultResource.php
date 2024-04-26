<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LaboratoryResultResource extends JsonResource
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
            'laboratory_order_id' => $this->laboratory_order_id,
            'laboratory_order_type' => $this->laboratory_order_type,
            'remarks' => $this->remarks,
            'results' => $this->results,
            'image' => is_null($this->image) ? "" : Storage::url($this->image),
            'status' => $this->status,
            'laboratory_test_id' => $this->laboratory_test_id,
            'added_by' => $this->added_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'relationships' => [
                'laboratoryTest' => LaboratoryTestResource::make($this->whenLoaded('laboratoryTest')),
                'laboratoryOrder' => LaboratoryOrderResource::make($this->whenLoaded('laboratoryOrder')),
                'addedBy' => UserResource::make($this->whenLoaded('addedBy'))
            ]
        ];
    }
}
