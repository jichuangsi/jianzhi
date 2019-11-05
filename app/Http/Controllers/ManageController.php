<?php

namespace App\Http\Controllers;

use App\Modules\Manage\Model\MenuModel;
use App\Modules\Manage\Model\MenuPermissionModel;
use App\Modules\Manage\Model\Permission;
use App\Modules\Manage\Model\ManagerModel;
use App\Modules\Manage\Model\ConfigModel;
use App\Modules\User\Model\MessageReceiveModel;
use Illuminate\Support\Facades\Route;
use Cache;
use Exception;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Worksheet_MemoryDrawing;
use PHPExcel_Reader_Excel2007;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Style_Color;
use Illuminate\Http\Request;
use App\Modules\User\Model\AttachmentModel;

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
        
        
        //获取通知
        if($this->manager){
            $messageCount = MessageReceiveModel::where('js_id', $this->manager->id)->where('message_type', 1)->where('status', 0)->count();
            $this->theme->set('messageCount',$messageCount);
            if($messageCount){
                //企业认证消息
                $eAuth_messageCount = MessageReceiveModel::where('js_id', $this->manager->id)->where('message_type', 1)->where('status', 0)->where('code_name', 'enterprise_auth')->count();
                if($eAuth_messageCount) $this->theme->set('eAuth_messageCount',$eAuth_messageCount);
                //个人认证消息
                $uAuth_messageCount = MessageReceiveModel::where('js_id', $this->manager->id)->where('message_type', 1)->where('status', 0)->where('code_name', 'realname_auth')->count();
                if($uAuth_messageCount) $this->theme->set('uAuth_messageCount',$uAuth_messageCount);
                //任务审核消息
                $newTask_messageCount = MessageReceiveModel::where('js_id', $this->manager->id)->where('message_type', 1)->where('status', 0)->where('code_name', 'new_task')->count();
                if($newTask_messageCount) $this->theme->set('newTask_messageCount',$newTask_messageCount);
                //意见反馈消息
                $newFeedback_messageCount = MessageReceiveModel::where('js_id', $this->manager->id)->where('message_type', 1)->where('status', 0)->where('code_name', 'user_feedback')->count();
                if($newFeedback_messageCount) $this->theme->set('newFeedback_messageCount',$newFeedback_messageCount);
            }
        }

    }
    
    protected function ajaxChangeMessageStatus(Request $request)
    {
        $type = $request->get('type');
        if(!empty($type)){
            $data = array(
                'status' => 1,
                'read_time' => date('Y-m-d H:i:s',time())
            );
            $res = MessageReceiveModel::where('code_name',$type)->where('status', 0)->update($data);
            if(!empty($res)){
                $data = array(
                    'code' => 1,
                    'msg' => '修改成功'
                );
            }else{
                $data = array(
                    'code' => 0,
                    'msg' => '修改失败'
                );
            }
        }else{
            $data = array(
                'code' => 0,
                'msg' => '缺少参数'
            );
        }
        return response()->json($data);
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
    protected function exportExcel($param = array()){
    //protected function exportExcel($title=array(), $data=array(), $sheetName='sheet1', $fileName='', $savePath='./', $isDown=false, $tip = ''){
        
        if(empty($param)) return;
        
        $obj = new \PHPExcel();
        
        //横向单元格标识
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        //默认列宽
        $columnWidth = 20;
        
        $obj->getActiveSheet(0)->setTitle(isset($param['sheetName'])?$param['sheetName']:'sheet1');   //设置sheet名称
        $_row = 1;   //设置纵向单元格标识
        
        if(isset($param['tip'])&&!empty($param['tip'])){
            $obj->getActiveSheet(0)->setCellValue('A'.$_row, $param['tip']);
            $obj->getActiveSheet(0)->getStyle( 'A'.$_row)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
            $obj->getActiveSheet(0)->getStyle('A'.$_row)->getAlignment()->setWrapText(true);
            $len = 1;
            if(isset($param['title'])&&is_array($param['title'])&&!empty($param['title'])){
                $len = count($param['title']);
            }
            $obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.$cellName[$len-1].$_row);
            $obj->getActiveSheet(0)->getRowDimension($_row)->setRowHeight(100);
            ++$_row;
        }
        
        if(isset($param['title'])&&is_array($param['title'])){
            $i = 0;
            foreach($param['title'] AS $v){   //设置列标题
                if(is_array($v)){
                    $obj->getActiveSheet(0)->setCellValue($cellName[$i].$_row, isset($v[0])?$v[0]:'');//默认数组第一个为标题
                    if(isset($v[1])){//默认数组第二个为颜色
                        $obj->getActiveSheet(0)->getStyle($cellName[$i].$_row)->applyFromArray(
                            array(
                                'fill' => array (
                                    'type'       => \PHPExcel_Style_Fill::FILL_SOLID ,
                                    'rotation'   => 90,
                                    'startcolor' => array (
                                        'rgb' => $v[1]
                                    ),
                                    'endcolor'   => array (
                                        'rgb' => $v[1]
                                    )
                                )
                            )
                         );
                    }
                    if(isset($v[2])){//默认数组第二个为宽度
                        $obj->getActiveSheet(0)->getColumnDimension($cellName[$i])->setWidth($v[2]);
                    }else{
                        $obj->getActiveSheet(0)->getColumnDimension($cellName[$i])->setWidth($columnWidth);
                    }
                }else{
                    $obj->getActiveSheet(0)->setCellValue($cellName[$i].$_row, $v);
                    $obj->getActiveSheet(0)->getColumnDimension($cellName[$i])->setWidth($columnWidth);
                }
                
                $obj->getActiveSheet(0)->getStyle($cellName[$i].$_row)->applyFromArray(
                        array(
                            'borders' => array (
                                'outline'     => array (
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),                        
                        )                    
                    );
                $i++;
            }
            $_row++;
        }
        
        //填写数据
        if(isset($param['data'])&&is_array($param['data'])){
            $i = 0;
            foreach($param['data'] AS $_v){
                $j = 0;
                foreach($_v AS $_cell){
                    $style = PHPExcel_Style_NumberFormat::FORMAT_TEXT;
                    if(is_array($_cell)){
                        if(isset($_cell[0])){//默认数组第一个为值
                            if(is_array($_cell[0])){
                                $list = implode(',', $_cell[0]);
                                $objValidation =$obj->getActiveSheet(0)->getCell($cellName[$j] . ($i+$_row))->getDataValidation(); //下拉样式
                                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST )                                
                                                ->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION )                                
                                                ->setAllowBlank(false)                                
                                                ->setShowInputMessage(true)                                
                                                ->setShowErrorMessage(true)
                                                ->setShowDropDown(true)
                                                ->setErrorTitle('输入的值有误')
                                                ->setError('您输入的值不在下拉框列表内.')        
                                                ->setPromptTitle('')                                
                                                ->setPrompt('')                                
                                                ->setFormula1('"' . $list . '"');                                
                            }else{
                                $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell[0]);
                            }
                        }
                        if(isset($_cell[1])){
                            switch($_cell[1]){//默认数组第二个为类型
                                case 'n': $style = PHPExcel_Style_NumberFormat::FORMAT_TEXT; break;
                                case 'n00': $style = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00; break;
                            }
                        }      
                        if(isset($_cell[2])){//默认数组第三个为颜色                            
                            $obj->getActiveSheet(0)->getStyle($cellName[$j] . ($i+$_row))->applyFromArray(
                                    array(                                        
                                        'fill' => array (
                                            'type'       => \PHPExcel_Style_Fill::FILL_SOLID ,
                                            //'rotation'   => 90,
                                            'startcolor' => array (
                                                'rgb' => $_cell[2]
                                            ),
                                            'endcolor'   => array (
                                                'rgb' => $_cell[2]
                                            )
                                        )
                                    )
                               );
                        }
                    }else{
                        $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);
                    }   
                    $obj->getActiveSheet(0)->getStyle($cellName[$j] . ($i+$_row))->getNumberFormat()->setFormatCode($style);
                    $obj->getActiveSheet(0)->getStyle($cellName[$j] . ($i+$_row))->applyFromArray(
                        array(
                            'borders' => array (
                                'outline'     => array (
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),
                        )
                    );
                    $j++;
                }
                $i++;
            }
        }
        
        //文件名处理
        if(!isset($param['fileName'])||empty($param['fileName'])){
            $param['fileName'] = uniqid(time(),true);
        }
        $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
        
        if(!isset($param['savePath'])||empty($param['savePath'])){
            $param['savePath'] = './';
        }
        
        if(isset($param['isDown'])&&$param['isDown']){   //网页下载
            //header('pragma:public');
            header("Content-Disposition:attachment;filename=".$param['fileName'].".xlsx");
            $objWrite->save('php://output');
            exit;
        }
        
        $_fileName = iconv("utf-8", "gb2312", $param['fileName']);   //转码
        header('pragma:public');
        header("Content-Disposition:attachment;filename=$_fileName.xlsx");
        $objWrite->save('php://output');exit;
        
        return $param['savePath'].$param['fileName'].'.xlsx';
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
    
    //文件上传相关方法
    //多文件上传
    protected function fileMultipleUpload(Request $request){
        $files = $request->file('file');
        
        if(!$files||empty($files)){
            return response()->json(['errCode' => 0, 'errMsg' => '缺少必要参数！']);
        }
        
        $result = array();
        foreach($files as $k => $file){
            $result[$k] = $this->fileUpload($file);
        }
        
        if(empty($result)){
            return response()->json(['errCode' => 0, 'errMsg' => '文件上传失败！']);
        }
        
        return response()->json(['ids' => $result]);
        
    }
    //单文件上传
    protected function fileSingleUpload(Request $request){
        $file = $request->file('file');
        if(!$file) {
            return response()->json(['errCode' => 0, 'errMsg' => '缺少必要参数！']);
        }
        $result = $this->fileUpload($file);
        
        if(!$result){
            return response()->json(['errCode' => 0, 'errMsg' => '文件上传失败！']);
        }
        
        return response()->json(['id' => $result]);
    }
    /**
     * 单文件删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fileSingleDelete(Request $request)
    {
        $id = $request->get('id');
        //查询当前的附件
        $file = AttachmentModel::where('id',$id)->first()->toArray();
        if(!$file)
        {
            return response()->json(['errCode' => 0, 'errMsg' => '附件没有上传成功！']);
        }
        //删除附件
        if(is_file($file['url']))
            unlink($file['url']);
            $result = AttachmentModel::destroy($id);
            if (!$result) {
                return response()->json(['errCode' => 0, 'errMsg' => '删除失败！']);
            }
            return response()->json(['errCode' => 1, 'errMsg' => '删除成功！']);
    }
    /**
     * 文件上传控制
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function fileUpload($file)
    {        
        //将文件上传的数据存入到attachment表中
        $attachment = \FileClass::uploadFile($file, 'task');
        $attachment = json_decode($attachment, true);
        //判断文件是否上传
        if($attachment['code']!=200)
        {
            return response()->json(['errCode' => 0, 'errMsg' => $attachment['message']]);
        }
        $attachment_data = array_add($attachment['data'], 'status', 1);
        $attachment_data['created_at'] = date('Y-m-d H:i:s', time());
        //将记录写入到attchement表中
        $result = AttachmentModel::create($attachment_data);
        $result = json_decode($result, true);
        if (!$result) {
            return NULL;
        }
        //回传附件id
        return $result['id'];
    }
}
