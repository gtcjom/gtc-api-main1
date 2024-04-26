<?php

namespace App\Services;

use App\Http\Resources\PatientQueueResource;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicPersonnel;
use App\Models\Doctor;
use App\Models\LaboratoryList;
use App\Models\Patient;
use App\Models\PatientQueue;
use App\Models\Scopes\UserScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\ClinicCapabilitiesEnum;
use App\Models\HealthUnit;

class ClinicService
{

    //create a function that accept amount and store in array and order by amount descending and take only the top 20



    public function dashboard()
    {
        $clinic = $this->getClinic();
        $stats = PatientQueue::query()
            ->toBase()
            ->selectRaw(
                "COUNT(CASE WHEN type_service = 'Consultation' AND status = 'pending' THEN 1 END) AS consultation, " .
                    "COUNT(CASE WHEN type_service = 'B' AND status = 'pending' THEN 1 END) AS other_service, " .
                    "COUNT(CASE WHEN status = 'completed' THEN 1 END) AS completed "
            )
            ->whereDate('date_queue', date('Y-m-d'))
            // ->where('clinic_id', $clinic->id)
            ->first();

        $patients = PatientQueue::query()
            ->with(['patient'])
            ->whereDate('date_queue', date('Y-m-d'))
            // ->where('clinic_id', $clinic->id)
            ->latest()
            ->get();
        return [
            'stats' => $stats,
            'clinic' => $clinic,
            'patients' => PatientQueueResource::collection($patients)
        ];
    }
    public function index()
    {
        request()->validate([
            'column' => ['nullable', Rule::in(['name'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])]
        ]);


        return Clinic::query()
            // ->with(['purok', 'barangay', 'municipality'])
            ->when(request('keyword'), fn ($query, $keyword) => $query->where('name', 'like', "%{$keyword}%"))
            ->when(request('column') == 'name', fn ($query, $keyword) => $query->where('name', 'like', "%{$keyword}%"))
            ->when(
                request('column') == 'name',
                fn ($q) => $q->orderBy('name', request('direction'))
            )
            ->latest()
            ->paginate(request('paginate', 12));
    }

    public function getClinicOftype($type)
    {
        request()->validate([
            'column' => ['nullable', Rule::in(['name'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])]
        ]);


        return Clinic::query()
            // ->with(['purok', 'barangay', 'municipality'])
            ->where('type', $type)
            ->when(request('keyword'), fn ($query, $keyword) => $query->where('name', 'like', "%{$keyword}%"))
            ->when(request('column') == 'name', fn ($query, $keyword) => $query->where('name', 'like', "%{$keyword}%"))
            ->when(
                request('column') == 'name',
                fn ($q) => $q->orderBy('name', request('direction'))
            )
            ->latest()
            ->get();
    }

    public function create(Request $request): Clinic
    {

        $managementID = "m-" . time();
        $laboratory_id = "l-" . time();
        $laboratory = new LaboratoryList();
        $laboratory->management_id = $managementID;
        $laboratory->laboratory_id = $laboratory_id;
        $laboratory->l_id = $laboratory_id;
        $laboratory->save();

        $clinic = new Clinic();
        $clinic->laboratory_id = $laboratory_id;
        $clinic->management_id = $managementID;
        $this->setInformation($request, $clinic);
        $clinic->save();
        // $clinic->load(['purok', 'barangay', 'municipality']);

        return $clinic;
    }



    private function setInformation(Request $request, Model|Clinic $clinic)
    {
        $clinic->clinic = $request->get('name');
        $clinic->street = $request->get('street');
        $clinic->region = $request->get('region');
        $clinic->province = $request->get('province');
        $clinic->municipality = $request->get('municipality');
        $clinic->barangay = $request->get('barangay');
        $clinic->purok = $request->get('purok');

        $clinic->purok_id = $request->get('purok_id');
        $clinic->barangay_id = $request->get('barangay_id');
        $clinic->municipality_id = $request->get('municipality_id');

        $clinic->latitude = $request->get('lat');
        $clinic->longitude = $request->get('lng');
        $clinic->status = 1;
        $clinic->contact_no = $request->get('contact_number');
        $clinic->type = $request->get('type');
        $clinic->tuberculosis = $request->get('tuberculosis');
        $clinic->animal_bites = $request->get('animal_bites');
        $clinic->hypertension = $request->get('hypertension');

        if ($request->has('image')) {
            $clinic->clinic_image  = $request->file('image')->store('clinic');
        }
    }

