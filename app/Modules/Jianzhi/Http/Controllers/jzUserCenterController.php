<?php

namespace App\Modules\Jianzhi\Http\Controllers;

use Auth;
use App\Modules\User\Model\UserModel;
use App\Modules\User\Model\UserDetailModel;
use App\Modules\Task\Model\TaskCateModel;
use App\Modules\Task\Model\TaskTypeModel;
use App\Modules\Task\Model\TaskModel;
use App\Modules\User\Model\DistrictModel;
use App\Modules\User\Model\UserTagsModel;
use App\Modules\User\Model\RealnameAuthModel;
use App\Modules\User\Model\TagsModel;
use App\Modules\Task\Model\WorkModel;
use App\Modules\Manage\Model\FeedbackModel;
use App\Modules\Jianzhi\Http\Requests\FeedbackRequest;
use App\Modules\User\Http\Requests\UserInfoRequest;
use App\Modules\Jianzhi\Http\Requests\RealnameAuthRequest;
use App\Modules\Jianzhi\Http\Requests\EnterpriseAuthRequest;
use App\Modules\Jianzhi\Http\Requests\EnterpriseInfoRequest;
use Illuminate\Http\Request;
use App\Modules\User\Http\Controllers\UserCenterController as BasicUserCenterController;
use App\Modules\User\Model\EnterpriseAuthModel;
use App\Modules\User\Model\MessageReceiveModel;
use App\Modules\Manage\Model\HelpModel;
use Illuminate\Support\Facades\DB;
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

class jzUserCenterController extends BasicUserCenterController
{    

    public function __construct()
    {
        parent::__construct();
        $this->initTheme('common','jianzhi');
        $this->user = Auth::user();
        $this->middleware('auth');
    }
    
    public function auth(){
        $data = array();
        if($this->user->type===2){
            $path = 'eAuth';
            
            $status = EnterpriseAuthModel::getEnterpriseAuthStatus2($this->user['id']);
        }else{
            $path = 'auth';
            
            $status = RealnameAuthModel::getRealnameAuthStatus2($this->user['id']);    
        }
        
        switch($status){
            case 0: case 1: case 2: {
                $data = [
                    'authStatus' => $status
                ];
                $path = 'authResult';
                break;
            }
        }   
        
        return $this->theme->scope($path,$data)->render();
    
    }
    
    public function info(){
        $data = array();
        if($this->user->type===2){
            $path = 'eInfo';
            
            $authStatus = EnterpriseAuthModel::getEnterpriseAuthStatus2($this->user['id']);
            if($authStatus!=1){
                return redirect('jz/auth');
            }else{
                $userInfo  = UserModel::getUsersById($this->user['id']);
                $enterpriseInfo  = EnterpriseAuthModel::getEnterpriseInfoByUid($this->user['id']);                
                $data = [
                    'einfo' => $enterpriseInfo,
                    'mobile'=>$userInfo[0]->mobile
                ];
            }
            
        }else{
            $path = 'info';
            
            //$uinfo = UserDetailModel::findByUid($this->user['id']);
            
            
            $authStatus = RealnameAuthModel::getRealnameAuthStatus2($this->user['id']);
            
            if($authStatus!=1){
                return redirect('jz/auth');
            }else{
                $userInfo  = UserModel::getUsersById($this->user['id']);
                $account = RealnameAuthModel::select('account')->where('uid', $this->user['id'])->first()->toArray();
                $data = [
                    'uinfo' => ['realname'=>$userInfo[0]->realname,'card_number'=>$userInfo[0]->card_number,'account'=>$account['account'],'mobile'=>$userInfo[0]->mobile],
                ];
            }
            
        }
        return $this->theme->scope($path,$data)->render();
        
    }
        
    public function help(){
        $data = array();
        
        $articleList = HelpModel::whereRaw('1 = 1');
        $list = $articleList->select('help.title', 'help.content','help.created_at')->where('status',1)->orderBy('help.created_at', 'desc')->get()->toArray();
        
        $path = 'help';
        
        $data = [
            'list' => $list
        ];
        
        return $this->theme->scope($path,$data)->render();
    }
    
