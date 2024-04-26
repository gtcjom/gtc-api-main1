<?php

namespace App\Http\Controllers\Clinic\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Prescription;
use App\Services\NoteService;

class PrescriptionsController extends Controller
{

    public function index(NoteService $noteService)
    {
        return NoteResource::collection($noteService->index(Prescription::class));
    }

    public function store(NoteService $noteService, NoteRequest $request)
    {
        $prescription = new Prescription();

        return NoteResource::make($noteService->store($prescription));
    }

    public function update(NoteService $noteService, NoteRequest $request, int $id)
    {
        $prescription = Prescription::query()->findOrFail($id);
        return NoteResource::make($noteService->update($prescription));
    }

    public function destroy(NoteService $noteService, int $id)
    {
        $noteService->destroy(Prescription::class,$id);
        return response()->noContent();

    }
}