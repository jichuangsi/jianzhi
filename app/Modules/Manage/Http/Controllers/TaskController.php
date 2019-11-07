<?php
namespace App\Modules\Manage\Http\Controllers;

use App\Http\Controllers\ManageController;
use App\Modules\Task\Model\TaskCateModel;
use App\Modules\User\Model\DistrictModel;
use App\Modules\Finance\Model\FinancialModel;
use App\Modules\Manage\Model\MessageTemplateModel;
use App\Modules\Task\Model\TaskAttachmentModel;
use App\Modules\Task\Model\TaskExtraModel;
use App\Modules\Task\Model\TaskExtraSeoModel;
use App\Modules\Task\Model\TaskModel;
use App\Modules\Task\Model\TaskTagsModel;
use App\Modules\Task\Model\TaskTypeModel;
use App\Modules\Task\Model\WorkCommentModel;
use App\Modules\Task\Model\WorkModel;
use App\Modules\User\Model\MessageReceiveModel;
use App\Modules\User\Model\EnterpriseAuthModel;
use App\Modules\User\Model\UserDetailModel;
use App\Modules\User\Model\UserModel;
use App\Modules\User\Model\AttachmentModel;
use App\Modules\Task\Model\WorkAttachmentModel;
use App\Modules\User\Model\UserTagsModel;
use App\Modules\Manage\Model\ConfigModel;
use App\Modules\Manage\Model\ManagerModel;
use App\Modules\User\Model\ChannelDistributionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Theme;
use App\Modules\User\Model\RealnameAuthModel;

class TaskController extends ManageController
{
    public function __construct()
    {
        parent::__construct();

        $this->initTheme('manage');
        $this->theme->setTitle('任务管理');
        $this->theme->set('manageType', 'task');
    }
     /**
     * 进入任务添加
     */
    public function getTaskAdd(){        
        $data = array();
        //dump( TaskCateModel::findByPid([0]));exit;
            //任务类型
            $taskType = TaskTypeModel::findByPid([0]);
            //技能标签     
            $taskCate = TaskCateModel::findAll();
            //查询地区一级数据
            $province = DistrictModel::findTree(0);
            //查询地区二级信息
            $city = DistrictModel::findTree($province[0]['id']);
            //查询三级
            $area = DistrictModel::findTree($city[0]['id']);
            $qiye = EnterpriseAuthModel::getqiye();
            $data = array(
                'taskType' => $taskType,
                'taskCate' => $taskCate,
                'province' => $province,
                'area' => $area,
                'city' => $city,
                'qiye' => $qiye,
            );
            
        return $this->theme->scope("manage.taskAdd",$data)->render();
    }
    /**
     * ajax获取任务子分类的数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSubTaskType(Request $request)
    {
        $id = intval($request->get('id'));
        if(is_null($id)){
            return response()->json(['errMsg'=>'参数错误！']);
        }
        $subTaskType = TaskTypeModel::findByPid([$id]);        
        
        $data = [
            'subTaskType'=>$subTaskType,
            'id'=>$id
        ];
        return response()->json($data);
    }
    public function ajaxDelAtt($aid,$tid){
    	$attret=AttachmentModel::where('id','=',$aid)->delete();
    	if($attret){
    		$attret1=TaskAttachmentModel::where('attachment_id','=',$aid)->where('task_id','=',$tid)->delete();
    		if($attret1){
    			return 1;
    		}
    	}
    	return 0;
    }
    
    /**
     * 上传任务附件
     */
     public function addattachment($file,$uids){
     		$attachment = \FileClass::uploadFile($file, 'task');
	        $attachment = json_decode($attachment, true);
	        //判断文件是否上传
	//      return response()->json(['errCode' => 0, 'errMsg' => $id]);
	        if($attachment['code']!=200)
	        {
	//          return response()->json(['errCode' => 0, 'errMsg' => $attachment['message']]);
	            return redirect()->back()->with(['error'=>$attachment['message']]);
	        }
	        $attachment_data = array_add($attachment['data'], 'status', 1);
	        $attachment_data['created_at'] = date('Y-m-d H:i:s', time());
	        $attachment_data['user_id']=$uids;
	        //将记录写入到attchement表中
	        $result = AttachmentModel::create($attachment_data);
	        return $result['id'];
     }
    /**
     * 任务提交，创建一个新任务
     * @param TaskRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */    
    public function postTaskAdd(Request $request)
    {       
    	$file = $request->file('file');
    	$file2 = $request->file('file2');
    	$file3 = $request->file('file3');
    	$data = $request->except('_token');
    	//将文件上传的数据存入到attachment表中
		$attarr=array();
		if(!empty($file)){
			$atid=$this->addattachment($file,$data['uids']);
			array_push($attarr,$atid);
		}   
		if(!empty($file2)){
			$atid=$this->addattachment($file2,$data['uids']);
			array_push($attarr,$atid);
		}
		if(!empty($file3)){
			$atid=$this->addattachment($file3,$data['uids']);
			array_push($attarr,$atid);
		}     
//      $result = json_decode($result, true);
        
        
        $data['uid'] = $data['uids'];
        $data['username'] ='ttt';
        if(!empty($attarr)){
        	$data['file_id']=$attarr;
        }
        
//      $data['type_id'] = $data['mainType'];
//      $data['sub_type_id'] = $data['subType'];
        $data['desc'] = \CommonClass::removeXss($data['desc']);
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['begin_at'] = preg_replace('/([\x80-\xff]*)/i', '', $data['begin_at']);
        $data['end_at'] = date('Y-m-d H:i:s', strtotime($data['end_at']));
        if(isset($data['district'])&&!empty($data['district'])){
            $districts = explode("-", $data['district']);
            $data['province'] = $districts[0];
            $data['city'] = $districts[1];
            $data['area'] = $districts[2];
        }
        //新建后马上发布任务并进入审核流程
        $data['status'] = 2;
        //任务进入赏金托管状态
        $data['bounty_status'] = 1;
        
        //$data['show_cash'] = $data['bounty'];        
        //$data['delivery_deadline'] = preg_replace('/([\x80-\xff]*)/i', '', $data['delivery_deadline']);        
        //$data['delivery_deadline'] = date('Y-m-d H:i:s', strtotime($data['delivery_deadline']));
        
        //联合验证托管赏金和开始时间结束时间
        
        
        //发布预览和暂不发布切换
        /* if ($data['slutype'] == 1) {
            $data['status'] = 1;
            $controller = 'bounty';
        } elseif ($data['slutype'] == 2) {
            return redirect()->to('task/preview')->with($data);
        } elseif ($data['slutype'] == 3) {
            $data['status'] = 0;
            $controller = 'detail';
        } */
        //查询当前的任务成功抽成比率
        /* $task_percentage = \CommonClass::getConfig('task_percentage');
        $task_fail_percentage = \CommonClass::getConfig('task_fail_percentage');
        $data['task_success_draw_ratio'] = $task_percentage;
        $data['task_fail_draw_ratio'] = $task_fail_percentage; */
        
                
        $taskModel = new TaskModel();
        $result = $taskModel->createTask($data);
        
        if (!$result) {
        	return  redirect()->to('manage/taskList')->with(array('message' => '创建任务失败'));
        }
        
        /* if($data['slutype']==3){
            return redirect()->to('user/unreleasedTasks');
        } */
        //return redirect()->to('task/' . $controller . '/' . $result['id']);
        //跳转至任务列表
        return redirect('manage/taskList')->with(['message' => '操作成功']);
    }
    /*
     * 进入任务修改
     */
    public function getTaskUpdate($id){
    	$data = array();
        //dump( TaskCateModel::findByPid([0]));exit;
            //任务类型
            $taskType = TaskTypeModel::findByPid([0]);
            //技能标签     
            $taskCate = TaskCateModel::findAll();
            //查询地区一级数据
            $province = DistrictModel::findTree(0);
            //查询地区二级信息
            $city = DistrictModel::findTree($province[0]['id']);
            //查询三级
            $area = DistrictModel::findTree($city[0]['id']);
            $qiye = EnterpriseAuthModel::getqiye();
            $taskinfo = $this->getTaskDetail($id);
            $taTags=$taskinfo['tags'];
            $skill = array();
	        if($taTags&&count($taTags)>0){
	            foreach($taskCate as $k => &$v){
	                if(isset($v['children_task'])){
	                    foreach($v['children_task'] as $k1 => &$v1){
	                        foreach($taTags as $k2 => $v2){
	                            if($v1['id']===$v2['cate_id']){
	                                array_push($skill, ['cate_id' =>$v1['id'], 'cate_name' =>$v1['name'] ]);
	                            }
	                        }
	                    }
	                }
	            }
	        }
            $data = array(
                'taskType' => $taskType,
                'taskCate' => $taskCate,
                'province' => $province,
                'area' => $area,
                'city' => $city,
                'qiye' => $qiye,
                'task' =>$taskinfo['task'],
                'taskAttachment'=>$taskinfo['taskAttachment'],
                'tags' =>$skill ,
                'works' => $taskinfo['works'],
                'taskcity' => DistrictModel::getDistrictName($taskinfo['task']['city']),
            	'taskarea' => DistrictModel::getDistrictName($taskinfo['task']['area']),
            );
            
        return $this->theme->scope("manage.taskUpdate",$data)->render();
    }
    /*
    /*
     * 提交任务修改
     */
    public function postTaskUpdate(Request $request){
		$data = $request->except('_token');
		$file1 = $request->file('file1');
		$attarr=array();
		if(!empty($file1)){
			if($data['aid1']>0){
				$attret1=$this->ajaxDelAtt($data['aid1'],$data['task_id']);
			}
			$atid=$this->addattachment($file1,$data['uids']);
			array_push($attarr,$atid);
		}
    	$file2 = $request->file('file2');
    	if(!empty($file2)){
			if($data['aid2']>0){
				$attret1=$this->ajaxDelAtt($data['aid2'],$data['task_id']);
			}
			$atid=$this->addattachment($file2,$data['uids']);
			array_push($attarr,$atid);
		}
    	$file3 = $request->file('file3');
    	if(!empty($file3)){
			if($data['aid3']>0){
				$attret1=$this->ajaxDelAtt($data['aid3'],$data['task_id']);
			}
			$atid=$this->addattachment($file3,$data['uids']);
			array_push($attarr,$atid);
		}
		if(!empty($attarr)){
//      	$data['file_id']=$attarr;
        	foreach ($attarr as $v) {
                $attachment_data = [
                    'task_id' => $data['task_id'],
                    'attachment_id' => $v,
                    'created_at' => date('Y-m-d H:i:s', time()),
                ];
                TaskAttachmentModel::create($attachment_data);
            }
        }
		//修改任务数据
        $task = [
        	'uid'=>$data['uids'],
            'title'=>$data['title'],
            'desc'=>$data['desc'],
            'type_id'=>$data['type_id'],
            'sub_type_id'=>$data['sub_type_id'],
            'begin_at'=>date('Y-m-d H:i:s', strtotime($data['begin_at'])),
            'end_at'=>date('Y-m-d H:i:s', strtotime($data['end_at'])),
            'title'=>$data['title'],
            'province'=>$data['province'],
            'city'=>$data['city'],
            'area'=>$data['area'],
            'address'=>$data['address'],
            'bounty'=>$data['bounty'],
            'worker_num'=>$data['worker_num'],
            'skill'=>$data['skill'],
        ];
        
        
        $tid=$data['task_id'];
        $result=TaskModel::updateTask($task,$tid);
//      dump($result);exit;
//      unset($result['skill']);
//      dump($result);
//      exit;
//      //修改任务数据
//      $task_result = TaskModel::where('id',$data['task_id'])->update($task);
        if(!$result)
        {
            return redirect()->back()->with(['error'=>'更新失败！']);
        }
        return redirect('manage/taskList')->with(['message' => '更新成功']);
	}
	