    public function proposal(){
        $data = array();
        $path = 'proposal';
        return $this->theme->scope($path,$data)->render();
    }
    
    public function contact(){
        $data = array();
        $path = 'contact';
        return $this->theme->scope($path,$data)->render();
    }
    
    public function comment(){
        $data = array();
        $path = 'comment';
        
        $works = array();
        $result = WorkModel::findMyWork2($this->user['id']);
        
        if($result){
            foreach($result as $k => $v){
                if($v['task']&&count($v['task'])>0&&$v['children_comment']&&count($v['children_comment'])>0){
                    if($v['task']['type_id']){
                         $type_name = TaskTypeModel::findById($v['task']['type_id']);
                         $v['task']['type_name'] = $type_name['name'];
                         $v['task']['type_icon'] = $type_name['pic'];
                    }
                    if($v['task']['sub_type_id']){
                        $sub_type_name = TaskTypeModel::findById($v['task']['sub_type_id']);
                        $v['task']['sub_type_name'] = $sub_type_name['name'];
                    }            
                    
                    /* $detail = UserDetailModel::findByUid($v['task']['uid']);
                    if($detail&&$detail['avatar']){
                        $v['task']['avatar'] = $detail['avatar'];
                    } */
                    
                    array_push($works, $v);
                }
            }
        }
        
        $data = [
            'works' => $works
        ];
        
        return $this->theme->scope($path,$data)->render();
    }
    
    public function skill(){
        $data = array();
        $path = 'skill';
        
        //技能标签
        $taskCate = TaskCateModel::findAll();
        //个人标签
        $myTags = UserTagsModel::myTag2($this->user['id']);
        
        if($myTags&&count($myTags)>0){
            foreach($taskCate as $k => &$v){
                if(isset($v['children_task'])){
                    foreach($v['children_task'] as $k1 => &$v1){
                        foreach($myTags as $k2 => $v2){
                            if($v1['id']===$v2['cate_id']){
                                $v1['checked'] = 1;
                            }
                        }
                    }
                }
            }
        }        
        
        $data = array(
            'taskCate' => $taskCate
        );
        
        return $this->theme->scope($path,$data)->render();
    }
    
