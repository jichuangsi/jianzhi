<?php

namespace App\Modules\Jianzhi\Http\Controllers;

use Auth;
use App\Modules\Task\Model\TaskCateModel;
use App\Modules\Task\Model\TaskTypeModel;
use App\Modules\Task\Model\TaskModel;
use App\Modules\User\Model\DistrictModel;
use App\Modules\User\Http\Controllers\UserCenterController as BasicUserCenterController;
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
        }else{
            $path = 'auth';
        }
        return $this->theme->scope($path,$data)->render();
    
    }
    
    public function info(){
        $data = array();
        if($this->user->type===2){
            $path = 'eInfo';
        }else{
            $path = 'info';
        }
        return $this->theme->scope($path,$data)->render();
        
    }
        
    public function help(){
        $data = array();
        $path = 'help';
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
        return $this->theme->scope($path,$data)->render();
    }
    
    public function skill(){
        $data = array();
        $path = 'skill';
        return $this->theme->scope($path,$data)->render();
    }
}
