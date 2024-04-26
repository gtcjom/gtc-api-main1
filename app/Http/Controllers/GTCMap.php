<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_GTCMap;

class GTCMap extends Controller
{
    public function getMunicipality(Request $request){
        $result = (new _GTCMap)::getMunicipality($request);
        return response()->json($result);
    }

    public function getBarangay(Request $request){
        $result = (new _GTCMap)::getBarangay($request);
        return response()->json($result);
    }

    public function getSpecificBarangay(Request $request){
        $result = (new _GTCMap)::getSpecificBarangay($request);
        return response()->json($result);
    }

    public function getIlledPatient(Request $request){
        $result = (new _GTCMap)::getIlledPatient($request);
        return response()->json($result);
    }
}




