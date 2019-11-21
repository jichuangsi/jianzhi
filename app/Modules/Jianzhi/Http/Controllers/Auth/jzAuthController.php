<?php

namespace App\Modules\Jianzhi\Http\Controllers\Auth;

use App\Modules\User\Http\Controllers\Auth\AuthController;
use App\Modules\User\Http\Requests\RegisterRequest;
use App\Modules\User\Model\UserModel;
use App\Modules\Task\Model\TaskCateModel;
use Auth;
use App\Modules\User\Model\UserDetailModel;
use App\Modules\Manage\Model\ConfigModel;
use Illuminate\Http\Request;
/* use App\Modules\Manage\Model\AgreementModel;
use App\Modules\Manage\Model\ConfigModel;
use App\Modules\User\Http\Requests\LoginRequest;
use App\Modules\User\Http\Requests\RegisterRequest;
use App\Modules\User\Model\OauthBindModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Validator;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Theme;
use Crypt;
use Socialite;
use App\Modules\Advertisement\Model\AdTargetModel;
use App\Modules\Advertisement\Model\AdModel; */

class jzAuthController extends AuthController
{
    protected $redirectPath = '/jz/home';
    
    protected $loginPath = '/';

    public function __construct()
    {
        parent::__construct();
        $this->initTheme('common','jianzhi');        
        $this->middleware('guest', ['except' => 'getLogout']);
    }
    
    public function getRegister()
    {
        $view = array();
        
        //查询所有的末级分类
        $category_all = TaskCateModel::findAll();        
        
        $view = [
            'category_all' => $category_all            
        ];
        
        return $this->theme->scope('register', $view)->render();
    }
    
    public function getAgreement(){
        $view = array();
        return $this->theme->scope('agreement', $view)->render();
    }    
    
    public function getNotice(){
        $view = array();
        return $this->theme->scope('notice', $view)->render();
    }  
    
    public function getPartner(){
        $view = array();
        return $this->theme->scope('partner', $view)->render();
    }  
    
    public function getTaskContract(){
        $view = array();
        return $this->theme->scope('taskContract', $view)->render();
    }  
    
    public function getServiceContract(){
        $view = array();
        return $this->theme->scope('serviceContract', $view)->render();
    }      
    
    public function getDispatchContract(){
        $view = array();
        return $this->theme->scope('dispatchContract', $view)->render();
    }   
    
    public function postRegister(RegisterRequest $request){
        
        if ($request->get('code') && !\CommonClass::checkCaptchaCode($request->get('code'))) {
            return back()->withInput($request->except('_token','code'))->with(['message' => '验证码无效！']);
        }
        
        if($request->get('wx_openid')){
            $wx_openid = $request->get('wx_openid');
            $isExit = UserDetailModel::where('wechat',$wx_openid)->count();
            if($isExit>0){
                return back()->withInput($request->except('_token','code'))->with(['message' => '该微信已绑定其他账号！']);
            }
        }
        
        if ($this->create($request->all())){
            $throttles = $this->isUsingThrottlesLoginsTrait();
            $user = UserModel::where('mobile', $request->get('mobile'))->orWhere('name', $request->get('mobile'))->first();            
            Auth::loginUsingId($user->id);
            UserModel::where('mobile', $request->get('mobile'))->orWhere('name', $request->get('mobile'))->update(['last_login_time' => date('Y-m-d H:i:s')]);
            return $this->handleUserWasAuthenticated($request, $throttles);
        }
        return back()->with(['message' => '注册失败！']);
    }
    
    public function postWxLogin(Request $request){
        $mobile = $request->get('mobile');
        
        if(!$mobile){
            return back()->with(['message' => '缺少必要参数！']);
        }
        
        $throttles = $this->isUsingThrottlesLoginsTrait();
        $user = UserModel::where('mobile', $mobile)->orWhere('name', $mobile)->first();
        Auth::loginUsingId($user->id);
        UserModel::where('mobile', $mobile)->orWhere('name', $mobile)->update(['last_login_time' => date('Y-m-d H:i:s')]);
        return $this->handleUserWasAuthenticated($request, $throttles);
    }
    
    public function ajaxWxAuth(Request $request){
        $code = $request->get('code');
        
        if(!$code){
            return response()->json(['errMsg' => '缺少必要参数！']);
        }
        
        //获取微信配置
        $weixinConfig = ConfigModel::getConfigByType('weixin');
        if(empty($weixinConfig)){
            return response()->json(['errMsg' => '缺少微信必要参数：code！']);
        }        
        
        $appid = $weixinConfig['wx_appid'];
        $secret = $weixinConfig['wx_secret'];
        
        if(empty($appid)||empty($secret)){
            return response()->json(['errMsg' => '缺少系统必要参数：appid，secret！']);
        }   
        
        $accessTokeUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        
        $result = json_decode(\CommonClass::request($accessTokeUrl));
        
        $access_token = $result->access_token;
        $openid = $result->openid;
        
        if(empty($access_token)||empty($openid)){
            return response()->json(['errMsg' => '缺少微信必要参数：access_token，openid！']);
        }  
        
        unset($result);
        
        $userInfoUrl = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        
        $result = json_decode(\CommonClass::request($userInfoUrl));
        
        $userInfo = UserModel::getUsersByOpenid($result->openid);
                
        if(!$userInfo||count($userInfo)===0){
            return response()->json(['mobile' => '', 'openid' => $result->openid, 'nickname' => $result->nickname, 'headimgurl' => $result->headimgurl]);
        }
        
        if(count($userInfo)>1){
            return response()->json(['errMsg' => '你的微信绑定多于一个账号，<br/>请用手机号登陆！']);
        }
        
        //更新微信信息
        if($result->openid){
            UserDetailModel::where('uid',$userInfo[0]->u_id)->update(['wechat'=>$result->openid]);
        }
        if($result->nickname){
            UserDetailModel::where('uid',$userInfo[0]->u_id)->update(['nickname'=>$result->nickname]);
        }
        if($result->headimgurl){
            UserDetailModel::where('uid',$userInfo[0]->u_id)->update(['avatar'=>$result->headimgurl]);
        }
        
        return response()->json(['mobile' => $userInfo[0]->u_mobile]);
        
    }
    
    public function ajaxCheckOpenid(Request $request){
        $openid = $request->get('openid');
        
        if(!$openid){
            return response()->json(['errMsg' => '缺少必要参数！']);            
        }
        
        $userInfo = UserModel::getUsersByOpenid($openid);
        
        if(!$userInfo||count($userInfo)===0){
            return response()->json(['mobile' => '']);
        }
        
        if(count($userInfo)>1){
            return response()->json(['errMsg' => '你的微信绑定多于一个账号，<br/>请用手机号登陆！']);
        }
        
        return response()->json(['mobile' => $userInfo[0]->u_mobile]);
    }
    
    public function ajaxSendCode(Request $request){
        $mobile = $request->get('mobile');
        $iscode = $request->get('iscode');
        if(!$mobile){
            return response()->json(['errMsg' => '缺少必要参数！']);
        }
        if($iscode){
        	if (!UserModel::checkUser($mobile)) {
				$error = array();
				$error['code'] = 'not';
				$error['msg']='手机号未注册';
                return response()->json($error);
            }
        }
        $result = json_decode(\CommonClass::sendSms($mobile));
        
        return response()->json($result);
    }
    
    
}
