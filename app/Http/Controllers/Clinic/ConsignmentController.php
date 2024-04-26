<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConsignmentOrderResource;
use App\Models\ConsignmentOrder;
use App\Models\ConsignmentOrderDetail;
use App\Models\ConsignmentOrderLocation;
use App\Models\HealthUnit;
use App\Models\ItemInventory;
use App\Services\ConsignmentService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ConsignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ConsignmentService $consignmentService, Request $request)
    {
        $location_id = $request->health_unit_id ? $request->health_unit_id : $this->getUserLocation()->id;
        return ConsignmentOrderResource::collection($consignmentService->getConsignmentList($location_id, $request->get('status')));
    }

    public function getUserLocation()
    {
        $user = request()->user();
        $location = HealthUnit::first();
        if (str_contains($user->type, 'RHU')) {
            $location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
        }
        if ($user->type == 'LMIS-BHS' || $user->type == 'BHS-BHW') {
            $location = HealthUnit::query()->where('type', '=', 'BHS')->where('barangay_id', '=', $user->barangay)->first();
        }
        if ($user->type == 'LMIS-CNOR') {
            $location = HealthUnit::query()->where('type', 'CNOR')->first();
        }
        return $location;
    }


    public function store(ConsignmentService $consignmentService, Request $request)
    {
        // return response()->json(['data' => $request->get('location')]);

        $locations = $request->input('location');

        try {

            DB::beginTransaction();

            $order = new ConsignmentOrder();
            $order->date = $request->date;
            $order->cof_number = $request->cof_number;
            $order->consignor = $request->consignor;
            $order->status = 'pending';
            $order->term = $request->term;
            // $order->address = $request->address;
            $order->scheduled_by = request()->user()->id;
            $order->hci_name = $request->hci_name;
            $order->hci_number = $request->hci_number;
            $order->to_location_type = $request->to_location_type;
            $order->to_location_id = $locations[0]['location_id'];
            $order->from_location_type = $request->from_location_type;
            $order->from_location_id = $this->getUserLocation()->id;
            $order->save();

            foreach ($locations as $location) {
                $type = $location['type'];
                $loc_id = $location['location_id'];
                $locationData = new ConsignmentOrderLocation();
                $locationData->location = $type;
                $locationData->location_id = $loc_id;
                $locationData->consignment_order_id =  $order->id;
                $locationData->save();

                foreach ($location['items'] as $item) {
                    $detail = new ConsignmentOrderDetail();
                    $detail->item_id = $item['item_id'];
                    $detail->quantity = $item['qty'];
                    $detail->price = $item['price'];
                    $detail->amount = $item['qty'] * $item['price'];
                    $detail->consignment_order_id = $order->id;
                    $detail->consignment_order_location_id = $locationData->id;
                    $detail->save();
                }
            }

            DB::commit();

            return response()->json(['message' => "Success"], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "failed", 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show($id)
    {
        return ConsignmentOrderResource::make(ConsignmentOrder::find($id));
    }


    public function approve(Request $request, $id)
    {

        try {

            DB::beginTransaction();

            $order = ConsignmentOrder::query()->with('locations')->find($id);
            $order->status = 'approved';
            $order->approved_by = request()->user()->id;
            $order->save();


            foreach ($order->locations as $location) {
                $loc = ConsignmentOrderLocation::find($location->id);
                $loc->approved_by = request()->user()->id;
                $loc->save();
            }

            DB::commit();
            return response()->json(['message' => "Success"], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed", 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkOrder(Request $request, $id)
    {

        try {

            DB::beginTransaction();

            $order = ConsignmentOrder::find($id);
            $order->status = 'checked';
            $order->checked_by = request()->user()->id;
            $order->save();

            foreach ($order->locations as $location) {
                $loc = ConsignmentOrderLocation::query()->with('items')->find($location->id);
                $loc->checked_by = request()->user()->id;
                $loc->save();
            }

            DB::commit();
            return response()->json(['message' => "Success"], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed", 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function processOrder(InventoryService $inventoryService, Request $request, $id)
    {

        try {

            DB::beginTransaction();

            $order = ConsignmentOrder::find($id);
            $order->status = 'processed';
            $order->processed_by = request()->user()->id;
            $order->save();

            foreach ($order->locations as $location) {
                $loc = ConsignmentOrderLocation::find($location->id);
                $loc->processed_by = request()->user()->id;
                $loc->save();


                foreach ($loc->items as $item) {
                    //Health UnIT location id == 2 // FOR CNOR
                    $inventoryService->stockOut(2, $item->item_id, $item->quantity);
                }
            }

            DB::commit();
            return response()->json(['message' => "Success"], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed", 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function deliverOrder($id)
    {

        try {

            DB::beginTransaction();

            $order = ConsignmentOrder::find($id);
            $order->status = 'delivered';
            $order->delivered_by = request()->user()->id;
            $order->save();

            foreach ($order->locations as $location) {
                $loc = ConsignmentOrderLocation::find($location->id);
                $loc->delivered_by = request()->user()->id;
                $loc->save();
            }

            DB::commit();
            return response()->json(['message' => "Success"], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed", 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function receiveOrder(InventoryService $inventoryService, $id)
    {

        try {

            DB::beginTransaction();

            $order = ConsignmentOrder::find($id);
            $order->status = 'received';
            $order->received_by = request()->user()->id;
            $order->save();

            foreach ($order->locations as $location) {
                $loc = ConsignmentOrderLocation::find($location->id);
                $loc->received_by = request()->user()->id;
                $loc->save();

                foreach ($loc->items as $item) {
                    $inventoryService->stockIn($item->item_id, $loc->location_id, $item->quantity, $item->price);
                }
            }

            DB::commit();
            return response()->json(['message' => "Success"], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed", 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
