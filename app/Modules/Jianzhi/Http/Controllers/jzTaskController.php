<?php

namespace App\Modules\Jianzhi\Http\Controllers;

use App\Modules\Task\Http\Controllers\IndexController as TaskBasicController;
use Auth;
use App\Modules\Jianzhi\Http\Requests\TaskRequest;
use App\Modules\Jianzhi\Http\Requests\WorkRequest;
use App\Modules\Task\Model\TaskModel;
use App\Modules\Task\Model\WorkModel;
use App\Modules\Task\Model\TaskTagsModel;
use App\Modules\Task\Model\TaskAttachmentModel;
use App\Modules\User\Model\AttachmentModel;
use App\Modules\Task\Model\TaskCateModel;
use App\Modules\Task\Model\TaskTypeModel;
use App\Modules\User\Model\DistrictModel;
use App\Modules\User\Model\UserTagsModel;
use Illuminate\Http\Request;
/* use App\Modules\Manage\Model\AgreementModel;
use App\Modules\Manage\Model\ConfigModel;
use App\Modules\User\Http\Requests\LoginRequest;
use App\Modules\User\Http\Requests\RegisterRequest;
use App\Modules\User\Model\OauthBindModel;
use App\Modules\User\Model\UserModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Theme;
use Crypt;
use Socialite;
use App\Modules\Advertisement\Model\AdTargetModel;
use App\Modules\Advertisement\Model\AdModel; */

class jzTaskController extends TaskBasicController
{    

    public function __construct()
    {
        parent::__construct();
        $this->initTheme('common','jianzhi');
        $this->user = Auth::user();
        $this->middleware('auth');
    }
    
    /**
     * 任务提交，创建一个新任务
     * @param TaskRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */    
    public function createNewTask(TaskRequest $request)
    {        
        $data = $request->except('_token');
        $data['uid'] = $this->user['id'];
        $data['username'] = $this->user['name'];
        $data['type_id'] = $data['mainType'];
        $data['sub_type_id'] = $data['subType'];
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
        
        //判断当前用户是否有资格创建任务
        $is_task_able = $this->isTaskAble();
        //返回为何不能创建任务的原因
        if(!$is_task_able['able'])
        {
            return redirect()->back()->with('error',$is_task_able['errMsg']);
        }
                
        $taskModel = new TaskModel();
        $result = $taskModel->createTask($data);
        
        if (!$result) {
            return redirect()->back()->with('error', '创建任务失败！');
        }
        
        /* if($data['slutype']==3){
            return redirect()->to('user/unreleasedTasks');
        } */
        //return redirect()->to('task/' . $controller . '/' . $result['id']);
        //跳转至任务列表
        return redirect()->to('jz/task');
    }
    
    /**
     * 查询个人首页任务列表
     */
    public function ajaxTasks(Request $request)
    {
        $param = $request->except('_token');
        
        $myTags = UserTagsModel::myTag($this->user['id']);
        
        if(!$myTags||empty($myTags)){
            return response()->json(['errMsg' => '请设置个人技能标签！']);
        }
        
        $taskIds = TaskTagsModel::getTasksByTagId($myTags);
        
        if(!$taskIds||empty($taskIds)){
            return response()->json(['errMsg' => '暂时没有适合你的任务！']);
        }
        
        $ids = array();
        foreach($taskIds as $k => $v){
            array_push($ids, $v['task_id']);
        }
        
        $param['ids'] = $ids;
        $param['size'] = $param['size']!=null&&!empty($param['size'])?$param['size']:5;
        
        $tasks = TaskModel::findBy2($param);
                
        return response()->json($tasks);
    }
    
    /**
         * 查询企业任务
     */
    public function ajaxEmyTasks(Request $request)
    {
        $status = intval($request->get('status'));
        
        if (!$status) {
            return response()->json(['errMsg' => '参数错误！']);
        }
        $param = array();
        $param['status'] = $status;
        $param['size'] = $request->get('size')!=null&&!empty($request->get('size'))?$request->get('size'):5;
        $param['uid'] = $this->user['id'];
        
        $my_tasks = TaskModel::eMyTasks($param);
                
        return response()->json($my_tasks); 
        
    }
    
