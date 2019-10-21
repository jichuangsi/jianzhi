<?php
namespace App\Modules\Jianzhi\Http\Requests;

use App\Modules\Task\Http\Requests\TaskRequest as TaskBasicRequest;

class TaskRequest extends TaskBasicRequest
{
	public $task_bounty_min_limit,$task_bounty_max_limit;
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		$rules = [
				'mainType'=>'required',
				'subType'=>'required',
				'begin_at'=>'required|beginAt',
		        'end_at'=>'required|beginAt',
				'title'=>'required',
		        'district'=>'required',
		        'skill'=>'required',
		        'address'=>'required|str_length:255',
				'desc'=>'required|str_length:5000',
				'bounty'=>'required|positive',
		        'worker_num'=>'required|positive'
		];
		/* $type_id = $this->only('xuanshang');
		$bounty  = json_encode($this->only('bounty'));
		$begin_at = json_encode($this->only('begin_at'));

		
		if(!empty($type_id))
		{
			$this->task_bounty_min_limit = \CommonClass::getConfig('task_bounty_min_limit');
			$this->task_bounty_max_limit = \CommonClass::getConfig('task_bounty_max_limit');
			$rules = array_add($rules, 'bounty', "required|bounty_max|bounty_min");
			$rules = array_add($rules, 'worker_num', 'required|positive');
			$rules = array_add($rules, 'delivery_deadline', "required|deliveryDeadline:$bounty,$begin_at");
			$rules = array_add($rules, 'begin_at', 'required|beginAt');
		} */

		return $rules;
	}
	public function messages()
	{
		return [
		        'mainType.required' => '请选择任务主类型',
				'subType.required' => '请选择任务子类型',
		        'begin_at.required'=>'请填入任务开始时间',
		        'end_at.required'=>'请填入任务开始时间',
    		    'title.required' => '请填写标题',
    		    'district.required' => '请选择任务城市',
    		    'skill.required' => '请选择任务所需技能',
    		    'address.required' => '请填入任务服务地址',	    
				'bounty.required' => '请填写任务预算',
				'bounty.numeric'=>'任务预算必须是数值',
				'worker_num.required' => '请填写由几人完成',				
				'title.required' => '请填写标题',
				'desc.required'=>'需求详情不能为空',				
				/* 'bounty.bounty_max'=>'赏金最大值不能超过'.$this->task_bounty_max_limit,
				'bounty.bounty_min'=>'赏金最小为'.$this->task_bounty_min_limit, */
		        'bounty.positive'=>'任务预算必须大于0',
				'worker_num.positive'=>'服务商数量必须大于0',
				'desc.str_length'=>'字数超过限制',
				'begin_at.beginAt'=>'开始时间不能在今天之前',
                'end_at.beginAt'=>'结束时间不能在今天之前'
		];
	}
}
