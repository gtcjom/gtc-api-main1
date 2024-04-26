<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientProfileController extends Controller
{

    public function update(Request $request, int $id)
    {

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png']
        ]);

        if($request->hasFile('avatar')){
            $patient = Patient::query()->findOrFail($id);
            $patient->avatar = $request->file('avatar')->store('patients/avatar');
            $patient->save();

        }

        return response()->noContent();
    }
}