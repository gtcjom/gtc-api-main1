<?php

namespace App\Services;

use App\Models\Household;
use App\Models\HouseRawAnswer;
use App\Models\Patient;
use Illuminate\Support\Str;

class HouseholdService
{

    public function dataList()
    {
        return Household::query()
            ->with(['municipality', 'barangayData','purokData'])
            ->latest()->paginate( request('paginate',12));
    }
    public function getHousehold(int $id)
    {
        return Household::query()
            ->with(['municipality', 'barangayData','members','purokData',
                'sanitation','housing','waste','calamity','houseCharacteristics',
                'income', 'rawAnswer','rawAnswer','houseHoldCharacteristics'])
            ->findOrFail($id);
    }


    public function updateMembers(int $id)
    {

        Patient::query()->where('household_id' , $id)->update([
            'household_id' => null,
            'head_relation' => null,
        ]);


        foreach ( request()->get('patient_ids') ?:[] as $key => $patientId){
            $patient = Patient::query()->find($patientId);
            $patient->household_id = $id;
            $patient->head_relation = request()->get('head_relation')[$key];
            $patient->save();
        }




    }

    public function create()
    {
        $household = new Household();
        $household->province =  request()->get("province","");
        $household->city   =  request()->get("municipality","");
        $household->zone   =  request()->get("zone","");
        $household->barangay   =  request()->get("barangay","");
        $household->purok  =  request()->get("purok","");
        $household->street =  request()->get("street","");
        $household->house_number   =  request()->get("house_number","");
        $household->house_id   =  request()->get("house_id","");
        $household->lat = request()->get("lat","");
        $household->lng = request()->get("lng","");
        $household->respondent = request()->get("respondent","");
        if(request()->has('patient_id')){
            $household->head_id = request()->get('patient_id');
            $patient = Patient::query()->find(request()->get('patient_id'));
            if($patient){
                $patient->household_id = $household->id;
                $patient->head_relation = 'head';
                $patient->save();
            }
        }
        $household->save();
        $household->load(['municipality', 'barangayData','members','sanitation','housing','waste','calamity','income', 'rawAnswer']);


        return $household;
    }

    public function update(int $id)
    {
       $household = Household::query()->findOrFail($id);
       $household->province =  request()->get("province","");
       $household->city   =  request()->get("municipality","");
       $household->zone   =  request()->get("zone","");
       $household->barangay   =  request()->get("barangay","");
       $household->purok  =  request()->get("purok","");
       $household->street =  request()->get("street","");
       $household->house_number   =  request()->get("house_number","");
       $household->house_id   =  request()->get("house_id","");
       $household->lat = request()->get("lat","");
       $household->lng = request()->get("lng","");
       $household->save();

       return [
           'raw' => $this->rawData($household->id),
           'household' => $household
       ];
    }

    public function rawData(int $houseHoldId)
    {
        $raw = HouseRawAnswer::query()->firstOrNew([
            'household_id' => $houseHoldId
        ]);
        //hc
        $raw->building_type = request()->get("building_type","");
        $raw->roof_materials = request()->get("roof_materials","");
        $raw->wall_materials = request()->get("wall_materials","");

        //hhc
        $raw->overseas_members = request()->get("overseas_members",0);
        $raw->nuclear_families = request()->get("nuclear_families",0);
        $raw->fam_plan = request()->get("fam_plan");
        $raw->fp  = request()->get("fp");
        $raw->fp_method = request()->get("fp_method","");
        $raw->fp_natural = request()->get("fp_natural","");
        $raw->undecide = request()->get("undecide","");
        $raw->indisease = request()->get("indisease","");
        $raw->no_intent = request()->get("no_intent","");
        $raw->pregnant = request()->get("pregnant","no");
        $raw->pregnant_number = request()->get("pregnant_number",0);
        $raw->solo = request()->get("solo","no");
        $raw->pwd = request()->get("pwd","no");
        $raw->disabled_number = request()->get("disabled_number",0);
        $raw->pets = request()->get("pets","no");
        $raw->number_pets = request()->get("number_pets", "");
        $raw->pet_vaccine_date = request()->get("pet_vaccine_date");
        $raw->pet_vax = request()->get("pet_vax","no");
        $raw->number_of_hh = request()->get("number_of_hh",0);

        //sanitation
        $raw->main_source = request()->get('wmain_source');
        $raw->drink_water = request()->get('drink_water');
        $raw->hh_toilet  = request()->get('hh_toilet');

        //housing
        $raw->status = request()->get('status');
        $raw->residence_area = request()->get('residence_area');
        $raw->electric = request()->get('electric');
        $raw->electric_housing = request()->get('electric_housing');
        $raw->com_channel = request()->get('com_channel');
        $raw->housingother = request()->get('housingother');


        //waste

        $raw->garbage_disposal = request()->get('garbage_disposal');
        $raw->collector = request()->get('collector');
        $raw->often_garbage = request()->get('often_garbage');
        $raw->volume_waste = request()->get('volume_waste');
        $raw->disposalothers = request()->get('disposalothers');



        //income
        $raw->jobList = request()->get('jobList','');
        $raw->jobIncome = request()->get('jobIncome','');


        //calamity
        $raw->calamity_experienced = request()->get('calamity_experienced');
        $raw->calamity = request()->get('calamity');
        $raw->assistancecalamity = request()->get('assistancecalamity');


        $raw->save();

        return $raw;
    }
}