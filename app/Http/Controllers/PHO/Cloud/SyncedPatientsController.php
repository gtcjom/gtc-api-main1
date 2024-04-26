<?php

namespace App\Http\Controllers\PHO\Cloud;

use App\Http\Controllers\Controller;
use App\Models\Patient;

class SyncedPatientsController extends Controller
{

    public function update()
    {
        $lastUpdated = Patient::query()->latest('last_updated')->first();
        $lastSync = $lastUpdated?->last_updated;
        //init guzzle client
        $client = new \GuzzleHttp\Client();
        //send request get request to cloud


    }
}
