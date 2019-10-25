<?php

namespace App\Http\Controllers;

use App\Modules\Manage\Model\MenuModel;
use App\Modules\Manage\Model\MenuPermissionModel;
use App\Modules\Manage\Model\Permission;
use App\Modules\Manage\Model\ManagerModel;
use App\Modules\Manage\Model\ConfigModel;
use Illuminate\Support\Facades\Route;
use Cache;
use Exception;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Worksheet_MemoryDrawing;
use PHPExcel_Reader_Excel2007;


class ManageController extends BasicController
{
    public $manager;
    public function __construct()
    {
        parent::__construct();

        //初始化后台菜单
        if (ManagerModel::getManager())
        {
            //
            $this->manageBreadcrumb();
            $this->breadcrumb = $this->theme->breadcrumb();
            $this->manager = ManagerModel::getManager();
            $this->theme->setManager($this->manager->username);

            //初始化后台菜单
            $manageMenu = MenuModel::getMenuPermission();
            $this->theme->set('manageMenu', $manageMenu);
        }

        //路由与面包屑
        $route = Route::currentRouteName();
        //查询权限,除了登录页面的路由
        if($route!='loginCreatePage')
        {
            $permission = Permission::where('name',$route)->first();
            if(!is_null($permission))
            {
                $permission = MenuPermissionModel::where('permission_id',$permission['id'])->first()->toArray();
                //查询菜单
                $menu_data = MenuModel::getMenu($permission['menu_id']);
                $this->theme->set('menu_data', $menu_data['menu_data']);
                $this->theme->set('menu_ids',$menu_data['menu_ids']);
            }
        }

        //获取基本配置（IM css自适应 客服QQ）
        $basisConfig = ConfigModel::getConfigByType('basis');
        if(!empty($basisConfig)){
            $this->theme->set('basis_config',$basisConfig);
        }

        //菜单图标(先写死)
        $menuIcon = [
            //旧菜单
			'后台首页'=>'fa-home',
            '系统配置'=>'fa-cog',
            '用户管理'=>'fa-users',
            '店铺管理'=>'fa-home',
            '任务控制台'=>'fa-tasks',
            '推荐管理'=>'fa-external-link',
            '站长工具'=>'fa-user',
            '资讯管理'=>'fa-file-text',
            '财务管理'=>'fa-bar-chart-o',
            '短信模板'=>'fa-envelope',
        ];
        $this->theme->set('menuIcon',$menuIcon);

        //获取授权码
        $kppwAuthCode = config('kppw.kppw_auth_code');
        if(!empty($kppwAuthCode)){
            $kppwAuthCode = \CommonClass::starReplace($kppwAuthCode, 5, 4);
            $this->theme->set('kppw_auth_code',$kppwAuthCode);
        }

    }
    
