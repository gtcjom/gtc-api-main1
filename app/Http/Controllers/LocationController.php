<?php

namespace App\Http\Controllers;

use App\Http\Resources\MunicipalityResource;
use App\Models\Municipality;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $municipalities = Municipality::query()
            ->with(['barangays' => fn ($q) => $q->orderBy('name', 'asc')])
            ->orderBy('name', 'asc')
            ->get();
        return MunicipalityResource::collection($municipalities);
    }
}