    /**
     * 查询个人任务
     */
    public function ajaxMyTasks(Request $request)
    {
        $status = intval($request->get('status'));
        
        if (!$status) {
            return response()->json(['errMsg' => '参数错误！']);
        }
        $param = array();
        $param['status'] = $status;
        $param['size'] = $request->get('size')!=null&&!empty($request->get('size'))?$request->get('size'):5;
        $param['uid'] = $this->user['id'];
        
        if($param['status']){
            switch ($param['status']) {
                case 3://招募中
                    $work_status = 0;
                    break;
                case 4://工作中
                    $work_status = 1;
                    break;
                case 5://待验收
                    $work_status = 2;
                    break;
                case 6://已验收
                    $work_status = 3;
                    break;
                case 7://已结算
                    $work_status = 5;
                    break;
                default://其他
                    $work_status = 4;
                    break;
            }
        }
        
        $my_tasks = null;
        if(isset($work_status)){
            $taskIds = WorkModel::getTasksByUidAndStatus($param['uid'], $work_status);
            if($taskIds){
                $ids = array();
                foreach($taskIds as $k => $v){
                    array_push($ids, $v['task_id']);
                }
                
                $param['ids'] = $ids;
                
                $my_tasks = TaskModel::myTasks($param);
            }else{
                return response()->json(['errMsg' => '暂时没找到你的任务！']);
            }
        }
        
        return response()->json($my_tasks);
        
    }
    
    /**
        * 取消个人任务报名
     * @param Request $request
     */
    public function ajaxCancelMyWork(Request $request){
        $taskId = intval($request->get('taskId'));
        
        if (!$taskId) {
            return response()->json(['errMsg' => '参数错误！']);
        }
        
        $param = array();
        $param['uid'] = $this->user['id'];
        $param['task_id'] = $taskId;
        $workModel = new WorkModel();        
        $result = $workModel->cancelMyWork($param);
        
        if($result){
            return response()->json($result);
        }else{
            return response()->json(['errMsg' => '取消报名失败！']);
        }
    }
    
    /**
           * 查询任务详情
     */
    public function getTaskdetail($id, Request $request)
    {
        $data = $request->all();
        //查询任务详情
        $detail = TaskModel::detail($id);        
        
        //查询任务的附件
        $attatchment_ids = TaskAttachmentModel::where('task_id','=',$id)->lists('attachment_id')->toArray();
        $attatchment_ids = array_flatten($attatchment_ids);
        $attatchment = AttachmentModel::whereIn('id',$attatchment_ids)->get();
        
        //查询任务技能标签
        $tags = TaskTagsModel::getTagsByTaskId($id);
        
        
        /* //判断当前状态是否需要区别三种角色,登陆之后的
        if($detail['status']>2 && Auth::check())
        {
            //判断当前角色是否是投稿人
            if(WorkModel::isWorker($this->user['id'],$detail['id']))
            {
                $user_type = 2;
                //判断用户投稿人是否入围
                $is_win_bid = WorkModel::isWinBid($id,$this->user['id']);
                $is_delivery = WorkModel::where('task_id',$id)->where('status','>',1)->where('uid',$this->user['id'])->first();
                $is_rights = WorkModel::where('task_id',$id)->where('status','=',4)->where('uid',$this->user['id'])->first();
            }
            //判断当前的角色是否是发布人,任务角色的优先级最高
            if($detail['uid']==$this->user['id'])
            {
                $user_type = 1;
            }
        } */
        
        //查询投稿记录
        $works = WorkModel::findAll2($id,$data);
        $works_count = WorkModel::where('task_id',$id)->where('status','<=',1)->where('forbidden',0)->count();//投稿记录个数统计
        $works_bid_count = WorkModel::where('task_id',$id)->where('status','=',1)->where('forbidden',0)->count();//中标记录个数统计
        //$works_winbid_count = WorkModel::where('task_id',$id)->where('status','=',1)->where('forbidden',0)->count();
        
        foreach($works as $k => &$v){
            $v['skills'] = UserTagsModel::getTagsByUserId($v['uid']);
        }
        
        $data = [
            'detail'=>$detail,
            'attatchment'=>$attatchment,
            'tags' => $tags,
            'works'=> $works,
            //'is_win_bid'=>$is_win_bid,
            //'is_delivery'=>$is_delivery,            
            'works_count'=>$works_count,
            'works_bid_count'=>$works_bid_count,            
            //'is_rights'=>$is_rights,
            //'works_winbid_count'=>$works_winbid_count,
        ];
        
        if($this->user->type===2){
            $path = 'etaskDetail';
        }else{
            $path = 'taskDetail';
        }
        
        return $this->theme->scope($path, $data)->render();
    }
    
