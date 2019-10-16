<?php

namespace App\Modules\Task\Model;
use Illuminate\Database\Eloquent\Model;

class TaskTagsModel extends Model
{
    protected $table = 'tag_task';

    public $timestamps = false;

    protected $fillable = [
        'tag_id', 'task_id'
    ];

    
    static function taskTag($tid)
    {
        $data = TaskTagsModel::select('a.id as tag_id')
            ->leftjoin('skill_tags as a','tag_task.tag_id', '=', 'a.id')
            ->where('tag_task.task_id', '=', $tid)
            ->get()
            ->toArray();
        return $data;
    }

    
    static function updateTag($tag_id,$tid)
    {
        $result = TaskTagsModel::firstOrCreate(['tag_id'=>$tag_id,'task_id'=>$tid])->save();
        return $result;
    }

    
    static function delTag($tag_id, $tid)
    {
        $query = TaskTagsModel::where('task_id','=',$tid);
        $query = $query->where(function($query) use($tag_id){
            $query->where('tag_id','=',$tag_id);
        });
        $result = $query->delete();
        return $result;
    }

    
    static function insert($tags,$tid)
    {
        if(is_array($tags)){
            foreach($tags as $v)
            {
                $result = Self::updateTag($v,$tid);
                if(!$result){
                    return false;
                }
            }
        }else{
            $result = Self::updateTag($tags,$tid);
        }

        return $result;
    }

    
    static function tagDelete($tags,$tid)
    {
        $result = Self::whereIn('tag_id',$tags)->delete();
        return $result;
    }

    
    static function getTagsByTaskId($tid)
    {
        if(is_array($tid)){
            $tag = TaskTagsModel::select('tag_id', 'task_id')->whereIn('task_id', $tid)->get()->toArray();
        }else{
            
            $tag = TaskTagsModel::where('tag_task.task_id',$tid)
                ->leftJoin('skill_tags','skill_tags.id','=','tag_task.tag_id')
                ->select('skill_tags.tag_name','tag_task.tag_id')->get()->toArray();
        }
        return $tag;
    }
    
    static function getTasksByTagId($tid)
    {
        if(is_array($tid)){
            $tag = TaskTagsModel::select('task_id')->whereIn('tag_id', $tid)->groupby('task_id')->get()->toArray();
        }else{            
            $tag = TaskTagsModel::where('tag_task.tag_id',$tid)->select('tag_task.task_id')->get()->toArray();
        }
        return $tag;
    }

}