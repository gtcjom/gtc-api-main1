<?php

namespace App\Jobs;

use App\Models\Patient;
use App\Models\PatientDependents;
use App\Models\Vital;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncedPatientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ?string $lastSync;
    private int $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(?string $lastSync, int $page = 1)
    {
        //
        $this->lastSync = $lastSync;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        //send request get request to cloud

        try {
            $url = config('app.cloud_url') . '/api/patients/un-synced-list?entity_type='
                . config('app.entity')
                . '&entity_key=' . config('app.entity_key')
                . '&lastSync=' . $this->lastSync
                . '&entity_unit=' . config('app.entity_unit')
                . '&page=' . $this->page;
            $response = $client->request(
                'GET',
                $url
            );

            if ($response->getStatusCode() == 200) {
                //get response body
                //return response
                $body = json_decode($response->getBody());
                $cloudPatients = $body->data;

                foreach ($cloudPatients as $cloudPatient) {
                    $patient = Patient::query()->firstOrNew([
                        'cloud_id' => $cloudPatient->cloud_id
                    ]);


                    if (is_null($patient->id)) {
                        $patient->join_category = 'hosp-app';
                        $patient->patient_id = $cloudPatient->cloud_id;
                        $patient->user_id = $cloudPatient->cloud_id;
                        $patient->main_mgmt_id = "general-magement-09202021";
                        $patient->management_id = "m-81632116452";
                    }

                    //get all properties of the object $cloudPatient

                    $properties = array_keys(get_object_vars($cloudPatient));



                    $propertiesToExclude = ['vitalHistory', 'dependents', 'avatar', 'updateHistory', 'department_add', 'respiratory'];
                    foreach ($properties as $property) {
                        if (!in_array($property, $propertiesToExclude)) {
                            $patient->$property = $cloudPatient->$property;
                        }
                    }

                    $patient->rispiratory = $cloudPatient->respiratory;

                    $patient->save();

                    Vital::query()->where('patient_id', $patient->id)->delete();

                    $cloudVitalHistory = array_reverse($cloudPatient->vitalHistory);


                    $vitalData = [];

                    foreach ($cloudVitalHistory as $cloudVital) {
                        $value = [];
                        $value['patient_id'] = $patient->id;

                        $value['lmp'] = $cloudVital->lmp;
                        $value['respiratory'] = $cloudVital->respiratory;
                        $value['glucose'] = $cloudVital->glucose;
                        $value['pulse'] = $cloudVital->pulse;
                        $value['cholesterol'] = $cloudVital->cholesterol;
                        $value['height'] = $cloudVital->height;
                        $value['weight'] = $cloudVital->weight;
                        $value['temperature'] = $cloudVital->temperature;
                        $value['blood_systolic'] = $cloudVital->blood_systolic ?? "";
                        $value['blood_diastolic'] = $cloudVital->blood_diastolic ?? "";
                        $value['updated_at'] = $cloudVital->updated_at;
                        $value['created_at'] = $cloudVital->created_at;
                        $vitalData[] = $value;
                    }

                    Vital::query()->insert($vitalData);





                    PatientDependents::query()->where('patient_id', $patient->id)->delete();

                    $data = [];

                    $dependents = $cloudPatient->dependents;

                    foreach ($dependents as $dependent) {
                        $value = [];
                        $value['patient_id'] = $patient->id;
                        $value['firstname'] = $dependent->firstname;
                        $value['lastname'] = $dependent->lastname;
                        $value['name_extension'] = $dependent->name_extension ?? "";
                        $value['relationship'] = $dependent->relationship ?? "";
                        $value['birthday'] = date('Y-m-d');
                        $value['citizenship'] = $dependent->citizenship ?? "Filipino";
                        $value['is_permanently_disabled'] = 0;
                        $data[] = $value;
                    }

                    PatientDependents::query()->insert($data);
                }
            }
        } catch (\Exception $e) {

            Log::alert('Unable to connect cloud', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
        }
    }
}
