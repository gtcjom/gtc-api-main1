<?php

namespace App\Http\Controllers\LMIS;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class RequestOrderController extends  Controller
{

    public function store(Request $request)
    {
        $orderRequest = new OrderRequest();
        $orderRequest->order_by = $request->user()->id;
        $orderRequest->clinic_id = $request->get('clinic_id');
        $orderRequest->to_clinic_id = $request->get('to_clinic_id');
        $orderRequest->save();

        $details  = [];

        foreach ($request->get('items') as $key => $item) {
            $details[] = [
                'order_id' => $orderRequest->id,
                'item_id' => $item,
                'quantity' => $request->get('quantity')[$key],
//                'unit_price' => $item['unit_price'],
//                'total_price' => $item['total_price'],
            ];
        }

        OrderDetails::query()->insert($details);



    }
}
