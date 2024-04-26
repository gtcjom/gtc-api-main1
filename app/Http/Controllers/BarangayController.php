<?php

namespace App\Http\Controllers;

use App\Http\Resources\BarangayResource;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class BarangayController extends Controller
{
	public function index(Request $request)
	{
		$municipality_id = $request->has('municipality_id') ? $request->municipality_id : null;
		$barangays = Barangay::query()
			->when($municipality_id, function($query) use ($municipality_id){
				return $query->where('municipality_id', $municipality_id);
			})
			->orderBy('name')
			->get();

		return BarangayResource::collection($barangays);
	}

	public function show(Barangay $barangay)
	{
		return response()->json([
			'data' => new BarangayResource($barangay->load('municipality')),
		]);
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => ['required', 'string'],
			'code' => ['required', 'string'],
			'municipality_id' => ['required', Rule::exists('municipalities', 'id')]
		]);

		$barangay = Barangay::create($data);

		return response()->json([
			'data' => new BarangayResource($barangay),
			'message' => "Barangay created successfully",
		], Response::HTTP_CREATED);
	}

	public function update(Request $request, Barangay $barangay)
	{
		$data = $request->validate([
			'name' => ['required', 'string'],
			'code' => ['required', 'string'],
			'municipality_id' => ['required', Rule::exists('municipalities', 'id')]
		]);

		$barangay->update($data);

		return response()->json([
			'data' => new BarangayResource($barangay),
			'message' => "Barangay updated successfully",
		], Response::HTTP_OK);
	}

	public function destroy(Barangay $barangay)
	{
		$barangay->delete();
		return response()->json(['message' => "Barangay deleted successfully"], Response::HTTP_OK);
	}
}
