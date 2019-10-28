<?php

namespace App\Modules\Manage\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HelpModel extends Model
{
    
    protected $table = 'help';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','uid','user_name','title','content','view','sort','created_at','updated_at','status'
    ];

    public $timestamps = false;


}
