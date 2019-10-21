<?php

namespace App\Modules\Manage\Model;

use App\Modules\User\Model\AttachmentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class FeedbackAttachmentModel extends Model
{
    protected $table = 'feedback_attachment';
    public  $timestamps = false;  
    public $fillable = ['feedback_id','attachment_id','type','created_at','id'];

    
    static function createOne($feedback_id,$attachment_id)
    {
        if(is_array($attachment_id)){
            foreach($attachment_id as $v){
                $type = AttachmentModel::where('id',$v)->lists('type');
                $model = new FeedbackAttachmentModel();
                $model->feedback_id = $feedback_id;
                $model->type = $type[0];
                $model->attachment_id = $v;
                $model->created_at = date('Y-m-d H:i:s',time());
                $result = $model->save();
                if(!$result){
                    return false;
                }
            }
        }else{
            $type = AttachmentModel::where('id',$attachment_id)->lists('type');
            $model = new FeedbackAttachmentModel;
            $model->feedback_id = $feedback_id;
            $model->type = $type[0];
            $model->attachment_id = $attachment_id;
            $model->created_at = date('Y-m-d H:i:s', time());
            $result = $model->save();
            if(!$result){
                return false;
            }
        }

        return true;
    }
    
    static function isDownAble($attachment_id,$uid)
    {
        $attachment_data = Self::where('attachment_id',$attachment_id)->first();
        
        $feedback_data = FeedbackModel::findById($attachment_data['feedback_id']);
        if($feedback_data['uid']==$uid)
        {
            return true;
        }
        return false;
    }

    
    static function findById($id)
    {
        $data = FeedbackAttachmentModel::select('attachment_id')
            ->where('feedback_id',$id)
            ->get()->toArray();
        return $data;
    }
}
