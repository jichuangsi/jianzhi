<?php

namespace App\Modules\User\Model;

use Illuminate\Database\Eloquent\Model;

class ChannelDistributionModel extends Model
{

    
    protected $table = 'channel_distribution';
	protected $primaryKey = 'id';


    protected $fillable = [
        'id', 'eid','mid','createtime'
    ];

    public $timestamps = false;

}