<?php

namespace App\Http\Controllers;

use App\Models\_Administrator;
use Illuminate\Http\Request;

class Administrator extends Controller
{
    function getGTCFeaturedNews(Request $request){
        return response()->json((new _Administrator)::getGTCFeaturedNews($request));
    }

    function getGTCFeaturedNewsMore(Request $request){
        return response()->json((new _Administrator)::getGTCFeaturedNewsMore($request));
    }

    function getGTCDialogList(Request $request){
        return response()->json((new _Administrator)::getGTCDialogList($request));
    }
}
