<?php

namespace App\Modules\Task\Model;

use Illuminate\Database\Eloquent\Model;
use Cache;

class TaskTypeModel extends Model
{

    
    protected $table = 'task_type';
    public  $timestamps = false;  
    public $fillable = ['id','name','status','desc','created_at','alias','pid', 'path','sort','pic'];
    
    public function parentTask()
    {
        return $this->belongsTo('App\Modules\Task\Model\TaskTypeModel', 'pid', 'id');
    }
    
    public function childrenTask()
    {
        return $this->hasMany('App\Modules\Task\Model\TaskTypeModel', 'pid', 'id');
    }

    static function findAll()
    {
        $data = Self::findAllCache();
        $data = \CommonClass::listToTree($data,'id','pid','children_task');
        return $data;
    }
    
    
    static function parentType()
    {
        $data = Self::with('parentTask')->get()->toArray();
        
        return $data;
    }
    
    static function hotType($num)
    {
        $data = Self::where('pid','!=',0)->where('status','!=',0)->orderBy('choose_num')->limit($num)->get()->toArray();
        return $data;
    }
    
    /*
     * 根据主类型名称获取id
     */
	static function getTaskTypeName($name)
    {
        $disinfo=TaskTypeModel::where('name', '=', $name)->where('pid','=',0)->first();
        if ($disinfo) {
            return $disinfo->id;
        }
        return 0;
    }
    
    static function findTypeIds($pid)
    {
        $type_data = TaskTypeModel::findById($pid);
        
        if($type_data && $type_data['pid']!=0)
        {
            return [$type_data['id']];
        }else{
            return Self::findByPid([$pid],['id']);
        }
    }
    
    
    static function findById($id)
    {
        $taskType = self::findAllCache();
        
        $data = array();
        foreach($taskType as $k=>$v)
        {
            if(is_array($id) && in_array($v['id'],$id))
            {
                $data[] = $v;
            }elseif($v['id']==$id)
            {
                $data = $v;
            }
        }
        return $data;
    }
    
    
    static function findByPid_bak($pid,$filds=array())
    {
        $taskType = self::findAllCache();
        
        $data = array();
        foreach($taskType as $k=>$v)
        {
            if(is_array($pid) && in_array($v['pid'],$pid))
            {
                if(is_null($filds))
                {
                    $data[] = $v;
                }elseif(is_string($filds))
                {
                    $data[] = $v[$filds];
                }elseif(is_array($filds))
                {
                    foreach($filds as $key=>$value)
                    {
                        if(isset($v[$value]))
                        {
                            $seed[$value] = $v[$value];
                        }
                    }
                    $data[] = $seed;
                }
            }elseif($v['pid']==$pid)
            {
                if(is_null($filds))
                {
                    $data[] = $v;
                }elseif(is_string($filds))
                {
                    $seed[$filds] = $v[$filds];
                    $data[] = $seed;
                }elseif(is_array($filds))
                {
                    foreach($filds as $key=>$value)
                    {
                        if(isset($v[$value]))
                        {
                            $seed[$value] = $v[$value];
                        }
                    }
                    $data[] = $seed;
                }
            }
        }
        return $data;
    }
    
    
    static function findByPid($pid,$filds=array())
    {
        $taskType = self::findAllCache();
        $data = array();
        foreach($taskType as $k=>$v)
        {
            if(in_array($v['pid'],$pid))
            {
                if(!empty($filds))
                {
                    foreach($filds as $key=>$value)
                    {
                        $seed[$value] = $v[$value];
                    }
                    $data[] = $seed;
                }else
                {
                    $data[] = $v;
                }
            }
        }
        
        return $data;
    }
    
    static function findAllCache()
    {   
        if(Cache::has('task_type')){
            $taskType = Cache::get('task_type');
        }else{
            $taskType = TaskTypeModel::select('*')->where('status','!=',0)->orderBy('pid', 'ASC')->orderBy('sort', 'ASC')->get()->toArray();
            Cache::put('task_type',$taskType,60*24);
        }
        return $taskType;
    }
}
