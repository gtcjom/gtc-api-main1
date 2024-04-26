<?php

namespace App\Services;

use App\Models\LaboratoryResult;
use App\Models\LaboratoryTest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class LaboratoryResultService
{
	public function store(Request $request)
	{
		$laboratoryResult = LaboratoryResult::create(array_merge([
			'laboratory_order_id' => $request->laboratory_order_id,
			'laboratory_order_type' => $request->laboratory_order_type,
			'laboratory_test_id' => $request->laboratory_test_id,
			'remarks' => $request->remarks,
			'results' => $request->results,
			'status' => 'completed',
			'added_by' => $request->user()->id,
			'image' => $request->file('image') ? $request->file('image')->store('laboratory-results/image') : null
		]));

		$laboratoryResult->load(['laboratoryOrder', 'addedBy']);

		return $laboratoryResult;
	}

	public function update(Request $request, int $id): Model|LaboratoryResult|Collection|Builder|array|null
	{
		$laboratoryResult = LaboratoryResult::findOrFail($id);
		$laboratoryResult->laboratory_order_type = $request->get('laboratory_order_type');
		$laboratoryResult->laboratory_test_id = $request->get('laboratory_test_id');
		$laboratoryResult->results = $request->get('results');
		$laboratoryResult->remarks = $request->get('remarks');

		if ($request->hasFile('image')) {
			$laboratoryResult->image = $request->file('image')->store('laboratory-results/image');
		}

		$laboratoryResult->save();
		$laboratoryResult->load(['laboratoryOrder', 'addedBy']);

		return $laboratoryResult;
	}

	public function list(Request $request)
	{
		$lab_test_type = $request->get('laboratory_type') ? $request->get('laboratory_type') : null;

		return LaboratoryResult::query()
			->with(['laboratoryTest', 'laboratoryOrder', 'addedBy'])
			->when($lab_test_type, function ($query, $lab_test_type) {
				$test_ids = LaboratoryTest::query()->where('type', $lab_test_type)->get()->pluck('id');

				$query->whereIn('laboratory_test_id', $test_ids);

				// if (str_contains($laboratory_order_type, ',')) {
				// 	$query->whereIn('laboratory_order_type', explode(',', $laboratory_order_type));
				// } else {
				// 	$query->where('laboratory_order_type', $laboratory_order_type);
				// }
			})
			->latest()
			->paginate(request('paginate', 12));
	}
}
