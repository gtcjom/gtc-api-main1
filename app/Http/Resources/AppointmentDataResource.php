<?php

namespace App\Http\Resources;

use App\Models\HealthUnit;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AppointmentDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $specimen_picture = $this->specimen_picture;
        $patient_selfie = $this->patient_selfie;

        if (!filter_var($specimen_picture, FILTER_VALIDATE_URL)) {
            $specimen_picture = $specimen_picture ? Storage::url($this->specimen_picture) : "";
        }

        if (!filter_var($patient_selfie, FILTER_VALIDATE_URL)) {
            $patient_selfie = $patient_selfie ? Storage::url($this->patient_selfie) : "";
        }


        return [
            'id' => $this->id,
            'bhs_id' => $this->bhs_id,
            'rhu_id' => $this->rhu_id,
            'serviced_by' => $this->serviced_by,
            'servicedBy' => $this->serviced_by ? $this->servicedBy : null,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'bhs' => $this->bhs_id ? HealthUnitResource::make($this->whenLoaded('bhs')) : null,
            'rhu' => $this->rhu_id ? HealthUnitResource::make($this->whenLoaded('rhu')) : null,
            'tb_symptoms' => $this->whenLoaded('tb_symptoms'),
            'prescriptions' => ItemUsageResource::collection($this->prescriptions),
            'laboratory_tests' => ItemUsageResource::collection($this->laboratoryTests),
            'lab_orders' => $this->labOrders ? LaboratoryOrderResource::collection($this->labOrders) : null,
            'has_for_reading' => $this->forReadingLabOrders,
            'pre_notes' => $this->pre_notes,
            'post_notes' => $this->post_notes,
            'vitals' => PatientVitalResource::make($this->whenLoaded('vitals')),
            'created_at' => $this->created_at,
            'is_done' => $this->is_done,
            'is_tb_positive' => $this->is_tb_positive,
            'referable' => $this->referable,
            'status' => $this->status,
            'for_sph' => $this->for_sph,
            'sph_referred_to' => $this->sph_referred_to,
            'satisfaction' => $this->satisfaction,
            'selfie' => $this->selfie ? Storage::url($this->selfie) : "",
            'patient_selfie' => $patient_selfie,
            'specimen_picture' => $specimen_picture,
            'doctor' => $this->doctor ?: null,
            'sph_statisfaction' => $this->sph_statisfaction,
            'sph_serviced_by' => $this->sph_serviced_by,
            'sph_prescribed_by' => $this->sph_prescribed_by,
            'sph_status' => $this->sph_status,
            'sph_medicine_released' => $this->sph_medicine_released,
            'sph_medicine_released_by' => $this->sph_medicine_released_by,
            'referred_by' => $this->referred_by,
            'reason' => $this->reason,
            'health_insurrance_coverage' => $this->health_insurrance_coverage,
            'health_insurrance_coverage_if_yes_type' => $this->health_insurrance_coverage_if_yes_type,
            'action_taken' => $this->action_taken,
            'impression' => $this->impression,
            'lab_findings' => $this->lab_findings,
            'clinical_history' => $this->clinical_history,
            'xray_result' => $this->xray_result ? Storage::url($this->xray_result) : "",
            'hemoglobin' => $this->hemoglobin,
            'hematocrit' => $this->hematocrit,
            'rcbc' => $this->rcbc,
            'wbc' => $this->wbc,
            'type' => $this->type,
            'updated_at' => $this->updated_at,
            'for_xray' => $this->for_xray,
            'vital_id' => $this->vital_id,
            'referred_to' => $this->referred_to,
            'for_lab' => $this->for_lab,
            'released_by' => $this->released_by,
            'referredToDoctor' => $this->referredToDoctor ? UserResource::make($this->referredToDoctor) : null,

            'prescribed_by' => $this->prescribed_by,
            'prescribedByDoctor' => $this->prescribedByDoctor ? UserResource::make($this->prescribedByDoctor) : null,

            'social_history' => $this->socialHistory ? $this->socialHistory : null,
            'environmental_history' => $this->environmentalHistory ? $this->environmentalHistory : null,
            'general_history' => $this->generalHistory ? $this->generalHistory : null,
            'surgical_history' => $this->surgicalHistory ? $this->surgicalHistory : null,
            'room_number' => $this->room_number,
            'mode_of_consultation' => $this->mode_of_consultation,
            'phic_no' => $this->phic_no,
            'cloud_id' => $this->cloud_id,
            'unsent' => $this->unsent,
            'verified' => $this->verified,
            'verified_at' => $this->verified_at,
            'last_updated' => $this->last_updated,
            'verified_by' => $this->verified_by,
            'verified_by_entity' => $this->verified_by_entity,
            "diagnosis_code" => $this->diagnosis_code,
            "procedure_code" => $this->procedure_code,
            'item_used' => $this->item_used,

            'for_anesthesia' => $this->for_anesthesia,
            'for_billing' => $this->for_billing,
            'for_housekeeping' => $this->for_housekeeping,
        ];
    }
}