    public function show($id)
    {
        return Clinic::query()
            ->with(['personnels'])
            ->findOrFail($id);
    }

    public function update(Request $request, int $id): Model|Clinic|Collection|Builder|array|null
    {
        $clinic = Clinic::query()->findOrFail($id);
        $this->setInformation($request, $clinic);
        $clinic->save();
        // $clinic->load(['purok', 'barangay', 'municipality']);
        return $clinic;
    }

    public function delete(int $id): Model|Collection|Builder|array|null
    {
        $clinic = Clinic::query()->findOrFail($id);
        $clinic->delete();

        return $clinic;
    }

    public function updatePersonnel(int $id)
    {
        $clinic  = Clinic::query()->findOrFail($id);
        $insert = [];
        $ids = request()->get('user_id') ?: [];

        ClinicPersonnel::query()
            ->where('clinic_id', $id)->delete();
        ClinicPersonnel::query()
            ->whereIn('user_id', $ids)->delete();
        $users = User::query()
            ->whereIn('type', userClinicTypes())
            ->whereIn('id', $ids)
            ->get();

        User::query()
            ->whereIn('type', userClinicTypes())
            ->whereIn('id', $ids)->update([
                'manage_by' => $clinic->management_id
            ]);
        Doctor::query()->whereIn('user_id', $users->pluck('user_id'))->update([
            'management_id' => $clinic->management_id
        ]);

        foreach ($users->pluck('id') as $user_id) {
            $insert[] = [
                'clinic_id' => $clinic->id,
                'user_id' => $user_id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ];
        }
        ClinicPersonnel::query()->insert($insert);
        $clinic->with(['personnels']);

        return $clinic;
    }

    public function getPersonnels(int $id)
    {
        $clinic  = Clinic::query()->findOrFail($id);

        return $clinic->personnels()->latest()->get();
    }

    public function unassignedWithClinic(int $id)
    {
    }

    public function receivingPatient(Request $request, int $priority = 0)
    {

        $date = date('Y-m-d');
        $current = PatientQueue::query()->where([
            'date_queue' => date('Y-m-d'),
            'clinic_id' => $request->get('clinic_id')
        ])->orderBy('number', 'desc')->first();

        if (is_null($current)) {
            $number = 1;
        } else {
            $number = $current->number + 1;
        }

        $patientQueue = new PatientQueue;
        $patientQueue->purpose = $request->get('purpose');
        $patientQueue->send_to = 'doctor';
        $patientQueue->clinic_id = $request->get('clinic_id');
        $patientQueue->patient_id = $request->get('patient_id');
        $patientQueue->to_intended_id = $request->get('to_intended_id');
        $patientQueue->doctor_id = $request->get('doctor_id');
        $patientQueue->room_number = $request->get('room_number');
        $patientQueue->type_service = $request->get('type_service');
        $patientQueue->status = 'pending';
        $patientQueue->date_queue = $date;
        $patientQueue->number = $number;
        $patientQueue->priority = $priority;
        if ($request->has('appointment_id')) {
            $appointment = Appointment::query()->where('status', 'pending')->findOrFail($request->get('appointment_id'));
            $appointment->status = 'done';
            $appointment->save();
            $patientQueue->appointment_id = $appointment->id;
        }
        $patientQueue->save();

        return $patientQueue;
    }


    public function clearQueues(int $clinicId)
    {
        PatientQueue::query()->where('clinic_id', $clinicId)->update([

            'status' => 'done'
        ]);
    }

    public function getClinic()
    {
        $clinic_personnel_data = ClinicPersonnel::query()->where('user_id', request()->user()->id)->first();
        return Clinic::query()
            ->with(['doctors'])
            ->where('id', $clinic_personnel_data->clinic_id)
            ->first();
    }

