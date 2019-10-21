<?php
namespace App\Modules\Jianzhi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterpriseAuthRequest extends FormRequest
{
	
	public function rules()
	{
        return [
            'company_name' => 'required',
            'owner' => 'required|string|between:2,5',
            'contactor' => 'required|string|between:2,5',
            'contactor_mobile' => 'required|alpha_num',
            'bank' => 'required',
            'account' => 'required',
            'tax_code' => 'required',
            'phone' => 'required|alpha_num',
            'address' => 'required',
            'business_license' => 'required'
        ];
	}

	
	public function authorize()
	{
		return true;
	}

    public function messages()
    {
        return [
            'company_name.required' => '请输入企业名称',
            'owner.required' => '请输入法人姓名',
            'owner.string' => '请输入法人姓名正确的格式',
            'owner.between' => '法人姓名:min - :max 个字符',
            'contactor.required' => '请输入联系人姓名',
            'contactor.string' => '请输入联系人姓名正确的格式',
            'contactor.between' => '联系人姓名:min - :max 个字符',
            'contactor_mobile.required' => '请输入联系人电话',
            'contactor_mobile.alpha_num' => '请输入正确的电话格式',
            'bank.required' => '请输入开户行',
            'account.required' =>  '请输入开户行账户',
            'tax_code.required' => '请输入纳税人识别码',
            'phone.required' => '请输入企业电话',
            'phone.alpha_num' => '请输入正确的电话格式',
            'address.required' => '请输入企业地址',
            'business_license.required' => '请上传营业执照副本',
        ];

    }
}