    /**
        * 个人实名认证
     * @param RealnameAuthRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createRealnameAuth(RealnameAuthRequest $request){
        
        $card_front_side = $request->file('card_front_side');
        $card_back_dside = $request->file('card_back_dside');
        
        $realnameInfo = array();
        $authRecordInfo = array();
        $error = array();
        $allowExtension = array('jpg', 'gif', 'jpeg', 'bmp', 'png');
        if ($card_front_side) {
            $uploadMsg = json_decode(\FileClass::uploadFile($card_front_side, 'user', $allowExtension));
            if ($uploadMsg->code != 200) {
                $error['card_front_side'] = $uploadMsg->message;
            } else {
                $realnameInfo['card_front_side'] = $uploadMsg->data->url;
            }
        }
        if ($card_back_dside) {
            $uploadMsg = json_decode(\FileClass::uploadFile($card_back_dside, 'user', $allowExtension));
            if ($uploadMsg->code != 200) {
                $error['card_back_dside'] = $uploadMsg->message;
            } else {
                $realnameInfo['card_back_dside'] = $uploadMsg->data->url;
            }
        }
        
        if (!empty($error)) {
            return back()->withErrors($error)->withInput();
        }
        
        $user = $this->user;
        
        $now = time();
        
        $realnameInfo['uid'] = $user->id;
        $realnameInfo['username'] = $user->name;
        $realnameInfo['realname'] = $request->get('realname');
        $realnameInfo['card_number'] = $request->get('card_number');
        $realnameInfo['account'] = $request->get('account');
        $realnameInfo['created_at'] = date('Y-m-d H:i:s', $now);
        $realnameInfo['updated_at'] = date('Y-m-d H:i:s', $now);
        
        $authRecordInfo['uid'] = $user->id;
        $authRecordInfo['username'] = $user->name;
        $authRecordInfo['auth_code'] = 'realname';
        $authRecordInfo['auth_time'] = date('Y-m-d H:i:s', $now);
        
        $RealnameAuthModel = new RealnameAuthModel();
        $status = $RealnameAuthModel->createRealnameAuth($realnameInfo, $authRecordInfo);
        
        if ($status){
            $messages = [
                'message_title'=>$realnameInfo['realname'].'申请个人认证',
                'code_name'=>'realname_auth',
                'message_content'=>$realnameInfo['realname'].'于'.$realnameInfo['created_at'].'提交个人认证申请！',
                'js_id'=>'1',//暂时只有超级管理员接受
                'fs_id'=>$realnameInfo['uid'],
                'message_type'=>1,
                'receive_time'=>date('Y-m-d H:i:s',time()),
                'status'=>0,
            ];
            MessageReceiveModel::create($messages);
            
            return redirect('jz/auth');
        }else
            return redirect()->back()->with(['error'=>'提交认证失败']);
    }
    
    /**
     * 重新实名认证
     */
    public function reAuth(Request $request){
        if($this->user->type===2){
            $enterpriseInfo = EnterpriseAuthModel::where('uid', $this->user->id)->where('status', 2)->orderBy('created_at', 'desc')->first();
            
            if ($enterpriseInfo){
                $ret = EnterpriseAuthModel::removeEnterpriseAuth2($this->user->id);
                
                return redirect('jz/auth');
            }
        }else{
            $realnameInfo = RealnameAuthModel::where('uid', $this->user->id)->where('status', 2)->orderBy('created_at', 'desc')->first();
            
            if ($realnameInfo){
                $ret = RealnameAuthModel::removeRealnameAuth2($this->user->id);
                
                return redirect('jz/auth');
            }
        }  
    }
    
    /**
        * 用户设置标签
     * @param Request $request
     */
    public function ajaxSaveSkills(Request $request){
        $skill = $request->get("skill");
        
        if (!$skill) {
            return response()->json(['errMsg' => '请选择标签后提交！']);
        }
        
        $skills = explode(",", $skill);
        $allTag = TagsModel::findAll();
        $myNewTags = array();
        foreach($skills as $k => $v){
            foreach($allTag  as $k1 => $v1){
                if(intval($v) === $v1['cate_id']){
                    array_push($myNewTags, $v1['id']);
                    break;
                }
            }
        }
        
        $myOldTags = UserTagsModel::myTag($this->user['id']);
        $myOldTags = array_flatten($myOldTags);
        
        //判断是在添加标签还是在删除标签
        if (count($myNewTags) > count($myOldTags)) {
            //判断用户有多少个标签
            if (count($myNewTags) > 15) {
                return response()->json(['errMsg' => '一个用户最多只能有15个标签！']); 
            }
            $dif_tags = array_diff($myNewTags, $myOldTags);
            $result = UserTagsModel::insert($dif_tags, $this->user['id']);
            if (!$result)
                return response()->json(['errMsg' => '更新标签错误！']); //回传错误信息
        } else if (count($myNewTags) < count($myOldTags)) {
            $dif_tags = array_diff($myOldTags, $myNewTags);
            $result = UserTagsModel::tagDelete($dif_tags, $this->user['id']);
            if (!$result)
                return response()->json(['errMsg' => '更新标签错误！']);//回传错误信息
        } else if (count($myNewTags) == count($myOldTags)) {
            //增加新标签
            $dif_tags = array_diff($myNewTags, $myOldTags);
            if(empty($dif_tags))
            {
                return response()->json(['errMsg' => '请选择标签后提交！']);
            }
            $result2 = UserTagsModel::insert($dif_tags, $this->user['id']);
            //删除老标签
            $dif_tags = array_diff($myOldTags, $myNewTags);
            $result1 = UserTagsModel::tagDelete($dif_tags, $this->user['id']);
            if (!$result1 && !$result2)
                return response()->json(['errMsg' => '更新标签错误！']); //回传错误信息
        }
        
        return response()->json('更新标签成功！');
    }
    
