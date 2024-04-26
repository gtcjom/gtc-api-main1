<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpecialtiesResource;
use App\Services\SpecialtiesService;
use Illuminate\Http\Request;

class SpecialtiesController extends Controller
{
    public function index(SpecialtiesService $specialtiesService)
    {
        return SpecialtiesResource::collection($specialtiesService->getSpecialties());
    }
    public function store(SpecialtiesService $specialtiesService)
    {
        return SpecialtiesResource::make($specialtiesService->store());
    }
    public function update(SpecialtiesService $specialtiesService, $id)
    {
        return SpecialtiesResource::make($specialtiesService->update($id));
    }
    public function deactivate(SpecialtiesService $specialtiesService, $id)
    {
        return SpecialtiesResource::make($specialtiesService->deactivate($id));
    }
    public function activate(SpecialtiesService $specialtiesService, $id)
    {
        return SpecialtiesResource::make($specialtiesService->activate($id));
    }
}
