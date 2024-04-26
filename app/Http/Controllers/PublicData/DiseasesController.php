<?php

namespace App\Http\Controllers\PublicData;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiseaseResource;
use App\Models\Disease;
use App\Services\DiseaseService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiseasesController extends Controller
{

    public function index(DiseaseService $service)
    {
        return DiseaseResource::collection($service->getDataList());
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'type' => ['required', Rule::in(['communicable', 'non-communicable'])],
            'name' => ['required', 'string', 'max:200'],
            'color' => ['required', 'string', 'max:10'],
            'radius' => ['required', 'integer', 'min:1']
        ]);

        $disease = Disease::query()->findOrFail($id);
        $disease->type = $request->type;
        $disease->name = $request->name;
        $disease->color = $request->color;
        $disease->radius = $request->radius;
        $disease->save();


        return $disease;
    }
}
