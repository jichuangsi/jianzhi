<?php
use App\Modules\Manage\Model\ConfigModel;
use App\Modules\Task\Model\TaskFocusModel;
use App\Modules\Task\Model\TaskServiceModel;
use App\Modules\Task\Model\WorkModel;
use App\Modules\User\Model\AuthRecordModel;
use App\Modules\User\Model\CommentModel;
use App\Modules\User\Model\DistrictModel;
use App\Modules\User\Model\TaskModel;
use App\Modules\User\Model\UserDetailModel;
use App\Modules\User\Model\UserFocusModel;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Auth;
use Teepluss\Theme\Facades\Theme;
use Illuminate\Support\Facades\Session;


class CommonClass
{
    /**
     * 生成随机码
     *
     * @param int $length
     * @param bool|false $intmode
     * @return string
     */
    static function random($length, $intmode = false)
    {
        $hash = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $intmode and $chars = "0123456789";
        $max = strlen($chars) - 1;
        PHP_VERSION < '4.2.0' && mt_srand(( double )microtime() * 1000000);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars [mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 检测验证码是否正确
     *
     * @param string $code
     * @return bool
     */
    static function checkCode($code)
    {
        $builder = new CaptchaBuilder(Session::get('phrase'));
        if ($builder->testPhrase($code)) {
            return true;
        }
        return false;
    }

    /**
     * 生成验证码图片
     *
     * @param  int $width
     * @param  int $height
     * @return string
     */
    static function getCodes($width = 120, $height = 35)
    {
        $builder = new CaptchaBuilder();
        $builder->build($width, $height);
        $code = $builder->inline();

        $phrase = $builder->getPhrase();
        Session::put('phrase', $phrase);
        return $code;
    }

    /**
     * ajax请求格式化响应
     *
     * @param $msg
     * @param int $code
     * @param string $data
     * @return string
     */
    static function formatResponse($msg, $code = 200, $data = '')
    {
        $result['code'] = $code;
        $result['message'] = $msg;

        if (!is_array($data) && CommonClass::isJson($data)) {
            $result['data'] = json_decode($data, true);
        } else {
            $result['data'] = $data;
        }

        return json_encode($result);
    }


    /**
     * 字符串星号替换
     *
     * @param str $str
     * @param int $start
     * @param int $length
     * @return str
     */
    static function starReplace($str, $start, $length = 0)
    {
        $i = 0;
        $star = '';
        if ($start >= 0) {
            if ($length > 0) {
                $str_len = strlen($str);
                $count = $length;
                if ($start >= $str_len) {//当开始的下标大于字符串长度的时候，就不做替换了
                    $count = 0;
                }
            } elseif ($length < 0) {
                $str_len = strlen($str);
                $count = abs($length);
                if ($start >= $str_len) {//当开始的下标大于字符串长度的时候，由于是反向的，就从最后那个字符的下标开始
                    $start = $str_len - 1;
                }
                $offset = $start - $count + 1;//起点下标减去数量，计算偏移量
                $count = $offset >= 0 ? abs($length) : ($start + 1);//偏移量大于等于0说明没有超过最左边，小于0了说明超过了最左边，就用起点到最左边的长度
                $start = $offset >= 0 ? $offset : 0;//从最左边或左边的某个位置开始
            } else {
                $str_len = strlen($str);
                $count = $str_len - $start;//计算要替换的数量
            }
        } else {
            if ($length > 0) {
                $offset = abs($start);
                $count = $offset >= $length ? $length : $offset;//大于等于长度的时候 没有超出最右边
            } elseif ($length < 0) {
                $str_len = strlen($str);
                $end = $str_len + $start;//计算偏移的结尾值
                $offset = abs($start + $length) - 1;//计算偏移量，由于都是负数就加起来
                $start = $str_len - $offset;//计算起点值
                $start = $start >= 0 ? $start : 0;
                $count = $end - $start + 1;
            } else {
                $str_len = strlen($str);
                $count = $str_len + $start + 1;//计算需要偏移的长度
                $start = 0;
            }
        }

        while ($i < $count) {
            $star .= '*';
            $i++;
        }

        return substr_replace($str, $star, $start, $count);

    }

    /**
     * 获取域名
     * @return string
     */
    static function getDomain()
    {
        return url();
    }

    /**
     * 检测字符串为json数据
     *
     * @param $str
     * @return bool
     */
    static function isJson($str)
    {
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * 用户中心页面跳转
     *
     * @param $msg
     * @param int $time
     * @return mixed
     */
    static function showMessage($msg, $time = 3000)
    {
        $theme = Theme::uses('default')->layout('usercenter');

        $data = array(
            'msg' => $msg,
            'time' => $time
        );
        return $theme->of('errors.msg', $data)->render();
    }

    /**
     * 后台页面跳转
     *
     * @param $msg
     * @param int $time
     * @return mixed
     */
    static function adminShowMessage($msg, $time = 3000)
    {
        $theme = Theme::uses('default')->layout('manage');

        $data = array(
            'msg' => $msg,
            'time' => $time
        );
        return $theme->of('errors.msg', $data)->render();
    }

    /**
     * 获取配置项规则
     *
     * @param string $alias
     * @return string
     */
    static function getConfig($alias)
    {
        $arrConfig = ConfigModel::where('alias', $alias)->first();
        return $arrConfig['rule'];
    }

    /**
     * @param $data
     * @param array $map
     * @return array
     */
    static function intToString($data, $map = array('status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')))
    {
        if ($data === false || $data === null) {
            return $data;
        }
        $data = (array)$data;
        foreach ($data as $key => $row) {
            foreach ($map as $col => $pair) {
                if (isset($row[$col]) && isset($pair[$row[$col]])) {
                    $data[$key][$col . '_text'] = $pair[$row[$col]];
                }
            }
        }
        return $data;
    }

    /**
     * 数组转换成xml
     *
     * @param $arr
     * @return string
     */
    static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";

            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }

        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * xml转换成array
     *
     * @param $xml
     * @return mixed
     */
    static function xmlToArray($xml)
    {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * 获取参数
     *
     * @param $queryString
     * @return mixed
     */
    static function getParamByQueryString($queryString)
    {
        parse_str(preg_replace("/(\w+)=/", '$1[]=', $queryString), $arr);
        foreach ($arr as $k => $v) {
            if (count($v) > 1)
                $$k = $v;
            else
                $$k = $v[0];
        }
        return $arr;
    }

    /**
     * 数组以某个字段的值为键值
     * @param $data
     * @param $key
     * @return array
     */
    static function keyBy($data,$key)
    {
        $array = array();
        foreach($data as $v)
        {
            $array[$v[$key]] = $v;
        }

        return $array;
    }

    /**
     * 数组以某一个字段值为键，并分组
     * @param $proArr
     * @param $money
     * @return string
     */
    static function keyByGroup($data,$key)
    {
        $array = array();
        foreach($data as $v)
        {
            $array[$v[$key]][] = $v;
        }

        return $array;
    }
    static function get_rand($proArr,$money) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);

        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = $money;
            if ($randNum <= $proCur) {
                $result = $proCur;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        return $result;
    }
    static function attachmentUnserialize()
    {
        $attachment_config = Self::getConfig('attachment');
        $attachment_config = json_decode($attachment_config,true);

        $attachment_config['extensions'] = explode('|',$attachment_config['extension']);
        foreach($attachment_config['extensions'] as $k=>$v)
        {
            $attachment_config['extensions'][$k] = '.'.$v;
        }

        return json_encode($attachment_config);
    }

    static function applauseRate($id)
    {
        $applauseRate = CommentModel::applauseRate($id);
        return $applauseRate;
    }

    /**
     * 获取用户头像
     *
     * @param $uid
     * @return mixed
     */
    static function getAvatar($uid)
    {
        $avatar = \App\Modules\User\Model\UserDetailModel::select('avatar')->where('uid', $uid)->first();

        return !empty($avatar['avatar'])?$avatar['avatar']:'';
    }

    /**
     * 首页获取公告
     * @return array
     */
    static function getHomepageNotice()
    {
        //公告
        $cate = \App\Modules\Manage\Model\ArticleCategoryModel::select('id')->where('cate_name','公告')->first();
        if(!empty($cate)){
            $catID = $cate->id;
            if(!empty($catID)){
                $noticeArticle = \App\Modules\Manage\Model\ArticleModel::where('cat_id',$catID)->orderBy('updated_at','DESC')->paginate(5)->toArray();
            }else{
                $noticeArticle['data'] = array();
            }
        }else{
            $noticeArticle['data'] = array();
        }

        return $res = array(
            'notice_article' => $noticeArticle['data']
        );
    }

    /**
     * 首页获取中标通知
     * @return array
     */
    static function getTaskWin()
    {
        //中标通知
        $taskWinArr = \App\Modules\Manage\Model\MessageTemplateModel::where('code_name','task_win')->first();
        if(!empty($taskWinArr)){
//            $taskWinTitle = $taskWinArr->name;
            if (Auth::check()){
                $user = Auth::User();
                $taskWin = \App\Modules\User\Model\MessageReceiveModel::where('code_name','task_win')->where('message_type',2)->where('js_id',$user->id)->orderBy('receive_time','DESC')->paginate(5)->toArray();
            }else{
                $taskWin = \App\Modules\User\Model\MessageReceiveModel::where('code_name','task_win')->where('message_type',2)->orderBy('receive_time','DESC')->paginate(5)->toArray();
            }
        }else{
            $taskWin['data'] = array();
        }
        return $res = array(
            'task_win' => $taskWin['data']
        );
    }

    /**
     * 首页提现消息
     * @return array
     */
    static function getWithdrawSuccess()
    {
        //提现
        $withdrawSuccessArr = \App\Modules\Manage\Model\MessageTemplateModel::where('code_name','withdraw_success')->first();
        if(!empty($withdrawSuccessArr)){
            if (Auth::check()){
                $user = Auth::User();
                $withdrawSuccess = \App\Modules\User\Model\MessageReceiveModel::where('code_name','withdraw_success')->where('message_type',1)->where('js_id',$user->id)->orderBy('receive_time','DESC')->paginate(5)->toArray();
            }else{
                $withdrawSuccess = \App\Modules\User\Model\MessageReceiveModel::where('code_name','withdraw_success')->where('message_type',1)->orderBy('receive_time','DESC')->paginate(5)->toArray();
            }
        }else{
            $withdrawSuccess['data'] = array();
        }
        return $res = array(
            'withdraw_success' => $withdrawSuccess['data']
        );
    }

    /**
     * 首页banner图
     * @return array
     */
    static function getHomepageBanner()
    {
        $adTargetID = \App\Modules\Advertisement\Model\AdTargetModel::where('code','HOME_TOP_SLIDE')->select('target_id')->first();
        if($adTargetID['target_id']){
            $adPicInfo = \App\Modules\Advertisement\Model\AdModel::where('target_id',$adTargetID['target_id'])->where('is_open',1)
                ->where(function($adPicInfo){
                    $adPicInfo->where('end_time','0000-00-00 00:00:00')
                        ->orWhere('end_time','>',date('Y-m-d H:i:s',time()));
                })
                ->select('ad_file','ad_url','listorder')
                ->orderby('listorder','ASC')->get()->toArray();
            if(count($adPicInfo) > 0){
                $banner = $adPicInfo;
            }else{
                $banner = [];
            }
        }else{
            $banner = [];
        }
        return $banner;
    }

    //投诉建议登陆用户信息
    static function getPhone()
    {
        $useDetail = [];
        $user = Auth::User();
        if($user){
            $useDetail = UserDetailModel::where('uid',$user->id)->select('uid','mobile')->first();
        }
        $userInfo = $useDetail;
        return $userInfo;
    }
    //任务的服务匹配
    static function service($task_id)
    {
        $service = TaskServiceModel::select('task_service.*','sc.title')->where('task_id',$task_id)
            ->join('service as sc','sc.id','=','task_service.service_id')
            ->get();
        return $service;
    }
    //统计某个人发布的任务各种状态的数值
    static function pie($id)
    {
        $pie_json = '[{ "label": "工作中",  "data": 38.7, "color": "#68BC31"},
        { "label": "选稿中",  "data": 24.5, "color": "#2091CF"},
        { "label": "交付中",  "data": 8.2, "color": "#AF4E96"},
        { "label": "已结束",  "data": 18.6, "color": "#DA5430"},
        { "label": "其他",  "data": 10, "color": "#FEE074"}]';
        $pie_array = json_decode($pie_json,true);
        //查询当前用户的所有已经发布的任务
        $task = TaskModel::where('uid',$id)->where('status','>=',2)->count();

        if($task==0)
        {
            return false;
        }
        //当前工作中的任务
        $work_task = TaskModel::where('uid',$id)->whereIn('status',[3,4,6])->count();
        $percent[] = ($work_task/$task)*100;
        //当前选稿中的任务
        $choose_task = TaskModel::where('uid',$id)->where('status',5)->count();
        $percent[] = ($choose_task/$task)*100;
        //交付中的任务
        $delivery_task = TaskModel::where('uid',$id)->where('status',7)->count();
        $percent[] = ($delivery_task/$task)*100;
        //已结束的任务
        $end_task = TaskModel::where('uid',$id)->whereIn('status',[8,9,10])->count();

        $percent[] = ($end_task/$task)*100;
        //其他
        $other = 100-array_sum($percent);
        $percent[] = $other;
        foreach($pie_array as $k=>$v)
        {
            if($percent[$k]!=0)
            {
                $pie_array[$k]['data'] = $percent[$k];
            }else{
                $pie_array = array_except($pie_array,[$k]);
            }
        }
        shuffle($pie_array);
        return json_encode($pie_array);
    }
    //获取地区
    static function getRegion($id)
    {
        $city = DistrictModel::where('id',$id)->first();
        $province = DistrictModel::where('id',$city['upid'])->first();
        $name = $province['name'].$city['name'];

        return $name;
    }

