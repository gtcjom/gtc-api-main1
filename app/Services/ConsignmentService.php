<?php

namespace App\Services;

use App\Models\ConsignmentOrder;
use App\Models\ConsignmentOrderDetail;
use App\Models\ConsignmentOrderLocation;
use Illuminate\Http\Request;

class ConsignmentService
{

    public function getConsignmentList(String $location_id = null)
    {
        return ConsignmentOrder::query()
            ->when(request()->user()->type == 'LMIS-CNOR', function ($query, $location_id) {
                return $query->where('status', '!=', 'pending');
            })
            ->when($location_id, function ($query, $location_id) {
                if ($location_id > 2 && request()->user()->type != 'LMIS-CNOR') {
                    return $query->where('to_location_id', '=', $location_id);
                }
            })
            ->when(request()->get('status'), function ($query) {
                return $query->where('status', request()->get('status'));
            })
            ->orderBy('id', 'desc')
            ->get()
            ->load('locations');
    }

    public function createOrder(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'cof_number' => ['nullable'],
            'consignor' => ['nullable'],
            'term' => ['nullable'],
            'hci_name' => ['nullable'],
            'hci_number' => ['nullable'],
            'to_location_type' => ['nullable'],
            'to_location_id' => ['nullable'],
            'from_location_type' => ['nullable'],
            'from_location_id' => ['nullable'],
        ]);

        return ConsignmentOrder::create($data);
    }
    public function createOrderLocation($data)
    {
        $location = new ConsignmentOrderLocation();
        $location->location = $data['location'];
        $location->consignment_order_id = $data['consignment_order_id'];
        $location->save();
        return $location;
    }

    public function createOrderDetail($data)
    {
        // $data = $request->validate([
        //     'batch_no' => ['nullable', 'string'],
        //     'expiry_date' => ['nullable', 'date'],
        //     'mfg_date' => ['nullable', 'date'],
        //     'item_id' => ['required', 'string'],
        //     'quantity' => ['required'],
        //     'price' => ['required'],
        //     'amount' => ['required'],
        // ]); 
        $detail = new ConsignmentOrderDetail();
        $detail->item_id = $data['item_id'];
        $detail->quantity = $data['quantity'];
        $detail->price = $data['price'];
        $detail->amount = $data['amount'];
        $detail->consignment_order_id = $data['consignment_order_id'];
        $detail->consignment_order_location_id = $data['consignment_order_location_id'];
        $detail->save();
        return $detail;
    }
}
