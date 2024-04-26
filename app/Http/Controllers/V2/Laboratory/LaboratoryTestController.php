<?php

namespace App\Http\Controllers\V2\Laboratory;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaboratoryTestResource;
use App\Models\LaboratoryTest;
use Illuminate\Http\Request;

class LaboratoryTestController extends Controller
{

    public function index()
    {
        $type = request()->get('type');
        return LaboratoryTestResource::collection(LaboratoryTest::query()
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $test = new LaboratoryTest();
        $test->name = $request->get('name');
        $test->description = $request->get('description');
        $test->type = $request->get('type');
        $test->save();

        return LaboratoryTestResource::make($test);
    }

    public function show($id)
    {

        $test =  LaboratoryTest::query()->findOrFail($id);
        return LaboratoryTestResource::make($test);
    }

    public function update(Request $request, $id)
    {
        //
        $test =  LaboratoryTest::query()->findOrFail($id);
        $test->name = $request->get('name');
        $test->description = $request->get('description');
        $test->type = $request->get('type');
        $test->save();

        return LaboratoryTestResource::make($test);
    }

    public function destroy($id)
    {
        //
        $test =  LaboratoryTest::query()->findOrFail($id);
        $test->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }
}
