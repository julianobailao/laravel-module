<?php

namespace Modules\ModuleControl\Entities;

use Illuminate\Database\Eloquent\Model;

class UserGroupPermission extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'action_id'
    ];
}
