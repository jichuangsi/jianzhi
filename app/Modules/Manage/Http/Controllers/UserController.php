<?php
namespace App\Modules\Manage\Http\Controllers;

use App\Http\Controllers\ManageController;
use App\Http\Requests;
use App\Modules\Manage\Model\ManagerModel;
use App\Modules\Manage\Model\MenuPermissionModel;
use App\Modules\Manage\Model\ModuleTypeModel;
use App\Modules\Manage\Model\Permission;
use App\Modules\Manage\Model\PermissionRoleModel;
use App\Modules\Manage\Model\Role;
use App\Modules\Manage\Model\RoleUserModel;
use App\Modules\User\Model\DistrictModel;
use App\Modules\User\Model\UserModel;
use App\Modules\User\Model\UserDetailModel;
use App\Modules\User\Model\UserTagsModel;
use App\Modules\Manage\Model\ConfigModel;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Model\RealnameAuthModel;
use App\Modules\User\Model\EnterpriseAuthModel;
use App\Modules\Task\Model\TaskCateModel;

class UserController extends ManageController
{
	//
    public function __construct()
    {
        parent::__construct();
        $this->initTheme('manage');
        $this->theme->setTitle('用户管理');
        $this->theme->set('manageType', 'User');
    }

    /**
     * 普通用户列表
     *
     * @param Request $request
     * @return mixed
     */
    public function getUserList(Request $request)
    {
        $list = UserModel::select('users.name', 'user_detail.created_at', 'user_detail.balance', 'user_detail.card_number', 'user_detail.mobile', 
                            'users.id', 'users.last_login_time', 'users.status', 'realname_auth.status as astatus', 'realname_auth.realname as realname')
            ->where('users.type', 1)
            ->leftJoin('user_detail', 'users.id', '=', 'user_detail.uid')
            ->leftJoin('realname_auth', 'users.id', '=', 'realname_auth.uid');

        if ($request->get('uid')){
            $list = $list->where('users.id', $request->get('uid'));
        }
        if ($request->get('username')){
            $list = $list->where('users.name','like', '%'.$request->get('username').'%');
        }
        if ($request->get('email')){
            $list = $list->where('users.email', $request->get('email'));
        }
        if ($request->get('mobile')){
            $list = $list->where('user_detail.mobile', $request->get('mobile'));
        }
        if (intval($request->get('status'))){
            switch(intval($request->get('status'))){
                case 1:
                    $status = null;
                    break;
                case 2:
                    $status = 1;
                    break;
                case 3:
                    $status = 0;
                    break;
                case 4:
                    $status = 2;
                    break;
                case -1;
                    //$status = [0,1,2];
                    $status = -1;
                    break;
            }
            if(is_array($status)){
                //$list = $list->whereIn('users.status', $status);
                $list = $list->whereIn('realname_auth.status', $status);                
            }else{
                //$list = $list->where('users.status', $status);
                if($status != -1)
                    $list = $list->where('realname_auth.status', $status);    
            }
        }
        $order = $request->get('order') ? $request->get('order') : 'desc';
        if ($request->get('by')){
            switch ($request->get('by')){
                case 'id':
                    $list = $list->orderBy('users.id', $order);
                    break;
                case 'created_at':
                    $list = $list->orderBy('users.created_at', $order);
                    break;
            }
        } else {
            $list = $list->orderBy('users.created_at', $order);
        }

        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;
        //时间筛选
        $timeType = 'users.created_at';
        if($request->get('start')){
            $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
            $list = $list->where($timeType,'>',$start);

        }
        if($request->get('end')){
            $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
            $list = $list->where($timeType,'<',$end);
        }
        $list = $list->paginate($paginate);
        //dump($list);
        $data = [
            'status'=>$request->get('status'),
            'list' => $list,
            'paginate' => $paginate,
            'order' => $order,
            'by' => $request->get('by'),
            'uid' => $request->get('uid'),
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'mobile' => $request->get('mobile')
        ];
        $search = [
            'status'=>$request->get('status'),
            'paginate' => $paginate,
            'order' => $order,
            'by' => $request->get('by'),
            'uid' => $request->get('uid'),
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'mobile' => $request->get('mobile'),
            'start' => $request->get('start'),
            'end' => $request->get('end')
        ];
        $data['search'] = $search;

 		return $this->theme->scope('manage.userList', $data)->render();
    }

