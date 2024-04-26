<?php

namespace App\Services\Cloud;

use App\Models\PatientCase;

class ExampleItemService
{

    public function items()
    {

        $localCase = PatientCase::query()->where('cloud_id', $case['id'])->first();
    }

}
