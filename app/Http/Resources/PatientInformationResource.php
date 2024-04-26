<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request),[
            'phicDirectDetail' => $this->getPhicDirect($this->phicdirect),
            'phicInDirectDetail' => $this->getphicIndirect($this->phicindirect),
            'self_earning_detail' => $this->getSelfEarning($this->self_earning),
            'migrant_worker_detail' => $this->getMigrantWorkers($this->phicdirect),
        ]);
    }

    public function phicDirects (): array
    {
        return [
            "Employed Private",
            "Employed Government",
            "Professional Practitioner",
            "Self-Earning Individual",
            "Kasambahay",
            "Migrant Worker",
            "Lifetime Member",
            "Filipinos with Dual Citizenship/Living Abroad",
            "Foreign National",
            "Family Driver",
            "Dependent Beneficiary",
        ];
    }

    public function phicIndirects(){

        return [
            "Listahanan",
            "4Ps/MCCT",
            "Senior Citizen",
            "PAMANA",
            "KIA/KIPO",
            "MLGU Sponsored",
            "PLGU Sponsored",
            "NGA Sponsored",
            "Private Sponsored",
            "PWD",
            "Bangsamoro/Normalization",
            "Dependent Beneficiary",

        ];
    }

    public function specifySelfEarnings()
    {
        return [
            "Individual",
            "Sole Proprietor",
            "Group Enrollment Scheme",
        ];
    }


    public function getphicIndirect($id){
        if(!is_integer($id))
            return "";
        return $this->phicIndirects()[$id - 1] ?? "";
    }

    public function getPhicDirect($id): string
    {
        if(!is_integer($id))
            return "";
        return $this->phicDirects()[$id - 1] ?? "";
    }

    public function getSelfEarning($id): string
    {
        if(!is_integer($id))
            return "";

        return $this->specifySelfEarnings()[$id - 1] ?? "";
    }

    public  function getMigrantWorkers( $id){
        if(!is_integer($id))
            return "";

        if($id == 1)
            return "Land-Based";

        return "Sea-Based";
    }
}
