<?php

namespace App\Http\Controllers\PMRF;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Tracker;
use App\Services\Cloud\PmrfPatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    public function index(PmrfPatientService $service, Request $request)
    {
        $where = $request->all();

        $where['pmrf_status']  = 'done';



        $patients =  Patient::query()->orderBy('pmrf_done_at', 'desc')->first();

        if(!is_null($patients)){
            $where['pmrf_done_at'] = $patients->pmrf_done_at;
        }

        return $service->getCloud($where);
    }

    public function sync(PmrfPatientService $service, Request $request)
    {
        $where = $request->all();

        $where['pmrf_status']  = 'done';

        unset($where['_method']);



        $patients =  Patient::query()->orderBy('pmrf_done_at', 'desc')->first();

        if(!is_null($patients)){
            $where['pmrf_done_at'] = $patients->pmrf_done_at;
        }

        $result = $service->getCloud($where);

        $service->sync($result['data']);

        return response()->json(['message' => 'Synced successfully']);
    }


}
