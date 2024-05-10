<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnesthesiaOrderResource;
use App\Http\Resources\AnesthesiaTestResource;
use App\Models\AnesthesiaTest;
use Illuminate\Http\Request;

class AnesthesiaTestController extends Controller
{
    //
    public function index()
    {
        $type = request()->get('type');
        return AnesthesiaTestResource::collection(AnesthesiaTest::query()
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })->orderBy('name')->get());
    }
    public function store(Request $request)
    {
        $test = new AnesthesiaTest();
        $test->name = $request->get('name');
        $test->description = $request->get('description');
        $test->type = $request->get('type');
        $test->save();

        return AnesthesiaTestResource::make($test);
    }
    public function show($id)
    {

        $test =  AnesthesiaTest::query()->findOrFail($id);
        return AnesthesiaTestResource::make($test);
    }
    public function update(Request $request, $id)
    {
        //
        $test =  AnesthesiaTest::query()->findOrFail($id);
        $test->name = $request->get('name');
        $test->description = $request->get('description');
        $test->type = $request->get('type');
        $test->save();

        return AnesthesiaTestResource::make($test);
    }
    public function destroy($id)
    {
        //
        $test =  AnesthesiaTest::query()->findOrFail($id);
        $test->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }
}
