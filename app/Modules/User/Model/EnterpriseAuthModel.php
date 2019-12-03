<?php

namespace App\Modules\User\Model;

use App\Modules\Employ\Models\UnionAttachmentModel;
use App\Modules\Task\Model\TaskCateModel;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class EnterpriseAuthModel extends Model
{
    protected $table = 'enterprise_auth';
    
    protected $fillable = [
       'id', 'uid', 'company_name', 'cate_id','employee_num','business_license','begin_at',
        'website','province','city','area','address','status','auth_time','created_at','updated_at','contactor',
        'contactor_mobile','phone','tax_code','bank','account'
    ];


    /*
     * 获取企业列表
     */
	static function getqiye()
    {
//  	 $taskType = TaskTypeModel::select('*')->where('status','!=',0)->orderBy('pid', 'ASC')->orderBy('sort', 'ASC')->get()->toArray();
       $userInfo = EnterpriseAuthModel::select('*')->where('status','=',1)->get()->toArray();
        return $userInfo;
    }
    
    static function isEnterpriseAuth($uid){
        $companyInfo = EnterpriseAuthModel::where('uid', $uid)->where('status',1)->first();
        $companyAuth = AuthRecordModel::where('uid',$uid)->where('status',1)->where('auth_code','enterprise')->first();
        if($companyInfo && $companyAuth){
            return true;
        }else{
            return false;
        }
    }

    
    static function getEnterpriseAuthStatus2($uid)
    {
        $companyInfo = EnterpriseAuthModel::where('uid', $uid)->first();
        if ($companyInfo) {
            return $companyInfo->status;
        }
        return -1;
    }

    static function getEnterpriseAuthStatus($uid)
    {
        $companyInfo = EnterpriseAuthModel::where('uid', $uid)->first();
        if ($companyInfo) {
            return $companyInfo->status;
        }
        return null;
    }
    /*
     * 根据企业名称获取uid
     */
    static function getEnterpriseName($name)
    {
        $companyInfo = EnterpriseAuthModel::where('company_name', $name)->first();
        if ($companyInfo) {
            return $companyInfo->uid;
        }
        return 0;
    }
    /*
     * 根据uid判断企业是否认证
     */
    static function getcheckName($uid)
    {
        $companyInfo = EnterpriseAuthModel::where('uid', $uid)->where('status','=',1)->first();
        if ($companyInfo) {
            return $companyInfo->uid;
        }
        return 0;
    }
    
    static function getEnterpriseInfo($id)
    {
        
        $companyInfo = EnterpriseAuthModel::where('id', $id)->first();
        
        if($companyInfo->cate_id){
            $cateInfo = TaskCateModel::findById($companyInfo->cate_id);
            $companyInfo['cate_name'] = $cateInfo['name'];
            
            $parentCate= TaskCateModel::findById($cateInfo['pid']);
            $companyInfo['cate_parent_name'] = $parentCate['name'];
        }else{
            $companyInfo['cate_name'] = '';
            $companyInfo['cate_parent_name'] = '';
        }
        
        if($companyInfo->province){
            $province = DistrictModel::where('id',$companyInfo->province)->first();
            $companyInfo['province_name'] = $province->name;
        }else{
            $companyInfo['province_name'] = '';
        }
        if($companyInfo->city){
            $city = DistrictModel::where('id',$companyInfo->city)->first();
            $companyInfo['city_name'] = $city->name;
        }else{
            $companyInfo['city_name'] = '';
        }
        if($companyInfo->area){
            $area = DistrictModel::where('id',$companyInfo->area)->first();
            $companyInfo['area_name'] = $area->name;
        }else{
            $companyInfo['area_name'] = '';
        }
        
        $attachment = UnionAttachmentModel::where('object_id',$companyInfo->uid)
            ->where('object_type',1)->get()->toArray();
        if(!empty($attachment)){
            $attachmentId = array();
            foreach($attachment as $k => $v){
                $attachmentId[] = $v['attachment_id'];
            }
            
            $attachmentInfo = AttachmentModel::whereIn('id',$attachmentId)->get()->toarray();
            if(!empty($attachmentInfo)){
                $companyInfo['attachement'] = $attachmentInfo;
            }
        }else{
            $companyInfo['attachement'] = array();
        }
        return $companyInfo;

    }

    static function getEnterpriseInfoByUid($uid)
    {
        
        $companyInfo = EnterpriseAuthModel::where('uid', $uid)->orderBy('created_at','desc')->first();        
        
        return $companyInfo;
        
    }

    
    static function createEnterpriseAuth($companyInfo, $authRecordInfo,$fileId='')
    {
        $status = DB::transaction(function () use ($companyInfo, $authRecordInfo,$fileId) {
            $authRecordInfo['auth_id'] = DB::table('enterprise_auth')->insertGetId($companyInfo);
            DB::table('auth_record')->insert($authRecordInfo);
            if (!empty($fileId)) {
               
               $fileAbleIds = AttachmentModel::fileAble($fileId);
               $fileAbleIds = array_flatten($fileAbleIds);
               foreach ($fileAbleIds as $v) {
                   $attachmentData = array(
                       'object_id'     => $companyInfo['uid'],
                       'object_type'   => 1,
                       'attachment_id' => $v,
                       'created_at'    => date('Y-m-d H:i:s', time())
                   );
                   UnionAttachmentModel::create($attachmentData);
               }
               
               $attachmentModel = new AttachmentModel();
               $attachmentModel->statusChange($fileAbleIds);
           }
        });
        return is_null($status) ? true : $status;
    }

    
    static function removeEnterpriseAuth()
    {
        $status = DB::transaction(function () {
            $user = Auth::User();
            EnterpriseAuthModel::where('uid', $user->id)->delete();
            AuthRecordModel::where('auth_code', 'enterprise')->where('uid', $user->id)->delete();
        });
        return is_null($status) ? true : $status;
    }

    static function removeEnterpriseAuth2($uid)
    {
        $status = DB::transaction(function () use($uid){
            EnterpriseAuthModel::where('uid', $uid)->delete();
        });
            return is_null($status) ? true : $status;
    }
    
    static function EnterpriseAuthPass($id)
    {
        $status = DB::transaction(function () use ($id) {
            EnterpriseAuthModel::where('id', $id)->update(array('status' => 1, 'auth_time' => date('Y-m-d H:i:s')));
            AuthRecordModel::where('auth_id', $id)
                ->where('auth_code', 'enterprise')
                ->update(array('status' => 1, 'auth_time' => date('Y-m-d H:i:s')));
        });

        return is_null($status) ? true : $status;
    }

    
    static function EnterpriseAuthDeny($id)
    {
        $status = DB::transaction(function () use ($id) {
            EnterpriseAuthModel::where('id', $id)->update(array('status' => 2));
            AuthRecordModel::where('auth_id', $id)
                ->where('auth_code', 'enterprise')
                ->update(array('status' => 2));
        });

        return is_null($status) ? true : $status;
    }

    static function EnterpriseAuthPassByUid($uid,$mid=0)
    {
        $status = DB::transaction(function () use ($uid,$mid) {
            EnterpriseAuthModel::where('uid', $uid)->update(array('status' => 1, 'mid' => $mid,'examine_time' => date('Y-m-d H:i:s'),'auth_time' => date('Y-m-d H:i:s')));
            AuthRecordModel::where('uid', $uid)
            ->where('auth_code', 'enterprise')->orderby('id', 'desc')->first()
            ->update(array('status' => 1, 'auth_time' => date('Y-m-d H:i:s')));
        });
            
            return is_null($status) ? true : $status;
    }
    
    
    static function EnterpriseAuthDenyByUid($uid,$mid=0)
    {
        $status = DB::transaction(function () use ($uid,$mid) {
            EnterpriseAuthModel::where('uid', $uid)->update(array('status' => 2, 'mid' => $mid,'examine_time' => date('Y-m-d H:i:s')));
            AuthRecordModel::where('uid', $uid)
            ->where('auth_code', 'enterprise')->orderby('id', 'desc')->first()
            ->update(array('status' => 2));
        });
            
            return is_null($status) ? true : $status;
    }    
    
    static function AllEnterpriseAuthPass($idArr)
    {
        
        $res = EnterpriseAuthModel::whereIn('id',$idArr)->get()->toArray();
        if(!empty($res) && is_array($res)){
            $id = array();
            foreach($res as $k => $v){
                if($v['status'] == 0){
                    $id[] = $v['id'];
                }
            }
        }else{
            $id = array();
        }
        $status = DB::transaction(function () use ($id) {
            EnterpriseAuthModel::whereIn('id', $id)->update(array('status' => 1, 'auth_time' => date('Y-m-d H:i:s')));
            AuthRecordModel::whereIn('auth_id', $id)
                ->where('auth_code', 'enterprise')
                ->update(array('status' => 1, 'auth_time' => date('Y-m-d H:i:s')));
        });

        return is_null($status) ? true : $status;
    }


    
    static function AllEnterpriseAuthDeny($idArr)
    {
        
        $res = EnterpriseAuthModel::whereIn('id',$idArr)->get()->toArray();
        if(!empty($res) && is_array($res)){
            $id = array();
            foreach($res as $k => $v){
                if($v['status'] == 0){
                    $id[] = $v['id'];
                }
            }
        }else{
            $id = array();
        }
        $status = DB::transaction(function () use ($id) {
            EnterpriseAuthModel::whereIn('id', $id)->update(array('status' => 2));
            AuthRecordModel::whereIn('auth_id', $id)
                ->where('auth_code', 'enterprise')
                ->update(array('status' => 2));
        });

        return is_null($status) ? true : $status;
    }
    
    static public function updateEnterpriseInfo($companyInfo, $uid){
        $status = DB::transaction(function () use ($companyInfo, $uid) {
            self::where('uid', $uid)->update($companyInfo);            
        });
            
        return is_null($status) ? true : $status;
    }

}
