<?php

namespace App\Modules\User\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;

class UserModel extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    
    protected $table = 'users';

    protected $primaryKey = 'id';

    
    protected $fillable = [
        'name', 'email', 'email_status', 'password', 'alternate_password', 'salt', 'status', 'overdue_date', 'validation_code', 'expire_date',
        'reset_password_code', 'remember_token','source'
    ];

    
    protected $hidden = ['password', 'remember_token'];


    
    static function encryptPassword($password, $sign = '')
    {
        return md5(md5($password . $sign));
    }

    static function checkUser($username)
    {
        $user = UserModel::where('name', $username)->orWhere('mobile', $username)->first();
        if ($user) {
            return true;
        }
        return false;
    }
    
    static function checkPassword($username, $password)
    {
        $user = UserModel::where('name', $username)->orWhere('mobile', $username)->first();
        if ($user) {
            $password = self::encryptPassword($password, $user->salt);
            if ($user->password === $password) {
                return true;
            }
        }
        return false;
    }
    
    static function checkPayPassword($email, $password)
    {
        $user = UserModel::where('email', $email)->first();
        if ($user) {
            $password = self::encryptPassword($password, $user->salt);
            if ($user->alternate_password == $password) {
                return true;
            }
        }
        return false;
    }
    
    static function psChange($data, $userInfo)
    {
        $user = new UserModel;
        $password = UserModel::encryptPassword($data['password'], $userInfo['salt']);
        $result = $user->where(['id'=>$userInfo['id']])->update(['password'=>$password]);

        return $result;
    }

    
    static function payPsUpdate($data, $userInfo)
    {
        $user = new UserModel;
        $password = UserModel::encryptPassword($data['password'], $userInfo['salt']);
        $result = $user->where(['id'=>$userInfo['id']])->update(['alternate_password'=>$password]);

        return $result;
    }

    
    static function createUser(array $data)
    {        
        $salt = \CommonClass::random(4);
        $validationCode = \CommonClass::random(6);
        $date = date('Y-m-d H:i:s');
        $now = time();
        $userArr = array(
            'name' => isset($data['username'])?$data['username']:(isset($data['mobile'])?$data['mobile']:''),
            'mobile' => $data['mobile'],
            'password' => UserModel::encryptPassword(isset($data['password'])?$data['password']:$data['mobile'], $salt),
            'alternate_password' => UserModel::encryptPassword(isset($data['password'])?$data['password']:$data['mobile'], $salt),
            'salt' => $salt,
            'last_login_time' => $date,
            'overdue_date' => date('Y-m-d H:i:s', $now + 60*60*3),
            'validation_code' => $validationCode,
            'created_at' => $date,
            'updated_at' => $date,
            //初始用户处于激活状态
            'email_status' => 2,
            'status' => 1,
            //用户类型1：个人；2：企业
            'type' => isset($data['type'])?$data['type']:'',
            //ע技能标签
            'skill' => isset($data['skill'])?$data['skill']:'',
            //微信信息
            'wechat' => isset($data['wx_openid'])?$data['wx_openid']:'',
            'nickname' => isset($data['wx_nickname'])?$data['wx_nickname']:'',
            'avatar' => isset($data['wx_headimgurl'])?$data['wx_headimgurl']:'',
        );
        $objUser = new UserModel();
        
        $status = $objUser->initUser($userArr);
        
        return $status;
        //��ʱȥ��������֤
        /* if ($status){
            $emailSendStatus = \MessagesClass::sendActiveEmail($data['email']);
            if (!$emailSendStatus){
                $status = false;
            }
            return $status;
        } */
    }


    
    private function initUser(array $data)
    {       
        $status = DB::transaction(function() use ($data){
            $skill = $data['skill'];
            unset($data['skill']);
            $wechat = $data['wechat'];
            unset($data['wechat']);
            $nickname = $data['nickname'];
            unset($data['nickname']);
            $avatar = $data['avatar'];
            unset($data['avatar']);
            
            $data['uid'] = UserModel::insertGetId($data);
            
            if($wechat) $data['wechat'] = $wechat;
            if($nickname) $data['nickname'] = $nickname;
            if($avatar) $data['avatar'] = $avatar;
            
            UserDetailModel::create($data);
            
            //技能标签
            if(!empty($skill)){
                $skills = explode(",", $skill);
                $allTag = TagsModel::findAll();
                $myTags = array();
                foreach($skills as $k => $v){
                    foreach($allTag  as $k1 => $v1){
                        if(intval($v) === $v1['cate_id']){
                            array_push($myTags, $v1['id']);
                            break;
                        }
                    }
                }
                if(count($myTags)>0){
                    UserTagsModel::insert($myTags, $data['uid']);
                }
            }
        });
        return is_null($status)? true : $status;
    }

    
    static function getUserName($id)
    {
        $userInfo = UserModel::where('id',$id)->first();
        return $userInfo->name;
    }
    /*
     * 获取企业列表
     */
	static function getqiye()
    {
//  	 $taskType = TaskTypeModel::select('*')->where('status','!=',0)->orderBy('pid', 'ASC')->orderBy('sort', 'ASC')->get()->toArray();
       $userInfo = UserModel::select('*')->where('type','=',2)->get()->toArray();
        return $userInfo;
    }
    static function getUserIdByMobileAndIDCard($mobile, $card_number)
    {
        $query = Self::select('users.id');
        
        if($mobile){
            $query = $query->where('users.mobile', $mobile);
        }
        
        if($card_number){
            $query = $query->where('realname_auth.card_number', $card_number);
        }
        
        $data = $query->leftjoin('realname_auth','realname_auth.uid', '=', 'users.id')->first();
        
        return $data;
    }
    
    
    public function isAuth($uid)
    {
        $auth = AuthRecordModel::where('uid',$uid)->where('status',4)->first();
        $bankAuth = BankAuthModel::where('uid',$uid)->where('status',4)->first();
        $aliAuth = AlipayAuthModel::where('uid',$uid)->where('status',4)->first();
        $data['auth'] = is_null($auth)?true:false;
        $data['bankAuth'] = is_null($bankAuth)?true:false;
        $data['aliAuth'] = is_null($aliAuth)?true:false;

        return $data;
    }

    
    static function editUser($data)
    {
            $status = DB::transaction(function () use ($data){
                if(isset($data['password'])&&!empty($data['password'])){
                    UserModel::where('id', $data['uid'])->update([
                        //'mobile' => $data['mobile'],
                        'password' => $data['password'],
                        'salt' => $data['salt'],
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                }/* else{
                    UserModel::where('id', $data['uid'])->update([
                        'mobile' => $data['mobile'],                       
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                } */
                
                UserDetailModel::where('uid', $data['uid'])->update([
                    'realname' => $data['realname'],
                    'qq' => isset($data['qq'])?$data['qq']:'',
                    'mobile' => isset($data['mobile'])?$data['mobile']:'',
                    'card_number' => $data['card_number'],
                    'province' => $data['province'],
                    'city' => $data['city'],
                    'area' => $data['area'],
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
                
                //���֤����
                $update = [
                    'realname' => $data['realname'],
                    'card_number' => $data['card_number'],
                    'account' => isset($data['account'])?$data['account']:'',
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ];
                
                if(isset($data['card_front_side'])&&!empty($data['card_front_side'])){                    
                    $update['card_front_side'] = $data['card_front_side'];
                }
                
                if(isset($data['card_back_dside'])&&!empty($data['card_back_dside'])){                    
                    $update['card_back_dside'] = $data['card_back_dside'];
                }
                
                RealnameAuthModel::where('uid', $data['uid'])->update($update);
                
                //ע�����Ӽ��ܱ�ǩ
                if(!empty($data['skill'])){
                    UserTagsModel::where('uid',$data['uid'])->delete();
                    
                    $skills = explode(",", $data['skill']);
                    $allTag = TagsModel::findAll();
                    $myTags = array();
                    foreach($skills as $k => $v){
                        foreach($allTag  as $k1 => $v1){
                            if(intval($v) === $v1['cate_id']){
                                array_push($myTags, $v1['id']);
                                break;
                            }
                        }
                    }
                    if(count($myTags)>0){
                        UserTagsModel::insert($myTags, $data['uid']);
                    }
                } 
            });
            
            
            
        return is_null($status) ? true : false;
    }

    
    static function addUser($data)
    {
        $status = DB::transaction(function () use ($data){
            $data['uid'] = UserModel::insertGetId([
                'name' => $data['name'],
                'email' => isset($data['email'])?$data['email']:'',
                'password' => $data['password'],
                'salt' => $data['salt'],
                'email_status' => $data['email_status'],
                'status' => $data['status'],
                'type' => $data['type'],
                'mobile' => $data['mobile'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            UserDetailModel::create([
                'uid' => $data['uid'],
                'realname' => $data['realname'],
                'qq' => isset($data['qq'])?$data['qq']:'',
                'mobile' => $data['mobile'],
                'card_number' => $data['card_number'],
                'province' => isset($data['province'])?$data['province']:'',
                'city' => isset($data['city'])?$data['city']:'',
                'area' => isset($data['area'])?$data['area']:'',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            //���֤����
            
            if(isset($data['astatus'])&&$data['astatus']){
                switch($data['astatus']){
                    case 1 : $status = 1; break;
                    case 2 : $status = 0; break;
                    case 3 : $status = 2; break;
                    default : $status = 0; break;
                }
                if($data['card_front_side']&&$data['card_back_dside']){
                    $data['aid'] = RealnameAuthModel::insertGetId([
                        'uid' => $data['uid'],
                        'username' => $data['name'],
                        'realname' => $data['realname'],
                        'status' => $status,
                        'card_number' => $data['card_number'],
                        'account' => isset($data['account'])?$data['account']:'',
                        'card_front_side' => $data['card_front_side'],
                        'card_back_dside' => $data['card_back_dside'],
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                    
                    AuthRecordModel::create([
                        'auth_id' => $data['aid'],
                        'uid' => $data['uid'],
                        'username' => $data['name'],
                        'auth_code' => 'realname',
                        'auth_time' => date('Y-m-d H:i:s', time()),
                    ]);
                }
            }else{
                if($data['card_front_side']&&$data['card_back_dside']){
                    $data['aid'] = RealnameAuthModel::insertGetId([
                        'uid' => $data['uid'],
                        'username' => $data['name'],
                        'realname' => $data['realname'],
                        'card_number' => $data['card_number'],
                        'account' => isset($data['account'])?$data['account']:'',
                        'card_front_side' => $data['card_front_side'],
                        'card_back_dside' => $data['card_back_dside'],
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                    
                    AuthRecordModel::create([
                        'auth_id' => $data['aid'],
                        'uid' => $data['uid'],
                        'username' => $data['name'],
                        'auth_code' => 'realname',
                        'auth_time' => date('Y-m-d H:i:s', time()),
                    ]);
                }
            }
            
            //ע�����Ӽ��ܱ�ǩ
            if(!empty($data['skill'])){
                $skills = explode(",", $data['skill']);
                $allTag = TagsModel::findAll();
                $myTags = array();
                foreach($skills as $k => $v){
                    foreach($allTag  as $k1 => $v1){
                        if(intval($v) === $v1['cate_id']){
                            array_push($myTags, $v1['id']);
                            break;
                        }
                    }
                }
                if(count($myTags)>0){
                    UserTagsModel::insert($myTags, $data['uid']);
                }
            }            
        });
        return is_null($status) ? true : false;
    }

    
    static function addEnterprise($data)
    {
        $status = DB::transaction(function () use ($data){
            $data['uid'] = UserModel::insertGetId([
                'name' => $data['name'],
                'email' => isset($data['email'])?$data['email']:'',
                'password' => $data['password'],
                'salt' => $data['salt'],
                'email_status' => $data['email_status'],
                'status' => $data['status'],
                'type' => $data['type'],
                'mobile' => $data['mobile'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            UserDetailModel::create([
                'uid' => $data['uid'],
                'mobile' => $data['mobile'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            //���֤����
            if(isset($data['astatus'])&&$data['astatus']){
                switch($data['astatus']){
                    case 1 : $status = 1; break;
                    case 2 : $status = 0; break;
                    case 3 : $status = 2; break;
                    default : $status = 0; break;
                }
                if($data['business_license']){
                    $data['aid'] = EnterpriseAuthModel::insertGetId([
                        'uid' => $data['uid'],
                        'company_name' => $data['company_name'],
                        'status' => $status,
                        'province' => isset($data['province'])?$data['province']:'',
                        'city' => isset($data['city'])?$data['city']:'',
                        'area' => isset($data['area'])?$data['area']:'',
                        'address' => $data['address'],
                        'contactor' => $data['contactor'],
                        'contactor_mobile' => $data['contactor_mobile'],
                        'phone' => $data['phone'],
                        'tax_code' => $data['tax_code'],
                        'bank' => $data['bank'],
                        'account' => $data['account'],
                        'owner' => $data['owner'],
                        'company_email' => isset($data['company_email'])?$data['company_email']:'',
                        'business_license' => $data['business_license'],
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                    
                    AuthRecordModel::create([
                        'auth_id' => $data['aid'],
                        'uid' => $data['uid'],
                        'username' => $data['name'],
                        'auth_code' => 'enterprise',
                        'auth_time' => date('Y-m-d H:i:s', time()),
                    ]);
                }
            }else{
                if($data['business_license']){
                    $data['aid'] = EnterpriseAuthModel::insertGetId([
                        'uid' => $data['uid'],
                        'company_name' => $data['company_name'],
                        'province' => isset($data['province'])?$data['province']:'',
                        'city' => isset($data['city'])?$data['city']:'',
                        'area' => isset($data['area'])?$data['area']:'',
                        'address' => $data['address'],
                        'contactor' => $data['contactor'],
                        'contactor_mobile' => $data['contactor_mobile'],
                        'phone' => $data['phone'],
                        'tax_code' => $data['tax_code'],
                        'bank' => $data['bank'],
                        'account' => $data['account'],
                        'owner' => $data['owner'],
                        'company_email' => $data['company_email'],
                        'business_license' => $data['business_license'],
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                    
                    AuthRecordModel::create([
                        'auth_id' => $data['aid'],
                        'uid' => $data['uid'],
                        'username' => $data['name'],
                        'auth_code' => 'enterprise',
                        'auth_time' => date('Y-m-d H:i:s', time()),
                    ]);
                }
            }
        });
            return is_null($status) ? true : false;
    }
    
    static function editEnterprise($data)
    {
        $status = DB::transaction(function () use ($data){
            if(isset($data['password'])&&!empty($data['password'])){
                UserModel::where('id', $data['uid'])->update([
                    //'email' => $data['email'],
                    'password' => $data['password'],
                    'salt' => $data['salt'],
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
            }/* else{
                UserModel::where('id', $data['uid'])->update([
                'mobile' => $data['mobile'],
                'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
            } */
            
            /* UserDetailModel::where('uid', $data['uid'])->update([
                'realname' => $data['realname'],
                'qq' => $data['qq'],
                'mobile' => $data['mobile'],
                'card_number' => $data['card_number'],
                'province' => $data['province'],
                'city' => $data['city'],
                'area' => $data['area'],
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]); */
            
            //���֤����
            $update = [
                'company_name' => $data['company_name'],
                'province' => $data['province'],
                'city' => $data['city'],
                'area' => $data['area'],
                'address' => $data['address'],
                'contactor' => $data['contactor'],
                'contactor_mobile' => $data['contactor_mobile'],
                'phone' => $data['phone'],
                'tax_code' => $data['tax_code'],
                'bank' => $data['bank'],
                'account' => $data['account'],
                'owner' => $data['owner'],
                'company_email' => $data['company_email'],                
                'updated_at' => date('Y-m-d H:i:s', time()),
            ];            
            
            if(isset($data['business_license'])&&!empty($data['business_license'])){
                $update['business_license'] = $data['business_license'];
            }
            
            EnterpriseAuthModel::where('uid', $data['uid'])->update($update);
        });
        
            return is_null($status) ? true : false;
    }
    
    static function getUsersById($ids){
        $query = Self::select('users.*', 'realname_auth.card_number', 'realname_auth.realname');
        if(is_array($ids)){
            $query = $query->whereIn('users.id', $ids);
        }else{
            $query = $query->where('users.id', $ids);
        }
        
        $data = $query
                    //->leftjoin("user_detail", 'users.id', '=', 'user_detail.uid')
                    ->leftjoin("realname_auth", 'users.id', '=', 'realname_auth.uid')
                    ->get();
        
        return $data;
    }
    
    static function getUsersByOpenid($openid){
        $query = Self::select('users.id as u_id', 'users.name as u_name', 'users.mobile as u_mobile', 'realname_auth.card_number as u_card_number', 'realname_auth.realname as u_realname');
        
        if(is_array($openid)){
            $query = $query->whereIn('user_detail.wechat', $openid);
        }else{
            $query = $query->where('user_detail.wechat', $openid);
        }
        
        $data = $query
                ->leftjoin("user_detail", 'users.id', '=', 'user_detail.uid')
                ->leftjoin("realname_auth", 'users.id', '=', 'realname_auth.uid')
                ->get();
        
        return $data;
    }
    
    static function checkOpenid($username, $openid)
    {        
        $users = UserDetailModel::select('users.name','users.mobile')->where('wechat', $openid)->join('users', 'users.id', '=', 'user_detail.uid')->get();
        
        if(!$users||count($users)===0||($users&&count($users)===1&&($users->name===$username||$users->mobile===$username))){            
            return true;
        }
        
        return false;
    }
}