    static function getEditorInit($dislodge=0)
    {
        $plugins = '["font",null,"fontSize",null,{"name":"bold", "className":"btn-info"},{"name":"italic", "className":"btn-info"},{"name":"strikethrough", "className":"btn-info"},
            {"name":"underline", "className":"btn-info"},
            null,
            {"name":"insertunorderedlist", "className":"btn-success"},
            {"name":"insertorderedlist", "className":"btn-success"},
            {"name":"outdent", "className":"btn-purple"},
            {"name":"indent", "className":"btn-purple"},
            null,
            {"name":"justifyleft", "className":"btn-primary"},
            {"name":"justifycenter", "className":"btn-primary"},
            {"name":"justifyright", "className":"btn-primary"},
            {"name":"justifyfull", "className":"btn-inverse"},
            null,
            {"name":"createLink", "className":"btn-pink"},
            {"name":"unlink", "className":"btn-pink"},
            null,
            {"name":"insertImage", "className":"btn-success"},
            null,
            "foreColor",
            null,
            {"name":"undo", "className":"btn-grey"},
            {"name":"redo", "className":"btn-grey"}
            ]';
        $plugins = json_decode($plugins,true);
        if($dislodge!=0 && is_array($dislodge))
        {
            $plugins_new = array();
            foreach($plugins as $k=>$v)
            {
                if(is_array($v) && in_array($v['name'],$dislodge))
                {

                }else{
                    $plugins_new[] = $v;
                }
            }
            return json_encode($plugins_new);
        }
        return json_encode($plugins);
    }

    /**
     * 判断当前任务是否评价过
     * @param $work_id
     */
    static function evaluted($task_id,$uid)
    {
        $comment = CommentModel::where('task_id',$task_id)->where('from_uid',$uid)->first();
        if($comment)
        {
            return 1;
        }else{
            return 0;
        }
    }
    static function ownerEvalute($task_id,$uid,$worker)
    {
        $comment = CommentModel::where('task_id',$task_id)->where('from_uid',$uid)->where('to_uid',$worker)->first();
        if($comment)
        {
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 判断两个用户的关注关系
     * @param $uid
     * @param $focus_uid
     * @return int
     */
    static function focusCheck($uid,$focus_uid)
    {
        $result = UserFocusModel::where('uid',$uid)->where('focus_uid',$focus_uid)->first();
        if($result)
        {
            return 1;
        }
        return 0;
    }



    /**
     * 获取一个用户的认证状态
     * @param $uid
     * @return array
     */
    static function getUserAuthData($uid,$type='realname')
    {
        $userAuth = AuthRecordModel::where('uid', $uid)->where('status', 2)->where('auth_code','!=','realname')->orWhere(['status' => 1, 'auth_code' => 'realname','uid' => $uid])->get()->toArray();
        $realName = false;
        $bank = false;
        $alipay = false;

        if (!empty($userAuth) && is_array($userAuth)) {
            foreach ($userAuth as $k => $v) {
                $authCode[] = $v['auth_code'];
            }
            if (in_array('realname', $authCode)) $realName = true;
            if (in_array('bank', $authCode)) $bank = true;
            if (in_array('alipay', $authCode)) $alipay = true;
        }

        switch($type)
        {
            case 'realname';
                $result = $realName;
                break;
            case 'bank':
                $result = $bank;
                break;
            case 'alipay':
                $result = $alipay;
                break;
        }

        return $result;
    }

    /**
     * 验证邮箱是否验证
     * @param $uid
     * @return mixed
     */
    static function checkEmailAuth($uid)
    {
        $user_detail = \App\Modules\User\Model\UserModel::where('id',$uid)->first();
        return $user_detail['email_status'];
    }
    static function changeTimeType($seconds)
    {
        $one_day = 3600*24;
        if ($seconds>$one_day){
            $day = floor($seconds/$one_day);
            $hour = $seconds%$one_day;
            $hour = floor($hour/3600);
            return $day.'天'.$hour.'时';
        }elseif($seconds>3600){
            $hour = floor($seconds/3600);
            $mimute = $seconds%3600;
            $mimute = floor($mimute/60);
            return $hour.'时'.$mimute.'分';
        }elseif($seconds>60)
        {
            $mimute = floor($seconds/60);
            return $mimute.'分';
        }
        return $seconds.'秒';
    }


    /**
     * 从配置项里获取域名配置
     * @return string
     */
    static function domain()
    {
        $domain = ConfigModel::where('alias','site_url')->where('type','site')->select('rule')->first();
        return $domain->rule;
    }
    static function taskFocus($uid,$task_id)
    {
        $result = TaskFocusModel::where('uid',$uid)->where('task_id',$task_id)->first();
        if($result)
        {
            return 1;
        }
        return 0;
    }
    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    static function listToTree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    static function group($data,$number)
    {
        $array = [];
        $group_number = floor(count($data)/$number)+1;
        if($group_number==1)
        {
            $array[] = $data;
        }else{
            for($i=0;$i<$group_number;$i++)
            {
                $array[] = array_slice($data,$i*$number,$number);
            }
        }
        return $array;
    }

    /**
     * 联系客服
     *
     * @param $qq
     * @return string
     */
    static function contactClient($qq)
    {
        return 'http://wpa.qq.com/msgrd?v=3&amp;uin='. $qq .'&amp;site=qq&amp;menu=yes';
    }

    //统计某个人承接的任务各种状态的数值
    static function myTaskPie($id)
    {
        $pie_json = '[{ "label": "工作中",  "data": 38.7, "color": "#68BC31"},
        { "label": "选稿中",  "data": 24.5, "color": "#2091CF"},
        { "label": "交付中",  "data": 8.2, "color": "#AF4E96"},
        { "label": "已结束",  "data": 18.6, "color": "#DA5430"},
        { "label": "其他",  "data": 10, "color": "#FEE074"}]';
        $pie_array = json_decode($pie_json,true);
        //查询当前用户的所有已经承接的任务
        $task = WorkModel::where('uid',$id)->select('task_id')->count();
        if($task==0)
        {
            return false;
        }
        $task_id = WorkModel::where('uid',$id)->select('task_id')->get()->toArray();
        $task_id = array_unique(array_flatten($task_id));

        //当前工作中的任务
        $work_task = TaskModel::whereIn('id',$task_id)->whereIn('status',[3,4,6])->count();
        $percent[] = number_format($work_task/$task,1)*10;
        //当前选稿中的任务
        $choose_task = TaskModel::whereIn('id',$task_id)->where('status',5)->count();
        $percent[] = number_format($choose_task/$task,1)*10;
        //交付中的任务
        $delivery_task = TaskModel::whereIn('id',$task_id)->where('status',7)->count();
        $percent[] = number_format($delivery_task/$task,1)*10;
        //已结束的任务
        $end_task = TaskModel::whereIn('id',$task_id)->whereIn('status',[8,9,10])->count();
        $percent[] = number_format($end_task/$task,1)*10;
        //其他
        $other = 100-array_sum($percent);
        $percent[] = $other;

        foreach($pie_array as $k=>$v)
        {
            if($percent[$k]!=0)
            {
                $pie_array[$k]['data'] = $percent[$k];
            }else{
                $pie_array = array_except($pie_array,[$k]);
            }
        }
        shuffle($pie_array);
        return json_encode($pie_array);
    }
    //防止跨站攻击
    static public function removeXss($val)
    {
        $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search.= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search.= '1234567890!@#$%^&*()';
        $search.= '~`";:?+/={}[]-_|\'\\';

        for ($i = 0; $i < strlen($search); $i++) {
            $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val);
        }

        $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta','blink', 'link',  'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound');
        $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint',
            'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged',
            'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange',
            'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave',
            'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize',
            'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);

        $found = true;
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                        $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
                        $pattern .= ')?';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
                $val = preg_replace($pattern, $replacement, $val);
                if ($val_before == $val) {
                    $found = false;
                }
            }
        }
        return $val;
    }

    /**
     * 跳转到首页
     * @return string
     */
    static function homePage()
    {
        $homePageUrl = '/';
        return $homePageUrl;
    }
    static function taskScheduling()
    {
        //判断当前系统是否是win
        if(PATH_SEPARATOR==';')
        {
            //取cache里边的上一次执行任务调度命令的时间
            if(!Cache::has('lastTaskSchedulingTime'))
            {
                //第一次执行任务调度
                Cache::forever('lastTaskSchedulingTime', time());
                self::taskSchedulingArtisan();
            }else
            {

                //判断当前时间是否超过上一次执行时间一天
                $last_time = Cache::get('lastTaskSchedulingTime');
                $one_day = 24*3600;
                if(($last_time+$one_day)<time())
                {
                    self::taskSchedulingArtisan();
                    Cache::forever('lastTaskSchedulingTime', time());
                }
            }
        }
    }
    static function taskSchedulingArtisan()
    {
        Artisan::call('taskWork');//投稿
        Artisan::call('taskSelectWork');//选稿
        Artisan::call('taskPublicity');//公示
        Artisan::call('taskDelivery');//选稿
        Artisan::call('taskComment');//评论
        Artisan::call('taskNoStick');//置顶过期
    }

    /**
     * 判断某用户是否被关注
     * @param $uid 被关注用户id
     * @return array
     */
    static function isFocus($uid)
    {
        if(Auth::check()){
            $userId = Auth::user()->id;
            //查询是否被关注
            $isFocus = UserFocusModel::where('uid',$userId)->where('focus_uid',$uid)->first();
        }else{
            $isFocus = [];
        }
        return $isFocus;
    }

    /**
     * 将二维数组中的某个字段取出来
     * @param array $array
     * @param string $fild
     * @return array
     */
    static function getList($array=array(),$fild='id')
    {
        $data = array();
        foreach($array as $v)
        {
            $data[] = isset($v[$fild])?$v[$fild]:$v;
        }
        return $data;
    }

    /**
     * 判断env函数值是否为空
     * @param $key
     * @return bool
     */
    static function checkEnvIsNull($key)
    {
        $value = env($key);
        if($value == '')
        {
            return false;
        }else{
            return true;
        }
    }

    /**
     * 查询env文件中某一变量的值
     * @param $key
     * @return mixed|string
     */
    static function findEnvInfo($key)
    {
        if(array_key_exists($key,$_ENV))
        {
            $envInfo = env($key)?env($key):($_ENV[$key]?$_ENV[$key]:'');
        }else{
            $envInfo = env($key);
        }
        return $envInfo;
    }
    
    //new function for SMS
    static function sendSms($mobile){
        $smsConfig = ConfigModel::getConfigByType('sms');
        
        $phone = $mobile;//手机号
        $signName = $smsConfig['sms_signname'];   //短信签名（注意不是工单号）
        $appid =  $smsConfig['sms_accesskeyid']; //appid
        $secret = $smsConfig['sms_accesskeysecret']; //秘钥
        $tplid = $smsConfig['sms_templatecode'];      //模板id
        
        $tplParam = json_encode(['code' => \CommonClass::getCaptchaCode()]);
        
        date_default_timezone_set("UTC");
        $params = [
            'AccessKeyId' => $appid,                 // appid
            'Timestamp'   => date('Y-m-d\TH:i:s\Z'), //时间
            'SignatureMethod'=>'HMAC-SHA1',  //固定值
            'SignatureVersion'=> '1.0',     //固定值
            'SignatureNonce'  => uniqid(),  //随机码(这是PHP函数基于以微秒计的当前时间，生成一个唯一的ID)
            //'Signature' => ''
            'Format'      => 'JSON',        //返回的数据类型(如果不传官方说默认JSON经测试是XML)
            //业务参数
            'Action'       => 'SendSms',     //固定值不要更改
            'Version'     => '2017-05-25',   //API的版本(固定值)
            'RegionId'    => 'cn-hangzhou',  //API支持的RegionID(固定值)
            'PhoneNumbers' => $phone,        //接收号码
            'SignName'      => $signName,    //签名
            'TemplateCode'  => $tplid,       //模板id
            'TemplateParam' => $tplParam,    //短信模板变量替换JSON串，如$code
        ];
        //return json_encode($params);
        //排序
        ksort($params);
        
        $str = '';
        
        foreach($params as $k => $v){
            $str .= urlencode($k) . '=' . urlencode($v) . '&';
        }
        
        $str = substr($str,0,-1);//把最后一个'&'符号截取下去
        
        $str = str_replace(['+','*','%7E'],['%20','%2A','~'],$str );//替换字符
        
        $new_str = 'GET&' . urlencode('/') . '&' . urlencode($str);//拼接新字符串
        
        $sign = base64_encode(hash_hmac('sha1', $new_str, $secret . '&',true));
        
        $sign = urlencode($sign);//生成签名
        
        $url = 'http://dysmsapi.aliyuncs.com/?Signature=' . $sign . '&'.  $str;
        
        return \CommonClass::request($url);
        
    }
    
    static function getCaptchaCode($length = 6, $charset = '0123456789'){
        $builder = new PhraseBuilder();
        $captchaCode = $builder->build($length, $charset);
        Session::put('captcha_code', $captchaCode);
        Session::put('send_captcha_code_time', time());
        
        return $captchaCode;
    }
    
    static function checkCaptchaCode($code, $duration = 60)
    {   
        $sendTime = Session::get('send_captcha_code_time');
        $nowTime = time();
        
        if(empty($sendTime)){
            Session::forget('captcha_code');
            Session::forget('send_captcha_code_time');
            return false;
        }else{            
            if($nowTime - $sendTime < intval($duration) ){
                $builder = new PhraseBuilder();
                if($builder->niceize(Session::get('captcha_code')) == $builder->niceize($code)){
                    Session::forget('captcha_code');
                    Session::forget('send_captcha_code_time');
                    return true;
                }else{
                    return false;
                }
            }else{
                Session::forget('captcha_code');
                Session::forget('send_captcha_code_time');
                return false;
            }
        }
    }

    static function request($url = '', $data = array(), $method = 'GET', $second = 30) {
        if (empty($url)) {
            return false;
        }
        
        $ch = curl_init();//初始化curl
        /* $headers = [
         'form-data' => ['Content-Type: multipart/form-data'],
         'json'      => ['Content-Type: application/json'],
         ]; */
        
        
        if($method == 'GET'){
            if($data){
                $querystring = http_build_query($data);
                $url = $url.'?'.$querystring;
            }
        }
        //dump($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);//设置超时
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);// https请求 不验证证书和hosts
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        //curl_setopt($ch, CURLOPT_HTTPHEADER,$headers[$type]);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);//要求结果为字符串且输出到屏幕上
        
        if($method == 'POST'){
            $post_data = "p=" . urlencode(json_encode($data));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');     // 请求方式
            curl_setopt($ch, CURLOPT_POST, TRUE);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        
        $data = curl_exec($ch);//运行curl
        
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_error($ch);
            //$error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }
}