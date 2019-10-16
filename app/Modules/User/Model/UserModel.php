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
            UserModel::where('id', $data['uid'])->update([
                'email' => $data['email'],
                'password' => $data['password'],
                'salt' => $data['salt']
            ]);
            UserDetailModel::where('uid', $data['uid'])->update([
                'realname' => $data['realname'],
                'qq' => $data['qq'],
                'province' => $data['province'],
                'city' => $data['city'],
                'area' => $data['area']
            ]);
        });
        return is_null($status) ? true : false;
    }

    
    static function addUser($data)
    {
        $status = DB::transaction(function () use ($data){
            $data['uid'] = UserModel::insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'salt' => $data['salt']
            ]);
            UserDetailModel::create([
                'uid' => $data['uid'],
                'realname' => $data['realname'],
                'qq' => $data['qq'],
                'mobile' => $data['mobile'],
                'province' => $data['province'],
                'city' => $data['city'],
                'area' => $data['area']
            ]);
        });
        return is_null($status) ? true : false;
    }

}
