<?php

namespace App\Services;

use App\Models\Barangay;
use App\Models\Calamity;
use App\Models\Disease;
use App\Models\DiseaseHistory;
use App\Models\HouseCharacteristic;
use App\Models\Household;
use App\Models\HouseholdCharacteristic;
use App\Models\HouseholdMember;
use App\Models\HouseRawAnswer;
use App\Models\Housing;
use App\Models\Interview;
use App\Models\Municipality;
use App\Models\Patient;
use App\Models\PatientInformation;
use App\Models\PatientRawAnswer;
use App\Models\Sanitation;
use App\Models\SourceIncome;
use App\Models\WasteManagement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubmissionEditService
{

    public function getPatients()
    {
        return Patient::query()->latest()->paginate( request('paginate',12));
    }
    public function create()
    {


        return $this->houseHold();


    }

    protected function geoLocation(): array
    {
        $l = request()->get("Record_your_current_location","");
        if(isset($l) && Str::length($l) > 0){
            $location = explode(" ", request()->get($l,""));


            return [
                'lat' => $location[0] ?? 0,
                'lng' => $location[1] ?? 0,
                'altitude' => $location[2] ?? 0,
                'accuracy' => $location[3] ?? 0,
            ];
        }
        return [];
    }
    public function houseHold()
    {
        $date = request()->get('group_identification/date_interview');
        $time = explode(".",request()->get('group_identification/time_started'))[0] ?? null;
        $date_interview = date('Y-m-d');
        if(isset($date,$time))
            $date_interview = "{$date} {$time}";



        $hh = new Household();
        $hh->province =  request()->get("group_identification/province","");
        $hh->city   =  request()->get("group_identification/municipality","");
        $hh->zone   =  request()->get("group_identification/zone","");
        $hh->barangay   =  request()->get("group_identification/barangay","");
        $hh->purok  =  request()->get("group_identification/purok_sitio","");
        $hh->street =  request()->get("group_identification/street","");
        $hh->house_number   =  request()->get("group_identification/housenumber","");
        $hh->respondent   =  request()->get("group_identification/name_respondent","");
        $hh->house_id   =  request()->get("group_identification/householdidn","");
        $hh->surveyor_id = request()->get('surveyor_id');
        $hh->date_interview = $date_interview;

        $location = $this->geoLocation();
        $hh->lat = $location['lat'] ?? 0;
        $hh->lng = $location['lng'] ?? 0;
        $hh->altitude = $location['altitude'] ?? 0;
        $hh->accuracy = $location['accuracy'] ?? 0;
        $hh->save();


        $this->houseHoldMembers($hh->id);

        $raw =  $this->houseHoldCreateRaw($hh->id);
        $this->houseHoldData($raw);
        return $hh;
    }


    public function houseHoldData($raw)
    {
        $this->houseCharacteristics($raw);
        $this->houseHoldCharacteristics($raw);
        $this->sanitation($raw);
        $this->housing($raw);
        $this->waste($raw);
        $this->income($raw);
        $this->calamity($raw);
    }

    public function houseHoldCreateRaw(int $household_id)
    {
        $raw = new HouseRawAnswer;
        $raw->household_id = $household_id;

        //hc
        $raw->building_type = request()->get("housingcharacteristics/buildingtype","");
        $raw->roof_materials = request()->get("housingcharacteristics/roofmaterials","");
        $raw->wall_materials = request()->get("housingcharacteristics/wallmaterials","");

        //hhc
        $raw->overseas_members = request()->get("charofhh/overseasmembers",0);
        $raw->nuclear_families = request()->get("charofhh/nuclearfamilies",0);
        $raw->memberssolo = request()->get("charofhh/memberssolo",0);
        $raw->no_of_hh = request()->get("charofhh/noofhh");
        $raw->fam_plan = request()->get("charofhh/famplan");
        $raw->fp  = request()->get("charofhh/plan") ?? "";
        $raw->fp_method = Str::replace(' ',',',request()->get("charofhh/modfam",""));
        $raw->fp_natural = Str::replace(' ',',',request()->get("charofhh/natfam",""));
        $raw->undecide = Str::replace(' ',',',request()->get("charofhh/undecided",""));
        $raw->indisease = request()->get("charofhh/indisease","");
        $raw->no_intent = Str::replace(' ',',',request()->get("charofhh/nointent",""));
        $raw->pregnant = request()->get("charofhh/pregnant","no");
        $raw->pregnant_number = request()->get("charofhh/memberspregnant",0);
        $raw->solo = request()->get("charofhh/solo","no");
        $raw->pwd = request()->get("charofhh/pwd","no");
        $raw->disabled_number = request()->get("charofhh/membersdisabled",0);
        $raw->pets = request()->get("charofhh/pets","no");
        $raw->number_pets = Str::replace(' ',',',request()->get("charofhh/numberpets", ""));
        $raw->pet_vaccine_date = request()->get("charofhh/datevaccine");
        $raw->pet_vax = request()->get("charofhh/petsvaccine","no");
        $raw->number_of_hh = request()->get("charofhh/noofhh",0);

        //sanitation
        $raw->main_source =  Str::replace(' ',',',request()->get('waterandsanitationgroup/mainsource',""));
        $raw->drink_water = Str::replace(' ',',',request()->get('waterandsanitationgroup/drinkwater'));
        $raw->hh_toilet  = Str::replace(' ',',',request()->get('waterandsanitationgroup/hhtoilet'));

        //housing
        $raw->status = Str::replace(' ',',',request()->get('housinggroup/status', ""));
        $raw->residence_area = request()->get('housinggroup/residencearea');
        $raw->electric = request()->get('housinggroup/electric');
        $raw->electric_housing = Str::replace(' ',',',request()->get('housinggroup/electrichousing',""));
        $raw->com_channel = request()->get('housinggroup/comchannel');
        $raw->housingother = request()->get('housinggroup/housingother');


        //waste

        $raw->garbage_disposal = Str::replace(' ',',',request()->get('wastemanagement/garbagedisposal',""));
        $raw->collector = Str::replace(' ',',',request()->get('wastemanagement/collector',""));
        $raw->often_garbage = Str::replace(' ',',',request()->get('wastemanagement/oftengarbage',""));
        $raw->volume_waste = request()->get('wastemanagement/volumewaste');
        $raw->disposalothers = request()->get('wastemanagement/disposalothers');



        //income
        $job = request()->get('sourcesofincome/joblist','');
        $raw->jobList = Str::replace(' ',',',request()->get('sourcesofincome/joblist',''));
        $jobList = explode(' ', $job);
        $amount = [];
        foreach ($jobList as $id){
            $amount[] = request("sourcesofincome/in{$id}",0);
        }
        $raw->jobIncome = implode(',',$amount);


        //calamity
        $raw->calamity_experienced = request()->get('calamitygroup/experienced');
        $raw->calamity = Str::replace(' ',',',request()->get('calamitygroup/calamity',""));
        $raw->assistancecalamity = request()->get('calamitygroup/assistancecalamity');



        $raw->save();

        return $raw;
    }
    public function houseCharacteristics($raw)
    {


        $hc = HouseCharacteristic::query()->firstOrNew([
            'household_id' => $raw->household_id
        ]);





        $hc->building_type = $this->building($raw->building_type);
        $hc->roof_materials = $this->materials($raw->roof_materials);
        $hc->wall_materials = $this->materials($raw->wall_materials);
        $hc->save();

    }
    public function houseHoldCharacteristics($raw)
    {
        $hhc = HouseholdCharacteristic::query()->firstOrNew([
            'household_id' => $raw->household_id
        ]);



        $hhc->overseas_members = $raw->overseas_members;
        $hhc->nuclear_families = $raw->nuclear_families;
        $hhc->memberssolo = $raw->memberssolo;
        $hhc->no_of_hh = $raw->no_of_hh;
        $hhc->fam_plan = $raw->fam_plan;
        $hhc->fp  = $raw->fp;
        $hhc->fp_method = $this->fpModernMethods($raw->fp_method);
        $hhc->fp_natural = $this->fpNaturalMethods($raw->fp_natural);
        $hhc->undecide = $this->undecided($raw->undecide);
        $hhc->indisease = $raw->indisease;
        $hhc->no_intent = $this->noIntent($raw->no_intent);
        $hhc->pregnant = $raw->pregnant;
        $hhc->pregnant_number = $raw->pregnant_number;
        $hhc->solo = $raw->solo;
        $hhc->pwd = $raw->pwd;
        $hhc->disabled_number = $raw->disabled_number;
        $hhc->pets = $raw->pets;
        $hhc->number_pets = $this->petNames($raw->number_pets);
        $hhc->pet_vaccine_date = $raw->pet_vaccine_date;
        $hhc->pet_vax = $raw->pet_vax;
        $hhc->number_of_hh = $raw->number_of_hh;
        $hhc->save();
    }
    public function houseHoldMembers(int $household_id)
    {




        $municipality = Municipality::query()->find(request()->get("group_identification/municipality",""));
        $barangay = Barangay::query()->find(request()->get("group_identification/barangay"));




        foreach (request()->get("personalinfo/personalinforepeat",[]) as $members){
            $patientId = 'p-' . rand(0, 999) . time();
            $userid = 'u-' . rand(0, 8888) . time();


            $first = $members["personalinfo/personalinforepeat/demographyrepeat/hhmemberfirst"] ?? "";
            $middle = $members["personalinfo/personalinforepeat/demographyrepeat/hhmembermiddle"] ?? "";
            $last = $members["personalinfo/personalinforepeat/demographyrepeat/hhmemberlast"] ?? "";

            $fill = [
                'patient_id' => $patientId,
                'management_id' => "m-81632116452",
                'main_mgmt_id' => "general-magement-09202021",
                'user_id' => $userid,
                'firstname' => $first,
                'lastname' => $last,
                'middle' => $middle,
                'suffix' => $members["personalinfo/personalinforepeat/demographyrepeat/hhmembersuffix"] ?? "",
                'birthday' => $members["personalinfo/personalinforepeat/demographyrepeat/bdateofmember"] ?? date("Y-m-d"),
                'civil_status' => $members["personalinfo/personalinforepeat/demographyrepeat/maritalstatusofmember"] ?? "",
                'gender' => $members["personalinfo/personalinforepeat/demographyrepeat/sexofmember"] ?? "",
                'street' => request()->get("group_identification/street","") ?? "",
                'purok' => request()->get("group_identification/purok_sitio","") ?? "",
                'mobile' => $members["personalinfo/personalinforepeat/demographyrepeat/D4_2_Contact_Number"] ?? "",
                'barangay' => $barangay->name ?? "",
                'municipality' => $municipality->name ?? "",
                'city' => $municipality->name ?? "",
                'barangay_id' => $barangay->id ?? null,
                'municipality_id' => $municipality->id ?? null,
                'purok_id' => request()->get("group_identification/purok_sitio") ?? null,
                'zone' => request()->get("group_identification/zone","") ?? "",
                'join_category' => 'hosp-app',
                'added_by' => null,
                'height' => $members["personalinfo/personalinforepeat/demographyrepeat/height"] ?? 0,
                'weight' => $members["personalinfo/personalinforepeat/demographyrepeat/weight"] ?? 0,
                'household_id' => $household_id,
                'head_relation' => $members["personalinfo/personalinforepeat/demographyrepeat/reltohh"] ?? "",
                'citizenship' => $members["personalinfo/personalinforepeat/demographyrepeat/citizenship"] ?? "",
                'religion' => $members["personalinfo/personalinforepeat/demographyrepeat/religion"] ?? "",
            ];

            $patient = new Patient();
            $patient->fill($fill);
            $location = $this->geoLocation();
            $patient->lat = $location['lat'] ?? 0;
            $patient->lng = $location['lng'] ?? 0;
            $patient->save();

            $raw = new PatientRawAnswer();
            $raw->immunization = Str::replace(' ',',',$members["personalinfo/personalinforepeat/healthofhh/immunization"] ?? "");
            $raw->vax_status = Str::replace(' ',',',$members["personalinfo/personalinforepeat/healthofhh/VaxStatus"] ?? "");
            $raw->crime_victim = Str::replace(' ',',',$members["personalinfo/personalinforepeat/crimegroup/crimevictim"] ?? "");
            $raw->crime_locations = Str::replace(' ',',',$members["personalinfo/personalinforepeat/crimegroup/crimelocations"] ?? "");
            $raw->patient_id = $patient->id;
            $raw->save();

            $member = new PatientInformation();
            $member->patient_id = $patient->id;

            //New Fields
            $member->pregnant = $members["personalinfo/personalinforepeat/demographyrepeat/pregnantwoman"] ?? "no";
            $member->lastmensperiod = $members["personalinfo/personalinforepeat/demographyrepeat/lastmensperiod"] ?? null;
            $member->clinic = $members["personalinfo/personalinforepeat/demographyrepeat/clinic"] ?? "no";
            $member->familycounselling = $members["personalinfo/personalinforepeat/demographyrepeat/familycounselling"] ?? "no";
            $member->prenatal = $members["personalinfo/personalinforepeat/demographyrepeat/prenatal"] ?? null;
            $member->tribe = $members["personalinfo/personalinforepeat/demographyrepeat/tribe"] ?? "";
            $member->noneIP = $members["personalinfo/personalinforepeat/demographyrepeat/noneIP"] ?? "";
            $member->tin = $members["personalinfo/personalinforepeat/maxwage/TINNo"] ?? "";
            $member->coopmember = $members["personalinfo/personalinforepeat/coopmember"] ?? "no";
            $member->philhealth = $members["personalinfo/personalinforepeat/philhealth"] ?? "no";
            $member->phic_no = $members["personalinfo/personalinforepeat/phic_no"] ?? "";
            $member->phic = $members["personalinfo/personalinforepeat/phic"] ?? "";
            $member->phicdirect = $members["personalinfo/personalinforepeat/phicdirect"] ?? "";
            $member->phicindirect = $members["personalinfo/personalinforepeat/phicindirect"] ?? "";
            $member->self_earning = $members["personalinfo/personalinforepeat/specifyselfearning"] ?? "";
            $member->migrant_worker = $members["personalinfo/personalinforepeat/specifyMigrantWorker"] ?? "";
            $member->arc_id = $members["personalinfo/personalinforepeat/acrIcardno"] ?? "";
            $member->foreign_num = $members["personalinfo/personalinforepeat/fnRPASRRVNo"] ?? "";
            $member->philsys = $members["personalinfo/personalinforepeat/demographyrepeat/D34_PHILSYS_ID_Number"] ?? "";
            /* $member->visit_clinic = $members["personalinfo/personalinforepeat/demographyrepeat/height"];
             $member->date_visit_clinic = $members["personalinfo/personalinforepeat/demographyrepeat/height"];*/
            $member->ob_gravida = $members["personalinfo/personalinforepeat/demographyrepeat/ob/gravida"]?? 0;
            $member->ob_parity = $members["personalinfo/personalinforepeat/demographyrepeat/ob/parity"]?? 0;
            $member->ob_abortion = $members["personalinfo/personalinforepeat/demographyrepeat/ob/abortion"]?? 0;
            $member->ob_living = $members["personalinfo/personalinforepeat/demographyrepeat/ob/living"]?? 0;
            $member->height_cm = $members["personalinfo/personalinforepeat/demographyrepeat/heightcm"] ?? 0;
            $member->registered_civil = $members["personalinfo/personalinforepeat/demographyrepeat/registeredincivilregoffice"] ?? "no";
            $member->marital_status = $members["personalinfo/personalinforepeat/demographyrepeat/maritalstatusofmember"] ?? "";
            $member->ethnicity = $members["personalinfo/personalinforepeat/demographyrepeat/ethnicityofmember"]?? "";
            $member->month_cash = $members["personalinfo/personalinforepeat/maxwage/monthcash"]?? 0;
            $member->immunization = $this->imunization($raw->immunization);
            $member->mmr = $members["personalinfo/personalinforepeat/healthofhh/selectmmr"]?? "";
            $member->hpv = $members["personalinfo/personalinforepeat/healthofhh/selecthpv"]?? "";
            $member->covid_vaccinated = $members["personalinfo/personalinforepeat/healthofhh/covid"]?? "";
            $member->vax_status = $this->vax_status($raw->vax_status);
            $member->dental = ($members["personalinfo/personalinforepeat/healthofhh/dental"] ?? 1) <= 3 ? $members["personalinfo/personalinforepeat/healthofhh/dental"]."x": "none" ;
            $member->cleft_lip = $members["personalinfo/personalinforepeat/healthofhh/cleftlip"] ?? "no";
            $member->donate_blood = $members["personalinfo/personalinforepeat/healthofhh/donateblood"]?? "no";
            $member->solo_parent = $members["personalinfo/personalinforepeat/healthofhh/hhsoloparent"] ?? "no";
            $member->physical_mental = $members["personalinfo/personalinforepeat/healthofhh/physicalmental"] ?? "no";
            $member->disease = $members["personalinfo/personalinforepeat/healthofhh/disease"] ?? "no";
            $member->other_disease = $members["personalinfo/personalinforepeat/healthofhh/otherdisease"]?? "";
            $member->past_victim = $members["personalinfo/personalinforepeat/crimegroup/pastvictim"]?? "no";
            $member->crime_victim = $this->crime($members["personalinfo/personalinforepeat/crimegroup/crimevictim"] ?? "");
            $member->crime_locations = $this->location($members["personalinfo/personalinforepeat/crimegroup/crimelocations"] ?? "");
            $member->children_nutrition_age = $members["personalinfo/personalinforepeat/nutriongroup/childrennutritionage"] ?? null;
            $member->children_nutrition_age_old_new = $members["personalinfo/personalinforepeat/nutriongroup/childrennutritionageoldnew"] ?? null;
            $member->children_nutrition_wt = $members["personalinfo/personalinforepeat/nutriongroup/childrennutritionwt"] ?? null;
            $member->date_nutrition = $members["personalinfo/personalinforepeat/nutriongroup/datenutrition"] ?? null;
            $member->care_center = $members["personalinfo/personalinforepeat/nutriongroup/carecenter"] ?? null;
            $member->feedingprogram = $members["personalinfo/personalinforepeat/nutriongroup/feedingprogram"] ?? null;
            $member->dental_carries = $members["personalinfo/personalinforepeat/nutriongroup/dentalcarries"] ?? null;
            $member->de_worm = $members["personalinfo/personalinforepeat/nutriongroup/deworm"] ?? null;
            $member->sth = $members["personalinfo/personalinforepeat/nutriongroup/sth"] ?? null;
            $member->selectoralpolio = $members["personalinfo/personalinforepeat/healthofhh/selectoralpolio"] ?? null;
            $member->specifypenta = $members["personalinfo/personalinforepeat/healthofhh/specifypenta"] ?? null;
            $member->selectmmr = $members["personalinfo/personalinforepeat/healthofhh/selectmmr"] ?? null;
            $member->selecthpv = $members["personalinfo/personalinforepeat/healthofhh/selecthpv"] ?? null;
            $member->donateblood  = $members["personalinfo/personalinforepeat/healthofhh/donateblood"] ?? null;
            $member->comnondisease = $members["personalinfo/personalinforepeat/healthofhh/comnondisease"] ?? '';
            $member->nondisease = $members["personalinfo/personalinforepeat/healthofhh/nondisease"] ?? '';
            $member->tbdate = $members["personalinfo/personalinforepeat/healthofhh/tbdate"] ?? null;
            $member->treatment = $members["personalinfo/personalinforepeat/healthofhh/treatment"] ?? null;
            $member->diabetes = $members["personalinfo/personalinforepeat/healthofhh/diabetes"] ?? null;
            $member->hypertension = $members["personalinfo/personalinforepeat/healthofhh/hypertension"] ?? null;
            $member->dismember = $members["personalinfo/personalinforepeat/healthofhh/yesinfiftytwo/dismember"] ?? null;
            $member->hhpwd = $members["personalinfo/personalinforepeat/healthofhh/hhpwd"] ?? null;
            $member->save();

            $diseases = [];

            $dc = "{$member->comnondisease} $member->nondisease";

            $ex = explode(' ',$dc);

            foreach ($ex as $d){
                if($d != ""){
                    $diseases[] = [
                        'patient_id' => $patient->id,
                        'disease' => $d,
                        'date_started' => date('Y-m-d'),
                        'municipality' => $municipality->id,
                        'barangay' => $barangay->id,
                        'latitude' => $patient->lat,
                        'longitude' => $patient->lng
                    ];
                }

            }

            if(count($diseases)){
                DiseaseHistory::query()->insert($diseases);
            }



            /*  $member->comnondisease = $members["personalinfo/personalinforepeat/healthofhh/comnondisease"] ?? null;
              $member->otherdisease = $members["personalinfo/personalinforepeat/healthofhh/otherdisease"] ?? null;
              $member->nondisease = $members["personalinfo/personalinforepeat/healthofhh/nondisease"] ?? null;*/






        }
    }
    public function sanitation($raw)
    {
        $sanitation = Sanitation::query()->firstOrNew([
            'household_id' => $raw->household_id
        ]);

        $sanitation->main_source =  $this->santitionList($raw->main_source);
        $sanitation->drink_water = $this->santitionList($raw->drink_water);
        $sanitation->hh_toilet = $this->toilet($raw->hh_toilet);
        $sanitation->save();

    }
    public function housing($raw)
    {
        $housing = Housing::query()->firstOrNew([
            'household_id' => $raw->household_id
        ]);





        $housing->status = $this->house($raw->status);
        $housing->residence_area = $raw->residence_area;
        $housing->electric = $raw->electric;
        $housing->electric_housing = $this->electricity($raw->electric_housing);
        $housing->com_channel = $raw->com_channel;
        $housing->housingother = $raw->housingother;

        $housing->save();
    }
    public function waste($raw)
    {
        $waste = WasteManagement::query()->firstOrNew([
            'household_id' => $raw->household_id
        ]);


        $waste->garbage_disposal = $this->wasteList($raw->garbage_disposal);
        $waste->collector = $this->collector($raw->collector);
        $waste->often_garbage = $this->timesCollect($raw->often_garbage);
        $waste->volume_waste = $raw->volume_waste;
        $waste->disposalothers = $raw->disposalothers;

        $waste->save();
    }

    public function income($raw)
    {
        $raw = HouseRawAnswer::query()->firstOrNew([
            'household_id' => $raw->household_id
        ]);
        $job = $raw->jobList;
        $jobList = explode(' ', $job);
        $jobIncome =  explode(',', $raw->jobIncome);

        SourceIncome::query()->where('household_id',$raw->household_id)->delete();
        foreach ($jobList as $key => $id){
            $income = new SourceIncome();
            $income->name = $this->jobList($id);
            $income->amount = $jobIncome[$key] ?? 0;
            $income->household_id = $raw->household_id;
            $income->save();
        }


    }

    public function calamity($raw)
    {
        $calamity =  Calamity::query()->firstOrNew([
            'household_id' => $raw->household_id
        ]);
        $calamity->experienced = $raw->calamity_experienced;
        $calamity->name = $this->calamityList($raw->calamity);
        $calamity->assistance = $raw->assistancecalamity;
        $calamity->save();
    }

    protected function materials($material): string
    {
        return match ($material) {
            'strongmaterials' => ' Strong materials (galvanized iron, aluminum, tile, concrete, brick, stone, asbestos)',
            'lightmaterials' => 'Light Materials (cogon, nipa, anahaw)',
            'salvaged' => 'Salvaged/makeshift materials',
            'mixedstrong' => 'Mixed but predominantly strong materials',
            'mixedlight' => 'Mixed but predominantly light materials',
            'mixedsalvaged' => 'Mixed but predominantly salvaged materials',
            'notapplicable' => 'Not applicable',
            default => $material
        };
    }

    protected function building($build): string
    {
        return match($build){
            'singlehouse' => "Single House",
            'duplex' => "Duplex",
            'multiunitresidential' => "Multi-unit residential (three units or more)",
            'commercialetc' => "Commercial/ industrial/ agricultural building (office, factory and others)",
            'otherhousingunit' => "Other housing unit (boat, cave and others)",
            default => $build
        };
    }

    protected function fpModernMethods($methods)
    {
        if(is_null($methods))
            return "";

        $numbers = explode(",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                '1' => 'Condom',
                '2' => 'Pill: Combined-Oral Contraceptive Pill (COC)',
                '3' => 'Pill: Progestin-Only Pill (POP)',
                '4' => 'IUD',
                '5' => 'DMPA/Injectable',
                '6' => 'Vasectomy',
                '7' => 'Bilateral Tubal Ligation (BTL)',
                '8' => 'Progestin Subdermal Implant',
                default => $no
            };
        }, $numbers);

        return implode(', ',$ways);
    }

    protected function fpNaturalMethods($methods)
    {
        if(is_null($methods))
            return "";

        $numbers = explode(",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                '1' => 'Lactation Amenorrhea Method (LAM)',
                '2' => 'Standard Days Method (SDM)',
                '3' => 'Sympto-Thermal Method (STM)',
                '4' => 'Basal Body Temperature (BBT)',
                '5' => 'Billings Ovulation Method (BOM)/ Cervical Mucus Method (CMM)',
                default => $no
            };
        }, $numbers);

        return implode(', ',$ways);
    }

    protected function petNames($numbers)
    {
        if(is_null($numbers) || Str::length($numbers) <=0)
            return "";
        $numbers = explode( ",",$numbers);

        $names = array_map(function ($no){
            return match ($no){
                '1' => 'Cat',
                '2' => 'Dog'
            };
        }, $numbers);

        return implode(', ',$names);

    }

    protected function santitionList($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode( ",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'waterandsanone' => 'Own use faucet, community water system',
                'waterandsantwo' => 'Shared faucet, community water system',
                'waterandsanthree' => 'Own use tubed/piped deep well',
                'waterandsanfour' => 'Shared tubed/piped deep well',
                'waterandsanfive' => 'Tubed/piped deep well',
                'waterandsansix' => 'Dug well',
                'waterandsanseven' => 'Protected spring',
                'waterandsaneight' => 'Unprotected spring',
                'waterandsannine' => 'Lake, river, rain and others',
                'waterandsanten' => 'Peddler',
                'waterandsaneleven' => 'Bottled Water',
                'waterandsantwelve' => 'Others, specify',
                default => $no

            };
        }, $numbers);

        return implode(', ',$ways);
    }

    protected function toilet($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode( ",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'totf_one' => 'Water-sealed, sewer septic tank, used exclusively by household',
                'totf_two' => ' Water-sealed, sewer septic tank, shared with other household',
                'totf_three' => 'Water-sealed, other depository, used exclusively by household',
                'totf_four' => 'Water-sealed, other depository, shared with other household',
                'totf_five' => 'Closed pit',
                'totf_six' => 'Open pit',
                'totf_seven' => 'Others (pail system and others)',
                'totf_eight' => 'None',
                default => $no

            };
        }, $numbers);
        return implode(', ',$ways);
    }

    protected function house($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode( ",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'housingone' => 'Own or owner-like possession of house and lot',
                'housingtwo' => 'Rent house/room including lot',
                'housingthree' => 'Own house, rent lot',
                'housingfour' => 'Own house, rent-free lot with consent of owner',
                'housingfive' => 'Own house, rent-free lot without consent of owner',
                'housingsix' => 'Rent-free house and lot with consent of owner',
                'housingseven' => 'Rent-free house and lot without consent of owner',
                'housingeight' => 'Living in a public space without rent',
                'housingten' => 'Other tenure status, specify',
                default => $no

            };
        }, $numbers);
        return implode(', ',$ways);
    }

    protected function electricity($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode( ",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'soe_one' => 'Electric company',
                'soe_two' => 'Generator',
                'soe_three' => 'Solar',
                'soe_four' => 'Battery',
                'soe_five' => 'Others, specify',

                default => $no

            };
        }, $numbers);
        return implode(', ',$ways);
    }

    protected function wasteList($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode(",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'gds_one' => 'Garbage Collection',
                'gds_two' => 'Burning',
                'gds_three' => 'Composting',
                'gds_four' => 'Recycling',
                'gds_five' => 'Waste Segregation',
                'gds_six' => 'Pit with cover',
                'gds_seven' => 'Pit without cover',
                'gds_eight' => 'Throwing of garbage in river, vacant lot, etc.',
                'gds_nine' => 'Others, specify',
                default => $no
            };
        }, $numbers);

        return implode(', ',$ways);
    }

    protected function collector($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode(",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'gc_one' => ' Municipality/city collector',
                'gc_two' => ' Barangay collector',
                'gc_three' => 'Private collector',
                'gc_four' => 'Others, specify',

                default => $no
            };
        }, $numbers);

        return implode(', ',$ways);
    }

    protected function timesCollect($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode( ",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'gct_one' => 'Daily',
                'gct_two' => 'Thrice a week',
                'gct_three' => 'Twice a week',
                'gct_four' => 'Once a week',
                'gct_five' => 'Others, specify',
                default => $no
            };
        }, $numbers);

        return implode(', ',$ways);
    }

    protected function jobList($methods)
    {
        {
            if(is_null($methods) || Str::length($methods) <=0)
                return "";

            $numbers = explode( ",",$methods);

            $ways = array_map(function ($no){
                return match ($no){
                    '1' => 'Crop Farming and Gardening',
                    '2' => 'Livestock and Poultry Raising',
                    '3' => 'Fishing',
                    '4' => 'Forestry and Hunting',
                    '5' => 'Wholesale and Retail',
                    '6' => 'Manufacturing',
                    '7' => 'Community Social Recreational and Personal Services',
                    '8' => 'Transportation, Storage, and Communication Services',
                    '9' => 'Mining and Quarrying',
                    '10' => 'Construction',
                    '11' => 'Remittances from Overseas Filipino Workers (OFW)',
                    '12' => 'Cash receipts, gifts, support, relief and other forms of assistance from abroad',
                    '13' => 'Cash receipts, support, assistance and relief from domestic sources',
                    '14' => 'Rental',
                    '15' => 'Interest from bank deposits, interest from loans extended to other families',
                    '16' => "Pension and retirement, workmen's compensation and social security benefits",
                    '17' => 'Dividends from investments',
                    '18_1' => 'Salary and Wages',
                    '19' => 'Other sources',
                };
            }, $numbers);

            return implode(', ',$ways);
        }

    }

    protected function calamityList($methods)
    {
        {
            if(is_null($methods) || Str::length($methods) <=0)
                return "";

            $numbers = explode(",",$methods);

            $ways = array_map(function ($no){
                return match ($no){
                    '1' => 'Typhoon',
                    '2' => 'Flood',
                    '3' => 'Drought',
                    '4' => 'Earthquake',
                    '5' => 'Volcanic Eruption',
                    '6' => 'Landslide',
                    '7' => 'Tsunami',
                    '8' => 'Fire',
                    '9' => 'Forest Fire',
                    '10' => 'Armed Conflict',
                    '11' => 'Other Calamity',

                };
            }, $numbers);

            return implode(', ',$ways);
        }

    }

    protected function noIntent($methods)
    {
        {
            if(is_null($methods) || Str::length($methods) <=0)
                return "";

            $numbers = explode(",",$methods);

            $ways = array_map(function ($no){
                return match ($no){
                    '1' => 'Menopause',
                    '2' => 'Newly-wed couple',
                    '3' => 'Still wants to bear a child.',
                    '4' => 'Cultural belief',
                    '5' => 'Others',

                };
            }, $numbers);

            return implode(', ',$ways);
        }

    }

    protected function undecided($methods)
    {
        {
            if(is_null($methods) || Str::length($methods) <=0)
                return "";

            $numbers = explode(",",$methods);

            $ways = array_map(function ($no){
                return match ($no){
                    '1' => 'Religion/Cultural belief',
                    '2' => 'Marital Agreement/Decision',
                    '3' => 'Health Related Diseases. If diagnosed pls. indicate',

                };
            }, $numbers);

            return implode(', ',$ways);
        }

    }
    public function imunization($methods)
    {
        {
            if(is_null($methods) || Str::length($methods) <=0)
                return "";

            $numbers = explode(",",$methods);

            Log::alert('numbers',[$numbers]);

            $ways = array_map(function ($no){
                return match ($no){
                    '1' => 'BCG',
                    '2' => 'Hepatitis B',
                    '3' => 'Pentavalent Vaccine (DPT-HepB-Hib)',
                    '4' => 'Oral Polio Vaccine (OPV)',
                    '5' => 'Inactivated Polio Vaccine (IPV)',
                    '6' => 'Pneumococcal Conjugate Vaccine (PCV)',
                    '7' => 'Measles, Mumps, Rubella (MMR)',
                    '8' => 'PPV23 Pneumococcal Pneumococcal Polyssacharide Vaccine',
                    '9' => 'Influenza Vaccine',
                    '10' => 'Human Papilloma Vaccine',

                };
            }, $numbers);

            return implode(', ',$ways);
        }

    }

    public function vax_status($methods)
    {
        {
            if(is_null($methods) || Str::length($methods) <=0)
                return "";

            $numbers = explode(",",$methods);

            Log::alert('4th4th',$numbers);

            $ways = array_map(function ($no){
                return match ($no){
                    '1st' => 'Partially Vaccinated (1st Primary Dose Only)',
                    '2nd' => 'Fully Vaccinated (1st & 2nd Primary Dose or Single Dose of J&J)',
                    '3rd' => 'Fully Vaccinated with 1st Booster',
                    '4th' => 'Fully Vaccinated with 2nd Booster',
                    'unvax' => 'Unvaccinated'

                };
            }, $numbers);

            return implode(', ',$ways);
        }

    }


    public function crime($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode(",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'cone' => 'Theft',
                'ctwo' => 'Robbery',
                'cthree' => 'Rape',
                'cfour' => 'Physical injury',
                'cfive' => 'Carnapping',
                'csix' => 'Cattle rustling',
                'cseven' => 'Others, specify',
                default => $no
            };
        }, $numbers);

        return implode(', ',$ways);
    }


    public function location($methods)
    {
        if(is_null($methods) || Str::length($methods) <=0)
            return "";

        $numbers = explode(",",$methods);

        $ways = array_map(function ($no){
            return match ($no){
                'loone' => 'Daily',
                'lotwo' => 'Thrice a week',
                'lothree' => 'Twice a week',
                'lofour' => 'Others, specify',
                default => $no
            };
        }, $numbers);

        return implode(', ',$ways);
    }



}
