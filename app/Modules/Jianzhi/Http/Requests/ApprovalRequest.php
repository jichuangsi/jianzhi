<?php
namespace App\Modules\Jianzhi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovalRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		$rules = [
		        'payment'=>'required|positive',
				'comment'=>'required|str_length:5000',
		];

		return $rules;
	}
	public function messages()
	{
		return [
    		    'payment.required' => '请填写任务金额',
    		    'payment.positive'=>'任务预算必须大于0',
				'comment.required' => '验收说明不能为空',
				'comment.str_length'=> '字数超过限制',
		];
	}
}