    public function getDoctors()
    {
        return Doctor::query()
            ->where('management_id', request()->user()->manage_by)
            ->orderBy('name', 'asc')
            ->get();
    }
    public function getDoctorByHealthUnit()
    {
        $healthUnit = HealthUnit::query()->findOrFail(request('health_unit_id'));
        return User::query()
            ->where('health_unit_id', request('health_unit_id'))
            ->where('type', $healthUnit->type . '-DOCTOR')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function receivingPatientList()
    {

        //    $clinic = Clinic::query()->findOrFail( request()->get('clinic_id'));

        return Patient::query()
            ->withoutGlobalScope(UserScope::class)
            ->whereDoesntHave('queueData', function ($query) {
                return $query->whereIn('status', ['pending', 'served']);
            })
            /*->when($clinic->type == 'municipality', fn(Builder $q) => $q->where('municipality_id', $clinic->municipality_id))
            ->when($clinic->type == 'barangay', fn(Builder $q) =>
                $q->where('municipality_id', $clinic->municipality_id)->where('barangay_id', $clinic->barangay_id)
            )
            ->when($clinic->type == 'purok', fn(Builder $q) =>
            $q->where('municipality_id', $clinic->municipality_id)
                ->where('barangay_id', $clinic->barangay_id)
                ->where('purok_id', $clinic->purok_id)
            )*/
            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->when(
                request('keyword'),
                function (Builder $q) {
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ',firstname,middle,lastname) like '%{$keyword}%' ");
                }
            )
            ->orderBy('id', 'desc')
            ->paginate(request('paginate', 12));
    }

    public function getPatient($id)
    {
        return Patient::query()->findOrFail($id);
    }

    public function patients()
    {
        return Patient::query()
            ->withoutGlobalScope(UserScope::class)
            // ->whereHas('queueData', fn ($q) => $q->where('clinic_id', request()->get('clinic_id')))
            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->when(
                request('keyword'),
                function (Builder $q) {
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ',firstname,middle,lastname) like '%{$keyword}%' ");
                }
            )
            ->orderBy('id', 'desc')
            ->paginate(request('paginate', 12));
    }


    public function queuePatients(int $id)
    {
        return PatientQueue::query()
            ->with([
                'doctor',
                'patient'
            ])
            ->where('clinic_id', $id)
            ->whereIn('status', ['pending', 'served'])
            ->orderBy('priority', 'desc')
            ->orderBy('number', 'asc')
            ->get();
    }


    public function queueAcceptance(int $id, int $clinic_id)
    {
        $patientQueue = PatientQueue::query()
            ->with(['patient'])
            ->where('status', 'pending')
            ->where('clinic_id', $clinic_id)
            ->findOrFail($id);

        $patientQueue->status = 'served';
        $patientQueue->save();

        return $patientQueue;
    }

    public function queueDone(int $id, int $clinic_id)
    {
        $patientQueue = PatientQueue::query()
            ->with(['patient' => [
                'barangayData',
                'purokData',
                'municipalityData',
            ]])
            ->where('status', 'served')
            // ->where('clinic_id',$clinic_id)
            ->findOrFail($id);

        $patientQueue->status = 'done';
        $patientQueue->save();

        return $patientQueue;
    }

    public function capableClinics(Request $request)
    {
        $type = $request->get('type');
        $capability = $request->get('capability');

        $capabilityColumnMap = [
            'tuberculosis' => 'tuberculosis',
            'animal_bites' => 'animal_bites',
            'hypertension' => 'hypertension',
        ];

        $clinics = Clinic::query()
            // ->with(['purok', 'barangay', 'municipality'])
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($capability, function ($query, $capability) use ($capabilityColumnMap) {
                $capabilityColumn = $capabilityColumnMap[$capability] ?? null;
                if ($capabilityColumn !== null) {
                    $query->where($capabilityColumn, 1);
                }
            })
            ->orderBy('clinic')
            ->get();

        return $clinics;
    }
}