    /**
            * 个人报名任务
     */
    public function ajaxCreateNewWork(WorkRequest $request){
        $param = $request->except('_token');
                
        if(!isset($param['agreement'])){
            return response()->json(['errMsg' => '参数错误！']);
        }
        
        $param['uid'] = $this->user['id'];
        $param['created_at'] = date('Y-m-d H:i:s',time());        
        
        //判断当前用户是否有资格投标
        $is_work_able = $this->isWorkAble($param['task_id']);
        //返回为何不能投标的原因
        if(!$is_work_able['able'])
        {
            return response()->json(['errMsg' => $is_work_able['errMsg']]);
        }
        //创建一个新的稿件
        $workModel = new WorkModel();
        $result = $workModel->workCreate($param);
        
        if(!$result) return response()->json(['errMsg' => '报名失败！']);
        
        $data = [
            'data' => $result
        ];
        
        return response()->json($data);
    }
    
    /**
        * 企业派单
     */
    public function ajaxAssignTask(Request $request)
    {
        $param = $request->except('_token');
        
        if(!isset($param['task_id'])||!isset($param['work_id'])){
            return response()->json(['errMsg' => '参数错误！']);
        }
                
        //检查当前选标的人是不是任务的发布者
        //查询任务的发布者
        $task_user = TaskModel::where('id',$param['task_id'])->lists('uid');
        
        if($task_user[0]!=$this->user['id'])
        {
            return response()->json(['errMsg' => '非法操作,你不是任务的发布者不能选择中标人选！']);
        }
        //判断当前的任务的入围人数是否用完
        $worker_num = TaskModel::where('id',$param['task_id'])->lists('worker_num');
        //当前任务的入围人数统计
        $win_bid_num = WorkModel::where('task_id',$param['task_id'])->where('status',1)->count();
        
        //判断当前是否可以选择中标
        if($worker_num[0]>$win_bid_num)
        {
            $param['worker_num'] = $worker_num[0];
            $param['win_bid_num'] = $win_bid_num;
            $workModel = new WorkModel();
            $result = $workModel->winBid2($param);
            
            if(!$result) return response()->json(['errMsg' => '派单失败！']);
        }else{
            return response()->json(['errMsg' => '当前派单人数已满！']);
        }
        
        return response()->json($result);
    }
    
    /**
        * 下载附件
     * @param $id
     */
    public function fileDownload($id)
    {
        $pathToFile = AttachmentModel::where('id',$id)->first();
        $pathToFile = $pathToFile['url'];
        return response()->download($pathToFile);
    }
    
    /**
        * 任务协议
     * @param $id
     */
    public function getTaskAgreement(){
        $view = array();
        return $this->theme->scope('agreement', $view)->render();
    } 
    
    /**
     * 判断当前用户是否有投稿的资格,便于扩展
     */
    private function isWorkAble($task_id)
    {
        //判断当前任务是否处于投稿期间
        $task_data = TaskModel::where('id',$task_id)->first();
        if($task_data['status']!=(3||4) || strtotime($task_data['begin_at'])>time())
        {
            return ['able' => false, 'errMsg' => '当前任务还未开始投稿！'];
        }
        //判断当前用户是否登录
        if (!isset($this->user['id'])) {
            return ['able' => false, 'errMsg' => '请登录后再操作！'];
        }
        //判断用户是否为当前任务的投稿人，如果已经是的，就不能投稿
        if (WorkModel::isWorker($this->user['id'], $task_id)) {
            return ['able' => false, 'errMsg' => '你已经投过稿了'];
        }
        //判断当前用户是否为任务的发布者，如果是用户的发布者，就不能投稿
        if (TaskModel::isEmployer($task_id, $this->user['id']))
        {
            return ['able' => false, 'errMsg'=>'你是任务发布者不能投稿！'];
        }
        return ['able'=>true];
    }
    
    /**
        * 判断当前用户是否有创建任务的资格,便于扩展
     */
    private function isTaskAble($task_id)
    {
        return ['able'=>true];
    }
}
