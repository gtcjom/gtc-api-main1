<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\PatientPMRFInformation;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Str;

class PHOPMRFQueueController extends Controller
{
    public function getQueue()
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
                request('pmrf_status'),
                fn ($q) => $q->where('pmrf_status', request('pmrf_status'))
            )
            ->when(
                request('pmrf_status_detail'),
                fn ($q) => $q->where('pmrf_status_detail', request('pmrf_status_detail'))
            )
            ->when(
                request('PIN'),
                fn ($q) => $q->where('philhealth', request('PIN'))
            )

            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->when(
                request('barangay'),
                function (Builder $q) {
                    $brgy = request('barangay');
                    return $q->whereRaw("CONCAT_WS(' ', barangay) like '%{$brgy}%' ");
                }
            )
            ->when(
                request('municipality'),
                function (Builder $q) {
                    $city = request('municipality');
                    return $q->whereRaw("CONCAT_WS(' ', municipality) like '%{$city}%' ");
                }
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


    public function getPatientPMRF($id)
    {
        $patient = Patient::query()
            ->with([
                'patientDependents',
                'barangayData',
                'purokData',
                'municipalityData',
                'household',
                'information',
                'rawAnswer',
                'diseases',
                'pmrf',
            ])
            ->findOrFail($id);

        return PatientResource::make($patient);
    }

    public function uploadSignature($id)
    {
        $patient = Patient::query()->findOrFail($id);

        $pmrf = PatientPMRFInformation::query()->where('patient_id', $patient->id)->first();
        if (request()->hasFile('signature')) {
            $pmrf->signature = request()->file('signature')->store('patient/signature');
        }
        if (request()->hasFile('thumbprint')) {
            $pmrf->thumbprint = request()->file('thumbprint')->store('patient/thumbprint');
        }
        $pmrf->save();

        $patient->pmrf_status = 'waiting-for-location';
        $patient->pmrf_status_detail = 'waiting for location update';
        $patient->save();

        return PatientResource::make($patient);
    }

    public function savePatientPMRF($id)
    {
        /*
            personal_details
            address_contact_details
            declaration_dependents
            member_type
            updating_amendment
            for_philhealth
        */

        // 'hypertension' => $request->boolean('hypertension'),
        // 'cancer' => $request->cancer_details,

        $patientPMRF = PatientPMRFInformation::find($id);
        if (!$patientPMRF) {
            $patientPMRF = new PatientPMRFInformation();
        }

        $personalDetail = [
            'firstname' => request('firstname'),
            'lastname' => request('lastname'),
            'middlename' => request('middlename'),
            'prefix' => request('prefix'),

            'mother_firstname' => request('mother_firstname'),
            'mother_lastname' => request('mother_lastname'),
            'mother_middlename' => request('mother_middlename'),
            'mother_suffix' => request('mother_suffix'),

            'spouse_firstname' => request('spouse_firstname'),
            'spouse_lastname' => request('spouse_lastname'),
            'spouse_middlename' => request('spouse_middlename'),
            'spouse_suffix' => request('spouse_suffix'),

            'birthdate' => request('birthdate'),
            'birthplace' => request('birthplace'),

            'philhealth' => request('philhealth'),
            'philsys' => request('philsys'),
            'tin' => request('tin'),

            'gender' => request('gender'),
            'civil_status' => request('civil_status'),
            'citizenship' => request('citizenship')

        ];
        $personalDetailData = [];
        foreach ($personalDetail as $key => $value) {
            if ($value) {
                $personalDetailData[$key] = $value; //Str::ucfirst(str_replace('_', ' ', $key));
            } else {
                $personalDetailData[$key] = ' ';
            }
        }

        $addressDetail = [
            'telephone' => request('telephone'),
            'mobile' => request('mobile'),
            'email' => request('email'),
            'unit' => request('unit'),
            'building' => request('building'),
            'house_number' => request('house_number'),
            'street' => request('street'),
            'subdivision' => request('subdivision'),
            'barangay' => request('barangay'),
            'municipality' => request('municipality'),
            'city' => request('city'),
            'province' => request('province'),
            'zip_code' => request('zip_code'),
            'mailing_unit' => request('mailing_unit'),
            'mailing_building' => request('mailing_building'),
            'mailing_house_number' => request('mailing_house_number'),
            'mailing_street' => request('mailing_street'),
            'mailing_subdivision' => request('mailing_subdivision'),
            'mailing_barangay' => request('mailing_barangay'),
            'mailing_municipality' => request('mailing_municipality'),
            'mailing_city' => request('mailing_city'),
            'mailing_province' => request('mailing_province'),
            'mailing_zip_code' => request('mailing_zip_code'),
        ];
        $addressDetailData = [];
        foreach ($addressDetail as $key => $value) {
            if ($value) {
                $addressDetailData[$key] = $value; //Str::ucfirst(str_replace('_', ' ', $key));
            } else {
                $addressDetailData[$key] = ' ';
            }
        }

        // $addressDetails = [

        // ];
        // $personalDetailData = [];
        // foreach ($personalDetail as $key => $value) {
        //     if ($value) {
        //         $personalDetailData[$key] = Str::ucfirst(str_replace('_', ' ', $key));
        //     }
        // }

        $patientPMRF->personal_details = json_encode($personalDetailData);
        $patientPMRF->address_contact_details = json_encode($addressDetailData);
        // $patientPMRF->declaration_dependents = 
        // $patientPMRF->member_type = 
        $patientPMRF->patient_id =  $id;
        $patientPMRF->save();

        $patient = Patient::findOrFail($id);
        $patient->philhealth = request('philhealth');
        if (request('pmrf_status')) {
            $patient->pmrf_status = request('pmrf_status');
        } else {
            $patient->pmrf_status = 'waiting-for-signature';
        }
        if (request('pmrf_status_detail')) {
            $patient->pmrf_status_detail = request('pmrf_status_detail');
        } else {
            $patient->pmrf_status_detail = 'waiting for signature upload';
        }

        if (request('pmrf_status') == 'done') {
            $patient->pmrf_done_at = Carbon::now()->toDateTimeString();
        }
        $patient->save();
    }
}
