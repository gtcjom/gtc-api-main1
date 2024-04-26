<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_Credits;

class Credits extends Controller
{
    public function creditBalance(Request $request){
        return response()->json((new _Credits)::creditBalance($request));
    }

    public function creditTransaction(Request $request){
        return response()->json((new _Credits)::creditTransaction($request));
    }

    public function creditLoadout(Request $request){
        return response()->json((new _Credits)::creditLoadout($request));
    }
    
    public function creditOthers(Request $request){
        return response()->json((new _Credits)::creditOthers($request));
    }

    
}
