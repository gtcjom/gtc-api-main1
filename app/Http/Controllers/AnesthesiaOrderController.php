<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnesthesiaOrderResource;
use App\Models\AnesthesiaOrder;
use App\Models\AnesthesiaTest;
use App\Services\AnesthesiaOrderService;
use App\Services\InventoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AnesthesiaOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AnesthesiaOrder $anesthesiaOrderService)
    {
        //
        return AnesthesiaOrderResource::collection($anesthesiaOrderService->list($request));
    }

    public function patientAnesthesiaOrders($id)
    {
        $anesthesia_test_type = request()->get('anesthesia_test_type') ? request()->get('anesthesia_test_type') : null;
        $type_ids = false;
        if ($anesthesia_test_type) {
            $type_ids = AnesthesiaTest::query()->where('type', $anesthesia_test_type == 1 ? 'imaging' : 'laboratory-test')->pluck('id');
        }
        if (request()->get('anesthesia_test_type') == 'all') {
            $type_ids = AnesthesiaTest::query()->pluck('id');
        }
        return AnesthesiaOrderResource::collection(AnesthesiaOrder::where('patient_id', $id)
            ->when(request('order_id') && request('order_id')  != 'undefined', function ($q) {
                return $q->where('id', request('order_id'));
            })
            ->when($type_ids, function ($query, $type_ids) {
                $query->whereIn('anesthesia_test_type', $type_ids);
            })
            ->when(request('appointment_id'), function ($q) {
                return $q->where('appointment_id', request('appointment_id'));
            })
            ->latest()
            ->get()
            ->load('patient', 'doctor', 'clinic', 'healthUnit'));
    }
    public function getAnesthesiaQueue()
    {
        $user = request()->user();
        $type = ($user->type == 'HIS-ANESTHESIA');
        $anesthesia_test_ids = AnesthesiaTest::query()->where('type', $type)->pluck('id');

        $pending_tests = AnesthesiaOrder::query()
            ->where('order_status', request('status', 'operating'))
            ->where('health_unit_id', $user->health_unit_id)
            ->whereIn('anesthesia_test_type', $anesthesia_test_ids)
            ->with(['patient', 'doctor', 'clinic', 'healthUnit'])
            ->latest()
            ->paginate(request('paginate', 10));
        return AnesthesiaOrderResource::collection($pending_tests);
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
    public function store(Request $request, InventoryService $inventoryService, AnesthesiaOrderService $anesthesiaOrderService)
    {
        //
        return response()->json([
            'data' => new AnesthesiaOrderService($anesthesiaOrderService->store($inventoryService, $request)),
            'message' => 'Anesthesia order created successfully.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnesthesiaOrder  $anesthesiaOrder
     * @return \Illuminate\Http\Response
     */
    public function show(AnesthesiaOrderService $anesthesiaOrderService, int $id)
    {
        //
        return response()->json([
            'data' => new AnesthesiaOrderResource($anesthesiaOrderService->show($id)),
            'message' => 'Anesthesia order retrived successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnesthesiaOrder  $anesthesiaOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(AnesthesiaOrder $anesthesiaOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AnesthesiaOrder  $anesthesiaOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnesthesiaOrderService $anesthesiaOrderService, int $id)
    {
        //
        return response()->json([
            'data' => new AnesthesiaOrderResource($anesthesiaOrderService->update($request, $id)),
            'message' => 'Anesthesia order updated successfully.'
        ], Response::HTTP_OK);
    }
    public function acceptAnesthesiaOrder(Request $request, AnesthesiaOrder $anesthesiaOrder)
    {
        // accept laboratory order
        $anesthesiaOrder->update(array_merge([
            'accepted_by' => $request->user()->id,
            'accepted_at' => Carbon::now()
        ]));

        return response()->json(
            [
                'data' => new AnesthesiaOrderResource($anesthesiaOrder),
                'message' => 'Anesthesia order is successfully accepted.'
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnesthesiaOrder  $anesthesiaOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $order =  AnesthesiaOrder::query()->findOrFail($id);
        $order->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }
}
