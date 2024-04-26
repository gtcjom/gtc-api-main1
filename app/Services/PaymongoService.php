<?php

namespace App\Services;

use Luigel\Paymongo\Facades\Paymongo;

class PaymongoService
{

    public function createSource()
    {
         return Paymongo::source()->create([
            'type' => 'gcash',
            'amount' => 100,
            'currency' => 'PHP',
            'redirect' => [
                'success' => config('client.paymongo.success_url'),
                'failed' => config('client.paymongo.failed_url'),
            ]
        ]);


    }
}