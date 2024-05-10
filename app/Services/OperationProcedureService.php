<?php

namespace App\Services;

use App\Models\OperationProcedure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OperationProcedureService
{
    public function show(int $id)
    {
        $operationProcedure = OperationProcedure::query()
            ->with(['patient', 'doctor', 'clinic', 'healthUnit'])
            ->findOrFail($id);

        return $operationProcedure;
    }

    public function list(Request $request)
    {
        $procedure = $request->get('procedure') ? $request->get('procedure') : null;

        return OperationProcedure::query()
            ->with(['patient', 'doctor', 'clinic', 'healthUnit'])
            ->when($procedure, function ($query, $procedure) {
                $query->where('procedure', $procedure);
            })
            ->paginate(request('paginate', 12));
    }

    public function store(Request $request)
    {
        $request->validate([
            'operation_number' => ['nullable'],
            'patient_id' => ['required', 'string', Rule::exists('patients', 'id')],
            'operation_date' => ['required', 'date'],
            'operation_time' => ['required', 'date_format:H:i'],
            'procedure' => ['required', 'string'],
            'doctor_id' => ['nullable'],
            'operation_status' => ['required', 'string'],
            'health_unit_id' => ['nullable'],
            'appointment_id' => ['nullable'],
        ]);
        $user = $request->user();

        $operationProcedure = OperationProcedure::create(array_merge([
            'operation_number' => 'ORP-' . substr(strtoupper(bin2hex(random_bytes(6))), 0, -1),
            'patient_id' => $request->patient_id,
            'operation_date' => $request->operation_date,
            'operation_time' => $request->operation_time,
            'procedure' => $request->procedure,
            'doctor_id' => $user->doctor_id,
            'health_unit_id' => $user->health_unit_id,
            'appointment_id' => $request->appointment_id,

            'operation_status' => 'operating_room'
        ]));

        $operationProcedure->load(['patient', 'doctor', 'clinic', 'healthUnit']);

        return $operationProcedure;
    }
    public function update(Request $request, int $id): Model|OperationProcedure|Collection|Builder|array|null
    {
        $data = $request->validate([
            'operation_date' => ['required', 'date'],
            'procedure' => ['required', 'string'],
        ]);

        $operationProcedure = OperationProcedure::findOrFail($id);
        $operationProcedure->update($data);
        $operationProcedure->load(['patient', 'doctor', 'clinic']);

        return $operationProcedure;
    }
}
