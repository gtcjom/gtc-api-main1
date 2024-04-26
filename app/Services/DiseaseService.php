<?php

namespace App\Services;

use App\Models\Disease;
use App\Models\DiseaseHistory;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DiseaseService
{

    public function getDataList()
    {
        return Disease::query()
            ->when(request('type'), fn (Builder $q) => $q->where('type', request('type')))
            ->when(request('keyword'), fn (Builder $builder) => $builder->where('name', 'like', "%" . request()->get('keyword') . "%"))
            ->paginate(request('paginate', 12));
    }

    public function make(string $patient_id, array $data): Model|Builder
    {
        $patient = Patient::query()
            ->with('interview')
            ->where('patient_id', $patient_id)->firstOrFail();

        $history = DiseaseHistory::query()->firstOrNew([
            'patient_id' => $patient_id,
            'disease' => $data['disease'],
            'date_cured' => null
        ]);
        $history->municipality  = $patient->municipality;
        $history->barangay  = $patient->barangay;
        $history->latitude  = $patient->interview?->lat ?: null;
        $history->longitude  = $patient->interview?->lng ?: null;
        $history->date_started  = $data['date_started'] ?? date('Y-m-d');
        $history->save();

        return $history;
    }

    public function createHistory(string $patient_id, array $data): Model|Builder
    {
        $patient = Patient::query()
            // ->with('interview')
            ->where('id', $patient_id)->firstOrFail();

        $history = DiseaseHistory::query()->firstOrNew([
            'patient_id' => $patient_id,
            'disease' => $data['disease'],
            'date_cured' => null
        ]);
        $history->municipality  = $patient->municipality;
        $history->barangay  = $patient->barangay;
        $history->latitude  = $patient->lat ?: null;
        $history->longitude  = $patient->lng ?: null;
        $history->date_started  = $data['date_started'] ?? date('Y-m-d');
        $history->save();

        return $history;
    }


    public function getMap(): Collection|array
    {
        $municipality = request()->get('municipality') ?: "all";
        $barangay = request()->get('barangay') ?: "all";
        $disease = request()->get('disease') ?: "all";
        return DiseaseHistory::query()
            ->with(['patient', 'diseaseData', 'municipalityData', 'barangayData'])
            ->when($disease != 'all', fn (Builder $builder) => $builder->where('disease', $disease))
            ->when($barangay != 'all', fn (Builder $builder) => $builder->where('barangay', $barangay))
            ->when($municipality != 'all', fn (Builder $builder) => $builder->where('municipality', $municipality))
            ->when(request()->get('date'), function (Builder $builder) {
                return $builder->whereNull('date_cured')
                    ->where('date_started', ">=",  request()->get('date'));
            }, function (Builder $builder) {
                return $builder->whereNull('date_cured');
            })
            ->get();
    }
}
