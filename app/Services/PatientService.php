<?php

namespace App\Services;

use App\Models\DiseaseHistory;
use App\Models\DoctorPatient;
use App\Models\Patient;
use App\Models\PatientDependents;
use App\Models\PatientInformation;
use App\Models\PatientRawAnswer;
use App\Models\Vital;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PatientService
{
    public function create(Request $request, bool $more = false)
    {
        $patientId = 'p-' . rand(0, 999) . time();
        $userid = 'u-' . rand(0, 8888) . time();


        $patient = new Patient();
        $patient = $this->setInformation($patient, $request);
        $patient->join_category = 'hosp-app';
        $patient->patient_id = $patientId;
        $patient->user_id = $userid;
        $patient->main_mgmt_id = "general-magement-09202021";
        $patient->management_id = "m-81632116452";

        if ($request->hasFile('avatar')) {
            $patient->avatar = $request->file('avatar')->store("patient/avatars");
        }

        $patient->save();
        if ($more)
            $this->patientData($patient->id);

        $dependents = is_array($request->get('patientDependents')) ? $request->get('patientDependents') : [];

        $this->addDependents($dependents, $patient->id);

        return $patient;
    }


    public function update(Request $request, Patient|int $id, bool $more = false)
    {
        if ($id instanceof Patient) {
            $patient = $id;
        } else {
            $patient = Patient::query()->findOrFail($id);
        }
        $patient = $this->setInformation($patient, $request);
        $patient->save();

        if ($more)
            $this->patientData($patient);

        $dependents = is_array($request->get('patientDependents')) ? $request->get('patientDependents') : [];
        $this->addDependents($dependents, $patient->id);
        return $patient;
    }

    public function makeEditAvailable(string $form_id, int $kobotools_id)
    {
        /*   $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', "https://kf.kobotoolbox.org/api/v2/assets/{$form_id}/data/{$kobotools_id}/enketo/edit/?return_url=false", [
            'headers' => [
                'Authorization' => "Token ec827f3affe8042be262037d9c93471190ad2d53",
            ],
        ]);

        return json_decode($response->getBody());*/
    }

    public function addDoctor(int $patient_id, int $doctor_id): void
    {
        /*$doctorPatient = new DoctorPatient();
        $doctorPatient->patient_id = $patient_id;
        $doctorPatient->doctor_id = $doctor_id;
        $doctorPatient->save();*/
    }

    public function patientData(mixed $patient)
    {
        $raw = PatientRawAnswer::query()->firstOrNew([
            'patient_id' => $patient->id
        ]);
        $raw->immunization = request('immunization', "");
        $raw->vax_status = request('vax_status', "");
        $raw->crime_victim = request("crime_victim", "");
        $raw->crime_locations = request("crime_locations", "");
        $raw->save();

        $interview = new InterviewService();

        $member = PatientInformation::query()->firstOrNew([
            'patient_id' => $patient->id
        ]);
        $member->pregnant = request()->get("pregnant", "no");

        //New Fields
        $member->lastmensperiod = request()->get("lastmensperiod") ?: null;
        $member->clinic =  request()->get("clinic") ?: "no";
        $member->familycounselling = request()->get("familycounselling") ?: "no";
        $member->prenatal = request()->get("prenatal") ?: null;
        $member->tribe = request()->get("tribe") ?: "";
        $member->noneIP = request()->get("noneIP") ?: "";
        $member->tin = request()->get("tin") ?: "";
        $member->coopmember = request()->get("coopmember") ?: "no";
        $member->philhealth = request()->get("philhealth") ?: "no";
        $member->phic_no = request()->get("phic_no") ?: "";
        $member->phic = request()->get("phic") ?: "";
        $member->phicdirect = request()->get("phicdirect") ?: "";
        $member->ip_tribe = request()->get("ip_tribe") ?: "";
        $member->otherIPtribe = request()->get("otherIPtribe") ?: "";

        $member->phicindirect = request()->get("phicindirect") ?: "";
        $member->self_earning = request()->get("self_earning") ?: "";
        $member->migrant_worker = request()->get("migrant_worker") ?: "";
        $member->mother_maiden_name = request()->get("mother_maiden_name") ?: "";
        $patient->mother_lastname = request()->get("mother_lastname") ?: "";
        $patient->mother_firstname = request()->get("mother_firstname") ?: "";
        $patient->mother_middlename = request()->get("mother_middlename") ?: "";
        $member->feedingprogram = request()->get("feedingprogram") ?: "";
        $member->arc_id = request()->get("arc_id") ?: "";
        $member->foreign_num = request()->get("foreign_num") ?: "";
        $member->philsys = request()->get("philsys") ?: "";

        /* $member->visit_clinic = $members["personalinfo/personalinforepeat/demographyrepeat/height"];
         $member->date_visit_clinic = $members["personalinfo/personalinforepeat/demographyrepeat/height"];*/
        $member->ob_gravida = request()->get("ob_gravida", 0);
        $member->ob_parity = request()->get("ob_parity", 0);
        $member->ob_abortion = request()->get("ob_abortion", 0);
        $member->ob_living = request()->get("ob_living", 0);
        $member->height_cm = request()->get("height_cm", 0);
        $member->registered_civil = request()->get("registered_civil", "no");
        $member->marital_status = request()->get("marital_status", "no");
        $member->ethnicity = request()->get("ethnicity", "no");
        $member->month_cash = request()->get("month_cash", 0);
        $member->philhealth = request()->get("philhealth", "no");
        // $member->immunization = $interview->imunization(request()->get("immunization", "no"));
        $member->mmr = request()->get("mmr", "");
        $member->hpv = request()->get("hpv", "");
        $member->covid_vaccinated = request()->get("covid_vaccinated", "");
        $member->vax_status = $interview->vax_status($raw->vax_status);
        $member->dental = (request()->get("dental", "") ?? 1) <= 3 ? request()->get("dental", "") . "x" : "none";
        $member->cleft_lip = request()->get("cleft_lip", "no");
        $member->donate_blood = request()->get("donate_blood", "no");
        $member->solo_parent = request()->get("solo_parent", "no");
        $member->physical_mental = request()->get("physical_mental", "no");
        $member->disease = request()->get("disease", "no");
        $member->other_disease = request()->get("other_disease", "");
        $member->past_victim = request()->get("past_victim", "");
        $member->crime_victim = $interview->crime(request()->get("crime_victim", ""));
        $member->crime_locations = $interview->location(request()->get("crime_locations", ""));
        $member->children_nutrition_age = request()->get("children_nutrition_age");
        $member->children_nutrition_age_old_new = request()->get("children_nutrition_age_old_new");
        $member->children_nutrition_wt = request()->get("children_nutrition_wt");
        $member->date_nutrition = request()->get("date_nutrition");
        $member->care_center = request()->get("care_center");
        $member->dental_carries = request()->get("dental_carries");
        $member->de_worm = request()->get("de_worm");
        $member->sth = request()->get("sth");
        $member->selectoralpolio = request()->get("selectoralpolio");
        $member->dismember = request()->get("dismember");
        $member->specifypenta = request()->get("specifypenta");
        $member->selectmmr = request()->get("selectmmr");
        $member->selecthpv = request()->get("selecthpv");
        $member->donateblood  = request()->get("donateblood");
        $member->comnondisease = request()->get("comnondisease", "");
        $member->nondisease = request()->get("nondisease", "");
        $member->tbdate = request()->get("tbdate");
        $member->treatment = request()->get("treatment");
        $member->diabetes = request()->get("diabetes");
        $member->hypertension = request()->get("hypertension");
        $member->dismember = request()->get("dismember");
        $member->hhpwd = request()->get("hhpwd");
        $patient->direct_contributor = request()->get('direct_contributor', "");
        $patient->indirect_contributor = request()->get('indirect_contributor', "");
        $patient->profession = request()->get('profession', "");
        $patient->salary = request()->get('salary', "");
        $patient->tin = request()->get('tin', "");
        $patient->floor = request()->get('floor', "");
        $patient->unit = request()->get('unit', "");
        $patient->zip = request()->get('zip_code', "");
        $member->save();

        $diseases = [];

        $dc = "{$member->comnondisease} $member->nondisease";

        $ex = explode(',', $dc);

        foreach ($ex as $d) {
            if ($d != "") {
                $diseases[] = [
                    'patient_id' => $patient->id,
                    'disease' => $d,
                    'date_started' => date('Y-m-d'),
                    'municipality' => $patient->municipality_id,
                    'barangay' => $patient->barangay_id,
                    'latitude' => $patient->lat,
                    'longitude' => $patient->lng
                ];
            }
        }

        if (count($diseases)) {
            DiseaseHistory::query()->insert($diseases);
        }
    }


    public function addDependents(array $dependents, int $patienId)
    {
        PatientDependents::query()->where('patient_id', $patienId)->delete();


        foreach ($dependents as $dependent) {
            $data = [];
            $data['patient_id'] = $patienId;
            $data['firstname'] = $dependent['firstname'];
            $data['lastname'] = $dependent['lastname'];
            $data['middle_name'] = $dependent['middle_name'];
            $data['name_extension'] = $dependent['name_extension'] ?? "";
            $data['relationship'] = $dependent['relationship'] ?? "";
            $data['birthday'] = date('Y-m-d');
            $data['citizenship'] = $dependent['citizenship'] ?? "Filipino";
            $data['is_permanently_disabled'] = 0;
            PatientDependents::query()->insert($data);
        }
    }


    private function setInformation(Patient $patient, Request $request): Patient
    {
        $patient->firstname = $request->get('firstname');
        $patient->lastname = $request->get('lastname');
        $patient->tin = $request->get('tin');
        $patient->lat = $request->get('lat', 0);
        $patient->lng = $request->get('lng', 0);
        if ($request->has('middle')) {
            $patient->middle = $request->get('middle', '');
        } else {
            $patient->middle = $request->get('middlename', '');
        }
        $patient->gender = $request->get('gender');
        $patient->birthday = $request->get('birthdate');
        $patient->street = $request->get('street', '');
        $patient->barangay_id = $request->get('barangay');
        $patient->barangay = $request->get('barangay');
        $patient->province = $request->get('province');
        $patient->region = $request->get('region');
        $patient->zip = $request->get('zip_code');
        $patient->purok_id = $request->get('purok');
        $patient->municipality_id = $request->get('municipality');
        $patient->municipality = $request->get('municipality');
        $patient->purok = $request->get('purok');
        $patient->purok_id = $request->get('purok');
        $patient->birthplace = $request->get('birthplace');
        $patient->civil_status = $request->get('civil_status');
        $patient->philhealth = $request->get('philhealth', '');
        $patient->telephone = $request->get('telephone');
        $patient->email = $request->get('email', '');
        $patient->citizenship = $request->get('citizenship', '');
        $patient->suffix = $request->get('suffix', "");
        $patient->religion = $request->get('religion', "");
        $patient->floor = $request->get('unit') ? $request->get('unit') : $request->get('floor');
        $patient->mother_lastname = $request->get("mother_lastname") ?: "";
        $patient->mother_firstname = $request->get("mother_firstname") ?: "";
        $patient->mother_middlename = $request->get("mother_middlename") ?: "";
        $patient->direct_contributor = $request->get('direct_contributor', "");
        $patient->indirect_contributor = $request->get('indirect_contributor', "");
        $patient->profession = $request->get('profession', "");
        $patient->salary = $request->get('monthly_income') ? $request->get('monthly_income') : $request->get('salary');
        $patient->house_number = $request->get('house_number', "");
        $patient->tin = $request->get('tin', "");
        $patient->subdivision = $request->get('subdivision', "");

        if ($request->hasFile('avatar')) {
            $patient->avatar = $request->file('avatar')->store("patient/avatars");
        }

        return $patient;
    }

    public function get(int $id)
    {
        $patient = Patient::query()->findOrFail($id);
    }

    public function getSubmissions()
    {
        return Patient::query()->whereNull('user_id')->get();
    }


    public function updateVitals(Request $request, int $patientId): void
    {



        $patient = Patient::query()->findOrFail($patientId);
        $patient->lmp = $request->has('lmp') ? $request->get('lmp') : $patient->lmp;
        $patient->respiratory = $request->get('respiratory');
        $patient->glucose = $request->get('glucose');
        $patient->uric_acid = $request->get('uric_acid');
        $patient->cholesterol = $request->get('cholesterol');
        $patient->height = $request->get('height');
        $patient->weight = $request->get('weight');
        $patient->pulse = $request->get('pulse');
        $patient->temperature = $request->get('temperature');
        $patient->blood_systolic = $request->get('blood_systolic');
        $patient->blood_diastolic = $request->get('blood_diastolic');
        $patient->save();

        $vital = new Vital();

        $vital->patient_id = $patientId;
        $vital->added_by_id = $request->user()->id;
        $vital->temperature = $request->get('temperature');
        $vital->blood_pressure = $request->get('blood_pressure') ?: "";
        $vital->weight = $request->get('weight');
        $vital->height = $request->get('height');
        $vital->respiratory = $request->get('respiratory');
        $vital->uric_acid = $request->get('uric_acid');
        $vital->cholesterol = $request->get('cholesterol');
        $vital->glucose = $request->get('glucose');
        $vital->pulse = $request->get('pulse');
        $vital->blood_systolic = $patient->blood_systolic;
        $vital->blood_diastolic = $patient->blood_diastolic;
        $vital->save();
    }


    public function chartData(int $id)
    {

        request()->validate([
            'chart_type' => ['required', 'string', Rule::in(
                [
                    'temperature',
                    'blood_pressure',
                    'respiratory',
                    'uric_acid',
                    'cholesterol',
                    'glucose',
                    'pulse',
                    'weight',
                    'height',
                ]
            )]
        ]);

        $type = request('chart_type');
        $value = [];
        $label = [];

        if ($type == 'blood_pressure') {
            $vitals = Vital::query()->select(['blood_systolic', 'blood_diastolic', 'created_at'])
                ->where('patient_id', $id)->latest()->get();

            foreach ($vitals as $vital) {
                $value[] = [
                    'blood_systolic' => $vital->blood_systolic,
                    'blood_diastolic' => $vital->blood_diastolic
                ];
                $label[] = $vital->created_at->format('M d,Y h:i:A');
            }
        } else {
            $vitals = Vital::query()->select([$type, 'created_at'])->where('patient_id', $id)->latest()->get();


            foreach ($vitals as $vital) {
                $value[] = $vital->$type;
                $label[] = $vital->created_at->format('M d,Y h:i:A');
            }
        }


        return [
            'values' => $value,
            'labels' => $label
        ];
    }


    public function mappings()
    {



        //store in a cache that will expire every 24 hours

        return Cache::remember('patient-mapping', 60 * 24, function () {
            return DB::table('patients')
                ->select('patients.id', 'patients.firstname', 'patients.lastname', 'patients.middle', 'patients.lat', 'patients.lng', 'municipalities.name as municipalityName', 'barangays.name as barangayName')
                ->join('municipalities', 'patients.municipality_id', '=', 'municipalities.id')
                ->join('barangays', 'patients.barangay_id', '=', 'barangays.id')
                ->whereNotNull('patients.lat')
                ->whereNotNull('patients.lng')
                ->get();
        });
    }
}
