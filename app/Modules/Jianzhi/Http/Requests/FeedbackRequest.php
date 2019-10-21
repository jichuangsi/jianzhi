<?php
namespace App\Modules\Jianzhi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		$rules = [
		        'title'=>'required|str_length:25',
				'desc'=>'required|str_length:200',
		];

		return $rules;
	}
	public function messages()
	{
		return [
    		    'title.required' => '反馈标题不能为空',
    		    'title.str_length'=>'字数超过限制',
				'desc.required' => '反馈内容不能为空',
				'desc.str_length'=> '字数超过限制',
		];
	}
}
