<?php

namespace App\Modules\Manage\Http\Controllers\Auth;

use App\Http\Controllers\ManageController;
use App\Modules\Manage\Http\Requests\LoginRequest;
use App\Modules\Manage\Model\ManagerModel;
use App\Modules\Manage\Model\Role;
use App\Modules\Manage\Model\RoleUserModel;
use App\Modules\Manage\Model\Permission;
use Illuminate\Support\Facades\Session;
use Teepluss\Theme\Facades\Theme;
use Validator;
use Illuminate\Http\Request;

class AuthController extends ManageController
{
    //认证成功后跳转路由
    protected $redirectPath = '/manage';

    //认证失败后跳转路由
    protected $loginPath = '/manage/login';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

    }

    /**
     * 后台登录页面
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogin()
    {
        if (ManagerModel::getManager()){
            return redirect($this->redirectPath);
        }

        $this->initTheme('managelogin');
        $this->theme->setTitle('后台登录');
        return $this->theme->scope('manage.login')->render();
    }

    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(LoginRequest $request)
    {

        if (!ManagerModel::checkPassword($request->get('username'), $request->get('password'))){
            return redirect($this->loginPath)->withInput()->withErrors(array('password'=> '请输入正确的密码'));
        }
        if(ManagerModel::where('username',$request->get('username'))->where('status',2)->first())
                return redirect($this->loginPath)->withInput()->withErrors(array('password'=> '用户已禁用'));
        $user = ManagerModel::where('username',$request->get('username'))->first();
        ManagerModel::managerLogin($user);
        
        $roids=RoleUserModel::select('role_id')->where('user_id','=',$user['id'])->get()->first();//当前登陆的角色id
		if(!empty($roids['role_id'])){
			$rolecount=Role::count();
			$rolelist=Role::get();
			$rr=0;
			$roid=0;      //销售人员角色id
			for($i=0;$i<$rolecount;$i++){
				$rr=Permission::select('permissions.name as pname','permissions.id','psr.role_id as roid')->where('psr.role_id','=',$rolelist[$i]['id'])
							->leftJoin('permission_role as psr', 'psr.permission_id', '=', 'permissions.id')->get()->toArray();
				if(count($rr)==1){
					if($rr[0]['pname']=='channelList'){
						$roid=$rr[0]['roid'];
					}
				}
			}
			if($roid>0 && $roid==$roids['role_id']){
				return redirect('/manage/channelList');
			}
		}
        
        return redirect($this->redirectPath);

    }

    /**
     * 后台登出
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        Session::forget('manager');
        return redirect($this->loginPath);
    }
}
