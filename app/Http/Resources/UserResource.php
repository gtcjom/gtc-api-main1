<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'user_id' => $this->user_id,
            'username' => $this->username,
            'type' => $this->type,
            'email' => $this->email,
            'name' => $this->name ?: "",
            'title' => $this->title ?: "",
            'region' => $this->region,
            'province' => $this->province,
            'barangay' => $this->barangay,
            'municipality' => $this->municipality,
            'street' => $this->street,
            'purok' => $this->purok,
            'status' => $this->status,
            'avatar' => $this->avatar ? Storage::url($this->avatar) : "",
            'personnel' => $this->whenLoaded('personnel'),
            'status' => $this->status,
            'room_id' => $this->room_id,
            'room' => $this->room,
            'specialty_id' => $this->specialty_id,
            'health_unit_id' => $this->health_unit_id,
            'specialty' => $this->specialty,
            'gender' => $this->gender,
            'healthUnit' => $this->healthUnit
        ];
    }
}
