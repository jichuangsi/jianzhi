<?php

namespace App\Modules\Task\Model;

use App\Modules\Employ\Models\EmployUserModel;
use App\Modules\Finance\Model\FinancialModel;
use App\Modules\Manage\Model\MessageTemplateModel;
use App\Modules\Order\Model\OrderModel;
use App\Modules\User\Model\AttachmentModel;
use App\Modules\User\Model\MessageReceiveModel;
use App\Modules\User\Model\UserDetailModel;
use App\Modules\User\Model\UserModel;
use App\Modules\Task\Model\TaskCateModel;
use App\Modules\Task\Model\TaskTypeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Modules\User\Model\TagsModel;
use App\Modules\Task\Model\TaskTagsModel;
//use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class TaskModel extends Model
{
    protected $table = 'task';
    protected $fillable = [
        'title', 'desc', 'type_id', 'cate_id', 'phone', 'region_limit', 'status', 'bounty', 'bounty_status', 'created_at', 'updated_at',
        'verified_at', 'begin_at', 'end_at', 'delivery_deadline', 'show_cash', 'real_cash', 'deposit_cash', 'province', 'city', 'area',
        'view_count', 'delivery_count', 'uid', 'username', 'worker_num', 'selected_work_at', 'publicity_at', 'checked_at', 'comment_at',
        'top_status','task_success_draw_ratio','task_fail_draw_ratio','engine_status','work_status','sub_type_id','address'
    ];

    static public function eMyTasks($data)
    {
        $query = self::select('task.*', 'tt.name as type_name', 'stt.name as sub_type_name', 'us.name as nickname', 'ud.avatar', 'tc.name as cate_name','province.name as province_name','city.name as city_name','area.name as area_name')
            ->where('task.status', '>', 1)
            ->where('task.status', '<=', 11)->where('task.uid', $data['uid'])->where('bounty_status',1);
        //状态筛选
        if (isset($data['status']) && $data['status'] != 0) {
            switch ($data['status']) {
                case 2://审核中
                    $status = [2];
                    break;
                case 3://招募中
                    $status = [3];
                    break;
                case 4://工作中
                    $status = [4];
                    break;
                case 5://待验收
                    $status = [5,6,7];
                    break;
                case 6://已验收
                    $status = [8];
                    break;
                case 7://已结算
                    $status = [9,10];
                    break;
                case 8://维权
                    $status = [11];
                    break;
                /* case 1:
                    $status = [3,4,6];
                    break;
                case 2:
                    $status = [5];
                    break;
                case 3:
                    $status = [7];
                    break;
                case 4:
                    $status = [8,9,10];
                    break;
                case 5:
                    $status = [2, 11];
                    break; */
            }
            $query->whereIn('task.status', $status);
        }
        //时间段筛选
        if (isset($data['time'])) {
            switch ($data['time']) {
                case 1:
                    $query->whereBetween('task.created_at', [date('Y-m-d H:i:s', strtotime('-1 month')), date('Y-m-d H:i:s', time())]);
                    break;
                case 2:
                    $query->whereBetween('task.created_at', [date('Y-m-d H:i:s', strtotime('-3 month')), date('Y-m-d H:i:s', time())]);
                    break;
                case 3:
                    $query->whereBetween('task.created_at', [date('Y-m-d H:i:s', strtotime('-6 month')), date('Y-m-d H:i:s', time())]);
                    break;
            }

        }

        $data = $query->leftjoin('task_type as tt', 'task.type_id', '=', 'tt.id')
            ->leftjoin('task_type as stt', 'task.sub_type_id', '=', 'stt.id')
            ->leftjoin('district as province','province.id','=','task.province')
            ->leftjoin('district as city','city.id','=','task.city')
            ->leftjoin('district as area','area.id','=','task.area')
            ->leftjoin('users as us', 'us.id', '=', 'task.uid')
            ->leftjoin('user_detail as ud', 'ud.uid', '=', 'task.uid')
            ->leftjoin('cate as tc', 'tc.id', '=', 'task.cate_id')
            ->orderBy('task.created_at','desc')
            ->paginate($data['size']);

        return $data;
    }
    
    static public function myTasks($data)
    {   
        
        $query = self::select('task.*', 'work.created_at as work_created_at', 'work.status as work_status', 'work.uid as work_uid',
                                'tt.name as type_name','tt.pic as type_icon', 'stt.name as sub_type_name', 'us.name as nickname', 'ud.avatar', 'tc.name as cate_name',
                                'province.name as province_name','city.name as city_name','area.name as area_name')
                ->where('task.status', '>', 1)
                ->where('task.status', '<=', 11)
                ->where('work.uid', $data['uid'])
                ->where('bounty_status',1);
        //主键筛选
        if (isset($data['ids'])) {
            $query = $query->whereIn('task.id', $data['ids']);
        }
        //状态筛选
        if(isset($data['status'])){
            if(is_array($data['status'])){
                $query->whereIn('task.status', $data['status']);
            }else{
                if ($data['status'] != 0) {
                    switch ($data['status']) {
                        case 2://审核中
                            $status = [2];
                            break;
                        case 3://招募中
                            $status = [3];
                            break;
                        case 4://工作中
                            $status = [4];
                            break;
                        case 5://待验收
                            $status = [5,6,7];
                            break;
                        case 6://已验收
                            $status = [8];
                            break;
                        case 7://已结算
                            $status = [9,10];
                            break;
                        case 8://维权
                            $status = [11];
                            break;
                    }
                    $query->whereIn('task.status', $status);
                }
            }
        }
        
        //时间段筛选
        if (isset($data['time'])) {
            switch ($data['time']) {
                case 1:
                    $query->whereBetween('task.created_at', [date('Y-m-d H:i:s', strtotime('-1 month')), date('Y-m-d H:i:s', time())]);
                    break;
                case 2:
                    $query->whereBetween('task.created_at', [date('Y-m-d H:i:s', strtotime('-3 month')), date('Y-m-d H:i:s', time())]);
                    break;
                case 3:
                    $query->whereBetween('task.created_at', [date('Y-m-d H:i:s', strtotime('-6 month')), date('Y-m-d H:i:s', time())]);
                    break;
            }
            
        }
        
        $data = $query->leftjoin('task_type as tt', 'task.type_id', '=', 'tt.id')
        ->leftjoin('task_type as stt', 'task.sub_type_id', '=', 'stt.id')
        ->leftjoin('district as province','province.id','=','task.province')
        ->leftjoin('district as city','city.id','=','task.city')
        ->leftjoin('district as area','area.id','=','task.area')
        ->leftjoin('users as us', 'us.id', '=', 'task.uid')
        ->leftjoin('user_detail as ud', 'ud.uid', '=', 'task.uid')
        ->leftjoin('cate as tc', 'tc.id', '=', 'task.cate_id')
        ->leftjoin('work as work', 'work.task_id', '=', 'task.id')
        ->orderBy('task.created_at','desc')
        ->paginate($data['size']);
        
        return $data;
    }
    
    /**
     * 任务筛选
     * @param $data
     * @return mixed
     * author: muker（qq:372980503）
     */
    static function findBy($data)
    {
        $query = self::select('task.*', 'b.name as type_name', 'us.name as user_name')->where('task.status', '>', 2)
            ->where('task.bounty_status', 1)->where('task.status', '<=', 9)->where('begin_at', "<=", date('Y-m-d H:i:s', time()))
            ->orderBy('top_status', 'desc');
        //关键词筛选
        if (isset($data['keywords'])) {
            $query = $query->where('task.title', 'like', '%' . e($data['keywords']) . '%');
        }
        //类别筛选
        if (isset($data['category']) && $data['category']!=0) {
            //查询所有的底层id
            $category_ids = TaskCateModel::findCateIds($data['category']);
            $query->whereIn('cate_id', $category_ids);
        }
        //地区筛选
        if (isset($data['province'])) {
            $query->where('task.province', intval($data['province']));
        }
        if (isset($data['city'])) {
            $query->where('task.city', intval($data['city']));
        }
        if (isset($data['area'])) {
            $query->where('task.area', intval($data['area']));
        }
        //任务状态
        if (isset($data['status'])) {
            switch ($data['status']) {
                case 1:
                    $status = [4];
                    break;
                case 2:
                    $status = [5];
                    break;
                case 3:
                    $status = [6, 7];
                    break;
                case 4:
                    $status = [8,9];
                    break;
            }
            $query->whereIn('task.status', $status);
        }
        //排序
        if (isset($data['desc']) && $data['desc']!='created_at') {
            $query->orderBy($data['desc'], 'desc');
        }elseif(isset($data['desc']) && $data['desc']=='created_at'){
            $query->orderBy('created_at');
        }else{
            $query->orderBy('created_at','desc');
        }

        $data = $query->join('task_type as b', 'task.type_id', '=', 'b.id')
            ->leftjoin('users as us', 'us.id', '=', 'task.uid')
            ->paginate(10);

        return $data;
    }
    
    /**
     * 任务筛选
     * @param $data
     * @return mixed
     * author: muker（qq:372980503）
     */
    static function findBy2($data)
    {
        $query = self::select('task.*', 'b.name as type_name', 'b.pic as type_icon', 'sb.name as sub_type_name', 'us.name as user_name', 'ud.avatar','province.name as province_name','city.name as city_name','area.name as area_name')
                ->where('task.status', '>', 2)
                ->where('task.bounty_status', 1)
                ->where('task.status', '<=', 9)
                //->where('begin_at', "<=", date('Y-m-d H:i:s', time()))
                ->orderBy('top_status', 'desc');
        //主键筛选
        if (isset($data['ids'])) {
            $query = $query->whereIn('task.id', $data['ids']);
        }
        //关键词筛选
        if (isset($data['keywords'])) {
            $query = $query->where('task.title', 'like', '%' . e($data['keywords']) . '%');
        }
        //类别筛选
        /* if (isset($data['category']) && $data['category']!=0) {
         //查询所有的底层id
         $category_ids = TaskCateModel::findCateIds($data['category']);
         $query->whereIn('cate_id', $category_ids);
         } */
        //类型筛选
        if (isset($data['type']) && $data['type']!=0) {
            //查询所有的底层id
            $type_ids = TaskTypeModel::findTypeIds($data['type']);
            $query->whereIn('task.type_id', $type_ids)->orWhereIn('task.sub_type_id', $type_ids);
        }
        //地区筛选
        if (isset($data['province'])) {
            $query->where('task.province', intval($data['province']));
        }
        if (isset($data['city'])) {
            $query->where('task.city', intval($data['city']));
        }
        if (isset($data['area'])) {
            $query->where('task.area', intval($data['area']));
        }
        //任务状态
        if (isset($data['status'])) {
            switch ($data['status']) {
                case 2://审核中
                    $status = [2];
                    break;
                case 3://招募中
                    $status = [3];
                    break;
                case 4://工作中
                    $status = [4];
                    break;
                case 5://待验收
                    $status = [5,6,7];
                    break;
                case 6://已验收
                    $status = [8];
                    break;
                case 7://已结算
                    $status = [9];
                    break;
                case 8://维权
                    $status = [11];
                    break;
                    /* case 1:
                     $status = [4];
                     break;
                     case 2:
                     $status = [5];
                     break;
                     case 3:
                     $status = [6, 7];
                     break;
                     case 4:
                     $status = [8,9];
                     break; */
            }
            $query->whereIn('task.status', $status);
        }
        //工作周期
        if (isset($data['duration'])) {
            unset($duration);
            switch ($data['duration']) {
                case 1://0-1天
                    $duration = ['min'=>0,'max'=>1];
                    break;
                case 2://2-7天
                    $duration = ['min'=>2,'max'=>7];
                    break;
                case 3://8-15天
                    $duration = ['min'=>8,'max'=>15];
                    break;
                case 4://16-30天
                    $duration = ['min'=>16,'max'=>30];
                    break;
                case 5://30-N天
                    $duration = ['min'=>31];
                    break;
            }
            if(isset($duration['min'])) $query->whereRaw('datediff(kppw_task.end_at,kppw_task.begin_at)>='.$duration['min']);
            if(isset($duration['max'])) $query->whereRaw('datediff(kppw_task.end_at,kppw_task.begin_at)<='.$duration['max']);
        }
        
        //排序
        if (isset($data['desc']) && $data['desc']!='created_at') {
            $query->orderBy($data['desc'], 'desc');
        }elseif(isset($data['desc']) && $data['desc']=='created_at'){
            $query->orderBy('created_at');
        }else{
            $query->orderBy('task.created_at','desc');
        }
        
        $data = $query
                ->leftjoin('task_type as b', 'task.type_id', '=', 'b.id')
                ->leftjoin('task_type as sb', 'task.sub_type_id', '=', 'sb.id')
                ->leftjoin('district as province','province.id','=','task.province')
                ->leftjoin('district as city','city.id','=','task.city')
                ->leftjoin('district as area','area.id','=','task.area')
                ->leftjoin('users as us', 'us.id', '=', 'task.uid')
                ->leftjoin('user_detail as ud', 'ud.uid', '=', 'task.uid')
                ->paginate($data['size']);
        
        return $data;
    }

    /**
     * 创建一个任务
     * @param $data
     * @return mixed
     */
    static public function createTask($data)
    {
        $status = DB::transaction(function () use ($data) {
            $result = self::create($data);
            if (!empty($data['file_id'])) {
                if(is_string($data['file_id'])){
                    $data['file_id'] = explode(",", $data['file_id']);
                }
                //查询用户的附件记录，排除掉用户删除的附件记录
                $file_able_ids = AttachmentModel::fileAble($data['file_id']);
                $file_able_ids = array_flatten($file_able_ids);

                foreach ($file_able_ids as $v) {
                    $attachment_data = [
                        'task_id' => $result['id'],
                        'attachment_id' => $v,
                        'created_at' => date('Y-m-d H:i:s', time()),
                    ];
                    TaskAttachmentModel::create($attachment_data);
                }
                //修改附件的发布状态
                $attachmentModel = new AttachmentModel();
                $attachmentModel->statusChange($file_able_ids);
            }
            
            //任务增加技能标签
            if(!empty($data['skill'])){
                $skills = explode(",", $data['skill']);
                $allTag = TagsModel::findAll();
                $taskTags = array();
                foreach($skills as $k => $v){
                    foreach($allTag  as $k1 => $v1){
                        if(intval($v) === $v1['cate_id']){
                            array_push($taskTags, $v1['id']);
                            break;
                        }
                    }
                }
                if(count($taskTags)>0){
                    TaskTagsModel::insert($taskTags, $result['id']);
                }
            }
            
            /* if (!empty($data['product'])) {
                foreach ($data['product'] as $k => $v) {
                    $server = ServiceModel::where('id', $v)->first();
                    if ($server['identify'] == 'ZHIDING') {
                        self::where('id', $result['id'])->update(['top_status' => 1]);
                    }
                    if ($server['identify'] == 'SOUSUOYINGQINGPINGBI') {
                        self::where('id', $result['id'])->update(['engine_status' => 1]);
                    }
                    if ($server['identify'] == 'GAOJIANPINGBI') {
                        self::where('id', $result['id'])->update(['work_status' => 1]);
                    }
                    $service_data = [
                        'task_id' => $result['id'],
                        'service_id' => $v,
                        'created_at' => date('Y-m-d H:i:s', time()),
                    ];
                    TaskServiceModel::create($service_data);
                }
            } */
            return $result;
        });
        return $status;
    }

    /**
     * 根据id查询任务
     * @param $id
     */
    static function findById($id)
    {
        $data = self::select('task.*', 'b.name as cate_name', 'c.name as type_name')
            ->where('task.id', '=', $id)
            ->join('cate as b', 'task.cate_id', '=', 'b.id')
            ->leftjoin('task_type as c', 'task.type_id', '=', 'c.id')
            ->first();

        return $data;
    }



    /**
     * 计算用户的任务金额
     */
    public function taskMoney($id)
    {
        $bounty = self::select('task.bounty')->where('id', '=', $id)->first();
        $bounty = $bounty['bounty'];
        $service = TaskServiceModel::select('task_service.service_id')
            ->where('task_id', '=', $id)->get()->toArray();
        $service = array_flatten($service);
        $serviceModel = new ServiceModel();
        $service_money = $serviceModel->serviceMoney($service);
        $money = $bounty + $service_money;

        return $money;
    }
    static function employbounty($money,$task_id,$uid,$code,$type = 2)
    {
        $status = DB::transaction(function () use ($money, $task_id, $uid, $code, $type) {
            //扣除用户的余额
            $query = DB::table('user_detail')->where('uid', '=', $uid);
            $query->where(function ($query) {
                $query->where('balance_status', '!=', 1);
            })->decrement('balance', $money);
            //修改任务的赏金托管状态
            $data = self::where('id', $task_id)->update(['bounty_status' => 1]);
            //生成财务记录，action 1表示发布任务
            $financial = [
                'action' => 1,
                'pay_type' => $type,
                'cash' => $money,
                'uid' => $uid,
                'created_at' => date('Y-m-d H:i:s', time())
            ];
            FinancialModel::create($financial);
            //修改订单状态
            OrderModel::where('code', $code)->update(['status' => 1]);

            //修改用户的托管状态
            self::where('id', '=', $task_id)->update(['status' => 0]);

            //增加用户的发布任务数量
            UserDetailModel::where('uid',$uid)->increment('publish_task_num',1);
        });

        return is_null($status)?true:false;
    }
    /**
     * 赏金托管数据操作
     * @param $money
     * @param $uid
     * @param $task_id
     */
    static function bounty($money, $task_id, $uid, $code, $type = 1)
    {
       $status = DB::transaction(function () use ($money, $task_id, $uid, $code, $type) {
            //扣除用户的余额
            $query = DB::table('user_detail')->where('uid', '=', $uid);
            $query->where(function ($query) {
                $query->where('balance_status', '!=', 1);
            })->decrement('balance', $money);
            //修改任务的赏金托管状态
            $data = self::where('id', $task_id)->update(['bounty_status' => 1]);
            //生成财务记录，action 1表示发布任务
            $financial = [
                'action' => 1,
                'pay_type' => $type,
                'cash' => $money,
                'uid' => $uid,
                'created_at' => date('Y-m-d H:i:s', time())
            ];
            FinancialModel::create($financial);
            //修改订单状态
            OrderModel::where('code', $code)->update(['status' => 1]);

            //修改用户的托管状态
            //判断用户的赏金是否大于系统的任务审核金额
            $bounty_limit = \CommonClass::getConfig('task_bounty_limit');
            if ($bounty_limit < $money) {
                self::where('id', '=', $task_id)->update(['status' => 3]);
            } else {
                self::where('id', '=', $task_id)->update(['status' => 2]);
            }
            //增加用户的发布任务数量
           UserDetailModel::where('uid',$uid)->increment('publish_task_num',1);
        });
        //如果托管成功就发送一条系统消息
        if(is_null($status))
        {
            //判断当前的任务发布成功之后是否需要发送系统消息
            $task_publish_success = MessageTemplateModel::where('code_name','task_publish_success')->where('is_open',1)->where('is_on_site',1)->first();
            if($task_publish_success)
            {
                $task = self::where('id',$task_id)->first()->toArray();
                $task_status = [
                    'status' => [
                        0 => '暂不发布',
                        1 => '已经发布',
                        2 => '赏金托管',
                        3 => '审核通过',
                        4 => '威客交稿',
                        5 => '雇主选稿',
                        6 => '任务公示',
                        7 => '交付验收',
                        8 => '双方互评'
                    ]
                ];
                $task = \CommonClass::intToString([$task],$task_status);
                $task = $task[0];
                $user = UserModel::where('id',$uid)->first();//必要条件
                $site_name = \CommonClass::getConfig('site_name');//必要条件
                $domain = \CommonClass::getDomain();
                //组织好系统消息的信息
                //发送系统消息
                $messageVariableArr = [
                    'username'=>$user['name'],
                    'task_number'=>$task['id'],
                    'task_title'=>$task['title'],
                    'task_status'=>$task['status_text'],
                    'website'=>$site_name,
                    'task_number'=>$task['id'],
                    'task_link'=>$task['title'],
                    'start_time'=>$task['begin_at'],
                    'manuscript_end_time'=>$task['delivery_deadline'],
                ];
                $message = MessageTemplateModel::sendMessage('task_publish_success',$messageVariableArr);
                $data = [
                    'message_title'=>$task_publish_success['name'],
                    'code'=>'task_publish_success',
                    'message_content'=>$message,
                    'js_id'=>$user['id'],
                    'message_type'=>2,
                    'receive_time'=>date('Y-m-d H:i:s',time()),
                    'status'=>0,
                ];
                MessageReceiveModel::create($data);
            }
        }
        return is_null($status)?true:false;
    }

    /**
     * 查询任务详情
     * @param $id
     */
    static function detail($id)
    {
        $query = self::select('task.*', 'a.name as user_name', 'b.name as type_name', 'sb.name as sub_type_name', 'c.name as cate_name','province.name as province_name','city.name as city_name','area.name as area_name','enterprise_auth.company_name')
            ->where('task.id', '=', $id);
        //赏金已经托管
        $query = $query->where(function ($query) {
            $query->where('task.status', '>=', 2)->where('bounty_status',1);
        });
        $data = $query->join('users as a', 'a.id', '=', 'task.uid')
            ->leftjoin('task_type as b', 'b.id', '=', 'task.type_id')
            ->leftjoin('task_type as sb', 'sb.id', '=', 'task.sub_type_id')            
            ->leftjoin('district as province','province.id','=','task.province')
            ->leftjoin('district as city','city.id','=','task.city')
            ->leftjoin('district as area','area.id','=','task.area')
            ->leftjoin('cate as c', 'c.id', '=', 'task.cate_id')
            ->leftjoin('enterprise_auth', 'task.uid', '=', 'enterprise_auth.uid')
            ->first();
        return $data;
    }

    /**
     * 查询任务详情
     * @param $id
     */
    static function detail2($id)
    {
        $query = self::select('task.*', 'b.name as type_name', 'sb.name as sub_type_name', 
            'enterprise_auth.company_name','province.name as province_name','city.name as city_name','area.name as area_name')
        ->where('task.id', '=', $id);
        //赏金已经托管
        $query = $query->where(function ($query) {
            $query->where('task.status', '>=', 2)->where('bounty_status',1);
        });
            $data = $query
            //->join('users as a', 'a.id', '=', 'task.uid')
            ->leftjoin('task_type as b', 'b.id', '=', 'task.type_id')
            ->leftjoin('task_type as sb', 'sb.id', '=', 'task.sub_type_id')
            ->leftjoin('district as province','province.id','=','task.province')
            ->leftjoin('district as city','city.id','=','task.city')
            ->leftjoin('district as area','area.id','=','task.area')
            ->leftjoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
            ->first();
            return $data;
    }
    
    /**
     * 查询任务详情
     * @param $id
     */
    static function detail3($id)
    {
        $query = self::select('task.*', 'b.name as type_name', 'sb.name as sub_type_name',
            'enterprise_auth.company_name','province.name as province_name','city.name as city_name','area.name as area_name',
            'wrn.realname as w_realname','wrn.card_number as w_card_number','wrn.account as w_account','wu.mobile as w_mobile','work.checked_at','work.payment','work.status as w_status')
            ->where('task.id', '=', $id);
            //赏金已经托管
            $query = $query->where(function ($query) {
                $query->where('task.status', '>=', 2)->where('bounty_status',1);
            });
                $data = $query
                //->join('users as a', 'a.id', '=', 'task.uid')
                ->leftjoin('task_type as b', 'b.id', '=', 'task.type_id')
                ->leftjoin('task_type as sb', 'sb.id', '=', 'task.sub_type_id')
                ->leftjoin('district as province','province.id','=','task.province')
                ->leftjoin('district as city','city.id','=','task.city')
                ->leftjoin('district as area','area.id','=','task.area')
                ->leftjoin('enterprise_auth', 'enterprise_auth.uid', '=', 'task.uid')
                ->leftjoin('work', 'task.id', '=', 'work.task_id')
                ->leftjoin('users as wu', 'work.uid', '=', 'wu.id')
                ->leftjoin('realname_auth as wrn', 'work.uid', '=', 'wrn.uid')
                ->get();
                return $data;
    }
    

    /**
     * 查找相似的任务
     * @param $cate_id
     */
    static function findByCate($cate_id, $id)
    {
        $query = self::where('cate_id', '=', $cate_id);
        $query = $query->where(function ($query) use ($id) {
            $query->where('id', '!=', $id);
        });
        //赏金已经托管的任务
        $query = $query->where(function ($query) {
            $query->where('status', '>', 2);
        });
        //没有到截稿时间
        $query = $query->where(function ($query) {
            $query->where('delivery_deadline', '>', date('Y-m-d H:i:s', time()));
        });
        $data = $query->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        return $data;
    }

    /**
     * 判断是不是雇主
     */
    static function isEmployer($task_id, $uid)
    {
        $data = self::where('id', $task_id)->first();
        if ($data['uid'] == $uid)
            return true;
        return false;
    }

    /**
     * 赏金分配
     * @param $id
     * @param $uid
     */
    static public function distributeBounty($id, $uid)
    {
        //计算赏金
        $bounty = self::where('id', $id)->first();
        $bounty = ($bounty['bounty'] / $bounty['worker_num']) * (1 - sprintf("%.2f", $bounty['task_success_draw_ratio']/100));

        $status = DB::transaction(function () use ($bounty, $uid) {
            //增加用户余额
            UserDetailModel::where('uid', $uid)->increment('balance', $bounty);
            //产生一笔财务流水 表示接受任务产生的钱
            $finance_data = [
                'action' => 2,
                'pay_type' => 1,
                'cash' => $bounty,
                'uid' => $uid,
                'create_at'=>date('Y-m-d H:i:s',time())
            ];
            FinancialModel::create($finance_data);
        });

        return is_null($status) ? true : false;
    }



    /**
     * 任务验收通过和任务验收失败
     * @param $task 相关任务数据
     * @param $type 操作类型1表示验收通过2表示验收失败
     */
    static function employAccept($task,$type)
    {
        $status = DB::transeaction(function() use($task,$type)
        {
            //验收通过
            if($type==1)
            {
                //将任务状态修改成3验收通过
                TaskModel::where('id',$task['id'])->update(['status'=>3]);
                //将任务的稿件修改成验收通过
                $employee_user = EmployUserModel::where('task_id',$task['id'])->first();
                //将任务的托管金打给威客，并生成记录
                self::distributeBounty($task['id'],$employee_user['uid']);
                $bounty = self::where('id', $task['id'])->first();
                $bounty = ($bounty['bounty'] / $bounty['worker_num']) * (1 - $bounty['task_success_draw_ratio']);
                //增加用户余额
                UserDetailModel::where('uid', $employee_user['uid'])->increment('balance', $bounty);
                //产生一笔财务流水 表示接受任务产生的钱
                $finance_data = [
                    'action' => 2,
                    'pay_type' => 1,
                    'cash' => $bounty,
                    'uid' => $employee_user['uid'],
                    'create_at'=>date('Y-m-d H:i:s',time())
                ];
                FinancialModel::create($finance_data);

            }else if($type==2)
            {

            }
        });
    }
}
