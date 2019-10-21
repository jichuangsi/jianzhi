<?php
namespace App\Modules\Jianzhi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterpriseInfoRequest extends FormRequest
{
	
	public function rules()
	{
        return [
            'owner' => 'required|string|between:2,5',
            'contactor' => 'required|string|between:2,5',
            'contactor_mobile' => 'required|alpha_num',
            'phone' => 'required|alpha_num',
        ];
	}

	
	public function authorize()
	{
		return true;
	}

    public function messages()
    {
        return [
            'owner.required' => '请输入法人姓名',
            'owner.string' => '请输入法人姓名正确的格式',
            'owner.between' => '法人姓名:min - :max 个字符',
            'contactor.required' => '请输入联系人姓名',
            'contactor.string' => '请输入联系人姓名正确的格式',
            'contactor.between' => '联系人姓名:min - :max 个字符',
            'contactor_mobile.required' => '请输入联系人电话',
            'contactor_mobile.alpha_num' => '请输入正确的电话格式',            
            'phone.required' => '请输入企业电话',
            'phone.alpha_num' => '请输入正确的电话格式',
        ];

    }
}
