<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteService
{

    public function index(string $class)
    {
        return $class::with(['addedBy', 'doctor'])
            ->where('patient_id', request('patient_id'))
            ->orderBy('datetime')
            ->get();
    }
    public function store(mixed $model)
    {
        return $this->setInformation($model);
    }

    public function update(mixed $model)
    {
        return $this->setInformation($model);
    }

    public function destroy(string $class, int $id)
    {
        $model = $class::findOrFail($id);
        return $model->delete();
    }

    public function setInformation(mixed $model)
    {
        $model->patient_id = request()->get('patient_id');
        $model->title = request()->get('title') ?: "";
        $model->added_by_id = request()->user()->id;
        $model->description = request()->get('description');
        $model->status = request()->get('status');
        $model->doctor_id = request()->get('doctor_id');
        $model->datetime = request()->get('date') . " " . request()->get('time');
        $model->save();
        $model->load('addedBy');
        $model->load('doctor');

        return $model;
    }

    public function getNotes($id)
    {
        return Note::query()
            ->where('patient_id', $id)
            ->get();
    }
}