    //Excel 相关方法
    /**
     * 数据导出
     * @param array $title   标题行名称
     * @param array $data   导出数据
     * @param string $sheetName sheet名
     * @param string $fileName 文件名
     * @param string $savePath 保存路径
     * @param $type   是否下载  false--保存   true--下载
     * @return string   返回文件全路径
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    protected function exportExcel($title=array(), $data=array(), $sheetName='sheet1', $fileName='', $savePath='./', $isDown=false){
        
        $obj = new \PHPExcel();
        
        //横向单元格标识
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        
        $obj->getActiveSheet(0)->setTitle($sheetName);   //设置sheet名称
        $_row = 1;   //设置纵向单元格标识
        if($title){
            $i = 0;
            foreach($title AS $v){   //设置列标题
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);
                $i++;
            }
            $_row++;
        }
        
        //填写数据
        if($data){
            $i = 0;
            foreach($data AS $_v){
                $j = 0;
                foreach($_v AS $_cell){
                    $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);
                    $j++;
                }
                $i++;
            }
        }
        
        //文件名处理
        if(!$fileName){
            $fileName = uniqid(time(),true);
        }
        $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
        
        if($isDown){   //网页下载
            //header('pragma:public');
            header("Content-Disposition:attachment;filename=$fileName.xlsx");
            $objWrite->save('php://output');
            exit;
        }
        
        $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码
        header('pragma:public');
        header("Content-Disposition:attachment;filename=$_fileName.xlsx");
        $objWrite->save('php://output');exit;
        
        return $savePath.$fileName.'.xlsx';
    }
    
    protected function fileImport($file, $allowExtension = array(), $template = ''){
        $importdUrl = '';
        $errMsg = '';
        if(empty($template)){
            if ($file) {
                $uploadMsg = json_decode(\FileClass::uploadFile($file, 'user', $allowExtension));
                
                if ($uploadMsg->code != 200) {
                    $errMsg = $uploadMsg->message;
                } else {
                    $importdUrl = $uploadMsg->data->url;
                }
            }else{
                return ['fail'=>true, 'errMsg'=>'缺少必要参数！'];
            }
        }else{
            $importdUrl = $template;
        }        
        
        if (!empty($errMsg)) {
            return ['fail'=>true, 'errMsg'=>$errMsg];
        }
        
        try {
            $inputFileType = PHPExcel_IOFactory::identify($importdUrl);
            if('Excel5'===$inputFileType){
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            }else if('Excel2007'===$inputFileType){
                $objReader = new PHPExcel_Reader_Excel2007();
            }
            if(!$objReader) return ['success'=>false, 'errMsg'=>'缺少必要参数！'];
            $objPHPExcel = $objReader->load($importdUrl);
        } catch(Exception $e) {
            //die('加载文件发生错误："'.pathinfo($importdUrl,PATHINFO_BASENAME).'": '.$e->getMessage());
            return ['fail'=>true, '$errMsg'=>$e->getMessage()];
        }
        //$sheet = $objPHPExcel->getSheet(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $data=$sheet->toArray();//该方法读取不到图片 图片需单独处理
        
        
        $imageFilePath= 'attachment' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . date('Y/m/d') . DIRECTORY_SEPARATOR;//图片在本地存储的路径
        if (! file_exists ( $imageFilePath )) {
            mkdir("$imageFilePath", 0777, true);
        }
        
        $this->extractImageFromWorksheet($sheet, $imageFilePath, $data);
        //dump($data);
        
        return $data;
    }
    
    
    private function extractImageFromWorksheet($worksheet,$basePath,&$data){
        
        foreach ($worksheet->getDrawingCollection() as $drawing) {
            list($startColumn,$startRow)= PHPExcel_Cell::coordinateFromString($drawing->getCoordinates());//获取图片所在行和列
            $startColumn = $this->ABC2decimal($startColumn);//由于图片所在位置的列号为字母，转化为数字
            $imageFilefolder = $drawing->getCoordinates() .time(). mt_rand(10000, 99999);
            //$xy=$drawing->getCoordinates();
            $path = $basePath . $imageFilefolder . DIRECTORY_SEPARATOR;
            if (! file_exists ( $path )) {
                mkdir("$path", 0777, true);
            }
            
            // for xlsx
            if ($drawing instanceof PHPExcel_Worksheet_Drawing) {
                $filename = $drawing->getPath();
                //$imageFileName = $drawing->getIndexedFilename();
                $path = $path . $drawing->getIndexedFilename();
                copy($filename, $path);
                //$result[$xy] = $path;
                $data[$startRow-1][$startColumn]=$path;//把图片插入到数组中
                // for xls
            } else if ($drawing instanceof PHPExcel_Worksheet_MemoryDrawing) {
                $image = $drawing->getImageResource();
                $renderingFunction = $drawing->getRenderingFunction();
                switch ($renderingFunction) {
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG:
                        //$imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $drawing->getIndexedFilename();
                        imagejpeg($image, $path);
                        break;
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_GIF:
                        //$imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $drawing->getIndexedFilename();
                        imagegif($image, $path);
                        break;
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG:
                        //$imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $drawing->getIndexedFilename();
                        imagegif($image, $path);
                        break;
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT:
                        //$imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $drawing->getIndexedFilename();
                        imagegif($image, $path);
                        break;
                }
                //$result[$xy] = $imageFileName;
                $data[$startRow-1][$startColumn]=$path;//把图片插入到数组中
            }
        }
    }
    
    private function ABC2decimal($abc){
        $ten = 0;
        $len = strlen($abc);
        for($i=1;$i<=$len;$i++){
            $char = substr($abc,0-$i,1);//反向获取单个字符
            
            $int = ord($char);
            $ten += ($int-65)*pow(26,$i-1);
        }
        return $ten;
    }
    
    protected function genEMail($val){
        return $val.'@'.$val.'.com';
    }
    //Excel 相关方法
}
