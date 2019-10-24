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

    
    static function checkPassword($username, $password)
    {
        $user = UserModel::where('name', $username)->orWhere('email', $username)->first();
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
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => UserModel::encryptPassword($data['password'], $salt),
            'alternate_password' => UserModel::encryptPassword($data['password'], $salt),
            'salt' => $salt,
            'last_login_time' => $date,
            'overdue_date' => date('Y-m-d H:i:s', $now + 60*60*3),
            'validation_code' => $validationCode,
            'created_at' => $date,
            'updated_at' => $date,
            //初始化激活用户
            'email_status' => 2,
            'status' => 1,
            //注册增加用户类型
            'type' => isset($data['type'])?$data['type']:'',
            //注册增加技能标签
            'skill' => isset($data['skill'])?$data['skill']:''
        );
        $objUser = new UserModel();
        
        $status = $objUser->initUser($userArr);
        
        return $status;
        //暂时去掉邮箱认证
        /* if ($status){
            $emailSendStatus = \MessagesClass::sendActiveEmail($data['email']);
            if (!$emailSendStatus){
                $status = false;
            }
            return $status;
        } */
    }


    
    public function initUser(array $data)
    {       
        $status = DB::transaction(function() use ($data){
            $skill = $data['skill'];
            unset($data['skill']);
            
            $data['uid'] = UserModel::insertGetId($data);
            UserDetailModel::create($data);
            
            //注册增加技能标签
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

    static function getUserIdByMobileAndIDCard($mobile, $card_number)
    {
        $query = Self::select('users.id');
        
        if($mobile){
            $query = $query->where('user_detail.mobile', $mobile);
        }
        
        if($card_number){
            $query = $query->where('user_detail.card_number', $card_number);
        }
        
        $data = $query->leftjoin('user_detail','user_detail.uid', '=', 'users.id')
            ->first();
        
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
                        //'email' => $data['email'],
                        'password' => $data['password'],
                        'salt' => $data['salt'],
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                }/* else{
                    UserModel::where('id', $data['uid'])->update([
                        'email' => $data['email'],
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                } */
                
                UserDetailModel::where('uid', $data['uid'])->update([
                    'realname' => $data['realname'],
                    'qq' => $data['qq'],
                    'mobile' => $data['mobile'],
                    'card_number' => $data['card_number'],
                    'province' => $data['province'],
                    'city' => $data['city'],
                    'area' => $data['area'],
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
                
                //身份证保存
                $update = [
                    'realname' => $data['realname'],
                    'card_number' => $data['card_number'],
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ];
                
                if(isset($data['card_front_side'])&&!empty($data['card_front_side'])&&isset($data['card_back_dside'])&&!empty($data['card_back_dside'])){                    
                    $update['card_front_side'] = $data['card_front_side'];
                    $update['card_back_dside'] = $data['card_back_dside'];
                }
                
                RealnameAuthModel::where('uid', $data['uid'])->update($update);
                
                //注册增加技能标签
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
            //身份证保存
            
            if($data['astatus']){
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
            
            //注册增加技能标签
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
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            UserDetailModel::create([
                'uid' => $data['uid'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            //身份证保存
            if($data['astatus']){
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
            'email' => $data['email'],
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
            
            //身份证保存
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
}
