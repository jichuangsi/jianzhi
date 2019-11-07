<?php

namespace App\Modules\User\Model;

use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{

    
    protected $table = 'manager';
	protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'username','realname'
    ];

    public $timestamps = false;

}