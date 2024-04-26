<?php

namespace App\Services;

use App\Models\Specialties;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;

class SpecialtiesService
{

    public function getSpecialties()
    {
        return Specialties::query()
            ->when(
                request()->keyword,
                function (Builder $q) {
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ',name,status) like '%{$keyword}%' ");
                }
            )
            ->paginate(request()->get('paginate', 10));
    }

    public function store()
    {
        $specialty = new Specialties();
        $specialty->name = request()->get('name');
        $specialty->status = request()->get('status');
        $specialty->save();
        return $specialty;
    }

    public function update($id)
    {
        $specialty = Specialties::query()->findOrFail($id);
        $specialty->name = request()->get('name');
        $specialty->status = request()->get('status');
        $specialty->save();
        return $specialty;
    }
    public function deactivate($id)
    {
        $specialty = Specialties::query()->findOrFail($id);
        $specialty->status = 'inactive';
        $specialty->save();
        return $specialty;
    }

    public function activate($id)
    {
        $specialty = Specialties::query()->findOrFail($id);
        $specialty->status = 'active';
        $specialty->save();
        return $specialty;
    }
}