    /**
      *进入上传凭证视图 
      */
    public function getTaskVoucher($id){
    	$task=TaskModel::where('id',$id)->first();
        if(!$task)
        {
            return redirect()->back()->with(['error'=>'当前任务不存在,无法继续操作！']);
        }
        $query = TaskModel::select('task.*', 'ea.company_name as nickname', 'ud.avatar','ud.qq')->where('task.id', $id);
        $taskDetail = $query->join('user_detail as ud', 'ud.uid', '=', 'task.uid')
            ->leftjoin('enterprise_auth as ea','ea.uid','=','task.uid')
            ->first()->toArray();
        if(!$taskDetail)
        {
             return redirect()->back()->with(['error'=>'当前任务已经被删除！']);
        }
         $data = [
            'task' => $taskDetail,
        ]; 
        return $this->theme->scope("manage.taskVoucher",$data)->render();
    }
    public function postTaskVoucher(Request $request){
    	$data = $request->except('_token');
    	$file = $request->file('file');
    	if(empty($file)){
    		return redirect()->back()->with(['error'=>'请选择上传文件']);
    	}
    	$attachment = \FileClass::uploadFile($file, 'task');
        $attachment = json_decode($attachment, true);
        //判断文件是否上传
//      return response()->json(['errCode' => 0, 'errMsg' => $id]);
        if($attachment['code']!=200)
        {
//          return response()->json(['errCode' => 0, 'errMsg' => $attachment['message']]);
            return redirect()->back()->with(['error'=>$attachment['message']]);
        }
        $attachment_data = array_add($attachment['data'], 'status', 1);
        $attachment_data['created_at'] = date('Y-m-d H:i:s', time());
        $attachment_data['user_id']=$data['tuid'];
        $attachment_data['status']=3;
        //将记录写入到attchement表中
        $result = AttachmentModel::create($attachment_data);
        $attachment_data = [
            'task_id' => $data['tid'],
            'attachment_id' => $result['id'],
            'created_at' => date('Y-m-d H:i:s', time()),
        ];
        TaskAttachmentModel::create($attachment_data);
        return redirect('manage/taskList')->with(['message' => '上传成功']);
    }
    /*
	 * 渠道商列表查看
	 */
	public function channelList(Request $request){
		$search = $request->all();
		$chanList = UserModel::select('enterprise_auth.*','users.id as uuid','district.name as dname');
		$paginate = $request->get('paginate') ? $request->get('paginate') : 10;
		if ($request->get('company_name')) {
            $chanList = $chanList->where('enterprise_auth.company_name','like','%'.e($request->get('company_name')).'%');
        }
		$chanList=$chanList->where('users.type','=',2)
					->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'users.id')
					->leftJoin('district', 'enterprise_auth.city', '=', 'district.id')
				  	->paginate($paginate);
		$data = [
            'chan' => $chanList,
        ]; 
        $data['merge'] = $search;
        $data['company_name'] = $request->get('company_name');
        return $this->theme->scope('manage.channelList',$data)->render();
	}
	/*
	 * 渠道商分配列表
	 */
	public function channelDistribution(Request $request){
		$search = $request->all();
		$chanList = UserModel::select('enterprise_auth.*','users.id as uuid','district.name as dname','ma.realname as musername','cd.createtime as cdtime');
		$paginate = $request->get('paginate') ? $request->get('paginate') : 10;
		if ($request->get('company_name')) {
            $chanList = $chanList->where('enterprise_auth.company_name','like','%'.e($request->get('company_name')).'%');
        }
        if($request->get('start')){
            $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
            $chanList = $chanList->where('cd.createtime','>',$start);
        }
        if($request->get('end')){
            $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
            $chanList = $chanList->where('cd.createtime','<',$end);
        }
		$chanList=$chanList->where('users.type','=',2)
					->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'users.id')
					->leftJoin('district', 'enterprise_auth.city', '=', 'district.id')
					->leftJoin('channel_distribution as cd', 'cd.eid', '=', 'enterprise_auth.id')
					->leftJoin('manager as ma', 'ma.id', '=', 'cd.mid')
				  	->paginate($paginate);
		$data = [
            'chan' => $chanList,
        ]; 
        $data['merge'] = $search;
        $data['company_name'] = $request->get('company_name');
        return $this->theme->scope('manage.channelDistribution',$data)->render();
	}
	/*
	 * 渠道商分配页面
	 */
	public function getChannelDistributionInfo($id){
		if(!empty($id)){
			$entauth=EnterpriseAuthModel::where('id','=',$id)->first();
		}
		$managelist=ManagerModel::select('manager.*')->where('ru.role_id','=','3')
								->leftJoin('role_user as ru', 'ru.user_id', '=', 'manager.id')->get()->toArray();
		$data=[
			'entinfo'=>$entauth,
			'managelist'=>$managelist,
		];
		return $this->theme->scope('manage.channelDistributionInfo',$data)->render();
	}
	/*
	 * 提交渠道商分配
	 */
	public function postChannelDistributionInfo(Request $request){
		$data = $request->except('_token');
		if(empty($data['eid'])){
			return redirect()->back()->with(['error'=>'缺少企业参数']);
		}
		if(empty($data['mid'])){
			return redirect()->back()->with(['error'=>'请选择销售人员']);
		}
		$data=[
			'eid'=>$data['eid'],
			'mid'=>$data['mid'],
			'createtime'=>date('Y-m-d H:i:s', time()),
		];
		ChannelDistributionModel::create($data);
		return redirect('manage/channelDistribution')->with(['message' => '分配成功']);
		return $this->theme->scope('manage.channelDistributionInfo',$data)->render();
	}
    /**
     * 任务列表
     *
     * @param Request $request
     * @return mixed
     */
    public function taskList(Request $request)
    {
        $search = $request->all();
        $by = $request->get('by') ? $request->get('by') : 'id';
        $order = $request->get('order') ? $request->get('order') : 'desc';
        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;

        $taskList = TaskModel::select('task.id', 'us.name', 'task.title', 'task.created_at', 'task.status', 'task.verified_at', 'task.bounty', 'tt.name as cname','enterprise_auth.company_name');
//                      ->where('task.status', '<=', '3');

        if ($request->get('task_title')) {
            $taskList = $taskList->where('task.title','like','%'.$request->get('task_title').'%');
        }
        if ($request->get('company_name')) {
            $taskList = $taskList->where('enterprise_auth.company_name','like','%'.e($request->get('company_name')).'%');
        }
        //状态筛选
        if ($request->get('status') && $request->get('status') != 0) {
            switch($request->get('status')){
                case 1:
                    $status = [3];
                    break;
                case 2:
                    $status = [2];
                    break;
                case 3:
                    $status = [0,1];
                    break;
                /* case 4:
                    $status = [9];
                    break;
                case 5:
                    $status = [10];
                    break;
                case 6:
                    $status = [11];
                    break; */
            }
            $taskList = $taskList->whereIn('task.status',$status);
        }
        //时间筛选
        if($request->get('time_type')){
            if($request->get('start')){
                $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
                $taskList = $taskList->where($request->get('time_type'),'>',$start);
            }
            if($request->get('end')){
                $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
                $taskList = $taskList->where($request->get('time_type'),'<',$end);
            }
        }
        $taskList = $taskList->orderBy($by, $order)
            ->leftJoin('users as us', 'us.id', '=', 'task.uid')
            ->leftJoin('task_type as tt', 'tt.id', '=', 'task.type_id')
            ->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
        ->paginate($paginate);
        $data = array(
            'task' => $taskList,
        );
        $data['tasks'] = $taskList->toArray();
        $data['merge'] = $search;

        return $this->theme->scope('manage.tasklist', $data)->render();
    }
    
    /**
     * 任务派单列表
     *
     * @param Request $request
     * @return mixed
     */
    public function getTaskDispatch(Request $request){
        
        $search = $request->all();
        $by = $request->get('by') ? $request->get('by') : 'id';
        $order = $request->get('order') ? $request->get('order') : 'desc';
        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;
        
        $taskList = TaskModel::select('task.id', 'us.name', 'task.title', 'task.created_at', 'task.status', 'task.verified_at', 'task.bounty_status', 'task.begin_at', 'task.end_at'
                                        ,'task.worker_num', 'task.delivery_count', 'enterprise_auth.company_name')
                                        ->where('task.worker_num', '>', 'task.delivery_count')
                                        ->where('task.status', 3)
                                        ->where('enterprise_auth.status', 1);
        
        if ($request->get('task_title')) {
            $taskList = $taskList->where('task.title','like','%'.$request->get('task_title').'%');
        }
        if ($request->get('username')) {
            $taskList = $taskList->where('us.name','like','%'.e($request->get('username')).'%');
        }
        if ($request->get('company_name')) {
            $taskList = $taskList->where('enterprise_auth.company_name','like','%'.e($request->get('company_name')).'%');
        }
        /* //状态筛选
        if ($request->get('status') && $request->get('status') != 0) {
            switch($request->get('status')){
                case 1:
                    $status = [0];
                    break;
                case 2:
                    $status = [1,2];
                    break;
                case 3:
                    $status = [3,4,5,6,7,8];
                    break;
                case 4:
                    $status = [9];
                    break;
                case 5:
                    $status = [10];
                    break;
                case 6:
                    $status = [11];
                    break;
            }
            $taskList = $taskList->whereIn('task.status',$status);
        } */
        //时间筛选
        if($request->get('time_type')){
            if($request->get('start')){
                $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
                $taskList = $taskList->where($request->get('time_type'),'>',$start);
            }
            if($request->get('end')){
                $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
                $taskList = $taskList->where($request->get('time_type'),'<',$end);
            }
            
        }
        $taskList = $taskList->orderBy($by, $order)
        ->leftJoin('users as us', 'us.id', '=', 'task.uid')
        ->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
        ->paginate($paginate);
        $listArr = $taskList->toArray();
        
        $data = array(
            'task' => $taskList,
            'listArr' => $listArr,
        );
        $data['merge'] = $search;
        
        return $this->theme->scope('manage.taskDispatch', $data)->render();
    }
    

    /**
     * 任务处理
     *
     * @param $id
     * @param $action
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function taskHandle($id, $action)
    {
        if (!$id) {
            return \CommonClass::showMessage('参数错误');
        }
        $id = intval($id);

        switch ($action) {
            //审核通过
            case 'pass':
                $status = 3;
                break;
            //审核失败
            case 'del':
                $status = 111;
                break; 
            case 'deny':
                $status = 1;
                break;
        }
        if($status==111){
			 $status = TaskModel::where('id',$id)->delete();
			 if ($status)
            return back();
		}
        //审核失败和成功 发送系统消息
        $task = TaskModel::where('id',$id)->first();
        $user = UserModel::where('id',$task['uid'])->first();
        $site_name = \CommonClass::getConfig('site_name');
        if($status==3)
        {
            $result = TaskModel::where('id', $id)->whereIn('status', [1,2])->update(array('status' => $status));
            if(!$result)
            {
                return redirect()->back()->with(['error'=>'操作失败！']);
            }
            $task_audit_failure = MessageTemplateModel::where('code_name','audit_success ')->where('is_open',1)->where('is_on_site',1)->first();
            if($task_audit_failure)
            {
                //发送系统消息
                $messageVariableArr = [
                    'username'=>$user['name'],
                    'website'=>$site_name,
                    'task_number'=>$task['id'],
                ];
                $message = MessageTemplateModel::sendMessage('audit_success',$messageVariableArr);
                $data = [
                    'message_title'=>$task_audit_failure['name'],
                    'code'=>'audit_success',
                    'message_content'=>$message,
                    'js_id'=>$user['id'],
                    'message_type'=>2,
                    'receive_time'=>date('Y-m-d H:i:s',time()),
                    'status'=>0,
                ];
                MessageReceiveModel::create($data);
            }
        }elseif($status==1)
        {
            $result = DB::transaction(function() use($id,$status,$task){
                 TaskModel::where('id', $id)->whereIn('status', [1,2])->update(array('status' => $status));
                //判断任务是否需要退款
                if($task['bounty_status']==1)
                {
                    UserDetailModel::where('uid',$task['uid'])->increment('balance',$task['bounty']);
                    //生成财务记录
                    $finance = [
                        'action'=>7,
                        'pay_type'=>1,
                        'cash'=>$task['bounty'],
                        'uid'=>$task['uid'],
                        'created_at'=>date('Y-m-d H:i:d',time()),
                        'updated_at'=>date('Y-m-d H:i:d',time())
                    ];
                    FinancialModel::create($finance);
                }
            });
            if(!is_null($result))
            {
                return redirect()->back()->with(['error'=>'操作失败！']);
            }
            $task_audit_failure = MessageTemplateModel::where('code_name','task_audit_failure ')->where('is_open',1)->where('is_on_site',1)->first();
            if($task_audit_failure)
            {
                //发送系统消息
                $messageVariableArr = [
                    'username'=>$user['name'],
                    'task_title'=>$site_name,
                    'website'=>$site_name,
                ];
                $message = MessageTemplateModel::sendMessage('task_audit_failure',$messageVariableArr);
                $data = [
                    'message_title'=>$task_audit_failure['name'],
                    'code'=>'task_audit_failure',
                    'message_content'=>$message,
                    'js_id'=>$user['id'],
                    'message_type'=>2,
                    'receive_time'=>date('Y-m-d H:i:s',time()),
                    'status'=>0,
                ];
                MessageReceiveModel::create($data);
            }

        }
        return redirect()->back()->with(['message'=>'操作成功！']);
    }


    /**
     * 任务批量处理
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function taskMultiHandle(Request $request)
    {
        if (!$request->get('ckb')) {
            return \CommonClass::adminShowMessage('参数错误');
        }
        switch ($request->get('action')) {
            case 'pass':
                $status = 3;
                break;
            case 'deny':
                $status = 1;
                break;
            case 'del':
                $status = 111;
                break;    
            default:
                $status = 3;
                break;
        }
		if($status==111){
			 $status = TaskModel::whereIn('id', $request->get('ckb'))->delete();
			 if ($status)
            return back();
		}
        $status = TaskModel::whereIn('id', $request->get('ckb'))->where('status', 2)->update(array('status' => $status));
        if ($status)
            return back();

    }
    
	/**
     * 后台任务详情信息
     */
	public function getTaskInfo($id){
		$task=TaskModel::where('id',$id)->first();
        if(!$task)
        {
            return redirect()->back()->with(['error'=>'当前任务不存在，无法查看稿件！']);
        }
        $query = TaskModel::select('task.*', 'ea.company_name as nickname', 'ud.avatar','ud.qq')->where('task.id', $id);
        $taskDetail = $query->join('user_detail as ud', 'ud.uid', '=', 'task.uid')
            ->leftjoin('enterprise_auth as ea','ea.uid','=','task.uid')
            ->first()->toArray();
        if(!$taskDetail)
        {
             return redirect()->back()->with(['error'=>'当前任务已经被删除！']);
        }
        $task_attachment = TaskAttachmentModel::select('task_attachment.*', 'at.url','at.status as atstatus','at.name as atname','at.created_at as atcreated')->where('task_id', $id)
            ->leftjoin('attachment as at', 'at.id', '=', 'task_attachment.attachment_id')->get()->toArray();
        $myTags = TaskTagsModel::getTagsByTaskId($id);
        $typename=TaskTypeModel::getTaskTypeid($taskDetail['type_id']);
        $taskDetail['typename']=$typename['name'];   //任务主类别
        $subtypename=TaskTypeModel::getTaskTypeid($taskDetail['sub_type_id']);
        $taskDetail['subtypename']=$subtypename['name'];   //任务主类别
        $works = WorkModel::findAll2($id);
        
        if($works&&count($works)>0){
            foreach($works as $k => &$v){
                $v['skills'] = UserTagsModel::getTagsByUserId($v['uid']);
                
                if($v['id']&&$v['status']>=2){
                    $w_attatchment_ids = WorkAttachmentModel::findById($v['id']);
                    $w_attatchment_ids = array_flatten($w_attatchment_ids);
                    $v['attachments'] = AttachmentModel::whereIn('id',$w_attatchment_ids)->get();
                }
                
                $v['comments'] = WorkCommentModel::where('work_id',$v['id'])->where('pid',0)->with('childrenComment')->get()->toArray();
            }
        }
        $data = [
            'task' => $taskDetail,
            'taskAttachment' => $task_attachment,
            'myTags'=>$myTags,
            'works' =>$works,
        ]; 
        return $this->theme->scope("manage.taskinfo",$data)->render();
	}
    /**
     * 任务详情
     * @param $id
     */
    public function taskDetail($id)
    {
        $task=TaskModel::where('id',$id)->first();
        if(!$task)
        {
            return redirect()->back()->with(['error'=>'当前任务不存在，无法查看稿件！']);
        }
        $query = TaskModel::select('task.*', 'us.name as nickname', 'ud.avatar','ud.qq')->where('task.id', $id);
        $taskDetail = $query->join('user_detail as ud', 'ud.uid', '=', 'task.uid')
            ->leftjoin('users as us','us.id','=','task.uid')
            ->first()->toArray();
        if(!$taskDetail)
        {
             return redirect()->back()->with(['error'=>'当前任务已经被删除！']);
        }
        $status = [
            0 => '暂不发布',
            1 => '已经发布',
            2 => '赏金托管',
            3 => '审核通过',
            4 => '威客交稿',
            5 => '雇主选稿',
            6 => '任务公示',
            7 => '交付验收',
            8 => '双方互评',
            9 => '任务完成',
            10=> '失败',
            11=> '维权'
        ];
        $taskDetail['status_text'] = $status[$taskDetail['status']];

        //任务类型
        $taskType = TaskTypeModel::all();
        //任务中标人数
        $taskDelivery = WorkModel::where('task_id', $id)->where('status', 3)->count();
        //任务附件
        $task_attachment = TaskAttachmentModel::select('task_attachment.*', 'at.url')->where('task_id', $id)
            ->leftjoin('attachment as at', 'at.id', '=', 'task_attachment.attachment_id')->get()->toArray();
        //查询seo数据
        $task_seo = TaskExtraSeoModel::where('task_id', $id)->first();
        //任务稿件
        $works = WorkModel::select('work.*','us.name as nickname','ud.avatar')
            ->where('work.status','<=',1)
            ->where('work.task_id',$id)
            ->with('childrenAttachment')
            ->leftjoin('user_detail as ud','ud.uid','=','work.uid')
            ->leftjoin('users as us','us.id','=','work.uid')
            ->get()->toArray();
        //任务留言
        $task_massages = WorkCommentModel::select('work_comments.*','us.name as nickname','ud.avatar')
                ->leftjoin('user_detail as ud','ud.uid','=','work_comments.uid')
                ->leftjoin('users as us','us.id','=','work_comments.uid')
                ->where('work_comments.task_id',$id)->paginate();
        //任务交付
        $work_delivery = WorkModel::select('work.*','us.name as nickname','ud.mobile','ud.qq','ud.avatar')
            ->whereIn('work.status',[2,3])
            ->where('work.task_id',$id)
            ->with('childrenAttachment')
            ->leftjoin('user_detail as ud','ud.uid','=','work.uid')
            ->leftjoin('users as us','us.id','=','work.uid')
            ->get()->toArray();

        $domain = \CommonClass::getDomain();

        $data = [
            'task' => $taskDetail,
            'domain' => $domain,
            'taskType' => $taskType,
            'taskDelivery' => $taskDelivery,
            'taskAttachment' => $task_attachment,
            'task_seo' => $task_seo,
            'works'=>$works,
            'task_massages'=>$task_massages,
            'work_delivery'=>$work_delivery
        ];
        return $this->theme->scope('manage.taskdetail', $data)->render();
    }
    
    public function taskDetail2($id){
        
        $data = $this->getTaskDetail($id);
        
        $data['type'] = 'detail';
        
        return $this->theme->scope('manage.taskdetail2', $data)->render();
    }
    
    public function taskDetail3($id){
        
        $work = WorkModel::select('work.id as w_id','work.task_id as w_task_id', 'work.desc as w_desc', 'work.status as w_status', 'work.uid as w_uid', 'work.created_at as w_created_at', 'work.delivered_at as w_delivered_at', 
                        'work.checked_at as w_checked_at','work.settle_at as w_settle_at','work.end_at as w_end_at','work.reject_at as w_reject_at','users.mobile as w_mobile',
                        'work.delivered_at as w_delivered_at', 'work.payment as w_payment','enterprise_auth.company_name','mt.name as mt_type_name','st.name as st_type_name','realname_auth.realname','realname_auth.card_number as w_card_number',
                        'task.title','task.begin_at','task.end_at','task.desc','province.name as province_name','city.name as city_name','area.name as area_name','task.address','task.status as t_status','task.bounty')->where('work.id', $id);
        
        $work = $work->leftJoin('users', 'users.id', '=', 'work.uid')
                    ->leftJoin('task', 'task.id', '=', 'work.task_id')
                    ->leftJoin('task_type as mt', 'task.type_id', '=', 'mt.id')
                    ->leftJoin('task_type as st', 'task.sub_type_id', '=', 'st.id')
                    ->leftjoin('district as province','province.id','=','task.province')
                    ->leftjoin('district as city','city.id','=','task.city')
                    ->leftjoin('district as area','area.id','=','task.area')
                    ->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
                    ->leftJoin('realname_auth', 'realname_auth.uid', '=', 'work.uid')->first();
        
        //查询任务技能标签
        $t_tags = TaskTagsModel::getTagsByTaskId($work->w_task_id);
        //查询任务的附件
        $t_attatchment_ids = TaskAttachmentModel::where('task_id','=',$work->w_task_id)->lists('attachment_id')->toArray();
        $t_attatchment_ids = array_flatten($t_attatchment_ids);
        $taskAttatchment = AttachmentModel::whereIn('id',$t_attatchment_ids)->get();            
        
        //查询接单人技能标签
        $w_tags = UserTagsModel::getTagsByUserId($work->w_uid);        
        
        $w_attatchment_ids = WorkAttachmentModel::findById($id);
        $w_attatchment_ids = array_flatten($w_attatchment_ids);
        $workkAttatchment = AttachmentModel::whereIn('id',$w_attatchment_ids)->get();

        $workComments = WorkCommentModel::where('work_id',$id)->where('pid',0)->with('childrenComment')->get()->toArray();
        
        $data = [
            'work' => $work,
            't_tag' => $t_tags,
            'w_tag' => $w_tags,
            't_attachment' => $taskAttatchment,
            'w_attachment' => $workkAttatchment,
            'w_comment' => $workComments,
        ];
        
        //dump($data);exit;
        
        return $this->theme->scope('manage.taskdetail3', $data)->render();
    }
    
    public function createTaskDispatch($id){
        $data = $this->getTaskDetail($id);
        
        $tags = $data['tags']; 
        $tagIds = array();
        foreach($tags as $k => $v){
            array_push($tagIds, $v['tag_id']);
        }
        
        $uids = UserTagsModel::getUsersByTagId($tagIds);
        $uids = array_flatten($uids);
        
        $exist = array();
        foreach($data['works'] as $v){
            array_push($exist, $v['uid']);
        }
        $uids = array_diff($uids, $exist);
        
        $users = UserModel::getUsersById($uids);
        
        $usersInfo = array();
        foreach($users as $k => $v){
            array_push($usersInfo, ['uid'=>$v->id,'realname'=>$v->realname,'card_number'=>$v->card_number,'mobile'=>$v->mobile]);
        }
        
        //判断当前的任务的入围人数是否用完
        $worker_num = TaskModel::where('id',$id)->lists('worker_num');
        $worker_num = $worker_num[0];
        //当前任务的入围人数统计
        $win_bid_num = WorkModel::where('task_id',$id)->where('status','>=',1)->count();
        
        
        $data['type'] = 'dispatch';
        $data['users'] = $usersInfo;
        $data['rest_worker'] = ($worker_num >= $win_bid_num)?($worker_num - $win_bid_num) : 0;
        
        return $this->theme->scope('manage.taskdetail2', $data)->render();
    }
    
    public function createNewTaskDispatch(Request $request){
        $param = $request->except('_token');
        
        if(!isset($param['task_id'])){
            return redirect()->back()->with(['error'=>'缺少必要参数！']);
        }
        
        if(!isset($param['users_id'])){
            return redirect()->back()->with(['error'=>'请选择接单人员！']);
        }
        
        $msg = array();
        
        //创建一个新的稿件
        $workModel = new WorkModel();
        $userIds = explode(',', $param['users_id']);
        foreach($userIds as $k => $v){
            
            //判断当前的任务的入围人数是否用完
            $worker_num = TaskModel::where('id',$param['task_id'])->lists('worker_num');
            $worker_num = $worker_num[0];
            //当前任务的入围人数统计
            $win_bid_num = WorkModel::where('task_id',$param['task_id'])->where('status','>=',1)->count();
            
            if($worker_num<=$win_bid_num){
                $msg['message'] .= '任务接单人数已满！';
                break;
            }           
            
            $data = [
                'task_id' => $param['task_id'],
                'uid' => $v,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s',time()),
            ];
            
            $status = $workModel->workCreate($data);
            
            if(!$status){
                $msg['message'] .= '第'.$k.'人接单失败！';
                continue;
            }
            
            if(($win_bid_num+1)== $worker_num)
            {
                //派单人数已满，任务状态变成进行中
                TaskModel::where('id',$param['task_id'])->update(['status'=>4,'updated_at'=>date('Y-m-d H:i:s',time())]);
            }
        }
        
        if(empty($msg)) $msg['message'] = '派单成功！';
        return redirect()->back()->with($msg);
        
    }
    
    private function getTaskDetail($id){
        
        //查询任务详情
        $detail = TaskModel::detail($id);
        
        //查询任务的附件
        $attatchment_ids = TaskAttachmentModel::where('task_id','=',$id)->lists('attachment_id')->toArray();
        $attatchment_ids = array_flatten($attatchment_ids);
        $attatchment = AttachmentModel::whereIn('id',$attatchment_ids)->get();
        
        //查询任务技能标签
        $tags = TaskTagsModel::getTagsByTaskId($id);
        
        //企业查询投稿记录
        $works = WorkModel::findAll2($id);
        
        if($works&&count($works)>0){
            foreach($works as $k => &$v){
                $v['skills'] = UserTagsModel::getTagsByUserId($v['uid']);
                
                if($v['id']&&$v['status']>=2){
                    $w_attatchment_ids = WorkAttachmentModel::findById($v['id']);
                    $w_attatchment_ids = array_flatten($w_attatchment_ids);
                    $v['attachments'] = AttachmentModel::whereIn('id',$w_attatchment_ids)->get();
                }
                
                $v['comments'] = WorkCommentModel::where('work_id',$v['id'])->where('pid',0)->with('childrenComment')->get()->toArray();
            }
            
        }
        
        
        $data = [
            'task'=>$detail,
            'taskAttachment'=>$attatchment,
            'tags' => $tags,
            'works' => $works,
        ];
        
        return $data;
    }

    /**
     * 任务详情提交
     * @param Request $request
     */
    public function taskDetailUpdate(Request $request)
    {
        $data = $request->except('_token');
        $task_extra = [
            'task_id'=>intval($data['task_id']),
            'seo_title'=>$data['seo_title'],
            'seo_keyword'=>$data['seo_keyword'],
            'seo_content'=>$data['seo_content'],
        ];
        $result = TaskExtraSeoModel::firstOrCreate(['task_id'=>$data['task_id']])
            ->where('task_id',$data['task_id'])
            ->update($task_extra);
        //修改任务数据
        $task = [
            'title'=>$data['title'],
            'desc'=>$data['desc'],
            'phone'=>$data['phone']
        ];
        //修改任务数据
        $task_result = TaskModel::where('id',$data['task_id'])->update($task);

        if(!$result || !$task_result)
        {
            return redirect()->back()->with(['error'=>'更新失败！']);
        }

        return redirect()->back()->with(['massage'=>'更新成功！']);
    }

    /**
     * 删除任务留言
     */
    public function taskMassageDelete($id)
    {
        $result = WorkCommentModel::destroy($id);

        if(!$result)
        {
            return redirect()->to('/manage/taskList')->with(['error'=>'留言删除失败！']);
        }
        return redirect()->to('/manage/taskList')->with(['massage'=>'留言删除成功！']);
    }
    
    //任务派单模板下载
    public function postTaskDispatchDownload(Request $request){
        $task = $request->get('chk');
        
        if(!isset($task)||empty($task)){
            return redirect()->to('/manage/taskDispatch')->with(['massage'=>'缺少必要参数！']);
        }
        
        $tasks = explode(",", $task);
        
        $tasksDetail = array();
        foreach($tasks as $k => $v){
            $detail = TaskModel::detail2($v);
            
            if(!$detail) continue;
            
            //查询任务技能标签
            $tags = TaskTagsModel::getTagsByTaskId($v);
            $skills = '';
            foreach($tags as $k1 => $v1){
                $skills .= $v1['tag_name'].',';
            }
                        
            $taskDetail = [
                'id' => $detail->id,
                'title' => $detail->title,
                'company_name' => $detail->company_name,
                'type_name' => $detail->type_name,
                'sub_type_name' => $detail->sub_type_name,
                'begin_at' => $detail->begin_at,
                'end_at' => $detail->end_at,
                'city' => $detail->province_name.$detail->city_name,
                'address' => $detail->address,
                'skills' => $skills?substr($skills,0,-1):'',
                'bounty' => [$detail->bounty, 'n00'],
                'worker_num' => [$detail->worker_num, 'n'],
                'desc' => $detail->desc,
                'created_at' => $detail->created_at,
                'realname' => '',
                'card_number' => '',
                'mobile' => '',
                'account' => '',
                'card_front_side' => '',
                'card_back_side' => '',
            ];            
            
            array_push($tasksDetail, $taskDetail);
        }
        
        $title = ['任务ID',['任务名称',NULL,'50'],['发布企业',NULL,'50'],'任务主类别','任务子类别','服务起始日期','服务截止日期','任务城市','服务地址',['技能要求',NULL,50],'任务预算','任务人数','任务描述','发布时间','姓名','身份证号码','手机号','银行卡号','身份证正面','身份证反面'];
        //dump($tasksDetail);       
        
        $param = [
            'sheetName' => '任务派单',
            'title' => $title,
            'data' => $tasksDetail,
            'fileName' => 'template_taskDispatch_'.time(),
            'savePath' => './',
            'isDown' => true
        ];
        
        $this->exportExcel($param);
        
        //$this->exportExcel($title, $tasksDetail,'任务派单', 'template_taskDispatch_'.time(), './', true);
        
        return redirect()->to('manage/taskDispatch');        
    }
    
    //任务派单导入视图
    public function getTaskDispatchImport(){
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $data = [
            'filesize' => $attachmentConfig['attachment']['size']
        ];
        
        return $this->theme->scope('manage.taskDispatchImport', $data)->render();
    }
    
    //提交任务派单导入数据
    public function postTaskDispatchImport(Request $request){
        $data = $this->fileImport($request->file('taskdispatchfile'));
        
        if(isset($data['fail'])&&$data['fail']){
            return back()->with(['error' => $data['errMsg']]);
        }
        
        //dump($data);
        
        $tasksDispatch = array();        
        foreach($data as $k => &$v){
            if($k === 0 || empty($v[0])) continue;
            
            $v['num'] = $k;
            $v[15] = strval($v[15]);
            $v[16] = strval($v[16]);
            $v[17] = strval($v[17]);
            $v[0] = intval($v[0]);
            
            if(empty($v[16])){
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'接单人手机不能为空！']);
                continue;
            }
            
            $uid = UserModel::getUserIdByMobileAndIDCard($v[16], $v[15]);
            
            if(!$uid||empty($uid)){
                
                $status = UserModel::where('mobile', $v[16])->first();
                if($status){
                    $v['msg'] = '新接单人的手机已经被注册！';
                    array_push($tasksDispatch, $v);
                    continue;
                }
                
                if(empty($v[14])||empty($v[15])||empty($v[18])||empty($v[19])){
                    array_push($tasksDispatch, ['num'=>$k, 'msg'=>'新接单人必须同时提供姓名，身份证号以及身份证正反面照片！']);
                    continue;
                }
                $salt = \CommonClass::random(4);
                $param = [
                    'name' => $v[16],
                    'email' => $this->genEMail($v[16]),
                    'realname' => $v[14],
                    'card_number' => $v[15],
                    'email_status' => 2,
                    'status' => 1,
                    'type' => 1,
                    'mobile' => $v[16],
                    'account' => $v[17],
                    'password' => UserModel::encryptPassword($v[16], $salt),
                    'salt' => $salt,
                    'card_front_side' => $v[18],
                    'card_back_dside' => $v[19],
                    'astatus' => 1,
                ];
                $status = UserModel::addUser($param); 
                
                if(!$status){
                    array_push($tasksDispatch, ['num'=>$k, 'msg'=>'新接单人创建失败！']);
                    continue;
                }
                
                $uid = UserModel::getUserIdByMobileAndIDCard($v[16], $v[15]);
                if(!$uid){
                    array_push($tasksDispatch, ['num'=>$k, 'msg'=>'新接单人创建失败！']);
                    continue;
                }
            }
            
            //检查是否已经报名
            $existWork = WorkModel::select('work.status')->where('uid', $uid->id)->where('task_id', $v[0])->first();
            
            if(!empty($existWork)&&$existWork->status>0){
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'该接单人已中标！']);
                continue;
            }
            
            //判断当前的任务的入围人数是否用完
            $worker_num = TaskModel::where('id',$v[0])->lists('worker_num');
            $worker_num = $worker_num[0];
            //当前任务的入围人数统计
            $win_bid_num = WorkModel::where('task_id',$v[0])->where('status','>=',1)->count();
            
            if($worker_num<=$win_bid_num){
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'任务接单人数已满！']);
                continue;
            }
            
            
            if(!empty($existWork)&&$existWork->status===0){
                $status = WorkModel::where('uid', $uid->id)->where('task_id', $v[0])->update(['status'=>1, 'bid_at' => date('Y-m-d H:i:s',time())]);
            }else{
                $param1 = [
                    'task_id' => $v[0],
                    'uid' => $uid->id,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'bid_at' => date('Y-m-d H:i:s',time()),
                ];
                
                //创建一个新的稿件
                $workModel = new WorkModel();
                $status = $workModel->workCreate($param1);
            }
            
            if(!$status){
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'接单失败！']);
                continue;
            }else{
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'接单成功！']);
            }
            
            if(($win_bid_num+1)== $worker_num)
            {                
                //派单人数已满，任务状态变成进行中
                TaskModel::where('id',$v[0])->update(['status'=>4,'updated_at'=>date('Y-m-d H:i:s',time())]);
            }            
        }
        
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $result = [
            'filesize' => $attachmentConfig['attachment']['size'],
            'tasksDispatch' => $tasksDispatch,
        ];
        //dump($result);
        return $this->theme->scope('manage.taskDispatchImport', $result)->render();
    }
    //进入导入视图
    public function getTaskListImport(){
    	return $this->theme->scope('manage.taskListImport')->render();
    }
    //提交任务导入数据
    public function postTaskExcel(Request $request){
    	
        $data = $this->fileImport($request->file('tasklistexcel'));
        
//      dump($data);exit;
        
        //dump($data);
           $tasksCheck=array();
        foreach($data as $k => $v){
//      	$file = $request->file('file');
	    	//将文件上传的数据存入到attachment表中
	    	$v['num'] = $k;
            
            if($k === 0 || empty($v[0])) continue;
            	
        		$autouid=EnterpriseAuthModel::getEnterpriseName($v[0]);//获取企业uid
        		if($autouid==0){
        			$v['msg'] = '没有该企业！';
	                array_push($tasksCheck, $v);
	                continue;
//      			return redirect()->back()->with(['error'=>'没有该企业：'.$v[0].'，导入中断。']);
        		}
        		$checkuid=EnterpriseAuthModel::getcheckName($autouid);//获取企业uid
        		if($checkuid==0){
        			$v['msg'] = '该企业还没有认证！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		if(empty($v[1])){
        			$v['msg'] = '任务名称不能为空！';
	                array_push($tasksCheck, $v);
	                continue;
//      			return redirect()->back()->with(['error'=>$v[0].'行，没有任务名称，导入中断。']);
        		}
        		$type_id=0;//任务主类型
        		if(empty($v[2])){
        			$v['msg'] = '请选择任务主类型';
	                array_push($tasksCheck, $v);
	                continue;
//      			return redirect()->back()->with(['error'=>$v[0].'行，没有选择任务主类型，导入中断。']);
        		}else{
        			$type_id=TaskTypeModel::getTaskTypeName($v[2]);
        			if($type_id==0){
        				$v['msg'] = '主类型错误';
		                array_push($tasksCheck, $v);
		                continue;
//      				return redirect()->back()->with(['error'=>$v[0].'行，主类型错误，导入中断。']);
        			}
        		}
        		$sub_type_id=0;//任务子类型
        		if(!empty($v[3])){
        			$aInfos = explode('-', $v[3]);
                	$sub_type_id = $aInfos[0];
        		}else{
        			$v['msg'] = '请选择子类型';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		
        		if(empty($v[4])){
        			$v['msg'] = '开始日期不能为空！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		
        		if(empty($v[5])){
        			$v['msg'] = '结束日期不能为空！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		if(strtotime($v[4])>strtotime($v[5])){
        			$v['msg'] = '结束时间不能比开始时间早！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		$province=0;//省份
        		if(!empty($v[6])){
        			$province=DistrictModel::getDistrictNames($v[6]);
        		}else{
        			$v['msg'] = '请选择省份！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		$city=0;//城市
        		if(!empty($v[7])){
        			$city=DistrictModel::getDistrictNames($v[7]);
        		}else{
        			$v['msg'] = '请选择城市！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		if(empty(floatval($v[8]))){
        			$v['msg'] = '请输入任务预算！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		if(empty($v[9])){
        			$v['msg'] = '请输入任务人数！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		if(empty($v[10])){
        			$v['msg'] = '请输入任务描述！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		if(empty($v[14])){
        			$v['msg'] = '请输入服务地址！';
	                array_push($tasksCheck, $v);
	                continue;
        		}
        		//------------------------------
//      		$attresult;
				$fileid_arr=array();
//      		if(!empty($v[11])){
//      			$attachment_data['name'] = basename($v[11]);
//			        $attachment_data['status']=1;
//			        $type = explode('.', $v[11]);
//			        $attachment_data['type']=$type[1];
//			        $attachment_data['url']=$v[11];
//			        $attachment_data['created_at'] = date('Y-m-d H:i:s', time());
//			        $attachment_data['user_id']=$autouid;
//			        //将记录写入到attchement表中
//			        $attresult = AttachmentModel::create($attachment_data);
//			        if($attresult){
//				        	array_push($fileid_arr,$attresult['id']);
//				        }
//      		}
        		for($i=11;$i<=13;$i++){
        			if(!empty($v[$i])){
	        			$attachment_data['name'] = basename($v[$i]);
				        $attachment_data['status']=1;
				        $type = explode('.', $v[$i]);
				        $attachment_data['type']=$type[1];
				        $attachment_data['url']=$v[$i];
				        $attachment_data['created_at'] = date('Y-m-d H:i:s', time());
				        $attachment_data['user_id']=$autouid;
				        //将记录写入到attchement表中
				        $attresult = AttachmentModel::create($attachment_data);
				        if($attresult){
				        	array_push($fileid_arr,$attresult['id']);
				        }
        			}
        		}
                $salt = \CommonClass::random(4);
                $skill=0;
				$isskill=false;
				
				for ($x=16; $x<=34; $x++) {
                if($x!=19 && $x!=23 && $x!=27 && $x!=31){
					  if(!empty($v[$x]) && !$isskill){
					  	$isskinfo = explode('-', $v[$x]);
					  	$skill=$isskinfo[0];
					  	$isskill=true;
					  }
					  if(!empty($v[$x]) && $isskill){
					  	$isskinfo = explode('-', $v[$x]);
					  	$skill=$skill.','.$isskinfo[0];
					  }
				  }
				}
//              for ($x=14; $x<=32; $x++) {
//              if($x!=17 && $x!=21 && $x!=25 && $x!=29){
//					  if(!empty($v[$x]) && !$isskill){
//					  	$isskinfo = explode('-', $v[$x]);
//					  	$skill=$isskinfo[0];
//					  	$isskill=true;
//					  }
//					  if(!empty($v[$x]) && $isskill){
//					  	$isskinfo = explode('-', $v[$x]);
//					  	$skill=$skill.','.$isskinfo[0];
//					  }
//				  }
//				} 
//				dump($skill);exit;
                $param = [
                    'uid' => $autouid,   //企业id
                    'type_id'=>$type_id,  //主类型id
                    'sub_type_id'=>$sub_type_id,   //子类型id
                    'title' => $v[1],      //任务名称
                    'begin_at' => date('Y-m-d H:i:s', strtotime($v[4])),  //开始时间
                    'end_at' => date('Y-m-d H:i:s', strtotime($v[5])),    //结束时间
                    'province' => $province,         //省份
                    'city' => $city,             //城市
                    'area' => 0,
                    'bounty' => floatval($v[8]),
                    'worker_num' => $v[9],
                    'desc' => $v[10],
//                  'file_id'=>[$result['id']],
                    'address' => $v[14],
//                  'skill'=>$skill,
                    'status' => 2,
                    'bounty_status' => 1,
                    
                ];
                $v[8]=floatval($v[8]);
                if(!empty($skill)){
                	 $param['skill']= $skill;
                }
                if(!empty($fileid_arr)){
                	$param['file_id']=$fileid_arr;
                }
//              if(!empty($attresult)){
//              	$param['file_id']=[$attresult['id']];
//              }
                
                 $taskModel = new TaskModel();
       		 	 $result = $taskModel->createTask($param);
       		 	 if($result){
       		 	 	$v['msg'] = '添加任务成功！';
	                array_push($tasksCheck, $v);
       		 	 }else{
       		 	 	$v['msg'] = '添加任务失败！';
	                array_push($tasksCheck, $v);
       		 	 }
        }
        $result = [
            'tasksCheck' => $tasksCheck,
        ];
        //dump($result);
        return $this->theme->scope('manage.taskListImport', $result)->render();
//      return redirect('manage/taskList')->with(['message' => '导入成功']);
    }
    
    /**
     * 任务验收列表
     *
     * @param Request $request
     * @return mixed
     */
    public function getTaskCheck(Request $request){
        
        $search = $request->all();
        /* $by = $request->get('by') ? $request->get('by') : 'work.id';
        $order = $request->get('order') ? $request->get('order') : 'desc'; */
        $by = $request->get('by') ? $request->get('by') : 'work.status';
        $order = $request->get('order') ? $request->get('order') : 'asc';
        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;
        
        
        
        $worklist = WorkModel::select('work.id as w_id','work.task_id as w_task_id', 'work.desc as w_desc', 'work.status as w_status', 'work.uid as w_uid', 'work.created_at as w_created_at', 
                                        'work.delivered_at as w_delivered_at', 'work.payment as w_payment','enterprise_auth.company_name','task_type.name as t_type_name','realname_auth.realname',
                                        'task.title')
                            ->where('work.status', '>=', '1');
            
            if ($request->get('task_title')) {
                $worklist = $worklist->where('task.title','like','%'.$request->get('task_title').'%');
            }
            if ($request->get('username')) {
                $worklist = $worklist->where('realname_auth.realname','like','%'.e($request->get('username')).'%');
            }
            if ($request->get('company_name')) {
                $worklist = $worklist->where('enterprise_auth.company_name','like','%'.e($request->get('company_name')).'%');
            }
            //状态筛选
             if ($request->get('status') && $request->get('status') != 0) {
                 switch($request->get('status')){
                     case 1:
                        $status = [3];
                        break;
                     case 2:
                         $status = [4];
                         break;
                     case 3:
                         $status = [2];
                         break;
                     case 4:
                         $status = [6];
                         break;                     
                 }
                 $worklist = $worklist->whereIn('work.status',$status);
             }
             
             
            //时间筛选
            if($request->get('time_type')){
                if($request->get('start')){
                    $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
                    $worklist = $worklist->where($request->get('time_type'),'>',$start);
                }
                if($request->get('end')){
                    $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
                    $worklist = $worklist->where($request->get('time_type'),'<',$end);
                }
                
            }            
            
            $worklist = $worklist->orderBy($by, $order)
                            ->leftJoin('users', 'users.id', '=', 'work.uid')
                            ->leftJoin('task', 'task.id', '=', 'work.task_id')
                            ->leftJoin('task_type', 'task.type_id', '=', 'task_type.id')
                            ->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
                            ->leftJoin('realname_auth', 'realname_auth.uid', '=', 'work.uid')
                            ->paginate($paginate);
           $listArr = $worklist->toArray();
            
            $data = array(
                'work' => $worklist,
                'listArr' => $listArr,
            );
            $data['merge'] = $search;
            
            return $this->theme->scope('manage.taskCheck', $data)->render();
    }
    
    /**
     * 任务批量终止
     */
    public function postTaskCheckEndAll(Request $request){
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/taskCheck')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                WorkModel::where('id', $id)->update(['status' => 5,'end_at'=>date('Y-m-d H:i:d',time())]);
            }
        });
            if(is_null($status))
            {
                return redirect()->to('manage/taskCheck')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/taskCheck')->with(array('message' => '操作失败'));
    }
    
    /**
     * 任务批量驳回
     */
    public function postTaskCheckRejectAll(Request $request){
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/taskCheck')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $reject = array();
        foreach ($data['chk'] as $id) {
            $result = WorkModel::where('id', $id)->where('status', 2)->count();
            if($result>0){
                array_push($reject, $id);
            }
        }
        
        $rest = array_diff($data['chk'], $reject);
        
        $status = DB::transaction(function () use ($reject) {
            WorkModel::whereIn('id', $reject)->update(['status' => 4, 'reject_at' => date('Y-m-d H:i:d',time())]);
        });
            if(is_null($status))
            {
                if(empty($rest)){
                    return redirect()->to('manage/taskCheck')->with(array('message' => '操作成功'));                    
                }else{
                    return redirect()->to('manage/taskCheck')->with(array('message' => '编号为：'.implode(',', $rest).' 的任务驳回失败！'));
                }
                
            }
            return  redirect()->to('manage/taskCheck')->with(array('message' => '操作失败'));
    }
    
    public function taskCheckHandle($id, $action){
        if (!$id) {
            return  redirect()->to('manage/taskCheck')->with(array('error' => '参数错误'));
        }
        $id = intval($id);
        
        switch ($action) {
            case 'pass':
                $wstatus = 3;
                break;
            case 'reject':
                $wstatus = 4;
                break;
            case 'end'://接单人任务为终止
                $wstatus = 6;
                break;
        }
        
        if($wstatus===3){
            $task_id = WorkModel::select('task_id')->where('id',$id)->first();
            //查询任务需要的人数
            $worker_num = TaskModel::where('id',$task_id['task_id'])->first();
            $worker_num = $worker_num['worker_num'];
            //任务验收通过人数
            $win_check = WorkModel::where('work.task_id',$task_id['task_id'])->whereIn('status',[3,5])->count();
            
            if(($win_check>=$worker_num)){
                return redirect()->to('manage/taskCheck')->with(array('error' => '不能再接受新的验收！'));
            } 
        }        
        
        $param = [
            'id' => $id,
            'task_id' => isset($task_id)&&isset($task_id['task_id'])?$task_id['task_id']:'',
            'wstatus' => $wstatus,
            'worker_num' => isset($worker_num)?$worker_num:'',
            'win_check' => isset($win_check)?$win_check:'',
        ];
        
        $status = DB::transaction(function () use ($param) {
            $update = ['status' => $param['wstatus']];
            if($param['wstatus']===3){
                $update['checked_at'] = date('Y-m-d H:i:s',time());
            }else if($param['wstatus']===4){
                $update['reject_at'] = date('Y-m-d H:i:s',time());
            }else if($param['wstatus']===6){
                $update['end_at'] = date('Y-m-d H:i:s',time());
            }
            WorkModel::where('id', $param['id'])->update($update);
            
            if($param['wstatus']===3&&($param['win_check']+1)==$param['worker_num'])
            {
                TaskModel::where('id',$param['task_id'])->update(['status'=>8,'comment_at'=>date('Y-m-d H:i:s',time()),'updated_at'=>date('Y-m-d H:i:s',time())]);
            }
        });
		
        if(is_null($status))
        {
            return redirect()->to('manage/taskCheck')->with(array('message' => '操作成功'));
        }
        return  redirect()->to('manage/taskCheck')->with(array('message' => '操作失败'));
    }
    
    //任务验收模板下载
    public function postTaskCheckDownload(Request $request){
        $work = $request->get('chk');
        
        if(!isset($work)||empty($work)){
            return  redirect()->to('manage/taskCheck')->with(array('message' => '下载失败'));
        }
        
        $works = explode(",", $work);        
        $task_ids = WorkModel::select('task_id')->whereIn('id',$works)->groupby('task_id')->get()->toArray();   
        
        if(!$task_ids||empty($task_ids)) {
            return redirect()->to('/manage/taskCheck')->with(['massage'=>'验收任务不存在！']);
        };
        
        $tasksDetail = array();        
        foreach($task_ids as $k => $v){
            
            $task_detail = TaskModel::detail3($v['task_id']);
            
            //查询任务技能标签
            $tags = TaskTagsModel::getTagsByTaskId($v['task_id']);
            $skills = '';
            foreach($tags as $k1 => $v1){
                $skills .= $v1['tag_name'].',';
            }
            
            foreach($task_detail as $v1){
                unset($taskDetail);
                $taskDetail = [
                    'id' => $v1->id,
                    'title' => $v1->title,
                    'type_name' => $v1->type_name,
                    'sub_type_name' => $v1->sub_type_name,
                    'begin_at' => $v1->begin_at,
                    'end_at' => $v1->end_at,
                    'city' => $v1->province_name.$v1->city_name,
                    'address' => $v1->address,
                    'skills' => $skills?substr($skills,0,-1):'',
                    'bounty' => $v1->bounty,
                    'worker_num' => [$v1->worker_num, 'n', '9BC2E6'],
                    'desc' => $v1->desc,
                    'attachment' => '',
                    'created_at' => $v1->created_at,
                    'worker_name' => [$v1->w_realname, 'n', '9BC2E6'],
                    'worker_card_number' => [$v1->w_card_number, 'n', '9BC2E6'],
                    'worker_mobile' => [$v1->w_mobile, 'n', '9BC2E6'],
                    'worker_account' => [$v1->w_account, 'n', '9BC2E6'],
                    'worker_front_id_side' => ['', 'n', '9BC2E6'],
                    'worker_back_id_side' => ['', 'n', '9BC2E6'],
                    'check_status' => [['1-通过','2-驳回','3-任务终止'],'','FFE699'],
                    'checked_at' => ['','','FFE699'],
                    'payment' => ['', 'n00','FFE699'],
                    'comment' => ['','','FFE699'],
                ];  
                
                array_push($tasksDetail, $taskDetail);
            }
        }
        //dump($tasksDetail);
        
        /* $template = $this->fileImport('',[],$_SERVER['DOCUMENT_ROOT'].'/attachment/sys/templates/template_taskys.xlsx');
        dump($template); */
        
        $tip="验收表格填写说明：
                        1、白底和蓝底的部分为系统导出模板时自带的信息，其中蓝底的部分可以修改，白底的部分不能修改，以免影响您的任务及付款，给您带来不必要的损失。
                        2、橙色底部的部分为验收需要填写的部分；
                        3、任务状态可选，分为1-通过，2-驳回，3-任务终止，驳回的状态是可以允许接单人继续提交验收的，任务终止则表示公司与该个人的业务强制停止，任务结束。
                        4、停止合作的个人信息不能删除，必须保留，验收状态选择终止任务；
                        5、如果该任务中途有人新增进来可以在表格里新增该个人的信息，以及验收情况；
                        6、评价及说明非必填。";
        
        $title = ['任务ID',['任务名称',NULL,50],'任务主类别','任务子类别','服务起始日期','服务截止日期','任务城市','服务地址',['技能要求',NULL,50],'任务预算',['任务人数','9BC2E6'],['任务描述',NULL,50],'任务图片','发布时间'
            ,['姓名','9BC2E6'],['身份证号码','9BC2E6'],['手机号','9BC2E6'],['银行卡号','9BC2E6'],['身份证正面','9BC2E6'],['身份证反面','9BC2E6']
            ,['验收状态(注：1-通过，2-驳回，3-任务终止)','FFE699'],['验收时间','FFE699'],['结算价格','FFE699'],['评价及说明','FFE699',50]];        
        
        
        
        $param = [
            'sheetName' => '任务验收及结算',
            'title' => $title,
            'data' => $tasksDetail,
            'fileName' => 'template_taskys_'.time(),
            'savePath' => './',
            'tip' => $tip,
            'isDown' => true
        ];
        
        $this->exportExcel($param);
        
        //$this->exportExcel($title, $tasksDetail,'任务验收及结算', 'template_taskys_'.time(), './', true, $tip);
        
        return redirect()->to('manage/taskCheck');        
        
    }
    
    //提交任务验收导入数据
    public function getTaskCheckImport(){
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $data = [
            'filesize' => $attachmentConfig['attachment']['size']
        ];
        
        return $this->theme->scope('manage.taskCheckImport', $data)->render();
    }
    
    //提交任务验收导入数据
    public function postTaskCheckImport(Request $request){
        $data = $this->fileImport($request->file('taskcheckfile'));
        
        if(isset($data['fail'])&&$data['fail']){
            return back()->with(['error' => $data['errMsg']]);
        }                
        
        $taskUpdate = array();
        foreach($data as $k => $v){
            if($k === 0 || $k === 1 || empty($v[0])) continue;            
            if (!array_key_exists(intval($v[0]),$taskUpdate)){
                $taskUpdate[$v[0]] = intval($v[10]);
            }
        }
        foreach($taskUpdate as $k => $v){
            TaskModel::where('id', $k)->update(['worker_num'=>$v]);
        }
        
        $tasksCheck = array();
        foreach($data as $k => &$v){
            if($k === 0 || $k === 1 || empty($v[0])) continue;
        
            $v['num'] = $k;
            $v[15] = strval($v[15]);
            $v[16] = strval($v[16]);
            $v[17] = strval($v[17]);
            $v[0] = intval($v[0]);
            
            if(empty($v[16])){
                $v['msg'] = '接单人手机不能为空！';
                array_push($tasksCheck, $v);
                continue;
            }
            
            $uid = UserModel::getUserIdByMobileAndIDCard($v[16], $v[15]);
            
            if(!$uid||empty($uid)){
                
                $status = UserModel::where('mobile', $v[16])->first();
                if($status){
                    $v['msg'] = '新接单人的手机已经被注册！';
                    array_push($tasksCheck, $v);
                    continue;
                }
                
                if(empty($v[14])||empty($v[15])||empty($v[18])||empty($v[19])){
                    $v['msg'] = '新接单人必须同时提供姓名，身份证号以及身份证正反面照片！';
                    array_push($tasksCheck, $v);
                    continue;
                }
                
                $salt = \CommonClass::random(4);
                $param = [
                    'name' => $v[16],
                    'email' => $this->genEMail($v[16]),
                    'realname' => $v[14],
                    'card_number' => $v[15],
                    'email_status' => 2,
                    'status' => 1,
                    'type' => 1,
                    'mobile' => $v[16],
                    'account' => $v[17],
                    'password' => UserModel::encryptPassword($v[16], $salt),
                    'salt' => $salt,
                    'card_front_side' => $v[18],
                    'card_back_dside' => $v[19],
                    'astatus' => 1,
                ];
                $status = UserModel::addUser($param);
                
                if(!$status){
                    $v['msg'] = '新接单人创建失败！';
                    array_push($tasksCheck, $v);
                    continue;
                }
                
                $uid = UserModel::getUserIdByMobileAndIDCard($v[16], $v[15]);
                if(!$uid){
                    $v['msg'] = '新接单人创建失败！';
                    array_push($tasksCheck, $v);
                    continue;
                }
            }else{
                if($v[17]&&$uid){
                    RealnameAuthModel::where('uid', $uid->id)->where('account', '<>', $v[17])->update(['account'=>$v[17]]);
                }
            }
            
            if($v[20]&&$uid){                
                $status = explode('-', $v[20]);
                
                if($status[0]){
                    unset($update);
                    $update = array();
                    switch(intval($status[0])){
                        case 1: $update['status'] = 3; $v['c_status']='验收通过'; break;
                        case 2: $update['status'] = 4; $v['c_status']='验收驳回'; break;
                        case 3: $update['status'] = 5; $v['c_status']='任务终止'; break;
                    }
                    
                    if(!isset($update['status'])){
                        $v['msg'] = '验收状态设置异常！';
                        $v['c_status']=$status[0];
                        array_push($tasksCheck, $v);
                        continue;
                    }
                    
                    if($v[21]){
                        $update['checked_at'] = date('Y-m-d H:i:s', strtotime($v[21]));
                        
                    }
                    if($v[22]){
                        $update['payment'] = floatval($v[22]);
                    }
                    if($v[23]){
                        $comment = \CommonClass::removeXss($v[23]);
                    }
                    $update['delivered_at'] = date('Y-m-d H:i:s', time());
                    $work_id = WorkModel::select('id')->where('task_id',$v[0])->where('uid',$uid->id)->first();
                    
                    if(!$work_id){
                        
                        $param1 = [
                            'task_id' => $v[0],
                            'uid' => $uid->id,
                            'status' => 1,
                            'created_at' => date('Y-m-d H:i:s',time()),
                            'delivered_at'=>date('Y-m-d H:i:s',time()),
                        ];
                        
                        //创建一个新的稿件
                        $workModel = new WorkModel();
                        $result = $workModel->workCreate($param1);
                        
                        if(!$result){
                            $v['msg'] = '新接单人接单失败！';
                            array_push($tasksCheck, $v);
                            continue;
                        }else{
                            $work_id = WorkModel::select('id')->where('task_id',$v[0])->where('uid',$uid->id)->first();
                        }
                    }
                    
                    
                    if($work_id){
                        
                        //查询任务需要的人数
                        $worker_num = TaskModel::where('id',$v[0])->first();
                        $worker_num = $worker_num['worker_num'];
                        //任务验收通过人数
                        $win_check = WorkModel::where('work.task_id',$v[0])->whereIn('status',[3,5])->count();
                        
                        if(($win_check>=$worker_num)&&$update['status'] === 3){
                            $v['msg'] = '不能再接受新的验收！';
                            array_push($tasksCheck, $v);
                            continue;
                        }                        
                        
                        $param = [
                            'work_id' => $work_id->id,
                            'update' => $update,
                            'win_check' => $win_check,
                            'worker_num' => $worker_num
                        ];
                        
                        if(isset($comment)&&!empty($comment)){
                            $param['task_id'] = $v[0];
                            $task = TaskModel::select('task.uid','users.name')->where('task.id', $v[0])->leftjoin('users','users.id','=','task.uid')->first();
                            $param['t_uid'] = $task->uid;
                            $param['t_uname'] = $task->name;
                            $param['comment'] = $comment;
                        }
                        
                        $result = DB::transaction(function() use($param) {
                            WorkModel::where('id', $param['work_id'])->update($param['update']);
                            
                            if(isset($param['comment'])&&!empty($param['comment'])){
                                //将数据存入数据库
                                $work_comment = [
                                    'task_id'=>$param['task_id'],
                                    'work_id'=>$param['work_id'],
                                    'uid'=>$param['t_uid'],
                                    'nickname'=>$param['t_uname'],
                                    'comment'=>$param['comment'],
                                    'created_at'=>date('Y-m-d H:i:s',time()),
                                ];
                                WorkCommentModel::create($work_comment);
                            }
                            if(($param['win_check']+1)==$param['worker_num']&&$param['update']['status']!=4)
                            {
                                TaskModel::where('id',$param['task_id'])->update(['status'=>8,'comment_at'=>date('Y-m-d H:i:s',time()),'updated_at'=>date('Y-m-d H:i:s',time())]);
                            }
                        });
                        
                            if($result){
                                $v['msg'] = '接单人验收状态更新失败！';
                                array_push($tasksCheck, $v);
                            }else{
                                $v['msg'] = '接单人验收状态更新成功！';
                                array_push($tasksCheck, $v);
                            }
                        
                    }else{
                        $v['msg'] = '接单人接单失败！';
                        array_push($tasksCheck, $v);
                        continue;
                    }
                }else{
                    $v['msg'] = '设置的验收状态异常！';
                    array_push($tasksCheck, $v);
                    continue;
                }
                
            }else{
                $v['msg'] = '接单人的验收状态异常！';
                array_push($tasksCheck, $v);
                continue;
            }
        }
        //dump($tasksCheck);
        
        
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $result = [
            'filesize' => $attachmentConfig['attachment']['size'],
            'tasksCheck' => $tasksCheck,
        ];
        //dump($result);
        return $this->theme->scope('manage.taskCheckImport', $result)->render();        
        
    }
    
    //任务结算
    public function getTaskSettle(Request $request){
        
        $search = $request->all();
        $by = $request->get('by') ? $request->get('by') : 'work.id';
        $order = $request->get('order') ? $request->get('order') : 'desc';
        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;
        
        
        $worklist = WorkModel::select('work.id as w_id','work.task_id as w_task_id', 'work.desc as w_desc', 'work.status as w_status', 'work.uid as w_uid', 'work.created_at as w_created_at',
            'work.delivered_at as w_delivered_at', 'work.checked_at as w_checked_at', 'work.payment as w_payment','enterprise_auth.company_name','task_type.name as t_type_name','realname_auth.realname',
            'task.title')
            ->whereIn('work.status', [3,5]);
            
            if ($request->get('task_title')) {
                $worklist = $worklist->where('task.title','like','%'.$request->get('task_title').'%');
            }
            if ($request->get('username')) {
                $worklist = $worklist->where('realname_auth.realname','like','%'.e($request->get('username')).'%');
            }
            if ($request->get('company_name')) {
                $worklist = $worklist->where('enterprise_auth.company_name','like','%'.e($request->get('company_name')).'%');
            }
            //状态筛选
            if ($request->get('status') && $request->get('status') != 0) {
                switch($request->get('status')){
                    case 1:
                        $status = [3];
                        break;
                    case 2:
                        $status = [4];
                        break;
                    case 3:
                        $status = [2];
                        break;
                    case 4:
                        $status = [5];
                        break;
                }
                $worklist = $worklist->whereIn('work.status',$status);
            }
            
            
            //时间筛选
            if($request->get('time_type')){
                if($request->get('start')){
                    $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
                    $worklist = $worklist->where($request->get('time_type'),'>',$start);
                }
                if($request->get('end')){
                    $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
                    $worklist = $worklist->where($request->get('time_type'),'<',$end);
                }
                
            }
            
            $worklist = $worklist->orderBy($by, $order)
            ->leftJoin('users', 'users.id', '=', 'work.uid')
            ->leftJoin('task', 'task.id', '=', 'work.task_id')
            ->leftJoin('task_type', 'task.type_id', '=', 'task_type.id')
            ->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
            ->leftJoin('realname_auth', 'realname_auth.uid', '=', 'work.uid')
            ->paginate($paginate);
            
            $listArr = $worklist->toArray();
            $data = array(
                'work' => $worklist,
                'listArr' => $listArr,
            );
            $data['merge'] = $search;
        
        return $this->theme->scope('manage.taskSettle', $data)->render();
    }
    
    /**
     * 任务批量结算
     */
    public function postTaskSettleAll(Request $request){
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/taskSettle')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        
        $settle = array();
        foreach ($data['chk'] as $id) {
            $result = WorkModel::where('id', $id)->where('status', 3)->count();
            if($result>0){
                array_push($settle, $id);
            }
        }
        
        $rest = array_diff($data['chk'], $settle);
        
        $status = DB::transaction(function () use ($settle) {
            WorkModel::whereIn('id', $settle)->update(['status' => 5, 'settle_at'=>date('Y-m-d H:i:d',time())]);
        });
            if(is_null($status))
            {
                if(empty($rest)){
                    return redirect()->to('manage/taskSettle')->with(array('message' => '操作成功'));
                }else{
                    return redirect()->to('manage/taskSettle')->with(array('message' => '编号为：'.implode(',', $rest).' 的任务结算失败！'));
                }
            }
            return  redirect()->to('manage/taskSettle')->with(array('message' => '操作失败'));
    }
    
    public function taskSettleHandle($id, $action){
        if (!$id) {
            return  redirect()->to('manage/taskSettle')->with(array('error' => '参数错误'));
        }
        $id = intval($id);
        
        switch ($action) {
            case 'settle':
                $wstatus = 5;
                break;
        }
        
        $task_id = WorkModel::select('task_id')->where('id',$id)->first();
        //判断当前的任务的入围人数是否用完
        $worker_num = TaskModel::where('id',$task_id['task_id'])->lists('worker_num');
        $worker_num = $worker_num[0];
        //当前任务的完成验收人数统计
        $settle_num = WorkModel::where('task_id',$task_id['task_id'])->where('status', '=' ,5)->count(); 
        
        $param = [
            'id' => $id,
            'task_id' => isset($task_id)&&isset($task_id['task_id'])?$task_id['task_id']:'',
            'wstatus' => $wstatus,
            'worker_num' => isset($worker_num)?$worker_num:'',
            'settle_num' => isset($settle_num)?$settle_num:'',
        ];        
        
        $status = DB::transaction(function () use ($param) {
            WorkModel::where('id', $param['id'])->update(['status' => $param['wstatus'], 'settle_at'=>date('Y-m-d H:i:s',time())]);
            
            if(($param['settle_num']+1)==$param['worker_num'])
            {
                TaskModel::where('id',$param['task_id'])->update(['status'=>9, 'updated_at'=>date('Y-m-d H:i:s',time())]);
            }
        });
		
            if(is_null($status))
            {
                return redirect()->to('manage/taskSettle')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/taskSettle')->with(array('message' => '操作失败'));
    }
    
    public function postTaskSettleDownload(Request $request){
        $work = $request->get('chk');
        
        if(!isset($work)||empty($work)){
            return  redirect()->to('manage/taskSettle')->with(array('message' => '下载失败'));
        }
        
        $works = explode(",", $work);
        $task_ids = WorkModel::select('task_id')->whereIn('id',$works)->groupby('task_id')->get()->toArray();
        
        if(!$task_ids||empty($task_ids)) {
            return redirect()->to('/manage/taskSettle')->with(['massage'=>'结算任务不存在！']);
        };        
        
        $tasksDetail = array();
        foreach($task_ids as $k => $v){
            
            $task_detail = TaskModel::detail3($v['task_id']);
            
            foreach($task_detail as $v1){
                unset($taskDetail);
                if($v1->w_status != 3) continue;
                $taskDetail = [
                    'uid'=> $v1->uid,
                    'company_name' => $v1->company_name,
                    'id' => $v1->id,
                    'title' => $v1->title,
                    'type_name' => $v1->type_name,
                    'checked_at' => $v1->checked_at,
                    'worker_name' => $v1->w_realname,
                    'worker_account' => $v1->w_account,
                    'worker_card_number' => $v1->w_card_number,
                    'worker_payment' => [$v1->payment,'n00'],
                    'worker_status' => '未结算',
                ];
                
                array_push($tasksDetail, $taskDetail);
            }
        }
        //dump($tasksDetail);
        
        /* $template = $this->fileImport('',[],$_SERVER['DOCUMENT_ROOT'].'/attachment/sys/templates/template_taskys.xlsx');
         dump($template); */
        
        $title = ['企业ID',['企业名称',NULL,50],'任务ID',['任务名称',NULL,50],'任务主类别','验收时间','收款户名(真实姓名,必填)','收款账号(个人银行卡号)','身份证号(必填)','打款金额/元(四舍五入至分,必填)','结算状态'];
        
        $param = [
            'sheetName' => '任务结算',
            'title' => $title,
            'data' => $tasksDetail,
            'fileName' => 'template_taskjs_'.time(),
            'savePath' => './',
            'isDown' => true
        ];
        
        $this->exportExcel($param);
        
        //$this->exportExcel($title, $tasksDetail,'任务结算', 'template_taskjs_'.time(), './', true);
        
        return redirect()->to('manage/taskCheck');        
        
    }
    
    //跳转任务结算导入视图
    public function getTaskSettleImport(){
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $data = [
            'filesize' => $attachmentConfig['attachment']['size']
        ];
        
        return $this->theme->scope('manage.taskSettleImport', $data)->render();
    }
    
    //提交任务结算导入数据
    public function postTaskSettleImport(Request $request){
        $data = $this->fileImport($request->file('tasksettlefile'));
        
        if(isset($data['fail'])&&$data['fail']){
            return back()->with(['error' => $data['errMsg']]);
        }
        
        //dump($data);
        $tasks = array();
        foreach($data as $k => $v){
            if($k === 0 || empty($v[2])) continue;
            if (!array_key_exists(intval($v[2]),$tasks)){
                $tasks[$v[2]] = $v[3];
            }
        }
        $tasks = array_keys($tasks);
        
        WorkModel::whereIn('task_id',$tasks)->where('status', '3')->update(['status'=>5, 'settle_at'=>date('Y-m-d H:i:d',time())]);
        
        foreach($tasks as $v){
            //判断当前的任务的入围人数是否用完
            $worker_num = TaskModel::where('id',$v)->lists('worker_num');
            $worker_num = $worker_num[0];
            //当前任务的完成验收人数统计
            $settle_num = WorkModel::where('task_id',$v)->where('status', '=' ,5)->count();
            
            if($settle_num==$worker_num)
            {
                TaskModel::where('id',$v)->update(['status'=>9, 'updated_at'=>date('Y-m-d H:i:s',time())]);
            }
        }
             
        $tasksSettle = array();
        foreach($data as $k => &$v){
            if($k === 0 || empty($v[0])) continue;
            
            $v['num'] = $k;
            $v[0] = intval($v[0]);
            $v[2] = intval($v[2]);
            $v[7] = strval($v[7]);
            $v[8] = strval($v[8]);
            
            $v['msg'] = '结算成功！';
            
            array_push($tasksSettle, $v);
        }        
        
        //dump($tasksSettle);        
        
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $result = [
            'filesize' => $attachmentConfig['attachment']['size'],
            'tasksSettle' => $tasksSettle,
        ];
        //dump($result);
        return $this->theme->scope('manage.taskSettleImport', $result)->render();        
        
    }
    
    
    public function taskDetail4($id, $action){
        
        $work = WorkModel::select('work.id as w_id','work.task_id as w_task_id', 'work.desc as w_desc', 'work.status as w_status', 'work.uid as w_uid', 'work.created_at as w_created_at', 
            'work.delivered_at as w_delivered_at','work.checked_at as w_checked_at','work.settle_at as w_settle_at','users.mobile as w_mobile',
            'work.delivered_at as w_delivered_at', 'work.payment as w_payment','enterprise_auth.company_name','mt.name as mt_type_name','st.name as st_type_name',
            //'province.name as province_name','city.name as city_name','area.name as area_name','task.address',
            'realname_auth.realname','realname_auth.card_number as w_card_number',
            'task.title','task.begin_at','task.end_at','task.status as t_status','task.bounty')->where('work.id', $id);
        
        $work = $work->leftJoin('users', 'users.id', '=', 'work.uid')
        ->leftJoin('task', 'task.id', '=', 'work.task_id')
        ->leftJoin('task_type as mt', 'task.type_id', '=', 'mt.id')
        ->leftJoin('task_type as st', 'task.sub_type_id', '=', 'st.id')
        /* ->leftjoin('district as province','province.id','=','task.province')
        ->leftjoin('district as city','city.id','=','task.city')
        ->leftjoin('district as area','area.id','=','task.area') */
        ->leftJoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
        ->leftJoin('realname_auth', 'realname_auth.uid', '=', 'work.uid')->first();
        
        //查询任务技能标签
        /* $t_tags = TaskTagsModel::getTagsByTaskId($work->w_task_id);
        //查询任务的附件
        $t_attatchment_ids = TaskAttachmentModel::where('task_id','=',$work->w_task_id)->lists('attachment_id')->toArray();
        $t_attatchment_ids = array_flatten($t_attatchment_ids);
        $taskAttatchment = AttachmentModel::whereIn('id',$t_attatchment_ids)->get(); */
        
        //查询接单人技能标签
        //$w_tags = UserTagsModel::getTagsByUserId($work->w_uid);
        
        $w_attatchment_ids = WorkAttachmentModel::findById($id);
        $w_attatchment_ids = array_flatten($w_attatchment_ids);
        $workkAttatchment = AttachmentModel::whereIn('id',$w_attatchment_ids)->get();
        
        //$workComments = WorkCommentModel::where('work_id',$id)->where('pid',0)->with('childrenComment')->get()->toArray();        
        
        $a_config = ConfigModel::getConfigByType('attachment');
        
        $data = [
            'work' => $work,
            //'t_tag' => $t_tags,
            //'w_tag' => $w_tags,
            //'t_attachment' => $taskAttatchment,
            'w_attachment' => $workkAttatchment,
            //'w_comment' => $workComments,
            'action' => $action,
            'a_config' => $a_config['attachment']
        ];
        
        //dump($data);exit;
        
        return $this->theme->scope('manage.taskdetail4', $data)->render();
    }
    
    public function postTaskSettleUpload(Request $request){ 
        $files = $request->get('file_id');
        if(!$files||empty($files)){
            return redirect()->back()->with(['error'=>'缺少必要参数！']);
        }        
        
        $uid = $request->get('w_uid');        
        if(!$uid||empty($uid)){
            return redirect()->back()->with(['error'=>'缺少必要参数！']);
        }
        
        $wid = $request->get('w_id');
        if(!$wid||empty($wid)){
            return redirect()->back()->with(['error'=>'缺少必要参数！']);
        }
        
        $tid = $request->get('w_task_id');
        if(!$tid||empty($tid)){
            return redirect()->back()->with(['error'=>'缺少必要参数！']);
        }
        
        
        $uploadFiles = explode(",", $files);
                
        //dump($uploadFiles);exit;
        
        $param = [
            'task_id' => $tid,
            'work_id' => $wid,
            'a_id' => $uploadFiles
        ];
        
        $status = DB::transaction(function() use($param){
            $file_able_ids = AttachmentModel::select('attachment.id','attachment.type')->whereIn('id',$param['a_id'])->get()->toArray();
            
            foreach($file_able_ids as $v){
                $work_attachment = [
                    'task_id'=>$param['task_id'],
                    'work_id'=>$param['work_id'],
                    'attachment_id'=>$v['id'],
                    'type'=>$v['type'],
                    'created_at'=>date('Y-m-d H:i:s',time()),
                ];
                WorkAttachmentModel::create($work_attachment);
            }
            
            WorkModel::where('id', $param['work_id'])->update(['desc'=>'通过验收记录导入自动创建','delivered_at'=>date('Y-m-d H:i:s',time())]);
        });
            
            if(is_null($status))
            {
                return redirect()->to('manage/taskSettle')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/taskSettle')->with(array('message' => '操作失败'));
    }
}
