<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppointmentDataResource;
use App\Http\Resources\OperationProcedureResource;
use App\Models\AppointmentData;
use App\Models\LaboratoryTest;
use App\Models\OperationProcedure;
use App\Services\OperationProcedureService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

class OperationProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, OperationProcedureService $operationProcedureService)
    {
        //
        return OperationProcedureResource::collection($operationProcedureService->list($request));
    }
    public function patientOperation($id)
    {
        $procedure = request()->get('procedure') ? request()->get('procedure') : null;
        $type_ids = false;
        // if ($procedure) {
        //     $type_ids = LaboratoryTest::query()->where('type', $procedure == 1 ? 'imaging' : 'laboratory-test')->pluck('id');
        // }
        // if (request()->get('operation_procedure') == 'all') {
        //     $type_ids = LaboratoryTest::query()->pluck('id');
        // }
        return OperationProcedureResource::collection(OperationProcedure::where('patient_id', $id)
            ->when(request('order_id') && request('order_id')  != 'undefined', function ($q) {
                return $q->where('id', request('order_id'));
            })
            ->when($type_ids, function ($query, $type_ids) {
                $query->whereIn('operation_procedure', $type_ids);
            })
            ->when(request('appointment_id'), function ($q) {
                return $q->where('appointment_id', request('appointment_id'));
            })
            ->latest()
            ->get()
            ->load('patient', 'doctor', 'clinic', 'healthUnit'));
    }

    public function getProcedureQueue()
    {
        $user = request()->user();
        // $type = $user->type == 'HIS-ANESTHESIA'

        $pending_operation = OperationProcedure::query()
            ->where('operation_status', request('status', 'operating_room'))
            ->where('health_unit_id', $user->health_unit_id)
            ->with(['patient', 'doctor', 'clinic', 'healthUnit'])
            ->latest()
            ->paginate(request('paginate', 10));
        return OperationProcedureResource::collection($pending_operation);
    }

    public function sendPatientToOR($appointment_id)
    {
        $appointment = AppointmentData::query()->findOrFail($appointment_id);

        $procedureOrder = OperationProcedure::query()
            ->where('operation_status', 'operating_room')
            ->where('patient_id', $appointment->patient_id)
            ->get();
        if ($procedureOrder->count()) {
            $appointment->status = 'resu';
            $appointment->save();
        } else if ($procedureOrder->count()) {
            $appointment->status = 'done';
            $appointment->save();
        }
        return [
            'operating_orders' => $procedureOrder,
            'appointment' => AppointmentDataResource::make($appointment)
        ];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, OperationProcedureService $operationProcedureService)
    {
        return response()->json([
            'data' => new OperationProcedureResource($operationProcedureService->store($request)),
            'message' => 'Operating Order successfully.'
        ], Response::HTTP_CREATED);
    }
    // public function store()
    // {
    //     //
    //     $procedural = new OperationProcedure();
    //     $procedural->patient_id = request()->get('patient_id');
    //     $procedural->operation_date = request()->get('operation_date');
    //     $procedural->operation_time = request()->get('operation_time');
    //     $procedural->procedure = request()->get('procedure');
    //     $procedural->doctor_id = request()->get('doctor_id');
    //     $procedural->operation_status = request()->get('operation_status');
    //     $procedural->save();
    //     return OperationProcedureResource::make($procedural);
    // }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OperationProcedure  $operationProcedure
     * @return \Illuminate\Http\Response
     */
    public function show(OperationProcedureService $operationProcedureService, int $id)
    {
        return response()->json([
            'data' => new OperationProcedureResource($operationProcedureService->show($id)),
            'message' => 'Operation order retrived successfully.'
        ], Response::HTTP_OK);
    }

    // public function acceptOperatingProcedure(Request $request, OperationProcedure $operationProcedure)
    // {
    //     $operationProcedure->update(array_merge([
    //         'accepted_by' => $request->user()->id,
    //         'accept_at' => Carbon::now()
    //     ]));

    //     return response()->json([
    //         'data' => new OperationProcedureResource($operationProcedure),
    //         'message' => 'Operation order is successfully accepted.'
    //     ], Response::HTTP_OK);
    // }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OperationProcedure  $operationProcedure
     * @return \Illuminate\Http\Response
     */
    public function edit(OperationProcedure $operationProcedure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OperationProcedure  $operationProcedure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OperationProcedureService $operationProcedureService, int $id)
    {
        return response()->json(
            [
                'data' => new OperationProcedureResource($operationProcedureService->update($request, $id)),
                'message' => 'Operation Procedure updated successfully.'
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OperationProcedure  $operationProcedure
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $procedural = OperationProcedure::query()->findOrFail($id);
        $procedural->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }
}
