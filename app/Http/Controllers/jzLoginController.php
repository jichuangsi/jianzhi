<?php
/**
 * ------------------------
 * Created by PhpStorm.
 * ------------------------
 *
 * ------------------------
 * Author: frank
 * Date: 16-4-19
 * Desc:
 * ------------------------
 *
 */

namespace App\Http\Controllers;

use App\Modules\Advertisement\Model\RecommendModel;
use App\Modules\Advertisement\Model\RePositionModel;
use App\Modules\Finance\Model\CashoutModel;
use App\Modules\Manage\Model\LinkModel;
use App\Modules\Shop\Models\GoodsModel;
use App\Modules\Task\Model\SuccessCaseModel;
use App\Modules\Task\Model\WorkModel;
use App\Modules\User\Model\CommentModel;
use App\Modules\User\Model\TaskModel;
use App\Modules\User\Model\AuthRecordModel;
use App\Modules\User\Model\UserModel;
use Illuminate\Routing\Controller;
use App\Modules\Advertisement\Model\AdTargetModel;
use App\Modules\Manage\Model\ConfigModel;
use Cache;
use Teepluss\Theme\Theme;


class jzLoginController extends IndexController
{
    public function __construct()
    {        
        parent::__construct();
        $this->initTheme('common','jianzhi');
    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $data = array();
        return $this->theme->scope('login',$data)->render();

    }









}