<?php

namespace App\Services\MHRA;

class BundleService
{

    public function make(array $data)
    {

        $bundle = [
            'resourceType' => 'Bundle',
            'type' => 'transaction',
            'entry' => []
        ];

        foreach ($data as $item) {
            $bundle['entry'][] = $item;
        }

        return $bundle;
    }




}
