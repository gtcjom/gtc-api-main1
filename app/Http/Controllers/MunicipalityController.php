<?php

namespace App\Http\Controllers;

use App\Http\Resources\MunicipalityResource;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MunicipalityController extends Controller
{
    public function index()
	{
		return MunicipalityResource::collection(Municipality::orderBy('name')->get());
	}

	public function show(Municipality $municipality)
	{
		return response()->json([
			'data' => new MunicipalityResource($municipality->load('barangays')),
		]);
	}

	public function store(Request $request)
	{
		$data = $request->validate(['name' => ['required', 'string']]);

		$municipality = Municipality::create($data);

		return response()->json([
			'data' => new MunicipalityResource($municipality),
			'message' => "Municipality created successfully",
		], Response::HTTP_CREATED);
	}

	public function update(Request $request, Municipality $municipality)
	{
		$data = $request->validate(['name' => ['required', 'string']]);

		$municipality->update($data);

		return response()->json([
			'data' => new MunicipalityResource($municipality),
			'message' => "Municipality updated successfully",
		], Response::HTTP_OK);
	}

	public function destroy(Municipality $municipality)
	{
		$municipality->delete();
		return response()->json(['message' => "Municipality deleted successfully"], Response::HTTP_OK);
	}
}
