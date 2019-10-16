<?php

namespace App\Modules\Jianzhi\Http\Controllers;

use App\Http\Controllers\HomeController;
use Auth;
use App\Modules\Task\Model\TaskCateModel;
use App\Modules\Task\Model\TaskTypeModel;
use App\Modules\Task\Model\TaskModel;
use App\Modules\User\Model\DistrictModel;
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

class jzHomeController extends HomeController
{    

    public function __construct()
    {
        parent::__construct();
        $this->initTheme('common','jianzhi');
        $this->user = Auth::user();
        $this->middleware('auth');
    }
    
    public function home(){        
        $data = array();
        //dump( TaskCateModel::findByPid([0]));exit;
        if($this->user->type===2){
            $path = 'etask';
            //任务类型
            $taskType = TaskTypeModel::findByPid([0]);
            //技能标签     
            $taskCate = TaskCateModel::findAll();
            //查询地区一级数据
            $province = DistrictModel::findTree(0);
            //查询地区二级信息
            $city = DistrictModel::findTree($province[0]['id']);
            //查询三级
            $area = DistrictModel::findTree($city[0]['id']);
            
            $data = array(
                'taskType' => $taskType,
                'taskCate' => $taskCate,
                'province' => $province,
                'area' => $area,
                'city' => $city,
            );
            
        }else{
            $path = 'home';
            
            //首页banner
            $banner = \CommonClass::getHomepageBanner();
            $taskType = TaskTypeModel::findByPid([0]);
            $province = DistrictModel::findTree(0);
            
            
            $data = array(
                'banner' => $banner,
                'taskType' => $taskType,
                'province'=>$province
            );
            
        }
        return $this->theme->scope($path,$data)->render();
    }
    
    public function task(){
        $data = array();
        
        if($this->user->type===2){
            $path = 'eproject';
            
        }else{
            $path = 'task';
        }
        return $this->theme->scope($path,$data)->render();
    }
    
    public function my(){
        $data = array();
        
        if($this->user->type===2){
            $path = 'emy';
        }else{
            $path = 'my';
        }
        return $this->theme->scope($path,$data)->render();
    }
}
