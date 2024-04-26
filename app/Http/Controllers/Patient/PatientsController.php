<?php

namespace App\Http\Controllers\Patient;

use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Services\Cloud\SendToCloudService;
use App\Services\PatientService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PatientsController
{

    public function index()
    {

        request()->validate([
            'column' => ['nullable', Rule::in(['firstname', 'lastname', 'middle'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])]
        ]);


        $patients = Patient::query()
            ->with([
                'patientDependents',
                'barangayData',
                'purokData',
                'municipalityData',
                'household',
                'information',
                'rawAnswer',
                'diseases',
            ])
            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->when(
                request('pmrf_status'),
                fn ($q) => $q->where('pmrf_status', request('pmrf_status'))
            )

            ->when(
                request('keyword'),
                function (Builder $q) {
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ', firstname, middle,lastname) like '%{$keyword}%' ");
                }
            )
            ->when(request()->boolean('no_household'), fn ($q) => $q->where(function ($q) {
                return $q->whereNull('household_id')->orWhere('household_id', request()->get('household_id'));
            }))
            ->orderBy('id', 'desc')
            ->paginate(request('paginate', 12));

        return PatientResource::collection($patients);
    }


    public function imageToBase64(Patient $patient)
    {

        if ($patient->avatar) {
            return response()->json([
                'image' => base64_encode(Storage::get($patient->avatar))
            ]);
        }

        return response()->json([
            'image' => ""
        ]);
    }

    public function store(PatientRequest $request, PatientService $patientService, SendToCloudService $cloudService)
    {
        $patient = $patientService->create($request);
        $cloudService->createPatient($patient->id);
        return PatientResource::make($patient);
    }

    public function updateFromApp(PatientRequest $request, PatientService $patientService, SendToCloudService $cloudService, int $id)
    {
        $patient = $patientService->update($request, $id);
        $cloudService->updatePatient($patient->id);

        return PatientResource::make($patient);
    }
    public function update(PatientRequest $request, PatientService $patientService, SendToCloudService $cloudService, int $id)
    {
        $patient = $patientService->update($request, $id, true);
        $cloudService->updatePatient($patient->id);

        return PatientResource::make($patient);
    }

    public function show(int $id)
    {
        $patient = Patient::query()
            ->with([
                'information',
                'barangayData',
                'diseases',
                'purokData',
                'municipalityData',
                'rawAnswer',
                'household' => [
                    'municipality', 'barangayData', 'members', 'purokData',
                    'sanitation', 'housing', 'waste', 'calamity', 'houseCharacteristics',
                    'income', 'rawAnswer', 'rawAnswer', 'houseHoldCharacteristics'
                ],
                'patientDependents',
                'latestSocialHistory',
                'latestEnvironmentalHistory',
            ])
            ->findOrFail($id);

        return PatientResource::make($patient);
    }
    public function destroy($id)
    {
        $patient =  Patient::query()->findOrFail($id);
        $patient->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }

    public function mapping(PatientService $patientService)
    {
        return $patientService->mappings();
    }
}