    /**
     * 普通企业列表
     *
     * @param Request $request
     * @return mixed
     */
    public function getEnterpriseList(Request $request)
    {
        $list = UserModel::select('users.name', //'user_detail.created_at', 'user_detail.balance', 'user_detail.card_number', 'user_detail.mobile',
            'users.id', 'users.last_login_time', 'users.status', 'enterprise_auth.status as astatus', 'enterprise_auth.company_name as company_name'
            , 'enterprise_auth.contactor as contactor', 'enterprise_auth.contactor_mobile as contactor_mobile'
            , 'province.name as province_name','city.name as city_name','area.name as area_name', 'enterprise_auth.address as address')
            ->where('users.type', 2)
            //->leftJoin('user_detail', 'users.id', '=', 'user_detail.uid')
            ->leftJoin('enterprise_auth', 'users.id', '=', 'enterprise_auth.uid')
            ->leftjoin('district as province','province.id','=','enterprise_auth.province')
            ->leftjoin('district as city','city.id','=','enterprise_auth.city')
            ->leftjoin('district as area','area.id','=','enterprise_auth.area');
            
            if ($request->get('uid')){
                $list = $list->where('users.id', $request->get('uid'));
            }
            if ($request->get('username')){
                $list = $list->where('users.name','like', '%'.$request->get('username').'%');
            }
            if ($request->get('company_name')){
                $list = $list->where('enterprise_auth.company_name','like', '%'.$request->get('company_name').'%');
            }
            if ($request->get('email')){
                $list = $list->where('users.email', $request->get('email'));
            }
            if ($request->get('contactor')){
                $list = $list->where('enterprise_auth.contactor','like', '%'.$request->get('contactor').'%');
            }
            if ($request->get('contactor_mobile')){
                $list = $list->where('enterprise_auth.contactor_mobile', $request->get('contactor_mobile'));
            }
            if (intval($request->get('status'))){
                switch(intval($request->get('status'))){
                    case 1:
                        $status = null;
                        break;
                    case 2:
                        $status = 1;
                        break;
                    case 3:
                        $status = 0;
                        break;
                    case 4:
                        $status = 2;
                        break;
                    case -1;
                    //$status = [0,1,2];
                    $status = -1;
                    break;
                }
                if(is_array($status)){
                    //$list = $list->whereIn('users.status', $status);
                    $list = $list->whereIn('enterprise_auth.status', $status);
                }else{
                    //$list = $list->where('users.status', $status);
                    if($status != -1)
                        $list = $list->where('enterprise_auth.status', $status);
                }
            }
            $order = $request->get('order') ? $request->get('order') : 'desc';
            if ($request->get('by')){
                switch ($request->get('by')){
                    case 'id':
                        $list = $list->orderBy('users.id', $order);
                        break;
                    case 'created_at':
                        $list = $list->orderBy('users.created_at', $order);
                        break;
                }
            } else {
                $list = $list->orderBy('users.created_at', $order);
            }
            
            $paginate = $request->get('paginate') ? $request->get('paginate') : 10;
            //时间筛选
            $timeType = 'users.created_at';
            if($request->get('start')){
                $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
                $list = $list->where($timeType,'>',$start);
                
            }
            if($request->get('end')){
                $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
                $list = $list->where($timeType,'<',$end);
            }
            $list = $list->paginate($paginate);
            
            $data = [
                'status'=>$request->get('status'),
                'list' => $list,
                'paginate' => $paginate,
                'order' => $order,
                'by' => $request->get('by'),
                'uid' => $request->get('uid'),
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'contactor_mobile' => $request->get('contactor_mobile'),
                'company_name' => $request->get('company_name'),
                'contactor' => $request->get('contactor')                
            ];
            $search = [
                'status'=>$request->get('status'),
                'paginate' => $paginate,
                'order' => $order,
                'by' => $request->get('by'),
                'uid' => $request->get('uid'),
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'contactor_mobile' => $request->get('contactor_mobile'),
                'company_name' => $request->get('company_name'),
                'contactor' => $request->get('contactor'),
                'start' => $request->get('start'),
                'end' => $request->get('end')
            ];
            $data['search'] = $search;
            
            return $this->theme->scope('manage.enterpriseList2', $data)->render();
    }
    
    
    /**
     * 处理用户
     *
     * @param $uid
     * @param $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleUser($uid, $action)
    {
        switch ($action){
            case 'enable':
                $status = 1;
                break;
            case 'disable':
                $status = 2;
                break;
        }
        $status = UserModel::where('id', $uid)->update(['status' => $status]);
        if ($status)
            return back()->with(['message' => '操作成功']);
    }
    
    /**
     * 添加普通企业视图
     *
     * @return mixed
     */
    public function getEnterpriseAdd()
    {
        $province = DistrictModel::findTree(0);
        //技能标签
        $taskCate = TaskCateModel::findAll();
        $data = [
            'province' => $province,
            'taskCate' => $taskCate,
        ];
        return $this->theme->scope('manage.enterpriseAdd', $data)->render();
    }
    
    /**
     * 添加企业表单提交
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEnterpriseAdd(Request $request){
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
            return back()->withErrors($error)->withInput();
        }
        
        $salt = \CommonClass::random(4);
        $data = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'tax_code' => $request->get('tax_code'),
            'password' => UserModel::encryptPassword($request->get('password'), $salt),
            'salt' => $salt,
            'email_status' => 2,
            'status' => 1,
            'type' => 2,
            'company_name' => $request->get('company_name'),
            'bank' => $request->get('bank'),
            'account' => $request->get('account'),
            'province' => $request->get('province'),
            'city' => $request->get('city'),
            'area' => $request->get('area'),
            'owner' => $request->get('owner'),
            'phone' => $request->get('phone'),
            'contactor' => $request->get('contactor'),
            'contactor_mobile' => $request->get('contactor_mobile'),
            'company_email' => $request->get('company_email'),
            'address' => $request->get('address'),
            'business_license' => $enterpriseInfo['business_license']
        ];
        $status = UserModel::addEnterprise($data);
        if ($status)
            return redirect('manage/enterpriseList')->with(['message' => '操作成功']);
        else
            return  redirect()->to('manage/enterpriseList')->with(array('message' => '操作失败'));
    }

    /**
     * 添加普通用户视图
     *
     * @return mixed
     */
    public function getUserAdd()
    {
        $province = DistrictModel::findTree(0);
        //技能标签
        $taskCate = TaskCateModel::findAll();
        $data = [
            'province' => $province,
            'taskCate' => $taskCate,
        ];
 		return $this->theme->scope('manage.userAdd', $data)->render();
    }

