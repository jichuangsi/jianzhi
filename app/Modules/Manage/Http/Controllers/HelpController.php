<?php
namespace App\Modules\Manage\Http\Controllers;

use App\Http\Controllers\BasicController;
use App\Http\Controllers\ManageController;
use App\Modules\Manage\Model\ArticleCategoryModel;
use App\Modules\Manage\Model\ArticleModel;
use App\Http\Requests;
use App\Modules\Manage\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;
use Theme;
use Illuminate\Support\Facades\Auth;
use App\Modules\Manage\Model\HelpModel;
use App\Modules\Manage\Http\Requests\HelpRequest;

class HelpController extends ManageController
{
    public function __construct()
    {
        parent::__construct();
        $this->initTheme('manage');
        $this->theme->set('manageType', 'article');

    }

    /**
     * 后台文章列表
     * @param $upID 文章分类父id
     * @param Request $request
     * @return mixed
     */
    public function helpList(Request $request)
    {
        $arr = $request->all();
        //文章列表
        $articleList = HelpModel::whereRaw('1 = 1');
        
        //标题筛选
        if ($request->get('title')) {
            $articleList = $articleList->where('help.title', 'like', "%" . e($request->get('title')) . '%');
        }
        if($request->get('start')){
            $start = date('Y-m-d H:i:s',strtotime($request->get('start')));
            $articleList = $articleList->where('help.created_at','>',$start);
        }
        if($request->get('end')){
            $end = date('Y-m-d H:i:s',strtotime($request->get('end')));
            $articleList = $articleList->where('help.created_at','<',$end);
        }
        $by = $request->get('by') ? $request->get('by') : 'help.created_at';
        $order = $request->get('order') ? $request->get('order') : 'desc';
        $paginate = $request->get('paginate') ? $request->get('paginate') : 10;


        $list = $articleList->select('help.id', 'help.title', 'help.content', 'help.view', 'help.created_at','help.uid','help.user_name')->where('status',1)
            ->orderBy($by, $order)->paginate($paginate);
        $listArr = $list->toArray();
        
        $data = array(
            'merge' => $arr,            
            'title' => $request->get('title'),
            'start' => $request->get('start'),
            'end' => $request->get('end'),
            'paginate' => $request->get('paginate'),
            'order' => $request->get('order'),
            'by' => $request->get('by'),
            'article_data' => $listArr,
            'article' => $list,

        );
        return $this->theme->scope('manage.helpList', $data)->render();

    }

    /**
     * 删除一条资讯消息
     * @param $upID 分类父id
     * @param $id
     */
    public function helpDelete($id)
    {        
        $result = HelpModel::where('id', $id)->delete();
        if (!$result) {
            return redirect()->to('/manage/helpList')->with(array('message' => '操作失败'));
        }
        return redirect()->to('/manage/helpList')->with(array('message' => '操作成功'));
    }

    /**
     * 批量删除
     * @param Request $request
     */
    public function allHelpDelete(Request $request)
    {
        $data = $request->except('_token');
        
        $res = HelpModel::destroy($data);
        if ($res) {
            return redirect()->to('/manage/helpList')->with(array('message' => '操作成功'));
        }
        return redirect()->to('/manage/helpList')->with(array('message' => '操作失败'));
    }

    /**
     * 新建资讯文章视图
     * @param $upID 文章分类父id
     * @param Request $request
     * @return mixed
     */
    public function addHelp(Request $request)
    {
        
        $data = array(
            
        );
        return $this->theme->scope('manage.addHelp', $data)->render();
    }

    /**
     * 新建资讯文章
     * @param ArticleRequest $request
     */
    public function postHelp(HelpRequest $request)
    {
        //获取文章信息
        $data = $request->except('_token');
        
        $data['created_at'] = date('Y-m-d H:i:s',time());
        $data['updated_at'] = date('Y-m-d H:i:s',time());
        //$data['sort'] = $request->get('sort');        
        $data['uid'] = $this->manager->id;
        $data['user_name'] = $this->manager->username;   
        $data['status'] = 1;
        $data['content'] = htmlspecialchars($data['content']);
        if(mb_strlen($data['content']) > 4294967295/3){
            $error['content'] = '文章内容太长，建议减少上传图片';
            if (!empty($error)) {
                return redirect('/manage/addHelp')->withErrors($error);
            }
        }
        //添加信息
        $res = HelpModel::create($data);
        if ($res) {
            return redirect('/manage/helpList')->with(array('message' => '操作成功'));
        }
        return false;
    }

    /**
     * 编辑文章视图
     * @param Request $request
     * @param $id 文章id
     * @param $upID 文章分类父id
     * @return mixed
     */
    public function editHelp(Request $request, $id)
    {
        $id = intval($id);
        
        $article = HelpModel::where('id', $id)->first();
        $data = array(
            'article' => $article,
        );
        return $this->theme->scope('manage.editHelp', $data)->render();
    }

    /**
     * 编辑文章
     * @param Request $request
     */
    public function postEditHelp(HelpRequest $request)
    {
        $data = $request->except('_token');
        
        $data['content'] = htmlspecialchars($data['content']);
        if(mb_strlen($data['content']) > 4294967295/3){
            $error['content'] = '文章内容太长，建议减少上传图片';
            if (!empty($error)) {
                return redirect('/manage/editHelp')->withErrors($error);
            }
        }
        $arr = array(
            'title' => $data['title'],
            //'sort' => $data['sort'],
            'content' => $data['content'],
            'updated_at' => date('Y-m-d H:i:s',time()),
        );
        //修改信息
        $res = HelpModel::where('id', $data['artID'])->update($arr);
        if ($res) {
            return redirect('/manage/helpList')->with(array('message' => '操作成功'));
        }
    }


}
