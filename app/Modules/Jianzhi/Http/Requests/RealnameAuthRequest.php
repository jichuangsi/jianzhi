<?php
namespace App\Modules\Jianzhi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RealnameAuthRequest extends FormRequest
{
	
	public function rules()
	{
        return [
            'realname' => 'required|string|between:2,5',
            'card_number' => 'required|alpha_num|between:15,18',
            'account' => 'required|alpha_num',
            'card_front_side' => 'required',
            'card_back_dside' => 'required'
        ];
	}

	
	public function authorize()
	{
		return true;
	}

    public function messages()
    {
        return [
            'realname.required' => '请输入真实姓名',
            'realname.string' => '请输入正确的格式',
            'realname.between' => '真实姓名:min - :max 个字符',

            'card_number.required' => '请输入身份证号码',
            'card_number.alpha_num' => '请输入正确的身份证格式',
            'card_number.between' => '身份证号码长度在:min - :max 位',
            
            'account.required' => '请输入银行卡号',
            'account.alpha_num' => '请输入正确的银行卡号格式',

            'card_front_side.required' => '请上传身份证正面图片',
            'card_back_dside.required' => '请上传身份证反面图片',
        ];

    }
}
