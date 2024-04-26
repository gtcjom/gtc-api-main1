<?php

namespace App\Http\Controllers\PHO;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index()
    {
        return Supplier::query()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
        ]);
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->save();
        return $supplier;
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
        ]);
        $supplier = Supplier::query()->findOrFail($id);
        $supplier->name = $request->name;
        $supplier->save();
        return $supplier;
    }

    public function destroy(int $id)
    {
        $supplier = Supplier::query()->findOrFail($id);
        $supplier->delete();
        return response()->noContent();
    }
}
