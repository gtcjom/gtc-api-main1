<?php

namespace App\Http\Controllers\PHO;

use App\Models\Purok;
use Illuminate\Validation\Rule;

class PurokController
{

    public function index()
    {
        request()->validate([
            'barangay_id' => ['required', Rule::exists('barangays', 'id')]
        ]);

        return Purok::query()
            ->where('barangay_id', request()->get('barangay_id'))
            ->orderBy('number', 'asc')
            ->get();


    }
}