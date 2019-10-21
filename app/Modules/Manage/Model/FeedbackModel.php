<?php

namespace App\Modules\Manage\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Model\AttachmentModel;

class FeedbackModel extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'uid',
        'phone',
        'desc',
        'type',
        'created_time',
        'handle_time',
        'status',
        'replay',
        'title'
    ];

    public $timestamps = false;

    /**
     * 根据id查询反馈
     * @param $id
     */
    static function findById($id)
    {
        $data = self::select('feedback.*')
        ->where('feedback.id', '=', $id)
        ->first();
        
        return $data;
    }
    
    /**
        * 反馈提交
     * @param $data
     * @return boolean
     */
    static public function delivery($data)
    {
        $status = DB::transaction(function() use($data){
            
            $result = FeedbackModel::create($data);   
            
            if($result&&$result['id']){
                
                if(isset($data['file_id'])){
                    if(is_string($data['file_id'])){
                        $data['file_id'] = explode(",", $data['file_id']);
                    }
                    $file_able_ids = AttachmentModel::select('attachment.id','attachment.type')->whereIn('id',$data['file_id'])->get()->toArray();
                    
                    foreach($file_able_ids as $v){
                        $feedback_attachment = [
                            'feedback_id'=>$result['id'],
                            'attachment_id'=>$v['id'],
                            'type'=>$v['type'],
                            'created_at'=>date('Y-m-d H:i:s',time()),
                        ];                        
                        $ret = FeedbackAttachmentModel::create($feedback_attachment);
                    }
                }
            }
            
        });
        
        return is_null($status)?true:false;
    }

}
