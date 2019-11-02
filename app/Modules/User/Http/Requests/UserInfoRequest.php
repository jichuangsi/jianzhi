<?php
namespace App\Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserInfoRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		return [
			'mobile'=>'size:11|mobile_phone',
		    'code' => 'required|alpha_num',
		];
	}

	
	public function messages()
	{
		return [
			'mobile.size'=>'国内的手机号码长度为11位',
			'mobile.mobile_phone'=>'请输入一个手机号码',
		    'code.required' => '请输入验证码',
		    'code.alpha_num' => '请输入正确的验证码格式',
		];
	}
}
