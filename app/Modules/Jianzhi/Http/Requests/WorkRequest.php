<?php
namespace App\Modules\Jianzhi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		$rules = [
				'desc'=>'required|str_length:5000',
		];

		return $rules;
	}
	public function messages()
	{
		return [
				'desc.required' => '验收说明不能为空',
				'desc.str_length'=> '字数超过限制',
		];
	}
}
