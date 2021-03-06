<?php

namespace App\Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\IndexController;
use App\Modules\Manage\Model\AgreementModel;
use App\Modules\Manage\Model\ConfigModel;
use App\Modules\User\Http\Requests\LoginRequest;
use App\Modules\User\Http\Requests\RegisterRequest;
use App\Modules\User\Model\OauthBindModel;
use App\Modules\User\Model\UserModel;
use App\Modules\User\Model\UserDetailModel;
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
use App\Modules\Advertisement\Model\AdModel;

class AuthController extends IndexController
{

    


    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    
    protected $redirectPath = '/user/index';

    
    protected $loginPath = '/login';

    

    public function __construct()
    {
        parent::__construct();
        $this->initTheme('auth');
        $this->theme->setTitle('威客|系统—客客出品,专业威客建站系统开源平台');
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    
    protected $code;

    protected function validator(array $data)
    {

    }

    
    protected function  create(array $data)
    {
        
        return UserModel::createUser($data);
    }


    
    public function getLogin()
    {
        $code = \CommonClass::getCodes();
        $oauthConfig = ConfigModel::getConfigByType('oauth');
        
        $ad = AdTargetModel::getAdInfo('LOGIN_LEFT');

        $view = array(
            'code' => $code,
            'oauth' => $oauthConfig,
            'ad' => $ad
        );

        $this->theme->set('authAction', '欢迎登录');
        $this->theme->setTitle('欢迎登录');
        return $this->theme->scope('user.login', $view)->render();
    }

    
    public function postLogin(LoginRequest $request)
    {
        $error = array();
        if ($request->get('code') && !\CommonClass::checkCaptchaCode($request->get('code'))) {
            //$error['code'] = '请输入正确的验证码';
            $error['code'] = '验证码无效';
        } else {
            //if (!UserModel::checkPassword($request->get('username'), $request->get('password'))) {
            if (!UserModel::checkUser($request->get('username'))) {
                //$error['password'] = '请输入正确的帐号或密码';
                $error['username'] = '请输入正确的手机号';
            } else {
                $user = UserModel::where('name', $request->get('username'))->first();
                if (!empty($user) && $user->status == 2){
                    $error['username'] = '该账户已禁用';
                }
            }
        }
        //校验微信
        if($request->get('wx_openid')){
            if(!UserModel::checkOpenid($request->get('username'),$request->get('wx_openid'))){
                $error['username'] = '该微信已绑定其他账号！';
            }            
        }
        
        
        if (!empty($error)) {
            return redirect($this->loginPath())->withInput($request->only('username', 'remember', 'wx_openid', 'wx_nickname', 'wx_headimgurl'))->withErrors($error);
        }
        $throttles = $this->isUsingThrottlesLoginsTrait();
        $user = UserModel::where('mobile', $request->get('username'))->orWhere('name', $request->get('username'))->first();
        //暂时去掉邮箱认证
        if ($user && !$user->status) {
            //return redirect('waitActive/' . Crypt::encrypt($user->email))->withInput(array('email' => $request->get('email')));
            $error['username'] = '该账户未激活';
            return redirect($this->loginPath())->withInput($request->only('username', 'remember'))->withErrors($error);
        }
        Auth::loginUsingId($user->id);
        UserModel::where('mobile', $request->get('username'))->orWhere('name', $request->get('username'))->update(['last_login_time' => date('Y-m-d H:i:s')]);
        //更新微信信息
        if($request->get('wx_openid')){
            UserDetailModel::where('uid',$user->id)->update(['wechat'=>$request->get('wx_openid')]);
        }
        if($request->get('wx_nickname')){
            UserDetailModel::where('uid',$user->id)->update(['nickname'=>$request->get('wx_nickname')]);
        }
        if($request->get('wx_headimgurl')){
            UserDetailModel::where('uid',$user->id)->update(['avatar'=>$request->get('wx_headimgurl')]);
        }
        return $this->handleUserWasAuthenticated($request, $throttles);
    }

    
    public function getRegister()
    {
        $code = \CommonClass::getCodes();
        
        $ad = AdTargetModel::getAdInfo('LOGIN_LEFT');
        
        $agree = AgreementModel::where('code_name','register')->first();

        $view = array(
            'code' => $code,
            'ad' => $ad,
            'agree' => $agree
        );
        $this->initTheme('auth');
        $this->theme->set('authAction', '欢迎注册');
        $this->theme->setTitle('欢迎注册');
        return $this->theme->scope('user.register', $view)->render();
    }

    
    public function postRegister(RegisterRequest $request)
    {
        
        if ($this->create($request->all())){
            return redirect('waitActive/' . Crypt::encrypt($request->get('email')));
        }
        return back()->with(['message' => '注册失败']);
    }

    
    public function activeEmail($validationInfo)
    {
        $info = Crypt::decrypt($validationInfo);
        $user = UserModel::where('email', $info['email'])->where('validation_code', $info['validationCode'])->first();

        $this->initTheme('auth');
        $this->theme->set('authAction', '欢迎注册');
        $this->theme->setTitle('欢迎注册');
        
        if ($user && time() > strtotime($user->overdue_date) || !$user) {
            return $this->theme->scope('user.activefail')->render();
        }
        
        $user->status = 1;
        $user->email_status = 2;
        $status = $user->save();
        if ($status){
            Auth::login($user);
            return $this->theme->scope('user.activesuccess')->render();
        }
    }

    
    public function waitActive($email)
    {
        $email = Crypt::decrypt($email);

        $emailType = substr($email, strpos($email, '@') + 1);
        $view = array(
            'email' => $email,
            'emailType' => $emailType
        );
        $this->initTheme('auth');
        $this->theme->set('authAction', '欢迎注册');
        $this->theme->setTitle('欢迎注册');
        return $this->theme->scope('user.waitactive', $view)->render();
    }


    
    public function flushCode()
    {
        $code = \CommonClass::getCodes();

        return \CommonClass::formatResponse('刷新成功', 200, $code);
    }

    
    public function checkUserName(Request $request)
    {
        $username = $request->get('param');

        $status = UserModel::where('name', $username)->first();
        if (empty($status)){
            $status = 'y';
            $info = '';
        } else {
            $info = '用户名不可用';
            $status = 'n';
        }
        $data = array(
            'info' => $info,
            'status' => $status
        );
        return json_encode($data);
    }

    
    public function checkEmail(Request $request)
    {
        $email = $request->get('param');

        $status = UserModel::where('email', $email)->first();
        if (empty($status)){
            $status = 'y';
            $info = '';
        } else {
            $info = '邮箱已占用';
            $status = 'n';
        }
        $data = array(
            'info' => $info,
            'status' => $status
        );
        return json_encode($data);
    }
    
    public function checkMobile(Request $request)
    {
        $mobile = $request->get('param');
        
        $status = UserModel::where('mobile', $mobile)->first();
        if (empty($status)){
            $status = 'y';
            $info = '';
        } else {
            $info = '手机号已注册';
            $status = 'n';
        }
        $data = array(
            'info' => $info,
            'status' => $status
        );
        return json_encode($data);
    }

    
    public function reSendActiveEmail($email)
    {
        $email = Crypt::decrypt($email);
        $status = UserModel::where('email', $email)->update(array('overdue_date' => date('Y-m-d H:i:s', time() + 60*60*3)));
        if ($status){
            $status = \MessagesClass::sendActiveEmail($email);
            if ($status){
                $msg = 'success';
            } else {
                $msg = 'fail';
            }
            return \CommonClass::formatResponse($msg);
        }
    }

    
    public function oauthLogin($type)
    {
        switch ($type){
            case 'qq':
                $alias = 'qq_api';
                break;
            case 'weibo':
                $alias = 'sina_api';
                break;
            case 'weixinweb':
                $alias = 'wechat_api';
                break;
        }
        
        $oauthConfig = ConfigModel::getOauthConfig($alias);
        $clientId = $oauthConfig['appId'];
        $clientSecret = $oauthConfig['appSecret'];
        $redirectUrl = url('oauth/' . $type . '/callback');
        $config = new \SocialiteProviders\Manager\Config($clientId, $clientSecret, $redirectUrl);
        return Socialite::with($type)->setConfig($config)->redirect();
    }

    
    public function handleOAuthCallBack($type)
    {

        switch ($type){
            case 'qq':
                $service = 'qq_api';
                break;
            case 'weibo':
                $service = 'sina_api';
                break;
            case 'weixinweb':
                $service = 'wechat_api';
                break;
        }
        $oauthConfig = ConfigModel::getOauthConfig($service);
        Config::set('services.' . $type . '.client_id', $oauthConfig['appId']);
        Config::set('services.' . $type . '.client_secret', $oauthConfig['appSecret']);
        Config::set('services.' . $type . '.redirect', url('oauth/' . $type . '/callback'));

        $user = Socialite::driver($type)->user();

        $userInfo = [];
        switch ($type){
            case 'qq':
                $userInfo['oauth_id'] = $user->id;
                $userInfo['oauth_nickname'] = $user->nickname;
                $userInfo['oauth_type'] = 0;
                break;
            case 'weibo':
                $userInfo['oauth_id'] = $user->id;
                $userInfo['oauth_nickname'] = $user->nickname;
                $userInfo['oauth_type'] = 1;
                break;
            case 'weixinweb':
                $userInfo['oauth_nickname'] = $user->nickname;
                $userInfo['oauth_id'] = $user->user['unionid']; 
                $userInfo['oauth_type'] = 2;
                break;
        }
        
        $oauthStatus = OauthBindModel::where(['oauth_id' => $userInfo['oauth_id'], 'oauth_type' => $userInfo['oauth_type']])
                    ->first();
        if (!empty($oauthStatus)){
            Auth::loginUsingId($oauthStatus->uid);
        } else {
            
            $uid = OauthBindModel::oauthLoginTransaction($userInfo);
            Auth::loginUsingId($uid);
        }
        return redirect()->intended($this->redirectPath());
    }

}
