<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateOperatingRoomChartRequest extends FormRequest
{
	public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
			'date' => [
				'required',
				'date'
			],
			'time' => [
				'nullable',
				'date_format:H:i'
			],
			'procedure' => [
				'nullable',
				'string'
			],
			'priority' => [
				'nullable',
				Rule::in([0, 1])
			],
			'room_number' => [
				'nullable',
				'string'
			],
			'appointment_id' => [
				'nullable'
			],
			'healthcare_professionals' => [
				'nullable',
				'array'
			],
        	'healthcare_professionals.*.title' => [
				'nullable',
				'string'
			],
        	'healthcare_professionals.*.doctor_id' => [
				'nullable'
			],
        ];
    }

	public function messages()
	{
		return [
			'time.required' => 'The time field is required.',
			'time.date_format' => 'The time field must be in the format hh:mm (ex: 09:00)',
		];
	}
}
