<?php

namespace App\Http\Resources;

use App\Http\Requests\UserRequest;
use App\Models\ConsignmentOrderLocation;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsignmentOrderResource extends JsonResource
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
            'date' => $this->date,
            'cof_number' => $this->cof_number,
            'consignor' => $this->consignor,
            'term' => $this->term,
            'status' => $this->status,
            'hci_name' => $this->hci_name,
            'hci_number' => $this->hci_number,
            'to_location_type' => $this->to_location_type,
            'to_location_id' => $this->to_location_id,
            'from_location_type' => $this->from_location_type,
            'from_location_id' => $this->from_location_id,
            'locations' => ConsignmentOrderLocationResource::collection($this->locations),
            'to_location'  => ConsignmentOrderLocationResource::make($this->toLocation),
            'approvedBy'  => UserResource::make($this->approvedBy),
            'scheduledBy'  => UserResource::make($this->scheduledBy),
            'checkedBy'  => UserResource::make($this->checkedBy),
            'receivedBy'  => UserResource::make($this->receivedBy),
            'deliveredBy'  => UserResource::make($this->deliveredBy),
            'processedBy' => UserResource::make($this->processedBy)
        ];
    }
}
