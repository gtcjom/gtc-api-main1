<?php

namespace App\Http\Controllers\PMRF;

use App\Http\Controllers\Controller;
use App\Services\Cloud\PmrfPatientService;
use Illuminate\Http\Request;

class VerifyPatientController extends Controller
{

    public function index(PmrfPatientService $service)
    {

    }


    public function getDoneList(PmrfPatientService $service, Request $request)
    {
        $where = $request->all();

        $where['pmrf_status']  = 'done';


       return $service->get($where);
    }
}
