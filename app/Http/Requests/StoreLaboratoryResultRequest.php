<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreLaboratoryResultRequest extends FormRequest
{

	public function authorize(): bool
	{
		return true;
	}

	public function rules()
	{
		return [
			'laboratory_order_id' => [
				'required'
			],
			'laboratory_order_type' => [
				'nullable',
				// Rule::in('sputum', 'x-ray', 'rt-pcr')
			],
			'laboratory_test_id' => [
				'required'
			],
			'remarks' => [
				'nullable',
				'string'
			],
			'results' => [
				'nullable',
				'string',
				Rule::requiredIf($this->laboratory_order_type == 'sputum' || $this->laboratory_order_type == 'rt-pcr'),
				// Rule::in('positive', 'negative'),
			],
			'image' => [
				'nullable',
				'image',
				'mimes:jpeg,jpg,png'
			],
			'status' => [
				'nullable'
			],
		];
	}

	public function messages()
	{
		return [
			'laboratory_order_type.in' => 'The laboratory order type must be sputum, x-ray or rt-pcr.',
			'results.in' => 'The result must be positive or negative.',
		];
	}
}
