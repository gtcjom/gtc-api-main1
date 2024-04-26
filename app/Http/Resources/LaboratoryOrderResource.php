<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LaboratoryOrderResource extends JsonResource
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
      'order_number' => $this->order_number,
      'order_date' => $this->order_date,
      'appointment_id' => $this->appointment_id,
      'patient_id' => $this->patient_id,
      'laboratory_test_type' => $this->laboratory_test_type,
      'doctor_id' => $this->doctor_id,
      'health_unit_id' => $this->health_unit_id,
      'clinic_id' => $this->clinic_id,
      'notes' => $this->notes,
      'status' => $this->status,
      'lab_result_description' => $this->lab_result_description,
      'processed_by' => $this->processed_by,
      'date_processed' => $this->date_processed,
      'order_status' => $this->order_status,
      'accepted_by' => $this->accepted_by,
      'accepted_at' => $this->accepted_at,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'type' => $this->type,
      'attachment' => $this->attachment ? Storage::url($this->attachment) : "",
      'lab_result_notes' => $this->lab_result_notes,
      'relationships' => [
        'healthUnit' => HealthUnitResource::make($this->whenLoaded('healthUnit')),
        'patient' => PatientResource::make($this->whenLoaded('patient')),
        'doctor' => UserResource::make($this->whenLoaded('doctor')),
        'clinic' => ClinicResources::make($this->whenLoaded('clinic')),
        'processed_by' => UserResource::make($this->whenLoaded('processedBy')),
        'accepted_by' => UserResource::make($this->whenLoaded('acceptedBy')),
      ]
    ];
  }
}
