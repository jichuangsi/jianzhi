<?php
namespace App\Modules\Manage\Http\Controllers;

use App\Http\Controllers\ManageController;
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
use App\Modules\User\Model\UserDetailModel;
use App\Modules\User\Model\UserModel;
use App\Modules\Manage\Model\ConfigModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Theme;

class TaskController extends ManageController
{
    public function __construct()
    {
        parent::__construct();

        $this->initTheme('manage');
        $this->theme->setTitle('任务列表');
        $this->theme->set('manageType', 'task');
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

        $taskList = TaskModel::select('task.id', 'us.name', 'task.title', 'task.created_at', 'task.status', 'task.verified_at', 'task.bounty_status');

        if ($request->get('task_title')) {
            $taskList = $taskList->where('task.title','like','%'.$request->get('task_id').'%');
        }
        if ($request->get('username')) {
            $taskList = $taskList->where('us.name','like','%'.e($request->get('username')).'%');
        }
        //状态筛选
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
        ->paginate($paginate);

        $data = array(
            'task' => $taskList,
        );
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
        
        $data = array(
            'task' => $taskList,
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
            case 'deny':
                $status = 10;
                break;
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
        }elseif($status==10)
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
                $status = 10;
                break;
            default:
                $status = 3;
                break;
        }

        $status = TaskModel::whereIn('id', $request->get('ckb'))->where('status', 1)->orWhere('status', 2)->update(array('status' => $status));
        if ($status)
            return back();

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
                $skills = $v1['tag_name'].',';
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
                'bounty' => $detail->bounty,
                'worker_num' => $detail->worker_num,
                'desc' => $detail->desc,
                'created_at' => $detail->created_at,
            ];            
            
            array_push($tasksDetail, $taskDetail);
        }
        
        $title = ['任务ID','任务名称','发布企业','任务主类别','任务子类别','服务起始日期','服务截止日期','任务城市','服务地址','技能要求','任务预算','任务人数','任务描述','发布时间','姓名','身份证号码','手机号','银行卡号','身份证正面','身份证反面'];
        //dump($tasksDetail);        
        
        $this->exportExcel($title, $tasksDetail,'任务派单', 'template_taskDispatch_'.time(), './', true);
        
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
            $v[16] = strval($v[16]);
            
            if(empty($v[16])){
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'接单人手机不能为空！']);
                continue;
            }
            
            $uid = UserModel::getUserIdByMobileAndIDCard($v[16], $v[15]);
            
            if(!$uid){
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
            
            //判断当前的任务的入围人数是否用完
            $worker_num = TaskModel::where('id',$v[0])->lists('worker_num');
            $worker_num = $worker_num[0];
            //当前任务的入围人数统计
            $win_bid_num = WorkModel::where('task_id',$v[0])->where('status',1)->count();
            
            if($worker_num<=$win_bid_num){
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'任务接单人数已满！']);
                continue;
            }
            
            $param1 = [
                'task_id' => $v[0],
                'uid' => $uid->id,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s',time()),
            ];
            
            //创建一个新的稿件
            $workModel = new WorkModel();
            $status = $workModel->workCreate($param1);
            
            if(!$status){
                array_push($tasksDispatch, ['num'=>$k, 'msg'=>'接单失败！']);
                continue;
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
    
}