    /**
     * 新建反馈
     */
    public function createFeedback(FeedbackRequest $request){
        $data = $request->except('_token');
        $data['uid'] = $this->user['id'];
        $data['title'] = \CommonClass::removeXss($data['title']);
        $data['desc'] = \CommonClass::removeXss($data['desc']);
        $data['created_at'] = date('Y-m-d H:i:s', time());
        
        $result = FeedbackModel::delivery($data);
        
        if(!$result) return redirect()->back()->with('error','反馈提交失败！');
        
        $messages = [
            'message_title'=>$this->user['name'].'提交了反馈意见',
            'code_name'=>'user_feedback',
            'message_content'=>$this->user['name'].'于'.$data['created_at'].'提交了反馈意见！',
            'js_id'=>'1',//暂时只有超级管理员接受
            'fs_id'=>$data['uid'],
            'message_type'=>1,
            'receive_time'=>date('Y-m-d H:i:s',time()),
            'status'=>0,
        ];
        MessageReceiveModel::create($messages);
        
        return redirect()->to('jz/my');
    }
    
    /**
        * 企业实名认证
     * @param RealnameAuthRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createEnterpriseAuth(EnterpriseAuthRequest $request){
        
        $business_license = $request->file('business_license');
        
        $enterpriseInfo = array();
        $authRecordInfo = array();
        $error = array();
        $allowExtension = array('jpg', 'gif', 'jpeg', 'bmp', 'png');
        if ($business_license) {
            $uploadMsg = json_decode(\FileClass::uploadFile($business_license, 'user', $allowExtension));
            if ($uploadMsg->code != 200) {
                $error['business_license'] = $uploadMsg->message;
            } else {
                $enterpriseInfo['business_license'] = $uploadMsg->data->url;
            }
        }
        
        if (!empty($error)) {
            return back()->withErrors($error)->withInput($request->except('_token'));
        }
        
        $user = $this->user;
        
        $now = time();
        
        $enterpriseInfo['uid'] = $user->id;
        $enterpriseInfo['company_name'] = $request->get('company_name');
        $enterpriseInfo['owner'] = $request->get('owner');
        $enterpriseInfo['contactor'] = $request->get('contactor');
        $enterpriseInfo['contactor_mobile'] = $request->get('contactor_mobile');
        $enterpriseInfo['bank'] = $request->get('bank');
        $enterpriseInfo['account'] = $request->get('account');
        $enterpriseInfo['tax_code'] = $request->get('tax_code');
        $enterpriseInfo['phone'] = $request->get('phone');
        $enterpriseInfo['address'] = $request->get('address');
        $enterpriseInfo['created_at'] = date('Y-m-d H:i:s', $now);
        $enterpriseInfo['updated_at'] = date('Y-m-d H:i:s', $now);
        
        $authRecordInfo['uid'] = $user->id;
        $authRecordInfo['username'] = $user->name;
        $authRecordInfo['auth_code'] = 'enterprise';
        $authRecordInfo['auth_time'] = date('Y-m-d H:i:s', $now);
        
        $status = EnterpriseAuthModel::createEnterpriseAuth($enterpriseInfo, $authRecordInfo);
        
        if ($status){
            $messages = [
                'message_title'=>$enterpriseInfo['company_name'].'申请企业认证',
                'code_name'=>'enterprise_auth',
                'message_content'=>$enterpriseInfo['company_name'].'于'.$enterpriseInfo['created_at'].'提交企业认证申请！',
                'js_id'=>'1',//暂时只有超级管理员接受
                'fs_id'=>$enterpriseInfo['uid'],
                'message_type'=>1,
                'receive_time'=>date('Y-m-d H:i:s',time()),
                'status'=>0,
            ];
            MessageReceiveModel::create($messages);
            
            return redirect('jz/auth');
        }else
            return redirect()->back()->with(['error'=>'提交认证失败'])->withInput($request->except('_token'));
    }
    
    /**
     * 企业更新内容
     * @param RealnameAuthRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function einfoUpdate(EnterpriseInfoRequest $request){
        
        if ($request->get('code') && !\CommonClass::checkCaptchaCode($request->get('code'))) {
            return redirect()->back()->withInput($request->except('_token','code'))->with(['message' => '验证码无效！']);
        }
        
        $business_license = $request->file('business_license');
        
        $enterpriseInfo = array();
        $error = array();
        $allowExtension = array('jpg', 'gif', 'jpeg', 'bmp', 'png');
        if ($business_license) {
            $uploadMsg = json_decode(\FileClass::uploadFile($business_license, 'user', $allowExtension));
            if ($uploadMsg->code != 200) {
                $error['business_license'] = $uploadMsg->message;
            } else {
                $enterpriseInfo['business_license'] = $uploadMsg->data->url;
            }
        }
        
        if (!empty($error)) {
            return back()->withErrors($error)->withInput($request->except('_token','code'));
        }
        
        $enterpriseInfo['owner'] = $request->get('owner');
        $enterpriseInfo['contactor'] = $request->get('contactor');
        $enterpriseInfo['contactor_mobile'] = $request->get('contactor_mobile');
        $enterpriseInfo['phone'] = $request->get('phone');
        $enterpriseInfo['updated_at'] = date('Y-m-d H:i:s', time());
        
        $status = EnterpriseAuthModel::updateEnterpriseInfo($enterpriseInfo, $this->user->id);
        
        if ($status)
            return redirect('jz/info');
        else
            return redirect()->back()->with(['error'=>'更新信息失败'])->withInput($request->except('_token','code'));
    }
    
    /**
        * 用户信息更新
     * @param UserInfoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function infoUpdate(UserInfoRequest $request)
    {
        
        if ($request->get('code') && !\CommonClass::checkCaptchaCode($request->get('code'))) {
            return redirect()->back()->withInput($request->except('_token','code'))->with(['message' => '验证码无效！']);
        }
        
        $mobile = $request->get('mobile');
        
        if(!isset($mobile)||empty($mobile)){
            return redirect()->back()->withInput($request->except('_token','code'))->with(['error' => '手机号不能为空！']);
        }
        
        $cnt = UserModel::where('id', '<>', $this->user['id'])->where('mobile', $mobile)->count();
        
        if($cnt>0){
            return redirect()->back()->withInput($request->except('_token','code'))->with(['error' => '手机号已注册！']);
        }
        
        $param = [
            'uid' => $this->user['id'],
            'mobile' => $mobile,
            'account' => $request->get('account')
        ];
        
        $result = DB::transaction(function () use ($param) {
            $salt = \CommonClass::random(4);
            UserModel::where('id', $param['uid'])->update(
                [
                    'name'=>$param['mobile'],                    
                    'mobile'=>$param['mobile'],
                    'password' => UserModel::encryptPassword($param['mobile'], $salt),
                    'alternate_password' => UserModel::encryptPassword($param['mobile'], $salt),
                    'salt' => $salt,
                ]                
                );
            RealnameAuthModel::where('uid', $param['uid'])->update(['account'=>$param['account'],'username'=>$param['mobile']]);
            UserDetailModel::where('uid', $param['uid'])->update(['mobile'=>$param['mobile']]);//同步一下
        });
        
        if ($result) {
            return redirect()->back()->withInput($request->except('_token','code'))->with(['error' => '修改失败！']);
        }
        
        return redirect()->back()->with(['message' => '修改成功！']);        
        
    }
    
    public function ajaxSendCode(Request $request){
        $mobile = $request->get('mobile');
        
        if(!$mobile){
            return response()->json(['errMsg' => '缺少必要参数！']);
        }
        
        $result = json_decode(\CommonClass::sendSms($mobile));
        
        return response()->json($result);
    }
}
