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
use App\Models\User;
use App\Models\WasteManagement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InterviewService
{

    public function getPatients()
    {
        return Patient::query()->latest()->paginate(request('paginate', 12));
    }
    public function create($data = null)
    {

        if (!is_null($data))
            request()->merge($data);

        return $this->houseHold();
    }

    protected function geoLocation(): array
    {
        $l = request()->get("location", "");

        if (isset($l) && Str::length($l) > 0) {
            $location = explode(" ", $l);

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
        $householdCheck = Household::query()
            ->where('uuid', request()->get('_uuid'))
            ->first();

        if (!is_null($householdCheck)) {
            return;
        }

        $date = request()->get('group_identification/a10dateinterview');
        $time = explode(".", request()->get('group_identification/a11timestarted'))[0] ?? null;
        $date_interview = date('Y-m-d');
        if (isset($date, $time))
            $date_interview = "{$date} {$time}";



        $hh = new Household();
        $hh->province =  request()->get("group_identification/a1province", "");
        $hh->city   =  request()->get("group_identification/a2municipality", "");
        $hh->zone   =  request()->get("group_identification/zone", "");
        $hh->barangay   =  request()->get("group_identification/a4barangay", "");
        $hh->purok  =  request()->get("group_identification/a5puroksitio", "");
        $hh->street =  request()->get("group_identification/a6street", "");
        $hh->house_number   =  request()->get("group_identification/a7housenumber", "");
        $hh->respondent   =  request()->get("group_identification/a9namerespondent", "");
        $hh->house_id   =  request()->get("group_identification/a8householdidn", "");
        $hh->uuid = request()->get('_uuid');

        $user = User::query()->where('username', request()->get('_submitted_by'))->first();
        if (!is_null($user))
            $hh->surveyor_id = $user->id;

        $hh->date_interview = $date_interview;
        $hh->surveryor_name = request()->get('p1surveyor');
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
        $raw->building_type = request()->get("housingcharacteristics/b1buildingtype", "");
        $raw->roof_materials = request()->get("housingcharacteristics/b2roofmaterials", "");
        $raw->wall_materials = request()->get("housingcharacteristics/b3wallmaterials", "");

        //hhc
        $raw->overseas_members = request()->get("charofhh/c1overseasmembers", 0);
        $raw->nuclear_families = request()->get("charofhh/c2nuclearfamilies", 0);
        $raw->memberssolo = request()->get("charofhh/c3solo", 0);
        $raw->no_of_hh = request()->get("charofhh/noofhh");
        $raw->fam_plan = request()->get("charofhh/famplan");
        $raw->fp  = request()->get("charofhh/plan") ?? "";
        $raw->fp_method = Str::replace(' ', ',', request()->get("charofhh/modfam", ""));
        $raw->fp_natural = Str::replace(' ', ',', request()->get("charofhh/natfam", ""));
        $raw->undecide = Str::replace(' ', ',', request()->get("charofhh/undecided", ""));
        $raw->indisease = request()->get("charofhh/indisease", "");
        $raw->no_intent = Str::replace(' ', ',', request()->get("charofhh/nointent", ""));
        $raw->pregnant = request()->get("charofhh/pregnant", "no");
        $raw->pregnant_number = request()->get("charofhh/memberspregnant", 0);
        $raw->solo = request()->get("charofhh/c3solo", "no");
        $raw->pwd = request()->get("charofhh/c5pwd", "no");
        $raw->disabled_number = request()->get("charofhh/membersdisabled", 0);
        $raw->pets = request()->get("charofhh/c7pets", "no");
        $raw->number_pets = Str::replace(' ', ',', request()->get("charofhh/c8numberpets", ""));
        $raw->pet_vaccine_date = request()->get("charofhh/c10datevaccine");
        $raw->pet_vax = request()->get("charofhh/c9petsvaccine", "no");
        $raw->number_of_hh = request()->get("charofhh/c11noofhh", 0);

        //sanitation
        //k5farhouse
        $raw->main_source =  Str::replace(' ', ',', request()->get('waterandsanitationgroup/k1mainsource', ""));
        $raw->drink_water = Str::replace(' ', ',', request()->get('waterandsanitationgroup/k3drinkwater'));
        $raw->hh_toilet  = Str::replace(' ', ',', request()->get('waterandsanitationgroup/k6hhtoilet'));

        //housing
        $raw->status = Str::replace(' ', ',', request()->get('housinggroup/l1status', ""));
        $raw->residence_area = request()->get('housinggroup/l3residencearea');
        $raw->electric = request()->get('housinggroup/l5electric');
        $raw->electric_housing = Str::replace(' ', ',', request()->get('housinggroup/l6electrichousing', ""));
        $raw->com_channel = request()->get('housinggroup/l8comchannel');
        $raw->housingother = request()->get('housinggroup/housingother');


        //waste
        //m6collectiontimesother

        $raw->garbage_disposal = Str::replace(' ', ',', request()->get('wastemanagement/m1garbagedisposal', ""));
        $raw->collector = Str::replace(' ', ',', request()->get('wastemanagement/m3collector', ""));
        $raw->often_garbage = Str::replace(' ', ',', request()->get('wastemanagement/m5oftengarbage', ""));
        $raw->volume_waste = request()->get('wastemanagement/m7volumewaste');
        $raw->disposalothers = request()->get('wastemanagement/disposalothers');
        $raw->collectiontimesother = request()->get('wastemanagement/m6collectiontimesother');



        //income
        $job = request()->get('sourcesofincome/n1sincome', '');
        $raw->jobList = Str::replace(' ', ',', request()->get('sourcesofincome/n1sincome', ''));
        $jobList = explode(' ', $job);
        $amount = [];
        $lists = [
            '1' => 'a',
            '2' => 'b',
            '3' => 'c',
            '4' => 'd',
            '5' => 'e',
            '6' => 'f',
            '7' => 'g',
            '8' => 'h',
            '9' => 'i',
            '10' => 'j',
            '11' => 'k',
            '12' => 'l',
            '13' => 'm',
            '14' => 'n',
            '15' => 'o',
            '16' => 'p',
            '17' => 'q',
            '18_1' => 'r',
            '19' => 's',
        ];
        foreach ($jobList as $id) {
            $amount[] = request("sourcesofincome/n1{$lists[$id]}", 0);
        }
        $raw->jobIncome = implode(',', $amount);


        //calamity
        $raw->calamity_experienced = request()->get('calamitygroup/o1experienced');
        $raw->calamity = Str::replace(' ', ',', request()->get('calamitygroup/calamity', ""));
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

        $municipality = Municipality::query()
            ->where('name', request()->get("group_identification/a2municipality", ""))
            ->first();
        /* ->find(request()->get("group_identification/a2municipality",""));*/
        $barangay = Barangay::query()
            ->where('municipality_id', $municipality?->id)
            ->where('code', request()->get("group_identification/a4barangay"))
            /*->find(request()->get("group_identification/a4barangay"));*/
            ->first();


        foreach (request()->get("personalinfo/personalinforepeat", []) as $members) {
            $patientId = 'p-' . rand(0, 999) . time();
            $userid = 'u-' . rand(0, 8888) . time();


            $first = $members["personalinfo/personalinforepeat/demographyrepeat/d2hhmemberfirst"] ?? "";
            $middle = $members["personalinfo/personalinforepeat/demographyrepeat/d3hhmembermiddle"] ?? "";
            $last = $members["personalinfo/personalinforepeat/demographyrepeat/d1hhmemberlast"] ?? "";

            $gender = $members["personalinfo/personalinforepeat/demographyrepeat/d7sexofmember"] ?? "";
            $gender = Str::replace('sex_', '', $gender);
            $fill = [
                'patient_id' => $patientId,
                //'management_id' => "m-81632116452",
                'main_mgmt_id' => "general-magement-09202021",
                'user_id' => $userid,
                'firstname' => $first,
                'lastname' => $last,
                'middle' => $middle,
                'suffix' => $members["personalinfo/personalinforepeat/demographyrepeat/hhmembersuffix"] ?? "",
                'birthday' => $members["personalinfo/personalinforepeat/demographyrepeat/d17bdateofmember"] ?? date("Y-m-d"),
                'civil_status' => $members["personalinfo/personalinforepeat/demographyrepeat/maritalstatusofmember"] ?? "",
                'gender' => $gender,
                'street' => request()->get("group_identification/street", "") ?? "",
                'mobile' => $members["personalinfo/personalinforepeat/demographyrepeat/d42_contactnumber"] ?? "",
                'barangay' => $barangay->name ?? "",
                'municipality' => $municipality->name ?? "",
                'city' => $municipality->name ?? "",
                'barangay_id' => $barangay->id ?? null,
                'municipality_id' => $municipality->id ?? null,
                'purok' => request()->get("group_identification/a5puroksitio") ?? "",
                'purok_id' => request()->get("group_identification/a5puroksitio") ? Str::replace('Purok', '', request()->get("group_identification/a5puroksitio")) : null,
                'zone' => request()->get("group_identification/a6street", "") ?? "",
                'join_category' => 'hosp-app',
                'added_by' => null,
                'height' => $members["personalinfo/personalinforepeat/demographyrepeat/d20height"] ?? 0,
                'weight' => $members["personalinfo/personalinforepeat/demographyrepeat/d19weight"] ?? 0,
                'household_id' => $household_id,
                'head_relation' => $members["personalinfo/personalinforepeat/demographyrepeat/d5reltohh"] ?? "",
                'citizenship' => $members["personalinfo/personalinforepeat/demographyrepeat/d7a_citizenship"] ?? "",
                'religion' => $members["personalinfo/personalinforepeat/demographyrepeat/d30religion"] ?? "",
            ];

            $patient = new Patient();
            $patient->fill($fill);
            $location = $this->geoLocation();
            $patient->lat = $location['lat'] ?? 0;
            $patient->lng = $location['lng'] ?? 0;
            $patient->save();

            $raw = new PatientRawAnswer();
            $raw->immunization = Str::replace(' ', ',', $members["personalinfo/personalinforepeat/healthofhh/h1immunization"] ?? "");
            $raw->vax_status = Str::replace(' ', ',', $members["personalinfo/personalinforepeat/healthofhh/h7vaxstatus"] ?? "");
            $raw->crime_victim = Str::replace(' ', ',', $members["personalinfo/personalinforepeat/crimegroup/i2crimevictim"] ?? "");
            $raw->crime_locations = Str::replace(' ', ',', $members["personalinfo/personalinforepeat/crimegroup/i4crimelocations"] ?? "");
            $raw->patient_id = $patient->id;
            $raw->current_symptoms = "";
            $raw->first_degree = "";

            $raw->save();

            $member = new PatientInformation();
            $member->patient_id = $patient->id;

            //New Fields
            $member->pwd = $members["personalinfo/personalinforepeat/demographyrepeat/D34_a_pwd"] ?? "no";
            $member->pregnant = $members["personalinfo/personalinforepeat/demographyrepeat/pregnantwoman"] ?? "no";
            $member->mother_maiden_name = $members["personalinfo/personalinforepeat/demographyrepeat/d41_hhmembermotherMN"] ?? "";
            $member->lastmensperiod = $members["personalinfo/personalinforepeat/demographyrepeat/lastmensperiod"] ?? null;
            $member->clinic = $members["personalinfo/personalinforepeat/demographyrepeat/clinic"] ?? "no";
            $member->familycounselling = $members["personalinfo/personalinforepeat/demographyrepeat/familycounselling"] ?? "no";
            $member->prenatal = $members["personalinfo/personalinforepeat/demographyrepeat/prenatal"] ?? null;
            $member->tribe = $members["personalinfo/personalinforepeat/demographyrepeat/d24tribe"] ?? "";
            $member->noneIP = $members["personalinfo/personalinforepeat/demographyrepeat/d28noneIP"] ?? "";
            $member->tin = $members["personalinfo/personalinforepeat/maxwage/d18b_TINNo"] ?? "";
            $member->coopmember = $members["personalinfo/personalinforepeat/d35coopmember"] ?? "no";
            $member->philhealth = $members["personalinfo/personalinforepeat/d36philhealth"] ?? "no";
            $member->sss_member = $members["personalinfo/personalinforepeat/d42hhmembersss"] ?? "no";
            $member->phic_no = $members["personalinfo/personalinforepeat/phic_no"] ?? "";
            $member->phic = $members["personalinfo/personalinforepeat/phic"] ?? "";
            $member->phicdirect = $members["personalinfo/personalinforepeat/phicdirect"] ?? "";
            $member->phicindirect = $members["personalinfo/personalinforepeat/phicindirect"] ?? "";
            $member->self_earning = $members["personalinfo/personalinforepeat/specifyselfearning"] ?? "";
            $member->migrant_worker = $members["personalinfo/personalinforepeat/specifyMigrantWorker"] ?? "";
            $member->arc_id = $members["personalinfo/personalinforepeat/acrIcardno"] ?? "";
            $member->foreign_num = $members["personalinfo/personalinforepeat/fnRPASRRVNo"] ?? "";
            $member->philsys = $members["personalinfo/personalinforepeat/demographyrepeat/D34philsysidnumber"] ?? "";
            /* $member->visit_clinic = $members["personalinfo/personalinforepeat/demographyrepeat/height"];
            $member->date_visit_clinic = $members["personalinfo/personalinforepeat/demographyrepeat/height"];*/
            $member->ob_gravida = $members["personalinfo/personalinforepeat/demographyrepeat/ob/d12gravida"] ?? 0;
            $member->ob_parity = $members["personalinfo/personalinforepeat/demographyrepeat/ob/d13parity"] ?? 0;
            $member->ob_abortion = $members["personalinfo/personalinforepeat/demographyrepeat/ob/d14abortion"] ?? 0;
            $member->ob_living = $members["personalinfo/personalinforepeat/demographyrepeat/ob/d15living"] ?? 0;
            $member->received_tetanus = $members["personalinfo/personalinforepeat/demographyrepeat/ob/D15a_Did_you_received_Tetanus"] ?? "no";
            $member->height_cm = $members["personalinfo/personalinforepeat/demographyrepeat/heightcm"] ?? 0;
            $member->registered_civil = $members["personalinfo/personalinforepeat/demographyrepeat/d22registeredincivilregoffice"] ?? "no";
            $member->marital_status = $members["personalinfo/personalinforepeat/demographyrepeat/d23maritalstatusofmember"] ?? "";
            $member->ethnicity = $members["personalinfo/personalinforepeat/demographyrepeat/ethnicityofmember"] ?? "";
            $member->month_cash = $members["personalinfo/personalinforepeat/maxwage/d18a_monthcash"] ?? 0;
            $member->immunization = $this->imunization($raw->immunization);
            $member->mmr = $members["personalinfo/personalinforepeat/healthofhh/selectmmr"] ?? "";
            $member->hpv = $members["personalinfo/personalinforepeat/healthofhh/selecthpv"] ?? "";
            $member->covid_vaccinated = $members["personalinfo/personalinforepeat/healthofhh/covid"] ?? "";
            $member->vax_status = $this->vax_status($raw->vax_status);
            $member->dental = ($members["personalinfo/personalinforepeat/healthofhh/h8dental"] ?? 1) <= 3 ? $members["personalinfo/personalinforepeat/healthofhh/h8dental"] . "x" : "none";
            $member->cleft_lip = $members["personalinfo/personalinforepeat/healthofhh/h9cleftlip"] ?? "no";
            $member->donate_blood = $members["personalinfo/personalinforepeat/healthofhh/h10donateblood"] ?? "no";
            $member->solo_parent = $members["personalinfo/personalinforepeat/healthofhh/h11hhsoloparent"] ?? "no";
            $member->physical_mental = $members["personalinfo/personalinforepeat/healthofhh/h12physicalmental"] ?? "no";
            $member->smoke = $members["personalinfo/personalinforepeat/healthofhh/H12d_smoke"] ?? "no";
            $member->sexactive = $members["personalinfo/personalinforepeat/healthofhh/H121_sexactive"] ?? "no";
            $member->howmanysex = $members["personalinfo/personalinforepeat/healthofhh/H121a_howmanysex"] ?? '';

            $member->disease = $members["personalinfo/personalinforepeat/healthofhh/h13disease"] ?? "no";
            $member->other_disease = $members["personalinfo/personalinforepeat/healthofhh/otherdisease"] ?? "";
            $member->past_victim = $members["personalinfo/personalinforepeat/crimegroup/i1pastvictim"] ?? "no";
            $member->crime_victim = $this->crime($members["personalinfo/personalinforepeat/crimegroup/i2crimevictim"] ?? "");
            $member->crime_locations = $this->location($members["personalinfo/personalinforepeat/crimegroup/i4crimelocations"] ?? "");
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
            $member->donateblood  = $members["personalinfo/personalinforepeat/healthofhh/h10donateblood"] ?? null;
            $member->bloodtransfuse = $members["personalinfo/personalinforepeat/healthofhh/h10a_bloodtransfuse"] ?? null;
            $member->comnondisease = $members["personalinfo/personalinforepeat/healthofhh/h15comnondisease"] ?? '';
            $member->nondisease = $members["personalinfo/personalinforepeat/healthofhh/h16nondisease"] ?? '';
            $member->tbdate = $members["personalinfo/personalinforepeat/healthofhh/tbdate"] ?? null;
            $member->treatment = $members["personalinfo/personalinforepeat/healthofhh/treatment"] ?? null;
            $member->diabetes = $members["personalinfo/personalinforepeat/healthofhh/diabetes"] ?? null;
            $member->hypertension = $members["personalinfo/personalinforepeat/healthofhh/hypertension"] ?? null;
            $member->dismember = $members["personalinfo/personalinforepeat/healthofhh/yesinfiftytwo/dismember"] ?? null;
            $member->hhpwd = $members["personalinfo/personalinforepeat/healthofhh/hhpwd"] ?? null;
            $member->save();

            $diseases = [];

            $dc = "{$member->comnondisease} $member->nondisease";

            $ex = explode(' ', $dc);

            foreach ($ex as $d) {
                if ($d != "") {
                    $diseases[] = [
                        'patient_id' => $patient->id,
                        'disease' => $d,
                        'date_started' => date('Y-m-d'),
                        'municipality' => $municipality->id,
                        'barangay' => $barangay->id,
                        'latitude' => $patient->lat,
                        'longitude' => $patient->lng,
                        'pui' => 1
                    ];
                }
            }

            if (count($diseases)) {
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
        $waste->collectiontimesother = $raw->collectiontimesother;

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

        SourceIncome::query()->where('household_id', $raw->household_id)->delete();
        foreach ($jobList as $key => $id) {
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
        return match ($build) {
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
        if (is_null($methods))
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
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

        return implode(', ', $ways);
    }

    protected function fpNaturalMethods($methods)
    {
        if (is_null($methods))
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
                '1' => 'Lactation Amenorrhea Method (LAM)',
                '2' => 'Standard Days Method (SDM)',
                '3' => 'Sympto-Thermal Method (STM)',
                '4' => 'Basal Body Temperature (BBT)',
                '5' => 'Billings Ovulation Method (BOM)/ Cervical Mucus Method (CMM)',
                default => $no
            };
        }, $numbers);

        return implode(', ', $ways);
    }

    protected function petNames($numbers)
    {
        if (is_null($numbers) || Str::length($numbers) <= 0)
            return "";
        $numbers = explode(",", $numbers);

        $names = array_map(function ($no) {
            return match ($no) {
                '1' => 'Cat',
                '2' => 'Dog'
            };
        }, $numbers);

        return implode(', ', $names);
    }

    protected function santitionList($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
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

        return implode(', ', $ways);
    }

    protected function toilet($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
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
        return implode(', ', $ways);
    }

    protected function house($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
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
        return implode(', ', $ways);
    }

    protected function electricity($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
                'soe_one' => 'Electric company',
                'soe_two' => 'Generator',
                'soe_three' => 'Solar',
                'soe_four' => 'Battery',
                'soe_five' => 'Others, specify',

                default => $no
            };
        }, $numbers);
        return implode(', ', $ways);
    }

    protected function wasteList($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
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

        return implode(', ', $ways);
    }

    protected function collector($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
                'gc_one' => ' Municipality/city collector',
                'gc_two' => ' Barangay collector',
                'gc_three' => 'Private collector',
                'gc_four' => 'Others, specify',

                default => $no
            };
        }, $numbers);

        return implode(', ', $ways);
    }

    protected function timesCollect($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
                'gct_one' => 'Daily',
                'gct_two' => 'Thrice a week',
                'gct_three' => 'Twice a week',
                'gct_four' => 'Once a week',
                'gct_five' => 'Others, specify',
                default => $no
            };
        }, $numbers);

        return implode(', ', $ways);
    }

    protected function jobList($methods)
    { {
            if (is_null($methods) || Str::length($methods) <= 0)
                return "";

            $numbers = explode(",", $methods);

            $ways = array_map(function ($no) {
                return match ($no) {
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

            return implode(', ', $ways);
        }
    }

    protected function calamityList($methods)
    { {
            if (is_null($methods) || Str::length($methods) <= 0)
                return "";

            $numbers = explode(",", $methods);

            $ways = array_map(function ($no) {
                return match ($no) {
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

            return implode(', ', $ways);
        }
    }

    protected function noIntent($methods)
    { {
            if (is_null($methods) || Str::length($methods) <= 0)
                return "";

            $numbers = explode(",", $methods);

            $ways = array_map(function ($no) {
                return match ($no) {
                    '1' => 'Menopause',
                    '2' => 'Newly-wed couple',
                    '3' => 'Still wants to bear a child.',
                    '4' => 'Cultural belief',
                    '5' => 'Others',
                };
            }, $numbers);

            return implode(', ', $ways);
        }
    }

    protected function undecided($methods)
    { {
            if (is_null($methods) || Str::length($methods) <= 0)
                return "";

            $numbers = explode(",", $methods);

            $ways = array_map(function ($no) {
                return match ($no) {
                    '1' => 'Religion/Cultural belief',
                    '2' => 'Marital Agreement/Decision',
                    '3' => 'Health Related Diseases. If diagnosed pls. indicate',
                };
            }, $numbers);

            return implode(', ', $ways);
        }
    }
    public function imunization($methods)
    { {
            if (is_null($methods) || Str::length($methods) <= 0)
                return "";



            $numbers = explode(",", $methods);

            Log::alert('numbers error', [$numbers]);

            $ways = array_map(function ($no) {
                return match ($no) {
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
                    '11' => 'Tetanus Toxoid',
                    "12_1" => "None",
                };
            }, $numbers);

            return implode(', ', $ways);
        }
    }

    public function vax_status($methods)
    { {
            if (is_null($methods) || Str::length($methods) <= 0)
                return "";

            $numbers = explode(",", $methods);

            Log::alert('4th4th', $numbers);

            $ways = array_map(function ($no) {
                return match ($no) {
                    '1st' => 'Partially Vaccinated (1st Primary Dose Only)',
                    '2nd' => 'Fully Vaccinated (1st & 2nd Primary Dose or Single Dose of J&J)',
                    '3rd' => 'Fully Vaccinated with 1st Booster',
                    '4th' => 'Fully Vaccinated with 2nd Booster',
                    'unvax' => 'Unvaccinated'
                };
            }, $numbers);

            return implode(', ', $ways);
        }
    }


    public function crime($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
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

        return implode(', ', $ways);
    }


    public function location($methods)
    {
        if (is_null($methods) || Str::length($methods) <= 0)
            return "";

        $numbers = explode(",", $methods);

        $ways = array_map(function ($no) {
            return match ($no) {
                'loone' => 'Daily',
                'lotwo' => 'Thrice a week',
                'lothree' => 'Twice a week',
                'lofour' => 'Others, specify',
                default => $no
            };
        }, $numbers);

        return implode(', ', $ways);
    }
}