    /**
     * 添加用户表单提交
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUserAdd(Request $request)
    {
        //dd($request->all());
        
        $card_front_side = $request->file('card_front_side');
        $card_back_dside = $request->file('card_back_dside');
        
        $realnameInfo = array();
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
        
        $salt = \CommonClass::random(4);
        $data = [
            'name' => $request->get('name'),
            'realname' => $request->get('realname'),
            'card_number' => $request->get('card_number'),
            'email_status' => 2,
            'status' => 1,
            'type' => 1,
            'mobile' => $request->get('mobile'),
            'qq' => $request->get('qq'),
            'email' => $request->get('email'),
            'province' => $request->get('province'),
            'city' => $request->get('city'),
            'area' => $request->get('area'),
            'password' => UserModel::encryptPassword($request->get('password'), $salt),
            'salt' => $salt,
            'card_front_side' => $realnameInfo['card_front_side'],
            'card_back_dside' => $realnameInfo['card_back_dside'],
            'skill' => $request->get('skill'),
        ];
        $status = UserModel::addUser($data);
        if ($status)
            return redirect('manage/userList')->with(['message' => '操作成功']);
        else 
            return  redirect()->to('manage/userList')->with(array('message' => '操作失败'));
    }

    /**
     * 检查用户名
     *
     * @param Request $request
     * @return string
     */
    public function checkUserName(Request $request){
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
    
    /**
     * 检查用户名
     *
     * @param Request $request
     * @return string
     */
    public function checkUserName2($username){
        $status = UserModel::where('name', $username)->first();
        if (empty($status)){
            $status = true;
            $info = '';
        } else {
            $info = '用户名不可用';
            $status = false;
        }
        $data = array(
            'info' => $info,
            'status' => $status
        );
        return $data;
    }

    /**
     * 检测邮箱是否可用
     *
     * @param Request $request
     * @return string
     */
    public function checkEmail(Request $request){
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

    //个人用户导入视图
    public function getUserImport(){
        
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $data = [
            'filesize' => $attachmentConfig['attachment']['size']
        ];
        
        return $this->theme->scope('manage.userImport', $data)->render();
    }
    
    //提交个人用户导入数据
    public function postUserImport(Request $request){
        
        $data = $this->fileImport($request->file('usersfile'));
        
        if(isset($data['fail'])&&$data['fail']){
            return back()->with(['error' => $data['errMsg']]);
        }
        
        //dump($data);
        $users = array();
        foreach($data as $k => &$v){
            if($k === 0 || empty($v[0]) || empty($v[2])) continue;
            
            $v['num'] = $k;
            $v[2] = strval($v[2]);
            
            $usable = $this->checkUserName2($v[2]);
            
            if(!$usable['status']){
                $v['msg'] = $usable['info'];
                array_push($users, $v);
                continue;
            }
            
            $skillIds = '';
            if(!empty($v[5])){
                $skills = explode(',', $v[5]);
                foreach($skills as $k1 => $v1){
                    if($v1){
                        $skill = explode('-', $v1);
                        if($skill[0])  $skillIds .= $skill[0] . ',';
                    }
                }
            }
            
            $astatus = '';
            if(!empty($v[6])){
                $aInfos = explode('-', $v[6]);
                $astatus = $aInfos[0];
            }
            
            if($astatus&&(empty($v[7])||empty($v[8]))){
                $v['msg'] = '必须同时附带身份证正反面照片，才可进入认证流程';
                array_push($users, $v);
                continue;
            }
            
            $salt = \CommonClass::random(4);
            $param = [
                'name' => $v[2],
                'email' => $this->genEMail($v[7]),
                'realname' => $v[0],
                'card_number' => $v[1],
                'email_status' => 2,
                'status' => 1,
                'type' => 1,
                'mobile' => $v[2],
                'password' => UserModel::encryptPassword($v[2], $salt),
                'salt' => $salt,
                'card_front_side' => $astatus?$v[7]:'',
                'card_back_dside' => $astatus?$v[8]:'',
                'skill' => $skillIds,
                'astatus' => $astatus,
            ];
            //dump($param);
            $status = UserModel::addUser($param);  
            
            if($status){
                $v['msg'] = '导入成功';
            }else{
                $v['msg'] = '导入失败';
            }
            
            array_push($users, $v);
        }
        
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $result = [
            'filesize' => $attachmentConfig['attachment']['size'],
            'users' => $users,
        ];
        //dump($result);
        return $this->theme->scope('manage.userImport', $result)->render();
    }
    
    //企业用户导入视图
    public function getEnterpriseImport(){
        $data = array();
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $data = [
            'filesize' => $attachmentConfig['attachment']['size']
        ];        
        return $this->theme->scope('manage.enterpriseImport', $data)->render();
    }
    
    //企业用户导入数据
    public function postEnterpriseImport(Request $request){
        $data = $this->fileImport($request->file('enterprisesfile'));
        
        if(isset($data['fail'])&&$data['fail']){
            return back()->with(['error' => $data['errMsg']]);
        }        
        //dump($data);
        $enterprises = array();
        
        foreach($data as $k => &$v){
            if($k === 0 || empty($v[0]) || empty($v[7])) continue;
            
            $v['num'] = $k;
            $v[7] = strval($v[7]);
            
            $usable = $this->checkUserName2($v[7]);
            
            if(!$usable['status']){
                $v['msg'] = $usable['info'];
                array_push($enterprises, $v);
                continue;
            }
            
            $astatus = '';
            if(!empty($v[9])){
                $aInfos = explode('-', $v[9]);
                $astatus = $aInfos[0];
            }
            
            if($astatus&&empty($v[8])){
                $v['msg'] = '必须同时附带营业执照副本照片，才可进入认证流程';
                array_push($enterprises, $v);
                continue;
            }
            
            $city = '';
            if(!empty($v[1])){
                $city = DistrictModel::getDistrictId($v[1]);
            }
            
            $salt = \CommonClass::random(4);
            $param = [
                'name' => $v[7],                
                'password' => UserModel::encryptPassword($v[7], $salt),
                'email' => $this->genEMail($v[7]),
                'salt' => $salt,
                'email_status' => 2,
                'status' => 1,
                'type' => 2,
                'company_name' => $v[0],
                'city' => $city?$city:'', 
                'tax_code' => $v[2],
                'owner' => $v[3],
                'company_email' => $v[4],
                'address' => $v[5],
                'contactor' => $v[6],
                'contactor_mobile' => $v[7],
                'business_license' => $v[8],
                'bank' => $v[10],
                'account' => $v[11],  
                'phone' => $v[12],
                'astatus' => $astatus,
            ];
            //dump($param);
            $status = UserModel::addEnterprise($param);
            
            if($status){
                $v['msg'] = '导入成功';
            }else{
                $v['msg'] = '导入失败';
            }
            
            array_push($enterprises, $v);
        }
        
        $attachmentConfig = ConfigModel::getConfigByType('attachment');
        
        $result = [
            'filesize' => $attachmentConfig['attachment']['size'],
            'enterprises' => $enterprises,
        ];
        //dump($result);
        return $this->theme->scope('manage.enterpriseImport', $result)->render();
        
    }
    
    /**
     * 编辑企业资料视图
     *
     * @param $uid
     * @return mixed
     */
    public function getEnterpriseEdit($uid)
    {
        $info = UserModel::select('users.name', 'users.email', 'enterprise_auth.*', 'users.id')
            ->where('users.id', $uid)
            ->leftJoin('user_detail', 'users.id', '=', 'user_detail.uid')
            ->leftJoin('enterprise_auth', 'users.id', '=', 'enterprise_auth.uid')
            ->first()->toArray();
            
            $province = DistrictModel::findTree(0);            
            
            $data = [
                'info' => $info,
                'province' => $province,
                'city' => DistrictModel::getDistrictName($info['city']),
                'area' => DistrictModel::getDistrictName($info['area']),
            ];
            return $this->theme->scope('manage.enterpriseDetail', $data)->render();
    }   
    
    /**
     * 编辑企业用户资料
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEnterpriseEdit(Request $request)
    {
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
            return back()->withErrors($error)->withInput();
        }
        
        $data = [
            'uid' => $request->get('uid'),
            'tax_code' => $request->get('tax_code'),
            'company_name' => $request->get('company_name'),
            'bank' => $request->get('bank'),
            'account' => $request->get('account'),
            'province' => $request->get('province'),
            'city' => $request->get('city'),
            'area' => $request->get('area'),
            'owner' => $request->get('owner'),
            'phone' => $request->get('phone'),
            'contactor' => $request->get('contactor'),
            'contactor_mobile' => $request->get('contactor_mobile'),
            'company_email' => $request->get('company_email'),
            'address' => $request->get('address'),
        ];
        
        if(!empty($request->get('password'))){
            $salt = \CommonClass::random(4);
            $data['password'] = UserModel::encryptPassword($request->get('password'), $salt);
            $data['salt'] = $salt;
        }
        
        if(!empty($enterpriseInfo['business_license'])){
            $data['business_license'] = $enterpriseInfo['business_license'];
        }        
        
        $status = UserModel::editEnterprise($data);
        if ($status)
            return redirect('manage/enterpriseList')->with(['message' => '操作成功']);
        else
            return  redirect()->to('manage/enterpriseList')->with(array('message' => '操作失败'));
    }
        
    /**
     * 编辑个人资料视图
     *
     * @param $uid
     * @return mixed
     */
    public function getUserEdit($uid)
    {
        $info = UserModel::select('users.name', 'user_detail.realname', 'user_detail.card_number', 'user_detail.mobile', 
                            'user_detail.qq', 'users.email', 'user_detail.province', 'user_detail.city', 'user_detail.area', 'users.id')
            ->where('users.id', $uid)
            ->leftJoin('user_detail', 'users.id', '=', 'user_detail.uid')
            ->leftJoin('realname_auth', 'users.id', '=', 'realname_auth.uid')
            ->first()->toArray();

        $province = DistrictModel::findTree(0);
        //技能标签
        $taskCate = TaskCateModel::findAll();
        //个人标签
        $myTags = UserTagsModel::myTag2($uid);
        $skill = array();
        if($myTags&&count($myTags)>0){
            foreach($taskCate as $k => &$v){
                if(isset($v['children_task'])){
                    foreach($v['children_task'] as $k1 => &$v1){
                        foreach($myTags as $k2 => $v2){
                            if($v1['id']===$v2['cate_id']){
                                array_push($skill, ['cate_id' =>$v1['id'], 'cate_name' =>$v1['name'] ]);
                            }
                        }
                    }
                }
            }
        } 
        
        $data = [
            'info' => $info,
            'province' => $province,
            'taskCate' => $taskCate,
            'city' => DistrictModel::getDistrictName($info['city']),
            'area' => DistrictModel::getDistrictName($info['area']),
            'skill' => $skill,
        ];
 		return $this->theme->scope('manage.userDetail', $data)->render();
    }

    /**
     * 编辑个人用户资料
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUserEdit(Request $request)
    {
        $card_front_side = $request->file('card_front_side');
        $card_back_dside = $request->file('card_back_dside');
        
        $realnameInfo = array();
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
        
        $data = [
            'uid' => $request->get('uid'),
            'realname' => $request->get('realname'),
            'mobile' => $request->get('mobile'),
            'card_number' => $request->get('card_number'),
            'qq' => $request->get('qq'),
            //'email' => $request->get('email'),
            'province' => $request->get('province'),
            'city' => $request->get('city'),
            'area' => $request->get('area'),
            'skill' => $request->get('skill'),
        ];
        
        if(!empty($request->get('password'))){
            $salt = \CommonClass::random(4);
            $data['password'] = UserModel::encryptPassword($request->get('password'), $salt);
            $data['salt'] = $salt;
        }
        
        if(!empty($realnameInfo['card_front_side'])){
            $data['card_front_side'] = $realnameInfo['card_front_side'];
        }
        
        if(!empty($realnameInfo['card_back_dside'])){
            $data['card_back_dside'] = $realnameInfo['card_back_dside'];
        }
        
        $status = UserModel::editUser($data);
        if ($status)
            return redirect('manage/userList')->with(['message' => '操作成功']);
       else
            return  redirect()->to('manage/userList')->with(array('message' => '操作失败'));
    }

    /**
     * 系统用户列表
     *
     * @param Request $request
     * @return mixed
     */
   	public function getManagerList(Request $request)
   	{
        $merge = $request->all();
        $list = ManagerModel::select('manager.id','manager.username','roles.display_name','manager.status','manager.email','manager.telephone','manager.QQ')->leftJoin('role_user','manager.id','=','role_user.user_id')
           ->leftJoin('roles','roles.id','=','role_user.role_id');
        $roles = Role::get();
        if($request->get('uid')){
            $list = $list->where('manager.id',$request->get('uid'));
        }
        if($request->get('username')){

            $list = $list->where('manager.username','like','%'. $request->get('username').'%');
        }
        if($request->get('QQ')){

            $list = $list->where('manager.QQ','like','%'. $request->get('QQ').'%');
        }
        if($request->get('email')){

            $list = $list->where('manager.email','like','%'. $request->get('email').'%');
        }
        if($request->get('display_name') && $request->get('display_name') != '全部'){
            $list = $list->where('roles.id',$request->get('display_name'));
        }
        if($request->get('telephone')){

            $list = $list->where('manager.telephone','like','%'. $request->get('telephone').'%');
        }
        if ($request->get('status')!=""){
            $list = $list->where('manager.status', $request->get('status'));
        }
        if($request->get('role_id')!=""){
            $list = $list->where('roles.id', $request->get('role_id'));
        }

        $order = $request->get('order') ? $request->get('order') : 'desc';
        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;
        $list = $list->orderBy('manager.id',$order)->paginate($paginate);
        $listArr = $list->toArray();
        $data = array(
            'merge' => $merge,
            'listArr' => $listArr,
            'status'=>$request->get('status'),
            'by' => $request->get('by'),
            'order' => $order,
            'display_name'=>$request->get('display_name'),
            'uid'=>$request->get('uid'),
            'username'=>$request->get('username'),
            'QQ'=>$request->get('QQ'),
            'email'=>$request->get('email'),
            'telephone'=>$request->get('telephone'),
            'list'=>$list,
            'roles'=>$roles,
            'role_id'=>$request->get('role_id'),
       );
		return $this->theme->scope('manage.managerList',$data)->render();
   	}

    /**
     * 处理用户
     *
     * @param $uid
     * @param $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleManage($uid, $action)
    {
        switch ($action){
            case 'enable':
                $status = 1;
                break;
            case 'disable':
                $status = 2;
                break;
        }
        $status = ManagerModel::where('id', $uid)->update(['status' => $status]);
        if ($status)
            return back()->with(['message' => '操作成功']);
    }

    /**
     * 验证系统用户名
     *
     * @param Request $request
     * @return string
     */
    public function checkManageName(Request $request){
        $username = $request->get('param');
        $status = ManagerModel::where('username', $username)->first();
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

    /**
     * 验证系统用户邮箱
     *
     * @param Request $request
     * @return string
     */
    public function checkManageEmail(Request $request){
        $email = $request->get('param');

        $status = ManagerModel::where('email', $email)->first();
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
    
    /**
     * 批量删除个人用户
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUserDeleteAll(Request $request){
        // dd($request->all());
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/userList')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                RealnameAuthModel::where('uid', $id)->delete();
                UserDetailModel::where('uid', $id)->delete();
                UserModel::where('id', $id)->delete();
            }
        });
            if(is_null($status))
            {
                return redirect()->to('manage/userList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/userList')->with(array('message' => '操作失败'));
    }
    
    /**
     * 批量删除企业用户
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEnterpriseDeleteAll(Request $request){
        // dd($request->all());
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/enterpriseList')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                EnterpriseAuthModel::where('uid', $id)->delete();
                UserDetailModel::where('uid', $id)->delete();
                UserModel::where('id', $id)->delete();
            }
        });
            if(is_null($status))
            {
                return redirect()->to('manage/enterpriseList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/enterpriseList')->with(array('message' => '操作失败'));
    }
    
    
    /**
     * 单个通过普通用户认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUserAuthPass($uid){
        if(!$uid){
            return  redirect('manage/userList')->with(array('message' => '操作失败'));
        }
        $status = DB::transaction(function () use ($uid) {
            RealnameAuthModel::realnameAuthPassByUid($uid);
        });
            if(is_null($status))
            {
                return redirect()->to('manage/userList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/userList')->with(array('message' => '操作失败'));
    }
    
    /**
     * 单个通过普通企业认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setEnterpriseAuthPass($uid){
        if(!$uid){
            return  redirect('manage/enterpriseList')->with(array('message' => '操作失败'));
        }
        $status = DB::transaction(function () use ($uid) {
            EnterpriseAuthModel::EnterpriseAuthPassByUid($uid);
        });
            if(is_null($status))
            {
                return redirect()->to('manage/enterpriseList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/enterpriseList')->with(array('message' => '操作失败'));
    }
    
    
    /**
     * 单个拒绝普通用户认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUserAuthReject($uid) {
        if(!$uid){
            return  redirect('manage/userList')->with(array('message' => '操作失败'));
        }
        $status = DB::transaction(function () use ($uid) {
            RealnameAuthModel::realnameAuthDenyByUid($uid);
        });
            if(is_null($status))
            {
                return redirect()->to('manage/userList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/userList')->with(array('message' => '操作失败'));
    }
    
    /**
     * 单个拒绝普通企业认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setEnterpriseAuthReject($uid) {
        if(!$uid){
            return  redirect('manage/enterpriseList')->with(array('message' => '操作失败'));
        }
        $status = DB::transaction(function () use ($uid) {
            EnterpriseAuthModel::EnterpriseAuthDenyByUid($uid);
        });
            if(is_null($status))
            {
                return redirect()->to('manage/enterpriseList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/enterpriseList')->with(array('message' => '操作失败'));
    }    
    
    /**
     * 批量通过普通用户认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUserAuthPassAll(Request $request) {
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/userList')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                RealnameAuthModel::realnameAuthPassByUid($id);
            }
        });
        if(is_null($status))
        {
            return redirect()->to('manage/userList')->with(array('message' => '操作成功'));
        }
        return  redirect()->to('manage/userList')->with(array('message' => '操作失败'));
    }
    
    /**
     * 批量通过普通企业认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEnterpriseAuthPassAll(Request $request){
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/enterpriseList')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                EnterpriseAuthModel::EnterpriseAuthPassByUid($id);
            }
        });
            if(is_null($status))
            {
                return redirect()->to('manage/enterpriseList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/enterpriseList')->with(array('message' => '操作失败'));
    }
    
    /**
     * 批量拒绝普通用户认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUserAuthRejectAll(Request $request) {
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/userList')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                RealnameAuthModel::realnameAuthDenyByUid($id);
            }
        });
            if(is_null($status))
            {
                return redirect()->to('manage/userList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/userList')->with(array('message' => '操作失败'));
    }    
    
    /**
     * 批量拒绝普通企业认证
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEnterpriseAuthRejectAll(Request $request) {
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/enterpriseList')->with(array('message' => '操作失败'));
        }
        if(is_string($data['chk'])){
            $data['chk'] = explode(',', $data['chk']);
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                EnterpriseAuthModel::EnterpriseAuthDenyByUid($id);
            }
        });
            if(is_null($status))
            {
                return redirect()->to('manage/enterpriseList')->with(array('message' => '操作成功'));
            }
            return  redirect()->to('manage/enterpriseList')->with(array('message' => '操作失败'));
    }

    /**
     * 批量删除系统用户
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postManagerDeleteAll(Request $request){
       // dd($request->all());
        $data = $request->except('_token');
        //var_dump($data['chk']);exit;
        if(!$data['chk']){
            return  redirect('manage/managerList')->with(array('message' => '操作失败'));
        }
        $status = DB::transaction(function () use ($data) {
            foreach ($data['chk'] as $id) {
                ManagerModel::where('id', $id)->delete();
               RoleUserModel::where('user_id', $id)->delete();
            }
        });
        if(is_null($status))
        {
            return redirect()->to('manage/managerList')->with(array('message' => '操作成功'));
        }
        return  redirect()->to('manage/managerList')->with(array('message' => '操作失败'));
    }

    /**
     *删除用户
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function managerDel($id){
        $status = DB::transaction(function () use ($id){
            ManagerModel::where('id',$id)->delete();
            RoleUserModel::where('user_id',$id)->delete();
        });

        if (is_null($status))
            return redirect()->to('manage/managerList')->with(['message' => '操作成功']);
    }
    /**
     * 添加用户视图
     *
     * @return mixed
     */
   	public function managerAdd()
   	{
        $roles = Role::get();
        $data = array(
            'roles'=>$roles
        );
		return $this->theme->scope('manage.managerAdd',$data)->render();
   	}

    /**
     * 系统用户表单提交
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postManagerAdd(Request $request)
    {
        $status = DB::transaction(function () use ($request) {
            $salt = \CommonClass::random(4);
            $data = [
                'username' => $request->get('username'),
                'realname' => $request->get('realname'),
                'telephone' => $request->get('telephone'),
                'QQ' => $request->get('QQ'),
                'email' => $request->get('email'),
                'password' => ManagerModel::encryptPassword($request->get('password'), $salt),
                'birth' => $request->get('birth'),
                'salt' => $salt,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ];
            ManagerModel::insert($data);
            $user = ManagerModel::where('username',$request->get('username'))->first();
            if($request->get('role_id'))
              $user->attachRole($request->get('role_id'));
        });
        if (is_null($status))
            return redirect('manage/managerList')->with(['message' => '操作成功']);
    }

    /**
     * 系统用户详情
     *
     * @param $id
     * @return mixed
     */
   	public function managerDetail($id)
   	{
        $info = ManagerModel::select('manager.id','manager.username','manager.status','manager.email','manager.telephone','manager.QQ','manager.password')->leftJoin('role_user','manager.id','=','role_user.user_id')
            ->leftJoin('roles','roles.id','=','role_user.role_id')->where('manager.id',$id)->first();
        $roles = Role::get();
        $data = array(
            'roles'=>$roles,
            'info'=>$info,

        );
		return $this->theme->scope('manage.managerDetail',$data)->render();
   	}

    /**
     * 编辑用户资料
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postManagerDetail(Request $request)
    {
        $status = DB::transaction(function () use ($request) {
            $id = $request->get('uid');
            if(!ManagerModel::where('id',$id)->where('password',$request->get('password'))->first()) {
                $salt = \CommonClass::random(4);
                $data = array(
                    'realname' => $request->get('realname'),
                    'telephone' => $request->get('telephone'),
                    'QQ' => $request->get('QQ'),
                    'password' => ManagerModel::encryptPassword($request->get('password'), $salt),
                    'birth' => $request->get('birth'),
                    'salt' => $salt,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time())
                );
            }else{
                $data = array(
                    'realname' => $request->get('realname'),
                    'telephone' => $request->get('telephone'),
                    'QQ' => $request->get('QQ'),
                    'birth' => $request->get('birth'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time())
                );
            }
            ManagerModel::where('id', $id)->update($data);
            $user = ManagerModel::where('id',$id)->first();
            if(!RoleUserModel::where('user_id',$id)->where('role_id',$request->get('role_id'))->first())
                $user->attachRole($request->get('role_id'));

        });
       if (is_null($status))
            return redirect('manage/managerList')->with(['message' => '操作成功']);
    }


    /**
     * 系统组列表
     *
     * @return mixed
     */
    public function getRolesList()
    {
        $list =  Role::select('roles.id','roles.display_name','roles.updated_at')->orderBy('roles.id','DESC')->paginate(10);
        $data = array(
            'list'=>$list
        );
        return $this->theme->scope('manage.rolesList',$data)->render();
    }

    /**
     * 添加系统组视图
     *
     * @return mixed
     */
    public function getRolesAdd()
    {
        $tree_menu = Permission::getPermissionMenu();
        $data = array(
            'list' =>$tree_menu,
        );
        return $this->theme->scope('manage.rolesAdd',$data)->render();
    }
    /**
     * 添加系统组
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRolesAdd(Request $request)
    {
          if(!count($request->get('id'))){
            return redirect('manage/rolesAdd')->with(['message' => '请设置用户组权限']);
        }
        $status = DB::transaction(function () use ($request) {
            $data = array(
                'name' => $request->get('name'),
                'display_name'=>$request->get('display_name'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            );
            $role_id = Role::insertGetId($data);
            foreach ($request->get('id') as $id) {
                $role_id = $role_id;
                $data2 = array(
                    'permission_id' => $id,
                    'role_id' => $role_id
                );
                $re2 = PermissionRoleModel::insert($data2);
            }
        });
        if (is_null($status))
            return redirect('manage/rolesList')->with(['message' => '操作成功']);
    }

    /**
     * 删除系统组列表
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRolesDel($id)
    {
        $status = DB::transaction(function () use ($id) {
            Role::where('id', $id)->delete();
            PermissionRoleModel::where('role_id',$id)->delete();
        });
        if (is_null($status))
            return redirect()->to('manage/rolesList')->with(['message' => '操作成功']);
    }

    /**
     * 系统组详情页
     *
     * @param $id
     * @return mixed
     */
    public function getRolesDetail($id)
    {
        $tree_menu = Permission::getPermissionMenu();

        $info1 = Role::where('id',$id)->first();
        $info = Role::select('roles.name','permissions.id','permissions.display_name')->join('permission_role','roles.id','=','permission_role.role_id')
            ->join('permissions','permissions.id','=','permission_role.permission_id')->where('roles.id',$id)->get();
        $ids = array();
        foreach ($info as $v) {
            $ids[] .= $v['id'];
        }
        $data = array(
            'ids'=>$ids,
            'info1'=>$info1,
            'info'=>$info,
            'list'=>$tree_menu,
        );
        return $this->theme->scope('manage.rolesDetail',$data)->render();
    }

    /**
     * 更新系统组详情页
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRolesDetail(Request $request)
    {
        $status = DB::transaction(function () use ($request) {
            $rid = $request->get('rid');
            $data = array(
                'name' => $request->get('name'),
                'display_name'=>$request->get('display_name'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            );
            Role::where('id', $rid)->update($data);

            PermissionRoleModel::where('role_id', $rid)->delete();

            if($request->get('id')) {
                foreach ($request->get('id') as $id) {
                    $role_id = $rid;
                    $data2 = array(
                        'permission_id' => $id,
                        'role_id' => $role_id
                    );
                    PermissionRoleModel::insert($data2);
                }
            }
        });
        if (is_null($status))
            return redirect('manage/rolesList')->with(['message' => '操作成功']);
    }

    /**
     * 权限列表
     *
     * @return mixed
     */
    public function getPermissionsList(Request $request)
    {
        $merge = $request->all();
        $list = Permission::select('permissions.id','permissions.name','permissions.display_name','permissions.module_type','menu.name as menu_name')
            ->leftJoin('menu','menu.id','=','permissions.module_type');
        if ($request->get('id')){
            $list = $list->where('permissions.id', $request->get('id'));
        }
        if ($request->get('display_name')){
            $list = $list->where('permissions.display_name','like','%'. $request->get('display_name').'%');
        }
        if ($request->get('name')){
            $list = $list->where('permissions.name','like','%'.  $request->get('name').'%');
        }
        $order = $request->get('order') ? $request->get('order') : 'desc';
        if ($request->get('module_type')!=""){
            $list = $list->where('permissions.module_type', $request->get('module_type'));
        }
        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;
        $list = $list->orderBy('permissions.id',$order)->paginate($paginate);
        $listArr = $list->toArray();
//        dd($listArr);
        $type = ModuleTypeModel::get();
        $data = array(
            'merge' => $merge,
            'listArr' => $listArr,
            'id'=>$request->get('id'),
            'display_name'=>$request->get('display_name'),
            'name'=>$request->get('name'),
            'module_type'=>$request->get('module_type'),
            'type'=>$type,
            'list'=>$list,
            'paginate' => $paginate,
        );
        return $this->theme->scope('manage.permissionsList',$data)->render();
    }

    /**
     * 添加权限视图
     *
     * @return mixed
     */
    public function getPermissionsAdd()
    {
        $modules = ModuleTypeModel::get();
        $data = array(
            'modules'=>$modules
        );
        return $this->theme->scope('manage.permissionsAdd',$data)->render();
    }

    /**
     * 添加权限
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPermissionsAdd(Request $request)
    {
        $data = $request->except('_token');
        $status = DB::transaction(function() use($data){
            $re =  Permission::insertGetId($data);
            //创建权限和菜单之间的关系
            $permission_user = ['menu_id'=>$data['module_type'],'permission_id'=>$re];
            MenuPermissionModel::insert($permission_user);
        });

        if(is_null($status))
            return redirect('manage/permissionsList')->with(['message' => '操作成功']);
    }

    /**
     * 删除权限
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getPermissionsDel($id){
        $re = Permission::where('id',$id)->delete();
        if($re)
            return redirect()->to('manage/permissionsList')->with(['message' => '操作成功']);
    }

    /**
     * 权限详情页
     *
     * @param $id
     * @return mixed
     */
    public function getPermissionsDetail($id)
    {
        //获取上一项id
        $preId = Permission::where('id', '>', $id)->min('id');
        //获取下一项id
        $nextId = Permission::where('id', '<', $id)->max('id');
        $info = Permission::select('permissions.*','mp.menu_id')
            ->where('permissions.id',$id)
            ->join('menu_permission as mp','permissions.id','=','mp.permission_id')
            ->first();
        $modules = ModuleTypeModel::get();
        $data = array(
            'modules'=>$modules,
            'info'=>$info,
            'preId'=>$preId,
            'nextId'=>$nextId
        );
        return $this->theme->scope('manage.permissionsDetail',$data)->render();
    }

    /**
     * 更新权限
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPermissionsDetail(Request $request)
    {
        $id = $request->get('id');
        $menu_id = $request->get('menu_id');
        $data = $request->except('id','_token','menu_id');
        $re = Permission::where('id',$id)->update($data);
        $permission = Permission::where('id',$id)->first();
        //删除原有的权限菜单关系
        $result1 = MenuPermissionModel::where('permission_id',$permission['id'])->delete();
        $result = MenuPermissionModel::firstOrCreate(['menu_id'=>$menu_id,'permission_id'=>$permission['id']]);
        if($re || $result)
            return redirect('manage/permissionsList')->with(['message' => '操作成功']);

    }
}
